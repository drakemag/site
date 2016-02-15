<?php
// namespace administrator\components\com_responsivizer\views\sources;
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
 * @package RESPONSIVIZER::SOURCES::administrator::components::com_responsivizer
 * @subpackage views
 * @subpackage sources
 * @since 1.0
 */
class ResponsivizerViewOfflinecache extends ResponsivizerView {
	/**
	 * Add the page title and toolbar for listEntities
	 */
	protected function addDisplayToolbar() {
		$doc = JFactory::getDocument();
		$doc->addStyleDeclaration('.icon-48-responsivizer{background-image:url("components/com_responsivizer/images/icon-48-offline.png")}');
		JToolBarHelper::title( JText::_( 'COM_RESPONSIVIZER_OFFLINE_PAGES_TOOLBAR' ), 'responsivizer' );
		
		JToolBarHelper::addNew('offlinecache.editentity');
		JToolBarHelper::editList('offlinecache.editentity');
		JToolBarHelper::deleteList('COM_RESPONSIVIZER_DELETE_PAGE', 'offlinecache.deleteentity');
		
		JToolBarHelper::custom('cpanel.display', 'home', 'home', 'COM_RESPONSIVIZER_CPANEL', false);
	}
	
	/**
	 * Add the page title and toolbar for editEntity
	 */
	protected function addEditEntityToolbar() {
		$isNew		= ($this->record->id == 0);
		$toolbarHelperTitle = $isNew ? 'COM_RESPONSIVIZER_NEW_OFFLINE_PAGE' : 'COM_RESPONSIVIZER_EDIT_OFFLINE_PAGE';
		
		$doc = JFactory::getDocument();
		$doc->addStyleDeclaration('.icon-48-responsivizer{background-image:url("components/com_responsivizer/images/icon-48-offline.png")}');
		JToolBarHelper::title( JText::_( $toolbarHelperTitle ), 'responsivizer' );
		JToolBarHelper::apply( 'offlinecache.applyEntity');
		JToolBarHelper::save( 'offlinecache.saveEntity');
		JToolBarHelper::custom('offlinecache.cancelEntity', 'cancel', 'cancel', 'JCANCEL', false);
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
		$rows = $this->get('Data');
		$filters = $this->get ( 'Filters' );
		$total = $this->get ( 'Total' );
		
		$doc = JFactory::getDocument();
		$this->loadJQuery($doc);
		$this->loadBootstrap($doc);
		// Load override styles
		$doc->addStylesheet ( JURI::root ( true ) . '/administrator/components/com_responsivizer/assets/css/offlinecache.css' );
		
		// Inject js translations
		$translations = array();
		$this->injectJsTranslations($translations, $doc);
		
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
		$this->componentParams = JComponentHelper::getParams('com_responsivizer');
		
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
		$doc->addStylesheet ( JURI::root ( true ) . '/administrator/components/com_responsivizer/assets/css/offlinecache.css' );
		
		// Inject js translations
		$translations = array();
		$this->injectJsTranslations($translations, $doc);
		
		// Load specific JS App
		$doc->addScriptDeclaration("
					Joomla.submitbutton = function(pressbutton) {
						jQuery('#adminForm').validation();
				
						if (pressbutton == 'offlinecache.cancelEntity') {
							Joomla.submitform( pressbutton );
							return true;
						}
		
						if(jQuery('#adminForm').validate()) {
							Joomla.submitform( pressbutton );
							return true;
						}
						return false;
					};
					
					jQuery(function($){
						$('#itemid, input[name^=process]').on('click', function(){
							$('#haschanged').val(1);
						});
					});
				");
		$lists = $this->getModel()->getLists($row);
		$this->option = $this->getModel ()->getState ( 'option' );
		$this->record = $row;
		$this->lists = $lists;
		$this->user = JFactory::getUser();
		
		// Add toolbar
		$this->addEditEntityToolbar();
		
		parent::display ( 'edit' );
	}
}