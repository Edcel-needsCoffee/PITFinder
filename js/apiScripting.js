const buildingLayers = {}; //new variables
let allBuildings = [];  

function loadBuildings() {
  const apiUrl = 'http://localhost/PITFinder-main2/api/get_buildingApi.php';


  console.log("1 - loadBuildings started");
  console.log("2 - API URL:", apiUrl);

  fetch(apiUrl)
    .then(response => {
      console.log("Response status:", response.status);
      if (!response.ok) {
        throw new Error(`API error: ${response.status}`);
      }
      return response.json();
    })
    .then(data => {
      console.log(`Loaded ${data.length} buildings`);
      allBuildings = data;  // ← store for later use

      const infoDiv = document.getElementById('building-info');
      let html = '<h3>All Buildings</h3><ul style="list-style: none; padding: 0;">';

      data.forEach(building => {
        if (!building.polygon || building.polygon.length < 3) return;

        const poly = L.polygon(building.polygon, {
          color: 'blue',
          weight: 2,
          fillColor: '#4caf50',
          fillOpacity: 0.5
        }).addTo(map);






        // updated side panel logic
        const id = building.buildingId;
        buildingLayers[id] = poly;

        poly.bindPopup(`<b>${building.buildingName}</b>`);

        poly.on('click', () => {
          showBuildingDetails(building, poly);
          poly.openPopup();
        });

        html += `
          <li style="margin:10px 0; padding:12px; background:#f0f8ff; border-radius:8px; cursor:pointer;"
              onclick="selectBuilding(${id})">
            <strong>${building.buildingName}</strong><br>
            <small>${building.room?.length || 0} rooms</small>
          </li>`;
      });

      html += '</ul>';
      infoDiv.innerHTML = html;
    })
    .catch(err => {
      console.error("Load failed:", err);
      document.getElementById('building-info').innerHTML +=
        '<p style="color:red;">Load error: ' + err.message + '</p>';
    });
}

function selectBuilding(id) {
  const poly = buildingLayers[id];
  if (!poly) {
    console.warn("No polygon for building ID:", id);
    return;
  }

  map.flyToBounds(poly.getBounds(), {
    padding: [60, 60],
    duration: 1.3
  });

  poly.setStyle({ fillColor: '#ff9800', weight: 3 });
  setTimeout(() => poly.setStyle({ fillColor: '#4caf50', weight: 2 }), 1800);

  const building = allBuildings.find(b => b.buildingId === id);
  if (building) {
    showBuildingDetails(building, poly);
  }
}

function showBuildingDetails(building, poly) {
  let html = `<h2>${building.buildingName}</h2>`;

  if (building.room?.length > 0) {
    html += '<h3>Rooms:</h3><ul>';
    building.room.forEach(r => {
      html += `<li><strong>${r.name}</strong> (Floor ${r.floor || '?'})<br>${r.details || ''}</li>`;
    });
    html += '</ul>';
  } else {
    html += '<p>No rooms listed.</p>';
  }

  if (building.description) {
    html += `<h3>Description</h3><p style="font-style:italic; color:#555;">${building.description}</p>`;
  }

  html += `
    <button onclick="showBuildingList()" 
            style="margin-top:20px; padding:10px 20px; background:#17b890; color:white; border:none; border-radius:6px; cursor:pointer;">
      ← Back to List
    </button>`;

  document.getElementById('building-info').innerHTML = html;
}

function showBuildingList() {
  const infoDiv = document.getElementById('building-info');
  let html = '<h3>All Buildings</h3><ul style="list-style:none; padding:0;">';

  allBuildings.forEach(building => {
    html += `
      <li style="margin:10px 0; padding:12px; background:#f0f8ff; border-radius:8px; cursor:pointer;"
          onclick="selectBuilding(${building.buildingId})">
        <strong>${building.buildingName}</strong><br>
        <small>${building.room?.length || 0} rooms</small>
      </li>`;
  });

  html += '</ul>';
  infoDiv.innerHTML = html;
}


loadBuildings();