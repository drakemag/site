<?php
/**
 * @package     corejoomla.site
 * @subpackage  mod_cpcategories
 *
 * @copyright   Copyright (C) 2009 - 2015 corejoomla.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */
defined('_JEXEC') or die;

require_once JPATH_ROOT.'/components/com_communitypolls/helpers/route.php';
require_once JPATH_ROOT.'/modules/mod_cpcategories/helper.php';
require_once JPATH_ROOT.'/components/com_cjlib/framework.php';

CJLib::import('corejoomla.framework.core');
jimport('joomla.application.categories');

$app = JFactory::getApplication();
CJFunctions::load_jquery(array('libs'=>array('treeview')));
$categories = JCategories::getInstance('CommunityPolls', array('countItems'=>true, 'assetid'=>'cpcategories'));

if(is_object($categories))
{
	$root = intval($params->get('catid', 0));
	$excluded = trim($params->get('excluded', ''));
	
	$excluded = explode(',', $excluded);
	JArrayHelper::toInteger($excluded);
	
	$nodes = $categories->get($root);
	$fields = array();
	$script = '';
	
	if($nodes)
	{
		$nodes = $nodes->getChildren(false);
		$appname = $app->input->getCmd('option', '');
		
		if($appname == 'com_communitypolls')
		{
			$menu = $app->getMenu()->getActive();
			$catid = $menu ? (int) @$menu->query['id'] : 0;
			
			if($catid)
			{
				$script = '
					jQuery(".cp_categories").find("li[rel=\''.$catid.'\']").find(".expandable-hitarea:first").click();
					jQuery(".cp_categories").find("li[rel=\''.$catid.'\']").parents("li.expandable").find(".expandable-hitarea:first").click();
					jQuery(".cp_categories").find("li[rel=\''.$catid.'\']").find("a:first").css("font-weight", "bold");';
			}
		}
		 
		$document = JFactory::getDocument();
		$document->addScriptDeclaration('jQuery(document).ready(function($){jQuery(".cp_categories").find(".cat-list:first").treeview({"collapsed": true});'.$script.'});');
		echo '<div class="cp_categories">'.CpCategoriesHelper::getCategoryTree($nodes, $excluded).'</div>';
	}
}