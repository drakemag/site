<?php
// namespace components\com_responsivizer\controllers;
/**
 * @package RESPONSIVIZER::DYNAMICCSS::components::com_responsivizer
 * @subpackage controllers
 * @author Joomla! Extensions Store
 * @copyright (C) 2014 - Joomla! Extensions Store
 * @license GNU/GPLv2 http://www.gnu.org/licenses/gpl-2.0.html
 */
defined ( '_JEXEC' ) or die ( 'Restricted access' );

/**
 * Main controller class
 *
 * @package RESPONSIVIZER::DYNAMICCSS::components::com_responsivizer
 * @subpackage controllers
 * @since 2.1
 */
class ResponsivizerControllerDynamiccss extends ResponsivizerController {
	/**
	 * Manage rendering of offline cache manifest generating on the fly for the current page resources
	 * @access public
	 * @return void
	 */
	public function display($cachable = false, $urlparams = false) {
		parent::display($cachable, $urlparams);
	}
}