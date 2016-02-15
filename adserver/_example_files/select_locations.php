<?php require_once("./includes/connection.php"); ?>
<?php require_once("./includes/functions.php"); ?>

<link rel="stylesheet" href="/templates/proclinicfl/css/style.css" type="text/css" />
<link rel="stylesheet" href="/templates/proclinicfl/css/lab_menu_locations.css" type="text/css" />

<div id="focus">
	<h1>Update Locations</h1>

<?php
			require_once("./navigation.php");

	$result = mysql_query("SELECT * FROM locations ORDER BY id", $connection);
	if (!$result) {
		die("Database query failed: " . mysql_error());
	};
	 
	$i = 0;
	 
	echo "<table class=\"edit_table\">";
	echo "	<tr>";
	echo "		<th class=\"id\">id</th>";
	echo "		<th class=\"company\">name / company</th>";
	echo "		<th class=\"address\">address,address2,city, state zip</th>";
	echo "		<th class=\"phone\">phone / fax</th>";
	echo "		<th class=\"hours\">hours / hours2</th>";
	echo "	</tr>";
	 
	echo "<form name='form_update' method='post' action='update_locations.php'>\n";
	while ($data = mysql_fetch_array($result)) {
		echo "	<tr>\n";
		echo "		<td rowspan=\"3\">{$data['id']}<input type='hidden' name='id[$i]' value='{$data['id']}' /></td>";
		echo "		<td><input type='text' name='name[$i]' value='{$data['name']}' /></td>\n";
		echo "		<td><input type='text' name='address1[$i]' value='{$data['address1']}' /></td>\n";
		echo "		<td><input type='text' name='phone[$i]' value='{$data['phone']}' /></td>\n";
		echo "		<td><input type='text' name='hours[$i]' value='{$data['hours']}' /></td>\n";
		echo "	</tr>\n";
		echo "	<tr>\n";
		echo "		<td rowspan=\"2\"><input type='text' name='company[$i]' value='{$data['company']}' /></td>\n";
		echo "		<td><input type='text' name='address2[$i]' value='{$data['address2']}' /></td>\n";
		echo "		<td rowspan=\"2\"><input type='text' name='fax[$i]' value='{$data['fax']}' /></td>\n";
		echo "		<td rowspan=\"2\"><input type='text' name='hours2[$i]' value='{$data['hours2']}' /></td>\n";
		echo "	</tr>\n";
		echo "	<tr>\n";
		echo "		<td>
						<input type='text' class='inline csz' name='city[$i]' value='{$data['city']}' />
						<input type='text' class='inline csz' name='state[$i]' value='{$data['state']}' />
						<input type='text' class='inline csz' name='zip[$i]' value='{$data['zip']}' />
					</td>\n";
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
