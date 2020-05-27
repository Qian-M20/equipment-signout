<?php


$id = $_POST["id"];
$id = addslashes($id);

// $id = 1602;

require_once("./signout.class.php");

$oSignout = new Signout();

$data = $oSignout->getAsset($id);

header("Content-Type: application/json");

echo $data;

?>