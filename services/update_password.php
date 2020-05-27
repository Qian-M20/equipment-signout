<?php

$password = $_POST["password"];
$id = $_POST["password_id"];
// $first_name = $_POST["first_name"];
// $last_name = $_POST["last_name"];
// $student_id = $_POST["student_id"];
// $dc_email = $_POST["dc_email"];


$password = addslashes($password);
$id = addslashes($id);
// $first_name = addslashes($first_name);
// $last_name = addslashes($last_name);
// $student_id = addslashes($student_id);


require_once("./signout.class.php");

$oSignout = new Signout();

$data = $oSignout->updatePassword($password,$id
);

header("Content-Type: application/json");

echo $data;
