const apiUrl = 'http://localhost/NavMap/api/get_buildingApi.php';

fetch(apiUrl) 
  .then(response => {
     if(!response.ok){
       throw new Error('Error Fetching a response')
     }
     return response.json();
  })

  .then(data => {
        data.forEach(building => {
        const polygon = L.polygon(building.polygon, {
          color: 'green',
          fillColor : 'green',
          fillOpacity : 1
        }).addTo(map);

        polygon.bindPopup(building.buildingName);
  })
  })
  .catch(error => {
     console.error('error in fetch data')
  });

