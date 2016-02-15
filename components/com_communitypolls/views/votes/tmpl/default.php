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

$user = JFactory::getUser();
$menu = JFactory::getApplication()->getMenu();
$active = $menu->getActive();
$this->itemId = $active->id;

$layout = $this->params->get('layout', 'default');
echo JLayoutHelper::render($layout.'.toolbar', array('params'=>$this->params, 'state'=>$this->state));

if(!empty($this->items))
{
	?>
	<table class="table table-bordered table-hover table-striped">
		<thead>
			<tr>
				<th><?php echo JText::_('JGLOBAL_TITLE');?></th>
				<th><?php echo JText::_('JCATEGORY');?></th>
				<th><?php echo JText::_('COM_COMMUNITYPOLLS_LABEL_VOTED_ON');?></th>
			</tr>
		</thead>
		<tbody>
			<?php foreach ($this->items as $item):?>
			<tr>
				<td>
					<a href="<?php echo JRoute::_(CommunityPollsHelperRoute::getPollRoute($item->slug, $item->catslug, $item->language));?>">
						<?php echo $this->escape($item->title);?>
					</a>
				</td>
				<td>
					<a href="<?php echo JRoute::_(CommunityPollsHelperRoute::getCategoryRoute($item->catslug, $item->language));?>">
						<?php echo $this->escape($item->category_title);?>
					</a>
				</td>
				<td>
					<?php echo CJFunctions::get_formatted_date($item->voted_on);?>
				</td>
			</tr>
			<?php endforeach;?>
		</tbody>
	</table>
	<?php if (($this->params->def('show_pagination', 1) == 1  || ($this->params->get('show_pagination') == 2)) && ($this->pagination->get('pages.total') > 1)) : ?>
		<div class="pagination">
		<?php  if ($this->params->def('show_pagination_results', 1)) : ?>
		<p class="counter pull-right"> <?php echo $this->pagination->getPagesCounter(); ?> </p>
		<?php endif; ?>
		<?php echo $this->pagination->getPagesLinks(); ?> </div>
	<?php  endif; ?>
	<?php 
}
else 
{
	?>
	<div class="alert alert-info"><i class="fa fa-info-circle"></i> <?php echo JText::_('COM_COMMUNITYPOLLS_NO_RESULTS_FOUND'); ?></div>
	<?php
}
?>