<?php



$errors = [];
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    //echo "The request is using the POST method <br>";
//var_dump($_POST);
//die;

    $username = trim($_POST['username']);
    $password = trim($_POST['password']);
    $firstname = trim($_POST['firstname']);
    $lastname = trim($_POST['lastname']);
    $email = trim($_POST['email']);
    $telephoneCode = trim($_POST['telephone1']);
    $telephoneNumber = trim($_POST['telephone2']);
    $telephone = trim($telephoneCode . $telephoneNumber);
    $passwordRepeat = trim($_POST['passwordRepeat']);


    /*
    if (strlen($username) == 0 || strlen($password) == 0 || strlen($firstname) == 0 || strlen($lastname) == 0 || strlen($email) == 0) {
        return false;
    }
    else {
        if ($passwordRepeat == $password) {
            echo "Nazwa Użytkownika: $username <br> Imie: $firstname <br> Nazwisko: $lastname <br> Hasło: $password <br> Email: $email <br> Telefon: $telephone";
        }
    }*/

    if (!usernameValidation($username)) {
        $errors['username'] = "Username is invalid";
    }

    if (!emailValidation($email)) {
        $errors['email'] = "Email is invalid";
    }
    if (!passwordRepeatValidation($password, $passwordRepeat)) {
        $errors['password'] = "Password isn't the same";
    }
    if (!mainValidation($username, $password, $email, $firstname, $lastname)) {
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
        addUser($username, $password, $email, $firstname, $lastname, $telephone);
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
<form>
        <label for="usernameInput">Username:</label> <br>
            <input name="username" id="usernameInput" type="text"><br>
        <label for="emailInput">E-mail:</label> <br>
            <input name="email" id="emailInput" type="email"><br>
        <label for="passwordInput">Password:</label> <br>
            <input name="password" id="passwordInput" type="password"><br>
        <label for="passwordRepeatIn">Repeat Password:</label> <br>
            <input name="passwordRepeat" id="passwordRepeatIn" type="password"><br> <br>
    <input type="submit" value="register">
</form>
</body>
</html>