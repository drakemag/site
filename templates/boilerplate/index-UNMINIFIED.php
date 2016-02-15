<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="<?php echo $this->language; ?>" lang="<?php echo $this->language; ?>" dir="<?php echo $this->direction; ?>" >
<head>
	<meta name="robots" content="noindex,nofollow" />
	<META content="noarchive" NAME="robots" />
	<META content="no-archive" NAME="robots" />
	<META content="no archive" NAME="robots" />
	
	<?php
		// Remove mootool sripts for guest and registered
		$user =& JFactory::getUser();
		if ($user->get('guest') == 1 or $user->usertype == 'Registered') {
			$headerstuff = $this->getHeadData();
			$headerstuff['scripts'] = array();
			$this->setHeadData($headerstuff);
		}
	?>

	
	
	<jdoc:include type="head" />
	<link rel="stylesheet" href="/templates/boilerplate/css/style.css" type="text/css" />
	<!--link rel="stylesheet" href="/templates/boilerplate/css/print.css" type="text/css" />
	<link rel="stylesheet" href="/templates/boilerplate/css/template_css.css" type="text/css" /-->
	<!--[if IE 6]><link href="/templates/boilerplate/css/ie6_fix.css" rel="stylesheet" type="text/css" /><![endif]-->
	<!--[if IE 7]><link href="/templates/boilerplate/css/ie7_fix.css" rel="stylesheet" type="text/css" /><![endif]-->
	<!--[if IE 8]><link href="/templates/boilerplate/css/ie8_fix.css" rel="stylesheet" type="text/css" /><![endif]-->
	<!--[if lt IE 7]><script language="JavaScript" src="/templates/boilerplate/js/png_fixer.js" type="text/javascript"></script><![endif]-->
	
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

<body class="<?php echo $pageclass; ?>"> 
	<jdoc:include type="message" />
<div id="wrapper">
	<div id="header">
		<?php echo $test; ?>
		<div id="logo"><a href="/">LOGO</a></div>
		<div id="main_menu"><jdoc:include type="modules" name="main_menu" /></div>
	</div>
	<div id="focus">
		<div id="left"><jdoc:include type="modules" name="left" /></div>
		<div id="content"><jdoc:include type="component" /></div>
		<div id="right"><jdoc:include type="modules" name="right" /></div>
	</div>
	<div id="footer"><jdoc:include type="modules" name="footer" /></div>
	<div id="credits"><jdoc:include type="modules" name="credits" /></div>
</div>


<!--script type="text/javascript">
	var gaJsHost = (("https:" == document.location.protocol) ? "https://ssl." : "http://www.");
	document.write(unescape("%3Cscript src='" + gaJsHost + "google-analytics.com/ga.js' type='text/javascript'%3E%3C/script%3E"));
	</script>
	<script type="text/javascript">
	try {
	var pageTracker = _gat._getTracker("UA-1512028-40");
	pageTracker._trackPageview();
	} catch(err) {}
</script-->

</body>
</html>