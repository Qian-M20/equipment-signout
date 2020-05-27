<?php






require_once("./sample.class.php");

$oSignout = new Signout();

$data = $oSignout->updateFromSample();

header("Content-Type: application/json");

echo $data;
