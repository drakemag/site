<?php
/**
 * @package    CComment
 * @author     DanielDimitrov <daniel@compojoom.com>
 * @date       08.07.13
 *
 * @copyright  Copyright (C) 2008 - 2013 compojoom.com . All rights reserved.
 * @license    GNU General Public License version 2 or later; see LICENSE
 */

defined('_JEXEC') or die('Restricted access');

/**
 * Class CcommentComponentCommunityQuotesPlugin
 *
 * @since  5.0
 */
class CcommentComponentCommunityQuotesPlugin extends ccommentComponentPlugin
{
	/**
	 * With this function we determine if the comment system should be executed for this
	 * content Item
	 *
	 * @return bool
	 */
	public function isEnabled()
	{
		$config = ccommentConfig::getConfig('com_communityquotes');
		$row = $this->row;

		$contentIds = $config->get('basic.exclude_content_items', array());
		$include = $config->get('basic.include_categories', 0);
		$categories = $config->get('basic.categories', array());

		/* doc id excluded ? */
		if (in_array((($row->id == 0) ? -1 : $row->id), $contentIds))
			return false;

		/* category included or excluded ? */
		$result = in_array((($row->catid == 0) ? -1 : $row->catid), $categories);
		if (($include && !$result) || (!$include && $result))
			return false; /* include and not found OR exclude and found */

		return true;
	}

	/**
	 * This function decides whether to show the comments
	 * in an article/item or to show the readmore link
	 *
	 * If it returns true - the comments are shown
	 * If it returns false - the setShowReadon function will be called
	 *
	 * @return boolean
	 */
	public function isSingleView()
	{
		$input = JFactory::getApplication()->input;

		return ($input->getCmd('option') == 'com_communityquotes' && $input->getCmd('view') == 'quotes' && $input->getCmd('task') == 'viewquote');
	}

	/**
	 * Shows a read more button
	 *
	 * @return bool
	 */
	public function showReadOn()
	{
		$config = ccommentConfig::getConfig('com_communityquotes');

		return $config->get('layout.show_readon');
	}

	/**
	 * Creates the link to the quote & comment
	 *
	 * @param   int   $contentId  - the quote id
	 * @param   int   $commentId  - the comment id
	 * @param   bool  $xhtml      - type of link that we need
	 *
	 * @return string
	 */
	public function getLink($contentId, $commentId = 0, $xhtml = true)
	{
		$comment = '';

		if ($commentId)
		{
			$comment = '#!/ccomment-comment=' . $commentId;
		}

		if ($this->row)
		{
			$alias = $this->row->alias;
		}
		else
		{
			$alias = $this->getAlias($contentId);
		}

		$itemId = '&Itemid=' . ccommentHelperUtils::getItemid('com_communityquotes');

		$link = JRoute::_('index.php?option=com_communityquotes&view=quotes&task=viewquote&id=' . $contentId . ':' . $alias . $itemId, $xhtml) . $comment;

		return $link;
	}

	/**
	 * Gets the alias from the db
	 *
	 * @param   int  $itemId  - the quote id
	 *
	 * @return mixed
	 */
	private function getAlias($itemId)
	{
		$db = JFactory::getDbo();
		$query = $db->getQuery(true);

		$query->select('alias')->from('#__quotes')
			->where('id=' . $itemId);

		$db->setQuery($query);

		return $db->loadObject()->alias;
	}

	/**
	 * Returns the id of the author of an item
	 *
	 * In this case the contentId = authorID
	 *
	 * @param   int  $contentId  the quote id
	 *
	 * @return mixed
	 */
	public function getAuthorId($contentId)
	{
		$db = JFactory::getDbo();
		$query = $db->getQuery(true);
		$query->select('created_by')->from('#__quotes')
			->where('id = ' . $db->q($contentId));

		$db->setQuery($query, 0, 1);
		$author = $db->loadObject();

		if ($author)
		{
			return $author->created_by;
		}

		return false;
	}

	/**
	 * In this case we will get the name out of the users table
	 * Jomsocial is just replicating + augmenting the data in their tables
	 *
	 * @param   array  $ids  quotes ids that we need to get the title
	 *
	 * @return mixed
	 */
	public function getItemTitles($ids)
	{
		$db = JFactory::getDbo();
		$query = $db->getQuery(true);
		$query->select('id, title')->from('#__quotes')
			->where('id IN (' . implode(',', $ids) . ')');

		$db->setQuery($query);

		return $db->loadObjectList('id');
	}
}
