<?php
ini_set('display_errors', '1');
ini_set('display_startup_errors', '1');
error_reporting(E_ALL);
require 'src/functions.php';
//echo "<pre>";


$location_name_list = sqlResult( 'SELECT location_name FROM `location` GROUP BY `location_name`;');


foreach ($location_name_list as $location) {
    $result = sqlResult('SELECT * FROM `location` WHERE location_name = "' . $location['location_name'] . '" ORDER BY `timestamp` DESC LIMIT 1;');
    //var_dump($result);
    echo 'Nazwa: '.$result[0]['location_name']."<br> Lat: ".
        $result[0]['latitude']."<br> Lon: ".
        $result[0]['longitude']."<br> Alt: ".
        $result[0]['altitude']."<br> Bearing: ".
        $result[0]['bearing']."<br> Last Timestamp: ".
        $result[0]['timestamp']."<br> <br>";
}












?>