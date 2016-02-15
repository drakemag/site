<?php
/**
 * @version		$Id: polls.php 01 2011-01-11 11:37:09Z maverick $
 * @package		CoreJoomla.Polls
 * @subpackage	Components.site
 * @copyright	Copyright (C) 2009 - 2014 corejoomla.com, Inc. All rights reserved.
 * @author		Maverick
 * @link		http://www.corejoomla.com/
 * @license		License GNU General Public License version 2 or later
 */

defined('_JEXEC') or die;
jimport( 'joomla.application.component.modellist' );

class CommunityPollsModelPolls extends JModelList
{
	public function __construct($config = array())
	{
		if (empty($config['filter_fields']))
		{
			$config['filter_fields'] = array(
				'id', 'a.id',
				'title', 'a.title',
				'alias', 'a.alias',
				'checked_out', 'a.checked_out',
				'checked_out_time', 'a.checked_out_time',
				'catid', 'a.catid', 'category_title',
				'published', 'a.published',
				'access', 'a.access', 'access_level',
				'created', 'a.created',
				'created_by', 'a.created_by',
				'ordering', 'a.ordering',
				'featured', 'a.featured',
				'language', 'a.language',
				'votes', 'a.votes',
				'voters', 'a.voters',
				'publish_up', 'a.publish_up',
				'publish_down', 'a.publish_down'
			);
		}
		
		parent::__construct($config);
	}

	protected function populateState($ordering = 'a.created', $direction = 'DESC')
	{
		$app = JFactory::getApplication();
		$user = JFactory::getUser();
		$params = $app->getParams();

		// List state information
		$value = $app->input->get('limit', $app->getCfg('list_limit', 0), 'uint');
		$this->setState('list.limit', $value);

		$value = $app->input->get('limitstart', 0, 'uint');
		$this->setState('list.start', $value);
		
		$categories = $app->input->getArray(array('catid'=>'array'));
		$this->setState('filter.category_id', $categories['catid']);
		
		// ordering
		$orderCol = $app->input->get('filter_order', $ordering, 'cmd');
		if (!in_array($orderCol, $this->filter_fields))
		{
			$orderCol = 'a.created';
		}

		$listOrder = $app->input->get('filter_order_Dir', $direction, 'word');
		if (!in_array(strtoupper($listOrder), array('ASC', 'DESC', '')))
		{
			$listOrder = 'DESC';
		}

		// publish state
		if ((!$user->authorise('core.edit.state', 'com_communitypolls')) && (!$user->authorise('core.edit', 'com_communitypolls')))
		{
			// Filter on published for those who do not have edit or edit.state rights.
			$this->setState('filter.published', 1);
		}

		// Process show_noauth parameter
		$this->setState('filter.access', true);
		
		// author id filtering
		$authorId = $app->input->get('filter_author_id', 0, 'uint');
		if($authorId)
		{
			$this->setState('filter.author_id', $authorId);
		}
		
		$featured = $app->input->get('filter_featured', null, 'cmd');
		if(in_array($featured, array('hide', 'only', 'show')))
		{
			$this->setState('filter.featured', $featured);
		}
		
		$timelimit = $app->input->get('filter_timelimit', 0, 'uint');
		switch ($timelimit)
		{
			case 1: // today's polls
				$this->setState('filter.date_filtering', 'range');
				$this->setState('filter.start_date_range', 'CURDATE()');
				$this->setState('filter.end_date_range', 'NOW()');
				break;
				
			case 2: // this week's polls
				$this->setState('filter.date_filtering', 'range');
				$this->setState('filter.start_date_range', 'SUBDATE(CURDATE(), INTERVAL WEEKDAY(NOW()) DAY)');
				$this->setState('filter.end_date_range', 'NOW()');
				break;
				
			case 3: // this month's polls
				$this->setState('filter.date_filtering', 'range');
				$this->setState('filter.start_date_range', 'DATE_FORMAT(NOW(), \'%Y-%m-01\')');
				$this->setState('filter.end_date_range', 'NOW()');
				break;
				
			case 4: // last month's polls
				$this->setState('filter.date_filtering', 'range');
				$this->setState('filter.start_date_range', 'DATE_SUB(DATE_FORMAT(NOW(), \'%Y-%m-01\'), INTERVAL 1 MONTH)');
				$this->setState('filter.end_date_range', 'DATE_FORMAT(NOW(), \'%Y-%m-01\')');
				break;
				
			case 5: // this year's polls
				$this->setState('filter.date_filtering', 'range');
				$this->setState('filter.start_date_range', 'DATE_FORMAT(NOW(), \'%Y-01-%d\')');
				$this->setState('filter.end_date_range', 'NOW()');
				break;
				
			default: // clear filter
				$this->setState('filter.date_filtering', '');
				$timelimit = 0;
				break;
		}
		
		$filter = $app->input->get('list_filter', '', 'string');
		if(strlen($filter) > 1)
		{
			$filterField = $app->input->get('list_filter_field', 'title', 'word');
			$this->setState('list.filter', $filter);
			$this->setState('list.filter_field', $filterField);
		}
		
		$this->setState('filter.timelimit', $timelimit);
		$this->setState('list.ordering', $orderCol);
		$this->setState('list.direction', $listOrder);
		$this->setState('params', $params);
		$this->setState('filter.language', JLanguageMultilang::isEnabled());
		$this->setState('layout', $app->input->getString('layout'));
	}

	protected function getStoreId($id = '')
	{
		// Compile the store id.
		$id .= ':' . serialize($this->getState('filter.published'));
		$id .= ':' . $this->getState('filter.access');
		$id .= ':' . $this->getState('filter.featured');
		$id .= ':' . $this->getState('filter.poll_id');
		$id .= ':' . $this->getState('filter.poll_id.include');
		$id .= ':' . serialize($this->getState('filter.category_id'));
		$id .= ':' . $this->getState('filter.category_id.include');
		$id .= ':' . serialize($this->getState('filter.author_id'));
		$id .= ':' . $this->getState('filter.author_id.include');
		$id .= ':' . serialize($this->getState('filter.author_alias'));
		$id .= ':' . $this->getState('filter.author_alias.include');
		$id .= ':' . $this->getState('filter.date_filtering');
		$id .= ':' . $this->getState('filter.date_field');
		$id .= ':' . $this->getState('filter.start_date_range');
		$id .= ':' . $this->getState('filter.end_date_range');
		$id .= ':' . $this->getState('filter.relative_date');

		return parent::getStoreId($id);
	}

	protected function getListQuery()
	{
		// Get the current user for authorisation checks
		$user	= JFactory::getUser();

		// Create a new query object.
		$db = $this->getDbo();
		$query = $db->getQuery(true);

		// Select the required fields from the table.
		$query->select(
			$this->getState(
				'list.select',
				'a.id, a.title, a.alias, a.description, a.private, a.language, a.checked_out, a.checked_out_time, a.catid, a.created, a.created_by, a.created_by_alias, ' .
					// Use created if modified is 0
					'CASE WHEN a.modified = ' . $db->quote($db->getNullDate()) . ' THEN a.created ELSE a.modified END as modified, ' .
					'a.modified_by, uam.name as modified_by_name,' .
					// Use created if publish_up is 0
					'CASE WHEN a.publish_up = ' . $db->quote($db->getNullDate()) . ' THEN a.created ELSE a.publish_up END as publish_up,' .
					'a.publish_down, a.attribs, a.metadata, a.metakey, a.metadesc, a.access,  a.votes, a.featured,' . ' ' . $query->length('a.description') . ' AS readmore'
			)
		);

		// Process an Archived Poll layout
		if ($this->getState('filter.published') == 2)
		{
			// If badcats is not null, this means that the poll is inside an archived category
			// In this case, the state is set to 2 to indicate Archived (even if the poll state is Published)
			$query->select($this->getState('list.select', 'CASE WHEN badcats.id is null THEN a.published ELSE 2 END AS published'));
		}
		else
		{
			/*
			Process non-archived layout
			If badcats is not null, this means that the poll is inside an unpublished category
			In this case, the published is set to 0 to indicate Unpublished (even if the poll state is Published)
			*/
			$query->select($this->getState('list.select', 'CASE WHEN badcats.id is not null THEN 0 ELSE a.published END AS published'));
		}

		$query->from('#__jcp_polls AS a');

		// Join over the categories.
		$query->select('c.title AS category_title, c.path AS category_route, c.access AS category_access, c.alias AS category_alias')
			->join('LEFT', '#__categories AS c ON c.id = a.catid');

		// Join over the users for the author and modified_by names.
		$query->select("CASE WHEN a.created_by_alias > ' ' THEN a.created_by_alias ELSE ua.name END AS author")
			->select("ua.email AS author_email")

			->join('LEFT', '#__users AS ua ON ua.id = a.created_by')
			->join('LEFT', '#__users AS uam ON uam.id = a.modified_by');

		// Join over the categories to get parent category titles
		$query->select('parent.title as parent_title, parent.id as parent_id, parent.path as parent_route, parent.alias as parent_alias')
			->join('LEFT', '#__categories as parent ON parent.id = c.parent_id');

		// Join to check for category published state in parent categories up the tree
		$query->select('CASE WHEN badcats.id is null THEN c.published ELSE 0 END AS parents_published');
		$subquery = 'SELECT cat.id as id FROM #__categories AS cat JOIN #__categories AS parent ';
		$subquery .= 'ON cat.lft BETWEEN parent.lft AND parent.rgt ';
		$subquery .= 'WHERE parent.extension = ' . $db->quote('com_communitypolls');

		if ($this->getState('filter.published') == 2)
		{
			// Find any up-path categories that are archived
			// If any up-path categories are archived, include all children in archived layout
			$subquery .= ' AND parent.published = 2 GROUP BY cat.id ';

			// Set effective state to archived if up-path category is archived
			$publishedWhere = 'CASE WHEN badcats.id is null THEN a.published ELSE 2 END';
		}
		else
		{
			// Find any up-path categories that are not published
			// If all categories are published, badcats.id will be null, and we just use the poll state
			$subquery .= ' AND parent.published != 1 GROUP BY cat.id ';

			// Select published to unpublished if up-path category is unpublished
			$publishedWhere = 'CASE WHEN badcats.id is null THEN a.published ELSE 0 END';
		}

		$query->join('LEFT OUTER', '(' . $subquery . ') AS badcats ON badcats.id = c.id');

		// Filter by access level.
		if ($access = $this->getState('filter.access'))
		{
			$groups = implode(',', $user->getAuthorisedViewLevels());
			$query->where('a.access IN (' . $groups . ')')
				->where('c.access IN (' . $groups . ')');
		}

		// Filter by published state
		$published = $this->getState('filter.published');

		if (is_numeric($published))
		{
			// Use poll state if badcats.id is null, otherwise, force 0 for unpublished
			$query->where($publishedWhere . ' = ' . (int) $published);
		}
		elseif (is_array($published))
		{
			JArrayHelper::toInteger($published);
			$published = implode(',', $published);

			// Use poll state if badcats.id is null, otherwise, force 0 for unpublished
			$query->where($publishedWhere . ' IN (' . $published . ')');
		}

		// Filter by featured state
		$featured = $this->getState('filter.featured');
		switch ($featured)
		{
			case 'hide':
				$query->where('a.featured = 0');
				break;

			case 'only':
				$query->where('a.featured = 1');
				break;

			case 'show':
			default:
				// Normally we do not discriminate
				// between featured/unfeatured items.
				break;
		}

		// Filter by a single or group of polls.
		$pollId = $this->getState('filter.poll_id');

		if (is_numeric($pollId))
		{
			$type = $this->getState('filter.poll_id.include', true) ? '= ' : '<> ';
			$query->where('a.id ' . $type . (int) $pollId);
		}
		elseif (is_array($pollId))
		{
			JArrayHelper::toInteger($pollId);
			$pollId = implode(',', $pollId);
			$type = $this->getState('filter.poll_id.include', true) ? 'IN' : 'NOT IN';
			$query->where('a.id ' . $type . ' (' . $pollId . ')');
		}

		// Filter by a single or group of categories
		$categoryId = $this->getState('filter.category_id');
		
		if (is_numeric($categoryId))
		{
			$type = $this->getState('filter.category_id.include', true) ? '= ' : '<> ';

			// Add subcategory check
			$includeSubcategories = $this->getState('filter.subcategories', false);
			$categoryEquals = 'a.catid ' . $type . (int) $categoryId;

			if ($includeSubcategories)
			{
				$levels = (int) $this->getState('filter.max_category_levels', '1');

				// Create a subquery for the subcategory list
				$subQuery = $db->getQuery(true)
					->select('sub.id')
					->from('#__categories as sub')
					->join('INNER', '#__categories as this ON sub.lft > this.lft AND sub.rgt < this.rgt')
					->where('this.id = ' . (int) $categoryId);

				if ($levels >= 0)
				{
					$subQuery->where('sub.level <= this.level + ' . $levels);
				}

				// Add the subquery to the main query
				$query->where('(' . $categoryEquals . ' OR a.catid IN (' . $subQuery->__toString() . '))');
			}
			else
			{
				$query->where($categoryEquals);
			}
		}
		elseif (is_array($categoryId) && (count($categoryId) > 0))
		{
			$categoryId = array_filter($categoryId);
			JArrayHelper::toInteger($categoryId);
			
			if (!empty($categoryId))
			{
				$categoryId = implode(',', $categoryId);
				$type = $this->getState('filter.category_id.include', true) ? 'IN' : 'NOT IN';
				$query->where('a.catid ' . $type . ' (' . $categoryId . ')');
			}
		}

		// Filter by author
		$authorId = $this->getState('filter.author_id');
		$authorWhere = '';
		
		if (is_numeric($authorId) && $authorId > 0)
		{
			$type = $this->getState('filter.author_id.include', true) ? '= ' : '<> ';
			$authorWhere = 'a.created_by ' . $type . (int) $authorId;
		}
		elseif (is_array($authorId))
		{
			JArrayHelper::toInteger($authorId);
			$authorId = implode(',', $authorId);

			if ($authorId)
			{
				$type = $this->getState('filter.author_id.include', true) ? 'IN' : 'NOT IN';
				$authorWhere = 'a.created_by ' . $type . ' (' . $authorId . ')';
			}
		}

		// Filter by author alias
		$authorAlias = $this->getState('filter.author_alias');
		$authorAliasWhere = '';

		if (is_string($authorAlias))
		{
			$type = $this->getState('filter.author_alias.include', true) ? '= ' : '<> ';
			$authorAliasWhere = 'a.created_by_alias ' . $type . $db->quote($authorAlias);
		}
		elseif (is_array($authorAlias))
		{
			$first = current($authorAlias);

			if (!empty($first))
			{
				JArrayHelper::toString($authorAlias);

				foreach ($authorAlias as $key => $alias)
				{
					$authorAlias[$key] = $db->quote($alias);
				}

				$authorAlias = implode(',', $authorAlias);

				if ($authorAlias)
				{
					$type = $this->getState('filter.author_alias.include', true) ? 'IN' : 'NOT IN';
					$authorAliasWhere = 'a.created_by_alias ' . $type . ' (' . $authorAlias . ')';
				}
			}
		}

		if (!empty($authorWhere) && !empty($authorAliasWhere))
		{
			$query->where('(' . $authorWhere . ' OR ' . $authorAliasWhere . ')');
		}
		elseif (empty($authorWhere) && empty($authorAliasWhere))
		{
			// If both are empty we don't want to add to the query
		}
		else
		{
			// One of these is empty, the other is not so we just add both
			$query->where($authorWhere . $authorAliasWhere);
		}

		// Filter by start and end dates.
		if ((!$user->authorise('core.edit.state', 'com_communitypolls')) && (!$user->authorise('core.edit', 'com_communitypolls'))) 
		{
			$nullDate	= $db->quote($db->getNullDate());
			$nowDate	= $db->quote(JFactory::getDate()->toSql());

			$query->where('(a.publish_up = '.$nullDate.' OR a.publish_up <= '.$nowDate.')')
				->where('(a.publish_down = '.$nullDate.' OR a.publish_down >= '.$nowDate.')');
		}

		// Filter by Date Range or Relative Date
		$dateFiltering = $this->getState('filter.date_filtering', 'off');
		$dateField = $this->getState('filter.date_field', 'a.created');

		switch ($dateFiltering)
		{
			case 'range':
				$nullDate	= $db->quote($db->getNullDate());
				$startDateRange = $this->getState('filter.start_date_range', $nullDate);
				$endDateRange = $this->getState('filter.end_date_range', $nullDate);
				$query->where(
					'(' . $dateField . ' >= ' . $startDateRange . ' AND ' . $dateField .
						' <= ' . $endDateRange . ')'
				);
				break;

			case 'relative':
				$nowDate	= $db->quote(JFactory::getDate()->toSql());
				$relativeDate = (int) $this->getState('filter.relative_date', 0);
				$query->where(
					$dateField . ' >= DATE_SUB(' . $nowDate . ', INTERVAL ' .
						$relativeDate . ' DAY)'
				);
				break;

			case 'off':
			default:
				break;
		}

		// Process the filter for list views with user-entered filters
		if ($filter = $this->getState('list.filter'))
		{
			// Clean filter variable
			$votesFilter = (int) $filter;
			$createdByFilter = (int) $filter;
			$filter = JString::strtolower($filter);

			switch ($this->getState('list.filter_field'))
			{
				case 'createdby':
					$query->where('a.created_by = ' . $createdByFilter . ' ');
					break;
					
				case 'author':
					$filter = $db->quote('%' . $db->escape($filter, true) . '%', false);
					$query->where(
						'LOWER( CASE WHEN a.created_by_alias > ' . $db->quote(' ') .
							' THEN a.created_by_alias ELSE ua.name END ) LIKE ' . $filter . ' '
					);
					break;

				case 'votes':
					$query->where('a.votes >= ' . $votesFilter . ' ');
					break;

				case 'title':
				default:
					$stopwords = array(
							"a", "about", "above", "above", "across", "after", "afterwards", "again", "against", "all", "almost", "alone", "along", "already", "also","although","always",
							"am","among", "amongst", "amoungst", "amount",  "an", "and", "another", "any","anyhow","anyone","anything","anyway", "anywhere", "are", "around", "as",  "at", 
							"back","be","became", "because","become","becomes", "becoming", "been", "before", "beforehand", "behind", "being", "below", "beside", "besides", "between", 
							"beyond", "bill", "both", "bottom","but", "by", "call", "can", "cannot", "cant", "co", "con", "could", "couldnt", "cry", "de", "describe", "detail", "do", "done", 
							"down", "due", "during", "each", "eg", "eight", "either", "eleven","else", "elsewhere", "empty", "enough", "etc", "even", "ever", "every", "everyone", "everything", 
							"everywhere", "except", "few", "fifteen", "fify", "fill", "find", "fire", "first", "five", "for", "former", "formerly", "forty", "found", "four", "from", "front", 
							"full", "further", "get", "give", "go", "had", "has", "hasnt", "have", "he", "hence", "her", "here", "hereafter", "hereby", "herein", "hereupon", "hers", "herself", 
							"him", "himself", "his", "how", "however", "hundred", "ie", "if", "in", "inc", "indeed", "interest", "into", "is", "it", "its", "itself", "keep", "last", "latter", 
							"latterly", "least", "less", "ltd", "made", "many", "may", "me", "meanwhile", "might", "mill", "mine", "more", "moreover", "most", "mostly", "move", "much", "must", 
							"my", "myself", "name", "namely", "neither", "never", "nevertheless", "next", "nine", "no", "nobody", "none", "noone", "nor", "not", "nothing", "now", "nowhere", "of", 
							"off", "often", "on", "once", "one", "only", "onto", "or", "other", "others", "otherwise", "our", "ours", "ourselves", "out", "over", "own","part", "per", "perhaps", 
							"please", "put", "rather", "re", "same", "see", "seem", "seemed", "seeming", "seems", "serious", "several", "she", "should", "show", "side", "since", "sincere", "six", 
							"sixty", "so", "some", "somehow", "someone", "something", "sometime", "sometimes", "somewhere", "still", "such", "system", "take", "ten", "than", "that", "the", "their", 
							"them", "themselves", "then", "thence", "there", "thereafter", "thereby", "therefore", "therein", "thereupon", "these", "they", "thickv", "thin", "third", "this", "those", 
							"though", "three", "through", "throughout", "thru", "thus", "to", "together", "too", "top", "toward", "towards", "twelve", "twenty", "two", "un", "under", "until", "up", 
							"upon", "us", "very", "via", "was", "we", "well", "were", "what", "whatever", "when", "whence", "whenever", "where", "whereafter", "whereas", "whereby", "wherein", 
							"whereupon", "wherever", "whether", "which", "while", "whither", "who", "whoever", "whole", "whom", "whose", "why", "will", "with", "within", "without", "would", "yet", 
							"you", "your", "yours", "yourself", "yourselves", "the");
					
					$keywords = array_diff(explode('-', $filter), $stopwords);
					$filters = array();
					
					foreach ($keywords as $keyword)
					{
						if(strlen($keyword) > 2)
						{
							$filters[] = 'LOWER( a.title ) LIKE '.$db->quote('%' . $db->escape($keyword, true) . '%', false);
						}
					}
					// Default to 'title' if parameter is not valid
					if(!empty($filters))
					{
						$query->where('('.implode(' OR ', $filters).')');
					}
					break;
			}
		}

		// Filter by language
		if ($this->getState('filter.language'))
		{
			$query->where('a.language in (' . $db->quote(JFactory::getLanguage()->getTag()) . ',' . $db->quote('*') . ')');
		}
		
		// do not show private polls to everyone
		if ( !$user->authorise('core.edit.state', 'com_communitypolls') && !$user->authorise('core.edit', 'com_communitypolls') )
		{
			$query->where('(a.private = 0 or a.created_by = '.$user->id.')');
		}

		// Add the list ordering clause.
		$query->order($this->getState('list.ordering', 'a.created') . ' ' . $this->getState('list.direction', 'DESC'));
// echo $query->dump();
		return $query;
	}

	public function getItems()
	{
		$items = parent::getItems();
		$user = JFactory::getUser();
		$userId = $user->get('id');
		$guest = $user->get('guest');
		$groups = $user->getAuthorisedViewLevels();
		$input = JFactory::getApplication()->input;

		// Get the global params
		$globalParams = JComponentHelper::getParams('com_communitypolls', true);

		// Convert the parameter fields into objects.
		foreach ($items as &$item)
		{
			$item->slug = $item->alias ? ($item->id . ':' . $item->alias) : $item->id;
			$item->parent_slug = ($item->parent_alias) ? ($item->parent_id . ':' . $item->parent_alias) : $item->parent_id;
				
			// No link for ROOT category
			if ($item->parent_alias == 'root')
			{
				$item->parent_slug = null;
			}

			$item->catslug = $item->category_alias ? ($item->catid . ':' . $item->category_alias) : $item->catid;
			
			$pollParams = new JRegistry;
			$pollParams->loadString($item->attribs);

			// Unpack readmore and layout params
			$item->alternative_readmore = $pollParams->get('alternative_readmore');
			$item->layout = $pollParams->get('layout');

			$item->params = clone $this->getState('params');
			$item->params->merge($pollParams);

			// Get display date
			switch ($item->params->get('list_show_date'))
			{
				case 'modified':
					$item->displayDate = $item->modified;
					break;

				case 'published':
					$item->displayDate = ($item->publish_up == 0) ? $item->created : $item->publish_up;
					break;

				default:
				case 'created':
					$item->displayDate = $item->created;
					break;
			}

			// Compute the asset access permissions.
			// Technically guest could edit a poll, but lets not check that to improve performance a little.
			if (!$guest)
			{
				$asset = 'com_communitypolls.poll.' . $item->id;

				// Check general edit permission first.
				if ($user->authorise('core.edit', $asset))
				{
					$item->params->set('access-edit', true);
				}

				// Now check if edit.own is available.
				elseif (!empty($userId) && $user->authorise('core.edit.own', $asset))
				{
					// Check for a valid user and that they are the owner.
					if ($userId == $item->created_by)
					{
						$item->params->set('access-edit', true);
					}
				}
			}

			$access = $this->getState('filter.access');

			if ($access)
			{
				// If the access filter has been set, we already have only the polls this user can view.
				$item->params->set('access-view', true);
			}
			else
			{
				// If no access filter is set, the layout takes some responsibility for display of limited information.
				if ($item->catid == 0 || $item->category_access === null)
				{
					$item->params->set('access-view', in_array($item->access, $groups));
				}
				else
				{
					$item->params->set('access-view', in_array($item->access, $groups) && in_array($item->category_access, $groups));
				}
			}

			// Get the tags, /* disabled to avoid firing 20 additional database queries on listing page.*/
// 			$item->tags = new JHelperTags;
// 			$item->tags->getItemTags('com_communitypolls.poll', $item->id);
		}

		return $items;
	}

	protected function _buildContentOrderBy()
	{
		$app		= JFactory::getApplication('site');
		$db			= $this->getDbo();
		$params		= $this->state->params;
		$itemid		= $app->input->get('id', 0, 'int') . ':' . $app->input->get('Itemid', 0, 'int');
		$orderCol	= $app->getUserStateFromRequest('com_communitypolls.category.list.' . $itemid . '.filter_order', 'filter_order', '', 'string');
		$orderDirn	= $app->getUserStateFromRequest('com_communitypolls.category.list.' . $itemid . '.filter_order_Dir', 'filter_order_Dir', '', 'cmd');
		$orderby	= ' ';
	
		if (!in_array($orderCol, $this->filter_fields))
		{
			$orderCol = null;
		}
	
		if (!in_array(strtoupper($orderDirn), array('ASC', 'DESC', '')))
		{
			$orderDirn = 'ASC';
		}
	
		if ($orderCol && $orderDirn)
		{
			$orderby .= $db->escape($orderCol) . ' ' . $db->escape($orderDirn) . ', ';
		}
	
		$pollOrderby		= $params->get('orderby_sec', 'rdate');
		$pollOrderDate	= $params->get('order_date');
		$categoryOrderby	= $params->def('orderby_pri', '');
		$secondary			= CommunityPollsHelperQuery::orderbySecondary($pollOrderby, $pollOrderDate) . ', ';
		$primary			= CommunityPollsHelperQuery::orderbyPrimary($categoryOrderby);
	
		$orderby .= $primary . ' ' . $secondary . ' a.created ';
	
		return $orderby;
	}
	
	public function getStart()
	{
		return $this->getState('list.start');
	}
}
