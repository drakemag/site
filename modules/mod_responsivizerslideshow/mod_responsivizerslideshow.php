<?php
/**
 * @author Joomla! Extensions Store
 * @package RESPONSIVIZER::MODULESLIDESHOW::modules::mod_responsivizerslideshow
 * @copyright (C) 2014 - Joomla! Extensions Store
 * @license GNU/GPLv2 http://www.gnu.org/licenses/gpl-2.0.html
 */
defined('_JEXEC') or die;

$doc = JFactory::getDocument();
$params = JComponentHelper::getParams('com_responsivizer');
require_once(dirname(__FILE__) . '/helper.php');
$module_id = $module->id;
$moduleclass_sfx = htmlspecialchars($params->get('moduleclass_sfx'));
$listofimages = modResponsivizerHelper::getImages($params);
modResponsivizerHelper::load_jquery($params);
$doc->addStyleSheet(JURI::base(true) . '/modules/mod_responsivizerslideshow/assets/css/responsivizer.css', 'text/css');
$doc->addScript(JURI::base(true) . '/modules/mod_responsivizerslideshow/assets/js/jquery.responsivizer.js');

// Set parameters
$slide_theme = $params->get('slide_theme', 'false');
$enable_minheight = $params->get('enable_minheight');
$min_height = $params->get('min_height');
$bg_color = $params->get('bg_color', '#ffffff');
$theme_shadow = $params->get('theme_shadow', 'theme-shadow-normal');
$theme_border = $params->get('theme_border', '00');
$theme_border_radius = $params->get('theme_border_radius', '00');
$transition = $params->get('transition', 'fade');
$easing = $params->get('easing', 'easeInQuart');
$direction = $params->get('direction', 'horizontal');
$animSpeed = intval($params->get('animSpeed', '2000'));
$pauseTime = intval($params->get('pauseTime', '3500'));
$controlNav = $params->get('controlNav', 'true');
$positionNav = $params->get('positionNav', 'under');
$colorNav = $params->get('colorNav', 'dark');
$colorNavActive = $params->get('colorNavActive', 'black');
$directionNav = $params->get('directionNav', 'default');
$pauseOnHover = $params->get('pauseOnHover', 'true');
$initDelay = $params->get('initDelay', '0');
$randomize = $params->get('randomize', 'false');
$bg_caption = $params->get('bg_caption', 'black');
$transparency_caption = $params->get('transparency_caption', '2');
$position_caption = $params->get('position_caption', 'bottom');
$text_align = $params->get('text_align', 'centered');
$color_caption = $params->get('color_caption', '#dddddd');
$imagesAutoHeight = $params->get('images_auto_height', 'true');

require(JModuleHelper::getLayoutPath('mod_responsivizerslideshow'));
