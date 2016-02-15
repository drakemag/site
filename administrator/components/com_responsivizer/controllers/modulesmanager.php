<?php
// namespace administrator\components\com_responsivizer\controllers;
/**
 * @package RESPONSIVIZER::MODULESMANAGER::administrator::components::com_responsivizer
 * @subpackage controllers
 * @author Joomla! Extensions Store
 * @copyright (C) 2014 - Joomla! Extensions Store
 * @license GNU/GPLv2 http://www.gnu.org/licenses/gpl-2.0.html  
 */
defined ( '_JEXEC' ) or die ( 'Restricted access' );

/**
 * Main controller manager
 * @package RESPONSIVIZER::MODULESMANAGER::administrator::components::com_responsivizer
 * @subpackage controllers
 * @since 2.0
 */
class ResponsivizerControllerModulesmanager extends ResponsivizerController {
	/**
	 * Default listEntities
	 * 
	 * @access public
	 * @param $cachable string
	 *       	 the view output will be cached
	 * @return void
	 */
	function display($cachable = false, $urlparams = false) {
		$option= $this->option;
		
		// Set model state
		$filterState = $this->getUserStateFromRequest ( "$option.modulesmanager.filterstate", 'filter_state', null );
		$filterPosition = $this->getUserStateFromRequest ( "$option.modulesmanager.filterpositions", 'filter_position', 0 );
		$filterMobileAvailable = $this->getUserStateFromRequest ( "$option.modulesmanager.filtermobileavailable", 'filter_mobile_available', null );
		$filterExclude = $this->getUserStateFromRequest ( "$option.modulesmanager.filterexclude", 'filter_exclude', null );
		$filterType = $this->getUserStateFromRequest ( "$option.modulesmanager.filtertypes", 'filter_type', null );
		$defaultModel = $this->setModelState('modulesmanager');
		
		// Set extended model state
		$defaultModel->setState('state', $filterState);
		$defaultModel->setState('position', $filterPosition);
		$defaultModel->setState('type', $filterType);
		$defaultModel->setState('mobileavailable', $filterMobileAvailable);
		$defaultModel->setState('exclude', $filterExclude);
		
		// Dependency injection setter on view/model for ResponsivizerRegistry instance
		$registry = new ResponsivizerRegistry();
		$view = $this->getView();
		$view->set('registry', $registry);
		 
		// Parent construction and view display
		parent::display($cachable);
	}
	  
	
	/**
	 * Edit entity
	 *
	 * @access public
	 * @return void
	 */
	public function editEntity() { 
		$this->app->input->set ( 'hidemainmenu', 1 );
		$idEntity = $this->app->input->getInt('id', 0);
		$model = $this->getModel();
		
		// Try to load record from model 
		if(!$record = $model->loadEntity($idEntity)) {
			// Model set exceptions for something gone wrong, so enqueue exceptions and levels on application object then set redirect and exit
			$modelExceptions = $model->getErrors();
			foreach ($modelExceptions as $exception) {
				$this->app->enqueueMessage($exception->getMessage(), $exception->getErrorLevel());
			}
			$this->setRedirect ( 'index.php?option=com_responsivizer&task=modulesmanager.display');
			return false;
		}
		
		// Additional model state setting
		$model->setState('option', $this->option);
		
		// Check out control on record
		if ($record->checked_out && $record->checked_out != $user->id) {
			$this->setRedirect ( 'index.php?option=com_responsivizer&task=modulesmanager.display', JTEXT::_('COM_RESPONSIVIZER_CHECKEDOUT_RECORD'), 'notice');
			return false;
		}
		
		// Get view and pushing model
		$view = $this->getView();
		$view->setModel ( $model, true );
		
		// Dependency injection setter on view/model for ResponsivizerRegistry instance
		$registry = new ResponsivizerRegistry();
		$view->set('registry', $registry);
		
		// Call edit view
		$view->editEntity($record); 
	}
  
	/**
	 * Manage entity apply/save after edit entity
	 *
	 * @access public
	 * @return void
	 */
	public function saveEntity() {
		$task = $this->task;
		$option = $this->option;
		
		//Load della  model e bind store
		$model = $this->getModel ();
		
		if(!$result = $model->storeEntity()) {
			// Model set exceptions for something gone wrong, so enqueue exceptions and levels on application object then set redirect and exit
			$modelException = $model->getError(null, false);
			$this->app->enqueueMessage($modelException->getMessage(), $modelException->getErrorLevel());
			$this->setRedirect ( 'index.php?option=com_responsivizer&task=modulesmanager.display', JText::_('COM_RESPONSIVIZER_ERROR_DUPLICATE_MODULE'));
			return false;
		}
		
		$redirects = array('task'=>'display' . $id, 'msgsufix'=>'_DUPLICATE_MODULE');
		$msg = 'COM_RESPONSIVIZER_SUCCESS' . $redirects['msgsufix'];
		$controllerTask = $redirects['task'];
	
		$this->setRedirect ( "index.php?option=$option&task=modulesmanager.$controllerTask", JTEXT::_($msg));
	}
	
	/**
	 * Manage cancel edit for entity and unlock record checked out
	 *
	 * @access public
	 * @return void
	 */
	public function cancelEntity() { 
		$id = $this->app->input->getInt('id');
		$option = $this->option;
		$this->setRedirect ( "index.php?option=$option&task=modulesmanager.display", JTEXT::_('COM_RESPONSIVIZER_CANCELED_OPERATION') );
	}
	
	/**
	 * Publishing entities
	 *
	 * @access public
	 * @return void
	 */
	public function publishEntities() {
		$cid = $this->app->input->get ( 'cid', array (), 'array' );
		$idEntity = (int) $cid[0];
		$option = $this->option;
	
		$model = $this->getModel();
	
		if(! $model->publishEntities($idEntity, $this->task)) {
			// Model set exceptions for something gone wrong, so enqueue exceptions and levels on application object then set redirect and exit
			$modelException = $model->getError(null, false);
			$this->app->enqueueMessage($modelException->getMessage(), $modelException->getErrorLevel());
			$this->setRedirect ( "index.php?option=$option&task=modulesmanager.display", JText::_('COM_RESPONSIVIZER_ERROR_STATE_CHANGE'));
			return false;
		}
		$this->setRedirect( "index.php?option=$option&task=modulesmanager.display",  JTEXT::_('COM_RESPONSIVIZER_SUCCESS_STATE_CHANGE'));
	}
	
	/**
	 * Manage cancel edit for entity and unlock record checked out
	 *
	 * @access public
	 * @return void
	 */
	public function editPositionsMatch() {
		$this->app->input->set ( 'hidemainmenu', 1 );
		$idEntity = $this->app->input->getInt('id', 0);
		$model = $this->getModel();
		
		// Additional model state setting
		$model->setState('option', $this->option);
		
		// Get view and pushing model
		$view = $this->getView();
		$view->setModel ( $model, true );
		
		// Dependency injection setter on view/model for ResponsivizerRegistry instance
		$registry = new ResponsivizerRegistry();
		$registry->loadFile(JPATH_RVZR_DBPOSITIONS, 'json');
		$record = $registry->toArray();
		
		// Call edit view
		$view->showPositionsMatchForm($record); 
	}
	
	/**
	 * Manage cancel edit for entity and unlock record checked out
	 *
	 * @access public
	 * @return void
	 */
	public function savePositionsMatch() {
		$option = $this->option;
		
		//Load della  model e bind store
		$model = $this->getModel ();
		// Dependency injection setter on view/model for ResponsivizerRegistry instance
		$registry = new ResponsivizerRegistry();
		if(!$result = $model->storePositionsDB($registry)) {
			// Model set exceptions for something gone wrong, so enqueue exceptions and levels on application object then set redirect and exit
			$modelException = $model->getError(null, false);
			$this->app->enqueueMessage($modelException->getMessage(), $modelException->getErrorLevel());
			$this->setRedirect ( 'index.php?option=com_responsivizer&task=modulesmanager.editPositionsMatch', JText::_('COM_RESPONSIVIZER_ERROR_EDIT_POSITIONS_ASSOCIATIONS'));
			return false;
		}
		
		$redirects = array('task'=>'editPositionsMatch', 'msgsufix'=>'_EDIT_POSITIONS_ASSOCIATIONS');
		$msg = 'COM_RESPONSIVIZER_SUCCESS' . $redirects['msgsufix'];
		$controllerTask = $redirects['task'];
		
		$this->setRedirect ( "index.php?option=$option&task=modulesmanager.$controllerTask", JTEXT::_($msg));
	}
	
	/**
	 * Class Constructor
	 * 
	 * @access public
	 * @return Object&
	 */
	public function __construct($config = array()) {
		// Parent contructor
		parent::__construct ( $config );

		// Registers tasks
		$this->registerTask ( 'applyEntity', 'saveEntity' );
		$this->registerTask ( 'unpublish', 'publishEntities' );
		$this->registerTask ( 'publish', 'publishEntities' );
	}
}