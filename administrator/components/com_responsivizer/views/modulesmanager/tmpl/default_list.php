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
	<table class="headerlist">
		<tr>
			<td class="left">
				<div class="input-prepend">
				  <span class="add-on"><span class="icon-filter"></span> <?php echo JText::_( 'COM_RESPONSIVIZER_FILTER' ); ?>:</span>
				  <input type="text" name="search" id="search" size="60" value="<?php echo $this->searchword;?>" class="text_area"/>
				</div>

				<button class="btn btn-primary btn-mini" onclick="this.form.submit();"><?php echo JText::_( 'COM_RESPONSIVIZER_GO' ); ?></button>
				<button class="btn btn-primary btn-mini" onclick="document.getElementById('search').value='';this.form.submit();"><?php echo JText::_( 'COM_RESPONSIVIZER_RESET' ); ?></button>
			</td>
			<td class="right">
				<?php
				echo $this->filters['exclude'];
				echo $this->filters['mobileavailable'];
				echo $this->filters['state'];
				echo $this->filters['type'];
				echo $this->filters['position'];
				echo $this->pagination->getLimitBox();
				?>
			</td>
		</tr>
	</table>

	<table class="adminlist table table-striped table-hover">
	<thead>
		<tr>
			<th>
				<?php echo JText::_( 'Num' ); ?>
			</th>
			<th nowrap="nowrap" class="title alignleft">
				<?php echo JHTML::_('grid.sort', 'COM_RESPONSIVIZER_TITLE', 's.title', @$this->orders['order_Dir'], @$this->orders['order'], 'modulesmanager.display' ); ?>
			</th>
			<th nowrap="nowrap" class="title">
				<?php echo JHTML::_('grid.sort', 'COM_RESPONSIVIZER_POSITION', 's.position', @$this->orders['order_Dir'], @$this->orders['order'], 'modulesmanager.display' ); ?>
			</th>
			<th nowrap="nowrap">
				<?php echo JHTML::_('grid.sort', 'JPUBLISHED', 'computed_published', @$this->orders['order_Dir'], @$this->orders['order'], 'modulesmanager.display' ); ?>
			</th>
			<th nowrap="nowrap" class="title">
				<?php echo JHTML::_('grid.sort', 'COM_RESPONSIVIZER_MOBILE_AVAILABLE', 'mobile_available', @$this->orders['order_Dir'], @$this->orders['order'], 'modulesmanager.display' ); ?>
			</th>
			<th nowrap="nowrap">
				<?php echo JHTML::_('grid.sort', 'COM_RESPONSIVIZER_MOBILE_EXCLUDE', 'r.mobile_exclude', @$this->orders['order_Dir'], @$this->orders['order'], 'modulesmanager.display' ); ?>
			</th>
			<th nowrap="nowrap">
				<?php echo JHTML::_('grid.sort', 'COM_RESPONSIVIZER_SHOW_TITLE', 'r.show_title', @$this->orders['order_Dir'], @$this->orders['order'], 'modulesmanager.display' ); ?>
			</th>
			<th nowrap="nowrap">
				<?php echo JHTML::_('grid.sort', 'COM_RESPONSIVIZER_SHOW_BG', 'r.show_bg', @$this->orders['order_Dir'], @$this->orders['order'], 'modulesmanager.display' ); ?>
			</th>
			<th nowrap="nowrap">
				<?php echo JHTML::_('grid.sort', 'COM_RESPONSIVIZER_MODULE_NAME', 'e.name', @$this->orders['order_Dir'], @$this->orders['order'], 'modulesmanager.display' ); ?>
			</th>
			<th width="1%" nowrap="nowrap">
				<?php echo JHTML::_('grid.sort', 'ID', 's.id', @$this->orders['order_Dir'], @$this->orders['order'], 'modulesmanager.display' ); ?>
			</th>
		</tr>
	</thead>
	<?php
	$k = 0;
	$allowedEditStateForModules = $this->user->authorise('core.edit.state', 'com_modules');
	for ($i=0, $n=count( $this->items ); $i < $n; $i++) {
		$row = $this->items[$i];
		
		// Published module column
		switch ($row->computed_published) {
			case 1:
				$published = '<img src="' . JURI::base(true) . '/components/com_responsivizer/images/icon-16-tick.png" width="16" height="16" title="' . JText::_('COM_RESPONSIVIZER_PUBLISHED_MODULE') . '" border="0" alt="unpublish" />';
				if($allowedEditStateForModules && !$row->checked_out) {
					$published = "<a href='index.php?option=com_responsivizer&task=modulesmanager.unpublish&cid[]=$row->id&time=" . time() . "'>" . $published . "</a>";
				}
				break;
				
			case 0:
			case -2:
				$published = JHtml::image('admin/publish_x.png', 'publish', 'class="hasTooltip" title="' . JText::_('COM_RESPONSIVIZER_UNPUBLISHED_MODULE') . '"', true);
				if($allowedEditStateForModules && !$row->checked_out) {
					$published = "<a href='index.php?option=com_responsivizer&task=modulesmanager.publish&cid[]=$row->id&time=" . time() . "'>" . $published . "</a>";
				}
				break;
				
			case -1:
				$published = JHtml::image('admin/publish_y.png', 'publish', 'class="hasTooltip" title="' . JText::_('COM_RESPONSIVIZER_DISABLED_EXTENSION') . '"', true);
				if($allowedEditStateForModules && !$row->checked_out) {
					$published = "<a href='index.php?option=com_responsivizer&task=modulesmanager.unpublish&cid[]=$row->id&time=" . time() . "'>" . $published . "</a>";
				}
				break;
		}
		
		// Mobile available column
		if($row->mobile_available && !$row->mobile_exclude) {
			$mobileAvailable = '<img src="' . JURI::base(true) . '/components/com_responsivizer/images/icon-16-tick.png" class="hasTooltip avl' . $row->id . ' width="16" height="16" border="0" title="' . JText::_('COM_RESPONSIVIZER_MOBILE_AVAILABLE_MODULE') . '" alt="available" />';
		} else {
			$hasMobile = $row->has_mobile ? ' hasmobile' : '';
			$hasMobileLabel = $row->has_mobile ? 'COM_RESPONSIVIZER_MAKE_EXISTS_MOBILE_DESC' : 'COM_RESPONSIVIZER_MAKE_MOBILE_DESC';
			$editLink = 'index.php?option=com_responsivizer&task=modulesmanager.editEntity&id=' . $row->id;
			$mobileAvailable = JHtml::image('admin/publish_x.png', 'noavailable', 'class="hasTooltip avl' . $row->id . '" title="' . JText::_('COM_RESPONSIVIZER_MOBILE_NOAVAILABLE_MODULE') . '"', true);
			if(!array_key_exists($row->position, $this->dbPositions)) {
				$mobileAvailable .= '<div><label class="label label-info makemobile' . $hasMobile .'"><a class="hasTooltip" title="'. JText::_($hasMobileLabel) .'" href="' . $editLink . '">' . JText::_('COM_RESPONSIVIZER_MAKE_MOBILE') . '</a></label></div>';
			}
		}
		
		// Mobile exclude columns
		$switcherEnabledState = $row->mobile_exclude ? 'checked="checked"' : null;
		$switcherDisabledState = !$row->mobile_exclude ? 'checked="checked"' : null;
		
		// Show titles column
		$row->show_title = is_null($row->show_title) ? 1 : $row->show_title;
		$showtitleEnabledState = $row->show_title ? 'checked="checked"' : null;
		$showtitleDisabledState = !$row->show_title ? 'checked="checked"' : null;
		
		// Show bg column
		$row->show_bg = is_null($row->show_bg) ? 1 : $row->show_bg;
		$showbgEnabledState = $row->show_bg ? 'checked="checked"' : null;
		$showbgDisabledState = !$row->show_bg ? 'checked="checked"' : null;
		?>
		<tr>
			<td>
				<?php echo $this->pagination->getRowOffset($i); ?>
			</td>
			<td class="alignleft">
				<?php
				if ( ($row->checked_out && ( $row->checked_out != $this->user->get ('id'))) || !$this->user->authorise('core.edit', 'com_modules') ) { ?>
					<label class="hasTooltip" title="<?php echo JText::_( 'COM_RESPONSIVIZER_CHECKEDOUT_RECORD' ); ?>">
						<?php echo $row->title; ?>
					</label>
					<?php 
				} else {
					$link = 'index.php?option=com_modules&amp;task=module.edit&amp;id=' . $row->id;
					?>
					<a target="_blank" href="<?php echo $link; ?>" class="hasTooltip fancybox_iframe" title="<?php echo JText::_( 'COM_RESPONSIVIZER_GOTO_JMODULE_EDIT' ); ?>">
						<?php echo $row->title; ?></a>
					<?php
				}
				?>
			</td>
			<td>
				<?php echo $row->position; ?>
			</td>
			<td>
				<?php echo $published;?>
			</td>
			<td>
				<?php echo $mobileAvailable;?>
			</td>
			<td>
				<div class="controls">
					<fieldset id="jform_mobile_exclude_state" class="radio btn-group inverse">
						<input type="radio" value="0" id="ajaxswitcher<?php echo $row->id;?>0" name="ajaxswitcher<?php echo $row->id;?>" data-action="0" data-role="ajaxswitcher" data-id="<?php echo $row->id;?>" <?php echo $switcherDisabledState;?>/>
						<label for="ajaxswitcher<?php echo $row->id;?>0"class="radio btn btn-default"><?php echo JText::_('JNO');?></label>
						<input type="radio" value="1" id="ajaxswitcher<?php echo $row->id;?>1" name="ajaxswitcher<?php echo $row->id;?>" data-action="1" data-role="ajaxswitcher" data-id="<?php echo $row->id;?>" <?php echo $switcherEnabledState;?>/>
						<label for="ajaxswitcher<?php echo $row->id;?>1" class="radio btn btn-default"><?php echo JText::_('JYES');?></label>
					</fieldset>
				</div>
			</td>
			<td>
				<div class="controls">
					<fieldset id="jform_show_title_state" class="radio btn-group inverse">
						<input type="radio" value="0" id="showtitle<?php echo $row->id;?>0" name="showtitle<?php echo $row->id;?>" data-field="show_title" data-value="0" data-role="showtitle" data-id="<?php echo $row->id;?>" <?php echo $showtitleDisabledState;?>/>
						<label for="showtitle<?php echo $row->id;?>0"class="radio btn btn-default"><?php echo JText::_('JNO');?></label>
						<input type="radio" value="1" id="showtitle<?php echo $row->id;?>1" name="showtitle<?php echo $row->id;?>" data-field="show_title" data-value="1" data-role="showtitle" data-id="<?php echo $row->id;?>" <?php echo $showtitleEnabledState;?>/>
						<label for="showtitle<?php echo $row->id;?>1" class="radio btn btn-default"><?php echo JText::_('JYES');?></label>
					</fieldset>
				</div>
			</td>
			<td>
				<div class="controls">
					<fieldset id="jform_show_title_state" class="radio btn-group inverse">
						<input type="radio" value="0" id="showbg<?php echo $row->id;?>0" name="showbg<?php echo $row->id;?>" data-field="show_bg" data-value="0" data-role="showbg" data-id="<?php echo $row->id;?>" <?php echo $showbgDisabledState;?>/>
						<label for="showbg<?php echo $row->id;?>0"class="radio btn btn-default"><?php echo JText::_('JNO');?></label>
						<input type="radio" value="1" id="showbg<?php echo $row->id;?>1" name="showbg<?php echo $row->id;?>" data-field="show_bg" data-value="1" data-role="showbg" data-id="<?php echo $row->id;?>" <?php echo $showbgEnabledState;?>/>
						<label for="showbg<?php echo $row->id;?>1" class="radio btn btn-default"><?php echo JText::_('JYES');?></label>
					</fieldset>
				</div>
			</td>
			<td>
				<?php echo $row->name; ?>
			</td>
			<td>
				<?php echo $row->id; ?>
			</td>
		</tr>
		<?php
	}
	?>
	<tfoot>
		<td colspan="13">
			<?php echo $this->pagination->getListFooter(); ?>
		</td>
	</tfoot>
	</table>

	<input type="hidden" name="section" value="view" />
	<input type="hidden" name="option" value="<?php echo $this->option;?>" />
	<input type="hidden" name="task" value="modulesmanager.display" />
	<input type="hidden" name="boxchecked" value="0" />
	<input type="hidden" name="filter_order" value="<?php echo @$this->orders['order'];?>" />
	<input type="hidden" name="filter_order_Dir" value="<?php echo @$this->orders['order_Dir'];?>" />
</form>