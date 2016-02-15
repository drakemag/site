<?php
/**
 * @version		$Id: default_items.php 01 2011-01-11 11:37:09Z maverick $
 * @package		CoreJoomla.Polls
 * @subpackage	Components.site
 * @copyright	Copyright (C) 2009 - 2012 corejoomla.com, Inc. All rights reserved.
 * @author		Maverick
 * @link		http://www.corejoomla.com/
 * @license		License GNU General Public License version 2 or later
 */

defined('_JEXEC') or die;

JHtml::_('bootstrap.tooltip');
$lang	= JFactory::getLanguage();

if (count($this->items[$this->parent->id]) > 0 && $this->maxLevelcat != 0) :
?>
	<?php foreach($this->items[$this->parent->id] as $id => $item) : ?>
		<?php
		if ($this->params->get('show_empty_categories_cat') || $item->numitems || count($item->getChildren())) :
		?>
			<div class="panel panel-<?php echo $this->theme?>">
				<div class="panel-heading">
					<h4 class="panel-title">
						<?php if($item->level > 1):?>
						<?php echo str_repeat('&nbsp;', ($item->level - 1) * 2 )?>
						<?php endif;?>
						<a href="<?php echo JRoute::_(CommunityPollsHelperRoute::getCategoryRoute($item->id));?>">
							<i class="fa fa-folder-open-o"></i> <?php echo $this->escape($item->title); ?>
						</a>
						<?php if ($this->params->get('show_cat_num_polls_cat') == 1) :?>
							<span class="badge badge-info tip hasTooltip" title="<?php echo JHtml::tooltipText('COM_COMMUNITYPOLLS_NUM_ITEMS'); ?>">
								<?php echo $item->numitems; ?>
							</span>
						<?php endif; ?>
						<?php if (count($item->getChildren()) > 0) : ?>
							<a href="#category-<?php echo $item->id;?>" data-toggle="collapse" data-toggle="button" data-parent="categories-listing" class="btn btn-mini pull-right">
								<span class="icon-plus"></span>
							</a>
						<?php endif;?>
					</h4>
				</div>
				<div id="category-body-<?php echo $item->id;?>" class="panel-collapse collapse">
					<div class="panel-body">
						<?php if ($this->params->get('show_description_image') && $item->getParams()->get('image')) : ?>
							<img src="<?php echo $item->getParams()->get('image'); ?>"/>
						<?php endif; ?>
						<?php if ($this->params->get('show_subcat_desc_cat') == 1) :?>
							<?php if ($item->description) : ?>
								<div class="category-desc">
									<?php echo JHtml::_('content.prepare', $item->description, '', 'com_communitypolls.categories'); ?>
								</div>
							<?php endif; ?>
						<?php endif; ?>
					</div>
				</div>
			</div>
			<div id="category-<?php echo $item->id;?>" class="child-categories collapse">
			<?php 
			if (count($item->getChildren()) > 0)
			{
				$this->items[$item->id] = $item->getChildren();
				$this->parent = $item;
				$this->maxLevelcat--;
				echo $this->loadTemplate('items');
				$this->parent = $item->getParent();
				$this->maxLevelcat++;
			}
			?>
			</div>
		<?php endif; ?>
	<?php endforeach; ?>
<?php endif; ?>
