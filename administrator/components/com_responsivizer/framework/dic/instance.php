<?php
// namespace components\com_responsivizer\libraries\framework\dic;
/**
 *
 * @package RESPONSIVIZER::administrator::components::com_responsivizer
 * @subpackage framework
 * @subpackage dic
 * @author Joomla! Extensions Store
 * @copyright (C) 2014 - Joomla! Extensions Store
 * @license GNU/GPLv2 http://www.gnu.org/licenses/gpl-2.0.html  
 */
defined('_JEXEC') or die('Restricted access');

/**
 * Base controller class
 * 
 * @package RESPONSIVIZER::administrator::components::com_responsivizer
 * @subpackage framework
 * @subpackage dic
 * @since 2.0
 */
class ResponsivizerDicInstance {
	public $name;
	public function __construct($instance) {
		$this->name = $instance;
	}
}
