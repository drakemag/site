<?php require_once("./includes/connection.php"); ?>
<?php require_once("./includes/functions.php"); ?>
<link rel="stylesheet" href="/templates/proclinicfl/css/style.css" type="text/css" />
<link rel="stylesheet" href="/templates/proclinicfl/css/lab_menu_locations.css" type="text/css" />

<div id="focus">
	<h1>Add New Lab Item(s) - Success</h1>

<?php
			require_once("./navigation.php");
	echo "<br /><br /><hr /><br />"; 
	//var_dump(get_defined_vars());

     
		//$id = $_POST['id'];
		$name = $_POST['name'];
		$company = $_POST['company'];
		$address1 = $_POST['address1'];
		$address2 = $_POST['address2'];
		$city = $_POST['city'];
		$state = $_POST['state'];
		$zip = $_POST['zip'];
		$phone = $_POST['phone'];
		$fax = $_POST['fax'];
		$hours = $_POST['hours'];
		$hours2 = $_POST['hours2'];

		//echo $name. "<br />" . $company. "<br />" . $address1. "<br />" . $address2. "<br />" . $city. "<br />" . $state. "<br />" . $zip. "<br />" . $phone. "<br />" . $fax. "<br />" . $hours. "<br />" . $hours2;
		 
		$query = "
			INSERT INTO locations 
				(id,name,company,address1,address2,city,state,zip,phone,fax,hours,hours2)
			VALUES 
				('','$name','$company','$address1','$address2','$city','$state','$zip','$phone','$fax','$hours','$hours2')
				";
		$result = mysql_query($query, $connection);
		if ($result) {
			// Success!
			echo "$name - <em>successfully added!</em><br />";
			//require_once("./scripts/navigation.php");
			//redirect_to("/scripts/add_location.php");
			
			echo "name = " . $name . "<br />";
			echo "company = " . $company . "<br />";
			echo "address1 = " . $address1 . "<br />";
			echo "address2 = " . $address2 . "<br />";
			echo "city = " . $city . "<br />";
			echo "state = " . $state . "<br />";
			echo "zip = " . $zip . "<br />";
			echo "phone = " . $phone . "<br />";
			echo "fax = " . $fax . "<br />";
			echo "hours = " . $hours . "<br />";
			echo "hours2 = " . $hours2;
			echo "<br /><hr /><br />";

		} else {
			// Display error message.
			echo "<p>Subject creation failed.</p>";
			echo "<p>" . mysql_error() . "</p>";
		}
		//		mysql_query($query) or die ("Error in query: $query");
			//echo "$name - <em>Updated!</em><br />";
			//++$i;

?>



