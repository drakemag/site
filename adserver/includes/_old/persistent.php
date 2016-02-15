<table>
	<tr>
		<td class="col1">
			<?php
				//3. PERFORM DB QUERY
				$result = mysql_query("SELECT * FROM sites WHERE upper(name) BETWEEN '0' AND 'jaa' AND category = '0' ORDER BY name ASC", $connection);
				if (!$result) {
					die("Database query failed: " . mysql_error());
				}
				echo "<table class=\"array_return\" cellpadding=\"0\" cellspacing=\"0\">\r";
				
				// 4. USE RETURNED DATA
				while ($row = mysql_fetch_array($result)) {
					//echo $row["name"]." ".$row["state"]."<br />";
					echo "<tr>\r";
						echo "	<td class=\"name\"><a href=\"http://www." . $row["name"] . "\">" . $row["name"] . "</a></td>\r";
						echo "	<td class=\"name_w\"><a href=\"http://www." . $row["name"] . "\">" . "<img src=\"/images/button_site_w.png\" />" . "</a></td>\r";
						echo "	<td class=\"admin\">";
						if ($row["admin"] == 1) 
							echo "<a href=\"http://www." . $row["name"] . "/administrator/\">" . "<img src=\"/images/button_site_a.png\" />" . "</a>";
						else
							echo "<img src=\"/images/blank.png\" />";
						echo "</td>\r";
						echo "	<td class=\"test\">";
						if ($row["test"] == 1) 
							echo "<a href=\"http://test." . $row["name"] . "\">" . "<img src=\"/images/button_site_t.png\" />" . "</a>";
						else 
							echo "<img src=\"/images/blank.png\" />";
						echo "</td>\r";
						echo "	<td class=\"test_admin\">";
						if ($row["test_admin"] == 1) 
							echo "<a href=\"http://test." . $row["name"] . "/administrator/\">" . "<img src=\"/images/button_site_ta.png\" />" . "</a>";
						else 
							echo "<img src=\"/images/blank.png\" />";
						echo "</td>\r";
					echo "</tr>\n\n";
				}
					echo "</table>\r";
			?>
		</td>
		<td>
			<?php
				//3. PERFORM DB QUERY
				$result = mysql_query("SELECT * FROM sites WHERE upper(name) BETWEEN 'jab' AND 'z' AND category = '0' ORDER BY name ASC", $connection);
				if (!$result) {
					die("Database query failed: " . mysql_error());
				}
				echo "<table cellpadding=\"0\" cellspacing=\"0\">";
				
				// 4. USE RETURNED DATA
				while ($row = mysql_fetch_array($result)) {
					//echo $row["name"]." ".$row["state"]."<br />";
					echo "<tr>";
						echo "<td class=\"name\"><a href=\"http://www." . $row["name"] . "\">" . $row["name"] . "</a></td>";
						echo "<td class=\"name_w\"><a href=\"http://www." . $row["name"] . "\">" . "<img src=\"/images/button_site_w.png\" />" . "</a></td>";
						echo "<td class=\"admin\">";
						if ($row["admin"] == 1) 
							echo "<a href=\"http://www." . $row["name"] . "/administrator/\">" . "<img src=\"/images/button_site_a.png\" />" . "</a>";
						else
							echo "<img src=\"/images/blank.png\" />";
						echo "</td>";
						echo "<td class=\"test\">";
						if ($row["test"] == 1) 
							echo "<a href=\"http://test." . $row["name"] . "\">" . "<img src=\"/images/button_site_t.png\" />" . "</a>";
						else 
							echo "<img src=\"/images/blank.png\" />";
						echo "</td>";
						echo "<td class=\"test_admin\">";
						if ($row["test_admin"] == 1) 
							echo "<a href=\"http://test." . $row["name"] . "/administrator/\">" . "<img src=\"/images/button_site_ta.png\" />" . "</a>";
						else 
							echo "<img src=\"/images/blank.png\" />";
						echo "</td>";
					echo "</tr>";
				}
					echo "</table>";
			?>
		</td>
	</tr>
</table>
