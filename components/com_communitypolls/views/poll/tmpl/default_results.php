<?php
/**
 * @version		$Id: default_results.php 01 2011-01-11 11:37:09Z maverick $
 * @package		CoreJoomla.Polls
 * @subpackage	Components.site
 * @copyright	Copyright (C) 2009 - 2014 corejoomla.com, Inc. All rights reserved.
 * @author		Maverick
 * @link		http://www.corejoomla.com/
 * @license		License GNU General Public License version 2 or later
 */

defined('_JEXEC') or die;

$palette = ChartsHelper::get_rgb_colors($this->item->pallete);

if(in_array($this->item->chart_type, array('sbar', 'gpie')))
{
	$document = JFactory::getDocument();
	$document->addScript('https://www.google.com/jsapi');
	$document->addScriptDeclaration('google.load("visualization", "1", {packages:["corechart"]});');
}

if(in_array($this->item->type, array('radio', 'checkbox')))
{ ////////////////////////////////////// CHOICE POLL ////////////////////////////////////////////
	if(in_array($this->item->chart_type, array('pie', 'gpie', 'sbar')))
	{
		if($this->item->chart_type == 'pie')
		{
			?>
			<img alt="<?php echo JText::_('COM_COMMUNITYPOLLS_LOADING_CHARTS');?>"
				src="<?php echo ChartsHelper::get_poll_pie_chart($this->item, $this->params->get('chart_width', 650), $this->params->get('pie_chart_height', 350));?>" />
			<?php 
		}
		elseif($this->item->chart_type == 'gpie')
		{
			?>
			<div class="gpie-chart center text-center" style="width: 100%;"></div>
			<?php 
		}
		?>
		<div class="panel panel-default">
			<div class="panel-body">
			<?php 
			foreach ($this->item->answers as $i=>$answer)
			{
				$title = $this->escape($answer->title);
				$images = array();
				
				foreach ($answer->resources as $resource)
				{
					if (strcmp($resource->type, 'url') == 0)
					{
						$title = JHtml::link($resource->value, $title, array('target'=>'_blank'));
					}
					elseif (strcmp($resource->type, 'image') == 0)
					{
						$images[] = $resource->src;
					}
				}
				?>
				<div class="answer-<?php echo $answer->id;?>">
					<label>
						<i class="icon-asterisk"></i> <?php echo $title.' (<span class="votecount">'.$answer->votes.'</span> '.
							strtolower(JText::plural('COM_COMMUNITYPOLLS_VOTES', $answer->votes)).' / <span class="votepct">'.$answer->pct.'%</span>)';?>
					</label>
					<div class="progress progress-striped">
						<div class="bar progress-bar" role="progressbar" aria-valuenow="<?php echo $answer->pct?>" aria-valuemin="0" aria-valuemax="100" 
							style="width: <?php echo $answer->pct?>%; background-color: <?php echo $palette[$i%count($palette)]?>">
							<span class="sr-only"><?php echo JText::sprintf('COM_COMMUNITYPOLLS_SR_ONLY_PCT_COMPLETE', $answer->pct);?></span>
						</div>
					</div>
				</div>
				<?php 
				foreach ($images as $image)
				{
					echo '<div class="thumbnail">'.JHtml::image($image, $answer->title, array('style'=>'max-height: 96px;')).'</div>';
				}
			}
			?>
			</div>
		</div>
		<?php 
	}
	else 
	{ // Image bar chart - should display only bar chart.
		echo '<img src="'.ChartsHelper::get_poll_bar_chart($this->item, $this->params->get('chart_width', 650)).'" alt="'.JText::_('COM_COMMUNITYPOLLS_LOADING_CHARTS').'"/>';
	}
}
else
{ ////////////////////////////////////// GRID POLL ///////////////////////////////////////////////
	?>
	<table class="table table-hover table-striped table-bordered grid-chart">
		<thead>
			<tr>
				<th></th>
				<?php 
				foreach ($this->item->columns as $column)
				{
					$title = $this->escape($column->title);
					
					foreach ($column->resources as $resource)
					{
						if (strcmp($resource->type, 'url') == 0)
						{
							$title = JHtml::link($resource->value, $title);
							break;
						}
					}
					?>
					<th class="text-center center"><?php echo $title;?></th>
					<?php 
				}
				?>
			</tr>		
		</thead>
		<tbody>
			<?php 
			foreach ($this->item->answers as $answer)
			{
				$title = $this->escape($answer->title);
				$images = array();
				
				foreach ($answer->resources as $resource)
				{
					if (strcmp($resource->type, 'url') == 0)
					{
						$title = JHtml::link($resource->value, $title);
					}
					elseif (strcmp($resource->type, 'image') == 0)
					{
						$images[] = $resource->src;
					}
				}
				?>
				<tr>
					<td>
						<?php 
						echo $title;
						
						foreach ($images as $image)
						{
							echo '<div class="thumbnail">'.JHtml::image($image, $answer->title, array('style'=>'max-height: 96px;')).'</div>';
						}
						?>
					</td>
					<?php 
					foreach ($this->item->columns as $column)
					{
						$found = false;
						foreach ($this->item->gridvotes as $vote)
						{
							if($vote->option_id == $answer->id && $vote->column_id == $column->id)
							{
								$found = $vote;
								break;
							}
						}
						?>
						<td style="text-align: center;"><?php echo $found ? $found->votes : 0;?></td>
						<?php 
					}
					?>
				</tr>
				<?php 
			}
			?>
		</tbody>
	</table>
	<?php 
}
?>
<div style="display: none;">
	<span id="poll_answers"><?php echo json_encode($this->item->answers);?></span>
	<span id="color_pallete"><?php echo json_encode($palette);?></span>
	<span id="chart_height"><?php echo $this->params->get('pie_chart_height');?></span>
</div>
