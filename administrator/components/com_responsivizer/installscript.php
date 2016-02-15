<?php
//namespace administrator\components\com_responsivizer;
/**
 * Installer dell'application
 * @package RESPONSIVIZER::administrator::components::com_responsivizer 
 * @author Joomla! Extensions Store
 * @copyright (C)2014 Joomla! Extensions Store
 * @license GNU/GPLv2 or later, see license.txt 
 */
defined ( '_JEXEC' ) or die ( 'Restricted access' );

/**
 * Script per i processi di install/update/uninstall delle estensioni. Segue una convenzione di classe
 * @package RESPONSIVIZER::administrator::components::com_responsivizer 
 */
class com_responsivizerInstallerScript {
	/*
	 * The release value to be displayed and checked against throughout this file.
	*/
	private $release = '2.0';
	
	/*
	 * Compt from 1.6 to 3.0
	*/
	private $quotingFunction;
	
	/*
	 * Find mimimum required joomla version for this extension. It will be read from the version attribute (install tag) in the manifest file
	*/
	private $minimum_joomla_release = '3.0';
	
	/**
	 * Sub installer per il plugin
	 * @param Object& $singletonInstaller
	 * @param Object& $database
	 */
	private function comResponsivizerInstallPlugin(&$singletonInstaller, &$database) {
		$plugins2Install = array(array('element'=>'responsivizer', 'folder'=>'system'),
								 array('element'=>'notoolbar', 'folder'=>'system'),
								 array('element'=>'lightimages', 'folder'=>'system'),
								 array('element'=>'routerreplacer', 'folder'=>'system'),
								 array('element'=>'resplangrouter', 'folder'=>'system'),
								 array('element'=>'respsharer', 'folder'=>'content'));
		
		$pathToPlugin = $singletonInstaller->getPath('source') . '/' . 'subextensions' . '/' . 'plugin';

		foreach ($plugins2Install as $plugin) {
			// Si necessita una nuova istanza dell'installer per il plugin
			$pluginInstaller = new JInstaller();
			if(!$pluginInstaller->install($pathToPlugin . '/' . $plugin['element'])) {
				echo JText::sprintf('COM_RESPONSIVIZER_PROGRESS_ERRORS_HTML', JText::_('COM_RESPONSIVIZER_ERROR_INSTALLING_PLUGINS'));
				return false;
			} else {
				$query = "UPDATE #__extensions" .
						"\n SET enabled = 1" .
						"\n WHERE element = " . $database->quote($plugin['element']) .
						"\n AND folder = ". $database->quote($plugin['folder']);
				$database->setQuery($query);
				if(!$database->execute()) {
					echo JText::sprintf('COM_RESPONSIVIZER_PROGRESS_ERRORS_HTML', JText::_('COM_RESPONSIVIZER_ERROR_PUBLISHING_PLUGIN'));
					return false;
				}
			}
			
			// Make last execution for router replacer plugin
			if($plugin['element'] == 'routerreplacer') {
				$query = "UPDATE #__extensions" .
						"\n SET ordering = 9999" .
						"\n WHERE element = " . $database->quote('routerreplacer') .
						"\n AND folder = ". $database->quote('system');
				$database->setQuery($query);
			}
		}
	
		return true;
	}
	
	/**
	 * Sub installer per il modulo
	 * @param Object& $singletonInstaller
	 * @param Object& $database
	 */
	private function comResponsivizerInstallModule(&$singletonInstaller, &$database) {
		$pathToModule = $singletonInstaller->getPath('source') . '/' . 'subextensions' . '/' . 'module';
	
		// Si necessita una nuova istanza dell'installer per il plugin
		$moduleInstaller = new JInstaller();
		if(!$moduleId = $moduleInstaller->install($pathToModule)) {
			echo JText::sprintf('COM_RESPONSIVIZER_PROGRESS_ERRORS_HTML', JText::_('COM_RESPONSIVIZER_ERROR_INSTALLING_MODULE'));
			return false;
		} else {
			$query = "UPDATE #__extensions" .
					"\n SET " . $database->{$this->quotingFunction}('enabled') . " = 1" .
					"\n WHERE " . $database->{$this->quotingFunction}('element') . " = " . $database->quote('mod_responsivizerslideshow');
			$database->setQuery($query);
			$database->execute();
				
			$query = "UPDATE #__modules" .
					"\n SET " . $database->{$this->quotingFunction}('published') . " = 1" .
					"\n ," . $database->{$this->quotingFunction}('position') . " = " . $database->quote('responsivizer_slideshow') .
					"\n ," . $database->{$this->quotingFunction}('showtitle') . " = 0" .
					"\n WHERE " . $database->{$this->quotingFunction}('module') . " = " . $database->quote('mod_responsivizerslideshow');
			$database->setQuery($query);
			if(!$database->execute()) {
				echo JText::sprintf('COM_RESPONSIVIZER_PROGRESS_ERRORS_HTML', JText::_('COM_RESPONSIVIZER_ERROR_PUBLISHING_MODULE'));
				return false;
			}
				
			// Publish all pages for default on joomla1.6+
			$query	= $database->getQuery(true);
			$query->select('id');
			$query->from('#__modules');
			$query->where($database->{$this->quotingFunction}('module') . '=' . $database->quote('mod_responsivizerslideshow'));
				
			$database->setQuery($query);
			$lastIDForModule = $database->loadResult();
	
			// Now insert
			try {
				$query	= $database->getQuery(true);
				$query->insert('#__modules_menu');
				$query->set($database->{$this->quotingFunction}('moduleid') . '=' . $database->quote($lastIDForModule));
				$query->set($database->{$this->quotingFunction}('menuid') . '= 0');
				$database->setQuery($query);
				$database->execute();
			} catch (Exception $e) {
				// Already existing no insert - do nothing all true
			}
		}
	
		return true;
	}
	
	/**
	 * Sub installer per il modulo switcher
	 * @param Object& $singletonInstaller
	 * @param Object& $database
	 */
	private function comResponsivizerInstallModuleSwitcher(&$singletonInstaller, &$database) {
		$pathToModule = $singletonInstaller->getPath('source') . '/' . 'subextensions' . '/' . 'moduleswitcher';
	
		// Si necessita una nuova istanza dell'installer per il plugin
		$moduleInstaller = new JInstaller();
		if(!$moduleId = $moduleInstaller->install($pathToModule)) {
			echo JText::sprintf('COM_RESPONSIVIZER_PROGRESS_ERRORS_HTML', JText::_('COM_RESPONSIVIZER_ERROR_INSTALLING_MODULE'));
			return false;
		} else {
			$query = "UPDATE #__extensions" .
					 "\n SET " . $database->{$this->quotingFunction}('enabled') . " = 1" .
					 "\n WHERE " . $database->{$this->quotingFunction}('element') . " = " . $database->quote('mod_responsivizerswitcher');
			$database->setQuery($query);
			$database->execute();
		}
	
		return true;
	}
	
	/**
	 * Sub installer per il template
	 * $singletonInstaller->getPath('source') => path to source tmp folder
	 * $singletonInstaller->getPath('extension_root') => path to destination extension folder
	 * @param Object& $singletonInstaller
	 * @param Object& $database
	 */
	private function comResponsivizerInstallTemplate(&$singletonInstaller, &$database) {
		$pathToTemplate = $singletonInstaller->getPath('source') . '/' . 'subextensions' . '/' . 'template';
	
		// Si necessita una nuova istanza dell'installer per il plugin
		$templateInstaller = new JInstaller();
		if(!$templateInstaller->install($pathToTemplate)) {
			echo JText::sprintf('COM_RESPONSIVIZER_PROGRESS_ERRORS_HTML', JText::_('COM_RESPONSIVIZER_ERROR_INSTALLING_TEMPLATE'));
			return false;
		}
		
		// Replace template style string no image icon
		$query = "UPDATE #__template_styles" .
				"\n SET " . $database->{$this->quotingFunction}('title') . " = " . $database->quote(JText::_('COM_RESPONSIVIZER_TEMPLATE_STYLE')) .
				"\n WHERE " . $database->{$this->quotingFunction}('template') . " = " . $database->quote('responsivizer');
		$database->setQuery($query);
		$database->execute();
	
		return true;
	}
	
	/**
	 * Sub installer per il plugin
	 * @param Object& $singletonInstaller
	 * @param Object& $database
	 */
	private function comResponsivizerUninstallPlugin(&$database) {
		$plugins2Uninstall = array(array('element'=>'responsivizer', 'folder'=>'system'),
						 		   array('element'=>'notoolbar', 'folder'=>'system'),
								   array('element'=>'lightimages', 'folder'=>'system'),
								   array('element'=>'routerreplacer', 'folder'=>'system'),
								   array('element'=>'resplangrouter', 'folder'=>'system'),
								   array('element'=>'respsharer', 'folder'=>'content'));
		
		foreach ($plugins2Uninstall as $plugin) {
			// Controllo esistenza del plugin
			$query = $database->getQuery(true);
			$query->select('extension_id');
			$query->from('#__extensions');
			$query->where($database->{$this->quotingFunction}('type') . '=' . $database->quote('plugin') . ' AND ' .
					$database->{$this->quotingFunction}('element') . '=' . $database->quote($plugin['element']));
			$database->setQuery($query);
			$pluginID = $database->loadResult();
				
			if(!$pluginID) {
				echo '<p>' . JText::sprintf('COM_RESPONSIVIZER_PLUGIN_ALREADY_REMOVED', $plugin['element']) . '</p>';
			} else {
				// Si necessita una nuova istanza dell'installer per il plugin
				$pluginInstaller = new JInstaller();
				if(!$pluginInstaller->uninstall('plugin', $pluginID)) {
					echo '<p>' . JText::sprintf('COM_RESPONSIVIZER_ERROR_UNINSTALLING_PLUGINS',  $plugin['element']) . '</p>';
					return false;
				}
			}
		}
		
		return true;
	}
	
	/**
	 * Sub installer per il modulo
	 * @param Object& $singletonInstaller
	 * @param Object& $database
	 */
	private function comResponsivizerUninstallModule(&$database) {
		// Controllo esistenza del modulo
		$query = $database->getQuery(true);
		$query->select('extension_id');
		$query->from('#__extensions');
		$query->where($database->{$this->quotingFunction}('type') . '=' . $database->quote('module') . ' AND ' .
				$database->{$this->quotingFunction}('element') . '=' . $database->quote('mod_responsivizerslideshow'));
		$database->setQuery($query);
		$moduleID = $database->loadResult();
		if(!$moduleID) {
			echo '<p>' . JText::_('COM_RESPONSIVIZER_MODULE_ALREADY_REMOVED') . '</p>';
			return false;
		} else {
			// Si necessita una nuova istanza dell'installer per il modulo
			$moduleInstaller = new JInstaller();
			if(!$moduleInstaller->uninstall('module', $moduleID)) {
				echo '<p>' . JText::_('COM_RESPONSIVIZER_ERROR_UNINSTALLING_MODULE') . '</p>';
				return false;
			}
		}
		return true;
	}
	
	/**
	 * Sub installer per il modulo switcher
	 * @param Object& $singletonInstaller
	 * @param Object& $database
	 */
	private function comResponsivizerUninstallModuleSwitcher(&$database) {
		// Controllo esistenza del modulo
		$query = $database->getQuery(true);
		$query->select('extension_id');
		$query->from('#__extensions');
		$query->where($database->{$this->quotingFunction}('type') . '=' . $database->quote('module') . ' AND ' .
					  $database->{$this->quotingFunction}('element') . '=' . $database->quote('mod_responsivizerswitcher'));
		$database->setQuery($query);
		$moduleID = $database->loadResult();
		if(!$moduleID) {
			echo '<p>' . JText::_('COM_RESPONSIVIZER_MODULE_ALREADY_REMOVED') . '</p>';
			return false;
		} else {
			// Si necessita una nuova istanza dell'installer per il modulo
			$moduleInstaller = new JInstaller();
			if(!$moduleInstaller->uninstall('module', $moduleID)) {
				echo '<p>' . JText::_('COM_RESPONSIVIZER_ERROR_UNINSTALLING_MODULE_SWITCHER') . '</p>';
				return false;
			}
		}
		return true;
	}
	
	/**
	 * Sub installer per il template
	 * @param Object& $singletonInstaller
	 * @param Object& $database
	 */
	private function comResponsivizerUninstallTemplate(&$database) {
		// Controllo esistenza del template
		$query = $database->getQuery(true);
		$query->select('extension_id');
		$query->from('#__extensions');
		$query->where($database->{$this->quotingFunction}('type') . '=' . $database->quote('template') . ' AND ' .
				$database->{$this->quotingFunction}('element') . '=' . $database->quote('responsivizer'));
		$database->setQuery($query);
		$templateID = $database->loadResult();
	
		if(!$templateID) {
			echo '<p>' . JText::_('COM_RESPONSIVIZER_TEMPLATE_ALREADY_REMOVED') . '</p>';
			return false;
		} else {
			// Si necessita una nuova istanza dell'installer per il template
			$templateInstaller = new JInstaller();
			if(!$templateInstaller->uninstall('template', $templateID)) {
				echo '<p>' . JText::_('COM_RESPONSIVIZER_ERROR_UNINSTALLING_TEMPLATE') . '</p>';
				return false;
			}
		}
		return true;
	}
	
	/**
	 * Manage manifest parsing and update file by file for template
	 * $parentParent->getPath('source') => path to source tmp folder
	 * $parentParent->getPath('extension_root') => path to destination extension folder
	 *
	 * @access private
	 * @param Object $singletonInstaller
	 * @return boolean
	 */
	private function comResponsivizerUpdateTemplate($singletonInstaller) {
		$pathToTemplate = $singletonInstaller->getPath('source') . '/' . 'subextensions' . '/' . 'template';
	
		// New installer for template, N.B.: installer not executed!
		$templateInstaller = new JInstaller();
		$templateInstaller->setPath('source', $pathToTemplate);
		$templateInstaller->setPath('extension_site', JPATH_ROOT . '/templates/responsivizer');
		$templateInstaller->setupInstall();
	
		// Get template
		$manifest = $templateInstaller->getManifest();
		if(!$manifest->templateupdates->children()->count()) {
			return true;
		}
	
		// Copy only needed files
		if(!$result = $templateInstaller->parseFiles($manifest->templateupdates)) {
			echo JText::sprintf('COM_RESPONSIVIZER_ERROR_UPDATING_TEMPLATE');
			return false;
		}

		return true;
	}
	
	/**
	 * Unpublish Joomla cache plugin to avoid cached pages between desktop and mobile templates
	 * 
	 * @access private
	 * @param $database DB Connector
	 * @return boolean
	 */
	private function unpublishCachePlugin($database) {
		$query = "UPDATE #__extensions" .
				 "\n SET " . $database->{$this->quotingFunction}('enabled') . " = 0" .
				 "\n WHERE " . $database->{$this->quotingFunction}('element') . " = " . $database->quote('cache') .
				 "\n AND " . $database->{$this->quotingFunction}('type') . " = " . $database->quote('plugin') .
				 "\n AND " . $database->{$this->quotingFunction}('folder') . " = " . $database->quote('system');
		$database->setQuery($query);
		if(!$database->execute()) {
			echo '<p>' . JText::_('COM_RESPONSIVIZER_UNPUBLISH_CACHE_PLUGIN') . '</p>';
			return false;
		}
	}
	
	/*
	 * $parent is the class calling this method.
	* $type is the type of change (install, update or discover_install, not uninstall).
	* preflight runs before anything else and while the extracted files are in the uploaded temp folder.
	* If preflight returns false, Joomla will abort the update and undo everything already done.
	*/
	public function preflight($type, $parent) {
	
	}
	
	/*
	 * $parent is the class calling this method.
	 * install runs after the database scripts are executed.
	 * If the extension is new, the install method is run.
	 * If install returns false, Joomla will abort the install and undo everything already done.
	 * $parent => JInstallerAdapterComponent
	 * $parentParent => $parent->getParent() = JInstaller
	 * $manifest => $parentParent->getManifest() = simple_xml_loadfile
	 */
	public function install($parent, $isUpdate = false) {
		$allWentWell = true;
		$database = JFactory::getDBO ();
		echo ('<style type="text/css">div.alert-success, span.step_details {display: none;font-size: 12px;} span.step_details div{margin-top:0 !important;}.installcontainer{width: 720px;}</style>');
		echo ('<link rel="stylesheet" type="text/css" href="' . JURI::root ( true ) . '/administrator/components/com_responsivizer/assets/css/bootstrap-install.css' . '" />');
		echo ('<script type="text/javascript" src="' . JURI::root ( true ) . '/administrator/components/com_responsivizer/js/installer.js' .'"></script>' );
		$lang = JFactory::getLanguage();
		$lang->load('com_responsivizer');
		$parentParent = $parent->getParent();
		
		// Utilizzo dell'installer direttamente da qui per le sub-estensioni
		$componentInstaller = JInstaller::getInstance();
		
		echo ('<div class="installcontainer">');
		// STEP1 - Start installing plugin
		if($this->comResponsivizerInstallPlugin($componentInstaller, $database)) { ?>
			<div class="progress">
				<div class="bar" role="progressbar" aria-valuenow="60" aria-valuemin="0" aria-valuemax="100">
					<span class="step_details"><?php echo JText::_('COM_RESPONSIVIZER_OK_INSTALLING_PLUGINS');?></span>
				</div>
			</div>
		<?php 
		} else {
			//$parentParent->abort();
			$allWentWell = false;
		}
		
		// STEP2 - Start installing modules
		if($this->comResponsivizerInstallModule($componentInstaller, $database)) { ?>
			<div class="progress">
				<div class="bar" role="progressbar" aria-valuenow="60" aria-valuemin="0" aria-valuemax="100">
					<span class="step_details"><?php echo JText::_('COM_RESPONSIVIZER_OK_INSTALLING_MODULE');?></span>
			  	</div>
			</div>
		<?php 
		} else {
			//$parentParent->abort();
			$allWentWell = false;
		}
		
		// STEP3 - Start installing modules
		if($this->comResponsivizerInstallModuleSwitcher($componentInstaller, $database)) { ?>
			<div class="progress">
				<div class="bar" role="progressbar" aria-valuenow="60" aria-valuemin="0" aria-valuemax="100">
					<span class="step_details"><?php echo JText::_('COM_RESPONSIVIZER_OK_INSTALLING_MODULE_SWITCHER');?></span>
			  	</div>
			</div>
		<?php 
		} else {
			//$parentParent->abort();
			$allWentWell = false;
		}
		
		// STEP4 - Start installing template, skip by default template install on update and demand parse manifest for single filse installation
		if(!$isUpdate) {
			if($this->comResponsivizerInstallTemplate($componentInstaller, $database)) { ?>
				<div class="progress">
				  	<div class="bar" role="progressbar" aria-valuenow="60" aria-valuemin="0" aria-valuemax="100">
				    	<span class="step_details"><?php echo JText::_('COM_RESPONSIVIZER_OK_INSTALLING_TEMPLATE');?></span>
				  	</div>
				</div>
			<?php 
			} else {
				//$parentParent->abort();
				$allWentWell = false;
			}
		} else {
			if($this->comResponsivizerUpdateTemplate($componentInstaller)) {?>
				<div class="progress">
				  	<div class="bar" role="progressbar" aria-valuenow="60" aria-valuemin="0" aria-valuemax="100">
				    	<span class="step_details"><?php echo JText::_('COM_RESPONSIVIZER_OK_UPDATING_TEMPLATE');?></span>
				  	</div>
				</div>
			<?php 
			} else {
				$allWentWell = false;
			}
		}
		
		// Additional step: unpublish Joomla cache plugin if enabled
		$this->unpublishCachePlugin($database); ?>
		<div class="progress">
			<div class="bar" role="progressbar" aria-valuenow="60" aria-valuemin="0" aria-valuemax="100">
				<span class="step_details"><?php echo JText::_('COM_RESPONSIVIZER_OK_INSTALLING_COMPONENT');?></span>
		  	</div>
		</div>
		
		<div class="alert alert-success"><?php echo $allWentWell ? JText::_('COM_RESPONSIVIZER_ALL_COMPLETED') : JText::_('COM_RESPONSIVIZER_NOTALL_COMPLETED');?></div>
		<?php 
		echo ('</div>');
		
		// Processing complete
		return true;
	}
	
	/*
	 * $parent is the class calling this method.
	* update runs after the database scripts are executed.
	* If the extension exists, then the update method is run.
	* If this returns false, Joomla will abort the update and undo everything already done.
	*/
	public function update($parent) {
		// Execute always SQL install file to get added updates in that file, disregard DBMS messages and Joomla queue for user
		$parentParent = $parent->getParent();
		$parentManifest = $parentParent->getManifest();
		try {
			// Install/update always without error handlingm case legacy JError
			JError::setErrorHandling(E_ALL, 'ignore');
			if (isset($parentManifest->install->sql)) {
				$parentParent->parseSQLFiles($parentManifest->install->sql);
			}
		} catch (Exception $e) {
			// Do nothing for user for Joomla 3.x case, case Exception handling
		}

		// Indifferentemente gestiamo l'installazione del plugin
		$this->install($parent, true);
	}
	
	/*
	 * $parent is the class calling this method.
	* $type is the type of change (install, update or discover_install, not uninstall).
	* postflight is run after the extension is registered in the database.
	*/
	public function postflight($type, $parent) {
		// define the following parameters only if it is an original install
		if ($type == 'install') { 
			//setting params default per componente
			$paramsDefArray = array ();
			// Template
			
			// General
			$params ['headers_accordion'] = '1';
			$params ['headers_menu_accordion_collapsed'] = '0';
			$params ['headers_topmenu_accordion_collapsed'] = '1';
			$params ['headers_accordion_collapsed'] = '0';
			$params ['explicit_logo_include'] = '0';
			$params ['home_icon_enabled'] = '0';
			$params ['inject_gajs'] = '0';
			$params ['show_messages'] = '0';
			$params ['module_background'] = '1';
			$params ['main_component_background'] = '1';
			$params ['show_print_email_icons'] = '0';
			$params ['transform_table_responsive'] = '0';
			$params ['responsive_elements_classname'] = 'responsive';
			$params ['tpl_load_jquery'] = '1';
			$params ['copyright_footer_html'] = '';
			$params ['tel_number'] = '';
			$params ['predefined_theme_0'] = '1';

			// Dimensions
			$params ['width'] = '1024px';
			$params ['width_threshold'] = '1024px';
			$params ['min_height'] = '380';
			$params ['width_menutop'] = '100';
			$params ['width_left'] = '20';
			$params ['width_right'] = '20';
			$params ['user1_width'] = '31';
			$params ['user2_width'] = '31';
			$params ['user3_width'] = '31';
			$params ['user4_width'] = '31';
			$params ['user5_width'] = '31';
			$params ['user6_width'] = '31';
			$params ['width_menubottom'] = '100';
			$params ['width_copyright'] = '100';

			// Margins and paddings
			$params['logo_top_padding'] = '2';
			$params['logo_bottom_padding'] = '2';
			$params['header_bottom_padding'] = '5';
			$params['menuside_top_padding'] = '50';
			$params['border_radius_elements'] = '3';
			$params['wrapper_site_padding'] = '2';
			$params['component_site_padding'] = '0';
			$params['footer_top_padding'] = '5';
			$params['footer_bottom_padding'] = '5';

			// Colors
			$params ['general_background'] = 'white';
			$params ['color_theme'] = 'blue';
			$params ['main_foreground_overridecolor'] = '0';
			$params ['main_foreground_color'] = '#6e6e6e';
			$params ['main_background_overridecolor'] = '0';
			$params ['main_background_color'] = '#f7f7f7';
			$params ['main_background_image_override'] = '0';
			$params ['main_background_image_repeat'] = '0';
			$params ['main_background_image_stretch'] = '0';
			$params ['main_background_image_element'] = 'body';
			$params ['main_background_image_stretch_height'] = '100%';
			$params ['override_titles_menu'] = '0';
			$params ['custom_titles_menu_color'] = '#333333';
			$params ['footer_background_enabled'] = '0';
			$params ['footer_background_color'] = '#333333';
			$params ['footer_foreground_color'] = '#FFFFFF';

			// Fonts
			$params ['font_content'] = 'Ubuntu';
			$params ['font'] = 'Ubuntu';
			$params ['fontSize'] = '1.00em';

			// Menu settings
			$params ['switcher_menutype'] = 'accordion';
			$params ['menuitem_width'] = '100';
			$params ['menuitem_vert_padding'] = '5';
			$params ['menuitem_horiz_padding'] = '10';
			$params ['submenuitem_splacement'] = '0';
			$params ['menutext_alignment'] = 'left';
			$params ['submenu_open_mode'] = 'relative';
			$params ['include_search_field'] = '1';
			$params ['transition_menu'] = '1';

			// Social icons
			$params ['enable_socials'] = '0';
			$params ['text_social_icons'] = '';
			$params ['facebook'] = '';
			$params ['google'] = '';
			$params ['twitter'] = '';
			$params ['linkedin'] = '';
			$params ['youtube'] = '';
			$params ['flickr'] = '';
			$params ['instagram'] = '';
			$params ['pinterest'] = '';
			$params ['tumblr'] = '';
			$params ['vimeo'] = '';
			$params ['delicious'] = '';
			$params ['skype'] = '';
			$params ['rss'] = '';

			// Social share
			$params ['social_sharer_enabled'] = '0';
			$params ['custom'] = '0';
			$params ['position'] = '2';
			$params ['showInArticles'] = '1';
			$params ['showInCategories'] = '0';
			$params ['showInSections'] = '0';
			$params ['showInFrontPage'] = '0';
			$params ['excludeSections'] = '';
			$params ['excludeCats'] = '';
			$params ['excludeArticles'] = '';
			$params ['includeArticles'] = '';
			$params ['facebookLikeButton'] = '1';
			$params ['facebookLikeAction'] = 'like';
			$params ['facebookLikeType'] = 'button_count';
			$params ['facebookLikeColor'] = 'light';
			$params ['facebookLikeFont'] = '';
			$params ['facebookLikeWidth'] = '100';
			$params ['facebookLikeAppId'] = '';
			$params ['facebookShareMeButton'] = '1';
			$params ['color_facebookShareMeBadgeText'] = '#FFFFFF';
			$params ['color_facebookShareMeBadge'] = '#3B5998';
			$params ['twitterButton'] = '1';
			$params ['twitterName'] = '0';
			$params ['twitterCounter'] = 'horizontal';
			$params ['linkedInButton'] = '1';
			$params ['linkedInType'] = 'right';
			$params ['plusButton'] = '1';
			$params ['plusType'] = 'medium';
			$params ['gshareButton'] = '1';
			$params ['shareAnnotation'] = 'bubble';
			$params ['pinterestButton'] = '1';

			// Plugin
			$params ['switch_enabled'] = '1';
			$params ['switch_always'] = '0';
			$params ['include_tablets'] = '1';
			$params ['native_app_feature'] = '0';
			$params ['offlinecache'] = '0';
			$params ['show_tpl_switcher'] = '0';
			$params ['desktop_fallback_cookie_lifetime'] = '10';
			$params ['template'] = 'responsivizer';
			$params ['include_compat_styles'] = '0';
			$params ['enable_debug'] = '0';
			$params ['plugin_lightimgs_status'] = '0';
			$params ['img_quality'] = '75';
			$params ['img_resizing_switcher'] = '0';
			$params ['img_resizing'] = '50';
			$params ['img_resizing_minwidth'] = '1280';
			$params ['img_support_gif'] = '0';
			$params ['mobile_homepage'] = '0';
			$params ['mobile_homepage_menuid'] = '0';

			// Module
			$params ['responsivizer_slideshow_enabled'] = '0';
			$params ['slideshow'] = '100';
			$params ['directionNav'] = 'default';
			$params ['pauseOnHover'] = 'true';
			$params ['initDelay'] = '0';
			$params ['randomize'] = 'false';
			$params ['target'] = '_blank';
			$params ['enable_minheight'] = '0';
			$params ['min_height'] = '380';
			$params ['images_auto_height'] = 'true';
			$params ['slide_theme'] = 'false';
			$params ['bg_color'] = '#ffffff';
			$params ['theme_shadow'] = 'theme-shadow-normal';
			$params ['theme_border'] = '00';
			$params ['theme_border_radius'] = '00';
			$params ['controlNav'] = 'true';
			$params ['positionNav'] = 'under';
			$params ['colorNav'] = 'dark';
			$params ['colorNavActive'] = 'black';
			$params ['transition'] = 'slide';
			$params ['direction'] = 'horizontal';
			$params ['easing'] = 'easeInQuart';
			$params ['animSpeed'] = '2000';
			$params ['pauseTime'] = '3000';
			$params ['bg_caption'] = 'black';
			$params ['transparency_caption'] = 'bottom';
			$params ['text_align'] = 'centered';
			$params ['color_caption'] = '#dddddd';
			$params ['imagespath'] = 'images/';
			$params ['image_1'] = '';
			$params ['imagealt_1'] = '';
			$params ['imagecaption_1'] = '';
			$params ['imagelink_1'] = '';
			 
			$result = $this->setParams ( $params ); 
			if (! $result) {  
				echo '<p>' . JText::_('COM_RESPONSIVIZER_ERROR_ASSIGNING_PARAMS') . '</p>'; 
			} 
		}
	}
	
	/*
	 * $parent is the class calling this method
	* uninstall runs before any other action is taken (file removal or database processing).
	*/
	public function uninstall($parent) { 
		$database = JFactory::getDBO ();
		$lang = JFactory::getLanguage();
		$lang->load('com_responsivizer');
		
		// STEP1 - Start uninstalling plugin
		if($this->comResponsivizerUninstallPlugin($database)) {
			echo '<p>' . JText::_('COM_RESPONSIVIZER_OK_UNINSTALLING_PLUGINS') . '</p>';
		}
		
		// STEP2 - Start uninstalling module
		if($this->comResponsivizerUninstallModule($database)){
			echo '<p>' . JText::_('COM_RESPONSIVIZER_OK_UNINSTALLING_MODULE') . '</p>';
		}
		
		// STEP3- Start uninstalling module
		if($this->comResponsivizerUninstallModuleSwitcher($database)){
			echo '<p>' . JText::_('COM_RESPONSIVIZER_OK_UNINSTALLING_MODULE_SWITCHER') . '</p>';
		}
		
		// STEP4 - Start uninstalling template
		if($this->comResponsivizerUninstallTemplate($database)) {
			echo '<p>' . JText::_('COM_RESPONSIVIZER_OK_UNINSTALLING_TEMPLATE') . '</p>';
		}
		
		echo '<p>' . JText::_('COM_RESPONSIVIZER_OK_UNINSTALLING_COMPONENT') . '</p>';
		
		// Processing complete
		return true;
	}
	
	/*
	 * get a variable from the manifest file (actually, from the manifest cache).
	*/
	public function getParam($name) {
		$db = JFactory::getDbo ();
		$db->setQuery ( 'SELECT manifest_cache FROM #__extensions WHERE name = "responsivizer"' );
		$manifest = json_decode ( $db->loadResult (), true );
		return $manifest [$name];
	}
	
	/*
	 * sets parameter values in the component's row of the extension table
	*/
	public function setParams($param_array) {
		$updated = true;
		if (count ( $param_array ) > 0) {
			$db = JFactory::getDbo ();
			// store the combined new and existing values back as a JSON string
			$paramsString = json_encode ( $param_array );
			$db->setQuery ( 'UPDATE #__extensions SET params = ' . $db->quote ( $paramsString ) . ' WHERE name = "responsivizer"' );
			$updated = $db->execute ();
		}
		return $updated;
	} 
	
	/*
	 * Class constructor
	 */
	public function __construct() {
		$dbInstanceTest = JFactory::getDbo();
		$this->quotingFunction = null;
		
		// Test 1.6 or 3.0
		if(method_exists($dbInstanceTest, 'quoteName')) {
			$this->quotingFunction = 'quoteName';
		} else {
			$this->quotingFunction = 'NameQuote';
		}
	}
} 