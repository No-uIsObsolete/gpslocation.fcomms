<?php
ini_set('display_errors', '1');
ini_set('display_startup_errors', '1');
error_reporting(E_ALL);
require 'src/functions.php';
//echo "<pre>";





$location_list = sqlResult( 'SELECT * FROM `location` WHERE location_name = "Andrzej" ORDER BY `timestamp` DESC LIMIT 1;');


foreach ($location_list as $location) {
    //var_dump($result);
    echo 'Nazwa: '.$location['location_name']."<br> Lat: ".
        $location['latitude']."<br> Lon: ".
        $location['longitude']."<br> Alt: ".
        $location['altitude']."<br> Bearing: ".
        $location['bearing']."<br> Last Timestamp: ".
        $location['timestamp']."<br> <br>";
}

?>
<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
</head>
<body>
<!-- %2C = Co-ordinate separator -->
<!--
https://www.openstreetmap.org/export/embed.html?bbox=[lon]%2C[lat]%2C[lon]%2C[lat]&amp;layer=mapnik&amp;marker=[lat]%2C[lon]"
-->

<iframe
        width="425"
        height="350"
        src="https://www.openstreetmap.org/export/embed.html?bbox=21.006267070770267%2C52.23042143591496%2C21.010746359825138%2C52.231827666971355&amp;layer=mapnik&amp;marker=52.23112455701123%2C21.0085067152977"
        style="border: 1px solid black">

</iframe>




<script src="assets/js/jquery.js"></script>
<script type="text/javascript">
</script>
</body>
</html>





