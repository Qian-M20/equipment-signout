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
<name>In Good Standing</name>
</status>
<status>
<id>1</id>
<name>Restricted (next day 11 am)</name>
</status>
<status>
<id>2</id>
<name>Restricted (same day 3 PM)</name>
</status>
<status>
<id>3</id>
<name>Loss of borrowing privileges</name>
</status>
");	



print("</data>
");
	


?>