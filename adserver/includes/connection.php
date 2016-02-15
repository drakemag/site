<?php
	require("constants.php");
	
	//conection:
	$connection = mysqli_connect(DB_SERVER,DB_USER,DB_PASS,DB_NAME) or die("Error " . mysqli_error($connection)); 

	
?>


