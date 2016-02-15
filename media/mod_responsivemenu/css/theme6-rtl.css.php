<?php header("Content-type: text/css"); include('setvars.css.php');?>/*set the variables*/

.responsiveMenuTheme6.sm-rtl .sm-blue-vertical a span.sub-arrow, .sm-blue ul a span.sub-arrow {left: 10px;}


.responsiveMenuTheme6.sm-rtl li img{float: right;margin-right: 0;margin-left: 3px;}

.sm-blue-vertical a span.sub-arrow, .sm-blue ul a span.sub-arrow { border-color:transparent #a4cde1 transparent transparent; border-style:dashed solid dashed dashed;}

.responsiveMenuTheme6.sm-rtl.sm-blue > li:first-child > a, .responsiveMenuTheme6.sm-rtl.sm-blue > li:first-child > span.separator {border-radius:0 8px 8px 0;}

@media screen and (max-width: <?php echo $maxMobileWidth; ?>px) {
	.responsiveMenuTheme6.sm-rtl.sm-blue > li:first-child > a, .responsiveMenuTheme6.sm-rtl.sm-blue > li:first-child > span.separator {border-radius:8px 8px 0 0;}
}


