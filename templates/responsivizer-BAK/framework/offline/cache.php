<?php
// namespace templates\responsivizer\framework\offline;
/**
 * @package RESPONSIVIZER::FRAMEWORK::templates::responsivizer
 * @subpackage framework
 * @subpackage offline
 * @author Joomla! Extensions Store
 * @copyright (C) 2013 - Joomla! Extensions Store
 * @license GNU/GPLv2 http://www.gnu.org/licenses/gpl-2.0.html
 */
defined('_JEXEC') or die('Restricted access');

/**
 * Offline cache responsibilities interface
 *
 * @package RESPONSIVIZER::FRAMEWORK::templates::responsivizer
 * @subpackage framework
 * @subpackage offline
 * @since 2.0
 */
interface IResponsivizerOfflineCache {
	/**
	 * Inject application cache directive if:
	 * -page is cached
	 * -page cache is published
	 * -global cache param is on
	 * -client device is detected as mobile
	 *
	 * @access public
	 * @param JDatabase $connector
	 * @param Object $cparams
	 * @return boolean
	 */
	public function injectCacheManifest($connector, $cparams);
}

/**
 * Offline cache implementation <<testable_behavior>>
 *
 * @package RESPONSIVIZER::FRAMEWORK::templates::responsivizer
 * @subpackage framework
 * @subpackage offline
 * @since 2.0
 */
class ResponsivizerOfflineCache implements IResponsivizerOfflineCache {
	/**
	 * Get current active menu id
	 * 
	 * @access private
	 * @return int
	 */
	private function getMenu() {
		$menu = JMenu::getInstance('site');
		$activeMenu = $menu->getActive();
		if(is_object($activeMenu)) {
			return (int)$activeMenu->id;
		}
		
		return false;
	}
	
	/**
	 * Detect if client is a valid mobile, AKA is:
	 * -application ismobile true on first request from this device
	 * -cookie for responsivizer template has been set after first request and detect
	 * 
	 * @access private
	 * @return int
	 */
	private function isValidMobile() {
		// Check if mobile device is set on application exec
		$app = JFactory::getApplication();
		
		if(method_exists($app, 'get')) {
			$isMobileOnApp = $app->get('ismobile', false);
		} else {
			$isMobileOnApp = $app->ismobile;
		}
		
		// Check if mobile device is set as template cookie after first requests
		$tmplCookie = JApplication::getHash ( 'RESPONSIVIZER_TEMPLATE' );
		$isMobileOnCookie = !is_null($tmplCookie);
		
		return $isMobileOnApp || $isMobileOnCookie;
	}
	
	/**
	 * Inject application cache directive if:
	 * -page is cached
	 * -page cache is published
	 * -global cache param is on
	 * -client device is detected as mobile
	 *
	 * @access public
	 * @param JDatabase $connector
	 * @param Object $cparams
	 * @return boolean
	 */
	public function injectCacheManifest($connector, $cparams) {
		$currentItemid = $this->getMenu();
		if(!$currentItemid) {
			return false;
		}
		// Check if current page is cached and is active published
		$query = "SELECT COUNT(*)" .
				 "\n FROM #__rvzr_offline_urls AS urls" .
				 "\n WHERE urls.itemid = " . $currentItemid;
		$queryResult = $connector->setQuery($query)->loadResult();
		
		if($queryResult && $this->isValidMobile()) {
			return 'manifest="' . JURI::getInstance()->base() . 'index.php?option=com_responsivizer&amp;task=offlinecache.showEntity&amp;menuid=' . $currentItemid . '&amp;format=cache"';
		}
	}
}