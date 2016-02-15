<?php
/**
 * @package     corejoomla.administrator
 * @subpackage  com_communitypolls
 *
 * @copyright   Copyright (C) 2009 - 2015 corejoomla.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */
defined('_JEXEC') or die();

$app 				= JFactory::getApplication();
$user 				= JFactory::getUser();

$params 			= $displayData['params'];
$catid				= (int) $displayData['catid'];
$theme 				= $params->get('theme', 'default');

$api = new CjLibApi();
?>
<div class="search-form">
	<div class="center text-center">
		<form action="<?php echo JRoute::_('index.php');?>" method="post" class="no-margin-bottom margin-top-10"> 
			<div class="input-append">
				<input type="text" name="list_filter" id="polls_search_box" class="input-xlarge" placeholder="<?php echo JText::_('COM_COMMUNITYPOLLS_SEARCH_PLACEHOLDER');?>">
				<button class="btn btn-default" type="submit"><?php echo JText::_('COM_COMMUNITYPOLLS_LABEL_SEARCH');?></button>
			</div>
			<div class="center text-center">
				<a href="<?php echo JRoute::_('index.php?option=com_communitypolls&view=search');?>">
					<?php echo JText::_('COM_COMMUNITYPOLLS_TRY_ADVANCED_SEARCH')?>
				</a>
			</div>
			<input type="hidden" name="view" value="polls">
			<input type="hidden" name="catid" value="<?php echo $catid;?>">
			<input type="hidden" name="return" value="<?php echo base64_encode(JRoute::_('index.php'));?>">
		</form>
	</div>
</div>