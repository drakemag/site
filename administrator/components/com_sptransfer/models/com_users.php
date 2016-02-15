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

include_once JPATH_ADMINISTRATOR . '/components/com_users/models/group.php';

class CYENDModelGroup extends UsersModelGroup {

    public function getTable($type = 'Usergroup', $prefix = 'JTable', $config = array()) {
        if (empty($config['dbo']))
            $config['dbo'] = $this->_db;

        return JTable::getInstance($type, $prefix, $config);
    }

    protected function canDelete($record) {
        return true;
    }

}

include_once JPATH_ADMINISTRATOR . '/components/com_users/models/level.php';

class CYENDModelLevel extends UsersModelLevel {

    public function getTable($type = 'Viewlevel', $prefix = 'JTable', $config = array()) {
        if (empty($config['dbo']))
            $config['dbo'] = $this->_db;

        return JTable::getInstance($type, $prefix, $config);
    }

    protected function canDelete($record) {
        return true;
    }

}

include_once JPATH_ADMINISTRATOR . '/components/com_users/models/user.php';

class CYENDModelUser extends UsersModelUser {

    public function getTable($type = 'User', $prefix = 'JTable', $config = array()) {
        if (empty($config['dbo']))
            $config['dbo'] = $this->_db;

        return JTable::getInstance($type, $prefix, $config);
    }

    protected function canDelete($record) {
        return true;
    }
    
    public function getItem($pk = null) {
        $item = parent::getItem($pk);
        if (empty($item->email))
            $item->id = 0;
        return $item;
    }

}

include_once JPATH_ADMINISTRATOR . '/components/com_users/models/note.php';

class CYENDModelNote extends UsersModelNote {

    public function getTable($type = 'Note', $prefix = 'UsersTable', $config = array()) {
        JTable::addIncludePath(JPATH_ADMINISTRATOR.DIRECTORY_SEPARATOR.'components'.DIRECTORY_SEPARATOR.'com_users'.DIRECTORY_SEPARATOR.'tables');
        if (empty($config['dbo']))
            $config['dbo'] = $this->_db;

        return JTable::getInstance($type, $prefix, $config);
    }

    protected function canDelete($record) {
        return true;
    }

}

include_once JPATH_COMPONENT . '/models/com.php';

class SPTransferModelCom_Users extends SPTransferModelCom
{
    public function notes($ids = null) {
        $this->destination_model = new CYENDModelNote(array('dbo' => $this->destination_db));        
        $this->source_model = new CYENDModelNote(array('dbo' => $this->source_db));
        
        $this->task->name = 'user_notes';
        
        $this->task->query = 'SELECT ' . $this->id . '
            FROM #__' . $this->task->name . '
            WHERE id > 0';
        
        $this->items_new($ids);   
    }
    
    public function notes_fix($ids = null) {
        $this->destination_model = new CYENDModelNote(array('dbo' => $this->destination_db));        
        $this->source_model = new CYENDModelNote(array('dbo' => $this->source_db));
        
        $this->task->name = 'user_notes';
        
        $this->task->query = 'SELECT ' . $this->id . '
            FROM #__' . $this->task->name . '
            WHERE id > 0';

        $this->items_new_fix($ids);
    }
    public function usergroups($ids = null) {
        $this->destination_model = new CYENDModelGroup(array('dbo' => $this->destination_db));        
        $this->source_model = new CYENDModelGroup(array('dbo' => $this->source_db));
        
        $this->task->query = 'SELECT ' . $this->id . ' 
            FROM #__' . $this->task->name . '
            WHERE parent_id > 0';
        $this->alias = "title";

        $this->items_new($ids);   
    }    
    public function usergroups_fix($ids = null) {
        $this->destination_model = new CYENDModelGroup(array('dbo' => $this->destination_db));        
        $this->source_model = new CYENDModelGroup(array('dbo' => $this->source_db));
        
        $this->task->query = 'SELECT ' . $this->id . '
            FROM #__' . $this->task->name . '
            WHERE parent_id > 0';
        $this->alias = "title";

        $this->items_new_fix($ids);
    }    
    public function viewlevels($ids = null) {
        $this->destination_model = new CYENDModelLevel(array('dbo' => $this->destination_db));        
        $this->source_model = new CYENDModelLevel(array('dbo' => $this->source_db));
        
        $this->task->query = 'SELECT ' . $this->id . '
            FROM #__' . $this->task->name . '
            WHERE id > 0';
        $this->alias = "title";

        $this->items_new($ids);   
    }    
    public function viewlevels_fix($ids = null) {
        $this->destination_model = new CYENDModelLevel(array('dbo' => $this->destination_db));        
        $this->source_model = new CYENDModelLevel(array('dbo' => $this->source_db));
        
        $this->task->query = 'SELECT ' . $this->id . '
            FROM #__' . $this->task->name . '
            WHERE id > 0';
        $this->alias = "title";

        $this->items_new_fix($ids);
    }
    public function users($ids = null) {
        $this->destination_model = new CYENDModelUser(array('dbo' => $this->destination_db));        
        $this->source_model = new CYENDModelUser(array('dbo' => $this->source_db));
        
        $this->task->query = 'SELECT ' . $this->id . '
            FROM #__' . $this->task->name . '
            WHERE id > 0';        
        $this->alias = "username";

        $this->items_new($ids);   
    }  
    public function users_fix($ids = null) {
        $this->destination_model = new CYENDModelUser(array('dbo' => $this->destination_db));        
        $this->source_model = new CYENDModelUser(array('dbo' => $this->source_db));
        
        $this->task->query = 'SELECT ' . $this->id . '
            FROM #__' . $this->task->name . '
            WHERE id > 0';

        $this->items_new_fix($ids);
    }
}
