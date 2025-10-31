<?php
ini_set('display_errors', '1');
ini_set('display_startup_errors', '1');
error_reporting(E_ALL);
require 'src/functions.php';
header('Access-Control-Allow-Origin: *');
require __DIR__ . '/vendor/bluerhinos/phpmqtt/phpMQTT.php';

use Bluerhinos\phpMQTT;

$server = '195.201.38.255';
$port = 1883;
$client_id = 'phpPublisher_' . uniqid();



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
        $mqtt = new phpMQTT($server, $port, $client_id);

        if ($mqtt->connect(true, NULL)) {
            $topic = "gpslocation/location/" . hash('sha1', $name);

            $payload = json_encode([
                'name' =>  $name,
                'latitude' => $lat,
                'longitude' => $lon,
                'altitude' => $alt,
                'bearing' => $bearing,
                'time' => $time
            ]);

            $mqtt->publish($topic, $payload, 0);
            $mqtt->close();


            echo "SUCCESS_MQTT";
        } else {
            echo "MQTT_CONNECT_FAILED";
        }

        echo "SUCCESS";
    } else {
        echo "MISSING_FIELDS";
    }
} else {
    echo "INVALID_REQUEST";
}
?>