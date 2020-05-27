<?php

// $update = $_POST["update"];
// $password = $_POST["password"];
$id = $_POST["borrower_id"];
$first_name = $_POST["first_name"];
$last_name = $_POST["last_name"];
$student_id = $_POST["student_id"];
$dc_email = $_POST["dc_email"];
$other_email = $_POST["other_email"];
$email_confirmation = $_POST["email_confirmation"];
$strikes = $_POST["borrower_status"];
$phone = $_POST["phone"];
$programs_id = $_POST["program_name"];
$program_year = $_POST["program_year"];
// $programs_id = $_POST["program_id"];


$id = addslashes($id);
$first_name = addslashes($first_name);
$last_name = addslashes($last_name);
$student_id = addslashes($student_id);
$dc_email = addslashes($dc_email);
$other_email = addslashes($other_email);
$email_confirmation = addslashes($email_confirmation);
$strikes = addslashes($strikes);
$phone = addslashes($phone);
$programs_id = addslashes($programs_id);
$program_year = addslashes($program_year);




require_once("./signout.class.php");

$oSignout = new Signout();

$data = $oSignout->updateBorrower($id,$first_name,$last_name,$student_id,$dc_email,$other_email,$email_confirmation,$strikes,$phone,$programs_id,$program_year
);

header("Content-Type: application/json");

echo $data;
