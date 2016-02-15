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

// Access check.
if (!JFactory::getUser()->authorise('core.manage', 'com_sptransfer')) 
{
	return JError::raiseWarning(404, JText::_('JERROR_ALERTNOAUTHOR'));
}

//import SP CYEND libraries and language
JFactory::getLanguage()->load('lib_spcyend', JPATH_SITE);
jimport('spcyend.utilities.factory');
jimport('spcyend.database.source');

// require helper file
JLoader::register('SPTransferHelper', dirname(__FILE__) . DIRECTORY_SEPARATOR . 'helpers' . DIRECTORY_SEPARATOR . 'sptransfer.php');

// import joomla controller library
jimport('joomla.application.component.controller'); 

// Get an instance of the controller prefixed by SPTransfer
$controller = JControllerLegacy::getInstance('SPTransfer');

// Perform the Request task
$controller->execute(JFactory::getApplication()->input->get('task'));

// Redirect if set by the controller
$controller->redirect();
