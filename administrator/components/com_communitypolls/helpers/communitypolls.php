<?php
/**
 * @version		$Id: helper.php 01 2012-06-30 11:37:09Z maverick $
 * @package		CoreJoomla.Pollss
 * @subpackage	Components
 * @copyright	Copyright (C) 2009 - 2012 corejoomla.com. All rights reserved.
 * @author		Maverick
 * @link		http://www.corejoomla.com/
 * @license		License GNU General Public License version 2 or later
 */

// No direct access to this file
defined('_JEXEC') or die;

class CommunityPollsHelper
{
	public static $extension = 'com_communitypolls';
	
	public static function addSubmenu($view){
		
		$option = JApplicationHelper::getComponentName();

		JSubMenuHelper::addEntry(JText::_('COM_COMMUNITYPOLLS_MENU_DASHBOARD'), 'index.php?option=com_communitypolls&view=dashboard', $view == 'dashboard');
		JSubMenuHelper::addEntry(JText::_('COM_COMMUNITYPOLLS_MENU_POLLS'), 'index.php?option=com_communitypolls&view=polls', $view == 'polls');
		JSubMenuHelper::addEntry(JText::_('COM_COMMUNITYPOLLS_MENU_CATEGORIES'), 'index.php?option=com_categories&view=categories&extension=com_communitypolls', ($view == 'categories' && $option != 'com_communitypolls'));
		JSubMenuHelper::addEntry(JText::_('COM_COMMUNITYPOLLS_MENU_USERS'), 'index.php?option=com_communitypolls&view=users', $view == 'users');
	}
	
	public static function getActions($categoryId = 0, $pollId = 0)
	{
		// Reverted a change for version 2.5.6
		$user	= JFactory::getUser();
		$result	= new JObject;
		
		if (empty($pollId) && empty($categoryId)) {
			$assetName = 'com_communitypolls';
		}
		elseif (empty($pollId)) {
			$assetName = 'com_communitypolls.category.'.(int) $categoryId;
		}
		else {
			$assetName = 'com_communitypolls.poll.'.(int) $pollId;
		}
		
		$actions = array(
				'core.admin', 'core.manage', 'core.create', 'core.edit', 'core.edit.own', 'core.edit.state', 'core.delete'
		);
		
		foreach ($actions as $action) {
			$result->set($action, $user->authorise($action, $assetName));
		}
		
		return $result;
	}
	
	public static function getCurrentLanguage($detectBrowser = true)
	{
		$app = JFactory::getApplication();
		$langCode = $app->input->cookie->getString(JApplicationHelper::getHash('language'));

		// No cookie - let's try to detect browser language or use site default
		if (!$langCode)
		{
			if ($detectBrowser)
			{
				$langCode = JLanguageHelper::detectLanguage();
			}
			else
			{
				$langCode = JComponentHelper::getParams('com_languages')->get('site', 'en-GB');
			}
		}

		return $langCode;
	}

	public static function getLanguageId($langCode)
	{
		$db    = JFactory::getDbo();
		$query = $db->getQuery(true)
			->select('lang_id')
			->from('#__languages')
			->where($db->quoteName('lang_code') . ' = ' . $db->quote($langCode));
		$db->setQuery($query);

		$id = $db->loadResult();

		return $id;
	}

	public function getRowData(JTable $table)
	{
		$data = new JHelper;

		return $data->getRowData($table);
	}
	
	public static function grab_dump($var){
		ob_start();
		var_dump($var);
		return ob_get_clean();
	}
	
	public static function process_html($content){
	
		$params = JComponentHelper::getComponent('com_communitypolls');
	
		return CJFunctions::process_html($content, ($params->get('default_editor', 'bbcode') == 'bbcode'));
	}
	
	/**
	 * Does the third party activities needed after poll creation/vote casting.
	 * Currently supporting Alpha User Points points submission,
	 * JomSocial Points submission, JomSocial activity stream and
	 * Twitter updates using shortened urls from bit.ly and tinyurl.com services.
	 *
	 * @global <type> $params
	 * @param <type> $action
	 * @param <type> $poll
	 */
	public static function do3rdPartyTask($action, $poll, $user=null) 
	{
		$params = JComponentHelper::getParams('com_communitypolls');
		
		CommunityPollsHelper::awardPoints($params, $action, $poll, $user);
		CommunityPollsHelper::awardBadges($params, $action, $poll, $user);
		CommunityPollsHelper::streamActivity($params, $action, $poll, $user);
		
		// Twitter Tweets Start
		if($params->get('enable_twitter', 0) == "1")
		{
			CommunityPollsHelper::sendToTwitter($poll);
		}
	}
		
	private static function awardPoints($params, $action, $poll, $user=null)
	{
		$points_system = $params->get('points_component', 'none');
		$user = !$user ? JFactory::getUser() : $user;
		$functions = array();
		
		if($points_system == 'none')
		{
			return false;
		}
		
		switch ($points_system)
		{
			case 'cjforum':
				$functions = array('polls'=>'com_communitypolls.poll', 'votes'=>'com_communitycore.vote', 'author'=>'com_communitypolls.author');
				break;
			
			case 'cjblog':
			case 'jomsocial':
			case 'easysocial':
			case 'touch':
				$functions = array('polls'=>'com_communitypolls.newpoll', 'votes'=>'com_communitycore.votes', 'author'=>'com_communitypolls.author');
				break;
		
			case 'aup':
				$functions = array('polls'=>'plgaup_communitypolls_polls', 'votes'=>'plgaup_communitypolls_votes', 'author'=>'plgaup_communitypolls_author');
				break;
		}
		
		switch ($action)
		{
			case 'POLL':
				
				CJFunctions::award_points($points_system, $user->id, array(
					'points'=>$params->get('points_for_polls'),
					'reference'=>$poll->id,
					'info'=>'Poll: '.$poll->id,
					'function'=>$functions['polls'],
					'component'=>'com_communitypolls'
				));
				break;
				
			case 'VOTE':
				
				CJFunctions::award_points($points_system, $user->id, array(
					'points'=>$params->get('points_for_votes'),
					'reference'=>'',
					'info'=>'Poll: '.$poll->id,
					'function'=>$functions['votes'],
					'component'=>'com_communitypolls'
				));
				
				CJFunctions::award_points($points_system, $poll->created_by, array(
					'points'=>$params->get('points_for_votes_owner'),
					'reference'=>'',
					'info'=>'Poll: '.$poll->id,
					'function'=>$functions['author'],
					'component'=>'com_communitypolls'
				));
				break;
		}
	}
	
	private static function awardBadges($params, $action, $poll, $user=null)
	{
		$badge_system = $params->get('badge_system', 'none');
		$user = !$user ? JFactory::getUser() : $user;
		
		switch ($action)
		{
			case 'POLL':
				
				if(!$user->guest && $badge_system == 'cjblog' && file_exists(JPATH_ROOT.'/components/com_cjblog/api.php'))
				{
					require_once JPATH_ROOT.'/components/com_cjblog/api.php';
				
					$db = JFactory::getDbo();
					$query = $db->getQuery(true)->select('polls, votes')->from('#__jcp_users')->where('id = '.$user->id);
					$db->setQuery($query);
					$my = $db->loadObject();
				
					// trigger badges rule
					CjBlogApi::trigger_badge_rule('com_communitypolls.num_polls', array('num_polls'=>$my->polls, 'ref_id'=>$poll->id), $user->id);
				}
				break;
				
			case 'VOTE':
				
				if($badge_system == 'cjblog' && file_exists(JPATH_ROOT.'/components/com_cjblog/api.php'))
				{
					require_once JPATH_ROOT.'/components/com_cjblog/api.php';
				
					if(!$user->guest)
					{
						$db = JFactory::getDbo();
						$query = $db->getQuery(true)->select('polls, votes')->from('#__jcp_users')->where('id = '.$user->id);
						$db->setQuery($query);
						$my = $db->loadObject();
				
						// trigger badges rule
						CjBlogApi::trigger_badge_rule('com_communitypolls.num_votes', array('num_votes'=>$my->votes, 'ref_id'=>$poll->id), $user->id);
					}
						
					CjBlogApi::trigger_badge_rule('com_communitypolls.num_poll_votes', array('num_votes'=>$poll->votes, 'ref_id'=>$poll->votes), $poll->created_by);
				}
				
				break;
		}
	}
	
	private static function streamActivity($params, $action, $poll, $user=null)
	{
		$component = $params->get('stream_component', 'none');
		$profile = $params->get('avatar_component', 'none');
		$user = !$user ? JFactory::getUser() : $user;
		
		$slug = $poll->alias ? ($poll->id.':'.$poll->alias) : $poll->id;
		$catslug = $poll->category_alias ? ($poll->catid.':'.$poll->category_alias) : $poll->catid;
		$link = CommunityPollsHelperRoute::getPollRoute($slug, $catslug, $poll->language);
		
    	$title = null;
    	$command = null;
    	$description = '';
    	
    	switch ($component)
    	{
    		case 'jomsocial':
    			
    			switch ($action)
    			{
    				case 'POLL': // New poll
    					$title = JText::sprintf('COM_COMMUNITYPOLLS_STREAM_NEW_POLL', '{actor}', $link, $poll->title);
    					$command = 'com_communitypolls.polls';
    					break;
    					
    				case 'VOTE': // New vote
    					$title = JText::sprintf('COM_COMMUNITYPOLLS_STREAM_NEW_VOTE', '{actor}', $link, $poll->title);
    					$command = 'com_communitypolls.votes';
    					break;
    			}
    			
    			break;
    			
    		case 'easysocial':
    			
    			$username = CJFunctions::get_user_profile_url($profile, $user->id, $user->name, false);
    			
    			switch ($action)
    			{
    				case 'POLL': // New poll
    					$title = JText::sprintf('COM_COMMUNITYPOLLS_STREAM_NEW_POLL', $username, $link, $poll->title);
    					$command = 'com_communitypolls.new_poll';
    					break;

    				case 'VOTE': // New vote
    					$title = JText::sprintf('COM_COMMUNITYPOLLS_STREAM_NEW_VOTE', $username, $link, $poll->title);
    					$command = 'com_communitypolls.new_vote';
    					break;
    			}

    			break;
    	}
    	
    	if(!empty($title) && !empty($command))
    	{
	    	CJFunctions::stream_activity(
	    		$component,
	    		$user->id,
	    		array(
	    			'command' => $command,
	    			'component' => 'com_communitypolls',
	    			'title' => $title,
	    			'href' => $link,
	    			'description' => $poll->description,
	    			'length' => $params->get('stream_character_limit', 256),
	    			'icon' => 'media/com_communitypolls/images/polls.png',
	    			'group' => 'Polls',
	    			'item_id' => $poll->id,
	    			'context' => 'polls'
	    		)
	    	);
    	}
	}
	
	public static function sendToTwitter($poll) 
	{
		require_once(CJLIB_PATH.'/twitter/twitter.php');

		try{

			$user = JFactory::getUser();
			$params = JComponentHelper::getParams('com_communitypolls');
			$username = $params->get('user_display_name');
			$itemid = CJFunctions::get_active_menu_id();

			$poll_url =  JRoute::_('index.php?option=com_communitypolls&view=polls&task=viewpoll&id='.$poll->id.':'.$poll->alias.$itemid, false, -1);
			$poll_url = CommunityPollsHelper::getShortenedUrl($poll_url);

			$twitter = new Twitter($params->get('twitter_consumer_key'), $params->get('twitter_consumer_secret'));
			$twitter->setOAuthToken($params->get('twitter_oauth_token'));
			$twitter->setOAuthTokenSecret($params->get('twitter_oauth_secret'));

			$twi_text = JText::sprintf('COM_COMMUNITYPOLLS_NEW_POLL_TWEET_TEXT', $user->$username, $poll_url, $poll->title);
			$twi_text = substr($twi_text, 0, 140);

			$twitter->statusesUpdate($twi_text);
		}catch (TwitterException $e){

			//JFactory::getApplication()->enqueueMessage(print_r($e, true));
			return false;
		}
	}
	
	public static function getShortenedUrl($url) 
	{
		$params = JComponentHelper::getParams('com_communitypolls');
		$shortUrl;
	
		switch($params->get('short_url_service')) 
		{
			case 'none':
	
				return $url;
	
			case 'bit.ly':
	
				require_once CJLIB_PATH.'/twitter/bitly.php';
	
				$bitly = new Bitly($params->get('bit_ly_login'), $params->get('bit_ly_api_key'));
	
				try 
				{
					$shortUrl = $bitly->shorten($url);
				}
				catch (BitlyException $e ) 
				{
					return false;
				}
	
				break;
	
			case 'tinyurl':
	
				require_once CJLIB_PATH.'/twitter/tinyurl.php';
	
				$tinyUrl = new TinyUrl();
				$shortUrl = $tinyUrl->create($url);
	
				break;
	
			default:
	
				$shortUrl = $url;
	
				break;
		}
	
		return $shortUrl;
	}
	
	public static function load_editor($id, $name, $html, $rows, $cols, $width=null, $height=null, $class=null, $style=null)
	{
		$params = JComponentHelper::getParams('com_communitypolls');
		$editor = $user->authorise('core.wysiwyg', 'com_communitypolls') ? ( $params->get('default_editor', 'bbcode') == 'bbcode' ? 'bbcode' : 'wysiwyg') : 'none';
	
		return CJFunctions::load_editor($editor, $id, $name, $html, $rows, $cols, $width, $height, $class, $style);
	}
	
	public static function getPoweredByLink() 
	{
		$poweredby = '<div style="text-align: center; width: 100%; font-family: arial; font-size:9px; font-color: #ccc;">' . JText::_('POWERED_BY') . ' <a href="http://www.corejoomla.com" alt="CoreJoomla">Community Polls</a></div>';
		return $poweredby;
	}
	
	public static function getMessages($poll, $params)
	{
		$messages = array();
		
		if ( $poll->publish_results == false )
		{
			if(!empty($poll->results_up) && $poll->results_up != '0000-00-00 00:00:00')
			{
				$publish_date = JHtml::_('date', $poll->results_up, JText::_('DATE_FORMAT_LC2'));
				$messages[] = JText::sprintf('COM_COMMUNITYPOLLS_MESSAGE_POLL_RESULTS_UNAVAILABLE', $publish_date);
			}
			else
			{
				$messages[] = JText::_('COM_COMMUNITYPOLLS_MESSAGE_POLL_RESULTS_HIDDEN');
			}
		}
			
		if( $poll->eligible == 2 )
		{
			$messages[] = JText::_('COM_COMMUNITYPOLLS_MESSAGE_ALREADY_VOTED');
		}
		elseif( $poll->eligible == 3 )
		{
			$messages[] = JText::_('COM_COMMUNITYPOLLS_MESSAGE_NOT_ELIGIBLE_TO_VOTE');
		}

		if($poll->closed)
		{
			$messages[] = JText::_('COM_COMMUNITYPOLLS_MESSAGE_POLL_CLOSED');
		}
		else if (!empty($poll->close_date) && $poll->close_date != '0000-00-00 00:00:00')
		{
			$messages[] = JText::sprintf('COM_COMMUNITYPOLLS_MESSAGE_POLL_CLOSED_DATE', CJFunctions::get_formatted_date($poll->close_date));
		}
			
		if( $poll->private == 1 )
		{
			$pollUri = CommunityPollsHelperRoute::getPollRoute($poll->slug, $poll->catslug, $poll->language);
			$secretUrl = JRoute::_($pollUri.'&secret='.$poll->secret, true, -1);
		
			$messages[] = JText::sprintf('COM_COMMUNITYPOLLS_MESSAGE_SECRET_URL', $secretUrl);
		}
		
		if( $poll->eligible == 2 && !empty($poll->end_message))
		{
			$bbcode	 = $params->get('default_editor', 'none') == 'wysiwygbb' ? true : false;
			$messages[] = CJFunctions::parse_html($poll->end_message, false, $bbcode, false);
		}
		
		return $messages;
	}
}