<?php header("Content-type: text/css"); include('setvars.css.php');?>/*set the variables*/ 

@import url(//fonts.googleapis.com/css?family=PT+Sans+Narrow:400,700);

.responsiveMenuTheme5{list-style:none;zoom:1;background:<?php echo $menuBG;?>;font-family:"PT Sans Narrow","Arial Narrow",Arial,Helvetica,sans-serif;font-size: 18px;margin:0;padding:0;}
.responsiveMenuTheme5:before,.responsiveMenuTheme5:after{content:" ";display:table;}
.responsiveMenuTheme5:after{clear:both;}
.responsiveMenuTheme5 ul{list-style:none;width:200px;}
.responsiveMenuTheme5 a {color:<?php echo $textColor; ?>;padding:50px 10px 15px;text-decoration:none;}
.responsiveMenuTheme5 span.separator{color:<?php echo $textColor; ?>;padding:10px 20px;display:block;cursor:pointer;}
.responsiveMenuTheme5 li.current{clear:none;}
.responsiveMenuTheme5 > li span.separator{padding:50px 10px 15px;}
.responsiveMenuTheme5 span.opener{display: none;}
  .responsiveMenuTheme5 li span.separator:hover {background-color:<?php echo $menuBG;?>;}
.responsiveMenuTheme5 li:hover > a, .responsiveMenuTheme5 li.active > a, .responsiveMenuTheme5 li:hover > span.separator, .responsiveMenuTheme5 > li:hover > span.navHeader {color:<?php echo $textColor; ?>;background-color: <?php echo $mobileMenu;?>;}
.responsiveMenuTheme5 li{position:relative;margin:0;padding:0;}
.responsiveMenuTheme5 > li{float:left;}
.responsiveMenuTheme5 > li > .parent, .responsiveMenuTheme5 > li.deeper > span{background-image:url(../images/downArrow.png);background-repeat:no-repeat;background-position:right 57px;padding:50px 25px 15px 10px;}
.responsiveMenuTheme5 > li > a{display:block;}
.responsiveMenuTheme5 li ul{position:absolute;left:-9999px;margin:0;padding:0;}
.responsiveMenuTheme5 > li.hover > ul{left:0;}
.responsiveMenuTheme5 > li.hover > ul > li.hover > ul{left:100%;top:0;}
.responsiveMenuTheme5 > li.hover > ul > li.hover > ul > li.hover > ul{left:100%;top:0;}
.responsiveMenuTheme5 > li.hover > ul > li.hover > ul > li.hover > ul > li.hover > ul{left:100%;top:0;}
.responsiveMenuTheme5 li li a, .responsiveMenuTheme5 li li span.separator {display:block;background:<?php echo $color2;?>;position:relative;z-index:100;border-top:1px solid <?php echo $bdColor; ?>;padding:10px 20px; }
.responsiveMenuTheme5 li li:first-child > a, .responsiveMenuTheme5 li li:first-child > span.separator {border-top:none;}

  .responsiveMenuTheme5 li li span.separator:hover {background-color:<?php echo $mobileMenu;?>;}
.responsiveMenuTheme5 li li li a{background:<?php echo $color3;?>;z-index:200;border-top:1px solid <?php echo $bdColor; ?>;}
.responsiveMenuTheme5 li li li li a{background:<?php echo $color4;?>;}
.responsiveMenuTheme5 li li a.parent{background-image:url(../images/upArrow.png);background-repeat:no-repeat;background-position:95% 50%;}

.responsiveMenuTheme5.isDesktop {background: none;}

.toggleMenu{display:none;color:<?php echo $textColor; ?>;padding:10px 15px;background:<?php echo $mobileMenu;?> url(../images/toggle-icon.png) no-repeat left center;height:40px;padding:0 0 0 40px;line-height:40px;border-radius: 0 0px 5px 0;font-family:"PT Sans Narrow","Arial Narrow",Arial,Helvetica,sans-serif;font-size: 18px;transition:left 2s;-webkit-transition:left 2s;}
.toggleMenu span{padding-right:10px;}
.toggleMenu.isMobile.active{}



.responsiveMenuTheme5.isMobile a, .responsiveMenuTheme5.isMobile > li span.separator, .responsiveMenuTheme5.isMobile > li span.navHeader {padding:10px 15px;}
.responsiveMenuTheme5.isMobile {top: 0px;left: 0px;width:100%;position: relative;}
.responsiveMenuTheme5.isMobile .active{display:block;position: relative;}
.responsiveMenuTheme5.isMobile > li{float:none;border-top:1px solid <?php echo $bdColor; ?>;}
.responsiveMenuTheme5.isMobile > li > .parent{background-position:95% 50%;}
.responsiveMenuTheme5.isMobile li li .parent{background-image:url(../images/downArrow.png);background-repeat:no-repeat;background-position:95% 50%;}
.responsiveMenuTheme5.isMobile ul{display:block;width:100%;}
.responsiveMenuTheme5.isMobile > li.hover > ul{position:static;}
.responsiveMenuTheme5.isMobile > li.hover > ul > li.hover > ul{position:static;}
.responsiveMenuTheme5.isMobile > li.hover > ul > li.hover > ul > li.hover > ul{position:static;}
.responsiveMenuTheme5.isMobile > li.hover > ul > li.hover > ul > li.hover > ul > li.hover > ul{position:static;}

.responsiveMenuTheme5.isMobile li li a.parent{background-image:url(../images/downArrow.png);background-repeat:no-repeat;background-position:95% 50%;}

.responsiveMenuTheme5 a img {vertical-align: middle;margin-right: 3px;}
.responsiveMenuTheme5 a span.image-title {vertical-align: middle;}

.responsiveMenuTheme5 span.navHeader{color:<?php echo $textColor; ?>;padding:10px 20px;display:block;}
.responsiveMenuTheme5 > li span.navHeader{padding:50px 20px 15px;}
.responsiveMenuTheme5 li li span.navHeader {display:block;background:<?php echo $color2;?>;position:relative;z-index:100;border-top:1px solid <?php echo $bdColor; ?>;}
.responsiveMenuTheme5 li li span.navHeader:hover {background-color: <?php echo $mobileMenu;?>;}
.responsiveMenuTheme5 > li li span.navHeader {padding: 10px 20px;}