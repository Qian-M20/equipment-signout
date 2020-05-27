<?php

require_once("../inc/connect_db.php");



/*     **********     this file removes users who are students and not the room admin.     **********     */

/*     **********     Faculty are left and not deleted because of their email address     **********     */

/*     **********     Need to manually remove part-time faculty.     **********     */

/*     **********     Students that have equipment out are not deleted.     **********     */

/*     **********     Remember to BACKUP database FIST     **********     */

$y = false;



$xx = 1;
$query = "SELECT id, first_name, last_name, student_id, dc_email, other_email, agreement, email_confirmation, strikes, phone,
programs_id, program_year
FROM borrowers
WHERE id > 1
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
	
	$data = preg_match("/@mycampus.durhamcollege.ca/",$borrowers_dc_email);
	
	//if (preg_match("/@mycampus.durhamcollege.ca/",$borrowers_dc_email) || preg_match("/@dcmail.ca/",$borrowers_dc_email) || preg_match("/@mycampus.uoit.ca/",$borrowers_dc_email) ) {
		
		// do they have anything borrowed and out
		$has_out = false;
		$query2 = "SELECT id
		FROM assets_logged_out
		WHERE in_time = '0'
		AND borrowers_id = '$borrowers_id' ";
		//print("$query2<br />");
		$mysql_result2 = mysql_query($query2, $mysql_link);
		while($row2 = mysql_fetch_row($mysql_result2)) {
			$has_out = true;
		}
		
		if ($has_out) {
			// do not delete borrower
			print("$borrowers_student_id - $borrowers_first_name $borrowers_last_name: Item Logged Out [$borrowers_dc_email]<br />");
		} else {
			// delete borrower
			// do I need to do further checks
			//print("<b>$borrowers_student_id - $borrowers_first_name $borrowers_last_name: Is a student [$borrowers_dc_email]</b><br />");
			$query3 = "DELETE FROM borrowers
			WHERE id = '$borrowers_id' ";
			//print("$xx $borrowers_dc_email :: $query3<br />");
			$mysql_result3 = mysql_query($query3, $mysql_link);
		}
		++$xx;
	//} else {
		//print("$borrowers_student_id - $borrowers_first_name $borrowers_last_name: NOT student [$borrowers_dc_email]<br />");
	//}
	
}



?>