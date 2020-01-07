<?php

$id = $_POST['id'];
$time = $_POST['time'];
$temphum = $_POST['data'];

$temp = substr($temphum, 0, 2);
$hum = substr($temphum, -2, 2);


$data = array(
	"id" => $id,
	"time" => $time,
	"humidity" => hexdec($hum),
	"temperature" => hexdec($temp)
);

$server_json = file_get_contents("data.json");

$decoded_json = json_decode($server_json);

$decoded_json[] = $data;

$json = json_encode($decoded_json);
file_put_contents("data.json", $json);