<?php
/**
 * @package		SP Transfer
 * @subpackage	Components
 * @copyright	SP CYEND - All rights reserved.
 * @author		SP CYEND
 * @link		http://www.cyend.com
 * @license GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
 */
// No direct access to this file
defined('_JEXEC') or die('Restricted Access');
JHtml::_('behavior.modal');
?>
    <?php foreach ($this->items as $i => $item): ?>
        <tr class="row<?php echo $i % 2; ?>">
            <td class="hidden">
                <?php echo $item->id; ?>
            </td>
            <td>
                <?php echo JHtml::_('grid.id', $i, $item->id); ?>
            </td>	
            <td class="left" id="names<?php echo $i;?>">
                <?php echo JText::_($item->extension_name); ?>
                <?php echo ' -> '; ?>
                <?php echo JText::_($item->extension_name . '_' . $item->name); ?>
            </td>
            <td class="left">
                <?php echo JText::_($item->extension_name . '_' . $item->name . '_desc'); ?>
            </td>
            <td class="center">            
                <input type="text" name="input_ids[]" id="input_ids<?php echo $item->id;?>" value="" class="inputbox" size="45" aria-invalid="false">
                <input type="hidden" name="status[]" id="status<?php echo $i;?>" value="" >
            </td>
            <td class="left">
                <?php if ($this->dbTestConnection) : ?>
                    <div class="btn-group">            
                        <?php if (($item->extension_name . '_' . $item->name) == 'com_users_usergroups') : ?>
                            <a class="btn btn-mini btn-primary modal" title="<?php echo JText::_('COM_SPTRANSFER_CHOOSE'); ?>" href="<?php echo JRoute::_('index.php?option=com_sptransfer&amp;view=groups&amp;layout=default&amp;tmpl=component'); ?>" onclick="return false;" rel="{handler: 'iframe', size: {x: 900, y: 400}}"><?php echo JText::_('COM_SPTRANSFER_CHOOSE'); ?></a>
                        <?php endif; ?>
                        <?php if (($item->extension_name . '_' . $item->name) == 'com_users_viewlevels') : ?>
                            <a class="btn btn-mini btn-primary modal" title="<?php echo JText::_('COM_SPTRANSFER_CHOOSE'); ?>" href="<?php echo JRoute::_('index.php?option=com_sptransfer&amp;view=levels&amp;layout=default&amp;tmpl=component'); ?>" onclick="return false;" rel="{handler: 'iframe', size: {x: 900, y: 400}}"><?php echo JText::_('COM_SPTRANSFER_CHOOSE'); ?></a>
                        <?php endif; ?>
                        <?php if (($item->extension_name . '_' . $item->name) == 'com_users_users') : ?>
                            <a class="btn btn-mini btn-primary modal" title="<?php echo JText::_('COM_SPTRANSFER_CHOOSE'); ?>" href="<?php echo JRoute::_('index.php?option=com_sptransfer&amp;view=users&amp;layout=modal&amp;tmpl=component'); ?>" onclick="return false;" rel="{handler: 'iframe', size: {x: 900, y: 400}}"><?php echo JText::_('COM_SPTRANSFER_CHOOSE'); ?></a>
                        <?php endif; ?>
                        <?php if (($item->extension_name . '_' . $item->name) == 'com_content_categories') : ?>
                            <a class="btn btn-mini btn-primary modal" title="<?php echo JText::_('COM_SPTRANSFER_CHOOSE'); ?>" href="<?php echo JRoute::_('index.php?option=com_sptransfer&amp;view=categories&amp;extension=com_content&amp;layout=default&amp;tmpl=component'); ?>" onclick="return false;" rel="{handler: 'iframe', size: {x: 900, y: 400}}"><?php echo JText::_('COM_SPTRANSFER_CHOOSE'); ?></a>
                        <?php endif; ?>
                        <?php if (($item->extension_name . '_' . $item->name) == 'com_content_content') : ?>
                            <a class="btn btn-mini btn-primary modal" title="<?php echo JText::_('COM_SPTRANSFER_CHOOSE'); ?>" href="<?php echo JRoute::_('index.php?option=com_sptransfer&amp;view=articles&amp;layout=default&amp;tmpl=component'); ?>" onclick="return false;" rel="{handler: 'iframe', size: {x: 900, y: 400}}"><?php echo JText::_('COM_SPTRANSFER_CHOOSE'); ?></a>
                        <?php endif; ?>
                        <?php if (($item->extension_name . '_' . $item->name) == 'com_contact_categories') : ?>
                            <a class="btn btn-mini btn-primary modal" title="<?php echo JText::_('COM_SPTRANSFER_CHOOSE'); ?>" href="<?php echo JRoute::_('index.php?option=com_sptransfer&amp;view=categories&amp;extension=com_contact&amp;layout=default&amp;tmpl=component'); ?>" onclick="return false;" rel="{handler: 'iframe', size: {x: 900, y: 400}}"><?php echo JText::_('COM_SPTRANSFER_CHOOSE'); ?></a>
                        <?php endif; ?>                
                        <?php if (($item->extension_name . '_' . $item->name) == 'com_contact_contact_details') : ?>
                            <a class="btn btn-mini btn-primary modal" title="<?php echo JText::_('COM_SPTRANSFER_CHOOSE'); ?>" href="<?php echo JRoute::_('index.php?option=com_sptransfer&amp;view=contacts&amp;layout=default&amp;tmpl=component'); ?>" onclick="return false;" rel="{handler: 'iframe', size: {x: 900, y: 400}}"><?php echo JText::_('COM_SPTRANSFER_CHOOSE'); ?></a>
                        <?php endif; ?>
                        <?php if (($item->extension_name . '_' . $item->name) == 'com_weblinks_categories') : ?>
                            <a class="btn btn-mini btn-primary modal" title="<?php echo JText::_('COM_SPTRANSFER_CHOOSE'); ?>" href="<?php echo JRoute::_('index.php?option=com_sptransfer&amp;view=categories&amp;extension=com_weblinks&amp;layout=default&amp;tmpl=component'); ?>" onclick="return false;" rel="{handler: 'iframe', size: {x: 900, y: 400}}"><?php echo JText::_('COM_SPTRANSFER_CHOOSE'); ?></a>
                        <?php endif; ?>
                        <?php if (($item->extension_name . '_' . $item->name) == 'com_weblinks_weblinks') : ?>
                            <a class="btn btn-mini btn-primary modal" title="<?php echo JText::_('COM_SPTRANSFER_CHOOSE'); ?>" href="<?php echo JRoute::_('index.php?option=com_sptransfer&amp;view=weblinks&amp;layout=default&amp;tmpl=component'); ?>" onclick="return false;" rel="{handler: 'iframe', size: {x: 900, y: 400}}"><?php echo JText::_('COM_SPTRANSFER_CHOOSE'); ?></a>
                        <?php endif; ?>
                        <?php if (($item->extension_name . '_' . $item->name) == 'com_newsfeeds_categories') : ?>
                            <a class="btn btn-mini btn-primary modal" title="<?php echo JText::_('COM_SPTRANSFER_CHOOSE'); ?>" href="<?php echo JRoute::_('index.php?option=com_sptransfer&amp;view=categories&amp;extension=com_newsfeeds&amp;layout=default&amp;tmpl=component'); ?>" onclick="return false;" rel="{handler: 'iframe', size: {x: 900, y: 400}}"><?php echo JText::_('COM_SPTRANSFER_CHOOSE'); ?></a>
                        <?php endif; ?>
                        <?php if (($item->extension_name . '_' . $item->name) == 'com_newsfeeds_newsfeeds') : ?>
                            <a class="btn btn-mini btn-primary modal" title="<?php echo JText::_('COM_SPTRANSFER_CHOOSE'); ?>" href="<?php echo JRoute::_('index.php?option=com_sptransfer&amp;view=newsfeeds&amp;layout=default&amp;tmpl=component'); ?>" onclick="return false;" rel="{handler: 'iframe', size: {x: 900, y: 400}}"><?php echo JText::_('COM_SPTRANSFER_CHOOSE'); ?></a>
                        <?php endif; ?>
                        <?php if (($item->extension_name . '_' . $item->name) == 'com_banners_categories') : ?>
                            <a class="btn btn-mini btn-primary modal" title="<?php echo JText::_('COM_SPTRANSFER_CHOOSE'); ?>" href="<?php echo JRoute::_('index.php?option=com_sptransfer&amp;view=categories&amp;extension=com_banners&amp;layout=default&amp;tmpl=component'); ?>" onclick="return false;" rel="{handler: 'iframe', size: {x: 900, y: 400}}"><?php echo JText::_('COM_SPTRANSFER_CHOOSE'); ?></a>
                        <?php endif; ?>
                        <?php if (($item->extension_name . '_' . $item->name) == 'com_banners_banner_clients') : ?>
                            <a class="btn btn-mini btn-primary modal" title="<?php echo JText::_('COM_SPTRANSFER_CHOOSE'); ?>" href="<?php echo JRoute::_('index.php?option=com_sptransfer&amp;view=clients&amp;layout=default&amp;tmpl=component'); ?>" onclick="return false;" rel="{handler: 'iframe', size: {x: 900, y: 400}}"><?php echo JText::_('COM_SPTRANSFER_CHOOSE'); ?></a>
                        <?php endif; ?>
                        <?php if (($item->extension_name . '_' . $item->name) == 'com_banners_banners') : ?>
                            <a class="btn btn-mini btn-primary modal" title="<?php echo JText::_('COM_SPTRANSFER_CHOOSE'); ?>" href="<?php echo JRoute::_('index.php?option=com_sptransfer&amp;view=banners&amp;layout=default&amp;tmpl=component'); ?>" onclick="return false;" rel="{handler: 'iframe', size: {x: 900, y: 400}}"><?php echo JText::_('COM_SPTRANSFER_CHOOSE'); ?></a>
                        <?php endif; ?>
                        <?php if (($item->extension_name . '_' . $item->name) == 'com_menus_menu_types') : ?>
                            <a class="btn btn-mini btn-primary modal" title="<?php echo JText::_('COM_SPTRANSFER_CHOOSE'); ?>" href="<?php echo JRoute::_('index.php?option=com_sptransfer&amp;view=menus&amp;layout=default&amp;tmpl=component'); ?>" onclick="return false;" rel="{handler: 'iframe', size: {x: 900, y: 400}}"><?php echo JText::_('COM_SPTRANSFER_CHOOSE'); ?></a>
                        <?php endif; ?>
                        <?php if (($item->extension_name . '_' . $item->name) == 'com_menus_menu') : ?>
                            <a class="btn btn-mini btn-primary modal" title="<?php echo JText::_('COM_SPTRANSFER_CHOOSE'); ?>" href="<?php echo JRoute::_('index.php?option=com_sptransfer&amp;view=items&amp;layout=default&amp;tmpl=component'); ?>" onclick="return false;" rel="{handler: 'iframe', size: {x: 900, y: 400}}"><?php echo JText::_('COM_SPTRANSFER_CHOOSE'); ?></a>
                        <?php endif; ?>
                        <?php if (($item->extension_name . '_' . $item->name) == 'com_modules_modules') : ?>
                            <a class="btn btn-mini btn-primary modal" title="<?php echo JText::_('COM_SPTRANSFER_CHOOSE'); ?>" href="<?php echo JRoute::_('index.php?option=com_sptransfer&amp;view=modules&amp;layout=default&amp;tmpl=component'); ?>" onclick="return false;" rel="{handler: 'iframe', size: {x: 900, y: 400}}"><?php echo JText::_('COM_SPTRANSFER_CHOOSE'); ?></a>
                        <?php endif; ?>
                        <?php if (($item->extension_name . '_' . $item->name) == 'com_users_notes') : ?>
                            <a class="btn btn-mini btn-primary modal" title="<?php echo JText::_('COM_SPTRANSFER_CHOOSE'); ?>" href="<?php echo JRoute::_('index.php?option=com_sptransfer&amp;view=notes&amp;layout=default&amp;tmpl=component'); ?>" onclick="return false;" rel="{handler: 'iframe', size: {x: 900, y: 400}}"><?php echo JText::_('COM_SPTRANSFER_CHOOSE'); ?></a>
                        <?php endif; ?>
                        <?php if (($item->extension_name . '_' . $item->name) == 'com_users_categories') : ?>
                            <a class="btn btn-mini btn-primary modal" title="<?php echo JText::_('COM_SPTRANSFER_CHOOSE'); ?>" href="<?php echo JRoute::_('index.php?option=com_sptransfer&amp;view=categories&amp;extension=com_users&amp;layout=default&amp;tmpl=component'); ?>" onclick="return false;" rel="{handler: 'iframe', size: {x: 900, y: 400}}"><?php echo JText::_('COM_SPTRANSFER_CHOOSE'); ?></a>
                        <?php endif; ?>
                        <?php if (($item->extension_name . '_' . $item->name) == 'com_tags_tags') : ?>
                            <a class="btn btn-mini btn-primary modal" title="<?php echo JText::_('COM_SPTRANSFER_CHOOSE'); ?>" href="<?php echo JRoute::_('index.php?option=com_sptransfer&amp;view=tags&amp;layout=default&amp;tmpl=component'); ?>" onclick="return false;" rel="{handler: 'iframe', size: {x: 900, y: 400}}"><?php echo JText::_('COM_SPTRANSFER_CHOOSE'); ?></a>
                        <?php endif; ?>

                        <a class="btn btn-mini" title="<?php echo JText::_('JCLEAR'); ?>" href="#" onclick="jClearItem('<?php echo $item->extension_name; ?>', '<?php echo $item->name; ?>');"><?php echo JText::_('JCLEAR'); ?></a>
                    </div>
                <?php endif; ?>
            </td>
        </tr>
    <?php endforeach; ?>
