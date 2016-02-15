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
 * Modules Component Module Model
 *
 * @package		Joomla.Administrator
 * @subpackage	com_modules
 * @since		1.5
 */
class SPTransferModelModules extends JModelList
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
				'title', 'a.title',
				'checked_out', 'a.checked_out',
				'checked_out_time', 'a.checked_out_time',
				'published', 'a.published',
				'access', 'a.access', 'access_level',
				'ordering', 'a.ordering',
				'module', 'a.module',
				'language', 'a.language', 'language_title',
				'publish_up', 'a.publish_up',
				'publish_down', 'a.publish_down',
				'client_id', 'a.client_id',
				'position', 'a.position',
				'pages',
				'name', 'e.name',
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

		$state = $this->getUserStateFromRequest($this->context.'.filter.state', 'filter_state', '', 'string');
		$this->setState('filter.state', $state);

		$position = $this->getUserStateFromRequest($this->context.'.filter.position', 'filter_position', '', 'string');
		$this->setState('filter.position', $position);

		$module = $this->getUserStateFromRequest($this->context.'.filter.module', 'filter_module', '', 'string');
		$this->setState('filter.module', $module);

		$clientId = $this->getUserStateFromRequest($this->context.'.filter.client_id', 'filter_client_id', 0, 'int', false);
		$previousId = $app->getUserState($this->context.'.filter.client_id_previous', null);
		if($previousId != $clientId || $previousId === null){
			$this->getUserStateFromRequest($this->context.'.filter.client_id_previous', 'filter_client_id_previous', 0, 'int', true);
			$app->setUserState($this->context.'.filter.client_id_previous', $clientId);
		}
		$this->setState('filter.client_id', $clientId);

		$language = $this->getUserStateFromRequest($this->context.'.filter.language', 'filter_language', '');
		$this->setState('filter.language', $language);

		// Load the parameters.
		$params = JComponentHelper::getParams('com_modules');
		$this->setState('params', $params);

		// List state information.
		parent::populateState('position', 'asc');
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
		$id	.= ':'.$this->getState('filter.state');
		$id	.= ':'.$this->getState('filter.position');
		$id	.= ':'.$this->getState('filter.module');
		$id	.= ':'.$this->getState('filter.client_id');
		$id	.= ':'.$this->getState('filter.language');

		return parent::getStoreId($id);
	}
	/**
	 * Returns an object list
	 *
	 * @param	string The query
	 * @param	int Offset
	 * @param	int The number of records
	 * @return	array
	 */
	protected function _getList($query, $limitstart=0, $limit=0)
	{
		$ordering = $this->getState('list.ordering', 'ordering');
		if (in_array($ordering, array('pages', 'name'))) {
			$this->_db->setQuery($query);
			$result = $this->_db->loadObjectList();
			$this->translate($result);
			$lang = JFactory::getLanguage();
			JArrayHelper::sortObjects($result, $ordering, $this->getState('list.direction') == 'desc' ? -1 : 1, true, $lang->getLocale());
			$total = count($result);
			$this->cache[$this->getStoreId('getTotal')] = $total;
			if ($total < $limitstart) {
				$limitstart = 0;
				$this->setState('list.start', 0);
			}
			return array_slice($result, $limitstart, $limit ? $limit : null);
		}
		else {
			if ($ordering == 'ordering') {
				$query->order('a.position ASC');
				$ordering = 'a.ordering';
			}
			if ($ordering == 'language_title') {
				$ordering = 'l.title';
			}
			$query->order($this->_db->quoteName($ordering) . ' ' . $this->getState('list.direction'));
			if ($ordering == 'position') {
				$query->order('a.ordering ASC');
			}
			$result = parent::_getList($query, $limitstart, $limit);
			$this->translate($result);
			return $result;
		}
	}

	/**
	 * Translate a list of objects
	 *
	 * @param	array The array of objects
	 * @return	array The array of translated objects
	 */
	protected function translate(&$items)
	{
		$lang = JFactory::getLanguage();
		$client = $this->getState('filter.client_id') ? 'administrator' : 'site';
		foreach($items as $item) {
			$extension = $item->module;
			$source = constant('JPATH_' . strtoupper($client)) . "/modules/$extension";
				$lang->load("$extension.sys", constant('JPATH_' . strtoupper($client)), null, false, false)
			||	$lang->load("$extension.sys", $source, null, false, false)
			||	$lang->load("$extension.sys", constant('JPATH_' . strtoupper($client)), $lang->getDefault(), false, false)
			||	$lang->load("$extension.sys", $source, $lang->getDefault(), false, false);
			$item->name = JText::_($item->name);
			if (is_null($item->pages)) {
				$item->pages = JText::_('JNONE');
			} elseif ($item->pages < 0) {
				$item->pages = JText::_('COM_MODULES_ASSIGNED_VARIES_EXCEPT');
			} elseif ($item->pages > 0) {
				$item->pages = JText::_('COM_MODULES_ASSIGNED_VARIES_ONLY');
			} else {
				$item->pages = JText::_('JALL');
			}
		}
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

		// Select the required fields from the table.
		$query->select(
			$this->getState(
				'list.select',
				'a.id, a.title, a.note, a.position, a.module, a.language,' .
				'a.checked_out, a.checked_out_time, a.published+2*(e.enabled-1) as published, a.access, a.ordering, a.publish_up, a.publish_down'
			)
		);
		$query->from($db->quoteName('#__modules').' AS a');

		// Join over the language
		$query->select('l.title AS language_title');
		$query->join('LEFT', $db->quoteName('#__languages').' AS l ON l.lang_code = a.language');

		// Join over the users for the checked out user.
		$query->select('uc.name AS editor');
		$query->join('LEFT', '#__users AS uc ON uc.id=a.checked_out');

		// Join over the asset groups.
		$query->select('ag.title AS access_level');
		$query->join('LEFT', '#__viewlevels AS ag ON ag.id = a.access');

		// Join over the module menus
		$query->select('MIN(mm.menuid) AS pages');
		$query->join('LEFT', '#__modules_menu AS mm ON mm.moduleid = a.id');

		// Join over the extensions
		$query->select('e.name AS name');
		$query->join('LEFT', '#__extensions AS e ON e.element = a.module');
		$query->group('a.id, a.title, a.note, a.position, a.module, a.language,a.checked_out,'.
						'a.checked_out_time, a.published, a.access, a.ordering,l.title, uc.name, ag.title, e.name,'.
						'l.lang_code, uc.id, ag.id, mm.moduleid, e.element, a.publish_up, a.publish_down,e.enabled');

		// Filter by access level.
		if ($access = $this->getState('filter.access')) {
			$query->where('a.access = '.(int) $access);
		}

		// Filter by published state
		$state = $this->getState('filter.state');
		if (is_numeric($state)) {
			$query->where('a.published = '.(int) $state);
		}
		elseif ($state === '') {
			$query->where('(a.published IN (0, 1))');
		}

		// Filter by position
		$position = $this->getState('filter.position');
		if ($position && $position != 'none') {
			$query->where('a.position = '.$db->Quote($position));
		}

		elseif ($position == 'none') {
			$query->where('a.position = '.$db->Quote(''));
		}

		// Filter by module
		$module = $this->getState('filter.module');
		if ($module) {
			$query->where('a.module = '.$db->Quote($module));
		}

		// Filter by client.
		$clientId = $this->getState('filter.client_id');
		if (is_numeric($clientId)) {
			$query->where('a.client_id = ' . (int) $clientId . ' AND e.client_id ='. (int) $clientId);
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
				$query->where('('.'a.title LIKE '.$search.' OR a.note LIKE '.$search.')');
			}
		}

		// Filter on the language.
		if ($language = $this->getState('filter.language')) {
			$query->where('a.language = ' . $db->quote($language));
		}

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
    
    public function getPositions()
	{
        $clientId = $this->getState('filter.client_id');
        
		jimport('joomla.filesystem.folder');

		$db		= $this->getDboSource();
		$query	= $db->getQuery(true);

		$query->select('DISTINCT(position)');
		$query->from('#__modules');
		$query->where($db->quoteName('client_id').' = '.(int) $clientId);
		$query->order('position');

		$db->setQuery($query);
		$positions = $db->loadColumn();
		$positions = (is_array($positions)) ? $positions : array();

		if ($error = $db->getErrorMsg()) {
			JError::raiseWarning(500, $error);
			return;
		}

		// Build the list
		$options = array();
		foreach ($positions as $position)
		{
			if (!$position)
			{
				$options[]	= JHtml::_('select.option', 'none', ':: '.JText::_('JNONE').' ::');

			}
			else
			{
				$options[]	= JHtml::_('select.option', $position, $position);
			}
		}
		return $options;
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
    
    public function getClientOptions()
	{
		// Build the filter options.
		$options	= array();
		$options[]	= JHtml::_('select.option', '0', JText::_('JSITE'));
		$options[]	= JHtml::_('select.option', '1', JText::_('JADMINISTRATOR'));
		return $options;
	}
    
    public function getStateOptions()
	{
		// Build the filter options.
		$options	= array();
		$options[]	= JHtml::_('select.option',	'1',	JText::_('JPUBLISHED'));
		$options[]	= JHtml::_('select.option',	'0',	JText::_('JUNPUBLISHED'));
		$options[]	= JHtml::_('select.option',	'-2',	JText::_('JTRASHED'));
		return $options;
	}
    
    public function getModules()
	{
        $clientId = $this->getState('filter.client_id');
        
		$db		= $this->getDboSource();
		$query	= $db->getQuery(true);

		$query->select('element AS value, name AS text');
		$query->from('#__extensions as e');
		$query->where('e.client_id = '.(int)$clientId);
		$query->where('type = '.$db->quote('module'));
		$query->leftJoin('#__modules as m ON m.module=e.element AND m.client_id=e.client_id');
		$query->where('m.module IS NOT NULL');
		$query->group('element,name');

		$db->setQuery($query);
		$modules = $db->loadObjectList();
		$lang = JFactory::getLanguage();
		foreach ($modules as $i=>$module) {
			$extension = $module->value;
			$path = $clientId ? JPATH_ADMINISTRATOR : JPATH_SITE;
			$source = $path . "/modules/$extension";
				$lang->load("$extension.sys", $path, null, false, false)
			||	$lang->load("$extension.sys", $source, null, false, false)
			||	$lang->load("$extension.sys", $path, $lang->getDefault(), false, false)
			||	$lang->load("$extension.sys", $source, $lang->getDefault(), false, false);
			$modules[$i]->text = JText::_($module->text);
		}
		JArrayHelper::sortObjects($modules, 'text', 1, true, $lang->getLocale());
		return $modules;
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
