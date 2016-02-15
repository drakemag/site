<?php

/**

 * @copyright	Copyright (C) 2005 - 2010 Open Source Matters. All rights reserved.

 * @license		GNU/GPL, see LICENSE.php

 * Joomla! is free software. This version may have been modified pursuant

 * to the GNU General Public License, and as distributed it includes or

 * is derivative of works licensed under the GNU General Public License or

 * other free or open source software licenses.

 * See COPYRIGHT.php for copyright notices and details.

 */



// no direct access

defined( '_JEXEC' ) or die( 'Restricted access' );

$broken_newsletter_link = '/back-issues/2015/winter/waiting-on-el-nino.html?utm_source=iContact&utm_medium=email&utm_campaign=Drakemag.com&utm_content=2015+winter+issue';
if ($_SERVER['REQUEST_URI'] == $broken_newsletter_link) {
	header('Location: http://www.drakemag.com/back-issues/2015/winter/1540-waiting-on-el-nino.html');
	exit;
}
/*
if (($this->error->getCode()) == '404' 


if (($this->error->getCode()) == '403') {
	header('Location: index.php?option=com_content&view=article&id=101&Itemid=8');
	exit;
}
if (($this->error->getCode()) == '500') {
	header('Location: index.php?option=com_content&view=article&id=102&Itemid=9');
	exit;
}
if (($this->error->getCode()) == '401') {
	header('Location: index.php?option=com_content&view=article&id=100&Itemid=10');
	exit;
}
*/


?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">

<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="<?php echo $this->language; ?>" lang="<?php echo $this->language; ?>" dir="<?php echo $this->direction; ?>">

<head>

	<title><?php echo $this->error->getCode() ?> - <?php echo $this->title; ?></title>

	<link rel="stylesheet" href="<?php echo $this->baseurl; ?>/templates/system/css/error.css" type="text/css" />

	<?php if($this->direction == 'rtl') : ?>

	<link rel="stylesheet" href="<?php echo $this->baseurl ?>/templates/system/css/error_rtl.css" type="text/css" />

	<?php endif; ?>

</head>

<body>

	<div align="center">

		<div id="outline">

		<div id="errorboxoutline">

			<div id="errorboxheader"><?php echo $this->error->getCode() ?> - <?php echo $this->error->message ?></div>

			<div id="errorboxbody">

			<?php if ($this->error->getCode() == '404') { ?>

        <div id="errorboxheader">Page not found</div>

						<div id="errorboxbody">

							<h1>The 404 conditional worked</h1>

							<p>Sorry! That page cannot be found.</p>

						</div>

				</div>

		<?php } ?>



			<p><strong><h1>test</h1><?php echo JText::_('You may not be able to visit this page because of:'); ?></strong></p>

				<ol>

					<li><?php echo JText::_('An out-of-date bookmark/favourite'); ?></li>

					<li><?php echo JText::_('A search engine that has an out-of-date listing for this site'); ?></li>

					<li><?php echo JText::_('A mis-typed address'); ?></li>

					<li><?php echo JText::_('You have no access to this page'); ?></li>

					<li><?php echo JText::_('The requested resource was not found'); ?></li>

					<li><?php echo JText::_('An error has occurred while processing your request.'); ?></li>

				</ol>

			<p><strong><?php echo JText::_('Please try one of the following pages:'); ?></strong></p>

			<p>

				<ul>

					<li><a href="<?php echo $this->baseurl; ?>/index.php" title="<?php echo JText::_('Go to the home page'); ?>"><?php echo JText::_('Home Page'); ?></a></li>

				</ul>

			</p>

			<p><?php echo JText::_('If difficulties persist, please contact the system administrator of this site.'); ?></p>

			<div id="techinfo">

			<p><?php echo $this->error->message; ?></p>

			<p>

				<?php if($this->debug) :

					echo $this->renderBacktrace();

				endif; ?>

			</p>

			</div>

			</div>

		</div>

		</div>

	</div>

</body>

</html>

