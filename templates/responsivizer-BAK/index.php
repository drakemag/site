<?php 
/**
 * @author Joomla! Extensions Store
 * @package RESPONSIVIZER::TEMPLATEDISPLAY::templates::responsivizer
 * @copyright (C) 2013 - Joomla! Extensions Store
 * @license GNU/GPLv2 http://www.gnu.org/licenses/gpl-2.0.html
 */
defined('_JEXEC') or die('Restricted access');
include 'framework/bootstrap.php';
?>

<!doctype html>
<html <?php echo $this->offlineCache->injectCacheManifest($DBO, $this->params);?> lang="<?php echo $this->language; ?>" dir="<?php echo $this->direction; ?>" >
<head>
<meta name="viewport" content="width=device-width, initial-scale=1" />
<jdoc:include type="head" />
<?php if($this->params->get('home_icon_enabled', 0) && $this->params->get('home_icon', null)):?>
<link rel="shortcut icon" href="<?php echo JURI::root () . $this->params->get('home_icon', null);?>" />  
<link rel="apple-touch-icon" href="<?php echo JURI::root () . $this->params->get('home_icon', null);?>"/>
<?php endif;?>
<?php if($this->params->get('native_app_feature', 0)):?>
<meta name="apple-mobile-web-app-capable" content="yes">
<meta name="mobile-web-app-capable" content="yes">
<?php endif;?>
</head>
<body <?php echo ('style="font-size:'.$fontSize.';"');?> class="<?php echo $general_background ; ?>">
  <div class="site">
	<?php if($this->modulesManager->countModules('responsivizer_menu_top')) : ?>
        <nav class="top_menu">
          <?php $this->modulesManager->injectModulesTags('responsivizer_menu_top');?>
          	  <?php if($this->params->get('include_search_field', 1) && !$app->input->get('tp', null)):?>	
		          <div id="top_search">
		            <?php echo JModuleHelper::renderModule(JModuleHelper::getModule('search'));?>
		          </div>
		      <?php endif;?>
        </nav>  
	<?php endif; ?>

	<?php if($this->modulesManager->countModules('responsivizer_menu_side')) : ?>
        <nav class="side_menu">
          <div id="sidemenu_trigger_container" data-role="trigger"><div id="sidemenu_trigger" data-role="trigger"></div></div>
          <?php $this->modulesManager->injectModulesTags('responsivizer_menu_side');?>
        </nav>  
	<?php endif; ?>

    <div class="wrapper-site">
      <header class="header-site">
        <?php if($this->modulesManager->countModules('responsivizer_language_switcher')) : ?>
        <div id="translate">
          <?php $this->modulesManager->injectModulesTags('responsivizer_language_switcher');?>
        </div>  
        <?php endif; ?>
        
        <?php if($this->modulesManager->countModules('responsivizer_logo')) : ?>
        <div class="logo" >
          <?php $this->modulesManager->injectModulesTags('responsivizer_logo');?>
        </div>
        <?php endif; ?>
        <div class="clr"></div>
        
        <?php if($this->params->get('explicit_logo', null) && $this->params->get('explicit_logo_include', 0)) : ?>
        <div class="logo">
			<a href="<?php echo JUri::root();?>">
	        	<img src="<?php echo JURI::root() . $this->params->get('explicit_logo')?>" alt="logo"/>
	        </a>
        </div>
        <?php endif; ?>
        <div class="clr"></div>
        
        <?php if ($this->modulesManager->countModules( 'responsivizer_search' ) && 
        		  !($this->modulesManager->countModules('responsivizer_menu_top') &&
        		    $this->params->get('include_search_field', 1))) : ?>
          <div id="search">
            <?php $this->modulesManager->injectModulesTags('responsivizer_search');?>
          </div>  
        <?php endif; ?>
        <div class="clr"></div>
        
 	    <?php if($this->modulesManager->countModules('responsivizer_slideshow') && $responsivizerSlideshowEnabled) : ?>
   	    <div class="slideshow" <?php echo ('style="width:'.$slideshow_width.'%;"');?>>
     	  <?php $this->modulesManager->injectModulesTags('responsivizer_slideshow');?>
        </div>
	    <?php endif; ?>
    	<div class="clr"></div>
      </header>
      
      
      <aside class="main_menu_box">
        <div>
          <?php if($this->modulesManager->countModules('responsivizer_mainmenu')) : ?>
          <nav class="main_menu">
            <?php $this->modulesManager->injectModulesTags('responsivizer_mainmenu');?>
          </nav>  
          <?php endif; ?>
          
          <div class="clr"></div>
        </div>  
      </aside> 
   
      <div class="middle-site" style="min-height : <?php echo $min_height ; ?>px;"> 
        <?php if($this->modulesManager->countModules('responsivizer_breadcrumb')) : ?>
        <nav class="breadcrumb">
          <?php $this->modulesManager->injectModulesTags('responsivizer_breadcrumb');?>
        </nav>  
        <?php endif; ?>
             
        <?php if($this->modulesManager->countModules('responsivizer_top')) : ?>
        <div class="top" >
          <?php $this->modulesManager->injectModulesTags('responsivizer_top');?>
        </div>
        <div class="clr"></div>
        <?php endif; ?>  
         
        <?php if($this->modulesManager->countModules('responsivizer_left')) : ?>
        <div class="left_column" >
          <?php $this->modulesManager->injectModulesTags('responsivizer_left');?>
        </div>
        <?php endif; ?>  
         
        <!--  RIGHT COLUMN -->
        <div class="right_column">          
          <!--  Module positions User 1, 2, 3 -->
          <?php if($this->modulesManager->countModules('responsivizer_user1') || $this->modulesManager->countModules('responsivizer_user2') || $this->modulesManager->countModules('responsivizer_user3')) : ?>
          <aside class="users_top">
            <?php if($this->modulesManager->countModules('responsivizer_user1')) : ?>
            <div class="user1" <?php echo ('style="width:'.$user1_width.'%;"');?>>
              <?php $this->modulesManager->injectModulesTags('responsivizer_user1');?>
            </div>
            <?php endif; ?>
                      
            <?php if($this->modulesManager->countModules('responsivizer_user3')) : ?>
            <div class="user3" <?php echo ('style="width:'.$user3_width.'%;"');?>>
              <?php $this->modulesManager->injectModulesTags('responsivizer_user3');?>
            </div>
            <?php endif; ?>
          
            <?php if($this->modulesManager->countModules('responsivizer_user2')) : ?>
            <div class="user2" <?php echo ('style="width:'.$user2_width.'%;"');?>>
              <?php $this->modulesManager->injectModulesTags('responsivizer_user2');?>
            </div>
            <?php endif; ?>       
          </aside>
          <?php endif; ?> 
             
          <div class="clr"></div>
          <div id="main_component" >
		  	<div class="main-content"> 
              <!--  Output main component and message -->
              <?php if($this->params->get('show_messages', null)): ?>
              	<jdoc:include type="message" />
              <?php endif; ?>
              <jdoc:include type="component" />
            </div>
            <div class="clr"></div>
		  </div>
            
		  <?php if($this->modulesManager->countModules('responsivizer_right')) : ?>
		    <aside class="right-module-position" >
              <?php $this->modulesManager->injectModulesTags('responsivizer_right');?>
            </aside>
		  <?php endif; ?>        
            
          <!--  Module positions User 4, 5, 6 -->
          <?php if($this->modulesManager->countModules('responsivizer_user4') || $this->modulesManager->countModules('responsivizer_user5') || $this->modulesManager->countModules('responsivizer_user6')) : ?>
          <aside class="users_bottom">
            <?php if($this->modulesManager->countModules('responsivizer_user4')) : ?>
            <div class="user4" <?php echo ('style="width:'.$user4_width.'%;"');?>>
              <?php $this->modulesManager->injectModulesTags('responsivizer_user4');?>
            </div>
            <?php endif; ?>
                      
            <?php if($this->modulesManager->countModules('responsivizer_user6')) : ?>
            <div class="user6" <?php echo ('style="width:'.$user6_width.'%;"');?>>
              <?php $this->modulesManager->injectModulesTags('responsivizer_user6');?>
            </div>
            <?php endif; ?>
          
            <?php if($this->modulesManager->countModules('responsivizer_user5')) : ?>
            <div class="user5" <?php echo ('style="width:'.$user5_width.'%;"');?>>
              <?php $this->modulesManager->injectModulesTags('responsivizer_user5');?>
            </div>
            <?php endif; ?>
            <div class="clr"></div>
          </aside>
          <?php endif; ?> 
          <div class="clr"></div>
        </div> 
           
        <div class="clr"></div>
        <?php if($this->modulesManager->countModules('responsivizer_bottom')) : ?>
        <div class="bottom" >
          <?php $this->modulesManager->injectModulesTags('responsivizer_bottom');?>
        </div>
        <?php endif; ?>
      </div> 
    
	  <footer class="bottom-site">
	  	<!--  bottom nav  -->
        <?php if ($this->modulesManager->countModules( 'responsivizer_menu_bottom' )) : ?>
        <nav class="bottom_menu">
          <?php $this->modulesManager->injectModulesTags('responsivizer_menu_bottom');?>
        </nav>
        <?php endif; ?>
        
        <?php if($enable_socials) : ?>
        <div id="social-links">
          <ul> 
            <?php if ($text_social_icons != '') : ?>
            	<li class="text_social_icons"><span><?php echo $text_social_icons ; ?></span></li>
            <?php endif; ?>  
            <?php 
            	foreach ($socials as $socialPlatform):
            		if ($this->params->get($socialPlatform, null)) : ?>
  						<li>
  							<a target="_blank" id="<?php echo $socialPlatform;?>" title="<?php echo $socialPlatform;?>" href="<?php echo $this->params->get($socialPlatform);?>">
  								<img src="<?php echo $this->baseurl ?>/templates/<?php echo $this->template ?>/images/social-icons/<?php echo $socialPlatform;?>.png" alt="" />
  							</a>
  						</li>
					<?php endif;
            	endforeach;
            ?>
          </ul>
        </div> 
        <?php endif; ?>
        
        <!--  Copyright footer  -->
        <?php if($this->modulesManager->countModules('responsivizer_copyright')) : ?>
        <div class="copyright">
    		<?php $this->modulesManager->injectModulesTags('responsivizer_copyright');?>
        </div>
        <?php endif; ?>
        
        <?php if($copyright = $this->params->get('copyright_footer_html', null)): ?>
        <div class="copyright">
        	<div class="module nobg">
		        <?php echo $copyright;?>
        	</div>
        </div>
        <?php endif; ?>
        
        <div class="clr"></div>
        
        <!-- tel phone number tag -->
        <?php if ($telNumber) : ?>
        	<div class="bottom_tel_number">
        		<a href="tel:<?php echo $telNumber;?>">
	        		<i class="fa fa-phone-square"></i>
        			<?php echo $telNumber;?>
        		</a>
	        </div>
        <?php endif; ?>
        
		<!--  bottom switch tpl  -->
        <?php if ($showTplSwitcher && !$switchAlways) : ?>
        <div class="bottom_switcher">
           <ul>
             <li>
             	<i class="fa fa-desktop"></i>
             	<a href="<?php echo $queryStringToDesktop;?>"><?php echo JText::_('TPL_RESPONSIVIZER_DESKTOP_VIEW');?></a>
             </li>
           </ul>
        </div>
        <?php endif; ?>
        
        <div class="clr"></div> 
      </footer>        
    </div>       
  </div>             
  <!--  bottom nav  -->
  <?php 
  	if ($this->modulesManager->countModules( 'responsivizer_debug' )) :
		$this->modulesManager->injectModulesTags('responsivizer_debug');
  	endif; 
  ?>
  
  <?php if($injectGajs):?>
	  <script type="text/javascript">
	  var _gaq = _gaq || [];
	  _gaq.push(['_setAccount', '<?php echo $gajsCode;?>']);
	  _gaq.push(['_trackPageview']);
	  (function() {var ga = document.createElement('script'); ga.type = 'text/javascript'; ga.async = true; ga.src = ('https:' == document.location.protocol ? 'https://ssl' : 'http://www') + '.google-analytics.com/ga.js'; var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(ga, s); })();
	  </script>
  <?php endif; ?>
</body>
</html>