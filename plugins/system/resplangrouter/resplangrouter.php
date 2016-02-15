<?php
/**
 * @author Joomla! Extensions Store
 * @package RESPONSIVIZER::plugins::system
 * @copyright (C) 2013 - Joomla! Extensions Store
 * @license GNU/GPLv2 http://www.gnu.org/licenses/gpl-2.0.html
 */
defined('_JEXEC') or die('Restricted access');
jimport('joomla.plugin.plugin');

/**
 * Observer class notified on events
 * 
 * @author Joomla! Extensions Store
 * @package RESPONSIVIZER::plugins::system
 * @since 2.1
 */
class plgSystemResplangrouter extends JPlugin {
	/**
	 * Joomla config object
	 * 
	 * @access private
	 * @var Object
	 */
	private $joomlaConfig;
	
	/**
	 * Main dispatch method
	 *
	 * @access private
	 * @return boolean
	 */
	public function onAfterInitialise() {
		$app = JFactory::getApplication();
		
		// Detect if current request is directed to component frontend
		if($app->input->get('option') == 'com_responsivizer' && !$app->getClientId()) {
			$this->joomlaConfig->set('sef', false);
			$_SERVER['REQUEST_METHOD'] = 'POST';
			
			// Set dummy norespsef var
			$app->input->post->set('norespsef', true);
			$_POST['norespsef'] = true;
		}
	}
	
	/**
	 * Class constructor, manage params from component
	 *
	 * @access private
	 * @return boolean
	 */
	public function __construct(&$subject) {
		parent::__construct($subject);
		$this->joomlaConfig = JFactory::getConfig();
	}
}