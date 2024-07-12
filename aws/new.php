<?php

$IP = $_SERVER['REMOTE_ADDR'];

$result = json_decode(file_get_contents('https://api.ip2location.com/v2/?key=P95AOLU23B&ip='.$IP.'&package=WS25&format=json&addon=country,region,city&lang=en'));

echo "<pre>"; print_r($result);