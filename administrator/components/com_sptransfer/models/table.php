<?php

/**
 * @package		SP Transfer
 * @subpackage	Components
 * @copyright	SP CYEND - All rights reserved.
 * @author		SP CYEND
 * @link		http://www.cyend.com
 * @license GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
 */
// no direct access
defined('_JEXEC') or die;

jimport('joomla.application.component.modellist');

class SPTransferModelTable extends JModelList {

    
    /**
     * Method to auto-populate the model state.
     *
     * Note. Calling getState in this method will result in recursion.
     *
     * @return	void
     * @since	1.6
     */
    protected function populateState($ordering = null, $direction = null) {
        //Get table name
        $name = JRequest::getVar('name');
        $this->setState('name', $name);
        $prefix = JRequest::getVar('prefix');
        $this->setState('prefix', $prefix);
        
        return parent::populateState($ordering, $direction);
    }

    /**
     * Build an SQL query to load the list data.
     *
     * @return	JDatabaseDriverQuery
     * @since	1.6
     */
    protected function getListQuery() {
        // Create a new query object.
        $db = $this->getDboSource();
        
        //apply pagination        
        $query = $db->getQuery(true);
        
        // Select the required fields from the table.
        /*
        $column_name = $this->getColumnName();
        $name = $this->getState('name');
        $query->select('*');
        $query->from('#__'.$name.' AS a');
        $query->order($column_name.' ASC');
         * 
         */
        
        $name = $this->getState('name');
        $query = 'select @rownum:=@rownum+1 sp_id, p.* from #__'.$name.' p, (SELECT @rownum:=-1) r';
        $query .= ' ORDER BY sp_id ASC';

        return $query;
    }
    
    protected function getColumnName() {
        $db = $this->getDboSource();
        $name = $this->getState('name');
        $query = 'describe #__'.$name;
        $db->setQuery($query);
        CYENDFactory::execute($db);
        $column_name = $db->loadResult();
        return $column_name;
    }
    
     public function getItems($pk = null) {
        //$items = parent::getItems(); 
        // Create a new query object.
        $db = $this->getDboSource();
        $query = $this->getListQuery($pk);

        $start = $this->getStart();
        $limit = $this->getState('list.limit');
        $db->setQuery($query, $start, $limit);
        
        CYENDFactory::execute($db);
        $items = $db->loadObjectList();

        return $items;
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

    private function modPrefix($prefix) { //Add underscore if not their
        if (!strpos($prefix, '_'))
            $prefix = $prefix . '_';
        return $prefix;
    }
    
    public function getTotal() {

        // Get a storage key.
        $store = $this->getStoreId('getTotal');

        // Try to load the data from internal storage.
        if (isset($this->cache[$store])) {
            return $this->cache[$store];
        }

        // Load the total.
        $query = $this->_getListQuery();
        try {
            $total = (int) $this->_getListCount($query);
        } catch (RuntimeException $e) {
            $this->setError($e->getMessage());
            return false;
        }

        // Add the total to the internal cache.
        $this->cache[$store] = $total;

        return $this->cache[$store];
    }
    
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
