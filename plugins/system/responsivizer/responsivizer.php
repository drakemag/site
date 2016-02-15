<?php
/**
 * @author Joomla! Extensions Store
 * @package RESPONSIVIZER::PLUGINSWITCHER::plugins::system
 * @copyright (C) 2014 - Joomla! Extensions Store
 * @license GNU/GPLv2 http://www.gnu.org/licenses/gpl-2.0.html
 */
defined('_JEXEC') or die('Restricted access');
jimport('joomla.plugin.plugin');

/**
 * Observer class notified on events
 * 
 * @author Joomla! Extensions Store
 * @package RESPONSIVIZER::PLUGINSWITCHER::plugins::system
 * @since 1.0
 */
class plgSystemResponsivizer extends JPlugin {
	/**
	 * Detect mobile requests
	 * 
	 * @access private
	 * @return boolean
	 */
	private function isMobileRequest() {
		$app = JFactory::getApplication();
		$isMobile = false;

		require_once(JPATH_PLUGINS . '/' . "system" . '/' . "responsivizer" . '/' . "responsivizer" . '/' . "device.php");
		$ua = new responsivizer_uagent_info();

		$isMobile = ($ua->DetectSmartphone() || $ua->DetectTierTablet());

		if ((int) $this->params->get('include_tablets', 0) == 0) {
			if ($ua->DetectTierTablet()) {
				$isMobile = false;
			}
		}
		
		if(method_exists($app, 'set')) {
			$app->set('ismobile', $isMobile);
		} else {
			$app->ismobile = $isMobile; // Compliance only for Joomla 3.1
		}
		
		return $isMobile;
	}
	
	/**
	 * Switch template to new target responsive one
	 *
	 * @access private
	 * @return void
	 */
	protected function _setTemplate($tpl = null) {
		if (empty($tpl)) {
			return;
		} else {
			$app = JFactory::getApplication();
			$app->setTemplate($tpl);
		}
	}
	
	/**
	 * Main dispatch method
	 *
	 * @access private
	 * @return boolean
	 */
	public function onAfterRoute() {
		$app = JFactory::getApplication();
		$changeTplRequest = null;
		$doChangeReq = false;
		$tpl = null;
		$cookieLifetime = 365 * 24 * 60 * 60;
		$desktopFallbackCookieLifetime = $this->params->get('desktop_fallback_cookie_lifetime', 10);
		$defaultMobileTemplate = $this->params->get('template', 'responsivizer');
		// No remember me for admin
		if ($app->isAdmin() || $app->input->get('noswitch', false)) {
			return;
		}

		// Do nothing if this plugin not enable the switcher function
		$enabledSwitcher = $this->params->get('switch_enabled', 0);
		if (!$enabledSwitcher) {
			return;
		}

		//Switch always if params activated or header is forced from HTTPClient to generate manifest
		$switcherHTTPHeader = $app->input->get('HTTP_X_REQUESTED_WITH', false, 'SERVER');
		$alwaysSwitcher = $this->params->get('switch_always', 0);
		if ($alwaysSwitcher || $switcherHTTPHeader === 'manifest') {
			$changeTplRequest = $defaultMobileTemplate;
		}

		// No change template detected by forcing switch always or HTTP header for backend copy
		if (!$changeTplRequest) {
			$changeTplRequest = $app->input->getString('responsivizer_template', null); // Try to get a desktop version forcing by REQUEST
			if ($changeTplRequest) {
				$doChangeReq = true;
				if ($changeTplRequest == 'desktop') {
					$cookieLifetime = $desktopFallbackCookieLifetime;
				}
			} else {
				jimport('joomla.utilities.utility');
				$hash = JApplication::getHash('RESPONSIVIZER_TEMPLATE');
				$changeTplRequest = $app->input->cookie->getString($hash, ''); // Try to get a switcher template from COOKIE by precedent detection for this device
				if ($changeTplRequest) {
					$doChangeReq = true;
				}
				if ($changeTplRequest == 'desktop') {
					$cookieLifetime = $desktopFallbackCookieLifetime;
				}
			}
		} else {
			$doChangeReq = true;
		}

		// Request to change template detected
		if ($doChangeReq) {
			// Apply this change tpl request
			$tpl = $changeTplRequest;
		} else {
			// Detect only on first time request mobile device user, later used by cookie value
			if ($enabledSwitcher) {
				$isMobile = self::isMobileRequest();
				if ($isMobile) {
					$tpl = $defaultMobileTemplate;
				}
			}
		}

		// Set now new detected template to switch to for mobile if any
		$this->_setTemplate($tpl);
		
		// Save to cookie for subsequent requests
		if(!$alwaysSwitcher) {
			$config = JFactory::getConfig();
			$cookie_domain = $config->get('cookie_domain', '');
			$cookie_path = $config->get('cookie_path', '/');
			$lifetime = time() + $cookieLifetime;
			jimport('joomla.utilities.utility');
			setcookie(JApplication::getHash('RESPONSIVIZER_TEMPLATE'), $tpl, $lifetime, $cookie_path, $cookie_domain); // PUSH in COOKIE
		}

		// Force tp=1 when Responsivizer is installed
		JComponentHelper::getParams('com_templates')->set('template_positions_display', true);
	}
	
	/**
	 * Class constructor, manage params from component
	 *
	 * @access private
	 * @return boolean
	 */
	public function __construct(&$subject) {
		parent::__construct($subject);
		$this->params = JComponentHelper::getParams('com_responsivizer');
	}
}

/**
 * Patch for Advanced Modules Manager
 * Requires that Responsivizer template switcher plugin is loaded before AMM
 * It's limited to avoid execution of AMM for mobile requests
 *
 * @access public
 */
function responsivizerPatchForAdvancedModulesManager() {
	jimport('joomla.filesystem.file');
	// Include the moduleHelper
	if (JFile::exists(JPATH_PLUGINS . '/system/nnframework/nnframework.php')) {
		$app = JFactory::getApplication();
		$cparams = JComponentHelper::getParams('com_responsivizer');
		// No remember me for admin
		if ($app->isAdmin() || $app->input->get('noswitch', false)) {
			return;
		}

		// Do nothing if this plugin not enable the switcher function
		$enabledSwitcher = $cparams->get('switch_enabled', 0);
		if (!$enabledSwitcher) {
			return;
		}

		// Avoid fatal error if AMM plugin is published BEFORE Responsivizer template switcher
		$classes = get_declared_classes();
		if (!in_array('JModuleHelper', $classes) && !in_array('jmodulehelper', $classes)) {
			$isMobile = false;
			//Switch always if params activated or header is forced from HTTPClient to generate manifest
			$switcherHTTPHeader = $app->input->get('HTTP_X_REQUESTED_WITH', false, 'SERVER');
			$alwaysSwitcher = $cparams->get('switch_always', 0);
			$explicitTemplate = $app->input->get('template', null);
			if ($alwaysSwitcher || $switcherHTTPHeader === 'manifest' || $explicitTemplate === 'responsivizer') {
				$isMobile = true;
			} else {
				$hash = JApplication::getHash('RESPONSIVIZER_TEMPLATE');
				$isMobile = $app->input->getString($hash, '', 'cookie'); // Try to get a switcher template from COOKIE by precedent detection for this device
			}

			if(!$isMobile) {
				require_once(JPATH_PLUGINS . '/' . "system" . '/' . "responsivizer" . '/' . "responsivizer" . '/' . "device.php");
				$ua = new responsivizer_uagent_info();
				$isMobile = ($ua->DetectSmartphone() || $ua->DetectTierTablet());
			}

			$changeTplRequest = $app->input->getString('responsivizer_template', null); // Try to get a desktop version forcing by REQUEST
			if ($changeTplRequest == 'desktop') {
				$isMobile = false;
			}
			
			// Preload here the modules helper class, bypass Joomla autoloader and everything else after this, also AMM
			if($isMobile) {
				require_once JPATH_ROOT . '/templates/responsivizer/framework/modules/helper.php';
			}
		}
	}
}

// Execute always patch asap in Joomla execution
responsivizerPatchForAdvancedModulesManager();