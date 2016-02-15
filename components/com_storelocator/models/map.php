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
class StorelocatorModelMap extends JModelList {

    /**
	 * LocatePlaces data array
	 *
	 * @var array
	 */
	var $_data;
	
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
		$query->select('#__storelocator_cats.name AS categories_name');
		$query->join('LEFT', '#__storelocator_cats AS #__storelocator_cats ON #__storelocator_cats.id = a.catid');
		// Join over the foreign key 'tags'
		$query->select('#__storelocator_tags.name AS tags_name');
		$query->join('LEFT', '#__storelocator_tags AS #__storelocator_tags ON #__storelocator_tags.id = a.tags');
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
	
	
	

	/**
	 * Returns the query
	 * @return string The query to be used to retrieve the rows from the database
	 */
	protected function _buildQuery()
	{
		$this->setGroupLimits();
		
		$query = 	' SELECT lp.*, cats.name as category, mt.image_url as markertype, tag.name as tags' .
					' FROM #__storelocator_locations as lp ' .
					' LEFT JOIN #__storelocator_cats as cats ON lp.catid = cats.id '.
					' LEFT JOIN #__storelocator_tags as tag ON lp.tags = tag.id'.
					' LEFT JOIN #__storelocator_marker_types as mt ON mt.id = cats.markerid ' .
					' GROUP BY lp.id';

		return $query;
	}

	
	
	protected function _AddWhere($categories, $tags, $featstate = 0, $text_query = '', $nameSearchMode = 0)
	{		
		$db = $this->getDBO();
		$where = array();
		
		// Published
		$where[] = 'lp.state = 1';
		
		// Published Date
		$jnow		= JFactory::getDate();
		$now		= $jnow->toSql();
		$nullDate	= $this->_db->getNullDate();
		$where[] = '( lp.publish_up = '.$this->_db->Quote($nullDate).' OR lp.publish_up <= '.$this->_db->Quote($now).' )';
		$where[] = '( lp.publish_down = '.$this->_db->Quote($nullDate).' OR lp.publish_down >= '.$this->_db->Quote($now).' )';
		
		// Categories
		if( is_array($categories) && count($categories) > 0)
		{
			// clean up
			for($i=0;$i<count($categories);$i++)
				$categories[$i] = intval($categories[$i]);
				
			// make category array into a string
			$catstr = implode(', ',$categories);
			
			$where[] = "cats.id IN ($catstr)";
		}
		
		// Tags
		if( is_array($tags) && count($tags) > 0)
		{
			// clean up
			for($i=0;$i<count($tags);$i++)
				$tags[$i] = intval($tags[$i]);
				
			// make tag array into a string
			$where[] = "tags.id IN (".implode(', ',$tags).")";
		}
		
		//Access Level
		$user = JFactory::getUser();
		$aid = max($user->getAuthorisedViewLevels());
		$where[] = 'lp.access <= '.$aid;

		//Featured
		if ($featstate)
			$where[] = 'lp.featured = 1';
			
		// Text Query
		if(!empty($text_query))
		{			
			$text_query = $db->quote( '%' . $text_query . '%', true );
									
			switch($nameSearchMode)
			{
				case 0:
					$where[] = "lp.name LIKE " . $text_query;
					break;
				case 1:
					$where[] = "(lp.cust1 LIKE " . $text_query . " OR lp.cust2 LIKE " . $text_query . " OR lp.cust3 LIKE " . $text_query . " OR lp.cust4 LIKE " . $text_query . " OR lp.cust5 LIKE ". $text_query . ")";
					break;
				case 2:
					$where[] = "(lp.cust1 LIKE " . $text_query . " OR lp.cust2 LIKE " . $text_query . " OR lp.cust3 LIKE " . $text_query . " OR lp.cust4 LIKE " .
								$text_query . " OR lp.cust5 LIKE ". $text_query . " OR lp.name LIKE " . $text_query . ")"; 			
					break;
			}
		}
		
		$query = " WHERE ".implode(' AND ', $where);
								
		return $query;
	
	}
	
	
	protected function _addHaving($tags, $tagModeAND, $distance)
	{
		$having = array();
		
		// Tags
		if( $tagModeAND && is_array($tags) && count($tags) > 0)
			$having[] = "COUNT(lp.id) = " . count($tags);
			
		// Distance
		if (doubleval($distance) > 0)
			$having[] = "distance < " . doubleval($distance);
			
		$query = (!count($having))?'':" HAVING ".implode(' AND ', $having);
		return $query;
	}
	
	public function getDataByLocation(	$lat, $lng, $radius, $categories = array(), $tags = array(), $map_units = 1, $max_search_results = 100, 
								$search_order = 'distance', $search_dir = 'ASC', $featstate = 0, $name_search = '', $tagModeAND = 0, $nameSearchMode = 0)
	{
		$this->setGroupLimits();
					
		$multiFactor = $map_units == 1 ? 3959 : 6371;
		
		// Search the rows in the table
		$query = sprintf("SELECT lp.*, 
							( %s * acos( cos( radians('%s') ) * 
							cos( radians( `lat` ) ) * cos( radians( `long` ) - radians('%s') ) + 
							sin( radians('%s') ) * sin( radians( lat ) ) ) ) 
							AS distance, mt.image_url as markertype, GROUP_CONCAT(DISTINCT cats.name) AS categories_name, GROUP_CONCAT(DISTINCT tags.name) AS tags_name   
							FROM #__storelocator_locations as lp 
							LEFT JOIN #__storelocator_cats as cats ON  find_in_set(cats.id, lp.catid) 
							LEFT JOIN #__storelocator_marker_types as mt ON mt.id = cats.markerid 
							LEFT JOIN #__storelocator_tags as tags ON find_in_set(tags.id, lp.tags)  
							%s  
							GROUP BY lp.id 
							%s 
							ORDER BY %s %s". ' LIMIT %d',
		  $multiFactor,
		  doubleval($lat),
		  doubleval($lng),
		  doubleval($lat),
		  $this->_AddWhere($categories, $tags, $featstate, $name_search, $nameSearchMode),
		  $this->_addHaving($tags, $tagModeAND, doubleval($radius)),
		  $search_order,
		  $search_dir,
		  intval($max_search_results)  
		  );
		  
		  $this->_results = $this->_getList( $query );
		  
		  return $this->_results;
	}
	
	public function getAllDataByCat($categories = array(), $tags = array(), $map_units = 1, $max_search_results = 100, $load_order = 'lp.name', $load_dir = 'ASC', $featstate = 0, $tagModeAND = 0)
	{
	
		$this->setGroupLimits();
		
		// Search the rows in the table
		$query = "SELECT lp.*, 0 AS distance, mt.image_url as markertype, GROUP_CONCAT(DISTINCT cats.name) AS categories_name, GROUP_CONCAT(DISTINCT tags.name) AS tags_name 
					FROM #__storelocator_locations as lp 
					LEFT JOIN #__storelocator_cats as cats ON  find_in_set(cats.id, lp.catid) 
					LEFT JOIN #__storelocator_marker_types as mt ON mt.id = cats.markerid 
					LEFT JOIN #__storelocator_tags as tags ON find_in_set(tags.id, lp.tags) ". 
					$this->_AddWhere($categories, $tags, $featstate) . 
					' GROUP BY lp.id '.
					$this->_addHaving($tags, $tagModeAND, 0).
					" ORDER BY $load_order $load_dir " . 
					' LIMIT '. (int)$max_search_results;
					
			//print str_replace('#_', 'joom',$query); die();	
		  
		  $this->_results = $this->_getList( $query );
		  return $this->_results;
	}
	
	public function getAllDataByName($text_query, $categories = array(), $tags = array(), $map_units = 1, $max_search_results = 100, $load_order = 'lp.name', $load_dir = 'ASC', $featstate = 0, $tagModeAND = 0, $nameSearchMode = 0)
	{
		$this->setGroupLimits();
		
		
		// Search the rows in the table
		$query = "SELECT lp.*, 0 AS distance, mt.image_url as markertype, GROUP_CONCAT(DISTINCT cats.name) AS categories_name, GROUP_CONCAT(DISTINCT tags.name) AS tags_name 
					FROM #__storelocator_locations as lp 
					 JOIN #__storelocator_cats as cat ON lp.catid = cat.id 
					LEFT JOIN #__storelocator_cats as cats ON  find_in_set(cats.id, lp.catid) 
					LEFT JOIN #__storelocator_marker_types as mt ON mt.id = cats.markerid 
					LEFT JOIN #__storelocator_tags as tags ON find_in_set(tags.id, lp.tags) ". 
					$this->_AddWhere($categories, $tags, $featstate, $text_query, $nameSearchMode) .
					' GROUP BY lp.id '.
					$this->_addHaving($tags, $tagModeAND, 0) .
					" ORDER BY $load_order $load_dir"  .
					" LIMIT ". (int)$max_search_results;
					
			//print str_replace('#_', 'jos',$query); die();
		  		  
		  $this->_results = $this->_getList( $query );
		  
		  return $this->_results;
	}
	
	public function getCategories($order_by = 'text ASC')
	{
		$query = "SELECT id as value, name as text FROM #__storelocator_cats ORDER BY ".$order_by;
		return $this->_getList( $query );
	}
	
	public function getTags($order_by = 'text ASC')
	{
		$query = "SELECT tag.id as value, tag.name as text, mt.image_url as marker_url "
				."FROM #__storelocator_tags as tag "
				."LEFT JOIN #__storelocator_marker_types as mt ON mt.id = tag.marker_id "
				."ORDER BY ".$order_by;
		 
		return $this->_getList( $query );
	}
	
	
	
	
	protected function setGroupLimits()
	{
		$db = $this->getDBO();
		$db->setQuery('SET SESSION group_concat_max_len=15000');
		$db->execute();	
	}
	
	public function logSearch( $search_limit, $search_limit_period, $center_lat, $center_lng, $search_data )
	{
		$db = $this->getDBO();
		$logCount = 0;
		$limited = false;
		
		// Check count of searches by IP within Search Limit Period
		if ($search_limit>0)
		{
			$period = strtotime($search_limit_period);
			
			$logquery = sprintf(	"SELECT COUNT(*) FROM #__storelocator_log_search WHERE `limited` = 0 AND `ipaddress` = '%s' AND UNIX_TIMESTAMP(`search_time`) > '%s'",
								$_SERVER['REMOTE_ADDR'],
								$period
								);
			$db->setQuery($logquery);								
			$logCount = $db->loadResult($logquery);
						
			if ($logCount >= $search_limit)
				$limited = true;
		}
		
		$search_data['log_count'] = $logCount;
		
		$loggingq = sprintf(	"INSERT INTO #__storelocator_log_search (`ipaddress`, `query`, `lat`, `long`, `limited`) VALUES ( '%s', %s, %s, %s, %d  )",
								$_SERVER['REMOTE_ADDR'],
								$this->_db->Quote(json_encode($search_data)),
								doubleval($center_lat),
								doubleval($center_lng),
								$limited?1:0);
								
		$db->setQuery( $loggingq );
		$db->execute();
		
		return $limited;		
	}
	
}
