<?php
/**
 * @version		$Id: votes.php 01 2011-01-11 11:37:09Z maverick $
 * @package		CoreJoomla.Polls
 * @subpackage	Components.site
 * @copyright	Copyright (C) 2009 - 2014 corejoomla.com, Inc. All rights reserved.
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
				'title', 'a.title',
				'alias', 'a.alias',
				'checked_out', 'a.checked_out',
				'checked_out_time', 'a.checked_out_time',
				'catid', 'a.catid', 'category_title',
				'published', 'a.published',
				'access', 'a.access', 'access_level',
				'created', 'a.created',
				'created_by', 'a.created_by',
				'ordering', 'a.ordering',
				'featured', 'a.featured',
				'language', 'a.language',
				'votes', 'a.votes',
				'publish_up', 'a.publish_up',
				'publish_down', 'a.publish_down'
			);
		}
		
		parent::__construct($config);
	}

	protected function populateState($ordering = 'ordering', $direction = 'ASC')
	{
		$app = JFactory::getApplication();
		$user = JFactory::getUser();
		$params = $app->getParams();

		// List state information
		$value = $app->input->get('limit', $app->getCfg('list_limit', 0), 'uint');
		$this->setState('list.limit', $value);

		$value = $app->input->get('limitstart', 0, 'uint');
		$this->setState('list.start', $value);

		// ordering
		$orderCol = $app->input->get('filter_order', 'v.voted_on');
		if (!in_array($orderCol, $this->filter_fields))
		{
			$orderCol = 'v.voted_on';
		}

		$listOrder = $app->input->get('filter_order_Dir', 'DESC');
		if (!in_array(strtoupper($listOrder), array('ASC', 'DESC', '')))
		{
			$listOrder = 'DESC';
		}

		$this->setState('list.ordering', $orderCol);
		$this->setState('list.direction', $listOrder);
		$this->setState('params', $params);
		$this->setState('filter.language', JLanguageMultilang::isEnabled());
		$this->setState('layout', $app->input->getString('layout'));
	}

	protected function getStoreId($id = '')
	{
		// Compile the store id.
		return parent::getStoreId($id);
	}

	protected function getListQuery()
	{
		// Get the current user for authorisation checks
		$user	= JFactory::getUser();

		// Create a new query object.
		$db = $this->getDbo();
		$query = $db->getQuery(true);

		// Select the required fields from the table.
		$query
			->select('v.id, v.voter_id, v.voted_on, a.title, a.alias, a.language, a.catid, c.title as category_title, c.alias as category_alias')
			->from('#__jcp_votes AS v')
			->join('left', '#__jcp_polls as a on a.id = v.poll_id')
			->join('inner', '#__categories AS c on c.id = a.catid');

		// Add the list ordering clause.
		$query->order($this->getState('list.ordering', 'v.voted_on') . ' ' . $this->getState('list.direction', 'ASC'));
		
		return $query;
	}

	public function getItems()
	{
		$items = parent::getItems();
		$user = JFactory::getUser();
		$userId = $user->get('id');
		$guest = $user->get('guest');
		$groups = $user->getAuthorisedViewLevels();
		$input = JFactory::getApplication()->input;

		// Get the global params
		$globalParams = JComponentHelper::getParams('com_communitypolls', true);

		return $items;
	}

	protected function _buildContentOrderBy()
	{
		$app		= JFactory::getApplication('site');
		$db			= $this->getDbo();
		$params		= $this->state->params;
		$itemid		= $app->input->get('id', 0, 'int') . ':' . $app->input->get('Itemid', 0, 'int');
		$orderCol	= $app->getUserStateFromRequest('com_communitypolls.category.list.' . $itemid . '.filter_order', 'filter_order', '', 'string');
		$orderDirn	= $app->getUserStateFromRequest('com_communitypolls.category.list.' . $itemid . '.filter_order_Dir', 'filter_order_Dir', '', 'cmd');
		$orderby	= ' ';
	
		if (!in_array($orderCol, $this->filter_fields))
		{
			$orderCol = null;
		}
	
		if (!in_array(strtoupper($orderDirn), array('ASC', 'DESC', '')))
		{
			$orderDirn = 'ASC';
		}
	
		if ($orderCol && $orderDirn)
		{
			$orderby .= $db->escape($orderCol) . ' ' . $db->escape($orderDirn) . ', ';
		}
	
		$pollOrderby		= $params->get('orderby_sec', 'rdate');
		$pollOrderDate	= $params->get('order_date');
		$categoryOrderby	= $params->def('orderby_pri', '');
		$secondary			= CommunityPollsHelperQuery::orderbySecondary($pollOrderby, $pollOrderDate) . ', ';
		$primary			= CommunityPollsHelperQuery::orderbyPrimary($categoryOrderby);
	
		$orderby .= $primary . ' ' . $secondary . ' a.created ';
	
		return $orderby;
	}
	
	public function getStart()
	{
		return $this->getState('list.start');
	}
}
