<?php

/**
 * @package		SP Transfer
 * @subpackage	Components
 * @copyright	SP CYEND - All rights reserved.
 * @author		SP CYEND
 * @link		http://www.cyend.com
 * @license GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
 */
// No direct access to this file
defined('_JEXEC') or die('Restricted access');

// import the Joomla modellist library
jimport('joomla.application.component.modellist');

class SPTransferModelDatabase extends JModelList {

    public function getTable($type = 'Tables', $prefix = 'SPTransferTable', $config = array()) {
        return JTable::getInstance($type, $prefix, $config);
    }

    public function getItem($table_name) {
        $db = $this->getDbo();
        //$query = "SELECT * FROM #__sptransfer_tables WHERE `extension_name` LIKE 'com_database' AND `name` LIKE '".$table_name."'";

        $query = $db->getQuery(true);
        $query->select(
                $this->getState(
                        'list.select', 'a.id, a.extension_name, a.name'
                )
        );
        $query->from('#__sptransfer_tables AS a');
        $query->where("a.extension_name LIKE 'com_database'");
        $query->where("a.name LIKE '" . $table_name . "'");

        $db->setQuery($query);
        CYENDFactory::execute($db);
        return $db->loadObject();
    }

    public function newItem($table_name) {
        $db = $this->getDbo();
        $query = "
            INSERT INTO  `#__sptransfer_tables` (
                `id` ,
                `extension_name` ,
                `name`
                )
                VALUES (
                NULL ,  'com_database',  '" . $table_name . "'
                );
            ";
        $db->setQuery($query);
        CYENDFactory::execute($db);
        return $this->getItem($table_name);
    }

    public function getItems($pk = null) {
        //$items = parent::getItems(); 
        // Create a new query object.
        $db = $this->getDboSource();
        $query = $this->getListQuery($pk);

        //apply pagination
        $start = $this->getStart();
        $limit = $this->getState('list.limit');

        $db->setQuery($query, $start, $limit);
        CYENDFactory::execute($db);
        $items = $db->loadColumn();
        if (empty($items)) {
            JFactory::getApplication()->enqueueMessage(JText::_('COM_SPTRANSFER_MSG_ERROR_OPTIONS'));
            JFactory::getApplication()->enqueueMessage(JText::plural('COM_SPTRANSFER_MSG_DB', $db->getErrorMsg()), 'error');
            return Array();
        }


        //Rename fields and keep only tables with same prefix
        $params = JComponentHelper::getParams('com_sptransfer');
        $prefix = $this->modPrefix($params->get("source_db_prefix", ''));
        $items2 = Array();
        $i = 0;
        foreach ($items as $item => $value) {
            $value2 = explode('_', $value);
            if ($value2[0] . "_" == $prefix) {
                $i += 1;
                @$items2[$item]->id = $i;
                $items2[$item]->prefix = $value2[0];
                unset($value2[0]);
                $items2[$item]->name = implode('_', $value2);
            }
        }

        return $items2;
    }

    protected function getListQuery($pk = null) {

        $params = JComponentHelper::getParams('com_sptransfer');
        $database = $params->get("source_database_name", '');
        $prefix = $this->modPrefix($params->get("source_db_prefix", ''));

        $query = 'SELECT TABLE_NAME FROM INFORMATION_SCHEMA.TABLES 
            WHERE TABLE_SCHEMA = \'' . $database . '\' 
            AND TABLE_NAME LIKE "' . $prefix . '%"';

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

        $source_db = JDatabaseDriver::getInstance($option);
        return $source_db;
    }

    private function modPrefix($prefix) { //Add underscore if not their
        if (!strpos($prefix, '_'))
            $prefix = $prefix . '_';
        return $prefix;
    }

    public function getTestConnection() {
        //Check connection
        $source = new CYENDSource();
        return $source->testConnection();
    }

    /**
     * Method to get the total number of items for the data set.
     *
     * @return  integer  The total number of items available in the data set.
     *
     */
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
