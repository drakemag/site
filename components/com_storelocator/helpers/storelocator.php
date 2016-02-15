<?php
/**
 * @version     2.0.0
 * @package     com_storelocator
 * @copyright   Copyright (C) 2013. Sysgen Media LLC. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 * @author      Sysgen Media <support@sysgenmedia.com> - http://www.sysgenmedia.com
 */

defined('_JEXEC') or die;

abstract class StorelocatorHelper
{
	public static function getArticle($articleid) {
		
		$articleid = (int)$articleid;

        //  Make sure parameter is set and is greater than zero
        if ($articleid > 0) {

            //  Build Query
            $query = "SELECT * FROM #__content WHERE id = $articleid";

            //  Load query into an object
            $db = JFactory::getDBO();
            $db->setQuery($query);
            return $db->loadObject();
        }

        //
        return null;
    }

}

