<?php header("Content-type: text/css"); include('setvars.css.php');?>/*set the variables*/

#responsiveMenuTheme5 ul.rtlLayout{text-align: right;}
@media all and (min-width: <?php echo $maxMobileWidth; ?>px) {
  #responsiveMenuTheme5 .rtlLayout li{float: right;}
}