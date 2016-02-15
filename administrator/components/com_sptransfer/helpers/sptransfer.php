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
defined('_JEXEC') or die;

/**
 * SPTransfer component helper.
 */
abstract class SPTransferHelper
{
	/**
	 * Configure the Linkbar.
	 */
	public static function addSubmenu($submenu) 
	{
		JSubMenuHelper::addEntry(JText::_('COM_SPTRANSFER_TABLES_SUBMENU'), 'index.php?option=com_sptransfer', $submenu == 'tables');
                JSubMenuHelper::addEntry(JText::_('COM_SPTRANSFER_DATABASE_SUBMENU'), 'index.php?option=com_sptransfer&view=database', $submenu == 'database');
                JSubMenuHelper::addEntry(JText::_('COM_SPTRANSFER_FILES_SUBMENU'), 'index.php?option=com_sptransfer&view=files', $submenu == 'files');
                //JSubMenuHelper::addEntry(JText::_('COM_SPTRANSFER_LOG_SUBMENU'), 'index.php?option=com_sptransfer&view=monitoring_log', $submenu == 'monitoring_log');
                JSubMenuHelper::addEntry(JText::_('COM_SPTRANSFER_HISTORY_SUBMENU'), 'index.php?option=com_sptransfer&view=log', $submenu == 'log');
		// set some global property
		//document = JFactory::getDocument();
	}
	/**
	 * Get the actions
	 */
	public static function getActions($itemsId = 0)
	{
		$user	= JFactory::getUser();
		$result	= new JObject;

		if (empty($tablesId)) {
			$assetName = 'com_sptransfer';
		}
		else {
			$assetName = 'com_sptransfer.tables.'.(int) $tablesId;
		}
                
		$actions = array(
			'core.admin', 'core.manage', 'core.create', 'core.edit', 'core.delete'
		);

		foreach ($actions as $action) {
			$result->set($action,	$user->authorise($action, $assetName));
		}

		return $result;
	}        
                
        public static function parseSize($size) {
        if ($size < 1024) {
            return JText::sprintf('COM_SPTRANSFER_FILESIZE_BYTES', $size);
        } elseif ($size < 1024 * 1024) {
            return JText::sprintf('COM_SPTRANSFER_FILESIZE_KILOBYTES', sprintf('%01.2f', $size / 1024.0));
        } else {
            return JText::sprintf('COM_SPTRANSFER_FILESIZE_MEGABYTES', sprintf('%01.2f', $size / (1024.0 * 1024)));
        }
    }
}
