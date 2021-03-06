<?php
/**
 * @package		Joomla.Administrator
 * @subpackage	com_menus
 * @copyright	Copyright (C) 2005 - 2012 Open Source Matters, Inc. All rights reserved.
 * @license		GNU General Public License version 2 or later; see LICENSE.txt
 */
// no direct access
defined('_JEXEC') or die;

// Include the component HTML helpers.
JHtml::addIncludePath(JPATH_COMPONENT . '/helpers/html');
JHtml::_('behavior.tooltip');
JHtml::_('behavior.multiselect');

$user = JFactory::getUser();
$app = JFactory::getApplication();
$userId = $user->get('id');
$listOrder = $this->escape($this->state->get('list.ordering'));
$listDirn = $this->escape($this->state->get('list.direction'));
$ordering = ($listOrder == 'a.lft');
$canOrder = $user->authorise('core.edit.state', 'com_menus');
$saveOrder = ($listOrder == 'a.lft' && $listDirn == 'asc');
?>
<div class="btn-group" id="toolbar">
    <a class="btn btn-primary" href="#" onclick="if (document.adminForm.boxchecked.value==0){alert('Please first make a selection from the list');}else{if (window.parent) window.parent.jSelectItem('com_menus', 'menu', findChecked());}" class="toolbar">
        <?php echo JText::_('COM_SPTRANSFER_CHOOSE'); ?>
    </a>
</div>
<div class="clr"> </div>
<form action="<?php echo JRoute::_('index.php?option=com_sptransfer&amp;view=items&amp;layout=default&amp;tmpl=component'); ?>" method="post" name="adminForm" id="adminForm">
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

            <select name="menutype" class="inputbox" onchange="this.form.submit()">
                <?php echo JHtml::_('select.options', $this->menutypes, 'value', 'text', $this->state->get('filter.menutype')); ?>
            </select>

            <select name="filter_level" class="inputbox" onchange="this.form.submit()">
                <option value=""><?php echo JText::_('COM_MENUS_OPTION_SELECT_LEVEL'); ?></option>
                <?php echo JHtml::_('select.options', $this->f_levels, 'value', 'text', $this->state->get('filter.level')); ?>
            </select>

            <select name="filter_published" class="inputbox" onchange="this.form.submit()">
                <option value=""><?php echo JText::_('JOPTION_SELECT_PUBLISHED'); ?></option>
                <?php echo JHtml::_('select.options', JHtml::_('jgrid.publishedOptions', array('archived' => false)), 'value', 'text', $this->state->get('filter.published'), true); ?>
            </select>

            <select name="filter_access" class="inputbox" onchange="this.form.submit()">
                <option value=""><?php echo JText::_('JOPTION_SELECT_ACCESS'); ?></option>
                <?php echo JHtml::_('select.options', $this->assetgroups, 'value', 'text', $this->state->get('filter.access')); ?>
            </select>


            <select name="filter_language" class="inputbox" onchange="this.form.submit()">
                <option value=""><?php echo JText::_('JOPTION_SELECT_LANGUAGE'); ?></option>
                <?php echo JHtml::_('select.options', $this->languages, 'value', 'text', $this->state->get('filter.language')); ?>
            </select>

        </div>
    </div>
    <div class="clearfix"> </div>
    <?php //Set up the grid heading.  ?>
    <table class="table table-striped">
        <thead>
            <tr>
                <th width="1%">
                    <input type="checkbox" name="checkall-toggle" value="" title="<?php echo JText::_('JGLOBAL_CHECK_ALL'); ?>" onclick="Joomla.checkAll(this)" />
                </th>
                <th class="title">
                    <?php echo JHtml::_('grid.sort', 'JGLOBAL_TITLE', 'a.title', $listDirn, $listOrder); ?>
                </th>
                <th width="5%">
                    <?php echo JHtml::_('grid.sort', 'JSTATUS', 'a.published', $listDirn, $listOrder); ?>
                </th>
                <th width="13%">
                    <?php echo JHtml::_('grid.sort', 'JGRID_HEADING_ORDERING', 'a.lft', $listDirn, $listOrder); ?>
                </th>
                <th width="10%">
                    <?php echo JHtml::_('grid.sort', 'JGRID_HEADING_ACCESS', 'a.access', $listDirn, $listOrder); ?>
                </th>
                <th width="10%">
                    <?php echo JText::_('JGRID_HEADING_MENU_ITEM_TYPE'); ?>
                </th>
                <th width="5%">
                    <?php echo JHtml::_('grid.sort', 'COM_MENUS_HEADING_HOME', 'a.home', $listDirn, $listOrder); ?>
                </th>
                <?php if (isset($app->menu_associations)) : ?>
                    <th width="5%">
                        <?php echo JHtml::_('grid.sort', 'COM_MENUS_HEADING_ASSOCIATION', 'association', $listDirn, $listOrder); ?>
                    </th>
                <?php endif; ?>
                <th width="5%">
                    <?php echo JHtml::_('grid.sort', 'JGRID_HEADING_LANGUAGE', 'language', $listDirn, $listOrder); ?>
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
        <?php // Grid layout  ?>
        <tbody>
            <?php
            $originalOrders = array();
            foreach ($this->items as $i => $item) :
                $orderkey = array_search($item->id, $this->ordering[$item->parent_id]);
                ?>
                <tr class="row<?php echo $i % 2; ?>">
                    <td class="center">
                        <?php echo JHtml::_('grid.id', $i, $item->id); ?>
                    </td>
                    <td>
                        <?php echo str_repeat('<span class="gi">|&mdash;</span>', $item->level - 1) ?>
                        <?php if ($item->checked_out) : ?>
                            <?php echo JHtml::_('jgrid.checkedout', $i, $item->editor, $item->checked_out_time, 'items.', false); ?>
                        <?php endif; ?>
                        <?php echo $this->escape($item->title); ?>
                        <p class="smallsub" title="<?php echo $this->escape($item->path); ?>">
                            <?php echo str_repeat('<span class="gtr">|&mdash;</span>', $item->level - 1) ?>
                            <?php if ($item->type != 'url') : ?>
                                <?php if (empty($item->note)) : ?>
                                    <?php echo JText::sprintf('JGLOBAL_LIST_ALIAS', $this->escape($item->alias)); ?>
                                <?php else : ?>
                                    <?php echo JText::sprintf('JGLOBAL_LIST_ALIAS_NOTE', $this->escape($item->alias), $this->escape($item->note)); ?>
                                <?php endif; ?>
                            <?php elseif ($item->type == 'url' && $item->note) : ?>
                                <?php echo JText::sprintf('JGLOBAL_LIST_NOTE', $this->escape($item->note)); ?>
                            <?php endif; ?></p>
                    </td>
                    <td class="center">
                        <?php echo JHtml::_('jgrid.published', $item->published, $i, 'newsfeeds.', false, 'cb'); ?>
                    </td>
                    <td class="order">
                        <?php echo $orderkey + 1; ?>
                    </td>
                    <td class="center">
                        <?php echo $this->escape($item->access_level); ?>
                    </td>
                    <td class="nowrap">
                        <span title="<?php echo isset($item->item_type_desc) ? htmlspecialchars($this->escape($item->item_type_desc), ENT_COMPAT, 'UTF-8') : ''; ?>">
                            <?php echo $this->escape($item->item_type); ?></span>
                    </td>
                    <td class="center">
                        <?php if ($item->type == 'component') : ?>
                            <?php if ($item->language == '*' || $item->home == '0'): ?>
                                <?php echo JHtml::_('jgrid.isdefault', $item->home, $i, 'items.', ($item->language != '*' || !$item->home) && false); ?>
                            <?php elseif ($canChange): ?>
                                <a href="<?php echo JRoute::_('index.php?option=com_menus&task=items.unsetDefault&cid[]=' . $item->id . '&' . JSession::getFormToken() . '=1'); ?>">
                                    <?php echo JHtml::_('image', 'mod_languages/' . $item->image . '.gif', $item->language_title, array('title' => JText::sprintf('COM_MENUS_GRID_UNSET_LANGUAGE', $item->language_title)), true); ?>
                                </a>
                            <?php else: ?>
                                <?php echo JHtml::_('image', 'mod_languages/' . $item->image . '.gif', $item->language_title, array('title' => $item->language_title), true); ?>
                            <?php endif; ?>
                        <?php endif; ?>
                    </td>
                    <?php if (isset($app->menu_associations)) : ?>
                        <td class="center">
                            <?php if ($item->association): ?>
                                <?php echo JHtml::_('MenusHtml.Menus.association', $item->id); ?>
                            <?php endif; ?>
                        </td>
                    <?php endif; ?>
                    <td class="center">
                        <?php if ($item->language == ''): ?>
                            <?php echo JText::_('JDEFAULT'); ?>
                        <?php elseif ($item->language == '*'): ?>
                            <?php echo JText::alt('JALL', 'language'); ?>
                        <?php else: ?>
                            <?php echo $item->language_title ? $this->escape($item->language_title) : JText::_('JUNDEFINED'); ?>
                        <?php endif; ?>
                    </td>
                    <td class="center">
                        <span title="<?php echo sprintf('%d-%d', $item->lft, $item->rgt); ?>">
                            <?php echo (int) $item->id; ?></span>
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
        <input type="hidden" name="original_order_values" value="<?php echo implode($originalOrders, ','); ?>" />
        <?php echo JHtml::_('form.token'); ?>
    </div>
</form>
