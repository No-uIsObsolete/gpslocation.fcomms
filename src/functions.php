<?php
ini_set('display_errors', '1');
ini_set('display_startup_errors', '1');
error_reporting(E_ALL);

session_start();


function connect()
{
    return mysqli_connect("195.201.38.255", "afura_gps_location", "?a)y*uO4R+H!FBtW", "afura_gps_location");
}

function sqlInsert($table, $params)
{
    $con = connect();
    $keys = array_keys($params);
    $values = array_values($params);


    $query = "INSERT INTO $table ( " . implode(", ", $keys) . " ) VALUES ( '" . implode("', '", $values) . "');";
    $sql = mysqli_query($con, $query);

}

function sqlUpdate($table, $params, $target, $targetData)
{
    $con = connect();


    $query = "UPDATE $table
                SET $params
                WHERE $target = '$targetData'";
    $sql = mysqli_query($con, $query);

}

function sqlResult($query)
{
    $con = connect();
    $sql = mysqli_query($con, $query);
    $result = array();
    while ($row = $sql->fetch_assoc()) {
        $result[] = $row;
    }
    return $result;
}

function getLocation($locationName) {
    $result = sqlResult( 'SELECT * FROM `location` WHERE location_name = "'.$locationName.'" ORDER BY `timestamp` DESC LIMIT 1;');
    return $result;
}

function getLocationInfo($locationName) {
    $result = sqlResult( 'SELECT * FROM `location` WHERE location_name = "'.$locationName.'" ORDER BY `timestamp` DESC LIMIT 100;');
    return $result;
}


?>