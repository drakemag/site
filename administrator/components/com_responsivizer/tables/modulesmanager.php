<?php
// namespace administrator\components\com_responsivizer\tables;
/**
 *
 * @package RESPONSIVIZER::MODULESMANAGER::administrator::components::com_responsivizer
 * @subpackage tables
 * @author Joomla! Extensions Store
 * @copyright (C) 2014 - Joomla! Extensions Store
 * @license GNU/GPLv2 http://www.gnu.org/licenses/gpl-2.0.html
 */
// no direct access
defined('_JEXEC') or die('Restricted access');

/**
 * ORM Table for 1-1 modules exclusions from mobile
 *
 * @package RESPONSIVIZER::MODULESMANAGER::administrator::components::com_responsivizer
 * @subpackage tables
 * @since 2.0
 */
class TableModulesmanager extends JTable {
	/**
	 * @var int
	 */
	public $id;

	/**
	 * @var int
	 */
	public $mobile_exclude;

	/**
	 * @var int
	 */
	public $show_title;
	
	/**
	 * @var int
	 */
	public $show_bg;

	/**
	 * Class constructor
	 * @param Object $_db
	 *
	 * return Object
	 */
	public function __construct($_db) {
		parent::__construct('#__rvzr_modules', 'id', $_db);
	}

	/**
	 * Method to store a row in the database from the JTable instance properties.
	 * If a primary key value is set the row with that primary key value will be
	 * updated with the instance property values.  If no primary key value is set
	 * a new row will be inserted into the database with the properties from the
	 * JTable instance.
	 *
	 * @param   boolean  $updateNulls  True to update fields even if they are null.
	 *
	 * @return  boolean  True on success.
	 *
	 * @link	http://docs.joomla.org/JTable/store
	 * @since   11.1
	 */
	public function store($updateNulls = false, $currentID = null) {
		// Initialise variables.
		$k = $this->_tbl_key;

		// If a primary key really exists in DB as numeric and not autoincrement update the object, otherwise insert it.
		if ($this->$k > 0) {
			$stored = $this->_db->updateObject($this->_tbl, $this, $this->_tbl_key, $updateNulls);
		} else {
			$this->id = (int)$currentID;
			$stored = $this->_db->insertObject($this->_tbl, $this, $this->_tbl_key);
		}

		return $stored;
	}
}
