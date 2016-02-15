<?php
/**
 * @version		$Id: users.php 01 2014-01-26 11:37:09Z maverick $
 * @package		CoreJoomla.Polls
 * @subpackage	Components
 * @copyright	Copyright (C) 2009 - 2014 corejoomla.com. All rights reserved.
 * @author		Maverick
 * @link		http://www.corejoomla.com/
 * @license		License GNU General Public License version 2 or later
 */
defined('_JEXEC') or die();
jimport('joomla.application.component.controlleradmin');

class CommunityPollsControllerUsers extends JControllerAdmin 
{
	public function __construct($config = array())
	{
		parent::__construct($config);
		
		if(APP_VERSION < 3)
		{
			$this->input = JFactory::getApplication()->input;
		}
	}

	public function getModel($name = 'Users', $prefix = 'CommunityPollsModel', $config = array('ignore_request' => true))
	{
		$model = parent::getModel($name, $prefix, $config);
	
		return $model;
	}
	
	public function rebuild()
	{
		JSession::checkToken() or jexit(JText::_('JINVALID_TOKEN'));
	
		$extension = $this->input->get('extension');
		$this->setRedirect(JRoute::_('index.php?option=com_communitypolls&view=users', false));
	
		$model = $this->getModel();
	
		if ($model->rebuild())
		{
			// Rebuild succeeded.
			$this->setMessage(JText::_('COM_COMMUNITYPOLLS_REBUILD_SUCCESS'));
	
			return true;
		}
		else
		{
			// Rebuild failed.
			$this->setMessage(JText::_('COM_COMMUNITYPOLLS_REBUILD_FAILURE'));
	
			return false;
		}
	}
	
	protected function postDeleteHook(JModelLegacy $model, $ids = null)
	{
	}
}