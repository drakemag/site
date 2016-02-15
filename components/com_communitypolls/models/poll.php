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

jimport('joomla.application.component.modelitem');

class CommunityPollsModelPoll extends JModelItem
{
	protected $_context = 'com_communitypolls.poll';

	protected function populateState()
	{
		$app = JFactory::getApplication('site');

		// Load state from the request.
		$pk = $app->input->getInt('id');
		$this->setState('poll.id', $pk);

		$offset = $app->input->getUInt('limitstart');
		$this->setState('list.offset', $offset);

		// Load the parameters.
		$params = $app->getParams();
		$this->setState('params', $params);

		// TODO: Tune these values based on other permissions.
		$user = JFactory::getUser();

		if ((!$user->authorise('core.edit.state', 'com_communitypolls')) && (!$user->authorise('core.edit', 'com_communitypolls')))
		{
			$this->setState('filter.published', 1);
			$this->setState('filter.archived', 2);
		}

		$this->setState('filter.language', JLanguageMultilang::isEnabled());
	}

	public function getItem($pk = null)
	{
		$user	= JFactory::getUser();

		$pk = (!empty($pk)) ? $pk : (int) $this->getState('poll.id');

		if ($this->_item === null)
		{
			$this->_item = array();
		}

		if (!isset($this->_item[$pk]))
		{
			try
			{
				$db = $this->getDbo();
				$query = $db->getQuery(true)
					->select(
						$this->getState(
							'item.select', 'a.id, a.asset_id, a.title, a.alias, a.description, a.type, a.answers_order, a.custom_answer,'.
							'a.pallete, anonymous, a.modify_answers, a.results_up, a.private, a.secret, a.min_answers, a.max_answers, a.close_date,' .
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
				
				if ((!$user->authorise('core.edit.state', 'com_communitypolls')) && (!$user->authorise('core.edit', 'com_communitypolls'))) {
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

				$data = $db->loadObject();
				
				if (empty($data))
				{
					throw new Exception(JText::_('COM_COMMUNITYPOLLS_ERROR_POLL_NOT_FOUND'), 403);
				}

				// Check for published state if filter set.
				if (((is_numeric($published)) || (is_numeric($archived))) && (($data->published != $published) && ($data->published != $archived)))
				{
					throw new Exception(JText::_('COM_COMMUNITYPOLLS_ERROR_POLL_NOT_FOUND'), 403);
				}
				
				if (
					($data->private == 1) && 
					($user->id != $data->created_by) && 
					!$user->authorise('core.edit.state', 'com_communitypolls') && 
					!$user->authorise('core.edit', 'com_communitypolls')
				)
				{
					// private poll, must not be accessible to the users without key
					$secret = JFactory::getApplication()->input->getCmd('secret', null);
					
					if( empty($secret) || strcmp($secret, $data->secret) != 0)
					{
						throw new Exception(JText::_('JERROR_ALERTNOAUTHOR'), 403);
					}
				}
				
				// Convert parameter fields to objects.
				$registry = new JRegistry;
				$registry->loadString($data->attribs);

				$data->params = clone $this->getState('params');
				$data->params->merge($registry);

				$registry = new JRegistry;
				$registry->loadString($data->metadata);
				$data->metadata = $registry;

				// merge default values
				$data->chart_type = !empty($data->chart_type) ? $data->chart_type : $data->params->get('chart_type');
				$data->custom_answer = in_array($data->custom_answer, array(0,1,2)) ? $data->custom_answer : $data->params->get('custom_answer');
				$data->anywhere = in_array($data->anywhere, array(0,1)) ? $data->anywhere : $data->params->get('anywhere');
				$data->featured = in_array($data->featured, array(0,1)) ? $data->featured : $data->params->get('featured');
				$data->anonymous = in_array($data->anonymous, array(0,1)) ? $data->anonymous : $data->params->get('anonymous');
				$data->private = in_array($data->private, array(0,1)) ? $data->private : $data->params->get('private');
				$data->answers_order = !empty($data->answers_order) ? $data->answers_order : $data->params->get('answers_order');
				$data->modify_answers = in_array($data->modify_answers, array(0,1)) ? $data->modify_answers : $data->params->get('modify_answers');
				
				// Technically guest could edit an poll, but lets not check that to improve performance a little.
				if (!$user->get('guest'))
				{
					$userId = $user->get('id');
					$asset = 'com_communitypolls.poll.' . $data->id;

					// Check general edit permission first.
					if ($user->authorise('core.edit', $asset))
					{
						$data->params->set('access-edit', true);
					}

					// Now check if edit.own is available.
					elseif (!empty($userId) && $user->authorise('core.edit.own', $asset))
					{
						// Check for a valid user and that they are the owner.
						if ($userId == $data->created_by)
						{
							$data->params->set('access-edit', true);
						}
					}
				}

				// Compute view access permissions.
				if ($access = $this->getState('filter.access'))
				{
					// If the access filter has been set, we already know this user can view.
					$data->params->set('access-view', true);
				}
				else
				{
					// If no access filter is set, the layout takes some responsibility for display of limited information.
					$user = JFactory::getUser();
					$groups = $user->getAuthorisedViewLevels();

					if ($data->catid == 0 || $data->category_access === null)
					{
						$data->params->set('access-view', in_array($data->access, $groups));
					}
					else
					{
						$data->params->set('access-view', in_array($data->access, $groups) && in_array($data->category_access, $groups));
					}
				}
				
				if($this->getState('load.answers'))
				{
					// Get the options
					$query = $db->getQuery(true)
						->select('a.id, a.title, a.votes, a.type, a.order')
						->from('#__jcp_options AS a')
						->where('a.poll_id = '.$data->id);
					
					$orderByType = empty($data->answers_order) ? $data->params->get('answers_order', 'order') : $data->answers_order;
					
					switch ($orderByType)
					{
						case 'votes':
							$query->order($db->qn('a.votes').' desc');
							break;
								
						case 'random':
							$query->order('RAND()');
							break;
								
						case 'order':
						default:
							$query->order($db->qn('a.order').' asc');
								
					}

					$db->setQuery($query);
					$answers = $db->loadObjectList();
					
					if(empty($answers))
					{
						throw new Exception(JText::_('COM_COMMUNITYPOLLS_ERROR_INVALID_POLL'), 403);
					}
					
					// Get the percentages for each answer
					$total_votes = 0;
					foreach($answers as $answer)
					{
						$total_votes += $answer->votes;
					}
					
					foreach($answers as $answer)
					{
						$answer->pct = $total_votes > 0 ? round( ($answer->votes*100) / $total_votes, 2) : 0;
					}
					
					// Get custom answers if any
					if($data->custom_answer == '1' && ((!$user->guest && $data->created_by == $user->id) || $user->authorise('core.manage', 'com_communitypolls.category.'.$data->catid)))
					{
						$query = $db->getQuery(true)
							->select('v.voter_id, v.voted_on, v.ip_address, v.custom_answer')
							->from('#__jcp_votes AS v');
							
						$query
							->select('u.name, u.username')
							->join('left', '#__users AS u on u.id = v.voter_id');
					
						$query
							->where('v.poll_id='.$data->id.' and v.custom_answer is not null');
							
						$db->setQuery($query);
						$data->custom_answers = $db->loadObjectList();
					}
					
					// Get the resources
					$query = $db->getQuery(true)
						->select('r.type, r.option_id, r.value')
						->from('#__jcp_resources AS r')
						->where('poll_id = '.$data->id.' and r.value is not null and r.value <> \'\'')
						->order('option_id asc');
					
					$db->setQuery($query);
					$resources = $db->loadObjectList();
					
					for($i=0; $i < count($answers); $i++) 
					{
						$answers[$i]->resources = array();
					
						if(!empty($resources))
						{
							foreach($resources as $resource) 
							{
								if($resource->option_id == $answers[$i]->id) 
								{
									if($resource->type == 'image')
									{
										$resource->src = P_IMAGES_URI.$resource->value;
									}
										
									$answers[$i]->resources[] = $resource;
								}
							}
						}
					}
					
					$data->answers = array();
					$data->columns = array();
					
					foreach($answers as $answer)
					{
						if(strcmp($answer->type, 'x') == 0)
						{
							array_push($data->answers, $answer);
						} 
						else 
						{
							array_push($data->columns, $answer);
						}
					}
					
					if(strcmp($data->type, 'grid') == 0)
					{
						$query = $db->getQuery(true)
							->select('option_id, column_id, count(*) as votes')
							->from('#__jcp_votes')
							->where('poll_id='.$data->id.' and option_id > 0 and column_id > 0')
							->group('option_id, column_id');
						
						$db->setQuery($query);
						$data->gridvotes = $db->loadObjectList();
					}
				}
				
				if($this->getState('load.details'))
				{
					$data->closed = false;
					$data->publish_results = false;
					
					if(!$user->guest && $user->id == $data->created_by)
					{ // first all owner to always view the results
						$data->publish_results = true;
					}
					elseif($user->authorise('core.results.view', 'com_communitypolls.poll.'.$data->id))
					{
						if(!empty($data->results_up) && ($data->results_up != '0000-00-00 00:00:00'))
						{ // if not the owner, check if publish results date is set, if yes validate
							$date = new JDate(JHtml::date($data->results_up, 'Y-m-d H:i:s'));
							$now = new JDate(JHtml::date('now', 'Y-m-d H:i:s'));
							
							if($now > $date)
							{
								$data->publish_results = true;
							}
						}
						else
						{ // if no validation required, user is automatically eligible to view results.
							$data->publish_results = true;
						}
					}
					
					if(!empty($data->close_date) && ($data->close_date != '0000-00-00 00:00:00'))
					{
						$date = new JDate(JHtml::date($data->close_date, 'Y-m-d H:i:s'));
						$now = new JDate(JHtml::date('now', 'Y-m-d H:i:s'));
					
						if($now > $date)
						{
							$data->closed = true;
						}
					}
					
					$query = $db->getQuery(true)
						->select('count(*) as votes, date(voted_on) as vdate')
						->from('#__jcp_votes')
						->where('poll_id = '.$pk)
						->group('date(vdate)');
					
					$db->setQuery($query);
					$data->vstats = $db->loadObjectList();
				}
				
				if($this->getState('load.eligibility'))
				{
					$data->eligible = $this->checkEligibility($data);
				}
				
				$data->params->set('access_vote', false);
				
				if($this->getState('load.details') && $this->getState('load.eligibility'))
				{
					if ($data->closed)
					{
						if ($data->publish_results == false)
						{
							$data->params->set('show_vote_form', true);
						}
						else
						{
							$data->params->set('show_vote_form', false);
						}
					}
					else if($data->publish_results == false || $data->eligible == 1 || (!$user->guest && $data->eligible == 2 && $data->modify_answers == 1))
					{
						$data->params->set('show_vote_form', true);
						
						if($data->eligible == 1 || (!$user->guest && $data->eligible == 2 && $data->modify_answers == 1))
						{
							$data->params->set('access_vote', true);
						}
					}
					else
					{
						$data->params->set('show_vote_form', false);
					}
				}
				
				$suggestionsOrder = trim($data->params->get('suggestions_order', ''));
				if($this->getState('load.suggestions') && $data->params->get('show_suggestions', 1) == 1 && !empty($suggestionsOrder))
				{
					$enabled_lists = explode(',', $suggestionsOrder);
					$data->suggestions = array();

					if(!empty($enabled_lists))
					{
						foreach ($enabled_lists as $i=>$type)
						{
							$data->suggestions[] = $this->getSuggestions($type, $data->created_by, $data->title, ($i == 0));
						}
					}
				}

				$this->_item[$pk] = $data;
			}
			catch (Exception $e)
			{
				if ($e->getCode() == 404)
				{
					// Need to go thru the error handler to allow Redirect to work.
					throw new Exception($e->getMessage(), 404);
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

	/**
	 * Returns eligibility status.
	 * 1 - Eligible
	 * 2 - Already voted 
	 * 3 - Unauthorised
	 * @param unknown $poll
	 * @param string $ip
	 * @return number
	 */
	public function checkEligibility( $poll, $ip = null )
	{
		$app = JFactory::getApplication();
		$user = JFactory::getUser();
		$params = JComponentHelper::getParams('com_communitypolls');

		// check if the user is authorized to vote. If embed poll option is enabled, he is authorized by default.
		if(!$user->authorise('core.vote', 'com_communitypolls.poll.'.$poll->id))
		{
// 			$this->setError(JText::_('COM_COMMUNITYPOLLS_MESSAGE_NOT_ELIGIBLE_TO_VOTE'));
			return 3;
		}
		 
		$vote_restriction = $params->get('vote_restriction', array());
		$vote_expiration = intval($params->get('vote_expiration', 0));
		 
		// check if the vote is already registered under cookie method (if enabled)
		if(in_array('cookie', $vote_restriction))
		{
			$name = CJFunctions::get_hash( $app->getName().P_COOKIE_PREFIX.$poll->id );
			$value = $app->input->cookie->get($name, 0);
			
			if($value !== 0)
			{
// 				$this->setError(JText::_('COM_COMMUNITYPOLLS_MESSAGE_ALREADY_VOTED'));
				return 2;
			}
		}
	
		// get the user ip address
		$ip = $ip ? $ip : CJFunctions::get_user_ip_address();
	
		// check if vote is already registered under ip and/or username method (if enabled).
		if( in_array('ip', $vote_restriction) || in_array('username', $vote_restriction) )
		{
			$db = JFactory::getDbo();
			$wheres = array();
			$wheres2 = array();
				
			if(!$user->guest && in_array('username', $vote_restriction))
			{
				$wheres[] = 'voter_id='.$user->id;
			}
				
			if(in_array('ip', $vote_restriction) && !empty($ip))
			{
				$wheres[] = 'ip_address='.$db->q($ip);
			}
				
			if($vote_expiration > 0)
			{
				$date = date('Y-m-d H:i:s', strtotime(gmdate('Y-m-d H:i:s'))-($vote_expiration*60));
				$wheres2[] = 'voted_on > '.$db->q($date);
			}
				
			if(count($wheres) > 0)
			{
				$query = 'select count(*) from #__jcp_votes where poll_id='.$poll->id.' and ('.implode(' or ', $wheres).')';
	
				if(!empty($wheres2))
				{
					$query = $query.' and ('.implode(' and ', $wheres2).')';
				}
	
				$db->setQuery($query);
				$count = $db->loadResult();
	
				if($count > 0)
				{
// 					$this->setError(JText::_('COM_COMMUNITYPOLLS_MESSAGE_ALREADY_VOTED'));
					return 2;
				}
			}
		}
	
		return 1;
	}
	
	public function getSuggestions($type, $user_id, $poll_title, $load_data = false)
	{
		$suggestion = array();
		$title = $polls = $id = $href = null;
		$itemid = CJFunctions::get_active_menu_id();
		
		$model = JModelList::getInstance('Polls', 'CommunityPollsModel');
		$params = $model->getState('params');
		
		$model->setState('filter.published', 1);
		$model->setState('list.start', 0);
		$model->setState('list.ordering', 'a.created');
		$model->setState('list.direction', 'desc');
		
		switch (strtoupper($type))
		{
			case 'A': // Author Polls
				$model->setState('list.limit', $params->get('author_polls_count', 5));
				$model->setState('list.filter', $user_id);
				$model->setState('list.filter_field', 'createdby');
				
				$title = JText::_('COM_COMMUNITYPOLLS_AUTHOR_POLLS');
				$id = 'poll-suggestion-author';
				$href = $load_data ? '#poll-suggestion-author' : JRoute::_('index.php?option=com_communitypolls&task=poll.suggestions&s=a&u='.$user_id.$itemid);
				break;
				
			case 'F': // Featured Polls
				$model->setState('list.limit', $params->get('featured_polls_count', 5));
				$model->setState('filter.featured', 'only');
				
				$title = JText::_('COM_COMMUNITYPOLLS_FEATURED_POLLS');
				$id = 'poll-suggestion-featured';
				$href = $load_data ? '#poll-suggestion-featured' : JRoute::_('index.php?option=com_communitypolls&task=poll.suggestions&s=f'.$itemid);
				break;
				
			case 'R': // Related Polls
				$model->setState('list.limit', $params->get('related_polls_count', 5));
				$model->setState('list.filter', $poll_title);
				$model->setState('list.filter_field', 'title');
				
				$title = JText::_('COM_COMMUNITYPOLLS_RELATED_POLLS');
				$id = 'poll-suggestion-related';
				$href = $load_data ? '#poll-suggestion-related' : JRoute::_('index.php?option=com_communitypolls&task=poll.suggestions&s=r&q='.JFilterOutput::stringURLSafe($poll_title).$itemid);
				break;
				
			case 'L': // Latest Polls
				$model->setState('list.limit', $params->get('latest_polls_count', 5));
				
				$title = JText::_('COM_COMMUNITYPOLLS_LATEST_POLLS');
				$id = 'poll-suggestion-latest';
				$href = $load_data ? '#poll-suggestion-latest' : JRoute::_('index.php?option=com_communitypolls&task=poll.suggestions&s=l'.$itemid);
				break;
				
			case 'M': // Most Voted Polls
				$model->setState('list.limit', $params->get('mostvoted_polls_count', 5));
				$model->setState('list.ordering', 'a.votes');
				$model->setState('list.direction', 'desc');
				
				$title = JText::_('COM_COMMUNITYPOLLS_MOST_VOTED_POLLS');
				$id = 'poll-suggestion-mostvoted';
				$href = $load_data ? '#poll-suggestion-latest' : JRoute::_('index.php?option=com_communitypolls&task=poll.suggestions&s=m'.$itemid);
				break;
		}
		
		if($load_data)
		{
			$polls = $model->getItems();
		}
		
		$suggestion = array('title'=>$title, 'polls'=>$polls, 'id'=>$id, 'href'=>$href, 'active'=>$load_data);
		
		return $suggestion;
	}
	
	public function registerVote(&$poll, $anywhere = false)
	{
		$user = JFactory::getUser();
		$app = JFactory::getApplication();
		$db = JFactory::getDbo();
		$params = JComponentHelper::getParams('com_communitypolls');
		 
		$id = $app->input->getInt('id', 0);
		$responses = $app->input->getArray(array('answers'=>'array'));
		$responses = $responses['answers'];
		$custom_answer = trim($app->input->getString('custom_answer', null));
		
		/////////////////////// Verification /////////////////////////////////////////////
			
		// check if input values are set
		if( (empty($responses) && empty($custom_answer)) || ($poll->custom_answer == 0 && empty($responses)) )
		{
			$this->setError(JText::_('COM_COMMUNITYPOLLS_ERROR_NO_SELECTION'));
			return false;
		}
		
		// Check if minimum and maximum answer selection rule validated
		if(
			($poll->type == 'checkbox') &&
			($poll->max_answers > 0 || $poll->min_answers > 0) && 
			($poll->max_answers >= $poll->min_answers) && 
			(count($responses) < $poll->min_answers || count($responses) > $poll->max_answers) )
		{
			$this->setError(JText::sprintf('COM_COMMUNITYPOLLS_ERROR_SELECT_MIN_MAX', $poll->min_answers, $poll->max_answers));
			return false;
		}
		
		// reCaptcha check if allowed, if yes validate
		if(!$user->authorise('core.vcaptcha', 'com_communitypolls.poll.'.$id))
		{
			$captcha = $app->input->getString('recaptcha_response_field', '');
			JPluginHelper::importPlugin('captcha');
			$dispatcher = JEventDispatcher::getInstance();
			$result = $dispatcher->trigger('onCheckAnswer', $captcha);
			 
			if(!$result[0])
			{
				$this->setError(JText::_('COM_COMMUNITYPOLLS_ERROR_INVALID_CAPTCHA'));
				return false;
			}
		}
		
		// get the user ip address
		$ipAddress = CJFunctions::get_user_ip_address();
		$eligible = $this->checkEligibility($poll, $ipAddress);
		
		if($eligible == 3 || ($eligible == 2 && ($user->guest || $poll->modify_answers != 1)))
		{
			if($eligible == 2) $this->setError(JText::_('COM_COMMUNITYPOLLS_MESSAGE_ALREADY_VOTED'));
			if($eligible == 3) $this->setError(JText::_('COM_COMMUNITYPOLLS_MESSAGE_NOT_ELIGIBLE_TO_VOTE'));
			return false;
		}
		
		/////////////////////// Verification completed//////////////////////////////////////
		
		if($eligible == 2 && $poll->modify_answers == 1)
		{
			// user already voted, delete it
			$query = $db->getQuery(true)
				->delete('#__jcp_votes')
				->where('poll_id = '.$poll->id.' and voter_id = '.$user->id)
				->order('voted_on desc');
			$db->setQuery($query);
			
			try 
			{
				$db->execute();
			}
			catch (Exception $e)
			{
				$this->setError(JText::_('COM_COMMUNITYPOLLS_ERROR_INTERNAL'));
				return false;
			}
		}
		 
		$answers = array();
		$columns = array();
		 
		if(strcmp($poll->type, 'grid') == 0)
		{
			foreach ($responses as $i=>$response)
			{
				$vals = explode('_', $response);
				 
				if(count($vals) == 2)
				{
					$answers[$i] = (int)$vals[0];
					$columns[$i] = (int)$vals[1];
				}
			}
		}
		else
		{
			if(!empty($responses))
			{
				foreach ($responses as $response)
				{
					$answers[] = (int) $response;
					$columns[] = 0;
				}
			}
		}
		
		$query_answers = array();
		$date = JFactory::getDate()->toSql();
		$user_id = $poll->anonymous == 1 ? 0 : $user->id;
		$ip = $poll->anonymous == 1 ? '0.0.0.0' : $ipAddress;
		
		// if the custom answer is not empty and custom answer option is not disabled, then
		// it should be either inserted as new option (if the stack answers is selected and answer does not exist) and adds a new vote or
		// inserted as a new vote without new option addded (if stack answers is not selected)
		if(!empty($custom_answer) && ($poll->custom_answer == 1 || $poll->custom_answer == 2) )
		{
			$answer_found = false;
				
			foreach ($poll->answers as $answer)
			{
				if(strcmp(strtolower(trim($answer->title)), strtolower(trim($custom_answer))) == 0)
				{
					$answer_found = $answer->id;
				}
			}
				
			// if the answer is not a new answer, just add one more vote to existing answer by appending answer id to answers list
			if( $answer_found )
			{
				// add new  vote only if the user does not enter the same answer in custom answer box as well as selected the original answer
				if( !in_array($answer_found, $answers) )
				{
					$answers[] = $answer_found;
					$columns[] = 0;
				}
			}
			// if the stack answers option is NOT selected but custom answer is enabled or it is a grid type poll, just add new vote
			else if( $poll->custom_answer == 1 || strcmp($poll->type, 'grid') == 0 )
			{
				$query_answers[] = '('.$poll->id.','.$user_id.','.$db->q($date).','.$db->q($ip).',0 , 0,'.$db->q($custom_answer).')';
			}
			// if stack answers option is selected, custom answer is a new answer and the poll type is not grid, just insert it
			else if($poll->custom_answer == 2)
			{
				$type_column = $db->qn('type');
				$order_column = $db->qn('order');
		
				// add custom answer to options
				$query = 'select max('.$order_column.') from #__jcp_options where poll_id = '.$poll->id;
				$db->setQuery($query);
				$order = (int)$db->loadResult();
				
				$query = '
	    				insert into
	    					#__jcp_options(poll_id, title,'.$order_column.','.$type_column.')
	    				values
	    					('.$poll->id.','.$db->q($custom_answer).','.$order.','.$db->q('x').')';
					
				$db->setQuery($query);
				
				try
				{
					$db->execute();
				}
				catch (Exception $e)
				{
					$this->setError($db->getErrorMsg());
					return false;
				}
				
				$answers[] = $db->insertid();
				$columns[] = 0;
			}
		}
		
		// build votes insert query
		$query = 'insert into #__jcp_votes (poll_id, voter_id, voted_on, ip_address, option_id, column_id, custom_answer) values ';
		
		if(!empty($answers))
		{
			foreach($answers as $i=>$answer)
			{
				$query_answers[] = '('.$poll->id.','.$user_id.','.$db->q($date).','.$db->q($ip).','.$answers[$i].','.$columns[$i].', null)';
			}
		}
		
		$query = $query.implode(',', $query_answers);
		$db->setQuery( $query );
		
		try
		{
			$db->execute();
		}
		catch (Exception $e)
		{
			$this->setError(JText::_('COM_COMMUNITYPOLLS_ERROR_INTERNAL').". Error Code: 100010".(P_DEBUG_MODE ? '| Query: '.$query.'| Error: '.$e->getMessage() : ''));
			return false;
		}
		
		/********************* Update last vote state in linked tables *******************************/
		$query = 'update #__jcp_polls set last_voted = '.$db->q($date).' where id = '.$poll->id;
		$db->setQuery( $query );
		
		try
		{
			$db->execute();
		}
		catch (Exception $e)
		{
			$this->setError(JText::_('COM_COMMUNITYPOLLS_ERROR_INTERNAL').". Error Code: 100011".(P_DEBUG_MODE ? '| Query: '.$query.'| Error: '.$db->getErrorMsg() : ''));
			return false;
		}
		
		if($user_id > 0)
		{
			$query = $db->getQuery(true)->select('count(*)')->from('#__jcp_users')->where('id = '.$user_id);
			$db->setQuery($query);
			$count = (int) $db->loadResult();

			if($count > 0)
			{
				$query->clear()->update('#__jcp_users')->set('last_voted = '.$db->q($date))->where('id = '.$user_id);
			}
			else 
			{
				$query->clear()->insert('#__jcp_users')->columns('id, last_voted')->values($user_id.','.$db->q($date));
			}
			
			$db->setQuery( $query );
			
			try
			{
				$db->execute();
			}
			catch (Exception $e)
			{
				$this->setError(JText::_('COM_COMMUNITYPOLLS_ERROR_INTERNAL').". Error Code: 100012".(P_DEBUG_MODE ? '| Query: '.$query.'| Error: '.$db->getErrorMsg() : ''));
				return false;
			}
			
			$query = 'update #__jcp_users set votes = (select count(*) from #__jcp_votes where voter_id = '.$user_id.') where id = '.$user_id;
			$db->setQuery( $query );
			
			try
			{
				$db->execute();
			}
			catch (Exception $e)
			{
				$this->setError(JText::_('COM_COMMUNITYPOLLS_ERROR_INTERNAL').". Error Code: 100013".(P_DEBUG_MODE ? '| Query: '.$query.'| Error: '.$db->getErrorMsg() : ''));
				return false;
			}
		}
		/********************* Update last vote state in linked tables *******************************/
		
		// Now update statistics
		$this->syncStatistics($poll->id);
		
		// now set cookie if vote restriction method of cookies is enabled.
		if( !$anywhere && in_array('cookie', $params->get('vote_restriction', array())) )
		{
			$name	= CJFunctions::get_hash($app->getName().P_COOKIE_PREFIX.$poll->id);
			$expire = (int)$params->get('vote_expiration', 525600);
			$app->input->cookie->set($name, 1, $expire, '/');
		}
		
		// update tracking information
		$tracking = new stdClass();
		$location = CJFunctions::get_user_location($ipAddress);
		$browser = CJFunctions::get_browser();
		
		$tracking->post_id = $id;
		$tracking->post_type = 2;
		$tracking->ip_address = $ip;
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
		
		// get latest answers with vote result for front-end viewing if eligible.
		$poll->votes = $poll->votes + count($responses);
		
		if($anywhere || $poll->publish_results)
		{
			try 
			{
				// Get the options
				$query = '
	            	SELECT
	            		id, title, votes, '.($poll->votes > 0 ? 'round((votes*100)/'.$poll->votes.', 2)' : '0').' as pct, '.
			            		$db->qn('type').','.$db->qn('order').'
	            	from
	            		#__jcp_options
					where
	            		poll_id='.$poll->id.'
	            	order by
	            		'.$db->qn('order').' asc';
			
				$db->setQuery($query);
				$answers = $db->loadObjectList();

				$poll->answers = array();
				$poll->columns = array();
			
				foreach($answers as $answer)
				{
					if(strcmp($answer->type, 'x') == 0)
					{
						$poll->answers[] = $answer;
					}
					else
					{
						$poll->columns[] = $answer;
					}
				}
			
				if(strcmp($poll->type, 'grid') == 0)
				{
					$query = '
							select
								option_id, column_id, count(*) as votes
							from
								#__jcp_votes
							where
								poll_id='.$poll->id.' and option_id > 0 and column_id > 0
							group by
								option_id, column_id';
			
					$db->setQuery($query);
					$poll->gridvotes = $db->loadObjectList();
				}
			}
			catch (Exception $e)
			{
				$this->setError(JText::_('COM_COMMUNITYPOLLS_ERROR_INTERNAL').". Error Code: 100015");
				return false;
			}
		}
		
		// vote successful, return poll
		return $poll;
	}
	
	private function syncStatistics($pollId = 0)
	{
		$where = '';
		$query = '';
		$db = JFactory::getDbo();
		
		if($pollId > 0)
		{
			$where = ' where o.poll_id = '.$pollId;
		}

		$query = '
			update
				#__jcp_options as o
			left join
				(
				select 
					t.option_id, count(*) as count 
				from 
					#__jcp_votes t'.($pollId > 0 ? ' where t.poll_id = '.$pollId : '').' 
				group by 
					t.option_id
				) as v on o.id = v.option_id
			set
				o.votes = coalesce(v.count, 0)'.$where;
		
		$db->setQuery($query);
		
		try 
		{
			$db->execute();
		}
		catch (Exception $e)
		{
			return false;
		}

		if($pollId > 0)
		{
			$query = 'update #__jcp_polls p set p.votes = (select count(*) from #__jcp_votes v where v.poll_id = '.$pollId.') where p.id = '.$pollId;
		}
		else 
		{
			$query = '
				update
					#__jcp_polls as p
				left join
					(select t.poll_id, count(*) as count from #__jcp_votes t group by t.poll_id) as v on p.id = v.poll_id
				set
					votes = coalesce(v.count, 0)';
		}
				
		$db->setQuery($query);
		
		try 
		{
			$db->execute();
		}
		catch (Exception $e)
		{
			return false;
		}
		
		if($pollId > 0)
		{
			$query = 'update #__jcp_polls set voters = (select count(distinct(voter_id)) from #__jcp_votes v where v.poll_id = '.$pollId.') where id = '.$pollId;
		}
		else 
		{
			$query = '
				update
					#__jcp_polls as p
				left join
					(select t.poll_id, count(distinct(voter_id)) as count from #__jcp_votes t group by t.poll_id) as v on p.id = v.poll_id
				set
					voters = coalesce(v.count, 0)';
		}
		
		$db->setQuery($query);
		
		try
		{
			$db->execute();
		}
		catch (Exception $e)
		{
			return false;
		}
	}
}
