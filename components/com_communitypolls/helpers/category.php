<?php
/**
 * @version		$Id: category.php 01 2014-01-26 11:37:09Z maverick $
 * @package		CoreJoomla.Polls
 * @subpackage	Components
 * @copyright	Copyright (C) 2009 - 2014 corejoomla.com. All rights reserved.
 * @author		Maverick
 * @link		http://www.corejoomla.com/
 * @license		License GNU General Public License version 2 or later
 */

defined('_JEXEC') or die;

class CommunityPollsCategories extends JCategories
{
	public function __construct($options = array())
	{
		$options['table'] = '#__jcp_polls';
		$options['extension'] = 'com_communitypolls';
		$options['statefield'] = 'published';

		parent::__construct($options);
	}
}
