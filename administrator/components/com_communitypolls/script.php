<?php
/**
 * @version		$Id: script.php 74 2014-02-28 20:04:22Z maverick $
 * @package		CoreJoomla.Polls
 * @subpackage	Components
 * @copyright	Copyright (C) 2009 - 2014 corejoomla.com, Inc. All rights reserved.
 * @author		Maverick
 * @link		http://www.corejoomla.com/
 * @license		License GNU General Public License version 2 or later
 */
// No direct access to this file
defined('_JEXEC') or die;

/**
 * Script file of CommunityPolls component
 */
class com_communitypollsInstallerScript
{
	/**
	 * method to install the component
	 *
	 * @return void
	 */
	function install($parent)
	{
		// $parent is the class calling this method
		$parent->getParent()->setRedirectURL('index.php?option=com_communitypolls');
	}

	/**
	 * method to uninstall the component
	 *
	 * @return void
	 */
	function uninstall($parent)
	{
		// $parent is the class calling this method
		echo '<p>' . JText::_('COM_COMMUNITYPOLLS_UNINSTALL_TEXT') . '</p>';
	}

	/**
	 * method to update the component
	 *
	 * @return void
	 */
	function update($parent){
		$db = JFactory::getDBO();
		if(method_exists($parent, 'extension_root')) {
			$sqlfile = $parent->getPath('extension_root').'/sql/install.mysql.utf8.sql';
		} else {
			$sqlfile = $parent->getParent()->getPath('extension_root').'/sql/install.mysql.utf8.sql';
		}
		// Don't modify below this line
		$buffer = file_get_contents($sqlfile);
		if ($buffer !== false) {
			jimport('joomla.installer.helper');
			$queries = JInstallerHelper::splitSql($buffer);
			if (count($queries) != 0) {
				foreach ($queries as $query)
				{
					$query = trim($query);
					if ($query != '' && $query{0} != '#') {
						$db->setQuery($query);
						if (!$db->query()) {
							JError::raiseWarning(1, JText::sprintf('JLIB_INSTALLER_ERROR_SQL_ERROR', $db->stderr(true)));
							return false;
						}
					}
				}
			}
		}
		// $parent is the class calling this method
		echo '<p>' . JText::_('COM_COMMUNITYPOLLS_UPDATE_TEXT') . '</p>';
		$parent->getParent()->setRedirectURL('index.php?option=com_communitypolls&view=polls');
	}

	/**
	 * method to run before an install/update/uninstall method
	 *
	 * @return void
	 */
	function preflight($type, $parent)
	{
		// $parent is the class calling this method
		// $type is the type of change (install, update or discover_install)
		echo '<p>' . JText::_('COM_COMMUNITYPOLLS_PREFLIGHT_' . $type . '_TEXT') . '</p>';
	}

	/**
	 * method to run after an install/update/uninstall method
	 *
	 * @return void
	 */
	function postflight($type, $parent){
		
		$db = JFactory::getDbo();
		
// 		$update_queries[] = 'ALTER IGNORE TABLE `#__jcp_categories` ADD COLUMN `language` VARCHAR(6) NOT NULL DEFAULT \'*\'';
// 		$update_queries[] = 'ALTER IGNORE TABLE `#__jcp_polls` ADD COLUMN `close_date` DATETIME DEFAULT NULL AFTER `featured`';
// 		$update_queries[] = 'ALTER IGNORE TABLE `#__jcp_polls` ADD COLUMN `results_up` DATETIME NOT NULL DEFAULT \'0000-00-00 00:00:00\' AFTER `close_date`';
// 		$update_queries[] = 'ALTER IGNORE TABLE `#__jcp_polls` ADD COLUMN `type` VARCHAR(10) DEFAULT \'radio\'';
// 		$update_queries[] = 'ALTER IGNORE TABLE `#__jcp_polls` ADD COLUMN `voters` INT(9) UNSIGNED NOT NULL DEFAULT \'0\'';
// 		$update_queries[] = 'ALTER IGNORE TABLE `#__jcp_polls` ADD COLUMN `anywhere` TINYINT(3) UNSIGNED NOT NULL DEFAULT \'0\'';
// 		$update_queries[] = 'ALTER IGNORE TABLE `#__jcp_polls` ADD COLUMN `sort_order` int(10) unsigned NOT NULL default \'0\'';
// 		$update_queries[] = 'ALTER IGNORE TABLE `#__jcp_polls` ADD COLUMN `custom_answer` TINYINT(3) UNSIGNED NOT NULL DEFAULT 0';
// 		$update_queries[] = 'ALTER IGNORE TABLE `#__jcp_polls` ADD COLUMN `chart_type` VARCHAR(10) NOT NULL DEFAULT \'global\'';
// 		$update_queries[] = 'ALTER IGNORE TABLE `#__jcp_polls` ADD COLUMN `pallete` VARCHAR(10) NOT NULL DEFAULT \'default\'';
// 		$update_queries[] = 'ALTER IGNORE TABLE `#__jcp_polls` ADD INDEX `idx_polls_sort_order`(`sort_order`)';
// 		$update_queries[] = 'ALTER IGNORE TABLE `#__jcp_polls` MODIFY COLUMN `published` TINYINT(3)';
// 		$update_queries[] = 'ALTER IGNORE TABLE `#__jcp_votes` MODIFY COLUMN `ip_address` VARCHAR(39) DEFAULT NULL';
// 		$update_queries[] = 'ALTER IGNORE TABLE `#__jcp_votes` ADD COLUMN `option_id` INT(10) UNSIGNED NOT NULL DEFAULT \'0\'';
// 		$update_queries[] = 'ALTER IGNORE TABLE `#__jcp_votes` ADD COLUMN `column_id` INT UNSIGNED NOT NULL DEFAULT \'0\'';
// 		$update_queries[] = 'ALTER IGNORE TABLE `#__jcp_votes` ADD COLUMN `custom_answer` TEXT';
// 		$update_queries[] = 'ALTER IGNORE TABLE `#__jcp_options` ADD COLUMN `type` VARCHAR(10) DEFAULT NULL';
// 		$update_queries[] = 'ALTER IGNORE TABLE `#__jcp_options` ADD COLUMN `order` int(10) unsigned NOT NULL';
// 		$update_queries[] = 'ALTER IGNORE TABLE `#__jcp_options` ADD COLUMN `published` TINYINT(1) UNSIGNED NOT NULL DEFAULT 1';
// 		$update_queries[] = 'ALTER IGNORE TABLE `#__jcp_categories` ADD COLUMN `nleft` INTEGER UNSIGNED NOT NULL DEFAULT \'0\'';
// 		$update_queries[] = 'ALTER IGNORE TABLE `#__jcp_categories` ADD COLUMN `nright` INTEGER UNSIGNED NOT NULL DEFAULT \'0\'';
// 		$update_queries[] = 'ALTER IGNORE TABLE `#__jcp_categories` ADD COLUMN `norder` INTEGER UNSIGNED NOT NULL DEFAULT \'0\'';
// 		$update_queries[] = 'ALTER IGNORE TABLE `#__jcp_categories` ADD COLUMN `parent_id` INTEGER UNSIGNED NOT NULL DEFAULT \'0\'';
// 		$update_queries[] = 'update #__jcp_options set `type` = \'x\' where `type` is null or `type` != \'y\'';
		
		// new in v3.1.0
		$update_queries[] = 'ALTER IGNORE TABLE `#__jcp_polls` ADD COLUMN `anonymous` TINYINT(1) UNSIGNED NOT NULL DEFAULT 0';
		$update_queries[] = 'ALTER IGNORE TABLE `#__jcp_polls` ADD COLUMN `private` TINYINT(1) UNSIGNED NOT NULL DEFAULT 0';
		$update_queries[] = 'ALTER IGNORE TABLE `#__jcp_polls` ADD COLUMN `secret` VARCHAR(16) DEFAULT NULL';
		$update_queries[] = 'ALTER IGNORE TABLE `#__jcp_polls` ADD INDEX `idx_polls_created_by`(`created_by`)';
		$update_queries[] = 'ALTER IGNORE TABLE `#__jcp_votes` ADD COLUMN `id` INTEGER UNSIGNED NOT NULL AUTO_INCREMENT, ADD PRIMARY KEY (`id`)';
		$update_queries[] = 'ALTER IGNORE TABLE `#__jcp_votes` ADD INDEX `idx_jcp_votes_optionid`(`option_id`)';
		$update_queries[] = 'ALTER IGNORE TABLE `#__jcp_votes` ADD INDEX `idx_jcp_votes_voter_id`(`voter_id`)';
		$update_queries[] = 'ALTER IGNORE TABLE `#__jcp_options` ADD INDEX `idx_jcp_options_pollid`(`poll_id`)';
		$update_queries[] = 'ALTER IGNORE TABLE `#__jcp_polls` ADD COLUMN `answers_order` VARCHAR(8) NOT NULL DEFAULT \'order\'';
		
		// new in v4.0.0
		$update_queries[] ='ALTER IGNORE TABLE `#__jcp_categories` ADD COLUMN `migrated` INTEGER UNSIGNED NOT NULL DEFAULT 0';
		$update_queries[] ='ALTER IGNORE TABLE `#__jcp_polls` ADD COLUMN `catid` INT(10) UNSIGNED NOT NULL DEFAULT 0';
		$update_queries[] ='ALTER IGNORE TABLE `#__jcp_polls` ADD COLUMN `modified` DATETIME NOT NULL DEFAULT \'0000-00-00 00:00:00\'';
		$update_queries[] ='ALTER IGNORE TABLE `#__jcp_polls` ADD COLUMN `modified_by` INT(10) UNSIGNED NOT NULL DEFAULT 0';
		$update_queries[] ='ALTER IGNORE TABLE `#__jcp_polls` ADD COLUMN `modify_answers` TINYINT(3) UNSIGNED NOT NULL DEFAULT 0';
		$update_queries[] ='ALTER IGNORE TABLE `#__jcp_polls` ADD COLUMN `end_message` MEDIUMTEXT';
		$update_queries[] ='ALTER IGNORE TABLE `#__jcp_polls` ADD COLUMN `language` VARCHAR(6) NOT NULL DEFAULT \'*\'';
		$update_queries[] ='ALTER IGNORE TABLE `#__jcp_polls` ADD COLUMN `checked_out` int(10) UNSIGNED NOT NULL DEFAULT 0';
		$update_queries[] ='ALTER IGNORE TABLE `#__jcp_polls` ADD COLUMN `checked_out_time` DATETIME NOT NULL DEFAULT \'0000-00-00 00:00:00\'';
		$update_queries[] ='ALTER IGNORE TABLE `#__jcp_polls` ADD COLUMN `publish_up` DATETIME NOT NULL DEFAULT \'0000-00-00 00:00:00\'';
		$update_queries[] ='ALTER IGNORE TABLE `#__jcp_polls` ADD COLUMN `publish_down` DATETIME NOT NULL DEFAULT \'0000-00-00 00:00:00\'';
		$update_queries[] ='ALTER IGNORE TABLE `#__jcp_polls` ADD COLUMN `metakey` TEXT';
		$update_queries[] ='ALTER IGNORE TABLE `#__jcp_polls` ADD COLUMN `metadesc` TEXT';
		$update_queries[] ='ALTER IGNORE TABLE `#__jcp_polls` ADD COLUMN `metadata` TEXT';
		$update_queries[] ='ALTER IGNORE TABLE `#__jcp_polls` ADD COLUMN `attribs` VARCHAR(5120)';
		$update_queries[] ='ALTER IGNORE TABLE `#__jcp_polls` ADD COLUMN `access` INT(10) UNSIGNED NOT NULL DEFAULT 0';
		$update_queries[] ='ALTER IGNORE TABLE `#__jcp_polls` ADD COLUMN `asset_id` INT(10) UNSIGNED NOT NULL DEFAULT 0';
		$update_queries[] ='ALTER IGNORE TABLE `#__jcp_polls` ADD COLUMN `ordering` INT(11) UNSIGNED NOT NULL DEFAULT 0';
		$update_queries[] ='ALTER IGNORE TABLE `#__jcp_polls` ADD COLUMN `created_by_alias` VARCHAR(255)';
		$update_queries[] ='ALTER IGNORE TABLE `#__jcp_polls` ADD COLUMN `version` INTEGER(10) UNSIGNED NOT NULL DEFAULT 0';
		$update_queries[] ='ALTER IGNORE TABLE `#__jcp_polls` ADD COLUMN `min_answers` INTEGER UNSIGNED NOT NULL DEFAULT 1';
		$update_queries[] ='ALTER IGNORE TABLE `#__jcp_polls` ADD COLUMN `max_answers` INTEGER UNSIGNED NOT NULL DEFAULT 0';
		$update_queries[] ='ALTER IGNORE TABLE `#__jcp_polls` DROP INDEX `FK_JCP_POLLS`';
		$update_queries[] ='ALTER IGNORE TABLE `#__jcp_polls` DROP INDEX `idx_polls_sort_order`';
		$update_queries[] ='ALTER IGNORE TABLE `#__jcp_polls` ADD INDEX `idx_jcp_polls_catid`(`catid`)';
		$update_queries[] ='ALTER IGNORE TABLE `#__jcp_polls` ADD INDEX `idx_jcp_polls_published`(`published`)';
		$update_queries[] ='ALTER IGNORE TABLE `#__jcp_polls` ADD INDEX `idx_jcp_polls_checkout`(`checked_out`)';
		$update_queries[] ='ALTER IGNORE TABLE `#__jcp_polls` ADD INDEX `idx_jcp_polls_access`(`access`)';
		$update_queries[] ='ALTER IGNORE TABLE `#__jcp_polls` ADD INDEX `idx_jcp_polls_language`(`language`)';
		
		// Perform all queries - we don't care if it fails
		foreach( $update_queries as $query ) {
		
			$db->setQuery( $query );
				
			try{
				
				$db->query();
			}catch(Exception $e){}
		}
		
		/** Create images and cache folders**/
		JFolder::create(JPATH_ROOT.'/media/communitypolls/images');
		JFolder::create(JPATH_ROOT.'/media/communitypolls/cache');
		JFolder::create(JPATH_ROOT.'/media/communitypolls/tmp');
		
		touch(JPATH_ROOT.'/media/communitypolls/index.html');
		touch(JPATH_ROOT.'/media/communitypolls/images/index.html');
		touch(JPATH_ROOT.'/media/communitypolls/tmp/index.html');
		touch(JPATH_ROOT.'/media/communitypolls/cache/index.html');
		/** end creating folders **/
		
		// migrate features
		$this->migrate_features();
		
		$files[] = JPATH_ROOT.'/media/com_communitypolls/anywhere/anywhere.js';
		$files[] = JPATH_ROOT.'/media/com_communitypolls/anywhere/help.html';
		
		foreach ($files as $file){
			
			$content = file_get_contents($file);
			$content = str_replace('YOUR_WEBSITE_END_WITH_SLASH',JURI::root(), $content);
			$f = fopen($file,'w');
			
			if($f) {
				
			  fwrite($f, $content);
			  fclose($f);
			} else {
				
			  echo 'Failed to update pollsanywhere script file. Please manually update your website URL in components/com_communitypolls/assets/anywhere/anywhere.js file';
			}
		}
		
		echo "<b><font color=\"red\">Database tables successfully migrated to the latest version. Please check the configuration options once again.</font></b>";
	}

	private function migrate_features()
	{
		// add core features support
		$this->add_core_features_support();
		
		// check if the categories are already upgraded
		$basePath = JPATH_ADMINISTRATOR . '/components/com_categories';
		require_once $basePath . '/models/category.php';
	
		jimport('joomla.application.categories');
		$categories = JCategories::getInstance('CommunityPolls');
	
		if(count($categories->get(0)->getChildren()) == 0)
		{
			// no cateogories upgraded, then get the existing categories
			/****************** CJLib includes ***************************/
			if(file_exists(JPATH_ROOT.'/components/com_cjlib/framework.php'))
			{
				require_once JPATH_ROOT.'/components/com_cjlib/framework.php';
			}
			else
			{
				die('CJLib (CoreJoomla API Library) component not found. Please download and install it to continue.');
			}
			CJLib::import('corejoomla.nestedtree.core');
			/****************** CJLib includes ***************************/
				
			$db = JFactory::getDbo();
			$tree = new CjNestedTree($db, '#__jcp_categories');
			$existing = $tree->get_tree();
				
			if(!empty($existing))
			{
				$this->add_category($existing);
			}
		}
	}
	
	private function add_category($nodes, $path = '', $nlevel = 1, $parent_id = 0)
	{
		foreach($nodes as $node)
		{
			if($node['migrated'] > 0)
			{
				// category already migrated, don't do anything.
			}
			else 
			{
				$config = array( 'table_path' => JPATH_ADMINISTRATOR.'/components/com_categories/tables');
				$model = new CategoriesModelCategory( $config );
					
				$data = array(
						'id' => 0,
						'parent_id' => $parent_id,
						'level' => $nlevel,
						'path' => $path.$node['alias'],
						'extension' => 'com_communitypolls',
						'title' => $node['title'],
						'alias' => $node['alias'],
						'description' => '',
						'published' => 1,
						'language' => $node['language']);
					
				$status = $model->save( $data );
		
				if(!$status)
				{
					JError::raiseWarning(500, JText::_('Unable to create default content category!'));
				}
				else
				{
					$newid = $model->getState($model->getName().'.id');
					if($newid)
					{
						try
						{
							$db = JFactory::getDbo();
							$query = $db->getQuery(true)->update('#__jcp_categories')->set('migrated = '. $newid)->where('id = '.$node['id']);
							$db->setQuery($query);
							$db->execute();
							
							$query = $db->getQuery(true)->update('#__jcp_polls')->set('catid = '.$newid)->where('category = '.$node['id']);
							$db->setQuery($query);
							$db->execute();
						}
						catch (Exception $e)
						{
							JError::raiseWarning(500, JText::_('Unable to update category data!'));
						}
					}
				}
					
				if(!empty($node['children']))
				{
					$this->add_category($node['children'], $path.'/', $nlevel + 1, $newid);
				}
			}
		}
	}
		
	private function add_core_features_support()
	{
		// add poll content type to content_types table
		$poll_table_def = new stdClass();
		$poll_table_def->special = new stdClass();
		$poll_table_def->special->dbtable = '#__jcp_polls';
		$poll_table_def->special->key = 'id';
		$poll_table_def->special->type = 'Poll';
		$poll_table_def->special->prefix = 'CommunityPollsTable';
		$poll_table_def->special->config = 'array()';
		
		$poll_table_def->common = new stdClass();
		$poll_table_def->common->dbtable = '#__ucm_content';
		$poll_table_def->common->key = 'ucm_id';
		$poll_table_def->common->type = 'Corecontent';
		$poll_table_def->common->prefix = 'JTable';
		$poll_table_def->common->config ='array()';
		
		$poll_field_mappings = new stdClass();
		$poll_field_mappings->common = new stdClass();
		$poll_field_mappings->common->core_content_item_id = 'id';
		$poll_field_mappings->common->core_title = 'title';
		$poll_field_mappings->common->core_state = 'published';
		$poll_field_mappings->common->core_alias = 'alias';
		$poll_field_mappings->common->core_created_time = 'created';
		$poll_field_mappings->common->core_modified_time = 'modified';
		$poll_field_mappings->common->core_body = 'description';
		$poll_field_mappings->common->core_hits = 'null';
		$poll_field_mappings->common->core_publish_up = 'publish_up';
		$poll_field_mappings->common->core_publish_down = 'publish_down';
		$poll_field_mappings->common->core_access = 'access';
		$poll_field_mappings->common->core_params= 'null';
		$poll_field_mappings->common->core_featured = 'featured';
		$poll_field_mappings->common->core_metadata = 'metadata';
		$poll_field_mappings->common->core_language = 'language';
		$poll_field_mappings->common->core_images = 'null';
		$poll_field_mappings->common->core_urls = 'null';
		$poll_field_mappings->common->core_version = 'version';
		$poll_field_mappings->common->core_ordering = 'ordering';
		$poll_field_mappings->common->core_metakey = 'metakey';
		$poll_field_mappings->common->core_metadesc = 'metadesc';
		$poll_field_mappings->common->core_catid = 'catid';
		$poll_field_mappings->common->core_xreference = 'null';
		$poll_field_mappings->common->asset_id = 'asset_id';
		
		$poll_field_mappings->special = new stdClass();
		$poll_field_mappings->special->votes = 'votes';
		$poll_field_mappings->special->voters = 'voters';
		$poll_field_mappings->special->last_voted = 'last_voted';
		$poll_field_mappings->special->close_date = 'close_date';
		$poll_field_mappings->special->results_up = 'results_up';
		$poll_field_mappings->special->ip_address = 'ip_address';
		$poll_field_mappings->special->type = 'type';
		$poll_field_mappings->special->anywhere = 'anywhere';
		$poll_field_mappings->special->custom_answer = 'custom_answer';
		$poll_field_mappings->special->chart_type = 'chart_type';
		$poll_field_mappings->special->pallete = 'pallete';
		$poll_field_mappings->special->anonymous = 'anonymous';
		$poll_field_mappings->special->secret = 'secret';
		$poll_field_mappings->special->answers_order = 'answers_order';
		$poll_field_mappings->special->modify_answers = 'modify_answers';
		$poll_field_mappings->special->end_message = 'end_message';
		
		$display_lookup_catid = new stdClass();
		$display_lookup_catid->sourceColumn = 'catid';
		$display_lookup_catid->targetTable = '#__categories';
		$display_lookup_catid->targetColumn = 'id';
		$display_lookup_catid->displayColumn = 'title';
		
		$display_lookup_title = new stdClass();
		$display_lookup_title->sourceColumn = 'created_by';
		$display_lookup_title->targetTable = '#__users';
		$display_lookup_title->targetColumn = 'id';
		$display_lookup_title->displayColumn = 'name';
		
		$display_lookup_access = new stdClass();
		$display_lookup_access->sourceColumn = 'access';
		$display_lookup_access->targetTable = '#__viewlevels';
		$display_lookup_access->targetColumn = 'id';
		$display_lookup_access->displayColumn = 'title';
		
		$display_lookup_modified_by = new stdClass();
		$display_lookup_modified_by->sourceColumn = 'modified_by';
		$display_lookup_modified_by->targetTable = '#__users';
		$display_lookup_modified_by->targetColumn = 'id';
		$display_lookup_modified_by->displayColumn = 'name';

		$poll_history_options = new stdClass();
		$poll_history_options->formFile = 'administrator/components/com_communitypolls/models/forms/poll.xml';
		$poll_history_options->hideFields = array('asset_id','checked_out','checked_out_time','version');
		$poll_history_options->ignoreChanges = array('modified_by', 'modified', 'checked_out', 'checked_out_time', 'version', 'votes');
		$poll_history_options->convertToInt = array('publish_up', 'publish_down', 'featured', 'ordering');
		$poll_history_options->displayLookup = array($display_lookup_catid, $display_lookup_title, $display_lookup_access, $display_lookup_modified_by);

		$poll_table = JTable::getInstance('Contenttype', 'JTable');
		$poll_type_id = (int) $poll_table->getTypeId('com_communitypolls.poll');
		
		$poll_content_type = array();
		$poll_content_type['type_id'] = $poll_type_id;
		$poll_content_type['type_title'] = 'Poll';
		$poll_content_type['type_alias'] = 'com_communitypolls.poll';
		$poll_content_type['table'] = json_encode($poll_table_def);
		$poll_content_type['rules'] = '';
		$poll_content_type['router'] = 'CommunityPollsHelperRoute::getPollRoute';
		$poll_content_type['field_mappings'] = json_encode($poll_field_mappings);
		$poll_content_type['content_history_options'] = json_encode($poll_history_options);
		
		$poll_table->save($poll_content_type);
		
		// add poll category type to content_types table
		$category_table_def = new stdClass();
		$category_table_def->special = new stdClass();
		$category_table_def->special->dbtable = '#__categories';
		$category_table_def->special->key = 'id';
		$category_table_def->special->type = 'Category';
		$category_table_def->special->prefix = 'JTable';
		$category_table_def->special->config = 'array()';
		
		$category_table_def->common = new stdClass();
		$category_table_def->common->dbtable = '#__ucm_content';
		$category_table_def->common->key = 'ucm_id';
		$category_table_def->common->type = 'Corecontent';
		$category_table_def->common->prefix = 'JTable';
		$category_table_def->common->config =  'array()';
		
		$category_field_mappings = new stdClass();
		$category_field_mappings->common = new stdClass();
		$category_field_mappings->common->core_content_item_id = 'id';
		$category_field_mappings->common->core_title = 'title';
		$category_field_mappings->common->core_state = 'published';
		$category_field_mappings->common->core_alias = 'alias';
		$category_field_mappings->common->core_created_time = 'created_time';
		$category_field_mappings->common->core_modified_time = 'modified_time';
		$category_field_mappings->common->core_body = 'description';
		$category_field_mappings->common->core_hits = 'hits';
		$category_field_mappings->common->core_publish_up = 'null';
		$category_field_mappings->common->core_publish_down = 'null';
		$category_field_mappings->common->core_access = 'access';
		$category_field_mappings->common->core_params = 'params';
		$category_field_mappings->common->core_featured = 'null';
		$category_field_mappings->common->core_metadata = 'metadata';
		$category_field_mappings->common->core_language = 'language';
		$category_field_mappings->common->core_images = 'null';
		$category_field_mappings->common->core_urls = 'null';
		$category_field_mappings->common->core_version = 'version';
		$category_field_mappings->common->core_ordering = 'null';
		$category_field_mappings->common->core_metakey = 'metakey';
		$category_field_mappings->common->core_metadesc = 'metadesc';
		$category_field_mappings->common->core_catid = 'parent_id';
		$category_field_mappings->common->core_xreference = 'null';
		$category_field_mappings->common->asset_id = 'asset_id';
		
		$category_field_mappings->special = new stdClass();
		$category_field_mappings->special->parent_id = 'parent_id';
		$category_field_mappings->special->lft = 'lft';
		$category_field_mappings->special->rgt = 'rgt';
		$category_field_mappings->special->level = 'level';
		$category_field_mappings->special->path = 'path';
		$category_field_mappings->special->extension = 'extension';
		$category_field_mappings->special->note = 'note';
		
		$category_display_created_by = new stdClass();
		$category_display_created_by->sourceColumn = 'created_user_id';
		$category_display_created_by->targetTable = '#__users';
		$category_display_created_by->targetColumn = 'id';
		$category_display_created_by->displayColumn = 'name';
		
		$category_display_access = new stdClass();
		$category_display_access->sourceColumn = 'access';
		$category_display_access->targetTable = '#__viewlevels';
		$category_display_access->targetColumn = 'id';
		$category_display_access->displayColumn = 'title';
		
		$category_display_modified_by = new stdClass();
		$category_display_modified_by->sourceColumn = 'modified_user_id';
		$category_display_modified_by->targetTable = '#__users';
		$category_display_modified_by->targetColumn = 'id';
		$category_display_modified_by->displayColumn = 'name';
		
		$category_display_parent_id = new stdClass();
		$category_display_parent_id->sourceColumn = 'parent_id';
		$category_display_parent_id->targetTable = '#__categories';
		$category_display_parent_id->targetColumn = 'id';
		$category_display_parent_id->displayColumn = 'title';
		
		$category_history_options = new stdClass();
		$category_history_options->formFile = 'administrator/components/com_categories/models/forms/category.xml';
		$category_history_options->hideFields = array('asset_id','checked_out', 'checked_out_time'. 'version', 'lft', 'rgt', 'level', 'path', 'extension');
		$category_history_options->ignoreChanges = array('modified_user_id', 'modified_time', 'checked_out', 'checked_out_time', 'version', 'hits', 'path');
		$category_history_options->convertToInt = array('publish_up', 'publish_down');
		$category_history_options->displayLookup = array($category_display_created_by, $category_display_access, $category_display_modified_by, $category_display_parent_id);

		$category_table = JTable::getInstance('Contenttype', 'JTable');
		$category_type_id = (int) $category_table->getTypeId('com_communitypolls.category');
		
		$category_content_type = array();
		$category_content_type['type_id'] = $category_type_id;
		$category_content_type['type_title'] = 'Poll Category';
		$category_content_type['type_alias'] = 'com_communitypolls.category';
		$category_content_type['table'] = json_encode($category_table_def);
		$category_content_type['rules']	= '';
		$category_content_type['router'] = 'CommunityPollsHelperRoute::getCategoryRoute';
		$category_content_type['field_mappings'] = json_encode($category_field_mappings);
		$category_content_type['content_history_options'] = json_encode($category_history_options);
		
		$category_table->save($category_content_type);
	}
}