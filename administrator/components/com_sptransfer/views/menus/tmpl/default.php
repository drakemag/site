<?php
/**
 * @package		SP Transfer
 * @subpackage	Components
 * @copyright	SP CYEND - All rights reserved.
 * @author		SP CYEND
 * @link		http://www.cyend.com
 * @license GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
 */

defined('_JEXEC') or die;

// Include the component HTML helpers.
JHtml::addIncludePath(JPATH_COMPONENT . '/helpers/html');

// Load the tooltip behavior.
JHtml::_('behavior.tooltip');
JHtml::_('behavior.multiselect');

$uri = JFactory::getUri();
$return = base64_encode($uri);
$user = JFactory::getUser();
$userId = $user->get('id');
$listOrder = $this->escape($this->state->get('list.ordering'));
$listDirn = $this->escape($this->state->get('list.direction'));
$modMenuId = (int) $this->get('ModMenuId');
?>
<div class="btn-group" id="toolbar">
            <a class="btn btn-primary" href="#" onclick="if (document.adminForm.boxchecked.value==0){alert('Please first make a selection from the list');}else{if (window.parent) window.parent.jSelectItem('com_menus', 'menu_types', findChecked());}" class="toolbar">
                <?php echo JText::_('COM_SPTRANSFER_CHOOSE'); ?>
            </a>
</div>
<div class="clr"> </div>
<form action="<?php echo JRoute::_('index.php?option=com_sptransfer&amp;view=menus&amp;layout=default&amp;tmpl=component');?>" method="post" name="adminForm" id="adminForm">
	<table class="table table-striped">
		<thead>
			<tr>
				<th width="1%" rowspan="2">
					<input type="checkbox" name="checkall-toggle" value="" title="<?php echo JText::_('JGLOBAL_CHECK_ALL'); ?>" onclick="Joomla.checkAll(this)" />
				</th>
				<th rowspan="2">
					<?php echo JHtml::_('grid.sort',  'JGLOBAL_TITLE', 'a.title', $listDirn, $listOrder); ?>
				</th>
				<th width="30%" colspan="3">
					<?php echo JText::_('COM_MENUS_HEADING_NUMBER_MENU_ITEMS'); ?>
				</th>
				<th width="1%" class="nowrap" rowspan="2">
					<?php echo JHtml::_('grid.sort',  'JGRID_HEADING_ID', 'a.id', $listDirn, $listOrder); ?>
				</th>
			</tr>
			<tr>
				<th width="10%">
					<?php echo JText::_('COM_MENUS_HEADING_PUBLISHED_ITEMS'); ?>
				</th>
				<th width="10%">
					<?php echo JText::_('COM_MENUS_HEADING_UNPUBLISHED_ITEMS'); ?>
				</th>
				<th width="10%">
					<?php echo JText::_('COM_MENUS_HEADING_TRASHED_ITEMS'); ?>
				</th>
			</tr>
		</thead>
		<tfoot>
			<tr>
				<td colspan="1">
                                    <?php echo $this->pagination->getLimitBox(); ?>
                                </td>
                                <td colspan="14">
                                    <?php echo $this->pagination->getListFooter(); ?>
                                </td>
			</tr>
		</tfoot>
		<tbody>
		<?php foreach ($this->items as $i => $item) :
		?>
			<tr class="row<?php echo $i % 2; ?>">
				<td class="center">
					<?php echo JHtml::_('grid.id', $i, $item->id); ?>
				</td>
				<td>
						<?php echo $this->escape($item->title); ?>
					<p class="smallsub">(<span><?php echo JText::_('COM_MENUS_MENU_MENUTYPE_LABEL') ?></span>
							<?php echo $this->escape($item->menutype)?>)
					</p>
				</td>
				<td class="center btns">
						<?php echo $item->count_published; ?>
				</td>
				<td class="center btns">
						<?php echo $item->count_unpublished; ?>
				</td>
				<td class="center btns">
						<?php echo $item->count_trashed; ?>
				</td>				
				<td class="center">
					<?php echo $item->id; ?>
				</td>
			</tr>
			<?php endforeach; ?>
		</tbody>
	</table>

	<div>
		<input type="hidden" name="task" value="" />
		<input type="hidden" name="boxchecked" value="0" />
		<input type="hidden" name="filter_order" value="<?php echo $listOrder; ?>" />
		<input type="hidden" name="filter_order_Dir" value="<?php echo $listDirn; ?>" />
		<?php echo JHtml::_('form.token'); ?>
	</div>
</form>
