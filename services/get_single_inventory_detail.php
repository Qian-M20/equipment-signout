<?php

$assets_desc = $_POST["asset_desc"];
$assets_desc = addslashes($assets_desc);


// $assets_desc = "AKG C 414B Condenser Mic1111";
require_once("./signout.class.php");

$oSignout = new Signout();

$data = $oSignout->getSingleInventoryDetail($assets_desc);

header("Content-Type: application/json");

echo $data;



?>