<?php
/**
 * @version		$Id: cpcaptcha.php 01 2014-01-11 11:37:09Z maverick $
 * @package		CoreJoomla.Polls
 * @subpackage	Components.site
 * @copyright	Copyright (C) 2009 - 2014 corejoomla.com, Inc. All rights reserved.
 * @author		Maverick
 * @link		http://www.corejoomla.com/
 * @license		License GNU General Public License version 2 or later
 */

defined('_JEXEC') or die;

class JFormRuleCpcaptcha extends JFormRuleCaptcha
{
	public function test(SimpleXMLElement $element, $value, $group = null, JRegistry $input = null, JForm $form = null)
	{
		$user = JFactory::getUser();
		$app = JFactory::getApplication();
		$catid = $input->get('catid');
		
		if(!$user->authorise('core.vcaptcha', 'com_communitypolls.category.'.$catid))
		{
			return parent::test($element, $value, $group, $input, $form);
		}
		
		return true;
	}
}