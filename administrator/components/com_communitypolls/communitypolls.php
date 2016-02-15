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

// //////// Mootools hacks ////////////
// JHtml::_('behavior.framework', true);
// JHtml::_('jquery.framework');

// $document = JFactory::getDocument();
// $headData = $document->getHeadData();
// $scripts = $headData['scripts'];

// $scripts2 = array();
// foreach ($scripts as $url=>$data)
// {
// 	if(strpos($url, 'mootools') > 0)
// 	{
// 		$scripts2[$url] = $data;
// 		unset($scripts[$url]);
// 	}
// }

// $scripts = array_merge($scripts2, $scripts);
// $headData['scripts'] = $scripts;
// $document->setHeadData($headData);
// ////////////////////////////////////

////////////////////////////////////////// CjLib Includes ///////////////////////////////////////////////
$cjlib = JPATH_ROOT.'/components/com_cjlib/framework.php';
if(file_exists($cjlib))
{
	require_once $cjlib;
}
else
{
	die('CJLib (CoreJoomla API Library) component not found. Please download and install it to continue.');
}
CJLib::import('corejoomla.framework.core');
////////////////////////////////////////// CjLib Includes ///////////////////////////////////////////////

if (!JFactory::getUser()->authorise('core.manage', 'com_communitypolls'))
{
	return JError::raiseWarning(404, JText::_('JERROR_ALERTNOAUTHOR'));
}

if(APP_VERSION >= 3)
{
	JHtml::_('behavior.tabstate');
}

JLoader::register('CommunityPollsHelper', JPATH_COMPONENT_ADMINISTRATOR.'/helpers/communitypolls.php');
require_once JPATH_COMPONENT_SITE.'/helpers/constants.php';
require_once JPATH_COMPONENT_SITE.'/helpers/charts.php';

JFactory::getLanguage()->load('com_communitypolls', JPATH_ROOT);
$controller = JControllerLegacy::getInstance('CommunityPolls');
$controller->execute(JFactory::getApplication()->input->get('task'));
$controller->redirect();