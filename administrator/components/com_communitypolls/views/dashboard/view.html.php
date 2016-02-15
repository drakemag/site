<?php
/**
 * @version		$Id: view.html.php 01 2014-01-26 11:37:09Z maverick $
 * @package		CoreJoomla.Polls
 * @subpackage	Components
 * @copyright	Copyright (C) 2009 - 2014 corejoomla.com. All rights reserved.
 * @author		Maverick
 * @link		http://www.corejoomla.com/
 * @license		License GNU General Public License version 2 or later
 */
defined('_JEXEC') or die;

class CommunityPollsViewDashboard extends JViewLegacy
{
	protected $items;
	protected $pagination;
	protected $state;

	public function display($tpl = null)
	{
		$app = JFactory::getApplication();
		
		if ($this->getLayout() !== 'modal')
		{
			CommunityPollsHelper::addSubmenu('dashboard');
		}

		$this->polls = $this->get('Polls');
		$this->votes = $this->get('Votes');
		$this->voters = $this->get('TopVoters');
// 		$this->geoReport = $this->get('GeoLocationReport');

		// Check for errors.
		if (count($errors = $this->get('Errors')))
		{
			JError::raiseError(500, implode("\n", $errors));

			return false;
		}
		
		$version = CJFunctions::get_component_update_check('com_communitypolls', CP_CURR_VERSION);
		$this->version = $version;
		
		$this->addToolbar();
		
		if(APP_VERSION < 3){
			
			$tpl = 'j25';
		}

		parent::display($tpl);
	}

	protected function addToolbar()
	{
		$canDo = CommunityPollsHelper::getActions(0, 0, 'com_communitypolls');
		$user  = JFactory::getUser();

		// Get the toolbar object instance
		$bar = JToolBar::getInstance('toolbar');
		JToolbarHelper::title(JText::_('COM_COMMUNITYPOLLS_DASHBOARD_TITLE'), 'stack dashboard');

		if ($user->authorise('core.admin', 'com_communitypolls'))
		{
			JToolbarHelper::preferences('com_communitypolls');
		}
	}
}
