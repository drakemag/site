<?php
/**
 * @version		$Id: communitypolls.php 01 2013-05-10 15:37:09Z maverick $
 * @package		corejoomla.polls
 * @subpackage	Components
 * @copyright	Copyright (C) 2009 - 2013 corejoomla.com. All rights reserved.
 * @author		Maverick
 * @link		http://www.corejoomla.com/
 * @license		License GNU General Public License version 2 or later
 */

defined('_JEXEC') or die;
JHtml::_('behavior.tabstate');

////////////////////////////////////////// CjLib Includes ///////////////////////////////////////////////
require_once JPATH_ROOT.'/components/com_cjlib/framework.php';
CJLib::import('corejoomla.framework.core');
////////////////////////////////////////// CjLib Includes ///////////////////////////////////////////////

require_once JPATH_COMPONENT.'/helpers/route.php';
require_once JPATH_COMPONENT.'/helpers/query.php';
require_once JPATH_COMPONENT.'/helpers/constants.php';
require_once JPATH_COMPONENT.'/helpers/charts.php';
require_once JPATH_COMPONENT_ADMINISTRATOR.'/helpers/communitypolls.php';

$controller = JControllerLegacy::getInstance('CommunityPolls');
$controller->execute(JFactory::getApplication()->input->get('task'));
$controller->redirect();