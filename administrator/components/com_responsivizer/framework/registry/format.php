<?php
// namespace components\com_responsivizer\libraries\framework\registry;
/**
 * @package RESPONSIVIZER::FRAMEWORK::administrator::components::com_responsivizer
 * @subpackage framework
 * @subpackage registry
 * @author Joomla! Extensions Store
 * @copyright (C) 2014 - Joomla! Extensions Store
 * @license GNU/GPLv2 http://www.gnu.org/licenses/gpl-2.0.html
 */
defined('_JEXEC') or die('Restricted access');

/**
 * Responsivizer Exception object
 *
 * @package RESPONSIVIZER::FRAMEWORK::administrator::components::com_responsivizer
 * @subpackage framework
 * @subpackage registry
 * @since 2.0
 */
abstract class ResponsivizerRegistryFormat {
	/**
	 * Instances container
	 * 
	 * @var array
	 */
	public static $registryFormats = array();
	
	/**
	 * Singleton abstract factory
	 * 
	 * @access public
	 * @param $format
	 */
	public static function getInstance($format) {
		if (!isset(self::$registryFormats[$format])) {
			$className = 'ResponsivizerRegistryFormat' . ucfirst($format);
			self::$registryFormats[$format] = new $className();
		}
		return self::$registryFormats[$format];
	}

	/**
	 * @access public
	 * @param $object
	 */
	public abstract function objectToString($object);

	/**
	 * @access public
	 * @param $data
	 */
	public abstract function stringToObject($data);
}