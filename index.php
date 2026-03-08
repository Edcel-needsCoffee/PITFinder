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
    * {
      box-sizing: border-box;
    }
    body, html {
      margin: 0;
      padding: 0;
      height: 100%;
      overflow: hidden;
      font-family: Arial, sans-serif;
    }

    /* Header */
    .head {
      position: relative;
      z-index: 10;
      color: white;
      background-color: #17b890;
      padding: 15px 0 10px;
      font-size: 30px;
      font-weight: bold;
      text-align: center;
      margin: 0;
    }

    /* Map – full area below header */
    #map {
      position: absolute;
      top: 50px;           /* height of header */
      left: 0;
      right: 0;
      bottom: 0;
      z-index: 1;
    }

    /* Fixed side panel – overlays map on the right */
    #side-panel {
      position: fixed;
      top: 70px;
      right: 100;
      width: 360px;
      height: calc(100% - 90px);
      background: #dcf2b0;
      box-shadow: -8px 0 25px rgba(0,0,0,0.35);
      z-index: 2000;       /* ABOVE map (Leaflet uses ~1–1000) */
      overflow-y: auto;
      border-left: 1px solid #ccc;
      border-radius: 10px;
      
    }

    .panel-header {
      padding: 16px 20px;
      background: #c2eabd;
      border-bottom: 1px solid #ddd;

    }

    .panel-header h2 {
      margin: 0;
      font-size: 1.4em;
      color: #2c3e50;
      text-align: center;
      font-weight: bold;

    }

    #building-info {
      margin-top: 20px;
      padding: 20px;
      margin-right: 30px;
      margin-left: 30px;
      text-align: center;
      background-color: #c7aa74;
      padding-bottom: 300px;
      border-radius: 10px;
      margin-bottom: 0;
    }
  </style>
</head>
<body>

  <div class="head">PIT Navigation System</div>

  <div id="map"></div>
  
  <div id="side-panel">
    <div class="panel-header">
      <h2>BUILDING INFO</h2>
    </div>
    <div id="building-info">
      <p style="text-align:center; padding:30px 20px; color:#555;">
        Click a building on the map<br>to see its rooms
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

    // Coordinate logger
    map.on('click', function(e) {
      var lat = e.latlng.lat.toFixed(8);
      var lng = e.latlng.lng.toFixed(8);
      console.log(`Clicked at: [${lat}, ${lng}]`);
    });

    // Redraw map (fixes size issues)
    setTimeout(() => map.invalidateSize(), 100);

    // Hard-coded polygons (temporary)
    const libRary = L.polygon([
      [11.051449926960808, 124.38671031683248],
      [11.051619063019347, 124.38674250333735],
      [11.051576285426206, 124.38703352298552],
      [11.051434132148811, 124.38699999537627],
      [11.051434132148811, 124.38697518494547],
      [11.051413730515387, 124.3869644561105],
      [11.051421627922046, 124.3869161763532],
      [11.051388063942284, 124.38690209475732],
      [11.051401226287743, 124.38685113279128],
      [11.051413072398153, 124.3868491211347]
    ]).addTo(map);

    const CGSBldg = L.polygon([
      [11.05177176788365, 124.3867690789734],
      [11.051733418681012, 124.38700192719868],
      [11.051619936317286, 124.38698119413755],
      [11.051662981356975, 124.38676030729368]
    ]).addTo(map);
    CGSBldg.on('click', function(){
      CGSBldg.bindPopup("This is College of Graduate Studies").openPopup();
    });

    const marker = L.marker([11.05144702397503, 124.38613759260512]).addTo(map);
    marker.bindPopup("You are here");
  </script>


  <script src="js/apiScripting.js"></script>

</body>
</html>