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

    .head {
      position: relative; z-index: 10;
      color: white; background-color: #17b890;
      padding: 0 20px; height: 56px;
      display: flex;
      align-items: center;
      justify-content: space-between;
      margin: 0;
    }
    .head-title { font-size: 22px; font-weight: bold; }

    .btn-admin {
      background: white; color: #17b890;
      border: none; padding: 7px 16px;
      border-radius: 6px; font-size: 0.82rem;
      font-weight: 700; cursor: pointer;
      text-decoration: none;
      display: flex;
      align-items: center;
      gap: 6px;
      transition: background 0.2s;
    }
    .btn-admin:hover { background: #e6f7f3; }

    #map {
      position: absolute; top: 56px;
      left: 0; right: 0; bottom: 0; z-index: 1;
    }

    /* LEFT PANEL - Building Info */
    #side-panel {
      position: fixed; top: 76px; left: 20px;
      width: 360px; height: calc(100% - 96px);
      background: #dcf2b0;
      box-shadow: -8px 0 25px rgba(0,0,0,0.35);
      z-index: 2000; overflow-y: auto;
      border-radius: 10px; border: 1px solid #ccc;
    }

    /* TOP RIGHT GROUP - Weather Mini + Announcements Button */
    .top-right-group {
      position: fixed;
      top: 76px;
      right: 20px;
      z-index: 2001;
      display: flex;
      flex-direction: column;
      align-items: flex-end;
      gap: 10px;
    }

    /* Weather Mini Widget */
    .weather-mini {
      background: linear-gradient(135deg, #17b890, #0f8a6a);
      color: white;
      padding: 10px 18px;
      border-radius: 12px;
      display: flex;
      flex-direction: column;
      align-items: center;
      text-align: center;
      box-shadow: 0 2px 8px rgba(0,0,0,0.15);
      min-width: 150px;
    }
    .weather-mini-temp {
      font-size: 1.8rem;
      font-weight: bold;
      line-height: 1;
    }
    .weather-mini-desc {
      font-size: 0.75rem;
      margin: 4px 0;
    }
    .weather-mini-location {
      font-size: 0.65rem;
      opacity: 0.9;
      display: flex;
      align-items: center;
      gap: 4px;
    }

    /* Announcements Toggle Button */
    #announcements-toggle-btn {
      background: #17b890;
      color: white;
      border: none;
      border-radius: 30px;
      padding: 10px 18px;
      cursor: pointer;
      font-size: 0.85rem;
      font-weight: bold;
      display: flex;
      align-items: center;
      gap: 8px;
      box-shadow: 0 2px 10px rgba(0,0,0,0.2);
      transition: all 0.3s ease;
    }
    #announcements-toggle-btn:hover {
      background: #0f8a6a;
      transform: scale(1.02);
    }
    #announcements-toggle-btn i {
      font-size: 1rem;
    }
    .unread-badge {
      background: #e74c3c;
      color: white;
      border-radius: 50%;
      padding: 2px 6px;
      font-size: 0.7rem;
      margin-left: 5px;
    }

    /* RIGHT PANEL - Full Announcements Panel */
    #announcements-panel {
      position: fixed;
      top: 76px;
      right: 20px;
      width: 380px;
      height: calc(100% - 96px);
      background: white;
      box-shadow: 8px 0 25px rgba(0,0,0,0.35);
      z-index: 2000;
      border-radius: 10px;
      border: 1px solid #ccc;
      display: flex;
      flex-direction: column;
      overflow-y: auto;
      transform: translateX(420px);
      transition: transform 0.3s ease;
    }
    #announcements-panel.open {
      transform: translateX(0);
    }

    .panel-header {
      padding: 12px 16px;
      border-bottom: 1px solid #ddd;
      text-align: center;
      display: flex;
      justify-content: space-between;
      align-items: center;
    }
    .panel-header h2 {
      margin: 0;
      color: #2c3e50;
      font-weight: bold;
      font-size: 1.1rem;
    }
    .close-panel-btn {
      background: none;
      border: none;
      font-size: 1.2rem;
      cursor: pointer;
      color: #888;
    }
    .close-panel-btn:hover {
      color: #e74c3c;
    }

    /* Full Weather Widget (inside panel) */
    .weather-widget {
      background: linear-gradient(135deg, #17b890, #0f8a6a);
      color: white;
      padding: 15px 20px;
      margin: 10px;
      border-radius: 12px;
    }
    .weather-main {
      display: flex;
      align-items: center;
      justify-content: space-between;
      margin-bottom: 10px;
    }
    .weather-info {
      display: flex;
      align-items: center;
      gap: 15px;
    }
    .weather-icon {
      font-size: 2.2rem;
    }
    .weather-temp {
      font-size: 2rem;
      font-weight: bold;
    }
    .weather-details-right {
      text-align: right;
    }
    .weather-desc {
      font-size: 1rem;
      text-transform: capitalize;
    }
    .weather-feelslike {
      font-size: 0.75rem;
      opacity: 0.9;
    }
    .weather-location {
      font-size: 0.85rem;
      opacity: 0.9;
      margin-top: 8px;
      padding-top: 8px;
      border-top: 1px solid rgba(255,255,255,0.2);
      display: flex;
      align-items: center;
      gap: 8px;
    }
    .weather-details-row {
      display: flex;
      justify-content: space-between;
      margin-top: 10px;
      padding-top: 10px;
      border-top: 1px solid rgba(255,255,255,0.2);
      font-size: 0.7rem;
    }
    .weather-detail-item {
      text-align: center;
      flex: 1;
    }
    .weather-detail-item i {
      font-size: 0.9rem;
      margin-bottom: 3px;
      display: block;
    }

    /* Announcements Section */
    .announcements-section {
      flex: 1;
      overflow-y: auto;
      padding: 5px;
    }
    .announcement-card {
      background: #f9f9f9;
      margin: 8px 10px;
      padding: 12px;
      border-radius: 8px;
      border-left: 3px solid #17b890;
      box-shadow: 0 1px 2px rgba(0,0,0,0.1);
    }
    .announcement-title {
      font-weight: bold;
      font-size: 0.9rem;
      color: #2c3e50;
      margin-bottom: 5px;
    }
    .announcement-message {
      font-size: 0.8rem;
      color: #555;
      margin: 5px 0;
      line-height: 1.4;
    }
    .announcement-date {
      font-size: 0.65rem;
      color: #888;
      display: flex;
      align-items: center;
      gap: 4px;
    }
    .no-announcements {
      text-align: center;
      padding: 40px;
      color: #888;
      font-size: 0.85rem;
    }

    /* Search Styles */
    .search-container {
      padding: 10px 12px;
      background: #f0f8ea;
      border-bottom: 1px solid #ddd;
    }
    .search-box {
      display: flex;
      gap: 6px;
      align-items: center;
      background: white;
      border: 1px solid #ccc;
      border-radius: 25px;
      padding: 4px 10px;
    }
    .search-box i { color: #888; font-size: 12px; }
    .search-box input {
      flex: 1;
      border: none;
      outline: none;
      font-size: 12px;
      padding: 6px 0;
      background: transparent;
    }
    .search-box input::placeholder { color: #aaa; }
    .search-box button {
      background: #17b890;
      border: none;
      color: white;
      padding: 4px 10px;
      border-radius: 20px;
      cursor: pointer;
      font-size: 11px;
      font-weight: bold;
    }
    .search-box button:hover { background: #138f74; }
    .search-results {
      max-height: 200px;
      overflow-y: auto;
      background: white;
      border-radius: 10px;
      margin-top: 6px;
      box-shadow: 0 2px 8px rgba(0,0,0,0.15);
      display: none;
    }
    .search-results.show { display: block; }
    .search-result-item {
      padding: 8px 12px;
      border-bottom: 1px solid #eee;
      cursor: pointer;
      font-size: 0.8rem;
    }
    .search-result-item:hover { background: #e6f7f3; }
    .result-name { font-weight: 600; color: #2c3e50; }
    .result-type {
      font-size: 10px;
      color: #17b890;
      margin-left: 6px;
    }
    .result-location { font-size: 10px; color: #888; margin-top: 2px; }
    .no-results { padding: 15px; text-align: center; color: #888; font-size: 0.75rem; }
    .clear-search {
      background: none;
      border: none;
      color: #999;
      cursor: pointer;
      padding: 0 6px;
    }
    .clear-search:hover { color: #b91c1c; }

    /* Building Info Styles */
    #building-info {
      padding: 15px;
      background: #f9f9f9;
      min-height: 200px;
    }
    #building-info ul { list-style: none; padding: 0; margin: 0; }
    #building-info li {
      background: #ffffff; margin: 8px 0; padding: 10px;
      border-radius: 8px; box-shadow: 0 1px 3px rgba(0,0,0,0.1);
      cursor: pointer; transition: background 0.2s;
      font-size: 0.85rem;
    }
    #building-info li:hover { background: #e8f4ff; }
    #building-info button {
      margin-top: 12px; padding: 8px 16px;
      background: #17b890; color: white;
      border: none; border-radius: 6px;
      cursor: pointer; font-size: 0.85rem;
    }
    #building-info button:hover { background: #138f74; }
    .error-msg { color: red; text-align: center; padding: 20px; }

    .floor-btn {
      width: 100%; background: #17b890; color: white;
      border: none; border-radius: 6px; padding: 8px 12px;
      font-size: 0.85rem; font-weight: bold; cursor: pointer;
      display: flex; justify-content: space-between; align-items: center;
      margin-bottom: 4px;
    }
    .floor-btn:hover { background: #138f74; }
    .floor-btn.open { background: #0f6e56; border-radius: 6px 6px 0 0; margin-bottom: 0; }
    .floor-btn .arrow { transition: transform 0.3s; font-style: normal; }
    .floor-btn.open .arrow { transform: rotate(180deg); }
    .floor-rooms {
      display: none; background: #eafaf1;
      border: 1px solid #17b890; border-top: none;
      border-radius: 0 0 6px 6px; margin-bottom: 8px;
    }
    .floor-rooms.open { display: block; }
    .room-item {
      padding: 8px 12px; border-bottom: 1px solid #d0ede0; font-size: 0.8rem;
    }
    .room-item:last-child { border-bottom: none; }
    .room-item strong { color: #2c3e50; }
    .room-item small { color: #777; display: block; margin-top: 2px; font-size: 0.7rem; }
    .back-btn {
      margin-top: 12px; padding: 8px 16px;
      background: #17b890; color: white; border: none;
      border-radius: 6px; cursor: pointer; font-size: 0.85rem; width: 100%;
    }
    .back-btn:hover { background: #138f74; }
    .building-title { font-size: 1.1rem; font-weight: bold; color: #2c3e50; margin-bottom: 5px; }
    .building-desc { font-style: italic; color: #555; font-size: 0.8rem; margin-bottom: 12px; }
    .highlight {
      background-color: #fffb05;
      transition: background-color 0.5s;
    }
  </style>
</head>
<body>

  <div class="head">
    <span class="head-title">PIT Navigation System</span>
    <a href="admin.html" class="btn-admin">
      <i class="fa fa-lock"></i>
      Administrator Login
    </a>
  </div>

  <div id="map"></div>

  <!-- LEFT PANEL: Building Info -->
  <div id="side-panel">
    <div class="panel-header">
      <h2>🏢 BUILDING INFO</h2>
    </div>
    
    <!-- Search Section -->
    <div class="search-container">
      <div class="search-box">
        <i class="fas fa-search"></i>
        <input type="text" id="search-input" placeholder="Search building or room..." autocomplete="off">
        <button id="search-btn">Search</button>
        <button id="clear-search-btn" class="clear-search" style="display: none;"><i class="fas fa-times"></i></button>
      </div>
      <div id="search-results" class="search-results"></div>
    </div>
    
    <div id="building-info">
      <p style="text-align:center; padding:30px; color:#555; font-size:0.85rem;">
        Loading buildings...<br>Please wait.
      </p>
    </div>
  </div>

  <!-- TOP RIGHT GROUP: Weather Mini + Announcements Button -->
  <div class="top-right-group">
    <!-- Mini Weather Widget (always visible) -->
    <div class="weather-mini" id="weather-mini">
      <div class="weather-mini-temp" id="mini-temp">--°C</div>
      <div class="weather-mini-desc" id="mini-desc">Loading...</div>
      <div class="weather-mini-location" id="mini-location">
        <i class="fas fa-map-marker-alt"></i> Palompon, Leyte
      </div>
    </div>

    <!-- Toggle Button for Announcements -->
    <button id="announcements-toggle-btn">
      <i class="fas fa-bullhorn"></i> Announcements
      <span id="unread-count" class="unread-badge" style="display: none;">0</span>
    </button>
  </div>

  <!-- RIGHT PANEL: Full Announcements Panel -->
  <div id="announcements-panel">
    <div class="panel-header">
      <h2>📢 Announcements</h2>
      <button class="close-panel-btn" id="close-panel-btn"><i class="fas fa-times"></i></button>
    </div>
    
    <!-- Full Weather Widget -->
    <div class="weather-widget" id="weather-widget">
      <div class="weather-main">
        <div class="weather-info">
          <div class="weather-icon" id="weather-icon"><i class="fas fa-cloud-sun"></i></div>
          <div class="weather-temp" id="weather-temp">--°C</div>
        </div>
        <div class="weather-details-right">
          <div class="weather-desc" id="weather-desc">Loading...</div>
          <div class="weather-feelslike" id="weather-feelslike">Feels like: --°C</div>
        </div>
      </div>
      <div class="weather-location" id="weather-location">
        <i class="fas fa-map-marker-alt"></i> Palompon, Leyte, Philippines
      </div>
      <div class="weather-details-row" id="weather-details-row">
        <div class="weather-detail-item">
          <i class="fas fa-tint"></i>
          <span id="weather-humidity">--%</span>
          <div>Humidity</div>
        </div>
        <div class="weather-detail-item">
          <i class="fas fa-wind"></i>
          <span id="weather-wind">-- km/h</span>
          <div>Wind</div>
        </div>
        <div class="weather-detail-item">
          <i class="fas fa-eye"></i>
          <span id="weather-visibility">-- km</span>
          <div>Visibility</div>
        </div>
      </div>
    </div>

    <!-- Announcements List -->
    <div class="announcements-section" id="announcements-list">
      <div class="no-announcements">Loading announcements...</div>
    </div>
  </div>

  <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
  <script>
    var map = L.map('map').setView([11.05265010, 124.38706219], 18);

    L.tileLayer('https://{s}.basemaps.cartocdn.com/light_all/{z}/{x}/{y}{r}.png', {
      attribution: '© OpenStreetMap contributors & CARTO'
    }).addTo(map);

    window.map = map;

    // ========== WEBSOCKET CONNECTION ==========
    const wsUrl = `ws://${window.location.hostname}:8080`;
    console.log('🔄 Connecting to WebSocket:', wsUrl);
    
    const socket = new WebSocket(wsUrl);
    let allBuildings = [];
    let allAnnouncements = [];
    const buildingLayers = {};
    let currentSearchQuery = '';
    let unreadCount = 0;
    let panelOpen = false;

    // Connection status indicator
    const wsStatus = document.createElement('div');
    wsStatus.style.cssText = 'position:fixed; bottom:10px; right:10px; width:10px; height:10px; border-radius:50%; background:#e74c3c; z-index:3000;';
    document.body.appendChild(wsStatus);

    // ========== TOGGLE FUNCTIONS ==========
    const toggleBtn = document.getElementById('announcements-toggle-btn');
    const announcementsPanel = document.getElementById('announcements-panel');
    const closePanelBtn = document.getElementById('close-panel-btn');
    const unreadSpan = document.getElementById('unread-count');

    function openPanel() {
      announcementsPanel.classList.add('open');
      panelOpen = true;
      unreadCount = 0;
      unreadSpan.style.display = 'none';
    }

    function closePanel() {
      announcementsPanel.classList.remove('open');
      panelOpen = false;
    }

    function togglePanel() {
      if (panelOpen) {
        closePanel();
      } else {
        openPanel();
      }
    }

    toggleBtn.addEventListener('click', togglePanel);
    closePanelBtn.addEventListener('click', closePanel);

    document.addEventListener('click', (e) => {
      if (panelOpen && !announcementsPanel.contains(e.target) && !toggleBtn.contains(e.target) && !document.querySelector('.weather-mini').contains(e.target)) {
        closePanel();
      }
    });

    // ========== WEATHER API with FULL DETAILS ==========
    const PALOMPON_LAT = 11.05265010;
    const PALOMPON_LNG = 124.38706219;

    async function fetchWeather() {
      try {
        const url = `https://wttr.in/${PALOMPON_LAT},${PALOMPON_LNG}?format=j1`;
        const response = await fetch(url);
        const data = await response.json();
        
        if (data && data.current_condition) {
          const temp = data.current_condition[0].temp_C;
          const feelsLike = data.current_condition[0].FeelsLikeC;
          const desc = data.current_condition[0].weatherDesc[0].value;
          const windSpeed = data.current_condition[0].windspeedKmph;
          const humidity = data.current_condition[0].humidity;
          const visibility = data.current_condition[0].visibility;
          
          let icon = 'fa-cloud-sun';
          if (desc.toLowerCase().includes('sun')) icon = 'fa-sun';
          else if (desc.toLowerCase().includes('rain')) icon = 'fa-cloud-rain';
          else if (desc.toLowerCase().includes('cloud')) icon = 'fa-cloud';
          else if (desc.toLowerCase().includes('storm')) icon = 'fa-bolt';
          else if (desc.toLowerCase().includes('fog')) icon = 'fa-smog';
          
          // Update FULL weather widget
          document.getElementById('weather-temp').innerHTML = `${temp}°C`;
          document.getElementById('weather-desc').innerHTML = desc;
          document.getElementById('weather-feelslike').innerHTML = `Feels like: ${feelsLike}°C`;
          document.getElementById('weather-icon').innerHTML = `<i class="fas ${icon}"></i>`;
          document.getElementById('weather-humidity').innerHTML = `${humidity}%`;
          document.getElementById('weather-wind').innerHTML = `${windSpeed} km/h`;
          document.getElementById('weather-visibility').innerHTML = `${visibility} km`;
          
          // Update MINI weather widget (stacked vertically)
          document.getElementById('mini-temp').innerHTML = `${temp}°C`;
          document.getElementById('mini-desc').innerHTML = desc;
        }
      } catch (error) {
        console.error('Weather error:', error);
        // Fallback data
        document.getElementById('weather-temp').innerHTML = '26°C';
        document.getElementById('weather-desc').innerHTML = 'Partly Cloudy';
        document.getElementById('weather-feelslike').innerHTML = 'Feels like: 28°C';
        document.getElementById('weather-icon').innerHTML = '<i class="fas fa-cloud-sun"></i>';
        document.getElementById('weather-humidity').innerHTML = '75%';
        document.getElementById('weather-wind').innerHTML = '12 km/h';
        document.getElementById('weather-visibility').innerHTML = '10 km';
        document.getElementById('mini-temp').innerHTML = '26°C';
        document.getElementById('mini-desc').innerHTML = 'Partly Cloudy';
      }
    }

    fetchWeather();
    setInterval(fetchWeather, 10 * 60 * 1000);

    // ========== WEBSOCKET EVENT HANDLERS ==========
    socket.onopen = () => {
      console.log("✅ WebSocket connected");
      wsStatus.style.background = '#27ae60';
      socket.send(JSON.stringify({ action: 'getBuildings' }));
      socket.send(JSON.stringify({ action: 'getAnnouncements' }));
    };

    socket.onerror = (err) => {
      console.error("❌ WebSocket error:", err);
      wsStatus.style.background = '#e74c3c';
    };

    socket.onclose = () => {
      console.warn("⚠️ WebSocket closed");
      wsStatus.style.background = '#e74c3c';
      setTimeout(() => {
        console.log("Reconnecting...");
        window.location.reload();
      }, 3000);
    };

    socket.onmessage = (event) => {
      let message;
      try { 
        message = JSON.parse(event.data); 
        console.log("📨 Received:", message.type);
      } catch (e) { 
        console.error("Failed to parse:", e); 
        return; 
      }

      if (message.type === 'initialData') {
        console.log("🏢 Buildings update:", message.data.length);
        allBuildings = message.data;
        Object.values(buildingLayers).forEach(poly => map.removeLayer(poly));
        Object.keys(buildingLayers).forEach(k => delete buildingLayers[k]);
        displayBuildings(allBuildings);
      }

      if (message.type === 'announcementsData') {
        console.log("📢 Announcements update:", message.data.length);
        const oldCount = allAnnouncements.length;
        allAnnouncements = message.data;
        displayAnnouncements(allAnnouncements);
        
        if (!panelOpen && message.data.length > oldCount) {
          unreadCount = message.data.length - oldCount;
          unreadSpan.textContent = unreadCount;
          unreadSpan.style.display = 'inline-block';
          setTimeout(() => {
            unreadSpan.style.display = 'none';
          }, 5000);
        }
      }

      if (message.type === 'error') {
        console.error("Server error:", message.message);
      }
    };

    // ========== DISPLAY ANNOUNCEMENTS ==========
    function displayAnnouncements(announcements) {
      const container = document.getElementById('announcements-list');
      
      if (!announcements || announcements.length === 0) {
        container.innerHTML = '<div class="no-announcements"><i class="fas fa-bullhorn"></i><br>No announcements yet.</div>';
        return;
      }
      
      container.innerHTML = announcements.map(ann => `
        <div class="announcement-card">
          <div class="announcement-title">
            <i class="fas fa-bullhorn" style="color: #17b890; font-size:0.8rem;"></i> 
            ${escapeHtml(ann.title)}
          </div>
          <div class="announcement-message">${escapeHtml(ann.message)}</div>
          <div class="announcement-date">
            <i class="far fa-calendar-alt"></i> 
            ${new Date(ann.created_at).toLocaleString('en-PH', {
              month: 'short', day: 'numeric', 
              hour: '2-digit', minute: '2-digit'
            })}
          </div>
        </div>
      `).join('');
    }

    // ========== SEARCH FUNCTIONALITY ==========
    function performSearch() {
      const query = document.getElementById('search-input').value.trim().toLowerCase();
      currentSearchQuery = query;
      
      const clearBtn = document.getElementById('clear-search-btn');
      
      if (!query) {
        document.getElementById('search-results').classList.remove('show');
        clearBtn.style.display = 'none';
        return;
      }
      
      clearBtn.style.display = 'flex';
      
      const results = [];
      
      allBuildings.forEach(building => {
        if (building.name && building.name.toLowerCase().includes(query)) {
          results.push({
            type: 'building',
            id: building.id,
            name: building.name,
            location: `${building.room?.length || 0} rooms`,
            buildingName: building.name
          });
        }
        
        if (building.room && building.room.length > 0) {
          building.room.forEach(room => {
            if (room.name && room.name.toLowerCase().includes(query)) {
              results.push({
                type: 'room',
                id: room.id,
                name: room.name,
                location: `${building.name} • Floor ${room.floor || '?'}`,
                buildingId: building.id,
                floor: room.floor,
                roomName: room.name
              });
            }
          });
        }
      });
      
      const resultsContainer = document.getElementById('search-results');
      
      if (results.length === 0) {
        resultsContainer.innerHTML = '<div class="no-results"><i class="fas fa-search"></i> No results found for "' + escapeHtml(query) + '"</div>';
        resultsContainer.classList.add('show');
        return;
      }
      
      resultsContainer.innerHTML = results.map(result => `
        <div class="search-result-item" onclick="selectSearchResult(${JSON.stringify(result).replace(/"/g, '&quot;')})">
          <div>
            <span class="result-name">${escapeHtml(result.name)}</span>
            <span class="result-type">${result.type === 'building' ? '🏢 Building' : '🚪 Room'}</span>
          </div>
          <div class="result-location"><i class="fas fa-map-marker-alt"></i> ${escapeHtml(result.location)}</div>
        </div>
      `).join('');
      resultsContainer.classList.add('show');
    }
    
    function selectSearchResult(result) {
      document.getElementById('search-results').classList.remove('show');
      
      if (result.type === 'building') {
        selectBuilding(result.id);
      } else if (result.type === 'room') {
        const building = allBuildings.find(b => b.id === result.buildingId);
        if (building) {
          selectBuilding(result.buildingId);
          setTimeout(() => {
            highlightRoomInBuilding(result.buildingId, result.roomName, result.floor);
          }, 500);
        }
      }
      
      document.getElementById('search-input').value = result.name;
    }
    
    function highlightRoomInBuilding(buildingId, roomName, floor) {
      const floorId = `floor-${buildingId}-${floor || 'Unknown'}`;
      const floorPanel = document.getElementById(floorId);
      const floorBtn = document.querySelector(`button[onclick*="${floorId.replace(/'/g, "\\'")}"]`);
      
      if (floorPanel && floorBtn) {
        document.querySelectorAll('.floor-rooms').forEach(p => p.classList.remove('open'));
        document.querySelectorAll('.floor-btn').forEach(b => b.classList.remove('open'));
        
        floorPanel.classList.add('open');
        floorBtn.classList.add('open');
        
        const roomItems = floorPanel.querySelectorAll('.room-item');
        roomItems.forEach(item => {
          const strongEl = item.querySelector('strong');
          if (strongEl && strongEl.textContent === roomName) {
            item.classList.add('highlight');
            item.scrollIntoView({ behavior: 'smooth', block: 'center' });
            setTimeout(() => {
              item.classList.remove('highlight');
            }, 2000);
          }
        });
      }
    }
    
    function clearSearch() {
      document.getElementById('search-input').value = '';
      document.getElementById('search-results').classList.remove('show');
      document.getElementById('clear-search-btn').style.display = 'none';
      currentSearchQuery = '';
    }

    // ========== BUILDING FUNCTIONS ==========
    function displayBuildings(buildings) {
      const infoDiv = document.getElementById('building-info');
      let html = '<h3 style="font-size:0.9rem;">All Buildings</h3><ul>';

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
            <strong>${escapeHtml(building.name)}</strong><br>
            <small style="font-size:0.7rem;">${building.room?.length || 0} rooms</small>
          </li>`;
      });

      html += '</ul>';
      infoDiv.innerHTML = html;
    }

    function selectBuilding(buildingId) {
      window.currentBuildingId = buildingId;
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

      let html = `<div class="building-title">${escapeHtml(building.name)}</div>`;
      if (building.description) {
        html += `<div class="building-desc">${escapeHtml(building.description)}</div>`;
      }

      if (sortedFloors.length === 0) {
        html += '<p style="color:#888; font-size:0.8rem;">No rooms listed.</p>';
      } else {
        sortedFloors.forEach(floor => {
          const label = floor === 'Unknown' ? 'Unknown Floor' : `Floor ${floor}`;
          const roomCount = floorMap[floor].length;
          const floorId = `floor-${building.id}-${floor}`;
          html += `
            <button class="floor-btn" onclick="toggleFloor('${floorId}', this)">
              <span>🏢 ${escapeHtml(label)} <span style="font-weight:normal;font-size:0.7rem;">(${roomCount})</span></span>
              <span class="arrow">▼</span>
            </button>
            <div class="floor-rooms" id="${floorId}">`;
          floorMap[floor].forEach(r => {
            html += `
              <div class="room-item">
                <strong>${escapeHtml(r.name)}</strong>
                <small>${escapeHtml(r.details) || 'No details'}</small>
              </div>`;
          });
          html += `</div>`;
        });
      }

      html += `<button class="back-btn" onclick="showBuildingList()">← Back to List</button>`;
      infoDiv.innerHTML = html;
    }

    function toggleFloor(floorId, btn) {
      const panel = document.getElementById(floorId);
      const isOpen = panel.classList.contains('open');
      document.querySelectorAll('.floor-rooms').forEach(p => p.classList.remove('open'));
      document.querySelectorAll('.floor-btn').forEach(b => b.classList.remove('open'));
      if (!isOpen) { panel.classList.add('open'); btn.classList.add('open'); }
    }

    function showBuildingList() {
      window.currentBuildingId = null;
      const infoDiv = document.getElementById('building-info');
      let html = '<h3 style="font-size:0.9rem;">All Buildings</h3><ul>';
      allBuildings.forEach(building => {
        if (!building.polygon || building.polygon.length < 3) return;
        html += `
          <li onclick="selectBuilding(${building.id})">
            <strong>${escapeHtml(building.name)}</strong><br>
            <small style="font-size:0.7rem;">${building.room?.length || 0} rooms</small>
          </li>`;
      });
      html += '</ul>';
      infoDiv.innerHTML = html;
    }

    function escapeHtml(str) {
      if (!str) return '';
      return str.replace(/[&<>]/g, function(m) {
        if (m === '&') return '&amp;';
        if (m === '<') return '&lt;';
        if (m === '>') return '&gt;';
        return m;
      });
    }
    
    // Event Listeners
    document.getElementById('search-btn').addEventListener('click', performSearch);
    document.getElementById('clear-search-btn').addEventListener('click', clearSearch);
    document.getElementById('search-input').addEventListener('keypress', (e) => {
      if (e.key === 'Enter') performSearch();
    });
    
    document.addEventListener('click', (e) => {
      const searchContainer = document.querySelector('.search-container');
      if (searchContainer && !searchContainer.contains(e.target)) {
        document.getElementById('search-results').classList.remove('show');
      }
    });
  </script>
</body>
</html>