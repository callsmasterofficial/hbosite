<?php 
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
$json_data = file_get_contents('https://accessdashboard.live/api/script/6179569ae72eff324892f54b');
echo $json_data;

?>