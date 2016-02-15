<?php
// namespace administrator\components\com_responsivizer\views\cpanel;
/**
 * @package RESPONSIVIZER::CPANEL::administrator::components::com_responsivizer
 * @subpackage views
 * @subpackage cpanel
 * @author Joomla! Extensions Store
 * @copyright (C) 2014 - Joomla! Extensions Store
 * @license GNU/GPLv2 http://www.gnu.org/licenses/gpl-2.0.html  
 */
defined ( '_JEXEC' ) or die ( 'Restricted access' );

/**
 * CPanel view
 *
 * @package RESPONSIVIZER::CPANEL::administrator::components::com_responsivizer
 * @subpackage views
 * @subpackage cpanel
 * @since 2.0
 */
class ResponsivizerViewCpanel extends ResponsivizerView {

	/**
	 * Render iconset for cpanel
	 *
	 * @param $link string
	 * @param $image string
	 * @access private
	 * @return string
	 */
	private function getIcon($link, $image, $text, $target = '', $title = null, $class = null) {
		$mainframe = JFactory::getApplication ();
		$lang = JFactory::getLanguage ();
		$option = $this->option;
		$src = "components/$option/images/$image";
		?>
		<div style="float:<?php echo ($lang->isRTL()) ? 'right' : 'left'; ?>;">
			<div class="icon">
				<a <?php echo $title . $class;?> <?php echo $target;?> href="<?php echo $link; ?>">
					<div class="task <?php echo $image;?>"></div> 
					<span class="task"><?php echo $text; ?></span>
				</a>
			</div>
		</div>
		<?php
	}
	
	/**
	 * Add the page title and toolbar.
	 *
	 * @since	1.6
	 */
	protected function addDisplayToolbar() {
		$doc = JFactory::getDocument();
		$doc->addStyleDeclaration('.icon-48-responsivizer{background-image:url("components/com_responsivizer/images/responsivizer-48x48.png")}');
		JToolBarHelper::title( JText::_( 'COM_RESPONSIVIZER_CPANEL_TOOLBAR' ), 'responsivizer' );
		JToolBarHelper::custom('cpanel.display', 'home', 'home', 'COM_RESPONSIVIZER_CPANEL', false);
	}
	
	/**
	 * Control panel display
	 *        	
	 * @access public
	 * @param string $tpl
	 * @return void
	 */
	public function display($tpl = null) {
		$doc = JFactory::getDocument ();
		$this->loadJQuery($doc);
		$this->loadBootstrap($doc);
		$doc->addStylesheet ( JURI::root ( true ) . '/administrator/components/com_responsivizer/assets/css/cpanel.css' );
		$doc->addStylesheet ( JURI::root ( true ) . '/administrator/components/com_responsivizer/assets/css/emulator.css' );
		$doc->addScript ( JURI::root ( true ) . '/administrator/components/com_responsivizer/js/chart.js' );
		$doc->addScript ( JURI::root ( true ) . '/administrator/components/com_responsivizer/js/emulator.js' );
		$doc->addScript ( JURI::root ( true ) . '/administrator/components/com_responsivizer/js/cpanel.js' );
		
		// Inject js translations
		$translations = array (	'COM_RESPONSIVIZER_TOTAL_MODULES_CHART',
								'COM_RESPONSIVIZER_PUBLISHED_MODULES_CHART',
								'COM_RESPONSIVIZER_UNPUBLISHED_MODULES_CHART',
								'COM_RESPONSIVIZER_TOTAL_MOBILE_MODULES_CHART',
								'COM_RESPONSIVIZER_PUBLISHED_MOBILE_MODULES_CHART',
								'COM_RESPONSIVIZER_UNPUBLISHED_MOBILE_MODULES_CHART',
								'COM_RESPONSIVIZER_TOTAL_CACHED_PAGES_CHART',
								'COM_RESPONSIVIZER_PUBLISHED_CACHED_PAGES_CHART',
								'COM_RESPONSIVIZER_SPACER_CHARACTER',
								'COM_RESPONSIVIZER_RESOLUTION_CHARACTER',
								'COM_RESPONSIVIZER_CHOOSE_PHONE',
								'COM_RESPONSIVIZER_CHOOSE_TABLET',
								'COM_RESPONSIVIZER_SCREEN_SIZE',
								'COM_RESPONSIVIZER_PIXEL_RATIO'
		);
		$this->injectJsTranslations($translations, $doc);
		
		$livesite =  substr_replace(JURI::root(), "", -1, 1);
		$infoData = $this->getModel()->getData($this->get('registry'));
		$doc->addScriptDeclaration('var responsivizerChartData = ' . json_encode($infoData));
		
		// Buffer delle icons
		ob_start ();
		$this->getIcon ( 'index.php?option=com_responsivizer&amp;task=config.display&amp;fieldset=template', 'icon-color-palette', JText::_ ( 'COM_RESPONSIVIZER_TEMPLATE_SETTINGS' ));
		$this->getIcon ( 'index.php?option=com_responsivizer&amp;task=config.display&amp;fieldset=plugin', 'icon-power-cord', JText::_ ( 'COM_RESPONSIVIZER_PLUGINS_SETTINGS' ));
		$this->getIcon ( 'index.php?option=com_responsivizer&amp;task=config.display&amp;fieldset=module', 'icon-pictures', JText::_ ( 'COM_RESPONSIVIZER_SLIDESHOW_SETTINGS' ));
		$this->getIcon ( 'index.php?option=com_responsivizer&amp;task=config.display&amp;fieldset=template&amp;#_template_social', 'icon-picture', JText::_ ( 'COM_RESPONSIVIZER_TEMPLATE_SOCIAL' ));
		$this->getIcon ( 'index.php?option=com_responsivizer&amp;task=config.display&amp;fieldset=template&amp;#_template_socialsharer', 'icon-broadcast', JText::_ ( 'COM_RESPONSIVIZER_TEMPLATE_SOCIAL_SHARER' ));
		$this->getIcon ( 'index.php?option=com_responsivizer&task=modulesmanager.display', 'icon-grid-view-2', JText::_ ( 'COM_RESPONSIVIZER_MODULES_MANAGER' ));
		$this->getIcon ( 'index.php?option=com_responsivizer&task=modulesmanager.editPositionsMatch', 'icon-equalizer', JText::_ ( 'COM_RESPONSIVIZER_EDIT_POSITIONS_MATCH' ));
		$this->getIcon ( '#', 'icon-mobile', JText::_ ( 'COM_RESPONSIVIZER_EMULATOR' ), '', 'data-content="' . JText::_('COM_RESPONSIVIZER_START_EMULATOR') . '"', 'class="triggerEmulator"');
		$this->getIcon ( 'index.php?option=com_responsivizer&task=editortemplate.display', 'icon-pencil', JText::_ ( 'COM_RESPONSIVIZER_EDITOR_TEMPLATE' ));
		$this->getIcon ( 'index.php?option=com_responsivizer&task=offlinecache.display', 'icon-database', JText::_ ( 'COM_RESPONSIVIZER_OFFLINE_CACHE' ));
		$this->getIcon ( 'http://storejextensions.org/responsivizer_documentation.html', 'icon-help', JText::_ ( 'COM_RESPONSIVIZER_HELP' ));

		$contents = ob_get_clean ();
		 
		// Assign reference variables
		$this->icons = $contents;
		$this->livesite = $livesite;
		$this->componentParams = JComponentHelper::getParams('com_responsivizer');
		$this->infoData = $infoData;
		$this->updatesData = $this->getModel()->getUpdates($this->get('httpclient'));
		$this->currentVersion = strval(simplexml_load_file(JPATH_COMPONENT_ADMINISTRATOR . '/responsivizer.xml')->version);
		
		$doc->addScriptDeclaration('var responsiveTemplate = "' . $this->componentParams->get('template', 'responsivizer') . '"');
		
		// Aggiunta toolbar
		$this->addDisplayToolbar();
		
		// Output del template
		parent::display ();
	}
}