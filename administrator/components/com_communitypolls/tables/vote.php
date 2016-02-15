<?php
/**
 * @version		$Id: vote.php 01 2014-01-26 11:37:09Z maverick $
 * @package		CoreJoomla.Polls
 * @subpackage	Components.admin.tables
 * @copyright	Copyright (C) 2009 - 2014 corejoomla.com. All rights reserved.
 * @author		Maverick
 * @link		http://www.corejoomla.com/
 * @license		License GNU General Public License version 2 or later
 */

defined('_JEXEC') or die;

class CommunityPollsTableVote extends JTable
{
	public function __construct(JDatabaseDriver $db)
	{
		parent::__construct('#__jcp_votes', 'id', $db);
	}
}