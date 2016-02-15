<?php defined( '_JEXEC' ) or die( 'Restricted access' );?>
 <?php
 ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" 
    xml:lang="<?php echo $this->language; ?>" lang="<?php echo $this->language; ?>" >
 <head>
 <jdoc:include type="head" />
 	<link href='http://fonts.googleapis.com/css?family=Crushed' rel='stylesheet' type='text/css'>
 	<link href='http://fonts.googleapis.com/css?family=Libre+Baskerville:400,400italic,700|Lusitana:400,700|Crushed' rel='stylesheet' type='text/css'>
 	<link rel="stylesheet" href="<?php echo $this->baseurl ?>/templates/<?php echo $this->template; ?>/css/style.css" type="text/css" />
 	<!--[if IE 6]><link href="/templates/boilerplate/css/ie6_fix.css" rel="stylesheet" type="text/css" /><![endif]-->
 	<!--[if IE 7]><link href="/templates/boilerplate/css/ie7_fix.css" rel="stylesheet" type="text/css" /><![endif]-->
 	<!--[if IE 8]><link href="/templates/boilerplate/css/ie8_fix.css" rel="stylesheet" type="text/css" /><![endif]-->
 	<!--[if lt IE 7]>
 		<?php
 			include("templates/baseline/js/png_fixer.js");
 		?>
 	<![endif]-->
 	<link href="/templates/boilerplate/favicon.ico" rel="shortcut icon" type="image/x-icon" /> 
 	<link href="http://feeds.feedburner.com/Drakemag" type="application/rss+xml" rel="alternate" title="WEBISTE BLOG TITLE" />
 	<link rel="image_src" href="http://www.drakemag.com/images/logo_retina_with_mayfly-black.png" /> 
	<meta name="viewport" content="width=device-width" />

<script>
  (function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
  (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
  m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
  })(window,document,'script','//www.google-analytics.com/analytics.js','ga');

  ga('create', 'UA-1512028-4', 'auto');
  ga('send', 'pageview');

</script>

	<!-- Quantcast Tag, part 1 -->
	<script type="text/javascript">
	var _qevents = _qevents || [];
	(function() {
	var elem = document.createElement('script');
	elem.src = (document.location.protocol == "https:" ? "https://secure" : "http://edge") + ".quantserve.com/quant.js";
	elem.async = true;
	elem.type = "text/javascript";
	var scpt = document.getElementsByTagName('script')[0];
	scpt.parentNode.insertBefore(elem, scpt);
	})();
	</script>

</head>
 <?php
 	header("Expires: Sat, 26 Jul 1997 05:00:00 GMT"); // Date in the past
 ?>
 <!--
 GOOGLE ANALYTICS SCRIPT GOES HERE
 -->
 <?php
 	require_once("adserver/includes/functions.php");
 	require_once("templates/baseline/functions.php");
 	//$app = JFactory::getApplication('site');
 	$itemid = JRequest::getVar('Itemid');
 	$menu = &JSite::getMenu();
 	$active = $menu->getItem($itemid);
 	//print_r($testing);
 	$params = $menu->getParams( $active->id);
 	//this works as well
 	//$pageclass = $params->get('pageclass_sfx');
 	
 	$app = JFactory::getApplication();
 	$option = $app->input->get('option');
 	$menu = $app->getMenu()->getActive();
 	$var_category_id = JRequest::getVar('id');
 	$var_item_id = JRequest::getVar('id');
 	$var_parent_id = JRequest::getVar('parent_id');
 	//echo $var_category_id . '<hr />' . $var_item_id . '<hr />' . $var_parent_id . '<hr />';
 	//this shows category id correctly in category view page (at least. not tested further yet) echo $this->category->id
 	//this probably works as well - echo $this->category->description;
 	//these work:
 	$this_parent_id = $this->item->parent_id;
 	//$this_item_author = $this->item->created_by;
 	//check homepage
 	if(JURI::root()!=JURI::current()) {
 	//not mainpage
 		$is_home_page = 0;
 		//echo 'back page';
 		$pageclass = 'not_home_page';
 	}else{
 		$is_home_page = 1;
 		//echo 'homepage';
 	};
 	if (is_object($menu)){
 		// this gets all the parameter vars
 		//echo $params . '<br />';
 		$var_layout_type = $menu->params->get('layout_type');
 		$var_num_intro_articles = $menu->params->get('num_intro_articles');
 		$var_menu_anchor_title = $menu->params->get('menu-anchor_title');
 		$var_page_title = $menu->params->get('page_title');
 		$var_page_heading = $menu->params->get('page_heading');
 		$var_pageclass_sfx = $menu->params->get('pageclass_sfx');
 		$var_menu_meta_description = $menu->params->get('menu-meta_description');
 		$var_menu_meta_keywords = $menu->params->get('menu-meta_keywords');
 		$var_option = $menu->params->get('option');
 		$var_view = $menu->params->get('view');
 		$var_layout = $menu->params->get('layout');
 		$var_id = $menu->params->get('id');
 			$var_show_category_heading_title_text = $menu->params->get('show_category_heading_title_text');
 			$var_show_category_title = $menu->params->get('show_category_title');
 			$var_show_description = $menu->params->get('show_description');
 			$var_show_description_image = $menu->params->get('show_description_image');
 			$var_maxLevel = $menu->params->get('maxLevel');
 			$var_show_empty_categories = $menu->params->get('show_empty_categories');
 			$var_show_no_articles = $menu->params->get('show_no_articles');
 			$var_show_subcat_desc = $menu->params->get('show_subcat_desc');
 			$var_show_cat_num_articles = $menu->params->get('show_cat_num_articles');
 			$var_show_cat_tags = $menu->params->get('show_cat_tags');
 			$var_page_subheading = $menu->params->get('page_subheading');
 			$var_num_leading_articles = $menu->params->get('num_leading_articles');
 			$var_num_intro_articles = $menu->params->get('num_intro_articles');
 			$var_num_columns = $menu->params->get('num_columns');
 			$var_num_links = $menu->params->get('num_links');
 			$var_multi_column_order = $menu->params->get('multi_column_order');
 			$var_show_subcategory_content = $menu->params->get('show_subcategory_content');
 			$var_orderby_pri = $menu->params->get('orderby_pri');
 			$var_orderby_sec = $menu->params->get('orderby_sec');
 			$var_order_date = $menu->params->get('order_date');
 			$var_show_pagination = $menu->params->get('show_pagination');
 			$var_show_pagination_results = $menu->params->get('show_pagination_results');
 			$var_show_title = $menu->params->get('show_title');
 			$var_link_titles = $menu->params->get('link_titles');
 			$var_show_intro = $menu->params->get('show_intro');
 			$var_info_block_position = $menu->params->get('info_block_position');
 			$var_show_category = $menu->params->get('show_category');
 			$var_link_category = $menu->params->get('link_category');
 			$var_show_parent_category = $menu->params->get('show_parent_category');
 			$var_link_parent_category = $menu->params->get('link_parent_category');
 			$var_show_author = $menu->params->get('show_author');
 			$var_link_author = $menu->params->get('link_author');
 			$var_show_create_date = $menu->params->get('show_create_date');
 			$var_show_modify_date = $menu->params->get('show_modify_date');
 			$var_show_publish_date = $menu->params->get('show_publish_date');
 			$var_show_item_navigation = $menu->params->get('show_item_navigation');
 			$var_show_vote = $menu->params->get('show_vote');
 			$var_show_readmore = $menu->params->get('show_readmore');
 			$var_show_readmore_title = $menu->params->get('show_readmore_title');
 			$var_show_icons = $menu->params->get('show_icons');
 			$var_show_print_icon = $menu->params->get('show_print_icon');
 			$var_show_email_icon = $menu->params->get('show_email_icon');
 			$var_show_hits = $menu->params->get('show_hits');
 			$var_show_noauth = $menu->params->get('show_noauth');
 			$var_show_feed_link = $menu->params->get('show_feed_link');
 			$var_feed_summary = $menu->params->get('feed_summary');
 			$var_menu_anchor_title = $menu->params->get('menu-anchor_title');
 			$var_menu_anchor_css = $menu->params->get('menu-anchor_css');
 			$var_menu_image = $menu->params->get('menu_image');
 			$var_menu_text = $menu->params->get('menu_text');
 			$var_page_title = $menu->params->get('page_title');
 			$var_show_page_heading = $menu->params->get('show_page_heading');
 			$var_page_heading = $menu->params->get('page_heading');
 			$var_pageclass_sfx = $menu->params->get('pageclass_sfx');
 			$var_menu_meta_description = $menu->params->get('menu-meta_description');
 			$var_menu_meta_keywords = $menu->params->get('menu-meta_keywords');
 			$var_robots = $menu->params->get('robots');		
 			$var_secure = $menu->params->get('secure');	
 	};
 ?>
<body id="<?php echo $pageclass; ?>">
 <jdoc:include type="modules" name="breadcrumbs" title="Breadcrumbs" style="xhtml" />
 <div id="wrapper">
 	<div id="dev_vars"><?php //include("templates/baseline/includes/dev_vars.php") ?></div>
 	<div id="header">
 		<div id="logo"><a href="/"><img src="templates/<?php echo $this->template; ?>/images/logo_lg.png" alt="Drake Magazine - fly fishing" /></a></div>
 		<div id="header_top">
 			<div id="social"><jdoc:include type="modules" name="social" style="xhtml" /></div>
 			<div id="menu_top"><jdoc:include type="modules" name="menu_top" style="xhtml" /></div>
 		</div>
 		<div id="menu_main">
 			<jdoc:include type="modules" name="menu_main" style="xhtml" />
 		</div>
 	</div>
 		<?php
			 $menu2 = &JSite::getMenu();
			 $active = $menu2->getActive();
			 $menuname = $active->title;
			 $menu_parentId = $active->tree[0];
			 $parentName = $menu2->getItem($menu_parentId)->title;
			 //echo $menu_parentId;		
 		?>
 		<?php if($is_home_page == 1){ ?>
 			<div id="promo">
 				<?php include("templates/baseline/includes/promo.php") ?>
 			</div>
 			<div id ="hp_content">
 				<jdoc:include type="component" />
 				<div class="next"><span><a href="/index.php/featured-content?start=6">Next</a></span></div>
 			</div>
 			<div id="hp_right">
				<?php if ($this->countModules( 'sub_promo' )) : ?>
					<div id="hp_sub_promo">
						<jdoc:include type="modules" name="sub_promo" style="xhtml" />
					</div>
 				<?php endif; ?>
 				<div id="hp_ads">
 					<div id="hp_ads_left">
 						<?php 
 							$no_of_ads = '4';
 							$ad_layout = 'vert';
 							$pos = 'rect2';
 							$advert_size = '1';						    
 							advert_func($no_of_ads,$ad_layout,$pos,$advert_size);
 						?>
 					</div>
 					<div id="hp_ads_right">
 						<?php 
 							$no_of_ads = '2';
 							$ad_layout = 'vert';
 							$pos = 'sky2';
 							$advert_size = '2';
 							advert_func($no_of_ads,$ad_layout,$pos,$advert_size);
 						?>
 					</div>
 				</div>
 			</div>
 		<?php }else{ ?>
 			<div id="content">
 				<jdoc:include type="modules" name="breadcrumb-trail" style="xhtml" />
 				<jdoc:include type="component" />
 			</div>
 			<div id="right">
 				<?php // include(""); ?> 
 				<jdoc:include type="modules" name="right" style="xhtml" />
 				<div id="ads_right">
 					<?php 
						$video_array = array(336,330,154,323,91,46,33,22,9);
						if(in_array($var_id, $video_array)){
	 						$no_of_ads = '2';
						}else{
							$no_of_ads = '4';
						};
 						$ad_layout = 'vert';
 						$pos = 'rect2';
 						$advert_size = '1';						    
 						advert_func($no_of_ads,$ad_layout,$pos,$advert_size);
 					?>
 				</div>
 			</div>
 			<div id="sky">
 				<?php 
 					$no_of_ads = '2';
 					$ad_layout = 'vert';
 					$pos = 'sky2';
 					$advert_size = '2';
 					advert_func($no_of_ads,$ad_layout,$pos,$advert_size);
 				?>
 			</div>
 		<?php }; ?>
  <jdoc:include type="message" />
	</div>
 </div>
 <div id="footer">
 	<div id="footer_top_wrapper">
 		<div id="footer_top">
 			<?php //include("templates/baseline/includes/email_optin_footer.html") ?>
 			<div id="social_footer">
 				<jdoc:include type="modules" name="social" style="xhtml" />
 			</div>
 		</div>
 	</div>
 	<div id="footer_bottom_wrapper">
 		<div id="footer_bottom">
 			<jdoc:include type="modules" name="footer_menu1" style="xhtml" />
 			<jdoc:include type="modules" name="footer_menu2" style="xhtml" />
 			<jdoc:include type="modules" name="footer_menu3" style="xhtml" />
 			<jdoc:include type="modules" name="footer_menu4" style="xhtml" />
 			<jdoc:include type="modules" name="footer_issue_promo" style="xhtml" />
 		</div>
 	</div>
 </div>
 <div id="credits">
 	<jdoc:include type="modules" name="credits" style="xhtml" />
 </div>
 <jdoc:include type="modules" name="debug" />


<!-- Start Quantcast tag, part 2 -->
	<script type="text/javascript">
		_qevents.push({
		qacct:"p-7bwQSeDWmI24Q"
		});
	</script>
	<noscript>
		<div style="display:none;">
		<img src="//pixel.quantserve.com/pixel/p-7bwQSeDWmI24Q.gif" border="0" height="1" width="1" alt="Quantcast"/>
		</div>
	</noscript>
<!-- End Quantcast tag -->
 </body>
 </html>
