<?php
/**
 * @version		$Id: mod_randompoll.php 01 2012-04-21 11:37:09Z maverick $
 * @package		CoreJoomla.Polls
 * @subpackage	Modules.site
 * @copyright	Copyright (C) 2009 - 2012 corejoomla.com, Inc. All rights reserved.
 * @author		Maverick
 * @link		http://www.corejoomla.com/
 * @license		License GNU General Public License version 2 or later
 */
defined('_JEXEC') or die;

require_once JPATH_ROOT.'/components/com_cjlib/framework.php';
CJLib::import('corejoomla.framework.core');
// CJLib::import('corejoomla.ui.bootstrap');

// include the helper file
require_once (dirname(__FILE__).DS.'helper.php');
require_once JPATH_ROOT.'/components/com_communitypolls/router.php';
require_once JPATH_ROOT.'/components/com_communitypolls/helpers/constants.php';
require_once JPATH_ROOT.'/components/com_communitypolls/helpers/charts.php';
require_once JPATH_ROOT.'/components/com_communitypolls/helpers/route.php';

// get the items to display from the helper
$show_description	= intval($params->get('showdescription', 0));
$show_category   	= intval($params->get('showcategory', 0));
$show_author     	= intval($params->get('showauthor', 0));
$show_votes      	= intval($params->get('showvotes', 0));
$show_closed		= intval($params->get('showclosed', 0));
$show_grid_polls	= intval($params->get('showgridpolls', 0));
$show_avatar		= intval($params->get('showavatar', 0));
$showlastvote		= intval($params->get('showlastvote', 0));
$chart_width		= intval($params->get('chartwidth', 0));
$chart_height		= intval($params->get('chartheight', 0));
$desc_length		= intval($params->get('desc_length', 0));
$hide_comments		= intval($params->get('hide_comments', 0));
$hide_pie_table		= intval($params->get('hide_pie_table', 0));
$default_view		= trim($params->get('default_view', 'form'));
$chart_type			= trim($params->get('charttype', 'global'));
$categories			= trim($params->get('categories', ''));
$pollids			= trim($params->get('pollids', ''));
$exclude_pollids	= trim($params->get('exclude_pollids', ''));
$allow_images		= intval($params->get('allowimages', 0));
$username			= trim($params->get('username', 'username'));
$moduleclass_sfx 	= htmlspecialchars($params->get('moduleclass_sfx'));

$config = JComponentHelper::getParams('com_communitypolls');
CJFunctions::load_jquery(array('libs'=>array('validate')));

$document = JFactory::getDocument();
$document->addStyleSheet(JURI::base(true).'/modules/mod_randompoll/assets/css/styles.css');
// $document->addScript(JURI::base(true).'/modules/mod_randompoll/assets/scripts/randompoll.js');
$document->addCustomTag('<script src="'.JURI::root(true).'/modules/mod_randompoll/assets/scripts/randompoll.js" type="text/javascript"></script>');

$lang = JFactory::getLanguage();
$lang->load('com_communitypolls', JPATH_SITE);
$lang->load('mod_randompoll', JPATH_SITE);

$excludes = array();

if(!empty($exclude_pollids)){
	
	$excludes = explode(',', $exclude_pollids);
	JArrayHelper::toInteger($excludes);
}

$poll = modRandomPollHelper::get_poll_details($pollids, $categories, $show_closed, $show_grid_polls, $excludes);
$allowed_types = $config->get('allowed_chart_types', array());

if(!empty($poll)){
	
	$poll->chart_type 	= in_array($chart_type, $allowed_types) 
		? $chart_type : ( in_array($poll->chart_type, $allowed_types) ? $poll->chart_type : $config->get('chart_type') );
	$poll->chart_width 	= $config->get('chart_width', 650);
	$poll->chart_height = $config->get('pie_chart_height', 350);
	$poll->slug			= $poll->alias ? ($poll->id.':'.$poll->alias) : $poll->id;
	$poll->catslug		= $poll->category_alias ? ($poll->catid.':'.$poll->category_alias) : $poll->catid;
		
	require JModuleHelper::getLayoutPath( 'mod_randompoll', $params->get('layout', 'default') );
} else {
	
	echo JText::_('COM_COMMUNITYPOLLS_NO_RESULTS_FOUND');
}
?>
