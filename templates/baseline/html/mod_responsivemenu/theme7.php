<?php
/**
 * @package		Joomla.Site
 * @subpackage	Responsive Menu - responsive-nav
 * @copyright	Copyright (C) Cecil Gupta. All rights reserved.
 * @license		GNU General Public License version 2 or later;
 */

// No direct access.
defined('_JEXEC') or die;
if(version_compare(JVERSION,'1.6.0','ge')) {
	// JOOMLA 1.6+ CODE
	$document =JFactory::getDocument();
	$document->addStyleSheet("media/mod_responsivemenu/css/sm-core-css.css");
	$document->addStyleSheet("media/mod_responsivemenu/css/theme7.css.php?maxMobileWidth=".$maxMobileWidth."&amp;menuBG=".$menuBG."&amp;textColor=".$textColor."&amp;textColor2=".$textColor2);
	if($rtlLayout){
		$document->addStyleSheet("media/mod_responsivemenu/css/theme7-rtl.css.php?maxMobileWidth=".$maxMobileWidth."&amp;menuBG=".$menuBG."&amp;textColor=".$textColor."&amp;textColor2=".$textColor2);
	}
	$document->addScript("modules/mod_responsivemenu/js/theme7.js",'text/javascript', true);
	?>
		<ul id="responsiveMenu<?php echo $module->id; ?>" class="sm sm-blue responsiveMenuTheme7 <?php echo $class_sfx;?> sm-vertical sm-blue-vertical <?php echo ($rtlLayout) ? 'sm-rtl' : ''; ?>" ><?php
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
	<?php 
}