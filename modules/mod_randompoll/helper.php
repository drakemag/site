<?php
/**
 * @version		$Id: helper.php 01 2012-04-21 11:37:09Z maverick $
 * @package		CoreJoomla.Polls
 * @subpackage	Modules.site
 * @copyright	Copyright (C) 2009 - 2012 corejoomla.com, Inc. All rights reserved.
 * @author		Maverick
 * @link		http://www.corejoomla.com/
 * @license		License GNU General Public License version 2 or later
 */
defined('_JEXEC') or die('Direct Access to this location is not allowed.');

class modRandomPollHelper 
{
	public static function get_poll_details($pollids, $categories, $show_closed = true, $show_grid_polls = true, $excludes = array())
	{
		$app = JFactory::getApplication();
		$db = JFactory::getDbo();
		$params = JComponentHelper::getParams('com_communitypolls');
		$listtype = $params->get('listtype', 'random');
		
		$wheres = array();
		$order = '';

		if( !empty( $pollids ) ) 
		{
			// limit to selected poll ids
			$pollids = explode(',', $pollids);
			JArrayHelper::toInteger($pollids);

			$wheres[] = 'p.id = '.$pollids[array_rand($pollids)];
			$order = ' order by rand()';
		} 
		else if( !empty( $categories ) ) 
		{
			// limit to selected category ids
			$categories = explode(',', $categories);
			JArrayHelper::toInteger($categories);

			$wheres[] = 'p.catid in ('.implode(',', $categories) .')';
			$order = $listtype == 'random' ? ' order by rand()' : ' order by created desc';
		} 
		else 
		{
			$order = $listtype == 'random' ? ' order by rand()' : ' order by created desc';
		}
		
		if(!empty($excludes))
		{
			$wheres[] = 'p.id not in ('.implode(',', $excludes).')';
		}

		$wheres[] = 'p.published = 1';
		
		if($show_closed == 0)
		{
			$wheres[] = 'p.close_date = '.$db->quote($db->getNullDate()).' or p.close_date > now()';
		}
		
		if($show_grid_polls == 0)
		{
			$wheres[] = 'p.'.$db->quoteName('type').' != '.$db->quote('grid');
		}
		
		$language = JFactory::getLanguage();
		$wheres[] = 'c.'.$db->quoteName('language').' in (' . $db->quote( $language->getTag() ) . ',' . $db->quote('*') . ')';
		$wheres[] = 'p.private = 0';
		
		$where = '(' . implode( ' ) and ( ', $wheres ) . ')';
		$query = 'select p.id from #__jcp_polls p left join #__categories c on p.catid = c.id where '.$where.$order;
		$db->setQuery($query, 0, 1);
		$id = $db->loadResult();
		
		if($id)
		{
			if(!class_exists('CommunityPollsModelPoll'))
			{
				JLoader::import('joomla.application.component.model');
				JLoader::import('poll', JPATH_ROOT.'/components/com_communitypolls/models');
			}
			
			$model = JModelLegacy::getInstance( 'poll', 'CommunityPollsModel' );
			$model->setState('load.answers', true);
			$model->setState('load.details', true);
			$model->setState('load.eligibility', true);
			$model->setState('load.suggestions', false);
			$model->setState('params', $params);

			$poll = $model->getItem($id);
			return $poll;
		}
		
		return false;
	}
} //end
