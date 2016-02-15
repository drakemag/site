<?php header("Content-type: text/css"); include('setvars.css.php');$rootItemsCount = $vars['rootItemsCount'];$rootItemsWidth = 100/$rootItemsCount;$moduleid=$vars['moduleid'];?>/*set the variables*/
@import url(//fonts.googleapis.com/css?family=PT+Sans+Narrow:400,700);
.responsiveMenuTheme8{list-style:none;zoom:1;background:<?php echo $menuBG;?>;margin:0;padding:0;font-family:"PT Sans Narrow","Arial Narrow",Arial,Helvetica,sans-serif;font-size: 18px;width: 100%;}
.responsiveMenuTheme8:before,.responsiveMenuTheme8:after{content:" ";display:table;}
.responsiveMenuTheme8:after{clear:both;}
.responsiveMenuTheme8 ul{list-style:none;min-width:100%;}
.responsiveMenuTheme8 a {color:<?php echo $textColor; ?>;padding:0px 3px 0px 20px;text-decoration:none;}
.responsiveMenuTheme8 span.separator{color:<?php echo $textColor; ?>;padding:0px 0 0 20px;display:block;cursor:pointer;}
.responsiveMenuTheme8 a:hover, .responsiveMenuTheme8 li.active > a, .responsiveMenuTheme8 li > span.separator:hover{color:<?php echo $textColor; ?>;background-color: <?php echo $mobileMenu;?>}
.responsiveMenuTheme8 li{position:relative;margin:0;padding:0;}
.responsiveMenuTheme8 li.current{clear:none;}
.responsiveMenuTheme8 > li{float:left;}
/*.responsiveMenuTheme8 > li > .parent, .responsiveMenuTheme8 > li.deeper > span{background-image:url(../images/down-bg8.png);background-repeat:no-repeat;background-position:right;}*/
.responsiveMenuTheme8 > li > .parent, .responsiveMenuTheme8 > li.deeper > span{}
.responsiveMenuTheme8 > li > a span.opener{float: right;}
.responsiveMenuTheme8 span.opener{background-image:url(../images/down-bg8.png);background-repeat:no-repeat;background-position:11px center;width:40px;text-indent: 100px;overflow: hidden;display: inline-block;padding: 10px 0;vertical-align: middle;position: absolute;right: 0;top: 0;}
.responsiveMenuTheme8 > li > a{display:block;padding: 0px 3px 0px 20px;}
.responsiveMenuTheme8 > li.parent > a{padding: 0px 40px 0px 20px;font-weight:bold;}
.responsiveMenuTheme8 > li.parent > .linker{font-weight:bold;}
.responsiveMenuTheme8 > li.parent > span.separator{padding: 0px 40px 0px 20px;}
.responsiveMenuTheme8 > li > span.separator {padding: 0px 0px 0px 20px;}
.responsiveMenuTheme8 span.linker {padding: 10px 0;display: inline-block;vertical-align: middle;}
.responsiveMenuTheme8 ul li span.linker {padding: 3px 0;}
.responsiveMenuTheme8 li ul{position:absolute;/*left:-9999px;*/margin:0;padding:0;display:none;}
.responsiveMenuTheme8 > li > ul{left:0;}
.responsiveMenuTheme8 > li > ul > li > ul{left:100%;top:0;}
.responsiveMenuTheme8 > li > ul > li > ul > li > ul{left:100%;top:0;}
.responsiveMenuTheme8 > li > ul > li > ul > li > ul > li > ul{left:100%;top:0;}
.responsiveMenuTheme8 li li > a{display:block;background:<?php echo $color3;?>;position:relative;z-index:100;border-top:1px solid <?php echo $bdColor; ?>;padding-right:20px;}
.responsiveMenuTheme8 > li > ul > li:first-child > a{border-top: none;}
.responsiveMenuTheme8 > li > ul > li:first-child > ul > li:first-child > a{border-top: none;}
.responsiveMenuTheme8 > li > ul > li:first-child > ul > li:first-child > ul > li:first-child > a {border-top: none;}
.responsiveMenuTheme8 > li > ul > li:first-child > ul > li:first-child > ul > li:first-child > ul > li:first-child > a {border-top: none;}
.responsiveMenuTheme8 li li span.separator {display:block;background:<?php echo $color3;?>;position:relative;z-index:100;border-top:1px solid <?php echo $bdColor; ?>;}
.responsiveMenuTheme8 li li li a{background:<?php echo $color4;?>;z-index:200;border-top:1px solid <?php echo $bdColor; ?>;}
.responsiveMenuTheme8 li li.parent a{padding: 0 40px 0 20px;}
.toggleMenu{display:none;color:<?php echo $textColor; ?>;padding:10px 15px;background:<?php echo $mobileMenu;?> url(../images/toggle-icon.png) no-repeat left center;height:40px;padding:0 0px 0 40px;line-height:40px;border-radius: 5px;font-family:"PT Sans Narrow","Arial Narrow",Arial,Helvetica,sans-serif;font-size: 18px;}
.toggleMenu span{padding-right:10px;}
.toggleMenu.active{border-radius: 5px 5px 0 0;}
.responsiveMenuTheme8.isMobile .active{display:block;}
.responsiveMenuTheme8.isMobile > li{float:none;border-top:1px solid <?php echo $bdColor; ?>;}
.responsiveMenuTheme8.isMobile > li > .parent{}
.responsiveMenuTheme8.isMobile li li .parent{}
.responsiveMenuTheme8.isMobile ul{display:none;width:100%;}
.responsiveMenuTheme8.isMobile > li > ul{position:static;}
.responsiveMenuTheme8.isMobile > li > ul > li > ul{position:static;}
.responsiveMenuTheme8.isMobile > li > ul > li > ul > li > ul{position:static;}
.responsiveMenuTheme8.isMobile > li > ul > li > ul > li > ul > li > ul{position:static;}
.responsiveMenuTheme8.isMobile li li a.parent{}
.responsiveMenuTheme8 a img {vertical-align: middle;margin-right: 3px;}
.responsiveMenuTheme8 li img, .responsiveMenuTheme8 li span.image-title {vertical-align: middle;}
.responsiveMenuTheme8 li img {margin-right: 3px;}
.responsiveMenuTheme8 span.navHeader{color:<?php echo $textColor; ?>;padding:0 20px;display:block;}
.responsiveMenuTheme8 > li.parent > span.navHeader{padding: 0px 40px 0px 20px;}
.responsiveMenuTheme8 li li span.navHeader {display:block;background:<?php echo $color3;?>;position:relative;z-index:100;border-top:1px solid <?php echo $bdColor; ?>;}
.responsiveMenuTheme8.isDesktop > li > ul {display: block !important;}
.responsiveMenuTheme8.isDesktop li ul{position:relative;display:block !important;}
.responsiveMenuTheme8.isDesktop > li > ul > li > ul{left:0;}
.responsiveMenuTheme8 > li > ul > li > ul li{padding-left: 15px;}
.responsiveMenuTheme8 li ul li span.opener{padding: 3px 0;}
.responsiveMenuTheme8.isDesktop li li span.opener{/*background-image:url(../images/down-bg8.png);*/background-position: center;background-image:none;}
.responsiveMenuTheme8.isDesktop li a, .responsiveMenuTheme8.isDesktop li span.separator, .responsiveMenuTheme8.isDesktop li span.navHeader{border-bottom:none;}
.responsiveMenuTheme8.isDesktop li li > a, .responsiveMenuTheme8.isDesktop li li > span.separator, .responsiveMenuTheme8.isDesktop li li > span.navHeader{border-left:none;border-top: none;}
#responsiveMenu<?php echo $moduleid;?>.responsiveMenuTheme8.isDesktop > li {width:<?php echo $rootItemsWidth;?>%;}
@media all and (min-width: <?php echo $maxMobileWidth; ?>px) {
}