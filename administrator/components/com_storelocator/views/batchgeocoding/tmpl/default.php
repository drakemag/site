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
<script>
	function beginCoding()
	{
		$('geocode').disabled = true;
		$('spinner').style.display = 'inline';
	}
</script>
<form action="<?php echo JRoute::_('index.php?option=com_storelocator&view=batchgeocoding'); ?>" method="post" name="adminForm" id="adminForm">
  <?php if(!empty($this->sidebar)): ?>
  <div id="j-sidebar-container" class="span2"> <?php echo $this->sidebar; ?> </div>
  <div id="j-main-container" class="span10">
  <?php else : ?>
  <div id="j-main-container">
    <?php endif;?>
    <h3>Geocode multiple locations quickly using the power of the Google Geocoding Web Service</h3>
    <p>Geocoding is the process of converting addresses (like &quot;1600  Amphitheater Parkway, Mountain View, CA&quot;) <br />
      into geographic  coordinates (like latitude 37.423021 and longitude -122.083739),  which you can use to place markers or position the map.</p>
    <p>Locations without coordinates: <strong><?php echo $this->nonCodedCount?></strong><br />
        <input name="geocode" type="button" id="geocode" onclick="beginCoding(); Joomla.submitbutton('batchgeocoding.geocode');" value="Process Locations" class="btn btn-warning" />
      <img src="components/com_storelocator/assets/images/spinner.gif" style="display:none;margin-left:15px;" align="absmiddle" id="spinner" /> </strong></p>
    <p class="text-error"><em>Note: Google imposes a Daily limit of 2,500 Geocode Requests per IP Address...</em></p>
    <div id="copyright-block" align="center" style="margin-top:10px;"> <a title="Store Locator Joomla Component" href="http://www.sysgenmedia.com">Store Locator</a> by <a title="Long Island Website Design" href="http://www.sysgenmedia.com">Sysgen Media LLC</a> </div>
    <input type="hidden" name="task" value="" />
    <input type="hidden" name="boxchecked" value="0" />
    <?php echo JHtml::_('form.token'); ?> </div>
</form>
