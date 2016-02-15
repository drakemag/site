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

include_once JPATH_ADMINISTRATOR . '/components/com_banners/models/client.php';

class CYENDModelClient extends BannersModelClient {

    public function __construct($config = array()) {
        parent::__construct($config);
        $this->addTablePath(JPATH_ADMINISTRATOR . '/components/com_banners/tables');
    }

    public function getTable($type = 'Client', $prefix = 'BannersTable', $config = array()) {
        if (empty($config['dbo']))
            $config['dbo'] = $this->_db;

        return JTable::getInstance($type, $prefix, $config);
    }

    protected function canDelete($record) {
        return true;
    }

}

include_once JPATH_ADMINISTRATOR . '/components/com_banners/models/banner.php';

class CYENDModelBanner extends BannersModelBanner {

    public function __construct($config = array()) {
        parent::__construct($config);
        $this->addTablePath(JPATH_ADMINISTRATOR . '/components/com_banners/tables');
    }

    public function getTable($type = 'Banner', $prefix = 'BannersTable', $config = array()) {
        if (empty($config['dbo']))
            $config['dbo'] = $this->_db;

        return JTable::getInstance($type, $prefix, $config);
    }

    protected function canDelete($record) {
        return true;
    }

}

include_once JPATH_COMPONENT . '/models/com.php';

class SPTransferModelCom_Banners extends SPTransferModelCom
{

    public function banner_clients($ids = null)    {
        $this->destination_model = new CYENDModelClient(array('dbo' => $this->destination_db));        
        $this->source_model = new CYENDModelClient(array('dbo' => $this->source_db));      
        
        $this->task->query = 'SELECT ' . $this->id . '
            FROM #__' . $this->task->name . '
            WHERE ' . $this->id . ' > 0';
        
        $this->task->state = 4; //state for success
        
        $this->items_new($ids);  
                
    }
    public function banners($ids = null)    {
        $this->destination_model = new CYENDModelBanner(array('dbo' => $this->destination_db));        
        $this->source_model = new CYENDModelBanner(array('dbo' => $this->source_db));      
        
        $this->task->query = 'SELECT ' . $this->id . '
            FROM #__' . $this->task->name . '
            WHERE ' . $this->id . ' > 0';
        
        $this->items_new($ids);  
        
    }
    public function banner_clients_fix($ids = null)    {
        $task = $this->task;
        //status completed
        $this->status = 'completed';
        return;
    }
    public function banners_fix($ids = null)    {
        $this->destination_model = new CYENDModelBanner(array('dbo' => $this->destination_db));        
        $this->source_model = new CYENDModelBanner(array('dbo' => $this->source_db));      
        
        $this->task->query = 'SELECT ' . $this->id . '
            FROM #__' . $this->task->name . '
            WHERE ' . $this->id . ' > 0';
        
        $this->items_new_fix($ids);  
    }
}
