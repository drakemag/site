<?php
/**
 * @version		$Id: view.html.php 01 2014-01-26 11:37:09Z maverick $
 * @package		CoreJoomla.Polls
 * @subpackage	Components
 * @copyright	Copyright (C) 2009 - 2014 corejoomla.com. All rights reserved.
 * @author		Maverick
 * @link		http://www.corejoomla.com/
 * @license		License GNU General Public License version 2 or later
 */

defined('_JEXEC') or die;

class CommunityPollsViewSearch extends JViewLegacy
{
	protected $defaultPageTitle = 'COM_COMMUNITYPOLLS_LABEL_ADVANCED_SEARCH';
	protected $viewName = 'search';

	public function display($tpl = null)
	{
		$app= JFactory::getApplication();
		$user   = JFactory::getUser();
		
		$params = $app->getParams();
		$aparams = JComponentHelper::getParams('com_communitypolls');
		$params->merge($aparams);
		
		// Get some data from the models
		$this->state = new JObject();
		$this->params = &$params;
		$this->pageclass_sfx = htmlspecialchars($params->get('pageclass_sfx'));
		$this->theme = $params->get('theme', 'default');
		
		// Check for errors.
		if (count($errors = $this->get('Errors')))
		{
			JError::raiseError(500, implode("\n", $errors));
		
			return false;
		}
		
		// Check for layout override only if this is not the active menu item
		// If it is the active menu item, then the view and category id will match
		$app = JFactory::getApplication();
		$active	= $app->getMenu()->getActive();

		if ($active && isset($active->query['layout']))
		{
			// We need to set the layout from the query in case this is an alternative menu item (with an alternative layout)
			$this->setLayout($active->query['layout']);
		}
		
		return parent::display($tpl);
	}
}
