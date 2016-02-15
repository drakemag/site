<?php
// namespace components\com_responsivizer\views\offlinecache;
/**
 * @package RESPONSIVIZER::OFFLINECACHE::components::com_responsivizer
 * @subpackage views
 * @subpackage offlinecache
 * @author Joomla! Extensions Store
 * @copyright (C) 2014 - Joomla! Extensions Store
 * @license GNU/GPLv2 http://www.gnu.org/licenses/gpl-2.0.html
 */
defined('_JEXEC') or die('Restricted access');

/**
 * Main view class
 *
 * @package RESPONSIVIZER::OFFLINECACHE::components::com_responsivizer
 * @subpackage views
 * @subpackage offlinecache
 * @since 2.0
 */
class ResponsivizerViewOfflinecache extends ResponsivizerView {
	/**
	 * Set headers and call template to format cache manifest
	 * Returns a 404 HTTP header if cache needs to be unvalidated
	 * 
	 * @access public
	 * @return void
	 */
	public function showEntity($record, $tpl = null) {
		// Force delete application cache if menu item is not published or if global cache param is not enabled
		if(!$record->published || $record->deleted || !$this->getModel()->getState('cparams')->get('offlinecache', false)) {
			JResponse::setHeader('Status', '404 not Found');
		} else {
			// Menu item is published and active so generate manifest to client
			$document = JFactory::getDocument();
			$document->setMimeEncoding('text/cache-manifest');
			JResponse::setHeader('Cache-Control', 'no-cache, private');
	
			$this->id = $record->id;
			$this->resources = $record->resources;
			$this->baseURI = JURI::root();
			parent::display($tpl);
		}
	}
}
