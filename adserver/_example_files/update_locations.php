<?php require_once("./includes/connection.php"); ?>
<?php require_once("./includes/functions.php"); ?>
<link rel="stylesheet" href="/templates/proclinicfl/css/style.css" type="text/css" />
<link rel="stylesheet" href="/templates/proclinicfl/css/lab_menu_locations.css" type="text/css" />

<div id="focus">
	<h1>Update Locations - Success</h1>

<?php
			require_once("./navigation.php");
	echo "<br /><br /><hr /><br />"; 
	echo "<div id=\"focus\">";
	//var_dump(get_defined_vars());

     
    $size = count($_POST['id']);
	//echo "<h1>".$size."</h1>";
      
    $i = 0;
    while ($i < $size) {
		$id = $_POST['id'][$i];
		$name = $_POST['name'][$i];
		$company = $_POST['company'][$i];
		$address1 = $_POST['address1'][$i];
		$address2 = $_POST['address2'][$i];
		$city = $_POST['city'][$i];
		$state = $_POST['state'][$i];
		$zip = $_POST['zip'][$i];
		$phone = $_POST['phone'][$i];
		$fax = $_POST['fax'][$i];
		$hours = $_POST['hours'][$i];
		$hours2 = $_POST['hours2'][$i];
		 
		$query = "
			UPDATE locations 
			SET 
				name = '$name',
				company = '$company',
				address1 = '$address1',
				address2 = '$address2',
				city = '$city',
				state = '$state',
				zip = '$zip',
				phone = '$phone',
				fax = '$fax',
				hours = '$hours',
				hours2 = '$hours2'
			WHERE id = '$id' 
			LIMIT 1";
		$result = mysql_query($query, $connection);
		if ($result) {
			// Success!
			//echo "$name - <em>Updated!</em><br />";
		} else {
			// Display error message.
			echo "<p>Subject creation failed.</p>";
			echo "<p>" . mysql_error() . "</p>";
		}
		//		mysql_query($query) or die ("Error in query: $query");
			echo "$name - <em>Updated!</em><br />";
			++$i;
			
    }
			echo "</div>"
?>



