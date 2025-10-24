<?php
ini_set('display_errors', '1');
ini_set('display_startup_errors', '1');
error_reporting(E_ALL);
require 'src/functions.php';
//echo "<pre>";





//$location_name_list = sqlResult( 'SELECT location_name FROM `location` GROUP BY `location_name`;');


//foreach ($location_name_list as $location) {
//    $result = sqlResult('SELECT * FROM `location` WHERE location_name = "' . $location['location_name'] . '" ORDER BY `timestamp` DESC LIMIT 1;');
//    //var_dump($result);
//    echo 'Nazwa: '.$result[0]['location_name']."<br> Lat: ".
//        $result[0]['latitude']."<br> Lon: ".
//        $result[0]['longitude']."<br> Alt: ".
//        $result[0]['altitude']."<br> Bearing: ".
//        $result[0]['bearing']."<br> Last Timestamp: ".
//        $result[0]['timestamp']."<br> <br>";
//}

?>
<!DOCTYPE html>
<!--
 @license
 Copyright 2025 Google LLC. All Rights Reserved.
 SPDX-License-Identifier: Apache-2.0
-->

<html>
<head>
    <title>Add a Map</title>

    <link rel="stylesheet" type="text/css" href="./style.css" />
    <script type="module" src="./index.js"></script>
    <!-- prettier-ignore -->
    <script>(g=>{var h,a,k,p="The Google Maps JavaScript API",c="google",l="importLibrary",q="__ib__",m=document,b=window;b=b[c]||(b[c]={});var d=b.maps||(b.maps={}),r=new Set,e=new URLSearchParams,u=()=>h||(h=new Promise(async(f,n)=>{await (a=m.createElement("script"));e.set("libraries",[...r]+"");for(k in g)e.set(k.replace(/[A-Z]/g,t=>"_"+t[0].toLowerCase()),g[k]);e.set("callback",c+".maps."+q);a.src=`https://maps.${c}apis.com/maps/api/js?`+e;d[q]=f;a.onerror=()=>h=n(Error(p+" could not load."));a.nonce=m.querySelector("script[nonce]")?.nonce||"";m.head.append(a)}));d[l]?console.warn(p+" only loads once. Ignoring:",g):d[l]=(f,...n)=>r.add(f)&&u().then(()=>d[l](f,...n))})
        ({key: "YOUR_API_KEY", v: "weekly"});</script>
</head>
<body>

<!-- The map, centered at Uluru, Australia. -->
<gmp-map center="-25.344,131.031" zoom="4" map-id="DEMO_MAP_ID">
</gmp-map>

</body>
</html>
