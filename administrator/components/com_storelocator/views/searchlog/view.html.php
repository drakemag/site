<?php
/**
 * @version     2.0.0
 * @package     com_storelocator
 * @copyright   Copyright (C) 2013. Sysgen Media LLC. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 * @author      Sysgen Media <support@sysgenmedia.com> - http://www.sysgenmedia.com
 */

// No direct access
defined('_JEXEC') or die;

jimport('joomla.application.component.view');

/**
 * View class for a list of Storelocator.
 */
class StorelocatorViewSearchlog extends JViewLegacy
{
	protected $items;
	protected $pagination;
	protected $state;

	/**
	 * Display the view
	 */
	public function display($tpl = null)
	{
		$this->state		= $this->get('State');
		$this->items		= $this->get('Items');
		$this->pagination	= $this->get('Pagination');

		// Check for errors.
		if (count($errors = $this->get('Errors'))) {
			throw new Exception(implode("\n", $errors));
		}
        
		StorelocatorHelper::addSubmenu('searchlog');
        
		$this->addToolbar();
        
        $this->sidebar = JHtmlSidebar::render();
		parent::display($tpl);
	}

	/**
	 * Add the page title and toolbar.
	 *
	 * @since	1.6
	 */
	protected function addToolbar()
	{
		require_once JPATH_COMPONENT.'/helpers/storelocator.php';

		$state	= $this->get('State');
		$canDo	= StorelocatorHelper::getActions($state->get('filter.category_id'));

		JToolBarHelper::title(JText::_('COM_STORELOCATOR_TITLE_SEARCHLOG'), 'searchlog.png');

        //Check if the form exists before showing the add/edit buttons
        $formPath = JPATH_COMPONENT_ADMINISTRATOR.'/views/';
        if (file_exists($formPath)) {

            if ($canDo->get('core.create')) {
			    //JToolBarHelper::addNew('.add','JTOOLBAR_NEW');
		    }

		    if ($canDo->get('core.edit') && isset($this->items[0])) {
			    //JToolBarHelper::editList('.edit','JTOOLBAR_EDIT');
		    }

        }

		if ($canDo->get('core.edit.state')) {

            if (isset($this->items[0]->state)) {
			   // JToolBarHelper::divider();
			    //JToolBarHelper::custom('searchlog.publish', 'publish.png', 'publish_f2.png','JTOOLBAR_PUBLISH', true);
			    //JToolBarHelper::custom('searchlog.unpublish', 'unpublish.png', 'unpublish_f2.png', 'JTOOLBAR_UNPUBLISH', true);
            } else if (isset($this->items[0])) {
                //If this component does not use state then show a direct delete button as we can not trash
                JToolBarHelper::deleteList('', 'searchlog.delete','JTOOLBAR_DELETE');
            }

            if (isset($this->items[0]->state)) {
			    //JToolBarHelper::divider();
			    //JToolBarHelper::archiveList('searchlog.archive','JTOOLBAR_ARCHIVE');
            }
            if (isset($this->items[0]->checked_out)) {
            	//JToolBarHelper::custom('searchlog.checkin', 'checkin.png', 'checkin_f2.png', 'JTOOLBAR_CHECKIN', true);
            }
		}
        
        //Show trash and delete for components that uses the state field
        if (isset($this->items[0]->state)) {
		    if ($state->get('filter.state') == -2 && $canDo->get('core.delete')) {
			    JToolBarHelper::deleteList('', 'searchlog.delete','JTOOLBAR_EMPTY_TRASH');
			    JToolBarHelper::divider();
		    } else if ($canDo->get('core.edit.state')) {
			    JToolBarHelper::trash('searchlog.trash','JTOOLBAR_TRASH');
			    JToolBarHelper::divider();
		    }
        }

		if ($canDo->get('core.admin')) {
			JToolBarHelper::preferences('com_storelocator');
		}
        
        //Set sidebar action - New in 3.0
		JHtmlSidebar::setAction('index.php?option=com_storelocator&view=searchlog');
        
        $this->extra_sidebar = '';
        
        
	}
    
	protected function getSortFields()
	{
		return array(
		'a.id' => JText::_('JGRID_HEADING_ID'),
		'a.ipaddress' => JText::_('COM_STORELOCATOR_SEARCHLOG_IPADDRESS'),
		'a.limited' => JText::_('COM_STORELOCATOR_SEARCHLOG_LIMITED'),
		'a.search_time' => JText::_('COM_STORELOCATOR_SEARCHLOG_SEARCH_TIME'),
		);
	}

    
}
