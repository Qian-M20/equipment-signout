<?php

require_once("./inc/connect_db.php");

if ($update == "Update") {
	//borrowers_id, asset_id, staff_id, day, month, year, update
	
	$borrowers_id = addslashes($borrowers_id);
	$asset_id = addslashes($asset_id);
	$staff_id = addslashes($staff_id);
	$day = addslashes($day);
	$month = addslashes($month);
	$year = addslashes($year);
	$loan_notes = addslashes($loan_notes);
	
	$due_time = mktime(12,0,0,$month,$day,$year);
	$out_time = mktime();
	
	$query = "INSERT INTO assets_logged_out
	SET assets_id = '$asset_id', 
	borrowers_id = '$borrowers_id', 
	out_time = '$out_time', 
	support_out = '$staff_id', 
	due_time = '$due_time', 
	loan_notes = '$loan_notes', 
	notes_out = '$loan_notes',
	action = '1' ";
	$mysql_result = mysql_query($query, $mysql_link);
	
	
	// put email code here
	
	// do email to person
	
	// get the persons info
	
	
	
	// get data on asset_id - 
	
	$query2 = "SELECT id, asset_description, barcode
	FROM assets
	WHERE id = '$asset_id' ";
	$mysql_result2 = mysql_query($query2, $mysql_link);
	while($row2 = mysql_fetch_row($mysql_result2)) {
		$asset_id = stripslashes($row2[0]);
		$asset_assets_description = stripslashes($row2[1]);
		$assets_barcode = stripslashes($row2[2]);
	}
	
	$query = "SELECT id, first_name, last_name, student_id, dc_email, other_email, agreement, email_confirmation, strikes, phone,
	programs_id, program_year
	FROM borrowers
	WHERE id = '$borrowers_id' ";
	//print("$query");
	$mysql_result = mysql_query($query, $mysql_link);
	while($row = mysql_fetch_row($mysql_result)) {
		$borrowers_id = stripslashes($row[0]);
		$borrowers_first_name = stripslashes($row[1]);
		$borrowers_last_name = stripslashes($row[2]);
		$borrowers_student_id = stripslashes($row[3]);
		$borrowers_dc_email = stripslashes($row[4]);
		$borrowers_other_email = stripslashes($row[5]);
		$borrowers_agreement = stripslashes($row[6]);
		$borrowers_email_confirmation = stripslashes($row[7]);
		$borrowers_strikes = stripslashes($row[8]);
		$borrowers_phone = stripslashes($row[9]);
		$borrowers_programs_id = stripslashes($row[10]);
		$borrowers_program_year = stripslashes($row[11]);
	}
	
	
	$to = "$borrowers_dc_email, $borrowers_other_email";
	//$to = "ray@netmarks.ca";
	$subject = "Equipment Signout Notification (LONG TERM)";
	$message = "Hello $borrowers_first_name $borrowers_last_name\r\n\r\nThe following equipment was signed out for an extended period:\r\n";
	$message .= "\r\n";
	

		
	$assets_logged_out_out_time2 = date("l d F Y h:i");
	$assets_logged_out_due_time2 = date("l d F Y h:i",$due_time);
	$assets_barcode = $assets_barcode;
	$assets_asset_description = $asset_assets_description;
	
	
	$message .= "Item: $assets_asset_description\r\n";
	$message .= "Barcode: $assets_barcode\r\n";
	$message .= "Signed out time: $assets_logged_out_out_time2\r\n";
	$message .= "Due time: $assets_logged_out_due_time2\r\n";
	$message .= "\r\n";
		

	
	
	
	$message .= "Please contact Megan, should you need any assistance.\r\n";
	$message .= "Megan Pickell\r\n";
	$message .= "905-721-2000 x3672\r\n";
	$message .= "megan.pickell@durhamcollege.ca\r\n";
	$message .= "Please do not reply to this email.\r\n";

	$headers = "From: noreply@signout.mad.durhamcollege.ca" . "\r\n" .
	"Reply-To: noreply@signout.mad.durhamcollege.ca" . "\r\n" .
	"Bcc: madmedialoans@durhamcollege.ca, megan.pickell@durhamcollege.ca" . "\r\n" .
	"X-Mailer: PHP/" . phpversion();
	
	mail($to, $subject, $message, $headers, "-fnoreply@signout.mad.durhamcollege.ca");
	
}





// this file returns an xml file that list the students/borrowers info.

$y = false;

header("Content-type: text/xml");

print("<?xml version=\"1.0\" encoding=\"iso-8859-1\"?>
<data>
");

$query = "SELECT id, first_name, last_name, student_id, dc_email, other_email, agreement, email_confirmation, strikes, phone,
programs_id, program_year
FROM borrowers
WHERE strikes = '0'
ORDER BY student_id ";
//print("$query");
$mysql_result = mysql_query($query, $mysql_link);
while($row = mysql_fetch_row($mysql_result)) {
	$borrowers_id = stripslashes($row[0]);
	$borrowers_first_name = stripslashes($row[1]);
	$borrowers_last_name = stripslashes($row[2]);
	$borrowers_student_id = stripslashes($row[3]);
	$borrowers_dc_email = stripslashes($row[4]);
	$borrowers_other_email = stripslashes($row[5]);
	$borrowers_agreement = stripslashes($row[6]);
	$borrowers_email_confirmation = stripslashes($row[7]);
	$borrowers_strikes = stripslashes($row[8]);
	$borrowers_phone = stripslashes($row[9]);
	$borrowers_programs_id = stripslashes($row[10]);
	$borrowers_program_year = stripslashes($row[11]);
	
	if ($borrowers_strikes > 3) {
		$borrowers_strikes = 3;
	}
	
	
	$borrowers_student_id = htmlspecialchars($borrowers_student_id);
	
	print("<student>
	<id>$borrowers_id</id>
	<student_id>$borrowers_student_id</student_id>
	<first_name>$borrowers_first_name</first_name>
	<last_name>$borrowers_last_name</last_name>
	<email>$borrowers_dc_email</email>
	<other_email>$borrowers_other_email</other_email>
	<agreement>$agreements_agreement</agreement>
	<agreement_signed>$borrowers_agreement</agreement_signed>
	<agreement_current_version>$max_agreement_id</agreement_current_version>
	<email_confirmation>$borrowers_email_confirmation</email_confirmation>
	<strikes>$borrowers_strikes</strikes>
	<phone>$borrowers_phone</phone>
	<id_combo>$borrowers_student_id $borrowers_last_name $borrowers_first_name</id_combo>
	<program_id>$borrowers_programs_id</program_id>
	<program_year>$borrowers_program_year</program_year>
	</student>
	");	
}


print("</data>
");
	


?>