<?php

require_once("../inc/connect_db.php");

// this file sets the prints fees to each student.

/*
advs yr 3
avmc yr 1 2

fad yr 128000
fine 1 2 3
mfun is fad yr 1

publ yr 1  for public relations

gdes yr 1 2 3


*/

// get all 
/*

print("<h1>REMOVING FROM ADD COPIES</h1>");

// delete any student id that is not GD or ADV from add or print 
$query = "SELECT id, student_id
FROM add_copies ";
//print("$query<br /><br />");
$mysql_result = mysql_query($query, $mysql_link);
while($row = mysql_fetch_row($mysql_result)) {
	$id = stripslashes($row[0]);
	$student_id = stripslashes($row[1]);
	
	$query1 = "SELECT student_id, program_name, program_year
	FROM ad_prints_students
	WHERE student_id = '$student_id' ";
	//print("$query1<br />");
	$mysql_result1 = mysql_query($query1, $mysql_link);
	while($row1 = mysql_fetch_row($mysql_result1)) {
		$student_id = stripslashes($row1[0]);
		$program_name = stripslashes($row1[1]);
		$program_year = stripslashes($row1[2]);
		
		if ( ($program_name == "ADVS" && $program_year == "3") || ($program_name == "ADVS" && $program_year == "2") || ($program_name == "ADVS" && $program_year == "1") ) {
			print("<p>$student_id in ADVS</p>");
			// keep
		} else if ( ($program_name == "PUBL" && $program_year == "1") ) {
			print("<h6>$student_id DELETE</h6>");
			$query2 = "DELETE FROM add_copies
			WHERE id = '$id' ";
			$mysql_result1 = mysql_query($query2, $mysql_link);
			// delete
		} else if ( ($program_name == "MFUN" && $program_year == "1") ) {
			print("<h6>$student_id DELETE</h6>");
			$query2 = "DELETE FROM add_copies
			WHERE id = '$id' ";
			$mysql_result1 = mysql_query($query2, $mysql_link);
			// delete
		} else if ( ($program_name == "FAD" && $program_year == "1") ) {
			print("<h6>$student_id DELETE</h6>");
			$query2 = "DELETE FROM add_copies
			WHERE id = '$id' ";
			$mysql_result1 = mysql_query($query2, $mysql_link);
			// delete
		} else if ( ($program_name == "FINE" && $program_year == "3") || ($program_name == "FINE" && $program_year == "2") || ($program_name == "FINE" && $program_year == "1") ) {
			print("<h6>$student_id DELETE</h6>");
			$query2 = "DELETE FROM add_copies
			WHERE id = '$id' ";
			$mysql_result1 = mysql_query($query2, $mysql_link);
			// delete
		} else if ( ($program_name == "GDES" && $program_year == "3") || ($program_name == "GDES" && $program_year == "2") || ($program_name == "GDES" && $program_year == "1") ) {
			print("<p>$student_id in GDES</p>");
			// keep
		} else {
			$query2 = "DELETE FROM add_copies
			WHERE id = '$id' ";
			$mysql_result1 = mysql_query($query2, $mysql_link);
			print("<h6>$student_id DELETE</h6>");
		}
		
		
	}
}
*/


/*

print("<h1>REMOVING FROM PRINT COPIES</h1>");

$query = "SELECT id, student_id
FROM print_copies ";
//print("$query<br /><br />");
$mysql_result = mysql_query($query, $mysql_link);
while($row = mysql_fetch_row($mysql_result)) {
	$id = stripslashes($row[0]);
	$student_id = stripslashes($row[1]);
	
	$query1 = "SELECT student_id, program_name, program_year
	FROM ad_prints_students
	WHERE student_id = '$student_id' ";
	//print("$query1<br />");
	$mysql_result1 = mysql_query($query1, $mysql_link);
	while($row1 = mysql_fetch_row($mysql_result1)) {
		$student_id = stripslashes($row1[0]);
		$program_name = stripslashes($row1[1]);
		$program_year = stripslashes($row1[2]);
		
		if ( ($program_name == "ADVS" && $program_year == "3") || ($program_name == "ADVS" && $program_year == "2") || ($program_name == "ADVS" && $program_year == "1") ) {
			print("<h3>$student_id in ADVS KEEP</h3>");
			// keep
		} else if ( ($program_name == "PUBL" && $program_year == "1") ) {
			print("<h6>$student_id DELETE</h6>");
			$query2 = "DELETE FROM print_copies
			WHERE id = '$id' ";
			$mysql_result1 = mysql_query($query2, $mysql_link);
			// delete
		} else if ( ($program_name == "MFUN" && $program_year == "1") ) {
			print("<h6>$student_id DELETE</h6>");
			$query2 = "DELETE FROM print_copies
			WHERE id = '$id' ";
			$mysql_result1 = mysql_query($query2, $mysql_link);
			// delete
		} else if ( ($program_name == "FAD" && $program_year == "1") ) {
			print("<h6>$student_id DELETE</h6>");
			$query2 = "DELETE FROM print_copies
			WHERE id = '$id' ";
			$mysql_result1 = mysql_query($query2, $mysql_link);
			// delete
		} else if ( ($program_name == "FINE" && $program_year == "3") || ($program_name == "FINE" && $program_year == "2") || ($program_name == "FINE" && $program_year == "1") ) {
			print("<h6>$student_id DELETE</h6>");
			$query2 = "DELETE FROM print_copies
			WHERE id = '$id' ";
			$mysql_result1 = mysql_query($query2, $mysql_link);
			// delete
		} else if ( ($program_name == "GDES" && $program_year == "3") || ($program_name == "GDES" && $program_year == "2") || ($program_name == "GDES" && $program_year == "1") ) {
			print("<h3>$student_id in GDES KEEP</h3>");
			// keep
		} else {
			$query2 = "DELETE FROM print_copies
			WHERE id = '$id' ";
			$mysql_result1 = mysql_query($query2, $mysql_link);
			print("<h6>$student_id DELETE</h6>");
		}
		
		
	}


*/


$myTime = mktime();

print("<h1>ADDING TO ADD COPIES</h1>");

/*
$query = "SELECT student_id, program_year, program_name
FROM ad_prints_students_2017
GROUP BY program_name, program_year
ORDER BY program_name, program_year, name_last, name_first";
print("$query<br /><br />");
$mysql_result = mysql_query($query, $mysql_link);
while($row = mysql_fetch_row($mysql_result)) {
	$student_id = stripslashes($row[0]);
	$program_year = stripslashes($row[1]);
	$program_name = stripslashes($row[2]);
	print("<h1>$program_name :: $program_year</h1>");
}
*/	
	
$theTime = mktime();
	
$y = 1;	

$query = "SELECT program_name, program_year, program_fee
FROM ad_prints_programs ";
print("$query<br /><br />");
$mysql_result = mysql_query($query, $mysql_link);
while($row = mysql_fetch_row($mysql_result)) {
	$name = stripslashes($row[0]);
	$year = stripslashes($row[1]);
	$fee = stripslashes($row[2]);
	$fee = $fee * 100;
	print("<h1>$name:$year:$fee</h1>");
	
	
	
	$query1 = "SELECT student_id, name_first, name_last
	FROM ad_prints_students
	WHERE program_year = '$year' AND program_name = '$name' ";
	//print("$query1<br />");
	$mysql_result1 = mysql_query($query1, $mysql_link);
	while($row1 = mysql_fetch_row($mysql_result1)) {
		$student_id = stripslashes($row1[0]);
		$name_first = stripslashes($row1[1]);
		$name_last = stripslashes($row1[2]);
		
		//print("$student_id:$name_first:$name_last<br />");
		
		
		++$y;
		
		$query2 = "INSERT INTO add_copies
		SET copies = '$fee',
		support = '100017807',
		dateme = '$theTime',
		invoice_id = '$y',
		student_id = '$student_id' ";
		
		print("<p>$query2</p>");
		//$mysql_result2 = mysql_query($query2, $mysql_link);

	}

	
}



/* OLD STUFF */	

/*

$query = "SELECT student_id, program_year, program_name
FROM ad_prints_students_2017
ORDER BY program_name, program_year, name_last, name_first";
print("$query<br /><br />");
$mysql_result = mysql_query($query, $mysql_link);
while($row = mysql_fetch_row($mysql_result)) {
	$student_id = stripslashes($row[0]);
	$program_year = stripslashes($row[1]);
	$program_name = stripslashes($row[2]);
	
	print("<p>$student_id || $program_name || $program_year</p>");
	
	$query1 = "SELECT student_id, name_first, name_last
	FROM ad_prints_students
	WHERE program_year = '$year' AND program_name = '$name' ";
	print("$query1<br />");
	$mysql_result1 = mysql_query($query1, $mysql_link);
	while($row1 = mysql_fetch_row($mysql_result1)) {
		$student_id = stripslashes($row1[0]);
		$name_first = stripslashes($row1[1]);
		$name_last = stripslashes($row1[2]);
		
		print("$student_id:$name_first:$name_last<br />");
		$query2 = "UPDATE ad_prints_students
		SET program_fee = '$fee'
		WHERE student_id = '$student_id' ";
		
		$fee = $fee * 100;
		
		$query2 = "INSERT INTO add_copies
		SET copies = '$fee',
		support = '100017807',
		dateme = '$theTime',
		invoice_id = '$y',
		student_id = '$student_id' ";
		
		print("$query2<br />");
		$mysql_result2 = mysql_query($query2, $mysql_link);

	}



	if ( ($program_name == "FINE" && $program_year == "3") ) {
		
		$query2 = "INSERT INTO add_copies
		SET student_id = '$student_id', 
		copies = '4000', 
		support = '100017807', 
		dateme = '$myTime', 
		invoice_id = '2017' ";
		print("<p>Adding AVMC $query2</p>");
		//$mysql_result2 = mysql_query($query2, $mysql_link);
		
		$query2 = "DELETE FROM add_copies
		WHERE student_id = '$student_id'";
		print("<p>REMOVING AVMC $query2</p>");
		//$mysql_result2 = mysql_query($query2, $mysql_link);
		
		
		
		// keep
	}
	
	*/
	
	/*
	if ( ($program_name == "ADVS" && $program_year == "3") || ($program_name == "ADVS" && $program_year == "2") || ($program_name == "ADVS" && $program_year == "1") ) {
		
		$query2 = "INSERT INTO add_copies
		SET student_id = '$student_id', 
		copies = '1500', 
		support = '100017807', 
		dateme = '$myTime', 
		invoice_id = '2015' ";
		print("<p>Adding ADVS $query2</p>");
		//$mysql_result2 = mysql_query($query2, $mysql_link);
		
		// keep
	}
	*/
	
	/*
	
	if ( ($program_name == "AVMC" && $program_year == "2") || ($program_name == "AVMC" && $program_year == "1") ) {
		
		$query2 = "INSERT INTO add_copies
		SET student_id = '$student_id', 
		copies = '1500', 
		support = '100017807', 
		dateme = '$myTime', 
		invoice_id = '2016' ";
		print("<p>Adding ADVS $program_year $query2</p>");
		$mysql_result1 = mysql_query($query1, $mysql_link);
		
		// keep
	} 
	*/
	/*
	else 
	
	*/
	
	
	/*
	if ( ($program_name == "PUBL" && $program_year == "1") ) {
		$query2 = "INSERT INTO add_copies
		SET student_id = '$student_id', 
		copies = '1000', 
		support = '100017807', 
		dateme = '$myTime', 
		invoice_id = '2016' ";
		print("<p>Adding PUBL $program_year $query2</p>");
		$mysql_result1 = mysql_query($query2, $mysql_link);
		// delete
	}
	*/
	
	/*
	else */
	/*
	if ( ($program_name == "MFUN" && $program_year == "1") ) {
		$query2 = "INSERT INTO add_copies
		SET student_id = '$student_id', 
		copies = '1500', 
		support = '100017807', 
		dateme = '$myTime', 
		invoice_id = '2016' ";
		print("<p>Adding MFUN $program_year $query2</p>");
		$mysql_result1 = mysql_query($query2, $mysql_link);
		// delete
	} 
	*/
	/*else 
	*/
	/*
	if ( ($program_name == "FAD" && $program_year == "1") ) {
		$query2 = "INSERT INTO add_copies
		SET student_id = '$student_id', 
		copies = '5000', 
		support = '100017807', 
		dateme = '$myTime', 
		invoice_id = '2016' ";
		print("<p>Adding FAD $program_year $query2</p>");
		$mysql_result1 = mysql_query($query2, $mysql_link);
		// delete
	}
	*/
	/*
	else if ( ($program_name == "FINE" && $program_year == "1")  ) {
		$query2 = "INSERT INTO add_copies
		SET student_id = '$student_id', 
		copies = '5000', 
		support = '100017807', 
		dateme = '$myTime', 
		invoice_id = '2015' ";
		print("<p>Adding FINE $query2</p>");
		$mysql_result1 = mysql_query($query2, $mysql_link);
		// delete
	} else if (($program_name == "FINE" && $program_year == "2") || ($program_name == "FINE" && $program_year == "3") ) {
		$query2 = "INSERT INTO add_copies
		SET student_id = '$student_id', 
		copies = '4000', 
		support = '100017807', 
		dateme = '$myTime', 
		invoice_id = '2015' ";
		print("<p>Adding FINE $query2</p>");
		$mysql_result1 = mysql_query($query2, $mysql_link);
		// delete
	} else 
	*/
	/*
	if ( ($program_name == "GDES" && $program_year == "2") || ($program_name == "GDES" && $program_year == "1") ) {
		//print("<p>$student_id in GDES</p>");
		// keep
		$query2 = "INSERT INTO add_copies
		SET student_id = '$student_id', 
		copies = '5000', 
		support = '100017807', 
		dateme = '$myTime', 
		invoice_id = '2016' ";
		print("<p>Adding GDES $program_year $query2</p>");
		$mysql_result1 = mysql_query($query2, $mysql_link);
		// delete
		
	}
	
	
	if ( ($program_name == "GDES" && $program_year == "3")  ) {
		//print("<p>$student_id in GDES</p>");
		// keep
		$query2 = "INSERT INTO add_copies
		SET student_id = '$student_id', 
		copies = '3100', 
		support = '100017807', 
		dateme = '$myTime', 
		invoice_id = '2016' ";
		print("<p>Adding GDES $program_year $query2</p>");
		$mysql_result1 = mysql_query($query2, $mysql_link);
		// delete
		
	}
	
	
	
	if ( ($program_name == "FINE" && $program_year == "1") ) {
		//print("<p>$student_id in GDES</p>");
		// keep
		$query2 = "INSERT INTO add_copies
		SET student_id = '$student_id', 
		copies = '5000', 
		support = '100017807', 
		dateme = '$myTime', 
		invoice_id = '2016' ";
		print("<p>Adding FINE $program_year $query2</p>");
		$mysql_result1 = mysql_query($query2, $mysql_link);
		// delete
	}
	
	if ( ($program_name == "FINE" && $program_year == "3") || ($program_name == "FINE" && $program_year == "2")  ) {
		//print("<p>$student_id in GDES</p>");
		// keep
		$query2 = "INSERT INTO add_copies
		SET student_id = '$student_id', 
		copies = '4000', 
		support = '100017807', 
		dateme = '$myTime', 
		invoice_id = '2016' ";
		print("<p>Adding FINE $program_year $query2</p>");
		$mysql_result1 = mysql_query($query2, $mysql_link);
		// delete
	}
	*/
	
	/*else {
		
	}
	
	*/
//}


// get all the records from add_copies
	// get the id of student
	// if in list of ad print students then keep else delete unless in GD or ADV
	// in list then add credit amount based on ad_prints_programs

// get all the records from print_copies
	// get the id of student
	// if in list of ad print students then keep if in GD or adv else delete





$y = 0;

// select the students id from add prints
// if id one of the ones in 

/*

$query = "SELECT program_name
FROM ad_prints_students 
GROUP BY program_name";
print("$query<br /><br />");
$mysql_result = mysql_query($query, $mysql_link);
while($row = mysql_fetch_row($mysql_result)) {
	$name = stripslashes($row[0]);
	print("<h3>$name</h3>");
	
}
*/

/*

$theTime = mktime();

$query = "SELECT program_name, program_year, program_fee
FROM ad_prints_programs ";
print("$query<br /><br />");
$mysql_result = mysql_query($query, $mysql_link);
while($row = mysql_fetch_row($mysql_result)) {
	$name = stripslashes($row[0]);
	$year = stripslashes($row[1]);
	$fee = stripslashes($row[2])*100;
	
	print("$name:$year:$fee<br /><br />");
	++$y;
	
	$query1 = "SELECT student_id, name_first, name_last
	FROM ad_prints_students
	WHERE program_year = '$year' AND program_name = '$name' ";
	print("$query1<br />");
	$mysql_result1 = mysql_query($query1, $mysql_link);
	while($row1 = mysql_fetch_row($mysql_result1)) {
		$student_id = stripslashes($row1[0]);
		$name_first = stripslashes($row1[1]);
		$name_last = stripslashes($row1[2]);
		
		print("$student_id:$name_first:$name_last<br />");
		//
		//$query2 = "UPDATE ad_prints_students
		//SET program_fee = '$fee'
		//WHERE student_id = '$student_id' ";
		
		//$fee = $fee * 100;
		
		$query2 = "INSERT INTO add_copies
		SET copies = '$fee',
		support = '100000001',
		dateme = '$theTime',
		invoice_id = '$y',
		student_id = '$student_id' ";
		//
		//print("$query2<br />");
		//$mysql_result2 = mysql_query($query2, $mysql_link);

	}

	
}
*/
//print("$query");
/*

$query = "SELECT student_id, program_name, program_year
FROM ad_prints_students
ORDER BY program_name, program_year, student_id";
//print("$query");
$mysql_result = mysql_query($query, $mysql_link);
while($row = mysql_fetch_row($mysql_result)) {
	$student_id = stripslashes($row[0]);
	$name = stripslashes($row[1]);
	$year = stripslashes($row[2]);
	
	//print("$student_id::$fee<br />");
	
	unset($fee);
	
	$query1 = "SELECT program_fee
	FROM ad_prints_programs
	WHERE program_name = '$name'
	AND program_year = '$year' ";
	print("$query1<br />");
	$mysql_result1 = mysql_query($query1, $mysql_link);
	while($row1 = mysql_fetch_row($mysql_result1)) {
		$fee = stripslashes($row1[0]);
		
		print("$student_id::$name::$year::$fee<br />");
		
	}
	
}




$query = "SELECT ad_prints_students.student_id, ad_prints_programs.program_fee
FROM ad_prints_students, ad_prints_programs
WHERE ad_prints_students.program_name = ad_prints_programs.program_name
AND ad_prints_students.program_year = ad_prints_programs.program_year
ORDER BY ad_prints_students.student_id";
print("$query");
$mysql_result = mysql_query($query, $mysql_link);
while($row = mysql_fetch_row($mysql_result)) {
	$student_id = stripslashes($row[0]);
	$fee = stripslashes($row[1]);
	
	print("$student_id::$fee<br />");
	
}

*/

?>