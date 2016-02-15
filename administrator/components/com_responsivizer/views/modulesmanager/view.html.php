<?php
// namespace administrator\components\com_responsivizer\views\modulesmanager;
/**
 * @package RESPONSIVIZER::SOURCES::administrator::components::com_responsivizer
 * @subpackage views
 * @subpackage sources
 * @author Joomla! Extensions Store
 * @copyright (C) 2014 - Joomla! Extensions Store
 * @license GNU/GPLv2 http://www.gnu.org/licenses/gpl-2.0.html  
 */
defined ( '_JEXEC' ) or die ( 'Restricted access' );
 
/**
 * @package RESPONSIVIZER::MODULESMANAGER::administrator::components::com_responsivizer
 * @subpackage views
 * @subpackage modulesmanager
 * @since 2.0
 */
class ResponsivizerViewModulesmanager extends ResponsivizerView {
	/**
	 * Add the page title and toolbar for listEntities
	 */
	protected function addDisplayToolbar() {
		$doc = JFactory::getDocument();
		$doc->addStyleDeclaration('.icon-48-responsivizer{background-image:url("components/com_responsivizer/images/icon-48-modules_manager.png")}');
		JToolBarHelper::title( JText::_( 'COM_RESPONSIVIZER_MODULES_MANAGER_TOOLBAR' ), 'responsivizer' );
		JToolBarHelper::custom('modulesmanager.editPositionsMatch', 'list-view', 'list-view', 'COM_RESPONSIVIZER_EDIT_POSITIONS_MATCH', false);
		JToolBarHelper::custom('cpanel.display', 'home', 'home', 'COM_RESPONSIVIZER_CPANEL', false);
	}
	
	/**
	 * Add the page title and toolbar for editEntity
	 */
	protected function addEditEntityToolbar() {
		$doc = JFactory::getDocument();
		$doc->addStyleDeclaration('.icon-48-responsivizer{background-image:url("components/com_responsivizer/images/icon-48-modules_manager.png")}');
		JToolBarHelper::title( JText::_( 'COM_RESPONSIVIZER_MODULES_COPY_TOOLBAR' ), 'responsivizer' );
		JToolBarHelper::apply( 'modulesmanager.applyEntity', 'COM_RESPONSIVIZER_CONFIRM');
		JToolBarHelper::custom('modulesmanager.cancelEntity', 'cancel', 'cancel', 'JCANCEL', false);
	}
	
	/**
	 * Add the page title and toolbar for showPositionsMatchForm
	 */
	protected function addShowPositionsMatchForm() {
		$doc = JFactory::getDocument();
		$doc->addStyleDeclaration('.icon-48-responsivizer{background-image:url("components/com_responsivizer/images/icon-48-modules_manager.png")}');
		$doc->addStyleDeclaration('.icon-32-cpanel{background-image:url("components/com_responsivizer/images/icon-32-cpanel.png")}');
		JToolBarHelper::title( JText::_( 'COM_RESPONSIVIZER_EDIT_POSITIONS_MATCH_TOOLBAR' ), 'responsivizer' );
		JToolBarHelper::apply( 'modulesmanager.savePositionsMatch', 'COM_RESPONSIVIZER_SAVEFILE');
		JToolBarHelper::custom('modulesmanager.cancelEntity', 'cancel', 'cancel', 'JCANCEL', false);
		JToolBarHelper::custom('cpanel.display', 'home', 'home', 'COM_RESPONSIVIZER_CPANEL', false);
	}
	
	/**
	 * Default display listEntities
	 *        	
	 * @access public
	 * @param string $tpl
	 * @return void
	 */
	public function display($tpl = null) {
		// Get main records
		$rows = $this->getModel()->getModulesData($this->get('registry'));
		$filters = $this->get ( 'Filters' );
		$total = $this->get ( 'Total' );
		
		$doc = JFactory::getDocument();
		$this->loadJQuery($doc);
		$this->loadBootstrap($doc);
		// Load override styles
		$doc->addStylesheet ( JURI::root ( true ) . '/administrator/components/com_responsivizer/assets/css/modulesmanager.css' );
		$doc->addStylesheet ( JURI::root ( true ) . '/administrator/components/com_responsivizer/assets/css/jquery.fancybox.css' );
		
		// Inject js translations
		$translations = array('COM_RESPONSIVIZER_MOBILE_MODULE_EXCLUDED',
							  'COM_RESPONSIVIZER_MOBILE_MODULE_INCLUDED',
							  'COM_RESPONSIVIZER_MOBILE_EXCLUDE_ERROR',
							  'COM_RESPONSIVIZER_MAKE_MOBILE_DESC',
							  'COM_RESPONSIVIZER_MAKE_EXISTS_MOBILE_DESC',
							  'COM_RESPONSIVIZER_MOBILE_AVAILABLE_MODULE',
							  'COM_RESPONSIVIZER_MOBILE_NOAVAILABLE_MODULE',
							  'COM_RESPONSIVIZER_SHOW_TITLE_ENABLED',
							  'COM_RESPONSIVIZER_SHOW_TITLE_DISABLED',
							  'COM_RESPONSIVIZER_SHOW_BG_ENABLED',
							  'COM_RESPONSIVIZER_SHOW_BG_DISABLED',
							  'COM_RESPONSIVIZER_CHANGE_STATE_ERROR',
							  'COM_RESPONSIVIZER_SWITCH_TO_JOOMLA_MM');
		$this->injectJsTranslations($translations, $doc);
		
		// Load specific JS App
		$doc->addScript ( JURI::root ( true ) . '/administrator/components/com_responsivizer/js/modulesmanager.js' );
		$doc->addCustomTag ('<script type="text/javascript" src="' . JURI::root ( true ) . '/administrator/components/com_responsivizer/js/jquery.fancybox.pack.js' . '"></script>');
		
		$orders = array ();
		$orders ['order'] = $this->getModel ()->getState ( 'order' );
		$orders ['order_Dir'] = $this->getModel ()->getState ( 'order_dir' );
		// Pagination view object model state populated
		$pagination = new JPagination ( $total, $this->getModel ()->getState ( 'limitstart' ), $this->getModel ()->getState ( 'limit' ) );
		
		$this->user = JFactory::getUser ();
		$this->pagination = $pagination;
		$this->searchword = $this->getModel ()->getState ( 'searchword' );
		$this->filters = $filters;
		$this->orders = $orders;
		$this->items = $rows;
		$this->option = $this->getModel ()->getState ( 'option' );
		$this->dbPositions = $this->get('registry')->toArray();
		
		// Add toolbar
		$this->addDisplayToolbar();
		
		parent::display ( 'list' );
	}
	
	/**
	 * Edit entity view
	 *
	 * @access public
	 * @param Object& $row the item to edit
	 * @return void
	 */
	public function editEntity(&$row) {
		// Load JS Client App dependencies
		$doc = JFactory::getDocument();
		$this->loadJQuery($doc);
		$this->loadBootstrap($doc);
		$this->loadValidation($doc);
		$doc->addStylesheet ( JURI::root ( true ) . '/administrator/components/com_responsivizer/assets/css/modulesmanager.css' );
		
		// Inject js translations
		$translations = array('COM_RESPONSIVIZER_SWITCH_TO_JOOMLA_MM');
		$this->injectJsTranslations($translations, $doc);
		
		// Load specific JS App
		$doc->addScript ( JURI::root ( true ) . '/administrator/components/com_responsivizer/js/modulesmanager.js' );
		$doc->addScriptDeclaration("
					Joomla.submitbutton = function(pressbutton) {
						jQuery('#adminForm').validation();
				
						if (pressbutton == 'modulesmanager.cancelEntity') {
							Joomla.submitform( pressbutton );
							return true;
						}
		
						if(jQuery('#adminForm').validate()) {
							Joomla.submitform( pressbutton );
							return true;
						}
						return false;
					}
				");
		$lists = $this->getModel()->getCopyLists($this->get('registry'));
		$this->option = $this->getModel ()->getState ( 'option' );
		$this->record = $row;
		$this->lists = $lists;
		
		// Add toolbar
		$this->addEditEntityToolbar();
		
		parent::display ( 'edit' );
	}
	
	/**
	 * Show editable form for positions match DB file
	 *
	 * @access public
	 * @param array $rowFileData
	 * @return void
	 */
	public function showPositionsMatchForm($rowFileData) {
		// Load JS Client App dependencies
		$doc = JFactory::getDocument();
		$this->loadJQuery($doc);
		$this->loadBootstrap($doc);
		$doc->addStylesheet ( JURI::root ( true ) . '/administrator/components/com_responsivizer/assets/css/modulesmanager.css' );
		$doc->addStylesheet ( JURI::root ( true ) . '/administrator/components/com_responsivizer/assets/css/select2.css' );
		
		// Load specific JS App
		$doc->addScript ( JURI::root ( true ) . '/administrator/components/com_responsivizer/js/select2.js' );
		$allNativePositions = array_keys($rowFileData);
		$implodedAsJSID = '#' . implode(',#', $allNativePositions);
		$doc->addScriptDeclaration("jQuery(function ($) {
								        $('$implodedAsJSID').select2({
								            tags:[],
								            tokenSeparators: [',', ' ']
								        }).on('select2-open',function(e){
								           $('div.select2-drop').hide();
								    	});
										$('ul.select2-choices').attr('data-content','". JText::_('COM_RESPONSIVIZER_EDIT_ASSOCIATION_FIELD') . "').popover({trigger:'hover', placement:'right', html:1});;
								    });");
		
		// Assign data to show
		$this->rowFileData = $rowFileData;
		$this->option = $this->getModel ()->getState ( 'option' );
		
		// Inject js translations
		$this->addShowPositionsMatchForm();
		
		parent::display ( 'matchform' );
	}
}