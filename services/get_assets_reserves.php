<?php


require_once("./signout.class.php");

$oSignout = new Signout();

$data = $oSignout->getAssetsReserves();

header("Content-Type: application/json");

echo $data;

?>