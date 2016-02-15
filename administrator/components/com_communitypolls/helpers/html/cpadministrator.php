<?php
/**
 * @version		$Id: helper.php 01 2012-06-30 11:37:09Z maverick $
 * @package		CoreJoomla.Pollss
 * @subpackage	Components
 * @copyright	Copyright (C) 2009 - 2012 corejoomla.com. All rights reserved.
 * @author		Maverick
 * @link		http://www.corejoomla.com/
 * @license		License GNU General Public License version 2 or later
 */
defined('_JEXEC') or die;

abstract class JHtmlCpAdministrator
{
	static function featured($value = 0, $i, $canChange = true)
	{
		// Array of image, task, title, action
		$states	= array(
			0	=> array('disabled.png',	'polls.featured',	'COM_COMMUNITYPOLLS_UNFEATURED',	'COM_COMMUNITYPOLLS_TOGGLE_TO_FEATURE'),
			1	=> array('featured.png',		'polls.unfeatured',	'COM_COMMUNITYPOLLS_FEATURED',		'COM_COMMUNITYPOLLS_TOGGLE_TO_UNFEATURE'),
		);
		
		$state	= JArrayHelper::getValue($states, (int) $value, $states[1]);
		$html	= JHtml::_('image', 'admin/'.$state[0], JText::_($state[2]), NULL, true);
		
		if ($canChange) {
			
			$html	= '<a href="#" onclick="return listItemTask(\'cb'.$i.'\',\''.$state[1].'\')" title="'.JText::_($state[3]).'">'.$html.'</a>';
		}

		return $html;
	}
}
