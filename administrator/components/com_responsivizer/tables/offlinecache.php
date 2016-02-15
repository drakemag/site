<?php
// namespace administrator\components\com_responsivizer\tables;
/**
 *
 * @package RESPONSIVIZER::OFFLINECACHE::administrator::components::com_responsivizer
 * @subpackage tables
 * @author Joomla! Extensions Store
 * @copyright (C) 2014 - Joomla! Extensions Store
 * @license GNU/GPLv2 http://www.gnu.org/licenses/gpl-2.0.html
 */
// no direct access
defined ( '_JEXEC' ) or die ( 'Restricted access' );

/**
 * ORM Table for offline cache pages
 *
 * @package RESPONSIVIZER::OFFLINECACHE::administrator::components::com_responsivizer
 * @subpackage tables
 * @since 2.0
 */
class TableOfflinecache extends JTable {
	/**
	 * @var int
	 */
	public $id = null;
	
	/**
	 * @var int
	 */
	public $itemid = null;
	
	/**
	 * @var string
	 */
	public $description = '';
	
	/**
	 * @var int
	 */
	public $checked_out = 0;
	
	/**
	 * @var datetime
	 */
	public $checked_out_time = 0;
	
	/**
	 * @var int
	 */
	public $published = 1;
	
	/**
	 * @var int
	 */
	public $process_css = 1;
	
	/**
	 * @var int
	 */
	public $process_js = 1;
	
	/**
	 * @var int
	 */
	public $process_img = 1;
	
	/**
	 * @var string
	 */
	public $resources = array();
	
	/**
	 * Load Table override
	 * @override
	 * 
	 * @see JTable::load()
	 */
	public function load($idEntity = null, $reset = true) {
		// If not $idEntity set return empty object
		if($idEntity) {
			if(!parent::load ( $idEntity )) {
				return false;
			}
		}
		// By default convert to plain object this json serialized field, later convertable in JRegistry if needed
		if ($this->resources) {
			$this->resources = json_decode ( $this->resources );
		} else {
			$this->resources = array();
		}
		
		return true;
	}
	
	/**
	 * Check Table override
	 * @override
	 * 
	 * @see JTable::check()
	 */
	public function check() {
		// Itemid for caching menu item required
		if (! $this->itemid) {
			$this->setError ( JTEXT::_ ( 'COM_RESPONSIVIZER_VALIDATION_ERROR' ) );
			return false;
		}
		
		// Check to avoid duplicated records spanning on same itemid
		$query = "SELECT urls.id, urls.itemid" .
				 "\n FROM #__rvzr_offline_urls AS urls" .
				 "\n WHERE urls.itemid = " . (int)$this->itemid;
		$existingAlreadyRecord = $this->_db->setQuery($query)->loadObject();
		// If detected already exists record id for this itemid load now in this post binded table
		if(is_object($existingAlreadyRecord) && isset($existingAlreadyRecord->id)) {
			$this->id = $existingAlreadyRecord->id;
			$this->deleted = 0;
		}
		
		return true;
	}
	
	/**
	 * Class constructor
	 * @param Object& $_db
	 * 
	 * return Object&
	 */
	public function __construct(&$_db) {
		parent::__construct ( '#__rvzr_offline_urls', 'id', $_db );
	}
}