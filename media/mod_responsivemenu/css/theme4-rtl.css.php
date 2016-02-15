<?php header("Content-type: text/css"); include('setvars.css.php');?>/*set the variables*/ 

.responsiveMenuTheme4.rtlLayout {text-align: right;}
.responsiveMenuTheme4.rtlLayout > li.hover > ul{right:0;left: auto;}
.responsiveMenuTheme4.rtlLayout > li > .parent, .responsiveMenuTheme4 > li.deeper > span{background-position:left;}
.responsiveMenuTheme4.rtlLayout > li.hover > ul > li.hover > ul{left:-100%;top:0;}
.responsiveMenuTheme4.rtlLayout > li.hover > ul > li.hover > ul > li.hover > ul{left:-100%;top:0;}
.responsiveMenuTheme4.rtlLayout > li.hover > ul > li.hover > ul > li.hover > ul > li.hover > ul{left:-100%;top:0;}

.responsiveMenuTheme4.rtlLayout li img{float: right;margin-right: 0;margin-left: 3px;}
.responsiveMenuTheme4.isMobile.rtlLayout li li a.parent {background-position: 0 50%;}

@media all and (min-width: <?php echo $maxMobileWidth; ?>px) {
    .responsiveMenuTheme4.rtlLayout > li{float:right;}
    .responsiveMenuTheme4.rtlLayout li li a.parent{background-image: url(../images/upArrow-rtl.png);background-position: 5% 50%;} 
}