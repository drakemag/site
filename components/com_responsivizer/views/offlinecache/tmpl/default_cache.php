<?php
/** 
 * @package RESPONSIVIZER::OFFLINECACHE::components::com_responsivizer
 * @subpackage views
 * @subpackage offlinecache
 * @subpackage tmpl
 * @author Joomla! Extensions Store
 * @copyright (C) 2014 - Joomla! Extensions Store
 * @license GNU/GPLv2 http://www.gnu.org/licenses/gpl-2.0.html
 */
defined('_JEXEC') or die('Restricted access');
?>
CACHE MANIFEST
# ETag: <?php echo md5($this->id) . gmdate('Hi') . intval(gmdate('s') / 10) . PHP_EOL;?>
CACHE:
<?php foreach ($this->resources as $resource) {
	echo $resource . PHP_EOL;
}
// Offline cache page
echo $this->baseURI . 'templates/responsivizer/images/offline_manifest.png' . PHP_EOL;
echo $this->baseURI . 'templates/responsivizer/images/offline_warning.png' . PHP_EOL;
echo $this->baseURI . 'templates/responsivizer/css/offline.css' . PHP_EOL;
?>

NETWORK:
*

FALLBACK:
/ index.php?tmpl=appcacheoffline

SETTINGS:
prefer-online