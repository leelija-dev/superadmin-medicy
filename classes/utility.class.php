<?php

class Utility
{
	use DatabaseConnection;

	function getNameById($id)
	{
		try {
			if (isAdminId($id)) {
				$query = " SELECT fname,lname FROM `admin` WHERE `admin_id`= '$id' ";
			} else {
				$query = " SELECT fname, lname FROM `employees` WHERE `emp_id`= '$id' ";
			}
			$stmt = $this->conn->prepare($query);
			$stmt->execute();

			$res = $stmt->get_result();

			if ($res->num_rows > 0) {
				while ($result = $res->fetch_assoc()) {
					$name = $result;
				}
				$fullName = $name['fname'] . ' ' . $name['lname'];

				$stmt->close();
				return $fullName;
			} else {
				$stmt->close();
				return '';
			}
		} catch (Exception $e) {
            echo "Error: " . $e->getMessage();
		}
	}

	##########################################################################################
	#
	#									Random Key
	#
	##########################################################################################

	/**
	 *	Generate the random key
	 *	Takes the lengh
	 */
	function randomkeys($length)
	{
		$key = '';
		$pattern = "1234567890ABCDEFGHIJKLMNOPQRSTUVWXYZ";
		for ($i = 0; $i < $length; $i++) {
			$key .= $pattern[rand(0, 35)];
		}
		return $key;
	} //

	/**	
	 *	Generating the random key without zero and O' as this might cerate confusion
	 *	Takes the length of the key as parameter.
	 *
	 *	@param
	 *			$key	Key to verify
	 *
	 *	@return string
	 */
	function randomkeys2($length)
	{
		$key = '';
		$pattern = "123456789ABCDEFGHIJKLMNPQRSTUVWXYZ";
		for ($i = 0; $i < $length; $i++) {
			$key .= $pattern[rand(0, 33)];
		}
		return $key;
	} //END OF GENERATING THE RANDOM NUMBER

	/**	
	 *	Generating the random key without zero and O' as this might cerate confusion
	 *	Takes the length of the key as parameter.
	 *
	 *	@param
	 *			$key	Key to verify
	 *
	 *	@return string
	 */
	function randomKeysByAlphabet($length)
	{
		$key = '';
		$pattern = "ABCDEFGHIJKLMNPQRSTUVWXYZ";
		for ($i = 0; $i < $length; $i++) {
			$key .= $pattern[rand(0, 24)];
		}
		return $key;
	} //END OF GENERATING THE RANDOM NUMBER


	/**	
	 *	Generating the random key without zero and O' as this might cerate confusion
	 *	Takes the length of the key as parameter.
	 *
	 *	@param
	 *			$key	Key to verify
	 *
	 *	@return string
	 */
	function randomKeysByNumber($length)
	{
		$key = '';
		$pattern = "1234567890";
		for ($i = 0; $i < $length; $i++) {
			$key .= $pattern[rand(0, 9)];
		}
		return $key;
	} //END OF GENERATING THE RANDOM NUMBER

	##########################################################################################
	#
	#				FILES 
	#				Updated for Image Upload. Date: July 15, 2007
	#
	##########################################################################################

	function fileUploadWithRename($FILE, $path)
	{

		/*GENERATING UNIQUE NAME*/
		$randNum = $this->randomkeys(8);

		if (isset($FILE['name'])) {

			$file = explode('.', $FILE['name']);
			$file_name = $file[0];

			//count the number of element in array
			$num = (int)count($file);

			//Getting the file extentions, applicable when the user put 2 or 3 dots before 
			//the extension like 1.jpg.jpg
			$file_extension = $file[$num - 1];

			//replace the file name with teh unique name
			$newName = $file_name . "-" . $randNum . "." . $file_extension;

			if (move_uploaded_file($FILE['tmp_name'], $path . $newName)) {
				return $path . $newName;
			} else {
				return false;
			}
		}
	} //eof



	/**
	 *	Check validity of a file. Whether file fall into a particular type or not.
	 *	
	 *	@param
	 *			$type_arr		The file type array
	 *			$fileName		Name of the file or the post file
	 *
	 *	@return string
	 */
	function checkValidFile($type, $fileName)
	{
		$msg 		= '';

		//image array
		$img_arr	= array("image/x-png", "image/jpeg", "image/gif", "image/tiff", "image/pjpeg", "image/jpg", "image/png");


		//document type array
		$doc_arr	= array("text/plain", "application/msword", "application/pdf", "application/postscript", "application/rtf");

		$file_type	= $fileName['type'];

		switch ($type) {
			case 'ANY':
				$msg	= 'SU001';
				break;
			case 'IMG':
				if (in_array($file_type, $img_arr)) {
					$msg	= 'SU001';
				} else {
					$msg	= 'ER001' . $file_type;
				}
				break;
			case 'DOC':
				if (in_array($file_type, $doc_arr)) {
					$msg	= 'SU001';
				} else {
					$msg	= 'ER001';
				}
				break;
			default:
				$msg	= 'ER001';
		} //switch


		//return the message
		return $msg;
	} //eof


	function unlinkFile($path)
	{
		if ($path != '') {
			if (is_file($path)) {
				unlink($path);
				return 'SU001';
			} else {
				return 'ER001';
			}
		}
		return 'ER001';
	}



	// function read_file_docx($filename)
	// {

	// 	$striped_content = '';
	// 	$content = '';

	// 	if (!$filename || !file_exists($filename)) return false;

	// 	$zip = zip_open($filename);

	// 	if (!$zip || is_numeric($zip)) return false;

	// 	while ($zip_entry = zip_read($zip)) {

	// 		if (zip_entry_open($zip, $zip_entry) == FALSE) continue;

	// 		if (zip_entry_name($zip_entry) != "word/document.xml") continue;

	// 		$content .= zip_entry_read($zip_entry, zip_entry_filesize($zip_entry));

	// 		zip_entry_close($zip_entry);
	// 	} // end while

	// 	zip_close($zip);

	// 	//echo $content;
	// 	//echo "<hr>";
	// 	//file_put_contents('1.xml', $content);

	// 	$content = str_replace('</w:r></w:p></w:tc><w:tc>', " ", $content);
	// 	$content = str_replace('</w:r></w:p>', "\r\n", $content);
	// 	$striped_content = strip_tags($content);

	// 	return $striped_content;
	// }



	// function read_doc_file($filename)
	// {
	// 	if (file_exists($filename)) {
	// 		if (($fh = fopen($filename, 'r')) !== false) {
	// 			$headers = fread($fh, 0xA00);

	// 			// 1 = (ord(n)*1) ; Document has from 0 to 255 characters
	// 			$n1 = (ord($headers[0x21C]) - 1);

	// 			// 1 = ((ord(n)-8)*256) ; Document has from 256 to 63743 characters
	// 			$n2 = ((ord($headers[0x21D]) - 8) * 256);

	// 			// 1 = ((ord(n)*256)*256) ; Document has from 63744 to 16775423 characters
	// 			$n3 = ((ord($headers[0x21E]) * 256) * 256);

	// 			// 1 = (((ord(n)*256)*256)*256) ; Document has from 16775424 to 4294965504 characters
	// 			$n4 = (((ord($headers[0x21F]) * 256) * 256) * 256);

	// 			// Total length of text in the document
	// 			$textLength = ($n1 + $n2 + $n3 + $n4);

	// 			$extracted_plaintext = fread($fh, $textLength);

	// 			// simple print character stream without new lines
	// 			//echo $extracted_plaintext;

	// 			// if you want to see your paragraphs in a new line, do this
	// 			return nl2br($extracted_plaintext);
	// 			// need more spacing after each paragraph use another nl2br
	// 		}
	// 	}
	// }

	/**
	 *	This function will allow user to download a file with file name, located in different
	 *	directory, associated with id. The page will open in separate link
	 *
	 *	@param
	 *		$keyId			Value of the key, the file is associated with
	 *		$keyCol			Name of the primary key column
	 *		$fileVal		Name of the file
	 *		$fileCol		Column Name of the file
	 *		$path			Path to the file
	 *		$toDownload		Which file to download
	 *		$table			Name of the table
	 *
	 *	@return NULL
	 */
	// function downloadFile($keyId, $keyCol, $fileCol, $path, $table)
	// {
	// 	$sql	= "SELECT " . $fileCol . " FROM " . $table . " WHERE " . $keyCol . " = '" . $keyId . "'";

	// 	$query	= $this->conn->query($sql);

	// 	if ($query->num_rows > 0) {

	// 		$result		= $query->fetch_array();
	// 		$fileVal	= $result[$fileCol];

	// 		if ($path == '') {
	// 			$path = $fileVal;
	// 		} else {
	// 			$path = $path . $fileVal;
	// 		}

	// 		ob_clean();
	// 		if (file_exists($path)) {

	// 			// header("Content-Disposition: attachment; filename=". basename($fileVal));
	// 			// header("Content-Length: " . filesize($path));
	// 			// header("Content-Type: " . filetype($path));

	// 			header($_SERVER["SERVER_PROTOCOL"] . " 200 OK");
	// 			header("Cache-Control: public"); // needed for internet explorer
	// 			header('Content-type: application/vnd.openxmlformats- officedocument.wordprocessingml.document');
	// 			header("Content-Transfer-Encoding: Binary");
	// 			header("Content-Length:" . filesize($path));
	// 			header("Content-Disposition: attachment; filename=" .  basename($fileVal));
	// 			readfile($path);
	// 			die();

	// 			// readfile($path);
	// 			echo "<javascript>
	// 					document.write('Please wait, while download is in process.'); 
	// 					document.write('Closing window.');
	// 					this.window.close();
	// 				  </javascript>";
	// 		} else {

	// 			echo "<javascript>document.write('No file found. Closing window.');
	// 				  this.window.close();</javascript>";
	// 		}
	// 	} else {
	// 		echo "<javascript>document.write('No file found. Closing window.');
	// 			 this.window.close();</javascript>";
	// 	}
	// } //eof

	/////////////////////////////////////////////////////////////////////////////////////
	//
	//						                Rename Files
	//
	/////////////////////////////////////////////////////////////////////////////////////
	/**
	 *	Generate unique file name before uploading in the server
	 */
	// function getNewName($fileName, $fileIndex)
	// {
	// 	$newName = '';

	// 	//manipulate the file name
	// 	$timestamp 	= time();
	// 	$randNum 	= $this->randomkeys(8);

	// 	$file = explode('.', $fileName['name']);
	// 	$file_name = $file[0];

	// 	//counting the number of element in the array
	// 	$num = (int)count($file);

	// 	//getting the file extension, applicable specially when user puts 2 or 3 dots before the extension,
	// 	// like 1.jpg.jpg
	// 	$file_extension = $file[$num - 1];

	// 	if (strtolower($file_extension) == 'gif') {
	// 		$file_extension = 'jpg';
	// 	}

	// 	//generate unique file name
	// 	$newName = $fileIndex . "_" . $timestamp . "_" . $randNum . "." . $file_extension;

	// 	//return the value
	// 	return $newName;
	// } //eof


	/**
	 *	Generate unique file name before uploading in the server. This name will be generating 
	 *	automatically, highly usable for SEO.
	 */
	// function getNewName2($fileName, $fileIndex, $id, $name)
	// {
	// 	$newName = '';

	// 	//manipulate the file name
	// 	$timestamp 	= time();
	// 	$randNum 	= $this->randomkeys(5);

	// 	$file = explode('.', $fileName['name']);
	// 	$file_name = $file[0];

	// 	$type_arr = array(
	// 		"'", ":", "\"", "\\", "~", "`", "!", "@", "#", "$", "%", "^", "&", "*", "+", "|",
	// 		";", ",", "<", ">", "?", "\/", "/", "{", "}", "="
	// 	);

	// 	$name	= str_replace($type_arr, "", $name);
	// 	$name	= str_replace(" ", "-", $name);

	// 	//counting the number of element in the array
	// 	$num = (int)count($file);


	// 	/*getting the file extension, applicable specially when user puts 2 or 3 dots before the
	// 	 extension, like 1.jpg.jpg*/
	// 	$file_extension = $file[$num - 1];
	// 	$file_extension = 'jpg';

	// 	//generate unique file name
	// 	if ($fileIndex != '') {
	// 		$newName = $name . '-' . $fileIndex . "-" . $id . "" . $randNum . "." . $file_extension;
	// 	} else {
	// 		$newName = $name . "-" . $id . "" . $randNum . "." . $file_extension;
	// 	}


	// 	//return the value
	// 	return $newName;
	// } //eof


	/**
	 *	Generate unique file name before uploading in the server. This name will be generating 
	 *	automatically, highly usable for SEO.
	 */
	// function getNewName3($fileName, $fileIndex, $id, $name)
	// {
	// 	$newName = '';

	// 	//manipulate the file name
	// 	$timestamp 	= time();
	// 	$randNum 	= $this->randomkeys(5);

	// 	$file = explode('.', $fileName['name']);
	// 	$file_name = $file[0];

	// 	$type_arr = array(
	// 		"'", ":", "\"", "\\", "~", "`", "!", "@", "#", "$", "%", "^", "&", "*", "+", "|",
	// 		";", ",", "<", ">", "?", "\/", "/", "{", "}", "="
	// 	);

	// 	$name	= str_replace($type_arr, "", $name);
	// 	$name	= str_replace(" ", "-", $name);

	// 	//echo "Name is = ".$name;exit; /* */

	// 	//counting the number of element in the array
	// 	$num = (int)count($file);

	// 	//getting the file extension, applicable specially when user puts 2 or 3 dots before the extension,
	// 	// like 1.jpg.jpg
	// 	$file_extension = $file[$num - 1];


	// 	//generate unique file name
	// 	$newName = $name . '-' . $fileIndex . "-" . $id . "" . $randNum . "." . $file_extension;
	// 	//$newName = $fileIndex."-".$id."".$randNum.".".$file_extension;

	// 	//return the value
	// 	return $newName;
	// } //eof

	// /**
	//  *	Generate unique file name before uploading in the server. This name will be generating 
	//  *	automatically, highly usable for SEO.
	//  */
	// function getNewName4($fileName, $fileIndex, $id)
	// {
	// 	$newName = '';

	// 	//manipulate the file name
	// 	$timestamp 	= time();
	// 	$randNum 	= $this->randomkeys(5);

	// 	$file = explode('.', $fileName['name']);
	// 	$file_name = $file[0];

	// 	$type_arr = array(
	// 		"'", ":", "\"", "\\", "~", "`", "!", "@", "#", "$", "%", "^", "&", "*", "+", "|",
	// 		";", ",", "<", ">", "?", "\/", "/", "{", "}", "="
	// 	);

	// 	$name	= str_replace($type_arr, "", $file_name);
	// 	$name	= str_replace(" ", "-", $name);



	// 	//counting the number of element in the array
	// 	$num = (int)count($file);

	// 	//getting the file extension, applicable specially when user puts 2 or 3 dots 
	// 	//before the extension,
	// 	// like 1.jpg.jpg
	// 	$file_extension = $file[$num - 1];

	// 	if ($fileIndex == '') {
	// 		//generate unique file name
	// 		$newName = $name . "-" . $id . "" . $randNum . "." . $file_extension;
	// 	} else {
	// 		//generate unique file name
	// 		$newName = $name . '-' . $fileIndex . "-" . $id . "" . $randNum . "." . $file_extension;
	// 	}

	// 	//return the value
	// 	return $newName;
	// } //eof


	// /**
	//  * 	Check the proper date format 
	//  *
	//  *	@param
	//  *			$formDate	Date in the form field inserted by the user
	//  *
	//  *	@return string
	//  */
	// function checkDate($formDate)
	// {
	// 	if (strlen($formDate) != 8) {
	// 		return "DT001";
	// 	} else {
	// 		if (ereg("([0-9]{4})([0-9]{2})([0-9]{2})", $formDate)) {

	// 			//$DATE = explode("-",$date);
	// 			$DATE = $formDate;
	// 			$year 	= substr($DATE, 0, 4);
	// 			$month 	= substr($DATE, 4, 2);
	// 			$day 	= substr($DATE, 6, 2);


	// 			/* Converting month value to the numerical */
	// 			if (ereg("^0", $month)) {
	// 				$month = explode("0", $month);
	// 				$month = (int)$month[1];
	// 			} else {
	// 				$month = (int)$month;
	// 			} //month

	// 			/* Converting day value to the numerical */
	// 			if (ereg("^0", $day)) {
	// 				$day = explode("0", $day);
	// 				$day = (int)$day[1];
	// 			} else {
	// 				$day = (int)$day;
	// 			} //day



	// 			if (($day < 1) || ($day > 31)) {
	// 				return "DT002" . $day;
	// 			} else if (($month < 1) || ($month > 12)) {
	// 				return "DT003" . $month;
	// 			} else if (($month == 2) && ($day == 29) && (($year % 4) != 0)) {
	// 				return "DT004";
	// 			} else if (($month == 2) && ($day > 29)) {
	// 				return "DT004";
	// 			} else if ((($month == 4) && ($day > 30)) || (($month == 6) && ($day > 30)) || (($month == 9) && ($day > 30))
	// 				|| (($month == 11) && ($day > 30))
	// 			) {
	// 				return "DT005";
	// 			} else {
	// 				return "success";
	// 			}
	// 		} //ereg if
	// 		else {
	// 			return "DT000";
	// 		}
	// 	}
	// } //eof



	/**
	 *	Show the price format according to user set up 
	 *	@return string
	 */
	// function priceFormat($value)
	// {

	// 	$sql = "SELECT * FROM currencies WHERE primary_curr='Y'";
	// 	$query	= $this->conn->query($sql);
	// 	$result	= $query->fetch_array();

	// 	$postion		=  $result['position_pref'];
	// 	$type			=  $result['title'];
	// 	$symbol_left 	=  $result['symbol_left'];
	// 	$symbol_right 	=  $result['symbol_right'];

	// 	$format		= '';
	// 	if ($postion == 'left') {
	// 		$format = $symbol_left . " " . number_format($value, 2, '.', ',');
	// 	} else {
	// 		$format = number_format($value, 2, '.', ',') . " " . $symbol_left;
	// 	}
	// 	return $format;
	// } //END OF FORMATTING CURRENCY


	// /**
	//  *	Generate randon x coordinate
	//  *	@return int
	//  */
	// function genRandX($from, $limit)
	// {
	// 	$x = rand($from, $limit);
	// 	return $x;
	// } //end of generating x

	// /**
	//  *	Generate randon y coordinate
	//  *	@return int
	//  */
	// function genRandY($from, $limit)
	// {
	// 	$y = rand($from, $limit);
	// 	return $y;
	// } //end of generating y

	// /**
	//  *	Generate random length
	//  */
	// function genRandLength($start, $end)
	// {
	// 	$length = rand($start, $end);
	// 	return $length;
	// }

	// /**
	//  *	String format, return s at the end if found more than one else return the normal
	//  *	@return string
	//  */
	// function stringFormat($value, $str)
	// {
	// 	$this->$str = $str;
	// 	if ($value > 1) {
	// 		$str = $str . "s";
	// 	} else {
	// 		$str = $str;
	// 	}
	// 	return $str;
	// } //end of string format

	/**
	 *	Check the handle name agaist the reserved word, only admin occupy the reserve word.
	 *	@return string
	 */
	function checkReserveHandle($handle)
	{
		$handleArr = array(
			'admin', 'adminuser', 'administrator', 'webmaster', 'superuser', 'siteadmin', 'webadmin', 'moderator',
			'site', 'moderators', 'info', 'information', 'sex', 'fuck', 'fucking', 'boob', 'boobs', 'penis', 'tits', 'sexy', 'bitch',
			'ass', 'sexy', 'bastard', 'intercourse', 'vomit', 'peeyuk', 'suck', 'sucks', 'fucking_ass', 'asshole', 'mother', 'father',
			'fucker', 'teat', 'dick', 'cunt', 'vagina', 'pussy', 'pussey', 'exist', 'exit', 'error', 'ER001', 'true', 'false', 'null'
		);
		$data = '';
		if (in_array($handle, $handleArr)) {
			$data = 'FOUND';
		} else {
			$data = 'NOT_FOUND';
		}
		return $data;
	} //end of check reserve handle




	///////////////////////////////////////////////////////////////////////////////////////////////
	//
	//							Address Format	
	//
	///////////////////////////////////////////////////////////////////////////////////////////////
	/**
	 *	Write address in a proper format depending upon the values pass to the function
	 *	@return string
	 */
	function addressFormat($fname, $lname, $add1, $add2, $city, $state, $zip, $country, $phone, $email, $to_from)
	{
		$address_string = '';
		if ($to_from != '') {
			$address_string .= "<strong>" . $to_from . "</strong>:<br />";
		}
		if ($fname != '') {
			$address_string .= $fname;
		}
		if ($lname != '') {
			$address_string .= " " . $lname;
		}
		if ($add1 != '') {
			$address_string .= "<br />" . $add1;
		}
		if ($add2 != '') {
			$address_string .= "<br />" . $add2;
		}
		if ($city != '') {
			$address_string .= "<br />City: " . $city;
		}
		if ($state != '') {
			$address_string .= "<br />State: " . $state;
		}
		if ($zip != '') {
			$address_string .= "<br />Postal Code: " . $zip;
		}
		if ($country != '') {
			$address_string .= "<br />" . $country . "<br />";
		}
		if ($phone != '') {
			$address_string .= "<br /><strong>Phone</strong>: " . $phone;
		}
		if ($email != '') {
			$address_string .= "<br /><strong>E-mail</strong>: " . $email;
		}
		return $address_string;
	} //eof

	/**
	 *	Write address in a proper format depending upon the values pass to the function
	 *	@return string
	 */
	function addressFormat2(
		$fname,
		$lname,
		$add1,
		$add2,
		$add3,
		$city,
		$county,
		$state,
		$zip,
		$country,
		$phone1,
		$phone2,
		$tollFree,
		$fax,
		$email,
		$business_name
	) {
		$address_string = '';
		if ($business_name != '') {
			$address_string .= "<strong>" . $business_name . "</strong>:<br />";
		}
		if ($fname != '') {
			$address_string .= $fname;
		}
		if ($lname != '') {
			$address_string .= " " . $lname;
		}
		if ($add1 != '') {
			$address_string .= "<br />" . $add1;
		}
		if ($add2 != '') {
			$address_string .= "<br />" . $add2;
		}
		if ($add3 != '') {
			$address_string .= "<br />" . $add3;
		}
		if ($city != '') {
			$address_string .= "<br />" . $city;
		}
		if ($county != '') {
			$address_string .= ", " . $county;
		}
		if ($state != '') {
			$address_string .= "<br />" . $state;
		}
		if ($zip != '') {
			$address_string .= "<br />Postal Code: " . $zip;
		}
		if ($country != '') {
			$address_string .= "<br />" . $country . "<br />";
		}
		if ($phone1 != '') {
			$address_string .= "<br /><br />Phone: " . $phone1;
		}
		if ($phone2 != '') {
			$address_string .= "/" . $phone2;
		}
		if ($tollFree != '') {
			$address_string .= "<br />Toll Free No: " . $tollFree;
		}
		if ($fax != '') {
			$address_string .= "<br />Fax: " . $fax;
		}
		if ($email != '') {
			$address_string .= "<br /><strong>E-mail</strong>: " . $email;
		}
		return $address_string;
	} //eof


	/**
	 *	Show the selected value in a dropdown menu
	 *
	 *	@param
	 *			$name		The current name
	 *			$value		The has to select
	 */
	function selectString($name, $value)
	{
		$select	= '';
		if ($name == $value) {
			$select	= 'selected';
		} else {
			$select	= '';
		}
		return $select;
	} //eof


	/**
	 *	Show the selected value in radio button or check box
	 *
	 *	@param
	 *			$name		The current name
	 *			$value		The has to select
	 */
	function checkString($name, $value)
	{
		$check	= '';
		if ($name == $value) {
			$check	= 'checked';
		} else {
			$check	= '';
		}
		return $check;
	} //eof



	/**
	 *	This function print get variable
	 *	
	 *	@param
	 *			$var		Value associated with the the get variable
	 *
	 *	@return NULL
	 */
	function printGet($var)
	{
		if (isset($_GET[$var])) {
			echo $_GET[$var];
		}
	} //eof

	/**
	 *	This function is a modified version of previous function. It will 
	 *	find for a default value as well.
	 *	
	 *	@param
	 *			$var		Value associated with the the get variable
	 *			$default	Default value to be printed if the get is not found
	 *
	 *	@return NULL
	 */
	function printGet2($var, $default)
	{
		if (isset($_GET[$var])) {
			echo $_GET[$var];
		} else {
			echo $default;
		}
	} //eof


	/**
	 *	Return get variables
	 *
	 *	@param
	 *			$var		Variable name
	 *			$default	Default value to be printed if the get is not found
	 *
	 *	@return string
	 */
	function returnGetVar($var, $default)
	{
		if (isset($_GET[$var])) {
			$var = $_GET[$var];
		} else {
			$var = $default;
		}

		//return the result
		return $var;
	} //eof



	/**
	 *	Select among either of the 2 values for the URL var. This function is helpful when someone needs value 1 for condition 1 and value 2
	 *	if that condition does not match.
	 *
	 *	@date	November 07, 2011
	 *
	 *	@param
	 *			$varType			Type of variable. It can be among GET, POST and SESSION.
	 *			$varName			Name of the variable
	 *			$varVal				Value of the variable to test
	 *			$val_1				First value
	 *			$val_2				Second value
	 *
	 *	@return	string			
	 */
	function assignValueByTypeVar($varType, $varName, $varVal, $val_1, $val_2)
	{
		//declare vars
		$varStr		= '';

		switch ($varType) {
			case 'GET':
				//test the condition and declare val
				if ((isset($_GET[$varName])) && ($_GET[$varName] == $varVal)) {
					$varStr	= $val_1;
				} else {
					$varStr	= $val_2;
				}
				break;

			case 'POST':
				//test the condition and declare val
				if ((isset($_POST[$varName])) && ($_POST[$varName] == $varVal)) {
					$varStr	= $val_1;
				} else {
					$varStr	= $val_2;
				}
				break;


			case 'SESSION':
				//test the condition and declare val
				if ((isset($_SESSION[$varName])) && ($_SESSION[$varName] == $varVal)) {
					$varStr	= $val_1;
				} else {
					$varStr	= $val_2;
				}
				break;

			default:
				//test the condition and declare val
				if ((isset($_SESSION[$varName])) && ($_SESSION[$varName] == $varVal)) {
					$varStr	= $val_1;
				} else {
					$varStr	= $val_2;
				}
				break;
		}


		//return val
		return $varStr;
	} //eof






	/**
	 *	Return post variables
	 *
	 *	@param
	 *			$var		Variable name
	 *			$default	Default value to be printed if the get is not found
	 *
	 *	@return string
	 */
	function returnPostVar($var, $default)
	{
		if (isset($_POST[$var])) {
			$var = $_POST[$var];
		} else {
			$var = $default;
		}

		//return the result
		return $var;
	} //eof

	/**
	 *	Create  session are in array if the form submission method is get
	 *
	 *	@param
	 *			$sess_arr	Session Array
	 *
	 *	@return	null
	 */
	function addGetSessArr($sess_arr)
	{
		foreach ($sess_arr as $k) {
			if (isset($_GET[$k])) {
				$_SESSION[$k] = $_GET[$k];
			}
		}
	} //eof


	/**
	 *	Create  session are in array if the form submission method is post
	 *
	 *	@param
	 *			$sess_arr	Session Array
	 *
	 *	@return	null
	 */
	function addPostSessArr($sess_arr)
	{

		foreach ($sess_arr as $k) {

			if (isset($_POST[$k])) {

				$_SESSION[$k] = $_POST[$k];
			} else {

				$_SESSION[$k] = '';
			}
		}
	} //eof


	/**
	 *	Generate array of fields
	 *	@param	$num	Number of fields to generate
	 *	@param	$type	Type of fields to generate
	 *	@param	$name	Name of the fields
	 *	@return	NULL
	 */
	function genFormFields($num, $type)
	{
		if ((int)$num > 0) {
			$num = (int)$num;
		} else {
			$num =  1;
		}

		for ($k = 0; $k < $num; $k++) {
			$j = $k + 1;
			echo "
			 <table width='100%' cellspacing='0' cellpadding='0' border='0'>
			  <tr>
			  	<td width='22%'>
				 <div style='text-align:left;'>Option Name (" . $j . ")</div>
				</td>
				<td width='78%'>
				<div style='padding-top: 5px; padding-top: 5px;'>
					<input type='" . $type . "' name='options[]' />
				</div>
				</td>
			  </tr>
			  </table>";
		}
	} //eof





	/**
	 *	This function will provide discount system to a package, depending on 
	 *	either percentage or flat rate like discount amount 10 on every perchase
	 *	
	 *	
	 *	@param	$amount 		The old price
	 *	@param	$type 			Whether flat rate or percentage
	 *	@param	$rate 			The amount to be deducted either as rate or flat amount
	 *
	 *	@return decimal
	 *	@date	November 14, 2006
	 */
	function discount($amount, $type, $rate)
	{
		$new_price = '';
		if ($type == '%') {
			$new_price = ($amount - (($rate * $amount) / 100));
		} else {
			$new_price = $amount - $rate;
		}
		return $new_price;
	} //eof

	/**
	 *	Display discount
	 */
	function printDiscount($rate, $type, $currency)
	{
		$discount = '';
		if ($rate >= 0) {
			if ($type == '%') {
				$discount = "Discount: " . $rate . $type;
			} else {
				$discount = "Discount: " . $currency . " " . $rate;
			}
		}
		return $discount;
	} //eof

	/**
	 *	If session is registered print the session value. Usable in input field
	 *
	 *	@param $name	Name of the session variable
	 *	@param $default	Default value if session not found
	 *
	 *	@date	November 14, 2006
	 */
	function getSessionValue($name, $default)
	{
		if (isset($_SESSION[$name])) {
			echo $_SESSION[$name];
		} else {
			echo $default;
		}
	} //eof

	/**
	 *	Delete record from the table
	 *	
	 *	@param	$id			Id of the row to delete
	 *	@param 	$column		Name of the column holding the id
	 *	@param	$table		Name of the table
	 *
	 *	@return decimal
	 *	@date	November 15, 2006
	 */
	function deleteRecord($id, $column, $table)
	{
		$sql	= "DELETE FROM " . $table . " WHERE " . $column . "='$id'";
		$query	= $this->conn->query($sql);

		$result = '';
		if (!$query) {
			$result = "ER103";
		} else {
			$result = "SU103";
		}

		//return the result
		return $result;
	} //eof

	/**
	 *	Delete array record from the table
	 *	
	 *	@param	$idArr			Array of ids to delete
	 *		 	$column			Name of the column holding the id
	 *			$table			Name of the table
	 *
	 *	@return decimal
	 *	@date	November 15, 2006
	 */
	function deleteRecordArr($idArr, $column, $table)
	{
		if (count($idArr) > 0) {
			foreach ($idArr as $k) {
				$this->deleteRecord($k, $column, $table);
			}
		}
	} //eof



	/**
	 *	This function will return all the id related to a table
	 *	
	 *	@param
	 *			$column		Column name
	 *			$table		Table name
	 *
	 *	@return array
	 */
	function getAllId($column, $table)
	{
		$data	= array();
		$select	= "SELECT " . $column . " FROM " . $table . "";
		$query	= $this->conn->query($select);
		while ($result	= $query->fetch_array()) {
			$data[]	= $result[$column];
		}
		return $data;
	} //eof


	/**
	 *	This function will return all the id related to a table
	 *	
	 *	@param
	 *			$column		Column name
	 *			$table		Table name
	 *
	 *	@return array
	 */
	function getAllIdByLimit($column, $table, $limit1, $limit2)
	{
		$select	= "SELECT " . $column . " FROM " . $table . " LIMIT " . $limit1 . "," . $limit2;
		$query	= mysql_query($select);
		$data	= array();
		while ($result	= mysql_fetch_array($query)) {
			$data[]	= $result[$column];
		}
		return $data;
	} //eof


	/**
	 *	Retrive all primary key
	 *	
	 *	@param 	$column		Name of the column holding the id
	 *	@param	$table		Name of the table
	 *	@param	$order		Order by column
	 *
	 *	@return decimal
	 *	@date	November 15, 2006
	 */
	function getAllKey($column, $table, $order)
	{
		$sql	= "SELECT " . $column . " FROM " . $table . " ORDER BY " . $order . "";
		$query	= mysql_query($sql);

		$data	= array();

		if (mysql_num_rows($query) > 0) {
			while ($result = mysql_fetch_array($query)) {
				$data[] = $result[$column];
			}
		}
		return $data;
	} //eof





	/**
	 *	Retrive all primary key based of any criteria or foreign key value
	 *
	 *	@date	September 22, 2011
	 *	
	 *	@param 	
	 *			$column			Name of the column holding the id
	 *			$condColName	Name of the conditional column of foreign key
	 *			$condColVal		Conditional column or foreign key velue
	 *			$table			Name of the table
	 *			$order			Order by column
	 *
	 *	@return decimal
	 *	
	 */
	function getAllIdByCond($column, $condColName, $condColVal, $table, $order)
	{

		$data	= array();
		$sql	= "SELECT " . $column . " 
				   FROM   " . $table . " 
				   WHERE  " . $condColName . " = " . $condColVal . "
				   ORDER BY " . $order . "";

		//    echo $sql.$this->conn->error;

		$query	= $this->conn->query($sql);

		if ($query->num_rows > 0) {
			while ($result = $query->fetch_array()) {
				$data[] = $result[$column];
			}
		}
		return $data;
	} //eof









	/**
	 *	This function will return corresponding values associated with it's id
	 *	
	 *	@param
	 *			$idArr			Values of primary keys
	 *			$idCol			Column name of the primary key
	 *			$assCol			Associated column to query		
	 *			$table			Table name
	 *
	 *	@return array
	 */
	function getValuesByKeys($idArr, $idCol, $assCol, $table)
	{
		//declare vars
		$data	= array();

		if (count($idArr) > 0) {
			foreach ($idArr as $j) {
				//statement
				$select	= "SELECT " . $assCol . " FROM " . $table . " WHERE " . $idCol . " = " . $j . "";

				//execute statement
				$query	= mysql_query($select);

				while ($result	= mysql_fetch_array($query)) {
					$data[]	= $result[$assCol];
				}
			}
		}

		//return the data
		return $data;
	} //eof






	/**
	 *	This function will return corresponding value of a column associated with it's id or Primary Key value
	 *	
	 *	@param
	 *			$idPk			Values of primary keys
	 *			$idCol			Column name of the primary key
	 *			$assCol			Associated column to query		
	 *			$table			Table name
	 *
	 *	@return array
	 */
	function getValueByKey($idPk, $idCol, $assCol, $table)
	{

		$data	= '';
		if (($idPk > 0) || (count($idPk) > 0)) {

			$select	= "SELECT " . $assCol . " FROM " . $table . " WHERE " . $idCol . " = '" . $idPk . "'";
			// echo $select.$this->conn->error;exit;
			$query	= $this->conn->query($select);

			if ($query->num_rows > 0) {
				$result	= $query->fetch_array();
				$data	= $result[$assCol];
			}
		} //eof

		return $data;
	} //eof



	/**
	 *	This function will return corresponding value of a column associated with it's id or Primary Key value
	 *	
	 *	@param
	 *			$idPk			Values of primary keys
	 *			$idCol			Column name of the primary key
	 *			$assCol			Associated column to query		
	 *			$table			Table name
	 *			$default		Default value
	 *
	 *	@return string
	 */
	function getSingleValueByKey($idPk, $idCol, $assCol, $table, $default)
	{
		//declare vars
		$data	= $default;

		if ($idPk > 0) {

			//statement
			$select	= "SELECT " . $assCol . " FROM " . $table . " WHERE " . $idCol . " = '" . $idPk . "'";

			//execute statement
			$query	= mysql_query($select);
			//echo $select.mysql_error(); exit;
			if (mysql_num_rows($query) > 0) {
				//get the result set
				$result	= mysql_fetch_array($query);

				//get the column value
				$data	= $result[$assCol];
			}
		} //eof

		//return the data
		return $data;
	} //eof

	/**
	 *	This function will return corresponding value of a column associated with it's id or Primary Key value
	 *	
	 *	@param
	 *			$idPk			Values of primary keys
	 *			$idCol			Column name of the primary key
	 *			$assCol			Associated column to query		
	 *			$table			Table name
	 *			$default		Default value
	 *
	 *	@return string
	 */
	function getSingleValueByKey1($idPk, $idCol, $assCol, $table, $default)
	{
		//declare vars
		$data	= $default;

		if ($idPk != '') {

			//statement
			$select	= "SELECT " . $assCol . " FROM " . $table . " WHERE " . $idCol . " = '" . $idPk . "'";

			//execute statement
			$query	= mysql_query($select);
			//echo $select.mysql_error(); exit;
			if (mysql_num_rows($query) > 0) {
				//get the result set
				$result	= mysql_fetch_array($query);

				//get the column value
				$data	= $result[$assCol];
			}
		} //eof

		//return the data
		return $data;
	} //eof




	############################################################################################################
	#
	#												Image Display
	#
	############################################################################################################

	/**
	 *	Display image depending upon the size that admin wants to display, usable specially in 
	 *	thumbnail display. Can be use in other places as well.
	 *	
	 *	@param	
	 *			$dir				Image directory
	 *			$name				Image name
	 *			$displayHeight		Height to be displayed
	 *			$displayWidth		Width of the images to be displayed
	 *			$border				If want to put any border around the image
	 *			$class				If any class is aplicable
	 *			$alt				Alternative text to the image
	 *			
	 */

	function imageDisplay($dir, $name, $displayHeight, $displayWidth, $border, $class, $alt)
	{
		$imageSize  = @getimagesize($dir . $name);
		$width		= $imageSize[0];
		$height		= $imageSize[1];

		//decide the height
		if ($height > $displayHeight) {
			$height = $displayHeight;
		} else {
			$height = $height;
		}
		//decide the width
		if ($width > $displayWidth) {
			$width = $displayWidth;
		} else {
			$width = $width;
		}


		//echo "image display".$height." ".$width." ".$displayHeight." ".$displayWidth.$dir.$name;

		$data = "<img src='" . $dir . $name . "' height='" . $height . "' width='" . $width . "' border='" . $border . "'
				class='" . $class . "' alt='" . $alt . "' />";
		return $data;
	} //eof


	/**
	 *	This is the higher version of display image. Here we calculate the heighest of it's 
	 *	Width or Height and then recalculate everything.
	 *	Display image depending upon the size that admin wants to display, usable specially in 
	 *	thumbnail display. Can be use in other places as well.
	 *	
	 *	@param	
	 *			$dir				Image directory
	 *			$name				Image name
	 *			$displayHeight		Height to be displayed
	 *			$displayWidth		Width of the images to be displayed
	 *			$border				If want to put any border around the image
	 *			$class				If any class is aplicable
	 *			$alt				Alternative text to the image
	 *			
	 */

	function imageDisplay2($dir, $name, $displayHeight, $displayWidth, $border, $class, $alt)
	{

		$imageSize  = getimagesize($dir . $name);
		$width		= $imageSize[0];
		$height		= $imageSize[1];

		$newWidth 	= 0;
		$newHeight	= 0;
		$ratio		= 1;

		//find which has the higher value
		if (($width <= $displayWidth) && ($height <= $displayHeight)) {

			$newWidth 	= $width;
			$newHeight 	= $height;
		} elseif (($width > $displayWidth) || ($height > $displayHeight)) {

			$ratio	= ($width / $height);

			if ($ratio > 1) {

				$newWidth  = $displayWidth;
				$newHeight = (int)(($height / $width) * $newWidth);

				if ($newHeight > $displayHeight) {
					$newHeight = $displayHeight;
				}
			} elseif ($ratio < 1) {

				$newHeight  = $displayHeight;
				$newWidth 	= (int)(($width / $height) * $newHeight);


				if ($newWidth > $displayWidth) {
					$newWidth = $displayWidth;
				}
			} else {

				$newHeight 	= $displayHeight;
				$newWidth 	= $displayWidth;
			}
		} else {

			$newHeight 	= $displayHeight;
			$newWidth 	= $displayWidth;
		}
		//echo "image display".$height." ".$width." ".$displayHeight." ".$displayWidth.$dir.$name;
		//echo '<img src="'.$dir.$name.'">';exit;
		$jsFunction		= 'fadeInfoBlock()';
		if ($name == 'close-button.png') {

			$data = "<img src='" . $dir . $name . "' height='" . $newHeight . "' width='" . $newWidth . "' 
				border='" . $border . "' class='" . $class . "' alt='" . $alt . "' onclick='" . $jsFunction . "' />";
		} else {

			$data = "<img src='" . $dir . $name . "' height='" . $newHeight . "' width='" . $newWidth . "' 
				border='" . $border . "' class='" . $class . "' alt='" . $alt . "' />";
		}
		//echo $data;exit;		
		return $data;
	} //eof

	/**
	 *	This is the higher version of display image version 2. Here we check if image is exist or not
	 *	before display them.
	 *	Display image depending upon the size that admin wants to display, usable specially in 
	 *	thumbnail display. Can be use in other places as well.
	 *	
	 *	@param	
	 *			$dir				Image directory
	 *			$name				Image name
	 *			$displayHeight		Height to be displayed
	 *			$displayWidth		Width of the images to be displayed
	 *			$border				If want to put any border around the image
	 *			$class				If any class is aplicable
	 *			$alt				Alternative text to the image
	 *			
	 */

	function imgDisplay($dir, $name, $displayWidth, $displayHeight, $border, $class, $alt, $str)
	{
		if (($name != '') && (file_exists($dir .  $name))) {
			echo $this->imageDisplay2($dir, $name, $displayHeight, $displayWidth, $border, $class, $alt);
		} else {
			echo "<span class='orangeLetter'>" . $str . "</span>";
		}
	} //eof


	/**
	 *	Return image mesg. A new version of the function imgDisplay. R at the name of the function
	 *	refered to Return.
	 */
	function imgDisplayR($dir, $name, $displayWidth, $displayHeight, $border, $class, $alt, $str)
	{

		$data	= '';
		//echo $dir .  $name;exit;
		if (($name != '')) {

			$data = $this->imageDisplay2($dir, $name, $displayHeight, $displayWidth, $border, $class, $alt);
		} else {

			$data =  "<span class='orangeLetter'>" . $str . "</span>";
		}

		//return the data
		return $data;
	} //eof




	/**
	 *	This function is going to return the aspect ratio of the images based on it's height and width
	 *
	 *	@date	November 14, 2011
	 *	
	 *	@param	
	 *			$useAspect			Whether to use the aspect ratio or not, the value can be either YES or NO
	 *			$dir				Image directory
	 *			$name				Image name
	 *			$displayHeight		Height to be displayed
	 *			$displayWidth		Width of the images to be displayed
	 *			
	 *	@return array
	 *			
	 */
	function getImageAspectRatio($useAspect, $dir, $name, $displayHeight, $displayWidth)
	{
		$imageSize  = @getimagesize($dir . $name);
		$width		= $imageSize[0];
		$height		= $imageSize[1];

		$newWidth 	= 0;
		$newHeight	= 0;
		$ratio		= 1;

		if ($useAspect == 'YES') {
			//find which has the higher value
			if (($width <= $displayWidth) && ($height <= $displayHeight)) {
				$newWidth 	= $width;
				$newHeight 	= $height;
			} elseif (($width > $displayWidth) || ($height > $displayHeight)) {
				$ratio	= ($width / $height);

				if ($ratio > 1) {
					$newWidth  = $displayWidth;
					$newHeight = (int)(($height / $width) * $newWidth);

					if ($newHeight > $displayHeight) {
						$newHeight = $displayHeight;
					}
				} elseif ($ratio < 1) {
					$newHeight  = $displayHeight;
					$newWidth 	= (int)(($width / $height) * $newHeight);


					if ($newWidth > $displayWidth) {
						$newWidth = $displayWidth;
					}
				} else {
					$newHeight 	= $displayHeight;
					$newWidth 	= $displayWidth;
				}
			} else {
				$newHeight 	= $displayHeight;
				$newWidth 	= $displayWidth;
			}
		} else {
			$newHeight 	= $displayHeight;
			$newWidth 	= $displayWidth;
		}

		//build the array
		$data	= array($newHeight, $newWidth);

		//return the image size to display	
		return $data;
	} //eof


	/**
	 *	This function has been been developed for SEO URL content purpose, to display an image by using Absolute path
	 *	for its display and existance by using the relative directory
	 *	
	 *	@param
	 *			$useAspect			Whether to use the aspect ratio or not, the value can be either YES or NO	
	 *			$absPath			Root folder for the webroot
	 *			$dir				Image directory
	 *			$name				Image name
	 *			$displayHeight		Height to be displayed
	 *			$displayWidth		Width of the images to be displayed
	 *			$border				If want to put any border around the image
	 *			$class				If any class is aplicable
	 *			$alt				Alternative text to the image
	 *			$str				Display an alternate image or any text
	 *
	 *	@return string
	 *			
	 */
	function showImgWithAbsPath($useAspect, $absPath, $dir, $name, $displayWidth, $displayHeight, $border, $class, $alt, $str)
	{
		//declare var
		$data	= '';

		if (($name != '') && (file_exists($dir .  $name))) {
			//get the image size
			$imgSize	= $this->getImageAspectRatio($useAspect, $dir, $name, $displayHeight, $displayWidth);

			//get the image height and width
			$newHeight	= $imgSize[0];
			$newWidth	= $imgSize[1];

			//construct the image tag
			$data 		= "<img src='" . $absPath . $dir . $name . "' height='" . $newHeight . "' width='" . $newWidth . "' 
							border='" . $border . "' class='" . $class . "' alt='" . $alt . "' />";
		} else {
			//show alternate string
			$data		= $str;
		}

		//return 
		return $data;
	} //eof




	###############################################################################################


	/**
	 *	Get no of entry agaist an id in a table
	 *	@param
	 *			$id			Id to count
	 *			$column		Column name that holds the id
	 *			$table		Table name
	 *	@return	int
	 *	@date	November 22, 2006
	 */
	function getNoOfEntry($id, $column, $table)
	{
		$num 	= 0;
		$sql 	= "SELECT * FROM " . $table . " WHERE " . $column . "='" . $id . "'";
		$query 	= $this->conn->query($sql);
		//echo $sql;
		$num	= $query->num_rows;
		//echo $num.$sql;.$sql
		return $num;
	} //eof

	/**
	 *	This function update the value of a id associated with a table. 
	 *	Useful when you want to change the status/single value of any table.
	 *
	 *	@param
	 *			$key_val	Primary key value
	 *			$key_col	Primary key column name
	 *			$set_col	column name
	 *			$col_val	value of the column
	 *			$table		Table name
	 *
	 *	@return NULL
	 */
	function updSingleData($key_val, $key_col, $col_val, $set_col, $table)
	{

		$col_val	= addslashes(trim($col_val));
		$sql	= "UPDATE {$table} SET {$set_col} ='{$col_val}' WHERE {$key_col}  = {$key_val}";
		$query	= $this->conn->query($sql);

		return $query;
	} //eof

	/**
	 *	Print status message
	 *
	 *	@param
	 *			$status		Status of  ads
	 *
	 *	@return string
	 */
	function getStatusMesg($status)
	{
		$mesg	= '';
		if ($status == 'a') {
			$mesg	= '<span class="greenLetter">active</span>';
		} else if ($status == 'd') {
			$mesg	= '<span class="orangeLetter">deactive</span>';
		} else {
			$mesg	= '<span class="error">N/A</span>';
		}

		return $mesg;
	} //eof

	/**
	 *	This functions will return the encoded and decoded result by base64 encode and decode
	 *
	 *	@param
	 *			$string		String to encode
	 */
	function encode($string)
	{
		return base64_encode($string);
	} //eof

	function decode($string)
	{
		return base64_decode($string);
	} //eof

	/**
	 *	Update the number of clicks whenever user clicks on a links to determine most popular
	 *	
	 *	@param	
	 *			$click_col  Counter column name
	 *			$key_id	  	Unique identity or field value associated with the table
	 *			$key_col    Primary key column name
	 *			$table		Name of the table
	 *
	 *	@return NULL
	 */
	function updateClicks($click_col, $key_id, $key_col, $table)
	{
		$sql	= 	"UPDATE " . $table . " SET " . $click_col . " = " . $click_col . " + 1 
					 WHERE " . $key_col . " = " . $key_id . " ";
		$query	= mysql_query($sql);
		if (!$query) {
			return mysql_error;
		} else {
			return $sql;
		}
	} //eof

	/**
	 *	Number of results to display per page.
	 *
	 *	@param
	 *			$selected 	If any value is selected
	 *			$class 		Class to render the display
	 *

	 *	@return NULL
	 */
	function dispResPerPage($selected, $class)
	{
		$selected = (int)$selected;
		$numArr  = array(10, 5, 10, 15, 20, 25, 50);
		$dispArr = array("Display", 5, 10, 15, 20, 25, 50);
		if (($selected < 1) || ($selected > 50) || (!in_array($selected, $numArr))) {
			$selected = 10;
		} else {
			$selected = (int)$selected;
		}
		echo "<select id='numResDisplay'  name='numResDisplay' class='$class'>";
		foreach ($numArr as $key => $j) {
			if ($j == $selected) {
				$select_string = 'selected';
			} else {
				$select_string = '';
			}

			echo "<option value='" . $j . "' " . $select_string . ">" . $dispArr[$key] . "</option>";
		}
		echo "</select>";
	} //eof

	/**
	 *	Position number selected
	 *
	 *	@param
	 *			$selected 	If any value is selected
	 *			$class 		Class to render the display
	 *
	 *	@return NULL
	 */
	function dispPosition($selected, $class)
	{
		$selected = (int)$selected;
		$numArr = array(1, 2, 3, 4, 5, 6);
		/* if(($selected < 1) || ($selected > 5) || (!in_array($selected, $numArr)))
		{
			$selected = 1;
		}
		else
		{
			$selected = (int)$selected;
		} */
		echo "<select id='numPos'  name='numPos' class='$class'>";
		echo "<option value='0'>-- Select --</option>";
		foreach ($numArr as $j) {
			if ($j == $selected) {
				$select_string = 'selected';
			} else {
				$select_string = '';
			}

			echo "<option value='" . $j . "' " . $select_string . ">" . $j . "</option>";
		}
		echo "</select>";
	} //eof

	/**
	 *	Display Sorting list
	 *
	 *	@param
	 *			$selected 	If any value is selected
	 *			$class 		Class to render the display
	 *
	 *	@return NULL
	 */
	function dispSort($selected)
	{
		$res = '';
		$selected = (int)$selected;
		$numArr = array(1, 2, 3, 4, 5, 6, 7, 8, 9, 10);

		$res .=  "<option value='0'>-- Select One --</option>";
		foreach ($numArr as $j) {
			if ($j == $selected) {
				$select_string = 'selected';
			} else {
				$select_string = '';
			}

			$res .=   "<option value='" . $j . "' " . $select_string . ">" . $j . "</option>";
		}

		//return result
		return $res;
	} //eof
	////////////////////////////////////////////////////////////////////////////////////////
	//
	//					**************	Get Image data	*******************
	//
	///////////////////////////////////////////////////////////////////////////////////////
	/**
	 *	Retrive the image data
	 *	@return Array
	 */
	function getImageData($id, $table)
	{
		$sql 	= "SELECT * FROM " . $table . " WHERE image_id= " . $id . "";
		$query	= mysql_query($sql);
		$data	= array();

		if (mysql_num_rows($query) > 0) {
			$result = mysql_fetch_array($query);
			$data	= array(
				$result['image_name'],		//0
				$result['image'],			//1
				$result['added_on']			//2
			);
		}

		return $data;
	} //eof



	########################################################################################
	#
	#					Security + Authentication + Authorization
	#
	########################################################################################

	/**
	 *   Find an item against it's id or unique value from the table, if that item doesn't 
	 *	exist it will throw error. This function can check existence of an object either 
	 *	by direct check or by association with primary key.
	 *
	 *	@param
	 *			$id				Identity/value of the object
	 *			$column_id		Column name, which contain the object
	 *			$is_user		Determine whether the value has to check directly or needs to 
	 *							check it's association as well, with another value. It can 
	 *							either YES or NO.
	 *			$userid			Value of the associated id
	 *			$user_column	Name of the associated key
	 *			$table			Table name
	 *		
	 *   @return string
	 */
	function noItemFound($id, $column_id, $is_user, $userid, $user_column, $table)
	{
		$msg = '';
		if ($is_user == 'YES') {
			$select = "SELECT * FROM " . $table . " WHERE " . $column_id . "='" . $id . "' AND " . $user_column . "= '" . $userid . "' ";
		} else {
			$select = "SELECT * FROM " . $table . " WHERE " . $column_id . "='" . $id . "'";
		}
		$query = mysql_query($select);
		//echo $select;exit;
		$num = mysql_num_rows($query);
		if ($num > 0) {
			$msg = 'FOUND';
		} else {
			$msg = 'NOT_FOUND';
		}
		return $msg;
	}

	/**
	 *	This function will check for the duplicate data
	 *	
	 *	@param
	 *			$id 	    	Unique identity associated with the table
	 *			$id_column  	Column Name
	 *			$tableName  	Table to query
	 */
	/*function duplicateEntry($id, $id_column, $tableName)
	{
		$select = "SELECT * FROM ".$tableName." WHERE  ".$id_column." = '$id'";
		$query  = mysql_query($select);
		
		$num    = mysql_num_rows($query);
		$msg = '';
		
		if($num > 0 )
		{
			$msg   = 'ER001';
		}
		else
		{
			$msg	= "SU001";
		}
		
		//return the message
		return $msg;
		
	}//eof check duplicacy*/


	/**
	 *	Check session authenticaion of a user
	 *
	 *	@param
	 *			$userId		User id
	 *
	 *	@return string
	 */
	function authSession($userId, $sessVar)
	{
		$res = '';
		if (isset($_SESSION[$sessVar]) && ($_SESSION[$sessVar] == $userId)) {
			$res = 'SU501';
		} else {
			$res = 'ER501';
		}
		return $res;
	} //eof

	/**
	 *	User in role will check whether the authenticated user is authorized to  
	 *	perform certain task or not. For example, if the user tries to change someone else's 
	 *	password or his account sesstings etc.
	 *
	 *	@param
	 *			$userId		User id
	 *			$userCol	Column name that holds the user id
	 *			$servId		Service id
	 *			$servCol	Column name that holds the service id
	 *			$table		Table name to perform authorization
	 *
	 *	@return string
	 */
	function checkUserRole($userId, $userCol, $servId, $servCol, $table)
	{
		$res		= '';
		$sql	= "SELECT * FROM " . $table . " 
				   WHERE " . $userCol . " = " . $userId . " AND " . $servCol . " = " . $servId . "";
		$query	= mysql_query($sql);

		if (mysql_num_rows($query) == 1) {
			$res = 'SU501';
		} else {
			$res = 'ER501';
		}
		return $res;
	} //eof

	/**
	 *	This function will enforce http connection to https
	 *	
	 *	@return NULL
	 */
	function enforceHttps()
	{
		if ($_SERVER['SERVER_PORT'] == 80) {
			header("Location: " . SECURE . PAGE);
		}
	} //eof





	########################################################################################
	#
	#								 Most Popular + Status Wise
	#
	########################################################################################

	/**
	 *	Get image ids depending on criteria
	 *	
	 *	@param
	 *			$num		Number of popular item
	 *			$column		Column name of key
	 *			$table		Table name to find popular
	 *	
	 *	@return array
	 */
	function getMostPopular($num, $column, $table)
	{
		$data	= array();

		$sql	= "SELECT " . $column . " FROM " . $table . " ORDER BY no_clicks DESC LIMIT $num";
		$query	= mysql_query($sql);

		if (mysql_num_rows($query) > 0) {
			while ($result = mysql_fetch_array($query)) {
				$data[]	=	$result[$column];
			}
		} //if

		return $data;
	} //eof

	/**
	 *	Get  ids depending on status criteria
	 *	
	 *	@param
	 *			$status		Status of the item
	 *			$column		Column name of key
	 *			$table		Table name to find popular
	 *	
	 *	@return array
	 */
	function getByStatus($status, $column, $table)
	{
		$data	= array();

		$sql	= "SELECT " . $column . " FROM " . $table . " WHERE status = '" . $status . "'";
		$query	= mysql_query($sql);

		if (mysql_num_rows($query) > 0) {
			while ($result = mysql_fetch_array($query)) {
				$data[]	=	$result[$column];
			}
		} //if

		return $data;
	} //eof


	/**
	 *	Get  ids depending on status criteria
	 *	
	 *	@param
	 *			$status		Status of the item
	 *			$num		Number of popular item
	 *			$column		Column name of key
	 *			$table		Table name to find popular
	 *	
	 *	@return array
	 */
	function getPopularActive($status, $num, $column, $table)
	{
		$final	= array();

		$pop	= $this->getMostPopular($num, $column, $table);
		$stat	= $this->getByStatus($status, $column, $table);

		$final	= array_intersect($pop, $stat);
		return $final;
	} //eof

	/**
	 *	Based upon a selection yes ir no in customizing package field, it would ask user to select the 
	 *	yes or no option for filling up the address field during package purchase period.
	 *
	 *	@param
	 *			$val	Value, either Y or N
	 *
	 *	@return NULL
	 *			
	 */
	function getAddrOption($val)
	{
		if ($val == 'Y') {
			echo
			'
			<div  style="width:100%; padding:10px 2px 2px 2px; height: auto; float:left" class="menuText">
					<div>
						2. Would your ads addresses be same as your contact address? 
					</div>
					<div style="float:left ">
						<div style="padding: 0px 10px 0px 5px; float:left ">
					  		<input name="addrOpt" id="addrOpt" type="radio" value="Y" title="yes" checked />
						</div>
					  	<div style="padding: 3px 10px 0px 5px; float:left "> Yes</div>
						<div style="padding: 0px 10px 0px 5px; float:left ">
					 		<input name="addrOpt" id="addrOpt" type="radio" value="N" title="no" />
						</div>
					  	<div style="padding: 3px 10px 0px 5px; float:left "> No</div>
					</div>
					<div class="clear"></div>
					<br />
					<span class="orangeLetter">
					Note: 
					</span>
					<br />
					<span class="blackLarge">
					1. If you select yes, all of your advertise address(es) will same as your 
					contact address. You will be able to customize the addresses later from your 
					user account<br />
					2. If select no, you have to customize them now during your registration process.
					</span>
				</div>
			';
		} else {
			echo '<input name="addrOpt" type="hidden" id="addrOpt" value="N" />';
		}
	} //eof

	######################################################################################
	#
	#							Generate Drop Down List
	#
	######################################################################################

	/**
	 *	Popultae the dropdown list same as pervious function except the dropdown is based upon a 
	 *	foreign key value
	 *
	 *	@param
	 *			$selected		If there is any selected value, it will be shown as selected, otherwise put zero(0).
	 *			$id				Column name of the primary key
	 *			$populate		Column name of the data to be displayed
	 *			$table			The table contains the data
	 *
	 *	@return NULL
	 */

	// function customDropDown($selected, $idColumn, $populate, $table){

	// 	$select		= "SELECT * FROM ".$table." ORDER BY ".$populate."";
	// 	$query		= $this->conn->query($select);
	// 	$rows 		= $query->num_rows;

	// 	if($rows > 0)
	// 	{
	// 		while($result	= 	$query->fetch_array())
	// 		{
	// 			$data_id	= $result[$idColumn];
	// 			if($data_id == $selected)
	// 			{
	// 				$select_string = 'selected';
	// 			}
	// 			else
	// 			{
	// 				$select_string = '';
	// 			}

	// 			echo "<option value='".$data_id."' class='menuText' ".$select_string.">".
	// 			$result[$populate]."</option>";

	// 		}
	// 	}	
	// }//eof


	function populateDropDown($selected, $idColumn, $populate, $table)
	{

		$select		= "SELECT * FROM " . $table . " ORDER BY " . $populate . "";
		// echo $select.$this->conn->error;
		// exit;
		$query		= $this->conn->query($select);
		$rows 		= $query->num_rows;

		if ($rows > 0) {

			while ($result	= 	$query->fetch_array()) {

				$data_id	= $result[$idColumn];

				if ($data_id == $selected) {
					$select_string = 'selected';
				} else {
					$select_string = '';
				}

				echo "<option value='" . $data_id . "' " . $select_string . ">" . $result[$populate] . "</option>";
			}
		}
	} //eof

	/**
	 *	Popultae the dropdown list same as pervious function except the dropdown is based upon a 
	 *	foreign key value
	 *
	 *	@param
	 *			$selected		If there is any selected value, it will be shown as selected, otherwise put zero(0).
	 *			$id				Column name of the primary key
	 *			$populate		Column name of the data to be displayed
	 *			$table			The table contains the data
	 *
	 *	@return NULL
	 */
	function popDropNew($selected, $id, $populate, $table, $ordBy)
	{
		$select		= "SELECT * FROM " . $table . " ORDER BY " . $ordBy . " DESC";
		$query		= mysql_query($select);

		if (mysql_num_rows($query) > 0) {
			while ($result	= 	mysql_fetch_array($query)) {
				$data_id	= $result[$id];
				if ($data_id == $selected) {
					$select_string = 'selected';
				} else {
					$select_string = '';
				}

				echo "<option value='" . $data_id . "' class='menuText' " . $select_string . ">" .
					$result[$populate] . "</option>";
			}
		}
	} //eof

	/**
	 *	Popultae the dropdown list same as pervious function except the dropdown is based upon a 
	 *	foreign key value
	 *
	 *	@param
	 *			$selected		If there is any selected value, it will be shown as selected, otherwise put zero(0).
	 *			$id				Column name of the primary key
	 *			$populate		Column name of the data to be displayed
	 *			$foreign_key	The foreign key column associated with WHERE clause
	 *			$key_value		The key value
	 *			$table			The table contains the data
	 *
	 *	@return NULL
	 */
	function populateDropDown2($selected, $id, $populate, $foreign_key, $key_value, $table)
	{
		// $cusDetail[0][27], 'id', 'city',  'state_id', $cusDetail[0][28], 'cities'

		$select		= "SELECT * FROM " . $table . " WHERE " . $foreign_key . " = " . $key_value . " ORDER BY " . $populate . "";
		// echo $select; exit;
		$query		= $this->conn->query($select);

		if ($query->num_rows > 0) {

			while ($result	= 	$query->fetch_array()) {

				$data_id	= $result[$id];
				if ($data_id == $selected) {

					$select_string = 'selected';
				} else {

					$select_string = '';
				}

				echo "<option value='" . $data_id . "' " . $select_string . ">" . $result[$populate] . "</option>";
			}
		}
	} //eof

	/**
	 *	Populate the dropdown list with maximum and minimum values. If the maximum values is
	 *	set to zero (0) then the list will populate as MinimumValue+
	 *
	 *	@param
	 *			$selected		If there is any selected value, it will be shown as selected, 
	 *							otherwise put zero(0).
	 *			$id_column		Column name of the primary key
	 *			$order			Order by, ASC or DESC
	 *			$min_col		Column contain the minimum values
	 *			$max_col		Column contains the maximum values
	 *			$table			The table contains the data
	 *
	 *	@return NULL
	 */
	function populateDropDown3($selected, $id_column, $min_col, $max_col, $order, $table, $class)
	{
		$select_string = '';
		$str_format	   = '';

		$select		= "SELECT " . $id_column . ", " . $min_col . ", " . $max_col . " FROM " . $table .
			" ORDER BY " . $min_col . " " . $order;

		$query		= mysql_query($select);

		if (mysql_num_rows($query) > 0) {
			while ($result	= 	mysql_fetch_array($query)) {
				$data_id	= $result[$id_column];
				$min_val	= $result[$min_col];
				$max_val	= $result[$max_col];

				if ($max_val == 0) {
					$str_format	   = $min_val . '+';
				} else {
					$str_format	   = $min_val . ' - ' . $max_val;
				}

				if ($data_id == $selected) {
					$select_string = 'selected';
				} else {
					$select_string = '';
				}

				echo "<option value='" . $data_id . "' class='" . $class . "' " . $select_string . ">" .
					$str_format . "</option>";
			}
		}
	} //eof

	/**
	 *	Populate the dropdown list with single. The maximum value will display as $maxVal+
	 *
	 *	@param
	 *			$selected		If there is any selected value, it will be shown as selected, 
	 *							otherwise put zero(0).
	 *			$id_column		Column name of the primary key
	 *			$order			Order by, ASC or DESC
	 *			$disp_col		Column contains the maximum values
	 *			$table			The table contains the data
	 *
	 *	@return NULL
	 */
	function populateDropDown4($selected, $disp_col, $order, $table)
	{
		$select_string = '';
		$str_format	   = '';
		$valArr		   = array();

		//select the max first
		$sql		= "SELECT MAX(" . $disp_col . ") AS MAXVAL" . " FROM " . $table;
		$maxQ		= mysql_query($sql);
		$res		= mysql_fetch_array($maxQ);
		$max_val	= $res['MAXVAL'];


		$select		= "SELECT " . $disp_col . " FROM " . $table . " ORDER BY " . $disp_col . " " . $order;

		$query		= mysql_query($select);

		if (mysql_num_rows($query) > 0) {
			while ($result	= 	mysql_fetch_array($query)) {
				$disp_val	= $result[$disp_col];

				if ($disp_val == $max_val) {
					$str_format	   = $disp_val . '+';
				} else {
					$str_format	   = $disp_val;
				}

				if ($disp_val == $selected) {
					$select_string = 'selected';
				} else {
					$select_string = '';
				}

				echo "<option value='" . $disp_val . "' " . $select_string . ">" .
					$str_format . "</option>";
			}
		}
	} //eof

	/**
	 *	Generate dropdown list with selected value depending upon the 2 array
	 *
	 *	@param
	 *			$selected		If want to show any selected value
	 *			$arr_value		Array of values
	 *			$arr_label		Array of labels corresponding to it's values
	 *
	 *	@return NULL
	 */
	function genDropDown($selected, $arr_value, $arr_label)
	{
		$sel_str = '';

		for ($k = 0; $k < count($arr_label); $k++) {
			if ($selected == $arr_value[$k]) {
				$sel_str = 'selected';
			} else {
				$sel_str = '';
			}
			echo "<option value='" . $arr_value[$k] . "' " . $sel_str . ">" . $arr_label[$k] . "</option>";
		};
	} //eof

	/**
	 *	Generate dropdown list with selected value depending upon the range
	 *
	 *	@param
	 *			$selected		If want to show any selected value
	 *			$low_range		Lower Range
	 *			$high_range		Higher Range
	 *
	 *	@return NULL
	 */
	function genDropDownWithRange($selected, $low_range, $high_range)
	{
		$sel_str = '';

		for ($k = $low_range; $k <= $high_range; $k++) {
			if ($selected == $k) {
				$sel_str = 'selected';
			} else {
				$sel_str = '';
			}
			echo "<option value='" . $k . "' " . $sel_str . ">" . $k . "</option>";
		};
	} //eof

	/**
	 *	Generate dropdown list with selected value that return the string
	 *
	 *	@param
	 *			$selected		If want to show any selected value
	 *			$arr_value		Array of values
	 *			$arr_label		Array of labels corresponding to it's values
	 *
	 *	@return NULL
	 */
	function genDropDownR($selected, $arr_value, $arr_label)
	{
		//declare var
		$ddStr	= '';
		$sel_str = '';

		for ($k = 0; $k < count($arr_label); $k++) {
			if ($selected == $arr_value[$k]) {
				$sel_str = 'selected';
			} else {
				$sel_str = '';
			}
			$ddStr	.= "<option value='" . $arr_value[$k] . "' " . $sel_str . ">" . $arr_label[$k] . "</option>";
		};

		//return the string
		return $ddStr;
	} //eof



	/**
	 *	
	 */
	function genDropDown2($selected, $id, $populate, $foreign_key, $key_value, $table)
	{
		$select		= "SELECT * FROM " . $table . " WHERE " . $foreign_key . " = " . $key_value .
			" ORDER BY " . $populate . "";
		$query		= mysql_query($select);

		if (mysql_num_rows($query) > 0) {
			while ($result	= 	mysql_fetch_array($query)) {
				$data_id	= $result[$id];

				if ($data_id == $selected) {
					$select_string = 'selected';
				} else {
					$select_string = '';
				}

				echo "<option value='" . $data_id . "' class='menuText' " . $select_string . ">" .
					$result[$populate] . "</option>";
			}
		}
	} //eof



	/**
	 *	Popultae the dropdown list if an id is not already listed. 
	 *
	 *	@param
	 *			$selected		If there is any selected value, it will be shown as selected, otherwise put zero(0).
	 *			$id				Column name of the primary key
	 *			$populate		Column name of the data to be displayed
	 *			$ordBy			Order by column name
	 *			$ordType		Ascending or descending
	 *			$onEdit			Whether the list for editing or the new one. It's values ae YES or NO.
	 *			$editItemId		If the list is for editing, we need to add the edit item id.
	 *			$default		Default value to display in the page
	 *			$table1			First table from which to check the listed data
	 *			$table2			Second table from which the list is going to pop up
	 *
	 *	@return NULL
	 */
	function genListIfNotListed($selected, $id, $populate, $ordBy, $ordType, $onEdit, $editItemId, $default, $table1, $table2)
	{
		//declare vars
		$listArr	= array();
		$allArr		= array();
		$restIds	= array();
		$assVal		= array();

		//get the listed data
		$listArr = $this->getAllId($id, $table1);

		//get all the data
		$allArr	= $this->getAllId($id, $table2);

		//get the left over ids
		$restIds = array_diff($allArr, $listArr);

		//regenerate the list if the list is for edit
		if ($onEdit == "YES") {
			$restIds[] = $editItemId;
		}


		/* foreach($restIds as $key => $val)
		{
			echo "Key = ".$key.", Val = ".$val.", Name = ".$assVal[$key]."<br />";
		} */

		if (count($restIds) > 0) {
			//generate the list
			foreach ($restIds as $key => $val) {
				//get value
				$assVal  = $this->getSingleValueByKey($val, $id, $populate, $table2, $default);

				if ($selected == $val) {
					$select_string = 'selected';
				} else {
					$select_string = '';
				}

				echo "<option value='" . $val . "' class='menuText' " . $select_string . ">" . $assVal . "</option>";
			}
		}
	} //eof




	/**
	 *	This is method is an advance version of the earlier function, as it works with the foreign key or conditions.
	 *	Popultae the dropdown list if an id is not already listed. 
	 *
	 *	@param
	 *			$selected		If there is any selected value, it will be shown as selected, otherwise put zero(0).
	 *			$id				Column name of the primary key
	 *			$populate		Column name of the data to be displayed
	 *			$ordBy			Order by column name
	 *			$ordType		Ascending or descending
	 *			$onEdit			Whether the list for editing or the new one. It's values ae YES or NO.
	 *			$editItemId		If the list is for editing, we need to add the edit item id.
	 *			$table1			First table from which to check the listed data
	 *			$table2			Second table from which the list is going to pop up
	 *
	 *	@return NULL
	 */
	function genListIfNotListedByCondition(
		$selected,
		$id,
		$populate,
		$condColName,
		$condColVal,
		$ordBy,
		$ordType,
		$onEdit,
		$editItemId,
		$table1,
		$table2
	) {
		//declare vars
		$listArr	= array();
		$allArr		= array();
		$restIds	= array();
		$assVal		= array();

		//get the listed data
		$listArr = $this->getAllId($id, $table1);
		// echo $column.'<br>'.$id.'<br>'. $condColName.'<br>'. $condColVal.'<br>'. $table2.'<br>'. $ordBy;

		//get all the data
		$allArr	= $this->getAllIdByCond($id, $condColName, $condColVal, $table2, $ordBy);

		//get the left over ids
		$restIds = array_diff($allArr, $listArr);

		//regenerate the list if the list is for edit
		if ($onEdit == "YES") {
			$restIds[] = $editItemId;
		}

		//make the query
		//$assVal  = $this->getValuesByKeys($restIds, $id, $populate, $table2);

		/*  
		foreach($restIds as $rKey => $val)
		{
			echo "Key = ".$rKey.", Val = ".$val.", Name = ".$assVal[$rKey]."<br />";
		}*/

		if (count($restIds) > 0) {
			//generate the list
			foreach ($restIds as $rKey => $rVal) {
				//get the value of the primary key
				//$val = $restIds[$rKey];

				//get value
				$assVal  = $this->getValueByKey($rVal, $id, $populate, $table2);

				if ($selected == $rVal) {
					$select_string = 'selected';
				} else {
					$select_string = '';
				}

				echo "<option value='" . $rVal . "' class='menuText' " . $select_string . ">" . $assVal . "</option>";
			}
		}
	} //eof


	//////////////////////////////////////////////////////////////////////////////////
	//
	//								Print Get Post Variables
	//
	//////////////////////////////////////////////////////////////////////////////////
	/**
	 *	Print get variables
	 *
	 *	@param
	 *			$varArr		`Array of variables
	 *
	 *	@return NULL
	 */
	function printGetVar($varArr)
	{
		if (count($varArr) > 0) {
			foreach ($varArr as $k) {
				if (isset($_GET[$k])) {
					$k = $_GET[$k];
				} else {
					$k = '';
				}
			}
		}
	} //eof



	/**
	 *	Print post variables
	 *
	 *	@param
	 *			$varArr		`Array of variables
	 *
	 *	@return NULL
	 */
	function printPostVar($varArr)
	{
		if (count($varArr) > 0) {
			foreach ($varArr as $k) {
				if (isset($_POST[$k])) {
					'$' . $k = $_POST[$k];
				} else {
					'$' . $k = '';
				}
			}
		}
	} //eof

	////////////////////////////////////////////////////////////////////////////////////////////




	/**
	 *	Get background color
	 *
	 *	@param
	 *			$i		The position of the row
	 *
	 *	@return string
	 */
	function getRowColor($i)
	{
		if ($i % 2 == 0) {
			$bgColor = "#F3F3F3";
		} else {
			$bgColor = "#FFFFFF";
		}

		return $bgColor;
	} //eof

	/**
	 *	Print row color
	 *
	 *	@param
	 *			$bgColor		Background color of a row
	 *
	 *	@return NULL
	 */
	function printRowColor($bgColor)
	{
		echo "bgcolor='" . $bgColor . "' onmouseover='this.bgColor=\"#FFF8C1\"' 
		onmouseout='this.bgColor=\"" . $bgColor . "\"'";
	} //eof



	/**
	 *	Get CSS type depending on division condition which is a little different from the 
	 *	above function.
	 *
	 *	@update	December 26, 2009
	 *
	 *	@param
	 *			$currNum		Current number to to divide
	 *			$divCond		Division contion, that is the factor to division with
	 *			$stile1			First style to render the product or image 
	 *			$style2			Second style to render the product or image 
	 *
	 *	@return string
	 */
	function getCSSByDivision($currNum, $divCond, $style1, $style2)
	{
		//declare var
		$strStyle	= "class=''";

		//check the condition
		if (($currNum % $divCond) == 0) {
			$strStyle	= "class='$style1'";
		} else {
			$strStyle	= "class='$style2'";
		}

		//return the result
		return	$strStyle;
	} //eof



	/**
	 *	Create close button
	 */
	function createCloseBtn()
	{
		$str	= '
					<div class="padB5 padR5 fr w75" align="right">
						<div class="blackLarge" 
						style="background-color: #FFD737; padding:2px; width:75px; 
						 border:1px solid #BABDCE;   text-align: center; font-weight:900; " >
						 <a href="#" title="Close Widow" onClick="window.close();">
						 Close
						 </a>
					    </div>
				    </div>
				  ';

		return $str;
	} //eof

	/**
	 *	Display back
	 */
	function showBack()
	{
		$str = '<span class="orangeLetter fr padR15">
			  	<a href="javascript: history.back()" >< Back</a>
			  </span>';

		return $str;
	} //eof

	/**
	 *	Display back button
	 */
	function showBack2($path, $name)
	{
		$str = '<span class="orangeLetter padR5">
			  	<a href="javascript: history.back()" title="< Back" >
					<img src="' . $path . $name . '" border="0" height="20" width="22" alt="Back" />
				</a>
			  </span>';

		return $str;
	} //eof


	/**
	 *	Display email id in the box, so that it won't go beyond certain length of email link.
	 *
	 *	@param
	 *			$toEmail		The email address to send mail
	 *			$toName			Name of the person
	 *			$send			Whether the send mail form will appear or not
	 *			$fileName		Which file to use to send mail
	 *
	 *	@return string
	 */
	function displayEmail($toEmail, $toName, $send, $fileName)
	{
		$dispLength	= $toEmail;
		$display	= '';

		if (strlen($toEmail) > 15) {
			$dispLength	= substr($toEmail, 0, 15) . "...";
		}

		if ($send == "YES") {
			echo	  "<a href=\"javascript:void(0)\" title=\"mail to " . $toEmail . "\" 
			           onclick=\"location.href='" . $fileName . "?toEmail=" . $toEmail . "&amp;toName=" . $toName . "','SendMail','scrollbars=yes,width=650,height=450';\">
					  " . $dispLength . "			  
					  </a>";
			//   onClick=\"MM_openBrWindow('".$fileName."?toEmail=".$toEmail."&amp;toName=".$toName."','SendMail','scrollbars=yes,width=650,height=450')\">
		} else {
			echo 	"<a href='mailto:" . $toEmail . "' title=\"mail to " . $toName . "\" >" . $dispLength . "</a>";
		}

		//return the value
		return $display;
	} //eof

	/**
	 *	Display long string in short format. On mouse over it will show the whole string.
	 *
	 *	@param
	 *			$str	String to display
	 *			$len	Maximum Length
	 *			$off	If str to represent the continuation of the string
	 *
	 *	@return NULL
	 */
	function dispLongStr($str, $len, $off)
	{
		$strLen	= strlen($str);
		$len	= (int) $len;
		$initiativetr	= '';
		$format	= '';

		if ($strLen > $len) {
			$initiativetr	= substr($str, 0, $len) . $off;
		} else {
			$initiativetr	= $str;
		}

		//start the string formatting
		$format	= "<span title='" . $str . "'>" . $initiativetr . "</span>";

		//return the formatted output
		return $format;
	} //eof

	/**
	 *	Display the close button
	 *
	 *	@return null
	 */
	function showCloseButton()
	{
		$str	= "";

		if (isset($_GET['action'])  && ($_GET['action'] == 'success')) {
			$str = " <div align='center'>
						  <input name=\"Button\" type=\"button\" class=\"button-cancel\" 
						  onClick=\"opener.location.reload();self.close()\" value=\"Close\">
					 </div>
					  ";
		}

		//string function
		return $str;
	} //eof

	function currentUrl()
	{

		$url      = "http://" . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
		$validURL = str_replace("&", "&amp;", $url);
		// $validURL = str_replace(".php", "", $url);

		// $url =  "//{$_SERVER['HTTP_HOST']}{$_SERVER['REQUEST_URI']}";
		// $escaped_url = htmlspecialchars( $url, ENT_QUOTES, 'UTF-8' );
		return $validURL;
	}





	/**
	 * This function is used to add st,nd,rd,th after the given number
	 * here $num is the gigen nuber
	 */
	function ordinal($num)
	{

		$last = substr($num, -1);
		if ($last > 3  or $last == 0 or ($num >= 11 and $num <= 19)) {
			$ext = 'th';
		} else if ($last == 3) {
			$ext = 'rd';
		} else if ($last == 2) {
			$ext = 'nd';
		} else {
			$ext = 'st';
		}
		return $num . $ext;
	}



	/**
	 *	Get the url to view the webpage by url or seo url. This is required as before navigating away from the link, the system needs to check
	 *	whether the page is based on content management system or it's an internal or external page.
	 *
	 *	@date	July 05, 2012
	 *
	 *	@param
	 *			$url			URL value stored in the database
	 *			$seoURL			Seo url if found any associated with the content
	 *			$path			Relative or static path to the file
	 *			$content_page	Content page name to forward. e.g. content.php or static.php. This is usable when we 
	 *							need different page for different purposes, such as for product it is product.php
	 *							and for packages, it is packages.php
	 *
	 *	@return	string	
	 */
	function getForwardURLByURLAndSEOURL($url, $seoURL, $path, $content_page)
	{
		//declare var
		$forward_url = '';

		//build up the condition
		if ($url != '') {
			$forward_url = $path . $url;

			if ($seoURL != '') {
				$forward_url = $path . $url . "?seo_url=" . $seoURL;
			}
		} else {
			//remove any white space
			$resStr		= trim($seoURL);

			//get the last character from the string
			$lastChar	= substr($resStr, -1);

			//chop it
			$resStr		= rtrim($resStr, $lastChar);

			//build the forward url
			$forward_url = $path . $content_page . "?seo_url=" . $seoURL;
		}

		//return the forward url
		return $forward_url;
	} //eof





	/**
	 *   This function is to used convert space character into dash(-)
	 *
	 *	@date October 07, 2011
	 *
	 *   @param
	 *			$title			Title of the page or string that has to convert in SEO friendly url
	 *			$parentId       Parent id of the corresponding category id 
	 *			$idCol			Column name of the primary key
	 *			$assCol			Associated column to query		
	 *			$table			Table name
	 *
	 *			 
	 *   @return string
	 */

	function createSEOURL($name, $parentId, $idCol, $assCol, $table)
	{
		//added security
		$name	= strip_tags(trim($name));

		//creating array of special chracters
		$type_arr = array(
			"'", ":", "\"", "\\", "~", "`", "!", "@", "#", "$", "%", "^", "&", "*", "+", "|",
			";", ",", "<", ">", "?", "\/", "/", "{", "}", "="
		);


		//convert to lower case
		$name	= strtolower($name);

		//replace the special characters
		$name	= str_replace($type_arr, "", $name);

		//replace the empty space with -
		$name	= str_replace(" ", "-", $name);

		//replace multiple dash with single dash
		$name	= preg_replace('/[-]+/', '-', $name, 1);

		//add  the SEO url of parent category
		$parentURL	= $this->getValueByKey($parentId, $idCol, $assCol, $table);

		//rewrite the url
		if (count($parentURL) > 0) {
			$name	= $parentURL . $name;
		} else {
			$name	= $name;
		}

		//search for the name with slash
		$nameWS	= $name . "/";

		//counting the occurance of an url
		$numOccarance = $this->getNoOfEntry($nameWS, $assCol, $table);

		//add the trailing slash
		if ($numOccarance > 0) {
			//add the number to the end of the name
			$name	= $name . "-" . ($numOccarance + 1);

			//add the trailing slash
			$name	= $name . "/";
		} else {
			$name 	= $nameWS;
		}

		//return the value
		return $name;
	} //eof




	/**
	 *   This function is to used write SEO URL with the with the category and sub-category  url
	 *
	 *	@date October 10, 2011
	 *
	 *   @param
	 *			$title			Title of the page or string that has to convert in SEO friendly url
	 *			$parentId       Parent id of the corresponding category id 
	 *			$catIdCol		Column name of the primary key
	 *			$catCol			Associated column to query		
	 *			$catTable		Table name of the category
	 *			$statCol		SEO URL column name in the static table
	 *			$statTable		Static content table name
	 *
	 *			 
	 *   @return string
	 */

	function createContentSEOURL($name, $parentId, $catIdCol, $catCol, $catTable, $statCol, $statTable)
	{
		//added security
		$name	= strip_tags(trim($name));
		$urlStr	= '';

		//creating array of special chracters
		$type_arr = array(
			"'", ":", "\"", "\\", "~", "`", "!", "@", "#", "$", "%", "^", "&", "*", "+", "|",
			";", ",", "<", ">", "?", "\/", "/", "{", "}", "="
		);


		//convert to lower case
		$name	= strtolower($name);

		//replace the special characters
		$name	= str_replace($type_arr, "", $name);

		//replace the empty space with -
		$name	= str_replace(" ", "-", $name);

		//replace multiple dash with single dash
		$name	= preg_replace('/[-]+/', '-', $name, 1);

		//add  the SEO url of parent category
		// $contentParentURL	= $this->getValueByKey($parentId, $catIdCol, $catCol, $catTable);
		$contentParentURL	= $this->getValueByKey($parentId, $catIdCol, $catCol, $statTable);



		//rewrite the url
		/*if(count($contentParentURL) > 0)
		{
			$name	= $contentParentURL.$name;
		}
		else
		{
			$name	= $name;
		}*/
		//$name	= $name;

		//search for the name with slash
		$nameWS	= $name . "/";
		//echo $nameWS;
		//counting the occurance of an url
		$numOccarance = $this->getNoOfEntry($nameWS, $statCol, $statTable);

		//add the trailing slash
		if ($numOccarance > 0) {
			//add the number to the end of the name
			$name	= $name . "-" . ($numOccarance + 1);

			//add the trailing slash
			$urlStr	= $name . "/";
		} else {
			$urlStr 	= $nameWS;
		}

		//return the value
		return $urlStr;
	} //eof


	// /**
	//  *	Align image according to it's value associated with a content or paragraph
	//  *
	//  *	@param
	//  *			$alignVal		Alignment of the image
	//  *
	//  *	@return string
	//  */
	// function getImageAlignStr($alignVal)
	// {
	// 	//declare var
	// 	$alignStr	= '';

	// 	switch ($alignVal) {
	// 		case 'left':
	// 			$alignStr = ' fl';
	// 			break;

	// 		case 'right':
	// 			$alignStr = ' fr';
	// 			break;

	// 		case 'center':
	// 			$alignStr = ' cen';
	// 			break;

	// 		default:
	// 			$alignStr = ' fl';
	// 	} //eof of switch

	// 	//return the align string
	// 	return $alignStr;
	// } //eof


	// // sentence teaser
	// // this function will cut the string by how many words you want
	// function word_teaser($string, $count)
	// {
	// 	$original_string = $string;
	// 	$words = explode(' ', $original_string);

	// 	if (count($words) > $count) {
	// 		$words = array_slice($words, 0, $count);
	// 		$string = implode(' ', $words);
	// 	}

	// 	return $string;
	// }
	// // sentence reveal teaser


	// // this function will get the remaining words
	// function word_teaser_end($string, $count)
	// {
	// 	$words = explode(' ', $string);
	// 	$words = array_slice($words, $count);
	// 	$string = implode(' ', $words);
	// 	return $string;
	// }


	/*****************************************************************************
	 *																			 *
	 *								SESSION FUNCTIONS							 *
	 *																			 *
	 *****************************************************************************/


	function setSession($sess, $value)
	{
		try {
			$this->delSession($sess);
			$_SESSION[$sess] = $value;
		} catch (Exception $e) {
			echo $e->getMessage();
		}
	}

	/**
	 *	Delete session if exist.
	 */
	function delSession($sess)
	{
		if (isset($_SESSION[$sess])) {
			$_SESSION[$sess] = '';
			unset($_SESSION[$sess]);
		}
	} //eof


	/**
	 *	Delete session are in array
	 *
	 *	@param
	 *			$sess_arr	Session Array
	 *
	 *	@return	null
	 */
	function delSessArr($sess_arr)
	{
		foreach ($sess_arr as $k) {
			$this->delSession($k);
		}
	} //eof

	/**
	 *	Go to page session
	 *
	 *	@param
	 *			$goTo		Name of the session variable 
	 *			$currentUrl	URL of the current page
	 *
	 *	@return string
	 */
	function setCurrentPageSession()
	{

		//unset the previously set session
		$this->delSession('goTo');

		//register with new page
		$currentUrl = $this->currentUrl();
		$_SESSION['goTo']	= $currentUrl;


		//return page name
		return $currentUrl;
	} //eof



	function goToPreviousSessionPage()
	{
		if (isset($_SESSION['goTo'])) {
			// return $_SESSION['goTo'];
			header('Location: ' . $_SESSION['goTo']);
			exit;
		} else {
			return null;
		}
	}

	function sessionPreviousPage()
	{
		if (isset($_SESSION['goTo'])) {
			return $_SESSION['goTo'];
		} else {
			return null;
		}
	}



	//////////////////////////////////////////////////////////////////////////////////////
	//
	//							Session + Get + Post + Form Fields and Variables
	//
	///////////////////////////////////////////////////////////////////////////////////////

	/**
	 *	Add to session
	 *	
	 *	@param
	 *			$sessArr	Session name
	 *
	 *	@return NULL
	 */
	function addToSession($sessArr)
	{
		foreach ($sessArr as $k) {
			$_SESSION[$k] = $k;
		}
	} //eof


	/**
	 *	Check the radio or check box if the session variable is there
	 *
	 *	@param
	 *			$name		The current name
	 *			$value		The has to select
	 *			$alt		Alternate text or default value
	 *
	 *	@return string
	 */
	function checkSessStr($name, $value, $alt)
	{
		$str 	= '';

		if (isset($_SESSION[$name])) {
			if ($_SESSION[$name] == $value) {
				$str 	= 'checked';
			} else {
				$str 	= $alt;
			}
		} else {
			$str 	= $alt;
		}

		//return
		return $str;
	} //eof

	/**
	 *	Check the radio or check box if the session variable is there
	 *
	 *	@param
	 *			$valArr		The current name
	 *			$value		The has to select
	 *			$alt		Alternate text or default value
	 *
	 *	@return string
	 */
	function checkSessStr2($valArr, $value, $alt)
	{

		$str 	= '';
		if (in_array($value, $valArr)) {
			$str 	= 'checked';
		} else {
			$str 	= $alt;
		}

		//return
		return $str;
	} //eof


	/**
	 *	This function simply print session variables
	 *	
	 *	@param
	 *			$var		Value associated with the the session variable
	 *
	 *	@return NULL
	 */
	function printSess($var)
	{

		if (isset($_SESSION[$var])) {
			echo $_SESSION[$var];
		}
	} //eof

	/**
	 *	This function is a modified version of previous function. It will 
	 *	find for a default value as well.
	 *	
	 *	@param
	 *			$var		Value associated with the the session variable
	 *			$default	Default value to be printed if the session is not registered
	 *
	 *	@return NULL
	 */
	function printSess2($var, $default)
	{

		if (isset($_SESSION[$var])) {
			echo $_SESSION[$var];
		} else {
			echo $default;
		}
	} //eof


	/**
	 *	This function is a modified version of previous function. It will return the session
	 *	value if registered else will return a default value
	 *	
	 *	@param
	 *			$var		Value associated with the the session variable
	 *			$default	Default value to be printed if the session is not registered
	 *
	 *	@return NULL
	 */
	function returnSess($var, $default)
	{

		$sessVal	= '';
		if (isset($_SESSION[$var])) {
			$sessVal  =  $_SESSION[$var];
		} else {
			$sessVal  =  $default;
		}

		return $sessVal;
	} //eof



	/*****************************************************************************
	 *																			 *
	 *								DATABASE FUNCTIONS							 *
	 *																			 *
	 *****************************************************************************/

	function getSingleData($fetch, $table, $column, $value)
	{
		try {
			$data	= array();
			$sql	= "SELECT $fetch FROM $table WHERE $column = '$value'";
			$query	= $this->conn->query($sql);
			// echo $sql;
			if ($query->num_rows > 0) {
				while ($result = $query->fetch_assoc()) {
					$data = $result;
				}
			}
			return $data;
		} catch (Exception $e) {
			echo '<b>Error on:</b> ' . __FILE__ . ', <b>On Line:</b>' . __LINE__ . '<br>';
			echo '<b>Error:</b> ' . $e->getMessage();
			exit;
		}
	}


	/*****************************************************************************
	 *																			 *
	 *								URL MANIPULATION							 *
	 *																			 *
	 *****************************************************************************/

	function url_to_domain($url)
	{
		$pieces = parse_url($url);
		$domain = isset($pieces['host']) ? $pieces['host'] : $pieces['path'];
		if (preg_match('/(?P<domain>[a-z0-9][a-z0-9\-]{1,63}\.[a-z\.]{2,6})$/i', $domain, $regs)) {
			return trim($regs['domain']);
		}
		return false;
	}


	/**
	 *	Advanced version of redirecting as compare to the other functions. This function
	 *	will accept all the URL variables
	 *
	 *	@param
	 *			$url			URL or web address to forward to, generally it is 
	 *							$_SERVER['PHP_SELF']
	 *			$actionType		This variable define the action SUCCESS or ERROR
	 *			$msg			Message id/code or messages to display
	 *			$name			Anchor name to show
	 *
	 *	@return NULL
	 */
	function redirectURL($url, $actionType, $msg, $name = '')
	{

		$parsedURL 	= parse_url($url);
		$name 	= empty($name) == false ? "#" . $name : '';

		if (isset($parsedURL['query'])) {
			// $pattern does not exist in the URL
			header("Location: " . $url . "&action=" . $actionType . "&msg=" . $msg . $name);
			exit;
		} else {
			// $pattern does not exist in the URL
			header("Location: " . $url . "?action=" . $actionType . "&msg=" . $msg . $name);
			exit;
		}
	} //eof


	/*****************************************************************************
	 *																			 *
	 *								URL MANIPULATION							 *
	 *																			 *
	 *****************************************************************************/

	// PHP code to extract IP
	function getVisIpAddr()
	{

		if (!empty($_SERVER['HTTP_CLIENT_IP'])) {
			return $_SERVER['HTTP_CLIENT_IP'];
		} else if (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
			return $_SERVER['HTTP_X_FORWARDED_FOR'];
		} else {
			return $_SERVER['REMOTE_ADDR'];
		}
	}


	/*****************************************************************************
	 *																			 *
	 *								DATABASE UPDATES							 *
	 *																			 *
	 *****************************************************************************/

	function getMysqlTimeZone()
	{

		try {

			$sql = 'SELECT @@global.time_zone as Time_Zone';
			$query   = $this->conn->query($sql);
			while ($row  = $query->fetch_object()) {
				$res	= $row->Time_Zone;
			}

			echo $res;
		} catch (Exception $e) {
			echo $e->getMessage();
		}
	}

	function setMysqlTimeZone($zone)
	{

		try {

			$sql = "SET GLOBAL time_zone = '$zone'";
			$query   = $this->conn->query($sql);
			var_dump($query);
		} catch (Exception $e) {
			echo $e->getMessage();
		}
	}


	/*****************************************************************************
	 *																			 *
	 *								STRING MANIPULATION							 *
	 *																			 *
	 *****************************************************************************/

	/**
	 * This Function will remove some special charagter from the given string
	 * Will Remove : %$?
	 * $str	The given string
	 */
	function getLatersOnly($str)
	{

		//Remove "#","'" and ";" using str_replace() function
		$result = preg_replace('/[0-9%$?]/s', '', $str);

		//The output after remove
		// echo "<br/><b>Text after remove: </b> <br/>".$result;
		return $result;
	}


	function formatName($name)
	{
		$name = basename($name);

		// Remove the file extension if present
		$name = preg_replace('/\\.[^.\\s]{3,4}$/', '', $name);

		// Replace special characters with spaces
		$name = preg_replace('/[^a-zA-Z0-9]/', ' ', $name);

		// Capitalize the string
		$name = ucwords($name);

		return $name;
	}


	function assArrToStr($array, $separator = ', ')
	{
		$result = '';
		foreach ($array as $key => $value) {
			$result .= $value . $separator;
		}
		// Remove the trailing separator
		if (!empty($result)) {
			$result = rtrim($result, $separator);
		}
		return $result;
	}

	function cleanAndExtract($inputString, $reaponseLength)
	{
		// Remove special characters and spaces
		$cleanedString = preg_replace('/[^A-Za-z0-9]/', '', $inputString);

		// Get the first 5 characters
		$result = substr($cleanedString, 0, $reaponseLength);

		return $result;
	}

	/*****************************************************************************
	 *																			 *
	 *									API REQUESTS							 *
	 *																			 *
	 *****************************************************************************/

	function callApi($reqURL)
	{

		// $url = "http://localhost/api/customer.php/customerId/".$cusId;

		// Fetch the API response
		$response = file_get_contents($reqURL);

		// Process the API response (e.g., decode JSON)
		return json_decode($response);
	}



	/********************************************************
	 *														*
	 *				DATE FORMAT CHANGE FUNCTION				*
	 *														*
	 *********************************************************/
	function convertDateFormat($date)
	{
		$dateTime = DateTime::createFromFormat('Y-m-d', $date);
		if ($dateTime === false) {
			return false;
		}
		return $dateTime->format('d-m-Y');
	}



	/// date range array create function
	function createDateRangeArray($startDate, $endDate)
	{
		$dateRange = array();
		$currentDate = strtotime($startDate);
		$endDate = strtotime($endDate);

		while ($currentDate <= $endDate) {
			$dateRange[] = date("d-m-Y", $currentDate);
			$currentDate = strtotime("+1 day", $currentDate);
		}

		return $dateRange;
	}




	// request or query ticket number generator
	// function ticketNumberGenerator()
	// {
	// 	$currentDateTime = date('siymdh');

	// 	$microtime = microtime(true);

	// 	$milliseconds = sprintf("%03d", ($microtime - floor($microtime)) * 1000);

	// 	return $currentDateTime . $milliseconds;
	// }


	function ticketNumberGenerator()
	{
		$length = 5;
		$characters = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';
		$result = '';
		
		for ($i = 0; $i < $length; $i++) {
			$randomIndex = rand(0, strlen($characters) - 1);
			$result .= $characters[$randomIndex];
		}
		
		return 'T-'.$result;
	}
} //eoc





function isAdminId($id)
{
	// Check if the ID starts with the prefix 'ADM'
	return strpos($id, 'ADM') === 0;
}


// function getNameFromId($id){
// 	if(isAdminId($id)){
// 		$query = "SELECT fname, lname FROM admin"; 
// 	}
// }



/*****************************************************************************
 *																			 *
 *									Date Manipulation						 *
 *																			 *
 *****************************************************************************/
function getNextDate($currentDate, $duration)
{
	// Convert the input date string to a DateTime object
	$currentDateTime = new DateTime($currentDate);

	// Extract the number and unit from the duration string
	preg_match('/(\d+)\s*(\w+)/', $duration, $matches);
	if (!$matches || count($matches) != 3) {
		// Invalid duration format
		return false;
	}

	$number = (int) $matches[1];
	$unit = strtolower($matches[2]);

	// Add the specified duration to the current date
	switch ($unit) {
		case 'day':
		case 'days':
			$currentDateTime->modify("+$number days");
			break;
		case 'week':
		case 'weeks':
			$currentDateTime->modify("+$number weeks");
			break;
		case 'month':
		case 'months':
			$currentDateTime->modify("+$number months");
			break;
		case 'year':
		case 'years':
			$currentDateTime->modify("+$number years");
			break;
		default:
			// Handle invalid unit
			return false;
	}

	// Format the result as a string and return
	return $currentDateTime->format('Y-m-d');
}


function formatDateTime($datetimeString, $dateDivider = '-', $showTime = False, $timeDivider = '.')
{
	// Create a DateTime object from the input string
	$datetime = new DateTime($datetimeString);

	// Format the date part as 'd-m-Y'
	$datestring = $datetime->format("d{$dateDivider}m{$dateDivider}Y");

	if ($showTime === True) {
		// Format the time part as 'g.iA' (12-hour format with am/pm)
		$datestring .= $datetime->format(" g{$timeDivider}iA");
	}

	// Concatenate the formatted date and time
	return $datestring;
}


function reverseFormatedDateTime($datetimeString)
{
	$dateObj = DateTime::createFromFormat('d-m-Y', $datetimeString);
	$formattedDate = $dateObj->format('Y-m-d');

	return $formattedDate;
}
