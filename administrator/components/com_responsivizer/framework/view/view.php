<?php
// namespace components\com_responsivizer\libraries\framework\view;
/**
 * @package RESPONSIVIZER::FRAMEWORK::administrator::components::com_responsivizer
 * @subpackage framework
 * @subpackage view
 * @author Joomla! Extensions Store
 * @copyright (C) 2014 - Joomla! Extensions Store
 * @license GNU/GPLv2 http://www.gnu.org/licenses/gpl-2.0.html  
 */
defined ( '_JEXEC' ) or die ( 'Restricted access' );
jimport ( 'joomla.application.component.view' );
jimport ( 'joomla.html.pagination' );
 
/**
 * Base view for all display core
 * 
 * @package RESPONSIVIZER::FRAMEWORK::administrator::components::com_responsivizer
 * @subpackage framework
 * @subpackage view
 * @since 2.0
 */
class ResponsivizerView extends JViewLegacy {
	/**
	 * User object for ACL authorise check
	 *
	 * @access protected
	 * @var Object
	 */
	protected $user;
	
	/**
	 * Document object, needed by views to inject
	 * CSS/JS tags into document output
	 *
	 * @access protected
	 * @var Object
	 */
	protected $document;
	
	/**
	 * Reference to option executed
	 *
	 * @access public
	 * @var string
	 */
	public $option;
	
	/**
	 * Reference to application
	 *
	 * @access public
	 * @var Object
	 */
	public $app;
	
	/**
	 * Inject language constant into JS Domain maintaining same name mapping
	 * 
	 * @access protected
	 * @param $translations Object&
	 * @param $document Object&
	 * @return void
	 */
	protected function injectJsTranslations(&$translations, &$document) {
		$jsInject = null;
 		// Do translations
		foreach ( $translations as $translation ) {
			$jsTranslation = strtoupper ( $translation );
			$translated = JText::_( $jsTranslation );
			$jsInject .= <<<JS
				$translation = '{$translated}'; 
JS;
		}
		$document->addScriptDeclaration($jsInject);
	}
	
	/**
	 * Manage injecting jQuery framework into document with class inheritance support
	 *
	 * @access protected
	 * @param Object& $doc
	 * @return void
	 */
	protected function loadJQuery(&$document) {
		JHTML::_('bootstrap.framework');
		try { JHtml::_('behavior.core'); } catch (Exception $e){} // Compatibility fix ensured for Joomla 3.4+
		
		$document->addScript ( JURI::root ( true ) . '/administrator/components/com_responsivizer/js/jstorage.min.js' );
		$base = JURI::root();
		$document->addScriptDeclaration("var responsivizerBaseURI='$base';");
	}
	
	/**
	 * Manage injecting jQuery framework into document with class inheritance support
	 *
	 * @access protected
	 * @param Object& $doc
	 * @return void
	 */
	protected function loadJQueryClass(&$document) {
		// jQuery foundation framework and class support
		$document->addScript ( JURI::root ( true ) . '/administrator/components/com_responsivizer/js/classnative.js' );
	}
	
	/**
	 * Manage injecting Bootstrap framework into document
	 * 
	 * @access protected
	 * @param Object& $doc
	 * @return void
	 */
	protected function loadBootstrap(&$document) {
		// Main styles for admin interface
		$document->addStylesheet ( JURI::root ( true ) . '/administrator/components/com_responsivizer/assets/css/bootstrap-interface.css' );
		
		// Main JS file for admin interface
		$document->addScript ( JURI::root ( true ) . '/administrator/components/com_responsivizer/js/bootstrap-interface.js' );
	}
	
	/**
	 * Manage injecting valildation plugin into document
	 *
	 * @access protected
	 * @param Object& $doc
	 * @return void
	 */
	protected function loadValidation(&$document) {
		$document->addStylesheet ( JURI::root ( true ) . '/administrator/components/com_responsivizer/assets/css/simplevalidation.css' );
		$document->addScript ( JURI::root ( true ) . '/administrator/components/com_responsivizer/js/jquery.simplevalidation.js' );
	}
	
	/**
	 * Manage injecting jQuery UI framework into document
	 *
	 * @access protected
	 * @param Object& $doc
	 * @return void
	 */
	protected function loadJQueryUI(&$document) {
		$document->addStylesheet ( JURI::root ( true ) . '/administrator/components/com_responsivizer/assets/css/jqueryui/jquery.ui.all.css' );
		$document->addScript ( JURI::root ( true ) . '/administrator/components/com_responsivizer/js/jquery.ui.js' );
	}
	
	/**
	 * Class constructor
	 *
	 * @param array $config
	 *        	return Object
	 */
	public function __construct($config = array()) {
		parent::__construct ( $config );
	
		$this->app = JFactory::getApplication ();
		$this->user = JFactory::getUser ();
		$this->document = JFactory::getDocument();
		$this->option = $this->app->input->get ( 'option' );
	}
}