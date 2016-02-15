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
jimport('joomla.application.component.model');

class SPTransferModelCom_Database extends JModelLegacy {

    protected $jAp;
    protected $tableLog;
    protected $destination_db;
    protected $destination_query;
    protected $source_db;
    protected $source_query;
    protected $user;
    protected $params;
    protected $task;
    protected $batch;
    protected $status;

    function __construct($config = array()) {
        parent::__construct($config);
        $this->jAp = JFactory::getApplication();
        $this->tableLog = parent::getTable('Log', 'SPTransferTable');
        $this->destination_db = $this->getDbo();
        $this->destination_query = $this->destination_db->getQuery(true);
        $this->source_db = $config['source_db'];
        $this->source_query = $this->source_db->getQuery(true);
        $this->user = JFactory::getUser(0);
        $this->params = JComponentHelper::getParams('com_sptransfer');
        $this->batch = $this->params->get('batch', 100);
        $this->task = $config['task'];
        $this->status = $config['status'];
    }

    public function getTable($type = 'Tables', $prefix = 'JTable', $config = array()) {
        return JTable::getInstance($type, $prefix, $config);
    }

    public function content($pks = null, $prefix, $name) {
        // Initialize
        $jAp = $this->jAp;
        $tableLog = $this->tableLog;
        $destination_db = $this->destination_db;
        $destination_query = $this->destination_query;
        $source_db = $this->source_db;
        $source_query = $this->source_query;
        $destination_table = $this->getTable('Content', 'JTable');
        $user = $this->user;
        $params = $this->params;
        $task = $this->task;

        $source_table_name = $prefix . '_' . $name;
        $destination_table_name = $destination_db->getPrefix() . $name;
        $items = Array();

        // Load items
        $query = 'SELECT source_id
            FROM #__sptransfer_log
            WHERE tables_id = ' . (int) $task->id . ' AND state >= 2
            ORDER BY id ASC';
        $destination_db->setQuery($query);
        if (!CYENDFactory::execute($destination_db)) {
            jexit($destination_db->getErrorMsg());
        }
        $excludes = $destination_db->loadColumn();

        //Find ids
        if (is_null($pks[0])) {
            $query = 'SELECT COUNT(*)' .
                    ' FROM #__' . $name;
            $source_db->setQuery($query);
            if (!CYENDFactory::execute($source_db)) {
                jexit($source_db->getErrorMsg());
            }
            $total_items = $source_db->loadResult();
            for ($index = 0; $index < $total_items; $index++) {
                $pks[$index] = $index;
            }
        }

        // Loop to save items
        foreach ($pks as $pk) {

            //Load data from source
            $exclude = array_search($pk, $excludes);
            if ($exclude !== false) {
                unset($excludes[$exclude]);
                continue;
            }

            $query = 'SELECT * FROM #__' . $name .
                    ' LIMIT ' . $pk . ', 1';
            $source_db->setQuery($query);
            if (!$source_db->query()) {
                jexit($source_db->getErrorMsg());
            }
            $item = $source_db->loadAssoc();

            if (empty($item))
                continue;

            //status pending
            $this->batch -= 1;
            if ($this->batch < 0)
                return;

            //log            
            $tableLog->reset();
            $tableLog->id = null;
            $tableLog->load(array("tables_id" => $task->id, "source_id" => $pk));
            $tableLog->created = null;
            $tableLog->note = "";
            $tableLog->source_id = $pk;
            $tableLog->destination_id = $pk;
            $tableLog->state = 1;
            $tableLog->tables_id = $task->id;

            //Build query
            $query = "INSERT INTO #__" . $name . " (";
            if ($params->get("new_ids", 0) == 2)
                $query = "REPLACE INTO #__" . $name . " (";
            $columnNames = Array();
            $values = Array();
            foreach ($item as $column => $value) {
                if (($column != 'sp_id') && (!is_null($value))) {
                    $columnNames[] = $destination_db->quoteName($column);
                    $temp1 = implode(',', $columnNames);
                    $values[] = $destination_db->quote($value);
                    $temp2 = implode(',', $values);
                }
            }
            $query .= $temp1 . ") VALUES (" . $temp2 . ")";

            // Create record
            $destination_db->setQuery($query);
            if (!CYENDFactory::execute($destination_db)) {
                jexit($destination_db->getErrorMsg());
            }

            //Log
            $tableLog->state = 4;
            $tableLog->store();
        } //Main loop end
        //status completed
        $this->status = 'completed';
    }

    public function setTable($prefix, $name) {
        //Exit if empty table
        $source_table_name = $prefix . '_' . $name;
        if (is_null($source_table_name))
            jexit('Empty source table name');

        // Init
        $destination_db = $this->destination_db;
        $destination_query = $this->destination_query;
        $source_db = $this->source_db;
        $source_query = $this->source_query;

        //Define destination table name
        $destination_table_name = $destination_db->getPrefix() . $name;

        // Get tables descriptions
        $query = 'SHOW CREATE TABLE ' . $source_table_name;

        $source_db->setQuery($query);
        $result = CYENDFactory::execute($source_db);
        if (!$result)
            jexit($source_db->getErrorMsg());
        $source_table_desc = $source_db->loadObject();

        $query = 'describe ' . $destination_db->quoteName($destination_table_name);
        $destination_db->setQuery($query);
        CYENDFactory::execute($destination_db);

        try {
            $destination_table_desc = $destination_db->loadAssocList();
        } catch (RuntimeException $e) {
            //JError::raiseWarning(500, $e->getMessage());            
            $destination_table_desc = null;
        }

        $query = $source_table_desc->{'Create Table'};
        $query = str_replace('CREATE TABLE `' . $source_table_name, 'CREATE TABLE `' . $destination_table_name, $query);
        if (empty($destination_table_desc)) {
            //Create table
            $destination_db->setQuery($query);
            $result = CYENDFactory::execute($destination_db);
            if (!$result) {
                jexit($destination_db->getErrorMsg());
            }
        } else {
            return true;
            //Compare tables
            $query = 'describe ' . $source_table_name;
            $source_db->setQuery($query);
            $result = CYENDFactory::execute($source_db);
            $source_table_desc = $source_db->loadAssocList();
            //$compare_desc = array_diff($destination_table_desc, $source_table_desc);
            //if (!empty($compare_desc)) {                
            if ($destination_table_desc != $source_table_desc) {
                // Different structure
                //@task - Deal option if different structure
                jexit('<b><font color="red">' . JText::sprintf('COM_SPTRANSFER_DATABASE_DIFFERENT_STRUCTURE', $destination_table_name) . '</font></b>');
            }
        }

        return true;
    }

    public function getResult() {

        $result = Array();
        $result['status'] = $this->status;
        $result['message'] = $this->task->extension_name . ' - ' . $this->task->name;

        return $result;
    }

}
