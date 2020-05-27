<?php


$option = $_POST["option"];
$option = addslashes($option);

// $option = 1;

require_once("./signout.class.php");

$oSignout = new Signout();

$data = $oSignout->getAssetsLoggedOut($option);

header("Content-Type: application/json");

echo $data;

?>