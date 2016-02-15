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
class StorelocatorViewLocations extends JViewLegacy
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
        
		StorelocatorHelper::addSubmenu('locations');
        
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

		JToolBarHelper::title(JText::_('COM_STORELOCATOR_TITLE_LOCATIONS'), 'locations.png');

        //Check if the form exists before showing the add/edit buttons
        $formPath = JPATH_COMPONENT_ADMINISTRATOR.'/views/location';
        if (file_exists($formPath)) {

            if ($canDo->get('core.create')) {
			    JToolBarHelper::addNew('location.add','JTOOLBAR_NEW');
		    }

		    if ($canDo->get('core.edit') && isset($this->items[0])) {
			    JToolBarHelper::editList('location.edit','JTOOLBAR_EDIT');
		    }

        }

		if ($canDo->get('core.edit.state')) {

            if (isset($this->items[0]->state)) {
			    JToolBarHelper::divider();
			    JToolBarHelper::custom('locations.publish', 'publish.png', 'publish_f2.png','JTOOLBAR_PUBLISH', true);
			    JToolBarHelper::custom('locations.unpublish', 'unpublish.png', 'unpublish_f2.png', 'JTOOLBAR_UNPUBLISH', true);
				JToolbarHelper::custom('locations.featured', 'featured.png', 'featured_f2.png', 'JFEATURED', true);
            } else if (isset($this->items[0])) {
                //If this component does not use state then show a direct delete button as we can not trash
                JToolBarHelper::deleteList('', 'locations.delete','JTOOLBAR_DELETE');
            }

            if (isset($this->items[0]->state)) {
			    JToolBarHelper::divider();
			    JToolBarHelper::archiveList('locations.archive','JTOOLBAR_ARCHIVE');
            }
            if (isset($this->items[0]->checked_out)) {
            	JToolBarHelper::custom('locations.checkin', 'checkin.png', 'checkin_f2.png', 'JTOOLBAR_CHECKIN', true);
            }
		}
        
        //Show trash and delete for components that uses the state field
        if (isset($this->items[0]->state)) {
		    if ($state->get('filter.state') == -2 && $canDo->get('core.delete')) {
			    JToolBarHelper::deleteList('', 'locations.delete','JTOOLBAR_EMPTY_TRASH');
			    JToolBarHelper::divider();
		    } else if ($canDo->get('core.edit.state')) {
			    JToolBarHelper::trash('locations.trash','JTOOLBAR_TRASH');
			    JToolBarHelper::divider();
		    }
        }

		if ($canDo->get('core.admin')) {
			JToolBarHelper::preferences('com_storelocator');
		}
		
		JToolBarHelper::help('help', true);
        
        //Set sidebar action - New in 3.0
		JHtmlSidebar::setAction('index.php?option=com_storelocator&view=locations');
        
        $this->extra_sidebar = '';
        jimport('joomla.form.form');
        $options = array();
        JForm::addFormPath(JPATH_COMPONENT . '/models/forms');
        $form = JForm::getInstance('com_storelocator.location', 'location');

        $field = $form->getField('catid');

        $query = $form->getFieldAttribute('catid','query');
        $translate = $form->getFieldAttribute('catid','translate');
        $key = $form->getFieldAttribute('catid','key_field');
        $value = $form->getFieldAttribute('catid','value_field');

        // Get the database object.
        $db = JFactory::getDBO();

        // Set the query and get the result list.
        $db->setQuery($query);
        $items = $db->loadObjectlist();

        // Build the field options.
        if (!empty($items))
        {
            foreach ($items as $item)
            {
                if ($translate == true)
                {
                    $options[] = JHtml::_('select.option', $item->$key, JText::_($item->$value));
                }
                else
                {
                    $options[] = JHtml::_('select.option', $item->$key, $item->$value);
                }
            }
        }

        JHtmlSidebar::addFilter(
            JText::_('Select Category'),
            'filter_catid',
            JHtml::_('select.options', $options, "value", "text", $this->state->get('filter.catid')),
            true
        );        //Filter for the field ".tags;
        jimport('joomla.form.form');
        $options = array();
        JForm::addFormPath(JPATH_COMPONENT . '/models/forms');
        $form = JForm::getInstance('com_storelocator.location', 'location');

        $field = $form->getField('tags');

        $query = $form->getFieldAttribute('tags','query');
        $translate = $form->getFieldAttribute('tags','translate');
        $key = $form->getFieldAttribute('tags','key_field');
        $value = $form->getFieldAttribute('tags','value_field');

        // Get the database object.
        $db = JFactory::getDBO();

        // Set the query and get the result list.
        $db->setQuery($query);
        $items = $db->loadObjectlist();

        // Build the field options.
        if (!empty($items))
        {
            foreach ($items as $item)
            {
                if ($translate == true)
                {
                    $options[] = JHtml::_('select.option', $item->$key, JText::_($item->$value));
                }
                else
                {
                    $options[] = JHtml::_('select.option', $item->$key, $item->$value);
                }
            }
        }

        JHtmlSidebar::addFilter(
            JText::_('Select Tags'),
            'filter_tags',
            JHtml::_('select.options', $options, "value", "text", $this->state->get('filter.tags')),
            true
        );
		JHtmlSidebar::addFilter(

			JText::_('JOPTION_SELECT_PUBLISHED'),

			'filter_published',

			JHtml::_('select.options', JHtml::_('jgrid.publishedOptions'), "value", "text", $this->state->get('filter.state'), true)

		);

		JHtmlSidebar::addFilter(
			JText::_("JOPTION_SELECT_ACCESS"),
			'filter_access',
			JHtml::_('select.options', JHtml::_("access.assetgroups", true, true), "value", "text", $this->state->get('filter.access'), true)

		);

        
	}
    
	protected function getSortFields()
	{
		return array(
		'a.id' => JText::_('JGRID_HEADING_ID'),
		'a.name' => JText::_('COM_STORELOCATOR_LOCATIONS_NAME'),
		'a.address' => JText::_('COM_STORELOCATOR_LOCATIONS_ADDRESS'),
		'a.catid' => JText::_('COM_STORELOCATOR_LOCATIONS_CATID'),
		'a.state' => JText::_('JSTATUS'),
		'a.featured' => JText::_('COM_STORELOCATOR_LOCATIONS_FEATURED'),
		'a.ordering' => JText::_('JGRID_HEADING_ORDERING'),
		'a.checked_out' => JText::_('COM_STORELOCATOR_LOCATIONS_CHECKED_OUT'),
		'a.checked_out_time' => JText::_('COM_STORELOCATOR_LOCATIONS_CHECKED_OUT_TIME'),
		'a.created_by' => JText::_('COM_STORELOCATOR_LOCATIONS_CREATED_BY'),
		'a.access' => JText::_('COM_STORELOCATOR_LOCATIONS_ACCESS'),
		);
	}

    
}
