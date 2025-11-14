<?php
require '../src/functions.php';
header('Access-Control-Allow-Origin: *');




if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email']);
    $password = trim($_POST['password']);

    if ($email != "" && $password != "") {
        $user = getUser($email, $password);

        if ($user) {
            echo json_encode([
                'success' => true,
                'user' => $user
            ]);
        } else {
            echo json_encode([
                'success' => false,
                'message' => 'Użytkownik nie znaleziony lub złe dane logowania'
            ]);
        }
    } else {
        echo json_encode([
            'success' => false,
            'message' => 'Wszystkie pola muszą być wypełnione'
        ]);
    }
} else {
    echo json_encode([
        'success' => false,
        'message' => 'Błąd metody żądania'
    ]);
}


?>