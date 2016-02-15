<?php
error_reporting(E_ALL ^ E_NOTICE);  //without this everything goes bonkers and dosen't work
/********************************************************************
EMAIL SCRIPT
*********************************************************************
*  This script checks the captcha to see if it has been solved,
*  and if it has, processes the POSTDATA into an email. In order for 
*  this to work, your version of PHP Must have email support compiled 
*  in (in addition to GD for the captcha), and the 'database.txt'
*  file must be writable by everyone.
********************************************************************/

/*******************************************************************
*					FORM VARIABLES
*******************************************************************/
//These are the only variables you will need to change
$dbFile = "database.txt"; //The path to your database.txt file. All fields are split with commas.
$emailAddr = "mike@bulldawgmfg.com"; //The email address you want the POSTDATA sent to.
$emailSubject = "Bulldawg Mfg Information Request"; //Subject of the emails sent by this script.
$timeOfDay = date("Y:m:d H:i"); //Format of the time displayed in the email. Refer to the PHP date() documentation for details on changing it.
$blacklist = array("<br>","<BR>","<br />","<BR />"); //Simple blacklist that checks the comment body for any known characters that may appear from spambot-generated text. Each string needs to be formatted following the POSIX Extended Regular Expression guidelines.
$redirectURL = "/index.php?option=com_content&view=article&id=3"; 

//Variables you probably shouldn't touch
$should_continue = true;

//This is where the magic happens
require('php-captcha.inc.php');

//We check to see if the user solved the captcha correctly
if (PhpCaptcha::Validate($_POST['word'])) {
	//Now we look for potential keywords associated with spam
	foreach($blacklist as $valueChk) {
		if(ereg($valueChk, $_POST['comments'])) {
			$should_continue = false;
			echo "Captcha circumvention enabled -- message not sent. Have a nice day.\n";
		}
	}
	//If we have no spam characters, we can finish submitting the form
	if($should_continue) {
		//To make sure the user has all the information required, we're going to use Javascript
		foreach ($_POST as $name=>$value) {
			//If it determines a blank field, we put something in there to show that it is so for cleanliness
			if ($_POST[$name] == "") {
				$concStr = $concStr . "" . ",";
			} elseif ($name == "word") {
				break;			
			} else {
				$concStr = $concStr . $_POST[$name] . ",";
			}
			$mailMessage = $mailMessage . "$name: $value \n";
			//echo "$name: $_POST[$name]<br />";
		}
		//check to see if the user at least has the name and email listed
		if ($_POST['name'] == "") {
			echo "Sorry, but we require you provide a name and/or email for us to contact you back with. Please go back and correct this. Thanks!";
			exit;
		}
		if ($_POST['email'] == "" ) {
			echo "Sorry, but we require you provide a name and/or email for us to contact you back with. Please go back and correct this. Thanks!";
			exit;
		}
		$concStr = $concStr . "\n";
		//echo $concStr;
		
		//Write this string to the file we defined earlier
		$fp = fopen($dbFile, "a+") or die("Could not create or access the file");
	    $numBytes = fwrite($fp, $concStr);
		fclose($fp);
		
		//email all this information to the defined emails
		//Concantenates the emailSubject and timeOfDay variables together
		$emailSubject = $emailSubject . " - " . $timeOfDay; //Change $emailSubject and $timeOfDay at the top of the script
		$headers = "From: ". $_POST['email'] . "\r\n"; //Will set the 'from' field in said email to that put into the form's 'email' field, permitting an easier way to contact the sender.
		mail($emailAddr, $emailSubject, $mailMessage, $headers);
		echo "<meta http-equiv='refresh' content='3;url=" . $redirectURL . "'>";
		echo "Your inquiry has been submitted! Have a nice day.<br />You will be redirected to the home page shortly";
	} else {
		echo "Error";
	}
} else {
	echo 'Wrong code entered, please try again.';
}
?>
