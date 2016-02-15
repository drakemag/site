<table>
	<tr>
		<td class="col1" style="padding-top:4px;">
			<table>
				<tr>
					<td>Rackspace</td>
					<td><a href="https://173.203.146.2:8443/">Plesk</a></td>
					<td><a href="http://my.rackspace.com/">Portal</a></td>
					<td><a href="http://173.203.146.2/phpMyAdmin/"><img src="/images/phpmyadmin.gif" alt="mysql" /></a></td>
				</tr>
				<tr>
					<td>Server Beach</td>
					<td><a href="https://76.74.236.100:8443/">Plesk</a></td>
					<td><a href="http://my.serverbeach.com/">Portal</a></td>
				</tr>
			</table>
		</td>
		<td>
			<?php
				//3. PERFORM DB QUERY
				$result = mysql_query("SELECT * FROM sites WHERE upper(name) BETWEEN '0' AND 'z' AND category = '0' ORDER BY name ASC", $connection);
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
