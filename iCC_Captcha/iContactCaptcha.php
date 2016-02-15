<?php
//INIT required components
require('php-captcha.inc.php');
$ch = curl_init();

/*
  * CHANGE THESE VARIABLES ONLY
  * If you want to log entries, These are the only variables you will need to change
  */
$writeToDB = true;	//Keep set to true if you want to log anything to a flat file
$dbFile = "database.txt"; //The path to your database.txt file. All fields are split with commas.
$email_field_name = "fields_email"; //The Text field from which the email will be provided by the user
$comments_field_name = "fields_comments"; //Textarea or field in which comments are stored
$include_date_submit = true; //Includes the date the text data was submitted on in ISO 8601 format
$adminEmail = "todd@toweeboats.com"; //Email to Carbon Copy all submissions

//Set target URL
$url = "http://app.icontact.com/icp/signup.php"; //iContact form process URL (Important!)
curl_setopt($ch, CURLOPT_URL, $url);

//Set User-Agent (Also pretty important)
//Below user-agent is taken from Firefox 3. user-agent.org is a good place to find others. 
//iContact's RESTful API won't discriminate against kind of user-agent, but it's not a wise idea to use cURL's default UA string
$useragent = "Mozilla/5.0 (Windows; U; Windows NT 6.0; en-US; rv:1.9.0.3) Gecko/2008092417 Firefox/3.0.3";
curl_setopt($ch, CURLOPT_USERAGENT, $useragent);

//Captcha check
if (PhpCaptcha::Validate($_POST['user_code'])) {
	//Ok, we have the right code -- we can send this data
	echo 'Valid code entered<br>';
	foreach ($_POST as $key => $value) {
		$fields_string .= $key . '=' . $value . '&';
	}
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
			$tmpStr = date('Y-m-d H:i:s') . "," . $_POST[$email_field_name] . "," . $_POST[$comments_field_name] . "\n";
		} else {
			$tmpStr = $_POST[$email_field_name] . "," . $_POST[$comments_field_name] . "\n";
		}
	    $numBytes = fwrite($fp, $tmpStr);
		fclose($fp);
	}
	
	//Prepare email for submission
	$subject = "Info Request";
	$emailBody = "On " . date('Y-m-d H:i:s') . " a form submission was instanced by " . $_SERVER['REMOTE_ADDR'] . " with the following remarks in the comments: \n\n " . $_POST[$comments_field_name] . "\n\n\n Reply to this email to contact him directly.";
	$headers = "Reply-to: " . $_POST[$email_field_name];
	
	//Submit Email
	if (mail($adminEmail, $subject, $emailBody, $headers)) {
		echo "Derp";
	} else {
		echo "ERROR: Failed to send mail.";
	}
	
	
	//Go to the success page
	header( 'Location: http://www.strutswings.com/index.php?option=com_content&view=article&id=110' ) ;
	
} else {
	//i'm the only echo statement left! Well at least I have all my characters...
	echo 'Invalid code entered. Please click the back button on your browser and try again.';
}
 

?>
