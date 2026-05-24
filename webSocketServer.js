const WebSocket = require('ws');
const mysql     = require('mysql2/promise');
const crypto    = require('crypto');

const wss  = new WebSocket.Server({ port: 8080 });

const pool = mysql.createPool({
  host:     process.env.DB_HOST     || 'localhost',
  user:     process.env.DB_USER     || 'root',
  password: process.env.DB_PASSWORD || '',
  database: process.env.DB_NAME     || 'pit_finder',
  waitForConnections: true,
  connectionLimit: 10,
  queueLimit: 0
});

// ── Session store: token → { adminId, fullName } ──────────────────
const sessions = {};

function generateToken() {
  return crypto.randomBytes(32).toString('hex');
}

// ── fetch all buildings ──────────────────────────────────────────
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

// ── broadcast to all clients (for buildings) ─────────────────────
function broadcastBuildings(buildings, diff = null) {
  const message = {
    type: 'initialData',
    data: buildings
  };
  if (diff) {
    message.changes = {
      added: diff.added.map(b => ({ id: b.id, name: b.name })),
      deleted: diff.deleted.map(b => ({ id: b.id, name: b.name })),
      updated: diff.updated.map(({ building, changes }) => ({
        id: building.id,
        name: building.name,
        changes
      }))
    };
  }
  
  wss.clients.forEach(client => {
    if (client.readyState === WebSocket.OPEN) {
      client.send(JSON.stringify(message));
    }
  });
}

// ── broadcast announcements to all clients ────────────────────────
async function broadcastAnnouncements() {
  const announcements = await fetchAllAnnouncements();
  const message = {
    type: 'announcementsData',
    data: announcements
  };
  
  wss.clients.forEach(client => {
    if (client.readyState === WebSocket.OPEN) {
      client.send(JSON.stringify(message));
    }
  });
}

// ── broadcast to admins only ─────────────────────────────────────
function broadcastToAdmins(data) {
  wss.clients.forEach(client => {
    if (client.readyState === WebSocket.OPEN && client.adminId) {
      client.send(JSON.stringify(data));
    }
  });
}

// ── diff checker ─────────────────────────────────────────────────
function diffBuildings(oldList, newList) {
  const oldMap = {};
  const newMap = {};

  oldList.forEach(b => oldMap[b.id] = b);
  newList.forEach(b => newMap[b.id] = b);

  const added   = [];
  const deleted = [];
  const updated = [];

  newList.forEach(b => {
    if (!oldMap[b.id]) {
      added.push(b);
    } else {
      const oldSnap = JSON.stringify(oldMap[b.id]);
      const newSnap = JSON.stringify(b);
      if (oldSnap !== newSnap) {
        const changes = [];

        if (oldMap[b.id].name !== b.name)
          changes.push(`name: "${oldMap[b.id].name}" → "${b.name}"`);

        if (oldMap[b.id].description !== b.description)
          changes.push(`description changed`);

        if (JSON.stringify(oldMap[b.id].polygon) !== JSON.stringify(b.polygon))
          changes.push(`polygon updated`);

        const oldRooms   = oldMap[b.id].room || [];
        const newRooms   = b.room || [];
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

  oldList.forEach(b => {
    if (!newMap[b.id]) deleted.push(b);
  });

  return { added, deleted, updated };
}

// ── console log with colors ──────────────────────────────────────
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

// ── write to activity_logs table ─────────────────────────────────
async function logActivity(adminId, action, targetTable, targetId, details) {
  try {
    await pool.query(
      'INSERT INTO activity_logs (admin_id, action, target_table, target_id, details) VALUES (?,?,?,?,?)',
      [adminId, action, targetTable, targetId, details]
    );
    const t = new Date().toLocaleTimeString();
    const color = action === 'ADD' ? '\x1b[32m'
                : action === 'DELETE' ? '\x1b[31m'
                : action === 'UPDATE' ? '\x1b[33m' : '\x1b[36m';
    console.log(`${color}[${t}] [AUDIT] ${action} ${targetTable}(${targetId}): ${details}\x1b[0m`);
  } catch (err) {
    console.error('logActivity error:', err.message);
  }
}

// ── fetch dashboard data ─────────────────────────────────────────
async function fetchDashboardStats() {
  const [[{ totalBuildings }]]     = await pool.query('SELECT COUNT(*) AS totalBuildings FROM buildings');
  const [[{ totalRooms }]]         = await pool.query('SELECT COUNT(*) AS totalRooms FROM rooms');
  const [[{ totalAnnouncements }]] = await pool.query('SELECT COUNT(*) AS totalAnnouncements FROM announcements');
  const [[{ buildingsNoRooms }]]   = await pool.query(`
    SELECT COUNT(*) AS buildingsNoRooms FROM buildings b
    WHERE NOT EXISTS (SELECT 1 FROM rooms r WHERE r.building_id = b.id)`);
  const [[{ actionsToday }]] = await pool.query(`
    SELECT COUNT(*) AS actionsToday FROM activity_logs
    WHERE DATE(performed_at) = CURDATE()`);
  const [lastRows] = await pool.query(`
    SELECT al.details, al.performed_at, au.full_name AS admin_name
    FROM activity_logs al
    JOIN admin_users au ON al.admin_id = au.id
    ORDER BY al.performed_at DESC LIMIT 1`);
  const last = lastRows[0];
  return {
    totalBuildings, totalRooms, totalAnnouncements,
    buildingsNoRooms, actionsToday,
    lastAction:   last?.details    || null,
    lastActionBy: last?.admin_name || null
  };
}

async function fetchActivityLogs(limit = 50) {
  const [rows] = await pool.query(`
    SELECT al.*, au.full_name AS admin_name
    FROM activity_logs al
    JOIN admin_users au ON al.admin_id = au.id
    ORDER BY al.performed_at DESC LIMIT ?`, [limit]);
  return rows;
}

async function fetchBuildingsOverview() {
  const [rows] = await pool.query(`
    SELECT b.id, b.name, b.date_created, COUNT(r.id) AS room_count
    FROM buildings b
    LEFT JOIN rooms r ON r.building_id = b.id
    GROUP BY b.id ORDER BY b.date_created DESC`);
  return rows;
}

// ── fetch announcements ──────────────────────────────────────────
async function fetchAllAnnouncements() {
  const [rows] = await pool.query(
    'SELECT * FROM announcements ORDER BY created_at DESC'
  );
  return rows;
}

// ── create announcement ──────────────────────────────────────────
async function createAnnouncement(adminId, title, message) {
  const [result] = await pool.query(
    'INSERT INTO announcements (admin_id, title, message, created_at) VALUES (?, ?, ?, NOW())',
    [adminId, title, message]
  );
  await logActivity(adminId, 'ADD', 'announcements', result.insertId,
    `Added announcement: "${title}"`);
  return result.insertId;
}

// ── delete announcement ──────────────────────────────────────────
async function deleteAnnouncement(adminId, announcementId) {
  const [[ann]] = await pool.query('SELECT title FROM announcements WHERE id = ?', [announcementId]);
  if (ann) {
    await pool.query('DELETE FROM announcements WHERE id = ?', [announcementId]);
    await logActivity(adminId, 'DELETE', 'announcements', announcementId,
      `Deleted announcement: "${ann.title}"`);
    return true;
  }
  return false;
}

// ── update announcement ──────────────────────────────────────────
async function updateAnnouncement(adminId, announcementId, title, message) {
  const [[old]] = await pool.query('SELECT title FROM announcements WHERE id = ?', [announcementId]);
  if (old) {
    await pool.query(
      'UPDATE announcements SET title = ?, message = ? WHERE id = ?',
      [title, message, announcementId]
    );
    await logActivity(adminId, 'UPDATE', 'announcements', announcementId,
      `Updated announcement: "${old.title}" → "${title}"`);
    return true;
  }
  return false;
}

// ── push fresh dashboard to all admin clients ────────────────────
async function pushAdminUpdate() {
  try {
    const [stats, logs, buildings] = await Promise.all([
      fetchDashboardStats(),
      fetchActivityLogs(50),
      fetchBuildingsOverview()
    ]);
    const [latestRows] = await pool.query(`
      SELECT al.*, au.full_name AS admin_name
      FROM activity_logs al
      JOIN admin_users au ON al.admin_id = au.id
      ORDER BY al.performed_at DESC LIMIT 1`);
    broadcastToAdmins({
      type: 'activityUpdate',
      stats,
      logs,
      buildings,
      log: latestRows[0] || null
    });
  } catch (err) {
    console.error('pushAdminUpdate error:', err.message);
  }
}

// ── Single unified poll ──────────────────────────────────────────
let lastBuildings = [];
let lastLogCount  = 0;

setInterval(async () => {
  try {
    const [buildings, [logRows]] = await Promise.all([
      fetchAllBuildings(),
      pool.query('SELECT COUNT(*) AS cnt FROM activity_logs')
    ]);
    const cnt = logRows[0].cnt;

    const snapshot      = JSON.stringify(buildings);
    const lastSnap      = JSON.stringify(lastBuildings);
    const buildingsChanged = snapshot !== lastSnap;
    const logsChanged      = Number(cnt) !== lastLogCount;

    if (buildingsChanged) {
      const diff = diffBuildings(lastBuildings, buildings);
      logChanges(diff);
      broadcastBuildings(buildings, diff);
      lastBuildings = buildings;
    }

    if (logsChanged) {
      lastLogCount = Number(cnt);
      await pushAdminUpdate();
    }

  } catch (err) {
    console.error('\x1b[31m[POLL ERROR]\x1b[0m', err.message);
  }
}, 3000);

// ── WebSocket connection handler ──────────────────────────────────
wss.on('connection', (ws) => {
  const time = () => new Date().toLocaleTimeString();
  console.log(`\x1b[36m[${time()}] 🔌 Client connected (total: ${wss.clients.size})\x1b[0m`);

  ws.on('message', async (message) => {
    let data;
    try {
      data = JSON.parse(message);
    } catch (e) {
      ws.send(JSON.stringify({ type: 'error', message: 'Invalid message format' }));
      return;
    }

    // ── get buildings for the map ──
    if (data.action === 'getBuildings') {
      try {
        const buildings = await fetchAllBuildings();
        lastBuildings   = buildings;
        ws.send(JSON.stringify({ type: 'initialData', data: buildings }));
        console.log(`\x1b[36m[${time()}] 📦 Sent initial data to client\x1b[0m`);
      } catch (err) {
        console.error('Error fetching buildings:', err);
        ws.send(JSON.stringify({ type: 'error', message: 'Failed to load buildings' }));
      }
      return;
    }

    // ── get announcements ──
    if (data.action === 'getAnnouncements') {
      try {
        const announcements = await fetchAllAnnouncements();
        ws.send(JSON.stringify({ type: 'announcementsData', data: announcements }));
        console.log(`\x1b[36m[${time()}] 📢 Sent announcements to client (${announcements.length})\x1b[0m`);
      } catch (err) {
        console.error('Error fetching announcements:', err);
        ws.send(JSON.stringify({ type: 'announcementsData', data: [] }));
      }
      return;
    }

    // ── admin login ──
    if (data.action === 'adminLogin') {
      const { username, password } = data;
      try {
        // Password is hashed using SHA-256 before DB comparison (secure login)
        const hash = crypto.createHash('sha256').update(password).digest('hex');
        const [rows] = await pool.query(
          'SELECT * FROM admin_users WHERE username = ? AND password_hash = ?',
          [username, hash]
        );
        if (rows.length === 0) {
          ws.send(JSON.stringify({ type: 'loginFailed' }));
          console.log(`\x1b[31m[${time()}] ❌ Failed login: "${username}"\x1b[0m`);
          return;
        }
        const admin = rows[0];
        const token = generateToken();
        sessions[token] = { adminId: admin.id, fullName: admin.full_name };
        ws.adminId   = admin.id;
        ws.adminName = admin.full_name;

        await pool.query('UPDATE admin_users SET last_login = NOW() WHERE id = ?', [admin.id]);
        await logActivity(admin.id, 'LOGIN', 'admin_users', admin.id,
          `${admin.full_name} logged in`);

        ws.send(JSON.stringify({ type: 'loginSuccess', token, fullName: admin.full_name }));
        console.log(`\x1b[32m[${time()}] ✅ Admin logged in: "${admin.full_name}"\x1b[0m`);
      } catch (err) {
        console.error('Login error:', err);
        ws.send(JSON.stringify({ type: 'error', message: 'Login failed' }));
      }
      return;
    }

    // ── all actions below require a valid session token ──
    const session = sessions[data.token];
    if (data.token !== undefined && !session) {
      ws.send(JSON.stringify({ type: 'authError', message: 'Unauthorized' }));
      return;
    }
    if (session) {
      ws.adminId   = session.adminId;
      ws.adminName = session.fullName;
    }
    const adminId  = ws.adminId;
    const fullName = ws.adminName;

    // ── get dashboard ──
    if (data.action === 'getDashboard') {
      try {
        const [stats, logs, buildings] = await Promise.all([
          fetchDashboardStats(),
          fetchActivityLogs(50),
          fetchBuildingsOverview()
        ]);
        ws.send(JSON.stringify({ type: 'dashboardData', stats, logs, buildings }));
      } catch (err) {
        console.error('Dashboard error:', err);
      }
      return;
    }

    // ── add building ──
    if (data.action === 'saveBuilding') {
      const { name, description, points } = data;
      try {
        const [result] = await pool.query(
          'INSERT INTO buildings (name, description) VALUES (?, ?)',
          [name, description || '']
        );
        const buildingId = result.insertId;
        await Promise.all((points || []).map((p, i) =>
          pool.query(
            'INSERT INTO building_points (building_id, lat, lng, point_order) VALUES (?,?,?,?)',
            [buildingId, p.lat, p.lng, i]
          )
        ));
        await logActivity(adminId, 'ADD', 'buildings', buildingId,
          `${fullName} added building "${name}"`);
        
        // Broadcast updated buildings to ALL clients
        const updatedBuildings = await fetchAllBuildings();
        lastBuildings = updatedBuildings;
        broadcastBuildings(updatedBuildings);
        await pushAdminUpdate();
        
        ws.send(JSON.stringify({ type: 'saveBuildingSuccess', buildingId, name }));
      } catch (err) {
        console.error('Save building error:', err);
        ws.send(JSON.stringify({ type: 'error', message: 'Failed to save building' }));
      }
      return;
    }

    // ── delete building ──
    if (data.action === 'deleteBuilding') {
      const { buildingId } = data;
      try {
        const [[building]] = await pool.query(
          'SELECT name FROM buildings WHERE id = ?', [buildingId]);
        if (!building) {
          ws.send(JSON.stringify({ type: 'error', message: 'Building not found' })); return;
        }
        await pool.query('DELETE FROM buildings WHERE id = ?', [buildingId]);
        await logActivity(adminId, 'DELETE', 'buildings', buildingId,
          `${fullName} deleted building "${building.name}"`);
        
        // Broadcast updated buildings to ALL clients
        const updatedBuildings = await fetchAllBuildings();
        lastBuildings = updatedBuildings;
        broadcastBuildings(updatedBuildings);
        await pushAdminUpdate();
        
        ws.send(JSON.stringify({ type: 'deleteBuildingSuccess', buildingId }));
      } catch (err) {
        console.error('Delete building error:', err);
        ws.send(JSON.stringify({ type: 'error', message: 'Failed to delete building' }));
      }
      return;
    }

    // ── update building ──
    if (data.action === 'updateBuilding') {
      const { buildingId, name, description } = data;
      try {
        const [[old]] = await pool.query(
          'SELECT name FROM buildings WHERE id = ?', [buildingId]);
        if (!old) {
          ws.send(JSON.stringify({ type: 'error', message: 'Building not found' })); return;
        }
        await pool.query(
          'UPDATE buildings SET name = ?, description = ? WHERE id = ?',
          [name, description, buildingId]
        );
        const details = old.name !== name
          ? `${fullName} renamed building "${old.name}" → "${name}"`
          : `${fullName} updated building "${name}"`;
        await logActivity(adminId, 'UPDATE', 'buildings', buildingId, details);
        
        // Broadcast updated buildings to ALL clients
        const updatedBuildings = await fetchAllBuildings();
        lastBuildings = updatedBuildings;
        broadcastBuildings(updatedBuildings);
        await pushAdminUpdate();
        
        ws.send(JSON.stringify({ type: 'updateBuildingSuccess', buildingId }));
      } catch (err) {
        console.error('Update building error:', err);
        ws.send(JSON.stringify({ type: 'error', message: 'Failed to update building' }));
      }
      return;
    }

    // ── add room ──
    if (data.action === 'addRoom') {
      const { buildingId, name, floor, details, type } = data;
      try {
        const [[building]] = await pool.query(
          'SELECT name FROM buildings WHERE id = ?', [buildingId]);
        if (!building) {
          ws.send(JSON.stringify({ type: 'error', message: 'Building not found' })); return;
        }
        const [result] = await pool.query(
          'INSERT INTO rooms (building_id, floor, name, details, type) VALUES (?,?,?,?,?)',
          [buildingId, floor, name, details || '', type || null]
        );
        await logActivity(adminId, 'ADD', 'rooms', result.insertId,
          `${fullName} added room "${name}" (Floor ${floor}) to "${building.name}"`);
        
        // Broadcast updated buildings to ALL clients
        const updatedBuildings = await fetchAllBuildings();
        lastBuildings = updatedBuildings;
        broadcastBuildings(updatedBuildings);
        await pushAdminUpdate();
        
        ws.send(JSON.stringify({ type: 'addRoomSuccess', roomId: result.insertId }));
      } catch (err) {
        console.error('Add room error:', err);
        ws.send(JSON.stringify({ type: 'error', message: 'Failed to add room' }));
      }
      return;
    }

    // ── delete room ──
    if (data.action === 'deleteRoom') {
      const { roomId } = data;
      try {
        const [[room]] = await pool.query(`
          SELECT r.name, r.floor, b.name AS building_name
          FROM rooms r JOIN buildings b ON r.building_id = b.id
          WHERE r.id = ?`, [roomId]);
        if (!room) {
          ws.send(JSON.stringify({ type: 'error', message: 'Room not found' })); return;
        }
        await pool.query('DELETE FROM rooms WHERE id = ?', [roomId]);
        await logActivity(adminId, 'DELETE', 'rooms', roomId,
          `${fullName} deleted room "${room.name}" (Floor ${room.floor}) from "${room.building_name}"`);
        
        // Broadcast updated buildings to ALL clients
        const updatedBuildings = await fetchAllBuildings();
        lastBuildings = updatedBuildings;
        broadcastBuildings(updatedBuildings);
        await pushAdminUpdate();
        
        ws.send(JSON.stringify({ type: 'deleteRoomSuccess', roomId }));
      } catch (err) {
        console.error('Delete room error:', err);
        ws.send(JSON.stringify({ type: 'error', message: 'Failed to delete room' }));
      }
      return;
    }

    // ── update room ──
    if (data.action === 'updateRoom') {
      const { roomId, name, floor, details, type } = data;
      try {
        const [[old]] = await pool.query(`
          SELECT r.name, r.floor, b.name AS building_name
          FROM rooms r JOIN buildings b ON r.building_id = b.id
          WHERE r.id = ?`, [roomId]);
        if (!old) {
          ws.send(JSON.stringify({ type: 'error', message: 'Room not found' })); return;
        }
        await pool.query(
          'UPDATE rooms SET name=?, floor=?, details=?, type=? WHERE id=?',
          [name, floor, details, type, roomId]
        );
        await logActivity(adminId, 'UPDATE', 'rooms', roomId,
          `${fullName} updated room "${old.name}" → "${name}" (Floor ${floor}) in "${old.building_name}"`);
        
        // Broadcast updated buildings to ALL clients
        const updatedBuildings = await fetchAllBuildings();
        lastBuildings = updatedBuildings;
        broadcastBuildings(updatedBuildings);
        await pushAdminUpdate();
        
        ws.send(JSON.stringify({ type: 'updateRoomSuccess', roomId }));
      } catch (err) {
        console.error('Update room error:', err);
        ws.send(JSON.stringify({ type: 'error', message: 'Failed to update room' }));
      }
      return;
    }

    // ── add announcement ──
    if (data.action === 'addAnnouncement') {
      const { title, message } = data;
      if (!title || !message) {
        ws.send(JSON.stringify({ type: 'error', message: 'Title and message required' }));
        return;
      }
      try {
        await createAnnouncement(adminId, title, message);
        await broadcastAnnouncements();
        await pushAdminUpdate();
        ws.send(JSON.stringify({ type: 'addAnnouncementSuccess' }));
        console.log(`\x1b[32m[${time()}] 📢 Announcement added: "${title}"\x1b[0m`);
      } catch (err) {
        console.error('Add announcement error:', err);
        ws.send(JSON.stringify({ type: 'error', message: 'Failed to add announcement' }));
      }
      return;
    }

    // ── delete announcement ──
    if (data.action === 'deleteAnnouncement') {
      const { announcementId } = data;
      try {
        await deleteAnnouncement(adminId, announcementId);
        await broadcastAnnouncements();
        await pushAdminUpdate();
        ws.send(JSON.stringify({ type: 'deleteAnnouncementSuccess' }));
      } catch (err) {
        console.error('Delete announcement error:', err);
        ws.send(JSON.stringify({ type: 'error', message: 'Failed to delete announcement' }));
      }
      return;
    }

    // ── update announcement ──
    if (data.action === 'updateAnnouncement') {
      const { announcementId, title, message } = data;
      try {
        await updateAnnouncement(adminId, announcementId, title, message);
        await broadcastAnnouncements();
        await pushAdminUpdate();
        ws.send(JSON.stringify({ type: 'updateAnnouncementSuccess' }));
      } catch (err) {
        console.error('Update announcement error:', err);
        ws.send(JSON.stringify({ type: 'error', message: 'Failed to update announcement' }));
      }
      return;
    }
  });

  ws.on('close', () => {
    console.log(`\x1b[90m[${time()}] 🔌 Client disconnected (total: ${wss.clients.size})\x1b[0m`);
  });

  ws.on('error', (err) => {
    console.error('\x1b[31m[WS ERROR]\x1b[0m', err.message);
  });
});

console.log('\x1b[32m✅ WebSocket server running on ws://localhost:8080\x1b[0m');