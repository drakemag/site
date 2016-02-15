<?php
/**
 * @package     Joomla.Administrator
 * @subpackage  com_content
 *
 * @copyright   Copyright (C) 2005 - 2013 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;


/**
 * @package     Joomla.Administrator
 * @subpackage  com_content
 */
abstract class JHtmlContentAdministrator
{
	/**
	 * @param   int $value	The state value
	 * @param   int $i
	 */
	public static function featured($value = 0, $i, $canChange = true)
	{
		JHtml::_('bootstrap.tooltip');

		// Array of image, task, title, action
		$states	= array(
			0	=> array('star-empty',	'locations.featured',	'COM_STORELOCATOR_UNFEATURED',	'COM_STORELCOATOR_TOGGLE_TO_FEATURE'),
			1	=> array('star',		'locations.unfeatured',	'COM_STORELCOATOR_FEATURED',		'COM_STORELCOATOR_TOGGLE_TO_UNFEATURE'),
		);
		$state	= JArrayHelper::getValue($states, (int) $value, $states[1]);
		$icon	= $state[0];
		if ($canChange)
		{
			$html	= '<a href="#" onclick="return listItemTask(\'cb'.$i.'\',\''.$state[1].'\')" class="btn btn-micro hasTooltip' . ($value == 1 ? ' active' : '') . '" title="'.JText::_($state[3]).'"><i class="icon-'
					. $icon.'"></i></a>';
		}

		return $html;
	}
}
