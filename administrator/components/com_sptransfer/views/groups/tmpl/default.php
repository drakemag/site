<?php
/**
 * @package		Joomla.Administrator
 * @subpackage	com_users
 * @copyright	Copyright (C) 2005 - 2012 Open Source Matters, Inc. All rights reserved.
 * @license		GNU General Public License version 2 or later; see LICENSE.txt
 */
// No direct access.
defined('_JEXEC') or die;

// Include the component HTML helpers.
JHtml::addIncludePath(JPATH_COMPONENT . '/helpers/html');

// Load the tooltip behavior.
JHtml::_('behavior.tooltip');
JHtml::_('behavior.multiselect');

$user = JFactory::getUser();
$listOrder = $this->escape($this->state->get('list.ordering'));
$listDirn = $this->escape($this->state->get('list.direction'));

JText::script('COM_USERS_GROUPS_CONFIRM_DELETE');
?>
<script type="text/javascript">
    Joomla.submitbutton = function(task)
    {
        if (task == 'groups.delete')
        {
            var f = document.adminForm;
            var cb='';
<?php foreach ($this->items as $i => $item): ?>
    <?php if ($item->user_count > 0): ?>
                        cb = f['cb'+<?php echo $i; ?>];
                        if (cb && cb.checked) {
                            if (confirm(Joomla.JText._('COM_USERS_GROUPS_CONFIRM_DELETE'))) {
                                Joomla.submitform(task);
                            }
                            return;
                        }
    <?php endif; ?>
<?php endforeach; ?>
        }
        Joomla.submitform(task);
    }
</script>
<div class="btn-group" id="toolbar">
    <a class="btn btn-primary" href="#" onclick="if (document.adminForm.boxchecked.value==0){alert('Please first make a selection from the list');}else{if (window.parent) window.parent.jSelectItem('com_users', 'usergroups', findChecked());}" class="toolbar">
        <?php echo JText::_('COM_SPTRANSFER_CHOOSE'); ?>
    </a>
</div>
<div class="clr"> </div>
<form action="<?php echo JRoute::_('index.php?option=com_sptransfer&amp;view=groups&amp;layout=default&amp;tmpl=component'); ?>" method="post" name="adminForm" id="adminForm">
    <div id="filter-bar" class="btn-toolbar">
        <div class="filter-search btn-group pull-left">
            <input type="text" name="filter_search" placeholder="<?php echo JText::_('JSEARCH_FILTER'); ?>" id="filter_search" value="<?php echo $this->escape($this->state->get('filter.search')); ?>" title="<?php echo JText::_('JSEARCH_FILTER'); ?>" />
        </div>
        <div class="btn-group pull-left">
            <button class="btn tip" type="submit" title="<?php echo JText::_('JSEARCH_FILTER_SUBMIT'); ?>"><i class="icon-search"></i></button>
            <button class="btn tip" type="button" title="<?php echo JText::_('JSEARCH_FILTER_CLEAR'); ?>" onclick="document.id('filter_search').value='';this.form.submit();"><i class="icon-remove"></i></button>
        </div>
    </div>
    <div class="clearfix"> </div>

    <table class="table table-striped">
        <thead>
            <tr>
                <th width="1%">
                    <input type="checkbox" name="checkall-toggle" value="" title="<?php echo JText::_('JGLOBAL_CHECK_ALL'); ?>" onclick="Joomla.checkAll(this)" />
                </th>
                <th class="left">
                    <?php echo JText::_('COM_USERS_HEADING_GROUP_TITLE'); ?>
                </th>
                <th width="10%">
                    <?php echo JText::_('COM_USERS_HEADING_USERS_IN_GROUP'); ?>
                </th>
                <th width="5%">
                    <?php echo JText::_('JGRID_HEADING_ID'); ?>
                </th>
            </tr>
        </thead>
        <tfoot>
            <tr>
                <td colspan="1">
                    <?php echo $this->pagination->getLimitBox(); ?>
                </td>
                <td colspan="3">
                    <?php echo $this->pagination->getListFooter(); ?>
                </td>
            </tr>
        </tfoot>
        <tbody>
            <?php foreach ($this->items as $i => $item) : ?>
                <tr class="row<?php echo $i % 2; ?>">
                    <td class="center">
                        <?php echo JHtml::_('grid.id', $i, $item->id); ?>
                    </td>
                    <td>
                        <?php echo str_repeat('<span class="gi">|&mdash;</span>', $item->level) ?>
                        <?php echo $this->escape($item->title); ?>
                        <?php if (JDEBUG) : ?>
                            <div class="fltrt"><div class="button2-left smallsub"><div class="blank"><a href="<?php echo JRoute::_('index.php?option=com_users&view=debuggroup&group_id=' . (int) $item->id); ?>">
                                            <?php echo JText::_('COM_USERS_DEBUG_GROUP'); ?></a></div></div></div>
                        <?php endif; ?>
                    </td>
                    <td class="center">
                        <?php echo $item->user_count ? $item->user_count : ''; ?>
                    </td>
                    <td class="center">
                        <?php echo (int) $item->id; ?>
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
