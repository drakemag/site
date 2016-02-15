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

jimport('joomla.application.component.modeladmin');

/**
 * Storelocator model.
 */
class StorelocatorModellocation extends JModelAdmin
{
	/**
	 * @var		string	The prefix to use with controller messages.
	 * @since	1.6
	 */
	protected $text_prefix = 'COM_STORELOCATOR';


	/**
	 * Returns a reference to the a Table object, always creating it.
	 *
	 * @param	type	The table type to instantiate
	 * @param	string	A prefix for the table class name. Optional.
	 * @param	array	Configuration array for model. Optional.
	 * @return	JTable	A database object
	 * @since	1.6
	 */
	public function getTable($type = 'Location', $prefix = 'StorelocatorTable', $config = array())
	{
		return JTable::getInstance($type, $prefix, $config);
	}

	/**
	 * Method to get the record form.
	 *
	 * @param	array	$data		An optional array of data for the form to interogate.
	 * @param	boolean	$loadData	True if the form is to load its own data (default case), false if not.
	 * @return	JForm	A JForm object on success, false on failure
	 * @since	1.6
	 */
	public function getForm($data = array(), $loadData = true)
	{
		// Initialise variables.
		$app	= JFactory::getApplication();

		// Get the form.
		$form = $this->loadForm('com_storelocator.location', 'location', array('control' => 'jform', 'load_data' => $loadData));
		if (empty($form)) {
			return false;
		}

		return $form;
	}

	/**
	 * Method to get the data that should be injected in the form.
	 *
	 * @return	mixed	The data for the form.
	 * @since	1.6
	 */
	protected function loadFormData()
	{
		// Check the session for previously entered form data.
		$data = JFactory::getApplication()->getUserState('com_storelocator.edit.location.data', array());

		if (empty($data)) {
			$data = $this->getItem();
            

			//Support for multiple or not foreign key field: catid
			$array = array();
			foreach((array)$data->catid as $value): 
				if(!is_array($value)):
					$array[] = $value;
				endif;
			endforeach;
			$data->catid = implode(',',$array);

			//Support for multiple or not foreign key field: tags
			$array = array();
			foreach((array)$data->tags as $value): 
				if(!is_array($value)):
					$array[] = $value;
				endif;
			endforeach;
			$data->tags = implode(',',$array);
		}

		return $data;
	}

	/**
	 * Method to get a single record.
	 *
	 * @param	integer	The id of the primary key.
	 *
	 * @return	mixed	Object on success, false on failure.
	 * @since	1.6
	 */
	public function getItem($pk = null)
	{
		if ($item = parent::getItem($pk)) {

			//Do any procesing on fields here if needed

		}

		return $item;
	}

	/**
	 * Prepare and sanitise the table prior to saving.
	 *
	 * @since	1.6
	 */
	protected function prepareTable($table)
	{
		jimport('joomla.filter.output');

		if (empty($table->id)) {

			// Set ordering to the last item if not set
			if (@$table->ordering === '') {
				$db = JFactory::getDbo();
				$db->setQuery('SELECT MAX(ordering) FROM #__storelocator_locations');
				$max = $db->loadResult();
				$table->ordering = $max+1;
			}

		}
	}
	
	
	/**
	 * Method to toggle the featured setting of locations.
	 *
	 * @param   array  The ids of the items to toggle.
	 * @param   integer  The value to toggle to.
	 *
	 * @return  boolean  True on success.
	 */
	public function featured($pks, $value = 0)
	{
		// Sanitize the ids.
		$pks = (array) $pks;
		JArrayHelper::toInteger($pks);

		if (empty($pks))
		{
			$this->setError(JText::_('JERROR_NO_ITEMS_SELECTED'));
			return false;
		}


		try {
			$db = $this->getDbo();
			
			$db->setQuery( 
				  'UPDATE #__storelocator_locations'
				. ' SET featured = '.(int) $value 
				. ' WHERE id IN ('.implode(',', $pks).')'
			 );
			 
			 $db->execute();
			
		} catch (Exception $e)
		{
			$this->setError($e->getMessage());
			return false;
		}

		return true;
	}
	
	/**
	 * Method to set the lat and long of a location
	 *
	 * @param   id  The id of the item to set
	 *
	 * @return  boolean  True on success.
	 */
	public function setCoords($id, $lat, $long)
	{
		$id = intval($id);
		if (!$id)
		{
			$this->setError(JText::_('JERROR_NO_ITEMS_SELECTED'));
			return false;
		}


		try {
			$db = $this->getDbo();
			
			$db->setQuery( 
				  'UPDATE #__storelocator_locations'
				. ' SET `lat` = '.(float) $lat 
				. ', `long` = '.(float) $long 
				. ' WHERE id ='.(int) $id
			 );
			 
			 $db->execute();
			
		} catch (Exception $e)
		{
			$this->setError($e->getMessage());
			return false;
		}

		return true;
	}


}