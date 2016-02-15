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

$doc = JFactory::getDocument();
$doc->addScript('modules/mod_globalnews/scripts/scroller.js');
?>

<script type="text/javascript" language="javascript">
<!--
var GN_Pausecontent_<?php echo $globalnews_id.'_'.$j; ?>=new Array();

<?php  $k=0;  foreach ($list as $item) : 
${'content'.$k} = $item->content;
${'content'.$k} = preg_replace( "/[\n\t\r]+/",' ',${'content'.$k} );
${'content'.$k} = str_replace( "'", "\\'",${'content'.$k} ); ?>
GN_Pausecontent_<?php echo $globalnews_id.'_'.$j; ?>[<?php echo $k; ?>]='<?php echo ${'content'.$k}; ?>';
<?php  $k++;  endforeach; ?>

new GN_Pausescroller(GN_Pausecontent_<?php echo $globalnews_id.'_'.$j; ?>, "gn_scroller_<?php echo $globalnews_id.'_'.$j; ?>", "", <?php echo $params->get('delay', 3000) ?>);
-->
</script>
<?php
if ( $more == 1 && $group->link ) : ?>
<div> <?php echo JHTML::_('link', $group->link, JText::_('MOD_GLOBALNEWS_MORE_ARTICLES'), array('class'=>'readon') ); ?> </div>
<?php
endif;