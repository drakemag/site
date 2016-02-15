<?php header("Content-type: text/css"); include('setvars.css.php');?>/*set the variables*/

@import url(//fonts.googleapis.com/css?family=PT+Sans+Narrow:400,700);

ul.responsiveMenuTheme3{list-style: none;margin: 0;padding: 0;font-family:"PT Sans Narrow","Arial Narrow",Arial,Helvetica,sans-serif;font-size: 18px;border:solid 1px <?php echo $bdColor; ?>;border-width: 1px 1px 0;}
ul.responsiveMenuTheme3 ul{display: none;list-style: none;margin: 0;padding: 0;}
ul.responsiveMenuTheme3 a, ul.responsiveMenuTheme3 span.separator, ul.responsiveMenuTheme3 span.navHeader {text-decoration: none;display: block;line-height: 25px;/*text-shadow:1px 1px 1px #fff;*/color: <?php echo $textColor; ?>;}
ul.responsiveMenuTheme3 li.current{clear:none;}
ul.responsiveMenuTheme3 li.active > a, ul.responsiveMenuTheme3 li:hover > a {background-color: <?php echo $mobileMenu;?>;}
ul.responsiveMenuTheme3 li.first a {border: none;}
ul.responsiveMenuTheme3 li.parent a{overflow: hidden;position: relative;}
ul.responsiveMenuTheme3 a span, ul.responsiveMenuTheme3 span.separator, ul.responsiveMenuTheme3 span.navHeader {padding: 10px 0;display: block;}
ul.responsiveMenuTheme3 li.parent a span.linker{margin-right: 40px;margin-right: 40px;overflow: hidden;}
ul.responsiveMenuTheme3 li a span img{vertical-align: middle;display:inline;margin-right: 3px;}
ul.responsiveMenuTheme3 li a span span.image-title{display: inline;vertical-align: middle;padding: 0;}
ul.responsiveMenuTheme3 li.parent a span.opener{background: url(../images/ico-parent.png) no-repeat right;text-indent:100px;overflow: hidden;width: 40px;position: absolute;top: 0;right: 0;text-align: right;}
ul.responsiveMenuTheme3 li.parent a.open span.opener{background: url(../images/ico-parent_open.png) no-repeat right;}


ul.responsiveMenuTheme3 li a, ul.responsiveMenuTheme3 li span.separator, ul.responsiveMenuTheme3 li span.navHeader {background:<?php echo $menuBG;?>;padding-left: 20px;border-bottom: 1px solid <?php echo $bdColor; ?>;border-top: 1px solid #fff;}
/*ul.responsiveMenuTheme3 li:hover > a{background: #F9F9F9;color:#4A8CE8;}*/

ul.responsiveMenuTheme3 li li a, ul.responsiveMenuTheme3 li li span.separator, ul.responsiveMenuTheme3 li li span.navHeader {background:<?php echo $color2;?>;padding-left: 40px;border-bottom: 1px solid <?php echo $bdColor; ?>;border-top: 1px solid #F2F2F2;}
/*ul.responsiveMenuTheme3 li li:hover > a{background: #F1F1F1;color:#3D7FDB;}*/

ul.responsiveMenuTheme3 li li li a, ul.responsiveMenuTheme3 li li li span.separator, ul.responsiveMenuTheme3 li li li span.navHeader{background:<?php echo $color3;?>;padding-left: 60px;border-bottom: 1px solid <?php echo $bdColor; ?>;border-top: 1px solid #EDEDED;}
/*ul.responsiveMenuTheme3 li li li:hover > a{background: #EAEAEA;color:#3173CF;}*/

ul.responsiveMenuTheme3 li li li li a, ul.responsiveMenuTheme3 li li li li span.separator, ul.responsiveMenuTheme3 li li li li span.navHeader{background:<?php echo $color4;?>;padding-left: 80px;border-bottom: 1px solid <?php echo $bdColor; ?>;border-top: 1px solid #EDEDED;}
/*ul.responsiveMenuTheme3 li li li li:hover > a{background: #E2E2E2;color:#2466C2;}*/

ul.responsiveMenuTheme3 li li li li li a, ul.responsiveMenuTheme3 li li li li li span.separator, ul.responsiveMenuTheme3 li li li li li span.navHeader{background: #D5D5D5;padding-left: 100px;border-bottom: 1px solid #D3D3D3;border-top: 1px solid #EDEDED;}
/*ul.responsiveMenuTheme3 li li li li li:hover > a{background: #DADADA;color:#1759B5;}*/


ul.responsiveMenuTheme3 li span.separator .opener {display: none;}


