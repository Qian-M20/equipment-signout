<?php



$room_id = $_POST["room_id"];
$room_id = addslashes($room_id);


require_once("./signout.class.php");

$oSignout = new Signout();

$data = $oSignout->getRoom($room_id);

header("Content-Type: application/json");

echo $data;



?>