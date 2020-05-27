<?php



$id = $_POST["id"];
$id = addslashes($id);

// $id = 14158;


require_once("./signout.class.php");

$oSignout = new Signout();

$data = $oSignout->getBorrower($id);

header("Content-Type: application/json");

echo $data;

?>