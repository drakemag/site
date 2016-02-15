<?php header("Content-type: text/css"); include('setvars.css.php');?>/*set the variables*/

.responsiveMenuTheme2.rtlLayout{text-align: right;}
.responsiveMenuTheme2.rtlLayout > li > .parent, .responsiveMenuTheme2.rtlLayout > li.deeper > span{background-position: left;}
.responsiveMenuTheme2.rtlLayout > li > ul{left: auto;right: 0;}
.responsiveMenuTheme2.rtlLayout > li > ul > li > ul{left: -100%;}
.responsiveMenuTheme2.rtlLayout > li > ul > li > ul > li > ul {left: -100%;}
.responsiveMenuTheme2.rtlLayout > li > ul > li > ul > li > ul li > ul {left: -100%;}
.responsiveMenuTheme2.rtlLayout.isDesktop li li span.opener {background-image:url(../images/left-rtl-bg.png);background-position: 5% 50%;background-repeat: no-repeat;}

.responsiveMenuTheme2.rtlLayout li img{float: right;margin-right: 0;margin-left: 3px;}

.responsiveMenuTheme2.isMobile.rtlLayout li li a.parent {background-position: 0 50%;}

@media all and (min-width: <?php echo $maxMobileWidth; ?>px) {
    .responsiveMenuTheme2.rtlLayout > li{float:right;} 
}
