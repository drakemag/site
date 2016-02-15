<?php
/**
 * @version		$Id: association.php 01 2014-01-26 11:37:09Z maverick $
 * @package		CoreJoomla.Polls
 * @subpackage	Components
 * @copyright	Copyright (C) 2009 - 2014 corejoomla.com. All rights reserved.
 * @author		Maverick
 * @link		http://www.corejoomla.com/
 * @license		License GNU General Public License version 2 or later
 */

defined('_JEXEC') or die;

JLoader::register('CommunityPolsHelper', JPATH_ADMINISTRATOR . '/components/com_communitypolls/helpers/communitypolls.php');
JLoader::register('CategoryHelperAssociation', JPATH_ADMINISTRATOR . '/components/com_categories/helpers/association.php');

abstract class CommunityPollsHelperAssociation extends CategoryHelperAssociation
{
	public static function getAssociations($id = 0, $view = null)
	{
		jimport('helper.route', JPATH_COMPONENT_SITE);

		$app = JFactory::getApplication();
		$jinput = $app->input;
		$view = is_null($view) ? $jinput->get('view') : $view;
		$id = empty($id) ? $jinput->getInt('id') : $id;

		if ($view == 'poll')
		{
			if ($id)
			{
				$associations = JLanguageAssociations::getAssociations('com_communitypolls', '#__jcp_polls', 'com_communitypolls.item', $id);

				$return = array();

				foreach ($associations as $tag => $item)
				{
					$return[$tag] = CommunityPollsHelperRoute::getPollRoute($item->id, $item->catid, $item->language);
				}

				return $return;
			}
		}

		if ($view == 'category' || $view == 'categories')
		{
			return self::getCategoryAssociations($id, 'com_communitypolls');
		}

		return array();

	}
}
