<?php
ini_set('display_errors', '1');
ini_set('display_startup_errors', '1');
error_reporting(E_ALL);
require 'src/functions.php';
header('Access-Control-Allow-Origin: *');


if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = $_POST['name'] ?? '';
    $lat = $_POST['latitude'] ?? '';
    $lon = $_POST['longitude'] ?? '';
    $alt = $_POST['altitude'] ?? '';
    $bearing = $_POST['bearing'] ?? '';
    $time = $_POST['time'] ?? '';

    if ($name && $lat && $lon && $alt && $bearing && $time) {
        sqlInsert('location', [
            'location_name' => $name,
            'latitude' => $lat,
            'longitude' => $lon,
            'altitude' => $alt,
            'bearing' => $bearing,
            'timestamp' => $time
        ]);
        echo "SUCCESS";
    } else {
        echo "MISSING_FIELDS";
    }
} else {
    echo "INVALID_REQUEST";
}
?>