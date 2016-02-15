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
class StorelocatorViewBatchgeocoding extends JViewLegacy
{
	protected $items;
	protected $state;

	/**
	 * Display the view
	 */
	public function display($tpl = null)
	{
		$this->state		= $this->get('State');
		$this->items		= $this->get('Items');
		$this->nonCodedCount = count($this->items);

		// Check for errors.
		if (count($errors = $this->get('Errors'))) {
			throw new Exception(implode("\n", $errors));
		}
        
		StorelocatorHelper::addSubmenu('batchgeocoding');
		

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

		JToolBarHelper::title(JText::_('COM_STORELOCATOR_TITLE_BATCHGEOCODING'), 'batchgeocoding.png');

		if ($canDo->get('core.admin')) {
			JToolBarHelper::preferences('com_storelocator');
		}
		
		JToolBarHelper::help('help', true);
        
        //Set sidebar action - New in 3.0
		JHtmlSidebar::setAction('index.php?option=com_storelocator&view=batchgeocoding');
        
        $this->extra_sidebar = '';
        //
        
	}
    

    
}
