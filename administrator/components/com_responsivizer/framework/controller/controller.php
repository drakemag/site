<?php
// namespace components\com_responsivizer\libraries\framework\controller;
/**
 *
 * @package RESPONSIVIZER::administrator::components::com_responsivizer
 * @subpackage framework
 * @subpackage controller
 * @author Joomla! Extensions Store
 * @copyright (C) 2014 - Joomla! Extensions Store
 * @license GNU/GPLv2 http://www.gnu.org/licenses/gpl-2.0.html  
 */
defined ( '_JEXEC' ) or die ( 'Restricted access' );
jimport ( 'joomla.application.component.controller' );

/**
 * Base controller class
 * 
 * @package RESPONSIVIZER::administrator::components::com_responsivizer
 * @subpackage framework
 * @subpackage controller
 * @since 1.0
 */
class ResponsivizerController extends JControllerLegacy {
	/**
	 * Dispatch option
	 *
	 * @access protected
	 * @var string
	 */
	protected $option;
	
	/**
	 * Main application reference
	 *
	 * @access protected
	 * @var Object
	 */
	protected $app;
	
	/**
	 * User object for ACL authorise check
	 *
	 * @access protected
	 * @var Object
	 */
	protected $user;
	
	/**
	 * Document object, needed by controllers to instantiate
	 * the right view object based on document format
	 *
	 * @access protected
	 * @var Object
	 */
	protected $document;
	
	/**
	 * Method override to check if you can add a new record.
	 *
	 * @param   array  $data  An array of input data.
	 *
	 * @return  boolean
	 *
	 * @since   1.6
	 */
	protected function allowAdmin($assetName) {
		// Initialise variables.
		$user = JFactory::getUser();
		$allow = JFactory::getUser()->authorise('core.admin', $assetName);
			
		return $allow;
	}
	
	/**
	 * Method override to check if you can add a new record.
	 *
	 * @param   array  $data  An array of input data.
	 *
	 * @return  boolean
	 *
	 * @since   1.6
	 */
	protected function allowAdd($assetName) {
		// Initialise variables.
		$user = JFactory::getUser();
		$allow = JFactory::getUser()->authorise('core.create', $assetName);
			
		return $allow;
	}
	
	/**
	 * Method override to check if you can edit an existing record.
	 *
	 * @param   array   $data  An array of input data.
	 * @param   string  $key   The name of the key for the primary key.
	 *
	 * @return  boolean
	 *
	 * @since   1.6
	 */
	protected function allowEdit($assetName) {
		// Initialise variables.
		$user = JFactory::getUser();
		$allow = JFactory::getUser()->authorise('core.edit', $assetName);
			
		return $allow;
	}
	
	/**
	 * Method override to check if you can edit an existing record.
	 *
	 * @param   array   $data  An array of input data.
	 * @param   string  $key   The name of the key for the primary key.
	 *
	 * @return  boolean
	 *
	 * @since   1.6
	 */
	protected function allowEditState($assetName) {
		// Initialise variables.
		$user = JFactory::getUser();
		$allow = JFactory::getUser()->authorise('core.edit.state', $assetName);
			
		return $allow;
	}
	
	/**
	 * Method override to check if you can edit an existing record.
	 *
	 * @param   array   $data  An array of input data.
	 * @param   string  $key   The name of the key for the primary key.
	 *
	 * @return  boolean
	 *
	 * @since   1.6
	 */
	protected function allowDelete($assetName) {
		// Initialise variables.
		$user = JFactory::getUser();
		$allow = JFactory::getUser()->authorise('core.delete', $assetName);
			
		return $allow;
	}
		
	/**
	 * Set model state from session
	 * @access protected
	 * @param string $scope
	 * @return void
	 */
	protected function setModelState($scope = 'default') {
		// App instance
		$app = JFactory::getApplication();
		$option = $this->app->input->get('option');

		$search = $this->getUserStateFromRequest("$option.$scope.searchword", 'search', null);
		
		$limit = $this->getUserStateFromRequest("$option.$scope.limit", 'limit', $app->getCfg('list_limit'), 'int');
		$limitStart = $this->getUserStateFromRequest("$option.$scope.limitstart", 'limitstart', 0, 'int');
		// Round del limit al change proof
		$limitStart = ($limit != 0 ? (floor($limitStart / $limit) * $limit) : 0);
		
		$filter_order = $this->getUserStateFromRequest("$option.$scope.filter_order", 'filter_order', 's.ordering', 'cmd');
		$filter_order_Dir = $this->getUserStateFromRequest("$option.$scope.filter_order_Dir", 'filter_order_Dir', 'asc', 'word');

		// Get default model
		$defaultModel = $this->getModel();

		// Set model state
		$defaultModel->setState('option', $option);
		$defaultModel->setState('limit', $limit);
		$defaultModel->setState('limitstart', $limitStart);
		$defaultModel->setState('searchword', $search);
		$defaultModel->setState('order', $filter_order);
		$defaultModel->setState('order_dir', $filter_order_Dir);

		return $defaultModel;
	}

	/**
	 * Gets the value of a user state variable and sets it in the session
	 *
	 * This is the same as the method in JApplication except that this also can optionally
	 * force you back to the first page when a filter has changed
	 *
	 * @param   string   $key        The key of the user state variable.
	 * @param   string   $request    The name of the variable passed in a request.
	 * @param   string   $default    The default value for the variable if not found. Optional.
	 * @param   string   $type       Filter for the variable, for valid values see {@link JFilterInput::clean()}. Optional.
	 * @param   boolean  $resetPage  If true, the limitstart in request is set to zero
	 *
	 * @return  The request user state.
	 * @since   2.0
	 */
	protected function getUserStateFromRequest($key, $request, $default = null, $type = 'none', $resetPage = true) {
		$app = JFactory::getApplication();
		$old_state = $app->getUserState($key);
		$cur_state = (!is_null($old_state)) ? $old_state : $default;
		$new_state = $this->app->input->get ( $request, null, $type );

		if (($cur_state != $new_state) && ($resetPage)) {
			$this->app->input->set ( 'limitstart', 0 );
		}

		// Save the new value only if it is set in this request.
		if ($new_state !== null) {
			$app->setUserState($key, $new_state);
		} else {
			$new_state = $cur_state;
		}

		return $new_state;
	}
	 
	/**
	 * Typical view method for MVC based architecture
	 *
	 * This function is provide as a default implementation, in most cases
	 * you will need to override it in your own controllers.
	 *
	 * @access public
	 * @param $cachable string
	 *       	 the view output will be cached
	 * @since 1.5
	 */
	public function display($cachable = false, $urlparams = false) {
		$document = JFactory::getDocument ();
		
		$viewType = $document->getType ();
		$coreName = $this->getNames ();
		$viewLayout = $this->app->input->get ( 'layout', 'default' );
		
		$view =  $this->getView ( $coreName, $viewType, '', array ('base_path' => $this->basePath ) );
		
		// Get/Create the model
		if ($model =  $this->getModel ( $coreName )) {
			// Push the model into the view (as default)
			$view->setModel ( $model, true );
		}
		
		// Set the layout
		$view->setLayout ( $viewLayout );
		$view->display ();
	}
	/**
	 * Method to get the controller name
	 *
	 * The dispatcher name by default parsed using the classname, or it can be
	 * set
	 * by passing a $config['name'] in the class constructor
	 *
	 * @access public
	 * @return string The name of the dispatcher
	 * @since 1.5
	 */
	public function getNames() {
		$name = $this->name;
		
		if (empty ( $name )) {
			$r = null;
			if (! preg_match ( '/(.*)Controller(.*)/i', get_class ( $this ), $r )) {
				throw new Exception(JText::_('JLIB_APPLICATION_ERROR_CONTROLLER_GET_NAME'), 500);
			}
			$name = ($r [2]);
		}
		
		return ($name);
	}
	
	/**
	 * Method to get the controller name
	 *
	 * The dispatcher name by default parsed using the classname, or it can be
	 * set
	 * by passing a $config['name'] in the class constructor
	 *
	 * @access public
	 * @return string The name of the dispatcher
	 * @since 1.5
	 */
	public function getName() {
		
		$r = null;
		if (! preg_match ( '/(.*)Controller/i', get_class ( $this ), $r )) {
			throw new Exception(JText::_('JLIB_APPLICATION_ERROR_CONTROLLER_GET_NAME'), 500);
		}
		$name = ($r [1]);
		
		return ($name);
	}
	
	/**
	 * Method to get a reference to the current view and load it if necessary.
	 *
	 * @access public
	 * @param
	 *       	 string	The view name. Optional, defaults to the controller
	 *       	 name.
	 * @param
	 *       	 string	The view type. Optional.
	 * @param
	 *       	 string	The class prefix. Optional.
	 * @param
	 *       	 array	Configuration array for view. Optional.
	 * @return object to the view or an error.
	 * @since 1.5
	 */
	public function getView($name = null, $type = 'html', $prefix = null, $config = array()) {
		static $views;
		
		if (! isset ( $views )) {
			$views = array ();
		}
		
		if (empty ( $name )) {
			$name = $this->getNames ();
		}
		
		if (empty ( $prefix )) {
			$prefix = $this->getName () . 'View';
		}
		
		if (empty ( $views [$name] )) {
			if ($view = $this->createView ( $name, $prefix, $type, $config )) {
				$views [$name] =  $view;
			} else {
				throw new Exception(JText::sprintf('JLIB_APPLICATION_ERROR_VIEW_NOT_FOUND', $name, $type, $prefix), 500);
			}
		}
		
		return $views [$name];
	}
	
	/**
	 * Method to get a model object, loading it if required.
	 *
	 * @access public
	 * @param
	 *       	 string	The model name. Optional.
	 * @param
	 *       	 string	The class prefix. Optional.
	 * @param
	 *       	 array	Configuration array for model. Optional.
	 * @return object model.
	 * @since 1.5
	 */
	public function getModel($name = '', $prefix = '', $config = array()) {
		static $models = array();
		
		if (empty ( $name )) {
			$name = $this->getNames ();
		}
		
		if (empty ( $prefix )) {
			$prefix = $this->getName () . 'Model';
		}
		
		if(array_key_exists($name, $models)) {
			return $models[$name];
		}
		
		if ($model = $this->createModel ( $name, $prefix, $config )) {
			$models[$name] = $model;
			// task is a reserved state
			$model->setState ( 'task', $this->task );
			
			// Lets get the application object and set menu information if its
			// available
			$app = JFactory::getApplication ();
			$menu = $app->getMenu ();
			if (is_object ( $menu )) {
				if ($item = $menu->getActive ()) {
					$params = $menu->getParams ( $item->id );
					// Set Default State Data
					$model->setState ( 'parameters.menu', $params );
				}
			}
		}
		return $model;
	}
	
	/**
	 * Constructor.
	 *
	 * @access protected
	 * @param
	 *       	 array An optional associative array of configuration settings.
	 *       	 Recognized key values include 'name', 'default_task',
	 *       	 'model_path', and
	 *       	 'view_path' (this list is not meant to be comprehensive).
	 * @since 1.5
	 */
	public function __construct($config = array()) {
		// Initialize private variables
		$this->redirect = null;
		$this->message = null;
		$this->messageType = 'message';
		$this->taskMap = array ();
		$this->methods = array ();
		$this->data = array ();
		$this->app = JFactory::getApplication ();
		$this->user = JFactory::getUser ();
		$this->document = JFactory::getDocument();
		$this->option = $this->app->input->get ( 'option' );
	  
		// Get the methods only for the final controller class
		$thisMethods = get_class_methods ( get_class ( $this ) );
		$baseMethods = get_class_methods ( 'ResponsivizerController' );
		$methods = array_diff ( $thisMethods, $baseMethods );
		$baseMethods = get_class_methods ( 'JControllerLegacy' );
		$methods = array_diff ( $methods, $baseMethods );
	
		// Add default display method
		$methods [] = 'display';
	
		// Iterate through methods and map tasks
		foreach ( $methods as $method ) {
			if (substr ( $method, 0, 1 ) != '_') {
				$this->methods [] = strtolower ( $method );
				// auto register public methods as tasks
				$this->taskMap [strtolower ( $method )] = $method;
			}
		}
	
		// set the view name
		if (empty ( $this->name )) {
			if (array_key_exists ( 'name', $config )) {
				$this->name = $config ['name'];
			} else {
				$this->name = $this->getNames ();
			}
		}
	
		// Set a base path for use by the controller
		if (array_key_exists ( 'base_path', $config )) {
			$this->basePath = $config ['base_path'];
		} else {
			$this->basePath = JPATH_COMPONENT;
		}
	
		// If the default task is set, register it as such
		if (array_key_exists ( 'default_task', $config )) {
			$this->registerDefaultTask ( $config ['default_task'] );
		} else {
			$this->registerDefaultTask ( 'display' );
		}
	
		// set the default model search path
		if (array_key_exists ( 'model_path', $config )) {
			// user-defined dirs
			$this->addModelPath ( $config ['model_path'] );
		} else {
			$this->addModelPath ( $this->basePath . '/models' );
		}
	
		// set the default view search path
		if (array_key_exists ( 'view_path', $config )) {
			// user-defined dirs
			$this->setPath ( 'view', $config ['view_path'] );
		} else {
			$this->setPath ( 'view', $this->basePath . '/views' );
		}
	}
}