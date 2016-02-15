<?php
/**
 * Override Chrome for Joomla! modules style
 * 
 * @author Joomla! Extensions Store
 * @package RESPONSIVIZER::TEMPLATEDISPLAY::templates::responsivizer
 * @copyright (C) 2013 - Joomla! Extensions Store
 * @license GNU/GPLv2 http://www.gnu.org/licenses/gpl-2.0.html
 */

defined("_JEXEC") or die("Restricted access");
function modChrome_responsivizer($module, &$params, &$attribs) {
   	// init vars
	$showtitle = $module->showtitle;
	if(isset($module->show_title) && (int)$module->show_title === 0) {
		$showtitle = false;
	}
	$noBGClass = '';
	if(isset($module->show_bg) && (int)$module->show_bg === 0) {
		$noBGClass = 'nobg ';
	}
	
	$content   = $module->content;
	$suffix    = '';
	$badge	   = '';
	$cParams = JComponentHelper::getParams('com_responsivizer');
	// create title
	$title = JString::str_ireplace('responsivizer - ', '', $module->title);
	
	$androidTheme = JComponentHelper::getParams('com_responsivizer')->get('predefined_theme_2', 0);
	$iosTheme = JComponentHelper::getParams('com_responsivizer')->get('predefined_theme_3', 0);
	
	// force module type
	if ($module->position == 'responsivizer_logo')  $suffix = 'logo';
	if ($module->position == 'responsivizer_top')  $suffix = 'normal';
	if ($module->position == 'responsivizer_bottom')  $suffix = 'normal';
	if ($module->position == 'responsivizer_left')  $suffix = 'normal';
	if ($module->position == 'responsivizer_right')  $suffix = 'normal';
	if ($module->position == 'responsivizer_user1')  $suffix = 'normal';
	if ($module->position == 'responsivizer_user2')  $suffix = 'normal';
	if ($module->position == 'responsivizer_user3')  $suffix = 'normal';
	if ($module->position == 'responsivizer_user4')  $suffix = 'normal';
	if ($module->position == 'responsivizer_user5')  $suffix = 'normal';
	if ($module->position == 'responsivizer_user6')  $suffix = 'normal';
	if ($module->module == 'mod_menu')  $suffix = 'menu';
	if ($module->position == 'responsivizer_menu_top')  $suffix = 'menutop';
	if ($module->position == 'responsivizer_menu_side')  $suffix = 'menuside';
	
	// No bg predefined module positions
	$noBGPositions = array( 'logo',
							'search',
							'responsivizer_logo',
							'responsivizer_search');
	if(in_array($module->position, $noBGPositions) || !$cParams->get('module_background', 1)) {
		$noBGClass = 'nobg ';
	}
		
	// set module skeleton using the suffix
	switch ($suffix) {
		case 'logo':
			$skeleton = 'logo';
			break;
		case 'normal':
			$skeleton = 'normal';
			break;
		case 'menu':
			$skeleton = 'menu';
			break;
		case 'menutop':
			$skeleton = 'menutop';
			break;
		case 'menuside':
			$skeleton = 'menuside';
			break;
		case 'user-footer':
			$skeleton = 'user-footer';
			break;
		default:
			$skeleton = 'not defined';
	}
	// Modules
	switch ($skeleton) {
		case 'logo':
			 echo $content; 
			break;
		case 'menu':
			?>
			<div class="moduletable module <?php echo $noBGClass . $params->get('moduleclass_sfx'); ?>" >
			<h3 class="module menumodule"><?php if ($showtitle && !($androidTheme || $iosTheme)) { echo $title; }?></h3>
				<?php echo $content; ?>
			</div>
			<?php 
			break;
		case 'menutop':
			?>
			<div class="moduletable module menutop <?php echo $noBGClass . $params->get('moduleclass_sfx'); ?>" >
			<h3 class="module menumodule menutop"></h3>
				<?php echo $content; ?>
			</div>
			<?php
			break;
		case 'normal':
			?>
			<div class="moduletable module <?php echo $noBGClass . $params->get('moduleclass_sfx'); ?>" >
				<?php if ($showtitle) : ?>
					<h3 class="module"><?php echo $title; ?></h3>
				<?php endif; ?>
		
				<div class="content-module">
					<?php echo $content; ?>
				</div>
			</div>
			<?php 
			break;			
		case 'user-footer':
			?>
			<div class="moduletable module <?php echo $noBGClass . $params->get('moduleclass_sfx'); ?>" >
				<div>
					<?php if ($showtitle) : ?>
					<div class="bg-user-footer-h3"></div>
					<h3 class="module"><?php echo $title; ?></h3>
					<?php endif; ?>
			
					<div class="content-module">
						<?php echo $content; ?>
					</div>
				</div>
			</div>
			<?php 
			break;
		case 'menuside':
			?>
			<div class="moduletable module <?php echo $suffix . $params->get('moduleclass_sfx'); ?>">
				<?php echo $content; ?>
			</div>
			<?php
			break;
		default:
			?>
			<div class="moduletable module <?php echo $suffix . ' ' . $noBGClass . $params->get('moduleclass_sfx'); ?>">
				<?php if ($showtitle) : ?>
					<h3 class="module"><?php echo $title; ?></h3>
				<?php endif; ?>
				<?php echo $content; ?>
			</div>
			<?php 
			break;
	}
}