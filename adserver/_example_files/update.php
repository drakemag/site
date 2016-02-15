<?php require_once("./includes/connection.php"); ?>
<?php require_once("./includes/functions.php"); ?>
<link rel="stylesheet" href="/templates/proclinicfl/css/style.css" type="text/css" />
<link rel="stylesheet" href="/templates/proclinicfl/css/lab_menu_locations.css" type="text/css" />

<div id="focus">
	<h1>Update Lab Menu - Success</h1>

<?php
			require_once("./navigation.php");
	echo "<br /><br /><hr /><br />"; 
//	var_dump(get_defined_vars());

     
    $size = count($_POST['id']);
     
    $i = 0;
    while ($i < $size) {
		$id = $_POST['id'][$i];
		$type = $_POST['type'][$i];
		$code = $_POST['code'][$i];
		$name = $_POST['name'][$i];
		$description = $_POST['description'][$i];
		$price = $_POST['price'][$i];
		 
		echo "<strong>" . $id . "</strong><br />"; 
		echo $type . "<br />"; 
		echo $code . "<br />"; 
		echo $name . "<br />"; 
		echo $description . "<br />"; 
		echo $price . "<br /><br />"; 

		$query = "
			UPDATE lab_menu 
			SET type = '$type',
			code = '$code', 
			name = '$name', 
			description = '$description', 
			price = '$price' 
			WHERE id = '$id' 
			LIMIT 1";
		$result = mysql_query($query, $connection);
		if ($result) {
			// Success!
//			redirect_to("/scripts/select.php");
			echo "$name - <em>Updated!</em><br />";
		} else {
			// Display error message.
			echo "<p>Subject creation failed.</p>";
			echo "<p><strong>error msg: </strong>" . mysql_error() . "</p>";
		}
		//		mysql_query($query) or die ("Error in query: $query");
			++$i;


		echo "<hr />";
    }

?>



