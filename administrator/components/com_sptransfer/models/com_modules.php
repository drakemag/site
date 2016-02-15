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

include_once JPATH_ADMINISTRATOR . '/components/com_modules/models/module.php';

class CYENDModelModule extends ModulesModelModule {

    public function __construct($config = array()) {
        parent::__construct($config);
    }

    public function getTable($type = 'Module', $prefix = 'JTable', $config = array()) {
        if (empty($config['dbo']))
            $config['dbo'] = $this->_db;

        return JTable::getInstance($type, $prefix, $config);
    }

    protected function canDelete($record) {
        return true;
    }
        
}

include_once JPATH_COMPONENT . '/models/com.php';

class SPTransferModelCom_Modules extends SPTransferModelCom
{

    public function modules($ids = null)    {
        
        $this->destination_model = new CYENDModelModule(array('dbo' => $this->destination_db));        
        $this->source_model = new CYENDModelModule(array('dbo' => $this->source_db));      
        
        $this->task->query = 'SELECT ' . $this->id . '
            FROM #__' . $this->task->name . '
            WHERE client_id = 0';
        
        $this->items_new($ids);   
    }
    public function modules_fix($ids = null)    {
        
        $this->destination_model = new CYENDModelModule(array('dbo' => $this->destination_db));        
        $this->source_model = new CYENDModelModule(array('dbo' => $this->source_db));      
        
        $this->task->query = 'SELECT ' . $this->id . '
            FROM #__' . $this->task->name . '
            WHERE client_id = 0';
        
        $this->items_new_fix($ids); 
    }
}
