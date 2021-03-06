<?php header("Content-type: text/css"); include('setvars.css.php');?>/*set the variables*/
/* Import "PT Sans Narrow" font from Google fonts */
@import url(//fonts.googleapis.com/css?family=PT+Sans+Narrow:400,700);
.responsiveMenuTheme7 span.opener{display:none;}
/*
---------------------------------------------------------------
  Note that styles you apply to the main menu items are inherited by the sub menus items too.
  If you'd like to avoid this, you could use child selectors (not supported by IE6) - for example:
  .sm-blue > li > a { ... } instead of .sm-blue a { ... }
---------------------------------------------------------------*/
/* Menu box
===================*/
	.sm-blue {
		background:<?php echo $menuBG;?>; /* Old browsers */
		/*background-image:url(css-gradients-fallback/main-menu-bg.png);*/
		background-image:-moz-linear-gradient(top,<?php echo $color2;?> 0%,<?php echo $menuBG;?> 100%);
		background-image:-webkit-gradient(linear,left top,left bottom,color-stop(0%,<?php echo $color2;?>),color-stop(100%,<?php echo $menuBG; ?>));
		background-image:-webkit-linear-gradient(top,<?php echo $color2;?> 0%,<?php echo $menuBG; ?> 100%);
		background-image:-o-linear-gradient(top,<?php echo $color2;?> 0%,<?php echo $menuBG; ?> 100%);
		background-image:-ms-linear-gradient(top,<?php echo $color2;?> 0%,<?php echo $menuBG; ?> 100%);
		background-image:linear-gradient(top,<?php echo $color2;?> 0%,<?php echo $menuBG; ?> 100%);
		-moz-border-radius:8px;
		-webkit-border-radius:8px;
		border-radius:8px;
		-moz-box-shadow:0 1px 1px rgba(0,0,0,0.3);
		-webkit-box-shadow:0 1px 1px rgba(0,0,0,0.3);
		box-shadow:0 1px 1px rgba(0,0,0,0.3);
	}
	.sm-blue-vertical {
		-moz-box-shadow:0 1px 4px rgba(0,0,0,0.3);
		-webkit-box-shadow:0 1px 4px rgba(0,0,0,0.3);
		box-shadow:0 1px 4px rgba(0,0,0,0.3);
	}
	.sm-blue ul {
		border:1px solid <?php echo $bdColor; ?>;
		padding:7px 0;
		background:#fff;
		-moz-border-radius:0 0 4px 4px;
		-webkit-border-radius:0 0 4px 4px;
		border-radius:0 0 4px 4px;
		-moz-box-shadow:0 5px 12px rgba(0,0,0,0.3);
		-webkit-box-shadow:0 5px 12px rgba(0,0,0,0.3);
		box-shadow:0 5px 12px rgba(0,0,0,0.3);
	}
	/* for vertical main menu subs and 2+ level horizontal main menu subs round all corners */
	.sm-blue-vertical ul,
	.sm-blue ul ul {
		-moz-border-radius:4px;
		-webkit-border-radius:4px;
		border-radius:4px;
    margin: 0;
	}
/* Menu items
===================*/
	.sm-blue a, .sm-blue span.separator, .sm-blue span.navHeader  {
		color:<?php echo $textColor; ?>;
		font-size:18px;
		line-height:23px;
		font-family:"PT Sans Narrow","Arial Narrow",Arial,Helvetica,sans-serif;
		text-decoration:none;
    	display: block;
	}
	.sm-blue > li > a {padding:13px 24px;}
	.sm-blue a:hover, .sm-blue a:focus, .sm-blue a:active,
	.sm-blue a.highlighted {
		background:#1983af; /* Old browsers */
		/*background-image:url(css-gradients-fallback/main-item-hover-bg.png);*/
		background-image:-moz-linear-gradient(top,<?php echo $color3;?> 0%,<?php echo $color2;?> 100%);
		background-image:-webkit-gradient(linear,left top, left bottom,color-stop(0%,<?php echo $color3;?>),color-stop(100%,<?php echo $color2;?>));
		background-image:-webkit-linear-gradient(top,<?php echo $color3;?> 0%,<?php echo $color2;?> 100%);
		background-image:-o-linear-gradient(top,<?php echo $color3;?> 0%,<?php echo $color2;?> 100%);
		background-image:-ms-linear-gradient(top,<?php echo $color3;?> 0%,<?php echo $color2;?> 100%);
		background-image:linear-gradient(top,<?php echo $color3;?> 0%,<?php echo $color2;?> 100%);
		color:<?php echo $textColor; ?>;
	}
	.sm-blue-vertical a, .sm-blue-vertical span.separator, .sm-blue-vertical span.navHeader  {
		background:<?php echo $menuBG;?>; /* Old browsers */
		/*background-image:url(css-gradients-fallback/vertical-main-item-bg.png);*/
		background-image:-moz-linear-gradient(top,<?php echo $color3;?> 0%,<?php echo $color2;?> 100%);
		background-image:-webkit-gradient(linear,left top,left bottom,color-stop(0%,<?php echo $color3;?>),color-stop(100%,<?php echo $color2;?>));
		background-image:-webkit-linear-gradient(top,<?php echo $color3;?> 0%,<?php echo $color2;?> 100%);
		background-image:-o-linear-gradient(top,<?php echo $color3;?> 0%,<?php echo $color2;?> 100%);
		background-image:-ms-linear-gradient(top,<?php echo $color3;?> 0%,<?php echo $color2;?> 100%);
		background-image:linear-gradient(top,<?php echo $color3;?> 0%,<?php echo $color2;?> 100%);
	}
	ul.sm-blue-vertical li.active > a, ul.sm-blue-vertical li.active > span.separator, ul.sm-blue-vertical li.active > span.navHeader {background: <?php echo $mobileMenu;?> !important;
	background-image:-moz-linear-gradient(top,<?php echo $menuBG;?> 0%,<?php echo $mobileMenu;?> 100%);
		background-image:-webkit-gradient(linear,left top,left bottom,color-stop(0%,<?php echo $menuBG;?>),color-stop(100%,<?php echo $mobileMenu;?>));
		background-image:-webkit-linear-gradient(top,<?php echo $menuBG;?> 0%,<?php echo $mobileMenu;?> 100%);
		background-image:-o-linear-gradient(top,<?php echo $menuBG;?> 0%,<?php echo $mobileMenu;?> 100%);
		background-image:-ms-linear-gradient(top,<?php echo $menuBG;?> 0%,<?php echo $mobileMenu;?> 100%);
		background-image:linear-gradient(top,<?php echo $menuBG;?> 0%,<?php echo $mobileMenu;?> 100%);
	} 
	.sm-blue ul a, .sm-blue ul span.separator, .sm-blue ul span.navHeader {
		background:transparent;
		color:<?php echo $textColor2;?>;
		font-size:16px;
		text-shadow:none;
	}
	.sm-blue ul a span.linker { padding:9px 40px 8px 23px;display: block;}
	.sm-blue ul a:hover, .sm-blue ul a:focus, .sm-blue ul a:active,
	.sm-blue ul a.highlighted {
		background:<?php echo $menuBG;?>; /* Old browsers */
		/*background-image:url(css-gradients-fallback/main-menu-bg.png);*/
		background-image:-moz-linear-gradient(top,<?php echo $color2;?> 0%,<?php echo $menuBG; ?> 100%);
		background-image:-webkit-gradient(linear,left top,left bottom,color-stop(0%,<?php echo $color2;?>),color-stop(100%,<?php echo $menuBG; ?>));
		background-image:-webkit-linear-gradient(top,<?php echo $color2;?> 0%,<?php echo $menuBG; ?> 100%);
		background-image:-o-linear-gradient(top,<?php echo $color2;?> 0%,<?php echo $menuBG; ?> 100%);
		background-image:-ms-linear-gradient(top,<?php echo $color2;?> 0%,<?php echo $menuBG; ?> 100%);
		background-image:linear-gradient(top,<?php echo $color2;?> 0%,<?php echo $menuBG; ?> 100%);
		color:<?php echo $textColor; ?>;
	}
	/* current items - add the class manually to some item or check the "markCurrentItem" script option */
	.sm-blue a.current, .sm-blue a.current:hover, .sm-blue a.current:focus, .sm-blue a.current:active,
	.sm-blue ul a.current, .sm-blue ul a.current:hover, .sm-blue ul a.current:focus, .sm-blue ul a.current:active {
		background:#006892;
		/*background-image:url(css-gradients-fallback/current-item-bg.png);*/
		background-image:-moz-linear-gradient(top,#005a84 0%,#00749f 100%);
		background-image:-webkit-gradient(linear,left top,left bottom,color-stop(0%,#005a84),color-stop(100%,#00749f));
		background-image:-webkit-linear-gradient(top,#005a84 0%,#00749f 100%);
		background-image:-o-linear-gradient(top,#005a84 0%,#00749f 100%);
		background-image:-ms-linear-gradient(top,#005a84 0%,#00749f 100%);
		background-image:linear-gradient(top,#005a84 0%,#00749f 100%);
		color:<?php echo $textColor; ?>;
	}
	/* round the left corners of the first item for horizontal main menu */
	.sm-blue > li:first-child > a, .sm-blue > li:first-child > span.separator, .sm-blue > li:first-child > span.navHeader {
		-moz-border-radius:8px 0 0 8px;
		-webkit-border-radius:8px 0 0 8px;
		border-radius:8px 0 0 8px;
	}
	/* round the corners of the first and last items for vertical main menu */
	.sm-blue-vertical > li:first-child > a, .sm-blue-vertical > li:first-child > span.separator, .sm-blue-vertical > li:first-child > span.navHeader {
		-moz-border-radius:8px 8px 0 0;
		-webkit-border-radius:8px 8px 0 0;
		border-radius:8px 8px 0 0;
	}
	.sm-blue-vertical > li:last-child > a, .sm-blue-vertical > li:last-child > span.separator, .sm-blue-vertical > li:last-child > span.navHeader {
		-moz-border-radius:0 0 8px 8px;
		-webkit-border-radius:0 0 8px 8px;
		border-radius:0 0 8px 8px;
	}
	.sm-blue a.has-submenu {
	}
/* Sub menu indicators
===================*/
	.sm-blue a span.sub-arrow {
		position:absolute;
		bottom:2px;
		left:50%;
		margin-left:-5px;
		/* we will use one-side border to create a triangle so that we don't use a real background image, of course, you can use a real image if you like too */
		width:0;
		height:0;
		overflow:hidden;
		border-width:5px; /* tweak size of the arrow */
		border-style:solid dashed dashed dashed;
		border-color:#a4cde1 transparent transparent transparent;
	}
	.sm-blue-vertical a span.sub-arrow,
 	.sm-blue ul a span.sub-arrow {
		bottom:auto;
		top:50%;
		margin-top:-5px;
		right:15px;
		left:auto;
		margin-left:0;
		border-style:dashed dashed dashed solid;
		border-color:transparent transparent transparent #a4cde1;
	}
/* Items separators
===================*/
	.sm-blue li {
		border-left:1px solid <?php echo $bdColor; ?>;
	}
	.sm-blue li:first-child,
	.sm-blue-vertical li,
	.sm-blue ul li {
		border-left:0;
	}
/* Scrolling arrows containers for tall sub menus - test sub menu: "Sub test" -> "more..." -> "more..." in the default download package
===================*/
	.sm-blue span.scroll-up, .sm-blue span.scroll-down {
		position:absolute;
		display:none;
		visibility:hidden;
		overflow:hidden;
		background:#ffffff;
		height:20px;
		/* width and position will be automatically set by the script */
	}
	.sm-blue span.scroll-up-arrow, .sm-blue span.scroll-down-arrow {
		position:absolute;
		top:-2px;
		left:50%;
		margin-left:-8px;
		/* we will use one-side border to create a triangle so that we don't use a real background image, of course, you can use a real image if you like too */
		width:0;
		height:0;
		overflow:hidden;
		border-width:8px; /* tweak size of the arrow */
		border-style:dashed dashed solid dashed;
		border-color:transparent transparent #247eab transparent;
	}
	.sm-blue span.scroll-down-arrow {
		top:6px;
		border-style:solid dashed dashed dashed;
		border-color:#247eab transparent transparent transparent;
	}
/*
---------------------------------------------------------------
  Responsiveness
  These will make the sub menus collapsible when the screen width is too small.
---------------------------------------------------------------*/
/* decrease horizontal main menu items left/right padding to avoid wrapping */
@media screen and (max-width: 850px) {
	.sm-blue:not(.sm-blue-vertical) > li > a {
		padding-left:18px;
		padding-right:18px;
	}
}
@media screen and (max-width: 750px) {
	.sm-blue:not(.sm-blue-vertical) > li > a {
		padding-left:10px;
		padding-right:10px;
	}
}
@media screen and (max-width: <?php echo $maxMobileWidth; ?>px) {
	/* The following will make the sub menus collapsible for small screen devices (it's not recommended editing these) */
	ul.sm-blue{width:auto !important;}
	ul.sm-blue ul{display:none;position:static !important;top:auto !important;left:auto !important;margin-left:0 !important;margin-top:0 !important;width:auto !important;min-width:0 !important;max-width:none !important;}
	ul.sm-blue>li{float:none;}
	ul.sm-blue>li>a,ul.sm-blue ul.sm-nowrap>li>a{white-space:normal;}
	ul.sm-blue iframe{display:none;}
	/* Uncomment this rule to disable completely the sub menus for small screen devices */
	/*.sm-blue ul, .sm-blue span.sub-arrow, .sm-blue iframe {
		display:none !important;
	}*/
/* Menu box
===================*/
	.sm-blue {
		background:transparent;
		-moz-box-shadow:0 1px 4px rgba(0,0,0,0.3);
		-webkit-box-shadow:0 1px 4px rgba(0,0,0,0.3);
		box-shadow:0 1px 4px rgba(0,0,0,0.3);
	}
	.sm-blue ul {
		border:0;
		padding:0;
		background:#fff;
		-moz-border-radius:0;
		-webkit-border-radius:0;
		border-radius:0;
		-moz-box-shadow:none;
		-webkit-box-shadow:none;
		box-shadow:none;
	}
	.sm-blue ul ul {
		/* darken the background of the 2+ level sub menus and remove border rounding */
		background:rgba(100,100,100,0.1);
		-moz-border-radius:0;
		-webkit-border-radius:0;
		border-radius:0;
	}
/* Menu items
===================*/
	.sm-blue a, .sm-blue span.separator, .sm-blue span.navHeader {
		padding:10px 5px 10px 28px !important; /* add some additional left padding to make room for the sub indicator */
		background:<?php echo $menuBG;?>; /* Old browsers */
		/*background-image:url(css-gradients-fallback/vertical-main-item-bg.png) !important;*/
		background-image:-moz-linear-gradient(top,<?php echo $color3;?> 0%,<?php echo $color2;?> 100%) !important;
		background-image:-webkit-gradient(linear,left top,left bottom,color-stop(0%,<?php echo $color3;?>),color-stop(100%,<?php echo $color2;?>)) !important;
		background-image:-webkit-linear-gradient(top,<?php echo $color3;?> 0%,<?php echo $color2;?> 100%) !important;
		background-image:-o-linear-gradient(top,<?php echo $color3;?> 0%,<?php echo $color2;?> 100%) !important;
		background-image:-ms-linear-gradient(top,<?php echo $color3;?> 0%,<?php echo $color2;?> 100%) !important;
		background-image:linear-gradient(top,<?php echo $color3;?> 0%,<?php echo $color2;?> 100%) !important;
		color:<?php echo $textColor; ?>;
	}
	.sm-blue ul a {
		background:transparent !important;
		color:<?php echo $textColor2;?> !important;
		text-shadow:none !important;
	}
	.sm-blue a.current {
		background:#006892 !important; /* Old browsers */
		/*background-image:url(css-gradients-fallback/current-item-bg.png) !important;*/
		background-image:-moz-linear-gradient(top,#005a84 0%,#00749f 100%) !important;
		background-image:-webkit-gradient(linear,left top,left bottom,color-stop(0%,#005a84),color-stop(100%,#00749f)) !important;
		background-image:-webkit-linear-gradient(top,#005a84 0%,#00749f 100%) !important;
		background-image:-o-linear-gradient(top,#005a84 0%,#00749f 100%) !important;
		background-image:-ms-linear-gradient(top,#005a84 0%,#00749f 100%) !important;
		background-image:linear-gradient(top,#005a84 0%,#00749f 100%) !important;
		color:#fff !important;
	}
	/* add some text indentation for the 2+ level sub menu items */
	.sm-blue ul a {
		border-left:8px solid transparent;
	}
	.sm-blue ul ul a {
		border-left:16px solid transparent;
	}
	.sm-blue ul ul ul a {
		border-left:24px solid transparent;
	}
	.sm-blue ul ul ul ul a {
		border-left:32px solid transparent;
	}
	.sm-blue ul ul ul ul ul a {
		border-left:40px solid transparent;
	}
	/* round the corners of the first and last items */
	.sm-blue > li:first-child > a, .sm-blue > li:first-child > span.separator, .sm-blue > li:first-child > span.navHeader {
		-moz-border-radius:8px 8px 0 0;
		-webkit-border-radius:8px 8px 0 0;
		border-radius:8px 8px 0 0;
	}
	/* presume we have 4 levels max */
	.sm-blue > li:last-child > a,
	.sm-blue > li:last-child > ul > li:last-child > a,
	.sm-blue > li:last-child > ul > li:last-child > ul > li:last-child > a,
	.sm-blue > li:last-child > ul > li:last-child > ul > li:last-child > ul > li:last-child > a,
	.sm-blue > li:last-child > ul > li:last-child > ul > li:last-child > ul > li:last-child > ul > li:last-child > a,
	.sm-blue > li:last-child > ul,
	.sm-blue > li:last-child > ul > li:last-child > ul,
	.sm-blue > li:last-child > ul > li:last-child > ul > li:last-child > ul,
	.sm-blue > li:last-child > ul > li:last-child > ul > li:last-child > ul > li:last-child > ul,
	.sm-blue > li:last-child > ul > li:last-child > ul > li:last-child > ul > li:last-child > ul > li:last-child > ul {
		-moz-border-radius:0 0 8px 8px;
		-webkit-border-radius:0 0 8px 8px;
		border-radius:0 0 8px 8px;
	}
	/* highlighted items, don't need rounding since their sub is open */
	.sm-blue > li:last-child > a.highlighted,
	.sm-blue > li:last-child > ul > li:last-child > a.highlighted,
	.sm-blue > li:last-child > ul > li:last-child > ul > li:last-child > a.highlighted,
	.sm-blue > li:last-child > ul > li:last-child > ul > li:last-child > ul > li:last-child > a.highlighted,
	.sm-blue > li:last-child > ul > li:last-child > ul > li:last-child > ul > li:last-child > ul > li:last-child > a.highlighted {
		-moz-border-radius:0;
		-webkit-border-radius:0;
		border-radius:0;
	}
/* Sub menu indicators
===================*/
	.sm-blue a span.sub-arrow,
	.sm-blue ul a span.sub-arrow {
		top:50%;
		margin-top:-9px;
		right:auto;
		left:6px;
		margin-left:0;
		width:17px;
		height:17px;
		font:bold 16px/16px monospace !important;
		text-align:center;
		border:0;
		text-shadow:none;
		background:rgba(0,0,0,0.1);
		-moz-border-radius:100px;
		-webkit-border-radius:100px;
		border-radius:100px;
	}
	/* Hide sub indicator "+" when item is expanded - we enable the item link when it's expanded */
	.sm-blue a.highlighted span.sub-arrow {
		display:none !important;
	}
/* Items separators
===================*/
	.sm-blue li {
		border-left:0;
	}
	.sm-blue ul li {
		border-top:1px solid rgba(0,0,0,0.05);
	}
	.sm-blue ul li:first-child {
		border-top:0;
	}
}
ul {list-style: none;}
@media all and (min-width: <?php echo $maxMobileWidth; ?>px) {
	#responsiveMenuTheme7 {
		position:relative;
		z-index:9999;
		width:12em;
	}
	#responsiveMenuTheme7 ul {
		width:12em; /* fixed width only please - you can use the "subMenusMinWidth"/"subMenusMaxWidth" script options to override this if you like */
	}
}
.responsiveMenuTheme7 li img, .responsiveMenuTheme7 li span.image-title {vertical-align: middle;}
.responsiveMenuTheme7 li img {margin-right: 3px;}
