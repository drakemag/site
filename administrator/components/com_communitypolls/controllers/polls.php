<?php
/**
 * @version		$Id: polls.php 01 2014-01-26 11:37:09Z maverick $
 * @package		CoreJoomla.Polls
 * @subpackage	Components
 * @copyright	Copyright (C) 2009 - 2014 corejoomla.com. All rights reserved.
 * @author		Maverick
 * @link		http://www.corejoomla.com/
 * @license		License GNU General Public License version 2 or later
 */
defined('_JEXEC') or die();
jimport('joomla.application.component.controlleradmin');

class CommunityPollsControllerPolls extends JControllerAdmin 
{
	public function __construct($config = array())
	{
		parent::__construct($config);
		$this->registerTask('unfeatured', 'featured');
		$this->registerTask('unpublish', 'publish');
		
		if(APP_VERSION < 3)
		{
			$this->input = JFactory::getApplication()->input;
		}
	}
	
	public function getModel($name = 'Poll', $prefix = 'CommunityPollsModel', $config = array('ignore_request' => true))
	{
		$model = parent::getModel($name, $prefix, $config);
	
		return $model;
	}
	
	protected function postDeleteHook(JModelLegacy $model, $ids = null)
	{
		$model->postDeleteActions($ids);
	}
	
	public function featured()
	{
		// Check for request forgeries
		JSession::checkToken() or jexit(JText::_('JINVALID_TOKEN'));

		$user   = JFactory::getUser();
		$ids    = $this->input->get('cid', array(), 'array');
		$values = array('featured' => 1, 'unfeatured' => 0);
		$task   = $this->getTask();
		$value  = JArrayHelper::getValue($values, $task, 0, 'int');

		if (empty($ids))
		{
			JError::raiseWarning(500, JText::_('JERROR_NO_ITEMS_SELECTED'));
		}
		else if (!$user->authorise('core.edit.state', 'com_communitypolls'))
		{
			JError::raiseNotice(403, JText::_('JLIB_APPLICATION_ERROR_EDITSTATE_NOT_PERMITTED'));
		}
		else 
		{
			// Get the model.
			$model = $this->getModel();

			// Publish the items.
			if (!$model->featured($ids, $value))
			{
				JError::raiseWarning(500, $model->getError());
			}
		}
		
		$this->setRedirect('index.php?option=com_communitypolls&view=polls');
	}
	
	public function publish()
	{
		$cid = JFactory::getApplication()->input->get('cid', array(), 'array');
		parent::publish();
	}
}