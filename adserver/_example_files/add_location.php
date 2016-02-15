<?php require_once("./includes/connection.php"); ?>
<?php require_once("./includes/functions.php"); ?>

<link rel="stylesheet" href="/templates/proclinicfl/css/style.css" type="text/css" />
<link rel="stylesheet" href="/templates/proclinicfl/css/lab_menu_locations.css" type="text/css" />

<div id="focus">
	<h1>Add New Location</h1>

<?php
			require_once("./navigation.php");

$result = mysql_query("SELECT DISTINCT(company) FROM locations ORDER BY id", $connection);
if (!$result) {
	die("Database query failed: " . mysql_error());
};
 
	 
	echo "<table class=\"edit_table\">";
	echo "	<tr>";
	echo "		<th class=\"id\">id</th>";
	echo "		<th class=\"company\">name / company</th>";
	echo "		<th class=\"address\">address,address2,city, state zip</th>";
	echo "		<th class=\"phone\">phone / fax</th>";
	echo "		<th class=\"hours\">hours / hours2</th>";
	echo "	</tr>";
	 
	echo "<form name='form_update' method='post' action='add_location_function.php'>\n";
	echo "	<tr>\n";
	echo "		<td rowspan=\"3\">" . /*{$data['id']}<input type='hidden' name='id' value='{$data['id']}' />*/ "</td>";
	echo "		<td><input type='text' name='name' value=\"Name\" onfocus=\"if(this.value == 'Name'){this.value = '';}\" onblur=\"if(this.value == ''){this.value='Name';}\" tabindex=\"1\" /></td>\n";
	echo "		<td><input type='text' name='address1' value=\"Address 1\" onfocus=\"if(this.value == 'Address 1'){this.value = '';}\" onblur=\"if(this.value == ''){this.value='Address 1';}\" tabindex=\"3\" /></td>\n";
	echo "		<td><input type='text' name='phone' value=\"Phone\" onfocus=\"if(this.value == 'Phone'){this.value = '';}\" onblur=\"if(this.value == ''){this.value='Phone';}\" tabindex=\"8\" /></td>\n";
	echo "		<td><input type='text' name='hours' value=\"Hours\" onfocus=\"if(this.value == 'Hours'){this.value = '';}\" onblur=\"if(this.value == ''){this.value='Hours';}\" tabindex=\"10\" /></td>\n";
	echo "	</tr>\n";
	echo "	<tr>\n";
	
	echo "			<td rowspan=\"2\">
					<select name=\"company\" tabindex=\"2\" />\n
						<option value=\"\" selected></option>";
						while ($data = mysql_fetch_array($result)) {
							echo "	<option value='{$data['company']}'>{$data['company']}</option>\n";
						}
	echo "				</select></td>\n";

//	echo "		<td rowspan=\"2\"><input type='text' name='company' value=\"Company\" onfocus=\"if(this.value == 'Company'){this.value = '';}\" onblur=\"if(this.value == ''){this.value='Company';}\" tabindex=\"2\" /></td>\n";
	echo "		<td><input type='text' name='address2' value=\"Address 2\" onfocus=\"if(this.value == 'Address 2'){this.value = '';}\" onblur=\"if(this.value == ''){this.value='Address 2';}\" tabindex=\"4\" /></td>\n";
	echo "		<td rowspan=\"2\"><input type='text' name='fax' value=\"Fax\" onfocus=\"if(this.value == 'Fax'){this.value = '';}\" onblur=\"if(this.value == ''){this.value='Fax';}\" tabindex=\"9\" /></td>\n";
	echo "		<td rowspan=\"2\"><input type='text' name='hours2' value=\"Hours 2\" onfocus=\"if(this.value == 'Hours 2'){this.value = '';}\" onblur=\"if(this.value == ''){this.value='Hours 2';}\" tabindex=\"11\" /></td>\n";
	echo "	</tr>\n";
	echo "	<tr>\n";
	echo "		<td>
					<input type='text' class='inline csz' name='city' value=\"City\" onfocus=\"if(this.value == 'City'){this.value = '';}\" onblur=\"if(this.value == ''){this.value='City';}\" tabindex=\"5\" />
					<input type='text' class='inline csz' name='state' value=\"State\" onfocus=\"if(this.value == 'State'){this.value = '';}\" onblur=\"if(this.value == ''){this.value='State';}\" tabindex=\"6\" />
					<input type='text' class='inline csz' name='zip' value=\"Zip\" onfocus=\"if(this.value == 'Zip'){this.value = '';}\" onblur=\"if(this.value == ''){this.value='Zip';}\" tabindex=\"7\" />
				</td>\n";
	echo "	</tr>\n";
	echo '	<tr>';
	echo "		<td><input type='submit' value='submit' /></td>";
	echo '	</tr>';
	echo "</form>";
	echo '</table>';
?>
</div>
