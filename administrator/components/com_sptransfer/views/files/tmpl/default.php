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
// load tooltip behavior
JHtml::_('behavior.tooltip');
JHtml::_('behavior.multiselect');
?>
<script type="text/javascript">
    Joomla.submitbutton = function(task) {

        form = document.getElementById('adminForm');
        if (task === 'files.transfer') {
            SPCYEND_core.transfer(task, form);
            return;
        }

        var myDomain = location.protocol + '//' +
                location.hostname +
                location.pathname.substring(0, location.pathname.lastIndexOf('/')) +
                //'/index.php?option=com_sptransfer&view=monitoring_log';                    
                '/components/com_sptransfer/log.htm';
        window.open(myDomain, 'SP Upgrade', 'width=640,height=480, scrollbars=1');
        Joomla.submitform(task);
    }
</script>
<form action="<?php echo JRoute::_('index.php?option=com_sptransfer&view=files'); ?>" method="post" name="adminForm" id="adminForm">
    <div class="alert alert-info" hidden="true" id="cyend_log"></div>
    <div class="alert" hidden="true" id="get_last_id"></div>
    <div id="sptransfer_table">
        <div class="span6">        
            <legend><?php echo JText::_('COM_SPTRANSFER_REMOTE_SITE'); ?></legend>
            <?php if (is_array($this->items_remote)) : ?>
                <table class="table table-condensed table-bordered table-striped" >
                    <thead><?php echo $this->loadTemplate('head_remote'); ?></thead>
                    <tfoot><?php echo $this->loadTemplate('foot_remote'); ?></tfoot>
                    <tbody><?php echo $this->loadTemplate('remote'); ?></tbody>
                </table>
            <?php endif; ?>
        </div>
        <div class="span6">
            <legend><?php echo JText::_('COM_SPTRANSFER_LOCAL_SITE'); ?></legend>
            <table class="table table-condensed table-bordered table-striped">
                <thead><?php echo $this->loadTemplate('head_local'); ?></thead>
                <tfoot><?php echo $this->loadTemplate('foot_local'); ?></tfoot>
                <tbody><?php echo $this->loadTemplate('local'); ?></tbody>
            </table>
        </div>
    </div>
    <div>
        <input type="hidden" id="folder_remote" name="folder_remote" value="<?php echo $this->folder_remote; ?>" />
        <input type="hidden" id="folder_local" name="folder_local" value="<?php echo $this->folder_local; ?>" />
        <input type="hidden" name="task" value="" />
        <input type="hidden" name="boxchecked" value="0" />
        <?php echo JHtml::_('form.token'); ?>
    </div>
</form>