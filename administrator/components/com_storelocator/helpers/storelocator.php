<?php
/**
 * @version     2.0.0
 * @package     com_storelocator
 * @copyright   Copyright (C) 2013. Sysgen Media LLC. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 * @author      Sysgen Media <support@sysgenmedia.com> - http://www.sysgenmedia.com
 */

// No direct access
defined('_JEXEC') or die;

/**
 * Storelocator helper.
 */
class StorelocatorHelper
{
	/**
	 * Configure the Linkbar.
	 */
	public static function addSubmenu($vName = '')
	{
		JHtmlSidebar::addEntry(
			JText::_('COM_STORELOCATOR_TITLE_LOCATIONS'),
			'index.php?option=com_storelocator&view=locations',
			$vName == 'locations'
		);
		JHtmlSidebar::addEntry(
			JText::_('COM_STORELOCATOR_TITLE_CATEGORIES'),
			'index.php?option=com_storelocator&view=categories',
			$vName == 'categories'
		);
		JHtmlSidebar::addEntry(
			JText::_('COM_STORELOCATOR_TITLE_TAGS'),
			'index.php?option=com_storelocator&view=tags',
			$vName == 'tags'
		);
		JHtmlSidebar::addEntry(
			JText::_('COM_STORELOCATOR_TITLE_MARKERS'),
			'index.php?option=com_storelocator&view=markers',
			$vName == 'markers'
		);
		JHtmlSidebar::addEntry(
			JText::_('COM_STORELOCATOR_TITLE_SEARCHLOG'),
			'index.php?option=com_storelocator&view=searchlog',
			$vName == 'searchlog'
		);
		JHtmlSidebar::addEntry(
			JText::_('COM_STORELOCATOR_TITLE_IMPORTEXPORT'),
			'index.php?option=com_storelocator&view=importexport',
			$vName == 'importexport'
		);
		JHtmlSidebar::addEntry(
			JText::_('COM_STORELOCATOR_TITLE_BATCHGEOCODING'),
			'index.php?option=com_storelocator&view=batchgeocoding',
			$vName == 'batchgeocoding'
		);

	}

	/**
	 * Gets a list of the actions that can be performed.
	 *
	 * @return	JObject
	 * @since	1.6
	 */
	public static function getActions()
	{
		$user	= JFactory::getUser();
		$result	= new JObject;

		$assetName = 'com_storelocator';

		$actions = array(
			'core.admin', 'core.manage', 'core.create', 'core.edit', 'core.edit.own', 'core.edit.state', 'core.delete'
		);

		foreach ($actions as $action) {
			$result->set($action, $user->authorise($action, $assetName));
		}

		return $result;
	}
}
