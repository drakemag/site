<?php
/**
 * @package		SP Transfer
 * @subpackage	Components
 * @copyright	SP CYEND - All rights reserved.
 * @author		SP CYEND
 * @link		http://www.cyend.com
 * @license GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
 */

// No direct access.
defined('_JEXEC') or die;

jimport('joomla.application.component.modellist');

/**
 * Methods supporting a list of newsfeed records.
 *
 * @package		Joomla.Administrator
 * @subpackage	com_newsfeeds
 * @since		1.6
 */
class SPTransferModelNewsfeeds extends JModelList
{
	/**
	 * Constructor.
	 *
	 * @param	array	An optional associative array of configuration settings.
	 * @see		JController
	 * @since	1.6
	 */
	public function __construct($config = array())
	{
		if (empty($config['filter_fields'])) {
			$config['filter_fields'] = array(
				'id', 'a.id',
				'name', 'a.name',
				'alias', 'a.alias',
				'checked_out', 'a.checked_out',
				'checked_out_time', 'a.checked_out_time',
				'catid', 'a.catid', 'category_title',
				'published', 'a.published',
				'access', 'a.access', 'access_level',
				'created', 'a.created',
				'created_by', 'a.created_by',
				'ordering', 'a.ordering',
				'language', 'a.language',
				'publish_up', 'a.publish_up',
				'publish_down', 'a.publish_down',
				'cache_time', 'a.cache_time',
				'numarticles',
			);
		}

		parent::__construct($config);
	}

	/**
	 * Method to auto-populate the model state.
	 *
	 * Note. Calling getState in this method will result in recursion.
	 *
	 * @since	1.6
	 */
	protected function populateState($ordering = null, $direction = null)
	{
		// Initialise variables.
		$app = JFactory::getApplication('administrator');

		// Load the filter state.
		$search = $this->getUserStateFromRequest($this->context.'.filter.search', 'filter_search');
		$this->setState('filter.search', $search);

		$accessId = $this->getUserStateFromRequest($this->context.'.filter.access', 'filter_access', null, 'int');
		$this->setState('filter.access', $accessId);

		$published = $this->getUserStateFromRequest($this->context . '.filter.published', 'filter_published', '');
        $this->setState('filter.published', $published);

		$categoryId = $this->getUserStateFromRequest($this->context.'.filter.category_id', 'filter_category_id', null);
		$this->setState('filter.category_id', $categoryId);        

		$language = $this->getUserStateFromRequest($this->context.'.filter.language', 'filter_language', '');
		$this->setState('filter.language', $language);

		// Load the parameters.
		$params = JComponentHelper::getParams('com_newsfeeds');
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
	 * @param	string	A prefix for the store id.
	 *
	 * @return	string	A store id.
	 */
	protected function getStoreId($id = '')
	{
		// Compile the store id.
		$id	.= ':'.$this->getState('filter.search');
		$id	.= ':'.$this->getState('filter.access');
		$id	.= ':'.$this->getState('filter.published');
		$id	.= ':'.$this->getState('filter.category_id');
		$id .= ':'.$this->getState('filter.language');

		return parent::getStoreId($id);
	}

	/**
	 * Build an SQL query to load the list data.
	 *
	 * @return	JDatabaseDriverQuery
	 */
	protected function getListQuery()
	{
		// Create a new query object.
		$db		= $this->getDboSource();
		$query	= $db->getQuery(true);
		$user	= JFactory::getUser();

		// Select the required fields from the table.
		$query->select(
			$this->getState(
				'list.select',
				'a.id, a.name, a.alias, a.checked_out, a.checked_out_time, a.catid,' .
				'a.numarticles, a.cache_time, ' .
				' a.published, a.access, a.ordering, a.language, a.publish_up, a.publish_down'
			)
		);
		$query->from($db->quoteName('#__newsfeeds').' AS a');

		// Join over the language
		$query->select('l.title AS language_title');
		$query->join('LEFT', $db->quoteName('#__languages').' AS l ON l.lang_code = a.language');

		// Join over the users for the checked out user.
		$query->select('uc.name AS editor');
		$query->join('LEFT', '#__users AS uc ON uc.id=a.checked_out');

		// Join over the asset groups.
		$query->select('ag.title AS access_level');
		$query->join('LEFT', '#__viewlevels AS ag ON ag.id = a.access');

		// Join over the categories.
		$query->select('c.title AS category_title');
		$query->join('LEFT', '#__categories AS c ON c.id = a.catid');

		// Filter by access level.
		if ($access = $this->getState('filter.access')) {
			$query->where('a.access = '.(int) $access);
		}

		// Implement View Level Access
		if (!$user->authorise('core.admin'))
		{
		    $groups	= implode(',', $user->getAuthorisedViewLevels());
			$query->where('a.access IN ('.$groups.')');
		}

		// Filter by published state.
		$published = $this->getState('filter.published');
		if (is_numeric($published)) {
			$query->where('a.published = '.(int) $published);
		}
		elseif ($published === '') {
			$query->where('(a.published IN (0, 1))');
		}

		// Filter by category.
		$categoryId = $this->getState('filter.category_id');
		if (is_numeric($categoryId)) {
			$query->where('a.catid = ' . (int) $categoryId);
		}

		// Filter by search in title
		$search = $this->getState('filter.search');
		if (!empty($search))
		{
			if (stripos($search, 'id:') === 0) {
				$query->where('a.id = '.(int) substr($search, 3));
			}
			else
			{
				$search = $db->Quote('%'.$db->escape($search, true).'%');
				$query->where('(a.name LIKE '.$search.' OR a.alias LIKE '.$search.')');
			}
		}

		// Filter on the language.
		if ($language = $this->getState('filter.language')) {
			$query->where('a.language = ' . $db->quote($language));
		}

		// Add the list ordering clause.
		$orderCol	= $this->state->get('list.ordering');
		$orderDirn	= $this->state->get('list.direction');
		if ($orderCol == 'a.ordering' || $orderCol == 'category_title') {
			$orderCol = 'c.title '.$orderDirn.', a.ordering';
		}
		$query->order($db->escape($orderCol.' '.$orderDirn));

		//echo nl2br(str_replace('#__','jos_',$query));
		return $query;
	}
    
    private function getDboSource() {
        $params = JComponentHelper::getParams('com_sptransfer');
        $option = array(); //prevent problems 
        $option['driver'] = $params->get("driver", 'mysqli');            // Database driver name
        $option['host'] = $params->get("host", 'localhost');    // Database host name
        $option['user'] = $params->get("source_user_name", '');       // User for database authentication
        $option['password'] = $params->get("source_password", '');   // Password for database authentication
        $option['database'] = $params->get("source_database_name", '');      // Database name
        $option['prefix'] = $this->modPrefix($params->get("source_db_prefix", ''));             // Database prefix (may be empty)

        $source_db =  JDatabaseDriver::getInstance($option);
        return $source_db;
    }
    
    private function getItemsSource() {
        // Get a storage key.
        $store = $this->getStoreId();

        // Try to load the data from internal storage.
        if (isset($this->cache[$store])) {
            return $this->cache[$store];
        }

        // Load the list items.
        $query = $this->_getListQuery();
        $db = $this->getDboSource();
        
        $db->setQuery($query, $this->getStart(), $this->getState('list.limit'));
        $items = $db->loadObjectList();

        // Check for a database error.
        if ($db->getErrorNum()) {
            $this->setError($db->getErrorMsg());
            return false;
        }

        // Add the items to the internal cache.
        $this->cache[$store] = $items;

        return $this->cache[$store];
    }
    
    private function modPrefix($prefix) { //Add underscore if not their
        if (!strpos($prefix, '_'))
            $prefix = $prefix . '_';
        return $prefix;
    }
    
    public function getItems() {
        return $this->getItemsSource(); 
    }
    
    public function getCategories() {
        $db = $this->getDboSource();
        $query = $db->getQuery(true);

        $query->select('ca.id AS id, ca.title AS title, ca.level');
        $query->from('#__categories AS ca');
        $query->join('INNER', '#__newsfeeds AS co ON co.catid = ca.id');
        $query->where('ca.parent_id > 0');
        $query->group('ca.id, ca.title');
        $query->order('ca.lft');

        // Filter on the published state
        if (isset($config['filter.published'])) {
            if (is_numeric($config['filter.published'])) {
                $query->where('a.published = ' . (int) $config['filter.published']);
            } elseif (is_array($config['filter.published'])) {
                JArrayHelper::toInteger($config['filter.published']);
                $query->where('a.published IN (' . implode(',', $config['filter.published']) . ')');
            }
        }

        $query->order('ca.lft');

        $db->setQuery($query);
        $items = $db->loadObjectList();
        $items2 = Array();

        foreach ($items as &$item) {
            $repeat = ($item->level - 1 >= 0) ? $item->level - 1 : 0;
            $item->title = str_repeat('- ', $repeat) . $item->title;
            $items2[] = JHtml::_('select.option', $item->id, $item->title);
        }

        return $items2;
    }

    public function getLanguages($all = true, $translate = true) {
        // Get the database object and a new query object.
        $db = $this->getDboSource();
        $query = $db->getQuery(true);

        // Build the query.
        $query->select('a.lang_code AS value, a.title AS text, a.title_native');
        $query->from('#__languages AS a');
        $query->where('a.published >= 0');
        $query->order('a.title');

        // Set the query and load the options.
        $db->setQuery($query);
        $items = $db->loadObjectList();
        if ($all) {
            array_unshift($items, new JObject(array('value' => '*', 'text' => $translate ? JText::alt('JALL', 'language') : 'JALL_LANGUAGE')));
        }

        // Detect errors
        if ($db->getErrorNum()) {
            JError::raiseWarning(500, $db->getErrorMsg());
        }

        return $items;
    }

    public function getAssetGroups() {
        $db = $this->getDboSource();
        $query = $db->getQuery(true);

        $query->select('a.id AS value, a.title AS text');
        $query->from($db->quoteName('#__viewlevels') . ' AS a');
        $query->group('a.id, a.title, a.ordering');
        $query->order('a.ordering ASC');

        $db->setQuery($query);
        $asset_groups = $db->loadObjectList();

        // Check for a database error.
        if ($db->getErrorNum()) {
            JError::raiseNotice(500, $db->getErrorMsg());
            return false;
        }

        return $asset_groups;
    }
    /**
     * Returns a record count for the query.
     *
     * @param   JDatabaseQuery|string  $query  The query.
     *
     * @return  integer  Number of rows for query.
     *
     */
    protected function _getListCount($query) {
        $db = $this->getDboSource();

        // Use fast COUNT(*) on JDatabaseQuery objects if there no GROUP BY or HAVING clause:
        if ($query instanceof JDatabaseQuery && $query->type == 'select' && $query->group === null && $query->having === null) {
            $query = clone $query;
            $query->clear('select')->clear('order')->select('COUNT(*)');

            $db->setQuery($query);
            return (int) $db->loadResult();
        }

        // Otherwise fall back to inefficient way of counting all results.
        $db->setQuery($query);
        $db->execute();

        return (int) $db->getNumRows();
    }
}
