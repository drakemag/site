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

/* @var $this UsersViewNotes */

JHtml::_('behavior.tooltip');

$listOrder = $this->escape($this->state->get('list.ordering'));
$listDirn = $this->escape($this->state->get('list.direction'));
?>
<div class="btn-group" id="toolbar">
    <a class="btn btn-primary" href="#" onclick="if (document.adminForm.boxchecked.value==0){alert('Please first make a selection from the list');}else{if (window.parent) window.parent.jSelectItem('com_users', 'notes', findChecked());}" class="toolbar">
        <?php echo JText::_('COM_SPTRANSFER_CHOOSE'); ?>
    </a>
</div>
<div class="clr"> </div>
<form action="<?php echo JRoute::_('index.php?option=com_sptransfer&amp;view=notes&amp;layout=default&amp;tmpl=component'); ?>" method="post" name="adminForm" id="adminForm">
    <div class="btn-toolbar">
        <div class="filter-search btn-group pull-left">
            <div class="filter-search btn-group pull-left">
                <input type="text" name="filter_search" placeholder="<?php echo JText::_('JSEARCH_FILTER'); ?>" id="filter_search" value="<?php echo $this->escape($this->state->get('filter.search')); ?>" title="<?php echo JText::_('JSEARCH_FILTER'); ?>" />
            </div>
            <div class="btn-group pull-left">
                <button class="btn tip" type="submit" title="<?php echo JText::_('JSEARCH_FILTER_SUBMIT'); ?>"><i class="icon-search"></i></button>
                <button class="btn tip" type="button" title="<?php echo JText::_('JSEARCH_FILTER_CLEAR'); ?>" onclick="document.id('filter_search').value='';this.form.submit();"><i class="icon-remove"></i></button>
            </div>
        </div>

        <div class="btn-group">
            <select name="filter_category_id" class="inputbox" onchange="this.form.submit()">
                <option value=""><?php echo JText::_('JOPTION_SELECT_CATEGORY'); ?></option>
                <?php echo JHtml::_('select.options', $this->categories, 'value', 'text', $this->state->get('filter.category_id')); ?>
            </select>

            <select name="filter_published" class="inputbox" onchange="this.form.submit()">
                <option value=""><?php echo JText::_('JOPTION_SELECT_PUBLISHED'); ?></option>
                <?php echo JHtml::_('select.options', JHtml::_('jgrid.publishedOptions'), 'value', 'text', $this->state->get('filter.state'), true);
                ?>
            </select>
        </div>
    </div>
    <div class="clearfix"></div>

    <table class="table table-striped">
        <thead>
            <tr>
                <th width="1%">
                    <input type="checkbox" name="toggle" value="" class="checklist-toggle" title="<?php echo JText::_('JGLOBAL_CHECK_ALL'); ?>" onclick="Joomla.checkAll(this)" />
                </th>
                <th class="left">
                    <?php echo JHtml::_('grid.sort', 'COM_USERS_USER_HEADING', 'u.name', $listDirn, $listOrder); ?>
                </th>
                <th  class="left">
                    <?php echo JHtml::_('grid.sort', 'COM_USERS_SUBJECT_HEADING', 'a.subject', $listDirn, $listOrder); ?>
                </th>
                <th width="20%">
                    <?php echo JHtml::_('grid.sort', 'COM_USERS_CATEGORY_HEADING', 'c.title', $listDirn, $listOrder); ?>
                </th>
                <th width="5%">
                    <?php echo JHtml::_('grid.sort', 'JSTATUS', 'a.state', $listDirn, $listOrder); ?>
                </th>
                <th width="10%">
                    <?php echo JHtml::_('grid.sort', 'COM_USERS_REVIEW_HEADING', 'a.review_time', $listDirn, $listOrder); ?>
                </th>
                <th width="1%" class="nowrap">
                    <?php echo JHtml::_('grid.sort', 'JGRID_HEADING_ID', 'a.id', $listDirn, $listOrder); ?>
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
            <?php foreach ($this->items as $i => $item) : ?>
                <tr class="row<?php echo $i % 2; ?>">
                    <td class="center checklist">
                        <?php echo JHtml::_('grid.id', $i, $item->id); ?>
                    </td>
                    <td>
                        <?php if ($item->checked_out) : ?>
                            <?php echo JHtml::_('jgrid.checkedout', $item->editor, $item->checked_out_time); ?>
                        <?php endif; ?>
                        <?php echo $this->escape($item->user_name); ?>
                    </td>
                    <td>
                        <?php if ($item->subject) : ?>
                            <?php echo $this->escape($item->subject); ?>
                        <?php else : ?>
                            <?php echo JText::_('COM_USERS_EMPTY_SUBJECT'); ?>
                        <?php endif; ?>
                    </td>
                    <td class="center">
                        <?php if ($item->catid && $item->cparams->get('image')) : ?>
                            <?php echo JHtml::_('users.image', $item->cparams->get('image')); ?>
                        <?php endif; ?>
                        <?php echo $this->escape($item->category_title); ?>
                    </td>
                    <td class="center">
                        <?php echo JHtml::_('jgrid.published', $item->state, $i, 'notes.', false, 'cb', $item->publish_up, $item->publish_down); ?>
                    </td>
                    <td class="center">
                        <?php if (intval($item->review_time)) : ?>
                            <?php echo $this->escape($item->review_time); ?>
                        <?php else : ?>
                            <?php echo JText::_('COM_USERS_EMPTY_REVIEW'); ?>
                        <?php endif; ?>
                    </td>
                    <td class="center">
                        <?php echo (int) $item->id; ?>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>

    <input type="hidden" name="task" value="" />
    <input type="hidden" name="boxchecked" value="0" />
    <input type="hidden" name="filter_order" value="<?php echo $listOrder; ?>" />
    <input type="hidden" name="filter_order_Dir" value="<?php echo $listDirn; ?>" />
    <?php echo JHtml::_('form.token'); ?>
</form>
