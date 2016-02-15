<?php
/**
 * @author Joomla! Extensions Store
 * @package RESPONSIVIZER::FRAMEWORK::templates::responsivizer 
 * @copyright (C) 2013 - Joomla! Extensions Store
 * @license GNU/GPLv2 http://www.gnu.org/licenses/gpl-2.0.html
 */
defined ( '_JEXEC' ) or die ( 'Restricted access' );
define ( 'JPATH_RVZR_DBPOSITIONS', JPATH_ROOT . '/templates/responsivizer/framework/modules/dbpositions.json' );

// Register autoloader prefix
JLoader::registerPrefix ( 'Responsivizer', JPATH_ROOT . '/administrator/components/com_responsivizer/framework' );
JLoader::registerPrefix ( 'Responsivizer', JPATH_ROOT . '/templates/' . $this->template . '/framework' );
JLoader::register ( 'JModuleHelper', JPATH_ROOT . '/templates/' . $this->template . '/framework/modules/helper.php', true );

/**
 * PARAMS initialization
 */
$componentParams = JComponentHelper::getParams ( 'com_responsivizer' );
$this->params->merge ( $componentParams );

if (! $componentParams->get ( 'enable_debug', 0 )) {
	ini_set ( 'display_errors', 0 );
	ini_set ( 'error_reporting', E_ERROR );
}

/**
 * TEMPLATE initializations
 */
$path = $this->baseurl . '/templates/' . $this->template;

// Set URI for switcher
$currentURI = JURI::getInstance ();
$clonedURI = clone ($currentURI); // Avoid collision on global URI object
$clonedURI->setVar ( 'responsivizer_template', 'desktop' );
$queryStringToDesktop = JRoute::_ ( $clonedURI );

// Generic params
$user1_width = $this->params->get ( 'user1_width', 31 );
$user2_width = $this->params->get ( 'user2_width', 31 );
$user3_width = $this->params->get ( 'user3_width', 31 );
$user4_width = $this->params->get ( 'user4_width', 31 );
$user5_width = $this->params->get ( 'user5_width', 31 );
$user6_width = $this->params->get ( 'user6_width', 31 );
$width_menutop = $this->params->get ( 'width_menutop', 35 );
$width_menubottom = $this->params->get ( 'width_menubottom', 35 );
$width_copyright = $this->params->get ( 'width_copyright', 35 );
$slideshow_width = $this->params->get ( 'slideshow_width', 100 );
$color_theme = $this->params->get ( 'color_theme', 'blue' );
$general_background = $this->params->get ( 'general_background', 'white' );
$min_height = $this->params->get ( 'min_height', 380 );
$enable_socials = $this->params->get ( 'enable_socials', null );
$text_social_icons = $this->params->get ( 'text_social_icons', null );
$font = $this->params->get ( 'font', 'Metrophobic' );
$font_content = $this->params->get ( 'font_content', 'Verdana' );
$fontSize = $this->params->get ( 'fontSize', '0.75em' );
$responsivizerSlideshowEnabled = $this->params->get ( 'responsivizer_slideshow_enabled', false );
$showTplSwitcher = $this->params->get ( 'show_tpl_switcher', false );
$switchAlways = $this->params->get ( 'switch_always', false );
$injectGajs = $this->params->get ( 'inject_gajs', false );
$gajsCode = $this->params->get ( 'gajs_code', null );
$switcherMenutype = $this->params->get ( 'switcher_menutype', 'dropdown' );
$telNumber = $this->params->get ( 'tel_number', '' );

$loadCompatStyles = $this->params->get ( 'include_compat_styles', false );

// Fonts definition
$stdFonts = array (
		'Arial',
		'Comic+sans+ms',
		'Verdana',
		'Trebuchet',
		'Georgia',
		'Times+new+roman',
		'Tahoma' 
);

// Socials definition
$socials = array (
		'facebook',
		'google',
		'twitter',
		'linkedin',
		'youtube',
		'flickr',
		'instagram',
		'pinterest',
		'tumblr',
		'vimeo',
		'delicious',
		'skype',
		'rss'
);

/**
 * FRAMEWORK initializations
 */

/**
 * Application reference
 * 
 * @var Object
 */
$app = JFactory::getApplication ();

/**
 * Database reference
 * 
 * @var Object
 */
$DBO = JFactory::getDbo ();

/**
 * Joomla Document reference
 * 
 * @var Object
 */
$document = JFactory::getDocument ();

/**
 * Responsivizer registry reference
 * 
 * @var Object
 */
$registry = new ResponsivizerRegistry ();

/**
 * Responsivizer Modules Manager reference
 * 
 * @var Object
 */
$modulesManager = new ResponsivizerModulesManager ( $registry, $document );
$document->modulesManager = $modulesManager;

/**
 * Offline cache manager reference
 * 
 * @var Object
 */
// ResponsivizerOfflineCache manager
$offlineCache = new ResponsivizerOfflineCache ();
$this->offlineCache = $offlineCache;

/**
 * JS libraries inclusion
 */
// Mootools framework inclusion by default
JHTML::_ ( 'behavior.framework' );

// jQuery framework inclusion on switcher
if ($this->params->get ( 'tpl_load_jquery' )) {
	if (version_compare ( JVERSION, '3.0', '>=' )) {
		JHtml::_ ( 'jquery.framework' );
	} else {
		if (! $app->get ( 'jQuery' ) && ! $app->get ( 'jquery' )) {
			$document->addScript ( JURI::root ( true ) . '/templates/responsivizer/js/jquery.js' );
			$app->set ( 'jQuery', 1 );
		}
	}
}

/**
 * Scripts inclusion
 */
$document->addScriptDeclaration ( 'var rvzrBaseURI = "' . JURI::root () . '";' );
$document->addScriptDeclaration ( 'var rvzrAccordionStatus = ' . $this->params->get ( 'headers_accordion', 1 ) . ';' );
$document->addScriptDeclaration ( 'var rvzrAccordionCollapsed = ' . $this->params->get ( 'headers_accordion_collapsed', 0 ) . ';' );
$document->addScriptDeclaration ( 'var rvzrAccordionMenuCollapsed = ' . $this->params->get ( 'headers_menu_accordion_collapsed', 0 ) . ';' );
$document->addScriptDeclaration ( 'var rvzrAccordionTopMenuCollapsed = ' . $this->params->get ( 'headers_topmenu_accordion_collapsed', 1 ) . ';' );
$document->addScriptDeclaration ( 'var rvzrOfflineCache = ' . $this->params->get ( 'offlinecache', 0 ) . ';' );
$document->addScriptDeclaration ( 'var rvzrResponsiveElementsClassname = "' . $this->params->get ( 'responsive_elements_classname', 'responsive' ) . '";' );
$document->addScript ( JURI::root ( true ) . '/templates/responsivizer/js/utility.js' );
if($this->params->get('native_app_feature', 0)) {
	$document->addScript ( JURI::root ( true ) . '/templates/responsivizer/js/stay_standalone.js' );
}

/**
 * Stylesheets inclusion
 */
$document->addStyleSheet ( JURI::root ( true ) . '/templates/system/css/general.css' );
$document->addStyleSheet ( JURI::root ( true ) . "/templates/$this->template/css/main.css" );
$document->addStyleSheet ( JURI::root ( true ) . "/templates/$this->template/css/resources/font-awesome.min.css" );
$document->addStyleSheet ( JURI::root ( true ) . "/index.php?option=com_responsivizer&amp;task=dynamiccss.display&amp;format=css" );
if ($general_background != 'white') {
	$document->addStyleSheet ( JURI::root ( true ) . "/templates/$this->template/css/themes/backgrounds/$general_background.css" );
}
$document->addStyleSheet ( JURI::root ( true ) . "/templates/$this->template/css/themes/typography/$color_theme.css" );

if($loadCompatStyles) {
	$document->addStyleSheet ( JURI::root ( true ) . "/templates/$this->template/css/resources/compat.css" );
}

// No standard fonts, fetch fonts from google
$uriInstance = JUri::getInstance ();
$httpPrefix = $uriInstance->isSSL () ? 'https' : 'http';
if (! in_array ( $font, $stdFonts )) {
	$document->addStyleSheet ( "$httpPrefix://fonts.googleapis.com/css?family=$font" );
}
if (! in_array ( $font_content, $stdFonts ) && $font != $font_content) {
	$document->addStyleSheet ( "$httpPrefix://fonts.googleapis.com/css?family=$font_content" );
}