<?php

/**
 * @package		SP Transfer
 * @subpackage	Components
 * @copyright	SP CYEND - All rights reserved.
 * @author		SP CYEND
 * @link		http://www.cyend.com
 * @license GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
 */
defined('_JEXEC') or die;

jimport('joomla.application.component.modellist');

/**
 * Methods supporting a list of user records.
 *
 * @package     Joomla.Administrator
 * @subpackage  com_users
 * @since       1.6
 */
class SPTransferModelUsers extends JModelList {

    /**
     * Constructor.
     *
     * @param   array  $config  An optional associative array of configuration settings.
     *
     * @see     JController
     * @since   1.6
     */
    public function __construct($config = array()) {
        if (empty($config['filter_fields'])) {
            $config['filter_fields'] = array(
                'id', 'a.id',
                'name', 'a.name',
                'username', 'a.username',
                'email', 'a.email',
                'block', 'a.block',
                'sendEmail', 'a.sendEmail',
                'registerDate', 'a.registerDate',
                'lastvisitDate', 'a.lastvisitDate',
                'activation', 'a.activation',
            );
        }

        parent::__construct($config);
    }

    /**
     * Method to auto-populate the model state.
     *
     * Note. Calling getState in this method will result in recursion.
     *
     * @return  void
     *
     * @since   1.6
     */
    protected function populateState($ordering = null, $direction = null) {
        // Initialise variables.
        $app = JFactory::getApplication('administrator');

        // Adjust the context to support modal layouts.
        if ($layout = JRequest::getVar('layout', 'default')) {
            $this->context .= '.' . $layout;
        }

        // Load the filter state.
        $search = $this->getUserStateFromRequest($this->context . '.filter.search', 'filter_search');
        $this->setState('filter.search', $search);

        $active = $this->getUserStateFromRequest($this->context . '.filter.active', 'filter_active');
        $this->setState('filter.active', $active);

        $state = $this->getUserStateFromRequest($this->context . '.filter.state', 'filter_state');
        $this->setState('filter.state', $state);

        $groupId = $this->getUserStateFromRequest($this->context . '.filter.group', 'filter_group_id', null, 'int');
        $this->setState('filter.group_id', $groupId);

        $range = $this->getUserStateFromRequest($this->context . '.filter.range', 'filter_range');
        $this->setState('filter.range', $range);

        $groups = json_decode(base64_decode(JRequest::getVar('groups', '', 'default', 'BASE64')));
        if (isset($groups)) {
            JArrayHelper::toInteger($groups);
        }
        $this->setState('filter.groups', $groups);

        $excluded = json_decode(base64_decode(JRequest::getVar('excluded', '', 'default', 'BASE64')));
        if (isset($excluded)) {
            JArrayHelper::toInteger($excluded);
        }
        $this->setState('filter.excluded', $excluded);

        // Load the parameters.
        $params = JComponentHelper::getParams('com_users');
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
     * @param   string  $id  A prefix for the store id.
     *
     * @return  string  A store id.
     *
     * @since   1.6
     */
    protected function getStoreId($id = '') {
        // Compile the store id.
        $id .= ':' . $this->getState('filter.search');
        $id .= ':' . $this->getState('filter.active');
        $id .= ':' . $this->getState('filter.state');
        $id .= ':' . $this->getState('filter.group_id');
        $id .= ':' . $this->getState('filter.range');

        return parent::getStoreId($id);
    }

    /**
     * Gets the list of users and adds expensive joins to the result set.
     *
     * @return  mixed  An array of data items on success, false on failure.
     *
     * @since   1.6
     */
    public function getItems() {
        // Get a storage key.
        $store = $this->getStoreId();

        // Try to load the data from internal storage.
        if (empty($this->cache[$store])) {
            $groups = $this->getState('filter.groups');
            $groupId = $this->getState('filter.group_id');
            if (isset($groups) && (empty($groups) || $groupId && !in_array($groupId, $groups))) {
                $items = array();
            } else {
                $items = $this->getItemsSource();
            }

            // Bail out on an error or empty list.
            if (empty($items)) {
                $this->cache[$store] = $items;

                return $items;
            }

            // Joining the groups with the main query is a performance hog.
            // Find the information only on the result set.
            // First pass: get list of the user id's and reset the counts.
            $userIds = array();
            foreach ($items as $item) {
                $userIds[] = (int) $item->id;
                $item->group_count = 0;
                $item->group_names = '';
                $item->note_count = 0;
            }

            // Get the counts from the database only for the users in the list.
            $db = $this->getDboSource();
            $query = $db->getQuery(true);

            // Join over the group mapping table.
            $query->select('map.user_id, COUNT(map.group_id) AS group_count')
                    ->from('#__user_usergroup_map AS map')
                    ->where('map.user_id IN (' . implode(',', $userIds) . ')')
                    ->group('map.user_id')
                    // Join over the user groups table.
                    ->join('LEFT', '#__usergroups AS g2 ON g2.id = map.group_id');

            $db->setQuery($query);

            // Load the counts into an array indexed on the user id field.
            $userGroups = $db->loadObjectList('user_id');

            $error = $db->getErrorMsg();
            if ($error) {
                $this->setError($error);

                return false;
            }

            $query->clear()
                    ->select('n.user_id, COUNT(n.id) As note_count')
                    ->from('#__user_notes AS n')
                    ->where('n.user_id IN (' . implode(',', $userIds) . ')')
                    ->where('n.state >= 0')
                    ->group('n.user_id');

            $db->setQuery((string) $query);

            // Load the counts into an array indexed on the aro.value field (the user id).
            $userNotes = $db->loadObjectList('user_id');

            $error = $db->getErrorMsg();
            if ($error) {
                $this->setError($error);

                return false;
            }

            // Second pass: collect the group counts into the master items array.
            foreach ($items as &$item) {
                if (isset($userGroups[$item->id])) {
                    $item->group_count = $userGroups[$item->id]->group_count;
                    //Group_concat in other databases is not supported
                    $item->group_names = $this->_getUserDisplayedGroups($item->id);
                }

                if (isset($userNotes[$item->id])) {
                    $item->note_count = $userNotes[$item->id]->note_count;
                }
            }

            // Add the items to the internal cache.
            $this->cache[$store] = $items;
        }

        return $this->cache[$store];
    }

    /**
     * Build an SQL query to load the list data.
     *
     * @return  JDatabaseDriverQuery
     *
     * @since   1.6
     */
    protected function getListQuery() {
        // Create a new query object.
        $db = $this->getDboSource();
        $query = $db->getQuery(true);

        // Select the required fields from the table.
        $query->select(
                $this->getState(
                        'list.select', 'a.*'
                )
        );

        $query->from($db->quoteName('#__users') . ' AS a');

        // If the model is set to check item state, add to the query.
        $state = $this->getState('filter.state');

        if (is_numeric($state)) {
            $query->where('a.block = ' . (int) $state);
        }

        // If the model is set to check the activated state, add to the query.
        $active = $this->getState('filter.active');

        // Filter the items over the group id if set.
        $groupId = $this->getState('filter.group_id');
        $groups = $this->getState('filter.groups');


        if ($groupId || isset($groups)) {
            $query->join('LEFT', '#__user_usergroup_map AS map2 ON map2.user_id = a.id');
            $query->group($db->quoteName(array('a.id', 'a.name', 'a.username', 'a.password', 'a.block', 'a.sendEmail', 'a.registerDate', 'a.lastvisitDate', 'a.activation', 'a.params', 'a.email')));

            if ($groupId) {
                $query->where('map2.group_id = ' . (int) $groupId);
            }

            if (isset($groups)) {
                $query->where('map2.group_id IN (' . implode(',', $groups) . ')');
            }
        }

        // Filter the items over the search string if set.
        if ($this->getState('filter.search') !== '') {
            // Escape the search token.
            $token = $db->Quote('%' . $db->escape($this->getState('filter.search')) . '%');

            // Compile the different search clauses.
            $searches = array();
            $searches[] = 'a.name LIKE ' . $token;
            $searches[] = 'a.username LIKE ' . $token;
            $searches[] = 'a.email LIKE ' . $token;

            // Add the clauses to the query.
            $query->where('(' . implode(' OR ', $searches) . ')');
        }

        // Add the list ordering clause.
        $query->order($db->escape($this->getState('list.ordering', 'a.name')) . ' ' . $db->escape($this->getState('list.direction', 'ASC')));

        return $query;
    }

    //sqlsrv change
    function _getUserDisplayedGroups($user_id) {
        $db = $this->getDboSource();
        $sql = "SELECT title FROM " . $db->quoteName('#__usergroups') . " ug left join " .
                $db->quoteName('#__user_usergroup_map') . " map on (ug.id = map.group_id)" .
                " WHERE map.user_id=" . $user_id;

        $db->setQuery($sql);
        $result = $db->loadColumn();
        return implode("\n", $result);
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

        $source_db = JDatabaseDriver::getInstance($option);
        return $source_db;
    }

    private function modPrefix($prefix) { //Add underscore if not their
        if (!strpos($prefix, '_'))
            $prefix = $prefix . '_';
        return $prefix;
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

    public function getUserGroups() {
        $db = $this->getDboSource();
        $query = $db->getQuery(true);
        $query->select('a.id AS value, a.title AS text, COUNT(DISTINCT b.id) AS level');
        $query->from($db->quoteName('#__usergroups') . ' AS a');
        $query->join('LEFT', $db->quoteName('#__usergroups') . ' AS b ON a.lft > b.lft AND a.rgt < b.rgt');
        $query->group('a.id, a.title, a.lft, a.rgt');
        $query->order('a.lft ASC');
        $db->setQuery($query);
        $options = $db->loadObjectList();

        // Check for a database error.
        if ($db->getErrorNum()) {
            JError::raiseNotice(500, $db->getErrorMsg());
            return null;
        }

        for ($i = 0, $n = count($options); $i < $n; $i++) {
            $options[$i]->text = str_repeat('- ', $options[$i]->level) . $options[$i]->text;
            $groups[] = JHtml::_('select.option', $options[$i]->value, $options[$i]->text);
        }

        return $groups;
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
