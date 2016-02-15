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
jimport('joomla.application.component.modeladmin');

class CommunityPollsModelPoll extends JModelAdmin
{
	protected $text_prefix = 'COM_COMMUNITYPOLLS';
	public $typeAlias = 'com_communitypolls.poll';
	protected $_item = null;

	public function __construct($config)
	{
		$config['event_after_delete'] = 'onPollAfterDelete';
		$config['event_after_save'] = 'onPollAfterSave';
		$config['event_before_delete'] = 'onPollBeforeDelete';
		$config['event_before_save'] = 'onPollBeforeSave';
		$config['event_change_state'] = 'onPollChangeState';
	
		parent::__construct($config);
	}
	
	protected function batchCopy($value, $pks, $contexts)
	{
		$categoryId = (int) $value;

		$i = 0;

		if (!parent::checkCategoryId($categoryId))
		{
			return false;
		}

		// Parent exists so we let's proceed
		while (!empty($pks))
		{
			// Pop the first ID off the stack
			$pk = array_shift($pks);

			$this->table->reset();

			// Check that the row actually exists
			if (!$this->table->load($pk))
			{
				if ($error = $this->table->getError())
				{
					// Fatal error
					$this->setError($error);

					return false;
				}
				else
				{
					// Not fatal error
					$this->setError(JText::sprintf('JLIB_APPLICATION_ERROR_BATCH_MOVE_ROW_NOT_FOUND', $pk));
					continue;
				}
			}

			// Alter the title & alias
			$data = $this->generateNewTitle($categoryId, $this->table->alias, $this->table->title);
			$this->table->title = $data['0'];
			$this->table->alias = $data['1'];

			// Reset the ID because we are making a copy
			$this->table->id = 0;

			// New category ID
			$this->table->catid = $categoryId;

			// TODO: Deal with ordering?
			//$table->ordering	= 1;

			// Get the featured state
			$featured = $this->table->featured;

			// Check the row.
			if (!$this->table->check())
			{
				$this->setError($table->getError());
				return false;
			}

			parent::createTagsHelper($this->tagsObserver, $this->type, $pk, $this->typeAlias, $this->table);

			// Store the row.
			if (!$this->table->store())
			{
				$this->setError($table->getError());
				return false;
			}

			// Get the new item ID
			$newId = $this->table->get('id');

			// Add the new ID to the array
			$newIds[$i] = $newId;
			$i++;
		}

		// Clean the cache
		$this->cleanCache();

		return $newIds;
	}

	protected function canDelete($record)
	{
		if (!empty($record->id) && $record->published == -2)
		{
			return JFactory::getUser()
				->authorise('core.delete', 'com_communitypolls.poll.' . (int) $record->id);
		}
		
		return false;
	}

	protected function canEditState($record)
	{
		$user = JFactory::getUser();

		// Check for existing poll.
		if (!empty($record->id))
		{
			return $user->authorise('core.edit.state', 'com_communitypolls.poll.' . (int) $record->id);
		}
		// New poll, so check against the category.
		elseif (!empty($record->catid))
		{
			return $user->authorise('core.edit.state', 'com_communitypolls.category.' . (int) $record->catid);
		}
		// Default to component settings if neither poll nor category known.
		else
		{
			return parent::canEditState('com_communitypolls');
		}
	}

	protected function prepareTable($table)
	{
		// Set the publish date to now
		$db = $this->getDbo();
		if ($table->published == 1 && (int) $table->publish_up == 0)
		{
			$table->publish_up = JFactory::getDate()->toSql();
		}

		if ($table->published == 1 && intval($table->publish_down) == 0)
		{
			$table->publish_down = $db->getNullDate();
		}

		// Increment the content version number.
		$table->version++;

		// Reorder the polls within the category so the new poll is first
		if (empty($table->id))
		{
			$table->reorder('catid = ' . (int) $table->catid . ' AND published >= 0');
		}
	}

	public function getTable($type = 'Poll', $prefix = 'CommunityPollsTable', $config = array())
	{
		return JTable::getInstance($type, $prefix, $config);
	}

	public function getItem($pk = null)
	{
		$pk = (!empty($pk)) ? $pk : (int) $this->getState($this->getName() . '.id');
		
		if ($this->_item === null)
		{
			$this->_item = array();
		}
		
		if(!$pk)
		{
			// new item
			if ($item = parent::getItem($pk))
			{
				// Convert the params field to an array.
				$registry = new JRegistry;
				$registry->loadString($item->attribs);
				$item->attribs = $registry->toArray();
				
				// Convert the metadata field to an array.
				$registry = new JRegistry;
				$registry->loadString($item->metadata);
				$item->metadata = $registry->toArray();
				
				if (!empty($item->id))
				{
					$item->tags = new JHelperTags;
					$item->tags->getTagIds($item->id, 'com_communitypolls.poll');
				}
				
				$item->answers = $item->columns = array();
				$form_data = JFactory::getApplication()->getUserState('com_communitypolls.edit.poll.data');
				
				if(!empty($form_data['poll-final-answers']))
				{
					$item->answers = json_decode($form_data['poll-final-answers']);
				
					if(!empty($form_data['poll-final-columns']))
					{
						$item->columns = json_decode($form_data['poll-final-columns']);
					}
				}
			}
			
			return $item;
		}
		else if (!isset($this->_item[$pk]))
		{
			try
			{
				$user = JFactory::getUser();
				$db = $this->getDbo();
				
				$query = $db->getQuery(true)
					->select(
						$this->getState(
							'item.select', 'a.id, a.asset_id, a.title, a.alias, a.description, a.type, a.answers_order, a.custom_answer,'.
							'a.pallete, anonymous, a.modify_answers, a.results_up, a.close_date, a.private, a.min_answers, a.max_answers,' .
							// If badcats is not null, this means that the poll is inside an unpublished category
							// In this case, the state is set to 0 to indicate Unpublished (even if the poll state is Published)
							'CASE WHEN badcats.id is null THEN a.published ELSE 0 END AS published, ' .
							'a.catid, a.created, a.created_by, a.created_by_alias, a.voters, a.last_voted, a.chart_type, a.anywhere, a.answers_order, a.end_message,' .
							// Use created if modified is 0
							'CASE WHEN a.modified = ' . $db->q($db->getNullDate()) . ' THEN a.created ELSE a.modified END as modified, ' .
							'a.modified_by, a.checked_out, a.checked_out_time, a.publish_up, a.publish_down, ' .
							'a.attribs, a.version, a.ordering, ' .
							'a.metakey, a.metadesc, a.access, a.votes, a.metadata, a.featured, a.language'
						)
				);
				
				$query->from('#__jcp_polls AS a');
			
				// Join on category table.
				$query->select('c.title AS category_title, c.alias AS category_alias, c.access AS category_access')
					->join('LEFT', '#__categories AS c on c.id = a.catid');
			
				// Join on user table.
				$query->select('u.name AS author, u.email')
					->join('LEFT', '#__users AS u on u.id = a.created_by');
			
				// Filter by language
				if ($this->getState('filter.language'))
				{
					$query->where('a.language in (' . $db->q(JFactory::getLanguage()->getTag()) . ',' . $db->q('*') . ')');
				}
			
				// Join over the categories to get parent category titles
				$query->select('parent.title as parent_title, parent.id as parent_id, parent.path as parent_route, parent.alias as parent_alias')
					->join('LEFT', '#__categories as parent ON parent.id = c.parent_id')
					
					->where('a.id = ' . (int) $pk);
			
				if ((!$user->authorise('core.edit.state', 'com_communitypolls')) && (!$user->authorise('core.edit', 'com_communitypolls'))) 
				{
					// Filter by start and end dates.
					$nullDate = $db->q($db->getNullDate());
					$date = JFactory::getDate();
			
					$nowDate = $db->q($date->toSql());
			
					$query->where('(a.publish_up = ' . $nullDate . ' OR a.publish_up <= ' . $nowDate . ')')
						->where('(a.publish_down = ' . $nullDate . ' OR a.publish_down >= ' . $nowDate . ')');
				}
			
				// Join to check for category published state in parent categories up the tree
				// If all categories are published, badcats.id will be null, and we just use the poll state
				$subquery = ' (SELECT cat.id as id FROM #__categories AS cat JOIN #__categories AS parent ';
				$subquery .= 'ON cat.lft BETWEEN parent.lft AND parent.rgt ';
				$subquery .= 'WHERE parent.extension = ' . $db->q('com_communitypolls');
				$subquery .= ' AND parent.published <= 0 GROUP BY cat.id)';
				$query->join('LEFT OUTER', $subquery . ' AS badcats ON badcats.id = c.id');
			
				// Filter by published state.
				$published = $this->getState('filter.published');
				$archived = $this->getState('filter.archived');
			
				if (is_numeric($published))
				{
					$query->where('(a.published = ' . (int) $published . ' OR a.published =' . (int) $archived . ')');
				}
			
				$db->setQuery($query);
			
				$item = $db->loadObject();

				if (empty($item))
				{
					return JError::raiseError(404, JText::_('COM_COMMUNITYPOLLS_ERROR_POLL_NOT_FOUND'));
				}
				
				if ($item)
				{
					// Convert the params field to an array.
					$registry = new JRegistry;
					$registry->loadString($item->attribs);
					$item->attribs = $registry->toArray();
				
					// Convert the metadata field to an array.
					$registry = new JRegistry;
					$registry->loadString($item->metadata);
					$item->metadata = $registry->toArray();
				}
				
				if($item->id && $this->getState('load.answers'))
				{
					$form_data = JFactory::getApplication()->getUserState('com_communitypolls.edit.poll.data');
					if(!empty($form_data['poll-final-answers']))
					{
						$item->answers = json_decode($form_data['poll-final-answers']);
				
						if(!empty($form_data['poll-final-columns']))
						{
							$item->columns = json_decode($form_data['poll-final-columns']);
						}
					}
					else
					{
						$db = JFactory::getDbo();
						$user = JFactory::getUser();
				
						// Get the options
						$query = $db->getQuery ( true )
							->select ( 'a.id, a.title, a.votes, a.type, a.order' )
							->from ( '#__jcp_options AS a' )
							->where ( 'a.poll_id = ' . $item->id )
							->order ( 'a.order asc' );
				
						$db->setQuery ( $query );
						$answers = $db->loadObjectList ();
				
						if (empty ( $answers ))
						{
							$answers = array();
						}
				
						// Get the percentages for each answer
						$total_votes = 0;
						foreach($answers as $answer)
						{
							$total_votes = $total_votes + $answer->votes;
						}
							
						foreach($answers as $answer)
						{
							$answer->pct = $total_votes > 0 ? round( ($answer->votes*100) / $total_votes, 2) : 0;
						}
				
						// Get custom answers if any
						if ($item->custom_answer == '1' && ((! $user->guest && $item->created_by == $user->id) || $user->authorise ( 'core.manage', 'com_communitypolls.category.' . $item->catid )))
						{
							$query = $db->getQuery ( true )
								->select ( 'v.voter_id, v.voted_on, v.ip_address, v.custom_answer' )
								->from ( '#__jcp_votes AS v' );
								
							$query
								->select ( 'u.name, u.username' )
								->join ( 'left', '#__users AS u on u.id = v.voter_id' );
								
							$query
								->where ( 'v.poll_id=' . $item->id . ' and v.custom_answer is not null' );
								
							$db->setQuery ( $query );
							$item->custom_answers = $db->loadObjectList ();
						}
				
						// Get the resources
						$query = $db->getQuery ( true )
							->select ( 'r.type, r.option_id, r.value' )
							->from ( '#__jcp_resources AS r' )
							->where ( 'poll_id = ' . $item->id . ' and r.value is not null and r.value <> \'\'' )
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
				
						$item->answers = array ();
						$item->columns = array ();
				
						foreach ( $answers as $answer )
						{
							if (strcmp ( $answer->type, 'x' ) == 0)
							{
								$item->answers [] = $answer;
							}
							else
							{
								$item->columns [] = $answer;
							}
						}
					}
				}
				else
				{
					$item->answers = array();
					$item->columns = array();
				}
				
				// Load associated content items
				$assoc = JLanguageAssociations::isEnabled();
			
				if ($assoc)
				{
					$item->associations = array();
					
					if ($item->id != null)
					{
						$associations = JLanguageAssociations::getAssociations('com_communitypolls', '#__jcp_polls', 'com_communitypolls.item', $item->id);
						
						foreach ($associations as $tag => $association)
						{
							$item->associations[$tag] = $association->id;
						}
					}
				}
				
				if (!empty($item->id) && APP_VERSION >= 3)
				{
					$item->tags = new JHelperTags;
					$item->tags->getTagIds($item->id, 'com_communitypolls.poll');
				}
				
				$this->_item[$pk] = $item;
			}
			catch (Exception $e)
			{
				if ($e->getCode() == 404)
				{
					// Need to go thru the error handler to allow Redirect to work.
					JError::raiseError(404, $e->getMessage());
				}
				else
				{
					$this->setError($e);
					$this->_item[$pk] = false;
				}
			}
		}
		
		return $this->_item[$pk];
	}

	public function getForm($data = array(), $loadData = true)
	{
		// Get the form.
		$form = $this->loadForm('com_communitypolls.poll', 'poll', array('control' => 'jform', 'load_data' => $loadData));
		if (empty($form))
		{
			return false;
		}
		$jinput = JFactory::getApplication()->input;

		// The front end calls this model and uses p_id to avoid id clashes so we need to check for that first.
		if ($jinput->get('p_id'))
		{
			$id = $jinput->get('p_id', 0);
		}
		// The back end uses id so we use that the rest of the time and set it to 0 by default.
		else
		{
			$id = $jinput->get('id', 0);
		}
		// Determine correct permissions to check.
		if ($this->getState('poll.id'))
		{
			$id = $this->getState('poll.id');
			// Existing record. Can only edit in selected categories.
			$form->setFieldAttribute('catid', 'action', 'core.edit');
			// Existing record. Can only edit own polls in selected categories.
			$form->setFieldAttribute('catid', 'action', 'core.edit.own');
		}
		else
		{
			// New record. Can only create in selected categories.
			$form->setFieldAttribute('catid', 'action', 'core.create');
		}

		$user = JFactory::getUser();

		// Check for existing poll.
		// Modify the form based on Edit State access controls.
		if ($id != 0 && (!$user->authorise('core.edit.state', 'com_communitypolls.poll.' . (int) $id))
			|| ($id == 0 && !$user->authorise('core.edit.state', 'com_communitypolls'))
		)
		{
			// Disable fields for display.
			$form->setFieldAttribute('featured', 'disabled', 'true');
			$form->setFieldAttribute('ordering', 'disabled', 'true');
			$form->setFieldAttribute('publish_up', 'disabled', 'true');
			$form->setFieldAttribute('publish_down', 'disabled', 'true');
			$form->setFieldAttribute('published', 'disabled', 'true');
			$form->setFieldAttribute('access', 'disabled', 'true');

			// Disable fields while saving.
			// The controller has already verified this is an poll you can edit.
			$form->setFieldAttribute('featured', 'filter', 'unset');
			$form->setFieldAttribute('ordering', 'filter', 'unset');
			$form->setFieldAttribute('publish_up', 'filter', 'unset');
			$form->setFieldAttribute('publish_down', 'filter', 'unset');
			$form->setFieldAttribute('published', 'filter', 'unset');
			$form->setFieldAttribute('access', 'disabled', 'true');
		}

		// Prevent messing with poll language and category when editing existing poll with associations
		$app = JFactory::getApplication();
		$assoc = JLanguageAssociations::isEnabled();

		if ($app->isSite() && $assoc && $this->getState('poll.id'))
		{
			$form->setFieldAttribute('language', 'readonly', 'true');
			$form->setFieldAttribute('catid', 'readonly', 'true');
			$form->setFieldAttribute('language', 'filter', 'unset');
			$form->setFieldAttribute('catid', 'filter', 'unset');
		}
		
		return $form;
	}

	protected function loadFormData()
	{
		// Check the session for previously entered form data.
		$app = JFactory::getApplication();
		$data = $app->getUserState('com_communitypolls.edit.poll.data', array());

		if (empty($data))
		{
			$data = $this->getItem();

			// Prime some default values.
			if ($this->getState('poll.id') == 0)
			{
				$filters = (array) $app->getUserState('com_communitypolls.polls.filter');
				$filterCatId = isset($filters['category_id']) ? $filters['category_id'] : null;
				
				$data->set('catid', $app->input->getInt('catid', $filterCatId));
			}
		}

		if(APP_VERSION >= 3)
		{
			$this->preprocessData('com_communitypolls.poll', $data);
		}

		return $data;
	}

	public function save($data)
	{
		$app = JFactory::getApplication();
		$user = JFactory::getUser();

		// Alter the title for save as copy
		if ($app->input->get('task') == 'save2copy')
		{
			list($title, $alias) = $this->generateNewTitle($data['catid'], $data['alias'], $data['title']);
			$data['title'] = $title;
			$data['alias'] = $alias;
			$data['state'] = 0;
		}

		if($user->authorise('core.polls.approve', 'com_communitypolls.category.'.$data['catid']))
		{
			$data['published'] = 1;
		}
		else
		{
			$data['published'] = 0;
		}
		
		if(!$data['id'])
		{
			$data['ip_address'] = CJFunctions::get_user_ip_address();
		}
		
		JPluginHelper::importPlugin('communitypolls');
		if (parent::save($data))
		{
			$db = JFactory::getDbo();
			$id = (int) $this->getState($this->getName() . '.id');
			$isnew = $this->getState($this->getName() . '.new');
			$item = $this->getItem($id);
			
			try
			{
				// Fetch the answers from the json object
				$answers = json_decode($data['poll-final-answers']);
				
				// if poll type is grid, just push all columns into answers and insert them at a go.
				if( strcmp($data['type'], 'grid') == 0 )
				{
					$columns = json_decode($data['poll-final-columns']);
					foreach ($columns as $column)
					{
						array_push($answers, $column);
					}
				}
								
				if($isnew)
				{
					foreach ($answers as $answer)
					{
						$query = $db->getQuery(true)
							->insert('#__jcp_options')
							->columns('poll_id, title, '.$db->qn('order').','.$db->qn('type'))
							->values($item->id.','.$db->q($answer->title).','.$answer->order.','.($answer->type == 'y' ? $db->q('y') : $db->q('x')));
						
						$db->setQuery($query);
						 
						if($db->query())
						{
							$answer->id = $db->insertid();
							
							if(!empty($answer->resources))
							{
								$query = $db->getQuery(true)->insert('#__jcp_resources')->columns($db->qn('type').','.$db->qn('value').', poll_id, option_id');
									
								foreach ($answer->resources as $resource)
								{
									if($resource->type == 'image' && JFile::exists(P_TEMP_STORE.'/'.$resource->value))
									{
										JFile::move(P_TEMP_STORE.'/'.$resource->value, P_IMAGES_UPLOAD_DIR.'/'.$resource->value);
										$query->values($db->q('image').','.$db->q($resource->value).','.$item->id.','.$answer->id);
									}
									else if($resource->type == 'url')
									{
										$query->values($db->q('url').','.$db->q($resource->value).','.$item->id.','.$answer->id);
									}
								}
									
								$db->setQuery($query);
									
								if(!$db->query())
								{
									$this->setError(JText::_('MSG_ERROR_SAVE_POLL').' Error Code: 105104');
									return false;
								}
							}
						} 
						else 
						{
							$this->setError(JText::_('MSG_ERROR_SAVE_POLL').' Error Code: 105108');
							return false;
						}
					}
					
					if($isnew)
					{
						// update tracking information
						$tracking = new stdClass();
						$location = CJFunctions::get_user_location($data['ip_address']);
						$browser = CJFunctions::get_browser();
						
						$tracking->post_id = $id;
						$tracking->post_type = 1;
						$tracking->ip_address = $data['ip_address'];
						$tracking->country = $location['country_code'];
						$tracking->city = $location['city'];
						$tracking->os = $browser['platform'];
						$tracking->browser_name = $browser['name'];
						$tracking->browser_version = $browser['version'];
						$tracking->browser_info = $browser['userAgent'];
						
						try
						{
							$db->insertObject('#__jcp_tracking', $tracking);
						}
						catch (Exception $e){}
					}
				}
				else
				{ 
					// private polls support for existing polls
					if($item->private == 1)
					{
						$query = $db->getQuery(true)->select('secret')->from('#__jcp_polls')->where('id = '.$item->id);
						$db->setQuery($query);
						$secret = $db->loadResult();
						 
						if(empty($secret))
						{
							$item->secret = CJFunctions::generate_random_key(16);
							$query = $db->getQuery(true)->update('#__jcp_polls')->set('secret = '.$db->quote($item->secret))->where('id = '.$item->id);
							$db->setQuery($query);
							$db->query();
						}
					}
					
					// update answers with existing ones or delete removed answers.
					$query = $db->getQuery(true)->select('id')->from('#__jcp_options')->where('poll_id='.$item->id);
					$db->setQuery($query);
					$existing_answers = $db->loadColumn();
					
					$deleted_options = array();
					 
					foreach ($existing_answers as $answer_id)
					{
						$flag = false;
						foreach ($answers as $answer)
						{
							if($answer->id > 0 && $answer->id == $answer_id)
							{
								$flag = true;
								break;
							}
						}
						
						if(!$flag)
						{
							$deleted_options[] = $answer_id;
						}
					}
					
					if(!empty($deleted_options))
					{
						$query = $db->getQuery(true)->delete('#__jcp_options')->where('id in ('.implode(',', $deleted_options).')');
						$db->setQuery($query);
						$db->query();
						 
						if($item->type == 'grid')
						{
							$query = $db->getQuery(true)->delete('#__jcp_votes')->where('poll_id='.$item->id);
							$db->setQuery($query);
							$db->query();
						}
						else
						{
							$query = $db->getQuery(true)->delete('#__jcp_votes')->where('option_id in ('.implode(',', $deleted_options).')');
							$db->setQuery($query);
							$db->query();
						}
					}
					
					// first delete all resources and can be inserted later for each answer
					$query = $db->getQuery(true)->delete('#__jcp_resources')->where('poll_id='.$item->id);
					$db->setQuery($query);
					$db->query();
					
					foreach ($answers as $answer)
					{
						if($answer->id > 0 && in_array($answer->id, $existing_answers))
						{
							$query = $db->getQuery(true)
								->update('#__jcp_options')
								->set('title='.$db->q($answer->title).','.$db->qn('order').'='.$db->q($answer->order))
								->where('id='.$answer->id);
								
							$db->setQuery($query);
							$db->query();
						} 
						else 
						{
							$query = $db->getQuery(true)
								->insert('#__jcp_options')
								->columns('poll_id, title,'.$db->qn('order').','.$db->qn('type'))
								->values($item->id.','.$db->q($answer->title).','.$answer->order.','.($answer->type == 'y' ? $db->q('y') : $db->q('x')));
							
							$db->setQuery($query);
								
							if($db->query())
							{
								$answer->id = $db->insertid();
							}
							else
							{
								$this->setError(JText::_('MSG_ERROR_SAVE_POLL').' Error Code: 105109');
								return false;
							}
						}

						if(!empty($answer->resources))
						{
							$query = $db->getQuery(true)->insert('#__jcp_resources')->columns($db->qn('type').','.$db->qn('value').', poll_id, option_id');
							$found = false;
							
							foreach ($answer->resources as $resource)
							{
								if($resource->type == 'image' && JFile::exists(P_TEMP_STORE.'/'.$resource->value))
								{
									JFile::move(P_TEMP_STORE.'/'.$resource->value, P_IMAGES_UPLOAD_DIR.'/'.$resource->value);
									$query->values($db->q('image').','.$db->q($resource->value).','.$item->id.','.$answer->id);
									$found = true;
								}
								else if($resource->type == 'image' && JFile::exists(P_IMAGES_UPLOAD_DIR.'/'.$resource->value))
								{
									$query->values($db->q('image').','.$db->q($resource->value).','.$item->id.','.$answer->id);
									$found = true;
								}
								else if($resource->type == 'url')
								{
									$query->values($db->q('url').','.$db->q($resource->value).','.$item->id.','.$answer->id);
									$found = true;
								}
							}
							
							if($found)
							{
								$db->setQuery($query);
								
								if(!$db->query())
								{
									$this->setError(JText::_('MSG_ERROR_SAVE_POLL').' Error Code: 105104');
									return false;
								}
							}
						}
					}
				}
			}
			catch (Exception $e)
			{
				$this->setError(JText::_('MSG_ERROR_SAVE_POLL').' Error: '.$e->getMessage());
				return false;
			}			
			
			if (isset($data['featured']))
			{
				$this->featured($this->getState($this->getName() . '.id'), $data['featured']);
			}

			$assoc = JLanguageAssociations::isEnabled();
			if ($assoc)
			{
				// Adding self to the association
				$associations = $data['associations'];

				foreach ($associations as $tag => $id)
				{
					if (empty($id))
					{
						unset($associations[$tag]);
					}
				}

				// Detecting all item menus
				$all_language = $item->language == '*';

				if ($all_language && !empty($associations))
				{
					JError::raiseNotice(403, JText::_('COM_COMMUNITYPOLLS_ERROR_ALL_LANGUAGE_ASSOCIATED'));
				}

				$associations[$item->language] = $item->id;

				// Deleting old association for these items
				$db = JFactory::getDbo();
				$query = $db->getQuery(true)
					->delete('#__associations')
					->where('context=' . $db->quote('com_communitypolls.item'))
					->where('id IN (' . implode(',', $associations) . ')');
				$db->setQuery($query);
				$db->execute();

				if ($error = $db->getErrorMsg())
				{
					$this->setError($error);
					return false;
				}

				if (!$all_language && count($associations))
				{
					// Adding new association for these items
					$key = md5(json_encode($associations));
					$query->clear()
						->insert('#__associations');

					foreach ($associations as $id)
					{
						$query->values($id . ',' . $db->quote('com_communitypolls.item') . ',' . $db->quote($key));
					}

					$db->setQuery($query);
					$db->execute();

					if ($error = $db->getErrorMsg())
					{
						$this->setError($error);
						return false;
					}
				}
			}
			
			// update stats
			$query = 'update #__jcp_users set polls = (select count(*) from #__jcp_polls where created_by = '.$item->created_by.') where id = '.$item->created_by;
			$db->setQuery($query);
			
			try 
			{
				$db->execute();
			}
			catch (Exception $e){}

			return true;
		}

		return false;
	}

	public function featured($pks, $value = 0)
	{
		// Sanitize the ids.
		$pks = (array) $pks;
		JArrayHelper::toInteger($pks);

		if (empty($pks))
		{
			$this->setError(JText::_('COM_COMMUNITYPOLLS_NO_ITEM_SELECTED'));
			return false;
		}

		$table = $this->getTable('Poll', 'CommunityPollsTable');

		try
		{
			$db = $this->getDbo();
			$query = $db->getQuery(true)
						->update($db->quoteName('#__jcp_polls'))
						->set('featured = ' . (int) $value)
						->where('id IN (' . implode(',', $pks) . ')');
			$db->setQuery($query);
			$db->execute();
		}
		catch (Exception $e)
		{
			$this->setError($e->getMessage());
			return false;
		}

		$table->reorder();

		$this->cleanCache();

		return true;
	}

	protected function getReorderConditions($table)
	{
		$condition = array();
		$condition[] = 'catid = ' . (int) $table->catid;
		return $condition;
	}

	protected function preprocessForm(JForm $form, $data, $group = 'content')
	{
		if(APP_VERSION >= 3)
		{
			// Association content items
			$assoc = JLanguageAssociations::isEnabled();
			if ($assoc)
			{
				$languages = JLanguageHelper::getLanguages('lang_code');
	
				// force to array (perhaps move to $this->loadFormData())
				$data = (array) $data;
	
				$addform = new SimpleXMLElement('<form />');
				$fields = $addform->addChild('fields');
				$fields->addAttribute('name', 'associations');
				$fieldset = $fields->addChild('fieldset');
				$fieldset->addAttribute('name', 'item_associations');
				$fieldset->addAttribute('description', 'COM_COMMUNITYPOLLS_ITEM_ASSOCIATIONS_FIELDSET_DESC');
				$add = false;
				foreach ($languages as $tag => $language)
				{
					if (empty($data['language']) || $tag != $data['language'])
					{
						$add = true;
						$field = $fieldset->addChild('field');
						$field->addAttribute('name', $tag);
						$field->addAttribute('type', 'modal_poll');
						$field->addAttribute('language', $tag);
						$field->addAttribute('label', $language->title);
						$field->addAttribute('translate_label', 'false');
						$field->addAttribute('edit', 'true');
						$field->addAttribute('clear', 'true');
					}
				}
				if ($add)
				{
					$form->load($addform, false);
				}
			}
		}
		
		parent::preprocessForm($form, $data, $group);
	}
	
	public function validate($form, $data, $group = null)
	{
		$params = JComponentHelper::getParams('com_communitypolls');
		$app = JFactory::getApplication();
		$user = JFactory::getUser();
		
		// check if the poll type selected is in allowed poll types
		if($app->isSite() && !in_array($data['type'], $params->get('allowed_poll_types')))
		{
			$this->setError(JText::_('COM_COMMUNITYPOLLS_VALIDATE_ERROR_INVALID_POLL_TYPE'));
			return false;
		}

		// check if the poll type selected is in allowed poll types
		if($app->isSite() && !in_array($data['chart_type'], $params->get('allowed_chart_types')))
		{
			$this->setError(JText::_('COM_COMMUNITYPOLLS_VALIDATE_ERROR_INVALID_CHART_TYPE'));
			return false;
		}
		
		// at least one answer should present
		$answers = json_decode($data['poll-final-answers']);
		if(empty($answers))
		{
			$this->setError(JText::_('COM_COMMUNITYPOLLS_VALIDATE_ERROR_ANSWERS_EMPTY'));
			return false;
		}
		
		// grid poll should contain at least one column present
		$columns = json_decode($data['poll-final-columns']);
		if(strcmp($data['type'], 'grid') == 0 && empty($columns))
		{
			$this->setError(JText::_('COM_COMMUNITYPOLLS_VALIDATE_ERROR_GRID_COLUMNS_EMPTY'));
			return false;
		}

		if($user->authorise('core.polls.approve', 'com_communitypolls.category.'.$data['catid']))
		{
			$data['published'] = 1;
		}
		else
		{
			$data['published'] = 0;
		}
		
		return parent::validate($form, $data, $group);
	}

	public function postDeleteActions($cid)
	{
		$db = JFactory::getDbo();
		$ids = implode(',', $cid);
	
		try
		{
			$query = $db->getQuery(true)
				->delete('#__jcp_votes')
				->where('poll_id in ('.$ids.')');
			
			$db->setQuery($query);
			$db->execute();
				
			$query = $db->getQuery(true)
				->update('#__jcp_users as u')
				->join('left', '(select t.created_by, count(*) as count from #__jcp_polls t where t.published = 1 group by t.created_by) AS p on u.id = p.created_by')
				->set('polls = coalesce(p.count, 0)');

			$db->setQuery($query);
			$db->execute();
				
			$query = $db->getQuery(true)
				->update('#__jcp_users as u')
				->join('left', '(select t.voter_id, count(*) as count from #__jcp_votes t group by t.voter_id) AS v on u.id = v.voter_id')
				->set('votes = coalesce(v.count, 0)');

			$db->setQuery($query);
			$db->execute();
		}
		catch (Exception $e)
		{
			return false;
		}
	}

	protected function cleanCache($group = null, $client_id = 0)
	{
		parent::cleanCache('com_communitypolls');
		parent::cleanCache('mod_communitypolls');
		parent::cleanCache('mod_randompoll');
	}
}
