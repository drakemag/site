<?php
/**
 * @version		$Id: charts.php 01 2011-01-11 11:37:09Z maverick $
 * @package		CoreJoomla.Polls
 * @subpackage	Components.site
 * @copyright	Copyright (C) 2009 - 2010 corejoomla.com, Inc. All rights reserved.
 * @author		Maverick
 * @link		http://www.corejoomla.com/
 * @license		License GNU General Public License version 2 or later
 */

// no direct access
defined('_JEXEC') or die('Restricted access');

define('PCHART_CLASS_PATH', JPATH_ROOT.'/components/com_communitypolls/assets/pchart/class/');
define('PCHART_FONTS_PATH', JPATH_ROOT.'/components/com_communitypolls/assets/pchart/fonts/');

class ChartsHelper
{
	public static function get_poll_bar_chart($poll, $width=0)
	{
		include_once PCHART_CLASS_PATH.'pData.class.php';
		include_once PCHART_CLASS_PATH.'pDraw.class.php';
		include_once PCHART_CLASS_PATH.'pImage.class.php';
		include_once PCHART_CLASS_PATH.'pCache.class.php';
		
		$palette = ChartsHelper::get_pallete($poll->pallete);

		$MyData = new pData();
		$votes_array = array();
		$filename = $poll->id.'-poll-barchart.png';

		foreach ($poll->answers as $answer)
		{
			$votes_array[] = round($answer->pct);
			$labels_array[] = $answer->title. ' ('.$answer->votes.' '.JText::plural('COM_COMMUNITYPOLLS_VOTES', $answer->votes).')';
		}
		
		$width = $width > 100 ? $width : 500;
		$height = count($labels_array)*60;

		$MyData->addPoints($labels_array, JText::_('COM_COMMUNITYPOLLS_ANSWERS'));
		$MyData->addPoints($votes_array, JText::_('COM_COMMUNITYPOLLS_VOTES'));
		$MyData->setAxisName(0, JText::_('COM_COMMUNITYPOLLS_VOTES').' (in %)');
		$MyData->setAbscissaName(JText::_('COM_COMMUNITYPOLLS_ANSWERS'));
		//$MyData->setAxisUnit(0, '%');

		$myCache = new pCache(array('CacheFolder'=>P_TEMP_STORE));
		$myCache->flush();
		
		$ChartHash = $myCache->getHash($MyData);
		
		if ( $myCache->isInCache($ChartHash) )
		{
			$myCache->saveFromCache($ChartHash, P_TEMP_STORE.DS.$filename);
		}
		else
		{
			$image = new pImage($width, $height, $MyData);
			$image->setFontProperties(array('FontName'=>PCHART_FONTS_PATH.'verdana.ttf','FontSize'=>8));
			$image->setGraphArea(30,30,$width-20,$height-20);
			$image->drawScale(array('CycleBackground'=>TRUE,'DrawSubTicks'=>TRUE,'GridR'=>0,'GridG'=>0,'GridB'=>0,'GridAlpha'=>10,'Pos'=>SCALE_POS_TOPBOTTOM,'Mode'=>SCALE_MODE_START0,'ManualScale'=>array('0'=>array('Min'=>0,'Max'=>100))));
			$image->setShadow(TRUE,array('X'=>1,'Y'=>1,'R'=>0,'G'=>0,'B'=>0,'Alpha'=>10));
			$image->drawBarChart(array('DisplayPos'=>LABEL_POS_INSIDE,'GradientMode'=>GRADIENT_EFFECT_CAN,'DisplayValues'=>TRUE,'Rounded'=>TRUE,'Surrounding'=>30,'OverrideColors'=>$palette));

			$myCache->writeToCache($ChartHash, $image);

			$image->Render(P_TEMP_STORE.DS.$filename);
		}
		
		return P_TEMP_STORE_URI.$filename;
	}

	public static function get_poll_pie_chart($poll, $width=0, $height=0)
	{
		include_once PCHART_CLASS_PATH.'pData.class.php';
		include_once PCHART_CLASS_PATH.'pDraw.class.php';
		include_once PCHART_CLASS_PATH.'pImage.class.php';
		include_once PCHART_CLASS_PATH.'pPie.class.php';
		include_once PCHART_CLASS_PATH.'pCache.class.php';
		
		$palette = ChartsHelper::get_pallete($poll->pallete);
		
		$MyData = new pData();
		$votes_array = array();
		$filename = $poll->id.'-poll-barchart.png';

		foreach ($poll->answers as $answer)
		{
			$votes_array[] = round($answer->pct);
			$labels_array[] = $answer->title;
		}

		$width = $width > 100 ? $width : 300;
		$height = $height > 50 ? $height : 200;

		$MyData->addPoints($votes_array,JText::_('COM_COMMUNITYPOLLS_VOTES'));
		$MyData->setAxisName(0, JText::_('COM_COMMUNITYPOLLS_VOTES').' (in %)');
		$MyData->addPoints($labels_array, 'Answers');
		$MyData->setAbscissa('Answers');
		$MyData->setAbscissaName(JText::_('COM_COMMUNITYPOLLS_ANSWERS'));

		$myCache = new pCache(array('CacheFolder'=>P_TEMP_STORE));
		$ChartHash = $myCache->getHash($MyData);

 		$myCache->flush();

		if ( $myCache->isInCache($ChartHash))
		{
			$myCache->saveFromCache($ChartHash, P_TEMP_STORE.DS.$filename);
		}
		else
		{
			$image = new pImage($width, $height, $MyData, TRUE);
			
//  			$image->dumpImageMap("ImageMap3DPieChart",IMAGE_MAP_STORAGE_FILE,"3DPieChart",P_TEMP_STORE); 
//  			$image->initialiseImageMap("ImageMap3DPieChart",IMAGE_MAP_STORAGE_FILE,"3DPieChart",P_TEMP_STORE);
			
			$image->setFontProperties(array('FontName'=>PCHART_FONTS_PATH.'verdana.ttf','FontSize'=>8));
			$image->setGraphArea(30,30,$width-20,$height-20);
			$image->setShadow(TRUE,array('X'=>1,'Y'=>1,'R'=>0,'G'=>0,'B'=>0,'Alpha'=>10));
			
			$PieChart = new pPie($image, $MyData);
			
			foreach ($votes_array as $i=>$arr)
			{
				$PieChart->setSliceColor($i, $palette[$i%count($palette)]);
			}
				
			$PieChart->draw3DPie($width/2, $height/2, array('Radius'=>$height/2,'DataGapAngle'=>0,'DataGapRadius'=>0,'Border'=>FALSE, 'WriteValues'=>PIE_VALUE_PERCENTAGE, 'RecordImageMap'=>TRUE, 'DrawLabels'=>FALSE));
//  			$PieChart->drawPieLegend(30,180,array('Style'=>LEGEND_NOBORDER,'Mode'=>LEGEND_VERTICAL));
				
			$myCache->writeToCache($ChartHash, $image);

			$image->Render(P_TEMP_STORE.DS.$filename);
		}
		
		return P_TEMP_STORE_URI.$filename;
	}
	
	public static function get_timeline_chart($poll)
	{
		include_once PCHART_CLASS_PATH.'pData.class.php';
		include_once PCHART_CLASS_PATH.'pDraw.class.php';
		include_once PCHART_CLASS_PATH.'pImage.class.php';
		include_once PCHART_CLASS_PATH.'pCache.class.php';
		
		$MyData = new pData();
		
		$dates = array();
		$votes = array();
		
		$filename = $poll->id.'-timeline-chart.png';
		
		foreach($poll->vstats as $stat)
		{
			$dates[] = $stat->vdate;
			$votes[] = $stat->votes;
		}
		
		$MyData->addPoints($votes, 'Votes');
		$MyData->setAxisName(0, JText::_('COM_COMMUNITYPOLLS_VOTES'));
		
		$MyData->addPoints($dates, 'Labels');
		$MyData->setSerieDescription('Labels', JText::_('COM_COMMUNITYPOLLS_LABEL_DATE'));
		$MyData->setAbscissa('Labels');
		
		$myCache = new pCache(array('CacheFolder'=>P_TEMP_STORE));
		$ChartHash = $myCache->getHash($MyData);
		
// 		$myCache->flush();
		
		if ( $myCache->isInCache($ChartHash))
		{
			$myCache->saveFromCache($ChartHash, P_TEMP_STORE.DS.$filename);
		}
		else
		{
			$image = new pImage(700, 230, $MyData);
			
			$image->Antialias = FALSE;
			$image->setFontProperties(array('FontName'=>PCHART_FONTS_PATH.'verdana.ttf','FontSize'=>8));
			$image->setGraphArea(60,40,650,200);
			
			$scaleSettings = array("XMargin"=>10,"YMargin"=>10,"Floating"=>TRUE,"GridR"=>200,"GridG"=>200,"GridB"=>200,"DrawSubTicks"=>TRUE,"CycleBackground"=>TRUE);
			$image->drawScale($scaleSettings);
			$image->drawAreaChart();
			
			$myCache->writeToCache($ChartHash, $image);
			$image->Render(P_TEMP_STORE.DS.$filename);
		}
		
		return P_TEMP_STORE_URI.$filename;
	}
	
	public static function get_pallete($name='default')
	{
		switch ($name)
		{
			case 'shankar':
				
				return array(
					"0"=>array("R"=>100,"G"=>151,"B"=>183,"Alpha"=>100),
					"1"=>array("R"=>224,"G"=>151,"B"=>92,"Alpha"=>100),
					"2"=>array("R"=>224,"G"=>46,"B"=>9,"Alpha"=>100),
					"3"=>array("R"=>92,"G"=>224,"B"=>92,"Alpha"=>100),
					'4'=>array('R'=>188,'G'=>46,'B'=>46,'Alpha'=>100),
					'5'=>array('R'=>188,'G'=>224,'B'=>46,'Alpha'=>100),
					'6'=>array('R'=>176,'G'=>46,'B'=>224,'Alpha'=>100), 
					'7'=>array('R'=>46,'G'=>151,'B'=>224,'Alpha'=>100),
					'8'=>array('R'=>9,'G'=>87,'B'=>183,'Alpha'=>100),
					'9'=>array('R'=>224,'G'=>100,'B'=>46,'Alpha'=>100));
				
			case 'kamala':
				
				return array(
					"0"=>array("R"=>144,"G"=>189,"B"=>12,"Alpha"=>100),
					"1"=>array("R"=>212,"G"=>80,"B"=>73,"Alpha"=>100),
					"2"=>array("R"=>8,"G"=>145,"B"=>145,"Alpha"=>100),
					"3"=>array("R"=>178,"G"=>219,"B"=>249,"Alpha"=>100),
					'4'=>array('R'=>247,'G'=>192,'B'=>27,'Alpha'=>100),
					'5'=>array('R'=>255,'G'=>235,'B'=>204,'Alpha'=>100),
					'6'=>array('R'=>95,'G'=>216,'B'=>165,'Alpha'=>100), 
					'7'=>array('R'=>231,'G'=>45,'B'=>41,'Alpha'=>100),
					'8'=>array('R'=>9,'G'=>87,'B'=>183,'Alpha'=>100),
					'9'=>array('R'=>254,'G'=>254,'B'=>204,'Alpha'=>100));
				
			case 'autumn':
				
				return array(
					"0"=>array("R"=>185,"G"=>106,"B"=>154,"Alpha"=>100),
					"1"=>array("R"=>216,"G"=>137,"B"=>184,"Alpha"=>100),
					"2"=>array("R"=>156,"G"=>192,"B"=>137,"Alpha"=>100),
					"3"=>array("R"=>216,"G"=>243,"B"=>201,"Alpha"=>100),
					'4'=>array('R'=>253,'G'=>232,'B'=>215,'Alpha'=>100),
					'5'=>array('R'=>255,'G'=>255,'B'=>255,'Alpha'=>100));
				
			case 'blind':
				
				return array(
					"0"=>array("R"=>109,"G"=>152,"B"=>171,"Alpha"=>100),
					"1"=>array("R"=>0,"G"=>39,"B"=>94,"Alpha"=>100),
					'2'=>array('R'=>254,'G'=>183,'B'=>41,'Alpha'=>100), 
					'3'=>array('R'=>168,'G'=>177,'B'=>184,'Alpha'=>100),
					'4'=>array('R'=>255,'G'=>255,'B'=>255,'Alpha'=>100),
					'5'=>array('R'=>0,'G'=>0,'B'=>0,'Alpha'=>100));
				
			case 'evening':
				
				return array(
					"0"=>array("R"=>242,"G"=>245,"B"=>237,"Alpha"=>100),
					"1"=>array("R"=>255,"G"=>194,"B"=>0,"Alpha"=>100),
					'2'=>array('R'=>255,'G'=>91,'B'=>0,'Alpha'=>100), 
					'3'=>array('R'=>184,'G'=>0,'B'=>40,'Alpha'=>100),
					'4'=>array('R'=>132,'G'=>0,'B'=>46,'Alpha'=>100),
					'5'=>array('R'=>74,'G'=>192,'B'=>242,'Alpha'=>100));
				
			case 'kitchen':
				
				return array(
					"0"=>array("R"=>155,"G"=>225,"B"=>251,"Alpha"=>100),
					"1"=>array("R"=>197,"G"=>239,"B"=>253,"Alpha"=>100),
					"2"=>array("R"=>189,"G"=>32,"B"=>49,"Alpha"=>100),
					'3'=>array('R'=>35,'G'=>31,'B'=>32,'Alpha'=>100),
					'4'=>array('R'=>255,'G'=>255,'B'=>255,'Alpha'=>100),
					'5'=>array('R'=>0,'G'=>98,'B'=>149,'Alpha'=>100));
				
			case 'light':
				
				return array(
					"0"=>array("R"=>239,"G"=>210,"B"=>121,"Alpha"=>100),
					"1"=>array("R"=>149,"G"=>203,"B"=>233,"Alpha"=>100),
					"2"=>array("R"=>2,"G"=>71,"B"=>105,"Alpha"=>100),
					'3'=>array('R'=>175,'G'=>215,'B'=>117,'Alpha'=>100),
					'4'=>array('R'=>44,'G'=>87,'B'=>0,'Alpha'=>100),
					'5'=>array('R'=>222,'G'=>157,'B'=>127,'Alpha'=>100));
				
			case 'navy':
				
				return array(
					"0"=>array("R"=>25,"G"=>78,"B"=>132,"Alpha"=>100),
					"1"=>array("R"=>59,"G"=>107,"B"=>156,"Alpha"=>100),
					"2"=>array("R"=>31,"G"=>36,"B"=>42,"Alpha"=>100),
					'3'=>array('R'=>55,'G'=>65,'B'=>74,'Alpha'=>100),
					'4'=>array('R'=>96,'G'=>187,'B'=>34,'Alpha'=>100),
					'5'=>array('R'=>242,'G'=>186,'B'=>187,'Alpha'=>100));
				
			case 'shade':
				
				return array(
					"0"=>array("R"=>117,"G"=>113,"B"=>22,"Alpha"=>100),
					"1"=>array("R"=>174,"G"=>188,"B"=>33,"Alpha"=>100),
					'2'=>array('R'=>217,'G'=>219,'B'=>86,'Alpha'=>100), 
					'3'=>array('R'=>0,'G'=>71,'B'=>127,'Alpha'=>100),
					'4'=>array('R'=>76,'G'=>136,'B'=>190,'Alpha'=>100),
					'5'=>array('R'=>141,'G'=>195,'B'=>233,'Alpha'=>100));
				
			case 'spring':
				
				return array(
					"0"=>array("R"=>146,"G"=>123,"B"=>81,"Alpha"=>100),
					"1"=>array("R"=>168,"G"=>145,"B"=>102,"Alpha"=>100),
					"2"=>array("R"=>128,"G"=>195,"B"=>28,"Alpha"=>100),
					'3'=>array('R'=>188,'G'=>221,'B'=>90,'Alpha'=>100),
					'4'=>array('R'=>255,'G'=>121,'B'=>0,'Alpha'=>100),
					'5'=>array('R'=>251,'G'=>179,'B'=>107,'Alpha'=>100));
				
			case 'summer':
				
				return array(
					"0"=>array("R"=>253,"G"=>184,"B"=>19,"Alpha"=>100),
					"1"=>array("R"=>246,"G"=>139,"B"=>31,"Alpha"=>100),
					'2'=>array('R'=>241,'G'=>112,'B'=>34,'Alpha'=>100), 
					'3'=>array('R'=>98,'G'=>194,'B'=>204,'Alpha'=>100),
					'4'=>array('R'=>228,'G'=>246,'B'=>248,'Alpha'=>100),
					'5'=>array('R'=>238,'G'=>246,'B'=>108,'Alpha'=>100));
				
			default:
				
				return array(
					'0'=>array('R'=>255,'G'=>91,'B'=>0,'Alpha'=>100),
					'1'=>array('R'=>74,'G'=>192,'B'=>242,'Alpha'=>100),
					'2'=>array('R'=>184,'G'=>0,'B'=>40,'Alpha'=>100),
					'3'=>array('R'=>238,'G'=>246,'B'=>108,'Alpha'=>100),
					'4'=>array('R'=>96,'G'=>187,'B'=>34,'Alpha'=>100),
					"5"=>array("R"=>185,"G"=>106,"B"=>154,"Alpha"=>100),
					'6'=>array('R'=>98,'G'=>194,'B'=>204,'Alpha'=>100)
				);
		}
	}
	
	public static function get_color_palletes()
	{
		return array(
			'default' => 'Default', 
			'shankar' => 'Shankar', 
			'kamala' => 'Kamala', 
			'autumn' => 'Autumn', 
			'blind' => 'Blind', 
			'evening' => 'Evening', 
			'kitchen' => 'Kitchen', 
			'light' => 'Light', 
			'navy' => 'Navy', 
			'shade' => 'Shade', 
			'spring' => 'Spring', 
			'summer' => 'Summer'
		);
	}
	
	public static function get_rgb_colors($name='default')
	{
		$colors = array();
		$pallete = ChartsHelper::get_pallete($name);
		
		foreach($pallete as $color)
		{
			$colors[] = ChartsHelper::RGBToHex($color);
		}
		
		return $colors;
	}
	
	public static function HexToRGB($hex) 
	{
		$hex = str_replace('#', '', $hex);
		$color = array();
		
		if(strlen($hex) == 3) 
		{
			$color['R'] = hexdec(substr($hex, 0, 1) . $r);
			$color['G'] = hexdec(substr($hex, 1, 1) . $g);
			$color['B'] = hexdec(substr($hex, 2, 1) . $b);
		}
		else if(strlen($hex) == 6) 
		{
			$color['R'] = hexdec(substr($hex, 0, 2));
			$color['G'] = hexdec(substr($hex, 2, 2));
			$color['B'] = hexdec(substr($hex, 4, 2));
		}
		
		$color['Alpha'] = 100;
		
		return $color;
	}
	
	public static function RGBToHex($rgb) 
	{
		$hex = '#';
		$hex.= str_pad(dechex($rgb['R']), 2, '0', STR_PAD_LEFT);
		$hex.= str_pad(dechex($rgb['G']), 2, '0', STR_PAD_LEFT);
		$hex.= str_pad(dechex($rgb['B']), 2, '0', STR_PAD_LEFT);
		
		return $hex;
	}
}