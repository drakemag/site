<?php
/**
 * @version		$Id: votes.php 01 2014-01-26 11:37:09Z maverick $
 * @package		CoreJoomla.Polls
 * @subpackage	Components
 * @copyright	Copyright (C) 2009 - 2014 corejoomla.com. All rights reserved.
 * @author		Maverick
 * @link		http://www.corejoomla.com/
 * @license		License GNU General Public License version 2 or later
 */
defined('_JEXEC') or die;
jimport( 'joomla.application.component.modellist' );

class CommunityPollsModelVotes extends JModelList
{
	public function __construct($config = array())
	{
		if (empty($config['filter_fields']))
		{
			$config['filter_fields'] = array(
				'id', 'a.id',
				'option_id', 'a.option_id',
				'column_id', 'a.column_id',
				'a.ip_address', 'a.ip_address',
				'voted_on', 'a.voted_on',
				'voter_id', 'a.voter_id',
				'author_id',
			);

			if (JLanguageAssociations::isEnabled())
			{
				$config['filter_fields'][] = 'association';
			}
		}
		
		parent::__construct($config);
		
		$this->populateState();
	}

	protected function populateState($ordering = null, $direction = null)
	{
		$app = JFactory::getApplication();

		// Adjust the context to support modal layouts.
		if ($layout = $app->input->get('layout'))
		{
			$this->context .= '.' . $layout;
		}
		
		$pollId = $app->getUserStateFromRequest($this->context . '.filter.poll_id', 'filter_poll_id');
		$this->setState('filter.poll_id', $pollId);
		
		$search = $this->getUserStateFromRequest($this->context . '.filter.search', 'filter_search');
		$this->setState('filter.search', $search);

		$authorId = $app->getUserStateFromRequest($this->context . '.filter.author_id', 'filter_author_id');
		$this->setState('filter.author_id', $authorId);

		$limit = $app->getUserStateFromRequest($this->context . '.list.limit', 'limit', $app->getCfg('list_limit'), 'uint');
		$this->getState('list.limit', $limit);
			
		$value = $app->getUserStateFromRequest($this->context . '.limitstart', 'limitstart', 0);
		$limitstart = ($limit != 0 ? (floor($value / $limit) * $limit) : 0);
		$this->setState('list.start', $limitstart);

		parent::populateState('a.voted_on', 'desc');
		
		// Force a language
		$forcedLanguage = $app->input->get('forcedLanguage');

		if (!empty($forcedLanguage))
		{
			$this->setState('filter.language', $forcedLanguage);
			$this->setState('filter.forcedLanguage', $forcedLanguage);
		}
	}

	protected function getStoreId($id = '')
	{
		// Compile the store id.
		$id .= ':' . $this->getState('filter.search');
		$id .= ':' . $this->getState('filter.access');
		$id .= ':' . $this->getState('filter.author_id');

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
		$query
			->select($this->getState('list.select', 'a.id, a.poll_id, a.voter_id, a.voted_on, a.ip_address, a.custom_answer'))
			->from('#__jcp_votes AS a');

		$query->select('o1.title AS answer_title')
			->join('LEFT', $db->qn('#__jcp_options') . ' AS o1 ON o1.id = a.option_id');

		$query->select('o2.title AS column_title')
			->join('LEFT', $db->qn('#__jcp_options') . ' AS o2 ON o2.id = a.column_id');
		
		$query->select('ua.name AS author_name')
			->join('LEFT', $db->qn('#__users'). ' AS ua ON ua.id = a.voter_id');

		$pollId = $this->getState('filter.poll_id');
		if (is_numeric($pollId))
		{
			$query->where('a.poll_id = '.$pollId);
		}
		
		// Filter by author
		$authorId = $this->getState('filter.author_id');
		if (is_numeric($authorId))
		{
			$type = $this->getState('filter.author_id.include', true) ? '= ' : '<>';
			$query->where('a.voter_id ' . $type . (int) $authorId);
		}

		// Filter by search in title.
		$search = $this->getState('filter.search');

		if (!empty($search))
		{
			if (stripos($search, 'id:') === 0)
			{
				$query->where('a.id = ' . (int) substr($search, 3));
			}
			elseif (stripos($search, 'author:') === 0)
			{
				$search = $db->quote('%' . $db->escape(substr($search, 7), true) . '%');
				$query->where('(ua.name LIKE ' . $search . ' OR ua.username LIKE ' . $search . ')');
			}
			else
			{
				$search = $db->quote('%' . $db->escape($search, true) . '%');
				$query->where('(a.title LIKE ' . $search . ' OR a.alias LIKE ' . $search . ')');
			}
		}
		
		// Add the list ordering clause.
		$orderCol = $this->state->get('list.ordering', 'a.voted_on');
		$orderDirn = $this->state->get('list.direction', 'desc');

		$query->order($db->escape($orderCol . ' ' . $orderDirn));
		
		return $query;
	}

	public function getAuthors()
	{
		// Create a new query object.
		$db = $this->getDbo();
		$query = $db->getQuery(true);

		// Construct the query
		$query->select('u.id AS value, u.name AS text')
			->from('#__users AS u')
			->join('INNER', '#__jcp_votes AS v ON v.voter_id = u.id')
			->group('u.id, u.name')
			->order('u.name');

		// Setup the query
		$db->setQuery($query);

		// Return the result
		return $db->loadObjectList();
	}
	
	protected function canDelete($record)
	{
		$user = JFactory::getUser();

		return $user->authorise('core.delete', $this->option);
	}
	
	public function getTable($name = 'Vote', $prefix = 'CommunityPollsTable', $options = array())
	{
		return parent::getTable($name, $prefix, $options);
	}
	
	public function delete(&$pks)
	{
		$dispatcher = JEventDispatcher::getInstance();
		$pks = (array) $pks;
		$table = $this->getTable();
		
		// Include the content plugins for the on delete events.
		JPluginHelper::importPlugin('content');
		
		// Iterate the items to delete each one.
		foreach ($pks as $i => $pk)
		{
		
			if ($table->load($pk))
			{
		
				if ($this->canDelete($table))
				{
		
					$context = $this->option . '.' . $this->name;
		
					// Trigger the onContentBeforeDelete event.
					$result = $dispatcher->trigger($this->event_before_delete, array($context, $table));
		
					if (in_array(false, $result, true))
					{
						$this->setError($table->getError());
						return false;
					}
		
					if (!$table->delete($pk))
					{
						$this->setError($table->getError());
						return false;
					}

					// Trigger the onContentAfterDelete event.
					$dispatcher->trigger($this->event_after_delete, array($context, $table));
		
				}
				else
				{
		
					// Prune items that you can't change.
					unset($pks[$i]);
					$error = $this->getError();
					if ($error)
					{
						JLog::add($error, JLog::WARNING, 'jerror');
						return false;
					}
					else
					{
						JLog::add(JText::_('JLIB_APPLICATION_ERROR_DELETE_NOT_PERMITTED'), JLog::WARNING, 'jerror');
						return false;
					}
				}
		
			}
			else
			{
				$this->setError($table->getError());
				return false;
			}
		}
		
		try 
		{
			$db = JFactory::getDbo();
			
			$query = $db->getQuery(true)
				->update('#__jcp_polls as p')
				->join('left', '(select t.poll_id, count(*) as count from #__jcp_votes t group by t.poll_id) as v on p.id = v.poll_id')
				->set('votes = coalesce(v.count, 0)');
			
			$db->setQuery($query);
			$db->execute();
			
			$query = $db->getQuery(true)
				->update('#__jcp_options AS p')
				->join('left', '(select t.option_id, count(*) as count from #__jcp_votes t group by t.option_id) as v on p.id = v.option_id')
				->set('votes = coalesce(v.count, 0)');
			
			$db->setQuery($query);
			$db->execute();
		}
		catch (Exception $e)
		{
			JLog::add(JText::_('JLIB_APPLICATION_ERROR_DELETE_NOT_PERMITTED'), JLog::WARNING, 'jerror');
			return false;
		}
		
		// Clear the component's cache
		$this->cleanCache();
		
		return true;
	}
	
	public function clear($pid)
	{
		if($pid > 0 && $this->canDelete(null))
		{
			$db = JFactory::getDbo();
			
			try 
			{
				$query = $db->getQuery(true)->delete('#__jcp_votes')->where('poll_id = '.$pid);
				$db->setQuery($query);
				$db->execute();
				
				$query = $db->getQuery(true)->update('#__jcp_polls')->set('votes = 0')->set('voters = 0')->where('id = '.$pid);
				$db->setQuery($query);
				$db->execute();
				
				$query = $db->getQuery(true)->update('#__jcp_options')->set('votes = 0')->where('poll_id = '.$pid);
				$db->setQuery($query);
				$db->execute();
				
				return true;
			}
			catch (Exception $e)
			{
				JLog::add(JText::_('JLIB_APPLICATION_ERROR_DELETE_NOT_PERMITTED'), JLog::WARNING, 'jerror');
				return false;
			}
		}
		
		return false;
	}
}
