<?php
// namespace components\com_responsivizer;
/**
 * Entrypoint frontend
 *
 * @package RESPONSIVIZER::components::com_responsivizer
 * @author Joomla! Extensions Store
 * @copyright (C) 2014 - Joomla! Extensions Store
 * @license GNU/GPLv2 http://www.gnu.org/licenses/gpl-2.0.html  
 */
defined ( '_JEXEC' ) or die ( 'Restricted access' );
if(!JComponentHelper::getParams('com_responsivizer')->get('enable_debug', 0)) {
	ini_set('display_errors', 0);
	ini_set('error_reporting', E_ERROR);
}

// Auto loader setup
JLoader::registerPrefix('Responsivizer',  JPATH_COMPONENT_ADMINISTRATOR . '/framework');

// Main application object
$app = JFactory::getApplication();
/*
 * All SMVC logic is based on controller.task correcting the wrong Joomla concept
 * of base execute on view names.
 * When task is not specified because Joomla force view query string such as menu
 * the view value is equals to controller and viewname = controller.display
 */
$viewName = $app->input->get ('view', 'offlinecache');
$controller_command = $app->input->get ( 'task', $viewName . '.showEntity' );
list ( $controller_name, $controller_task ) = explode ( '.', $controller_command );
// Defaults
if (! $controller_name) {
	$controller_name = 'offlinecache';
}
if (! $controller_task) {
	$controller_task = 'showEntity';
}

$path = JPATH_COMPONENT . DIRECTORY_SEPARATOR . 'controllers' . DIRECTORY_SEPARATOR . strtolower($controller_name) . '.php';
if (file_exists ( $path )) {
	require_once $path;
} else {
	$app->enqueueMessage(JText::_('COM_RESPONSIVIZER_ERROR_NO_CONTROLLER_FILE'), 'error');
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
	$app->enqueueMessage(JText::_('COM_RESPONSIVIZER_ERROR_NO_CONTROLLER'), 'error');
	return false;
} 