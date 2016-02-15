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
$fields_address1 = "fields_address1";
$fields_address2 = "fields_address2";
$fields_city = "fields_city";
$fields_state = "fields_state";
$fields_zip = "fields_zip";
$fields_country = "fields_country";

$email_field_name_new = "fields_email_new"; //The Text field from which the email will be provided by the user
$fields_fname_new = "fields_fname_new";
$fields_lname_new = "fields_lname_new";
$fields_address1_new = "fields_address1_new";
$fields_address2_new = "fields_address2_new";
$fields_city_new = "fields_city_new";
$fields_state_new = "fields_state_new";
$fields_zip_new = "fields_zip_new";
$fields_country_new = "fields_country_new";

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
				$_POST[$email_field_name] . "," .   
				$_POST[$fields_fname] . "," . 
				$_POST[$fields_lname] . "," .   
				$_POST[$fields_address1] . "," .   
				$_POST[$fields_address2] . "," .   
				$_POST[$fields_city] . "," .   
				$_POST[$fields_state] . "," .   
				$_POST[$fields_zip] . "," .   
				$_POST[$fields_country] . "," .   
				
				$_POST[$email_field_name_new] . "," .   
				$_POST[$fields_fname_new] . "," .   
				$_POST[$fields_lname_new] . "," .   
				$_POST[$fields_address1_new] . "," .   
				$_POST[$fields_address2_new] . "," .   
				$_POST[$fields_city_new] . "," .   
				$_POST[$fields_state_new] . "," .   
				$_POST[$fields_zip_new] . "," .   
				$_POST[$fields_country_new] . "," . 

				date('Y-m-d H:i:s') . "\n";
		} else {
			$tmpStr = 
$_POST[$email_field_name] . "\n";
		}
	    $numBytes = fwrite($fp, $tmpStr);
		fclose($fp);
	}
$admin_email = "info@drakemag.com"; //The email address you want the POSTDATA sent to.
$emailSubject = "Drake Maggazine - Change of Address"; //Subject of the emails sent by this script.
$timeOfDay = date("Y:m:d H:i"); //Format of the time displayed in the email. Refer to the PHP date() documentation for details on changing it.

                //email all this information to the defined emails
                //Concantenates the emailSubject and timeOfDay variables together
                $emailSubject = $emailSubject . " - " . $timeOfDay; //Change $emailSubject and $timeOfDay at the top of the script
                $headers = "From: ". $_POST[$email_field_name] . "\r\n"; //Will set the 'from' field in said email to that put into the form's 'email' field, permitting an easier way to contact the sender.
				$mailMessage = 
					"Date: " . date('Y-m-d H:i:s') . "\n" .
					"". "\r\n" .
					"PREVIOUS ADDRESS:" . "\r\n" .
					"Name: " . $_POST[$fields_fname] . " " . $_POST[$fields_lname] . "\r\n" . 
					"Email: " . $_POST[$email_field_name] . "\r\n" . 
					"Address1: " . $_POST[$fields_address1] . "\r\n" . 
					"Address2: " . $_POST[$fields_address2] . "\r\n" . 
					"City: " . $_POST[$fields_city] . "\r\n" . 
					"State: " . $_POST[$fields_state] . "\r\n" . 
					"Zip: " . $_POST[$fields_zip] . "\r\n" . 
					"Country: " . $_POST[$fields_country] . "\r\n" .
					"". "\r\n" .
					"". "\r\n" .

					"NEW ADDRESS" . "\r\n" .
					"Name: " . $_POST[$fields_fname_new] . " " . $_POST[$fields_lname_new] . "\r\n" .
					"Email: " . $_POST[$email_field_name_new] . "\r\n" .
					"Address1: " . $_POST[$fields_address1_new] . "\r\n" . 
					"Address2: " . $_POST[$fields_address2_new] . "\r\n" . 
					"City: " . $_POST[$fields_city_new] . "\r\n" . 
					"State: " . $_POST[$fields_state_new] . "\r\n" . 
					"Zip: " . $_POST[$fields_zip_new] . "\r\n" . 
					"Country: " . $_POST[$fields_country_new] . "\r\n";
                mail($admin_email, $emailSubject, $mailMessage, $headers);

	//Go to the success page
	header( 'Location:'. $_POST['redirect'] .'' );
	
} else {
	//i'm the only echo statement left! Well at least I have all my characters...
	echo 'Invalid code entered. Please click the back button on your browser and try again.';
}
 
 
?>
