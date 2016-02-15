<?php
/** 
 * @package RESPONSIVIZER::DYNAMICCSS::components::com_responsivizer
 * @subpackage views
 * @subpackage dynamiccss
 * @subpackage tmpl
 * @author Joomla! Extensions Store
 * @copyright (C) 2014 - Joomla! Extensions Store
 * @license GNU/GPLv2 http://www.gnu.org/licenses/gpl-2.0.html
 */
defined('_JEXEC') or die('Restricted access');

// Assign custom css vars
$general_background = $this->params->get('general_background', 'white');
$font = str_replace('+', ' ', $this->params->get('font', 'Metrophobic'));
$font_content = str_replace('+', ' ', $this->params->get('font_content', 'Verdana'));
$width = $this->params->get('width', '1024px');
$width_threshold = $this->params->get('width_threshold', '1024px');
$width_left = $this->params->get('width_left', 20);
$width_right = $this->params->get('width_right', 20);
$menuitem_width = $this->params->get('menuitem_width', 30);
$mivp = $this->params->get('menuitem_vert_padding', 5);
$mihp = $this->params->get('menuitem_horiz_padding', 10);
$submenuitem_splacement = $this->params->get('submenuitem_splacement', 40);
$menutext_alignment = $this->params->get('menutext_alignment', 'left');
$submenu_open_mode = $this->params->get('submenu_open_mode', 'absolute');
$transition_menu = $this->params->get('transition_menu', 1);
$width_menutop = $this->params->get('width_menutop', 35);
$width_menubottom = $this->params->get('width_menubottom', 35);
$width_copyright = $this->params->get('width_copyright', 35);
$switcher_menutype = $this->params->get('switcher_menutype', 'dropdown');
$main_foreground_overridecolor = $this->params->get('main_foreground_overridecolor', false);
$main_foreground_color = $this->params->get('main_foreground_color', '#6e6e6e');
$main_background_overridecolor = $this->params->get('main_background_overridecolor', false);
$main_background_color = $this->params->get('main_background_color', '#f7f7f7');
$main_background_image_override = $this->params->get('main_background_image_override', false);
$main_background_image = $this->params->get('main_background_image', null);
$main_background_image_repeat = $this->params->get('main_background_image_repeat', false);
$main_background_image_stretch = $this->params->get('main_background_image_stretch', false);
$main_background_image_element = $this->params->get('main_background_image_element', 'body');
$main_background_image_stretch_height = $this->params->get('main_background_image_stretch_height', '100%');
$footer_background_enabled = $this->params->get('footer_background_enabled', false);
$footer_background_color = $this->params->get('footer_background_color', '#333333');
$footer_foreground_color = $this->params->get('footer_foreground_color', '#FFFFFF');
$main_component_background = $this->params->get('main_component_background', 1);

// Margins and paddings
$logoTopPadding = $this->params->get('logo_top_padding', 2) . 'px';
$logoBottomPadding = $this->params->get('logo_bottom_padding', 2) . 'px';
$menusideTopPadding = $this->params->get('menuside_top_padding', 50) . 'px';
$headerBottomPadding = $this->params->get('header_bottom_padding', 5) . 'px';
$borderRadiusElements = $this->params->get('border_radius_elements', 3) . 'px';
$wrapperSitePadding = $this->params->get('wrapper_site_padding', 2) . 'px';
$componentSitePadding = $this->params->get('component_site_padding', 0) . 'px';
$footerTopPadding = $this->params->get('footer_top_padding', 2) . 'px';
$footerBottomPadding = $this->params->get('footer_bottom_padding', 2) . 'px';

// Color themes mapping
$colorsThemeMappings = array('white'=>array('#CCC', 'white-40.png'),
							 'black'=>array('#666', 'white-20.png'),
							 'blue'=>array('#333', 'white-40.png'),
							 'gray'=>array('#333', 'white-40.png'),
							 'green'=>array('#333', 'white-40.png'),
							 'android'=>array('#333', 'white-40.png'),
							 'ios'=>array('#333', 'white-40.png'));
?>
h1, .componentheading, h2.contentheading, .blog-featured h2 {
	font-family: '<?php echo $font ; ?>', 'Open Sans';
}

ul.menu li a, ul.menu ul li a, ul.menu li span {
font-family: '<?php echo $font ; ?>', 'Open Sans';
	padding: <?php echo $mivp;?>px <?php echo $mihp;?>px <?php echo $mivp;?>px <?php echo $mihp;?>px;
}

.top h3, .bottom h3, div.moduletable h3, .user1 h3, .user2 h3, .user3 h3, 
.user4 h3, .user5 h3, .user6 h3 {
	font-family: '<?php echo $font ; ?>', 'Open Sans';
}

.submenu li a, .submenu li span.separator {
	font-family: '<?php echo $font ; ?>', 'Open Sans';
}

.top_menu {
	width: <?php echo $width_menutop;?>%;
}

.bottom_menu {
	width: <?php echo $width_menubottom;?>%;
}

.copyright {
	width: <?php echo $width_copyright;?>%;
	float: left;
}

body {
	font-family: '<?php echo $font_content ; ?>';
	<?php
	if(!empty($main_foreground_overridecolor)) {
		echo 'color: ' . $main_foreground_color . ';';
	}

	if($main_background_image_element == 'body') {
		$backgroundString = null;
		if(!empty($main_background_overridecolor)) {
			$backgroundString .= $main_background_color;
		}
		
		if(!empty($main_background_image_override) && !empty($main_background_image)) {
			$repeat = $main_background_image_repeat ? 'repeat' : 'no-repeat';
			$backgroundString .= " url($this->liveSite" . "$main_background_image) $repeat";
		}
		
		if(!empty($backgroundString)) {
			echo 'background:' . $backgroundString . ';';
		}
		
		if(!empty($main_background_image_stretch)) {
			echo "background-size: 100% $main_background_image_stretch_height;";
		}
	} 
	?>

}

div.site {
	<?php
	if(!empty($main_foreground_overridecolor)) {
		echo 'color: ' . $main_foreground_color . ';';
	}
	
	if($main_background_image_element == 'site') {
		$backgroundString = null;
		if(!empty($main_background_overridecolor)) {
			$backgroundString .= $main_background_color;
		}
		
		if(!empty($main_background_image_override) && !empty($main_background_image)) {
			$repeat = $main_background_image_repeat ? 'repeat' : 'no-repeat';
			$backgroundString .= " url($this->liveSite" . "$main_background_image) $repeat";
		}
		
		if(!empty($backgroundString)) {
			echo 'background:' . $backgroundString . ';';
		}
		
		if(!empty($main_background_image_stretch)) {
			echo "background-size: 100% $main_background_image_stretch_height;";
		}
	} 
	?>

}

<?php if(!empty($main_foreground_overridecolor)):?>
.module h3, .breadcrumb a:hover, .link a:hover, h1,
.componentheading, .contentheading, h1 a, #social-links li.text_social_icons,
#main_component a, #main_component a:hover, div.content-module a, div.content-module a:hover  {
	color: <?php echo $main_foreground_color;?>  !important;
}
<?php endif; ?>

/** Dynamic Width **/
.wrapper-site {
	max-width:<?php echo $width ; ?>;
}

.left_column {
	width:<?php echo $width_left ; ?>%;
}

.right-module-position {
	width:<?php echo $width_right ; ?>%;
}

<?php if($this->params->get('override_titles_menu', 0)):?>
	.main-content h1.contentheading, .main-content h2.contentheading {
		background: <?php echo $this->params->get('custom_titles_menu_color', '#333333');?> !important;
	}
	<?php if($this->params->get('predefined_theme_2', 0) || $this->params->get('predefined_theme_3', 0)):?>
	ul.menu > li, ul.menu li ul {
		background: <?php echo $this->params->get('custom_titles_menu_color', '#333333');?> !important;
	}
	<?php endif;?>
<?php endif;?>

/** Dynamic media queries **/
@media screen and (min-width: <?php echo (intval($width_threshold) + 1) . 'px';?>) {
	#main_component {
		width: 75%;
		float: left;
	}
}

@media screen and (max-width: <?php echo $width_threshold;?>) {
	.logo {
		float: none;
		clear: both;
		text-align: center;
		padding: <?php echo $logoTopPadding;?> 0 <?php echo $logoBottomPadding;?> 0;
	}
	#search {
		right: 0;
	}

	input[type="text"],input[type="password"],input[type="email"],textarea {
		width: 80%;
	}

	/** 	CONTENT 	**/
	.left_column,right_column {
		float: none;
		border: none;
		width: auto;
		clear: both;
		width: 100%;
	}
	.right_column {
		overflow: visible;
	}
	.moduletable {
		clear: both;
		border: 1px solid #e3e3e3;
		border-radius: <?php echo $borderRadiusElements; ?>;
		background: #f7f7f7;
	}
	div.pagination ul li {
		border: 1px solid #e3e3e3;
		border-radius: 8px;
		background: #f7f7f7;
	}
	.module.nobg {
		 border: none;
		 background: none;
		 padding: 0 5px;
	}
	.slideshow .moduletable {
		border: none;
		background: none;
	}
	.submenu li {
		display: inline-block;
		margin: 0 1%;
		width: 48%;
	}
	.submenu li li {
		text-align: <?php echo $menutext_alignment ; ?>;
		float: none;
		margin: 2px 2px;
		width: 100%;
	}
	.submenu li li a,.submenu li li span.separator {
		text-align: <?php echo $menutext_alignment ; ?>;
		padding-top: 4px;
		padding-bottom: 3px;
	}
	.submenu li.active a,.submenu li.active span.separator,.submenu li.active a:hover,.submenu li.active span.separator:hover
		{
		margin-left: 0;
		width: auto;
	}
	.submenu li li a,.submenu li li span.separator,.submenu li.active li a,.submenu li.active li span.separator
		{
		background-position: 5px 10px;
	}

	/** 	FOOTER 		**/
	.bottom_menu li a,.bottom_menu li span.separator {
		padding: 5px 7px 4px 7px;
	}
	#search .inputbox {
		width: 120px;
	}
	.bottom_menu.with_social_icons {
		margin: 0 0 0 -12px;
	}
	
	.main_menu_box {
		float: left;
		width: 100%;
	}
	ul.menu li ul {
		top: <?php echo $switcher_menutype == 'dropdown' ? '100%' : 0;?>;
		width: 100%;
		margin-top: 0;
	}
	ul.menu li {
		text-align: <?php echo $menutext_alignment ; ?>;
		float: left;
	}
	ul.menu li li {
		text-align: <?php echo $menutext_alignment ; ?>;
		float: none;
		margin: 2px 0;
	}
	ul.menu li.active a,ul.menu li.active span.separator,ul.menu li.active a:hover,ul.menu li.active span.separator:hover
		{
		margin-left: 0;
		width: auto;
	}
	ul.menu li li a,ul.menu li li span.separator {
		width: auto;
	}
	
	.user1,.user2,.user3,.user4,.user5,.user6,.right-module-position {
		float: none;
		padding: 0;
		margin: 0;
		width: 100% !important;
		clear: both;
	}

	/* ++++++++++++++  blog  ++++++++++++++ */
	.cols-2 .column-1 {
		width: auto;
		float: none;
	}
	.cols-2 .column-2 {
		width: auto;
		float: none;
	}
	.cols-3 .column-1 {
		float: none;
		width: auto;
		padding: 0;
		margin-right: 0;
	}
	.cols-3 .column-2 {
		float: none;
		width: auto;
		width: auto;
		padding: 0;
	}
	.cols-3 .column-3 {
		float: none;
		width: auto;
		padding: 0;
	}
	.column-2 {
		width: auto;
		margin-left: 0;
	}
	.column-3 {
		width: auto;
	}

	/** 			TOP MENU 		**/
	.top_menu .moduletable {
		margin: 0;
	}
	.top_menu li {
		margin: 10px 0 0 5px;
		height: auto;
		background-image: none;
		opacity: 1.0;
		-ms-filter: "prodig:DXImageTransform.Microsoft.Alpha(Opacity=100)";
		/* hack IE8 	*/
		filter: apha(opacity =           100); /* hack IE7		*/
	}
	.top_menu li:hover {
		opacity: 1;
		-ms-filter: "prodig:DXImageTransform.Microsoft.Alpha(Opacity=100)";
		/* hack IE8 	*/
		filter: apha(opacity =           100); /* hack IE7		*/
	}
	.top_menu li a {
		text-shadow: none;
		background-image: none;
		font-size: 80%;
		margin-right: 0px;
		padding: 0px 13px;
		height: auto;
	}
	.top_menu li:first-child a {
		border-left: none;
	}

	/** 		TRANSLATE  		**/
	#translate {
		position: relative;
		float: right;
		margin: 6px 0 0;
	}

	ul.menu>li {
		width: <?php echo $menuitem_width ; ?>%;
	}
	.top_menu ul.menu>li, .bottom_menu ul.menu>li {
		width: <?php echo $menuitem_width ; ?>%;
	}
	ul.menu li ul ul {
		left: <?php echo $submenuitem_splacement ; ?>%;
	}
	ul.menu li a, ul.menu li span.separator, ul.menu li.clicked>ul {
		text-align: <?php echo $menutext_alignment ; ?>;
		border-radius: <?php echo $borderRadiusElements; ?>;
	}
	ul.menu li ul {
		position: <?php echo $submenu_open_mode ; ?> !important;
	}
	<?php if($transition_menu):?>
	ul.menu li ul {
		transition: visibility 0.6s ease-in;
		transition: opacity 0.6s ease-in;
		transition: max-height 0.2s ease-in;
	}
	ul.menu li:hover>ul,ul.menu li:focus>ul {
		transition: max-height 0.8s ease-in;
	}
	<?php endif;?>
	.moduletable, div.pagination ul li  {
		border-color: <?php echo $colorsThemeMappings[$general_background][0];?>;
		background: transparent url(<?php echo $this->liveSite;?>templates/responsivizer/images/<?php echo $colorsThemeMappings[$general_background][1];?>) 0 0 repeat;
	}
	
	#main_component {
		border-color: <?php echo $colorsThemeMappings[$general_background][0];?>;
		<?php if($main_component_background):?>
		background: transparent url(<?php echo $this->liveSite;?>templates/responsivizer/images/<?php echo $colorsThemeMappings[$general_background][1];?>) 0 0 repeat;
		<?php endif; ?>
	}
	
	#main_component {
		padding-left: <?php echo $componentSitePadding;?>;
		padding-right: <?php echo $componentSitePadding;?>;
	}
		
	.header-site {
		padding-bottom: <?php echo $headerBottomPadding;?>;
	}
	
	.wrapper-site {
		padding: 0 <?php echo $wrapperSitePadding;?>;
	}
	
	div.wrapper-site footer.bottom-site {
		padding-top: <?php echo $footerTopPadding;?>;
		padding-bottom: <?php echo $footerBottomPadding;?>;
		<?php if($footer_background_enabled):?>
			background-color: <?php echo $footer_background_color;?>;
			color: <?php echo $footer_foreground_color;?>;
		<?php endif;?>
	}
	
	<?php if($footer_background_enabled):?>
		div.wrapper-site footer.bottom-site * {
			color: <?php echo $footer_foreground_color;?>;
		}
	<?php endif; ?>
	
	<?php if(!$this->params->get('show_print_email_icons', 0)):?>
		ul.actions li.print-icon, ul.actions li.email-icon {
			display: none;
		}
	<?php endif;?>
	
	nav.side_menu #sidemenu_trigger_container {
		top: <?php echo $menusideTopPadding;?>;
	}
	
	<?php if($this->params->get('transform_table_responsive', 0)):?>
	table, thead, tbody, th, td, tr {
		display: block;
		width: 100%;
	}
	<?php endif;?>
	
	div.productdetails div.floatleft, div.productdetails div.floatright {
		float: none !important;
		width: 100% !important; 
	}
}