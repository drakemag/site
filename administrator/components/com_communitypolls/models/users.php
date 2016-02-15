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
jimport( 'joomla.application.component.modellist' );

class CommunityPollsModelUsers extends JModelList
{
	public function __construct($config = array())
	{
		if (empty($config['filter_fields']))
		{
			$config['filter_fields'] = array(
				'id', 'a.id',
				'name', 'u.name',
				'username', 'u.username',
				'polls', 'a.polls',
				'votes', 'a.votes',
				'last_poll', 'a.last_poll',
				'last_voted', 'a.last_voted'
			);
		}
		
		parent::__construct($config);
	}

	protected function populateState($ordering = null, $direction = null)
	{
		$app = JFactory::getApplication();

		// Adjust the context to support modal layouts.
		if ($layout = $app->input->get('layout'))
		{
			$this->context .= '.' . $layout;
		}

		$search = $this->getUserStateFromRequest($this->context . '.filter.search', 'filter_search');
		$this->setState('filter.search', $search);
		
		// List state information.
		parent::populateState('ua.name', 'asc');
	}

	protected function getStoreId($id = '')
	{
		// Compile the store id.
		$id .= ':' . $this->getState('filter.search');

		return parent::getStoreId($id);
	}

	protected function getListQuery()
	{
		// Create a new query object.
		$db = $this->getDbo();
		$query = $db->getQuery(true);
		$user = JFactory::getUser();
		$app = JFactory::getApplication();

		// Select the required fields from the table.
		$query->select($this->getState('list.select', 'a.id, a.polls, a.votes, a.last_voted, a.last_poll'));
		$query->from('#__jcp_users AS a');

		// Join over the users for the author.
		$query->select('ua.name AS author_name')
			->join('LEFT', '#__users AS ua ON ua.id = a.id');
		
		// Filter by search in title.
		$search = $this->getState('filter.search');

		if (!empty($search))
		{
			if (stripos($search, 'id:') === 0)
			{
				$query->where('a.id = ' . (int) substr($search, 3));
			}
			else
			{
				$search = $db->quote('%' . $db->escape($search, true) . '%');
				$query->where('(ua.name LIKE ' . $search . ' OR ua.username LIKE ' . $search . ')');
			}
		}

		// Add the list ordering clause.
		$orderCol = $this->state->get('list.ordering', 'ua.name');
		$orderDirn = $this->state->get('list.direction', 'asc');
		
		$query->order($db->escape($orderCol . ' ' . $orderDirn));

		return $query;
	}

	public function getItems()
	{
		$items = parent::getItems();

		if (JFactory::getApplication()->isSite())
		{
			$user = JFactory::getUser();
			$groups = $user->getAuthorisedViewLevels();

			for ($x = 0, $count = count($items); $x < $count; $x++)
			{
				// Check the access level. Remove polls the user shouldn't see
				if (!in_array($items[$x]->access, $groups))
				{
					unset($items[$x]);
				}
			}
		}

		return $items;
	}
	
	public function rebuild()
	{
		$db = JFactory::getDbo();
		 
		$query = '
        	insert into
        		#__jcp_users(id, polls, votes)
        	select
        		u.id,
        		(select count(*) from #__jcp_polls p where p.created_by=u.id) AS polls,
        		(select count(*) from #__jcp_votes v where v.voter_id=u.id) AS votes
        	from
        		#__users u
        	on duplicate key
        		update polls = values(polls), votes = values(votes)';
		
		$db->setQuery($query);
		
		try
		{
			$db->query();
		}
		catch (Exception $e){}

		
		$query = $db->getQuery(true)->update('#__jcp_users AS u')->set('u.last_poll = (select max(p.created) from #__jcp_polls p where p.created_by = u.id)');
		$db->setQuery($query);
		
		try
		{
			$db->query();
		}
		catch (Exception $e){}
		 
		$query = $db->getQuery(true)->update('#__jcp_users AS u')->set('u.last_voted = (select max(v.voted_on) from #__jcp_votes v where v.voter_id = u.id)');
		$db->setQuery($query);
		
		try
		{
			$db->query();
		}
		catch (Exception $e){}
		
		return true;
		
	}
}
