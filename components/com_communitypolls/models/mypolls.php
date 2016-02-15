<?php
/**
 * @version		$Id: polls.php 01 2011-01-11 11:37:09Z maverick $
 * @package		CoreJoomla.Polls
 * @subpackage	Components.site
 * @copyright	Copyright (C) 2009 - 2014 corejoomla.com, Inc. All rights reserved.
 * @author		Maverick
 * @link		http://www.corejoomla.com/
 * @license		License GNU General Public License version 2 or later
 */

defined('_JEXEC') or die;
jimport( 'joomla.application.component.modellist' );
require_once JPATH_COMPONENT_SITE.'/models/polls.php';

class CommunityPollsModelMypolls extends CommunityPollsModelPolls
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
				'voters', 'a.voters',
				'publish_up', 'a.publish_up',
				'publish_down', 'a.publish_down'
			);
		}
		
		parent::__construct($config);
	}

	protected function populateState($ordering = 'a.created', $direction = 'DESC')
	{
		parent::populateState();
		$this->setState('filter.author_id', JFactory::getUser()->id);
	}
}
