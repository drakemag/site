<?php
/**
 * @author Daniel Dimitrov - compojoom.com
 * @date: 13.05.13
 *
 * @copyright  Copyright (C) 2008 - 2013 compojoom.com . All rights reserved.
 * @license    GNU General Public License version 2 or later; see LICENSE
 */

defined('_JEXEC') or die('Restricted access');


class ccommentComponentDPCalendarSettings extends ccommentComponentSettings
{
	public function getCategories() {
		$db = JFactory::getDBO();

		$query = $db->getQuery(true);
		$query->select('id, title');
		$query->from('#__categories');
		$query->where('published = 1');
		$query->where('extension = ' . $db->quote('com_dpcalendar'));
		$query->order('title ASC');

		$db->setQuery($query);
		$options = $db->loadObjectList();

		return $options;
	}
}