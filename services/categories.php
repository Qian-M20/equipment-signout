<?php

require_once("./inc/connect_db.php");


// this file returns an xml file that list of categories



header("Content-type: text/xml");

print("<?xml version=\"1.0\" encoding=\"iso-8859-1\"?>
<data>
");

/*
print("<program>
<id>0</id>
<name>Unknown</name>
</program>
");	
*/
	
$query = "SELECT id, name
FROM categories
ORDER BY id ";
$mysql_result = mysql_query($query, $mysql_link);
while($row = mysql_fetch_row($mysql_result)) {
	$programs_id = stripslashes($row[0]);
	$programs_name = stripslashes($row[1]);
	
	
	print("<program>
	<id>$programs_id</id>
	<name>$programs_name</name>
	</program>
	");	
}


print("</data>
");
	


?>