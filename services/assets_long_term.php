<?php

require_once("./inc/connect_db.php");




// this file returns an xml file that list the students/borrowers info.

$y = false;

header("Content-type: text/xml");

print("<?xml version=\"1.0\" encoding=\"iso-8859-1\"?>
<data>
");

$query = "SELECT id, asset_description, serial_number, durham_college_number, notes, actions_id, barcode, categories_id, info
FROM assets
WHERE actions_id = '1'
ORDER BY asset_description, barcode ";
$mysql_result = mysql_query($query, $mysql_link);
while($row = mysql_fetch_row($mysql_result)) {
	$assets_id = stripslashes($row[0]);
	$assets_asset_description = htmlspecialchars(stripslashes($row[1]));
	$assets_serial_number = stripslashes($row[2]);
	$assets_durham_college_number = stripslashes($row[3]);
	$assets_notes = htmlspecialchars(stripslashes($row[4]));
	$assets_actions_id = stripslashes($row[5]);
	$assets_barcode = stripslashes($row[6]);
	$assets_categories_id = stripslashes($row[7]);
	$assets_info = stripslashes($row[8]);
	
	
	unset($assets_logged_out);
	
	$query1 = "SELECT id
	FROM assets_logged_out
	WHERE assets_id = '$assets_id'
	AND in_time = '0' ";
	$mysql_result1 = mysql_query($query1, $mysql_link);
	while($row1 = mysql_fetch_row($mysql_result1)) {
		$assets_logged_out = true;
	}
	
	if (!$assets_logged_out) {
	
	
		print("<asset>
		<id>$assets_id</id>
		<asset_description>$assets_asset_description</asset_description>
		<serial_number>$assets_serial_number</serial_number>
		<durham_college_number>$assets_durham_college_number</durham_college_number>
		<notes>$assets_notes</notes>
		<actions_id>$assets_actions_id</actions_id>
		<barcode>$assets_barcode</barcode>
		<categories_id>$assets_categories_id</categories_id>
		<barcode_description>$assets_barcode $assets_asset_description</barcode_description>
		<info>$assets_info</info>
		</asset>
		");	
	
	}
}


print("</data>
");
	


?>