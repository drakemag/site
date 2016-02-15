<?php
/**
 * @version     2.0.0
 * @package     com_storelocator
 * @copyright   Copyright (C) 2013. Sysgen Media LLC. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 * @author      Sysgen Media <support@sysgenmedia.com> - http://www.sysgenmedia.com
 */

defined('_JEXEC') or die;

jimport( 'joomla.application.component.model' );

/**
 * Methods supporting a list of Storelocator records.
 */
class StorelocatorModelimportexport extends JModelLegacy
{

    /**
	 * LocatePlaces data array
	 *
	 * @var array
	 */
	var $_cats;


	/**
     * Constructor.
     *
     * @param    array    An optional associative array of configuration settings.
     * @see        JController
     * @since    1.6
     */
    public function __construct($config = array())
    {
        
        parent::__construct($config);
    }
	
	
	/**
	 * Returns the query
	 * @return string The query to be used to retrieve the rows from the database
	 */
	protected function _buildCatsQuery()
	{
		$query = ' SELECT * FROM #__storelocator_cats ORDER BY name DESC ';

		return $query;
	}

	/**
	 * Retrieves the data
	 * @return array Array of objects containing the data from the database
	 */
	public function getCategories()
	{
		// Lets load the data if it doesn't already exist
		if (empty( $this->_cats ))
		{
			$query = $this->_buildCatsQuery();
			$this->_cats = $this->_getList( $query );
		}

		return $this->_cats;
	}
	
	
	public function importCSV($model)
	{
		@ini_set('memory_limit', '512M');
		
		
		$post = JFactory::getApplication()->input->post;
		$csvfile = JFactory::getApplication()->input->files->get( 'csvfile' );
		$user = JFactory::getUser();
		
		$skipfirst = (bool)$post->get('skipfirst', false);		
		
		// Error Check Basic PHP issues
		if ($csvfile['error'] !=0)
			return $this->file_upload_error_message($csvfile['error']);

		// Import CSV File to an Array
		$row = 0;
		$locations = array();
		if (($handle = fopen($csvfile['tmp_name'], "r")) !== FALSE) {
			
			setlocale(LC_ALL, 'en_US.UTF-8');

			if ($skipfirst)
				$data = fgetcsv($handle, 0, ","); // Skip First Line of Data
				
			while (($data = fgetcsv($handle, 0, ",")) !== FALSE) {
				//$data = preg_replace ('/([\x80-\xff])/se', "pack (\"C*\", (ord ($1) >> 6) | 0xc0, (ord ($1) & 0x3f) | 0x80)", $data);
				$locations[] = $data;
				$row++;
			}
			fclose($handle);
		}
				
		$rowCount = 0;
		$importErrors = array();
		
		// Foreach Line, Store an Entry...
		foreach($locations as $location)
		{
						
			$rowCount++;
			
			if(count($location) < 18) // Must have at least 18 columns
			{
				$importErrors[] = "Error Importing Row: $rowCount - Incorrect number of columns. Make sure you Have 18+ Columns";
				continue;
			}
			
			// Translate the Columns into Fields
			$data = array();
			$data['name'] 		= $location[0];
			$data['address'] 	= $location[1];
			$data['phone'] 		= $location[2]; 
			$data['website'] 	= $location[3];
			$data['lat'] 		= $location[4];
			$data['long']		= $location[5];
			$data['catid'] 		= $this->_getImportCat($location[6]);
			$data['email'] 		= $location[7];
			$data['facebook'] 	= $location[8];
			$data['twitter'] 	= $location[9];
			$data['cust1'] 		= $location[10];
			$data['cust2'] 		= $location[11];
			$data['cust3'] 		= $location[12];
			$data['cust4'] 		= $location[13];
			$data['cust5'] 		= $location[14];
			$data['tags'] 		= $this->_getImportTags($location[15]);
			$data['featured']	= intval($location[16]) ? 1 : 0;
			$data['state'] 	= intval($location[17]) ? 1 : 0;
			
			$data['access'] = 1; // Import as Public	
			$data['created_by'] = $user->id; // Import as current user
			
			
			if (isset($location[18]) && !empty($location[18]) )
				$data['publish_up'] = $location[18];
			
			if (isset($location[19]) && !empty($location[19]) )
				$data['publish_down'] = $location[19];
			
			if (isset($location[20]))
				$data['description'] = $location[20];
			else
				$data['description'] = '';
			
			if ($data['catid'] == -1)
			{
				$importErrors[] = "Error Importing Row: $rowCount - Category Not Specified or Could Not Be Created.";
				continue;
			}
			
			
			$table = $model->getTable();
			
						
		
			// Attempt to save the data.
			if (!$table->save($data))
			{
				
		
				// Redirect back to the edit screen.
				$this->setError(JText::sprintf('JLIB_APPLICATION_ERROR_SAVE_FAILED', $model->getError()));
				$this->setMessage($this->getError(), 'error');
				$importErrors[] = "Error Importing Row: $rowCount - Could Not Save Row to DB.";
				
				return false;
			}
			
	
			
		}

		$goodImports = $row - count($importErrors);
		
		$msg = "Found: $row locations. Imported: $goodImports locations. Errors: ".count($importErrors)." locations.";
		
		if (count($importErrors) > 0)
			$msg .= '</li><li>'. implode('</li><li>',$importErrors);
		
		return $msg;
	
	}
	
	protected function _getImportCat($cat)
	{
		$db = JFactory::getDBO();
		
		if (empty($cat))
			return '';
			
			
		$categories = array();
		$inputs = explode(',',$cat);
						
		foreach($inputs as $input)
		{	
			$input = trim($input);
		
			if(is_numeric($input))
			{
				$query = 'SELECT count(*) FROM #__storelocator_cats WHERE id = '.intval($input);
				$db->setQuery($query);
				$result = $db->loadResult();
				
				if($result)
					$categories[]=intval($input);
					
			} else {
				$query = 'SELECT id FROM #__storelocator_cats WHERE name = '.$db->quote($input).' limit 1';
				$db->setQuery($query);
				$result = $db->loadResult();
				
				if($result)
				{
					$categories[]=intval($result);
				}
				else
				{
					// No String or ID Found...  Insert New Cat and return ID
					$query = 'INSERT INTO #__storelocator_cats SET name = '.$db->quote(trim($input));
					$db->setQuery($query);
					$db->execute();
					$newid = $db->insertid();
		
					if($newid)
						$categories[]=intval($newid);
				}
			}
		}
				
		if (count($categories)==0) // Cant find vaild Cat, so error
			return '';
			
		return $categories;
	}
	
	protected function _getImportTags($tag)
	{
		$db = JFactory::getDBO();
		
		if (empty($tag))
			return -1;
			
			
		$tags = array();
		$inputs = explode(',',$tag);
		
		foreach($inputs as $input)
		{	
			$input = trim($input);
		
			if(is_numeric($input))
			{
				$query = 'SELECT count(*) FROM #__storelocator_tags WHERE id = '.intval($input);
				$db->setQuery($query);
				$result = $db->loadResult();
				
				if($result)
					$tags[]=intval($input);
					
			} else {
				$query = 'SELECT id FROM #__storelocator_tags WHERE name = '.$db->quote($input).' limit 1';
				$db->setQuery($query);
				$result = $db->loadResult();
				
				if($result)
				{
					$tags[]=intval($result);
				} else {
					// No String or ID Found...  Insert New Cat and return ID
					$query = 'INSERT INTO #__storelocator_tags SET name = '.$db->quote(trim($input));
					$db->setQuery($query);
					$db->execute();
					$newid = $db->insertid();
		
					if($newid)
						$tags[]=intval($newid);
				}
			}
		}
		
		if (count($tags)==0) // Cant find vaild Cat, so error
			return -1;
			
		return $tags;
	}
	
	
	public function exportCSV($locationsModel)
	{

		$post = JFactory::getApplication()->input->post;
		$exportcats = $post->get('exportcats', '', 'ARRAY' );
		if(!isset($exportcats) || count($exportcats)==0)
			return 'Error: You must choose which categories to export';
		
		if ( !is_array($exportcats) )
			$exportcats = array($exportcats);
		
		
		$locations = $locationsModel->getItems();		
					
		setlocale(LC_ALL, 'en_US.UTF-8');	
		header("Cache-Control: must-revalidate, post-check=0, pre-check=0");		
		header('Content-Type: text/csv; charset=utf-8');
		header('Content-Disposition: attachment; filename=export.csv');
		
		// create a file pointer connected to the output stream
		$output = fopen('php://output', 'w');
		
		// output the column headings
		fputcsv($output, $this->_getFields());
		
						
		foreach($locations as $location)
		{			
			// Check if category
			$valid = false;
			
			if ( $location->state != -2 && count($cats = explode(',',$location->catid)) > 0)
				foreach($cats as $cat)
					if (in_array($cat, $exportcats))
						$valid = true;
			
			if (!$valid)
				continue;
		
			$row = array(	$location->name, $location->address, $location->phone, $location->website, $location->lat, $location->long, $location->catid, 
							$location->email, $location->facebook, $location->twitter, $location->cust1, $location->cust2, $location->cust3, $location->cust4, 
							$location->cust5, $location->tags, intval($location->featured), intval($location->state), $location->publish_up, $location->publish_down, $location->description );
			
			fputcsv($output, $row);
		}
		
		exit;
	}
	
	
	
	public function exportSample()
	{
		setlocale(LC_ALL, 'en_US.UTF-8');	
		header("Cache-Control: must-revalidate, post-check=0, pre-check=0");		
		header('Content-Type: text/csv; charset=utf-8');
		header('Content-Disposition: attachment; filename=export.csv');
		
		// create a file pointer connected to the output stream
		$output = fopen('php://output', 'w');
		
		// output the column headings
		fputcsv($output, $this->_getFields());
						
		$row = array(	"Sample Name", 
						"123 Main St, Anytown NY 11743", 
						"123-456-7890", 
						"http://www.example.com", 
						"", 
						"", 
						"General", 
						"test@example.com", 
						"http://www.facebook.com/12345", 
						"sysgenmedia",
					 	"Example 1", 
					 	"Example 2", 
						"Example 3", 
						"Example 4", 
						"Example 5", 
						"Joomla, Extensions", 
						0, 
						1, 
						"", 
						"", 
						"<p>This is a cool place</p>" 
				);
			
		fputcsv($output, $row);
		exit;
	}
	
	protected function _getFields()
	{
		return array('Name','Address','Phone','Website','Latitude','Longitude','Category','Email','Facebook',
								'Twitter','Custom1','Custom2','Custom3','Custom4','Custom5','Tags','Featured','Published','Start Publishing','End Publishing','Description');
	}
	
	public function exportLogs($logsModel)
	{

		$post = JFactory::getApplication()->input->post;
		
		$logs = $logsModel->getData();
			
		setlocale(LC_ALL, 'en_US.UTF-8');	
		header("Cache-Control: must-revalidate, post-check=0, pre-check=0");		
		header('Content-Type: text/csv; charset=utf-8');
		header('Content-Disposition: attachment; filename=logs.csv');
		
		// create a file pointer connected to the output stream
		$output = fopen('php://output', 'w');
		
		// output the column headings
		fputcsv($output, array('ipaddress','lat','long','limited','search_time','query'));
		
		foreach($logs as $log)
		{			
			$row = array(	$log->ipaddress, $log->lat, $log->long, $log->limited, $log->search_time, $log->execute  );
			
			fputcsv($output, $row);
		}
		
		exit;
	}
	
	protected function file_upload_error_message($error_code) {
		switch ($error_code) {
			case UPLOAD_ERR_INI_SIZE:
				return 'The uploaded file exceeds the upload_max_filesize directive in php.ini';
			case UPLOAD_ERR_FORM_SIZE:
				return 'The uploaded file exceeds the MAX_FILE_SIZE directive that was specified in the HTML form';
			case UPLOAD_ERR_PARTIAL:
				return 'The uploaded file was only partially uploaded';
			case UPLOAD_ERR_NO_FILE:
				return 'No file was uploaded';
			case UPLOAD_ERR_NO_TMP_DIR:
				return 'Missing a temporary folder';
			case UPLOAD_ERR_CANT_WRITE:
				return 'Failed to write file to disk';
			case UPLOAD_ERR_EXTENSION:
				return 'File upload stopped by extension';
			default:
				return 'Unknown upload error';
		}
	}


}