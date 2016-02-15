<?php
/**
 * @version		$Id: default_suggestions.php 01 2011-01-11 11:37:09Z maverick $
 * @package		CoreJoomla.Polls
 * @subpackage	Components.site
 * @copyright	Copyright (C) 2009 - 2014 corejoomla.com, Inc. All rights reserved.
 * @author		Maverick
 * @link		http://www.corejoomla.com/
 * @license		License GNU General Public License version 2 or later
 */

defined('_JEXEC') or die;

$comment_system = $this->params->get('comment_system', 'none');
$sharing_services = $this->params->get('sharing_services', array());

if(count($sharing_services) > 0)
{
	$document = JFactory::getDocument();
	$document->addScript('//s7.addthis.com/js/300/addthis_widget.js#async=1');
	$document->addScriptDeclaration('jQuery(document).ready(function($){addthis.init();});');
}
?>
<div class="panel-group" id="poll-extra-accordion">
	<!--************************* START: Sharing/Bookmarks *************************-->
	<?php if($this->item->anywhere == 1 || count($sharing_services) > 0):?>
	<div class="panel panel-<?php echo $this->theme?> poll-sharing">
		<div class="panel-heading">
			<a data-toggle="collapse" data-parent="#poll-extra-accordion" href="#poll-sharing-body">
				<i class="icon icon-users"></i> <strong><?php echo JText::_('COM_COMMUNITYPOLLS_POLL_SHARING');?></strong>
			</a>
		</div>
		<div id="poll-sharing-body" class="panel-collapse collapse in">
			<div class="panel-body">
				<?php if($this->item->anywhere == '1'): ?>
				<div class="polls-anywhere">
					<p><?php echo JText::sprintf('COM_COMMUNITYPOLLS_ANYWHERE_DESCRIPTION', P_MEDIA_URI.'anywhere/help.html');?></p>
					<div class="polls-anywhere-code">
						<?php
						$code = 
							'<div class="cjpollsanywhere"><input type="hidden"></div>'.
							'<script type="text/javascript" src="'.P_MEDIA_URI.'anywhere/anywhere.js"></script>'.
							'<script type="text/javascript">PollsAnywhere({id:'.$this->item->id.', anywhere: true});</script>';
						
						echo '<pre>'.htmlentities($code).'</pre>';
						?>
					</div>
				</div>
				<?php endif;?>
				
				<?php if(!empty($sharing_services)):?>
				<div class="social-sharing">
					<div class="addthis_toolbox addthis_default_style ">
						<?php if(in_array('fblike', $sharing_services)):?>
						<a class="addthis_button_facebook_like" fb:like:layout="button_count"></a>
						<?php endif;?>
						<?php if(in_array('tweet', $sharing_services)):?>
						<a class="addthis_button_tweet"></a>
						<?php endif;?>
						<?php if(in_array('googleplus', $sharing_services)):?>
						<a class="addthis_button_google_plusone" g:plusone:size="medium"></a>
						<?php endif;?> 
						<?php if(in_array('addthis', $sharing_services)):?>
						<a class="addthis_counter addthis_pill_style"></a>
						<?php endif;?>
					</div>
					<p><?php echo JText::_('COM_COMMUNITYPOLLS_SOCIAL_SHARING_DESC');?></p>
				</div>
				<?php endif;?>
			</div>
		</div>
	</div>
	<?php endif;?>
	<!--************************* END: Sharing/Bookmarks *************************-->
	
	<?php if($this->params->get('show_timeline', 1) == 1):?>
	<!--***************************** START: Timeline ****************************-->
	<div class="panel panel-<?php echo $this->theme?> poll-timeline">
		<div class="panel-heading">
			<a data-toggle="collapse" data-parent="#poll-extra-accordion" href="#poll-timeline-body">
				<i class="icon icon-chart"></i> <strong><?php echo JText::_('COM_COMMUNITYPOLLS_POLL_TIMELINE');?></strong>
			</a>
		</div>
		<div id="poll-timeline-body" class="panel-collapse collapse">
			<div class="panel-body">
				<?php if(empty($this->item->vstats)):?>
					<?php echo JText::_('COM_COMMUNITYPOLLS_MSG_NO_DATA_AVAILABLE_FOR_TIMELINE')?>
				<?php else:?>
					<?php if(in_array($this->item->chart_type, array('gpie', 'sbar'))):?>
					<div id="timeline-graph"></div>
					<div class="timeline-data">
						<script type="text/javascript">
							var data = new google.visualization.DataTable();
					        data.addColumn('date', "<?php echo JText::_('COM_COMMUNITYPOLLS_LABEL_DATE')?>");
					        data.addColumn('number', "<?php echo JText::_('COM_COMMUNITYPOLLS_LABEL_VOTES')?>");
					        
					        data.addRows(
							        [
							        <?php foreach($this->item->vstats as $i=>$stat):?>
							        	[new Date('<?php echo $stat->vdate?>'), <?php echo $stat->votes;?>]
							        	<?php echo $i+1 == count($this->item->vstats) ? '' : ',';?>
							        <?php endforeach;?>
							        ]);
					        var chart = new google.visualization.AreaChart(document.getElementById('timeline-graph'));
					        chart.draw(data, {width: '100%', height: 250, 'chartArea': {'width': '92%', 'height': '80%'}, 'legend': {'position': 'in'}});
						</script>
					</div>
					<?php else:?>
					<img src="<?php echo ChartsHelper::get_timeline_chart($this->item);?>"/>
					<?php endif;?>
				<?php endif;?>
			</div>
		</div>
	</div>
	<!--******************************* END: Timeline ******************************-->
	<?php endif;?>
	
	<!--***************************** START: Suggestions ****************************-->
	<?php if(isset($this->item->suggestions) && count($this->item->suggestions) > 0):?>
	<div class="suggestions">
		<?php if(count($this->item->suggestions) == 1):?>
			<?php foreach($this->item->suggestions as $i=>$suggestion):?>
			<h3 class="page-header"><?php echo $this->escape($suggestion['title']);?></h3>
			<?php endforeach;?>
		<?php else:?>
		<ul class="nav nav-tabs" id="poll-suggestions">
			<?php foreach($this->item->suggestions as $i=>$suggestion):?>
			<li<?php echo $suggestion['active'] ? ' class="active"' : '';?>>
				<a data-toggle="tab" data-target="#<?php echo $suggestion['id']?>" href="<?php echo $suggestion['href'];?>">
					<strong><i class="icon icon-asterisk"></i> <?php echo $this->escape($suggestion['title']);?></strong>
				</a>
			</li>
			<?php endforeach;?>
		</ul>
		<div class="tab-content">
		<?php endif;?>
			
			<?php foreach($this->item->suggestions as $i=>$suggestion):?>
			
			<?php if(count($this->item->suggestions) != 1):?>
			<div class="tab-pane<?php echo $suggestion['active'] ? ' active' : '';?>" id="<?php echo $suggestion['id'];?>">
			<?php endif;?>
			
				<?php if(!empty($suggestion['polls'])):?>
				<table class="table table-striped table-hover">
					<tbody>
						<?php 
						foreach ($suggestion['polls'] as $i=>$poll):
						$slug = $poll->alias ? ($poll->id.':'.$poll->alias) : $poll->id;
						$catslug = $poll->category_alias ? ($poll->catid.':'.$poll->category_alias) : $poll->catid;
						?>
						<tr>
							<td>
								<?php if($this->params->get('show_suggestions_vote_count', 1) == 1):?>
								<span class="label" title="<?php echo JText::plural('COM_COMMUNITYPOLLS_VOTES', $poll->votes)?>" data-trigger="tooltip" data-placement="right"><?php echo $poll->votes?></span>&nbsp;
								<?php endif;?>
								<a href="<?php echo CommunityPollsHelperRoute::getPollRoute($slug, $catslug);?>"><?php echo $this->escape($poll->title);?></a>
							</td>
						</tr>
						<?php endforeach;?>
					</tbody>
				</table>
				<?php else:?>
				<?php echo JText::_('COM_COMMUNITYPOLLS_LOADING_SUGGESTIONS');?>
				<?php endif;?>
			
			<?php if(count($this->item->suggestions) != 1):?>
			</div>
			<?php endif;?>
			
			<?php endforeach;?>
		<?php if(count($this->item->suggestions) != 1):?>
		</div>
		<?php endif;?>
	</div>
	<?php endif;?>
	<!--***************************** END: Suggestions ****************************-->
</div>

<!--************************* START: Comments *************************-->
<?php if($comment_system != 'none'):?>
<div id="cpcomments" class="row-fluid">
	<div class="span12">
		<h2 class="page-header"><?php echo JText::_('COM_COMMUNITYPOLLS_COMMENTS');?></h2>
		<?php 
		$fburl = JRoute::_(CommunityPollsHelperRoute::getPollRoute($this->item->slug, $this->item->catslug), false, -1);
		echo CJFunctions::load_comments($comment_system, 'com_communitypolls', $this->item->id, $this->escape($this->item->title), $fburl, $this->params->get('disqus_intdbt_id'), $this->item);
		?>
	</div>
</div>
<?php endif;?>
<!--************************* END: Comments *************************-->

<div id="anywhere-modal" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;">
	<div class="modal-header">
		<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
		<h3 id="myModalLabel"><?php echo JText::_('COM_COMMUNITYPOLLS_POLLS_ANYWHERE_TITLE');?></h3>
	</div>
	<div class="modal-body"></div>
	<div class="modal-footer">
		<button class="btn" data-dismiss="modal" aria-hidden="true"><?php echo JText::_('COM_COMMUNITYPOLLS_CLOSE');?></button>
	</div>
</div>
