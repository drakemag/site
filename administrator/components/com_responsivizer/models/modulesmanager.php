<?php
// namespace administrator\components\com_responsivizer\models;
/**
 * @package RESPONSIVIZER::MODULESMANAGER::administrator::components::com_responsivizer
 * @subpackage models
 * @author Joomla! Extensions Store
 * @copyright (C) 2014 - Joomla! Extensions Store
 * @license GNU/GPLv2 http://www.gnu.org/licenses/gpl-2.0.html
 */
defined ( '_JEXEC' ) or die ( 'Restricted access' );

/**
 * Sources model responsibilities
 *
 * @package RESPONSIVIZER::MODULESMANAGER::administrator::components::com_responsivizer
 * @subpackage models
 * @since 2.0
 */
interface IModulesmanagerModel {
	/**
	 * Main get data method
	 *
	 * @access public
	 * @param Object $registry
	 * @return Object[]
	 */
	public function getModulesData(IResponsivizerRegistry $registry = null);
	
	/**
	 * Return select lists used as filter for editEntity
	 *
	 * @access public
	 * @param IResponsivizerRegistry $registry
	 * @return array
	 */
	public function getCopyLists(IResponsivizerRegistry $registry);
	
	/**
	 * Store positions available to match DB file
	 *
	 * @access public
	 * @return array
	 */
	public function storePositionsDB(IResponsivizerRegistry $registry);
}

/**
 * Sources model concrete implementation <<testable_behavior>>
 *
 * @package RESPONSIVIZER::MODULESMANAGER::administrator::components::com_responsivizer
 * @subpackage models
 * @since 2.0
 */
class ResponsivizerModelModulesmanager extends ResponsivizerModel implements IModulesmanagerModel {
	/**
	 * String for merged matched available positions for Responsivizer template
	 *
	 * @access private
	 * @var string
	 */
	private $findInPositions;
	
	/**
	 * Array for merged matched available positions for Responsivizer template
	 *
	 * @access private
	 * @var array
	 */
	private $positions;
	
	/**
	 * Build list entities query
	 *
	 * @access protected
	 * @return string
	 */
	protected function buildListQuery() {
		// WHERE
		$where = array ();
		$whereString = null;
		$orderString = null;
		// STATE FILTER
		if ($filter_state = $this->state->get ( 'state' )) {
			if ($filter_state == 'P') {
				$where [] = 's.published = 1';
			} else if ($filter_state == 'U') {
				$where [] = 's.published = 0';
			}
		}
		
		// POSITION FILTER
		$modulePosition =  $this->state->get ( 'position' );
		if ($modulePosition || $modulePosition === '') {
				$where [] = 's.position = ' . $this->_db->quote($modulePosition);
		}
		
		// MODULE TYPE FILTER
		if ($filter_type = $this->state->get ( 'type' )) {
			$where [] = 's.module = ' . $this->_db->quote($filter_type);
		}
		
		// MOBILE STATUS FILTER
		if ($filter_mobile_available = (int)$this->state->get ( 'mobileavailable' )) {
			if ($filter_mobile_available === 1) {
				$where [] = 's.position IN(' . $this->findInPositions . ')';
				$where [] = '(r.mobile_exclude != 1 OR ISNULL(r.mobile_exclude))';
			} else if ($filter_mobile_available === -1) {
				$where [] = '(s.position NOT IN(' . $this->findInPositions . ') OR r.mobile_exclude = 1)';
			}
		}
		
		// MOBILE EXCLUDE FILTER
		if ($filter_exclude = (int)$this->state->get ( 'exclude' )) {
			if ($filter_exclude === 1) {
				$where [] = 'r.mobile_exclude = 1';
			} else if ($filter_exclude === -1) {
				$where [] = '(r.mobile_exclude != 1 OR ISNULL(r.mobile_exclude))';
			}
		}
		
		// TEXT FILTER
		if ($this->state->get ( 'searchword' )) {
			$where [] = "s.title LIKE '%" . $this->state->get ( 'searchword' ) . "%'";
		}
		
		if (count ( $where )) {
			$whereString = "\n AND " . implode ( "\n AND ", $where );
		}
		
		// ORDERBY
		if ($this->state->get ( 'order' )) {
			$orderString = "\n ORDER BY " . $this->state->get ( 'order' ) . " ";
		}
		
		// ORDERDIR
		if ($this->state->get ( 'order_dir' )) {
			$orderString .= $this->state->get ( 'order_dir' );
		}
		
		$query = "SELECT" .
				 "\n s.id," .
				 "\n s.title," .
				 "\n s.position," .
				 "\n s.published+2*(e.enabled-1) as computed_published," .
				 "\n s.checked_out," .
				 "\n e.name AS name," .
				 "\n r.mobile_exclude," .
				 "\n r.show_title," .
				 "\n r.show_bg," .
				 "\n m.id AS has_mobile," .
				 "\n (CASE WHEN s.position IN(" . $this->findInPositions . ") THEN 1 ELSE 0 END) AS mobile_available" .
				 "\n FROM #__modules AS s" .
				 "\n LEFT JOIN #__modules AS m ON m.title LIKE CONCAT('%', s.title, '%') AND m.id != s.id AND (m.published IN (0, 1)) AND m.client_id = 0" .
				 "\n LEFT JOIN #__extensions AS e ON e.element = s.module" .
				 "\n LEFT JOIN #__rvzr_modules AS r ON r.id = s.id" .
				 "\n WHERE (s.published IN (0, 1)) AND s.client_id = 0 AND e.client_id = 0" .
				 $whereString . 
				 "\n GROUP BY s.id" .
				 $orderString;
				  
		return $query;
	}
	
	/**
	 * Main get data method
	 *
	 * @override
	 * @param Object $registry
	 * @access public
	 * @return Object[]
	 */
	public function getModulesData(IResponsivizerRegistry $registry = null) {
		// Load native template positions from Json DB file
		$registry->loadFile(JPATH_RVZR_DBPOSITIONS, 'json');
		$dbArray = $registry->toArray();
		$totalPositions = $registry->toFlatArray($dbArray);
		$this->positions = $totalPositions;
		
		// Total mobile positions to search in
		$this->findInPositions = $registry->toDatabaseString($totalPositions);
		
		// Build query
		$query = $this->buildListQuery ();
		$this->_db->setQuery ( $query, $this->getState ( 'limitstart' ), $this->getState ( 'limit' ) );
		try {
			$result = $this->_db->loadObjectList ();
			if($this->_db->getErrorNum()) {
				throw new ResponsivizerException(JText::_('COM_RESPONSIVIZER_ERROR_RETRIEVING_MODULES_LIST') . $this->_db->getErrorMsg(), 'error');
			}
		} catch (ResponsivizerException $e) {
			$this->app->enqueueMessage($e->getMessage(), $e->getErrorLevel());
			$result = array();
		} catch (Exception $e) {
			$rvzrException = new ResponsivizerException($e->getMessage(), 'error');
			$this->app->enqueueMessage($rvzrException->getMessage(), $rvzrException->getErrorLevel());
			$result = array();
		}
		return $result;
	}
	
	/**
	 * Return select lists used as filter for listEntities
	 *
	 * @access public
	 * @return array
	 */
	public function getFilters() {
		$filters ['state'] = JHTML::_ ( 'grid.state', $this->getState ( 'state' ));
		
		// Translate findInPositions pre populated by getData into JOption object
		$positionObjects = array();
		$queryModulePositions = "SELECT DISTINCT " . $this->_db->quoteName('position') .
								"\n FROM " . $this->_db->quoteName('#__modules') .
								"\n WHERE " . $this->_db->quoteName('client_id') . " = 0" .
								"\n AND " . $this->_db->quoteName('position') . " != ''";
		$currentUsedPositions = $this->_db->setQuery($queryModulePositions)->loadColumn();
		$positionObjects[] = JHTML::_('select.option', 0, JText::_('COM_RESPONSIVIZER_NOPOSITION'));
		$positionObjects[] = JHTML::_('select.option', '', JText::_('COM_RESPONSIVIZER_NOTASSIGNED'));
		if(!empty($currentUsedPositions)) {
			foreach ($currentUsedPositions as $position) {
				$positionObjects[] = JHTML::_('select.option', $position, $position);
			}
		}
		$filters ['position'] = JHTML::_ ( 'select.genericlist', $positionObjects, 'filter_position', 'onchange="Joomla.submitform();"', 'value', 'text', $this->getState('position'));
		
		$filters ['mobileavailable'] = JHTML::_ ( 'grid.state', $this->getState ( 'mobileavailable' ), JText::_('COM_RESPONSIVIZER_MOBILE_AVAILABLE'), JText::_('COM_RESPONSIVIZER_MOBILE_NOAVAILABLE'), $this->getState ( 'mobileavailable' ));
		
		
		// States for mobile available modules
		$mobileAvailableObjects = array();
		$mobileAvailableObjects[] = JHTML::_('select.option', null, JText::_('COM_RESPONSIVIZER_MOBILE_STATUS'));
		$mobileAvailableObjects[] = JHTML::_('select.option', 1, JText::_('COM_RESPONSIVIZER_MOBILE_AVAILABLE'));
		$mobileAvailableObjects[] = JHTML::_('select.option', -1, JText::_('COM_RESPONSIVIZER_MOBILE_NOAVAILABLE'));
		$filters ['mobileavailable'] = JHTML::_ ( 'select.genericlist', $mobileAvailableObjects, 'filter_mobile_available', 'onchange="Joomla.submitform();"', 'value', 'text', $this->getState('mobileavailable'));
		
		// States for mobile exclusion
		$mobileExclusion = array();
		$mobileExclusion[] = JHTML::_('select.option', null, JText::_('COM_RESPONSIVIZER_MOBILE_EXCLUSION'));
		$mobileExclusion[] = JHTML::_('select.option', 1, JText::_('COM_RESPONSIVIZER_MOBILE_EXCLUDED'));
		$mobileExclusion[] = JHTML::_('select.option', -1, JText::_('COM_RESPONSIVIZER_MOBILE_NOEXCLUDED'));
		$filters ['exclude'] = JHTML::_ ( 'select.genericlist', $mobileExclusion, 'filter_exclude', 'onchange="Joomla.submitform();"', 'value', 'text', $this->getState('exclude'));
		
		// States for module types
		$queryModuletypes = "SELECT element AS value, name AS text" .
							"\n FROM #__extensions as e" .
							"\n LEFT JOIN #__modules as m ON m.module=e.element AND m.client_id=e.client_id" .
							"\n WHERE e.client_id = 0" .
							"\n AND type = " . $this->_db->quote('module') .
							"\n AND m.module IS NOT NULL" .
							"\n GROUP BY element, name";
		
		$this->_db->setQuery($queryModuletypes);
		$moduleTypes = array();
		$moduleTypes = $this->_db->loadObjectList();
		$lang = JFactory::getLanguage();
		foreach ($moduleTypes as $i => $module) {
			$extension = $module->value;
			$path = JPATH_SITE;
			$source = $path . "/modules/$extension";
			$lang->load("$extension.sys", $path, null, false, true)
			||	$lang->load("$extension.sys", $source, null, false, true);
			$moduleTypes[$i]->text = JText::_($module->text);
		}
		JArrayHelper::sortObjects($moduleTypes, 'text', 1, true, true);
		array_unshift($moduleTypes, JHTML::_('select.option', null, JText::_('COM_RESPONSIVIZER_SELECT_TYPE')));
		$filters ['type'] = JHTML::_ ( 'select.genericlist', $moduleTypes, 'filter_type', 'onchange="Joomla.submitform();"', 'value', 'text', $this->getState('type'));
		
		return $filters;
	}
	
	/**
	 * Return select lists used as filter for editEntity
	 *
	 * @access public
	 * @param IResponsivizerRegistry $registry
	 * @return array
	 */
	public function getCopyLists(IResponsivizerRegistry $registry) {
		$lists = array ();
		// Load native template positions from Json DB file
		$registry->loadFile(JPATH_RVZR_DBPOSITIONS, 'json');
		$dbArray = $registry->toArray();
		$positions = array_keys($dbArray);
		
		$options = array();
		$options[] = JHTML::_('select.option', null, JText::_('COM_RESPONSIVIZER_SELECT_POSITION'));
		foreach ($positions as $singlePosition) {
			$options[] = JHTML::_('select.option', $singlePosition, $singlePosition);
		}
		
		$lists ['positions'] = JHTML::_ ( 'select.genericlist', $options, 'position', 'data-validation="required" class="labelctrls"', 'value', 'text');
		
		return $lists;
	}
	
	/**
	 * Load entity from ORM table
	 *
	 * @access public
	 * @param int $id
	 * @return Object&
	 */
	public function loadEntity($id) {
		// load table record
		$table = $this->getTable('Module', 'JTable');
	
		try {
			if(!$table->load($id)) {
				throw new ResponsivizerException($this->_db->getErrorMsg (), 'error');
			}
		} catch (ResponsivizerException $e) {
			$this->setError($e);
			return false;
		} catch (Exception $e) {
			$rvzrException = new ResponsivizerException($e->getMessage(), 'error');
			$this->setError($rvzrException);
			return false;
		}
		return $table;
	}
	
	/**
	 * Storing entity by ORM table
	 *
	 * @access public
	 * @return mixed
	 */
	public function storeEntity() {
		$table = $this->getTable('Module', 'JTable');
		try {
			if (!$table->bind ($_POST, true)) {
				throw new ResponsivizerException($table->getError (), 'error');
			}
				
			// Store binded values
			$sourceModuleID = $table->id;
			$newTitle = $table->title;
			$newPosition = $table->position;
				
			if (!$table->load ($table->id)) {
				throw new ResponsivizerException($table->getError (), 'error');
			}
				
			// Clean id to make always insert and replace override chosen position
			$table->id = null;
			$table->title = $newTitle;
			$table->position = $newPosition;
	
			if (! $table->store (false)) {
				throw new ResponsivizerException($table->getError (), 'error');
			}
			// Only if table supports ordering
			if(property_exists($table, 'ordering')) {
				$table->reorder();
			}
			
			// Duplicate module menu assignment
			$queryAssignment = "SELECT " . $this->_db->qn('menuid') .
							   "\n FROM #__modules_menu" .
							   "\n WHERE " . $this->_db->qn('moduleid') . '=' . (int)$sourceModuleID;
			$this->_db->setQuery($queryAssignment);
			$menuIDs = $this->_db->loadColumn();
			
			// Prepare single executed insert query
			if(is_array($menuIDs) && count($menuIDs)) {
				$allValues = array();
				foreach ($menuIDs as $menuID) {
					$allValues[] = '( '. (int)$table->id . ' , ' . (int)$menuID . ' )';				
				}
				$allValues = implode(',', $allValues);
				$insertModuleMenuAssignment = "INSERT INTO #__modules_menu VALUES " . $allValues;
				if(!$this->_db->setQuery($insertModuleMenuAssignment)->execute()) {
					throw new ResponsivizerException(JText::_('COM_RESPONSIVIZER_ERROR_ASSIGN_MODULE_MENU'), 'notice');
				}
			}
		} catch(ResponsivizerException $e) {
			$this->setError($e);
			return false;
		}  catch(Exception $e) {
			$ResponsivizerException = new ResponsivizerException($e->getMessage(), 'error');
			$this->setError($ResponsivizerException);
			return false;
		}
		return $table;
	}
	
	/**
	 * Publishing state changer for entities
	 *
	 * @override
	 * @access public
	 * @param int $idEntity
	 * @param string $state
	 * @return boolean
	 */
	public function publishEntities($idEntity, $state) {
		// Table load
		$table = $this->getTable ('Module', 'JTable');
		if (isset ( $idEntity )) {
			try {
				if (! $table->load($idEntity)) {
					throw new ResponsivizerException($table->getError (), 'notice');
				}
				switch ($state) {
					case 'unpublish' :
						$table->published = 0;
						break;
							
					case 'publish' :
						$table->published = 1;
						break;
				}
	
				if (! $table->store ()) {
					throw new ResponsivizerException($table->getError (), 'notice');
				}
			} catch(ResponsivizerException $e) {
				$this->setError($e);
				return false;
			}  catch(Exception $e) {
				$rvzrException = new ResponsivizerException($e->getMessage(), 'notice');
				$this->setError($rvzrException);
				return false;
			}
		}
		return true;
	}
	
	/**
	 * Store positions available to match DB file
	 *
	 * @access public
	 * @return array
	*/
	public function storePositionsDB(IResponsivizerRegistry $registry) {
		try {
			// Bind POST as registry object data
			$jformData = $this->app->input->get ( 'jform', array (
					0 
			), 'array' );
			$registry->loadArray ( $jformData );
			
			// Cycle on IteratorAggregate registry object
			foreach ( $registry as &$data ) {
				// Explode input values as array to serialize
				$data = explode ( ',', $data );
			}
			
			// Finally try to store data on file system
			return $registry->writeFile ( $registry->toObject (), JPATH_RVZR_DBPOSITIONS, 'json' );
		} catch ( ResponsivizerException $e ) {
			$this->setError ( $e );
			return false;
		} catch ( Exception $e ) {
			$ResponsivizerException = new ResponsivizerException ( $e->getMessage (), 'error' );
			$this->setError ( $ResponsivizerException );
			return false;
		}
	}
}