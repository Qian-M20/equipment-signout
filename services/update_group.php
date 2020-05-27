<?php

$dataArray = json_decode($dataArray);

$data = array();


foreach ($dataArray as $key => $value) {
    $data[$key] = $value;
}

// echo $data;

$newName = $data[0];
$rowId = $data[1];


$newName = addslashes($newName);
$rowId = addslashes($rowId);


require_once("./signout.class.php");

$oSignout = new Signout();

$newData = $oSignout->updateGroup($newName, $rowId);

header("Content-Type: application/json");

echo $newData;
