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
class SPTransferControllerDatabase extends JControllerAdmin {

    public function getModel($name = 'Database', $prefix = 'SPTransferModel', $config = array()) {
        $model = parent::getModel($name, $prefix, array('ignore_request' => true));
        return $model;
    }

    function transfer() {
        // Check for request forgeries
        JRequest::checkToken() or jexit(JText::_('JINVALID_TOKEN'));

        //Validate Input IDs        
        $statuses = JRequest::getVar('status', array(), '', 'array');
        $input_ids = JRequest::getVar('input_ids', array(), '', 'array');
        $input_ids = $this->validateInputIDs($input_ids);
        if (!$input_ids) {
            $this->setRedirect('index.php?option=com_sptransfer&view=database', JText::_('COM_SPTRANSFER_MSG_ERROR_INVALID_IDS'), 'error');
            return false;
        }

        //Initial tasks
        //Disable warnings
        error_reporting(E_ERROR | E_PARSE);
        set_time_limit(0);

        // Open monitor log
        /*
          echo '<script type="text/javascript">'
          , "var myDomain = location.protocol + '//' +
          location.hostname +
          location.pathname.substring(0, location.pathname.lastIndexOf('/')) +
          '/components/com_sptransfer/log.htm';
          window.open(myDomain,'SP Transfer','width=640,height=480, scrollbars=1');
          return true; "
          , '</script>';
         */

        //check for MySQLi
        //if (!$this->checkDbType(JPATH_SITE.'/configuration.php')) return false;
        // Connect to source db
        $params = JComponentHelper::getParams('com_sptransfer');
        if (!($source_db = $this->connect($params)))
            return false;

        // Main Loop within extensions
        //Get ids
        $ids = JRequest::getVar('cid', array(), '', 'array');
        $input_prefixes = JRequest::getVar('input_prefixes', array(), '', 'array');
        $input_names = JRequest::getVar('input_names', array(), '', 'array');

        // Get the model.
        $model = $this->getModel();

        //Main Loop
        //Loop on ids
        $id = $ids[0];

        $table_name = $input_prefixes[$id - 1] . '_' . $input_names[$id - 1];
        $item = $model->getItem($table_name);
        if (is_null($item)) {
            //Insert new item in tables
            $item = $model->newItem($table_name);
        }
        if (is_null($item)) {
            jexit('<p>' . JText::plural('COM_SPTRANSFER_DATABASE_FAILED', $table_name) . '</p>');
        }

        $status = $this->getStatus($statuses);
        $modelContent = parent::getModel('com_database', 'SPTransferModel', array('source_db' => $source_db, 'task' => $item, 'status' => $status));
        $modelContent->setTable($input_prefixes[$id - 1], $input_names[$id - 1]);

        $modelContent->content($input_ids[$id - 1], $input_prefixes[$id - 1], $input_names[$id - 1]);

        //end loop on ids
        // Finish
        //enable warnings
        error_reporting(E_ALL);
        $source_db->close();
        set_time_limit(30);

        $result = $modelContent->getResult();
        jexit(json_encode($result));
    }

    function connect($params) {
        $option = array(); //prevent problems 
        $option['driver'] = $params->get("driver", 'mysqli');            // Database driver name
        $option['host'] = $params->get("host", 'localhost');    // Database host name
        $option['user'] = $params->get("source_user_name", '');       // User for database authentication
        $option['password'] = $params->get("source_password", '');   // Password for database authentication
        $option['database'] = $params->get("source_database_name", '');      // Database name
        $option['prefix'] = $this->modPrefix($params->get("source_db_prefix", ''));             // Database prefix (may be empty)

        $source_db = JDatabaseDriver::getInstance($option);

        $jAp = JFactory::getApplication();

        // Test connection
        $query = "SELECT id from #__categories WHERE id = 0";
        $source_db->setQuery($query);
        $result = $source_db->query();
        if (!$result) {
            jexit($source_db->getErrorMsg());
        }


        //change character set
        $query = "SHOW VARIABLES LIKE 'character_set_database'";
        $source_db->setQuery($query);
        $result = $source_db->query();
        if (!$result) {
            jexit($source_db->getErrorMsg());
        }

        $source_db->setUTF();

        return $source_db;
    }

    function checkDbType($fileConfiguration) {
        $jAp = JFactory::getApplication();
        $pathValidation = TRUE;
        if (!file_exists($fileConfiguration)) {
            $pathValidation = FALSE;
            jexit('<p>' . JText::sprintf('COM_SPTRANSFER_MSG_INVALIDPATH3', $fileConfiguration) . '</p>');
        }

        if ($pathValidation) {
            $mBool = FALSE;
            $handleConfiguration = @fopen($fileConfiguration, "r");
            while (($buffer = fgets($handleConfiguration, 4096)) !== false) {
                if (strpos($buffer, "mysqli") > 0)
                    $mBool = TRUE;
            }
            fclose($handleConfiguration);

            if (!$mBool) {
                jexit('<p><b><font color="red">' . JText::_('COM_SPTRANSFER_MSG_MYSQLI') . '</font></b></p>');
            }
        }

        return true;
    }

    function validateInputIDs($input_ids) {
        $return = Array();
        foreach ($input_ids as $i => $ids) {
            if ($ids != "") {
                $ranges = explode(",", $ids);
                foreach ($ranges as $j => $range) {
                    if (preg_match("/^[0-9]*$/", $range)) {
                        $return[$i][] = $range;
                    } else {
                        if (preg_match("/^[0-9]*-[0-9]*$/", $range)) {
                            $nums = explode("-", $range);
                            if ($nums[0] >= $nums[1])
                                return false;
                            for ($k = $nums[0]; $k <= $nums[1]; $k++) {
                                $return[$i][] = $k;
                            }
                        } else {
                            return false;
                        }
                    }
                }
            }
        }
        if (count($return) == 0) {
            return true;
        } else {
            return $return;
        }
    }

    function modPrefix($prefix) { //Add underscore if not their
        if (!strpos($prefix, '_'))
            $prefix = $prefix . '_';
        return $prefix;
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
