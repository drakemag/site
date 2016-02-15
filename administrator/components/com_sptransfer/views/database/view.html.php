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
class SPTransferViewDatabase extends JViewLegacy {

    /**
     * SPTransfers view display method
     * @return void
     */
    function display($tpl = null) {
        $this->dbTestConnection = $this->get('TestConnection');
        if ($this->dbTestConnection === false ) {
            CYENDFactory::enqueueMessage(JText::_('SPLIB_MSG_ERROR_CONNECTION'), 'error');
            $this->addToolBar();
            return false;
        } else {
            CYENDFactory::enqueueMessage(JText::_('SPLIB_MSG_SUCCESS_CONNECTION'), 'notice');
        }  
        
        // Get data from the model
        $items = $this->get('Items');

        $pagination = $this->get('Pagination');

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
            $bar->appendButton('Confirm', 'COM_SPTRANSFER_CONFIRM_MSG', 'copy', 'COM_SPTRANSFER_TRANSFER', 'database.transfer', true);
            JToolBarHelper::divider();
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
    }

    private function addJS() {
        //Handle chosed items
        $rows = "";
        foreach ($this->items as $item) {
            $rows .= "rows[" . $item->id . "]='" . $item->prefix . "_" . $item->name . "';\n";
        }

        //Choose items
        $js = "
		function jSelectItem(prefix, name, id_arr) {

            rows = new Array();
            var id_type;
            " . $rows . "                
            for(i=0;i<=" . count($this->items) . ";i++) {            
                if (rows[i] == prefix+'_'+name) {
                    id_type = i-1;
                }
            }            
            var chklength = id_arr.length;
            var chklength = id_arr.length;
            var input_ids = 'input_ids'+id_type;
            var input_id;
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
		function jClearItem(prefix, name) {
            rows = new Array();
            var id_type;
            " . $rows . "
            for(i=0;i<=" . count($this->items) . ";i++) {            
                if (rows[i] == prefix+'_'+name) {
                    id_type = i-1;
                }
            }
            var input_ids = 'input_ids'+id_type;
            document.getElementById(input_ids).value = '';            
    	}";

        $doc = JFactory::getDocument();
        $doc->addScriptDeclaration($js);
        
        $doc->addScript(JURI::root() . 'media/com_sptransfer/js/core.js');
        $doc->addScript(JURI::root() . 'media/com_sptransfer/js/submit.js');
    }

}
