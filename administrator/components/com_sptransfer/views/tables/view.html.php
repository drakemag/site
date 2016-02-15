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

// import Joomla view library
jimport('joomla.application.component.view');

/**
 * SPTransfers View
 */
class SPTransferViewTables extends JViewLegacy {

    /**
     * SPTransfers view display method
     * @return void
     */
    function display($tpl = null) {
        
        //live update
        jimport('spcyend.utilities.remoteupdate');
        $remoteupdate = CYENDModelRemoteUpdate::getInstance('RemoteUpdate', 'CYENDModel');
        if ($remoteupdate->setDownloadId()) {
            $remoteupdate->updateDownloadId('pkg_sptransfer');
            $remoteupdate->updateDownloadId('spcyend');
        }
        
        // Get data from the model
        $this->dbTestConnection = $this->get('TestConnection');
        if ($this->dbTestConnection) {
            CYENDFactory::enqueueMessage(JText::_('SPLIB_MSG_SUCCESS_CONNECTION'), 'notice');
        } else {
            CYENDFactory::enqueueMessage(JText::_('SPLIB_MSG_ERROR_CONNECTION'), 'error');
        }
        $items = $this->get('Items');
        $pagination = $this->get('Pagination');
        
        //create tags tables if not present
        $this->get('TagsTables');

        // Check for errors.
        if (count($errors = $this->get('Errors'))) {
            JError::raiseError(500, implode('<br />', $errors));
            return false;
        }
        // Assign data to the view
        $this->items = $items;
        $this->pagination = $pagination;

        // Set the toolbar
        $this->addToolBar();

        //Set JavaScript
        $this->addJS();

        // Display the template
        parent::display($tpl);

        // Set the document
        $this->setDocument();
    }

    /**
     * Setting the toolbar
     */
    protected function addToolBar() {
        $canDo = SPTransferHelper::getActions();

        JToolBarHelper::title(JText::_('COM_SPTRANSFER_TABLES_TITLE'), 'copy');
               
        if ($canDo->get('core.admin')) {
            $bar = JToolBar::getInstance('toolbar');
            if ($this->dbTestConnection) {
                $bar->appendButton('Confirm', 'COM_SPTRANSFER_CONFIRM_MSG', 'copy', 'COM_SPTRANSFER_TRANSFER', 'tables.transfer', true);
                $bar->appendButton('Confirm', 'COM_SPTRANSFER_CONFIRM_MSG', 'purge', 'COM_SPTRANSFER_FIX', 'tables.fix', true);
                //$bar->appendButton('Confirm', 'COM_SPTRANSFER_CONFIRM_MSG', 'move', 'COM_SPTRANSFER_TRANSFER_ALL', 'tables.transfer_all', false);
                JToolBarHelper::divider();
            }
            JToolBarHelper::preferences('com_sptransfer');
        }
        $bar =  JToolBar::getInstance('toolbar');
        $bar->appendButton('Help', 'help', 'JTOOLBAR_HELP', 'http://cyend.com/extensions/extensions/components/documentation/88-user-guide-sp-transfer', 640, 480);
    }

    /**
     * Method to set up the document properties
     *
     * @return void
     */
    protected function setDocument() {
        $document = JFactory::getDocument();
        $document->setTitle(JText::_('COM_SPTRANSFER_ADMINISTRATION'));
        //$document->addScript(JUri::root().'libraries'.DIRECTORY_SEPARATOR.'spcyend'.DIRECTORY_SEPARATOR.'bootstrap'.DIRECTORY_SEPARATOR.'js'.DIRECTORY_SEPARATOR.'bootstrap.min.js');
        //$document->addStyleSheet(JUri::root().'libraries'.DIRECTORY_SEPARATOR.'spcyend'.DIRECTORY_SEPARATOR.'bootstrap'.DIRECTORY_SEPARATOR.'css'.DIRECTORY_SEPARATOR.'bootstrap.min.css');
    }

    private function addJS() {
        //Handle chosed items
        $rows = "";
        foreach ($this->items as $item) {
            $rows .= "rows[" . $item->id . "]='" . $item->extension_name . "_" . $item->name . "';\n";
        }

        //Choose items
        $js = "
		function jSelectItem(extension_name, name, id_arr) {

            if (extension_name == 'com_users.notes') {
                extension_name = 'com_users';
                name = 'notes_categories';
            }
            
            rows = new Array();
            var id_type;
            " . $rows . "                
            for(i=0;i<=" . count($this->items) . ";i++) {            
                if (rows[i] == extension_name+'_'+name) {
                    id_type = i;
                }
            }    
            var input_ids = 'input_ids'+id_type;
            var input_id;
            var chklength = id_arr.length;
            for(k=0;k<chklength;k++) {
                input_id = document.getElementById(input_ids);
                if (input_id.value == '') {
                    input_id.value = id_arr[k];
                } else {
                    input_id.value = input_id.value + ',' + id_arr[k];
                }                
            }
            SqueezeBox.close();
    	}";

        $doc = JFactory::getDocument();
        $doc->addScriptDeclaration($js);

        //Clear selected items
        $js = "
		function jClearItem(extension_name, name) {
            var rows = new Array();
            var id_type;
            " . $rows . "
            for(i=0;i<=" . count($this->items) . ";i++) {            
                if (rows[i] == extension_name+'_'+name) {
                    id_type = i;
                }
            }
            var input_ids = 'input_ids'+id_type;
            document.getElementById(input_ids).value = '';            
    	}";

        $doc->addScriptDeclaration($js);
        
        $doc->addScript(JURI::root() . 'media/com_sptransfer/js/core.js');
        $doc->addScript(JURI::root() . 'media/com_sptransfer/js/submit.js');
    }

}
