<?php
/**
 * @package     corejoomla.administrator
 * @subpackage  com_communitypolls
 *
 * @copyright   Copyright (C) 2009 - 2014 corejoomla.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */
defined('_JEXEC') or die();

JHtml::addIncludePath(JPATH_COMPONENT . '/helpers');
JHtml::_('behavior.caption');

$layout = $this->params->get('layout', 'default');
$categories = JCategories::getInstance('CommunityPolls', array('countItems'=>true));
$category = $categories->get($this->state->get('category.id', 'root'));
$theme = $this->params->get('theme', 'default');
?>
<div id="cj-wrapper" class="category-details<?php echo $this->pageclass_sfx;?>">
	<?php echo JLayoutHelper::render($layout.'.toolbar', array('params'=>$this->params, 'state'=>$this->state));?>
	
	<div class="panel panel-<?php echo $theme;?>">
		<div class="panel-heading">
			<h2 class="panel-title">
				<span><i class="fa fa-folder-open"></i> <?php echo JText::_('COM_COMMUNITYPOLLS_LABEL_CATEGORIES');?></span>
				<?php if(!empty($category->parent_id)):?>
				<a href="<?php echo JRoute::_(CommunityPollsHelperRoute::getCategoryRoute($category));?>">
					<small>: <?php echo $this->escape($category->title). ($category->numitems ? ' ('.$category->numitems.')'  : '');?></small> 
				</a>
				<?php endif;?>
			</h2>
		</div>
		<div class="panel-body">
			<?php 
			if (!empty($category->id) && ((!empty($category->description) && $this->params->get('show_cat_description')) || (count($category->getChildren()) > 0)))
			{
				echo JLayoutHelper::render($layout.'.category_list', array('category'=>$category, 'params'=>$this->params, 'maxlevel'=>1));
			}
			
			if($this->params->get('show_search_box'))
			{
				echo JLayoutHelper::render($layout.'.search_form', array('params'=>$this->params, 'state'=>$this->state, 'catid'=>(isset($category->id) ? $category->id : 0)));
			} 
			?>
		</div>
	</div>
	
	<?php echo JLayoutHelper::render($layout.'.polls_list', array('items'=>$this->items, 'state'=>$this->state, 
			'params'=>$this->params, 'pagination'=>$this->pagination, 'heading'=>$this->params->get('page_heading'), 'viewName'=>$this->viewName)); ?>
</div>