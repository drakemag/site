<?php 
/**
 * @author Joomla! Extensions Store
 * @package RESPONSIVIZER::MODULESLIDESHOW::modules::mod_responsivizerslideshow
 * @copyright (C) 2014 - Joomla! Extensions Store
 * @license GNU/GPLv2 http://www.gnu.org/licenses/gpl-2.0.html
 */
defined ( '_JEXEC' ) or die ();
$target = $params->get ( 'target' );

/**
 * Helper class pseudo MVC
 * @author Joomla! Extensions Store
 * @package RESPONSIVIZER::MODULESLIDESHOW::modules::mod_responsivizerslideshow
 * @since 1.0
 */
class modResponsivizerHelper {
	public static function getImages(&$params) {
		$imgsAndCaps = array ();
		
		$i = 1;
		
		while ( $params->get ( 'image_' . $i )) : 
			// if image_ + caption + link
			if (($params->get ( 'image_' . $i )) && ($params->get ( 'imagecaption_' . $i )) && ($params->get ( 'imagelink_' . $i ) != '')) { 
				$listitem = "<li><a target='" . $params->get ( 'target' ) . "' href='" . $params->get ( 'imagelink_' . $i ) . "'><img class='slide' title='' alt='" . $params->get ( 'imagealt_' . $i ) . "' src='" . JURI::root () .  $params->get ( 'image_' . $i ) . "' /><p class='responsivizer-caption' style='color:" . $params->get ( 'color_caption' ) . "'>" . $params->get ( 'imagecaption_' . $i ) . "</p></a></li>";
				array_push ( $imgsAndCaps, $listitem );
		 		// if image_ + no caption + link
			} else if (($params->get ( 'image_' . $i )) && ($params->get ( 'imagecaption_' . $i ) == null) && ($params->get ( 'imagelink_' . $i ) != '')) {
			 	$listitem = "<li><a target='" . $params->get ( 'target' ) . "' href='" . $params->get ( 'imagelink_' . $i ) . "'><img class='slide' title='' alt='" . $params->get ( 'imagealt_' . $i ) . "' src='" . JURI::root () .  $params->get ( 'image_' . $i ) . "' /></a></li>";
				array_push ( $imgsAndCaps, $listitem );
			 		// if image_ + caption + no link
			} else if (($params->get ( 'image_' . $i )) && ($params->get ( 'imagecaption_' . $i )) && ($params->get ( 'imagelink_' . $i ) == '')) {
			 	$listitem = "<li><img class='slide' title='' alt='" . $params->get ( 'imagealt_' . $i ) . "' src='" . JURI::root () . $params->get ( 'image_' . $i ) . "' /><p class='responsivizer-caption' style='color:" . $params->get ( 'color_caption' ) . "'>" . $params->get ( 'imagecaption_' . $i ) . "</p></li>";
				array_push ( $imgsAndCaps, $listitem );
		 		// if image + no caption + no link
			} else if (($params->get ( 'image_' . $i )) && ($params->get ( 'imagecaption_' . $i ) == null) && ($params->get ( 'imagelink_' . $i ) == '')) {
		 		$listitem = "<li><img class='slide' title='' alt='" . $params->get ( 'imagealt_' . $i ) . "' src='" . JURI::root () . $params->get ( 'image_' . $i ) . "' /></li>";
				array_push ( $imgsAndCaps, $listitem );
			} 
			$i ++;
		endwhile;
		
		return $imgsAndCaps;
	}
	public static function load_jquery(&$params) {
		$doc = JFactory::getDocument ();
		$app = JFactory::getApplication ();
		
		if(version_compare(JVERSION, '3.0', '>=')) {
			JHtml::_('jquery.framework');
		} else {
			if (!$app->get('jQuery') && !$app->get('jquery')) {
				$doc->addScript ( JURI::root ( true ) . '/modules/mod_responsivizerslideshow/assets/js/jquery.js' );
				$app->set('jQuery', 1);
			}
		}
			
		$doc->addScript ( JURI::root ( true ) . '/modules/mod_responsivizerslideshow/assets/js/jquery.easing.js' );
	}
}