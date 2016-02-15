<?php
/**
 * @version     2.0.0
 * @package     com_storelocator
 * @copyright   Copyright (C) 2013. Sysgen Media LLC. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 * @author      Sysgen Media <support@sysgenmedia.com> - http://www.sysgenmedia.com
 */

defined('_JEXEC') or die;

// Include dependancies
jimport('joomla.application.component.controller');
require_once JPATH_COMPONENT.'/helpers/storelocator.php';


// Execute the task.
$controller	= JControllerLegacy::getInstance('Storelocator');
$controller->execute(JFactory::getApplication()->input->get('task'));
$controller->redirect();
