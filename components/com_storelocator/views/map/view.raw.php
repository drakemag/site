<?php
/**
 * @version     2.0.0
 * @package     com_storelocator
 * @copyright   Copyright (C) 2013. Sysgen Media LLC. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 * @author      Sysgen Media <support@sysgenmedia.com> - http://www.sysgenmedia.com
 */

// No direct access
defined('_JEXEC') or die;

jimport('joomla.application.component.view');

/**
 * View class for a list of Storelocator.
 */
class StorelocatorViewMap extends JViewLegacy
{
	
	
	protected $item;
	
	/**
	 * Display the view
	 */
	public function display($tpl = null)
	{
		
		// Check for errors.
		if (count($errors = $this->get('Errors')))
		{
			JError::raiseError(500, implode('<br />', $errors));
			return false;
		}
	
		
		$app 		= JFactory::getApplication('site');
		$jinput 	= JFactory::getApplication()->input;
		$params 	= $app->getParams('com_storelocator');
		$model		= $this->getModel();
		
		// Get the parameters of the active menu item
		$menuitemid = $jinput->get( 'Itemid' );
		if ($menuitemid)
		{
			$menus = $app->getMenu();
			$menu    = $menus->getActive();
			$menuparams = $menus->getParams( $menuitemid );
			$params->merge( $menuparams );
		}
		
		// Get System / Menu Params
		$max_search_results = max($params->get('max_search_results', 100), 1);
		$map_units 			= $params->get( 'map_units', 1 );
		$load_order 		= $params->get('load_order', 'lp.name');
		$search_order 		= $params->get('search_order', 'distance');
		$load_dir 			= $params->get('load_dir', 'ASC');
		$search_dir 		= $params->get('search_dir', 'ASC');
		$tagModeAND 		= (int)$params->get('tagmode_and', 0);
		$nameSearchMode  	= (int)$params->get('namesearchmode', 0);
		$marker_pref 		= $params->get('marker_pref', 'tags');	
		
		// Get parameters from URL
		$searchall = 	(bool)$jinput->get->get('searchall', 0);
		$center_lat = 	$jinput->get->get('lat', '0');
		$center_lng = 	$jinput->get->get('lng', '0');
		$radius = 		$jinput->get->get('radius', '25');
		$catid = 		$jinput->get->get('catid', '-1');
		$tagid = 		$jinput->get->get('tagid', '-1');
		$featstate = 	$jinput->get->get('featstate', '0');
		$query = 		$jinput->get->get('query', '');
		$name_search =	$jinput->get->get('name_search', '', 'STRING');
		
		if ($tagid == -1)
			$tagModeAND = false;
		
		
		
		// Get Searchable Categories
		$catReq = array();
		
		if (!$params->get('show_all', 1))
		{
			if ($allowcats = $params->get( 'categories', 0 ))
			{
				if (is_array($allowcats))	
					$catReq = $allowcats;
				else
					$catReq[] = $allowcats;
			}
		}

		// Cat Search Filter
		if (is_numeric($catid) && $catid > 0)
			$categories = array($catid);
		else if (stripos($catid,'_')!==false)
			$categories = explode('_',$catid);
		else
			$categories = $catReq;
			
			
			
			
		// Tag Filter
		$tagReq = array();		
		if (!$params->get('show_all_tags', 1))
		{
			if ($tagallow = $params->get( 'tags', 0 ))
			{
				if (is_array($tagallow))	
					$tagReq = $tagallow;
				else
					$tagReq[] = $tagallow;
			}
		}
		
			
		// Tag Search Filter
		if (is_numeric($tagid) && $tagid > 0)
			$tags = array($tagid);
		else if (stripos($tagid,'_')!==false)
			$tags = explode('_',$tagid);
		else
			$tags = $tagReq;
		
		
		
		
		// If Logging Enabled, Save Search to Log
		$searchlog_enabled = (int)$params->get('searchlog_enabled', 0);
		$search_limit = (int)$params->get('search_limit', 0);
		$search_limit_period = $params->get('search_limit_period', '-1 Day');
		$limited = false;
		
		if($searchlog_enabled && !$searchall && $search_limit > 0)
		{
			$limited = $model->logSearch(  	$search_limit, 
											$search_limit_period, 
											$center_lat, 
											$center_lng,
											array( 	'center_lat' => $center_lat, 
													'center_lng' => $center_lng, 
													'radius' => $radius, 
													'categories' => $categories, 
													'tags' => $tags, 
													'featstate' => $featstate, 
													'name_search' => $name_search));	
		}
		
		
		
		if(!$limited)
		{
			if($searchall)
				$results = $model->getAllDataByCat($categories, $tags, $map_units, $max_search_results, $load_order, $load_dir, $featstate, $tagModeAND);
			else if (!empty($name_search) && $center_lat == '0' && $center_lng == '0')
				$results = $model->getAllDataByName($name_search, $categories, $tags, $map_units, $max_search_results, $search_order, $search_dir, $featstate, $tagModeAND, $nameSearchMode);
			else
				$results = $model->getDataByLocation($center_lat, $center_lng, $radius, $categories, $tags, $map_units, $max_search_results, $search_order, $search_dir, $featstate, $name_search, $tagModeAND, $nameSearchMode);
		}
		
		header( "Content-type: text/xml; charset=utf-8" );
		echo "<?xml version=\"1.0\" encoding=\"utf-8\"?>";
				
		echo "\n\t<markers>";
		
		if ($limited)
			echo "\n\t<limited>1</limited>";				
		else
			echo "\n\t\t<limited>0</limited>";
		
		if( count( $results ) ) {
			
			// We have results, load the Tag Map
			$tagMap = array();
			$tagMapObj = $model->getTags();
			
			if ($tagMapObj && count($tagMapObj))
				foreach($tagMapObj as $obj)
					$tagMap[$obj->value] = $obj->marker_url;
			
					
			foreach( $results as $item ) {
								
				$markertype = $item->markertype;
				
				
				// Apply Tag Preference if Desired
				if ($marker_pref == 'tags' && !empty($tagMap) && !empty($item->tags) && $item->tags != -1 )
				{
					$item_tags = explode(',', $item->tags);
					if (count($tags)) // Searched by Tags
					{
							
						foreach($item_tags as $tag)
						{
							if ( in_array($tag, $tags) )
							{
								$markertype = $tagMap[$tag];
								break;
							}
						}
					} else {  // Not Searching by tags, but stil lwant first tag icon
					
						$markertype = $tagMap[$item_tags[0]];
					}
				}
				
				if (!empty($markertype) && stripos($markertype, 'http') === false)
					$markertype = JURI::root().$markertype;
							
				echo "\n\t\t<marker>";
				
				echo "\n\t\t\t<name>".$this->XMLClean($item->name)."</name>";
				echo "\n\t\t\t<category>".$this->XMLClean($item->categories_name)."</category>";
				echo "\n\t\t\t<markertype>".$this->XMLClean( $markertype )."</markertype>";
				echo "\n\t\t\t<featured>".($item->featured?'true':'false')."</featured>";
				echo "\n\t\t\t<address>".$this->XMLClean($item->address)."</address>";
				echo "\n\t\t\t<lat>".doubleval($item->lat)."</lat>";
				echo "\n\t\t\t<lng>".doubleval($item->long)."</lng>";
				echo "\n\t\t\t<distance>".doubleval($item->distance)."</distance>";
				echo "\n\t\t\t<fulladdress><![CDATA[".$item->description."]]></fulladdress>";
				echo "\n\t\t\t<phone>".$this->XMLClean($item->phone)."</phone>";
				echo "\n\t\t\t<url>".$this->XMLClean($item->website)."</url>";
				echo "\n\t\t\t<email>".$this->XMLClean($item->email)."</email>";
				echo "\n\t\t\t<facebook>".$this->XMLClean($item->facebook)."</facebook>";
				echo "\n\t\t\t<twitter>".$this->XMLClean($item->twitter)."</twitter>";
				echo "\n\t\t\t<tags><![CDATA[".$this->XMLClean($item->tags_name)."]]></tags>";
				echo "\n\t\t\t<custom1 name=\"".$params->get( 'cust1_label' )."\"><![CDATA[".$this->XMLClean($item->cust1)."]]></custom1>";
				echo "\n\t\t\t<custom2 name=\"".$params->get( 'cust2_label' )."\"><![CDATA[".$this->XMLClean($item->cust2)."]]></custom2>";
				echo "\n\t\t\t<custom3 name=\"".$params->get( 'cust3_label' )."\"><![CDATA[".$this->XMLClean($item->cust3)."]]></custom3>";
				echo "\n\t\t\t<custom4 name=\"".$params->get( 'cust4_label' )."\"><![CDATA[".$this->XMLClean($item->cust4)."]]></custom4>";
				echo "\n\t\t\t<custom5 name=\"".$params->get( 'cust5_label' )."\"><![CDATA[".$this->XMLClean($item->cust5)."]]></custom5>";
			
	
				
				echo "\n\t\t</marker>";
			}
		}

		echo "\n\t</markers>";
		exit;

	}
	
	protected function XMLClean($strin) 
	{
        $strout = null;

		for ($i = 0; $i < strlen($strin); $i++) {
				$ord = ord($strin[$i]);

				if (($ord > 0 && $ord < 32)) {
						$strout .= "&amp;#{$ord};";
				}
				else {
						switch ($strin[$i]) {
								case '<':
										$strout .= '&lt;';
										break;
								case '>':
										$strout .= '&gt;';
										break;
								case '&':
										$strout .= '&amp;';
										break;
								case '"':
										$strout .= '&quot;';
										break;
								default:
										$strout .= $strin[$i];
						}
				}
		}

		return $strout;
	}
	
	protected function getMarkerAsXML()
	{		
		$item = $this->item;
		
		$dom = new DOMDocument('1.0', 'UTF-8');
		$node = $dom->createElement("markers");
		$parnode = $dom->appendChild($node);
		
		$document = JFactory::getDocument();
		$document->setMimeEncoding('text/xml');
		
		$node = $dom->createElement("marker");
		$newnode = $parnode->appendChild($node);
		$newnode->setAttribute("name",$item->title);
		$newnode->setAttribute("description", $item->description);
		$newnode->setAttribute("lat", $item->latitude);
		$newnode->setAttribute("lng", $item->longitude);
		$newnode->setAttribute("catid", $item->catid);
		$newnode->setAttribute("id", $item->id);
		//$newnode->setAttribute("photos", $item->photos);
		
		echo $dom->saveXML();
	
	}	
    	
}
