<?php
/**
 * @version		$Id: constants.php 01 2011-01-11 11:37:09Z maverick $
 * @package		CoreJoomla.Polls
 * @subpackage	Components.site
 * @copyright	Copyright (C) 2009 - 2012 corejoomla.com, Inc. All rights reserved.
 * @author		Maverick
 * @link		http://www.corejoomla.com/
 * @license		License GNU General Public License version 2 or later
 */

defined('_JEXEC') or die('Restricted access');

// Please do not touch these until and unless you know what you are doing.
define('CP_CURR_VERSION',  					'4.2.5');
define('CP_CJLIB_VER',						'2.2.1');

define('P_COOKIE_PREFIX',					'jcp_poll_');
define('P_MEDIA_PATH',						JPATH_ROOT.'/media/com_communitypolls/' );
define('P_MEDIA_URI',						JURI::base(false).'/media/com_communitypolls/' );
define('P_IMAGES_UPLOAD_DIR',				JPATH_ROOT.'/media/communitypolls/images');
define('P_IMAGES_URI',						JURI::root(false).'media/communitypolls/images/');
define('P_CACHE_STORE',						JPATH_ROOT.'/media/communitypolls/cache');
define('P_CACHE_STORE_URI',					JURI::root(false).'media/communitypolls/cache/');
define('P_TEMP_STORE',						JPATH_ROOT.'/media/communitypolls/tmp');
define('P_TEMP_STORE_URI',					JURI::root(false).'media/communitypolls/tmp/');
?>