<?php
/**
 * @version     2.0.0
 * @package     com_storelocator
 * @copyright   Copyright (C) 2013. Sysgen Media LLC. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 * @author      Sysgen Media <support@sysgenmedia.com> - http://www.sysgenmedia.com
 */
// no direct access
defined('_JEXEC') or die;

JHtml::addIncludePath(JPATH_COMPONENT . '/helpers/html');
JHtml::_('behavior.tooltip');
JHtml::_('behavior.formvalidation');
JHtml::_('formbehavior.chosen', 'select');
JHtml::_('behavior.keepalive');

// Import CSS
$document = JFactory::getDocument();
$document->addStyleSheet('components/com_storelocator/assets/css/storelocator.css');
?>
<script type="text/javascript">
    
    
    Joomla.submitbutton = function(task)
    {
        if(task == 'category.cancel'){
            Joomla.submitform(task, document.getElementById('category-form'));
        }
        else{
            
            if (task != 'category.cancel' && document.formvalidator.isValid(document.id('category-form'))) {
                Joomla.submitform(task, document.getElementById('category-form'));
            }
            else {
                alert('<?php echo $this->escape(JText::_('JGLOBAL_VALIDATION_FORM_FAILED')); ?>');
            }
        }
    }
</script>

<form action="<?php echo JRoute::_('index.php?option=com_storelocator&layout=edit&id=' . (int) $this->item->id); ?>" method="post" enctype="multipart/form-data" name="adminForm" id="category-form" class="form-validate">
    <div class="row-fluid">
        <div class="span10 form-horizontal">
            <fieldset class="adminform">

                			<div class="control-group">
				<div class="control-label"><?php echo $this->form->getLabel('id'); ?></div>
				<div class="controls"><?php echo $this->form->getInput('id'); ?></div>
			</div>
			<div class="control-group">
				<div class="control-label"><?php echo $this->form->getLabel('name'); ?></div>
				<div class="controls"><?php echo $this->form->getInput('name'); ?></div>
			</div>
			<div class="control-group">
				<div class="control-label"><?php echo $this->form->getLabel('markerid'); ?></div>
				<div class="controls"><?php echo $this->form->getInput('markerid'); ?></div>
			</div>

			<?php
				foreach((array)$this->item->markerid as $value): 
					if(!is_array($value)):
						echo '<input type="hidden" class="markerid" name="jform[markeridhidden]['.$value.']" value="'.$value.'" />';
					endif;
				endforeach;
			?>
			<script type="text/javascript">
				jQuery('input:hidden.markerid').each(function(){
					var name = jQuery(this).attr('name');
					if(name.indexOf('markeridhidden')){
						jQuery('#jform_markerid option[value="'+jQuery(this).val()+'"]').attr('selected',true);
					}
				});
			</script>

            </fieldset>
        </div>

        

        <input type="hidden" name="task" value="" />
        <?php echo JHtml::_('form.token'); ?>

    </div>
</form>