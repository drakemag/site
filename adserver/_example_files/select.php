<?php require_once("./includes/connection.php"); ?>
<?php require_once("./includes/functions.php"); ?>

<link rel="stylesheet" href="/templates/proclinicfl/css/style.css" type="text/css" />
<link rel="stylesheet" href="/templates/proclinicfl/css/lab_menu_locations.css" type="text/css" />

<div id="focus">
	<h1>Update Lab Menu</h1>

<?php
			require_once("./navigation.php");

	$result = mysql_query("SELECT * FROM lab_menu ORDER BY id", $connection);
	if (!$result) {
		die("Database query failed: " . mysql_error());
	};
	 
	$i = 0;
	 
	echo "<table class=\"edit_table\">";
	echo "	<tr>";
	echo "		<th class=\"id\">id</th>";
	echo "		<th class=\"type\">type</th>";
	echo "		<th class=\"code\">code</th>";
	echo "		<th class=\"name\">name/description</th>";
	echo "		<th class=\"price\">price</th>";
	echo "	</tr>";
	 
	echo "<form name='form_update' method='post' action='update.php'>\n";
	while ($data = mysql_fetch_array($result)) {
/*
		echo '	<tr>';
		echo "		<td>{$data['id']}<input type='hidden' name='id[$i]' value='{$data['id']}' /></td>";
		echo "		<td><input type='text' size='40' name='type[$i]' value='{$data['type']}' /></td>";
		echo "		<td><input type='text' size='40' name='code[$i]' value='{$data['code']}' /></td>";
		echo "		<td><input type='text' size='40' name='name[$i]' value='{$data['name']}' /></td>";
		echo "		<td><input type='text' size='40' name='description[$i]' value='{$data['description']}' /></td>";
		echo "		<td><input type='text' size='40' name='price[$i]' value='{$data['price']}' /></td>";
		echo '	</tr>';
*/

		
		echo "	<tr>\n";
		echo "		<td rowspan=\"2\">{$data['id']}<input type='hidden' name='id[$i]' value='{$data['id']}' /></td>";
		echo "		<td rowspan=\"2\"><input type='text' name='type[$i]' value='{$data['type']}' /></td>\n";
		echo "		<td rowspan=\"2\"><input type='text' name='code[$i]' value='{$data['code']}' /></td>";
		echo "		<td><strong><input type='text' name='name[$i]' value='{$data['name']}' /></strong></td>\n";
		echo "		<td rowspan=\"2\"><strong><input type='text' name='price[$i]' value='{$data['price']}' /></strong></td>\n";
		echo "	</tr>\n";
		echo "	<tr>\n";
		echo "		<td><input type='text' name='description[$i]' value='{$data['description']}' /></td>\n";	
		echo "	</tr>\n";
		
		
		++$i;
	}
	echo '	<tr>';
	echo "		<td><input type='submit' value='submit' /></td>";
	echo '	</tr>';
	echo "</form>";
	echo '</table>';
?>
</div>
