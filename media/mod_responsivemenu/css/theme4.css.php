<?php header("Content-type: text/css"); include('setvars.css.php');?>/*set the variables*/ 

@import url(//fonts.googleapis.com/css?family=PT+Sans+Narrow:400,700);

.responsiveMenuTheme4{list-style:none;zoom:1;background:<?php echo $menuBG;?>;font-family:"PT Sans Narrow","Arial Narrow",Arial,Helvetica,sans-serif;font-size: 18px;margin:0;padding:0;}
.responsiveMenuTheme4:before,.responsiveMenuTheme4:after{content:" ";display:table;}
.responsiveMenuTheme4:after{clear:both;}
.responsiveMenuTheme4 ul{list-style:none;width:200px;}
.responsiveMenuTheme4 a {color:<?php echo $textColor; ?>;padding:10px 20px;text-decoration:none;}
.responsiveMenuTheme4 span.separator{color:<?php echo $textColor; ?>;padding:10px 20px;display:block;cursor:pointer;}
.responsiveMenuTheme4 span.opener{display: none;}
	.responsiveMenuTheme4 li span.separator:hover {background-color:<?php echo $menuBG;?>;}
.responsiveMenuTheme4 a:hover, .responsiveMenuTheme4 li.active > a, .responsiveMenuTheme4 li > span.separator:hover{color:<?php echo $textColor; ?>;background-color: <?php echo $mobileMenu;?>;}
.responsiveMenuTheme4 li{position:relative;margin:0;padding:0;}
.responsiveMenuTheme4 li.current{clear:none;}
.responsiveMenuTheme4 > li{float:left;}
.responsiveMenuTheme4 > li > .parent, .responsiveMenuTheme4 > li.deeper > span{background-image:url(../images/downArrow.png);background-repeat:no-repeat;background-position:right;}
.responsiveMenuTheme4 > li > a{display:block;}
.responsiveMenuTheme4 li ul{position:absolute;left:-9999px;margin:0;padding:0;}
.responsiveMenuTheme4 > li.hover > ul{left:0;}
.responsiveMenuTheme4 > li.hover > ul > li.hover > ul{left:100%;top:0;}
.responsiveMenuTheme4 > li.hover > ul > li.hover > ul > li.hover > ul{left:100%;top:0;}
.responsiveMenuTheme4 > li.hover > ul > li.hover > ul > li.hover > ul > li.hover > ul{left:100%;top:0;}
.responsiveMenuTheme4 li li a, .responsiveMenuTheme4 li li span.separator {display:block;background:<?php echo $color2;?>;position:relative;z-index:100;border-top:1px solid <?php echo $bdColor; ?>;}
.responsiveMenuTheme4 li li:first-child > a, .responsiveMenuTheme4 li li:first-child > span.separator {border-top:none;}

	.responsiveMenuTheme4 li li span.separator:hover {background-color:<?php echo $mobileMenu;?>;}
.responsiveMenuTheme4 li li li a{background:<?php echo $color3;?>;z-index:200;border-top:1px solid <?php echo $bdColor; ?>;}
.responsiveMenuTheme4 li li li li a{background:<?php echo $color4;?>;}
.responsiveMenuTheme4 li li a.parent{background-image:url(../images/upArrow.png);background-repeat:no-repeat;background-position:95% 50%;}


.toggleMenu{display:none;color:<?php echo $textColor; ?>;padding:10px 15px;background:<?php echo $mobileMenu;?> url(../images/toggle-icon.png) no-repeat left center;height:40px;padding:0 0 0 40px;line-height:40px;border-radius: 0 5px 5px 0;font-family:"PT Sans Narrow","Arial Narrow",Arial,Helvetica,sans-serif;font-size: 18px;transition:left 2s;-webkit-transition:left 2s;}
.toggleMenu span{padding-right:10px;}
.toggleMenu.isMobile.active{}
.responsiveMenuTheme4.isMobile {top: 0px;left: -200px;width:200px;position: absolute;}
.responsiveMenuTheme4.isMobile .active{display:block;position: relative;}
.responsiveMenuTheme4.isMobile > li{float:none;border-top:1px solid <?php echo $bdColor; ?>;}
.responsiveMenuTheme4.isMobile > li > .parent{background-position:95% 50%;}
.responsiveMenuTheme4.isMobile li li .parent{background-image:url(../images/downArrow.png);background-repeat:no-repeat;background-position:95% 50%;}
.responsiveMenuTheme4.isMobile ul{display:block;width:100%;}
.responsiveMenuTheme4.isMobile > li.hover > ul{position:static;}
.responsiveMenuTheme4.isMobile > li.hover > ul > li.hover > ul{position:static;}
.responsiveMenuTheme4.isMobile > li.hover > ul > li.hover > ul > li.hover > ul{position:static;}
.responsiveMenuTheme4.isMobile > li.hover > ul > li.hover > ul > li.hover > ul > li.hover > ul{position:static;}

.responsiveMenuTheme4.isMobile li li a.parent{background-image:url(../images/downArrow.png);background-repeat:no-repeat;background-position:100% 50%;}

.responsiveMenuTheme4 a img {vertical-align: middle;margin-right: 3px;}
.responsiveMenuTheme4 a span.image-title {vertical-align: middle;}

.responsiveMenuTheme4 span.navHeader{color:<?php echo $textColor; ?>;padding:10px 20px;display:block;}
.responsiveMenuTheme4 li li span.navHeader {display:block;background:<?php echo $color3;?>;position:relative;z-index:100;border-top:1px solid <?php echo $bdColor; ?>;}