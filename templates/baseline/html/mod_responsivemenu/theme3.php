<?php
/**
 * @package		Joomla.Site
 * @subpackage	Responsive Menu
 * @copyright	Copyright (C) Cecil Gupta. All rights reserved.
 * @license		GNU General Public License version 2 or later;
 */

// No direct access.
defined('_JEXEC') or die;
if(version_compare(JVERSION,'1.6.0','ge')) {
	// JOOMLA 1.6+ CODE
	$document =JFactory::getDocument();
	$document->addStyleSheet("media/mod_responsivemenu/css/theme3.css.php?maxMobileWidth=".$maxMobileWidth."&amp;menuBG=".$menuBG."&amp;textColor=".$textColor."&amp;textColor2=".$textColor2);
	if($rtlLayout){
		$document->addStyleSheet("media/mod_responsivemenu/css/theme3-rtl.css.php?maxMobileWidth=".$maxMobileWidth."&amp;menuBG=".$menuBG."&amp;textColor=".$textColor);
	}
	$document->addScript("modules/mod_responsivemenu/js/theme3.js",'text/javascript', true);
	?>
	<ul id="responsiveMenu<?php echo $module->id; ?>" class="responsiveMenuTheme3  <?php echo $class_sfx;?> <?php echo ($rtlLayout) ? 'rtlLayout' : ''; ?>" data-breakpoint="10000" ><?php
	foreach ($list as $i => &$item) :
		$class = '';
		if ($item->id == $active_id) {
			$class .= 'current ';
		}
		
		if($i==0){
			$class .= 'first ';
		}
		

		if (	$item->type == 'alias' &&
				in_array($item->params->get('aliasoptions'),$path)
			||	in_array($item->id, $path)) {
		  $class .= 'active ';
		}
		if ($item->deeper) {
			$class .= 'deeper ';
		}
		
		if ($item->parent) {
			$class .= 'parent ';
		}

		if (!empty($class)) {
			$class = ' class="'.trim($class) .'"';
		}

		echo '<li id="item-'.$item->id.'"'.$class.'>';

		// Render the menu item.
		switch ($item->type) :
			case 'separator':
			case 'url':
			case 'heading':
			case 'component':
				require JModuleHelper::getLayoutPath('mod_responsivemenu', 'default_'.$item->type);
				break;

			default:
				require JModuleHelper::getLayoutPath('mod_responsivemenu', 'default_url');
				break;
		endswitch;

		// The next item is deeper.
		if ($item->deeper) {
			echo '<ul>';
		}
		// The next item is shallower.
		else if ($item->shallower) {
			echo '</li>';
			echo str_repeat('</ul></li>', $item->level_diff);
		}
		// The next item is on the same level.
		else {
			echo '</li>';
		}
	endforeach;
	?></ul>
	<style type="text/css">
	@media all and (min-width: 10000px) {
	    body.one-page{padding-top:70px;}
	    .responsiveMenuTheme1{overflow:visible;}
	    .responsiveMenuTheme1.opacity{opacity:1;}
	    .responsiveMenuTheme1.one-page{top:0;right:auto;max-width:1080px;}
	    .responsiveMenuTheme1 li{position:relative;list-style:none;float:left;display:block;background-color:#3474A6;width:20%;overflow:visible;}
	    .responsiveMenuTheme1 li a{border-left:1px solid #407EAE;border-bottom:none;}
	    .responsiveMenuTheme1 li > ul{position:absolute;top:auto;left:0;}
	    .responsiveMenuTheme1 li > ul li{width:100%;}
	    .responsiveMenuTheme1 li ul li > ul{margin-left:100%;top:0;}
	    .responsiveMenuTheme1 li ul li a{border-bottom:none;}
	    .responsiveMenuTheme1 li ul.open{display:block;opacity:1;visibility:visible;z-index:1;}
	    .responsiveMenuTheme1 li ul.open li{overflow:visible;max-height:100px;}
	    .responsiveMenuTheme1 li ul.open ul.open{margin-left:100%;top:0;}
	    .toggleMenu { display: none; }
	}
	

	</style>
	<?php 
}