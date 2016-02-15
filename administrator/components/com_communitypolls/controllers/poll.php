<?php
/**
 * @version		$Id: poll.php 01 2014-01-26 11:37:09Z maverick $
 * @package		CoreJoomla.Polls
 * @subpackage	Components
 * @copyright	Copyright (C) 2009 - 2014 corejoomla.com. All rights reserved.
 * @author		Maverick
 * @link		http://www.corejoomla.com/
 * @license		License GNU General Public License version 2 or later
 */
defined('_JEXEC') or die;
jimport('joomla.application.component.controllerform');

class CommunityPollsControllerPoll extends JControllerForm
{
	public function __construct($config = array())
	{
		parent::__construct($config);

		if(APP_VERSION < 3)
		{
			$this->input = JFactory::getApplication()->input;
		}
		
		if ($this->input->get('return') == 'featured')
		{
			$this->view_list = 'featured';
			$this->view_item = 'poll&return=featured';
		}
	}

	protected function allowAdd($data = array())
	{
		$user = JFactory::getUser();
		$categoryId = JArrayHelper::getValue($data, 'catid', $this->input->getInt('filter_category_id'), 'int');
		$allow = null;

		if ($categoryId)
		{
			// If the category has been passed in the data or URL check it.
			$allow = $user->authorise('core.create', 'com_communitypolls.category.' . $categoryId);
		}

		if ($allow === null)
		{
			// In the absense of better information, revert to the component permissions.
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
		$user = JFactory::getUser();
		$userId = $user->get('id');

		// Check general edit permission first.
		if ($user->authorise('core.edit', 'com_communitypolls.poll.' . $recordId))
		{
			return true;
		}

		// Fallback on edit.own.
		// First test if the permission is available.
		if ($user->authorise('core.edit.own', 'com_communitypolls.poll.' . $recordId))
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

	public function batch($model = null)
	{
		JSession::checkToken() or jexit(JText::_('JINVALID_TOKEN'));

		// Set the model
		$model = $this->getModel('Poll', '', array());

		// Preset the redirect
		$this->setRedirect(JRoute::_('index.php?option=com_communitypolls&view=polls' . $this->getRedirectToListAppend(), false));

		return parent::batch($model);
	}
	
	protected function postSaveHook(JModelLegacy $model, $validData = array())
	{
		$task = $this->getTask();
		
		if ($task == 'save')
		{
			$this->setRedirect(JRoute::_('index.php?option=com_communitypolls&view=polls', false));
		}
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
