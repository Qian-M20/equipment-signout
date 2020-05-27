<?php


$barcode = $_POST["barcode"];

$barcode = addslashes($barcode);

// $barcode = "12WE12SS";

require_once("./signout.class.php");

$oSignout = new Signout();

$data = $oSignout->verifyBarcode($barcode);

header("Content-Type: application/json");

echo $data;

?>