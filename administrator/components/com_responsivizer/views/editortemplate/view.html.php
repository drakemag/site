<?php
// namespace administrator\components\com_responsivizer\views\editortemplate;
/**
 * @package RESPONSIVIZER::EDITORTEMPLATE::administrator::components::com_responsivizer
 * @subpackage views
 * @subpackage editortemplate
 * @author Joomla! Extensions Store
 * @copyright (C) 2014 - Joomla! Extensions Store
 * @license GNU/GPLv2 http://www.gnu.org/licenses/gpl-2.0.html  
 */
defined ( '_JEXEC' ) or die ( 'Restricted access' );
 
/**
 * @package RESPONSIVIZER::EDITORTEMPLATE::administrator::components::com_responsivizer
 * @subpackage views
 * @subpackage editortemplate
 * @since 2.0
 */
class ResponsivizerViewEditortemplate extends ResponsivizerView {
	/**
	 * Add the page title and toolbar.
	 *
	 * @since	1.6
	 */
	protected function addDisplayToolbar() {
		$doc = JFactory::getDocument();
		$doc->addStyleDeclaration('.icon-48-responsivizer{background-image:url("components/com_responsivizer/images/icon-48-template_editor.png")}');
		JToolBarHelper::title( JText::_( 'COM_RESPONSIVIZER_EDITOR_TEMPLATE_TOOLBAR' ), 'responsivizer' );
		JToolBarHelper::custom('editortemplate.display', 'edit', 'edit', 'COM_RESPONSIVIZER_TEMPLATE_FILE_LIST', false);
		JToolBarHelper::custom('cpanel.display', 'home', 'home', 'COM_RESPONSIVIZER_CPANEL', false);
	}
	
	/**
	 * Default display listEntities
	 *        	
	 * @access public
	 * @param string $tpl
	 * @param mixed $data to present
	 * @return void
	 */
	public function display($tpl = null, $data = null) {
		$doc = JFactory::getDocument();
		$this->loadJQuery($doc);
		$this->loadBootstrap($doc);
		// Load override styles
		$doc->addStylesheet ( JURI::root ( true ) . '/administrator/components/com_responsivizer/assets/css/editortemplate.css' );
		
		// Inject js translations
		$translations = array();
		$this->injectJsTranslations($translations, $doc);
		
		// Load specific JS App
		$doc->addScript ( JURI::root ( true ) . '/administrator/components/com_responsivizer/js/editortemplate.js' );
		
		$this->option = $this->getModel ()->getState ( 'option' );
		$this->templateID = $data->id;
		
		// Aggiunta toolbar
		$this->addDisplayToolbar();
			
		parent::display ();
	}
}