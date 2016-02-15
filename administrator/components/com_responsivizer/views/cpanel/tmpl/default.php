<?php 
/** 
 * @package RESPONSIVIZER::CPANEL::administrator::components::com_responsivizer
 * @subpackage views
 * @subpackage cpanel
 * @subpackage tmpl
 * @author Joomla! Extensions Store
 * @copyright (C) 2014 - Joomla! Extensions Store
 * @license GNU/GPLv2 http://www.gnu.org/licenses/gpl-2.0.html  
 */
defined ( '_JEXEC' ) or die ( 'Restricted access' ); 
?>
<!-- CPANEL ICONS -->
<div id="cpanel">
	<?php echo $this->icons; ?>
	
	<div id="updatestatus">
		<?php 
		if(is_object($this->updatesData)) {
			if(version_compare($this->updatesData->latest, $this->currentVersion, '>')) { ?>
				<a href="http://storejextensions.org/extensions/responsivizer.html" target="_blank" alt="storejoomla link">
					<label data-content="<?php echo JText::sprintf('COM_RESPONSIVIZER_GET_LATEST', $this->currentVersion, $this->updatesData->latest, $this->updatesData->relevance);?>" class="label label-important hasPopover">
						<span class="icon-warning"></span>
						<?php echo JText::sprintf('COM_RESPONSIVIZER_OUTDATED', $this->updatesData->latest);?>
					</label>
				</a>
			<?php } else { ?>
				<label data-content="<?php echo JText::sprintf('COM_RESPONSIVIZER_YOUHAVE_LATEST', $this->currentVersion);?>" class="label label-success hasPopover">
					<span class="icon-checkmark"></span>
					<?php echo JText::sprintf('COM_RESPONSIVIZER_UPTODATE', $this->updatesData->latest);?>
				</label>	
			<?php }
		}
		?>
	</div>
</div>
<!-- RIGHT ACCORDION -->

<div class="accordion" id="rvzr_accordion_cpanel">
	<div class="accordion-group">
    	<div class="accordion-heading">
      		<div class="accordion-toggle" data-toggle="collapse" data-parent="#rvzr_accordion_cpanel" href="#responsivizer_stats">
	      		<h4 class="accordion-title">
	      			<span class="icon-chart"></span>
	      			<?php echo JText::_('COM_RESPONSIVIZER_CPANEL_STATS');?>
      			</h4>
      		</div>
	    </div>
	    <div id="responsivizer_stats" class="accordion-body collapse">
			<div class="accordion-inner">
				<div class="single_stat_container">
					<div class="statcircle">
						<span class="icon-grid-view-2 icon-large"></span>
					</div>
					<ul class="subdescription_stats">
						<li class="es-stat-no"><?php echo $this->infoData['totalModules']; ?></li>
						<li class="es-stat-title"><?php echo JText::_('COM_RESPONSIVIZER_TOTAL_MODULES');?></li>
					</ul>
				</div>
				
				<div class="single_stat_container">
					<div class="statcircle">
						<span class="icon-grid-view icon-large"></span>
					</div>
					<ul class="subdescription_stats">
						<li class="es-stat-no"><?php echo $this->infoData['publishedModules']; ?></li>
						<li class="es-stat-title"><?php echo JText::_('COM_RESPONSIVIZER_PUBLISHED_MODULES');?></li>
					</ul>
				</div>
				
				<div class="single_stat_container">
					<div class="statcircle">
						<span class="icon-grid-view icon-large"></span>
					</div>
					<ul class="subdescription_stats">
						<li class="es-stat-no"><?php echo $this->infoData['unpublishedModules']; ?></li>
						<li class="es-stat-title"><?php echo JText::_('COM_RESPONSIVIZER_UNPUBLISHED_MODULES');?></li>
					</ul>
				</div>
				
				<div class="single_stat_container">
					<div class="statcircle">
						<span class="icon-mobile icon-large"></span>
					</div>
					<ul class="subdescription_stats">
						<li class="es-stat-no"><?php echo $this->infoData['totalMobileModules']; ?></li>
						<li class="es-stat-title"><?php echo JText::_('COM_RESPONSIVIZER_TOTAL_MOBILE_MODULES');?></li>
					</ul>
				</div>
				
				<div class="single_stat_container">
					<div class="statcircle">
						<span class="icon-mobile icon-large"></span>
					</div>
					<ul class="subdescription_stats">
						<li class="es-stat-no"><?php echo $this->infoData['publishedMobileModules']; ?></li>
						<li class="es-stat-title"><?php echo JText::_('COM_RESPONSIVIZER_PUBLISHED_MOBILE_MODULES');?></li>
					</ul>
				</div>
				
				<div class="single_stat_container">
					<div class="statcircle">
						<span class="icon-mobile icon-large"></span>
					</div>
					<ul class="subdescription_stats">
						<li class="es-stat-no"><?php echo $this->infoData['unpublishedMobileModules']; ?></li>
						<li class="es-stat-title"><?php echo JText::_('COM_RESPONSIVIZER_UNPUBLISHED_MOBILE_MODULES');?></li>
					</ul>
				</div>
				
				<div class="single_stat_container">
					<div class="statcircle">
						<span class="icon-database icon-large"></span>
					</div>
					<ul class="subdescription_stats">
						<li class="es-stat-no"><?php echo $this->infoData['totalCachedPages']; ?></li>
						<li class="es-stat-title"><?php echo JText::_('COM_RESPONSIVIZER_TOTAL_CACHED_PAGES');?></li>
					</ul>
				</div>
				
				<div class="single_stat_container">
					<div class="statcircle">
						<span class="icon-database icon-large"></span>
					</div>
					<ul class="subdescription_stats">
						<li class="es-stat-no"><?php echo $this->infoData['publishedCachedPages']; ?></li>
						<li class="es-stat-title"><?php echo JText::_('COM_RESPONSIVIZER_PUBLISHED_CACHED_PAGES');?></li>
					</ul>
				</div>
				
				<div id="chart_container">
					<canvas id="chart_canvas"></canvas>
				</div>
      		</div>
    	</div>
	</div>
	<div class="accordion-group">
	    <div class="accordion-heading">
			<div class="accordion-toggle" data-toggle="collapse" data-parent="#rvzr_accordion_cpanel" href="#responsivizer_status">
				<h4 class="accordion-title">
					<span class="icon-help"></span>
					<?php echo JText::_('COM_RESPONSIVIZER_ABOUT');?>
				</h4>
	      	</div>
    	</div>
	    <div id="responsivizer_status" class="accordion-body collapse">
	 		<div class="accordion-inner">
				<div class="single_container">
			 		<label class="label label-warning"><?php echo JText::_('COM_RESPONSIVIZER_VERSION_COMPONENT_TEXT') . $this->currentVersion;?></label>
		 		</div>
		 		
		 		<div class="single_container">
			 		<label class="label label-info"><?php echo JText::_('COM_RESPONSIVIZER_AUTHOR_COMPONENT');?></label>
		 		</div>
		 		
		 		<div class="single_container">
			 		<label class="label label-info"><?php echo JText::_('COM_RESPONSIVIZER_SUPPORTLINK');?></label>
		 		</div>
		 		
		 		<div class="single_container">
			 		<label class="label label-info"><?php echo JText::_('COM_RESPONSIVIZER_DEMOLINK');?></label>
		 		</div>
			</div>
	    </div>
 	</div>
</div>
<form name="adminForm" id="adminForm" action="index.php">
	<input type="hidden" name="option" value="<?php echo $this->option;?>"/>
	<input type="hidden" name="task" value=""/>
</form>