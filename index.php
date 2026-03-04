<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Document</title>
  <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css"
     integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY="
     crossorigin=""/>

     <style>
       #map {
         height : 100vh;
       }
     </style>
</head>
<body>
  
  <div class="map" id="map"></div> 


<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>

 
     

     
<script>
      var map = L.map('map').setView([11.051987434086843, 124.38721536092984], 18);
     
      L.tileLayer('https://tile.openstreetmap.org/{z}/{x}/{y}.png', {
        maxZoom: 19,
        attribution: '&copy; <a href="http://www.openstreetmap.org/copyright">OpenStreetMap</a>'
      }).addTo(map);


const Library = L.polygon([[11.051449926960808, 124.38671031683248],
                          [11.051619063019347, 124.38674250333735],
                          [11.051576285426206, 124.38703352298552],
                          [11.051434132148811, 124.38699999537627],  ///Adding a polygon building
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
  [11.051619936317286, 124.38698119413755], //another polygon
  [11.051662981356975, 124.38676030729368]
]).addTo(map);


const HighschoolBldg = L.polygon([
[11.051392213115161, 124.38611041056501],
[11.051312522286056, 124.38648932779691],
[11.051219549624744, 124.38646339001019],
[11.051295920027265, 124.3860743232096],
]).addTo(map);


HighschoolBldg.on('click', function(){
  HighschoolBldg.bindPopup("This is High School Bldg");
});






const marker = L.marker([11.05144702397503, 124.38613759260512]).addTo(map);
marker.bindPopup("You are here");





</script>

</body>
</html>