<?php
// namespace administrator\components\com_responsivizer\framework\http;
/**
 * @package RESPONSIVIZER::FRAMEWORK::administrator::components::com_responsivizer
 * @subpackage framework
 * @subpackage http
 * @author Joomla! Extensions Store
 * @copyright (C) 2014 - Joomla! Extensions Store
 * @license GNU/GPLv2 http://www.gnu.org/licenses/gpl-2.0.html
 */
defined('_JEXEC') or die('Restricted access');
jimport('joomla.application.component.model');

/**
 * HTTP response object
 * 
 * @package RESPONSIVIZER::FRAMEWORK::administrator::components::com_responsivizer
 * @subpackage framework
 * @subpackage http
 * @since 2.0
 */
class ResponsivizerHttpResponse {
	/**
	 * @var    integer  The server response code.
	 * @since  11.3
	 */
	public $code;

	/**
	 * @var    array  Response headers.
	 * @since  11.3
	 */
	public $headers = array();

	/**
	 * @var    string  Server response body.
	 * @since  11.3
	 */
	public $body;
}
