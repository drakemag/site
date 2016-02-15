<?php
/**
 * @version		$Id: komento_plugin.php 01 2011-08-13 11:37:09Z maverick $
 * @package		CoreJoomla.Polls
 * @subpackage	Components
 * @copyright	Copyright (C) 2009 - 2011 corejoomla.com. All rights reserved.
 * @author		Maverick
 * @link		http://www.corejoomla.com/
 * @license		License GNU General Public License version 2 or later
 */

// no direct access
defined('_JEXEC') or die('Restricted access');

// Always load abstract class
require_once( JPATH_ROOT.'/components/com_komento/komento_plugins/abstract.php' );

class KomentoComCommunitypolls extends KomentoExtension
{
	public $_item;
	public $component = 'com_communitypolls';

	public $_map = array(
			'id'            => 'id',
			'title'         => 'title',
			'hits'          => 'votes',
			'created_by'    => 'created_by',
			'catid'         => 'catid'
	);

	public function __construct( $component )
	{
		parent::__construct( $component );
	}
	
	public function load( $cid )
	{
		static $instances = array();

		if( !isset( $instances[$cid] ) )
		{
			$db		= JFactory::getDbo();
			
			$query	= '
					select 
						a.id, a.title, a.alias, a.catid, a.created_by, a.votes, a.language,
						c.title AS category_title, c.alias AS category_alias,
						u.name AS author
					from 
						#__jcp_polls AS a
					left join
						#__categories AS c ON c.id = a.catid
					left join
						#__users AS u ON u.id = a.created_by
					where 
						a.id = ' . $db->quote( (int) $cid );
			
			$db->setQuery( $query );

			if( !$result = $db->loadObject() )
			{
				return false;
			}

			$instances[$cid] = $result;
		}

		$this->_item = $instances[$cid];

		return $this;
	}

	public function getContentIds( $categories = '' )
	{
		$db = JFactory::getDbo();
		$query = '';

		if( empty( $categories ) ){
			
			$query = 'select `id` from #__jcp_polls ORDER BY `id`';
		} else {
			
			if( is_array( $categories ) )
			{
				$categories = implode( ',', $categories );
			}

			$query = 'select `id` from #__jcp_polls ORDER BY `id` where `catid` in (' . $categories . ') ORDER BY `id`';
		}

		$db->setQuery( $query );
		
		return $db->loadColumn();
	}

	public function getCategories()
	{
		return JHtml::_('category.categories', 'com_communitypolls');
	}

	// to determine if is listing view
	public function isListingView()
	{
		$app = JFactory::getApplication();
		return ($app->input->getCmd('view') == 'polls');
	}

	// to determine if is entry view
	public function isEntryView()
	{
		$app = JFactory::getApplication();
		return ($app->input->getCmd('view') == 'poll');
	}

	public function onExecute( &$poll, $html, $view, $options = array() )
	{
		return $html;
	}
	
	public function getEventTrigger()
	{
		return false;
	}
	
	public function getContentPermalink()
	{
		require_once JPATH_ROOT.'/components/com_communitypolls/helpers/route.php';
		
		$slug = $this->_item->alias ? ($this->_item->id.':'.$this->_item->alias) : $this->_item->id;
		$catslug = $this->_item->category_alias ? ($this->_item->catid.':'.$this->_item->category_alias) : $this->_item->catid;
		
		return $this->prepareLink( CommunityPollsHelperRoute::getPollRoute($slug, $catslug, $this->_item->language));
	}
}