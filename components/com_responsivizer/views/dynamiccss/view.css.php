<?php
// namespace components\com_responsivizer\views\offlinecache;
/**
 *
 * @package RESPONSIVIZER::DYNAMICCSS::components::com_responsivizer
 * @subpackage views
 * @subpackage offlinecache
 * @author Joomla! Extensions Store
 * @copyright (C) 2014 - Joomla! Extensions Store
 * @license GNU/GPLv2 http://www.gnu.org/licenses/gpl-2.0.html
 */
defined ( '_JEXEC' ) or die ( 'Restricted access' );

/**
 * Main view class
 *
 * @package RESPONSIVIZER::DYNAMICCSS::components::com_responsivizer
 * @subpackage views
 * @subpackage offlinecache
 * @since 2.1
 */
class ResponsivizerViewDynamiccss extends ResponsivizerView {
	/**
	 * Set headers and call template to format cache manifest
	 * Returns a 404 HTTP header if cache needs to be unvalidated
	 *
	 * @access public
	 * @return void
	 */
	public function display($tpl = null) {
		$document = $this->document;
		$document->setMimeEncoding ( 'text/css' );
		
		// Assign parameters from extension
		$this->params = $this->getModel()->getComponentParams();
		$this->liveSite = JUri::root();
		
		parent::display($tpl);
	}
}
