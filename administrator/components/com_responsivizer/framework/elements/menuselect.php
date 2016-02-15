<?php
//namespace components\com_responsivizer\framework\elements;
/**  
 * @package RESPONSIVIZER::components::com_responsivizer 
 * @subpackage framework
 * @subpackage elements
 * @author Joomla! Extensions Store
 * @copyright (C) 2014 - Joomla! Extensions Store
 * @license GNU/GPLv2 http://www.gnu.org/licenses/gpl-2.0.html   
 */ 
defined( '_JEXEC' ) or die( 'Restricted access' );
jimport('joomla.form.fields.list');

/**
 * Form Field for menu tree
 * @package RESPONSIVIZER::components::com_responsivizer 
 * @subpackage framework
 * @subpackage elements
 * @since 2.0
 */
class ResponsivizerElementsMenuselect extends JObject {
	/**
	 * Tree recursion menu
	 * 
	 * @access private 
	 * @param int $id
	 * @param string $indent
	 * @param array $list
	 * @param array $children
	 * @param int $maxlevel
	 * @param int $level
	 * @param int $type
	 * @return array
	 */
	private static function treeRecurse($id, $indent, $list, &$children, $maxlevel = 9999, $level = 0, $type = 1) {
		if (@$children [$id] && $level <= $maxlevel) {
			foreach ( $children [$id] as $v ) {
				$id = $v->id;
	
				if ($type) {
					$pre = '<sup>|_</sup>&nbsp;';
					$spacer = '.&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;';
				} else {
					$pre = '- ';
					$spacer = '&nbsp;&nbsp;';
				}
	
				if ($v->parent == 0) {
					$txt = $v->name;
				} else {
					$txt = $pre . $v->name;
				}
				$pt = $v->parent;
				$list [$id] = $v;
				$list [$id]->treename = "$indent$txt";
				$list [$id]->children = count ( @$children [$id] );
				$list = self::TreeRecurse ( $id, $indent . $spacer, $list, $children, $maxlevel, $level + 1, $type );
			}
		}
		return $list;
	}
	
	/**
	 * Build the multiple select list for Menu Links/Pages
	 * 
	 * @static
	 * @access public
	 * @param int $currentOption
	 * @return array
	 */
	public static function getOptions($currentOption) {
		$db = JFactory::getDBO ();
		
		// get a list of the menu items, all menus not already cached by other records, but including menu cached by this record and selected in dropdown
		$query = "SELECT m.id, m.parent_id AS parent, m.title AS name, m.menutype, t.title, cached.itemid, cached.deleted" .
				 "\n FROM #__menu AS m" .
				 "\n INNER JOIN #__menu_types AS t" .
				 "\n ON m.menutype = t.menutype" .
				 "\n LEFT JOIN #__rvzr_offline_urls AS cached" .
				 "\n ON m.id = cached.itemid" .
				 "\n WHERE m.published = 1" .
				 "\n AND m.client_id = 0" .
				 "\n AND m.type = " . $db->quote('component') .
				 "\n ORDER BY m.menutype, m.parent_id, m.lft";
		$db->setQuery ( $query );
		$mitems = $db->loadObjectList ();
		$mitems_temp = $mitems;
		
		if(empty($mitems)) {
			return $mitems;
		}
		
		// establish the hierarchy of the menu
		$children = array ();
		// first pass - collect children
		foreach ( $mitems as $v ) {
			$id = $v->id;
			$pt = $v->parent;
			$list = @$children [$pt] ? $children [$pt] : array ();
			array_push ( $list, $v );
			$children [$pt] = $list;
		}
		// second pass - get an indent list of the items
		$list = self::treeRecurse ( intval ( $mitems [0]->parent ), '', array (), $children, 9999, 0, 0 );
		
		// Code that adds menu name to Display of Page(s)
		$mitems_spacer = $mitems_temp [0]->menutype;
		
		$mitems = array ();
		$lastMenuType = null;
		$tmpMenuType = null;
		foreach ( $list as $list_a ) {
			if ($list_a->menutype != $lastMenuType) {
				if ($tmpMenuType) {
					$mitems [] = JHTML::_ ( 'select.option', '</OPTGROUP>' );
				}
				$mitems [] = JHTML::_ ( 'select.option', '<OPTGROUP>', $list_a->title );
				$lastMenuType = $list_a->menutype;
				$tmpMenuType = $list_a->menutype;
			}
			
			// Exclude other cached resources
			if($list_a->itemid && $list_a->deleted == 0 && $list_a->itemid != $currentOption) {
				continue;
			}
			$mitems [] = JHTML::_ ( 'select.option', $list_a->id, $list_a->treename );
		}
		if ($lastMenuType !== null) {
			$mitems [] = JHTML::_ ( 'select.option', '</OPTGROUP>' );
		}
			
		return $mitems;
	}
}