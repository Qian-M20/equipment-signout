<?php

require_once("./inc/connect_db.php");




// this file returns an xml file that list the students/borrowers info.

$y = false;

header("Content-type: text/xml");

print("<?xml version=\"1.0\" encoding=\"iso-8859-1\"?>
<data>
");


	
print("<status>
<id>0</id>
<name>Unknown</name>
</status>
<status>
<id>1</id>
<name>1</name>
</status>
<status>
<id>2</id>
<name>2</name>
</status>
<status>
<id>3</id>
<name>3</name>
</status>
");	



print("</data>
");
	


?>