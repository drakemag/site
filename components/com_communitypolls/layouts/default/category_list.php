<?php
/**
 * @package     corejoomla.administrator
 * @subpackage  com_communitypolls
 *
 * @copyright   Copyright (C) 2009 - 2015 corejoomla.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */
defined('JPATH_BASE') or die;

$language   = JFactory::getLanguage()->getTag();
$params     = $displayData['params'];
$item       = $displayData['category'];
$maxlevel   = $displayData['maxlevel'];
$showParent = $displayData['maxlevel'];
$user       = JFactory::getUser();
$access     = $user->getAuthorisedViewLevels();
$theme      = $params->get('theme', 'default');
$columns    = 3;
$colspan    = 12 / $columns;
$catParams  = $item->getParams();
$catImage   = $catParams->get('image');

if($params->get('show_parent'))
{
    ?>
    <div class="media">
    	<?php 
    	$categoryUrl = CommunityPollsHelperRoute::getCategoryRoute($item);
    	if($params->get('show_description_image', 1) == 1 && !empty($catImage))
    	{
    		?>
    		<div class="media-left hidden-phone">
    			<a href="#" class="thumbnail" onclick="return false;">
    				<img src="<?php echo $catParams->get('image');?>" alt="<?php echo $this->escape($catParams->get('image_alt'));?>" class="media-object" style="max-width: 128px;">
    			</a>
    		</div>
    		<?php 
    	}
    	?>
    	<div class="media-body">
            <?php 
            if($item->parent_id)
            {
                if($maxlevel == 1)
                {
                    ?>
                    <a href="<?php echo JRoute::_($categoryUrl);?>"><?php echo $this->escape($item->title);?></a>
            		<a href="<?php echo JRoute::_($categoryUrl.'&format=feed&type=rss');?>"  title="<?php echo JText::_('COM_COMMUNITYPOLLS_RSS_FEED');?>" data-toggle="tooltip">
                        <sup class="margin-left-5"><small><i class="fa fa-rss-square"></i></small></sup>
                    </a>
                	<?php 
                }
                else 
                {
                    ?>
                    <h4 class="panel-title margin-bottom-5">
                        <a href="<?php echo JRoute::_($categoryUrl);?>"><?php echo $this->escape($item->title);?></a>
                		<a href="<?php echo JRoute::_($categoryUrl.'&format=feed&type=rss');?>" title="<?php echo JText::_('COM_COMMUNITYPOLLS_RSS_FEED');?>" data-toggle="tooltip">
                			<sup class="margin-left-5"><small><i class="fa fa-rss-square"></i></small></sup>
                		</a>
            		</h4>
                    <?php
                }
            }
            
    		if(!empty($item->description) && $params->get('show_description'))
    		{
    			echo JHtml::_('content.prepare', $item->description, '', 'com_communitypolls.categories');
    		}
    		?>
    	</div>
    </div>
    <?php
}

if ($maxlevel != 0 && count($item->getChildren()) > 0) 
{
	$categories = $item->getChildren();
	$itemNum = 0;
	$categoryCount = 0;

	foreach ($categories as $node)
	{
		$categoryCount++;
		if($itemNum % $columns == 0)
		{
			?>
			<div class="row-fluid">
			<?php
		}
		
		if(in_array($node->access, $access) && $user->authorise('core.view', 'com_communitypolls.category.'.$node->id) && in_array($node->language, array($language, '*')))
		{
			$categoryUrl = CommunityPollsHelperRoute::getCategoryRoute($node);
			?>
			<div class="span<?php echo $colspan?>">
				<a href="<?php echo JRoute::_($categoryUrl);?>">
					<?php echo $this->escape($node->title);?>
					<span class="muted visible-phone visible-xs">(<?php echo JText::plural('COM_COMMUNITYPOLLS_NUM_QUESTIONS', $node->numitems);?>)</span>
				</a>
				<a href="<?php echo JRoute::_($categoryUrl.'&format=feed&type=rss');?>" title="<?php echo JText::_('COM_COMMUNITYPOLLS_RSS_FEED');?>" data-toggle="tooltip">
					<sup class="margin-left-5"><small><i class="fa fa-rss-square"></i></small></sup>
				</a>
				<?php if(count($node->getChildren()) > 0):?>
				<a href="#" onclick="return false;" data-toggle="tooltip" title="<?php echo JText::_('COM_COMMUNITYPOLLS_THIS_CATEGORY_HAS_SUB_CATEGORIES')?>">
					<sup><i class="fa fa-folder-open-o"></i></sup>
				</a>
				<?php endif;?>
				<small class="text-muted">(<?php echo $node->numitems;?>)</small>
			</div>
			<?php
			$itemNum++;
		}

		if($categoryCount == count($categories) || $itemNum % $columns == 0)
		{
			?>
			</div>
			<?php
		}
	}
}