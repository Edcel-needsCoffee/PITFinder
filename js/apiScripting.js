function loadBuildings() {
  const apiUrl = 'http://localhost/NavMap/api/get_buildingApi.php';


  //fetching data's from get_buildingApi.php
  fetch(apiUrl)
    .then(response => {
      if (!response.ok) {
        throw new Error(`API error: ${response.status}`);
      }
      return response.json();
    })

    //initializing the data
    .then(data => {
      console.log(`Loaded ${data.length} buildings`);

      data.forEach(building => {
        if (!building.polygon || building.polygon.length < 3) return;

        const poly = L.polygon(building.polygon, {
          color: 'blue',
          weight: 2,
          fillColor: '#4caf50',
          fillOpacity: 0.5
        }).addTo(map);



        //side panel logic
        poly.on('click', function () {
          poly.bindPopup(`
         <b>${building.buildingName}</b>
        `).openPopup(); //shows a popup box for polygons
          
        let html = `<h2>${building.buildingName || 'buildings'}</h2>`;
          
          if (building.room && building.room.length > 0) {
            html += '<h3>Rooms:</h3><ul>';
            building.room.forEach(r => {
              html += `<li><strong>${r.name}</strong> (Floor ${r.floor || '?'})<br>${r.details || ''}</li>`;
            });
            html += '</ul>';
          } else {
            html += '<p>No rooms</p>';
          }

           if (building.description) {
            html+=`<h3 style="color:black;"> Description</h3>`
            html += `<p style="font-style: italic; color: #555; margin: 0px 0;">
            ${building.description}</p>`; }

          document.getElementById('building-info').innerHTML = html;
        });
      });
    })
    .catch(err => {
      console.error('Fetch failed:', err);
      document.getElementById('building-info').innerHTML += '<p style="color:red;">Load error</p>';
    });
}

if (window.map) loadBuildings();