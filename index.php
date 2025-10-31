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
    <link rel="stylesheet" href="assets/js/Leaflet/leaflet.css">
    <title>GPS</title>
</head>
<body>
<!-- %2C = Co-ordinate separator -->
<!--
...bbox=[lon]%2C[lat]%2C[lon]%2C[lat]&amp;layer=mapnik&amp;marker=[lat]%2C[lon]"
-->
<div id="map"></div>

<form>
    <select id="tracks">
        <option value="">Brak Trasy</option>
        <?php
        $location_name_list = sqlResult( 'SELECT location_name FROM `location` GROUP BY `location_name`;');

        foreach ($location_name_list as $location_list) {
            echo '<option value="'.$location_list['location_name'].'" data-hashedValue="'.hash('sha1', $location_list['location_name']).'">'.$location_list['location_name'].'</option>';
        }

        ?>
    </select>
</form>

<!--<iframe-->
<!--        id="mapIFrame"-->
<!--        width="425"-->
<!--        height="350"-->
<!--        src="https://www.openstreetmap.org/export/embed.html?bbox=21.006272435188297%2C52.23041979309559%2C21.010751724243164%2C52.23182602420405&amp;layer=mapnik&amp;marker=52.23112291421792%2C21.00851207971573"-->
<!--        style="border: 1px solid black">-->
<!--</iframe>-->


<script src="assets/js/Leaflet/leaflet.js"></script>
<script src="assets/js/jquery.js"></script>
<script src="assets/js/mqtt.min.js"></script>
<script type="text/javascript">
    const url = 'wss://mqtt.kgtech.pl:8888'
    const options = {
        clean: true,
        connectTimeout: 4000
    }
    const client = mqtt.connect(url, options)


    $(document).ready(function () {
        client.on('connect', function () {
            console.log('Connected')
            // Subscribe to a topic

            })






        let map = L.map('map').setView([52.23152293421792, 21.005272435188297], 17);

        L.tileLayer('https://tile.openstreetmap.org/{z}/{x}/{y}.png', {
            maxZoom: 19,
            attribution: '&copy; <a href="http://www.openstreetmap.org/copyright">OpenStreetMap</a>'
        }).addTo(map);

        let redIcon = L.icon({
            iconUrl: 'assets/js/Leaflet/images/first-marker-icon.png',
            iconRetinaUrl: 'assets/js/Leaflet/images/first-marker-icon-2x.png',
            shadowUrl: 'assets/js/Leaflet/images/marker-shadow.png',
            iconSize: [25, 41],
            iconAnchor: [12, 41],
            popupAnchor: [1, -34],
            tooltipAnchor: [16, -28],
            shadowSize: [41, 41]
        });

        let lastMarker = L.marker([52.23152293421792, 21.005272435188297], {icon: redIcon, zIndexOffset: 1000}).addTo(map);

        let mapData = {
            markers: [],
            pathLine: null
        };

        let lastTopic = null;

        // let Interval = null;
        $(document).on('change', 'select[id="tracks"]', function () {
            if (lastTopic != null) {
                client.unsubscribe(lastTopic);
                lastTopic = null;
            }
            let locationName = $(this).find(":selected").val();
            var selectedOption = $(this).find(':selected');
            var hashedName = selectedOption.data('hashedvalue');
            // if (Interval) {
            //     clearInterval(Interval);
            //     Interval = null;
            // }
            if (locationName !== "") {
                LocationTracking(locationName, map, lastMarker, mapData)

                lastTopic = `gpslocation/location/${hashedName}`

                client.subscribe(lastTopic, function (err) {
                    if (!err) {
                        console.log(`Subscribed to ${lastTopic}`)
                    } else {
                        console.error('Subscription error:', err)
                    }

                client.on('message', function (topic, payload, packet) {
                    // Payload is Buffer
                    console.log(`Topic: ${topic}, Message: ${payload.toString()}, QoS: ${packet.qos}`)
                    const payloadData = JSON.parse(payload);

                    let lastMarkerCoords = lastMarker.getLatLng();
                    let lastMarkerPopup = lastMarker.getPopup()
                    let lastTime = lastMarker.timeDate
                    let marker = L.marker([lastMarkerCoords.lat, lastMarkerCoords.lng]).bindPopup(lastMarkerPopup).addTo(map)
                    marker.on('mouseover', function() { this.openPopup(); });
                    marker.on('mouseout', function() { this.closePopup(); });

                    mapData.markers.push(marker);


                    console.log(lastMarkerCoords.lat, lastMarkerCoords.lng, payloadData.latitude, payloadData.longitude)

                    let dist = distanceInBetween(lastMarkerCoords.lat, lastMarkerCoords.lng, payloadData.latitude, payloadData.longitude);
                    let tDiff = timeDiff(lastTime.time, payloadData.time);
                    let speed = (dist / tDiff).toFixed(2);
                    if (speed < 0) {
                        speed = speed * -1;
                    }
                    map.setView([payloadData.latitude, payloadData.longitude])
                    lastMarker.setLatLng([payloadData.latitude, payloadData.longitude]);
                    lastMarker.bindPopup("<b>Czas:</b><br>" + payloadData.time +"<br><b>Prędkość:</b> "+ speed + "m/s")
                    lastMarker.on('mouseover', function (e) {
                        this.openPopup();
                    });
                    lastMarker.on('mouseout', function (e) {
                        this.closePopup();
                    });

                    mapData.pathLine = L.polyline([[lastMarkerCoords.lat, lastMarkerCoords.lng], [payloadData.latitude, payloadData.longitude]], { color: 'blue' }).addTo(map);

                })
                })
            // Interval = setInterval(function () {
                //     LocationTracking(locationName, map, lastMarker, mapData);
                // }, 5000);
            }
        })
    })

    function LocationTracking (name, map, lastMarker, mapData) {
        $.post("ajax.php",
            {
                locationName: name,
                action: 'getLocation'
            },
            function (data) {
                if (typeof data.status !== 'undefined' && data.status === 'success') {

                    mapData.markers.forEach(m => map.removeLayer(m));
                    mapData.markers = [];

                    if (mapData.pathLine) {
                        map.removeLayer(mapData.pathLine);
                        mapData.pathLine = null;
                    }

                    let points = markerDataDump(data.data)

                    //console.log (points)
                    //////////////////////////////////////////////
                    points.forEach((p, i) => {
                        let [pointLat, pointLon] = p.coords;
                        let speed = 0;

                        if (i < points.length - 1) {

                            if (i === 0) {
                                //console.log(p.coords)
                                let next = points[i + 1];
                                let dist = distanceInBetween(pointLat, pointLon, next.coords[0], next.coords[1]);
                                let tDiff = timeDiff(p.time, next.time);
                                speed = (dist / tDiff).toFixed(2);
                                if (speed < 0) {
                                    speed = speed * -1;
                                }
                                map.setView([pointLat, pointLon])
                                lastMarker.setLatLng([pointLat, pointLon]);
                                lastMarker.bindPopup("<b>Czas:</b><br>" + p.time +"<br><b>Prędkość:</b> "+ speed + "m/s")
                                lastMarker.timeDate = {time: `${p.time}`}
                                lastMarker.on('mouseover', function (e) {
                                    this.openPopup();
                                });
                                lastMarker.on('mouseout', function (e) {
                                    this.closePopup();
                                });
                            }
                            else {

                                let next = points[i + 1];
                                let dist = distanceInBetween(pointLat, pointLon, next.coords[0], next.coords[1]);
                                let tDiff = timeDiff(p.time, next.time);
                                speed = (dist / tDiff).toFixed(2);
                                if (speed < 0) {
                                    speed = speed * -1;
                                }

                                let marker = L.marker([pointLat, pointLon]).addTo(map);
                                marker.bindPopup(
                                    "<b>Czas:</b><br>" + p.time + "<br><b>Prędkość:</b> "+ speed + "m/s"
                                )
                                marker.on('mouseover', function() { this.openPopup(); });
                                marker.on('mouseout', function() { this.closePopup(); });
                                mapData.markers.push(marker);
                            }
                        }


                    })
                    mapData.pathLine = L.polyline(points.map(p => p.coords), {color: 'blue'}).addTo(map);
                    /////////////////////////////////////////
                    // let lastLat = ;
                    // let lastLon = ;
                    // let lastPointTime = ;
                    // let currentLat = ;
                    // let currentLon = ;
                    // let stampedTime = ;
                    //
                    // let distanceBetween = distanceInBetween(lastLat, lastLon, currentLat, currentLon)
                    // let timeBetween = timeDiff(lastPointTime, stampedTime)
                    // let velocity = distanceBetween / timeBetween
                } else {
                    //console.log('fail');
                }
            });
    }

    function distanceInBetween(lat1, lon1, lat2, lon2) {
        const R = 6371;
        const toRad = deg => deg * Math.PI / 180;

        const dLat = toRad(lat2 - lat1);
        const dLon = toRad(lon2 - lon1);

        const a = Math.sin(dLat / 2) ** 2 +
            Math.cos(toRad(lat1)) * Math.cos(toRad(lat2)) *
            Math.sin(dLon / 2) ** 2;

        const c = 2 * Math.atan2(Math.sqrt(a), Math.sqrt(1 - a));

        const distanceKm = R * c;
        return distanceKm * 1000;
    }

    function parseTimestamp(ts) {
        return new Date(ts.replace(" ", "T"));
    }

    function timeDiff(t1, t2) {
        const date1 = parseTimestamp(t1);
        const date2 = parseTimestamp(t2);
        const diffMs = date2 - date1;
        return diffMs / 1000;
    }

    function markerDataDump (data) {
        let list = [];

        for (let i = 0; i < data.length; i++) {
        list.push( {coords: [
            data[i]['latitude'], data[i]['longitude']], time: data[i]['timestamp']
            });
        }
        return list
    }



</script>
</body>
</html>





