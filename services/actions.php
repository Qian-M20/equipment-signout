<?php

// GET LIST OF DEPARTMENTS

require_once("./signout.class.php");

$oSignOut = new Signout();

$data = $oSignOut->getActions();

header("Content-Type: application/json");

echo $data;

?>