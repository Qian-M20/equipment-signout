<?php


$barcode = $_POST["barcode"];
$barcode = addslashes($barcode);

// $barcode = 22070020777;


require_once("./signout.class.php");

$oSignout = new Signout();

$data = $oSignout->getAssetHistory($barcode);

header("Content-Type: application/json");

echo $data;

?>