<?php
/**
 * Categories Element for StoreLocator Component
 * 
 * @package    SysgenMedia.StoreLocator
 * @subpackage Components
 * @copyright	Copyright (c)2011 Sysgen Media LLC. All Rights Reserved.
 * @license		GNU/GPLv3
 */

// Check to ensure this file is included in Joomla!
defined('_JEXEC') or die( 'Restricted access' );

// import the list field type
jimport('joomla.form.helper');
JFormHelper::loadFieldClass('list');

class JFormFieldSltags extends JFormFieldList
{
	/**
	 * Element name
	 *
	 * @access	protected
	 * @var		string
	 */
	public	$type = 'SLTags';
	
	/**
	 * Method to get a list of options for a list input.
	 *
	 * @return	array		An array of JHtml options.
	 */
	protected function getOptions() 
	{
		$db = JFactory::getDBO();
		$query = $db->getQuery(true);
		$query->select('id,name');
		$query->from('#__storelocator_tags');
		$db->setQuery((string)$query);
		$tags = $db->loadObjectList();
		$options = array();
		if ($tags)
		{
			foreach($tags as $tag) 
			{
				$options[] = JHtml::_('select.option', $tag->id, $tag->name);
			}
		}
		$options = array_merge(parent::getOptions(), $options);
		return $options;
	}
}
