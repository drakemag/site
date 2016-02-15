<?php
/*------------------------------------------------------------------------
# mod_globalnews - Global News Module
# ------------------------------------------------------------------------
# author    Jesús Vargas Garita
# copyright Copyright (C) 2010 joomlahill.com. All Rights Reserved.
# @license - http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
# Websites: http://www.joomlahill.com
# Technical Support:  Forum - http://www.joomlahill.com/forum
-------------------------------------------------------------------------*/

// no direct access
defined('_JEXEC') or die;

foreach ($list as $item) :  ?>

<div class="gn_static gn_static_<?php echo $globalnews_id; ?>">
	<?php echo $item->content; ?>
</div>
<?php 
endforeach; ?>
<?php
if ( $more == 1 && $group->link ) : ?>
<div> <?php echo JHTML::_('link', $group->link, JText::_('MOD_GLOBALNEWS_MORE_ARTICLES'), array('class'=>'readon') ); ?> </div>
<?php
endif;