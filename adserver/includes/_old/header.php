	<div id="header">
		<div id="nav">
			<a href="/">home</a> |
			<a href="/tools.php">tools</a> |
			<!--a href="/joomla.php">joomla &amp; carts</a> |-->
			<a href="/retired.php">retired</a> 
			<!--a href="/alexa.php">alexa</a> |-->
			<!--a href="/testing.php">testing</a> | -->
		</div>
		<div id="nav_icons">
			<?php
				//3. PERFORM DB QUERY
				$ql = mysql_query("SELECT * FROM quick_links ORDER BY name ASC", $connection);
				if (!$ql) {
					die("Database query failed: " . mysql_error());
				}
				
				// 4. USE RETURNED DATA
				while ($row = mysql_fetch_array($ql)) {
					//echo $row["name"]." ".$row["state"]."<br />";
						echo "<a href=\"" . $row["url"] . "\"><img src=\"" . $row["img_url"] . "\" title=\"" . $row["name"] . "\" alt=\"" . $row["name"] . "\" /></a>";
				}
			?>
			<a href="add_edit/edit_quicklinks.php">e</a><a href="add_edit/add_new_quicklink.php">a</a>
		</div>
	</div>
	<div id="quick_links">
		<table>
			<tr>
				<td class="col1" style="padding-top:4px;">
					<table>
						<tr>
							<td>Rackspace</td>
							<td><a href="https://173.203.146.2:8443/"><img src="/images/icon_plesk.png" /> Plesk</a></td>
							<td><a href="http://my.rackspace.com/"><img src="/images/icon_rs_portal.png" /> Portal</a></td>
							<td><a href="http://173.203.146.2/phpMyAdmin/"><img src="/images/phpmyadmin.gif" alt="mysql" /></a></td>
						</tr>
						<tr>
							<td>Drake - TX <a href="https://66.135.39.100:23794">(fw)</a></td>
							<td><a href="https://66.135.39.100:8443/"><img src="/images/icon_plesk.png" /> Plesk</a></td>
							<td><a href="https://mypeer1.com/login.php"><img src="/images/icon_server_beach.png" /> Portal</a></td>
							<td><a href="http://66.135.39.100:3232/"><img src="/images/phpmyadmin.gif" alt="mysql" /></a></td>
						</tr>
						<tr>
							<td>Drake - 2 <a href="https://216.151.210.250:1443/index.html">(fw)</a></td>
							<td><a href="https://207.198.99.90:2087"><img src="/images/icon_plesk.png" /> Plesk</a></td>
							<td><a href="https://mypeer1.com/login.php"><img src="/images/icon_server_beach.png" /> Portal</a></td>
							<td><a href="https://207.198.99.90:3232/"><img src="/images/phpmyadmin.gif" alt="mysql" /></a></td>
						</tr>
						<tr>
							<td>CHP</td>
							<td><a href="https://69.20.93.48:13454"><img src="/images/icon_plesk.png" /> Webmin</a></td>
							<td><a href="http://my.rackspace.com/"><img src="/images/icon_rs_portal.png" /> Portal</a></td>
							<td><a href="http://69.20.93.48/phpMyAdmin/"><img src="/images/phpmyadmin.gif" alt="mysql" /></a></td>
						</tr>
						
					</table>
				</td>
				<td>
				</td>
			</tr>
		</table>
	</div>
