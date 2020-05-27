<?php

require_once("./inc/connect_db.php");

// student only notification
//print("Assets Reminder.php<br>");


// this file returns an xml file that confirms that the email address is updated and they agreement number the borrower has signed

$y = true;

if ($y) {
	
	
	$now_time = mktime()-(60*30);
	
	$reminder_time_start = mktime() + $time_start;
	$reminder_time_end = mktime() + $time_end;
	
	//print(date("d F Y h:m:s<br>",$reminder_time_start)."<br>");
	//print(date("d F Y h:m:s<br>",$reminder_time_end)."<br>");
	
	$query = "SELECT assets.asset_description, assets.notes, assets.serial_number, assets.barcode,
	assets_logged_out.out_time, assets_logged_out.due_time,
	borrowers.first_name, borrowers.last_name, borrowers.student_id, borrowers.dc_email, borrowers.other_email, borrowers.id, borrowers.strikes
	FROM assets, assets_logged_out, borrowers
	WHERE assets.id = assets_logged_out.assets_id
	AND assets_logged_out.borrowers_id = borrowers.id
	AND assets_logged_out.in_time = '0'
	AND assets_logged_out.due_time > $reminder_time_start
	AND assets_logged_out.due_time < $reminder_time_end
	GROUP BY borrowers.id
	ORDER BY asset_description ";
	//print("$query<br>");
	$mysql_result = mysql_query($query, $mysql_link);
	while($row = mysql_fetch_row($mysql_result)) {
		
		
		$assets_asset_description = stripslashes($row[0]);
		$assets_notes = htmlspecialchars(stripslashes($row[1]));
		$assets_serial_number = htmlspecialchars(stripslashes($row[2]));
		$assets_barcode = htmlspecialchars(stripslashes($row[3]));
		$assets_logged_out_out_time = date("D d M Y h:i:s A",htmlspecialchars(stripslashes($row[4])));
		$assets_logged_out_due_time = date("D d M Y h:i:s A",htmlspecialchars(stripslashes($row[5])));
		$borrowers_first_name = strtoupper(htmlspecialchars(stripslashes($row[6])));
		$borrowers_last_name = strtoupper(htmlspecialchars(stripslashes($row[7])));
		$borrowers_student_id = htmlspecialchars(stripslashes($row[8]));
		$borrowers_dc_email = htmlspecialchars(stripslashes($row[9]));
		$borrowers_other_email = htmlspecialchars(stripslashes($row[10]));
		$borrowers_id = htmlspecialchars(stripslashes($row[11]));
		$borrowers_strikes = htmlspecialchars(stripslashes($row[12]));
		
		
		if ($row[5] < mktime()) {
			$overdue = 1;
		} else {
			$overdue = 0;
		}
		
		  		
  		if (preg_match("/@mycampus.durhamcollege.ca/i", $borrowers_dc_email) || preg_match("/@mycampus.uoit.ca/i", $borrowers_dc_email) ) {
			$student = TRUE;
		} else {
			$student = FALSE;
		}

		$emails2 = "$borrowers_dc_email,$borrowers_other_email";
		//$emails = "ray@netmarks.ca";
		$emails = "$borrowers_dc_email,$borrowers_other_email";

		$subject = "REMINDER: You have 2 hours to return the following equipment before a restriction is placed on your account.";
		$message = "";
		
		//$message = "To: $borrowers_first_name $borrowers_last_name - $borrowers_student_id\n";
		//$message .= "\n";
		//$message .= "Our records show you have the following overdue equipment:\n";
		//$message .= "\n";
		//$from = "From: ray@netmarks.ca";
		
		
		$message_equipment = "";
		$query2 = "SELECT assets.asset_description, assets.notes, assets.serial_number, assets.barcode,
		assets_logged_out.out_time, assets_logged_out.due_time,
		borrowers.first_name, borrowers.last_name, borrowers.student_id, borrowers.dc_email, borrowers.other_email
		FROM assets, assets_logged_out, borrowers
		WHERE assets.id = assets_logged_out.assets_id
		AND assets_logged_out.borrowers_id = borrowers.id
		AND assets_logged_out.in_time = '0'
    		AND borrowers_id = '$borrowers_id'
		GROUP BY borrowers.id
		ORDER BY asset_description ";
		$mysql_result2 = mysql_query($query2, $mysql_link);
		while($row2 = mysql_fetch_row($mysql_result2)) {
			$assets_asset_description = htmlspecialchars(stripslashes($row2[0]));
			$assets_notes = htmlspecialchars(stripslashes($row2[1]));
			$assets_serial_number = htmlspecialchars(stripslashes($row2[2]));
			$assets_barcode = htmlspecialchars(stripslashes($row2[3]));
			$assets_logged_out_out_time = date("D d M Y h:i A",htmlspecialchars(stripslashes($row2[4])));
			$assets_logged_out_due_time = date("D d M Y h:i A",htmlspecialchars(stripslashes($row2[5])));
			
			$message_equipment .= "Asset signed out : $assets_asset_description\n";
			if ($assets_notes) {
				$message_equipment .= "Notes : $assets_notes\n";
			}
			if ($assets_serial_number) {
				$message_equipment .= "Serial Number : $assets_serial_number\n";
			}
			$message_equipment .= "Barcode : $assets_barcode\n";
			$message_equipment .= "Signed out at : $assets_logged_out_out_time\n";
			$message_equipment .= "Due Date/Time : $assets_logged_out_due_time\n";
			$message_equipment .= "\n";
			
		}
		
		//$message .= "Please return this equipment. If the equipment has been lost or stolen please contact ????\n";
		
		
		
		$headers = "From: noreply@signout.mad.durhamcollege.ca" . "\n" .
		"Reply-To: noreply@signout.mad.durhamcollege.ca" . "\n" .
		"X-Mailer: PHP/" . phpversion();
		
		//"Bcc: signout@netmarks.ca" . "\n" .
		
		
			$message = "To: $borrowers_first_name $borrowers_last_name\n";
			$message .= "REMINDER: You have 2 hours to return the following equipment before a restriction is place on your account.\n";
			$message .= "\n";
			
			
			
			$message .= "Equipment Signed Out\n";
			$message .= "-----------------------------\n";
			$message .= "$message_equipment\n";
			
			
			
			
	
			$message .= "L120 hours of operation:\n";
			$message .= "Monday/Wednesday/Friday, 9-4 PM\n";
			$message .= "Tuesday/Thursday 9-11AM\n";


			$message .= "If you have already returned the equipment, please forward this email to Megan (see below) and provide as many details as possible as to when you returned the item. Thank you.\n";
			$message .= "\n";
			$message .= "Sincerely,\n";
			$message .= "Oliver Fernandez, Photography Support Specialist\n";
			$message .= "905-721-2000 ext. 2671\n";
			$message .= "oliver.fernandez@durhamcollege.ca\n";
			$message .= "\n";
			$message .= "Megan Pickell, Student Support Technician\n";
			$message .= "905-721-2000 ext. 3672\n";
			$message .= "megan.pickell@durhamcollege.ca\n";
			$message .= "\n";
			$message .= "L120: 905-721-2000 ext. 2514\n";
					
					
			//$emails = "ray@netmarks.ca";
			mail($emails, $subject, $message, $headers);
			
			
			print("<p><b>To: $emails2</b></p>
			<p>SUBJECT: $subject</p>
			<p>$message</p>
			<p>$headers</p><br /><br /><br />");
		
		
		
	
	}

	
} else {
	// send no access allowed
	header("Content-type: text/xml");

	print("<?xml version=\"1.0\" encoding=\"iso-8859-1\"?>
	<data>
		<student_id>$student_id</student_id>
		<error>Update Failed!</error>
	</data>
	");
}

//print("end file");

?>

