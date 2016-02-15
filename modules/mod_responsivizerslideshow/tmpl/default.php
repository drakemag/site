<?php 
/**
 * @author Joomla! Extensions Store
 * @package RESPONSIVIZER::MODULESLIDESHOW::modules::mod_responsivizerslideshow
 * @subpackage tmpl
 * @copyright (C) 2014 - Joomla! Extensions Store
 * @license GNU/GPLv2 http://www.gnu.org/licenses/gpl-2.0.html
 */
defined('_JEXEC') or die; 
?> 
<div id="responsivizer-<?php echo $module_id; ?>" class="responsivizer <?php if ( $slide_theme == 'false' ) : ?> <?php echo $theme_shadow ; ?> theme-border-<?php echo $theme_border ; ?> theme-border-radius-<?php echo $theme_border_radius ; ?> <?php else : ?>responsivizer-slide-theme-<?php echo $direction; ?><?php endif ; ?> <?php echo $directionNav ; ?> <?php if ($controlNav == 'true') : ?>position-nav-<?php echo $positionNav ; ?><?php endif ; ?> bg-caption-<?php echo $bg_caption ; ?>-<?php echo $transparency_caption ; ?> position-caption-<?php echo $position_caption ; ?>-<?php echo $text_align ; ?> color-nav-<?php echo $colorNav ; ?> color-nav-active-<?php echo $colorNavActive ; ?>" style="background-color : <?php echo $bg_color ; ?>;" >
  
  <ul class="slides" <?php if ( $enable_minheight ) : ?>style="min-height:<?php echo $min_height ; ?>px;"<?php endif ; ?>>
  	<?php
  		foreach($listofimages as $item){
  			echo $item; 
  		}
  	?> 
  </ul>
  
  <?php if ( $slide_theme != 'false' ) : ?>
  <span class="slide-theme">
  	<span class="slide-theme-side slide-top-left"></span>
  	<span class="slide-theme-side slide-top-right"></span>
  	<span class="slide-theme-side slide-bottom-left"></span>
  	<span class="slide-theme-side slide-bottom-right"></span>
  </span>
  <?php endif; ?>
  
</div>

<script type="text/javascript">
  jQuery(window).load(function() {
    jQuery('#responsivizer-<?php echo $module_id; ?>').responsivizer({
        animation: "<?php echo $transition; ?>",
        easing:"<?php echo $easing; ?>",
 		direction: "<?php echo $direction; ?>",      
		slideshowSpeed: <?php echo $pauseTime; ?>, 		 
		animationSpeed: <?php echo $animSpeed; ?>, 	 
    	directionNav: <?php if ($directionNav == 'false') { echo "false" ;} else { echo "true" ;} ?>,             
    	controlNav: <?php echo $controlNav ; ?>,    
    	pauseOnHover: <?php echo $pauseOnHover; ?>,
    	initDelay: <?php echo $initDelay; ?>,
    	randomize: <?php echo $randomize; ?>,
    	smoothHeight: false,
    	touch: true,
    	keyboardNav: true,
    	imagesAutoHeight: <?php echo $imagesAutoHeight; ?>
    }); 
  });
</script>