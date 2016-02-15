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
class SPTransferViewFiles extends JViewLegacy {

    /**
     * SPTransfers view display method
     * @return void
     */
    function display($tpl = null) {
        // Do not allow cache
        JResponse::allowCache(false);

        JHtml::_('behavior.framework', true);

        // Get data from the model
        $items_remote = $this->get('ItemsRemote');
        $this->assignRef('items_remote', $items_remote);
        $items_local = $this->get('ItemsLocal');
        $this->assignRef('items_local', $items_local);
        
        // Set the toolbar
        $this->addToolBar();

        // Set the document
        $this->setDocument();
        
        //set folders
        $this->folder_remote = JRequest::getVar('folder_remote');
        $this->folder_local = JRequest::getVar('folder_local');
        
        // Display the template
        parent::display($tpl);
        
    }

    /**
     * Setting the toolbar
     */
    protected function addToolBar() {
        $canDo = SPTransferHelper::getActions();

        JToolBarHelper::title(JText::_('COM_SPTRANSFER_TABLES_TITLE'), 'install.png');

        if ($canDo->get('core.admin')) {
            $bar = JToolBar::getInstance('toolbar');
            $bar->appendButton('Confirm', 'COM_SPTRANSFER_CONFIRM_MSG', 'move', 'COM_SPTRANSFER_TRANSFER', 'files.transfer', true);
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
        //Set JavaScript
        $document->addScript(JURI::root().'media/com_sptransfer/js/files.js');
        $document->addScript(JURI::root().'media/com_sptransfer/js/files_2.js');
        $document->addScript(JURI::root().'media/com_sptransfer/js/submit.js');
    }

}
