<?php
/**
 * @version		$Id: default.php 01 2011-01-11 11:37:09Z maverick $
 * @package		CoreJoomla.Polls
 * @subpackage	Components.site
 * @copyright	Copyright (C) 2009 - 2010 corejoomla.com, Inc. All rights reserved.
 * @author		Maverick
 * @link		http://www.corejoomla.com/
 * @license		License GNU General Public License version 2 or later
 */

defined('_JEXEC') or die;

JHtml::addIncludePath(JPATH_COMPONENT . '/helpers');
CJFunctions::load_jquery(array('libs'=>array('validate')));

// Create shortcuts to some parameters.
$params  = $this->item->params;
$canEdit = $params->get('access-edit');
$user    = JFactory::getUser();
$info    = $params->get('info_block_position', 0);
$system  = $params->get('avatar_component', 'none');
$bbcode	 = $params->get('default_editor', 'none') == 'wysiwygbb' ? true : false;
$messages= CommunityPollsHelper::getMessages($this->item, $this->item->params);

JHtml::_('behavior.caption');
$useDefList = ($params->get('show_modify_date') || $params->get('show_publish_date') || $params->get('show_create_date')
	|| $params->get('show_votes') || $params->get('show_category') || $params->get('show_parent_category') || $params->get('show_author'));

// Build the poll information block.
$info_block = '';
if ($params->get('show_author') && !empty($this->item->author ))
{
	$author_name = $this->item->created_by_alias ? $this->item->created_by_alias : $this->item->author;
	$author_url = CJFunctions::get_user_profile_url($system, $this->item->created_by, $author_name, true);
	$created_by = $params->get('link_author') ? JHtml::link($author_url, $author_name) : $author_name;

	$info_block = $info_block.'<div class="info-item createdby"><span class="icon-user"></span> ';
	$info_block = $info_block.JText::sprintf('COM_COMMUNITYPOLLS_WRITTEN_BY', $created_by);
	$info_block = $info_block.'</div>';
}

if ($params->get('show_parent_category') && !empty($this->item->parent_slug))
{
	$info_block = $info_block.'<div class="info-item parent-category-name"><span class="icon-folder-open"></span> ';
	$title = $this->escape($this->item->parent_title);
	$url = '<a href="'.JRoute::_(CommunityPollsHelperRoute::getCategoryRoute($this->item->parent_slug)).'">'.$title.'</a>';

	if ($params->get('link_parent_category') && !empty($this->item->parent_slug))
	{
		$info_block = $info_block.JText::sprintf('COM_COMMUNITYPOLLS_PARENT', $url);
	}
	else
	{
		$info_block = $info_block.JText::sprintf('COM_COMMUNITYPOLLS_PARENT', $title); 
	}
	$info_block = $info_block.'</div>';
}

if ($params->get('show_category'))
{
	$info_block = $info_block.'<div class="info-item category-name"><span class="icon-folder"></span> ';
	$title = $this->escape($this->item->category_title);
	$url = '<a href="' . JRoute::_(CommunityPollsHelperRoute::getCategoryRoute($this->item->catslug)) . '">' . $title . '</a>';
	
	if ($params->get('link_category') && $this->item->catslug)
	{
		$info_block = $info_block.JText::sprintf('COM_COMMUNITYPOLLS_CATEGORY', $url); 
	}
	else 
	{
		$info_block = $info_block.JText::sprintf('COM_COMMUNITYPOLLS_CATEGORY', $title);
	}
	$info_block = $info_block.'</div>';
}

if ($params->get('show_publish_date'))
{
	$info_block = $info_block.'<div class="info-item published"><span class="icon-calendar"></span> ';
	$info_block = $info_block.JText::sprintf('COM_COMMUNITYPOLLS_PUBLISHED_DATE_ON', JHtml::_('date', $this->item->publish_up, JText::_('DATE_FORMAT_LC3')));
	$info_block = $info_block.'</div>';
}

if ($info == 0)
{
	if ($params->get('show_create_date'))
	{
		$info_block = $info_block.'<span class="info-item create"><div class="icon-calendar"></div> ';
		$info_block = $info_block.JText::sprintf('COM_COMMUNITYPOLLS_CREATED_DATE_ON', JHtml::_('date', $this->item->created, JText::_('DATE_FORMAT_LC3')));
		$info_block = $info_block.'</span>';
	}
	
	if ($params->get('show_modify_date'))
	{
		$info_block = $info_block.'<div class="info-item modified"><span class="icon-calendar"></span> ';
		$info_block = $info_block.JText::sprintf('COM_COMMUNITYPOLLS_LAST_UPDATED', JHtml::_('date', $this->item->modified, JText::_('DATE_FORMAT_LC3')));
		$info_block = $info_block.'</div>';
	}
}

$layout = $this->params->get('layout', 'default');
?>
<div id="cj-wrapper" class="item-page<?php echo $this->pageclass_sfx?>">

	<?php echo JLayoutHelper::render($layout.'.toolbar', array('params'=>$this->params, 'state'=>$this->state));?>
	
	<div class="panel panel-default margin-top-20">
		<div class="panel-body">
			<?php if (!$useDefList && $this->print) : ?>
				<div id="pop-print" class="btn">
					<?php echo JHtml::_('icon.print_screen', $this->item, $params); ?>
				</div>
				<div class="clearfix"> </div>
			<?php endif; ?>
			
			<?php if ($params->get('show_title') || $params->get('show_author')) : ?>
			<div class="page-header no-margin-top">
				<h2 class="no-margin-top">
					<?php if ($params->get('show_title')) : ?>
						<?php if ($params->get('link_titles') && !empty($this->item->readmore_link)) : ?>
							<a href="<?php echo $this->item->readmore_link; ?>"> <?php echo $this->escape($this->item->title); ?></a>
						<?php else : ?>
							<?php echo $this->escape($this->item->title); ?>
						<?php endif; ?>
					<?php endif; ?>
				</h2>
				<?php if ($this->item->published == 0) : ?>
					<span class="label label-warning"><?php echo JText::_('JUNPUBLISHED'); ?></span>
				<?php endif; ?>
				<?php if (strtotime($this->item->publish_up) > strtotime(JFactory::getDate())) : ?>
					<span class="label label-warning"><?php echo JText::_('JNOTPUBLISHEDYET'); ?></span>
				<?php endif; ?>
				<?php if ((strtotime($this->item->publish_down) < strtotime(JFactory::getDate())) && $this->item->publish_down != '0000-00-00 00:00:00') : ?>
					<span class="label label-warning"><?php echo JText::_('JEXPIRED'); ?></span>
				<?php endif; ?>
			</div>
			<?php endif; ?>
			
			<?php if (!$this->print) : ?>
				<?php if ($canEdit || $params->get('show_print_icon') || $params->get('show_email_icon')) : ?>
				<div class="btn-group pull-right">
					<a class="btn dropdown-toggle" data-toggle="dropdown" href="#"> <span class="icon-cog"></span> <span class="caret"></span> </a>
					<?php // Note the actions class is deprecated. Use dropdown-menu instead. ?>
					<ul class="dropdown-menu actions">
						<?php if ($params->get('show_print_icon')) : ?>
						<li class="print-icon"> <?php echo JHtml::_('icon.print_popup', $this->item, $params); ?> </li>
						<?php endif; ?>
						<?php if ($params->get('show_email_icon')) : ?>
						<li class="email-icon"> <?php echo JHtml::_('icon.email', $this->item, $params); ?> </li>
						<?php endif; ?>
						<?php if ($canEdit) : ?>
						<li class="edit-icon"> <?php echo JHtml::_('icon.edit', $this->item, $params); ?> </li>
						<?php endif; ?>
					</ul>
				</div>
				<?php endif; ?>
			<?php else : ?>
				<?php if ($useDefList) : ?>
					<div id="pop-print" class="btn">
						<?php echo JHtml::_('icon.print_screen', $this->item, $params); ?>
					</div>
				<?php endif; ?>
			<?php endif; ?>

			<?php if ($useDefList && ($info == 0 || $info == 2)) : ?>
				<div class="poll-info muted"><?php echo $info_block?></div>
			<?php endif; ?>
			
			<?php if (!$params->get('show_intro')) : echo $this->item->event->afterDisplayTitle; endif; ?>
			<?php echo $this->item->event->beforeDisplayContent; ?>
			<?php if ($params->get('access-view')):?>
				<?php echo $this->item->text; ?>
			<?php endif; ?>
			<?php echo $this->item->event->afterDisplayContent; ?>
				
			<?php if ($useDefList && ($info == 1 || $info == 2)) : ?>
				<div class="poll-info muted"><?php echo $info_block?></div>
			<?php endif; ?>
		</div>
		<?php if ($params->get('show_tags', 1) && !empty($this->item->tags->itemTags)) : ?>
			<ul class="list-group" style="margin-left: 0;">
				<li class="list-group-item">
					<?php $this->item->tagLayout = new JLayoutFile('joomla.content.tags'); ?>
					<?php echo $this->item->tagLayout->render($this->item->tags->itemTags); ?>
				</li>
			</ul>
		<?php endif; ?>
		
		<?php if ($params->get('show_votes')):?>
		<div class="panel-footer muted">
			<span class="info-item votes"><span class="icon-chart"></span> <?php echo JText::sprintf('COM_COMMUNITYPOLLS_VOTE_STATISTICS', $this->item->votes, $this->item->voters)?></span>
			<?php echo JText::sprintf('COM_COMMUNITYPOLLS_LAST_VOTE', CJFunctions::get_formatted_date($this->item->last_voted));?>
		</div>
		<?php endif;?>
	</div>
	
	<div class="panel panel-<?php echo $this->theme;?> poll-messages" style="<?php echo count($messages) ? '' : 'display: none';?>">
		<div class="panel-heading"><?php echo JText::_('COM_COMMUNITYPOLLS_MESSAGE');?></div>
		<div class="panel-body">
			<div class="poll-end-message"></div>
			
			<?php 
			foreach ($messages as $message)
			{
				if(!empty($message))
				{
				?>
				<p><i class="fa fa-info-circle"></i> <?php echo $message;?></p>
				<?php
				}
			}
			?>
		</div>
	</div>
		
	<?php 
	$show_vote_form = $this->item->params->get('show_vote_form');
	$access_vote = $this->item->params->get('access_vote');
	
	if( $show_vote_form )
	{
		?>
		<div class="voting-form">
			<?php echo $this->loadTemplate('answers');?>
		</div>
		<?php 
	}
	
	if ( $this->item->publish_results )
	{
		?>
		<div class="poll-results" <?php echo $show_vote_form ? 'style="display: none;"' : '';?>>
			<?php  echo $this->loadTemplate('results');?>
		</div>
		<?php 
	}
	?>
	
	<?php if($access_vote || ($this->item->publish_results && $show_vote_form)):?>
	<div class="well well-small">
		<?php if ($this->item->publish_results && $show_vote_form && $this->item->votes > 0):?>
		<button class="btn btn-default btn-view-result"><i class="fa fa-bar-chart-o"></i> <?php echo JText::_('COM_COMMUNITYPOLLS_VIEW_RESULT');?></button>
		<button class="btn btn-default btn-vote-form hide"><i class="fa fa-th-list"></i> <?php echo JText::_('COM_COMMUNITYPOLLS_VOTE_FORM');?></button>
		<?php endif;?>
		
		<?php if ($access_vote):?>
		<button class="btn btn-primary btn-vote"><i class="fa fa-hand-o-up"></i> <?php echo JText::_('COM_COMMUNITYPOLLS_SUBMIT_VOTE')?></button>
		<?php endif;?>
	</div>
	<?php endif;?>
	
	<?php echo $this->loadTemplate('extra');?>
	
	<input type="hidden" id="cjpageid" value="poll">
</div>