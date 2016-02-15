<?php
/**
 * @package		SP Transfer
 * @subpackage	Components
 * @copyright	SP CYEND - All rights reserved.
 * @author		SP CYEND
 * @link		http://www.cyend.com
 * @license GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
 */
// No direct access.
defined('_JEXEC') or die;

JHtml::_('behavior.tooltip');
JHtml::_('behavior.multiselect');

$client = $this->state->get('filter.client_id') ? 'administrator' : 'site';
$user = JFactory::getUser();
$listOrder = $this->escape($this->state->get('list.ordering'));
$listDirn = $this->escape($this->state->get('list.direction'));
?>
<div class="btn-group" id="toolbar">
    <a class="btn btn-primary" href="#" onclick="if (document.adminForm.boxchecked.value==0){alert('Please first make a selection from the list');}else{if (window.parent) window.parent.jSelectItem('com_modules', 'modules', findChecked());}" class="toolbar">
        <?php echo JText::_('COM_SPTRANSFER_CHOOSE'); ?>
    </a>
</div>
<div class="clr"> </div>
<form action="<?php echo JRoute::_('index.php?option=com_sptransfer&amp;view=modules&amp;layout=default&amp;tmpl=component'); ?>" method="post" name="adminForm" id="adminForm">
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
            <select name="filter_client_id" class="inputbox" onchange="this.form.submit()">
                <?php echo JHtml::_('select.options', $this->clientoptions, 'value', 'text', $this->state->get('filter.client_id')); ?>
            </select>
            <select name="filter_state" class="inputbox" onchange="this.form.submit()">
                <option value=""><?php echo JText::_('JOPTION_SELECT_PUBLISHED'); ?></option>
                <?php echo JHtml::_('select.options', $this->stateoptions, 'value', 'text', $this->state->get('filter.state')); ?>
            </select>

            <select name="filter_position" class="inputbox" onchange="this.form.submit()">
                <option value=""><?php echo JText::_('COM_MODULES_OPTION_SELECT_POSITION'); ?></option>
                <?php echo JHtml::_('select.options', $this->positions, 'value', 'text', $this->state->get('filter.position')); ?>
            </select>

            <select name="filter_module" class="inputbox" onchange="this.form.submit()">
                <option value=""><?php echo JText::_('COM_MODULES_OPTION_SELECT_MODULE'); ?></option>
                <?php echo JHtml::_('select.options', $this->modules, 'value', 'text', $this->state->get('filter.module')); ?>
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

    <table class="adminlist" id="modules-mgr">
        <thead>
            <tr>
                <th width="1%">
                    <input type="checkbox" name="checkall-toggle" value="" onclick="Joomla.checkAll(this)" />
                </th>
                <th class="title">
                    <?php echo JHtml::_('grid.sort', 'JGLOBAL_TITLE', 'a.title', $listDirn, $listOrder); ?>
                </th>
                <th width="5%">
                    <?php echo JHtml::_('grid.sort', 'JSTATUS', 'a.published', $listDirn, $listOrder); ?>
                </th>
                <th width="15%" class="left">
                    <?php echo JHtml::_('grid.sort', 'COM_MODULES_HEADING_POSITION', 'position', $listDirn, $listOrder); ?>
                </th>
                <th width="10%">
                    <?php echo JHtml::_('grid.sort', 'JGRID_HEADING_ORDERING', 'ordering', $listDirn, $listOrder); ?>
                </th>
                <th width="10%" class="left" >
                    <?php echo JHtml::_('grid.sort', 'COM_MODULES_HEADING_MODULE', 'name', $listDirn, $listOrder); ?>
                </th>
                <th width="10%">
                    <?php echo JHtml::_('grid.sort', 'COM_MODULES_HEADING_PAGES', 'pages', $listDirn, $listOrder); ?>
                </th>
                <th width="10%">
                    <?php echo JHtml::_('grid.sort', 'JGRID_HEADING_ACCESS', 'a.access', $listDirn, $listOrder); ?>
                </th>
                <th width="5%">
                    <?php echo JHtml::_('grid.sort', 'JGRID_HEADING_LANGUAGE', 'language_title', $listDirn, $listOrder); ?>
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
                <td colspan="9">
                    <?php echo $this->pagination->getListFooter(); ?>
                </td>
            </tr>
        </tfoot>
        <tbody>
            <?php
            foreach ($this->items as $i => $item) :
                $ordering = ($listOrder == 'ordering');
                ?>
                <tr class="row<?php echo $i % 2; ?>">
                    <td class="center">
                        <?php echo JHtml::_('grid.id', $i, $item->id); ?>
                    </td>
                    <td>
                        <?php if ($item->checked_out) : ?>
                            <?php echo JHtml::_('jgrid.checkedout', $i, $item->editor, $item->checked_out_time, 'modules.', false); ?>
                        <?php endif; ?>
                        <?php echo $this->escape($item->title); ?>

                        <?php if (!empty($item->note)) : ?>
                            <p class="smallsub">
                                <?php echo JText::sprintf('JGLOBAL_LIST_NOTE', $this->escape($item->note)); ?></p>
                        <?php endif; ?>
                    </td>
                    <td class="center">
                        <?php echo JHtml::_('jgrid.published', $item->published, $i, 'newsfeeds.', false, 'cb'); ?>
                    </td>
                    <td class="left">
                        <?php if ($item->position) : ?>
                            <?php echo $item->position; ?>
                        <?php else : ?>
                            <?php echo ':: ' . JText::_('JNONE') . ' ::'; ?>
                        <?php endif; ?>
                    </td>
                    <td class="order">
                        <?php echo $item->ordering; ?>
                    </td>
                    <td class="left">
                        <?php echo $item->name; ?>
                    </td>
                    <td class="center">
                        <?php echo $item->pages; ?>
                    </td>

                    <td class="center">
                        <?php echo $this->escape($item->access_level); ?>
                    </td>
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
