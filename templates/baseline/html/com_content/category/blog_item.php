<?php
/**
 * @package     Joomla.Site
 * @subpackage  Layout
 *
 * @copyright   Copyright (C) 2005 - 2014 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;

// Create a shortcut for params.
$params = $this->item->params;
JHtml::addIncludePath(JPATH_COMPONENT.'/helpers/html');
$canEdit = $this->item->params->get('access-edit');
$varID = $this->item->params->get('id');
$info    = $params->get('info_block_position', 0);
$pageclass = $params->get( 'pageclass_sfx' );
$var_layout_type = $this->item->params->get('layout_type');
?>
<?php
	$var_id = $this->item->catid;
?>

<?php if($pageclass == 'home_class'){  // HOME PAGE | PUT-IN ?>
	<div class="cotd_wrapper">
		<div class="cotd_overlay"></div>
		<div class="cotd_bkgd">
			<?php echo JLayoutHelper::render('joomla.content.intro_image', $this->item); ?>
		</div>
		<div class="cotd_content">
			<?php echo JLayoutHelper::render('joomla.content.blog_style_default_item_title', $this->item); ?>

			<?php if ($canEdit || $params->get('show_print_icon') || $params->get('show_email_icon')) : ?>
				<?php echo JLayoutHelper::render('joomla.content.icons', array('params' => $params, 'item' => $this->item, 'print' => false)); ?>
			<?php endif; ?>

			<?php if ($params->get('show_tags') && !empty($this->item->tags->itemTags)) : ?>
				<?php echo JLayoutHelper::render('joomla.content.tags', $this->item->tags->itemTags); ?>
			<?php endif; ?>

			<?php // Todo Not that elegant would be nice to group the params ?>
			<?php $useDefList = ($params->get('show_modify_date') || $params->get('show_publish_date') || $params->get('show_create_date')
				|| $params->get('show_hits') || $params->get('show_parent_category') || $params->get('show_author') ); ?>

			<?php if ($useDefList && ($info == 0 || $info == 2)) : ?>
				<?php echo JLayoutHelper::render('joomla.content.info_block.block', array('item' => $this->item, 'params' => $params, 'position' => 'above')); ?>
			<?php endif; ?>

			<div class="cotd_intro">
				<?php if (JLayoutHelper::render('joomla.content.intro_image', $this->item) != null){ ?>
					<div class="cotd_image">
						<?php echo JLayoutHelper::render('joomla.content.intro_image', $this->item); ?>
					</div>
				<?php }; ?>
				<?php if (!$params->get('show_intro')) : ?>
					<?php echo $this->item->event->afterDisplayTitle; ?>
				<?php endif; ?>
				<?php echo $this->item->event->beforeDisplayContent; ?> <?php echo $this->item->introtext; ?>
			</div>

			<div class="cotd_readmore">
				<?php if ($useDefList && ($info == 1 || $info == 2)) : ?>
					<?php echo JLayoutHelper::render('joomla.content.info_block.block', array('item' => $this->item, 'params' => $params, 'position' => 'below')); ?>
				<?php  endif; ?>
				<?php if ($params->get('show_readmore') && $this->item->readmore) :
					if ($params->get('access-view')) :
						$link = JRoute::_(ContentHelperRoute::getArticleRoute($this->item->slug, $this->item->catid));
					else :
						$menu = JFactory::getApplication()->getMenu();
						$active = $menu->getActive();
						$itemId = $active->id;
						$link1 = JRoute::_('index.php?option=com_users&view=login&Itemid=' . $itemId);
						$returnURL = JRoute::_(ContentHelperRoute::getArticleRoute($this->item->slug, $this->item->catid));
						$link = new JUri($link1);
						$link->setVar('return', base64_encode($returnURL));
					endif; ?>

					<?php echo JLayoutHelper::render('joomla.content.readmore', array('item' => $this->item, 'params' => $params, 'link' => $link)); ?>

				<?php endif; ?>
				
	<?php 
		if ($params->get('access-view')) :
			$link = JRoute::_(ContentHelperRoute::getArticleRoute($this->item->slug, $this->item->catid));
		else :
			$menu = JFactory::getApplication()->getMenu();
			$active = $menu->getActive();
			$itemId = $active->id;
			$link1 = JRoute::_('index.php?option=com_users&view=login&Itemid=' . $itemId);
			$returnURL = JRoute::_(ContentHelperRoute::getArticleRoute($this->item->slug, $this->item->catid));
			$link = new JUri($link1);
			$link->setVar('return', base64_encode($returnURL));
		endif; ?>

		<?php echo JLayoutHelper::render('joomla.content.readmore', array('item' => $this->item, 'params' => $params, 'link' => $link)); ?>

	<?php ?>
			
			</div>
			
			<?php if ($this->item->state == 0 || strtotime($this->item->publish_up) > strtotime(JFactory::getDate())
				|| ((strtotime($this->item->publish_down) < strtotime(JFactory::getDate())) && $this->item->publish_down != '0000-00-00 00:00:00' )) : ?>
			</div>
			<?php endif; ?>
		</div>

	
		</div>

<?php } elseif ($var_id =='348' || $var_id =='336' || $var_id =='330' || $var_id =='154' || $var_id =='91' || $var_id =='323' || $var_id =='46' || $var_id =='33' || $var_id =='22' || $var_id =='9') { ?>
<div class="video_cat_wrapper">
	<div class="video_image">
			<?php echo JLayoutHelper::render('joomla.content.intro_image', $this->item); ?>

    </div>
    <div class="video_details">
    	<div class="video_title">
        	<h2>
			<?php echo JLayoutHelper::render('joomla.content.blog_style_default_item_title', $this->item); ?>
            </h2>
        </div>
        <div class="video_author">
        	<?php if ($params->get('show_author') && !empty($this->item->author )) : ?>
                <?php $author =  $this->item->author; ?>
                <?php $author = ($this->item->created_by_alias ? $this->item->created_by_alias : $author);?>
        
                    <?php if (!empty($this->item->contactid ) &&  $params->get('link_author') == true):?>
                        <?php 	echo JText::sprintf('COM_CONTENT_WRITTEN_BY' ,
                         JHtml::_('link', JRoute::_('index.php?option=com_contact&view=contact&id='.$this->item->contactid), $author)); ?>
        
                    <?php else :?>
                        by&nbsp;<?php echo JText::sprintf($author); ?>
                    <?php endif; ?>
			<?php endif; ?>
        </div>
        <div class="video_watch">
        <!--SHOW NUMBER OF COMMENTS -->
		<?php
            $dbc=&JFactory::getDBO();
                $sql="select count(*) from #__jcomments where published= 1 and object_id='".$this->item->id."';";
                $run=$dbc->setQuery($sql);
                $count = $dbc->loadResult();
                //echo $sql;
                
                if(isset($count) && $count<1) {
                    echo '<a href="'.JRoute::_(ContentHelperRoute::getArticleRoute($this->item->slug, $this->item->catid)).'#comments" class="nb">Add a Comment </a>';
                } elseif ($count ==1){
                    echo '<a href="'.JRoute::_(ContentHelperRoute::getArticleRoute($this->item->slug, $this->item->catid)).'#comments" class="nb">'.$count.'&nbsp;&nbsp;Comment</a>';
                } else {
                    echo '<a href="'.JRoute::_(ContentHelperRoute::getArticleRoute($this->item->slug, $this->item->catid)).'#comments" class="nb">'.$count.'&nbsp;&nbsp;Comment(s)</a>';
                }
        ?> | <a href="<?php echo JRoute::_(ContentHelperRoute::getArticleRoute($this->item->slug, $this->item->catid)); ?>">Watch Video</a>

        </div>
    </div>
</div>


<?php } elseif($var_layout_type == 'blog'){  // BLOG LAYOUT ?>
	<?php echo JLayoutHelper::render('joomla.content.intro_image', $this->item); ?>
	<?php if ($this->item->state == 0 || strtotime($this->item->publish_up) > strtotime(JFactory::getDate())
		|| ((strtotime($this->item->publish_down) < strtotime(JFactory::getDate())) && $this->item->publish_down != '0000-00-00 00:00:00' )) : ?>
		<div class="system-unpublished">
	<?php endif; ?>

	<?php echo JLayoutHelper::render('joomla.content.blog_style_default_item_title', $this->item); ?>

	<?php if ($canEdit || $params->get('show_print_icon') || $params->get('show_email_icon')) : ?>
		<?php // echo JLayoutHelper::render('joomla.content.icons', array('params' => $params, 'item' => $this->item, 'print' => false)); ?>
	<?php endif; ?>

	<?php if ($params->get('show_tags') && !empty($this->item->tags->itemTags)) : ?>
		<?php echo JLayoutHelper::render('joomla.content.tags', $this->item->tags->itemTags); ?>
	<?php endif; ?>

	<?php // Todo Not that elegant would be nice to group the params ?>

	<?php $useDefList = ($params->get('show_modify_date') || $params->get('show_publish_date') || $params->get('show_create_date')
		|| $params->get('show_hits') || $params->get('show_category') || $params->get('show_parent_category') || $params->get('show_author') ); ?>
	    <!--div class="add_this">
	        <!-- AddThis Button BEGIN -->
	        
	        <!--div class="addthis_toolbox addthis_default_style ">
	    
	        <a class="addthis_button_compact"></a>
	        <a class="addthis_button_email"></a>
	        <a class="addthis_button_facebook"></a>
	        <a class="addthis_button_twitter"></a>
	        <script type="text/javascript" src="http://s7.addthis.com/js/250/addthis_widget.js#pubid=ra-507eb9477661d3f7"></script>
	        <!-- AddThis Button END -->
	        <!--/div>
	    </div-->
	<?php if ($useDefList && ($info == 0 || $info == 2)) : ?>
		<?php echo JLayoutHelper::render('joomla.content.info_block.block', array('item' => $this->item, 'params' => $params, 'position' => 'above')); ?>

	<?php endif; ?>



	<?php if (!$params->get('show_intro')) : ?>
		<?php echo $this->item->event->afterDisplayTitle; ?>
	<?php endif; ?>
	<?php echo $this->item->event->beforeDisplayContent; ?> <?php echo $this->item->introtext; ?>

	<?php if ($useDefList && ($info == 1 || $info == 2)) : ?>
		<?php echo JLayoutHelper::render('joomla.content.info_block.block', array('item' => $this->item, 'params' => $params, 'position' => 'below')); ?>
	<?php  endif; ?>

	<?php if ($params->get('show_readmore') && $this->item->readmore) :
		if ($params->get('access-view')) :
			$link = JRoute::_(ContentHelperRoute::getArticleRoute($this->item->slug, $this->item->catid));
		else :
			$menu = JFactory::getApplication()->getMenu();
			$active = $menu->getActive();
			$itemId = $active->id;
			$link1 = JRoute::_('index.php?option=com_users&view=login&Itemid=' . $itemId);
			$returnURL = JRoute::_(ContentHelperRoute::getArticleRoute($this->item->slug, $this->item->catid));
			$link = new JUri($link1);
			$link->setVar('return', base64_encode($returnURL));
		endif; ?>

		<?php echo JLayoutHelper::render('joomla.content.readmore', array('item' => $this->item, 'params' => $params, 'link' => $link)); ?>

	<?php endif; ?>

	<?php if ($this->item->state == 0 || strtotime($this->item->publish_up) > strtotime(JFactory::getDate())
		|| ((strtotime($this->item->publish_down) < strtotime(JFactory::getDate())) && $this->item->publish_down != '0000-00-00 00:00:00' )) : ?>
	</div>
	<?php endif; ?>

	<?php echo $this->item->event->afterDisplayContent; ?>



<?php } else { ?>	
	<?php if ($this->item->state == 0 || strtotime($this->item->publish_up) > strtotime(JFactory::getDate())
		|| ((strtotime($this->item->publish_down) < strtotime(JFactory::getDate())) && $this->item->publish_down != '0000-00-00 00:00:00' )) : ?>
		<div class="system-unpublished">
	<?php endif; ?>

	<?php echo JLayoutHelper::render('joomla.content.blog_style_default_item_title', $this->item); ?>

	<?php if ($canEdit || $params->get('show_print_icon') || $params->get('show_email_icon')) : ?>
		<?php echo JLayoutHelper::render('joomla.content.icons', array('params' => $params, 'item' => $this->item, 'print' => false)); ?>
	<?php endif; ?>

	<?php if ($params->get('show_tags') && !empty($this->item->tags->itemTags)) : ?>
		<?php echo JLayoutHelper::render('joomla.content.tags', $this->item->tags->itemTags); ?>
	<?php endif; ?>

	<?php // Todo Not that elegant would be nice to group the params ?>
	<?php $useDefList = ($params->get('show_modify_date') || $params->get('show_publish_date') || $params->get('show_create_date')
		|| $params->get('show_hits') || $params->get('show_category') || $params->get('show_parent_category') || $params->get('show_author') ); ?>

	<?php if ($useDefList && ($info == 0 || $info == 2)) : ?>
		<?php echo JLayoutHelper::render('joomla.content.info_block.block', array('item' => $this->item, 'params' => $params, 'position' => 'above')); ?>
	<?php endif; ?>

	<?php echo JLayoutHelper::render('joomla.content.intro_image', $this->item); ?>



	<?php if (!$params->get('show_intro')) : ?>
		<?php echo $this->item->event->afterDisplayTitle; ?>
	<?php endif; ?>
	<?php echo $this->item->event->beforeDisplayContent; ?> <?php echo $this->item->introtext; ?>

	<?php if ($useDefList && ($info == 1 || $info == 2)) : ?>
		<?php echo JLayoutHelper::render('joomla.content.info_block.block', array('item' => $this->item, 'params' => $params, 'position' => 'below')); ?>
	<?php  endif; ?>

	<?php if ($params->get('show_readmore') && $this->item->readmore) :
		if ($params->get('access-view')) :
			$link = JRoute::_(ContentHelperRoute::getArticleRoute($this->item->slug, $this->item->catid));
		else :
			$menu = JFactory::getApplication()->getMenu();
			$active = $menu->getActive();
			$itemId = $active->id;
			$link1 = JRoute::_('index.php?option=com_users&view=login&Itemid=' . $itemId);
			$returnURL = JRoute::_(ContentHelperRoute::getArticleRoute($this->item->slug, $this->item->catid));
			$link = new JUri($link1);
			$link->setVar('return', base64_encode($returnURL));
		endif; ?>

		<?php echo JLayoutHelper::render('joomla.content.readmore', array('item' => $this->item, 'params' => $params, 'link' => $link)); ?>

	<?php endif; ?>

	<?php if ($this->item->state == 0 || strtotime($this->item->publish_up) > strtotime(JFactory::getDate())
		|| ((strtotime($this->item->publish_down) < strtotime(JFactory::getDate())) && $this->item->publish_down != '0000-00-00 00:00:00' )) : ?>
	</div>
	<?php endif; ?>

	<?php echo $this->item->event->afterDisplayContent; ?>
<?php }; ?>

	
