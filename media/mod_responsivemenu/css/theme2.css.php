<?php header("Content-type: text/css"); include('setvars.css.php');?>/*set the variables*/

@import url(//fonts.googleapis.com/css?family=PT+Sans+Narrow:400,700);

.responsiveMenuTheme2{list-style:none;zoom:1;background:<?php echo $menuBG;?>;margin:0;padding:0;font-family:"PT Sans Narrow","Arial Narrow",Arial,Helvetica,sans-serif;font-size: 18px;width: 100%;}
.responsiveMenuTheme2:before,.responsiveMenuTheme2:after{content:" ";display:table;}
.responsiveMenuTheme2:after{clear:both;}
.responsiveMenuTheme2 ul{list-style:none;min-width:100%;}
.responsiveMenuTheme2 a {color:<?php echo $textColor; ?>;padding:0px 15px;text-decoration:none;}
.responsiveMenuTheme2 span.separator{color:<?php echo $textColor; ?>;padding:0 0 0 15px;display:block;cursor:pointer; }
.responsiveMenuTheme2 a:hover, .responsiveMenuTheme2 li.active > a, .responsiveMenuTheme2 li > span.separator:hover{color:<?php echo $textColor; ?>;background-color: <?php echo $mobileMenu;?>}
.responsiveMenuTheme2 li{position:relative;margin:0;padding:0;}
.responsiveMenuTheme2 li.current{clear:none;}
.responsiveMenuTheme2 > li{float:left;}
/*.responsiveMenuTheme2 > li > .parent, .responsiveMenuTheme2 > li.deeper > span{background-image:url(../images/down-bg.png);background-repeat:no-repeat;background-position:right;}*/
.responsiveMenuTheme2 > li > .parent, .responsiveMenuTheme2 > li.deeper > span{}
.responsiveMenuTheme2 span.opener{background-image:url(../images/down-bg.png);background-repeat:no-repeat;background-position:11px center;width:40px;text-indent: 100px;overflow: hidden;display: inline-block;padding: 15px 0;vertical-align: middle;position: absolute;right: 0;top: 0;}
.responsiveMenuTheme2 > li > a{display:block;padding: 0px 15px;}
.responsiveMenuTheme2 > li.parent > a{padding: 0px 40px 0px 15px;}
.responsiveMenuTheme2 > li.parent > span.separator{padding: 0px 40px 0px 15px;}
.responsiveMenuTheme2 > li > span.separator {padding: 0px 0 0 15px;}
.responsiveMenuTheme2 span.linker {padding: 15px 0;display: inline-block;vertical-align: middle;}
.responsiveMenuTheme2 li ul{position:absolute;/*left:-9999px;*/margin:0;padding:0;display:none;}
.responsiveMenuTheme2 > li > ul{left:0;}
.responsiveMenuTheme2 > li > ul > li > ul{left:100%;top:0;}
.responsiveMenuTheme2 > li > ul > li > ul > li > ul{left:100%;top:0;}
.responsiveMenuTheme2 > li > ul > li > ul > li > ul > li > ul{left:100%;top:0;}
.responsiveMenuTheme2 li li > a{display:block;background:<?php echo $color3;?>;position:relative;z-index:100;border-top:1px solid <?php echo $bdColor; ?>;white-space:nowrap;}
.responsiveMenuTheme2 > li > ul > li:first-child > a{border-top: none;}
.responsiveMenuTheme2 > li > ul > li:first-child > ul > li:first-child > a{border-top: none;}
.responsiveMenuTheme2 > li > ul > li:first-child > ul > li:first-child > ul > li:first-child > a {border-top: none;}
.responsiveMenuTheme2 > li > ul > li:first-child > ul > li:first-child > ul > li:first-child > ul > li:first-child > a {border-top: none;}

.responsiveMenuTheme2 li li span.separator {display:block;background:<?php echo $color3;?>;position:relative;z-index:100;border-top:1px solid <?php echo $bdColor; ?>;}
.responsiveMenuTheme2 li li li a{background:<?php echo $color4;?>;z-index:200;border-top:1px solid <?php echo $bdColor; ?>;}
.responsiveMenuTheme2 li li.parent a{padding: 0 40px 0 15px;}

.toggleMenu{display:none;color:<?php echo $textColor; ?>;padding:10px 15px;background:<?php echo $mobileMenu;?> url(../images/toggle-icon.png) no-repeat left center;height:40px;padding:0 0px 0 40px;line-height:40px;border-radius: 5px;font-family:"PT Sans Narrow","Arial Narrow",Arial,Helvetica,sans-serif;font-size: 18px;}
.toggleMenu span{padding-right:10px;}
.toggleMenu.active{border-radius: 5px 5px 0 0;}
.responsiveMenuTheme2.isMobile .active{display:block;}
.responsiveMenuTheme2.isMobile > li{float:none;border-top:1px solid <?php echo $bdColor; ?>;}
.responsiveMenuTheme2.isMobile > li > .parent{}
.responsiveMenuTheme2.isMobile li li .parent{}
.responsiveMenuTheme2.isMobile ul{display:none;width:100%;}
.responsiveMenuTheme2.isMobile > li > ul{position:static;}
.responsiveMenuTheme2.isMobile > li > ul > li > ul{position:static;}
.responsiveMenuTheme2.isMobile > li > ul > li > ul > li > ul{position:static;}
.responsiveMenuTheme2.isMobile > li > ul > li > ul > li > ul > li > ul{position:static;}

.responsiveMenuTheme2.isMobile li li a.parent{}

.responsiveMenuTheme2 a img {vertical-align: middle;margin-right: 3px;}

.responsiveMenuTheme2 li img, .responsiveMenuTheme2 li span.image-title {vertical-align: middle;}
.responsiveMenuTheme2 li img {margin-right: 3px;}

.responsiveMenuTheme2 span.navHeader{color:<?php echo $textColor; ?>;padding:0 15px;display:block;}
.responsiveMenuTheme2 > li.parent > span.navHeader{padding: 0px 40px 0px 15px;}
.responsiveMenuTheme2 li li span.navHeader {display:block;background:<?php echo $color3;?>;position:relative;z-index:100;border-top:1px solid <?php echo $bdColor; ?>;}
.responsiveMenuTheme2.isDesktop > li > a{border-left:1px solid <?php echo $bdColor; ?>;border-bottom:none;}
.responsiveMenuTheme2.isDesktop li a, .responsiveMenuTheme2.isDesktop li span.separator, .responsiveMenuTheme2.isDesktop li span.navHeader{border-left:1px solid <?php echo $bdColor; ?>;border-bottom:none;}
.responsiveMenuTheme2.isDesktop li li span.opener{background-image:url(../images/right-bg.png);background-position: center;}

@media all and (min-width: <?php echo $maxMobileWidth; ?>px) {
}