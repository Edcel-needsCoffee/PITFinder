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
      padding: 15px 0;
      font-size: 28px;
      font-weight: bold;
      text-align: center;
      margin: 0;
    }

    /* Map */
    #map {
      position: absolute;
      top: 50px;
      left: 0;
      right: 0;
      bottom: 0;
      z-index: 1;
    }

    /* Side panel */
    #side-panel {
      position: fixed;
      top: 70px;
      left: 20px;              /* ← FIXED: added unit */
      width: 360px;
      height: calc(100% - 90px);
      background: #dcf2b0;
      box-shadow: -8px 0 25px rgba(0,0,0,0.35);
      z-index: 2000;
      overflow-y: auto;
      border-radius: 10px;
      border: 1px solid #ccc;
    }

    .panel-header {
      padding: 16px 20px;
      background: #c2eabd;
      border-bottom: 1px solid #ddd;
      text-align: center;
    }

    .panel-header h2 {
      margin: 0;
      font-size: 1.5em;
      color: #2c3e50;
      font-weight: bold;
    }

    #building-info {
      padding: 20px;
      background: #f9f9f9;         /* cleaner look */
      min-height: 200px;
    }

    /* Make list items look clickable */
    #building-info ul {
      list-style: none;
      padding: 0;
      margin: 0;
    }

    #building-info li {
      background: #ffffff;
      margin: 10px 0;
      padding: 14px;
      border-radius: 8px;
      box-shadow: 0 2px 6px rgba(0,0,0,0.1);
      cursor: pointer;
      transition: background 0.2s;
    }

    #building-info li:hover {
      background: #e8f4ff;
    }

    #building-info button {
      margin-top: 15px;
      padding: 10px 20px;
      background: #17b890;
      color: white;
      border: none;
      border-radius: 6px;
      cursor: pointer;
      font-size: 1em;
    }

    #building-info button:hover {
      background: #138f74;
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

    // Optional: log clicks on map (for debugging)
    map.on('click', function(e) {
      console.log(`Map clicked at: [${e.latlng.lat.toFixed(8)}, ${e.latlng.lng.toFixed(8)}]`);
    });

    // Fix map size after layout settles
    setTimeout(() => map.invalidateSize(), 200);

    // Keep your temporary hardcoded polygons (they won't conflict)
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
    CGSBldg.bindPopup("This is College of Graduate Studies");

    const marker = L.marker([11.05144702397503, 124.38613759260512]).addTo(map);
    marker.bindPopup("You are here");
  </script>

  <!-- Load your dynamic logic last -->
  <script src="js/apiScripting.js"></script>

</body>
</html>