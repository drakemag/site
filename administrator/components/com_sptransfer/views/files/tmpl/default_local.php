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
<?php foreach ($this->items_local as $i => $item): ?>
    <tr class="row<?php echo $i % 2; ?>">        
        <td>
            <?php if ($item->type == 1) : ?>
            <a href="#" onclick="browse_local('<?php echo $item->path; ?>');" >
                <?php echo JHtml::_('image', $item->icon_16, $item->name, null, true, true) ? JHtml::_('image', $item->icon_16, $item->name, array('width' => 16, 'height' => 16), true) : JHtml::_('image', 'com_sptransfer/con_info.png', $item->name, array('width' => 16, 'height' => 16), true); ?> 
            </a>
            <?php else : ?>
                <?php echo JHtml::_('image', $item->icon_16, $item->name, null, true, true) ? JHtml::_('image', $item->icon_16, $item->name, array('width' => 16, 'height' => 16), true) : JHtml::_('image', 'com_sptransfer/con_info.png', $item->name, array('width' => 16, 'height' => 16), true); ?> 
            <?php endif; ?>
        </td>
        <td class="left">
            <input type="hidden" name="input_prefixes[]" id="input_prefixes" value="<?php echo JText::_($item->name); ?>"  >
            <input type="hidden" name="input_names[]" id="input_names" value="<?php echo $item->type; ?>" >
            <?php if ($item->type == 1) : ?>
                <a href="#" onclick="browse_local('<?php echo $item->path; ?>');" >
                    <?php echo $item->name; ?>
                </a>
            <?php else : ?>
                <?php echo JText::_($item->name); ?>
            <?php endif; ?>
        </td>
        <td class="center">            
            <?php if ($item->type == 0) : ?>
                <?php echo SPTransferHelper::parseSize($item->size); ?>
            <?php endif; ?>
        </td>
    </tr>
<?php endforeach; ?>

