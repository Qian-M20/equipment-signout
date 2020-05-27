<?php


// $durham_college_number = $_POST["durham_college_number"];
$id = $_POST["room_id"];
$room_name = $_POST["room_name"];
$room_desc = $_POST["room_desc"];
$bottom_notes = $_POST["bottom_notes"];
$block_size = $_POST["block_size"];
$block_start = $_POST["block_start"];
$nodb = $_POST["nodb"];
$restr_day = $_POST["restr_day"];
$restr_night = $_POST["restr_night"];



// $durham_college_number = addslashes($durham_college_number);
$id = addslashes($id);
$room_name = addslashes($room_name);
$room_desc = addslashes($room_desc);
$bottom_notes = addslashes($bottom_notes);
$block_size = addslashes($block_size);
$block_start = addslashes($block_start);
$nodb = addslashes($nodb);
$restr_day = addslashes($restr_day);
$restr_night = addslashes($restr_night);


require_once("./signout.class.php");

$oSignout = new Signout();

$data = $oSignout->updateRoom($id,$room_name,$room_desc,$bottom_notes,$block_size,$block_start,$nodb,$restr_day,$restr_night
);

header("Content-Type: application/json");

echo $data;
