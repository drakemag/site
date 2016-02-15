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

$linkMore = '';
$linkNext = '';
if ( $more == 1 && $group->link ) {
	$linkMore = '<a href=\"'.$group->link.'\">'.JText::_('MOD_GLOBALNEWS_MORE_ARTICLES').'</a>';
}
if ( $params->get( 'next', 1 ) == 1 ) {
 	$linkNext = JText::_('Next');
}
?>

<div id="gn_slider_<?php echo $globalnews_id.'_'.$j; ?>" class="gn_slider gn_slider_<?php echo $globalnews_id; ?>">
  <div class="gn_opacitylayer">
  <?php foreach ($list as $item) : ?>
    <div class="gn_news" style="display:none;">
	<?php echo $item->content; ?>
	</div>
  <?php endforeach; ?>
  </div>
</div>
<div class="gn_pagination gn_pagination_<?php echo $globalnews_id; ?>" id="paginate-gn_slider_<?php echo $globalnews_id.'_'.$j; ?>"></div>
<?php

$doc = JFactory::getDocument();

$doc->addScript('modules/mod_globalnews/scripts/slider.js');
$doc->addScriptDeclaration("
window.addEventListener('load', function(){
    GN_ContentSlider('gn_slider_".$globalnews_id."_".$j."', ".$params->get('delay',3000).", '".$linkNext."', '".$linkMore."');
}, false);");