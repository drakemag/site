<?php
// namespace administrator\components\com_responsivizer\views\config;
/**
 *
 * @package RESPONSIVIZER::CONFIG::administrator::components::com_responsivizer
 * @subpackage views
 * @subpackage config
 * @author Joomla! Extensions Store
 * @copyright (C) 2014 - Joomla! Extensions Store
 * @license GNU/GPLv2 http://www.gnu.org/licenses/gpl-2.0.html  
 */
defined ( '_JEXEC' ) or die ( 'Restricted access' );

/**
 * Config view
 *
 * @package RESPONSIVIZER::CONFIG::administrator::components::com_responsivizer
 * @subpackage views
 * @subpackage config
 * @since 1.0
 */
class ResponsivizerViewConfig extends ResponsivizerView {
	/**
	 * Add the page title and toolbar.
	 *
	 * @since	1.6
	 */
	protected function addDisplayToolbar() {
		$doc = JFactory::getDocument();
		$doc->addStyleDeclaration('.icon-48-responsivizer{background-image:url("components/com_responsivizer/images/icon-48-config.png")}');
		JToolBarHelper::title( JText::_( 'COM_RESPONSIVIZER_' . strtoupper($this->getModel()->getState('fieldset')) . '_CONFIG' ), 'responsivizer' );
		JToolBarHelper::apply('config.saveEntity', 'COM_RESPONSIVIZER_SAVECONFIG');
		JToolBarHelper::custom('cpanel.display', 'home', 'home', 'COM_RESPONSIVIZER_CPANEL', false);
	}
	
	/**
	 * Configuration panel rendering for component settings
	 *
	 * @access public
	 * @param string $tpl
	 * @return void
	 */
	public function display($tpl = null) {
		$doc = JFactory::getDocument();
		$this->loadJQuery($doc);
		$this->loadJQueryUI($doc);
		$this->loadBootstrap($doc);
		$doc->addStylesheet ( JURI::root ( true ) . '/administrator/components/com_responsivizer/assets/css/colorpicker.css' );
		$doc->addScript ( JURI::root ( true ) . '/administrator/components/com_responsivizer/js/config.js' );
		$doc->addScript ( JURI::root ( true ) . '/administrator/components/com_responsivizer/js/colorpicker.js' );
		try { 
			JHtml::_('behavior.framework'); 
		} catch (Exception $e){} // Compatibility fix ensured for Joomla 3.4+
		
		// Inject js translations
		$translations = array('COM_RESPONSIVIZER_ADDIMAGE',
							  'COM_RESPONSIVIZER_REMOVEIMAGE');
		$this->injectJsTranslations($translations, $doc);
		
		$params = $this->get('Data');
		$form = $this->get('Form');
		$extendedForm = $this->get('ExtendedForm');
		
		$doc->addScriptDeclaration("var responsivizer_bg_color = '" .  $params->get('bg_color', '#ffffff') . "'");
		$doc->addScriptDeclaration("var responsivizer_color_caption = '" .  $params->get('color_caption', '#dddddd') . "'");
		
		// Bind the form to the data.
		if ($form && $params) {
			$form->bind($params);
		}
		
		$this->params_form = $form;
		$this->params_extended_form = $extendedForm;
		$this->params = $params;
		$this->fieldset = $this->getModel()->getState('fieldset');
		
		// Aggiunta toolbar
		$this->addDisplayToolbar();
		
		// Output del template
		parent::display();
	}
}
?>