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
 * Batchgeocoding list controller class.
 */
class StorelocatorControllerBatchgeocoding extends JControllerAdmin
{
	/**
	 * Proxy for getModel.
	 * @since	1.6
	 */
	public function getModel($name = 'batchgeocoding', $prefix = 'StorelocatorModel', $config=array())
	{
		$model = parent::getModel($name, $prefix, array('ignore_request' => true));
		return $model;
	}
	
	
	
	public function geocode()
	{
		set_time_limit(240);
		
		$app = JFactory::getApplication();
				
		// Check URL allow fopen
		if (!ini_get('allow_url_fopen'))
		{
			$msg = "You have disabled 'allow_url_fopen' in your PHP Settings. This is required for the Geocoding function to contact Google. </li>
					<li>For more information see: http://www.php.net/manual/en/filesystem.configuration.php";
			
			$app->enqueueMessage($msg, 'error');
			return;
		}


		// Initialize delay in geocode speed
		$delay = 0;
		$good_encode = 0;
		$error_encode = array();
		
		$model = $this->getModel();
		$localBatch = $model->getItems();
		$params 	= JComponentHelper::getParams('com_storelocator');
		
			
		$base_url = sprintf('https://maps.google.com/maps/api/geocode/json?region=%s&sensor=false&address=', 
					$params->get( 'base_country', '' ) );
		
	
		
		// Iterate through the rows, geocoding each address
		foreach($localBatch as $location)
		{
			$id = $location->id;
			$address = $location->address;
			
			$dataResponse = file_get_contents($base_url . urlencode($address)); 
			
			// Error Check
			if ($dataResponse === false)
			{
				$msg = "An unknown error occured in attempting to contact Google from your server. This is required for the Geocoding function to contact Google.";
				$app->enqueueMessage($msg, 'error');
				return;
			}
			
			
			$data = json_decode($dataResponse);
			
			
			switch($data->status) // Check that its Good to Go
			{
				case "OK": // Good Result, Save Results
					$result = $this->_storeLocation($id, $data->results[0]->geometry->location->lat, $data->results[0]->geometry->location->lng);
					
					if($result)
						$good_encode++;
					else
						$error_encode[] = array('location' => $location, 'error' => 'Error Saving Location.');
					break;
	
				case "ZERO_RESULTS": // Nothing Found
					$error_encode[] = array('location' => $location, 'error' => 'No Results Found for Address');
					break;
				case "REQUEST_DENIED": // Why?
					$error_encode[] = array('location' => $location, 'error' => 'Request Denied');
					break;
				case "INVALID_REQUEST": // Prob Missing Address
					$error_encode[] = array('location' => $location, 'error' => 'Invalid Address');
					break;
				
				case "OVER_QUERY_LIMIT": 
					$msg = "You are over your Daily Quota of 2,500 Geocode Requests. "
							."( <a href=\"http://code.google.com/apis/maps/faq.html#geocoder_limit\" target=\"_blank\">See Google Geocoding Web Service Terms of Use</a> )";
					$app->enqueueMessage($msg, 'error');
					
					$link = 'index.php?option=com_storelocator&view=batchgeocoding';
					$msg2 .= "Processed: $good_encode locations. &nbsp; Errors: ".count($error_encode)." locations.";
					if (count($error_encode))
						foreach($error_encode as $error)
							$msg2 .= sprintf("</li><li>Error: %s - Location ID: %d / Name: %s ", $error['error'], $error['location']->id, $error['location']->name);
					$this->setRedirect($link, $msg2, count($error_encode)>0?'notice':'message');
					return;
			}
			
			if ($good_encode + count($error_encode) > 500) // Max Batch Size
			{
				$msg = "Maximum Batch Size of 500 Requestes Reached, Please Run Again to Complete Geocoding";
				$app->enqueueMessage($msg, 'error');
				
				$link = 'index.php?option=com_storelocator&view=batchgeocoding';
				$msg2 .= "Processed: $good_encode locations. &nbsp; Errors: ".count($error_encode)." locations.";
				if (count($error_encode))
					foreach($error_encode as $error)
						$msg2 .= sprintf("</li><li>Error: %s - Location ID: %d / Name: %s ", $error['error'], $error['location']->id, $error['location']->name);
				$this->setRedirect($link, $msg2, count($error_encode)>0?'notice':'message');
				return;
			
			}
			
			usleep(150000);
		}
		$msg = "Geocoding Complete! Processed: $good_encode locations. &nbsp; Errors: ".count($error_encode)." locations.";
		
		if (count($error_encode))
			foreach($error_encode as $error)
				$msg .= sprintf("</li><li>Error: %s - Location ID: %d / Name: %s ", $error['error'], $error['location']->id, $error['location']->name);
		
		$link = 'index.php?option=com_storelocator&view=batchgeocoding';
		$this->setRedirect($link, $msg, count($error_encode)>0?'notice':'message');
		
	}
	
	protected function _storeLocation($id, $lat, $long)
	{
		$location = $this->getModel('location');
		return $location->setCoords($id, $lat, $long);
		
	}
	
}