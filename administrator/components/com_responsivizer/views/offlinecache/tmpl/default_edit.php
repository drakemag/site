<?php 
/** 
 * @package RESPONSIVIZER::SOURCES::administrator::components::com_responsivizer
 * @subpackage views
 * @subpackage sources
 * @subpackage tmpl
 * @author Joomla! Extensions Store
 * @copyright (C) 2014 - Joomla! Extensions Store
 * @license GNU/GPLv2 http://www.gnu.org/licenses/gpl-2.0.html  
 */
defined ( '_JEXEC' ) or die ( 'Restricted access' ); 
?>
<form action="index.php" method="post" name="adminForm" id="adminForm"> 
	<div class="accordion-group">
    	<div class="accordion-heading opened">
	    	<div class="accordion-toggle noaccordion">
	      		<h4><?php echo JText::_( 'COM_RESPONSIVIZER_PAGE_DETAILS' ); ?></h4>
	    	</div>
    	</div>
    	<div id="collapseOne" class="accordion-body collapse in">
	      	<div class="accordion-inner">
	        	<table class="admintable">
					<tbody>
						<tr>
							<td class="key left_title">
								<label for="title">
									<?php echo JText::_( 'COM_RESPONSIVIZER_CHOOSE_PAGE' ); ?>:
								</label>
							</td>
							<td class="right_details">
								<?php echo $this->lists['itemids']; ?>
							</td>
						</tr>
						<tr>
							<td class="key left_title">
								<label for="type">
									<?php echo JText::_( 'COM_RESPONSIVIZER_DESCRIPTION' ); ?>:
								</label>
							</td>
							<td class="right_details">
								<textarea class="inputbox" type="text" name="description" id="description" rows="5" cols="80"><?php echo $this->record->description;?></textarea>
							</td>
						</tr> 
						<tr>
							<td class="key left_title">
								<label for="description">
									<?php echo JText::_( 'COM_RESPONSIVIZER_PUBLISHED' ); ?>:
								</label>
							</td>
							<td class="right_details">
								<fieldset class="radio btn-group small">
									<?php echo $this->lists['published']; ?>
								</fieldset>
							</td>
						</tr> 
						
						<tr>
							<td class="key left_title">
								<label for="description">
									<?php echo JText::_( 'COM_RESPONSIVIZER_PROCESS_CSS' ); ?>:
								</label>
							</td>
							<td class="right_details">
								<fieldset class="radio btn-group small">
									<?php echo $this->lists['process_css']; ?>
								</fieldset>
							</td>
						</tr> 
						
						<tr>
							<td class="key left_title">
								<label for="description">
									<?php echo JText::_( 'COM_RESPONSIVIZER_PROCESS_JS' ); ?>:
								</label>
							</td>
							<td class="right_details">
								<fieldset class="radio btn-group small">
									<?php echo $this->lists['process_js']; ?>
								</fieldset>
							</td>
						</tr> 
						
						<tr>
							<td class="key left_title">
								<label for="description">
									<?php echo JText::_( 'COM_RESPONSIVIZER_PROCESS_IMG' ); ?>:
								</label>
							</td>
							<td class="right_details">
								<fieldset class="radio btn-group small">
									<?php echo $this->lists['process_img']; ?>
								</fieldset>
							</td>
						</tr> 
						
						
						<tr>
							<td class="key left_title">
								<label for="description">
									<?php echo JText::_( 'COM_RESPONSIVIZER_CACHED_RESOURCES' ); ?>:
								</label>
							</td>
							<td class="right_details">
								<div class="well">
									<?php echo implode('<div class="spacer"></div>', $this->record->resources);?>
								</div>
							</td>
						</tr> 
					</tbody>
				</table>
	  		</div>
	    </div>
  	</div>

	<input type="hidden" name="option" value="<?php echo $this->option?>" />
	<input type="hidden" name="id" value="<?php echo $this->record->id; ?>" />
	<input type="hidden" name="haschanged" id="haschanged" value="0" />
	<input type="hidden" name="task" value="" />
</form>