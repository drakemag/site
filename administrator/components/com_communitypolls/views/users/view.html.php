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

class CommunityPollsViewUsers extends JViewLegacy
{
	protected $items;
	protected $pagination;
	protected $state;

	public function display($tpl = null)
	{
		if ($this->getLayout() !== 'modal')
		{
			CommunityPollsHelper::addSubmenu('users');
		}
		
		$this->items         = $this->get('Items');
		$this->pagination    = $this->get('Pagination');
		$this->state         = $this->get('State');
		$this->filterForm    = $this->get('FilterForm');
		
		// Check for errors.
		if (count($errors = $this->get('Errors')))
		{
			JError::raiseError(500, implode("\n", $errors));

			return false;
		}

		// We don't need toolbar in the modal window.
		if ($this->getLayout() !== 'modal')
		{
			$this->addToolbar();
		}
		
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

		JToolbarHelper::title(JText::_('COM_COMMUNITYPOLLS_USERS_TITLE'), 'stack user');

		if ($user->authorise('core.admin', 'com_communitypolls'))
		{
			JToolbarHelper::custom('users.rebuild', 'refresh.png', 'refresh_f2.png', 'JTOOLBAR_REBUILD', false);
			JToolbarHelper::preferences('com_communitypolls');
		}

		JToolbarHelper::help('JHELP_COMMUNITYPOLLS_POLL_MANAGER');
	}

	protected function getSortFields()
	{
		return array(
			'ua.name' => JText::_('JGLOBAL_TITLE'),
			'a.polls' => JText::_('COM_COMMUNITYPOLLS_POLLS'),
			'a.votes' => JText::_('COM_COMMUNITYPOLLS_VOTES'),
			'a.last_poll' => JText::_('COM_COMMUNITYPOLLS_LAST_POLL'),
			'a.last_voted' => JText::_('COM_COMMUNITYPOLLS_LAST_VOTED'),
			'a.id' => JText::_('JGRID_HEADING_ID')
		);
	}
}
