<?php
/**
 * @version     2.0.0
 * @package     com_storelocator
 * @copyright   Copyright (C) 2013. Sysgen Media LLC. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 * @author      Sysgen Media <support@sysgenmedia.com> - http://www.sysgenmedia.com
 */

// No direct access.
defined('_JEXEC') or die;

require_once JPATH_COMPONENT.'/controller.php';

/**
 * Importexport list controller class.
 */
class StorelocatorControllerImportexport extends StorelocatorController
{
	/**
	 * Proxy for getModel.
	 * @since	1.6
	 */
	public function &getModel($name = 'Importexport', $prefix = 'StorelocatorModel')
	{
		$model = parent::getModel($name, $prefix, array('ignore_request' => true));
		return $model;
	}
}