<?php 
/** 
 * @package RESPONSIVIZER::EDITORTEMPLATE::administrator::components::com_responsivizer
 * @subpackage views
 * @subpackage editortemplate
 * @subpackage tmpl
 * @author Joomla! Extensions Store
 * @copyright (C) 2014 - Joomla! Extensions Store
 * @license GNU/GPLv2 http://www.gnu.org/licenses/gpl-2.0.html  
 */
defined ( '_JEXEC' ) or die ( 'Restricted access' ); 
?>
<iframe scrolling="no" data-jversion="<?php echo intval(version_compare(JVERSION, '3.2', '<')); ?>" src="index.php?option=com_templates&amp;view=template&amp;tmpl=component&amp;rvzr=1&amp;id=<?php echo $this->templateID;?>" id="com_templates" name="com_templates"></iframe>
<form action="index.php" method="post" name="adminForm" id="adminForm">
	<input type="hidden" name="option" value="<?php echo $this->option;?>" />
	<input type="hidden" name="task" value="editortemplate.display" />
</form>
