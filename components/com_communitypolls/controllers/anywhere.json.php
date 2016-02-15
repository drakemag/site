<?php
/**
 * @package     corejoomla.administrator
 * @subpackage  com_communitypolls
 *
 * @copyright   Copyright (C) 2009 - 2014 corejoomla.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */
defined('_JEXEC') or die();

class CommunityPollsControllerAnywhere extends JControllerAdmin
{
	protected $text_prefix = 'COM_COMMUNITYPOLLS';
	
	public function __construct ($config = array())
	{
		parent::__construct($config);
	}

	public function execute ($task)
	{
		$app = JFactory::getApplication();
		$extension = $app->input->getCmd('extension');
		
		try 
		{
			$model = $this->getModel('Poll');
			$params = JComponentHelper::getParams('com_communitypolls');
			$pk = $app->input->getInt('id', 0);
			$callback = $app->input->getCmd('callback');
			
			$model->setState('load.answers', true);
			$model->setState('load.details', true);
			$model->setState('load.eligibility', true);
			$model->setState('load.suggestions', false);
			$model->setState('params', $params);
			
			$poll = $model->getItem($pk);
			$this->processPoll($poll, $params, true);
			
			echo $callback.'('.(new JResponseJson($poll)).')';
		}
		catch(Exception $e)
		{
			echo new JResponseJson($e);
		}
	}

	public function getModel ($name = 'Anywhere', $prefix = 'CommunityPollsModel', $config = array('ignore_request' => true))
	{
		$model = parent::getModel($name, $prefix, $config);
		return $model;
	}
	
	private function processPoll(&$poll, $config, $anywhere=true)
	{
		$app = JFactory::getApplication();
		$user = JFactory::getUser();
		$params = $app->input->getArray(array('params'=>'array'));
		$params = $params['params'];
		$allowed_chart_types = $config->get('allowed_chart_types', array());
	
		if(!empty($params['chart']) && in_array($params['chart'], $allowed_chart_types))
		{
			$poll->chart = $params['chart'];
		}
		else if(!empty($poll->chart_type) && in_array($poll->chart_type, $allowed_chart_types))
		{
			$poll->chart = $poll->chart_type;
		} 
		else 
		{
			$poll->chart = $poll->chart = $config->get('chart_type');
		}
	
		$width = !empty($params['chartwidth']) ? $params['chartwidth'] : $config->get('chart_width', 650);
		$height = !empty($params['chartheight']) ? $params['chartheight'] : $config->get('pie_chart_height', 350);
		$poll->pallete = !empty($params['pallete']) ? $params['pallete'] : $poll->pallete;
		
		if($poll->publish_results)
		{
			if(in_array($poll->chart, array('pie', 'bpie')))
			{
				$poll->src = ChartsHelper::get_poll_pie_chart($poll, $width, $height);
			} 
			else if( in_array($poll->chart, array('bar')) ) 
			{
				$poll->src = ChartsHelper::get_poll_bar_chart($poll, $width);
			}
		}
	
		if( $anywhere )
		{
			$tpl = !empty($params['template']) ? JFile::makeSafe($params['template']) : 'default';
				
			$poll->template = JFile::read(JPATH_ROOT.'/media/com_communitypolls/anywhere/templates/'.$tpl.'/template.tpl');
			$poll->last_voted = CJFunctions::get_formatted_date($poll->last_voted);
			$poll->random = rand(0, 1000000);
	
			$poll->lbl_vote = JText::_('COM_COMMUNITYPOLLS_VOTES_1');
			$poll->lbl_votes = JText::_('COM_COMMUNITYPOLLS_VOTES');
			$poll->lbl_view_result = JText::_('COM_COMMUNITYPOLLS_VIEW_RESULT');
			$poll->lbl_vote_now = JText::_('COM_COMMUNITYPOLLS_SUBMIT_VOTE');
			$poll->lbl_vote_form = JText::_('COM_COMMUNITYPOLLS_VOTE_FORM');
			$poll->lbl_custom_answer = JText::_('COM_COMMUNITYPOLLS_CUSTOM_ANSWER_TEXT');
			$poll->msg_no_answer = JText::_('COM_COMMUNITYPOLLS_ERROR_NO_SELECTION');
			$poll->msg_poll_closed = JText::_('COM_COMMUNITYPOLLS_MESSAGE_POLL_CLOSED');
			$poll->already_voted = JText::_('COM_COMMUNITYPOLLS_MESSAGE_ALREADY_VOTED');
			$poll->no_result_view_msg = JText::_('COM_COMMUNITYPOLLS_MESSAGE_POLL_RESULTS_HIDDEN');
				
			$poll->colors = ChartsHelper::get_rgb_colors($poll->pallete);
			$poll->description = CJFunctions::process_html($poll->description, ($config->get('default_editor') == 'wysiwygbb'));
		}
		
		// now set cookie if vote restriction method of cookies is enabled.
		$options = JComponentHelper::getParams('com_communitypolls');
		$poll->expire = (int)$options->get('vote_expiration', 525600);
	
		// unset unnecessary data
		unset($poll->email);
		unset($poll->created_by);
		unset($poll->username);
	}
}