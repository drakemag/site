<?php
//We need this script
require('php-captcha.inc.php');
 
//Init cURL (If you don't have cURL support...)
$ch = curl_init();

//If you want to log entries, These are the only variables you will need to change
$writeToDB = true;	//Keep set to true if you want to log anything to a flat file
$dbFile = "database.txt"; //The path to your database.txt file. All fields are split with commas.

$email_field_name = "fields_email"; //The Text field from which the email will be provided by the user
$fields_fname = "fields_fname";
$fields_lname = "fields_lname";
$fields_company = "fields_company";
$fields_phone = "fields_phone";
$fields_address1 = "fields_address1";
$fields_address2 = "fields_address2";
$fields_city = "fields_city";
$fields_state = "fields_state";
$fields_zip = "fields_zip";
$fields_comments = "fields_comments";

$include_date_submit = true; //Includes the date the text data was submitted on in ISO 8601 format

//Set target URL
$url = "http://app.icontact.com/icp/signup.php"; //iContact form process URL (Important!)
curl_setopt($ch, CURLOPT_URL, $url);

//Set User-Agent (Also pretty important)
//Below user-agent is taken from Firefox 3. user-agent.org is a good place to find others. 
//iContact's scripts won't discriminate against kind of user-agent, but it's not a wise idea to use cURL's default UA string
//neither is 'Smallfish Captcha Script' or 'Hopping Woman'
$useragent = "Mozilla/5.0 (Windows; U; Windows NT 6.0; en-US; rv:1.9.0.3) Gecko/2008092417 Firefox/3.0.3";
curl_setopt($ch, CURLOPT_USERAGENT, $useragent);

//Captcha check
if (PhpCaptcha::Validate($_POST['user_code'])) {
	//Ok, we have the right code -- we can send this data
	//echo 'Valid code entered<br>';
	foreach ($_POST as $key => $value) {
		//echo "In Loop";
		//echo $value . "<br>";
		$fields_string .= $key . '=' . $value . '&'; 
		//echo $_POST['fields_email'];
	}
	//echo $fields_string; 
	rtrim($fields_string);

	//Let's determine how must POSTDATA we're sending
	curl_setopt($ch, CURLOPT_POST, count($_POST));

	//Set the POST data
	curl_setopt($ch, CURLOPT_POSTFIELDS, $fields_string);

	//Execute cURL and then close it
	$result = curl_exec($ch);
	curl_close($ch);

	//Writes out email to the database file defined in headers
	if ($writeToDB) {
		//Write this string to the file we defined earlier
		$fp = fopen($dbFile, "a+") or die("Could not create or access the file");
		if ($include_date_submit) {
			$tmpStr = 
				$_POST[$fields_fname] . "," . 
				$_POST[$fields_lname] . "," .   
				$_POST[$email_field_name] . "," . 
				$_POST[$fields_phone] . "," . 
				
				$_POST[$fields_company] . "," .
				$_POST[$fields_address1] . "," .
				$_POST[$fields_address2] . "," .
				$_POST[$fields_city] . "," .
				$_POST[$fields_state] . "," .
				$_POST[$fields_zip] . "," .				
				
				$_POST[$fields_comments] . "," . 
		
				date('Y-m-d H:i:s') . "\n";
		} else {
			$tmpStr = 
			$_POST[$email_field_name] . "\n";
		}
	    $numBytes = fwrite($fp, $tmpStr);
		fclose($fp);
	}

	
		$admin_email = "info@drakemag.com,geoff@drakemag.com"; //The email address you want the POSTDATA sent to.
		$subject = "Drake Magazine - Advertising Information Request" . " - " . $timeOfDay; //Subject of the emails sent by this script.
		$timeOfDay = date("Y:m:d H:i"); //Format of the time displayed in the email. Refer to the PHP date() documentation for details on changing it.
	
		//email all this information to the defined emails
		//Concantenates the emailSubject and timeOfDay variables together
		$email = $_POST[$email_field_name];
		$mailMessage = 
			"Date: " . date('Y-m-d H:i:s') . "\r\n" .
			"Name: " . $_POST[$fields_fname] . " " . $_POST[$fields_lname] . "\r\n" . 
			"Email: " . $_POST[$email_field_name] . "\r\n" . 
			"Company: " . $_POST[$fields_company] . "\r\n" . 
			"Address1: " . $_POST[$fields_address1] . "\r\n" . 
			"Address2: " . $_POST[$fields_address2] . "\r\n" . 
			"City: " . $_POST[$fields_city] . "\r\n" . 
			"State: " . $_POST[$fields_state] . "\r\n" . 
			"Zip: " . $_POST[$fields_zip] . "\r\n" . 
			"Phone: " . $_POST[$fields_phone] . "\r\n" . 
			"Comments: " . $_POST[$fields_comments] . "\r\n";
		
		//send email
		mail($admin_email, "$subject", $mailMessage, "From:" . $email);
			
	
	

  
	
	//Go to the success page
	header( 'Location:'. $_POST['redirect'] .'' );
	
  
  
	
} else {
	//i'm the only echo statement left! Well at least I have all my characters...
	echo 'Invalid code entered. Please click the back button on your browser and try again.';
}
 

?>
