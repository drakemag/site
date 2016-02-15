<?php require_once("includes/connection.php"); ?>
<?php require_once("includes/functions.php"); ?>


<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta name="robots" content="noindex,nofollow">
<META content="noarchive" NAME="robots">
<META content="no-archive" NAME="robots">
<META content="no archive" NAME="robots">

<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Errors Page</title>
<link rel="stylesheet" href="style.css" type="text/css" /> 
<link rel="icon" href="/templates/sfs/favicon.ico" type="image/x-icon" rel="Shortcut Icon"> 
</head>

<body class="edit">
<div id="wrapper">
	<?php include("includes/header.php"); ?>
	<div id="content">
		<table>
			<tr>
				<td colspan="2"><h3>Error Messsages</h3></td>
			</tr>
			<tr>
				<td colspan="2">
					<?php 
						if (!empty($errors)) { 
							display_errors($field_errors); 
						}			
					?>
				</td>
			</tr>
			<tr>
				<td colspan="2">
					<a href="/">back to homepage</a>
				</td>
			</tr>
		</table>
	</div>
</div>		
</body>
</html>

<?php
	mysql_close ($connection);
?>