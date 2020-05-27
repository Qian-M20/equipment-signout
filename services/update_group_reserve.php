<?php

$groups_id_array = json_decode($groups_id_array);
$groups_name_array = json_decode($groups_name_array);
$asset_desc = $_POST['asset_desc'];


// $data = array();

$data_id = array();
$data_name = array();


foreach ($groups_id_array as $key => $value) {
    $data_id[$key] = $value;
}

foreach ($groups_name_array as $key => $value) {
    $data_name[$key] = $value;
} 


// echo $data;

// $groups_id[] = $data[0];
// $groups_name[] = $data[1];
// $groups_desc = $data[2];

$groups_id = array_map('addslashes', $data_id);
$groups_name = array_map('addslashes', $data_name);
$groups_desc = addslashes($asset_desc);


require_once("./signout.class.php");

$oSignout = new Signout();

$data = $oSignout->updateGroupReserve(
    $groups_id,
    $groups_name,
    $groups_desc
);

header("Content-Type: application/json");

echo $data;

?>