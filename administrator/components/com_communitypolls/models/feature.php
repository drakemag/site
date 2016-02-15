<?php
/**
 * @version		$Id: poll.php 01 2014-01-26 11:37:09Z maverick $
 * @package		CoreJoomla.Polls
 * @subpackage	Components
 * @copyright	Copyright (C) 2009 - 2014 corejoomla.com. All rights reserved.
 * @author		Maverick
 * @link		http://www.corejoomla.com/
 * @license		License GNU General Public License version 2 or later
 */

defined('_JEXEC') or die;

require_once __DIR__ . '/poll.php';

class CommunityPollsModelFeature extends CommunityPollsModelPoll
{
	public function getTable($type = 'Featured', $prefix = 'CommunityPollsTable', $config = array())
	{
		return JTable::getInstance($type, $prefix, $config);
	}

	protected function getReorderConditions($table)
	{
		$condition = array();
		return $condition;
	}
}
