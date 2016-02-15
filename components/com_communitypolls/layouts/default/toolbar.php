<?php
/**
 * @version		$Id: polls.php 01 2011-01-11 11:37:09Z maverick $
 * @package		CoreJoomla.Polls
 * @subpackage	Components.site
 * @copyright	Copyright (C) 2009 - 2014 corejoomla.com, Inc. All rights reserved.
 * @author		Maverick
 * @link		http://www.corejoomla.com/
 * @license		License GNU General Public License version 2 or later
 */

defined('_JEXEC') or die;

$user 		= JFactory::getUser();
$params 	= $displayData['params'];
$state 		= $displayData['state'];

$category 	= isset($displayData['category']) ? $displayData['category'] : null;
$asset 		= !empty($category) ? 'com_communitypolls.category.'.$category->id : 'com_communitypolls';
$timelimit 	= $state->get('filter.timelimit');
$return		= base64_encode(JRoute::_('index.php'));

if($params->get('show_toolbar', 1) == 1)
{
?>
<nav class="navbar navbar-default" role="navigation">
	<div class="navbar-inner">
		<div class="navbar-header">
			<button type="button" class="btn btn-navbar navbar-toggle" data-toggle="collapse" data-target="#cp-navbar-collapse">
<!-- 				<span class="sr-only">Toggle navigation</span> -->
				<span class="icon-bar"></span>
				<span class="icon-bar"></span>
				<span class="icon-bar"></span>
			</button>
			<a class="brand navbar-brand" href="<?php echo JRoute::_('index.php?option=com_communitypolls');?>">
				<?php echo JText::_('COM_COMMUNITYPOLLS_POLLS');?>
			</a>
		</div>
		<div class="collapse nav-collapse navbar-collapse navbar-responsive-collapse" id="cp-navbar-collapse">
			<?php if($user->authorise('core.create', $asset)):?>
			<ul class="nav pull-right no-margin-bottom">
				<li>
					<a href="<?php echo CommunityPollsHelperRoute::getFormRoute().'&return='.$return;?>">
						<i class="fa fa-edit"></i> <?php echo JText::_('COM_COMMUNITYPOLLS_SUBMIT_POLL');?>
					</a>
				</li>
				<li class="dropdown">
					<a href="#" class="dropdown-toggle" data-toggle="dropdown"><?php echo JText::_('COM_COMMUNITYPOLLS_LABEL_ACCOUNT');?> <b class="caret"></b></a>
					<ul class="dropdown-menu">
						<?php if($user->authorise('core.create', $asset)):?>
						<li>
							<a href="<?php echo JRoute::_('index.php?option=com_communitypolls&view=mypolls');?>">
								<i class="fa fa-user"></i> <?php echo JText::_('COM_COMMUNITYPOLLS_LABEL_MY_POLLS');?>
							</a>
						</li>
						<?php endif;?>
						<?php if($user->authorise('core.vote', $asset)):?>
						<li>
							<a href="<?php echo JRoute::_('index.php?option=com_communitypolls&view=votes');?>">
								<i class="fa fa-hand-o-up"></i> <?php echo JText::_('COM_COMMUNITYPOLLS_LABEL_MY_VOTES');?>
							</a>
						</li>
						<?php endif;?>
					</ul>
				</li>
			</ul>
			<?php endif;?>
			<ul class="nav no-margin-bottom">
				<li class="dropdown">
					<a href="#" class="dropdown-toggle" data-toggle="dropdown"><?php echo JText::_('COM_COMMUNITYPOLLS_LABEL_DISCOVER');?> <b class="caret"></b></a>
					<ul class="dropdown-menu">
						<li class="nav-header"><?php echo JText::_('COM_COMMUNITYPOLLS_BROWSE_POLLS');?></li>
						<li>
							<a href="#" onclick="filterPolls('', 'voters', 'desc', 0, ''); return false;">
								<i class="fa fa-fire"></i> <?php echo JText::_('COM_COMMUNITYPOLLS_LABEL_POPULAR_POLLS')?>
							</a>
						</li>
						<li>
							<a href="#" onclick="filterPolls('', 'votes', 'desc', 0, ''); return false;">
								<i class="fa fa-hand-o-up"></i> <?php echo JText::_('COM_COMMUNITYPOLLS_LABEL_MOST_VOTED_POLLS')?>
							</a>
						</li>
						<li>
							<a href="#" onclick="filterPolls('only', 'featured', 'desc', 0, ''); return false;">
								<i class="fa fa-empire"></i> <?php echo JText::_('COM_COMMUNITYPOLLS_LABEL_FEATURED_POLLS')?>
							</a>
						</li>
						<li class="nav-header"><?php echo JText::_('COM_COMMUNITYPOLLS_FILTER_POLLS');?></li>
						<li<?php echo $timelimit == 0 ? ' class="active"' : ''?>>
							<a href="#" onclick="filterPolls('', 'a.created', 'desc', 0, ''); return false;">
								<i class="fa fa-clock-o"></i> <?php echo JText::_('COM_COMMUNITYPOLLS_ALL_TIME');?>
							</a>
						</li>
						<li<?php echo $timelimit == 1 ? ' class="active"' : ''?>>
							<a href="#" onclick="filterPolls('', 'a.created', 'desc', 1, ''); return false;">
								<i class="fa fa-clock-o"></i> <?php echo JText::_('COM_COMMUNITYPOLLS_TODAY');?>
							</a>
						</li>
						<li<?php echo $timelimit == 2 ? ' class="active"' : ''?>>
							<a href="#" onclick="filterPolls('', 'a.created', 'desc', 2, ''); return false;">
								<i class="fa fa-clock-o"></i> <?php echo JText::_('COM_COMMUNITYPOLLS_THIS_WEEK');?>
							</a>
						</li>
						<li<?php echo $timelimit == 3 ? ' class="active"' : ''?>>
							<a href="#" onclick="filterPolls('', 'a.created', 'desc', 3, ''); return false;">
								<i class="fa fa-clock-o"></i> <?php echo JText::_('COM_COMMUNITYPOLLS_THIS_MONTH');?>
							</a>
						</li>
						<li<?php echo $timelimit == 4 ? ' class="active"' : ''?>>
							<a href="#" onclick="filterPolls('', 'a.created', 'desc', 4, ''); return false;">
								<i class="fa fa-clock-o"></i> <?php echo JText::_('COM_COMMUNITYPOLLS_LAST_MONTH');?>
							</a>
						</li>
						<li<?php echo $timelimit == 5 ? ' class="active"' : ''?>>
							<a href="#" onclick="filterPolls('', 'a.created', 'desc', 5, ''); return false;">
								<i class="fa fa-clock-o"></i> <?php echo JText::_('COM_COMMUNITYPOLLS_CURRENT_YEAR');?>
							</a>
						</li>
						<li class="nav-header"><?php echo JText::_('COM_COMMUNITYPOLLS_LABEL_SEARCH');?></li>
						<li>
							<a href="<?php echo JRoute::_('index.php?option=com_communitypolls&view=search');?>">
								<i class="fa fa-hand-o-up"></i> <?php echo JText::_('COM_COMMUNITYPOLLS_LABEL_ADVANCED_SEARCH')?>
							</a>
						</li>
					</ul>
				</li>
			</ul>
		</div>
	</div>
</nav>
<?php 
}
?>
<form id="toolbarFilterForm" name="toolbarFilterForm" action="<?php echo JRoute::_('index.php');?>" method="post" style="display: none;">
	<input type="hidden" id="filter_featured" name="filter_featured" value="">
	<input type="hidden" id="view" name="view" value="polls">
	<input type="hidden" id="list_filter" name="list_filter" value="">
	<input type="hidden" id="filter_order" name="filter_order" value="created">
	<input type="hidden" id="filter_order_Dir" name="filter_order_Dir" value="desc">
	<input type="hidden" id="filter_timelimit" name="filter_timelimit" value="<?php echo $state->get('filter.timelimit', 0);?>">
</form>

<script type="text/javascript">
<!--
function filterPolls(featured, order, direction, timelimit, search)
{
	document.toolbarFilterForm.filter_featured.value = featured;
	document.toolbarFilterForm.filter_order.value = order;
	document.toolbarFilterForm.filter_order_Dir.value = direction;
	document.toolbarFilterForm.filter_timelimit.value = timelimit;
	document.toolbarFilterForm.list_filter.value = search;
	document.toolbarFilterForm.view.value = 'polls';

	document.toolbarFilterForm.submit();
}
//-->
</script>