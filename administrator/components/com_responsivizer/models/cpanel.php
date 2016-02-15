<?php
// namespace administrator\components\com_responsivizer\models;
/**
 * @package RESPONSIVIZER::CPANEL::administrator::components::com_responsivizer
 * @subpackage models
 * @author Joomla! Extensions Store
 * @copyright (C) 2014 - Joomla! Extensions Store
 * @license GNU/GPLv2 http://www.gnu.org/licenses/gpl-2.0.html  
 */
defined ( '_JEXEC' ) or die ( 'Restricted access' );
define ('SERVER_REMOTE_URI', 'http://storejextensions.org/dmdocuments/updates/');
define ('UPDATES_FORMAT', '.json');

/**
 * CPanel model responsibility
 *
 * @package RESPONSIVIZER::CPANEL::administrator::components::com_responsivizer
 * @subpackage models
 * @since 2.0
 */
interface ICPanelModel {
	/**
	 * Main get data method
	 *
	 * @access public
	 * @return array
	 */
	public function getData(IResponsivizerRegistry $registry = null);
}

/**
 * CPanel model concrete implementation
 *
 * @package RESPONSIVIZER::CPANEL::administrator::components::com_responsivizer
 * @subpackage models
 * @since 1.0
 */
class ResponsivizerModelCpanel extends ResponsivizerModel implements ICPanelModel { 
	  
	/**
	 * Modules mobile list entities query
	 *
	 * @access private
	 * @param string $field
	 * @param string $value
	 * @return string
	 */
	private function buildListQueryMobileModules($totalMobilePositions, $excluded = null) {
		$join = null;
		$where = null;
		
		if($excluded === true) {
			$join =  "\n JOIN " . $this->_db->qn('#__extensions') . " AS e ON e.element = m.module";
			$where = "\n AND e.client_id = 0 AND (e.enabled = 0 OR m.published = 0 OR r.mobile_exclude = 1) AND m.published >= 0";
		} elseif($excluded === false) {
			$join =  "\n JOIN " . $this->_db->qn('#__extensions') . " AS e ON e.element = m.module";
			$where = "\n AND e.client_id = 0 AND e.enabled = 1 AND m.published = 1 AND (r.mobile_exclude = 0 OR ISNULL(r.mobile_exclude)) ";
		} else {
			$where .= "\n AND m.published >= 0";
		}
		
		//Dyna query
		$query = "SELECT COUNT(*)" .
				"\n FROM " . $this->_db->qn('#__modules') . " AS m" .
				$join .
				"\n LEFT JOIN " . $this->_db->qn('#__rvzr_modules') . " AS r" .
				"\n ON m.id = r.id". 
				"\n WHERE m.client_id = 0 AND m.position IN(" . $totalMobilePositions . ")" .
				$where;
		
		
		return $query;
	}
	
	/**
	 * Modules list entities query
	 *
	 * @access private
	 * @param string $field
	 * @param string $value
	 * @return string
	 */
	private function buildListQueryModules($field, $value, $condition = ' = ') {
		$join = null;
		$where = null;
		// Evaluate JOIN #__extensions enabling ONLY for published modules, total and upublished disregard enable #__extensions as a total
		if($value == 1) {
			$join =  "\n JOIN " . $this->_db->qn('#__extensions') . " AS e ON e.element = m.module";
			$where = "\n AND " . $this->_db->qn($field) . $condition . $this->_db->quote($value) . 
					 "\n AND e.enabled = 1 AND e.client_id = 0";
		} elseif($value == 0 && $condition == ' = ') {
			$join =  "\n JOIN " . $this->_db->qn('#__extensions') . " AS e ON e.element = m.module";
			$where = "\n AND e.client_id = 0 AND (e.enabled = 0 OR " . $this->_db->qn($field) . $condition . $this->_db->quote($value) . ")";
		} else {
			$where = "\n AND " . $this->_db->qn($field) . $condition . $this->_db->quote($value);
		}
		
		//Dyna query
		$query = "SELECT DISTINCT COUNT(*)" . 
				 "\n FROM " . $this->_db->qn('#__modules') . " AS m" . 
				 $join .
				 "\n WHERE m.client_id = 0 " .
				 $where;
		return $query;
	}
	
	/**
	 * Modules list entities query
	 *
	 * @access private
	 * @param string $field
	 * @param string $value
	 * @return string
	 */
	private function buildListQueryOfflineCache($field, $value, $condition = ' = ') {
		$where = null;
		
		if($value == 1) {
			$where = "\n AND " . $this->_db->qn($field) . $condition . $this->_db->quote($value);
		}
		
		//Dyna query
		$query = "SELECT COUNT(*)" .
				"\n FROM " . $this->_db->qn('#__rvzr_offline_urls') . " AS urls" .
				"\n WHERE urls.deleted = 0 " .
				$where;
		return $query;
	}

	/**
	 * Main get data method
	 *
	 * @access public
	 * @return array
	 */
	public function getData(IResponsivizerRegistry $registry = null) {
		// Load native template positions from Json DB file
		$registry->loadFile(JPATH_RVZR_DBPOSITIONS, 'json');
		$dbArray = $registry->toArray();
		$totalPositions = $registry->toFlatArray($dbArray);
		
		// Total mobile positions to search in
		$findInPositions = $registry->toDatabaseString($totalPositions);
		
		$calculatedStats = array();
		// Build queries
		try {
			// Total modules
			$query = $this->buildListQueryModules ('published', 0, ' >= ');
			$this->_db->setQuery ( $query );
			$calculatedStats['totalModules'] = $this->_db->loadResult ();
			// Total global modules
			if($this->_db->getErrorNum()) {
				throw new ResponsivizerException(JText::_('COM_RESPONSIVIZER_DBERROR_STATS') . $this->_db->getErrorMsg(), 'error');
			}
			
			// Published modules
			$query = $this->buildListQueryModules ('published', 1);
			$this->_db->setQuery ( $query );
			$calculatedStats['publishedModules'] = $this->_db->loadResult ();
			// Total published modules
			if($this->_db->getErrorNum()) {
				throw new ResponsivizerException(JText::_('COM_RESPONSIVIZER_DBERROR_STATS') . $this->_db->getErrorMsg(), 'error');
			}
			
			// Unpublished modules
			$query = $this->buildListQueryModules ('published', 0);
			$this->_db->setQuery ( $query );
			$calculatedStats['unpublishedModules'] = $this->_db->loadResult ();
			// Total published modules
			if($this->_db->getErrorNum()) {
				throw new ResponsivizerException(JText::_('COM_RESPONSIVIZER_DBERROR_STATS') . $this->_db->getErrorMsg(), 'error');
			}
			
			$query = $this->buildListQueryMobileModules ($findInPositions);
			$this->_db->setQuery ( $query );
			$calculatedStats['totalMobileModules'] = $this->_db->loadResult ();
			// Modules that match mobile positions in JSON DB
			if($this->_db->getErrorNum()) {
				throw new ResponsivizerException(JText::_('COM_RESPONSIVIZER_DBERROR_STATS') . $this->_db->getErrorMsg(), 'error');
			}
			
			$query = $this->buildListQueryMobileModules ($findInPositions, false);
			$this->_db->setQuery ( $query );
			$calculatedStats['publishedMobileModules'] = $this->_db->loadResult ();
			// Modules that match mobile positions AND have not #__rvzr_modules record or = 0 and are published and enabled
			if($this->_db->getErrorNum()) {
				throw new ResponsivizerException(JText::_('COM_RESPONSIVIZER_DBERROR_STATS') . $this->_db->getErrorMsg(), 'error');
			}
			
			$query = $this->buildListQueryMobileModules ($findInPositions, true);
			$this->_db->setQuery ( $query );
			$calculatedStats['unpublishedMobileModules'] = $this->_db->loadResult ();
			// Modules that match mobile positions AND have #__rvzr_modules record = 1 or are not published or not enabled
			if($this->_db->getErrorNum()) {
				throw new ResponsivizerException(JText::_('COM_RESPONSIVIZER_DBERROR_STATS') . $this->_db->getErrorMsg(), 'error');
			}
			
			// Stats for cached pages
			$query = $this->buildListQueryOfflineCache ('published', null);
			$this->_db->setQuery ( $query );
			$calculatedStats['totalCachedPages'] = $this->_db->loadResult ();
			// Modules that match mobile positions AND have #__rvzr_modules record = 1 or are not published or not enabled
			if($this->_db->getErrorNum()) {
				throw new ResponsivizerException(JText::_('COM_RESPONSIVIZER_DBERROR_STATS') . $this->_db->getErrorMsg(), 'error');
			}
			
			$query = $this->buildListQueryOfflineCache ('published', 1);
			$this->_db->setQuery ( $query );
			$calculatedStats['publishedCachedPages'] = $this->_db->loadResult ();
			// Modules that match mobile positions AND have #__rvzr_modules record = 1 or are not published or not enabled
			if($this->_db->getErrorNum()) {
				throw new ResponsivizerException(JText::_('COM_RESPONSIVIZER_DBERROR_STATS') . $this->_db->getErrorMsg(), 'error');
			}
		} catch (ResponsivizerException $e) {
			$this->app->enqueueMessage($e->getMessage(), $e->getErrorLevel());
			$calculatedStats = array();
		} catch (Exception $e) {
			$rvzrException = new ResponsivizerException($e->getMessage(), 'error');
			$this->app->enqueueMessage($rvzrException->getMessage(), $rvzrException->getErrorLevel());
			$calculatedStats = array();
		}
		
		return $calculatedStats;
	}
	
	/**
	 * Get by remote server informations for new updates of this extension
	 *
	 * @access public
	 * @return mixed An object json decoded from server if update information retrieved correctly otherwise false
	 */
	public function getUpdates(IResponsivizerHttp $httpClient) {
		// Updates server remote URI
		$option = $this->getState('option');
		if(!$option) {
			return false;
		}
		$url = SERVER_REMOTE_URI . $option . UPDATES_FORMAT;
		
		// Try to get informations
		try {
			$response = $httpClient->get($url)->body;
			if($response) {
				$decodedUpdateInfos = json_decode($response);
			}
			return $decodedUpdateInfos;
		} catch(ResponsivizerException $e) {
			return false;
		}  catch(Exception $e) {
			return false;
		}
	}
}