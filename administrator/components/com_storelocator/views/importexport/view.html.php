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
class StorelocatorViewImportexport extends JViewLegacy
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
		
		

		// Check for errors.
		if (count($errors = $this->get('Errors'))) {
			throw new Exception(implode("\n", $errors));
		}
        
		StorelocatorHelper::addSubmenu('importexport');
        
		$this->addToolbar();
        
        $this->sidebar = JHtmlSidebar::render();
		

		$categories			 = $this->get('Categories');
		$this->categories	 = JHTML::_('select.genericlist',   $categories, 'exportcats[]', 'class="inputbox" size="6" multiple="multiple" style="width:180px;" ', 'id', 'name');
		
		
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

		JToolBarHelper::title(JText::_('COM_STORELOCATOR_TITLE_IMPORTEXPORT'), 'importexport.png');

		if ($canDo->get('core.admin')) {
			JToolBarHelper::preferences('com_storelocator');
		}
		
		JToolBarHelper::help('help', true);
        
        //Set sidebar action - New in 3.0
		JHtmlSidebar::setAction('index.php?option=com_storelocator&view=importexport');
        
        $this->extra_sidebar = '';
        //
        
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
