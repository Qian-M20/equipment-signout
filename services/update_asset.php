<?php


// $durham_college_number = $_POST["durham_college_number"];
$id = $_POST["asset_id"];
$asset_description = $_POST["asset_desc"];
$serial_number = $_POST["serial_number"];
$notes = $_POST["notes"];
$actions_id = $_POST["actions"];
$barcode = $_POST["barcode"];
$categories_id = $_POST["categories"];
$info = $_POST["info"];

// $durham_college_number = addslashes($durham_college_number);
$id = addslashes($id);
$asset_description = addslashes($asset_description);
$serial_number = addslashes($serial_number);
$notes = addslashes($notes);
$actions_id = addslashes($actions_id);
$barcode = addslashes($barcode);
$categories_id = addslashes($categories_id);
$info = addslashes($info);




require_once("./signout.class.php");

$oSignout = new Signout();

$data = $oSignout->updateAsset($id,$asset_description,$serial_number,$notes,$actions_id,$barcode,$categories_id,$info
);

header("Content-Type: application/json");

echo $data;
