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

include_once JPATH_ADMINISTRATOR . '/components/com_tags/models/tag.php';

class CYENDModelTag extends TagsModelTag {

    public function __construct($config = array()) {
        parent::__construct($config);
        $this->addTablePath(JPATH_ADMINISTRATOR . '/components/com_tags/tables');
    }

    public function getTable($type = 'Tag', $prefix = 'TagsTable', $config = array()) {
        if (empty($config['dbo']))
            $config['dbo'] = $this->_db;

        return JTable::getInstance($type, $prefix, $config);
    }

    protected function canDelete($record) {
        return true;
    }

}

include_once JPATH_COMPONENT . '/models/com.php';

class SPTransferModelCom_Tags extends SPTransferModelCom {

    public function tags($ids = null) {
        /*
          $tags = new JHelperTags;
          JFactory::$database = $this->source_db;
          $query = $tags->getTagItemsQuery(2);
          $this->source_db->setQuery($query);
          $this->source_db->execute();
          $msg = $this->source_db->loadAssocList();
          CYENDFactory::print_r($msg);
          JFactory::$database = $this->destination_db;
          return;
         * 
         */

        $this->destination_model = new CYENDModelTag(array('dbo' => $this->destination_db));
        $this->source_model = new CYENDModelTag(array('dbo' => $this->source_db));

        $this->task->query = 'SELECT ' . $this->id . ' 
            FROM #__' . $this->task->name . '
            WHERE ' . $this->id . ' > 1';

        $this->items_new($ids);
    }

    public function tags_fix($ids = null) {

        $this->destination_model = new CYENDModelTag(array('dbo' => $this->destination_db));
        $this->source_model = new CYENDModelTag(array('dbo' => $this->source_db));

        $this->task->query = 'SELECT ' . $this->id . ' 
            FROM #__' . $this->task->name . '
            WHERE ' . $this->id . ' > 1';

        $this->items_new_fix($ids);
    }

}
