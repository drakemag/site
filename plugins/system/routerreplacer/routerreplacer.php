<?php
/**
 * @author Joomla! Extensions Store
 * @package RESPONSIVIZER::ROUTER::plugins::system
 * @copyright (C) 2014 - Joomla! Extensions Store
 * @license GNU/GPLv2 http://www.gnu.org/licenses/gpl-2.0.html
 */
defined('_JEXEC') or die('Restricted access');
define('ROUTER_MODE_SKIP_SEF', 2);
define('ROUTER_MODE_SKIP_RAW', -1);
jimport('joomla.plugin.plugin');

/**
 * Observer class notified on events
 * 
 * @author Joomla! Extensions Store
 * @package RESPONSIVIZER::ROUTER::plugins::system
 * @since 2.0
 */
class plgSystemRouterReplacer extends JPlugin {
	public function onAfterRoute() {
		$app = JFactory::getApplication();
		$document = JFactory::getDocument();
		$template = $app->getTemplate();
		if ($document->getType() !== 'html' || $app->input->get('tmpl') === 'component' || $template !== $this->params->get('template')) {
			return false;
		}

		if ($app->input->get("task") == "edit" || $app->isAdmin() || $app->input->get("option") == 'com_users') {
			return false;
		}
		
		if (!$this->params->get('mobile_homepage_menuid') || !$this->params->get('mobile_homepage')) {
			return false;
		}
		
		// Detect the home menu itemid in standard mode - keep multilanguage multimenu home id
		$menu = $app->getMenu();
		$activeMenu = $menu->getActive();
		$currentItemid = $activeMenu->id;
		$isHome = (bool)$activeMenu->home;
		
		// If home route detected get the custom mobile and overwite router parse and set request vars
		if($isHome) {
			// Get the router
			$router = $app->getRouter();
			
			//Avoid risk of recursion on multiple parse on same request
			$clonedRouter = clone($router);
			
			// Get the menu item to switch to as the set home for mobile devices from settings
			$switchToItemid = $this->params->get('mobile_homepage_menuid');
			if(!$switchToItemid) {
				return false;
			}
			$switchToMenu = $menu->getItem($switchToItemid);
			
			// Parse new JURI route
			$newUriInstance = JURI::getInstance(JUri::base() . $switchToMenu->route);
			$result = $clonedRouter->parse($newUriInstance);
			
			// Overwrite REQUEST vars in current application dispatch
			if(is_array($result) && count($result)) {
				foreach ($result as $getVarName=>$getVarValue) {
					$app->input->set($getVarName, $getVarValue);
				}
			}
		}
		

	}

	public function __construct(&$subject) {
		parent::__construct($subject);
		$this->params = JComponentHelper::getParams('com_responsivizer');
	}
}
