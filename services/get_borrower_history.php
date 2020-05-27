<?php


$borrowers_id = $_POST["id"];
$borrowers_id = addslashes($borrowers_id);

// $borrowers_id = 12847;


require_once("./signout.class.php");

$oSignout = new Signout();

$data = $oSignout->getBorrowerHistory($borrowers_id);

header("Content-Type: application/json");

echo $data;

?>