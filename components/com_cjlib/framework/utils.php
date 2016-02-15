<?php
/**
 * @package     corejoomla.administrator
 * @subpackage  com_cjlib
 *
 * @copyright   Copyright (C) 2009 - 2014 corejoomla.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */
defined('_JEXEC') or die;

class CjLibUtils
{
	/**
	 * word-sensitive substring function with html tags awareness
	 *
	 * @param string text The text to cut
	 * @param int len The maximum length of the cut string
	 * @param array Array of tags to exclude
	 *
	 * @return string The modified html content
	 */
	public static function substrws( $text, $len=180, $tags=array()) 
	{
		if(function_exists('mb_strlen'))
		{
			if( (mb_strlen($text, 'UTF-8') > $len) ) 
			{
				$whitespaceposition = mb_strpos($text, ' ', $len, 'UTF-8')-1;
				if( $whitespaceposition > 0 ) 
				{
					$chars = count_chars(mb_substr($text, 0, $whitespaceposition + 1, 'UTF-8'), 1);
					if (!empty($chars[ord('<')]) && $chars[ord('<')] > $chars[ord('>')])
					{
						$whitespaceposition = mb_strpos($text, '>', $whitespaceposition, 'UTF-8') - 1;
					}
						
					$text = mb_substr($text, 0, $whitespaceposition + 1, 'UTF-8');
				}
					
				// close unclosed html tags
				if( preg_match_all("|<([a-zA-Z]+)|",$text,$aBuffer) ) 
				{
					if( !empty($aBuffer[1]) ) 
					{
						preg_match_all("|</([a-zA-Z]+)>|",$text,$aBuffer2);
						if( count($aBuffer[1]) != count($aBuffer2[1]) ) 
						{
							foreach( $aBuffer[1] as $index => $tag ) 
							{
								if( empty($aBuffer2[1][$index]) || $aBuffer2[1][$index] != $tag)
								{
									$text .= '</'.$tag.'>';
								}
							}
						}
					}
				}
			}
		} 
		else 
		{
			if( (strlen($text) > $len) ) 
			{
				$whitespaceposition = strpos($text, ' ', $len)-1;
				if( $whitespaceposition > 0 ) {
						
					$chars = count_chars(substr($text, 0, $whitespaceposition + 1), 1);
					if ($chars[ord('<')] > $chars[ord('>')])
					{
						$whitespaceposition = strpos($text, '>', $whitespaceposition) - 1;
					}
						
					$text = substr($text, 0, $whitespaceposition + 1);
				}
					
				// close unclosed html tags
				if( preg_match_all("|<([a-zA-Z]+)|",$text,$aBuffer) ) 
				{
					if( !empty($aBuffer[1]) ) 
					{
						preg_match_all("|</([a-zA-Z]+)>|",$text,$aBuffer2);
						if( count($aBuffer[1]) != count($aBuffer2[1]) ) 
						{
							foreach( $aBuffer[1] as $index => $tag ) 
							{
								if( empty($aBuffer2[1][$index]) || $aBuffer2[1][$index] != $tag)
								{
									$text .= '</'.$tag.'>';
								}
							}
						}
					}
				}
			}
		}
	
		return preg_replace('#<p[^>]*>(\s|&nbsp;?)*</p>#', '', $text);;
	}

	/**
	 * Convert special characters to HTML entities with UTF-8 encoding.
	 * 
	 * @param string $var content to be escaped
	 */
	public static function escape($var)
	{
		return htmlspecialchars($var, ENT_COMPAT, 'UTF-8');
	}
	
	/**
	 * Returns unicode alias string from the <code>title</code> passed as an argument. If the Joomla version is less than 1.6, the function will gracefully degrades and outputs normal alias.
	 *
	 * @param string $title
	 */
	public static function getUrlSafeString($title){

		if (JFactory::getConfig()->get('unicodeslugs') == 1) {
		
			return JFilterOutput::stringURLUnicodeSlug($title);
		} else {
		
			return JFilterOutput::stringURLSafe($title);
		}
	}

	/**
	 * Gets the ip address of the user from request
	 *
	 * @return string ip address
	 */
	public static function getUserIpAddress() {

		$ip = '';

		if( !empty($_SERVER['HTTP_X_FORWARDED_FOR']) AND strlen($_SERVER['HTTP_X_FORWARDED_FOR'])>6 ){
				
			$ip = strip_tags($_SERVER['HTTP_X_FORWARDED_FOR']);
		}elseif( !empty($_SERVER['HTTP_CLIENT_IP']) AND strlen($_SERVER['HTTP_CLIENT_IP'])>6 ){
				
			$ip = strip_tags($_SERVER['HTTP_CLIENT_IP']);
		}elseif(!empty($_SERVER['REMOTE_ADDR']) AND strlen($_SERVER['REMOTE_ADDR'])>6){
				
			$ip = strip_tags($_SERVER['REMOTE_ADDR']);
		}

		return trim($ip);
	}

	/**
	 * Gets the formatted number in the format 10, 100, 1000, 10k, 20.1k etc
	 * 
	 * @param integer $num number to format
	 * @return string formatted number
	 */
	public static function formatNumber ($num)
	{
		$num = (int) $num;
		if ($num < 1000)
		{
			return $num;
		}
	
		if ($num < 10000)
		{
			return substr($num, 0, 1).','.substr($num, 1);
		}
	
		return round($num/1000, 1).'k';
	}
	

	/**
	 * Generate a random character string
	 *
	 * @param int $length length of the string to be generated
	 * @param string $chars characters to be considered, default alphanumeric characters.
	 *
	 * @return string randomly generated string
	 */
	public static function getRandomKey($length = 32, $chars = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz1234567890'){
	
		// Length of character list
		$chars_length = (strlen($chars) - 1);
			
		// Start our string
		$string = $chars{rand(0, $chars_length)};
	
		// Generate random string
		for ($i = 1; $i < $length; $i = strlen($string))
		{
			// Grab a random character from our list
			$r = $chars{rand(0, $chars_length)};
	
			// Make sure the same two characters don't appear next to each other
			if ($r != $string{$i - 1}) $string .=  $r;
		}
	
		// Return the string
		return $string;
	}
	
	public static function getCurrentUrl()
	{
		$uri = JFactory::getURI();
		$absolute_url = $uri->toString();
		
		return JRoute::_($absolute_url);
	}
}
