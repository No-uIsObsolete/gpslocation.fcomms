<?php
require 'src/functions.php';
header('Content-Type: application/json; charset=UTF-8');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Headers: Content-Type');




$data = json_decode(file_get_contents("php://input"), true);

if (!isset($data['email']) || !isset($data['password'])) {
    echo json_encode(["success" => false, "message" => "Missing credentials"]);
    exit;
}

$userdata = getUserApi($data['email'], $data['password']);

if (is_array($userdata) && isset($userdata['username'], $userdata['email'])) {
    echo json_encode([
        "success" => true,
        "user" => [
            "username" => $userdata['username'],
            "email" => $userdata['email']
        ]
    ]);
} else {
    echo json_encode(["success" => false, "message" => "Invalid username or password"]);
}
?>