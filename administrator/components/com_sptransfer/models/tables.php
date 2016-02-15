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

class SPTransferModelTables extends JModelList {

    public function getTable($type = 'Tables', $prefix = 'SPTransferTable', $config = array()) {
        return JTable::getInstance($type, $prefix, $config);
    }

    public function getItem($pk) {
        // Create a new query object.
        $db = $this->getDbo();
        $query = $this->getQuery($pk);

        $db->setQuery($query);
        CYENDFactory::execute($db);
        $item = $db->loadObject();

        return $item;
    }

    protected function getQuery($pk) {
        // Create a new query object.
        $db = $this->getDbo();
        $query = $db->getQuery(true);

        // Select the required fields from the table.
        $query->select(
                $this->getState(
                        'list.select', 'a.id, a.extension_name, a.name, a.category'
                )
        );
        $query->from('#__sptransfer_tables AS a');

        // Filter by id
        $query->where('a.id = ' . (int) $pk);

        return $query;
    }

    public function getItems($pk = null) {
        //$items = parent::getItems(); 
        // Create a new query object.
        $db = $this->getDbo();
        $query = $this->getListQuery($pk);

        $db->setQuery($query);
        CYENDFactory::execute($db);
        $items = $db->loadObjectList();

        return $items;
    }

    protected function getListQuery($pk = null) {
        // Create a new query object.
        $db = $this->getDbo();
        $query = $db->getQuery(true);

        // Select the required fields from the table.
        $query->select(
                $this->getState(
                        'list.select', 'a.id, a.extension_name, a.name, a.category'
                )
        );
        $query->from('#__sptransfer_tables AS a');
        
        $query->where('a.extension_name NOT LIKE' . $db->q('com_database'));

        // Filter by extension_name
        // Join over the extension name
        if (!is_null($pk)) {
            //$query->join('LEFT', '`#__extensions` AS l ON l.extension_name = a.extension_name GROUP BY a.extension_name');
            $query->where('l.extension_id = ' . (int) $pk);
        }

        //Limit up to id < 1000
        $query->where('a.id < 1000');

        // Ordering
        $query->order('a.order ASC');

        return $query;
    }

    public function getTestConnection() {
        //Check connection
        $source = new CYENDSource();
        return $source->testConnection();
    }

    public function getPathConnection() {
        //Check connection
        $source = new CYENDSource();
        return $source->testPathConnection();
    }

    public function getTagsTables() {

        $source = new CYENDSource();
        $source_db = $source->source_db;
        $destination_db = $this->getDbo();

        $query = "SELECT id from #__tags";
        $source_db->setQuery($query);
        if (!CYENDFactory::execute($source_db)) {

            $source_prefix = $source_db->getPrefix();
            $destination_prefix = $destination_db->getPrefix();

            // create #__tags
            $destination_db->setQuery('SHOW CREATE TABLE  #__tags');
            if (!CYENDFactory::execute($destination_db))
                return false;
            $destination_table_desc = $destination_db->loadObject();

            $query1 = $destination_table_desc->{'Create Table'};
            $query = str_replace('CREATE TABLE `' . $destination_prefix, 'CREATE TABLE `' . $source_prefix, $query1);
            $source_db->setQuery($query);
            if (!CYENDFactory::execute($source_db)) {
                $message = '<b><font color="red">' . JText::sprintf('COM_SPTRANSFER_MSG_ERROR_QUERY', $destination_db->getErrorMsg()) . '</font></b>';
                JFactory::getApplication()->enqueueMessage($message, 'error');
                return false;
            }
            
            // create #__contentitem_tag_map
            $destination_db->setQuery('SHOW CREATE TABLE  #__contentitem_tag_map');
            if (!CYENDFactory::execute($destination_db))
                return false;
            $destination_table_desc = $destination_db->loadObject();

            $query1 = $destination_table_desc->{'Create Table'};
            $query = str_replace('CREATE TABLE `' . $destination_prefix, 'CREATE TABLE `' . $source_prefix, $query1);
            $source_db->setQuery($query);
            if (!CYENDFactory::execute($source_db)) {
                $message = '<b><font color="red">' . JText::sprintf('COM_SPTRANSFER_MSG_ERROR_QUERY', $destination_db->getErrorMsg()) . '</font></b>';
                JFactory::getApplication()->enqueueMessage($message, 'error');
                return false;
            }
        }
    }
}
    