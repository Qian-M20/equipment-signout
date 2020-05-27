<?php


$borrower = $_POST["borrower"];
$borrower = addslashes($borrower);

require_once("/signout.class.php");

$oSignout = new Signout();

$data = $oSignout->getBorrowers($borrower);

header("Content-Type: application/json");

echo $data;

?>