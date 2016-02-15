<?php
if( !defined( '_VALID_MOS' ) && !defined( '_JEXEC' ) ) die( 'Direct Access to '.basename(__FILE__).' is not allowed.' );

/**
 * @subpackage	responsiveMenu
 * @copyright	Copyright (C) Cecil Gupta. All rights reserved.
 * @license		GNU General Public License version 2 or later; see LICENSE.txt
 */
$document =JFactory::getDocument();
$theme = $params->get('selectTheme');
$rtlLayout = (bool)$params->get('rtlLayout', false);
$maxMobileWidth = $params->get('maxMobileWidth', 800);
$menuBG = str_replace("#", "%23", $params->get('menuBG', "%233474a6"));
$textColor = str_replace("#", "%23", $params->get('textColor', "%23ffffff"));
$textColor2 = str_replace("#", "%23", $params->get('textColor2', "%23247eab"));

JLoader::import( 'joomla.version' );
$version = new JVersion();



$addjQuery=(int)$params->get( 'jQuery', 1 );
if($addjQuery){
	if (version_compare( $version->RELEASE, '2.5', '<=')) {
		$document->addScript( 'http://code.jquery.com/jquery-1.10.2.min.js' );
		$document->addScript( 'modules/mod_responsivemenu/js/jquery.noconflict.js' );
	}else{
		JHtml::_('jquery.framework');
	}
}



if(version_compare(JVERSION,'1.6.0','ge')) {
	// Include the syndicate functions only once
	require_once (dirname(__FILE__).DIRECTORY_SEPARATOR.'helper.php');
	
	$list	= modResponsiveMenuHelper::getList($params);
	$app	= JFactory::getApplication();
	$menu	= $app->getMenu();
	$active	= $menu->getActive();
	$active_id = isset($active) ? $active->id : $menu->getDefault()->id;
	$path	= isset($active) ? $active->tree : array();
	$showAll	= $params->get('showAllChildren');
	$class_sfx	= htmlspecialchars($params->get('class_sfx'));

	if(count($list)) {
		require(JModuleHelper::getLayoutPath('mod_responsivemenu','theme'.$theme));
	}
}