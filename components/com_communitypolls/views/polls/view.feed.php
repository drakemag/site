<?php
/**
 * @version		$Id: view.feed.php 01 2014-01-26 11:37:09Z maverick $
 * @package		CoreJoomla.Polls
 * @subpackage	Components
 * @copyright	Copyright (C) 2009 - 2014 corejoomla.com. All rights reserved.
 * @author		Maverick
 * @link		http://www.corejoomla.com/
 * @license		License GNU General Public License version 2 or later
 */

defined('_JEXEC') or die;

class CommunityPollsViewPolls extends JViewCategoryfeed
{
	protected $viewName = 'poll';

	protected function reconcileNames($item)
	{
		// Get description, author and date
		$app               = JFactory::getApplication();
		$params            = $app->getParams();
		$item->description = $params->get('feed_summary', 0) ? CJFunctions::substrws($item->introtext, 250) : $item->description;

		// Add readmore link to description if introtext is shown, show_readmore is true and fulltext exists
		if (!$item->params->get('feed_summary', 0) && $item->params->get('feed_show_readmore', 0) && $item->description)
		{
			$item->description .= '<p class="feed-readmore"><a target="_blank" href ="' . $item->link . '">' . JText::_('COM_COMMUNITYPOLLS_FEED_READMORE') . '</a></p>';
		}

		$item->author = $item->created_by_alias ? $item->created_by_alias : $item->author;
	}
}
