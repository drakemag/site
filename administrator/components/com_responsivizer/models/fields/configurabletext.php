<?php
//namespace administrator\components\com_responsivizer;
/** 
 * Form field list
 * @package RESPONSIVIZER::CONFIG::administrator::components::com_responsivizer 
 * @subpackage models
 * @subpackage fields
 * @author Joomla! Extensions Store 
 * @copyright (C)2014 Joomla! Extensions Store
 * @license GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html 
 */
defined('_JEXEC') or die();
jimport('joomla.filesystem.folder');

/**
 * Renders a configurable filelist element
 *
 * @package RESPONSIVIZER::administrator::components::com_responsivizer 
 * @subpackage elements
 * @since 2.0
 */
class JFormFieldConfigurableText extends JFormField {

	/**
	 * The form field type.
	 *
	 * @var    string
	 *
	 * @since  11.1
	 */
	protected $type = 'ConfigurableText';

	/**
	 * Method to get the field input markup.
	 *
	 * @return  string  The field input markup.
	 *
	 * @since   11.1
	 */
	protected function getInput() {
		// Initialize some field attributes.
		$base = JURI::root();
		$size = $this->element['size'] ? ' size="' . (int) $this->element['size'] . '"' : '';
		$maxLength = $this->element['maxlength'] ? ' maxlength="' . (int) $this->element['maxlength'] . '"' : '';
		$class = $this->element['class'] ? ' class="' . (string) $this->element['class'] . '"' : '';
		$readonly = ((string) $this->element['readonly'] == 'true') ? ' readonly="readonly"' : '';
		$disabled = ((string) $this->element['disabled'] == 'true') ? ' disabled="disabled"' : '';
		$imgLink = $this->value ? $this->value : 'administrator/components/com_responsivizer/images/no-image.png';

		// Initialize JavaScript field attributes.
		$onchange = $this->element['onchange'] ? ' onchange="' . (string) $this->element['onchange'] . '"' : '';

		return 	'<input type="text" name="' . $this->name . '" id="' . $this->id . '"' . ' value="' . htmlspecialchars($this->value, ENT_COMPAT, 'UTF-8') . '"' . $class . $size . $disabled . $readonly . $onchange . $maxLength . '/>' .
				'<button class="btn btn-small btn-info dialog_trigger"><span class="icon-list"></span><span class="label-icon">' . JText::_('COM_RESPONSIVIZER_SELECT_IMAGE') . '</span></button>' .
			   	'<span data-content="' . JText::_('COM_RESPONSIVIZER_SORT_IMAGES') . '" class="handler pull-right hasPopover icon-expand-2"></span>' .
			   	'<img class="preview" src="' . $base . $imgLink . '" alt="image"/>';
	}
}
