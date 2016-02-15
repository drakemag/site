<?php
// namespace administrator\components\com_responsivizer;
/**
 * Entrypoint dell'application di backend
 *
 * @package RESPONSIVIZER::administrator::components::com_responsivizer
 * @author Joomla! Extensions Store
 * @copyright (C) 2014 - Joomla! Extensions Store
 * @license GNU/GPLv2 http://www.gnu.org/licenses/gpl-2.0.html  
 */
defined ( '_JEXEC' ) or die ( 'Restricted access' );
define('JPATH_RVZR_DBPOSITIONS', JPATH_ROOT . '/templates/responsivizer/framework/modules/dbpositions.json');

if(!JComponentHelper::getParams('com_responsivizer')->get('enable_debug', 0)) {
	ini_set('display_errors', 0);
	ini_set('error_reporting', E_ERROR);
}

// Auto loader setup
JLoader::registerPrefix('Responsivizer',  JPATH_COMPONENT . '/framework');

// Main application object
$app = JFactory::getApplication();
/*
 * controller.task core MVC execute
 */
$controller_command = $app->input->get( 'task', 'cpanel.display' );
list ( $controller_name, $controller_task ) = explode ( '.', $controller_command );

// Defaults
if (! $controller_name) {
	$controller_name = 'cpanel';
}
if (! $controller_task) {
	$controller_task = 'display';
}

$path = JPATH_COMPONENT . '/controllers/' . strtolower($controller_name) . '.php';
if (file_exists ( $path )) {
	require_once $path;
} else {
	$app->enqueueMessage(JText::_('ERROR_NO_CONTROLLER_FILE'), 'error');
	return false;
}

// Create the controller
$classname = 'ResponsivizerController' . ucfirst ( $controller_name );
if (class_exists ( $classname )) {
	$controller = new $classname ();
	// Perform the Request task
	$controller->execute ( $controller_task );
	
	// Redirect if set by the controller
	$controller->redirect ();
} else {
	$app->enqueueMessage(JText::_('ERROR_NO_CONTROLLER'), 'error');
	return false;
} 