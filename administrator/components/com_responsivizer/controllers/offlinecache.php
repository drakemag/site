<?php
// namespace administrator\components\com_responsivizer\controllers;
/**
 * @package RESPONSIVIZER::SOURCES::administrator::components::com_responsivizer
 * @subpackage controllers
 * @author Joomla! Extensions Store
 * @copyright (C) 2014 - Joomla! Extensions Store
 * @license GNU/GPLv2 http://www.gnu.org/licenses/gpl-2.0.html  
 */
defined ( '_JEXEC' ) or die ( 'Restricted access' );

/**
 * Main controller manager
 * @package RESPONSIVIZER::SOURCES::administrator::components::com_responsivizer
 * @subpackage controllers
 * @since 1.0
 */
class ResponsivizerControllerOfflinecache extends ResponsivizerController {
	/**
	 * Default listEntities
	 * 
	 * @access public
	 * @param $cachable string
	 *       	 the view output will be cached
	 * @return void
	 */
	function display($cachable = false, $urlparams = false) {
		// Set model state
		$option= $this->option; 

		$filter_state = $this->getUserStateFromRequest ( "$option.offlinecache.filterstate", 'filter_state', null );
		$filter_order = $this->getUserStateFromRequest ( "$option.offlinecache.filter_order", 'filter_order', 'pagetitle', 'cmd' );
		$defaultModel = $this->setModelState('offline');
		
		// Set model state
		$defaultModel->setState('state', $filter_state); 
		$defaultModel->setState('order', $filter_order);  
		
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
		$cid = $this->app->input->get ( 'cid', array (0), 'array' );;
		$idEntity = (int) $cid[0];
		$user = JFactory::getUser();
		$model = $this->getModel();
		
		// Try to load record from model 
		if(!$record = $model->loadEntity($idEntity)) {
			// Model set exceptions for something gone wrong, so enqueue exceptions and levels on application object then set redirect and exit
			$modelExceptions = $model->getErrors();
			foreach ($modelExceptions as $exception) {
				$this->app->enqueueMessage($exception->getMessage(), $exception->getErrorLevel());
			}
			$this->setRedirect ( 'index.php?option=com_responsivizer&task=offlinecache.display');
			return false;
		}
		
		// Additional model state setting
		$model->setState('option', $this->option);
		
		// Check out control on record
		if ($record->checked_out && $record->checked_out != $user->id) {
			$this->setRedirect ( 'index.php?option=com_responsivizer&task=offlinecache.display', JTEXT::_('COM_RESPONSIVIZER_CHECKEDOUT_RECORD'), 'notice');
			return false;
		}
		
		// Check out del record
		if ($record->id) {
			$record->checkout ( $user->id );
		}
		
		// Get view and pushing model
		$view = $this->getView();
		$view->setModel ( $model, true );
		
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
		$hasChanged = $this->app->input->getInt('haschanged', 0);
		$option = $this->option;
		
		//Load della  model e bind store
		$model = $this->getModel ();
		$model->setState('hasChanged', $hasChanged);
		
		// Instance and dependency injection on 
		$HTTPClient = new ResponsivizerHttp();
		
		if(!$result = $model->storeEntity($HTTPClient)) {
			// Model set exceptions for something gone wrong, so enqueue exceptions and levels on application object then set redirect and exit
			$modelException = $model->getError(null, false);
			$this->app->enqueueMessage($modelException->getMessage(), $modelException->getErrorLevel());
			$this->setRedirect ( "index.php?option=$option&task=offlinecache.editEntity&cid[]=" . $this->app->input->getInt('id'), JText::_('COM_RESPONSIVIZER_ERROR_SAVING'));
			return false;
		}

		// Security safe if not model record id detected
		if(!$id = $result->id) {
			$id = $this->app->input->getInt('id');
		}
		
		$redirects = $task == 'saveEntity' ? array('task'=>'display', 'msgsufix'=>'_SAVING') : array('task'=>'editEntity&cid[]=' . $id, 'msgsufix'=>'_APPLY');
		$msg = 'COM_RESPONSIVIZER_SUCCESS' . $redirects['msgsufix'];
		$controllerTask = $redirects['task'];
	
		$this->setRedirect ( "index.php?option=$option&task=offlinecache.$controllerTask", JTEXT::_($msg));
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
		//Load della  model e checkin before exit
		$model = $this->getModel ( );
		
		if(!$model->cancelEntity($id)) {
			// Model set exceptions for something gone wrong, so enqueue exceptions and levels on application object then set redirect and exit
			$modelException = $model->getError(null, false);
			$this->app->enqueueMessage($modelException->getMessage(), $modelException->getErrorLevel());
		}
		 
		$this->setRedirect ( "index.php?option=$option&task=offlinecache.display", JTEXT::_('COM_RESPONSIVIZER_CANCELED_OPERATION') );
	}
	
	/**
	 * Delete a db table entity
	 *
	 * @access public
	 * @return void
	 */
	public function deleteEntity() {
		$cids = $this->app->input->get ( 'cid', array (), 'array' );;
		$option = $this->option;

		//Load della  model e checkin before exit
		$model = $this->getModel ( );
		
		if(!$model->deleteEntity($cids)) {
			// Model set exceptions for something gone wrong, so enqueue exceptions and levels on application object then set redirect and exit
			$modelException = $model->getError(null, false);
			$this->app->enqueueMessage($modelException->getMessage(), $modelException->getErrorLevel());
			$this->setRedirect ( "index.php?option=$option&task=offlinecache.display", JText::_('COM_RESPONSIVIZER_ERROR_DELETE'));
			return false;
		}
	
		$this->setRedirect ( "index.php?option=$option&task=offlinecache.display", JTEXT::_('COM_RESPONSIVIZER_SUCCESS_DELETE') );
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
			$this->setRedirect ( "index.php?option=$option&task=offlinecache.display", JText::_('COM_RESPONSIVIZER_ERROR_STATE_CHANGE'));
			return false;
		}
		
		$this->setRedirect( "index.php?option=$option&task=offlinecache.display",  JTEXT::_('COM_RESPONSIVIZER_SUCCESS_STATE_CHANGE'));
	}
	
	/**
	 * Class Constructor
	 * 
	 * @access public
	 * @return Object&
	 */
	public function __construct($config = array()) {
		parent::__construct ( $config );
		// Register Extra tasks
		$this->registerTask ( 'moveorder_up', 'moveOrder' );
		$this->registerTask ( 'moveorder_down', 'moveOrder' );
		$this->registerTask ( 'applyEntity', 'saveEntity' );
		$this->registerTask ( 'unpublish', 'publishEntities' );
		$this->registerTask ( 'publish', 'publishEntities' );
	}
}