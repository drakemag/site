<?php header("Content-type: text/css"); include('setvars.css.php');?>/*set the variables*/

.responsiveMenuTheme1.rtlLayout{text-align: right;}
.responsiveMenuTheme1.rtlLayout > li > .parent, .responsiveMenuTheme1.rtlLayout > li.deeper > span{background-position: left;}
.responsiveMenuTheme1.rtlLayout > li > ul{left: auto;right: 0;}
.responsiveMenuTheme1.rtlLayout > li > ul > li > ul{left: -100%;}
.responsiveMenuTheme1.rtlLayout > li > ul > li > ul > li > ul {left: -100%;}
.responsiveMenuTheme1.rtlLayout > li > ul > li > ul > li > ul li > ul {left: -100%;}
.responsiveMenuTheme1.rtlLayout.isDesktop li li span.opener {background-image:url(../images/left-rtl-bg.png);background-position: 5% 50%;background-repeat: no-repeat;}

.responsiveMenuTheme1.rtlLayout li img{float: right;margin-right: 0;margin-left: 3px;}

.responsiveMenuTheme1.isMobile.rtlLayout li li a.parent {background-position: 0 50%;}

@media all and (min-width: <?php echo $maxMobileWidth; ?>px) {
    .responsiveMenuTheme1.rtlLayout > li{float:right;}
	.responsiveMenuTheme1.rtlLayout li li a.parent{}    
}