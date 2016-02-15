<?php
/**
 * @version		$Id: form.php 01 2011-01-11 11:37:09Z maverick $
 * @package		CoreJoomla.Polls
 * @subpackage	Components.site
 * @copyright	Copyright (C) 2009 - 2014 corejoomla.com, Inc. All rights reserved.
 * @author		Maverick
 * @link		http://www.corejoomla.com/
 * @license		License GNU General Public License version 2 or later
 */

defined('_JEXEC') or die;

// Base this model on the backend version.
require_once JPATH_COMPONENT_ADMINISTRATOR.'/models/poll.php';

class CommunityPollsModelForm extends CommunityPollsModelPoll
{
	public $typeAlias = 'com_communitypolls.poll';

	protected function populateState()
	{
		$app = JFactory::getApplication();

		// Load state from the request.
		$pk = $app->input->getInt('p_id');
		$this->setState('poll.id', $pk);

		$this->setState('poll.catid', $app->input->getInt('catid'));

		$return = $app->input->get('return', null, 'base64');
		$this->setState('return_page', base64_decode($return));

		// Load the parameters.
		$params	= $app->getParams();
		$this->setState('params', $params);

		$this->setState('layout', $app->input->getString('layout'));
	}

	public function getItem($itemId = null)
	{
		$itemId = (int) (!empty($itemId)) ? $itemId : $this->getState('poll.id');

		// Get a row instance.
		$table = $this->getTable();

		// Attempt to load the row.
		$return = $table->load($itemId);

		// Check for a table object error.
		if ($return === false && $table->getError())
		{
			$this->setError($table->getError());

			return false;
		}

		$properties = $table->getProperties(1);
		$value = JArrayHelper::toObject($properties, 'JObject');

		// Convert attrib field to Registry.
		$value->params = new JRegistry;
		$value->params->loadString($value->attribs);

		// Compute selected asset permissions.
		$user	= JFactory::getUser();
		$userId	= $user->get('id');
		$asset	= 'com_communitypolls.poll.' . $value->id;

		// Check general edit permission first.
		if ($user->authorise('core.edit', $asset))
		{
			$value->params->set('access-edit', true);
		}

		// Now check if edit.own is available.
		elseif (!empty($userId) && $user->authorise('core.edit.own', $asset))
		{
			// Check for a valid user and that they are the owner.
			if ($userId == $value->created_by)
			{
				$value->params->set('access-edit', true);
			}
		}

		// Check edit state permission.
		if ($itemId)
		{
			// Existing item
			$value->params->set('access-change', $user->authorise('core.edit.state', $asset));
		}
		else
		{
			// New item.
			$catId = (int) $this->getState('poll.catid');

			if ($catId)
			{
				$value->params->set('access-change', $user->authorise('core.edit.state', 'com_communitypolls.category.' . $catId));
				$value->catid = $catId;
			}
			else
			{
				$value->params->set('access-change', $user->authorise('core.edit.state', 'com_communitypolls'));
			}
		}

		// Convert the metadata field to an array.
		$registry = new JRegistry;
		$registry->loadString($value->metadata);
		$value->metadata = $registry->toArray();

		if ($itemId)
		{
			$value->tags = new JHelperTags;
			$value->tags->getTagIds($value->id, 'com_communitypolls.poll');
			$value->metadata['tags'] = $value->tags;
		}

		if($itemId && $this->getState('load.answers'))
		{
			$form_data = JFactory::getApplication()->getUserState('com_communitypolls.edit.poll.data');
			
			if(!empty($form_data['poll-final-answers']))
			{
				$value->answers = json_decode($form_data['poll-final-answers']);
				
				if(!empty($form_data['poll-final-columns']))
				{
					$value->columns = json_decode($form_data['poll-final-columns']);
				}
			}
			else 
			{
				$db = JFactory::getDbo();
				
					// Get the options
				$query = $db->getQuery ( true )
					->select ( 'a.id, a.title, a.votes, a.type, a.order' )
					->from ( '#__jcp_options AS a' )
					->where ( 'a.poll_id = ' . $value->id )
					->order ( 'a.order asc' );
				
				$db->setQuery ( $query );
				$answers = $db->loadObjectList ();
				
				if (empty ( $answers )) 
				{
					$answers = array();
					//return JError::raiseError ( 404, JText::_ ( 'COM_COMMUNITYPOLLS_ERROR_POLL_NOT_FOUND' ) );
				}
				
				// Get custom answers if any
				if ($value->custom_answer == '1' && ((! $user->guest && $value->created_by == $user->id) || $user->authorise ( 'core.manage', 'com_communitypolls.category.' . $value->catid ))) 
				{
					$query = $db->getQuery ( true )
						->select ( 'v.voter_id, v.voted_on, v.ip_address, v.custom_answer' )
						->from ( '#__jcp_votes AS v' );
					
					$query
						->select ( 'u.name, u.username' )
						->join ( 'left', '#__users AS u on u.id = v.voter_id' );
					
					$query
						->where ( 'v.poll_id=' . $value->id . ' and v.custom_answer is not null' );
					
					$db->setQuery ( $query );
					$value->custom_answers = $db->loadObjectList ();
				}
				
				// Get the resources
				$query = $db->getQuery ( true )
					->select ( 'r.type, r.option_id, r.value' )
					->from ( '#__jcp_resources AS r' )
					->where ( 'poll_id = ' . $value->id . ' and r.value is not null and r.value <> \'\'' )
					->order ( 'option_id asc' );
				
				$db->setQuery ( $query );
				$resources = $db->loadObjectList ();
				
				for($i = 0; $i < count ( $answers ); $i ++) 
				{
					$answers [$i]->resources = array ();
					
					if (! empty ( $resources )) 
					{
						foreach ( $resources as $resource ) 
						{
							if ($resource->option_id == $answers [$i]->id) 
							{
								if ($resource->type == 'image') 
								{
									$resource->src = P_IMAGES_URI . $resource->value;
								}
								
								$answers [$i]->resources [] = $resource;
							}
						}
					}
				}
				
				$value->answers = array ();
				$value->columns = array ();
				
				foreach ( $answers as $answer ) 
				{
					if (strcmp ( $answer->type, 'x' ) == 0) 
					{
						$value->answers [] = $answer;
					} 
					else 
					{
						$value->columns [] = $answer;
					}
				}
			}
		}
		
		if($itemId && $this->getState('load.gridvotes') && strcmp ( $value->type, 'grid' ) == 0)
		{
			$query = $db->getQuery ( true )
				->select ( 'option_id, column_id, count(*) as votes' )
				->from ( '#__jcp_votes' )
				->where ( 'poll_id=' . $poll->id . ' and option_id > 0 and column_id > 0' )
				->group ( 'option_id, column_id' );
			
			$db->setQuery ( $query );
			$value->gridvotes = $db->loadObjectList ();
		}
		
		return $value;
	}

	public function getReturnPage()
	{
		return base64_encode($this->getState('return_page'));
	}

	public function save($data)
	{
		// Associations are not edited in frontend ATM so we have to inherit them
		if (JLanguageAssociations::isEnabled() && !empty($data['id']))
		{
			if ($associations = JLanguageAssociations::getAssociations('com_communitypolls', '#__jcp_polls', 'com_communitypolls.item', $data['id']))
			{
				foreach ($associations as $tag => $associated)
				{
					$associations[$tag] = (int) $associated->id;
				}

				$data['associations'] = $associations;
			}
		}
		
		return parent::save($data);
	}
}
