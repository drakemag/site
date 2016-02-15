<?php
	// DEVELOPMENT VARIABLES
		$user =& JFactory::getUser();

		$itemid = JRequest::getVar('Itemid');
		$menu = &JSite::getMenu();
		$active = $menu->getItem($itemid);
		$params = $menu->getParams( $active->id );
		$pageclass = $params->get( 'pageclass_sfx' );
		
		$doc = JFactory::getDocument(); 
			
	// GET PAGE CLASS
		//$pageclass = $params->get( 'pageclass_sfx' );
		  
	// GETTING ARTICLE & CATEGORY IDs
		$db =& JFactory::getDBO();
		if(JRequest::getVar('view')=='article'){
			$query = "SELECT catid FROM #__content WHERE id='".JRequest::getVar('id')."'";
			$db->setQuery($query);
			$categoryId = $db->loadResult();
		}
		elseif(JRequest::getVar('view')=='category'){
			$query = "SELECT catid FROM #__content WHERE catid='".JRequest::getVar('id')."'";
			$db->setQuery($query);
			$categoryId = $db->loadResult();
		} else {
			$categoryId = "homepage";
		}
	if((JRequest::getVar('view')=='article') || (JRequest::getVar('view')=='category')){
		$query = "SELECT parent_id FROM #__categories WHERE id=$categoryId";
		$db->setQuery($query);
		$parentId = $db->loadResult();
	}
	
	// UNSETTING JOOMLA SCRIPTS
		if (isset($this->_script['text/javascript']))
		{
		   $this->_script['text/javascript'] = preg_replace('%window\.addEvent\(\'load\',\s*function\(\)\s*{\s*new\s*JCaption\(\'img.caption\'\);\s*}\);\s*%', '', $this->_script['text/javascript']);
		   if (empty($this->_script['text/javascript']))
			  unset($this->_script['text/javascript']);
		}
		unset($this->_scripts['/media/system/js/core.js']);
		// remove script and tags, needed since 2.5 update
		unset($this->_scripts['/media/system/js/caption.js']);
		$this->_script = preg_replace('%window\.addEvent\(\'load\',\s*function\(\)\s*{\s*new\s*JCaption\(\'img.caption\'\);\s*}\);\s*%', '', $this->_script);
		if (empty($this->_script['text/javascript'])) unset($this->_script['text/javascript']);
?>