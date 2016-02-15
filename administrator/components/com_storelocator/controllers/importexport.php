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

jimport('joomla.application.component.controlleradmin');

/**
 * Importexport list controller class.
 */
class StorelocatorControllerImportexport extends JControllerAdmin
{
	/**
	 * Proxy for getModel.
	 * @since	1.6
	 */
	public function getModel($name = 'importexport', $prefix = 'StorelocatorModel', $config=array())
	{
		$model = parent::getModel($name, $prefix, array('ignore_request' => true));
		return $model;
	}
    
    
	/**
	 * Method to save the submitted ordering values for records via AJAX.
	 *
	 * @return  void
	 *
	 * @since   3.0
	 */
	public function saveOrderAjax()
	{
		// Get the input
		$input = JFactory::getApplication()->input;
		$pks = $input->post->get('cid', array(), 'array');
		$order = $input->post->get('order', array(), 'array');

		// Sanitize the input
		JArrayHelper::toInteger($pks);
		JArrayHelper::toInteger($order);

		// Get the model
		$model = $this->getModel();

		// Save the ordering
		$return = $model->saveorder($pks, $order);

		if ($return)
		{
			echo "1";
		}

		// Close the application
		JFactory::getApplication()->close();
	}
	
	
	/**
	 * save a record (and redirect to main page)
	 * @return void
	 */
	function import()
	{
		$params = JComponentHelper::getParams( 'com_storelocator' );
		$auto_detect_line_endings = $params->get( 'auto_detect_line_endings', 1 );
		
		ini_set("auto_detect_line_endings", $auto_detect_line_endings);
		
		$model = $this->getModel();
		$locationModel = $this->getModel('location');
		$msg = $model->importCSV($locationModel);

		$link = 'index.php?option=com_storelocator&view=importexport';
		$this->setRedirect($link, $msg);
	}
	
	/**
	 * save a record (and redirect to main page)
	 * @return void
	 */
	function export()
	{
		$model = $this->getModel();
		$locationsModel = $this->getModel('locations');
		$msg = $model->exportCSV($locationsModel);
		
		$link = 'index.php?option=com_storelocator&view=importexport';
		$this->setRedirect($link, $msg);
	}
	
	/**
	 * save a record (and redirect to main page)
	 * @return void
	 */
	function sample()
	{
		$model = $this->getModel();
		$msg = $model->exportSample();
		
		$link = 'index.php?option=com_storelocator&view=importexport';
		$this->setRedirect($link, $msg);
	}
	
    
    
    
}