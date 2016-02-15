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

JHtml::addIncludePath(JPATH_COMPONENT.'/helpers/html');
JHtml::_('bootstrap.tooltip');
JHtml::_('behavior.multiselect');
JHtml::_('formbehavior.chosen', 'select');
JHtml::_('behavior.framework');

// Import CSS
$document = JFactory::getDocument();
$document->addStyleSheet('components/com_storelocator/assets/css/storelocator.css');

//Joomla Component Creator code to allow adding non select list filters
if (!empty($this->extra_sidebar)) {
    $this->sidebar .= $this->extra_sidebar;
}
?>

<form action="<?php echo JRoute::_('index.php?option=com_storelocator&view=importexport'); ?>" method="post" name="adminForm" id="adminForm" enctype="multipart/form-data">
  <?php if(!empty($this->sidebar)): ?>
  <div id="j-sidebar-container" class="span2"> <?php echo $this->sidebar; ?> </div>
  <div id="j-main-container" class="span10">
  <?php else : ?>
  <div id="j-main-container">
    <?php endif;?>
    <div class="row-fluid">
      <div class="span5">
        <h2><img src="components/com_storelocator/assets/images/Load.png" alt="Import" width="48" height="48" align="absmiddle" />CSV Import </h2>
        <p>
          <label class="checkbox">
            <input name="skipfirst" type="checkbox" id="skipfirst" checked="checked" />
            First Row Contains Column Names </label>
          <label><strong>Please Select a CSV file to Import:</strong></label>
          <input type="file" name="csvfile" id="csvfile" data-label="Choose CSV File" />
           
          <small class="muted">Max File Size <?php echo ini_get('upload_max_filesize')?></small> </p>
        <p>
          
          <input type="button" name="sample" id="sample" value="Export a Sample File" onclick="Joomla.submitbutton('importexport.sample')" class="btn" />
          <input type="button" name="import" id="import" value="Import" onclick="Joomla.submitbutton('importexport.import')" class="btn btn-primary" />
        </p>
      </div>
      <div class="span5">
        <h2><img src="components/com_storelocator/assets/images/Save.png" alt="Export" width="48" height="48" align="absmiddle" />CSV Export </h2>
        <p>
          <label for="exp_cat"><strong>Select Categories to Export:</strong></label>
          <?php echo $this->categories; ?> </p>
        <p>
          <input type="button" name="exportcsv" id="exportcsv" value="Export" onclick="Joomla.submitbutton('importexport.export')" class="btn btn-info" />
        </p>
      </div>
    </div>    
    <input type="hidden" name="task" value="" />
    <input type="hidden" name="boxchecked" value="0" />
    <?php echo JHtml::_('form.token'); ?> </div>
</form>
<script>



(function( $ ) {
  $.fn.niceFileField = function() {
    this.each(function(index, file_field) {
      file_field = $(file_field);
      var label = file_field.attr("data-label") || "Choose File";

      file_field.css({"display": "none"});
      file_field.after("<div class=\"nice_file_field input-append\"><input class=\"input span4\" type=\"text\"><a class=\"btn\">" + label + "</a></div>");

      var nice_file_field = file_field.next(".nice_file_field");
      console.log("nice_file_field", nice_file_field)
      nice_file_field.find("a").click( function(){ file_field.click() } );
      file_field.change( function(){
        nice_file_field.find("input").val(file_field.val());
      });
    });
  };
})( jQuery );

jQuery("#csvfile").niceFileField();
</script>