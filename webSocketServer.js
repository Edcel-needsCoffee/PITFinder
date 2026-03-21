const WebSocket = require('ws');
const mysql = require('mysql2/promise');

const wss = new WebSocket.Server({ port: 8080 });

const pool = mysql.createPool({
  host: 'localhost',
  user: 'root',
  password: '',
  database: 'pit_finder',
  waitForConnections: true,
  connectionLimit: 10,
  queueLimit: 0
});

async function fetchAllBuildings() {
  const [buildings] = await pool.query('SELECT * FROM buildings');

  const enriched = await Promise.all(
    buildings.map(async (building) => {
      const [points] = await pool.query(
        'SELECT lat, lng FROM building_points WHERE building_id = ? ORDER BY point_order',
        [building.id]
      );
      const [rooms] = await pool.query(
        'SELECT * FROM rooms WHERE building_id = ?',
        [building.id]
      );
      return {
        ...building,
        polygon: points.map(p => [p.lat, p.lng]),
        room: rooms
      };
    })
  );

  return enriched;
}

function broadcast(data) {
  wss.clients.forEach(client => {
    if (client.readyState === WebSocket.OPEN) {
      client.send(JSON.stringify(data));
    }
  });
}

// --- Diff checker: compares old vs new building data ---
function diffBuildings(oldList, newList) {
  const oldMap = {};
  const newMap = {};

  oldList.forEach(b => oldMap[b.id] = b);
  newList.forEach(b => newMap[b.id] = b);

  const added    = [];
  const deleted  = [];
  const updated  = [];

  // Check for added and updated
  newList.forEach(b => {
    if (!oldMap[b.id]) {
      added.push(b);
    } else {
      const oldSnap = JSON.stringify(oldMap[b.id]);
      const newSnap = JSON.stringify(b);
      if (oldSnap !== newSnap) {
        // Figure out what specifically changed
        const changes = [];

        if (oldMap[b.id].name !== b.name)
          changes.push(`name: "${oldMap[b.id].name}" → "${b.name}"`);

        if (oldMap[b.id].description !== b.description)
          changes.push(`description changed`);

        if (JSON.stringify(oldMap[b.id].polygon) !== JSON.stringify(b.polygon))
          changes.push(`polygon updated`);

        // Room-level diff
        const oldRooms = oldMap[b.id].room || [];
        const newRooms = b.room || [];
        const oldRoomMap = {};
        const newRoomMap = {};
        oldRooms.forEach(r => oldRoomMap[r.id] = r);
        newRooms.forEach(r => newRoomMap[r.id] = r);

        newRooms.forEach(r => {
          if (!oldRoomMap[r.id]) {
            changes.push(`room added: "${r.name}" (Floor ${r.floor || '?'})`);
          } else if (JSON.stringify(oldRoomMap[r.id]) !== JSON.stringify(r)) {
            changes.push(`room updated: "${r.name}" (Floor ${r.floor || '?'})`);
          }
        });

        oldRooms.forEach(r => {
          if (!newRoomMap[r.id]) {
            changes.push(`room deleted: "${r.name}" (Floor ${r.floor || '?'})`);
          }
        });

        updated.push({ building: b, changes });
      }
    }
  });

  // Check for deleted buildings
  oldList.forEach(b => {
    if (!newMap[b.id]) {
      deleted.push(b);
    }
  });

  return { added, deleted, updated };
}

// --- Console log with colors and formatting ---
function logChanges(diff) {
  const time = new Date().toLocaleTimeString();

  diff.added.forEach(b => {
    console.log(`\x1b[32m[${time}] ✅ BUILDING ADDED   → "${b.name}" (ID: ${b.id})\x1b[0m`);
  });

  diff.deleted.forEach(b => {
    console.log(`\x1b[31m[${time}] 🗑️  BUILDING DELETED → "${b.name}" (ID: ${b.id})\x1b[0m`);
  });

  diff.updated.forEach(({ building, changes }) => {
    console.log(`\x1b[33m[${time}] ✏️  BUILDING UPDATED → "${building.name}" (ID: ${building.id})\x1b[0m`);
    changes.forEach(c => {
      console.log(`\x1b[33m         └─ ${c}\x1b[0m`);
    });
  });
}

// --- Poll every 3 seconds ---
let lastBuildings = [];

setInterval(async () => {
  try {
    const buildings = await fetchAllBuildings();
    const snapshot  = JSON.stringify(buildings);
    const lastSnap  = JSON.stringify(lastBuildings);

    if (snapshot !== lastSnap) {
      const diff = diffBuildings(lastBuildings, buildings);
      logChanges(diff);  // Log what changed to console

      // Tell the client what type of change happened too
      broadcast({
        type: 'initialData',
        data: buildings,
        changes: {
          added:   diff.added.map(b => ({ id: b.id, name: b.name })),
          deleted: diff.deleted.map(b => ({ id: b.id, name: b.name })),
          updated: diff.updated.map(({ building, changes }) => ({
            id: building.id,
            name: building.name,
            changes
          }))
        }
      });

      lastBuildings = buildings;
    }

  } catch (err) {
    console.error('\x1b[31m[POLL ERROR]\x1b[0m', err.message);
  }
}, 3000);

wss.on('connection', (ws) => {
  const time = new Date().toLocaleTimeString();
  console.log(`\x1b[36m[${time}] 🔌 Client connected (total: ${wss.clients.size})\x1b[0m`);

  ws.on('message', async (message) => {
    let data;
    try {
      data = JSON.parse(message);
    } catch (e) {
      ws.send(JSON.stringify({ type: 'error', message: 'Invalid message format' }));
      return;
    }

    if (data.action === 'getBuildings') {
      try {
        const buildings = await fetchAllBuildings();
        lastBuildings   = buildings;
        ws.send(JSON.stringify({ type: 'initialData', data: buildings }));
        console.log(`\x1b[36m[${new Date().toLocaleTimeString()}] 📦 Sent initial data to client\x1b[0m`);
      } catch (err) {
        console.error('Error fetching buildings:', err);
        ws.send(JSON.stringify({ type: 'error', message: 'Failed to load buildings' }));
      }
    }
  });

  ws.on('close', () => {
    console.log(`\x1b[90m[${new Date().toLocaleTimeString()}] 🔌 Client disconnected (total: ${wss.clients.size})\x1b[0m`);
  });

  ws.on('error', (err) => {
    console.error('\x1b[31m[WS ERROR]\x1b[0m', err.message);
  });
});

console.log('\x1b[32m✅ WebSocket server running on ws://localhost:8080\x1b[0m');