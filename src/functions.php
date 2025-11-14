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

function getUser($user, $password)
{
    $hashedPassword = hash('sha256', $password);
    $query = "SELECT * FROM user where (username = '$user' or email = '$user') AND password = '$hashedPassword' LIMIT 1";
    $result = sqlResult($query);
    if (isset($result[0])) {
        return $result[0];
    }
}

function checkUsername($username)
{

    $query = "SELECT username FROM user WHERE username = '$username'";
    $result = sqlResult($query);
    if (isset ($result[0])) {
        if ($result[0]['username'] == $username) {
            return false;
        }

    } else {
        return true;
    }

}

function checkEmail($email)
{
    $query = "SELECT email FROM user WHERE email = '$email'";
    $result = sqlResult($query);
    if (isset ($result[0])) {
        if ($result[0]['email'] == $email) {
            return false;
        }

    } else {
        return true;
    }
}

function checkLogin($user, $password)
{

    $hashedPassword = hash('sha256', $password);
    $query = "SELECT id, username, email, password, status FROM user where (username = '$user' or email = '$user') AND password = '$hashedPassword'";
    $result = sqlResult($query);

    if (isset($result[0])) {
        if ($result[0]['status'] == 1) {
            return "Success";
        } else {
            return "The user has not been activated or has been banned.";
        }
    } else {
        return "The parameters are wrong or this user does not exist.";
    }
}

function usernameValidation($username)
{
    if (str_contains($username, ' ')) {
        return false;
    } else {
        return true;
    }
}

function emailValidation($email)
{
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        return false;
    } else {
        return true;
    }

}
function uppercaseCharactersValidation($password)
{
    $uppercase = preg_match('@[A-Z]@', $password);
    if (!$uppercase) {
        return false;
    } else {
        return true;
    }
}

function lowercaseCharactersValidation($password)
{
    $lowercase = preg_match('@[a-z]@', $password);
    if (!$lowercase) {
        return false;
    } else {
        return true;
    }
}

function numbersValidation($password)
{
    $number = preg_match('@[0-9]@', $password);
    if (!$number) {
        return false;
    } else {
        return true;
    }
}

function specialCharactersValidation($password)
{
    $specialChars = '!@#$%^&*()-_=+[{]};:\'",<.>/?\\|';
    if (strpbrk($password, $specialChars) === false) {
        return false;
    } else {
        return true;
    }
}


function passwordValidation($password)
{
    if (strlen($password) > 7) {

        return true;

    } else {
        return false;
    }
}


function passwordRepeatValidation($password, $passwordRepeat)
{
    if ($passwordRepeat != $password) {
        return false;
    } else {
        return true;
    }
}

function mainValidation($username, $email, $password)
{
    if (strlen($username) == 0 ||  strlen($email) == 0 || strlen($password) == 0)  {
        return false;
    } else {
        return true;
    }
}

function addUser($user, $email, $password)
{
    $hashedPassword = hash('sha256', $password);

    $table = 'user';
    sqlInsert($table, ['username' => $user, 'email' => $email, 'password' => $hashedPassword, 'status' => 1]);
}



function getUserApi($user, $password)
{
    $hashedPassword = hash('sha256', $password);
    $query = "SELECT * FROM user where (email = '$user') AND password = '$hashedPassword' AND status = '1' LIMIT 1";
    $result = sqlResult($query);
    if (isset($result[0])) {
        return [
            'id' => $result[0]['id'],
            'username' => $result[0]['username'],
            'email' => $result[0]['email']
        ];
    } else {
        return null;
    }
}

function logout()
{
    session_unset();
    header('Location: login.php');
}
?>