<?php
// namespace components\com_responsivizer\libraries\framework\exception;
/**
 * @package RESPONSIVIZER::FRAMEWORK::administrator::components::com_responsivizer
 * @subpackage framework
 * @subpackage language
 * @author Joomla! Extensions Store
 * @copyright (C) 2014 - Joomla! Extensions Store
 * @license GNU/GPLv2 http://www.gnu.org/licenses/gpl-2.0.html
 */
defined('_JEXEC') or die('Restricted access');
jimport('joomla.language.language');

/**
 * Responsivizer Exception object
 *
 * @package RESPONSIVIZER::FRAMEWORK::administrator::components::com_responsivizer
 * @subpackage framework
 * @subpackage language
 * @since 2.1
 */
class ResponsivizerLanguageMultilang extends JLanguage {
	/**
	 * Method to determine if the language filter plugin is enabled.
	 * This works for both site and administrator.
	 *
	 * @return  boolean  True if site is supporting multiple languages; false otherwise.
	 *
	 * @since   2.5.4
	 */
	public static function isEnabled() {
		// Flag to avoid doing multiple database queries.
		static $tested = false;

		// Status of language filter plugin.
		static $enabled = false;

		// Get application object.
		$app = JFactory::getApplication();

		// If being called from the front-end, we can avoid the database query.
		if ($app->isSite()) {
			$enabled = $app->getLanguageFilter();
			return $enabled;
		}

		// If already tested, don't test again.
		if (!$tested) {
			// Determine status of language filter plug-in.
			$db = JFactory::getDBO();
			$query = $db->getQuery(true);

			$query->select('enabled');
			$query->from($db->quoteName('#__extensions'));
			$query->where($db->quoteName('type') . ' = ' . $db->quote('plugin'));
			$query->where($db->quoteName('folder') . ' = ' . $db->quote('system'));
			$query->where($db->quoteName('element') . ' = ' . $db->quote('languagefilter'));
			$db->setQuery($query);

			$enabled = $db->loadResult();
			$tested = true;
		}

		return $enabled;
	}
	
	/** 
	 * Load language ID
	 * 
	 * @access public
	 * @param string $languagTag
	 * @return int
	 * 
	 */
	public static function loadLanguageID($languageTag) {
		// Determine status of language filter plug-in.
		$db = JFactory::getDBO();
		$query = $db->getQuery(true);
		
		$query->select('lang_id');
		$query->from($db->quoteName('#__languages'));
		$query->where($db->quoteName('lang_code') . ' = ' . $db->quote($languageTag));
		$db->setQuery($query);
		
		$langID = $db->loadResult();
		return $langID;
	}
	
	/**
	 * Override Language instantiator
	 *
	 * @access	public
	 * @return	JLanguage  The Language object.
	 * @since	1.5
	 */
	public static function getInstance($lang = null, $debug = false) {
		$conf	= JFactory::getConfig();
		
		if(is_null($lang)) {
			$locale = $conf->get('config.language');
		} else {
			$locale = $lang;
		}
		
		$langInstance = new ResponsivizerLanguageMultilang($locale);
		$langInstance->setDebug($conf->get('config.debug_lang'));
		
		return $langInstance;
	}
}
