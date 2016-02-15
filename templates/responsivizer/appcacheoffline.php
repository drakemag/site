<?php 
/**
 * @author Joomla! Extensions Store
 * @package RESPONSIVIZER::TEMPLATEDISPLAY::templates::responsivizer
 * @copyright (C) 2013 - Joomla! Extensions Store
 * @license GNU/GPLv2 http://www.gnu.org/licenses/gpl-2.0.html
 */
defined('_JEXEC') or die('Restricted access');
include 'framework/bootstrap.php';
$document->addStyleSheet( JURI::root ( true ) . "/templates/$this->template/css/offline.css");
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
					<?php echo JText::_('TPL_RESPONSIVIZER_OFFLINE_PLEASE_CONNECT');?>
				</div>
			</div>
		</div> 
		<div class="clr"></div>
	</div> 
	<div class="background-site"></div> 
</body>
</html>