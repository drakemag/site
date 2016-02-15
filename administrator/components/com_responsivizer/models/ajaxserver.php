<?php
//namespace components\com_responsivizer\models; 
/** 
 * @package RESPONSIVIZER::AJAXSERVER::components::com_responsivizer 
 * @subpackage models
 * @author Joomla! Extensions Store
 * @copyright (C)2014 Joomla! Extensions Store
 * @license GNU/GPLv2 http://www.gnu.org/licenses/gpl-2.0.html  
 */
defined('_JEXEC') or die('Restricted access');
jimport('joomla.filesystem.file');

/**
 * Ajax Server model responsibilities
 *
 * @package RESPONSIVIZER::AJAXSERVER::components::com_responsivizer  
 * @subpackage models
 * @since 1.0
 */
interface IAjaxserverModel {
	public function loadAjaxEntity($id, $param, $DIModels) ;
}

/** 
 * Classe che gestisce il recupero dei dati per il POST HTTP
 * @package RESPONSIVIZER::AJAXSERVER::components::com_responsivizer  
 * @subpackage models
 * @since 1.0
 */
class ResponsivizerModelAjaxserver extends ResponsivizerModel implements IAjaxserverModel {
	/**
	 * Manage store/update Pingomatic entity record
	 * 
	 * @access private
	 * @param $idModule
	 * @param $additionalModels
	 *
	 * @Return array
	 */
	private function mobilePublishModules($params, $additionalModels = null) {
		// Response JSON object
		$response = new stdClass();

		// Store on ORM Table
		$table = $this->getTable('Modulesmanager');
		$table->load((int)$params->id);
		$table->mobile_exclude = (int)$params->action;
		
		try {
			if (!$table->store(false, $params->id)) {
				throw new ResponsivizerException($table->getError(), 'error');
			}
		} catch (ResponsivizerException $e) {
			$response->result = false;
			$response->errorMsg = $e->getMessage();
			return $response;
		} catch (Exception $e) {
			$rvzrException = new ResponsivizerException($e->getMessage(), 'error');
			$response->result = false;
			$response->errorMsg = $rvzrException->getMessage();
			return $response;
		}

		// Search for a module instance already assigned to mobile
		$registry = new ResponsivizerRegistry();
		$registry->loadFile(JPATH_RVZR_DBPOSITIONS, 'json');
		$dbArray = $registry->toArray();
		$totalPositions = $registry->toFlatArray($dbArray);
		$mobilePositions = $registry->toDatabaseString($totalPositions);
		$query = "SELECT s.position, m.id AS has_mobile" .
				"\n FROM `#__modules` AS s" .
				"\n LEFT JOIN #__modules AS m ON m.title LIKE CONCAT('%', s.title, '%') AND m.id != s.id AND (m.published IN (0, 1)) AND m.client_id = 0" .
				"\n AND m.position IN(" . $mobilePositions . ')' .
				"\n WHERE s.id = " . (int)$params->id;
		$moduleObject = $this->_db->setQuery($query)->loadObject();
		$modulePosition = $moduleObject->position;
		$hasMobile = $moduleObject->has_mobile;
		
		// Manage exceptions from DB Model and return to JS domain
		$response->result = true;
		$response->has_mobile = (bool)$hasMobile;
		$response->id = (int)$params->id;
		
		// Check if sent module id position match a native template position and is native mobile
		$response->native_mobile = (bool)(array_key_exists($modulePosition, $dbArray));

		return $response;
	}
	
	/**
	 * Manage store/update Pingomatic entity record
	 *
	 * @access private
	 * @param $idModule
	 * @param $additionalModels
	 *
	 * @Return array
	 */
	private function changeStateModules($params, $additionalModels = null) {
		// Response JSON object
		$response = new stdClass();
	
		// Store on ORM Table
		$table = $this->getTable('Modulesmanager');
		$table->load((int)$params->id);
		$table->{$params->field} = (int)$params->value;
	
		try {
			if (!$table->store(false, $params->id)) {
				throw new ResponsivizerException($table->getError(), 'error');
			}
		} catch (ResponsivizerException $e) {
			$response->result = false;
			$response->errorMsg = $e->getMessage();
			return $response;
		} catch (Exception $e) {
			$rvzrException = new ResponsivizerException($e->getMessage(), 'error');
			$response->result = false;
			$response->errorMsg = $rvzrException->getMessage();
			return $response;
		}

		$response->result = true;
		return $response;
	}

	/**
	 * Mimic an entities list, as ajax calls arrive are redirected to loadEntity public responsibility to get handled
	 * by specific subtask. Responses are returned to controller and encoded from view over HTTP to JS client
	 * 
	 * @access public 
	 * @param string $id Rappresenta l'op da eseguire tra le private properties
	 * @param mixed $param Parametri da passare al private handler
	 * @param Object[]& $DIModels
	 * @return Object& $utenteSelezionato
	 */
	public function loadAjaxEntity($id, $param , $DIModels) {
		//Delega la private functions delegata dalla richiesta sulla entity
		$response = $this->$id($param, $DIModels);

		return $response;
	}
}
