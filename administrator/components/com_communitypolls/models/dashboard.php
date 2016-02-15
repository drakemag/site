<?php
/**
 * @version		$Id: default.php 01 2014-01-26 11:37:09Z maverick $
 * @package		CoreJoomla.Polls
 * @subpackage	Components
 * @copyright	Copyright (C) 2009 - 2014 corejoomla.com. All rights reserved.
 * @author		Maverick
 * @link		http://www.corejoomla.com/
 * @license		License GNU General Public License version 2 or later
 */
defined('_JEXEC') or die;
jimport('joomla.application.component.modelitem');

class CommunityPollsModelDashboard extends JModelLegacy
{
	public function getPolls()
	{
		$db = $this->getDbo();
		$query = $db->getQuery(true);
		$user = JFactory::getUser();
		$app = JFactory::getApplication();
		
		// Select the required fields from the table.
		$query->select(
				$this->getState(
						'list.select',
						'a.id, a.title, a.alias, a.checked_out, a.checked_out_time, a.catid' .
						', a.published, a.access, a.created, a.created_by, a.created_by_alias, a.ordering, a.featured, a.language, a.votes' .
						', a.publish_up, a.publish_down'
				)
		);
		$query->from('#__jcp_polls AS a');
		
		// Join over the language
		$query->select('l.title AS language_title')
		->join('LEFT', $db->quoteName('#__languages') . ' AS l ON l.lang_code = a.language');
		
		// Join over the users for the checked out user.
		$query->select('uc.name AS editor')
		->join('LEFT', '#__users AS uc ON uc.id=a.checked_out');
		
		// Join over the asset groups.
		$query->select('ag.title AS access_level')
		->join('LEFT', '#__viewlevels AS ag ON ag.id = a.access');
		
		// Join over the categories.
		$query->select('c.title AS category_title')
		->join('LEFT', '#__categories AS c ON c.id = a.catid');
		
		// Join over the users for the author.
		$query->select('ua.name AS author_name')
		->join('LEFT', '#__users AS ua ON ua.id = a.created_by');
		
		// Implement View Level Access
		if (!$user->authorise('core.admin'))
		{
			$groups = implode(',', $user->getAuthorisedViewLevels());
			$query->where('a.access IN (' . $groups . ')');
		}
		
		$query->order($db->escape('a.created desc'));
		$db->setQuery($query, 0, 10);
		
		try
		{
			$polls = $db->loadObjectList();
			return $polls; 
		} 
		catch (Exception $e){}
		
		return false;
	}
	
	public function getVotes()
	{
		$db = JFactory::getDbo();
		$query = $db->getQuery(true);
		
		$query
			->select('count(*) as votes, date(voted_on) as dvoted')
			->from('#__jcp_votes')
			->where('voted_on >= DATE_SUB(CURRENT_DATE, INTERVAL 1 YEAR)')
			->group('date(voted_on)')
			->order('voted_on desc');
		
		$db->setQuery($query);
		
		try
		{
			$votes = $db->loadObjectList();
			return $votes;
		}
		catch (Exception $e){}
		
		return false;
	}
	
	public function getTopVoters()
	{
		$db = JFactory::getDbo();
		$query = $db->getQuery(true);
		
		$query->select('a.id, a.votes, a.last_voted')
		->from('#__jcp_users AS a');
		
		$query->select('u.name as username')
		->join('LEFT', '#__users AS u on a.id = u.id');
		
		$query->where('a.votes > 0');
		$query->order('a.votes desc');
		
		$db->setQuery($query, 0, 10);
		
		try
		{
			$voters = $db->loadObjectList();
			return $voters;
		}
		catch (Exception $e){}
		
		return false;
	}
	
	public function getGeoLocationReport()
	{
		$db = JFactory::getDbo();
		try
		{
			$query = $db->getQuery(true)
				->select('count(*) as votes, a.country, c.country_name')
				->from('#__jcp_tracking AS a')
				->join('left', '#__corejoomla_countries AS c ON a.country = c.country_code')
				->group('country');
	
			$db->setQuery($query);
			$replyCounts = $db->loadAssocList('country');
			return $replyCounts;
		}
		catch (Exception $e){}
		return false;
	}
}
