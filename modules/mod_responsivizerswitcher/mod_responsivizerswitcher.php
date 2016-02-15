<?php
/**
 * @author Joomla! Extensions Store
 * @package RESPONSIVIZER::MODULESWITCHER::modules::mod_responsivizerswitcher
 * @copyright (C) 2014 - Joomla! Extensions Store
 * @license GNU/GPLv2 http://www.gnu.org/licenses/gpl-2.0.html
 */

// no direct access
defined( '_JEXEC' ) or die( 'Restricted access' );
if (!defined('DS')) {
	define('DS', DIRECTORY_SEPARATOR);
}

$componentParams = JComponentHelper::getParams('com_responsivizer');
$mobileTemplate = $componentParams->get('template', 'responsivizer');

// Set URI for switcher
$currentURI = JURI::getInstance ();
$clonedURI = clone ($currentURI); // Avoid collision on global URI object
$clonedURI->setVar ( 'responsivizer_template', $mobileTemplate );
$queryStringToMobile = JRoute::_ ( $clonedURI );

require(JModuleHelper::getLayoutPath('mod_responsivizerswitcher'));
