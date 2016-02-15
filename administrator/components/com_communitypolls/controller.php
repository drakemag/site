<?php
/**
 * @version		$Id: controller.php 01 2011-01-11 11:37:09Z maverick $
 * @package		CoreJoomla.Polls
 * @subpackage	Components.controller
 * @copyright	Copyright (C) 2009 - 2010 corejoomla.com, Inc. All rights reserved.
 * @author		Maverick
 * @link		http://www.corejoomla.com/
 * @license		License GNU General Public License version 2 or later
 */
defined('_JEXEC') or die;

class CommunityPollsController extends JControllerLegacy
{
	protected $default_view = 'dashboard';

	public function display($cachable = false, $urlparams = false)
	{
		if(APP_VERSION == '2.5')
		{
			$this->input = JFactory::getApplication()->input;
		}
		
		$view   = $this->input->get('view', 'dashboard');
		$layout = $this->input->get('layout', 'default');
		$id     = $this->input->getInt('id');
		
		$doc = JFactory::getDocument();
		CJFunctions::add_script_to_document($doc, 'cj.polls.min.js', true, JUri::root(true).'/media/com_communitypolls/js/');
		
		// Check for edit form.
		if ($view == 'poll' && $layout == 'edit' && !$this->checkEditId('com_communitypolls.edit.poll', $id))
		{
			// Somehow the person just went to the form - we don't allow that.
			$this->setError(JText::sprintf('JLIB_APPLICATION_ERROR_UNHELD_ID', $id));
			$this->setMessage($this->getError(), 'error');
			$this->setRedirect(JRoute::_('index.php?option=com_communitypolls&view=polls', false));

			return false;
		}

		parent::display();

		return $this;
	}
}
