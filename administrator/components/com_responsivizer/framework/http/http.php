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
 * HTTP connector client object interface
 *
 * @package RESPONSIVIZER::FRAMEWORK::administrator::components::com_responsivizer
 * @subpackage framework
 * @subpackage http
 * @since 2.0
 */
interface IResponsivizerHttp {
	/**
	 * Method to send the GET command to the server.
	 *
	 * @param   string  $url      Path to the resource.
	 * @param   array   $headers  An array of name-value pairs to include in the header of the request.
	 *
	 * @return  ResponsivizerHttpResponse
	 *
	 * @since   11.3
	 */
	public function get($url, array $headers = null);
}


/**
 * HTTP connector client object concrete implementation
 * 
 * @package RESPONSIVIZER::FRAMEWORK::administrator::components::com_responsivizer
 * @subpackage framework
 * @subpackage http
 * @since 2.0
 */
class ResponsivizerHttp implements IResponsivizerHttp {
	/**
	 * Number of requests placed
	 * @var    Int 
	 * @since  11.3
	 */
	protected $numRequests;

	/**
	 * @var    JRegistry  Options for the HTTP client.
	 * @since  11.3
	 */
	protected $options;

	/**
	 * @var    ResponsivizerHttpTransport  The HTTP transport object to use in sending HTTP requests.
	 * @since  11.3
	 */
	protected $transport;

	/**
	 * Component params
	 * @var    Object&
	 * @access protected
	 */
	protected $cParams;
	
	/**
	 * Application object
	 * @var    Object&
	 * @access protected
	 */
	protected $app;

	/**
	 * Constructor.
	 *
	 * @param   ResponsivizerHttpTransport  $transport  The HTTP transport object.
	 * @param   $cParams Object& Component configuration
	 *
	 * @since   11.3
	 */
	public function __construct(ResponsivizerHttpTransport $transport = null, &$cParams = null) {
		$this->numRequests = 0;
		$this->cParams = $cParams;
		$this->app = JFactory::getApplication();

		$this->transport = isset($transport) ? $transport : new ResponsivizerHttpTransportSocket($this->options);
	}

	/**
	 * Method to send the OPTIONS command to the server.
	 *
	 * @param   string  $url      Path to the resource.
	 * @param   array   $headers  An array of name-value pairs to include in the header of the request.
	 *
	 * @return  ResponsivizerHttpResponse
	 *
	 * @since   11.3
	 */
	public function options($url, array $headers = null) {
		$this->numRequests++;
		return $this->transport->request('OPTIONS', new JUri($url), null, $headers);
	}

	/**
	 * Method to send the HEAD command to the server.
	 *
	 * @param   string  $url      Path to the resource.
	 * @param   array   $headers  An array of name-value pairs to include in the header of the request.
	 *
	 * @return  ResponsivizerHttpResponse
	 *
	 * @since   11.3
	 */
	public function head($url, array $headers = null) {
		$this->numRequests++;
		return $this->transport->request('HEAD', new JUri($url), null, $headers);
	}

	/**
	 * Method to send the GET command to the server.
	 *
	 * @param   string  $url      Path to the resource.
	 * @param   array   $headers  An array of name-value pairs to include in the header of the request.
	 *
	 * @return  ResponsivizerHttpResponse
	 *
	 * @since   11.3
	 */
	public function get($url, array $headers = null) {
		$this->numRequests++;
		return $this->transport->request('GET', new JUri($url), null, $headers);
	}

	/**
	 * Method to send the POST command to the server.
	 *
	 * @param   string  $url      Path to the resource.
	 * @param   mixed   $data     Either an associative array or a string to be sent with the request.
	 * @param   array   $headers  An array of name-value pairs to include in the header of the request.
	 *
	 * @return  ResponsivizerHttpResponse
	 *
	 * @since   11.3
	 */
	public function post($url, $data, array $headers = null) {
		$this->numRequests++;
		return $this->transport->request('POST', new JUri($url), $data, $headers);
	}

	/**
	 * Method to send the PUT command to the server.
	 *
	 * @param   string  $url      Path to the resource.
	 * @param   mixed   $data     Either an associative array or a string to be sent with the request.
	 * @param   array   $headers  An array of name-value pairs to include in the header of the request.
	 *
	 * @return  ResponsivizerHttpResponse
	 *
	 * @since   11.3
	 */
	public function put($url, $data, array $headers = null) {
		$this->numRequests++;
		return $this->transport->request('PUT', new JUri($url), $data, $headers);
	}

	/**
	 * Method to send the DELETE command to the server.
	 *
	 * @param   string  $url      Path to the resource.
	 * @param   array   $headers  An array of name-value pairs to include in the header of the request.
	 *
	 * @return  ResponsivizerHttpResponse
	 *
	 * @since   11.3
	 */
	public function delete($url, array $headers = null) {
		$this->numRequests++;
		return $this->transport->request('DELETE', new JUri($url), null, $headers);
	}

	/**
	 * Method to send the TRACE command to the server.
	 *
	 * @param   string  $url      Path to the resource.
	 * @param   array   $headers  An array of name-value pairs to include in the header of the request.
	 *
	 * @return  ResponsivizerHttpResponse
	 *
	 * @since   11.3
	 */
	public function trace($url, array $headers = null) {
		$this->numRequests++;
		return $this->transport->request('TRACE', new JUri($url), null, $headers);
	}
}
