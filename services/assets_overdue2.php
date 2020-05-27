<?php

require_once("./inc/connect_db.php");

// this file returns an xml file that confirms that the email address is updated and they agreement number the borrower has signed

$y = true;

if ($y) {
	
	
	$now_time = mktime()-(60*30);
	$query = "SELECT assets.asset_description, assets.notes, assets.serial_number, assets.barcode,
	assets_logged_out.out_time, assets_logged_out.due_time,
	borrowers.first_name, borrowers.last_name, borrowers.student_id, borrowers.dc_email, borrowers.other_email, borrowers.id
	FROM assets, assets_logged_out, borrowers
	WHERE assets.id = assets_logged_out.assets_id
	AND assets_logged_out.borrowers_id = borrowers.id
	AND assets_logged_out.in_time = '0'
	AND assets_logged_out.due_time < $now_time
	GROUP BY borrowers.id
	ORDER BY asset_description ";
	$mysql_result = mysql_query($query, $mysql_link);
	while($row = mysql_fetch_row($mysql_result)) {
		$assets_asset_description = stripslashes($row[0]);
		$assets_notes = htmlspecialchars(stripslashes($row[1]));
		$assets_serial_number = htmlspecialchars(stripslashes($row[2]));
		$assets_barcode = htmlspecialchars(stripslashes($row[3]));
		$assets_logged_out_out_time = date("D d M Y h:i:s A",htmlspecialchars(stripslashes($row[4])));
		$assets_logged_out_due_time = date("D d M Y h:i:s A",htmlspecialchars(stripslashes($row[5])));
		$borrowers_first_name = htmlspecialchars(stripslashes($row[6]));
		$borrowers_last_name = htmlspecialchars(stripslashes($row[7]));
		$borrowers_student_id = htmlspecialchars(stripslashes($row[8]));
		$borrowers_dc_email = htmlspecialchars(stripslashes($row[9]));
		$borrowers_other_email = htmlspecialchars(stripslashes($row[10]));
		$borrowers_id = htmlspecialchars(stripslashes($row[11]));
		if ($row[5] < mktime()) {
			$overdue = 1;
		} else {
			$overdue = 0;
		}
		
		
		$emails2 = "$borrowers_dc_email,$borrowers_other_email";
		$emails = "ray@netmarks.ca";
		$subject = "OVERDUE Equipment Signed Out. Please Return Now";
		$subject = "Equipment is out past the due date, please return ASAP";
		$message = "TO: $borrowers_first_name $borrowers_last_name - $borrowers_student_id\r\n";
		$message .= "Our records show you have the following overdue equipment:\r\n";
		$from = "From: ray@netmarks.ca";
		
		
		
		$query2 = "SELECT assets.asset_description, assets.notes, assets.serial_number, assets.barcode,
		assets_logged_out.out_time, assets_logged_out.due_time,
		borrowers.first_name, borrowers.last_name, borrowers.student_id, borrowers.dc_email, borrowers.other_email
		FROM assets, assets_logged_out, borrowers
		WHERE assets.id = assets_logged_out.assets_id
		AND assets_logged_out.borrowers_id = borrowers.id
		AND assets_logged_out.in_time = '0'
		AND assets_logged_out.due_time < $now_time
		AND borrowers_id = '$borrowers_id'
		GROUP BY borrowers.id
		ORDER BY asset_description ";
		$mysql_result2 = mysql_query($query2, $mysql_link);
		while($row2 = mysql_fetch_row($mysql_result2)) {
			$assets_asset_description = stripslashes($row2[0]);
			$assets_notes = htmlspecialchars(stripslashes($row2[1]));
			$assets_serial_number = htmlspecialchars(stripslashes($row2[2]));
			$assets_barcode = htmlspecialchars(stripslashes($row2[3]));
			$assets_logged_out_out_time = date("D d M Y h:i:s A",htmlspecialchars(stripslashes($row2[4])));
			$assets_logged_out_due_time = date("D d M Y h:i:s A",htmlspecialchars(stripslashes($row2[5])));
			
			$message .= "Asset signed out : $assets_asset_description\r\n";
			if ($assets_notes) {
				$message .= "Notes : $assets_notes\r\n";
			}
			if ($assets_serial_number) {
				$message .= "Serial Number : $assets_serial_number\r\n";
			}
			$message .= "Barcode : $assets_barcode\r\n";
			$message .= "Signed out at : $assets_logged_out_out_time\r\n";
			$message .= "Due Date/Time : $assets_logged_out_due_time\r\n";
			$message .= "\r\n";
			
		}
		
		//$message .= "Please return this equipment. If the equipment has been lost or stolen please contact ????\r\n";
		$message .= "Strikes are assigned for each day an item is late. An item is deemed to be late if it has not been returned within 30 minutes of the time it is due. The first strike is added after the 30 minute grace period has elapsed. After a strike, you cannot sign out any equipment until you've had an interview with the Associate Dean. Late fees may be assessed at the discretion of the Associate Dean. After two weeks, you will be invoiced for any missing equipment.\r\n";
		$message .= "Please let us know when we can expect the equipment to be returned. If you already returned the equipment, provide as many details as possible.\r\n";
		$message .= "Thank you. Here are our hours of operation:\r\n";
		$message .= "Monday to Friday, 9-5 PM closed for lunch 12:30 to 1:00 PM\r\n";
		$message .= "Sincerely,\r\n";
		$message .= "Jim Ferr, Technical Team Leader / Server Specialist\r\n";
		$message .= "905-721-2000 ext. 2645\r\n";
		$message .= "mailto:jim.ferr@durhamcollege.ca\r\n";

		
		
		$headers = "From: noreply@signout.mad.durhamcollege.ca" . "\r\n" .
		"Reply-To: noreply@signout.mad.durhamcollege.ca" . "\r\n" .
		"X-Mailer: PHP/" . phpversion();
		
		//mail($emails, $subject, $message, $headers);
		//"Bcc: signout@netmarks.ca" . "\r\n" .
		
		$message = nl2br($message);
		$headers = nl2br($headers);
		
		print("<p>TO: $emails2</p>
		<p>SUBJECT: $subject</p>
		<p>$message</p>
		<p>$headers</p><br /><br /><br />");
		/*
		print("<asset_types>
		<asset_description>$assets_asset_description</asset_description>
		<assets_notes>$assets_notes</assets_notes>
		<assets_serial_number>$assets_serial_number</assets_serial_number>
		<assets_barcode>$assets_barcode</assets_barcode>
		<assets_logged_out_out_time>$assets_logged_out_out_time</assets_logged_out_out_time>
		<assets_logged_out_due_time>$assets_logged_out_due_time</assets_logged_out_due_time>
		<borrowers_first_name>$borrowers_first_name</borrowers_first_name>
		<borrowers_last_name>$borrowers_last_name</borrowers_last_name>
		<borrowers_student_id>$borrowers_student_id</borrowers_student_id>
		<borrowers_dc_email>$borrowers_dc_email</borrowers_dc_email>
		<borrowers_other_email>$borrowers_other_email</borrowers_other_email>
		<overdue>$overdue</overdue>
		</asset_types>
		");
		*/
	
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

$dbh = null;

?>

