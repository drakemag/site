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
class SPTransferControllerFiles extends JControllerAdmin {
    
    public function getModel($name = 'Files', $prefix = 'SPTransferModel', $config = array()) {
        
        $model = parent::getModel($name, $prefix, array('ignore_request' => true));
        return $model;
    }

    function browse() {
        // Check for request forgeries
        JRequest::checkToken() or jexit(JText::_('JINVALID_TOKEN'));

        $url = 'index.php?option=com_sptransfer&view=files';

        //folder remote
        $folder_remote = JRequest::getVar('folder_remote');
        if (!empty($folder_remote))
            $url .= '&folder_remote=' . $folder_remote;

        //folder local
        $folder_local = JRequest::getVar('folder_local');
        if (!empty($folder_local))
            $url .= '&folder_local=' . $folder_local;

        $this->setRedirect($url);

        return;
    }

    function transfer() {
        // Check for request forgeries
        JRequest::checkToken() or jexit(JText::_('JINVALID_TOKEN'));
        
        //delete log file
        $log_file = JFactory::getConfig()->get('log_path') . DIRECTORY_SEPARATOR . 'com_sptransfer.php';
        if (file_exists($log_file))
            unlink ($log_file);
        
        //Disable warnings
        error_reporting(E_ERROR | E_PARSE);
        set_time_limit(0);
        
        $model = parent::getModel('Files', 'SPTransferModel');
        if(!$model->transfer()) {
            jexit($model->getError());
        }
        
        // Finish
        //enable warnings
        error_reporting(E_ALL);
        set_time_limit(30);

        $result = Array();
        $result['status'] = 'completed';
        jexit(json_encode($result));
    }

}

