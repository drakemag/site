<?php
/**
 * @version		$Id: default_answers.php 01 2011-01-11 11:37:09Z maverick $
 * @package		CoreJoomla.Polls
 * @subpackage	Components.site
 * @copyright	Copyright (C) 2009 - 2014 corejoomla.com, Inc. All rights reserved.
 * @author		Maverick
 * @link		http://www.corejoomla.com/
 * @license		License GNU General Public License version 2 or later
 */

defined('_JEXEC') or die;

if(in_array($this->item->type, array('radio', 'checkbox')))
{ ////////////////////////////////////// CHOICE POLL ////////////////////////////////////////////
	?>
	<ul class="list-group no-margin-left">
		<?php 
		foreach ($this->item->answers as $answer)
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
			<li class="list-group-item">
				<label class="<?php echo $this->item->type;?>">
					<input name="answer" type="<?php echo $this->item->type;?>" value="<?php echo $answer->id?>"/> <?php echo $title;?>
				</label>
				
				<?php 
				if(!empty($images))
				{
					echo '<ul class="thumbnails">';
					foreach ($images as $image)
					{
						echo '<li class="span4"><div class="thumbnail">'.JHtml::image($image, $answer->title, array('style'=>'max-height: 96px;')).'</div></li>';
					}
					echo '</ul>';
				}
				?>
			</li>
			<?php 
		}
		?>
	</ul>
	<?php 
}
else
{ ////////////////////////////////////// GRID POLL ///////////////////////////////////////////////
	?>
	<table class="table table-hover table-striped table-bordered">
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
						
						if(!empty($images))
						{
							echo '<ul class="thumbnails">';
							foreach ($images as $image)
							{
								echo '<li class="span4"><div class="thumbnail">'.JHtml::image($image, $answer->title, array('style'=>'max-height: 96px;')).'</div></li>';
							}
							echo '</ul>';
						}
						?>
					</td>
					<?php 
					foreach ($this->item->columns as $column)
					{
						?>
						<td style="text-align: center;">
							<input type="radio" name="answer<?php echo $answer->id?>" value="<?php echo $answer->id.'_'.$column->id?>">
						</td>
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

if($this->item->custom_answer == 1 || $this->item->custom_answer == 2)
{
	?>
	<input name="custom_answer" type="text" class="input-block-level" placeholder="<?php echo JText::_('COM_COMMUNITYPOLLS_CUSTOM_ANSWER_TEXT');?>">
	<?php 
}

if($this->item->eligible == 1 && (! JFactory::getUser()->authorise('core.vcaptcha', 'com_communitypolls.poll.'.$this->item->id) ))
{
	?>
	<form action="<?php echo JRoute::_('index.php');?>" method="post" class="margin-top-10" id="captcha_form">
		<div id="cjpolls_captcha"></div>
		<?php 
		JHtml::_('behavior.framework');
		JPluginHelper::importPlugin('captcha');
		$dispatcher = APP_VERSION < 3 ? JDispatcher::getInstance() : JEventDispatcher::getInstance();
		$dispatcher->trigger('onInit','cjpolls_captcha');
		?>
	</form>
	<?php
}
?>
<div class="alert alert-error" style="display: none;"><i class="fa fa-warning"></i> <span id="cp-error-message"></span></div>

<div id="error_no_selection" style="display: none;"><?php echo JText::_('COM_COMMUNITYPOLLS_ERROR_NO_SELECTION');?></div>
<div id="error_select_one_answer" style="display: none;"><?php echo JText::_('COM_COMMUNITYPOLLS_ERROR_SELECT_ONE_ANSWER');?></div>
<div id="url_vote" style="display: none;"><?php echo JRoute::_('index.php?option=com_communitypolls&task=poll.vote');?></div>
<div id="poll_id" style="display: none;"><?php echo $this->item->id;?></div>
<div id="poll_type" style="display: none;"><?php echo $this->escape($this->item->type);?></div>