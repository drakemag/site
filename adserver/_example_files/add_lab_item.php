<?php require_once("./includes/connection.php"); ?>
<?php require_once("./includes/functions.php"); ?>

<link rel="stylesheet" href="/templates/proclinicfl/css/style.css" type="text/css" />
<link rel="stylesheet" href="/templates/proclinicfl/css/lab_menu_locations.css" type="text/css" />

<div id="focus">
	<h1>Add New Lab Items</h1>

<?php
			require_once("./navigation.php");
	 
	 
	echo "<table class=\"edit_table\">";
	 
	echo "<form name='form_update' method='post' action='add_lab_item_function.php'>\n";

	$i = 0;
	while($i <= 5){		
		echo "	<tr>";
		echo "		<th class=\"id\">id</th>";
		echo "		<th class=\"type\">type</th>";
		echo "		<th class=\"code\">code</th>";
		echo "		<th class=\"name\">name/description</th>";
		echo "		<th class=\"price\">price</th>";
		echo "	</tr>";
		echo "	<tr>\n";
		echo "		<td rowspan=\"2\">" . /*{$data['id']}<input type='hidden' name='id[$i]' value='{$data['id']}' /> */ "</td>";

		echo "			<td rowspan=\"2\">\n
							<select name=\"type[$i]\" tabindex=\"" . $i . "1\" />\n
								<option value=\"\" selected></option>";
								$result = mysql_query("SELECT DISTINCT(type) FROM lab_menu ORDER BY id", $connection);
								if (!$result) {
									die("Database query failed: " . mysql_error());
								};
								while ($data = mysql_fetch_array($result)) {
									echo "	<option value='{$data['type']}'>{$data['type']}</option>\n";
								}
		echo "				</select>\n
						</td>\n";
		//echo "		<td rowspan=\"2\"><input type='text' name='type[$i]' tabindex=\"" . $i . "1\" /></td>\n";
		echo "		<td rowspan=\"2\"><input type='text' name='code[$i]' tabindex=\"" . $i . "2\" /></td>";
		echo "		<td><strong><input type='text' name='name[$i]' tabindex=\"" . $i . "3\" /></strong></td>\n";
		echo "		<td rowspan=\"2\"><strong><input type='text' name='price[$i]' tabindex=\"" . $i . "5\" /></strong></td>\n";
		echo "	</tr>\n";
		echo "	<tr>\n";
		echo "		<td><input type='text' name='description[$i]' tabindex=\"" . $i . "4\" /><br /><br /></td>\n";	
		echo "	</tr>\n";
		$i++;
	}
	
	echo '	<tr>';
	echo "		<td><input type='submit' value='submit' /></td>";
	echo '	</tr>';
	echo "</form>";
	echo '</table>';
?>
</div>
