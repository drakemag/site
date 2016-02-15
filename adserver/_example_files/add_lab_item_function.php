<?php require_once("./includes/connection.php"); ?>
<?php require_once("./includes/functions.php"); ?>
<link rel="stylesheet" href="/templates/proclinicfl/css/style.css" type="text/css" />
<link rel="stylesheet" href="/templates/proclinicfl/css/lab_menu_locations.css" type="text/css" />

<div id="focus">
	<h1>Add New Lab Item(s) - Success</h1>

<?php
			require_once("./navigation.php");
	echo "<br /><br /><hr /><br />"; 

$size = count($_POST['type']);
//echo $size;
//print_r($_POST);

$counter = 0;
while ($counter < $size && $_POST['type'][$counter] != '') {
		$type = $_POST['type'][$counter];
		$code = $_POST['code'][$counter];
		$name = $_POST['name'][$counter];
		$description = mysql_real_escape_string($_POST['description'][$counter]);
		$price = $_POST['price'][$counter];
		//echo $type, $code, $name, $description, $price . "<br />";
		$query_string = " (
			'".$type."', 
			'".$code."', 
			'".$name."', 
			'".$description."', 
			'".$price
			."')";
		
		$query = mysql_query("INSERT INTO lab_menu (type, code, name, description, price) VALUES ". $query_string);
		$result = mysql_query($query, $connection);

		if ($query) {
			// Success!
			echo "<strong>added to database:</strong><br />";
			echo "type = " . $type . "<br />";
			echo "code = " . $code . "<br />";
			echo "name = " . $name . "<br />";
			echo "description = " . $description . "<br />";
			echo "price = " . $price . "<br />";
			//echo $query_string;
			echo "<br />";
		} else {
			// Display error message.
			echo "<p>Subject creation failed.</p>";
			echo "<p>" . mysql_error() . "</p>";
		}

		$counter++;
};


?>



