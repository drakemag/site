<?php
	require('php-captcha.inc.php');
	$aFonts = array('fonts/VeraBd.ttf', 'fonts/VeraIt.ttf', 'fonts/Vera.ttf');
	$oVisualCaptcha = new PhpCaptcha($aFonts, 260, 50);
	$oVisualCaptcha->SetOwnerText('');
	$oVisualCaptcha->SetBackgroundImages('images/captcha.jpg');
	$oVisualCaptcha->Create();
?>
