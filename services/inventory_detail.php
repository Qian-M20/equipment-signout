<?phprequire_once("./inc/connect_db.php");// this file returns an xml file that confirms that the email address is updated and they agreement number the borrower has signed$y = true;$do_email = false;$agreement_id = addslashes($agreement_id);$student_id = addslashes($student_id);$other_email = addslashes($other_email);if ($_POST['description']) {	$description = addslashes($_POST['description']);} else {	$description = addslashes($_GET['description']);}$query2 = "SELECT id, nameFROM actionsORDER BY id ";$mysql_result2 = mysql_query($query2, $mysql_link);while($row2 = mysql_fetch_row($mysql_result2)) {	$id = stripslashes($row2[0]);	$name = stripslashes($row2[1]);	$action[$id] = $name;}		$query2 = "SELECT id, nameFROM categoriesORDER BY id ";$mysql_result2 = mysql_query($query2, $mysql_link);while($row2 = mysql_fetch_row($mysql_result2)) {	$id = stripslashes($row2[0]);	$name = stripslashes($row2[1]);	$categories[$id] = $name;}		if ($y) {	header("Content-type: text/xml");		print("<?xml version=\"1.0\" encoding=\"iso-8859-1\"?>	<inventory_detail>	");		$query = "SELECT id, serial_number, durham_college_number, notes, actions_id, barcode, categories_id info	FROM assets	WHERE asset_description = '$description' 	ORDER BY actions_id ";	//print("$query");	$mysql_result = mysql_query($query, $mysql_link);	while($row = mysql_fetch_row($mysql_result)) {		$id = stripslashes($row[0]);		$serial_number = htmlspecialchars(stripslashes($row[1]));		$durham_college_number = htmlspecialchars(stripslashes($row[2]));		$notes = htmlspecialchars(stripslashes($row[3]));		$action_id = htmlspecialchars(stripslashes($row[4]));		$barcode = htmlspecialchars(stripslashes($row[5]));		$categories_id = htmlspecialchars(stripslashes($row[6]));		$info = htmlspecialchars(stripslashes($row[7]));				$action_name = $action[$action_id];		$categories_name = $categories[$categories_id];		unset($student_id, $last_name, $first_name, $signed_out, $borrower, $scan_date);				$query2 = "SELECT borrowers.student_id, borrowers.last_name, borrowers.first_name, assets_logged_out.out_time		FROM assets_logged_out, borrowers		WHERE assets_logged_out.borrowers_id = borrowers.id		AND assets_logged_out.assets_id = '$id'		AND assets_logged_out.in_time = '0' ";		//print("$query2<br />");		$mysql_result2 = mysql_query($query2, $mysql_link);		while($row2 = mysql_fetch_row($mysql_result2)) {			$student_id = stripslashes($row2[0]);			$last_name = stripslashes($row2[1]);			$first_name = stripslashes($row2[2]);			$signed_out = date("d M Y h:m A",$row2[3]);			$borrower = "$student_id $first_name $last_name";		}			$query2 = "SELECT inventory.scan_date		FROM assets, inventory		WHERE assets.id = inventory.assets_id		AND assets.id = '$id'";		//print("$query2<br />");		$mysql_result2 = mysql_query($query2, $mysql_link);		while($row2 = mysql_fetch_row($mysql_result2)) {			$scan_date = date("d M Y h:m A",$row2[0]);		}						print("<asset>		<asset_description>$assets_asset_description</asset_description>		<assets_id>$assets_id</assets_id>		<serial_number>$serial_number</serial_number>		<durham_college_number>$durham_college_number</durham_college_number>		<notes>$notes</notes>		<action_id>$action_id</action_id>		<action_name>$action_name</action_name>		<barcode>$barcode</barcode>		<categories_id>$categories_id</categories_id>		<categories_name>$categories_name</categories_name>		<info>$info</info>		<signed_out>$signed_out</signed_out>		<borrower>$borrower</borrower>		<inventory>$scan_date</inventory>		</asset>		");			}	print("</inventory_detail>	");	} else {	// send no access allowed	header("Content-type: text/xml");	print("<?xml version=\"1.0\" encoding=\"iso-8859-1\"?>	<data>		<student_id>$student_id</student_id>		<error>Update Failed!</error>	</data>	");}$dbh = null;?>