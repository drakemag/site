<?php 
/**
 * @author Joomla! Extensions Store
 * @package RESPONSIVIZER::MODULESWITCHER::modules::mod_responsivizerswitcher
 * @copyright (C) 2014 - Joomla! Extensions Store
 * @license GNU/GPLv2 http://www.gnu.org/licenses/gpl-2.0.html
 */

// no direct access
defined('_JEXEC') or die('Restricted access'); ?>
<style type="text/css">
	#responsivizer_mobile_switcher {
		background: url('<?php echo $currentURI->base();?>modules/mod_responsivizerswitcher/assets/mobile-icon.png') no-repeat;
		padding: 10px 0 10px 50px;
		font-size: 14px;
	}
		
	#responsivizer_mobile_switcher a {
		color: #333;
	}
</style>

<div id="responsivizer_mobile_switcher">
	<a href="<?php echo $queryStringToMobile;?>">
		<?php echo JText::_('MOD_RESPONSIVIZERSWITCHER_SWITCH_TO_MOBILE');?>
	</a>
</div>