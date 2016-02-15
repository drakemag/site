<?php
/**
 * @package		Joomla.Administrator
 * @subpackage	com_users
 * @copyright	Copyright (C) 2005 - 2012 Open Source Matters, Inc. All rights reserved.
 * @license		GNU General Public License version 2 or later; see LICENSE.txt
 */
defined('_JEXEC') or die;

JHtml::addIncludePath(JPATH_COMPONENT . '/helpers/html');
JHtml::_('behavior.tooltip');

$field = JRequest::getCmd('field');
$function = 'jSelectUser_' . $field;
$listOrder = $this->escape($this->state->get('list.ordering'));
$listDirn = $this->escape($this->state->get('list.direction'));
?>
<div class="btn-group">
    <a class="btn btn-primary" href="#" onclick="if (document.adminForm.boxchecked.value==0){alert('Please first make a selection from the list');}else{if (window.parent) window.parent.jSelectItem('com_users', 'users', findChecked());}" class="toolbar">
        <?php echo JText::_('COM_SPTRANSFER_CHOOSE'); ?>
    </a>
</div>
<div class="clr"> </div>

<form action="<?php echo JRoute::_('index.php?option=com_sptransfer&view=users&layout=modal&tmpl=component&groups=' . JRequest::getVar('groups', '', 'default', 'BASE64') . '&excluded=' . JRequest::getVar('excluded', '', 'default', 'BASE64')); ?>" method="post" name="adminForm" id="adminForm">
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
        <div class="btn-group pull-right">
            <select name="filter_group_id" class="inputbox" onchange="this.form.submit()">
                <option value=""><?php echo JText::_('JOPTION_ACCESS_SHOW_ALL_GROUPS'); ?></option>
                <?php echo JHtml::_('select.options', $this->usergroups, 'value', 'text', $this->state->get('filter.group_id')); ?>
            </select>
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
                    <?php echo JHtml::_('grid.sort', 'COM_USERS', 'a.name', $listDirn, $listOrder); ?>
                </th>
                <th class="nowrap" width="25%">
                    <?php echo JHtml::_('grid.sort', 'JGLOBAL_USERNAME', 'a.username', $listDirn, $listOrder); ?>
                </th>
                <th class="nowrap" width="25%">
                    <?php echo JHtml::_('grid.sort', 'COM_USERS_USERGROUPS', 'group_names', $listDirn, $listOrder); ?>
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
            <?php
            $i = 0;
            foreach ($this->items as $item) :
                ?>
                <tr class="row<?php echo $i % 2; ?>">
                    <td class="center">
                        <?php echo JHtml::_('grid.id', $i, $item->id); ?>
                    </td>
                    <td>
                        <?php echo $item->name; ?>
                    </td>
                    <td align="center">
                        <?php echo $item->username; ?>
                    </td>
                    <td align="left">
                        <?php echo nl2br($item->group_names); ?>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
    <div>
        <input type="hidden" name="task" value="" />
        <input type="hidden" name="field" value="<?php echo $this->escape($field); ?>" />
        <input type="hidden" name="boxchecked" value="0" />
        <input type="hidden" name="filter_order" value="<?php echo $listOrder; ?>" />
        <input type="hidden" name="filter_order_Dir" value="<?php echo $listDirn; ?>" />
        <?php echo JHtml::_('form.token'); ?>
    </div>
</form>
