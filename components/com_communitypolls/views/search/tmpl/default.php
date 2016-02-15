<?php
/**
 * @version		$Id: defauult.php 01 2014-01-26 11:37:09Z maverick $
 * @package		CoreJoomla.Polls
 * @subpackage	Components
 * @copyright	Copyright (C) 2009 - 2014 corejoomla.com. All rights reserved.
 * @author		Maverick
 * @link		http://www.corejoomla.com/
 * @license		License GNU General Public License version 2 or later
 */

defined('_JEXEC') or die;

JHtml::addIncludePath(JPATH_COMPONENT.'/helpers');

JHtml::_('behavior.caption');

$user = JFactory::getUser();
$menu = JFactory::getApplication()->getMenu();
$active = $menu->getActive();
$this->itemId = $active->id;
$layout = $this->params->get('layout', 'default');
?>
<div id="cj-wrapper" class="polls<?php echo $this->pageclass_sfx;?>">
	<?php if ($this->params->get('show_page_heading', 1)) : ?>
	<div class="page-header">
		<h1> <?php echo $this->escape($this->params->get('page_heading')); ?> </h1>
	</div>
	<?php endif; ?>
	
	<?php echo JLayoutHelper::render($layout.'.toolbar', array('params'=>$this->params, 'state'=>$this->state));?>

	<div class="row-fluid">
		<div class="span12">
			<div class="alert alert-info"><i class="fa fa-info-circle"></i> <?php echo JText::_('COM_COMMUNITYPOLLS_ENTER_SEARCH_CRITERIA');?></div>
			
			<form action="<?php echo JRoute::_('index.php?option=com_communitypolls');?>" method="post">
				
				<div class="well">
					<div class="row-fluid">
						<div class="span12">
							 <fieldset class="no-margin-top">
							 	<legend><?php echo JText::_('COM_COMMUNITYPOLLS_LABEL_KEYWORDS');?></legend>
							 	<input name="list_filter" type="text" class="span8" placeholder="<?php echo JText::_('COM_COMMUNITYPOLLS_LABEL_KEYWORDS');?>">
							 	
							 	<select name="list_filter_field" class="span4">
							 		<option value="title"><?php echo JText::_('COM_COMMUNITYPOLLS_SEARCH_TITLES');?></option>
							 		<option value="author"><?php echo JText::_('COM_COMMUNITYPOLLS_SEARCH_USER_NAME');?></option>
							 		<option value="createdby"><?php echo JText::_('COM_COMMUNITYPOLLS_SEARCH_USERID');?></option>
							 		<option value="votes"><?php echo JText::_('COM_COMMUNITYPOLLS_SEARCH_VOTES');?></option>
							 	</select>
							 	
							 	<label class="checkbox"><input type="checkbox" value="1" name="all"> <?php echo JText::_('COM_COMMUNITYPOLLS_SEARCH_ALL_WORDS');?></label>
							 </fieldset>
						</div>
					</div>
				</div>
				
				<div class="well">
					<div class="row-fluid">
						<div class="span6">
							<fieldset class="no-margin-top">
								<legend><?php echo JText::_('COM_COMMUNITYPOLLS_SEARCH_OPTIONS');?></legend>
								<label><?php echo JText::_('COM_COMMUNITYPOLLS_SEARCH_ORDER_BY');?></label>
								<select name="filter_order" size="1">
									<option value="a.created"><?php echo JText::_('COM_COMMUNITYPOLLS_LABEL_DATE');?></option>
									<option value="a.votes"><?php echo JText::_('COM_COMMUNITYPOLLS_LABEL_VOTES');?></option>
									<option value="a.catid"><?php echo JText::_('COM_COMMUNITYPOLLS_LABEL_CATEGORY');?></option>
								</select>
								<label><?php echo JText::_('COM_COMMUNITYPOLLS_SEARCH_ORDER');?></label>
								<select name="filter_order_Dir" size="1">
									<option value="asc"><?php echo JText::_('COM_COMMUNITYPOLLS_ASCENDING');?></option>
									<option value="desc"><?php echo JText::_('COM_COMMUNITYPOLLS_DESCENDING');?></option>
								</select>
							</fieldset>
						</div>
						<div class="span6">
							<fieldset class="no-margin-top">
								<legend><?php echo JText::_('COM_COMMUNITYPOLLS_LABEL_CATEGORIES')?></legend>
								<?php 
								$categories = JHtml::_('category.categories', 'com_communitypolls');
								foreach ($categories as $id=>$category)
								{
									if($category->value == '1') 
									{
										unset($categories[$id]);
									}
								}
								
								$nocat = new JObject();
								$nocat->set('text', JText::_('COM_COMMUNITYPOLLS_LABEL_ALL_CATEGORIES'));
								$nocat->set('value', '0');
								$nocat->set('disable', false);
								
								array_unshift($categories, $nocat);
								echo JHTML::_('select.genericlist', $categories, 'catid[]', 'size = "10" multiple="multiple"');
								?>
							</fieldset>
						</div>
					</div>
				</div>
				
				<div class="well">
					<div class="row-fluid">
						<div class="center">
							<a href="<?php echo JRoute::_('index.php?option=com_communitypolls')?>" class="btn"><?php echo JText::_('JCANCEL');?></a>
							<button class="btn btn-primary" type="submit"><i class="fa fa-search-plus"></i> <?php echo JText::_('COM_COMMUNITYPOLLS_LABEL_SEARCH');?></button>
						</div>
					</div>
				</div>
				
				<input type="hidden" name="view" value="polls">
				<input type="hidden" id="filter_featured" name="filter_featured" value="">
			</form>
		</div>
	</div>
</div>