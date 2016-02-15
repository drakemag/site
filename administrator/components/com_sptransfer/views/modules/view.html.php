<?php
/**
 * @package		SP Transfer
 * @subpackage	Components
 * @copyright	SP CYEND - All rights reserved.
 * @author		SP CYEND
 * @link		http://www.cyend.com
 * @license GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
 */

// No direct access.
defined('_JEXEC') or die;

jimport('joomla.application.component.view');

/**
 * View class for a list of modules.
 *
 * @package		Joomla.Administrator
 * @subpackage	com_modules
 * @since		1.6
 */
class SPTransferViewModules extends JViewLegacy
{
	protected $items;
	protected $pagination;
	protected $state;

	/**
	 * Display the view
	 */
	public function display($tpl = null)
	{
		$this->items		= $this->get('Items');
		$this->pagination	= $this->get('Pagination');
		$this->state		= $this->get('State');
        $this->positions = $this->get('Positions'); 
        $this->languages = $this->get('Languages');
        $this->assetgroups = $this->get('AssetGroups');
        $this->clientoptions = $this->get('ClientOptions');
        $this->stateoptions = $this->get('StateOptions');
        $this->modules = $this->get('Modules'); 

		// Check for errors.
		if (count($errors = $this->get('Errors'))) {
			JError::raiseError(500, implode("\n", $errors));
			return false;
		}

		// Check if there are no matching items
		if(!count($this->items)){
			JFactory::getApplication()->enqueueMessage(
				JText::_('COM_MODULES_MSG_MANAGE_NO_MODULES')
				, 'warning'
			);
		}

        $lang =  JFactory::getLanguage();
        $lang->load('com_modules');
        
        $js = '
		function findChecked(checkbox, stub) {
            var chk_arr =  document.getElementsByName("cid[]");
            var chklength = chk_arr.length;             
            var id_arr = [];
            for(k=0;k< chklength;k++)
            {
                if (chk_arr[k].checked == true) {
                    id_arr.push( chk_arr[k].value );                
                }
            }         
            return id_arr;
        }';

        $doc = JFactory::getDocument();
        $doc->addScriptDeclaration($js);
        
		parent::display($tpl);
	}

	
}
