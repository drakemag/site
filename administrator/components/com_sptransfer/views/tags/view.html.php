<?php

/**
 * @package		SP Trasnfer
 * @subpackage	Components
 * @copyright	SP CYEND - All rights reserved.
 * @author		SP CYEND
 * @link		http://www.cyend.com
 * @license GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
 */
defined('_JEXEC') or die;

include_once JPATH_ADMINISTRATOR . '/components/com_tags/helpers/tags.php';
include_once JPATH_ADMINISTRATOR . '/components/com_tags/views/tags/view.html.php';

class SPTransferViewTags extends TagsViewTags {

    public function display($tpl = null) {
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
