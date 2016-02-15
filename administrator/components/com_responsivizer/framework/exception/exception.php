<?php
// namespace components\com_responsivizer\libraries\framework\exception;
/**
 * @package RESPONSIVIZER::FRAMEWORK::administrator::components::com_responsivizer
 * @subpackage framework
 * @subpackage exception
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
 * @subpackage exception
 * @since 2.0
 */
class ResponsivizerException extends Exception {
	/**
	 * Error level
	 * @access private
	 * @var string
	 */
	private $errorLevel;
	
	/**
	 * Error level accessor method
	 * @access public
	 * @return string
	 */
	public function getErrorLevel() {
		return $this->errorLevel;
	}
	
	/**
	 * Class constructor
	 * @access public
	 * @return Object&
	 */
	public function __construct($message, $level, $code = null) {
		parent::__construct($message, $code);
	
		// Set error level
		$this->errorLevel = $level;
	}
}