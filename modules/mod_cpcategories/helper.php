<?php
/**
 * @package     corejoomla.site
 * @subpackage  mod_cpcategories
 *
 * @copyright   Copyright (C) 2009 - 2015 corejoomla.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */
defined('_JEXEC') or die;

JHtml::addIncludePath(JPATH_ROOT.'/components/com_content/helpers');

class CpCategoriesHelper 
{
	public static function getCategoryTree($nodes, $excluded)
	{
		$content = '<ul class="cat-list">';
		foreach($nodes as $node)
		{
			if(in_array($node->id, $excluded)) continue;
			$value = CJFunctions::escape($node->title);
	
			if(!empty($node->numitems))
			{
				$value = $value . ' <span class="muted">(' . $node->numitems . ')</span>';
			}
	
			$content = $content . '<li rel="'.$node->id.'">';
			$content = $content . JHtml::link(JRoute::_(CommunityPollsHelperRoute::getCategoryRoute($node->id)), $value);
			
			$children = $node->getChildren();
			if(!empty($children)) 
			{
				$content = $content . CpCategoriesHelper::getCategoryTree($children, $excluded);
			}
	
			$content = $content . '</li>';
		}
	
		$content = $content . '</ul>';
		return $content;
	}
}