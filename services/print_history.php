<?php



$student_id = $_POST["student_id"];
$student_id = addslashes($student_id);

// $student_id = 100428083;

require_once("./signout.class.php");

$oSignout = new Signout();

$data = $oSignout->getPrintHistory($student_id);

header("Content-Type: application/json");

echo $data;


?>

