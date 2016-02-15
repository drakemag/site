<?php
// namespace administrator\components\com_responsivizer\controllers;
/**
 * @package RESPONSIVIZER::CPANEL::administrator::components::com_responsivizer
 * @subpackage controllers
 * @author Joomla! Extensions Store
 * @copyright (C) 2014 - Joomla! Extensions Store
 * @license GNU/GPLv2 http://www.gnu.org/licenses/gpl-2.0.html  
 */
defined ( '_JEXEC' ) or die ( 'Restricted access' );

/**
 * CPanel controller
 *
 * @package RESPONSIVIZER::CPANEL::administrator::components::com_responsivizer
 * @subpackage controllers
 * @since 2.0
 */
class ResponsivizerControllerCpanel extends ResponsivizerController {
	/**
	 * Show Control Panel
	 * @access public
	 * @return void
	 */
	function display($cachable = false, $urlparams = false) {
		$defaultModel = $this->getModel();
		$defaultModel->setState('option', $this->option);
		
		// Dependency injection setter on view/model for ResponsivizerRegistry instance
		$view = $this->getView();
		$registry = new ResponsivizerRegistry();
		$view->set('registry', $registry);
		$HTTPClient = new ResponsivizerHttp();
		$view->set('httpclient', $HTTPClient);
		
		// No operations
		parent::display ($cachable); 
	}
}