<?php 
    require_once("includes/header_info.php");
?>

<link rel="stylesheet" href="css/style.css" type="text/css" />
<?php 
    require_once("includes/connection.php");
    require_once("includes/functions.php");
    require_once("includes/nav.php");
?>


<div id="wrapper">
<?php //print_r($_POST); ?>
<h1>Ad Server: Upload Advert</h1>
    


<?php
	$target = "images/banners/"; 
	$target = $target . basename( $_FILES['uploaded']['name']) ; 
	$ok=1; 

		 //This is our size condition 
	if ($uploaded_size > 350000) 
	{ 
		echo "Your file is too large.<br>"; 
		$ok=0; 
	} 

		 //This is our limit file type condition 
	if ($uploaded_type =="text/php") 
	{ 
		echo "No PHP files<br>"; 
		$ok=0; 
	} 

		 //Here we check that $ok was not set to 0 by an error 
	if ($ok==0) 
	{ 
		Echo "Sorry your file was not uploaded"; 
	} 

		 //If everything is ok we try to upload it 
	else 
	{ 
		if(move_uploaded_file($_FILES['uploaded']['tmp_name'], $target)) 
		{ 
			echo "The file ". basename( $_FILES['uploadedfile']['name']). " has been uploaded"; 
		} 
		else 
		{ 
			//echo "Sorry, there was a problem uploading your file."; 
		} 
	} 
?> 

<form enctype="multipart/form-data" action="#?action=upload" method="POST">
 Please choose a file: <input name="uploaded" type="file" /><br />
 <input type="submit" value="Upload" />
 </form> 


