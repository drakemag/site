<?php
/**
 * @package     corejoomla.administrator
 * @subpackage  com_communitypolls
 *
 * @copyright   Copyright (C) 2009 - 2014 corejoomla.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */
defined('_JEXEC') or die();

$user = JFactory::getUser();

$params 			= $displayData['params'];
$state				= isset($displayData['state']) ? $displayData['state'] : null;
$pagination 		= $displayData['pagination'];
$items 				= $displayData['items'];
$theme 				= $params->get('theme', 'default');
$avatar  			= $params->get('avatar_component', 'none');
$profileComponent 	= $params->get('profile_component', 'none');
$avatarSize 		= $params->get('list_avatar_size', 48);
$heading 			= isset($displayData['heading']) ? $displayData['heading'] : JText::_('COM_COMMUNITYPOLLS_POLLS');
$subHeading 		= '';
$bbcode 			= $params->get('default_editor', 'none') == 'wysiwygbb' ? true : false;

$category 			= isset($displayData['category']) ? $displayData['category'] : null;
$asset 				= !empty($category) ? 'com_communitypolls.category.'.$category->id : 'com_communitypolls';
$subHeading 		= $category ? ' <small>['.$this->escape($category->title).']</small>' : $subHeading;

if(is_object($state))
{
	$authorId = $state->get('filter.author_id', 0);
	if($authorId)
	{
		$author = JFactory::getUser($authorId);
		$subHeading = $subHeading.' <small>['.$author->name.']</small>';
	}
	
	$featured = $state->get('filter.featured'. '');
	if($featured == 'only')
	{
		$subHeading = $subHeading.' <small>['.JText::_('COM_COMMUNITYPOLLS_LABEL_FEATURED_POLLS').']</small>';
	}
	
	$ordering = $state->get('list.ordering', '');
	if($ordering == 'votes')
	{
		$subHeading = $subHeading.' <small>['.JText::_('COM_COMMUNITYPOLLS_LABEL_MOST_VOTED_POLLS').']</small>';
	}
	else if($ordering == 'voters')
	{
		$subHeading = $subHeading.' <small>['.JText::_('COM_COMMUNITYPOLLS_LABEL_POPULAR_POLLS').']</small>';
	}
	
	$recent = $state->get('list.recent', false);
	if($recent == true)
	{
		$subHeading = $subHeading.' <small>['.JText::_('COM_COMMUNITYPOLLS_RECENT_POLLS').']</small>';
	}
}

if(!empty($items))
{
$api = new CjLibApi();
?>

<div class="panel panel-<?php echo $theme;?>">

	<?php if(!empty($heading)):?>
	<div class="panel-heading">
		<div class="panel-title"><i class="fa fa-pie-chart"></i> <?php echo $heading.$subHeading;?></div>
	</div>
	<?php endif;?>
	
	<ul class="list-group no-margin-left">
	<?php 
	foreach ($items as $item)
	{
		$author = $this->escape($item->author);
		$profileUrl = $api->getUserProfileUrl($profileComponent, $item->created_by);
		$pollUrl = CommunityPollsHelperRoute::getPollRoute($item->slug, $item->catslug, $item->language);
		$userAvatar = $api->getUserAvatarImage($avatar, $item->created_by, $item->author_email, $avatarSize, true);
		?>
		<li class="list-group-item<?php echo $item->featured ? ' list-group-item-warning' : '';?> pad-bottom-5">
			<div class="media">
				<?php if($avatar != 'none'):?>
				<div class="media-left hidden-phone">
					<?php if($profileComponent != 'none'):?>
					<a href="<?php echo $profileUrl;?>" title="<?php echo $author?>" class="thumbnail no-margin-bottom" data-toggle="tooltip">
						<img src="<?php echo $userAvatar;?>" alt="<?php echo $author?>" style="max-width: <?php echo $avatarSize;?>px;">
					</a>
					<?php else:?>
					<div class="thumbnail"><img src="<?php echo $userAvatar;?>" alt="<?php echo $author?>" style="max-width: <?php echo $avatarSize;?>px;"></div>
					<?php endif;?>
				</div>
				<?php endif;?>
				<div class="media-left hidden-xs hidden-phone">
					<div class="thumbnail center item-count-box no-margin-bottom">
						<div class="item-count-num"><?php echo CjLibUtils::formatNumber($item->votes);?></div>
						<div class="item-count-caption muted"><?php echo JText::plural('COM_COMMUNITYPOLLS_VOTES', $item->votes);?></div>
					</div>
				</div>
				
				<div class="media-body">
					<h4 class="media-heading no-margin-top">
						<?php
						if (in_array($item->access, $user->getAuthorisedViewLevels()))
						{
							?>
							<a href="<?php echo JRoute::_($pollUrl); ?>" title="<?php echo JHtml::_('string.truncate', strip_tags($item->description), 250);?>">
								<?php echo $this->escape($item->title); ?>
							</a>
							<?php
						}
						else 
						{
							echo $this->escape($item->title) . ' : ';
							
							$itemId = JFactory::getApplication()->getMenu()->getActive()->id;
							$fullURL = JRoute::_('index.php?option=com_users&view=login&Itemid=' . $itemId.'&return='.base64_encode(JRoute::_($pollUrl)));
							?>
							<a href="<?php echo $fullURL; ?>" class="register">
								<?php echo JText::_('COM_COMMUNITYPOLLS_REGISTER_TO_READ_MORE'); ?>
							</a>
							<?php
						}
						?>
					</h4>
					<ul class="inline list-inline forum-info">
						<?php if($item->published == 0 || $item->published == -2 || $item->featured == 1 || $item->private == 1 || 
								(strtotime($item->publish_up) > strtotime(JFactory::getDate())) ||
								((strtotime($item->publish_down) < strtotime(JFactory::getDate())) && $item->publish_down != '0000-00-00 00:00:00')):?>
						<li>
							<?php if($item->featured == 1):?>
							<span class="label label-info"><i class="fa fa-bookmark"></i> <?php echo JText::_('JFEATURED');?></span>
							<?php endif;?>
							
							<?php if($item->published == 0):?>
							<span class="label label-warning"><i class="fa fa-ban"></i> <?php echo JText::_('JUNPUBLISHED');?></span>
							<?php endif;?>
							
							<?php if($item->published == -2):?>
							<span class="label label-danger"><i class="fa fa-trash-o"></i> <?php echo JText::_('JTRASHED');?></span>
							<?php endif;?>
							
							<?php if (strtotime($item->publish_up) > strtotime(JFactory::getDate())) : ?>
								<span class="label label-warning"><?php echo JText::_('JNOTPUBLISHEDYET'); ?></span>
							<?php endif; ?>
							
							<?php if ((strtotime($item->publish_down) < strtotime(JFactory::getDate())) && $item->publish_down != '0000-00-00 00:00:00') : ?>
								<span class="label label-warning"><?php echo JText::_('JEXPIRED'); ?></span>
							<?php endif; ?>
							
							<?php if($item->private == 1):?>
								<span class="label label-success"><?php echo JText::_('COM_COMMUNITYPOLLS_LABEL_PRIVATE');?></span>
							<?php endif;?>
						</li>
						<?php endif;?>
						<li class="muted">
							<?php 
							if($profileComponent != 'none')
							{
								$profileLink = JHtml::link($profileUrl, $item->author);
								echo JText::sprintf('COM_COMMUNITYPOLLS_POLL_CREATED_BY', $profileLink);
							}
							else
							{
								echo JText::sprintf('COM_COMMUNITYPOLLS_POLL_CREATED_BY', $author);
							}
							?>
						</li>
						<?php if($params->get('list_show_parent', 1) == 1):?>
						<li class="muted">
							<?php echo JText::sprintf('COM_COMMUNITYPOLLS_CATEGORY_IN', JHtml::link(CommunityPollsHelperRoute::getCategoryRoute($item->catid, $item->language), $item->category_title));?>
						</li>
						<?php endif;?>
						
						<?php if(isset($item->displayDate)):?>
						<li class="muted">
							<?php echo CjLibDateUtils::getHumanReadableDate($item->displayDate);?>.
						</li>
						<?php endif;?>
						
						<li class="visible-phone visible-xs muted text-muted">
							<?php echo JText::plural('COM_COMMUNITYPOLLS_NUM_VOTES_TEXT', $item->votes);?>
						</li>
					</ul>
					
					<div class="margin-top-5 tags">
						<?php 
						if ($params->get('show_tags', 1) && !empty($item->tags)) 
						{
							$item->tagLayout = new JLayoutFile('joomla.content.tags');
							echo $item->tagLayout->render($item->tags->itemTags);
						}
						?>
					</div>
					<?php echo JHtml::_('string.truncate', CJFunctions::parse_html($item->description, false, $bbcode, false), $params->get('readmore_limit', 180)); ?>
				</div>
			</div>
		</li>
		<?php
	}
	?>
	</ul>
</div>
	
<?php if (!empty($items)) : ?>
	<?php if (($params->def('show_pagination', 2) == 1  || ($params->get('show_pagination') == 2)) && ($pagination->pagesTotal > 1)) : ?>
		<form action="<?php echo htmlspecialchars(JUri::getInstance()->toString()); ?>" method="post" name="adminForm" id="adminForm">
			<div class="pagination">
				<?php if ($params->def('show_pagination_results', 1)) : ?>
					<p class="counter pull-right">
						<?php echo $pagination->getPagesCounter(); ?>
					</p>
				<?php endif; ?>
		
				<?php echo $pagination->getPagesLinks(); ?>
			</div>
		</form>
	<?php endif; ?>
<?php  endif; ?>

<?php
}
else if($params->get('show_no_polls'))
{
	?>
	<div class="alert alert-info"><i class="fa fa-info-circle"></i> <?php echo JText::_('COM_COMMUNITYPOLLS_NO_RESULTS_FOUND')?></div>
	<?php 
}
?>