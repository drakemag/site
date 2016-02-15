<?php
function adjustColorLightenDarken($color_code,$percentage_adjuster = 0) {
    $percentage_adjuster = round($percentage_adjuster/100,2);
    if(is_array($color_code)) {
        $r = $color_code["r"] - (round($color_code["r"])*$percentage_adjuster);
        $g = $color_code["g"] - (round($color_code["g"])*$percentage_adjuster);
        $b = $color_code["b"] - (round($color_code["b"])*$percentage_adjuster);
 
        return array("r"=> round(max(0,min(255,$r))),
            "g"=> round(max(0,min(255,$g))),
            "b"=> round(max(0,min(255,$b))));
    }
    else if(preg_match("/#/",$color_code)) {
        $hex = str_replace("#","",$color_code);
        $r = (strlen($hex) == 3)? hexdec(substr($hex,0,1).substr($hex,0,1)):hexdec(substr($hex,0,2));
        $g = (strlen($hex) == 3)? hexdec(substr($hex,1,1).substr($hex,1,1)):hexdec(substr($hex,2,2));
        $b = (strlen($hex) == 3)? hexdec(substr($hex,2,1).substr($hex,2,1)):hexdec(substr($hex,4,2));
        $r = round($r - ($r*$percentage_adjuster));
        $g = round($g - ($g*$percentage_adjuster));
        $b = round($b - ($b*$percentage_adjuster));
 
        return "#".str_pad(dechex( max(0,min(255,$r)) ),2,"0",STR_PAD_LEFT)
            .str_pad(dechex( max(0,min(255,$g)) ),2,"0",STR_PAD_LEFT)
            .str_pad(dechex( max(0,min(255,$b)) ),2,"0",STR_PAD_LEFT);
    }
}

parse_str($_SERVER['QUERY_STRING'], $vars);

$maxMobileWidth = $vars['maxMobileWidth'];
$menuBG = $vars['menuBG'];
$textColor = $vars['textColor'];
$textColor2 = $vars['textColor2'];
$color2 = adjustColorLightenDarken($menuBG, -10);if($color2=="#000000")$color2="#111111";
$color3 = adjustColorLightenDarken($color2, -10);
$color4 = adjustColorLightenDarken($color3, -10);
$bdColor = adjustColorLightenDarken($menuBG, 10);
$mobileMenu = adjustColorLightenDarken($menuBG, 10);if($menuBG=="#000000")$mobileMenu="#222222";




/* IF you wish to override with your colors, just uncomment these lines after setting your own values */

// $color2 = "#000000";
// $color3 = "#000000";
// $color4 = "#000000";
// $bdColor = "#000000";
// $mobileMenu = "#000000";
