<?php
ini_set('display_errors', '1');
ini_set('display_startup_errors', '1');
error_reporting(E_ALL);
require 'src/functions.php';
//echo "<pre>";





//$location_list = getLocation('Andrzej');
//
//
//foreach ($location_list as $location) {
//    var_dump($result);
//    echo 'Nazwa: '.$location['location_name']."<br> Lat: ".
//        $location['latitude']."<br> Lon: ".
//        $location['longitude']."<br> Alt: ".
//        $location['altitude']."<br> Bearing: ".
//        $location['bearing']."<br> Last Timestamp: ".
//        $location['timestamp']."<br> <br>";
//}



?>
<!DOCTYPE html>
<html lang="pl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>GPS</title>
</head>
<body>
<!-- %2C = Co-ordinate separator -->
<!--
...bbox=[lon]%2C[lat]%2C[lon]%2C[lat]&amp;layer=mapnik&amp;marker=[lat]%2C[lon]"
-->


<form>
    <select id="tracks">
        <option value="">Wybierz Trasę:</option>
        <?php
        $location_name_list = sqlResult( 'SELECT location_name FROM `location` GROUP BY `location_name`;');

        foreach ($location_name_list as $location_list) {
            echo '<option value="'.$location_list['location_name'].'">'.$location_list['location_name'].'</option>';
        }

        ?>
    </select>
</form>

<iframe
        id="mapIFrame"
        width="425"
        height="350"
        src=""
        style="border: 1px solid black">

</iframe>




<script src="assets/js/jquery.js"></script>
<script type="text/javascript">
    $(document).ready(function () {
        $(document).on('change', 'select[id="tracks"]', function () {
            let locationName = $(this).find(":selected").val();
            if (locationName !== "") {

                function LocationTracking () {
                    $.post("ajax.php",
                        {
                            locationName: locationName,
                            action: 'getLocation'
                        },
                        function (data) {
                            if (typeof data.status !== 'undefined' && data.status === 'success') {

                                console.log(data)

                                // let lastLat = data.l_Lat
                                // let lastLon = data.l_Lon
                                // let lastAlt = data.l_Alt
                                let currentlat = data.data[0]['latitude']
                                let currentlon = data.data[0]['longitude']
                                // let alt = data.c_Alt
                                $('iframe[id="mapIFrame"]').attr('src', 'https://www.openstreetmap.org/export/embed.html?bbox='
                                    + currentlon + '%2C' + currentlat + '%2C' + currentlon + '%2C'
                                    + currentlat + 'l&amp;layer=mapnik&amp;marker=' + currentlat + '%2C' + currentlon);

                            } else {
                                console.log('fail');
                            }
                        });
                }



                //var Interval = setInterval(LocationTracking, 5000)


                // $(document).on('click', 'select[id="tracks"]', function () {
                //     clearInterval(Interval)
                // })
            }
        })
    })

</script>
</body>
</html>





