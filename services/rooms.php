<?php

require_once("./inc/connect_db.php");


// this file returns an xml file that list the students/borrowers info.

if ($id) {
	
	// sanitize
	$id = addslashes($id);
	$name = addslashes($name);
	$description = addslashes($description);
	$block_size = addslashes($block_size);
	$block_start = addslashes($block_start);
	$block_number = addslashes($block_number);
	$restrictions_days = addslashes($restrictions_days);
	$restrictions_nights = addslashes($restrictions_nights);
	$bottom_description = addslashes($bottom_description);
	
	// thought
	if ($id == -1) {
		$query = "INSERT INTO rooms
		SET name = '$name',
		descript = '$description', 
		block_size = '$block_size', 
		block_start = '$block_start', 
		block_number = '$block_number', 
		restrictions_day = '$restrictions_days', 
		restrictions_night = '$restrictions_nights',
		notes_bottom = '$bottom_description' ";
		$mysql_result = mysql_query($query, $mysql_link);
	} else {
		$query = "UPDATE rooms
		SET name = '$name',
		descript = '$description', 
		block_size = '$block_size', 
		block_start = '$block_start', 
		block_number = '$block_number', 
		restrictions_day = '$restrictions_days', 
		restrictions_night = '$restrictions_nights',
		notes_bottom = '$bottom_description' 
		WHERE id = '$id' ";
		$mysql_result = mysql_query($query, $mysql_link);
	}
	//print("$query<br>");
}

header("Content-type: text/xml");

print("<?xml version=\"1.0\" encoding=\"iso-8859-1\"?>
<data>
");


if ($rooms_id) {
	$query = "SELECT id, name, descript, block_size, block_start, block_number, restrictions_day, restrictions_night, notes_bottom
	FROM rooms
	WHERE id = '$rooms_id' ";
	$mysql_result = mysql_query($query, $mysql_link);
	while($row = mysql_fetch_row($mysql_result)) {
		$id = stripslashes($row[0]);
		$name = stripslashes($row[1]);
		$description = htmlspecialchars(stripslashes($row[2]));
		$block_size = stripslashes($row[3]);
		$block_start = stripslashes($row[4]);
		$block_number = stripslashes($row[5]);
		$restrictions_day = stripslashes($row[6]);
		$restrictions_night = stripslashes($row[7]);
		$notes_bottom = stripslashes($row[8]);
		
		print("<room>
		<id>$id</id>
		<name>$name</name>
		<description>$description</description>
		<bottom_description>$notes_bottom</bottom_description>
		<block_size>$block_size</block_size>
		<block_start>$block_start</block_start>
		<block_number>$block_number</block_number>
		<restrictions_day>$restrictions_day</restrictions_day>
		<restrictions_night>$restrictions_night</restrictions_night>
		</room>
		");	
	}
} else {
	$query = "SELECT id, name, descript, block_size, block_start, block_number, restrictions_day, restrictions_night, notes_bottom
	FROM rooms
	ORDER BY name";
	$mysql_result = mysql_query($query, $mysql_link);
	while($row = mysql_fetch_row($mysql_result)) {
		$id = stripslashes($row[0]);
		$name = stripslashes($row[1]);
		$description = htmlspecialchars(stripslashes($row[2]));
		$block_size = stripslashes($row[3]);
		$block_start = stripslashes($row[4]);
		$block_number = stripslashes($row[5]);
		$restrictions_day = stripslashes($row[6]);
		$restrictions_night = stripslashes($row[7]);
		$notes_bottom = stripslashes($row[8]);
		
		print("<room>
		<id>$id</id>
		<name>$name</name>
		<description>$description</description>
		<bottom_description>$notes_bottom</bottom_description>
		<block_size>$block_size</block_size>
		<block_start>$block_start</block_start>
		<block_number>$block_number</block_number>
		<restrictions_day>$restrictions_day</restrictions_day>
		<restrictions_night>$restrictions_night</restrictions_night>
		</room>
		");	
	}
	
	/*
	$query = "SELECT id, name
	FROM rooms
	ORDER BY name ";
	$mysql_result = mysql_query($query, $mysql_link);
	while($row = mysql_fetch_row($mysql_result)) {
		$id = stripslashes($row[0]);
		$name = stripslashes($row[1]);
		
		print("<room>
		<id>$id</id>
		<name>$name</name>
		</room>
		");	
	}
	*/
}




print("</data>
");
	


?>