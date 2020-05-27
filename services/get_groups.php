<?php


require_once("./signout.class.php");

$oSignout = new Signout();

$data = $oSignout->getGroups();

header("Content-Type: application/json");

echo $data;

?>