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
<form action="index.php" method="post" name="adminForm" id="adminForm"> 
	<?php foreach ($this->rowFileData as $position2Match=>$positionsMatched): ?>
	<span class="label label-info positions"><?php echo JText::_('COM_RESPONSIVIZER_NATIVE_POSITION');?>
	<span class="badge badge-inverse-info"><?php echo $position2Match;?></span>
	</span>
	<div class="well positions">
		<span class="badge"><?php echo JText::_('COM_RESPONSIVIZER_EDIT_ASSOCIATIONS');?></span>
		<input type="hidden" id="<?php echo $position2Match;?>" name="jform[<?php echo $position2Match;?>]" style="width:500px" value="<?php echo implode(',', $positionsMatched);?>"/>
	</div>
	<?php endforeach; ?>

	<input type="hidden" name="option" value="<?php echo $this->option?>" />
	<input type="hidden" name="task" value="" />
</form>