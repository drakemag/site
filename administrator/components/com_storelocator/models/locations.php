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
class StorelocatorModellocations extends JModelList
{

    /**
     * Constructor.
     *
     * @param    array    An optional associative array of configuration settings.
     * @see        JController
     * @since    1.6
     */
    public function __construct($config = array())
    {
        if (empty($config['filter_fields'])) {
            $config['filter_fields'] = array(
                                'id', 'a.id',
                'name', 'a.name',
                'address', 'a.address',
                'lat', 'a.lat',
                'long', 'a.long',
                'catid', 'a.catid',
                'tags', 'a.tags',
                'state', 'a.state',
                'featured', 'a.featured',
                'description', 'a.description',
                'phone', 'a.phone',
                'website', 'a.website',
                'facebook', 'a.facebook',
                'twitter', 'a.twitter',
                'email', 'a.email',
                'cust1', 'a.cust1',
                'cust2', 'a.cust2',
                'cust3', 'a.cust3',
                'cust4', 'a.cust4',
                'cust5', 'a.cust5',
                'ordering', 'a.ordering',
                'publish_up', 'a.publish_up',
                'publish_down', 'a.publish_down',
                'created_by', 'a.created_by',
                'access', 'a.access', 'access_level',

            );
        }

        parent::__construct($config);
    }


	/**
	 * Method to auto-populate the model state.
	 *
	 * Note. Calling getState in this method will result in recursion.
	 */
	protected function populateState($ordering = null, $direction = null)
	{
		// Initialise variables.
		$app = JFactory::getApplication('administrator');

		// Load the filter state.
		$search = $app->getUserStateFromRequest($this->context.'.filter.search', 'filter_search');
		$this->setState('filter.search', $search);

		$published = $app->getUserStateFromRequest($this->context.'.filter.state', 'filter_published', '', 'string');
		$this->setState('filter.state', $published);
        
        
		//Filtering catid
		$this->setState('filter.catid', $app->getUserStateFromRequest($this->context.'.filter.catid', 'filter_catid', '', 'string'));

		//Filtering tags
		$this->setState('filter.tags', $app->getUserStateFromRequest($this->context.'.filter.tags', 'filter_tags', '', 'string'));

		//Filtering access
		$this->setState('filter.access', $app->getUserStateFromRequest($this->context.'.filter.access', 'filter_access', '', 'string'));

        
		// Load the parameters.
		$params = JComponentHelper::getParams('com_storelocator');
		$this->setState('params', $params);

		// List state information.
		parent::populateState('a.name', 'asc');
	}

	/**
	 * Method to get a store id based on model configuration state.
	 *
	 * This is necessary because the model is used by the component and
	 * different modules that might need different sets of data or different
	 * ordering requirements.
	 *
	 * @param	string		$id	A prefix for the store id.
	 * @return	string		A store id.
	 * @since	1.6
	 */
	protected function getStoreId($id = '')
	{
		// Compile the store id.
		$id.= ':' . $this->getState('filter.search');
		$id.= ':' . $this->getState('filter.state');

		return parent::getStoreId($id);
	}

	/**
	 * Build an SQL query to load the list data.
	 *
	 * @return	JDatabaseQuery
	 * @since	1.6
	 */
	protected function getListQuery()
	{
		// Create a new query object.
		$db		= $this->getDbo();
		$query	= $db->getQuery(true);

		// Select the required fields from the table.
		$query->select(
			$this->getState(
				'list.select',
				'a.*'
			)
		);
		$query->from('`#__storelocator_locations` AS a');


    // Join over the users for the checked out user.
    $query->select('uc.name AS editor');
    $query->join('LEFT', '#__users AS uc ON uc.id=a.checked_out');
    
		// Join over the foreign key 'catid'
		$query->select('#__storelocator_cats.name AS categories_name');
		$query->join('LEFT', '#__storelocator_cats AS #__storelocator_cats ON #__storelocator_cats.id = a.catid');
		
		// Join over the asset groups.
		$query->select('ag.title AS access_level');
		$query->join('LEFT', '#__viewlevels AS ag ON ag.id = a.access');
		
		// Join over the foreign key 'tags'
		$query->select('#__storelocator_tags.name AS tags_name');
		$query->join('LEFT', '#__storelocator_tags AS #__storelocator_tags ON #__storelocator_tags.id = a.tags');
		
		// Join over the user field 'created_by'
		$query->select('created_by.name AS created_by');
		$query->join('LEFT', '#__users AS created_by ON created_by.id = a.created_by');


    // Filter by published state
    $published = $this->getState('filter.state');
    if (is_numeric($published)) {
        $query->where('a.state = '.(int) $published);
    } else if ($published === '') {
        $query->where('(a.state IN (0, 1))');
    }
    

		// Filter by search in title or address
		$search = $this->getState('filter.search');
		if (!empty($search)) {
			if (stripos($search, 'id:') === 0) {
				$query->where('a.id = '.(int) substr($search, 3));
			} else {
				$search = $db->Quote('%'.$db->escape($search, true).'%');
                $query->where('( a.name LIKE '.$search.' OR a.address LIKE '.$search.' )');
			}
		}
        


		//Filtering catid
		$filter_catid = $this->state->get("filter.catid");
		if ($filter_catid) {
			$query->where("a.catid = '".$db->escape($filter_catid)."'");
		}

		//Filtering tags
		$filter_tags = $this->state->get("filter.tags");
		if ($filter_tags) {
			$query->where("a.tags = '".$db->escape($filter_tags)."'");
		}

		//Filtering access
		$filter_access = $this->state->get("filter.access");
		if ($filter_access) {
			$query->where("a.access = '".$db->escape($filter_access)."'");
		}        
        
        
		// Add the list ordering clause.
        $orderCol	= $this->state->get('list.ordering');
        $orderDirn	= $this->state->get('list.direction');
        if ($orderCol && $orderDirn) {
            $query->order($db->escape($orderCol.' '.$orderDirn));
        }

		return $query;
	}
}
