<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Document</title>
  <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css"
     integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY="
     crossorigin=""/>
    <script src="js/apiScripting.js"></script>
     <style>
       #map {
         height : 100vh;
         width: 100%;
       }

.head{
  display: block;
  color: white;
  background-color: green;
  padding: 20px;
  font-size: 30px;
  font-weight: bold;
  text-align: center;
  padding-top: 10px;
  margin-top: 0px;
}

     </style>
</head>
<body>

   <div class="head"> PIT Navigation System </div>
  <div class="map" id="map"></div> 
 


<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>

 
     

     
<script>
      var map = L.map('map').setView([11.051987434086843, 124.38721536092984], 18);
     
L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
  attribution: 'Map data © OpenStreetMap contributors'
}).addTo(map);

      
// Click anywhere on the map → print exact coordinates to console
map.on('click', function(e) {
    var lat = e.latlng.lat.toFixed(8);   // 8 decimal places = good precision
    var lng = e.latlng.lng.toFixed(8);
    
    console.log(`Clicked at: [${lat}, ${lng}]`);
});


const libRary = L.polygon([[11.051449926960808, 124.38671031683248],
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
CGSBldg.on('click', function(){
  CGSBldg.bindPopup("This is College of Graduate Studies");
});

const highSchoolBldg = L.polygon([
[11.051392213115161, 124.38611041056501],
[11.051312522286056, 124.38648932779691],
[11.051219549624744, 124.38646339001019],
[11.051295920027265, 124.3860743232096],
]).addTo(map);


highSchoolBldg.on('click', function(){
  highSchoolBldg.bindPopup("This is High School Bldg");
});

const ictBuilding =L.polygon([
[11.05256060, 124.38676983],
[11.05268696, 124.38679934],
[11.05275803, 124.38642114],
[11.05263167, 124.38640237],
]).addTo(map);

ictBuilding.on('click', function(){
  ictBuilding.bindPopup("This is ICT Building");
});

const coteBuilding =L.polygon([
 [11.05273434, 124.38683152],
 [11.05347932, 124.38695759],
 [11.05357146, 124.38660622],
 [11.05279752, 124.38645065],
]).addTo(map);

coteBuilding.on('click', function(){
  coteBuilding.bindPopup("This is COTE Building");
});


const cogBuilding =L.polygon([
 [11.05178665, 124.38704610],
 [11.05171821, 124.38740283],
 [11.05145233, 124.38734919],
 [11.05146286, 124.38720167],
 [11.05159448, 124.38723385],
 [11.05164450, 124.38701123],
]).addTo(map);

cogBuilding.on('click', function(){
  cogBuilding.bindPopup("Maritime Engineering");
});



const consTruction =L.polygon([
 [11.05185773, 124.38652307],
 [11.05184457, 124.38659281],
 [11.05151551, 124.38654721],
 [11.05159185, 124.38615024],
 [11.05167872, 124.38617170],
 [11.05163134, 124.38645601],
]).addTo(map);

consTruction.on('click', function(){
  consTruction.bindPopup("Under construction!");
});

const guidanceOffice =L.polygon([
 [11.05237896, 124.38637823],
 [11.05229472, 124.38636214],
[11.05225786, 124.38658744],
[11.05234737, 124.38659817],
 [11.05241318, 124.38639164],
]).addTo(map);

guidanceOffice.on('click', function(){
  guidanceOffice.bindPopup("This is the Guidance Office");
});



const casBuilding =L.polygon([
 [11.05243424, 124.38686371],
 [11.05197093, 124.38678861],
 [11.05174980, 124.38746721],
 [11.05225786, 124.38755840],
 [11.05244214, 124.38686907],
]).addTo(map);

casBuilding.on('click', function(){
  casBuilding.bindPopup("This is the CAS Building");
});

const miniForest =L.polygon([
[11.05223417, 124.38784003],
 [11.05217099, 124.38801169],
 [11.05184457, 124.38787222],
 [11.05162344, 124.38792050],
 [11.05157079, 124.38808143],
 [11.05132334, 124.38816190],
 [11.05117066, 124.38847303],
 [11.05111274, 124.38851595],
 [11.05114433, 124.38766301],
 [11.05127595, 124.38753963],
 [11.05191828, 124.38767910],
]).addTo(map);

miniForest.on('click', function(){
  miniForest.bindPopup("Mini Forest");
});


const oVal =L.polygon([
[11.05418745, 124.38739479],
 [11.05410321, 124.38789904],
 [11.05277119, 124.38760936],
 [11.05292914, 124.38703001],
]).addTo(map);

oVal.on('click', function(){
  oVal.bindPopup("Oval Field");
});

const bahayAlumnai =L.polygon([
 [11.05200515, 124.38626289],
 [11.05180508, 124.38620389],
 [11.05175770, 124.38643187],
 [11.05196303, 124.38648552],
]).addTo(map);

bahayAlumnai.on('click', function(){
  bahayAlumnai.bindPopup("Bahay Alumnai");
});


const cteBuilding =L.polygon([
[11.05267116, 124.38695490],
 [11.05260798, 124.38726872],
 [11.05247373, 124.38725799],
 [11.05254744, 124.38693881],
]).addTo(map);

cteBuilding.on('click', function(){
  cteBuilding.bindPopup("CTE Building");
});


/*const basketballCourt =L.polygon([
 [11.05399792, 124.38697904],
 [11.05396896, 124.38710511],
 [11.05375310, 124.38704342],
 [11.05376363, 124.38694149],
]).addTo(map); 

basketballCourt.on('click', function(){
  basketballCourt.bindPopup("Basketball Court");
}); */

/*const volleyBallCourt =L.polygon([
 [11.05409268, 124.38657939],
 [11.05404004, 124.38687444],
 [11.05376889, 124.38681543],
 [11.05381891, 124.38653648],
]).addTo(map); *?

/*volleyBallCourt.on('click', function(){
  volleyBallCourt.bindPopup("VolleyBall Court");
});*/


const gymNasium =L.polygon([
[11.05131018, 124.38664109],
 [11.05114433, 124.38764960],
 [11.05100744, 124.38762814],
 [11.05106272, 124.38727945],
 [11.05102061, 124.38727945],
 [11.05103903, 124.38717216],
 [11.05109168, 124.38718826],
 [11.05110748, 124.38713461],
 [11.05106272, 124.38712925],
 [11.05109695, 124.38699245],
 [11.05113907, 124.38699514],
 [11.05119172, 124.38669741],
 [11.05105483, 124.38667059],
 [11.05105746, 124.38659549],
 [11.05131544, 124.38664645],
]).addTo(map);

gymNasium.on('click', function(){
  gymNasium.bindPopup(" PIT Gymnasium");
});

const highbuilding =L.polygon([
[11.05108642, 124.38656330],
 [11.05093110, 124.38657939],
 [11.05091794, 124.38648015],
 [11.05098638, 124.38646942],
 [11.05094163, 124.38603759],
 [11.05101797, 124.38601613],
]).addTo(map);

highbuilding.on('click', function(){
  highbuilding.bindPopup("Highschool building");
});


const marker = L.marker([11.05144702397503, 124.38613759260512]).addTo(map);
marker.bindPopup("You are here");



</script>

</body>
</html>