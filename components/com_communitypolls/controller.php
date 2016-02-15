<?php
/**
 * @version		$Id: controller.php 01 2011-01-11 11:37:09Z maverick $
 * @package		CoreJoomla.Polls
 * @subpackage	Components.site
 * @copyright	Copyright (C) 2009 - 2010 corejoomla.com, Inc. All rights reserved.
 * @author		Maverick
 * @link		http://www.corejoomla.com/
 * @license		License GNU General Public License version 2 or later
 */

// no direct access
defined('_JEXEC') or die;

jimport('joomla.application.component.controller');

/**
 * Community Polls Component Controller
 */
class CommunityPollsController extends JControllerLegacy 
{
	public function __construct($config = array())
	{
		$this->input = JFactory::getApplication()->input;

		if ($this->input->get('view') === 'polls' && $this->input->get('layout') === 'modal')
		{
			JHtml::_('stylesheet', 'system/adminlist.css', array(), true);
			$config['base_path'] = JPATH_COMPONENT_ADMINISTRATOR;
		}

		parent::__construct($config);
	}

	public function display($cachable = false, $urlparams = false)
	{
		$doc = JFactory::getDocument();
		$id    = $this->input->getInt('p_id');
		$vName = $this->input->getCmd('view', 'polls');
		$this->input->set('view', $vName);
		$custom_tag = true;
		$cachable = false;

		// Check for edit form.
		if ($vName == 'form' && !$this->checkEditId('com_communitypolls.edit.poll', $id))
		{
			// Somehow the person just went to the form - we don't allow that.
			return JError::raiseError(403, JText::sprintf('JLIB_APPLICATION_ERROR_UNHELD_ID', $id));
		}
		
		$params = JComponentHelper::getParams('com_communitypolls');
		$loadCss = $params->get('load_bootstrap_css', false);
		CjLib::behavior('bootstrap', array('loadcss' => $loadCss, 'customtag'=>$custom_tag));
		
		if($params->get('layout', 'default') != 'bs3')
		{
			CJLib::behavior('bscore', array('customtag'=>$custom_tag));
		}
		
		CJFunctions::load_jquery(array('libs'=>array('fontawesome'), 'custom_tag'=>$custom_tag));
		CJFunctions::add_css_to_document($doc, JUri::root(true).'/media/com_communitypolls/css/cj.polls.min.css', $custom_tag);
		CJFunctions::add_script(JUri::root(true).'/media/com_communitypolls/js/cj.polls.min.js', $custom_tag);
		
		$safeurlparams = array('catid' => 'INT', 'id' => 'INT', 'cid' => 'ARRAY', 'year' => 'INT', 'month' => 'INT', 'limit' => 'UINT', 'limitstart' => 'UINT',
			'showall' => 'INT', 'return' => 'BASE64', 'filter' => 'STRING', 'filter_order' => 'CMD', 'filter_order_Dir' => 'CMD', 'filter-search' => 'STRING', 'print' => 'BOOLEAN', 'lang' => 'CMD', 'Itemid' => 'INT');

		parent::display($cachable, $safeurlparams);

		return $this;
	}
}
?>