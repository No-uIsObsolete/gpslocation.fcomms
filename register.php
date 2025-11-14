<?php
ini_set('display_errors', '1');
ini_set('display_startup_errors', '1');
error_reporting(E_ALL);
require 'src/functions.php';
if (isset($_SESSION['user'])) {
    header('Location: index.php');
}
$errors = [];
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $username = trim($_POST['username']);
    $email = trim($_POST['email']);
    $password = trim($_POST['password']);
    $passwordRepeat = trim($_POST['passwordRepeat']);

    if (!usernameValidation($username)) {
        $errors['username'] = "Username is invalid";
    }

    if (!emailValidation($email)) {
        $errors['email'] = "Email is invalid";
    }
    if (!passwordRepeatValidation($password, $passwordRepeat)) {
        $errors['password'] = "Password isn't the same";
    }
    if (!mainValidation($username, $email, $password)) {
        $errors['main'] = "<p>Fill all the required fields</p>";
    }
    if (!passwordValidation($password)) {
        $errors['passwordLength'] = "Password should be at least 8 characters";
    }
    if (!specialCharactersValidation($password)) {
        $errors['passwordSpecialCharacters'] = "Password should contain at least one special character";
    }
    if (!uppercaseCharactersValidation($password)) {
        $errors['passwordUppercase'] = "Password should contain at least one uppercase character";
    }
    if (!lowercaseCharactersValidation($password)) {
        $errors['passwordLowercase'] = "Password should contain at least one lowercase character";
    }
    if (!numbersValidation($password)) {
        $errors['passwordNumber'] = "Password should contain at least one number";
    }

    if (!checkUsername($username)) {
        $errors['usernameInUse'] = "Username already exists";
    }

    if (!checkEmail($email)) {
        $errors['emailInUse'] = "Email already exists";
    }


    if (empty($errors))  {
        addUser($username, $email, $password);
        header('Location: login.php');
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
<form action="/register.php" method="post">
    <?php if (isset($errors['main'])){
        echo $errors['main'];
    } ?>
        <label for="usernameInput">Username:</label> <br>
            <input name="username" id="usernameInput" type="text" value="<?php echo $_POST['username']??'';?>">
    <?php if (isset($errors['username'])){
        echo $errors['username'];
    }
    else {
        if (isset($errors['usernameInUse'])){echo $errors['usernameInUse'];}
    }?><br>
        <label for="emailInput">E-mail:</label> <br>
            <input name="email" id="emailInput" type="email" value="<?php echo $_POST['email']??'';?>">
    <?php if (isset($errors['email'])){
        echo $errors['email'];
    }
    else {
        if (isset($errors['emailInUse'])){echo $errors['emailInUse'];}
    }?><br>
        <label for="passwordInput">Password:</label> <br>
            <input name="password" id="passwordInput" type="password" value="<?php echo $_POST['password']??'';?>">
    <?php if (isset($errors['passwordLength'])){
        echo $errors['passwordLength'];
    } else {
        if (isset($errors['passwordLowercase'])){ echo $errors['passwordLowercase'];}
        else {
            if (isset($errors['passwordUppercase'])){ echo $errors['passwordUppercase'];}
            else {
                if (isset($errors['passwordNumber'])){ echo $errors['passwordNumber'];}
                else {
                    if (isset($errors['passwordSpecialCharacters'])){ echo $errors['passwordSpecialCharacters'];}
                }
            }


        }
    }?><br>
        <label for="passwordRepeatIn">Repeat Password:</label> <br>
            <input name="passwordRepeat" id="passwordRepeatIn" type="password" value="<?php echo $_POST['passwordRepeat']??'';?>">
    <?php if (isset($errors['password'])){
        echo $errors['password'];
    } ?><br> <br>
    <input type="submit" value="register">
    Masz już konto? <a href="login.php">Zaloguj Się</a> <br>
</form>
</body>
</html>