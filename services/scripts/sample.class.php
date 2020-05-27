<?php

error_reporting(E_ALL);

date_default_timezone_set('America/Toronto');


class Sample
{
	public $dbo = "";

	public function __construct()
	{
		require_once("../inc/connect_pdo.php");
		$this->dbo = $dbo;
	}

	public function updateFromSample () {
		/*
		UPDATE tableA a
		INNER JOIN tableB b ON a.name_a = b.name_b
		SET validation_check = if(start_dts > end_dts, 'VALID', '')
		*/
		
		//dc_email = '$dc_email', 
		
		$query = "UPDATE borrowers a
		INNER JOIN sample b ON a.id = b.sample_id
		SET first_name = b.first_name, 
		last_name = b.last_name, 
		other_email = b.email, 
		phone = b.email ";
		print("$query");
		
		$this->dbo->query($query);
		
		
		
	}
	
	
	public function convertFancyQuotes($str)
	{
		return str_replace(array(chr(145), chr(146), chr(147), chr(148), chr(151)), array("'", "'", '"', '"', '-'), $str);
	}

	// actions
	public function getActions()
	{

		$errorCode = 0;
		$errorMessage = "";

		try {

			$query = "SELECT id, name
			FROM actions
			ORDER BY id ";
			foreach ($this->dbo->query($query) as $row) {
				$action_id = $this->convertFancyQuotes(stripslashes($row[0]));
				$action_name = $this->convertFancyQuotes(stripslashes($row[1]));

				$action["id"] = $action_id;
				$action["name"] = $action_name;

				$actions[] = $action;
			}
		} catch (PDOException $e) {
			$errorCode = -1;
			$errorMessage = "PDOException for getActions.";
		}

		$err["id"] = $errorCode;
		$err["message"] = $errorMessage;

		$data["error"] = $err;
		$data["query"] = $query;
		$data["actions"] = $actions;

		$data = json_encode($data);

		return $data;
	}

	// categories
	public function getCategories()
	{

		$errorCode = 0;
		$errorMessage = "";

		try {

			$query = "SELECT id, name
			FROM categories
			ORDER BY id ";
			foreach ($this->dbo->query($query) as $row) {
				$category_id = $this->convertFancyQuotes(stripslashes($row[0]));
				$category_name = $this->convertFancyQuotes(stripslashes($row[1]));

				$category["id"] = $category_id;
				$category["name"] = $category_name;

				$categories[] = $category;
			}
		} catch (PDOException $e) {
			$errorCode = -1;
			$errorMessage = "PDOException for getActions.";
		}

		$err["id"] = $errorCode;
		$err["message"] = $errorMessage;

		$data["error"] = $err;
		$data["query"] = $query;
		$data["categories"] = $categories;

		$data = json_encode($data);

		return $data;
	}

	// borrowers
	public function getBorrowers()
	{

		$y = false;

		$errorCode = 0;
		$errorMessage = "";


		try {

			$query = "SELECT id, first_name, last_name, student_id
				FROM borrowers
				ORDER BY student_id ";

			//print("$query");
			foreach ($this->dbo->query($query) as $row) {
				$borrowers_id = stripslashes($row[0]);
				$borrowers_first_name = stripslashes($row[1]);
				$borrowers_last_name = stripslashes($row[2]);
				$borrowers_student_id = stripslashes($row[3]);
				// $borrowers_dc_email = stripslashes($row[4]);
				// $borrowers_other_email = stripslashes($row[5]);
				// $borrowers_agreement = stripslashes($row[6]);
				// $borrowers_email_confirmation = stripslashes($row[7]);
				// $borrowers_strikes = stripslashes($row[8]);
				// $borrowers_phone = stripslashes($row[9]);
				// $borrowers_programs_id = stripslashes($row[10]);
				// $borrowers_program_year = stripslashes($row[11]);
				// $borrowers_password = stripslashes($row[12]);

				$borrowers_student_id = htmlspecialchars($borrowers_student_id);

				// if ($borrowers_strikes > 3) {
				// 	$borrowers_strikes = 3;
				// }

				$borrower["id"] = $borrowers_id;
				$borrower["borrowers_first_name"] = $borrowers_first_name;
				$borrower["borrowers_last_name"] = $borrowers_last_name;
				$borrower["borrowers_student_id"] = $borrowers_student_id;
				// $borrower["borrowers_dc_email"] = $borrowers_dc_email;
				// $borrower["borrowers_other_email"] = $borrowers_other_email;
				// $borrower["borrowers_agreement"] = $borrowers_agreement;
				// $borrower["borrowers_email_confirmation"] = $borrowers_email_confirmation;
				// $borrower["borrowers_phone"] = $borrowers_phone;
				// $borrower["borrowers_programs_id"] = $borrowers_programs_id;
				// $borrower["borrowers_program_year"] = $borrowers_program_year;
				// $borrower["borrowers_password"] = $borrowers_password;

				$borrowers[] = $borrower;
			}

			$query = "SELECT id, name
				FROM programs
				ORDER BY name ";

			//print("$query");
			foreach ($this->dbo->query($query) as $row) {
				$id = stripslashes($row[0]);
				$name = stripslashes($row[1]);

				$program["id"] = $borrowers_id;
				$program["borrowers_first_name"] = $borrowers_first_name;

				$programs[] = $program;
			}
		} catch (PDOException $e) {
			$errorCode = -1;
			$errorMessage = "PDOException for getBorrowers.";
		}

		$error["id"] = $errorCode;
		$error["message"] = $errorMessage;

		$data["error"] = $error;

		$data["borrowers"] = $borrowers;
		$data["programs"] = $programs;
		$data["query"] = $query;

		$data = json_encode($data);

		return $data;
	}

	public function getBorrower($id)
	{

		// this file returns an xml file that list the students/borrowers info.
		$y = false;

		$errorCode = 0;
		$errorMessage = "";

		try {

			$query = "SELECT id, student_id, first_name, last_name, dc_email, other_email, email_confirmation, program_year, phone, strikes, programs_id
			FROM borrowers
			WHERE id = '$id' 
			ORDER BY student_id";

			foreach ($this->dbo->query($query) as $row) {
				$id = stripslashes($row[0]);
				$student_id = stripslashes($row[1]);
				$first_name = htmlspecialchars(stripslashes($row[2]));
				$last_name = htmlspecialchars(stripslashes($row[3]));
				$dc_email = htmlspecialchars(stripslashes($row[4]));
				$other_email = htmlspecialchars(stripslashes($row[5]));
				$email_confirmation = stripslashes($row[6]);
				$program_year = stripslashes($row[7]);
				$phone = stripslashes($row[8]);
				$strikes = stripslashes($row[9]);
				$programs_id = stripslashes($row[10]);


				$borrower["id"] = $id;
				$borrower["student_id"] = $student_id;
				$borrower["first_name"] = $first_name;
				$borrower["last_name"] = $last_name;
				$borrower["dc_email"] = $dc_email;
				$borrower["other_email"] = $other_email;
				$borrower["email_confirmation"] = $email_confirmation;
				$borrower["program_year"] = $program_year;
				$borrower["phone"] = $phone;
				$borrower["strikes"] = $strikes;
				$borrower["programs_id"] = $programs_id;


				// $query2 = "SELECT name
				// 		FROM borrower_status
				// 		WHERE borrower_status_id = '$strikes' ";

				// foreach ($this->dbo->query($query2) as $row2) {
				// 	$strikes = stripslashes($row2[0]);
				// 	$borrower["strikes"] = $strikes;
				// }


				// $query3 = "SELECT name
				// 		FROM programs
				// 		WHERE id = '$program_id' ";

				// foreach ($this->dbo->query($query3) as $row3) {
				// 	$program_name = stripslashes($row3[0]);
				// 	$borrower["program_name"] = $program_name;
				// }
			}
		} catch (PDOException $e) {
			$errorCode = -1;
			$errorMessage = "PDOException for getBorrower.";
		}

		$error["id"] = $errorCode;
		$error["message"] = $errorMessage;

		$data["error"] = $error;

		//$data["assets"] = $asset;
		$data["query"] = $query;
		$data["borrower"] = $borrower;

		$data = json_encode($data);

		return $data;
	}

	public function updateBorrower($id, $first_name, $last_name, $student_id, $dc_email, $other_email, $email_confirmation, $strikes, $phone, $programs_id, $program_year)
	{


		$errorCode = 0;
		$errorMessage = "Borrower Updated!";


		try {
			$query = "UPDATE borrowers
				SET first_name = '$first_name', 
				last_name = '$last_name', 
				student_id = '$student_id', 
				dc_email = '$dc_email', 
				other_email = '$other_email', 
				email_confirmation = '$email_confirmation',
				strikes = '$strikes', 
				phone = '$phone', 
				programs_id = '$programs_id', 
				program_year = '$program_year'
				WHERE id = '$id' ";

			$this->dbo->query($query);
		} catch (PDOException $e) {
			$errorCode = -1;
			$errorMessage = "PDOException for updateBorrower.";
		}



		$error["id"] = $errorCode;
		$error["message"] = $errorMessage;

		$data["error"] = $error;
		$data["query"] = $query;

		$data = json_encode($data);

		return $data;
	}

	public function getBorrowerHistory($borrowers_id)
	{
		$borrowers_id = addslashes($borrowers_id);

		$y = false;

		$errorCode = 0;
		$errorMessage = "";

		try {


			$query = "SELECT assets.asset_description, 
			assets.notes, 
			assets.serial_number, 
			assets.barcode,
			assets_logged_out.out_time, 
			assets_logged_out.in_time
			-- assets_logged_out.due_time, 
			-- borrowers.first_name, 
			-- borrowers.last_name, 
			-- borrowers.student_id, 
			-- borrowers.dc_email, 
			-- borrowers.other_email
			FROM assets, assets_logged_out, borrowers
			WHERE assets.id = assets_logged_out.assets_id
			AND assets_logged_out.borrowers_id = borrowers.id
			AND borrowers.id = '$borrowers_id' 
			ORDER BY assets_logged_out.out_time DESC";

			foreach ($this->dbo->query($query) as $row) {
				$assets_asset_description = stripslashes($row[0]);
				$assets_notes = htmlspecialchars(stripslashes($row[1]));
				$assets_serial_number = htmlspecialchars(stripslashes($row[2]));
				$assets_barcode = htmlspecialchars(stripslashes($row[3]));
				$assets_logged_out_out_time = date("D d M Y h:i:s A", htmlspecialchars(stripslashes($row[4])));
				// $assets_logged_out_due_time = date("D d M Y h:i:s A", htmlspecialchars(stripslashes($row[5])));
				$assets_logged_out_in_time = date("D d M Y h:i:s A", htmlspecialchars(stripslashes($row[5])));
				/* $borrowers_first_name = htmlspecialchars(stripslashes($row[7]));
				$borrowers_last_name = htmlspecialchars(stripslashes($row[8]));
				$borrowers_student_id = htmlspecialchars(stripslashes($row[9]));
				$borrowers_dc_email = htmlspecialchars(stripslashes($row[10]));
				$borrowers_other_email = htmlspecialchars(stripslashes($row[11])); */


				/* if ($row[5] < mktime()) {
					$overdue = 1;
				} else {
					$overdue = 0;
				} */

				if ($row[5] == "0") {
					$assets_logged_out_in_time = "";
				}


				$history["asset_description"] = $assets_asset_description;
				$history["assets_notes"] = $assets_notes;
				$history["assets_serial_number"] = $assets_serial_number;
				$history["assets_barcode"] = $assets_barcode;
				$history["assets_logged_out_out_time"] = $assets_logged_out_out_time;
				// $history["assets_logged_out_due_time"] = $assets_logged_out_due_time;
				$history["assets_logged_out_in_time"] = $assets_logged_out_in_time;
				/* $history["borrowers_first_name"] = $borrowers_first_name;
				$history["borrowers_last_name"] = $borrowers_last_name;
				$history["borrowers_student_id"] = $borrowers_student_id;
				$history["borrowers_dc_email"] = $borrowers_dc_email;
				$history["borrowers_other_email"] = $borrowers_other_email; */

				$b_history[] = $history;
			}
		} catch (PDOException $e) {
			$errorCode = -1;
			$errorMessage = "PDOException for getBorrowerHistory.";
		}

		$error["id"] = $errorCode;
		$error["message"] = $errorMessage;

		$data["error"] = $error;

		$data["query"] = $query;
		$data["b_history"] = $b_history;

		$data = json_encode($data);

		return $data;
	}

	public function getLongTermBorrowers()
	{

		$y = false;

		$errorCode = 0;
		$errorMessage = "";


		try {

			$query = "SELECT id, first_name, last_name, student_id
				FROM borrowers
				WHERE dc_email LIKE '%@durhamcollege.ca'
				ORDER BY student_id ";

			//print("$query");
			foreach ($this->dbo->query($query) as $row) {
				$borrowers_id = stripslashes($row[0]);
				$borrowers_first_name = stripslashes($row[1]);
				$borrowers_last_name = stripslashes($row[2]);
				$borrowers_student_id = stripslashes($row[3]);
				$borrowers_student_id = htmlspecialchars($borrowers_student_id);

				$borrower["id"] = $borrowers_id;
				$borrower["borrowers_first_name"] = $borrowers_first_name;
				$borrower["borrowers_last_name"] = $borrowers_last_name;
				$borrower["borrowers_student_id"] = $borrowers_student_id;

				$borrowers[] = $borrower;
			}
		} catch (PDOException $e) {
			$errorCode = -1;
			$errorMessage = "PDOException for getBorrowers.";
		}

		$error["id"] = $errorCode;
		$error["message"] = $errorMessage;

		$data["error"] = $error;

		$data["borrowers"] = $borrowers;
		$data["query"] = $query;

		$data = json_encode($data);

		return $data;
	}

	// password
	public function updatePassword($password, $id, $first_name, $last_name, $student_id)
	{

		$errorCode = 0;
		$errorMessage = "Password updated!";


		try {
			$password = addslashes($password);

			$password = md5($password);

			$query = "UPDATE borrowers
					SET password = '$password'
					WHERE id = '$id' ";

			$this->dbo->query($query);
		} catch (PDOException $e) {
			$errorCode = -1;
			$errorMessage = "PDOException for updatePassword. Something went wrong.";
		}


		$error["id"] = $errorCode;
		$error["message"] = $errorMessage;

		$data["error"] = $error;
		$data["query"] = $query;

		$data = json_encode($data);

		return $data;
	}

	// assets
	public function getAssets()
	{

		// this file returns an xml file that list the students/borrowers info.


		$y = false;

		$errorCode = 0;
		$errorMessage = "";

		try {

			$query = "SELECT id, asset_description, barcode
			FROM assets
			ORDER BY asset_description, barcode ";

			foreach ($this->dbo->query($query) as $row) {
				$assets_id = stripslashes($row[0]);
				$assets_asset_description = htmlspecialchars(stripslashes($row[1]));

				$assets_barcode = stripslashes($row[2]);

				$asset["id"] = $assets_id;
				$asset["asset_description"] = $assets_asset_description;
				$asset["barcode"] = $assets_barcode;

				$assets[] = $asset;
			}
		} catch (PDOException $e) {
			$errorCode = -1;
			$errorMessage = "PDOException for getAssets.";
		}

		$error["id"] = $errorCode;
		$error["message"] = $errorMessage;

		$data["error"] = $error;

		//$data["assets"] = $asset;
		$data["query"] = $query;
		$data["assets"] = $assets;

		$data = json_encode($data);

		return $data;
	}

	public function getAsset($id)
	{

		// this file returns an xml file that list the students/borrowers info.


		$y = false;

		$errorCode = 0;
		$errorMessage = "";

		try {

			$query = "SELECT id, asset_description, serial_number, durham_college_number, notes, actions_id, barcode, categories_id, info
			FROM assets
			WHERE id = '$id' 
			ORDER BY asset_description, barcode ";

			foreach ($this->dbo->query($query) as $row) {
				$assets_id = stripslashes($row[0]);
				$assets_asset_description = htmlspecialchars(stripslashes($row[1]));
				$assets_serial_number = stripslashes($row[2]);
				$assets_durham_college_number = stripslashes($row[3]);
				$assets_notes = htmlspecialchars(stripslashes($row[4]));
				$assets_actions_id = stripslashes($row[5]);
				$assets_barcode = stripslashes($row[6]);
				$assets_categories_id = stripslashes($row[7]);
				$assets_info = stripslashes($row[8]);


				$query2 = "SELECT name
						FROM actions
						WHERE id = '$assets_actions_id' ";

				foreach ($this->dbo->query($query2) as $row2) {
					$assets_actions_name = stripslashes($row2[0]);
					$asset["assets_actions_name"] = $assets_actions_name;
				}

				$query3 = "SELECT name
						FROM categories
						WHERE id = '$assets_categories_id' ";

				foreach ($this->dbo->query($query3) as $row3) {
					$assets_categories_name = stripslashes($row3[0]);
					$asset["assets_categories_name"] = $assets_categories_name;
				}


				$asset["id"] = $assets_id;
				$asset["asset_description"] = $assets_asset_description;
				$asset["serial_number"] = $assets_serial_number;
				$asset["durham_college_number"] = $assets_durham_college_number;
				$asset["notes"] = $assets_notes;
				$asset["actions_id"] = $assets_actions_id;
				$asset["barcode"] = $assets_barcode;
				$asset["categories_id"] = $assets_categories_id;
				$asset["info"] = $assets_info;

				// $assets[] = $asset;
			}
		} catch (PDOException $e) {
			$errorCode = -1;
			$errorMessage = "PDOException for getAsset.";
		}

		$error["id"] = $errorCode;
		$error["message"] = $errorMessage;

		$data["error"] = $error;

		//$data["assets"] = $asset;
		$data["query"] = $query;
		$data["asset"] = $asset;

		$data = json_encode($data);

		return $data;
	}

	public function updateAsset($id, $asset_description, $serial_number, $notes, $actions_id, $barcode, $categories_id, $info)
	{


		$errorCode = 0;
		$errorMessage = "Asset updated!";


		try {
			if ($id) {
				if ($id == -1) {
					$query = "INSERT INTO assets
					SET asset_description = '$asset_description', 
					serial_number = '$serial_number', 
					notes = '$notes', 
					actions_id = '$actions_id', 
					barcode = '$barcode', 
					categories_id = '$categories_id', 
					info = '$info'";
					// we don't need to update the id as it's automatically generated
					// id = '$id' ";
					$this->dbo->query($query);
				} else {
					$query = "UPDATE assets
					SET asset_description = '$asset_description', 
					serial_number = '$serial_number', 
					notes = '$notes', 
					actions_id = '$actions_id', 
					barcode = '$barcode',  
					info = '$info', 
					categories_id = '$categories_id'
					WHERE id = '$id' ";
					$this->dbo->query($query);
				}
			}
		} catch (PDOException $e) {
			$errorCode = -1;
			$errorMessage = "PDOException for updateAsset.";
		}



		$error["id"] = $errorCode;
		$error["message"] = $errorMessage;

		$data["error"] = $error;

		//$data["assets"] = $asset;
		$data["query"] = $query;

		$data = json_encode($data);

		return $data;
	}

	public function getAssetHistory($barcode)
	{
		$barcode = addslashes($barcode);

		$y = false;

		$errorCode = 0;
		$errorMessage = "";

		try {

			$query = "SELECT assets.asset_description, assets.notes, assets.serial_number, assets.barcode,
			assets_logged_out.out_time, assets_logged_out.due_time, assets_logged_out.in_time,
			borrowers.first_name, borrowers.last_name, borrowers.student_id, borrowers.dc_email, borrowers.other_email
			FROM assets, assets_logged_out, borrowers
			WHERE assets.id = assets_logged_out.assets_id
			AND assets_logged_out.borrowers_id = borrowers.id
			AND assets.barcode = '$barcode' 
			ORDER BY assets_logged_out.out_time DESC";

			foreach ($this->dbo->query($query) as $row) {
				$assets_asset_description = stripslashes($row[0]);
				$assets_notes = htmlspecialchars(stripslashes($row[1]));
				$assets_serial_number = htmlspecialchars(stripslashes($row[2]));
				$assets_barcode = htmlspecialchars(stripslashes($row[3]));
				$assets_logged_out_out_time = date("D d M Y h:i:s A", htmlspecialchars(stripslashes($row[4])));
				$assets_logged_out_due_time = date("D d M Y h:i:s A", htmlspecialchars(stripslashes($row[5])));
				$assets_logged_out_in_time = date("D d M Y h:i:s A", htmlspecialchars(stripslashes($row[6])));
				$borrowers_first_name = htmlspecialchars(stripslashes($row[7]));
				$borrowers_last_name = htmlspecialchars(stripslashes($row[8]));
				$borrowers_student_id = htmlspecialchars(stripslashes($row[9]));
				$borrowers_dc_email = htmlspecialchars(stripslashes($row[10]));
				$borrowers_other_email = htmlspecialchars(stripslashes($row[11]));
				if ($row[5] < mktime()) {
					$overdue = 1;
				} else {
					$overdue = 0;
				}
				if ($row[6] == "0") {
					$assets_logged_out_in_time = "";
				}

				$history["asset_description"] = $assets_asset_description;
				$history["assets_notes"] = $assets_notes;
				$history["assets_serial_number"] = $assets_serial_number;
				$history["assets_barcode"] = $assets_barcode;
				$history["assets_logged_out_out_time"] = $assets_logged_out_out_time;
				$history["assets_logged_out_due_time"] = $assets_logged_out_due_time;
				$history["assets_logged_out_in_time"] = $assets_logged_out_in_time;
				$history["borrowers_first_name"] = $borrowers_first_name;
				$history["borrowers_last_name"] = $borrowers_last_name;
				$history["borrowers_student_id"] = $borrowers_student_id;
				$history["borrowers_dc_email"] = $borrowers_dc_email;
				$history["borrowers_other_email"] = $borrowers_other_email;
				$history["overdue"] = $overdue;


				$a_history[] = $history;
			}
		} catch (PDOException $e) {
			$errorCode = -1;
			$errorMessage = "PDOException for getBorrowerHistory.";
		}

		$error["id"] = $errorCode;
		$error["message"] = $errorMessage;

		$data["error"] = $error;

		$data["query"] = $query;
		$data["a_history"] = $a_history;

		$data = json_encode($data);

		return $data;
	}

	public function getAssetsLoggedOut($option)
	{


		$option = stripslashes($option);


		$errorCode = 0;
		$errorMessage = "";

		// building current time
		$current_month = date("m");
		$current_day = date("d");
		$current_year = date("Y");
		$current_hour = date("H");
		$current_minute = date("i");
		$current_second = date("s");

		// current time
		$current_time = mktime($current_hour, $current_minute, $current_second, $current_month, $current_day, $current_year);

		// current time - 10 days
		$ten_days_ago = mktime($current_hour, $current_minute, $current_second, $current_month, $current_day - 10, $current_year);

		try {

			$query = "";

			switch ($option) {
				case 0:
					$query = "SELECT assets_logged_out.assets_id, assets_logged_out.borrowers_id, assets_logged_out.out_time, assets_logged_out.in_time, assets_logged_out.due_time, first_name, last_name, student_id, dc_email, other_email, asset_description, serial_number, barcode
					FROM assets_logged_out, assets, borrowers 
					WHERE assets_logged_out.assets_id = assets.id 
					AND assets_logged_out.borrowers_id = borrowers.id 
					ORDER BY assets_logged_out.out_time";
					break;

				case 1:
					$query = "SELECT assets_logged_out.assets_id, assets_logged_out.borrowers_id, assets_logged_out.out_time, assets_logged_out.in_time, assets_logged_out.due_time, first_name, last_name, student_id, dc_email, other_email, asset_description, serial_number, barcode
					FROM assets_logged_out, assets, borrowers 
					WHERE assets_logged_out.assets_id = assets.id 
					AND assets_logged_out.borrowers_id = borrowers.id 
					AND assets_logged_out.due_time < $current_time ";
					break;

				case 2:
					$query = "SELECT assets_logged_out.assets_id, assets_logged_out.borrowers_id, assets_logged_out.out_time, assets_logged_out.in_time, assets_logged_out.due_time, first_name, last_name, student_id, dc_email, other_email, asset_description, serial_number, barcode
					FROM assets_logged_out, assets, borrowers 
					WHERE assets_logged_out.assets_id = assets.id 
					AND assets_logged_out.borrowers_id = borrowers.id 
					AND assets_logged_out.due_time < $ten_days_ago ";
					break;

				default:
					$query = "SELECT assets_logged_out.assets_id, assets_logged_out.borrowers_id, assets_logged_out.out_time, assets_logged_out.in_time, assets_logged_out.due_time, first_name, last_name, student_id, dc_email, other_email, asset_description, serial_number, barcode
					FROM assets_logged_out, assets, borrowers 
					WHERE assets_logged_out.assets_id = assets.id 
					AND assets_logged_out.borrowers_id = borrowers.id 
					ORDER BY assets_logged_out.out_time";
					break;
			}

			foreach ($this->dbo->query($query) as $row) {
				$assets_id = stripslashes($row[0]);
				$borrowers_id = stripslashes($row[1]);
				$out_time = date("D d M Y h:i:s A", htmlspecialchars(stripslashes($row[2])));
				$in_time = date("D d M Y h:i:s A", htmlspecialchars(stripslashes($row[3])));
				$due_time = date("D d M Y h:i:s A", htmlspecialchars(stripslashes($row[4])));
				$first_name = stripslashes($row[5]);
				$last_name = stripslashes($row[6]);
				$student_id = stripslashes($row[7]);
				$dc_email = stripslashes($row[8]);
				$other_email = stripslashes($row[9]);
				$asset_description = stripslashes($row[10]);
				$serial_number = stripslashes($row[11]);
				$barcode = stripslashes($row[12]);

				$log["assets_id"] = $assets_id;
				$log["borrowers_id"] = $borrowers_id;

				$log["out_time"] = $out_time;
				$log["due_time"] = $due_time;
				$log["in_time"] = $in_time;
				$log["first_name"] = $first_name;
				$log["last_name"] = $last_name;
				$log["student_id"] = $student_id;
				$log["dc_email"] = $dc_email;
				$log["other_email"] = $other_email;
				$log["asset_description"] = $asset_description;
				$log["serial_number"] = $serial_number;
				$log["barcode"] = $barcode;



				// $query2 = "SELECT first_name, last_name, student_id, dc_email, other_email
				// 		FROM borrowers
				// 		WHERE student_id = '$borrowers_id' ";

				// foreach ($this->dbo->query($query2) as $row2) {
				// 	$first_name = stripslashes($row2[0]);
				// 	$last_name = stripslashes($row2[1]);
				// 	$student_id = stripslashes($row2[2]);
				// 	$dc_email = stripslashes($row2[3]);
				// 	$other_email = stripslashes($row2[4]);

				// 	$log["first_name"] = $first_name;
				// 	$log["last_name"] = $last_name;
				// 	$log["student_id"] = $student_id;
				// 	$log["dc_email"] = $dc_email;
				// 	$log["other_email"] = $other_email;
				// }

				// $query3 = "SELECT id, asset_description, serial_number, barcode
				// 		FROM assets
				// 		WHERE id = '$assets_id' ";

				// foreach ($this->dbo->query($query3) as $row3) {
				// 	$asset_id = stripslashes($row3[0]);
				// 	$asset_description = stripslashes($row3[1]);
				// 	$serial_number = stripslashes($row3[2]);
				// 	$barcode = stripslashes($row3[3]);

				// 	$log["asset_id"] = $asset_id;
				// 	$log["asset_description"] = $asset_description;
				// 	$log["serial_number"] = $serial_number;
				// 	$log["barcode"] = $barcode;
				// }

				$logs[] = $log;
			}
		} catch (PDOException $e) {
			$errorCode = -1;
			$errorMessage = "PDOException for getRooms.";
		}

		$err["id"] = $errorCode;
		$err["message"] = $errorMessage;

		$data["error"] = $err;
		$data["query"] = $query;

		$data["logs"] = $logs;

		$data = json_encode($data);

		return $data;
	}

	// groups
	public function getGroups()
	{

		$y = false;

		$errorCode = 0;
		$errorMessage = "";

		try {

			$query = "SELECT id, name, programs_id, year
					FROM groups
					ORDER BY programs_id, year";

			foreach ($this->dbo->query($query) as $row) {

				$groups_id = stripslashes($row[0]);
				$groups_name = htmlspecialchars(stripslashes($row[1]));
				$groups_programs_id = htmlspecialchars(stripslashes($row[2]));
				$groups_year = htmlspecialchars(stripslashes($row[3]));


				$query2 = "SELECT name
						FROM programs
						WHERE id = '$groups_programs_id' ";

				foreach ($this->dbo->query($query2) as $row2) {
					$programs_name = stripslashes($row2[0]);
					$group["program_name"] = $programs_name;
				}

				$group["id"] = $groups_id;
				$group["name"] = $groups_name;
				$group["program_id"] = $groups_programs_id;
				$group["year"] = $groups_year;

				$groups[] = $group;
			}
		} catch (PDOException $e) {
			$errorCode = -1;
			$errorMessage = "PDOException for getGroups.";
		}

		$error["id"] = $errorCode;
		$error["message"] = $errorMessage;

		$data["error"] = $error;

		$data["query"] = $query;
		$data["groups"] = $groups;

		$data = json_encode($data);

		return $data;
	}

	public function updateGroup($newName, $rowId)
	{

		$newName = stripslashes($newName);
		$rowId = stripslashes($rowId);

		$errorCode = 0;
		$errorMessage = "Update successfully!";

		try {

			$query = "UPDATE groups
				SET name = '$newName'
				WHERE id = '$rowId' ";

			$this->dbo->query($query);
		} catch (PDOException $e) {
			$errorCode = -1;
			$errorMessage = "PDOException for updateBorrower.";
		}

		$error["id"] = $errorCode;
		$error["message"] = $errorMessage;

		$data["error"] = $error;
		$data["query"] = $query;

		$data = json_encode($data);

		return $data;
	}

	// borrower status
	public function getBorrowerStatus()
	{

		$errorCode = 0;
		$errorMessage = "";

		try {

			$query = "SELECT borrower_status_id, name
					FROM borrower_status
					ORDER BY borrower_status_id";

			foreach ($this->dbo->query($query) as $row) {
				$borrower_status_id = stripslashes($row[0]);
				$name = stripslashes($row[1]);


				$status["borrower_status_id"] = $borrower_status_id;
				$status["name"] = $name;

				$statuses[] = $status;
			}
		} catch (PDOException $e) {
			$errorCode = -1;
			$errorMessage = "PDOException for getGroups.";
		}

		$error["id"] = $errorCode;
		$error["message"] = $errorMessage;

		$data["error"] = $error;

		$data["query"] = $query;
		$data["borrower_status"] = $statuses;

		$data = json_encode($data);

		return $data;
	}

	// program names
	public function getProgramNames()
	{

		$errorCode = 0;
		$errorMessage = "";

		try {

			$query = "SELECT id, name
					FROM programs
					ORDER BY id";

			foreach ($this->dbo->query($query) as $row) {
				$id = stripslashes($row[0]);
				$name = stripslashes($row[1]);

				$program["id"] = $id;
				$program["name"] = $name;

				$programs[] = $program;
			}
		} catch (PDOException $e) {
			$errorCode = -1;
			$errorMessage = "PDOException for getGroups.";
		}

		$error["id"] = $errorCode;
		$error["message"] = $errorMessage;

		$data["error"] = $error;

		$data["query"] = $query;
		$data["programs"] = $programs;

		$data = json_encode($data);

		return $data;
	}

	// long term signout
	public function insertLongTermSignout($borrower_id, $asset_id, $date, $support_out_id, $loan_notes)
	{

		$borrower_id = stripslashes($borrower_id);
		$asset_id = stripslashes($asset_id);
		$date = stripslashes($date);
		$support_out_id = stripslashes($support_out_id);
		$loan_notes = stripslashes($loan_notes);

		// concatenate the js date with 15:00:00 to form the due time
		$due_time = $date . " 15:00:00";
		// convert due time into dateObject
		$dueDateObject = new DateTime($due_time);
		// use the method timestamp() to convert dueDateObject into timestamp
		$dueTimeStamp =  $dueDateObject->getTimestamp();

		$current_hour = date("H");
		$current_minute = date("i");
		$current_second = date("s");
		// $due_date[] = explode("-", $date);

		$errorCode = 0;
		$errorMessage = "Long term signout succeed";


		// $due_time = mktime($due_date[0], $due_date[1], $due_date[2], $current_hour, $current_minute, $current_second);

		$out_time = mktime($current_hour, $current_minute, $current_second, date("m"), date("d"), date("Y"));

		if (!empty($date)) {
			try {

				$query = "INSERT INTO assets_logged_out
							SET borrowers_id = '$borrower_id', 
							assets_id = '$asset_id', 
							out_time = '$out_time', 
							due_time = '$dueTimeStamp', 
							support_out = '$support_out_id', 
							loan_notes = '$loan_notes' ";

				$this->dbo->query($query);
			} catch (PDOException $e) {
				$errorCode = -1;
				$errorMessage = "Long term signout failed";
			}
		} else {
			$errorCode = 2;
			$errorMessage = "No due date sent";
		}


		$error["id"] = $errorCode;
		$error["message"] = $errorMessage;

		$data["error"] = $error;

		$data["query"] = $query;

		$data = json_encode($data);

		return $data;
	}

	// print history
	public function getPrintHistory($student_id)
	{

		$y = false;

		$errorCode = 0;
		$errorMessage = "";

		$student_id = stripslashes($student_id);


		$totalD = 0;

		try {
			$query = "SELECT prints, dateme, support
			FROM print_copies
			WHERE student_id = '$student_id'";

			foreach ($this->dbo->query($query) as $row) {
				$print_copies_prints = stripslashes($row[0]);
				// $datemeD = stripslashes($row[1]);
				$datemeD = date("D d M Y h:i:s A", htmlspecialchars(stripslashes($row[1])));
				$supportD = stripslashes($row[2]);
				$totalD = $totalD + $print_copies_prints;

				// construct an array exclusively for debit funds
				$print_dataD_item["datemeD"] = $datemeD;
				$print_dataD_item["supportD"] = $supportD;
				// keep track of the dollar amount for every single debit record
				$print_dataD_item["dollarAmountD"] = number_format($print_copies_prints * 0.01, 2);

				// $print_dataD_item["totalD"] = number_format($totalD * 0.01, 2);
				$print_dataD[] = $print_dataD_item;
			}

			$print_dataD_item["totalD"] = number_format($totalD  * 0.01, 2);


			$totalC = 0;

			$query = "SELECT copies, invoice_id, support, dateme
					FROM add_copies
					WHERE student_id = '$student_id' ";

			foreach ($this->dbo->query($query) as $row) {
				$print_copies_prints = stripslashes($row[0]);
				$invoice_id = stripslashes($row[1]);
				$supportC = stripslashes($row[2]);
				$datemeC =  date("D d M Y h:i:s A", htmlspecialchars(stripslashes($row[3])));
				$totalC = $totalC + $print_copies_prints;

				// construct an array exclusively for credit funds
				$print_dataC_item["invoice_id"] = $invoice_id;
				$print_dataC_item["supportC"] = $supportC;
				$print_dataC_item["datemeC"] = $datemeC;
				// keep track of the dollar amount for every single credit record
				$print_dataC_item["dollarAmountC"] = number_format($print_copies_prints * 0.01, 2);
				// $print_dataC_item["totalC"] = number_format($totalC  * 0.01, 2);

				$print_dataC[] = $print_dataC_item;
			}

			$print_dataC_item["totalC"] = number_format($totalC  * 0.01, 2);


			$total = $totalC - $totalD;
			// format the total dollar amount 
			$total = number_format($total * 0.01, 2);

			// $total = "Credit Available : $" . number_format($total * .01, 2);


			// $print_data["total"] = $total
			// $print[] = array_merge($print_dataD, $print_dataC);

		} catch (PDOException $e) {
			$errorCode = -1;
			$errorMessage = "PDOException for getBorrower.";
		}

		$error["id"] = $errorCode;
		$error["message"] = $errorMessage;

		$data["error"] = $error;

		$data["query"] = $query;
		$data["print_dataD"] = $print_dataD;
		$data["print_data_totalD"] = $print_dataD_item["totalD"];

		$data["print_dataC"] = $print_dataC;
		$data["print_data_totalC"] = $print_dataC_item["totalC"];

		$data["total"] = $total;

		$data = json_encode($data);

		return $data;
	}

	// rooms
	public function updateRoom($id, $room_name, $room_desc, $bottom_notes, $block_size, $block_start, $nodb, $restr_day, $restr_night)
	{


		$errorCode = 0;
		$errorMessage = "Room updated!";


		try {
			if ($id) {
				if ($id == -1) {
					$query = "INSERT INTO rooms
					SET name = '$room_name',
					descript = '$room_desc', 
					block_size = '$block_size', 
					block_start = '$block_start', 
					block_number = '$nodb', 
					restrictions_day = '$restr_day', 
					restrictions_night = '$restr_night', 
					notes_bottom = '$bottom_notes' ";

					// we don't need to update the id as it's automatically generated

					$this->dbo->query($query);
				} else {
					$query = "UPDATE rooms
					SET name = '$room_name',
					descript = '$room_desc', 
					block_size = '$block_size', 
					block_start = '$block_start', 
					block_number = '$nodb', 
					restrictions_day = '$restr_day', 
					restrictions_night = '$restr_night', 
					notes_bottom = '$bottom_notes'
					WHERE id = '$id' ";
					$this->dbo->query($query);
				}
			}
		} catch (PDOException $e) {
			$errorCode = -1;
			$errorMessage = "PDOException for updateRoom.";
		}



		$error["id"] = $errorCode;
		$error["message"] = $errorMessage;

		$data["error"] = $error;

		//$data["assets"] = $asset;
		$data["query"] = $query;

		$data = json_encode($data);

		return $data;
	}

	public function getRooms()
	{

		$errorCode = 0;
		$errorMessage = "";

		try {

			$query = "SELECT id, name
			FROM rooms
			ORDER BY name ";
			foreach ($this->dbo->query($query) as $row) {
				$room_id = $this->convertFancyQuotes(stripslashes($row[0]));
				$room_name = $this->convertFancyQuotes(stripslashes($row[1]));

				$room["id"] = $room_id;
				$room["name"] = $room_name;

				$rooms[] = $room;
			}
		} catch (PDOException $e) {
			$errorCode = -1;
			$errorMessage = "PDOException for getRooms.";
		}

		$err["id"] = $errorCode;
		$err["message"] = $errorMessage;

		$data["error"] = $err;
		$data["query"] = $query;
		$data["rooms"] = $rooms;

		$data = json_encode($data);

		return $data;
	}

	public function getRoom($room_id)
	{

		$room_id = stripslashes($room_id);

		$y = false;

		$errorCode = 0;
		$errorMessage = "";

		try {

			$query = "SELECT id, name, descript, block_size, block_start, block_number, restrictions_day, restrictions_night, notes_bottom
			FROM rooms
			WHERE id = '$room_id' ";

			foreach ($this->dbo->query($query) as $row) {
				$id = stripslashes($row[0]);
				$name = stripslashes($row[1]);
				$description = htmlspecialchars(stripslashes($row[2]));
				$block_size = stripslashes($row[3]);
				$block_start = stripslashes($row[4]);
				$block_number = stripslashes($row[5]);
				$restrictions_day = stripslashes($row[6]);
				$restrictions_night = stripslashes($row[7]);
				$notes_bottom = stripslashes($row[8]);


				$room["id"] = $id;
				$room["name"] = $name;
				$room["description"] = $description;
				$room["block_size"] = $block_size;
				$room["block_start"] = $block_start;
				$room["block_number"] = $block_number;
				$room["restrictions_day"] = $restrictions_day;
				$room["restrictions_night"] = $restrictions_night;
				$room["notes_bottom"] = $notes_bottom;
			}
		} catch (PDOException $e) {
			$errorCode = -1;
			$errorMessage = "PDOException for getRoom.";
		}

		$error["id"] = $errorCode;
		$error["message"] = $errorMessage;

		$data["error"] = $error;

		$data["query"] = $query;
		$data["room"] = $room;

		$data = json_encode($data);

		return $data;
	}

	public function deleteRoom($room_id)
	{

		$room_id = stripslashes($room_id);

		$y = false;

		$errorCode = 0;
		$errorMessage = "Room Deleted.";

		try {
			$query = "DELETE FROM rooms
					WHERE id = '$room_id' ";

			$this->dbo->query($query);
		} catch (PDOException $e) {
			$errorCode = -1;
			$errorMessage = "PDOException for delete Room.";
		}

		$error["id"] = $errorCode;
		$error["message"] = $errorMessage;

		$data["error"] = $error;

		$data["query"] = $query;

		$data = json_encode($data);

		return $data;
	}

	// groups reserve
	public function getGroupsReserve()
	{
		$errorCode = 0;
		$errorMessage = "";

		try {

			$query = "SELECT assets_description, id
			FROM groups_reserve
			GROUP BY assets_description
			ORDER BY assets_description ";

			foreach ($this->dbo->query($query) as $row) {
				$asset_desc = stripslashes($row[0]);
				$id = stripslashes($row[1]);

				$groups_reserve_info["asset_desc"] = $asset_desc;
				$groups_reserve_info["id"] = $id;

				$groups_reserve[] = $groups_reserve_info;
			}
		} catch (PDOException $e) {
			$errorCode = -1;
			$errorMessage = "PDOException for getGroupsReserve.";
		}

		$err["id"] = $errorCode;
		$err["message"] = $errorMessage;

		$data["error"] = $err;
		$data["query"] = $query;
		$data["groups_reserve"] = $groups_reserve;

		$data = json_encode($data);

		return $data;
	}

	public function getGroupReserve($assets_desc)
	{

		$assets_desc = htmlspecialchars(stripslashes($assets_desc));

		$errorCode = 0;
		$errorMessage = "";

		$list = "";

		try {

			$query = "SELECT groups_id
					FROM groups_reserve
					WHERE assets_description = '$assets_desc' ";

			foreach ($this->dbo->query($query) as $row) {
				if (empty($list)) {
					$list = $row[0];
				} else {
					$list .= "," . $row[0];
				}
			}



			// left side	
			$query = "SELECT groups.id, groups.name, groups.programs_id, groups.year
			FROM groups
			WHERE groups.id IN ($list)
			ORDER BY groups.year, groups.name, groups.programs_id ";

			foreach ($this->dbo->query($query) as $row) {
				$group_id = stripslashes($row[0]);
				$group_name = stripslashes($row[1]);
				$programs_id = stripslashes($row[2]);
				$group_year = stripslashes($row[3]);

				// to get the program names
				$query2 = "SELECT id, name
							FROM programs
							WHERE id = '$programs_id' ";

				foreach ($this->dbo->query($query2) as $row2) {
					$program_name = stripslashes($row2[1]);
				}


				$group_info["id"] = $group_id;
				$group_info["name"] = $group_name;
				$group_info["program_name"] = $program_name;
				$group_info["year"] = $group_year;


				$groups_reserve[] = $group_info;
			}



			// right side 	
			$query = "SELECT groups.id, groups.name, groups.programs_id, groups.year
			FROM groups
			WHERE groups.id NOT IN ($list)
			ORDER BY groups.year, groups.name, groups.programs_id ";

			foreach ($this->dbo->query($query) as $row) {

				$group_id = stripslashes($row[0]);
				$group_name = stripslashes($row[1]);
				$programs_id = stripslashes($row[2]);
				$group_year = stripslashes($row[3]);

				// to get the program names
				$query2 = "SELECT id, name
							FROM programs
							WHERE id = '$programs_id' ";

				foreach ($this->dbo->query($query2) as $row2) {
					$program_name = stripslashes($row2[1]);
				}


				$group_info["id"] = $group_id;
				$group_info["name"] = $group_name;
				$group_info["program_name"] = $program_name;
				$group_info["year"] = $group_year;


				$groups_can_be_reserve[] = $group_info;
			}
		} catch (PDOException $e) {
			$errorCode = -1;
			$errorMessage = "PDOException for getGroupReserve.";
		}

		$err["id"] = $errorCode;
		$err["message"] = $errorMessage;

		$data["error"] = $err;
		$data["query"] = $query;

		// left table
		$data["groups_reserve"] = $groups_reserve;

		// right table
		$data["groups_can_be_reserve"] = $groups_can_be_reserve;

		$data = json_encode($data);

		return $data;
	}

	public function updateGroupReserve($groups_id, $groups_name, $groups_desc)
	{

		$groups_id = array_map('stripslashes', $groups_id);
		$groups_name = array_map('stripslashes', $groups_name);
		$groups_desc = stripslashes($groups_desc);


		$errorCode = 0;
		$errorMessage = "Updated Successfully.";

		try {

			$query = "DELETE FROM groups_reserve
						WHERE assets_description = '$groups_desc' ";

			$this->dbo->query($query);

			/*
			$groups_id[22];
			$groups_id[2];
			$groups_id[3453];
			*/

			//for($i = 0; $i < count($groups_id); $i++){}
			// using foreach instead of for loop,
			// for loop would have skipped elements.
			// also, foreach loop is natively faster on PHP than for loop


			foreach ($groups_id as $key => $value) {
				/* $query = "INSERT INTO groups_reserve
							SET name = '$groups_name[$key]',
							groups_id = '$groups_id[$key]'"; */

				$query = "INSERT INTO groups_reserve
						SET assets_description = '$groups_desc',
						groups_id = '$groups_id[$key]'";

				$this->dbo->query($query);
			}
		} catch (PDOException $e) {
			$errorCode = -1;
			$errorMessage = "PDOException for getBorrower.";
		}

		$error["id"] = $errorCode;
		$error["message"] = $errorMessage;

		$data["error"] = $error;
		$data["query"] = $query;

		$data = json_encode($data);

		return $data;
	}

	// assets reserve
	public function getAssetsReserves()
	{
		$errorCode = 0;
		$errorMessage = "";

		try {

			// top menu 

			$query = "SELECT asset_description
			FROM assets
			GROUP BY asset_description
			ORDER BY asset_description ";

			foreach ($this->dbo->query($query) as $row) {
				$asset_desc = stripslashes($row[0]);

				$assets_reserves_info_menu["asset_desc"] = $asset_desc;

				$assets_reserves_menu[] = $assets_reserves_info_menu;
			}

			// lower table

			$query = "SELECT asset_description, id, categories_id, notes
			FROM assets
			GROUP BY asset_description
			ORDER BY asset_description ";

			foreach ($this->dbo->query($query) as $row) {
				$asset_desc = stripslashes($row[0]);
				$id = stripslashes($row[1]);
				$categories_id = stripslashes($row[2]);
				$notes = stripslashes($row[3]);

				$assets_reserves_info["asset_desc"] = $asset_desc;
				$assets_reserves_info["id"] = $id;
				$assets_reserves_info["categories_id"] = $categories_id;
				$assets_reserves_info["notes"] = $notes;


				// make a reserve record if it does not exist

				// set $x = true
				// search for $asset_desc in assets_reserve
				// $x = false

				// if x = true
				// then insert record of assests description into assets_reserve.

				$x = true;

				$query2 = "SELECT COUNT(asset_description)
							FROM assets_reserve
							WHERE asset_description = '$asset_desc' ";

				foreach ($this->dbo->query($query2) as $row2) {
					$reserve_entry_count = stripslashes($row2[0]);

					if ($reserve_entry_count > 0) {
						$x = false;
					}
				}

				if ($x) {
					$query2 = "INSERT INTO assets_reserve
								SET asset_description = '$asset_desc',
								reserve = 0,
								replacement_cost = 0.00";
					$this->dbo->query($query2);
				}


				// categories
				$query2 = "SELECT name 
							FROM categories
							WHERE id = '$categories_id' ";

				foreach ($this->dbo->query($query2) as $row2) {
					$category_name = stripslashes($row2[0]);
					$assets_reserves_info["category_name"] = $category_name;
				}


				// counting the total assets
				$query2 = "SELECT COUNT(asset_description)
							FROM assets
							WHERE asset_description = '$asset_desc' ";

				foreach ($this->dbo->query($query2) as $row2) {
					$assets_reserves_count = stripslashes($row2[0]);
					$assets_reserves_info["assets_reserves_count"] = $assets_reserves_count;
				}

				// counting the active assets
				$query2 = "SELECT COUNT(asset_description)
							FROM assets
							WHERE actions_id = '1'
							AND asset_description = '$asset_desc' ";

				foreach ($this->dbo->query($query2) as $row2) {
					$assets_reserves_active = stripslashes($row2[0]);
					$assets_reserves_info["assets_reserves_active"] = $assets_reserves_active;
				}

				// counting the reserved assets and getting the replacement cost
				$query2 = "SELECT reserve, replacement_cost
							FROM assets_reserve
							WHERE asset_description = '$asset_desc' 
							ORDER BY replacement_cost DESC
							LIMIT 0,1";

				foreach ($this->dbo->query($query2) as $row2) {
					$assets_reserves_reserved = stripslashes($row2[0]);
					$assets_reserves_replacement_cost = stripslashes($row2[1]);

					$assets_reserves_info["assets_reserves_reserved"] = $assets_reserves_reserved;
					$assets_reserves_info["assets_reserves_replacement_cost"] = $assets_reserves_replacement_cost;
				}


				// getting the bin count
				$query2 = "SELECT count(bin)
							FROM assets_bin
							GROUP BY asset_description 
							WHERE asset_description = '$asset_desc' ";

				foreach ($this->dbo->query($query2) as $row2) {
					$assets_reserves_bin = stripslashes($row2[0]);

					$assets_reserves_info["assets_reserves_bin"] = $assets_reserves_bin;
				}



				// getting the out count from assets_logged_out
				$query2 = "SELECT id
							FROM assets
							WHERE assets_description = '$asset_desc' ";

				$list = "";
				foreach ($this->dbo->query($query) as $row) {
					if (empty($list)) {
						$list = $row[0];
					} else {
						$list .= "," . $row[0];
					}
				}

				$query2 = "SELECT count(assets_id)
							FROM assets_logged_out
							WHERE assets_id IN ($list) ";

				foreach ($this->dbo->query($query2) as $row2) {
					$assets_reserves_out_count = stripslashes($row2[0]);
				}

				$assets_reserves_out = $assets_reserves_out_count + $assets_reserves_info["assets_reserves_bin"];


				$assets_reserves_info["assets_reserves_out"] = $assets_reserves_out;



				// calculating the available

				$assets_reserves_info["assets_reserves_available"] = $assets_reserves_info["assets_reserves_active"] - $assets_reserves_info["assets_reserves_reserved"] - $assets_reserves_info["assets_reserves_out"];

				if ($assets_reserves_info["assets_reserves_available"] < 0) {
					$assets_reserves_info["assets_reserves_available"] = 0;
				}


				$assets_reserves[] = $assets_reserves_info;
			}
		} catch (PDOException $e) {
			$errorCode = -1;
			$errorMessage = "PDOException for getAssetsReserves.";
		}

		$err["id"] = $errorCode;
		$err["message"] = $errorMessage;

		$data["error"] = $err;
		$data["query"] = $query;
		$data["assets_reserves"] = $assets_reserves;
		$data["assets_reserves_menu"] = $assets_reserves_menu;
		$data = json_encode($data);

		return $data;
	}

	public function getAssetsReserve($asset_desc)
	{
		$asset_desc = stripslashes($asset_desc);

		$errorCode = 0;
		$errorMessage = "";

		try {
			$query = "SELECT asset_description, id, categories_id, notes, actions_id
			FROM assets
			WHERE asset_description = '$asset_desc'
			GROUP BY asset_description ";

			foreach ($this->dbo->query($query) as $row) {
				$reserve_asset_description = stripslashes($row[0]);
				$reserve_id = stripslashes($row[1]);
				$reserve_category_id = stripslashes($row[2]);
				$reserve_notes = stripslashes($row[3]);
				$reserve_actions_id = stripslashes($row[4]);

				$assets_reserve_data1["reserve_asset_description"] = $reserve_asset_description;
				$assets_reserve_data1["reserve_id"] = $reserve_id;
				$assets_reserve_data1["reserve_notes"] = $reserve_notes;
				$assets_reserve_data1["reserve_category_id"] = $reserve_category_id;


				// $assets_reserve1[] = $assets_reserve_data1;

				// there should be one record only for each searching result
				$assets_reserve1 = $assets_reserve_data1;

				// categories
				$query2 = "SELECT name 
							FROM categories
							WHERE id = '$reserve_category_id' ";

				foreach ($this->dbo->query($query2) as $row2) {
					$category_name = stripslashes($row2[0]);
					$category["category_name"] = $category_name;

					// $assets_reserve_categories[] = $category;

					// there should be one record only for each searching result
					$assets_reserve_categories = $category;
				}

				// actions
				$query2 = "SELECT name 
							FROM categories
							WHERE id = '$reserve_actions_id' ";

				foreach ($this->dbo->query($query2) as $row2) {
					$action_name = stripslashes($row2[0]);
					$action["action_name"] = $action_name;

					$assets_reserve_actions[] = $action;
				}

				// reserve count
				$query2 = "SELECT reserve, replacement_cost
							FROM assets_reserve
							WHERE asset_description = '$asset_desc'";

				foreach ($this->dbo->query($query2) as $row2) {
					$reserve_num = stripslashes($row2[0]);
					$reserve_replacement_cost = stripslashes($row2[1]);

					$assets_reserve_data2["reserve_num"] = $reserve_num;
					$assets_reserve_data2["reserve_replacement_cost"] = $reserve_replacement_cost;

					// $assets_reserve2[] = $assets_reserve_data2;
					// there should be one record only for each searching result
					$assets_reserve2 = $assets_reserve_data2;
				}
			}
		} catch (PDOException $e) {
			$errorCode = -1;
			$errorMessage = "PDOException for getAssetsReserve.";
		}

		$err["id"] = $errorCode;
		$err["message"] = $errorMessage;

		$data["error"] = $err;
		$data["query"] = $query;
		$data["query2"] = $query2;
		$data["assets_reserve1"] = $assets_reserve1;
		$data["assets_reserve2"] = $assets_reserve2;
		$data["assets_reserve_categories"] = $assets_reserve_categories;

		$data = json_encode($data);

		return $data;
	}

	public function updateAssetsReserve($asset_desc, $reserve, $category_id, $notes, $replacement_cost)
	{

		$asset_desc = stripslashes($asset_desc);
		$reserve = stripslashes($reserve);
		$category_id = stripslashes($category_id);
		$notes = stripslashes($notes);
		$replacement_cost = stripslashes($replacement_cost);

		$errorCode = 0;
		$errorMessage = "Asset reserve updated!";


		try {
			$query = "UPDATE assets
						SET categories_id = '$category_id',
						notes = '$notes'
						WHERE asset_description = '$asset_desc' ";

			$this->dbo->query($query);

			$query2 = "UPDATE assets_reserve
						SET reserve = '$reserve',
						replacement_cost = '$replacement_cost'
						WHERE asset_description = '$asset_desc' ";

			$this->dbo->query($query2);
		} catch (PDOException $e) {
			$errorCode = -1;
			$errorMessage = "PDOException for updateAssetsReserve.";
		}


		$error["id"] = $errorCode;
		$error["message"] = $errorMessage;

		$data["error"] = $error;

		$data["query"] = $query;
		$data["query2"] = $query2;

		$data = json_encode($data);

		return $data;
	}

	// inventory
	public function getInventory($option)
	{
		$errorCode = 0;
		$errorMessage = "";

		$option = stripslashes($option);

		// options
		// 0 returns all everything
		// 1 for not inventoried
		// 2 for active
		// 3 neither inventoried active

		try {

			$query = "";

			switch ($option) {
				case 0:
					$query = "SELECT assets.id, assets.barcode, assets.serial_number, assets.asset_description, assets.notes, inventory.scan_date, actions.name, (SELECT out_time FROM assets_logged_out WHERE assets_logged_out.assets_id = assets.id ORDER BY out_time DESC LIMIT 1) AS out_time, (SELECT in_time FROM assets_logged_out WHERE assets_logged_out.assets_id = assets.id ORDER BY out_time DESC LIMIT 1) AS in_time

					FROM assets, inventory, actions 
					WHERE assets.id = inventory.assets_id 
					AND assets.actions_id = actions.id 
					";
					break;

					// for not inventoried
				case 1:
					$query = "SELECT assets.id, assets.barcode, assets.serial_number, assets.asset_description, assets.notes, inventory.scan_date, actions.name, (SELECT out_time FROM assets_logged_out WHERE assets_logged_out.assets_id = assets.id ORDER BY out_time DESC LIMIT 1) AS out_time, (SELECT in_time FROM assets_logged_out WHERE assets_logged_out.assets_id = assets.id ORDER BY out_time DESC LIMIT 1) AS in_time

					FROM assets
					JOIN actions ON assets.actions_id = actions.id
					LEFT JOIN inventory ON assets.id=inventory.assets_id WHERE inventory.scan_date = 0
					
					";
					break;

					// for active 
				case 2:
					$query = "SELECT assets.id, assets.barcode, assets.serial_number, assets.asset_description, assets.notes, inventory.scan_date, actions.name, (SELECT out_time FROM assets_logged_out WHERE assets_logged_out.assets_id = assets.id ORDER BY out_time DESC LIMIT 1) AS out_time, (SELECT in_time FROM assets_logged_out WHERE assets_logged_out.assets_id = assets.id ORDER BY out_time DESC LIMIT 1) AS in_time

					FROM assets
					JOIN actions ON actions.id = 1
					JOIN inventory ON assets.id = inventory.assets_id

					";
					break;

					// not inventoried and active
				case 3:
					$query = "SELECT assets.id, assets.barcode, assets.serial_number, assets.asset_description, assets.notes, inventory.scan_date, actions.name, (SELECT out_time FROM assets_logged_out WHERE assets_logged_out.assets_id = assets.id ORDER BY out_time DESC LIMIT 1) AS out_time, (SELECT in_time FROM assets_logged_out WHERE assets_logged_out.assets_id = assets.id ORDER BY out_time DESC LIMIT 1) AS in_time

					FROM assets
					JOIN actions ON actions.id = assets.actions_id AND actions.id = 1
					LEFT JOIN inventory ON assets.id=inventory.assets_id WHERE inventory.scan_date = 0
					";
					break;

				default:
					$query = "SELECT assets.id, assets.barcode, assets.serial_number, assets.asset_description, assets.notes, inventory.scan_date, actions.name, (SELECT out_time FROM assets_logged_out WHERE assets_logged_out.assets_id = assets.id ORDER BY out_time DESC LIMIT 1) AS out_time, (SELECT in_time FROM assets_logged_out WHERE assets_logged_out.assets_id = assets.id ORDER BY out_time DESC LIMIT 1) AS in_time

					FROM assets, inventory, actions 
					WHERE assets.id = inventory.assets_id 
					AND assets.actions_id = actions.id 
					";
					break;
			}

			foreach ($this->dbo->query($query) as $row) {
				$asset_id = stripslashes($row[0]);
				$barcode = stripslashes($row[1]);
				$serial_number = stripslashes($row[2]);
				$asset_description = stripslashes($row[3]);
				$notes = stripslashes($row[4]);
				$actions_name = stripslashes($row[6]);
				$out_time = date("D d M Y h:i:s A", htmlspecialchars(stripslashes($row[7])));
				$in_time = date("D d M Y h:i:s A", htmlspecialchars(stripslashes($row[8])));
				$scan_date = stripslashes($row[5]);

				if (!$scan_date) {
					$inventory["scan_date"] = "";
				} else if ($scan_date == 0) {
					$inventory["scan_date"] = "";
				} else {
					$inventory["scan_date"] = date("D d M Y h:i:s A", htmlspecialchars(stripslashes($scan_date)));
				}


				$inventory["asset_id"] = $asset_id;
				$inventory["barcode"] = $barcode;
				$inventory["serial_number"] = $serial_number;
				$inventory["asset_description"] = $asset_description;
				$inventory["notes"] = $notes;
				// $inventory["scan_date"] = $scan_date;
				$inventory["actions_name"] = $actions_name;
				$inventory["out_time"] = $out_time;
				$inventory["in_time"] = $in_time;

				if (!$out_time) {
					$inventory["out_time"] = "";
				}
				if (!$in_time) {
					$inventory["in_time"] = "";
				}


				$inventories[] = $inventory;
			}
		} catch (PDOException $e) {
			$errorCode = -1;
			$errorMessage = "PDOException for getInventory.";
		}

		$error["id"] = $errorCode;
		$error["message"] = $errorMessage;

		$data["error"] = $error;
		$data["query"] = $query;
		$data["inventories"] = $inventories;

		$data = json_encode($data);

		return $data;
	}

	public function newInventory()
	{
		$errorCode = 0;
		$errorMessage = "";

		try {

			// emptying the inventory
			$query = "DELETE FROM inventory";
			$this->dbo->query($query);


			// getting all the assets id's from assets table
			$query = "SELECT id
						FROM assets";

			$assets_to_be = "";

			foreach ($this->dbo->query($query) as $row) {
				$assets_id = stripslashes($row[0]);

				if (empty($assets_to_be)) {
					$assets_to_be = $row[0];
				} else {
					$assets_to_be .= "," . $row[0];
				}

				// inserting the above-mentioned assets id's into inventory table
				$query2 = "INSERT INTO inventory
							SET assets_id = '$assets_id',
							scan_date = '' ";

				$this->dbo->query($query2);
			}
		} catch (PDOException $e) {
			$errorCode = -1;
			$errorMessage = "PDOException for getInventory.";
		}

		$err["id"] = $errorCode;
		$err["message"] = $errorMessage;
		$data["query"] = $query;
		$data["query2"] = $query2;
		$data["assets_to_be"] = $assets_to_be;
		$data["error"] = $err;

		$data = json_encode($data);
		return $data;
	}

	public function verifyBarcode($barcode)
	{
		$barcode = stripslashes($barcode);


		$errorCode = 0;
		$errorMessage = "";

		$current_month = date("m");
		$current_day = date("d");
		$current_year = date("Y");
		$current_hour = date("H");
		$current_minute = date("i");
		$current_second = date("s");

		$current_time = mktime($current_hour, $current_minute, $current_second, $current_month, $current_day, $current_year);



		try {

			$query = "SELECT id
						FROM assets
						WHERE barcode = '$barcode'";

			foreach ($this->dbo->query($query) as $row) {
				$assets_id = stripslashes($row[0]);
			}


			if ($assets_id) {
				$query2 = "SELECT scan_date, assets_id
							FROM inventory
							WHERE assets_id = '$assets_id' ";

				foreach ($this->dbo->query($query2) as $row2) {
					$scan_date = stripslashes($row2[0]);
					$inventory_assets_id = stripslashes($row2[1]);


					// if the result is null, the following lines of code never went through
					//print $row2;

				}

				if (is_null($inventory_assets_id)) {
					$errorCode = 3;
					$errorMessage = "Asset not in the inventory.";
				} else if ($scan_date == 0) {
					// do query here to insert scandate
					$query3 = "UPDATE inventory
								SET scan_date = '$current_time' 
								WHERE assets_id = '$assets_id' ";
					$this->dbo->query($query3);

					$errorCode = 0;
					$errorMessage = "Asset Scanned Successfully.";
				} else {
					$errorCode = 1;
					$errorMessage = "Asset already scanned.";
				}
			} else {
				$errorCode = 2;
				$errorMessage = "Asset not found in Assets table.";
			}
		} catch (PDOException $e) {
			$errorCode = -1;
			$errorMessage = "PDOException for verifyBarcode.";
		}



		$error["id"] = $errorCode;
		$error["message"] = $errorMessage;

		$data["error"] = $error;
		$data["query"] = $query;
		$data["query2"] = $query2;
		$data["query3"] = $query3;
		$data["barcode"] = $barcode;
		$data["inventory_assets_id"] = $inventory_assets_id;
		$data["scan_date"] = $scan_date;

		$data = json_encode($data);

		return $data;
	}

	public function getInventoryDetail()
	{
		$errorCode = 0;
		$errorMessage = "";

		/* try {

			// top table
			$query = "SELECT asset_description, id, categories_id, notes
			FROM assets
			GROUP BY asset_description
			ORDER BY asset_description ";

			foreach ($this->dbo->query($query) as $row) {
				$asset_desc = stripslashes($row[0]);
				$assets_id = stripslashes($row[1]);
				$categories_id = stripslashes($row[2]);
				$notes = stripslashes($row[3]);

				$inventory_detail["asset_desc"] = $asset_desc;
				$inventory_detail["id"] = $assets_id;
				$inventory_detail["categories_id"] = $categories_id;
				$inventory_detail["notes"] = $notes;


				// categories
				$query2 = "SELECT name 
							FROM categories
							WHERE id = '$categories_id' ";

				foreach ($this->dbo->query($query2) as $row2) {
					$category_name = stripslashes($row2[0]);
					$inventory_detail["category_name"] = $category_name;
				}

				// counting the total assets
				$query2 = "SELECT COUNT(asset_description)
							FROM assets
							WHERE asset_description = '$asset_desc' ";

				foreach ($this->dbo->query($query2) as $row2) {
					$assets_count = stripslashes($row2[0]);
					$inventory_detail["assets_count"] = $assets_count;
				}

				// counting the active assets
				$query2 = "SELECT COUNT(asset_description)
							FROM assets
							WHERE actions_id = '1'
							AND asset_description = '$asset_desc' ";

				foreach ($this->dbo->query($query2) as $row2) {
					$assets_active = stripslashes($row2[0]);
					$inventory_detail["assets_active"] = $assets_active;
				}

				// counting the lost assets
				$query2 = "SELECT COUNT(asset_description)
							FROM assets
							WHERE actions_id = '2'
							AND asset_description = '$asset_desc' ";

				foreach ($this->dbo->query($query2) as $row2) {
					$assets_lost = stripslashes($row2[0]);
					$inventory_detail["assets_lost"] = $assets_lost;
				}

				// counting the repair assets
				$query2 = "SELECT COUNT(asset_description)
							FROM assets
							WHERE actions_id = '3'
							AND asset_description = '$asset_desc' ";

				foreach ($this->dbo->query($query2) as $row2) {
					$assets_repair = stripslashes($row2[0]);
					$inventory_detail["assets_repair"] = $assets_repair;
				}

				// counting the retired assets
				$query2 = "SELECT COUNT(asset_description)
							FROM assets
							WHERE actions_id = '4'
							AND asset_description = '$asset_desc' ";

				foreach ($this->dbo->query($query2) as $row2) {
					$assets_retired = stripslashes($row2[0]);
					$inventory_detail["assets_retired"] = $assets_retired;
				}

				// counting the do not display assets
				$query2 = "SELECT COUNT(asset_description)
							FROM assets
							WHERE actions_id = '5'
							AND asset_description = '$asset_desc' ";

				foreach ($this->dbo->query($query2) as $row2) {
					$assets_do_not_display = stripslashes($row2[0]);
					$inventory_detail["assets_do_not_display"] = $assets_do_not_display;
				}

				// counting the reserved assets and getting the replacement cost
				$query2 = "SELECT reserve
							FROM assets_reserve
							WHERE asset_description = '$asset_desc'
							ORDER BY reserve
							LIMIT 0,1";

				foreach ($this->dbo->query($query2) as $row2) {
					$assets_reserved = stripslashes($row2[0]);
					$inventory_detail["assets_reserved"] = $assets_reserved;
				}


				// getting the bin count
				$query2 = "SELECT count(bin)
							FROM assets_bin
							GROUP BY asset_description 
							WHERE asset_description = '$asset_desc' ";

				foreach ($this->dbo->query($query2) as $row2) {
					$assets_bin = stripslashes($row2[0]);

					$inventory_detail["assets_bin"] = $assets_bin;
				}

				// getting the out count from assets_logged_out
				$query2 = "SELECT id
							FROM assets
							WHERE asset_description = '$asset_desc' ";

				$list = "";
				foreach ($this->dbo->query($query2) as $row2) {
					if (empty($list)) {
						$list = $row2[0];
					} else {
						$list .= "," . $row2[0];
					}
				}

				$query2 = "SELECT count(assets_id)
							FROM assets_logged_out
							WHERE assets_id IN ($list)
							AND in_time = 0
							ORDER BY in_time
							";

				foreach ($this->dbo->query($query2) as $row2) {
					$assets_out_count = stripslashes($row2[0]);
				}

				$assets_out = $assets_out_count + $inventory_detail["assets_bin"];


				$inventory_detail["assets_out"] = $assets_out;


				// calculating the available
				$inventory_detail["assets_available"] = $inventory_detail["assets_active"] -
					$inventory_detail["assets_reserved"] -
					$inventory_detail["assets_out"];

				if ($inventory_detail["assets_available"] < 0) {
					$inventory_detail["assets_available"] = 0;
				}


				// getting the scanned count
				$query2 = "SELECT COUNT(scan_date)
							FROM inventory
							WHERE scan_date > 0
							AND assets_id IN ($list)
				";

				foreach ($this->dbo->query($query2) as $row2) {
					$assets_scanned_count = stripslashes($row2[0]);
					$inventory_detail["assets_scanned_count"] = $assets_scanned_count;
				}

				$inventory[] = $inventory_detail;
			}
		}  */

		try {

			// top table
			$query = "SELECT assets.asset_description, assets.id, assets.categories_id, assets.notes
			FROM assets
			JOIN inventory ON assets.id = inventory.assets_id
			GROUP BY assets.asset_description
			-- ORDER BY assets.asset_description 
			
			";

			foreach ($this->dbo->query($query) as $row) {
				$asset_desc = stripslashes($row[0]);
				$assets_id = stripslashes($row[1]);
				$categories_id = stripslashes($row[2]);
				$notes = stripslashes($row[3]);

				$inventory_detail["asset_desc"] = $asset_desc;
				$inventory_detail["id"] = $assets_id;
				$inventory_detail["categories_id"] = $categories_id;
				$inventory_detail["notes"] = $notes;


				// categories
				$query2 = "SELECT name 
				FROM categories
				WHERE id = '$categories_id' ";

				foreach ($this->dbo->query($query2) as $row2) {
					$category_name = stripslashes($row2[0]);
					$inventory_detail["category_name"] = $category_name;
				}

				// counting the total assets
				$query2 = "SELECT COUNT(asset_description)
				FROM assets
				WHERE asset_description = '$asset_desc' ";

				foreach ($this->dbo->query($query2) as $row2) {
					$assets_count = stripslashes($row2[0]);
					$inventory_detail["assets_count"] = $assets_count;
				}

				// counting the active assets
				$query2 = "SELECT COUNT(asset_description)
				FROM assets
				WHERE actions_id = '1'
				AND asset_description = '$asset_desc' ";

				foreach ($this->dbo->query($query2) as $row2) {
					$assets_active = stripslashes($row2[0]);
					$inventory_detail["assets_active"] = $assets_active;
				}

				// counting the lost assets
				$query2 = "SELECT COUNT(asset_description)
				FROM assets
				WHERE actions_id = '2'
				AND asset_description = '$asset_desc' ";

				foreach ($this->dbo->query($query2) as $row2) {
					$assets_lost = stripslashes($row2[0]);
					$inventory_detail["assets_lost"] = $assets_lost;
				}

				// counting the repair assets
				$query2 = "SELECT COUNT(asset_description)
				FROM assets
				WHERE actions_id = '3'
				AND asset_description = '$asset_desc' ";

				foreach ($this->dbo->query($query2) as $row2) {
					$assets_repair = stripslashes($row2[0]);
					$inventory_detail["assets_repair"] = $assets_repair;
				}

				// counting the retired assets
				$query2 = "SELECT COUNT(asset_description)
				FROM assets
				WHERE actions_id = '4'
				AND asset_description = '$asset_desc' ";

				foreach ($this->dbo->query($query2) as $row2) {
					$assets_retired = stripslashes($row2[0]);
					$inventory_detail["assets_retired"] = $assets_retired;
				}

				// counting the do not display assets
				$query2 = "SELECT COUNT(asset_description)
				FROM assets
				WHERE actions_id = '5'
				AND asset_description = '$asset_desc' ";

				foreach ($this->dbo->query($query2) as $row2) {
					$assets_do_not_display = stripslashes($row2[0]);
					$inventory_detail["assets_do_not_display"] = $assets_do_not_display;
				}

				// counting the reserved assets and getting the replacement cost
				$query2 = "SELECT reserve
				FROM assets_reserve
				WHERE asset_description = '$asset_desc'
				ORDER BY reserve
				LIMIT 0,1";

				foreach ($this->dbo->query($query2) as $row2) {
					$assets_reserved = stripslashes($row2[0]);
					$inventory_detail["assets_reserved"] = $assets_reserved;
				}


				// getting the bin count
				$query2 = "SELECT count(bin)
				FROM assets_bin
				GROUP BY asset_description 
				WHERE asset_description = '$asset_desc' ";

				foreach ($this->dbo->query($query2) as $row2) {
					$assets_bin = stripslashes($row2[0]);

					$inventory_detail["assets_bin"] = $assets_bin;
				}

				// getting the out count from assets_logged_out
				$query2 = "SELECT id
				FROM assets
				WHERE asset_description = '$asset_desc' ";

				$list = "";
				foreach ($this->dbo->query($query2) as $row2) {
					if (empty($list)) {
						$list = $row2[0];
					} else {
						$list .= "," . $row2[0];
					}
				}

				$query2 = "SELECT count(assets_id)
				FROM assets_logged_out
				WHERE assets_id IN ($list)
				AND in_time = 0
				ORDER BY in_time
				";

				foreach ($this->dbo->query($query2) as $row2) {
					$assets_out_count = stripslashes($row2[0]);
				}

				$assets_out = $assets_out_count + $inventory_detail["assets_bin"];


				$inventory_detail["assets_out"] = $assets_out;


				// calculating the available
				$inventory_detail["assets_available"] = $inventory_detail["assets_active"] -
					$inventory_detail["assets_reserved"] -
					$inventory_detail["assets_out"];

				if ($inventory_detail["assets_available"] < 0) {
					$inventory_detail["assets_available"] = 0;
				}


				// getting the scanned count
				$query2 = "SELECT COUNT(scan_date)
				FROM inventory
				WHERE scan_date > 0
				AND assets_id IN ($list)
	";

				foreach ($this->dbo->query($query2) as $row2) {
					$assets_scanned_count = stripslashes($row2[0]);
					$inventory_detail["assets_scanned_count"] = $assets_scanned_count;
				}

				$inventory[] = $inventory_detail;
			}
		} catch (PDOException $e) {
			$errorCode = -1;
			$errorMessage = "PDOException for getAssetsReserves.";
		}

		$err["id"] = $errorCode;
		$err["message"] = $errorMessage;

		$data["error"] = $err;
		$data["query"] = $query;
		$data["query2"] = $query2;
		$data["inventory_details"] = $inventory;
		$data = json_encode($data);

		return $data;
	}

	public function getSingleInventoryDetail($assets_desc)
	{

		$assets_desc = stripslashes($assets_desc);

		// $assets_desc = "Zoom H4n Pro - Audio Kit";

		$errorCode = 0;
		$errorMessage = "";

		try {
			$query = "SELECT id
					FROM assets
					WHERE asset_description = '$assets_desc'";

			// list of all the id's with the passed description 
			$list = "";
			foreach ($this->dbo->query($query) as $row) {
				if (empty($list)) {
					$list = $row[0];
				} else {
					$list .= "," . $row[0];
				}
			}

			$query2 = "SELECT barcode, serial_number, categories_id, actions_id, notes, info, id
				FROM assets
				WHERE id IN ($list)
				";

			foreach ($this->dbo->query($query2) as $row2) {
				$barcode = stripslashes($row2[0]);
				$serial_number = stripslashes($row2[1]);
				$categories_id = stripslashes($row2[2]);
				$actions_id = stripslashes($row2[3]);
				$notes = stripslashes($row2[4]);
				$info = stripslashes($row2[5]);
				$the_asset_id = stripslashes($row2[6]);


				$inventory_single_info["barcode"] = $barcode;
				$inventory_single_info["serial_number"] = $serial_number;
				$inventory_single_info["notes"] = $notes;
				$inventory_single_info["info"] = $info;
				$inventory_single_info["the_asset_id"] = $the_asset_id;


				// categories
				$query3 = "SELECT name 
							FROM categories
							WHERE id = '$categories_id' ";

				foreach ($this->dbo->query($query3) as $row3) {
					$category_name = stripslashes($row3[0]);
					$inventory_single_info["category_name"] = $category_name;
				}

				// categories
				$query3 = "SELECT name 
							FROM actions
							WHERE id = '$actions_id' ";

				foreach ($this->dbo->query($query3) as $row3) {
					$action_name = stripslashes($row3[0]);
					$inventory_single_info["action_name"] = $action_name;
				}

				// signed out date
				$query3 = "SELECT out_time, borrowers_id
							FROM assets_logged_out
							WHERE assets_id = '$the_asset_id'
							AND in_time = 0
							ORDER BY out_time
							LIMIT 0,1
							";


				$out_time = "";
				$inventory_single_info["out_time"] = $out_time;
				$borrowers_id = "";
				$inventory_single_info["borrowers_id"] = $borrowers_id;
				foreach ($this->dbo->query($query3) as $row3) {
					$out_time = date("D d M Y h:i:s A", htmlspecialchars(stripslashes($row[0])));
					$borrowers_id = stripslashes($row3[1]);

					if (empty($out_time)) {
						$inventory_single_info["out_time"] = "";
					} else {
						$inventory_single_info["out_time"] = $out_time;
					}

					// $inventory_single_info["out_time"] = $out_time;


					if (empty($borrowers_id)) {
						$inventory_single_info["borrowers_id"] = "";
					} else {
						$inventory_single_info["borrowers_id"] = $borrowers_id;
					}
				}


				if (empty($borrowers_id)) {
					$inventory_single_info["borrowers_id"] = "";
					$inventory_single_info["borrowers_first_name"] = "";
					$inventory_single_info["borrowers_last_name"] = "";
				} else {
					// getting borrower name
					$query3 = "SELECT first_name, last_name
							FROM borrowers
							WHERE id = '$borrowers_id'
							";

					$borrowers_first_name = "";
					$borrowers_last_name = "";
					$inventory_single_info["borrowers_first_name"] = $borrowers_first_name;
					$inventory_single_info["borrowers_last_name"] = $borrowers_last_name;
					foreach ($this->dbo->query($query3) as $row3) {
						$borrowers_first_name = stripslashes($row3[0]);
						$borrowers_last_name = stripslashes($row3[1]);

						if (empty($borrowers_first_name)) {
							$inventory_single_info["borrowers_first_name"] = "";
						} else {
							$inventory_single_info["borrowers_first_name"] = $borrowers_first_name;
						}

						if (empty($borrowers_last_name)) {
							$inventory_single_info["borrowers_last_name"] = "";
						} else {
							$inventory_single_info["borrowers_last_name"] = $borrowers_last_name;
						}
					}
				}




				// getting the scan date
				$query3 = "SELECT scan_date
							FROM inventory
							WHERE assets_id = '$the_asset_id'
					";

				$scan_date = "";
				$inventory_single_info["scan_date"] = $scan_date;

				foreach ($this->dbo->query($query3) as $row3) {
					$scan_date = date("D d M Y h:i:s A", htmlspecialchars(stripslashes($row3[0])));

					if (empty($scan_date)) {
						$inventory_single_info["scan_date"] = "";
					} else {
						$inventory_single_info["scan_date"] = $scan_date;
					}


					// $scan_date = stripslashes($row3[0]);
					$inventory_single_info["scan_date"] = $scan_date;
				}

				$inventory_single[] = $inventory_single_info;
			}
		} catch (PDOException $e) {
			$errorCode = -1;
			$errorMessage = "PDOException for getAssetsReserves.";
		}

		$err["id"] = $errorCode;
		$err["message"] = $errorMessage;

		$data["error"] = $err;
		// $data["query"] = $query;
		$data["query2"] = $query2;
		$data["query3"] = $query3;
		$data["inventory_single"] = $inventory_single;

		$data = json_encode($data);

		return $data;
	}



	/* inventory
	public function getInventory($option)
	{
		$errorCode = 0;
		$errorMessage = "";

		$inventoried_ids = "";
		$active_assets_id = "";

		try {

			// listing of not inventoried assets
			$query = "SELECT assets_id 
			FROM inventory";

			foreach ($this->dbo->query($query) as $row) {
				if (empty($inventoried_ids)) {
					$inventoried_ids = $row[0];
				} else {
					$inventoried_ids .= "," . $row[0];
				}
			}

			// listing of active inventorized assets
			$query2 = "SELECT id 
			FROM assets
			WHERE actions_id = 1
			AND id IN ($inventoried_ids)";

			foreach ($this->dbo->query($query2) as $row) {
				if (empty($active_assets_id)) {
					$active_assets_id = $row[0];
				} else {
					$active_assets_id .= "," . $row[0];
				}
			}

			$inventoried_active = $inventoried_ids . "," . $active_assets_id;




			switch ($option) {
				case 0:
					$query3 = "SELECT barcode, serial_number, asset_description, notes, id
					FROM assets
					WHERE id IN ($inventoried_ids)
					";

					break;

				case 1: // not inventoried
					$query3 = "SELECT barcode, serial_number, asset_description, notes, id
					FROM assets
					WHERE id NOT IN ($inventoried_ids)
					";


					break;

				case 2: // active
					$query3 = "SELECT barcode, serial_number, asset_description, notes, id
					FROM assets
					WHERE id IN ($inventoried_active)
					";


					break;

				case 3: // not inventoried and active
					$query3 = "SELECT id
					FROM assets
					WHERE id NOT IN ($inventoried_ids)
					
					";

					foreach ($this->dbo->query($query3) as $row) {
						$not_inventoried_id = stripslashes($row[0]);

					}

					$query3 = "SELECT barcode, serial_number, asset_description, notes, id
					FROM assets
					WHERE id IN ($active_assets_id)
					";

					break;

				default:
					$query3 = "SELECT barcode, serial_number, asset_description, notes, id
					FROM assets
					WHERE id IN ($inventoried_ids)
					";

					break;
			}

			foreach ($this->dbo->query($query3) as $row) {
				$barcode = stripslashes($row[0]);
				$serial_number = stripslashes($row[1]);
				$asset_description = stripslashes($row[2]);
				$notes = stripslashes($row[3]);
				$assets_id = stripslashes($row[4]);


				$query4 = "SELECT scan_date
				FROM inventory
				WHERE assets_id = '$assets_id'
				";

				foreach ($this->dbo->query($query4) as $row4) {
					$scan_date = date("D d M Y h:i:s A", htmlspecialchars(stripslashes($row4[0])));

					$inventory_info["scan_date"] = $scan_date;
				}

				$query5 = "SELECT name
				FROM actions
				WHERE id = '$assets_id'
				";

				foreach ($this->dbo->query($query5) as $row5) {
					$action_name = $this->convertFancyQuotes(stripslashes($row4[0]));

					$inventory_info["action_name"] = $action_name;
				}


				$inventory_info["barcode"] = $barcode;
				$inventory_info["serial_number"] = $serial_number;
				$inventory_info["asset_description"] = $asset_description;
				$inventory_info["notes"] = $notes;
				$inventory_info["assets_id"] = $assets_id;


				// assets_logged_out information from here
				$query6 = "SELECT out_time, in_time
							FROM assets_logged_out
							WHERE assets_id = '$assets_id'
							ORDER BY out_time DESC
							LIMIT 0,1";

				foreach ($this->dbo->query($query6) as $row6) {

					$out_time = date("D d M Y h:i:s A", htmlspecialchars(stripslashes($row5[0])));
					$in_time = date("D d M Y h:i:s A", htmlspecialchars(stripslashes($row5[1])));

					$inventory_info["out_time"] = $out_time;
					$inventory_info["in_time"] = $in_time;
				}

				$inventory[] = $inventory_info;
			}
		} catch (PDOException $e) {
			$errorCode = -1;
			$errorMessage = "PDOException for getInventory";
		}


		$error["id"] = $errorCode;
		$error["message"] = $errorMessage;

		$data["error"] = $error;
		$data["inventoried_ids"] = $inventoried_ids;
		$data["active_assets_id"] = $active_assets_id;
		$data["query"] = $query;
		$data["query2"] = $query2;
		$data["query3"] = $query3;
		$data["query4"] = $query4;
		$data["query5"] = $query5;
		$data["query6"] = $query6;
		$data["option"] = $option;
		$data["inventory"] = $inventory;

		$data = json_encode($data);

		return $data;
	} */
}
