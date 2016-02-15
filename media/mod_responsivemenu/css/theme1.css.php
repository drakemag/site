<?php header("Content-type: text/css"); include('setvars.css.php');$rootItemsCount = $vars['rootItemsCount'];$rootItemsWidth = 100/$rootItemsCount;$moduleid=$vars['moduleid'];?>/*set the variables*/

@import url(//fonts.googleapis.com/css?family=PT+Sans+Narrow:400,700);

.responsiveMenuTheme1{list-style:none;zoom:1;background:<?php echo $menuBG;?>;margin:0;padding:0;font-family:"PT Sans Narrow","Arial Narrow",Arial,Helvetica,sans-serif;font-size: 18px;width: 100%;}
.responsiveMenuTheme1:before,.responsiveMenuTheme1:after{content:" ";display:table;}
.responsiveMenuTheme1:after{clear:both;}
.responsiveMenuTheme1 ul{list-style:none;min-width:100%;}
.responsiveMenuTheme1 a {color:<?php echo $textColor; ?>;padding:0px 3px 0px 20px;text-decoration:none;}
.responsiveMenuTheme1 span.separator{color:<?php echo $textColor; ?>;padding:0px 0 0 20px;display:block;cursor:pointer;}
.responsiveMenuTheme1 a:hover, .responsiveMenuTheme1 li.active > a, .responsiveMenuTheme1 li > span.separator:hover{color:<?php echo $textColor; ?>;background-color: <?php echo $mobileMenu;?>}
.responsiveMenuTheme1 li{position:relative;margin:0;padding:0;}
.responsiveMenuTheme1 li.current{clear:none;}
.responsiveMenuTheme1 > li{float:left;}
/*.responsiveMenuTheme1 > li > .parent, .responsiveMenuTheme1 > li.deeper > span{background-image:url(../images/down-bg.png);background-repeat:no-repeat;background-position:right;}*/
.responsiveMenuTheme1 > li > .parent, .responsiveMenuTheme1 > li.deeper > span{}
.responsiveMenuTheme1 > li > a span.opener{float: right;}
.responsiveMenuTheme1 span.opener{background-image:url(../images/down-bg.png);background-repeat:no-repeat;background-position:11px center;width:40px;text-indent: 100px;overflow: hidden;display: inline-block;padding: 15px 0;vertical-align: middle;position: absolute;right: 0;top: 0;}
.responsiveMenuTheme1 > li > a{display:block;padding: 0px 3px 0px 20px;}
.responsiveMenuTheme1 > li.parent > a{padding: 0px 40px 0px 20px;}
.responsiveMenuTheme1 > li.parent > span.separator{padding: 0px 40px 0px 20px;}
.responsiveMenuTheme1 > li > span.separator {padding: 0px 0px 0px 20px;}
.responsiveMenuTheme1 span.linker {padding: 15px 0;display: inline-block;vertical-align: middle;}
.responsiveMenuTheme1 li ul{position:absolute;/*left:-9999px;*/margin:0;padding:0;display:none;}
.responsiveMenuTheme1 > li > ul{left:0;}
.responsiveMenuTheme1 > li > ul > li > ul{left:100%;top:0;}
.responsiveMenuTheme1 > li > ul > li > ul > li > ul{left:100%;top:0;}
.responsiveMenuTheme1 > li > ul > li > ul > li > ul > li > ul{left:100%;top:0;}
.responsiveMenuTheme1 li li > a{display:block;background:<?php echo $color3;?>;position:relative;z-index:100;border-top:1px solid <?php echo $bdColor; ?>;white-space:nowrap;padding-right:20px;}
.responsiveMenuTheme1 > li > ul > li:first-child > a{border-top: none;}
.responsiveMenuTheme1 > li > ul > li:first-child > ul > li:first-child > a{border-top: none;}
.responsiveMenuTheme1 > li > ul > li:first-child > ul > li:first-child > ul > li:first-child > a {border-top: none;}
.responsiveMenuTheme1 > li > ul > li:first-child > ul > li:first-child > ul > li:first-child > ul > li:first-child > a {border-top: none;}

.responsiveMenuTheme1 li li span.separator {display:block;background:<?php echo $color3;?>;position:relative;z-index:100;border-top:1px solid <?php echo $bdColor; ?>;}
.responsiveMenuTheme1 li li li a{background:<?php echo $color4;?>;z-index:200;border-top:1px solid <?php echo $bdColor; ?>;}
.responsiveMenuTheme1 li li.parent a{padding: 0 40px 0 20px;}

.toggleMenu{display:none;color:<?php echo $textColor; ?>;padding:10px 15px;background:<?php echo $mobileMenu;?> url(../images/toggle-icon.png) no-repeat left center;height:40px;padding:0 0px 0 40px;line-height:40px;border-radius: 5px;font-family:"PT Sans Narrow","Arial Narrow",Arial,Helvetica,sans-serif;font-size: 18px;}
.toggleMenu span{padding-right:10px;}
.toggleMenu.active{border-radius: 5px 5px 0 0;}
.responsiveMenuTheme1.isMobile .active{display:block;}
.responsiveMenuTheme1.isMobile > li{float:none;border-top:1px solid <?php echo $bdColor; ?>;}
.responsiveMenuTheme1.isMobile > li > .parent{}
.responsiveMenuTheme1.isMobile li li .parent{}
.responsiveMenuTheme1.isMobile ul{display:none;width:100%;}
.responsiveMenuTheme1.isMobile > li > ul{position:static;}
.responsiveMenuTheme1.isMobile > li > ul > li > ul{position:static;}
.responsiveMenuTheme1.isMobile > li > ul > li > ul > li > ul{position:static;}
.responsiveMenuTheme1.isMobile > li > ul > li > ul > li > ul > li > ul{position:static;}

.responsiveMenuTheme1.isMobile li li a.parent{}

.responsiveMenuTheme1 a img {vertical-align: middle;margin-right: 3px;}

.responsiveMenuTheme1 li img, .responsiveMenuTheme1 li span.image-title {vertical-align: middle;}
.responsiveMenuTheme1 li img {margin-right: 3px;}

.responsiveMenuTheme1 span.navHeader{color:<?php echo $textColor; ?>;padding:0 20px;display:block;}
.responsiveMenuTheme1 > li.parent > span.navHeader{padding: 0px 40px 0px 20px;}
.responsiveMenuTheme1 li li span.navHeader {display:block;background:<?php echo $color3;?>;position:relative;z-index:100;border-top:1px solid <?php echo $bdColor; ?>;}

.responsiveMenuTheme1.isDesktop li li span.opener{background-image:url(../images/right-bg.png);background-position: center;}
.responsiveMenuTheme1.isDesktop li a, .responsiveMenuTheme1.isDesktop li span.separator, .responsiveMenuTheme1.isDesktop li span.navHeader{border-left:1px solid <?php echo $bdColor; ?>;border-bottom:none;}
.responsiveMenuTheme1.isDesktop li li > a, .responsiveMenuTheme1.isDesktop li li > span.separator, .responsiveMenuTheme1.isDesktop li li > span.navHeader{border-left:none;}
#responsiveMenu<?php echo $moduleid;?>.responsiveMenuTheme1.isDesktop > li {width:<?php echo $rootItemsWidth;?>%;}

@media all and (min-width: <?php echo $maxMobileWidth; ?>px) {
}

