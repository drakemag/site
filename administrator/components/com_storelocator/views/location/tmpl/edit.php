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

// Google Maps Inclusion - with Optional Key
$params = JComponentHelper::getParams('com_storelocator');
$googleKey = $params->get( 'google_maps_v3_api_key', '' );
$document->addScript('https://maps.googleapis.com/maps/api/js?v=3.9&sensor=false&key='.$googleKey); // Load v3 API with Key
?>
<script type="text/javascript">
    
	jQuery(document).ready(function(){
		jQuery('#jform_catid').append('<option value="">None</option>');
		jQuery('#jform_catid').chosen().change(function(){
			if(jQuery('#jform_catid option:selected').length == 0){
			jQuery("#jform_catid option[value='']").attr('selected','selected');
			}
		});
	});
	
	jQuery(document).ready(function(){
		jQuery('#jform_tags').append('<option value="">None</option>');
		jQuery('#jform_tags').chosen().change(function(){
			if(jQuery('#jform_tags option:selected').length == 0){
			jQuery("#jform_tags option[value='']").attr('selected','selected');
			}
		});
	});

    Joomla.submitbutton = function(task)
    {
        if(task == 'location.cancel'){
            Joomla.submitform(task, document.getElementById('location-form'));
        }
        else
		{
			
			
			// do coordinate field validation
			if ( jQuery('#jform_lat').val() == '' || isNaN(jQuery('#jform_lat').val()) 
						|| jQuery('#jform_lat').val() < -90 || jQuery('#jform_lat').val() > 90)
			{
					jQuery('#errormodal .modal-body').html('<p class="text-error">Latitude and longitude values must correspond to a valid location on the face of the earth. Latitude values can take any value between -90 and 90</p>');
					jQuery('#errormodal').modal();				
			} 
			else if ( jQuery('#jform_long').val() == '' || isNaN(jQuery('#jform_long').val()) 
						||  jQuery('#jform_long').val() < -180 || jQuery('#jform_long').val() > 180)
			{
					jQuery('#errormodal .modal-body').html('<p class="text-error">Latitude and longitude values must correspond to a valid location on the face of the earth. Longitude values can take any value between -180 and 180</p>');
					jQuery('#errormodal').modal();	
			} else {
				if (task != 'location.cancel' && document.formvalidator.isValid(document.id('location-form'))) {
					jQuery('#jform_lat').prop('disabled', false);
					jQuery('#jform_long').prop('disabled', false);
					Joomla.submitform(task, document.getElementById('location-form'));
				}
				else {
					jQuery('#errormodal .modal-body').html('<p class="text-error"><?php echo $this->escape(JText::_('JGLOBAL_VALIDATION_FORM_FAILED')); ?></p>');
					jQuery('#errormodal').modal();
				}
			}
        }
    }
</script>

<form action="<?php echo JRoute::_('index.php?option=com_storelocator&layout=edit&id=' . (int) $this->item->id); ?>" method="post" enctype="multipart/form-data" name="adminForm" id="location-form" class="form-validate">
    <div class="row-fluid">
        <div class="span10 form-horizontal">
            <fieldset class="adminform">

            
			<div class="control-group">
				<div class="control-label"><?php echo $this->form->getLabel('name'); ?></div>
				<div class="controls"><?php echo $this->form->getInput('name'); ?></div>
			</div>
			<div class="control-group">
				<div class="control-label"><?php echo $this->form->getLabel('address'); ?></div>
				<div class="controls"><?php echo $this->form->getInput('address'); ?> &nbsp; 
                <button name="getcoords" type="button" onclick="gatherCoords()" id="auto_cal_button" class="btn btn-info">Calculate Coordinates</button></div>
			</div>
            
            <div class="control-group">
				<div class="control-label">Auto Calculate</div>
				<div class="controls">
                
                	<label class="radio">
                    	<input type="radio" name="auto_cal" id="auto_cal_yes" value="1" checked onclick="setAutoType();">
                    	Use address to determine Latitude / Longitude. Click Calculate Coordinates.
                    </label>
                    <label class="radio">
                    	<input type="radio" name="auto_cal" id="auto_cal_no" value="0" onclick="setAutoType();" >
                    	Enable Manual Entry
                    </label>
                </div>
			</div>
			<div class="control-group">
				<div class="control-label"><?php echo $this->form->getLabel('lat'); ?></div>
				<div class="controls"><?php echo $this->form->getInput('lat'); ?></div>
			</div>
			<div class="control-group">
				<div class="control-label"><?php echo $this->form->getLabel('long'); ?></div>
				<div class="controls"><?php echo $this->form->getInput('long'); ?></div>
			</div>
			<div class="control-group">
				<div class="control-label"><?php echo $this->form->getLabel('catid'); ?></div>
				<div class="controls"><?php echo $this->form->getInput('catid'); ?></div>
			</div>

			<?php
				foreach((array)$this->item->catid as $value): 
					if(!is_array($value)):
						echo '<input type="hidden" class="catid" name="jform[catidhidden]['.$value.']" value="'.$value.'" />';
					endif;
				endforeach;
			?>
			<script type="text/javascript">
				jQuery('input:hidden.catid').each(function(){
					var name = jQuery(this).attr('name');
					if(name.indexOf('catidhidden')){
						jQuery('#jform_catid option[value="'+jQuery(this).val()+'"]').attr('selected',true);
					}
				});
			</script>			<div class="control-group">
				<div class="control-label"><?php echo $this->form->getLabel('tags'); ?></div>
				<div class="controls"><?php echo $this->form->getInput('tags'); ?></div>
			</div>

			<?php
				foreach((array)$this->item->tags as $value): 
					if(!is_array($value)):
						echo '<input type="hidden" class="tags" name="jform[tagshidden]['.$value.']" value="'.$value.'" />';
					endif;
				endforeach;
			?>
			<script type="text/javascript">
				jQuery('input:hidden.tags').each(function(){
					var name = jQuery(this).attr('name');
					if(name.indexOf('tagshidden')){
						jQuery('#jform_tags option[value="'+jQuery(this).val()+'"]').attr('selected',true);
					}
				});
			</script>			
            <div class="control-group">
				<div class="control-label"><?php echo $this->form->getLabel('state'); ?></div>
				<div class="controls"><?php echo $this->form->getInput('state'); ?></div>
			</div>
			<div class="control-group">
				<div class="control-label"><?php echo $this->form->getLabel('featured'); ?></div>
				<div class="controls"><?php echo $this->form->getInput('featured'); ?></div>
			</div>
			<div class="control-group">
				<div class="control-label"><?php echo $this->form->getLabel('description'); ?></div>
				<div class="controls"><?php echo $this->form->getInput('description'); ?></div>
			</div>
			<div class="control-group">
				<div class="control-label"><?php echo $this->form->getLabel('phone'); ?></div>
				<div class="controls"><?php echo $this->form->getInput('phone'); ?></div>
			</div>
			<div class="control-group">
				<div class="control-label"><?php echo $this->form->getLabel('website'); ?></div>
				<div class="controls"><?php echo $this->form->getInput('website'); ?></div>
			</div>
			<div class="control-group">
				<div class="control-label"><?php echo $this->form->getLabel('facebook'); ?></div>
				<div class="controls"><?php echo $this->form->getInput('facebook'); ?></div>
			</div>
			<div class="control-group">
				<div class="control-label"><?php echo $this->form->getLabel('twitter'); ?></div>
				<div class="controls">
                	<div class="input-prepend">
                    	<span class="add-on">@</span>
                    	<?php echo $this->form->getInput('twitter'); ?>
                 </div>
				</div>
			</div>
			<div class="control-group">
				<div class="control-label"><?php echo $this->form->getLabel('email'); ?></div>
				<div class="controls"><?php echo $this->form->getInput('email'); ?></div>
			</div>
			<div class="control-group">
				<div class="control-label"><?php echo $this->form->getLabel('cust1'); ?></div>
				<div class="controls"><?php echo $this->form->getInput('cust1'); ?></div>
			</div>
			<div class="control-group">
				<div class="control-label"><?php echo $this->form->getLabel('cust2'); ?></div>
				<div class="controls"><?php echo $this->form->getInput('cust2'); ?></div>
			</div>
			<div class="control-group">
				<div class="control-label"><?php echo $this->form->getLabel('cust3'); ?></div>
				<div class="controls"><?php echo $this->form->getInput('cust3'); ?></div>
			</div>
			<div class="control-group">
				<div class="control-label"><?php echo $this->form->getLabel('cust4'); ?></div>
				<div class="controls"><?php echo $this->form->getInput('cust4'); ?></div>
			</div>
			<div class="control-group">
				<div class="control-label"><?php echo $this->form->getLabel('cust5'); ?></div>
				<div class="controls"><?php echo $this->form->getInput('cust5'); ?></div>
			</div>
			<div class="control-group">
				<div class="control-label"><?php echo $this->form->getLabel('publish_up'); ?></div>
				<div class="controls"><?php echo $this->form->getInput('publish_up'); ?></div>
			</div>
			<div class="control-group">
				<div class="control-label"><?php echo $this->form->getLabel('publish_down'); ?></div>
				<div class="controls"><?php echo $this->form->getInput('publish_down'); ?></div>
			</div>
			<div class="control-group">
				<div class="control-label"><?php echo $this->form->getLabel('created_by'); ?></div>
				<div class="controls"><?php echo $this->form->getInput('created_by'); ?></div>
			</div>
			<div class="control-group">
				<div class="control-label"><?php echo $this->form->getLabel('access'); ?></div>
				<div class="controls"><?php echo $this->form->getInput('access'); ?></div>
			</div>

			<div class="control-group">
				<div class="control-label"><?php echo $this->form->getLabel('id'); ?></div>
				<div class="controls"><?php echo $this->form->getInput('id'); ?></div>
			</div>
            </fieldset>
        </div>

        

        <input type="hidden" name="task" value="" />
        <?php echo JHtml::_('form.token'); ?>

    </div>
</form>
<script type="text/javascript">
	
	jQuery('#jform_lat').prop('disabled', true);
	jQuery('#jform_long').prop('disabled', true);
	
	function gatherCoords() 
	{

		var geocoder = new google.maps.Geocoder();
		var address = jQuery('#jform_address').val();
		
		geocoder.geocode({address: address}, function(results, status) {
			if (status == google.maps.GeocoderStatus.OK) {
				jQuery('#jform_lat').val( results[0].geometry.location.lat() );
				jQuery('#jform_long').val( results[0].geometry.location.lng() );
			} else { //implement errors				 
				jQuery('#errormodal .modal-body').html('<p class="text-error">Address: ' + address + ' not found</p>');
				jQuery('#errormodal').modal();
			}
		});
	}
	
	function setAutoType()
	{
		if (jQuery('#auto_cal_yes').prop('checked'))
		{
			jQuery('#jform_lat').prop('disabled', true);
			jQuery('#jform_long').prop('disabled', true);
			jQuery('#auto_cal_button').show();
		} else {
			jQuery('#jform_lat').prop('disabled', false);
			jQuery('#jform_long').prop('disabled', false);
			jQuery('#auto_cal_button').hide();
		}
	
	}
	
</script>

    <div id="errormodal" class="modal hide fade">
        <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
            <h3 class="mhead">Location Error</h3>
        </div>
        <div class="modal-body">
        </div>
    </div>