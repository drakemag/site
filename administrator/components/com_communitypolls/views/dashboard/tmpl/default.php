<?php
/**
 * @version		$Id: default.php 01 2014-01-26 11:37:09Z maverick $
 * @package		CoreJoomla.Polls
 * @subpackage	Components
 * @copyright	Copyright (C) 2009 - 2014 corejoomla.com. All rights reserved.
 * @author		Maverick
 * @link		http://www.corejoomla.com/
 * @license		License GNU General Public License version 2 or later
 */
defined('_JEXEC') or die;

JHtml::addIncludePath(JPATH_COMPONENT.'/helpers/html');

JHtml::_('bootstrap.tooltip');
CJLib::behavior('bscore');
CJLib::behavior('fontawesome');

$app		= JFactory::getApplication();
$user		= JFactory::getUser();
$userId		= $user->id;

$daily_votes = array();

if(!empty($this->votes))
{
	foreach($this->votes as $dvotes)
	{
		$daily_votes[] = "[new Date('".$dvotes->dvoted."'),".$dvotes->votes."]";
	}
}

// $countries = array();
// foreach ($this->geoReport as $country=>$value)
// {
// 	$countries[] = "['".$value['country_name']."',".$value['votes']."]";
// }

$document = JFactory::getDocument();
$document->addScript('https://www.google.com/jsapi');
?>
<div id="cj-wrapper">
	<div class="span8">
		
		<div role="tabpanel">
			<ul class="nav nav-tabs" role="tablist">
				<?php
				/* 
				<li role="presentation" class="active">
					<a href="#geo" aria-controls=geo role="tab" data-toggle="tab">
						<i class="fa fa-globe"></i> <?php echo JText::_('COM_COMMUNITYPOLLS_GEO_ACTIVITY');?>
					</a>
				</li>
				*/
				?>
				<li role="presentation" class="active">
					<a href="#trends" aria-controls="trends" role="tab" data-toggle="tab">
						<i class="fa fa-bar-chart-o"></i> <?php echo JText::_('COM_COMMUNITYPOLLS_TITLE_LATEST_DAILY_VOTE_STATS')?>
					</a>
				</li>
			</ul>
			<div class="tab-content">
				<?php
				/*
				<div role="tabpanel" class="tab-pane active" id="geo">
					<div id="geo-location-report" style="width: 100%; height: 300px; max-height: 300px;"></div>
					<script type="text/javascript">
					google.load("visualization", "1.1", {packages:["geochart", "annotatedtimeline"]});
					google.setOnLoadCallback(drawRegionsMap);
					function drawRegionsMap() {
						var data = google.visualization.arrayToDataTable([
								[<?php echo JText::_('COM_COMMUNITYPOLLS_COUNTRY')?>, "<?php echo JText::_('COM_COMMUNITYPOLLS_VOTES')?>"], 
								<?php echo implode(',', $countries)?>
							]);
						var options = {};
						var chart = new google.visualization.GeoChart(document.getElementById('geo-location-report'));
						chart.draw(data, options);
					}
					</script>
				</div>
				*/
				?>
				<div role="tabpanel" class="tab-pane active" id="trends">
					<div id="daily-stats-chart" style="width: 100%; height: 300px;"></div>
					<script type="text/javascript">
						google.load("visualization", "1", {packages:["annotatedtimeline"]});
						google.setOnLoadCallback(drawChart);
		
						function drawChart() {
		
							var data = new google.visualization.DataTable();
					        data.addColumn('date', "<?php echo JText::_('JDATE')?>");
					        data.addColumn('number', "<?php echo JText::_('LBL_VOTES')?>");
					        data.addRows([<?php echo implode(',', $daily_votes);?>]);
		
							var options = {
					          title: 'Daily voting statistics',
					          hAxis: {title: '<?php echo JText::_('JDATE');?>',  titleTextStyle: {color: 'red'}},
					          displayAnnotations: false
					        };
		
					        var chart = new google.visualization.AnnotatedTimeLine(document.getElementById('daily-stats-chart'));
					        chart.draw(data, options);
					      }
					</script>
				</div>
			</div>
		</div>
		
		<div class="panel panel-default">
			<div class="panel-heading">
				<strong><i class="fa fa-refresh"></i> <?php echo JText::_('COM_COMMUNITYPOLLS_TITLE_LATEST_POLLS');?></strong>
			</div>
			<?php if(!$this->polls):?>
			<div class="panel-body">
				<?php echo JText::_('JGLOBAL_NO_MATCHING_RESULTS'); ?>
			</div>
			<?php else:?>
			<table class="table table-striped table-hover">
				<caption></caption>
				<thead>
					<tr>
						<th><?php echo JText::_('JGLOBAL_TITLE');?></th>
						<th width="10%" class="nowrap hidden-phone"><?php echo JText::_('JAUTHOR');?></th>
						<th width="5%" class="nowrap hidden-phone"><?php echo JText::_('JGRID_HEADING_LANGUAGE');?></th>
						<th width="10%" class="nowrap hidden-phone"><?php echo JText::_('JDATE');?></th>
						<th width="10%"><?php echo JText::_('COM_COMMUNITYPOLLS_VOTES');?></th>
						<th width="1%" class="nowrap hidden-phone"><?php echo JText::_('JGRID_HEADING_ID');?></th>
					</tr>
				</thead>
				<tbody>
				<?php foreach ($this->polls as $i => $item) :
					$canEdit    = $user->authorise('core.edit',       'com_communitypolls.poll.'.$item->id);
					$canCheckin = $user->authorise('core.manage',     'com_checkin') || $item->checked_out == $userId || $item->checked_out == 0;
					$canEditOwn = $user->authorise('core.edit.own',   'com_communitypolls.poll.'.$item->id) && $item->created_by == $userId;
					$canChange  = $user->authorise('core.edit.state', 'com_communitypolls.poll.'.$item->id) && $canCheckin;
					?>
					<tr>
						<td class="has-context">
							<div>
								<?php if ($item->checked_out) : ?>
									<?php echo JHtml::_('jgrid.checkedout', $i, $item->editor, $item->checked_out_time, 'polls.', $canCheckin); ?>
								<?php endif; ?>
								<?php if ($item->language == '*'):?>
									<?php $language = JText::alt('JALL', 'language'); ?>
								<?php else:?>
									<?php $language = $item->language_title ? $this->escape($item->language_title) : JText::_('JUNDEFINED'); ?>
								<?php endif;?>
								<?php if ($canEdit || $canEditOwn) : ?>
									<a href="<?php echo JRoute::_('index.php?option=com_communitypolls&task=poll.edit&id=' . $item->id); ?>" title="<?php echo JText::_('JACTION_EDIT'); ?>">
										<?php echo $this->escape($item->title); ?></a>
								<?php else : ?>
									<span title="<?php echo JText::sprintf('JFIELD_ALIAS_LABEL', $this->escape($item->alias)); ?>"><?php echo $this->escape($item->title); ?></span>
								<?php endif; ?>
								<div class="small">
									<?php echo JText::_('JCATEGORY') . ": " . $this->escape($item->category_title); ?>
								</div>
							</div>
						</td>
						<td class="small hidden-phone">
							<?php if ($item->created_by_alias) : ?>
								<a href="<?php echo JRoute::_('index.php?option=com_users&task=user.edit&id='.(int) $item->created_by); ?>" title="<?php echo JText::_('JAUTHOR'); ?>">
								<?php echo $this->escape($item->author_name); ?></a>
								<p class="smallsub"> <?php echo JText::sprintf('JGLOBAL_LIST_ALIAS', $this->escape($item->created_by_alias)); ?></p>
							<?php else : ?>
								<a href="<?php echo JRoute::_('index.php?option=com_users&task=user.edit&id='.(int) $item->created_by); ?>" title="<?php echo JText::_('JAUTHOR'); ?>">
								<?php echo $this->escape($item->author_name); ?></a>
							<?php endif; ?>
						</td>
						<td class="small hidden-phone">
							<?php if ($item->language == '*'):?>
								<?php echo JText::alt('JALL', 'language'); ?>
							<?php else:?>
								<?php echo $item->language_title ? $this->escape($item->language_title) : JText::_('JUNDEFINED'); ?>
							<?php endif;?>
						</td>
						<td class="nowrap small hidden-phone">
							<?php echo JHtml::_('date', $item->created, JText::_('DATE_FORMAT_LC4')); ?>
						</td>
						<td class="center">
							<?php echo (int) $item->votes; ?>
						</td>
						<td class="center hidden-phone">
							<?php echo (int) $item->id; ?>
						</td>
					</tr>
					<?php endforeach; ?>
				</tbody>
			</table>
			<?php endif;?>
		</div>
	</div>
	<div class="span4">
		<div class="panel panel-default">
			<div class="panel-heading">
				<strong><i class="fa fa-bullhorn"></i> <?php echo JText::_('COM_COMMUNITYPOLLS_TITLE_VERSION');?></strong>
			</div>
			<table class="table table-striped">
				<thead>
					<tr>
						<td colspan="2">
							<p>If you use Community Polls, please post a rating and a review at the Joomla Extension Directory</p>
							<a class="btn btn-info" href="http://extensions.joomla.org/extensions/contacts-and-feedback/polls/21726" target="_blank">
								<i class="icon-share icon-white"></i> <span style="color: white">Post Your Review</span>
							</a>
						</td>
					</tr>
				</thead>
				<tbody>
					<tr>
						<th><?php echo JText::_('COM_COMMUNITYPOLLS_MENU');?>:</th>
						<td><?php echo CP_CURR_VERSION;?></td>
					<tr>
					<?php if(!empty($this->version)):?>
					<tr>
						<th>Latest Version:</th>
						<td><?php echo $this->version['version'];?></td>
					</tr>
					<tr>
						<th>Latest Version Released On:</th>
						<td><?php echo $this->version['released'];?></td>
					</tr>
					<tr>
						<th>CjLib Version</th>
						<td><?php echo CJLIB_VER;?></td>
					</tr>
					<tr>
						<td colspan="2" style="text-align: center;">
							<?php if($this->version['status'] == 1):?>
							<a href="http://www.corejoomla.com/downloads.html" target="_blank" class="btn btn-danger">
								<i class="icon-download icon-white"></i> <span style="color: white">Please Update</span>
							</a>
							<?php else:?>
							<a href="#" class="btn btn-success"><i class="icon-ok icon-white"></i> <span style="color: white">Up-to date</span></a>
							<?php endif;?>
						</td>
					</tr>
					<?php endif;?>
				</tbody>
			</table>
		</div>
		<div class="panel panel-default">
			<div class="panel-heading">
				<strong><i class="fa fa-group"></i> <?php echo JText::_('COM_COMMUNITYPOLLS_TITLE_TOP_VOTERS');?></strong>
			</div>
			<?php if(!$this->voters):?>
			<div class="panel-body">
				<?php echo JText::_('JGLOBAL_NO_MATCHING_RESULTS'); ?>
			</div>
			<?php else:?>
			<table class="table table-striped table-hover">
				<caption></caption>
				<thead>
					<tr>
						<th><?php echo JText::_('JGLOBAL_TITLE');?></th>
						<th width="20%"><?php echo JText::_('COM_COMMUNITYPOLLS_VOTES');?></th>
						<th width="25%" class="nowrap hidden-phone"><?php echo JText::_('COM_COMMUNITYPOLLS_LAST_VOTED');?></th>
					</tr>
				</thead>
				<tbody>
				<?php foreach ($this->voters as $i => $item) :
					?>
					<tr>
						<td><?php echo $this->escape($item->username);?>
						<td><?php echo $item->votes;?></td>
						<td class="hidden-phone">
							<?php echo JHtml::_('date', $item->last_voted, JText::_('DATE_FORMAT_LC4')); ?>
						</td>
					</tr>
					<?php endforeach; ?>
				</tbody>
			</table>
			<?php endif;?>
		</div>
		<div class="panel panel-default">
			<div class="panel-heading"><strong>Credits: </strong></div>
			<div class="panel-body">
				<div>Community Polls is a free software released under Gnu/GPL license. Copyright© 2009-12 corejoomla.com</div>
				<div>Core Components: Bootstrap, jQuery, FontAwesome and ofcourse Joomla<sup>&reg;</sup>.</div>
			</div>
		</div>
	</div>
</div>
