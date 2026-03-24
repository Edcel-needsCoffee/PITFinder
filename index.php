<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>PIT Navigation System</title>
  <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css"
        integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY=" crossorigin=""/>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

  <style>
    * { box-sizing: border-box; }
    body, html {
      margin: 0; padding: 0;
      height: 100%; overflow: hidden;
      font-family: Arial, sans-serif;
    }

    /* FIX: header is now flex to fit title + button */
    .head {
      position: relative; z-index: 10;
      color: white; background-color: #17b890;
      padding: 0 20px; height: 56px;
      display: flex; align-items: center;
      justify-content: space-between; margin: 0;
    }
    .head-title { font-size: 22px; font-weight: bold; }

    .btn-admin {
      background: white; color: #17b890;
      border: none; padding: 7px 16px;
      border-radius: 6px; font-size: 0.82rem;
      font-weight: 700; cursor: pointer;
      text-decoration: none;
      display: flex; align-items: center; gap: 6px;
      transition: background 0.2s;
    }
    .btn-admin:hover { background: #e6f7f3; }

    #map {
      position: absolute; top: 56px;
      left: 0; right: 0; bottom: 0; z-index: 1;
    }
    #side-panel {
      position: fixed; top: 76px; left: 20px;
      width: 360px; height: calc(100% - 96px);
      background: #dcf2b0;
      box-shadow: -8px 0 25px rgba(0,0,0,0.35);
      z-index: 2000; overflow-y: auto;
      border-radius: 10px; border: 1px solid #ccc;
    }
    .panel-header {
      padding: 16px 20px; background: #c2eabd;
      border-bottom: 1px solid #ddd; text-align: center;
    }
    .panel-header h2 {
      margin: 0; font-size: 1.5em;
      color: #2c3e50; font-weight: bold;
    }
    #building-info {
      padding: 20px; background: #f9f9f9; min-height: 200px;
    }
    #building-info ul { list-style: none; padding: 0; margin: 0; }
    #building-info li {
      background: #ffffff; margin: 10px 0; padding: 14px;
      border-radius: 8px; box-shadow: 0 2px 6px rgba(0,0,0,0.1);
      cursor: pointer; transition: background 0.2s;
    }
    #building-info li:hover { background: #e8f4ff; }
    #building-info button {
      margin-top: 15px; padding: 10px 20px;
      background: #17b890; color: white;
      border: none; border-radius: 6px;
      cursor: pointer; font-size: 1em;
    }
    #building-info button:hover { background: #138f74; }
    .error-msg { color: red; text-align: center; padding: 20px; }

    .floor-btn {
      width: 100%; background: #17b890; color: white;
      border: none; border-radius: 8px; padding: 12px 16px;
      font-size: 1em; font-weight: bold; cursor: pointer;
      display: flex; justify-content: space-between; align-items: center;
      margin-bottom: 6px; transition: background 0.2s;
    }
    .floor-btn:hover { background: #138f74; }
    .floor-btn.open { background: #0f6e56; border-radius: 8px 8px 0 0; margin-bottom: 0; }
    .floor-btn .arrow { transition: transform 0.3s; font-style: normal; }
    .floor-btn.open .arrow { transform: rotate(180deg); }
    .floor-rooms {
      display: none; background: #eafaf1;
      border: 1px solid #17b890; border-top: none;
      border-radius: 0 0 8px 8px; margin-bottom: 10px; overflow: hidden;
    }
    .floor-rooms.open { display: block; }
    .room-item {
      padding: 10px 16px; border-bottom: 1px solid #d0ede0; font-size: 0.95em;
    }
    .room-item:last-child { border-bottom: none; }
    .room-item strong { color: #2c3e50; }
    .room-item small { color: #777; display: block; margin-top: 2px; }
    .back-btn {
      margin-top: 15px; padding: 10px 20px;
      background: #17b890; color: white; border: none;
      border-radius: 6px; cursor: pointer; font-size: 1em; width: 100%;
    }
    .back-btn:hover { background: #138f74; }
    .building-title { font-size: 1.3em; font-weight: bold; color: #2c3e50; margin-bottom: 6px; }
    .building-desc  { font-style: italic; color: #555; font-size: 0.9em; margin-bottom: 16px; }
    .no-rooms { padding: 10px 16px; color: #888; font-size: 0.9em; }
  </style>
</head>
<body>

  <!-- HEADER with admin button -->
  <div class="head">
    <span class="head-title">PIT Navigation System</span>
    <a href="admin.html" class="btn-admin">
      <i class="fa fa-lock" style="font-size:0.85em;"></i>
      Administrator Login
    </a>
  </div>

  <div id="map"></div>

  <div id="side-panel">
    <div class="panel-header"><h2>BUILDING INFO</h2></div>
    <div id="building-info">
      <p style="text-align:center; padding:40px 20px; color:#555; font-size:1.1em;">
        Loading buildings...<br>Please wait a moment.
      </p>
    </div>
  </div>

  <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
  <script>
    var map = L.map('map').setView([11.05265010, 124.38706219], 18);

    L.tileLayer('https://{s}.basemaps.cartocdn.com/light_all/{z}/{x}/{y}{r}.png', {
      attribution: '© OpenStreetMap contributors & CARTO'
    }).addTo(map);

    window.map = map;

    const socket = new WebSocket('ws://localhost:8080');
    let allBuildings = [];
    const buildingLayers = {};

    socket.onopen = () => {
      console.log("WebSocket connection established");
      socket.send(JSON.stringify({ action: 'getBuildings' }));
    };

    socket.onerror = (err) => {
      console.error("WebSocket error:", err);
      document.getElementById('building-info').innerHTML =
        '<p class="error-msg">⚠️ Connection error. Please refresh the page.</p>';
    };

    socket.onclose = () => console.warn("WebSocket connection closed");

    socket.onmessage = (event) => {
      let message;
      try { message = JSON.parse(event.data); }
      catch (e) { console.error("Failed to parse server message:", e); return; }

      if (message.type === 'initialData') {
        allBuildings = message.data;

        // Remove old polygons and redraw on live update
        Object.values(buildingLayers).forEach(poly => map.removeLayer(poly));
        Object.keys(buildingLayers).forEach(k => delete buildingLayers[k]);
        displayBuildings(allBuildings);
      }

      if (message.type === 'error') {
        document.getElementById('building-info').innerHTML =
          `<p class="error-msg">⚠️ ${message.message}</p>`;
      }
    };

    function displayBuildings(buildings) {
      const infoDiv = document.getElementById('building-info');
      let html = '<h3>All Buildings</h3><ul>';

      buildings.forEach(building => {
        if (!building.polygon || building.polygon.length < 3) return;

        const poly = L.polygon(building.polygon, {
          color: 'blue', weight: 2,
          fillColor: '#4caf50', fillOpacity: 0.5
        }).addTo(map);

        buildingLayers[building.id] = poly;
        poly.bindPopup(`<b>${building.name}</b>`);
        poly.on('click', () => selectBuilding(building.id));

        html += `
          <li onclick="selectBuilding(${building.id})">
            <strong>${building.name}</strong><br>
            <small>${building.room?.length || 0} rooms</small>
          </li>`;
      });

      html += '</ul>';
      infoDiv.innerHTML = html;
    }

    function selectBuilding(buildingId) {
      const poly = buildingLayers[buildingId];
      if (!poly) { console.error("Polygon not found:", buildingId); return; }

      map.flyToBounds(poly.getBounds(), { padding: [60, 60], duration: 1.3 });
      poly.setStyle({ fillColor: '#fffb05', weight: 3 });
      setTimeout(() => poly.setStyle({ fillColor: '#4caf50', weight: 2 }), 1800);

      const building = allBuildings.find(b => b.id === buildingId);
      if (building) showBuildingDetails(building);
    }

    function showBuildingDetails(building) {
      const infoDiv = document.getElementById('building-info');

      const floorMap = {};
      if (building.room && building.room.length > 0) {
        building.room.forEach(r => {
          const floor = r.floor || 'Unknown';
          if (!floorMap[floor]) floorMap[floor] = [];
          floorMap[floor].push(r);
        });
      }

      const sortedFloors = Object.keys(floorMap).sort((a, b) => {
        if (a === 'Unknown') return 1;
        if (b === 'Unknown') return -1;
        return Number(a) - Number(b);
      });

      let html = `<div class="building-title">${building.name}</div>`;
      if (building.description) {
        html += `<div class="building-desc">${building.description}</div>`;
      }

      if (sortedFloors.length === 0) {
        html += '<p style="color:#888;">No rooms listed.</p>';
      } else {
        sortedFloors.forEach(floor => {
          const label     = floor === 'Unknown' ? 'Unknown Floor' : `Floor ${floor}`;
          const roomCount = floorMap[floor].length;
          const floorId   = `floor-${building.id}-${floor}`;
          html += `
            <button class="floor-btn" onclick="toggleFloor('${floorId}', this)">
              <span>🏢 ${label} <span style="font-weight:normal;font-size:0.85em;">(${roomCount} room${roomCount > 1 ? 's' : ''})</span></span>
              <span class="arrow">▼</span>
            </button>
            <div class="floor-rooms" id="${floorId}">`;
          floorMap[floor].forEach(r => {
            html += `
              <div class="room-item">
                <strong>${r.name}</strong>
                <small>${r.details || 'No details available'}</small>
              </div>`;
          });
          html += `</div>`;
        });
      }

      html += `<button class="back-btn" onclick="showBuildingList()">← Back to List</button>`;
      infoDiv.innerHTML = html;
    }

    function toggleFloor(floorId, btn) {
      const panel  = document.getElementById(floorId);
      const isOpen = panel.classList.contains('open');
      document.querySelectorAll('.floor-rooms').forEach(p => p.classList.remove('open'));
      document.querySelectorAll('.floor-btn').forEach(b => b.classList.remove('open'));
      if (!isOpen) { panel.classList.add('open'); btn.classList.add('open'); }
    }

    function showBuildingList() {
      const infoDiv = document.getElementById('building-info');
      let html = '<h3>All Buildings</h3><ul>';
      allBuildings.forEach(building => {
        if (!building.polygon || building.polygon.length < 3) return;
        html += `
          <li onclick="selectBuilding(${building.id})">
            <strong>${building.name}</strong><br>
            <small>${building.room?.length || 0} rooms</small>
          </li>`;
      });
      html += '</ul>';
      infoDiv.innerHTML = html;
    }
  </script>

</body>
</html>