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

class CommunityPollsViewPoll extends JViewLegacy
{
	protected $form;
	protected $item;
	protected $state;

	public function display($tpl = null)
	{
		$app = JFactory::getApplication();
		$model = $this->getModel();
		$model->setState('load.answers', true);
		
		if ($this->getLayout() == 'edit')
		{
			$this->form		= $this->get('Form');
		}
		
		$this->item		= $this->get('Item');
		$this->state	= $this->get('State');
		$this->canDo	= CommunityPollsHelper::getActions($this->state->get('filter.category_id'), 0, 'com_communitypolls');

		// Check for errors.
		if (count($errors = $this->get('Errors')))
		{
			JError::raiseError(500, implode("\n", $errors));
			return false;
		}

		if ($this->getLayout() == 'modal')
		{
			$this->form->setFieldAttribute('language', 'readonly', 'true');
			$this->form->setFieldAttribute('catid', 'readonly', 'true');
		}

		$params	= &$this->state->params;
		$this->params = $params;
		$this->theme  = $this->params->get('theme', 'default');
		
		if ($this->getLayout() == 'edit')
		{
			$this->addToolbar();
		}
		else 
		{
			$pollId = JFactory::getApplication()->input->get('id', 0);
			$vmodel = JModelLegacy::getInstance('Votes', 'CommunityPollsModel', array('ignore_request' => true));
			$vmodel->setState('params', $this->params);
			$vmodel->setState('filter.poll_id', $pollId);
			
			$this->items = $vmodel->getItems();
			$this->pagination = $vmodel->getPagination();
			
			$canDo = $this->canDo;
			$userId	= JFactory::getUser()->get('id');
			JToolbarHelper::title(JText::_('COM_COMMUNITYPOLLS_PAGE_POLL_DETAILS'), 'pencil-2 poll-add');
			
			if ($canDo->get('core.edit') || ($canDo->get('core.edit.own') && $this->item->created_by == $userId))
			{
				JToolBarHelper::custom( 'votes.clear', 'refresh.png', 'refresh.png', JText::_('COM_COMMUNITYPOLLS_CLEAR_VOTES'), false, false );
				
			}
			
			if ($canDo->get('core.delete'))
			{
				JToolbarHelper::deleteList('', 'votes.delete', 'JTOOLBAR_DELETE');
			}
			
			JToolbarHelper::cancel('poll.cancel');
		}
		
		parent::display($tpl);
	}

	protected function addToolbar()
	{
		JFactory::getApplication()->input->set('hidemainmenu', true);
		$user		= JFactory::getUser();
		$userId		= $user->get('id');
		$isNew		= ($this->item->id == 0);
		$checkedOut	= !($this->item->checked_out == 0 || $this->item->checked_out == $userId);

		// Built the actions for new and existing records.
		$canDo		= $this->canDo;
		JToolbarHelper::title(JText::_('COM_COMMUNITYPOLLS_PAGE_' . ($checkedOut ? 'VIEW_POLL' : ($isNew ? 'ADD_POLL' : 'EDIT_POLL'))), 'pencil-2 poll-add');

		// For new records, check the create permission.
		if ($isNew && (count($user->getAuthorisedCategories('com_communitypolls', 'core.create')) > 0))
		{
			JToolbarHelper::apply('poll.apply');
			JToolbarHelper::save('poll.save');
			JToolbarHelper::save2new('poll.save2new');
			JToolbarHelper::cancel('poll.cancel');
		}
		else
		{
			// Can't save the record if it's checked out.
			if (!$checkedOut)
			{
				// Since it's an existing record, check the edit permission, or fall back to edit own if the owner.
				if ($canDo->get('core.edit') || ($canDo->get('core.edit.own') && $this->item->created_by == $userId))
				{
					JToolbarHelper::apply('poll.apply');
					JToolbarHelper::save('poll.save');

					// We can save this record, but check the create permission to see if we can return to make a new one.
					if ($canDo->get('core.create'))
					{
						JToolbarHelper::save2new('poll.save2new');
					}
				}
			}

			// If checked out, we can still save
			if ($canDo->get('core.create'))
			{
				JToolbarHelper::save2copy('poll.save2copy');
			}

			if ($this->state->params->get('save_history', 0) && $user->authorise('core.edit'))
			{
				JToolbarHelper::versions('com_communitypolls.poll', $this->item->id);
			}

			JToolbarHelper::cancel('poll.cancel', 'JTOOLBAR_CLOSE');
		}

		JToolbarHelper::divider();
		JToolbarHelper::help('JHELP_COMMUNIYPOLLS_POLL_MANAGER_EDIT');
	}
}
