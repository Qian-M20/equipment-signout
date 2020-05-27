<?php

$option = $_POST["option"];

// $option = 3;

require_once("./signout.class.php");

$oSignout = new Signout();

$data = $oSignout->getInventory($option);

header("Content-Type: application/json");

echo $data;

?>