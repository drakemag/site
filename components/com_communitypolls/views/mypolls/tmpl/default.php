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
$theme = $this->params->get('theme', 'default');
?>
<div id="cj-wrapper" class="polls<?php echo $this->pageclass_sfx;?>">

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
			if (!empty($category->id) && ((!empty($category->description) && $params->get('show_description')) || (count($category->getChildren()) > 0)))
			{
				echo JLayoutHelper::render($layout.'.category_list', array('category'=>$category, 'params'=>$this->params, 'maxlevel'=>1));
			}
			
			echo JLayoutHelper::render($layout.'.search_form', array('params'=>$this->params, 'state'=>$this->state, 'catid'=>(isset($category->id) ? $category->id : 0))); 
			?>
		</div>
	</div>

	<?php if ($this->params->get('show_page_heading', 1)) : ?>
	<div class="page-header">
		<h1> <?php echo $this->escape($this->params->get('page_heading')); ?> </h1>
	</div>
	<?php endif; ?>
	
	<?php echo JLayoutHelper::render($layout.'.polls_list', array('items'=>$this->items, 'state'=>$this->state, 
			'params'=>$this->params, 'pagination'=>$this->pagination, 'heading'=>$this->params->get('page_heading'), 'viewName'=>$this->viewName)); ?>
</div>