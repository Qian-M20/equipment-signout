<?php

$borrower_id = $_POST["faculty_id"];
$asset_id = $_POST["asset_id"];
$date = $_POST["calendar"];
$support_out_id = $_POST["signout_staff_id"];
$loan_notes = $_POST["signout_notes"];


$borrower_id = addslashes($borrower_id);
$asset_id = addslashes($asset_id);
$date = addslashes($date);
$support_out_id = addslashes($support_out_id);
$loan_notes = addslashes($loan_notes);


require_once("./signout.class.php");

$oSignout = new Signout();

$data = $oSignout->insertLongTermSignout(
    $borrower_id,
    $asset_id,
    $date,
    $support_out_id,
    $loan_notes
);

header("Content-Type: application/json");

echo $data;