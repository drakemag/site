<?php 
// namespace administrator\components\com_responsivizer\views\ajaxserver;

/**
 * @author Joomla! Extensions Store
 * @package RESPONSIVIZER::AJAXSERVER::administrator::components::com_responsivizer
 * @subpackage views
 * @subpackage ajaxserver
 * @copyright (C)2014 Joomla! Extensions Store
 * @license GNU/GPLv2 http://www.gnu.org/licenses/gpl-2.0.html  
 */
defined ( '_JEXEC' ) or die ( 'Restricted access' );

/**
 * Config view
 *
 * @package RESPONSIVIZER::AJAXSERVER::administrator::components::com_responsivizer
 * @subpackage views
 * @subpackage ajaxserver
 * @since 1.0
 */
class ResponsivizerViewAjaxserver extends ResponsivizerView {
	/**
	 * Return application/json response to JS client APP
	 * Replace $tpl optional param with $userData contents to inject
	 *        	
	 * @access public
	 * @param string $tpl
	 * @return void
	 */
	public function display($userData = null) {
		$doc = JFactory::getDocument();
		$doc->setMimeEncoding('application/json');

		echo json_encode($userData);  
		
		exit();
	}
}