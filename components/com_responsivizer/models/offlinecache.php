<?php
// namespace components\com_responsivizer\models;
/**
 * @package RESPONSIVIZER::OFFLINECACHE::components::com_responsivizer
 * @subpackage models
 * @author Joomla! Extensions Store
 * @copyright (C) 2014 - Joomla! Extensions Store
 * @license GNU/GPLv2 http://www.gnu.org/licenses/gpl-2.0.html
 */
defined ( '_JEXEC' ) or die ( 'Restricted access' );

/**
 * Main offline cache resources model class
 *
 * @package RESPONSIVIZER::OFFLINECACHE::components::com_responsivizer
 * @subpackage models
 * @since 2.0
 */
class ResponsivizerModelOfflinecache extends ResponsivizerModel {  
	/**
	 * Load entity from ORM table
	 *
	 * @access public
	 * @param int $id
	 * @return Object&
	 */
	public function loadEntity($id) {
		try {
			// Try to load offline cache manifest resources for this menu id
			$query = "SELECT urls.id, urls.published, urls.deleted, urls.resources" .
					 "\n FROM #__rvzr_offline_urls AS urls" .
					 "\n WHERE urls.itemid = " . (int)$id;
			if(!$record = $this->_db->setQuery($query)->loadObject()) {
				throw new ResponsivizerException($this->_db->getErrorMsg(), 'error');
			}
			
			// Decode Json resources
			$record->resources = json_decode($record->resources);
			if(!is_array($record->resources)) {
				throw new ResponsivizerException(null, 'error');
			}
				
		} catch (ResponsivizerException $e) {
			$this->setError($e);
			return false;
		} catch (Exception $e) {
			$ResponsivizerException = new ResponsivizerException($e->getMessage(), 'error');
			$this->setError($ResponsivizerException);
			return false;
		}
		return $record;
	}
	
	/**
	 * Class Constructor
	 * @access public
	 * @return Object&
	 */
	function __construct($config = array()) {
		parent::__construct ($config);
		$this->setState('cparams', JComponentHelper::getParams('com_responsivizer')); 
	}
}