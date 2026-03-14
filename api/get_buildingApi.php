<?php

$servername = "localhost";
$username = "root";
$password="";
$database = "pit_finder";



$mysqli = new mysqli($servername, $username, $password, $database);


/*if($mysqli->connect_error){
    die("Error Connecting database" . $mysqli->connect_error);
} else {
    echo "Connected database";
}*/
$sql = "SELECT * FROM buildings";
$result = $mysqli -> query($sql);

$building = [];

while($buildingTemp = $result->fetch_assoc()){
    $buildingId = $buildingTemp['id'];

   $polygonSql = "SELECT lat, lng FROM building_points WHERE building_id = $buildingId ORDER BY point_order";
   $polygonResult = $mysqli->query($polygonSql);

   //var_dump($polygonResult);



   $polygon = [];
   while($point = $polygonResult -> fetch_assoc()){
      $polygon[] = [$point['lat'], $point['lng']];
   }
    
   $roomSql = "SELECT * FROM rooms WHERE building_id = $buildingId";
   $roomResult = $mysqli->query($roomSql);

   $room = [];
   while($r = $roomResult -> fetch_assoc()){
     $room[] = [
         'name' => $r['name'],
         'details' => $r['details'],
         'floor' => $r['floor']
     ];
   }

   $buildings[] = [
         'buildingId' => $buildingId,
         'buildingName' => $buildingTemp['name'],
         'polygon' => $polygon,
          'description'=>$buildingTemp['description'] ?? null,
         'room' => $room
   ];
   }

   echo json_encode($buildings);


?>