<?php 
/** 
 * @package RESPONSIVIZER::MODULESMANAGER::administrator::components::com_responsivizer
 * @subpackage views
 * @subpackage modulesmanager
 * @subpackage tmpl
 * @author Joomla! Extensions Store
 * @copyright (C) 2014 - Joomla! Extensions Store
 * @license GNU/GPLv2 http://www.gnu.org/licenses/gpl-2.0.html  
 */
defined ( '_JEXEC' ) or die ( 'Restricted access' ); 
?>
<form action="index.php" method="post" name="adminForm" id="adminForm" class="copy"> 
	
	<div id="left_source">
		<div class="well">
			<label class="label label-info" id="source_moduletitle" data-moduletitle="<?php echo $this->record->title;?>">
				<?php echo JText::sprintf('COM_RESPONSIVIZER_COPY_MODULE_SOURCE', '<span class="badge badge-inverse-info">' . $this->record->title . '</span>');?>
			</label>
			<div></div>
			<label class="label label-info" id="source_position" data-position="<?php echo $this->record->position;?>">
				<?php echo JText::sprintf('COM_RESPONSIVIZER_COPY_MODULE_SOURCE_POSITION', '<span class="badge badge-inverse-info">' . ($this->record->position ? $this->record->position : 'N.A.') . '</span>');?>
			</label>
			<div class="copyarrow"></div>
		</div>
		<div class="well left">
			<iframe src="<?php echo JURI::root();?>?tp=1&amp;noswitch=1" class="copy_iframe source"></iframe>
		</div>
	</div>
	
	<div id="right_target">
		<div class="well">
			<label class="label label-info right">
				<?php echo JText::sprintf('COM_RESPONSIVIZER_DUPLICATED_MODULE_NAME', '<input type="text" data-validation="required" class="labelctrls" name="title" value="Responsivizer - ' . $this->record->title . '" />');?>
			</label>
			<div></div>
			<label class="label label-info">
				<?php echo JText::sprintf('COM_RESPONSIVIZER_CHOOSE_TARGET_POSITION', $this->lists['positions']);?>
			</label>
		</div>
		<div class="well right">
			<iframe src="<?php echo JURI::root();?>?template=responsivizer&tp=1" class="copy_iframe target"></iframe>
		</div>
	</div>
	<input type="hidden" name="option" value="<?php echo $this->option?>" />
	<input type="hidden" name="id" value="<?php echo $this->record->id; ?>" />
	<input type="hidden" name="task" value="" />
</form>