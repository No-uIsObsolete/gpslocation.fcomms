<?php
ini_set('display_errors', '1');
ini_set('display_startup_errors', '1');
error_reporting(E_ALL);
require 'src/functions.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $data = ['status' => 'success'];
    $action = $_POST['action'];


    switch ($action) {
        case 'getLocation':
            if (!empty($_POST['locationName'])) {
            $result = getLocationInfo($_POST['locationName']);

            $data['data'] = $result;
            }
            else {
                $data['status'] = 'error';
            }
            break;
    }
    header('Content-Type: application/json; charset=utf-8');
    echo json_encode($data);
}
?>