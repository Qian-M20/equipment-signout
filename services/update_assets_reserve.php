<?php

$asset_desc = $_POST['assets_reserve_desc'];
$reserve = $_POST['reserve'];
$category_id = $_POST['category'];
$notes = $_POST['reserve_notes'];
$replacement_cost = $_POST['replacement_cost'];

$asset_desc = addslashes($asset_desc);
$reserve = addslashes($reserve);
$category_id = addslashes($category_id);
$notes = addslashes($notes);
$replacement_cost = addslashes($replacement_cost);


require_once("./signout.class.php");

$oSignout = new Signout();

$data = $oSignout->updateAssetsReserve(
    $asset_desc,
    $reserve,
    $category_id,
    $notes,
    $replacement_cost
);

header("Content-Type: application/json");

echo $data;

?>