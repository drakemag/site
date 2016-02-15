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

include_once JPATH_ADMINISTRATOR . '/components/com_menus/models/menu.php';

class CYENDModelMenu extends MenusModelMenu {

    public function __construct($config = array()) {
        parent::__construct($config);
        $this->addTablePath(JPATH_ADMINISTRATOR . '/components/com_menus/tables');
    }

    public function getTable($type = 'MenuType', $prefix = 'JTable', $config = array()) {
        if (empty($config['dbo']))
            $config['dbo'] = $this->_db;

        return JTable::getInstance($type, $prefix, $config);
    }

    protected function canDelete($record) {
        return true;
    }
        
}

include_once JPATH_ADMINISTRATOR . '/components/com_menus/models/item.php';

class CYENDModelItem extends MenusModelItem {

    public function __construct($config = array()) {
        parent::__construct($config);
        $this->addTablePath(JPATH_ADMINISTRATOR . '/components/com_menus/tables');
    }

    public function getTable($type = 'Menu', $prefix = 'MenusTable', $config = array()) {
        if (empty($config['dbo']))
            $config['dbo'] = $this->_db;

        return JTable::getInstance($type, $prefix, $config);
    }

    protected function canDelete($record) {
        return true;
    }
    
    public function getItem($pk = null) {
        $this->setState('item.type', false);
        return parent::getItem($pk);
    }

}

include_once JPATH_COMPONENT . '/models/com.php';

class SPTransferModelCom_Menus extends SPTransferModelCom
{

    public function menu_types($ids = null)    {
        
        $this->destination_model = new CYENDModelMenu(array('dbo' => $this->destination_db));        
        $this->source_model = new CYENDModelMenu(array('dbo' => $this->source_db));      
        
        $this->task->query = 'SELECT ' . $this->id . '
            FROM #__' . $this->task->name . '
            WHERE ' . $this->id . ' > 0';
        
        $this->alias = 'menutype';
        $this->task->state = 4; //state for success
        
        $this->items_new($ids);  
        
    }
    
    public function menu($ids = null)    {
        
        $this->destination_model = new CYENDModelItem(array('dbo' => $this->destination_db));        
        $this->source_model = new CYENDModelItem(array('dbo' => $this->source_db));      
        
        if(!($this->task->query = $this->menu_query())) return;
        
        $this->items_new($ids);   
    }   
    public function menu_types_fix($ids = null)    {
        $task = $this->task;
        //status completed
        $this->status = 'completed';
        return;
    }
    
    public function menu_fix($ids = null)    {
        
        $this->destination_model = new CYENDModelItem(array('dbo' => $this->destination_db));        
        $this->source_model = new CYENDModelItem(array('dbo' => $this->source_db));      

        $this->task->query = 'SELECT ' . $this->id . '
            FROM #__' . $this->task->name . '
            WHERE ' . $this->id . ' > 0';
        
        $this->task->query = 'SELECT ' . $this->id . '
            FROM #__' . $this->task->name . '
            WHERE parent_id > 0';
        
        $this->items_new_fix($ids);
    }
    
    private function menu_query() {
        //Filter per menu_types already transferred
        $query = $this->destination_db->getQuery(true);
        $query->select('a.menutype');
        $query->from('#__menu_types AS a');
        $query->join('LEFT', '`#__sptransfer_log` AS b ON b.destination_id = a.id');
        $query->where('b.tables_id = 15 AND b.state >= 2');
        $query->order('b.id ASC');
        $this->destination_db->setQuery($query);
        $result = $this->destination_db->query();
        if (!$result) {
            jexit($this->destination_db->getErrorMsg());
        }
        $temp2 = $this->destination_db->loadColumn();

        if (is_null($temp2[0])) {
            jexit('<p>' . JText::_('COM_SPTRANSFER_MENUTYPE_UNAVAILABLE') . '</p>');
        }

        foreach ($temp2 as $i => $temp3) {
            if (strpos($temp3, '-sp-')) {
                $temp4 = explode('-sp-', $temp3);
                $temp3 = $temp4[0];
            }
            $temp2[$i] = '"' . $temp3 . '"';
        }

        $query = 'SELECT id 
            FROM #__menu
            WHERE id > 1';
        $query .= ' AND menutype IN (' . implode(',', $temp2) . ')';

        return $query;
    }
    
}
