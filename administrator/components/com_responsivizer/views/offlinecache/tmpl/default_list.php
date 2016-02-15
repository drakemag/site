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
	<table class="headerlist">
		<tr>
			<td class="left">
				<div class="input-prepend">
				  <span class="add-on"><span class="icon-filter"></span> <?php echo JText::_( 'COM_RESPONSIVIZER_FILTER' ); ?>:</span>
				  <input type="text" name="search" id="search" size="60" value="<?php echo $this->searchword;?>" class="text_area"/>
				</div>

				<button class="btn btn-primary btn-mini" onclick="this.form.submit();"><?php echo JText::_( 'COM_RESPONSIVIZER_GO' ); ?></button>
				<button class="btn btn-primary btn-mini" onclick="document.getElementById('search').value='';this.form.submit();"><?php echo JText::_( 'COM_RESPONSIVIZER_RESET' ); ?></button>
				<?php if(!$this->componentParams->get('offlinecache', false)):?>
					<span class="label label-important"><?php echo JText::_('COM_RESPONSIVIZER_DISABLED_OFFLINECACHE');?></span>
				<?php else:?>
					<span class="label label-success"><?php echo JText::_('COM_RESPONSIVIZER_ENABLED_OFFLINECACHE');?></span>
				<?php endif;?>
			</td>
			<td class="right">
				<?php
				echo $this->filters['state'];
				echo $this->pagination->getLimitBox();
				?>
			</td>
		</tr>
	</table>

	<table class="adminlist table table-striped table-hover">
	<thead>
		<tr>
			<th width="1%">
				<?php echo JText::_( 'Num' ); ?>
			</th>
			<th width="1%">
				<input type="checkbox" name="toggle" value=""  onclick="Joomla.checkAll(this)" />
			</th>
			<th width="30%" nowrap="nowrap" class="title">
				<?php echo JHTML::_('grid.sort', 'COM_RESPONSIVIZER_PAGETITLE', 'm.title', @$this->orders['order_Dir'], @$this->orders['order'], 'offlinecache.display' ); ?>
			</th>
			<th width="40%" nowrap="nowrap" class="title">
				<?php echo JHTML::_('grid.sort', 'COM_RESPONSIVIZER_DESCRIPTION', 's.description', @$this->orders['order_Dir'], @$this->orders['order'], 'offlinecache.display' ); ?>
			</th>
			<th width="5%" nowrap="nowrap">
				<?php echo JHTML::_('grid.sort', 'JPUBLISHED', 's.published', @$this->orders['order_Dir'], @$this->orders['order'], 'offlinecache.display' ); ?>
			</th>
			<th width="20%" nowrap="nowrap" class="title">
				<?php echo JText::_('COM_RESPONSIVIZER_NUM_RESOURCES'); ?>
			</th>
			<th width="5%" nowrap="nowrap">
				<?php echo JHTML::_('grid.sort', 'ID', 's.id', @$this->orders['order_Dir'], @$this->orders['order'], 'offlinecache.display' ); ?>
			</th>
			<th width="5%" nowrap="nowrap">
				<?php echo JHTML::_('grid.sort', 'COM_RESPONSIVIZER_PAGEID', 's.itemid', @$this->orders['order_Dir'], @$this->orders['order'], 'offlinecache.display' ); ?>
			</th>
		</tr>
	</thead>
	<?php
	$k = 0;
	for ($i=0, $n=count( $this->items ); $i < $n; $i++) {
		$row = $this->items[$i];
		$link =  'index.php?option=com_responsivizer&task=offlinecache.editEntity&cid[]='. $row->id ;

		if(!$row->checked_out || $row->checked_out == $this->user->id) {
			$taskPublishing	= !$row->published ? 'offlinecache.publish' : 'offlinecache.unpublish';
			$altPublishing 	= !$row->published ? JText::_( 'Publish' ) : JText::_( 'Unpublish' );
			$published = '<a href="javascript:void(0);" onclick="return listItemTask(\'cb' . $i . '\',\'' . $taskPublishing . '\')">';
			$published .= $row->published ? '<img alt="' . $altPublishing . '" src="' . JURI::base(true) . '/components/com_responsivizer/images/icon-16-tick.png" width="16" height="16" border="0" alt="unpublish" />' : JHtml::image('admin/publish_x.png', 'publish', '', true);
			$published .= '</a>';
		} else {
			$altPublishing 	= $row->published ? JText::_( 'Published' ) : JText::_( 'Unpublished' );
			$published = $row->published ? '<img alt="' . $altPublishing . '" src="' . JURI::base(true) . '/components/com_responsivizer/images/icon-16-tick.png" width="16" height="16" border="0" alt="unpublish" />' : JHtml::image('admin/publish_x.png', 'publish', '', true);
		}
		
		$checked = $row->checked_out && $row->checked_out != $this->user->id ? JHtml::_('jgrid.checkedout', $i, '', $row->checked_out_time, 'offlinecache.', false) : JHTML::_('grid.checkedout', $row, $i );
		?>
		<tr>
			<td align="center">
				<?php echo $this->pagination->getRowOffset($i); ?>
			</td>
			<td align="center">
				<?php echo $checked; ?>
			</td>
			<td>
				<?php
				if ( ($row->checked_out && ( $row->checked_out != $this->user->get ('id')))) {
					echo $row->pagetitle;
				} else {
					?>
					<a href="<?php echo $link; ?>" title="<?php echo JText::_( 'COM_RESPONSIVIZER_EDIT_RECORD' ); ?>">
						<?php echo $row->pagetitle; ?></a>
					<?php
				}
				?>
			</td>
			<td align="center">
				<?php echo $row->description; ?>
			</td>
			
			<td align="center">
				<?php echo $published;?>
			</td>
			<td align="center">
				<?php echo count(json_decode($row->resources)); ?>
			</td>
			<td align="center">
				<?php echo $row->id; ?>
			</td>
			<td align="center">
				<?php echo $row->itemid; ?>
			</td>
		</tr>
		<?php
	}
	?>
	<tfoot>
		<td colspan="10">
			<?php echo $this->pagination->getListFooter(); ?>
		</td>
	</tfoot>
	</table>

	<input type="hidden" name="section" value="view" />
	<input type="hidden" name="option" value="<?php echo $this->option;?>" />
	<input type="hidden" name="task" value="offlinecache.display" />
	<input type="hidden" name="boxchecked" value="0" />
	<input type="hidden" name="filter_order" value="<?php echo @$this->orders['order'];?>" />
	<input type="hidden" name="filter_order_Dir" value="<?php echo @$this->orders['order_Dir'];?>" />
</form>