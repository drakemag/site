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

// import Joomla controlleradmin library
jimport('joomla.application.component.controlleradmin');

/**
 * SPTransfers Controller
 */
class SPTransferControllerTables extends JControllerAdmin {

    function fix() {
        // Check for request forgeries
        JRequest::checkToken() or jexit(JText::_('JINVALID_TOKEN'));

        //Validate Input IDs
        $statuses = JRequest::getVar('status', array(), '', 'array');
        $input_ids_full = JRequest::getVar('input_ids', array(), '', 'array');
        $ids = JRequest::getVar('cid', array(), '', 'array');
        $id = $ids[0];
        $input_ids = $this->validateInputIDs($input_ids_full, $statuses);
        
        if (!$input_ids) {
            echo JText::_('COM_SPTRANSFER_MSG_ERROR_INVALID_IDS');
            jexit();
        }

        //Initial tasks
        //Disable warnings
        error_reporting(E_ERROR | E_PARSE);
        set_time_limit(0);

        // Main Loop within extensions

        // Get the model.
        $model = parent::getModel('Tables', 'SPTransferModel');

        //Loop on ids

        if (!($item = $model->getItem($id)))
            jexit($model->getError());

        $status = $this->getStatus($statuses);
        $modelContent = parent::getModel($item->extension_name, 'SPTransferModel', array('task' => $item, 'status' => $status));
        $modelContent->{$item->name . '_fix'}($input_ids);

        //end of loop
        // Finish
        //enable warnings
        error_reporting(E_ALL);
        set_time_limit(30);

        $result = $modelContent->getResult();
        jexit(json_encode($result));
    }

    function transfer() {
        // Check for request forgeries
        JRequest::checkToken() or jexit(JText::_('JINVALID_TOKEN'));

        //Validate Input IDs
        $statuses = JRequest::getVar('status', array(), '', 'array');
        $input_ids_full = JRequest::getVar('input_ids', array(), '', 'array');
        $ids = JRequest::getVar('cid', array(), '', 'array');
        $id = $ids[0];
        $input_ids = $this->validateInputIDs($input_ids_full, $statuses);
        
        if (!$input_ids) {
            echo JText::_('COM_SPTRANSFER_MSG_ERROR_INVALID_IDS');
            jexit();
        }
        
        //Initial tasks
        //Disable warnings
        error_reporting(E_ERROR | E_PARSE);
        set_time_limit(0);

        //monitor log        
        // Get the model.
        $model = parent::getModel('Tables', 'SPTransferModel');

        //Main Loop
        //Loop on ids
        
        if (!($item = $model->getItem($id)))
            jexit($model->getError());
        $status = $this->getStatus($statuses);
        $modelContent = parent::getModel($item->extension_name, 'SPTransferModel', array('task' => $item, 'status' => $status));

        echo $modelContent->{$item->name}($input_ids);

        //end of loop
        // Finish
        //enable warnings
        error_reporting(E_ALL);
        set_time_limit(30);

        $result = $modelContent->getResult();
        jexit(json_encode($result));
    }

    function validateInputIDs($input_ids, $statuses) {
        $return = Array();
        foreach ($input_ids as $i => $value) {
            if ($statuses[$i] != 'completed' && $value != "") {
                $ranges = explode(",", $value);
                foreach ($ranges as $range) {
                    if (preg_match("/^[0-9]*$/", $range)) {
                        $return[] = $range;
                    } else {
                        if (preg_match("/^[0-9]*-[0-9]*$/", $range)) {
                            $nums = explode("-", $range);
                            if ($nums[0] >= $nums[1])
                                return false;
                            for ($k = $nums[0]; $k <= $nums[1]; $k++) {
                                $return[] = $k;
                            }
                        } else {
                            return false;
                        }
                    }
                }
                break;
            }
        }
        if (count($return) == 0) {
            return true;
        } else {
            return $return;
        }
    }

    function getStatus($statuses) {

        foreach ($statuses as $value) {
            if ($value != 'completed') {
                return $value;
            }
        }

        return 'completed';
    }

}
