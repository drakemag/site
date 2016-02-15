<?php
/**
 * @version		$Id: poll.php 01 2011-01-11 11:37:09Z maverick $
 * @package		CoreJoomla.Polls
 * @subpackage	Components.site
 * @copyright	Copyright (C) 2009 - 2014 corejoomla.com, Inc. All rights reserved.
 * @author		Maverick
 * @link		http://www.corejoomla.com/
 * @license		License GNU General Public License version 2 or later
 */

defined('_JEXEC') or die;
jimport('joomla.application.component.controllerform');

class CommunityPollsControllerPoll extends JControllerForm
{
	protected $view_item = 'form';
	protected $view_list = 'categories';
	protected $urlVar = 'p_id';

	public function add()
	{
		if (!parent::add())
		{
			$this->setRedirect($this->getReturnPage());
		}
	}

	protected function allowAdd($data = array())
	{
		$user       = JFactory::getUser();
		$categoryId = JArrayHelper::getValue($data, 'catid', $this->input->getInt('catid'), 'int');
		$allow      = null;

		if ($categoryId)
		{
			$allow	= $user->authorise('core.create', 'com_communitypolls.category.'.$categoryId);
		}

		if ($allow === null)
		{
			return parent::allowAdd();
		}
		else
		{
			return $allow;
		}
	}

	protected function allowEdit($data = array(), $key = 'id')
	{
		$recordId = (int) isset($data[$key]) ? $data[$key] : 0;
		$user     = JFactory::getUser();
		$userId   = $user->get('id');
		$asset    = 'com_communitypolls.poll.' . $recordId;

		// Check general edit permission first.
		if ($user->authorise('core.edit', $asset))
		{
			return true;
		}

		// Fallback on edit.own.
		// First test if the permission is available.
		if ($user->authorise('core.edit.own', $asset))
		{
			// Now test the owner is the user.
			$ownerId = (int) isset($data['created_by']) ? $data['created_by'] : 0;
			if (empty($ownerId) && $recordId)
			{
				// Need to do a lookup from the model.
				$record = $this->getModel()->getItem($recordId);

				if (empty($record))
				{
					return false;
				}

				$ownerId = $record->created_by;
			}

			// If the owner matches 'me' then do the test.
			if ($ownerId == $userId)
			{
				return true;
			}
		}

		// Since there is no asset tracking, revert to the component permissions.
		return parent::allowEdit($data, $key);
	}

	public function cancel($key = 'p_id')
	{
		parent::cancel($key);
		
		// Redirect to the return page.
		$this->setRedirect($this->getReturnPage());
	}

	public function edit($key = null, $urlVar = 'p_id')
	{
		$result = parent::edit($key, $urlVar);

		return $result;
	}

	public function getModel($name = 'form', $prefix = '', $config = array('ignore_request' => true))
	{
		$model = parent::getModel($name, $prefix, $config);

		return $model;
	}

	protected function getRedirectToItemAppend($recordId = null, $urlVar = null)
	{
		$append = parent::getRedirectToItemAppend($recordId, $urlVar);
		$itemId	= $this->input->getInt('Itemid');
		$catId  = $this->input->getInt('catid');
		$return	= $this->getReturnPage();
		
		if ($itemId)
		{
			$append .= '&Itemid='.$itemId;
		}

		if ($catId)
		{
			$append .= '&catid='.$catId;
		}
		
		if ($return)
		{
			$append .= '&return='.base64_encode($return);
		}
		
		return $append;
	}

	protected function getReturnPage()
	{
		$return = $this->input->get('return', null, 'base64');

		if (empty($return) || !JUri::isInternal(base64_decode($return)))
		{
			return JUri::base();
		}
		else
		{
			return base64_decode($return);
		}
	}

	protected function postSaveHook(JModelLegacy $model, $validData = array())
	{
		return;
	}

	public function save($key = null, $urlVar = 'p_id')
	{
		$result = parent::save($key, $urlVar);

		// If ok, redirect to the return page.
// 		if ($result)
// 		{
// 			$this->setRedirect($this->getReturnPage());
// 		}

		return $result;
	}

	public function suggestions()
	{
		$app = JFactory::getApplication();
		$type = $app->input->getCmd('s', null);
		$created_by = $app->input->getInt('u', 0);
		$query = $app->input->getString('q', 0);
		
		if(!empty($type))
		{
			$model = $this->getModel('poll');
			$listing = $model->getSuggestions($type, $created_by, $query, true);
				
			if(!empty($listing) && !empty($listing['polls']))
			{
				$itemid = CJFunctions::get_active_menu_id();
				$content = '<table class="table table-striped table-hover"><tbody>';
		
				foreach ($listing['polls'] as $i=>$poll)
				{
					$slug = $poll->alias ? ($poll->id.':'.$poll->alias) : $poll->id;
					$catslug = $poll->category_alias ? ($poll->catid.':'.$poll->category_alias) : $poll->catid;

					$content .= '<tr>
									<td>
										<span class="label tooltip-hover" title="'.JText::sprintf('TXT_VOTES', $poll->votes).'" data-placement="right">'.$poll->votes.'</span>&nbsp;
										<a href="'.JRoute::_(CommunityPollsHelperRoute::getPollRoute($slug, $catslug)).'">'.CJFunctions::escape($poll->title).'</a>
									</td>
								</tr>';
				}
		
				$content .= '</tbody></table>';
				echo $content;
			} 
			else 
			{
				echo JText::_('COM_COMMUNITYPOLLS_NO_RESULTS_FOUND');
			}
		}
		
		jexit();
	}
	
	public function ajxvote()
	{
		$this->vote(true);
	}
	
	public function vote($anywhere = false)
	{
		$app = JFactory::getApplication();
		$user = JFactory::getUser();
		$model = $this->getModel('poll');
		
		$id = $app->input->getInt('id', 0);
		$callback = $app->input->getCmd('callback');
		
		if(!$id)
		{
			$this->printMessage(json_encode(array('error'=>JText::_('COM_COMMUNITYPOLLS_ERROR_INVALID_POLL'))), $anywhere, $callback);
			jexit();
		}
		
		if(!$user->authorise('core.vote', 'com_communitypolls.poll.'.$id))
		{
			if($user->guest)
			{
				$this->printMessage(json_encode(array('error'=>JText::_('COM_COMMUNITYPOLLS_ERROR_LOGIN_TO_VOTE'))), $anywhere, $callback);
			} 
			else 
			{
				$this->printMessage(json_encode(array('error'=>JText::_('JERROR_ALERTNOAUTHOR'))), $anywhere, $callback);
			}
			
			jexit();
		}
		
		$params = JComponentHelper::getParams('com_communitypolls');
		$model->setState('load.answers', true);
		$model->setState('load.details', true);
		$model->setState('load.eligibility', false);
		$model->setState('load.suggestions', false);
		$model->setState('params', $params);
		
		$poll = $model->getItem($id);
		
		if(empty($poll))
		{
			$this->printMessage(json_encode(array('error'=>JText::_('JERROR_ALERTNOAUTHOR'))), $anywhere, $callback);
			jexit();
		}
		
		if(!$model->registerVote($poll))
		{
			$this->printMessage(json_encode(array('error'=>$model->getError())), $anywhere, $callback);
			jexit();
		}
		
		CJFunctions::trigger_event('corejoomla', 'onAfterVote', $poll);
		CommunityPollsHelper::do3rdPartyTask('VOTE', $poll);

		if($poll->created_by > 0 && $params->get('notif_new_vote') == '1')
		{
			$slug = $poll->alias ? ($poll->id.':'.$poll->alias) : $poll->id;
			$catslug = $poll->category_alias ? ($poll->catid.':'.$poll->category_alias) : $poll->catid;
			$link = JRoute::_(CommunityPollsHelperRoute::getPollRoute($poll->id, $poll->catid, $poll->language), true, -1);
			
			$body = JText::sprintf('COM_COMMUNITYPOLLS_EMAIL_NEW_VOTE_BODY', $poll->author, $poll->title, $link, $app->getCfg('sitename'));
			$from = $app->getCfg('mailfrom' );
			$fromname = $app->getCfg('fromname' );
			
			CJFunctions::send_email($from, $fromname, $poll->email, JText::_('COM_COMMUNITYPOLLS_EMAIL_NEW_VOTE_TITLE'), $body, 1);
		}
			
		$this->set_poll_parameters($poll, $params, $anywhere);
		
		if($poll->publish_results)
		{
			$poll->results = true;
		}
		else
		{
			$poll->answers = array();
			$poll->columns = array();
			$poll->gridvotes = array();
			
			// unset unnecessary data
			unset($poll->email);
			unset($poll->created_by);
			unset($poll->username);
			
			$poll->results = false;
		}
		
		$end_message = htmlspecialchars($poll->end_message);
		if(empty($end_message))
		{
			$poll->end_message = JText::_('COM_COMMUNITYPOLLS_MESSAGE_VOTE_REGISTERED');
		}
		else 
		{
			$bbcode	 = ($params->get('default_editor', 'none') == 'wysiwygbb') ? true : false;
			$poll->end_message = CJFunctions::parse_html($poll->end_message, false, $bbcode, false);
		}
			
		$this->printMessage(json_encode(array('poll'=>$poll)), $anywhere, $callback);
		jexit();
	}
	
	private function printMessage($message, $anywhere, $callback)
	{
		echo $anywhere ? $callback.'('.$message.')' : $message;
	}
	
	private function set_poll_parameters($poll, $config, $anywhere = true){
		
		$app = JFactory::getApplication();
		$user = JFactory::getUser();
		$params = $app->input->getArray(array('params'=>'array'));
		$params = $params['params'];
		$allowed_chart_types = $config->get('allowed_chart_types', array('sbar', 'gpie'));

		if(!empty($params['chart']) && in_array($params['chart'], $allowed_chart_types)){
			
			$poll->chart = $params['chart'];
		}else if(!empty($poll->chart_type) && in_array($poll->chart_type, $allowed_chart_types)){
			
			$poll->chart = $poll->chart_type;
		} else {
			
			$poll->chart_type = $poll->chart = $config->get('chart_type');
		}
		
		$width = !empty($params['chartwidth']) ? $params['chartwidth'] : $config->get('chart_width', 650);
		$height = !empty($params['chartheight']) ? $params['chartheight'] : $config->get('pie_chart_height', 350);
		$poll->pallete = !empty($params['pallete']) ? $params['pallete'] : $poll->pallete;
		
		if($poll->publish_results){
			
			if(in_array($poll->chart, array('pie', 'bpie'))){
				
				$poll->src = ChartsHelper::get_poll_pie_chart($poll, $width, $height);
			} else if( in_array($poll->chart, array('bar')) ) {
				
				$poll->src = ChartsHelper::get_poll_bar_chart($poll, $width);
			}
		}
				
		if( $anywhere ){
			
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
			$poll->description = CJFunctions::process_html($poll->description, ($config->get('default_editor') == 'bbcode'));
		}
		
		// unset unnecessary data
		unset($poll->email);
		unset($poll->created_by);
		unset($poll->username);
	}
	
	public function upload()
	{
		$user = JFactory::getUser();
		$xhr = $_SERVER['HTTP_X_REQUESTED_WITH'] == 'XMLHttpRequest';
		if(!$xhr) echo '<textarea>';
		
		if($user->authorise('core.attachments', 'com_communitypolls'))
		{
			$params = JComponentHelper::getParams('com_communitypolls');
			$allowed_extensions = $params->get('allowed_image_types', 'jpg,gif,png,jpeg');
			$allowed_size = ((int)$params->get('max_attachment_size', 256))*1024;
			$input = JFactory::getApplication()->input;
		
			if(!empty($allowed_extensions))
			{
				$tmp_file = $input->files->get('input-attachment');
		
				if($tmp_file['error'] > 0)
				{
					echo json_encode(array('error'=>JText::_('MSG_ERROR_PROCESSING')));
				} 
				else 
				{
					$temp_file_path = $tmp_file['tmp_name'];
					$temp_file_name = $tmp_file['name'];
					$temp_file_ext = JFile::getExt($temp_file_name);
		
					if (!in_array(strtolower($temp_file_ext), explode(',', strtolower($allowed_extensions))))
					{
						echo json_encode(array('error'=>JText::_('MSG_INVALID_FILETYPE')));
					} 
					else if ($tmp_file['size'] > $allowed_size)
					{
						echo json_encode(array('error'=>JText::_('MSG_MAX_SIZE_FAILURE')));
					} 
					else 
					{
						$file_name = CJFunctions::generate_random_key(25, 'abcdefghijklmnopqrstuvwxyz1234567890').'.'.$temp_file_ext;
							
						if(JFile::upload($temp_file_path, P_TEMP_STORE.'/'.$file_name))
						{
							echo json_encode(array('file_name'=>$file_name));
						} 
						else 
						{
							echo json_encode(array('error'=>JText::_('MSG_ERROR_PROCESSING')));
						}
					}
				}
		
			} 
			else 
			{
				echo '{"file_name": null}';
			}
		
		} 
		else 
		{
			echo json_encode(array('error'=>JText::_('JERROR_ALERTNOAUTHOR')));
		}
		
		if(!$xhr) echo '</textarea>';
		
		jexit();
	}
}
