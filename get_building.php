<?php

$servername = "localhost";
$username = "root";
$password = "";
$db = "pit_finder";


$mysqli = new mysqli($servername, $username, $password, $db);


if(mysqli -> connect_error){
    die("CANT CONNECT TO DATABASE". $mysqli->connect_error);
}

$sql = "SELECT * FROM buildings";
$result = mysqli->query(sql);


$buildings = [];

while($building = $result -> fetch_assoc()){
    $buildingId = $building['id'];

    $polygonSql = "SELECT lat, lng FROM building_points WHERE building_id = buildingId ORDER BY";
    $polygonResult = mysqli ->query(polygonSql);

    $polygon = [];
    while($point = $polygon->fetch_assoc()){
        $polygon[] = [$point['lat'], $point['lng']];
    }

    $roomSql = "SELECT * FROM rooms where building_id = buildingId";
    $roomResult = mysqli -> query(roomSql);

    $rooms = [];
    while($room = $roomResult -> fetch_assoc()){
        $rooms[] = [
            'name' => $room['name'];
            'details' => $room['details'];
            'floor' => $room['floor']
        ]
    }
}

?>