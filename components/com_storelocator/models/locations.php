<?php

/**
 * @version     2.0.0
 * @package     com_storelocator
 * @copyright   Copyright (C) 2013. Sysgen Media LLC. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 * @author      Sysgen Media <support@sysgenmedia.com> - http://www.sysgenmedia.com
 */
defined('_JEXEC') or die;

jimport('joomla.application.component.modellist');

/**
 * Methods supporting a list of Storelocator records.
 */
class StorelocatorModelLocations extends JModelList {

    /**
     * Constructor.
     *
     * @param    array    An optional associative array of configuration settings.
     * @see        JController
     * @since    1.6
     */
    public function __construct($config = array()) {
        parent::__construct($config);
    }

    /**
     * Method to auto-populate the model state.
     *
     * Note. Calling getState in this method will result in recursion.
     *
     * @since	1.6
     */
    protected function populateState($ordering = null, $direction = null) {
        
        // Initialise variables.
        $app = JFactory::getApplication();

        // List state information
        $limit = $app->getUserStateFromRequest('global.list.limit', 'limit', $app->getCfg('list_limit'));
        $this->setState('list.limit', $limit);

        $limitstart = JFactory::getApplication()->input->getInt('limitstart', 0);
        $this->setState('list.start', $limitstart);
        
        
		if(empty($ordering)) {
			$ordering = 'a.ordering';
		}
        
        // List state information.
        parent::populateState($ordering, $direction);
    }

    /**
     * Build an SQL query to load the list data.
     *
     * @return	JDatabaseQuery
     * @since	1.6
     */
    protected function getListQuery() {
        // Create a new query object.
        $db = $this->getDbo();
        $query = $db->getQuery(true);

        // Select the required fields from the table.
        $query->select(
                $this->getState(
                        'list.select', 'a.*'
                )
        );
        
        $query->from('`#__storelocator_locations` AS a');
        

    // Join over the users for the checked out user.
    $query->select('uc.name AS editor');
    $query->join('LEFT', '#__users AS uc ON uc.id=a.checked_out');
    
		// Join over the foreign key 'catid'
		$query->select('#__storelocator_cats_521367.name AS categories_name_521367');
		$query->join('LEFT', '#__storelocator_cats AS #__storelocator_cats_521367 ON #__storelocator_cats_521367.id = a.catid');
		// Join over the foreign key 'tags'
		$query->select('#__storelocator_tags_524376.name AS tags_name_524376');
		$query->join('LEFT', '#__storelocator_tags AS #__storelocator_tags_524376 ON #__storelocator_tags_524376.id = a.tags');
		// Join over the created by field 'created_by'
		$query->select('created_by.name AS created_by');
		$query->join('LEFT', '#__users AS created_by ON created_by.id = a.created_by');


		// Filter by search in title
		$search = $this->getState('filter.search');
		if (!empty($search)) {
			if (stripos($search, 'id:') === 0) {
				$query->where('a.id = '.(int) substr($search, 3));
			} else {
				$search = $db->Quote('%'.$db->escape($search, true).'%');
                $query->where('( a.name LIKE '.$search.' )');
			}
		}
        


		//Filtering catid
		$filter_catid = $this->state->get("filter.catid");
		if ($filter_catid) {
			$query->where("a.catid = '".$filter_catid."'");
		}

		//Filtering tags
		$filter_tags = $this->state->get("filter.tags");
		if ($filter_tags) {
			$query->where("a.tags = '".$filter_tags."'");
		}

		//Filtering access
		$filter_access = $this->state->get("filter.access");
		if ($filter_access) {
			$query->where("a.access = '".$filter_access."'");
		}        
        
        return $query;
    }

}
