<?php
// namespace templates\responsivizer\framework\html;
/**
 *
 * @package RESPONSIVIZER::FRAMEWORK::templates::responsivizer
 * @subpackage framework
 * @subpackage html
 * @author Joomla! Extensions Store
 * @copyright (C) 2013 - Joomla! Extensions Store
 * @license GNU/GPLv2 http://www.gnu.org/licenses/gpl-2.0.html
 */
defined ( '_JEXEC' ) or die ( 'Restricted access' );

/**
 * Offline cache responsibilities interface
 *
 * @package RESPONSIVIZER::FRAMEWORK::templates::responsivizer
 * @subpackage framework
 * @subpackage html
 * @since 2.1
 */
class ResponsivizerHtml extends JHtml {
	// Reset include paths to allow icons override and replacement
	public static function resetPaths() {
		self::$includePaths = array ();
	}
}