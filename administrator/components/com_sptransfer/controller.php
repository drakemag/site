<?php

/**
 * @package		SP Transfer
 * @subpackage	Components
 * @copyright	SP CYEND - All rights reserved.
 * @author		SP CYEND
 * @link		http://www.cyend.com
 * @license GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
*/

// No direct access to this file
defined('_JEXEC') or die('Restricted access');

// import Joomla controller library
jimport('joomla.application.component.controller');

/**
 * General Controller of SPTransfer component
 */
class SPTransferController extends JControllerLegacy
{
	/**
	 * display task
	 *
	 * @return void
	 */
	function display($cachable = false, $urlparams = Array()) 
	{
	
		// set default view if not set
		JRequest::setVar('view', JRequest::getCmd('view', 'tables'));

		// call parent behavior
		parent::display($cachable);

		$view = JRequest::setVar('view', JRequest::getCmd('view', 'tables'));
                SPTransferHelper::addSubmenu($view   );
	}
}
