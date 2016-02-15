<?php
/** 
 * App runner
 * @package JCHAT::plugins::system
 * @author Joomla! Extensions Store
 * @copyright (C) 2014 - Joomla! Extensions Store
 * @license GNU/GPLv2 http://www.gnu.org/licenses/gpl-2.0.html  
 */
// no direct access
defined('_JEXEC') or die('Restricted access');
jimport('joomla.plugin.plugin');
jimport('joomla.html.toolbar');

class ResponsivizerToolbar extends JToolBar {
	/**
	 * Fake JToolbar global object with accessor to bar protected property
	 *
	 * @param string  $name  The name of the toolbar.
	 * @return ResponsivizerToolbar object.
	 *
	 * @since   11.1
	 */
	public static function getInstance($name = 'toolbar') {
		if (empty(self::$instances[$name])) {
			self::$instances[$name] = new ResponsivizerToolbar($name);
		}

		return self::$instances[$name];
	}

	/**
	 * Render a null tool bar.
	 *
	 * @return  null
	 *
	 */
	public function render() {
		return null;
	}

	/**
	 * Public accessor method to clear toolbar
	 * @access public
	 * @return void
	 */
	public function clearItems() {
		$this->_bar = array();
	}
}

class plgSystemNoToolbar extends JPlugin {
	/**
	 * Check if we are correct to execute plugin
	 * 
	 * @access private
	 * @return boolean
	 */
	private function checkIfCorrectExecution($session) {
		$app = JFactory::getApplication();
		
		$clientID = $app->getClientId();
		$option = $app->input->get('option', null);
		$rvzrIFrame = $app->input->get('rvzr', null);
		$noSwitch = $app->input->get('noswitch', false);
		$tp = $app->input->get('tp', false);
		
		// Force tp=1 during comparison between copy module source target
		if($clientID == 0 && $tp == 1 && $noSwitch == 1) {
			JComponentHelper::getParams('com_templates')->set('template_positions_display', true);
		}
		
		if(($clientID == 1 && $rvzrIFrame == 1 && $option == 'com_templates') || ($clientID == 1 && $session->get('rvzr', false) && $option == 'com_templates')) {
			return true;
		}
		return false;
	}
	
	/**
	 * onAfterInitialise handler
	 *
	 * @access	public
	 * @return null
	 */
	public function onAfterInitialise() {
		// Session management
		$session = JFactory::getSession();
		
		// Danger!!! Exec only when needed
		if($this->checkIfCorrectExecution($session)) {
			$toolbarinstance = ResponsivizerToolbar::getInstance();
		}
	}

	/**
	 * onAfterInitialise handler
	 *
	 * @access	public
	 * @return null
	 */
	public function onAfterDispatch() {
		// Session management
		$session = JFactory::getSession();
		
		// Danger!!! Exec only when needed
		if($this->checkIfCorrectExecution($session)) {
			// Reset current toolbar
			$toolbarinstance = ResponsivizerToolbar::getInstance();
			$toolbarinstance->clearItems();
			
			// Reset title
			$app = JFactory::getApplication();
			$app->JComponentTitle = null;
			
			// Document manipulation and script helper execution
			$doc = JFactory::getDocument();
			$doc->addStyleDeclaration('div#toolbar{display:none;}
									   div.CodeMirror{height:900px !important}
									   div.fltrt:last-child #template-manager-css{display:none}');
			
			$option = $app->input->get('option', null);
			$file = $app->input->get('file', null);
			$view = $app->input->get('view', null);
			// If we are in edit mode we call parent window JS function to add save button
			if($option == 'com_templates' && ($file || $view == 'source')) {
				$doc->addScriptDeclaration('if(window.parent.ResponsivizerEditorTemplate === Object(window.parent.ResponsivizerEditorTemplate)){window.parent.ResponsivizerEditorTemplate.injectToolbarButtons();}');
			}
			$doc->addScriptDeclaration('jQuery(function($){$("#myTabTabs").remove();});');
			
			// Finally ensure to render only component output
			$app->input->set('tmpl', 'component');
			
			// Place rvzr var in session for later requests not stateless
			$session->set('rvzr', 1);
		} else {
			// Clear session var as soon as exits from com_templates for normal toolbar behavior
			if($session->has('rvzr')) {
				$session->clear('rvzr');
			}
		}
	}
}
