<?php 
/**
 * @author Joomla! Extensions Store
 * @package RESPONSIVIZER::TEMPLATEDISPLAY::templates::responsivizer
 * @copyright (C) 2013 - Joomla! Extensions Store
 * @license GNU/GPLv2 http://www.gnu.org/licenses/gpl-2.0.html
 */
defined('_JEXEC') or die('Restricted access');
include 'framework/bootstrap.php';
?>

<!doctype html>
<html lang="<?php echo $this->language; ?>" dir="<?php echo $this->direction; ?>" >
<head>
<meta name="viewport" content="width=device-width, initial-scale=1" />
<jdoc:include type="head" />
</head>
<body <?php echo ('style="font-size:'.$fontSize.';"');?> class="<?php echo $general_background ; ?>">
  <div class="site"> 
    <div class="wrapper-site">
		<div class="clr"></div>
		<div id="main_component" >
			<div class="main-content"> 
				<!--  Output main component and message -->
				<?php if($this->params->get('show_messages', null)): ?>
					<jdoc:include type="message" />
				<?php endif; ?>
				<jdoc:include type="component" />
            </div>
			<div class="clr"></div>
		</div>
	</div>
</body>
</html>