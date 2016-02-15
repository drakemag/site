<?php
/**
 * @package     Joomla.Site
 * @subpackage  Layout
 *
 * @copyright   Copyright (C) 2005 - 2013 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;

$doTask   = $displayData['doTask'];
$class    = $displayData['class'];
$text     = $displayData['text'];
$btnClass = $displayData['btnClass'];

// Success buttons
$successButtons[] = '';
//$successButtons[] = 'icon-save';

// Primary buttons
$primaryButtons[] = 'icon-list-view';
$primaryButtons[] = 'icon-edit';

if(in_array($class, $primaryButtons)) {
	$btnClass .= ' btn-primary';
	$class .= ' icon-white';
}
if(in_array($class, $successButtons)) {
	$btnClass .= ' btn-success';
	$class .= ' icon-white';
}

?>
<button onclick="<?php echo $doTask; ?>" class="<?php echo $btnClass; ?>">
	<span class="<?php echo trim($class); ?>"></span>
	<?php echo $text; ?>
</button>
