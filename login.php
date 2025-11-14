<?php
ini_set('display_errors', '1');
ini_set('display_startup_errors', '1');
error_reporting(E_ALL);
require 'src/functions.php';
if (isset($_SESSION['user'])) {
    header('Location: index.php');
}
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email']);
    $password = trim($_POST['password']);

    $checkLogin = checkLogin($email, $password);

    if ($checkLogin = "Success") {
        $_SESSION['user'] = getUser($email, $password);
        header('Location: index.php');
    }

}

?>
<!DOCTYPE html>
<html lang="pl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="stylesheet" href="assets/js/Leaflet/leaflet.css">
    <title>GPS</title>
</head>
<body>
    <h1>GPS-Location</h1>
    <form action="/login.php" method="post">
        <label for="emailInput">E-mail:</label> <br>
        <input name="email" id="emailInput" type="email"> <br>
        <label for="passwordInput">Password:</label> <br>
        <input name="password" id="passwordInput" type="password"> <br>
        <?php if (isset($checkLogin)) {echo $checkLogin;} ?> <br>
        Brak Login'u? <a href="register.php">Register Here</a> <br> <br>
        <input type="submit" value="login">
    </form>
</body>
</html>