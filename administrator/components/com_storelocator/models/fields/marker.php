<?php
/**
 * Marker Element for StoreLocator Component
 * 
 * @package    SysgenMedia.StoreLocator
 * @subpackage Components
 * @copyright	Copyright (c)2011 Sysgen Media LLC. All Rights Reserved.
 * @license		GNU/GPLv3
 */

// Check to ensure this file is included in Joomla!
defined('_JEXEC') or die( 'Restricted access' );

// import the list field type
jimport('joomla.form.helper');
JFormHelper::loadFieldClass('radio');

class JFormFieldMarker extends JFormFieldRadio
{
	
	/**
	 * The form field type.
	 *
	 * @var		string
	 * @since	1.6
	 */
	protected $type = 'marker';
	
	
	/**
	 * Method to get the radio button field input markup.
	 *
	 * @return  string  The field input markup.
	 *
	 * @since   11.1
	 */
	protected function getInput()
	{
		$html = array();

		// Initialize some field attributes.
		$class = $this->element['class'] ? ' class="radio ' . (string) $this->element['class'] . '"' : ' class="radio"';

		// Start the radio field output.
		$html[] = '<fieldset id="' . $this->id . '"' . $class . '>';

		// Get the field options.
		$options = $this->getOptions();

		// Build the radio field output.
		foreach ($options as $i => $option)
		{

			// Initialize some option attributes.
			$checked = ((string) $option->value == (string) $this->value) ? ' checked="checked"' : '';
			$class = !empty($option->class) ? ' class="radio inline ' . $option->class . '"' : ' class="radio inline "';
			$disabled = !empty($option->disable) ? ' disabled="disabled"' : '';

			// Initialize some JavaScript option attributes.
			$onclick = !empty($option->onclick) ? ' onclick="' . $option->onclick . '"' : '';

			$inputHTML = '<input type="radio" id="' . $this->id . $i . '" name="' . $this->name . '"' . ' value="'
				. htmlspecialchars($option->value, ENT_COMPAT, 'UTF-8') . '"' . $checked . $class . $onclick . $disabled . '/>';
				
			$img_url = @(stripos($option->image_url, 'http') === FALSE && $option->image_url[0] != '/')?'../'.$option->image_url:$option->image_url;

			$html[] = '<label for="' . $this->id . $i . '" class="radio inline span3" style="margin-left:15px;">
				'.$inputHTML.'
				<img src="'.$img_url.'" align="absmiddle" style="max-height:45px;max-width:75px;" /> '
				. JText::alt($option->text, preg_replace('/[^a-zA-Z0-9_\-]/', '_', $this->fieldname)) . '</label>';
		}		
		
		$html[] = '</fieldset>';

		return implode($html);
	}

	/**
	 * Method to get the field options for radio buttons.
	 *
	 * @return  array  The field option objects.
	 *
	 * @since   11.1
	 */
	protected function getOptions()
	{
		$options = array();
		
		//gather Markers
		
		$query = "SELECT id as value, name as text, image_url FROM #__storelocator_marker_types ORDER BY id ASC";
		$db = JFactory::getDBO();
		$db->setQuery($query);
		$markers = $db->loadObjectList();		

		foreach ($markers as $option)
		{
			// Create a new option object based on the <option /> element.
			$tmp = JHtml::_(
				'select.option', (string) $option->value, trim((string) $option->text), 'value', 'text', false 
			);
			
			$tmp->image_url = trim((string) $option->image_url);

			// Add the option object to the result set.
			$options[] = $tmp;
		}

		reset($options);

		return $options;
	}
}
