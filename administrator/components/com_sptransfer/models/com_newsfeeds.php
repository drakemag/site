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

include_once JPATH_ADMINISTRATOR . '/components/com_newsfeeds/models/newsfeed.php';

class CYENDModelNewsfeed extends NewsfeedsModelNewsfeed {

    public function __construct($config = array()) {
        parent::__construct($config);
        $this->addTablePath(JPATH_ADMINISTRATOR . '/components/com_newsfeeds/tables');
    }

    public function getTable($type = 'Newsfeed', $prefix = 'NewsfeedsTable', $config = array()) {
        if (empty($config['dbo']))
            $config['dbo'] = $this->_db;

        return JTable::getInstance($type, $prefix, $config);
    }

    protected function canDelete($record) {
        return true;
    }

}

include_once JPATH_COMPONENT . '/models/com.php';

class SPTransferModelCom_Newsfeeds extends SPTransferModelCom
{

    public function newsfeeds($ids = null)    {
        $this->destination_model = new CYENDModelNewsfeed(array('dbo' => $this->destination_db));        
        $this->source_model = new CYENDModelNewsfeed(array('dbo' => $this->source_db));      
        
        $this->task->query = 'SELECT ' . $this->id . '
            FROM #__' . $this->task->name . '
            WHERE ' . $this->id . ' > 0';
        
        $this->items_new($ids);   
    }
    public function newsfeeds_fix($ids = null)    {
        $this->destination_model = new CYENDModelNewsfeed(array('dbo' => $this->destination_db));        
        $this->source_model = new CYENDModelNewsfeed(array('dbo' => $this->source_db));      
        
        $this->task->query = 'SELECT ' . $this->id . '
            FROM #__' . $this->task->name . '
            WHERE catid > 0';
        
        $this->items_new_fix($ids);
    }
}
