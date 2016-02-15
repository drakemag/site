<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="<?php echo $this->language; ?>" lang="<?php echo $this->language; ?>" dir="<?php echo $this->direction; ?>" >
<head>
	<?php 
		// Remove mootool sripts for guest and registered
		unset( $this->_scripts['/media/system/js/mootools.js'] );
	?>
	<jdoc:include type="head" />
	
	<link href="http://feeds.feedburner.com/SITEFEED" type="application/rss+xml" rel="alternate" title="WEBISTE BLOG TITLE" />
	<link rel="image_src" href="http://www.WEBSITEADDRESS.com/images/media/logo_sm.jpg" /> 
	<link href="/templates/boilerplate/favicon.ico" rel="shortcut icon" type="image/x-icon" /> 

	<link rel="stylesheet" href="/templates/boilerplate/css/style.css" type="text/css" />
	<link rel="stylesheet" href="/modules/mod_superfishmenu/tmpl/css/superfish.css" type="text/css" />
	<!--link rel="stylesheet" href="/templates/boilerplate/css/print.css" type="text/css" />
	<link rel="stylesheet" href="/templates/boilerplate/css/template_css.css" type="text/css" /-->
	<!--[if IE 6]><link href="/templates/boilerplate/css/ie6_fix.css" rel="stylesheet" type="text/css" /><![endif]-->
	<!--[if IE 7]><link href="/templates/boilerplate/css/ie7_fix.css" rel="stylesheet" type="text/css" /><![endif]-->
	<!--[if IE 8]><link href="/templates/boilerplate/css/ie8_fix.css" rel="stylesheet" type="text/css" /><![endif]-->
	<!--[if lt IE 7]>
		<?php
			/* CORRECT WAY TO CALL JS IN J HEAD */
			$document = &JFactory::getDocument();
			$document->addScript( '/templates/boilerplate/js/png_fixer.js' );
		?>
	<![endif]-->
</head>
<?php
	header("Expires: Sat, 26 Jul 1997 05:00:00 GMT"); // Date in the past
?> 
<?php
/* PAGE CLASS ************************************/
		$menus     = &JSite::getMenu();
		$menu       = $menus->getActive();
		$pageclass = "";
		if (is_object( $menu )) :
		$params = new JParameter( $menu->params );
		$pageclass = $params->get( 'pageclass_sfx' );
		endif;
?>
<!--
GOOGLE ANALYTICS SCRIPT GOES HERE
-->
<body class="<?php echo $pageclass; ?>">
<jdoc:include type="message" />
<div id="wrapper">
	<div id="header">
		<div id="logo"><a href="/">LOGO</a></div>
		<div id="main_menu">
			<jdoc:include type="modules" name="main_menu" style="xhtml" />
		</div>
	</div>
	<div id="focus">
		<div id="left">
			<jdoc:include type="modules" name="left" style="xhtml" />
		</div>
		<div id="content">
			<jdoc:include type="component" />
		</div>
		<div id="right">
			<?php include("templates/boilerplate/scripts/latest_blog.php") ?> 
			<?php include("templates/boilerplate/scripts/latest_list.php") ?> 
			<jdoc:include type="modules" name="right" style="xhtml" />
		</div>
	</div>
	<div id="footer">
		<jdoc:include type="modules" name="footer" style="xhtml" />
	</div>
	<div id="credits">
		<jdoc:include type="modules" name="credits" style="xhtml" />
	</div>
</div>
<jdoc:include type="modules" name="debug" />
</body>
</html>