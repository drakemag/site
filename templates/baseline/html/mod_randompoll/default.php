<?php
/**
 * @version		$Id: default.php 01 2012-04-21 11:37:09Z maverick $
 * @package		CoreJoomla.Polls
 * @subpackage	Modules.site
 * @copyright	Copyright (C) 2009 - 2012 corejoomla.com, Inc. All rights reserved.
 * @author		Maverick
 * @link		http://www.corejoomla.com/
 * @license		License GNU General Public License version 2 or later
 */

//don't allow other scripts to grab and execute our file
defined('_JEXEC') or die('Direct Access to this location is not allowed.');

$user = JFactory::getUser();
$rand = mt_rand();
$palette = ChartsHelper::get_rgb_colors($poll->pallete);
$bbcode = $config->get('default_editor') == 'bbcode';

if($poll->publish_results && in_array($poll->chart_type, array('gpie', 'sbar')))
{
	$document = JFactory::getDocument();
	$document->addScript('https://www.google.com/jsapi');
	$document->addScriptDeclaration('google.load("visualization", "1", {packages:["corechart"]});');
}

$poll->slug			= $poll->alias ? ($poll->id.':'.$poll->alias) : $poll->id;
$poll->catslug		= $poll->category_alias ? ($poll->catid.':'.$poll->category_alias) : $poll->catid;
$poll->parent_slug	= $poll->parent_alias ? ($poll->parent_id . ':' . $poll->parent_alias) : $poll->parent_id;
?>
<script type="text/javascript">
<!--
jQuery(document).ready(function($){

	var factory = new RandomPollsFactory('rp-<?php echo $rand;?>');
	factory.init_form();
	factory.init_charts();
	<?php if($default_view == 'charts' && $poll->eligible):?>
	$('.rp-<?php echo $rand;?>').find('.rp-btn-result').click();
	<?php endif;?>
});
//-->
</script>
<div class="rp-poll-wrapper rp-<?php echo $rand;?>">

	<h3 class="page-title"><?php echo CJFunctions::escape($poll->title);?></h3>
	
	<?php if($show_description == 1 && !empty($poll->description)):?>
	<?php if($desc_length > 0):?>
	<div class="rp-poll-description"><?php echo CJFunctions::substrws(CJFunctions::process_html($poll->description, $bbcode), $desc_length);?></div>
	<?php else:?>
	<div class="rp-poll-description"><?php echo CJFunctions::process_html($poll->description, $bbcode);?></div>
	<?php endif;?>
	<?php endif;?>
	
	<div class="rp-poll-messages">
		<?php
		if ( $poll->eligible == 1 ||  $poll->publish_results == false || ($poll->eligible == 2 && $poll->modify_answers == 1) )
		{
			if($poll->eligible == 1 || $poll->eligible == 2)
			{
				echo '<div class="rp-poll-end-message"></div>';
			}
			
			if($poll->publish_results == false)
			{
				if($user->authorise('core.results.view', 'com_communitypolls.poll.'.$poll->id))
				{
					$publish_date = JHtml::_('date', $poll->results_up, JText::_('DATE_FORMAT_LC4'));
					echo '<div><i class="fa fa-info-circle"></i> '.JText::sprintf('COM_COMMUNITYPOLLS_MESSAGE_POLL_RESULTS_UNAVAILABLE', $publish_date).'</div>';
				}
				else
				{
					echo '<div><i class="fa fa-info-circle"></i> '.JText::_('COM_COMMUNITYPOLLS_MESSAGE_POLL_RESULTS_HIDDEN').'</div>';
				}
			}
		}
		
		if( $poll->eligible == 2 )
		{
			echo '<div><i class="fa fa-info-circle"></i> '.JText::_('COM_COMMUNITYPOLLS_MESSAGE_ALREADY_VOTED').'</div>';
		}
		elseif( $poll->eligible == 3 )
		{
			echo '<div><i class="fa fa-info-circle"></i> '.JText::_('COM_COMMUNITYPOLLS_MESSAGE_NOT_ELIGIBLE_TO_VOTE').'</div>';
		}
		
		if( $poll->private == 1 )
		{
			$pollUri = CommunityPollsHelperRoute::getPollRoute($poll->slug, $poll->catslug, $poll->language);
			$secretUrl = JRoute::_($pollUri.'&secret='.$poll->secret, true, -1);
			
			echo '<p>'.JText::sprintf('COM_COMMUNITYPOLLS_MESSAGE_SECRET_URL', $secretUrl).'</p>';
		}
		?>
	</div>
	
	<!--************************* START: Poll Form *************************-->
	<?php if( $poll->eligible == 1 || $poll->publish_results == false || 
				!$user->authorise('core.results.view', 'com_communitypolls') || ($poll->eligible == 2 && !$user->guest && $poll->modify_answers == 1) ):?>
	<div class="rp-poll-form margin-top-20">
	
		<?php if(strcmp($poll->type, 'grid') == 0):?>
		
		<table class="table table-hover table-striped table-bordered rp-voting-grid">
			<thead>
				<tr>
					<th class="answer-header"></th>
					<?php 
					foreach ($poll->columns as $column)
					{
						$url = null;
						foreach ($column->resources as $resource)
						{
							if(strcmp($resource->type, 'image') == 0)
							{
								$url = $resource->value;
							}
						}
						?>
						<th class="answer-header"><?php echo CJFunctions::escape($column->title)?></th>
						<?php 
					}
					?>
				</tr>
			</thead>
			<tbody>
				<?php foreach($poll->answers as $answer):?>
				<tr>
					<th class="answer-header"><?php echo CJFunctions::escape($answer->title);?></th>
					
					<?php foreach($poll->columns as $column):?>
					<td><input type="radio" name="answer<?php echo $answer->id?>" value="<?php echo $answer->id.'_'.$column->id?>"></td>
					<?php endforeach;?>
				</tr>
				<?php endforeach;?>
			</tbody>
		</table>
		
		<?php else: //if not grid?>
		
		<div class="rp-form-wrapper">
		
			<?php foreach($poll->answers as $i=>$answer):?>
			<div class="rp-answer <?php echo ($i == count($poll->answers)-1 && $poll->custom_answer != '1') ? 'no-border-bottom' : ''?>">
				<?php
				$url = null;
				foreach ($answer->resources as $resource)
				{
					if(strcmp($resource->type, 'url') == 0)
					{
						$url = $resource->value;
						break;
					}
				}
				?>
				
				<label class="<?php echo $poll->type;?>">
					<input name="poll-answer" id="rp-answer-<?php echo $rand.'-'.$answer->id;?>" type="<?php echo $poll->type;?>" value="<?php echo $answer->id?>">
					<?php if(!empty($url)):?>
					<a target="_blank" href="<?php echo CJFunctions::escape($url);?>"><?php echo CJFunctions::escape($answer->title);?></a>
					<?php else:?>
					<?php echo $answer->title;?>ytrdy 
					<?php endif;?>
				</label>
			
				<?php 
				if($allow_images == 1)
				{
					foreach($answer->resources as $resource)
					{
						if(strcmp($resource->type, 'image') == 0)
						{
							?>
							<div><img src="<?php echo P_IMAGES_URI.$resource->value;?>"></div>
							<?php
						}
					}
				}
				?>
			</div>
			<?php endforeach;?>
			
			<?php if($poll->custom_answer == '1' || $poll->custom_answer == '2'):?>
			<div class="rp-custom-answer-wrapper">
				<div class="custom_answer_label"><?php echo JText::_('COM_COMMUNITYPOLLS_CUSTOM_ANSWER_TEXT')?></div>
				<input type="text" name="custom_answer" class="rp-custom-answer input-medium">
			</div>
			<?php endif;?>
			
		</div>
		
		<?php endif; //end if not grid?>
				
		<?php if(!$user->authorise('core.vcaptcha', 'com_communitypolls') && !$user->authorise('core.admin', 'com_communitypolls')):?>
		<form action="<?php echo JRoute::_('index.php');?>" method="post" class="margin-top-10" id="captcha_form">
			<div id="cjrpolls_captcha"></div>
			<?php 
			JHtml::_('behavior.framework');
			JPluginHelper::importPlugin('captcha');
			$dispatcher = JDispatcher::getInstance();
			$dispatcher->trigger('onInit','cjrpolls_captcha');
			?>
		</form>
		<?php endif;?>
		
		<input type="hidden" name="pollId" class="pollId" value="<?php echo $poll->id;?>">
		<input type="hidden" name="pollType" id="pollType" value="<?php echo $poll->type;?>">
	</div>
	<?php endif; // end if eligible?>
	<!--************************* END: Poll Form END ************************-->
	
	<!--************************* START: Poll Charts *************************-->
	<div class="rp-results margin-top-20" style="<?php echo ($poll->eligible == 1 || ($poll->eligible == 2 && !$user->guest && $poll->modify_answers == 1)) ? 'display: none' : '';?>">
		<?php if($poll->publish_results == true):?>
		<div class="rp-results-src<?php echo in_array($poll->chart_type, array('bar', 'sbar')) ? ' chart-align-left' : '';?>">
			<?php
			if(strcmp($poll->type, 'grid') == 0)
			{
				$grid = '<table class="table table-hover table-striped table-bordered rp-voting-grid"><thead><tr><th class="answer-header"></th>';
				foreach ($poll->columns as $column)
				{
					$grid = $grid.'<th class="answer-header">'.CJFunctions::escape($column->title).'</th>';
				}
				
				$grid = $grid.'</tr></thead><tbody>';
				foreach($poll->answers as $answer)
				{
					$grid = $grid.'<tr><th class="answer-header">'.CJFunctions::escape($answer->title).'</th>';
					foreach ($poll->columns as $column)
					{
						$found = false;
						foreach ($poll->gridvotes as $vote)
						{
							if($vote->option_id == $answer->id && $vote->column_id == $column->id)
							{
								$grid = $grid.'<td class="answer-'.$answer->id.'-'.$column->id.'">'.$vote->votes.'</td>';
								$found = true;
								break;
							}
						}
						
						if(!$found)
						{
							$grid = $grid.'<td class="answer-'.$answer->id.'-'.$column->id.'">0</td>';
						}
					}
					
					$grid = $grid.'</tr>';
				}
				
				$grid = $grid.'</table>';
				echo $grid;
			}
			else
			{
				switch ($poll->chart_type)
				{
					case 'bar':
						echo '<img src="'.ChartsHelper::get_poll_bar_chart($poll, $chart_width).'" alt="'.JText::_('COM_COMMUNITYPOLLS_LABEL_LOADING_CHARTS').'"/>';
						break;
						
					case 'pie':
						echo '<img src="'.ChartsHelper::get_poll_pie_chart($poll, $chart_width, $chart_height).'" alt="'.JText::_('COM_COMMUNITYPOLLS_LABEL_LOADING_CHARTS').'"/>';
						break;
						
					case 'gpie';
						echo '<div class="rp-gpie-chart"></div>';
						break;
						
					case 'sbar':
						// nothing to do. see custom one below
						break;
				}
			}
			?>
		</div>
			
		<?php if(strcmp($poll->type, 'grid') != 0 && ( ($poll->chart_type == 'sbar') || (!$hide_pie_table && in_array($poll->chart_type, array('pie', 'gpie'))) ) ):?>
		<div class="rp-sbar-chart">
		
			<?php foreach($poll->answers as $i=>$answer): // repeat through answers and print sbar chart?>
			<div class="rp-sbar-chart-bar-wrapper answer-<?php echo $answer->id;?>">
				<div class="rp-sbar-title-wrapper">
					<?php
					$url = null; 
					foreach ($answer->resources as $resource)
					{
						if(strcmp($resource->type, 'url') == 0)
						{
							$url = $resource->value;
							break;
						}
					}
					?>
					
					<div class="rp-sbar-title">
						<?php if(!empty($url)):?>
						<a target="_blank" href="<?php echo CJFunctions::escape($url);?>"><?php echo CJFunctions::escape($answer->title);?></a>
						<?php else:?>
						<span><?php echo CJFunctions::escape($answer->title);?></span>
						<?php endif;?>
						<small class="muted">(<?php echo JText::plural('COM_COMMUNITYPOLLS_VOTE_PCT', $answer->votes);?>)</small>
					</div>
					
					<?php if($allow_images == 1): ?>
					<?php foreach($answer->resources as $resource):?>
					<?php if(strcmp($resource->type, 'image') == 0):?>
					
					<div><img src="<?php echo P_IMAGES_URI.$resource->value;?>"></div>
					
					<?php endif;?>
					<?php endforeach;?>
					<?php endif;?>
					
				</div>
				
				<div class="rp-sbar-chart-wrapper clearfix">
					<small class="rp-sbar-pct pull-right margin-right-10 muted" <?php echo $answer->pct > 95 ? 'style="color: #fff;"' : '';?>>
						<?php echo $answer->pct;?>%
					</small>
					<div class="rp-sbar-bar bar" style="background-color: <?php echo $palette[$i%count($palette)];?>; width: <?php echo $answer->pct;?>%"></div>
				</div>
			</div>
			
			<?php endforeach; // END: repeat through answers and print sbar chart?>
			
		</div>
		
		<?php endif; // end simple bar chart?>
							
		<?php if($poll->custom_answer == '1' 
				&& !empty($poll->custom_answers) 
				&& ((!$user->guest && $poll->created_by == $user->id) || $user->authorise('core.manage', 'com_communitypolls'))):?>
		
		<a id="btn_custom_answers" href="#" rel="nofollow" onclick="return false;"><?php echo JText::_('COM_COMMUNITYPOLLS_VIEW_CUSTOM_ANSWERS')?></a>
		
		<div class="tab-custom-answers" title="<?php echo JText::_('COM_COMMUNITYPOLLS_LABEL_CUSTOM_ANSWERS');?>" style="display: none;">
			<table class="table table-hover table-striped">
				<thead>
					<tr>
						<th style="width: 100px;"><?php echo JText::_('COM_COMMUNITYPOLLS_LABEL_USERNAME');?></th>
						<th style="width: 100px;"><?php echo JText::_('COM_COMMUNITYPOLLS_LABEL_DATE');?></th>
						<?php if($user->authorise('core.manage', 'com_communitypolls')):?>
						<th style="width: 100px;"><?php echo JText::_('COM_COMMUNITYPOLLS_IP_ADDRESS');?></th>
						<?php endif;?>
						<th><?php echo JText::_('COM_COMMUNITYPOLLS_POLL_ANSWERS');?></th>
					</tr>
				</thead>
				<tbody>
					<?php foreach ($poll->custom_answers as $answer):?>
					<tr>
						<td><?php echo $answer->voter_id > 0 ? $answer->username : JText::_('COM_COMMUNITYPOLLS_GUEST');?></td>
						<td><?php echo CJFunctions::get_formatted_date($answer->voted_on);?></td>
						<?php if($user->authorise('core.manage', 'com_communitypolls')):?>
						<td><?php echo $answer->ip_address;?></td>
						<?php endif;?>
						<td><?php echo $answer->custom_answer;?></td>
					</tr>
					<?php endforeach;?>
				</tbody>
			</table>
		</div>
		
		<?php endif;?>
		
		<?php else: ?>
		<div class="ui-widget ui-widget-content ui-corner-all ui-state-highlight poll-status-closed"><?php echo JText::_('COM_COMMUNITYPOLLS_MESSAGE_POLL_RESULTS_HIDDEN');?></div>
		<?php endif;?>
	</div>
	<!--************************* END: Poll Charts *************************-->
		
	<?php if($poll->eligible == 1 || ($poll->eligible == 2 && !$user->guest && $poll->modify_answers == 1)):?>
	<div class="rp-buttons-wrapper margin-top-10">
		<button class="rp-btn-vote btn btn-primary btn-small"><?php echo JText::_('COM_COMMUNITYPOLLS_SUBMIT_VOTE');?></button>
		<?php if ($poll->publish_results && $poll->votes > 0):?>
		<button class="rp-btn-result btn btn-default btn-small"><?php echo JText::_('COM_COMMUNITYPOLLS_VIEW_RESULT');?></button>
		<button class="rp-btn-form btn btn-default btn-small" style="display: none;"><?php echo JText::_('COM_COMMUNITYPOLLS_VOTE_FORM');?></button>
		<?php endif;?>
	</div>
	<?php endif;?>
	
	<div class="rp-footer margin-top-20">
		<?php if($show_avatar == 1):?>
		<div class="pull-left margin-right-10"><?php echo CJFunctions::get_user_avatar($config->get('avatar_component'), $poll->created_by, $username, 36, $poll->email);?></div>
		<?php endif;?>
		
		<?php if($show_author == 1):?>
		<div class="rp-text">
			<?php echo JText::sprintf('TXT_AUTHOR', 
					CJFunctions::get_user_profile_link(
						$config->get('avatar_component'), 
						$poll->created_by, 
						$poll->created_by > 0 ? CJFunctions::escape($poll->author) : JText::_('COM_COMMUNITYPOLLS_LABEL_GUEST')));?>
		</div>
		<?php endif;?>
		
		<?php if($show_category == 1):?>
		<div class="rp-text">
			<?php echo JText::sprintf('TXT_CATEGORY', JHtml::link(JRoute::_(CommunityPollsHelperRoute::getCategoryRoute($poll->catslug)), CJFunctions::escape($poll->category_title)));?>
		</div>
		<?php endif;?>
		
		<?php if($show_votes == 1):?>
		<div class="rp-text"><?php echo JText::sprintf('COM_COMMUNITYPOLLS_TOTAL_VOTES', $poll->votes)?></div>
		<?php endif;?>
		
		<?php if($showlastvote == 1):?>
		<div class="rp-text"><?php echo JText::sprintf('COM_COMMUNITYPOLLS_LAST_VOTE', CJFunctions::get_formatted_date($poll->last_voted));?></div>
		<?php endif;?>
		
		<?php if($show_avatar == 1):?>
		<div class="float-clear"></div>
		<?php endif;?>
		
		<?php if(!$hide_comments && $config->get('comment_system', 'none') != 'none'):?>
		<p>
			<a href="<?php echo JRoute::_(CommunityPollsHelperRoute::getPollRoute($poll->slug, $poll->catslug, $poll->language));?>#cpcomments">
				<?php echo JText::_('COM_COMMUNITYPOLLS_LABEL_COMMENT');?>
			</a>
		</p>
		<?php endif;?>
	</div>
	
	<div style="display: none;">
		<span class="rp-random"><?php echo $rand;?></span>
		<span class="rp_poll_answers"><?php echo json_encode($poll->answers);?></span>
		<span class="rp_color_pallete"><?php echo json_encode($palette);?></span>
		<span class="rp_chart_type"><?php echo $poll->chart_type;?></span>
		<span class="rp_chart_width"><?php echo $chart_width;?></span>
		<span class="rp_chart_height"><?php echo $chart_height;?></span>
		<span class="rp_url_vote"><?php echo JRoute::_('index.php?option=com_communitypolls&task=poll.vote')?></span>
		<span class="lbl_no_answers_selected"><?php echo JText::_('MSG_ERROR_NO_SELECTION');?></span>
		<span class="lbl_no_select_one_answer"><?php echo JText::_('COM_COMMUNITYPOLLS_ERROR_SELECT_ONE_ANSWER');?></span>
		<span class="msg_thanks_for_voting"><?php echo JText::_('MSG_THANK_YOU_NO_RESULTS');?></span>
		<span class="lbl_answers"><?php echo JText::_('COM_COMMUNITYPOLLS_LABEL_ANSWERS');?></span>
		<span class="lbl_votes"><?php echo JText::_('COM_COMMUNITYPOLLS_LABEL_VOTES');?></span>
		<img class="rp-anim-icon" alt="..." src="<?php echo JURI::root(true).'/modules/mod_randompoll/assets/images/ui-anim_basic_16x16.gif'?>"/>
	</div>
</div>