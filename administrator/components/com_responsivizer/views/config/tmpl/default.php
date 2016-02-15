<?php 
/** 
 * @package RESPONSIVIZER::CONFIG::administrator::components::com_responsivizer
 * @subpackage views
 * @subpackage config
 * @subpackage tmpl
 * @author Joomla! Extensions Store
 * @copyright (C) 2014 - Joomla! Extensions Store
 * @license GNU/GPLv2 http://www.gnu.org/licenses/gpl-2.0.html  
 */
defined ( '_JEXEC' ) or die ( 'Restricted access' ); 
?> 
<form action="index.php" method="post"  id="adminForm"  name="adminForm">  
	<?php 
	//API nuova JForm da config.xml con fields personalizzati in sostituzione di JElement 	  
	$fieldSets = $this->params_form->getFieldsets();
	$tabs = array();
	$contents = array();
	foreach ($fieldSets as $name => $fieldSet) :
		if(strpos($name, $this->fieldset) !== 0) {
			continue;
		}
		$label = JText::_(empty($fieldSet->label) ? 'COM_RESPONSIVIZER'.$name.'_FIELDSET_LABEL' : $fieldSet->label); 
		$tabs[] = "<li><a href='#$fieldSet->id' data-toggle='tab' data-element='$fieldSet->id'>$label</a></li>";
		ob_start(); ?>
		<div id="<?php echo $fieldSet->id;?>" class="tab-pane">
		<?php  
		foreach ($this->params_form->getFieldset($name) as $field):?>
			<div class="control-group">
				<div class="control-label"><?php echo $field->label; ?></div>
				<div class="controls"><?php echo $field->input; ?></div>
			</div>
		<?php endforeach; ?>
		</div>
		<?php 
		if($name == 'module_settings') {
			$fieldSets = $this->params_extended_form->getFieldsets();
			foreach ($fieldSets as $name => $fieldSet) :
				$label = JText::_('COM_RESPONSIVIZER_MODULE_IMAGES');
				$tabs[] = "<li><a href='#$fieldSet->id' data-toggle='tab' data-element='$fieldSet->id'>$label</a></li>";?>
				<div id="<?php echo $fieldSet->id;?>" class="tab-pane">
					<ul id="sortable">
						<li>
						<?php  
						foreach ($this->params_extended_form->getFieldset($name) as $field):?>
							<div class="control-group">
								<div class="control-label"><?php echo $field->label; ?></div>
								<div class="controls"><?php echo $field->input; ?></div>
							</div>
							<?php 
							if($field->type == 'Spacer'): ?>
								</li><li>
							<?php endif; ?>
						<?php endforeach; ?>
						</li>
					</ul>
				</div>
			<?php 
			endforeach;
			}
		?>
		<?php $contents[] = ob_get_clean();?>
	<?php endforeach; ?>
	
	<ul id="tab_configuration_<?php echo $this->fieldset;?>" class="nav nav-tabs"><?php echo implode('', $tabs);?></ul>
	<div id="config-responsivizer" class="tab-content current"><?php echo implode('', $contents);?></div> 
	<input type="hidden" name="option" value="<?php	echo $this->option;?>" /> 
	<input type="hidden" name="task" value="" />
</form> 

<!-- Modal -->
<div class="modal fade" id="suggestions_modal" data-backdrop="false" tabindex="-1" role="dialog" aria-labelledby="Suggestions" aria-hidden="true">
	<div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
          <h4 class="modal-title"><?php echo JText::_('COM_RESPONSIVIZER_SELECT_IMAGE_FILESYSTEM');?></h4>
        </div>
        <div class="modal-body">
        	<iframe id="dialog_iframe" scrolling="no"></iframe>
        </div>
      </div><!-- /.modal-content -->
	</div><!-- /.modal-dialog -->
</div><!-- /.modal -->