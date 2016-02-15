<?php
/**
 * @version		$Id: polltype.php 01 2014-01-26 11:37:09Z maverick $
 * @package		CoreJoomla.Polls
 * @subpackage	Components
 * @copyright	Copyright (C) 2009 - 2014 corejoomla.com. All rights reserved.
 * @author		Maverick
 * @link		http://www.corejoomla.com/
 * @license		License GNU General Public License version 2 or later
 */
defined('JPATH_BASE') or die;

class JFormFieldPollType extends JFormFieldList
{
	protected $type = 'PollType';
	
	protected function getInput()
	{
		return parent::getInput();
	}
	
	protected function getOptions()
	{
		$params = JComponentHelper::getParams('com_communitypolls');
		$allowed_poll_types = $params->get('allowed_poll_types', array('radio', 'checkbox'));
		$options = array();
		
		foreach ($this->element->children() as $option)
		{
			// Only add <option /> elements.
			if ($option->getName() != 'option')
			{
				continue;
			}
		
			// Filter requirements
			if ($requires = explode(',', (string) $option['requires']))
			{
				// Requires multilanguage
				if (in_array('multilanguage', $requires) && !JLanguageMultilang::isEnabled())
				{
					continue;
				}
		
				// Requires associations
				if (in_array('associations', $requires) && !JLanguageAssociations::isEnabled())
				{
					continue;
				}
			}
		
			$value = (string) $option['value'];

			if(!in_array($value, $allowed_poll_types))
			{
				continue;
			}
			
			$disabled = (string) $option['disabled'];
			$disabled = ($disabled == 'true' || $disabled == 'disabled' || $disabled == '1');
		
			$disabled = $disabled || ($this->readonly && $value != $this->value);
		
			// Create a new option object based on the <option /> element.
			$tmp = JHtml::_(
					'select.option', $value,
					JText::alt(trim((string) $option), preg_replace('/[^a-zA-Z0-9_\-]/', '_', $this->fieldname)), 'value', 'text',
					$disabled
			);
		
			// Set some option attributes.
			$tmp->class = (string) $option['class'];
		
			// Set some JavaScript option attributes.
			$tmp->onclick = (string) $option['onclick'];
		
			// Add the option object to the result set.
			$options[] = $tmp;
		}
		
		reset($options);
		
		return $options;
	}
}