<?php
/**
 * @version		$Id: helper.php 01 2011-01-11 11:37:09Z maverick $
 * @package		CoreJoomla.Polls
 * @subpackage	Components
 * @copyright	Copyright (C) 2009 - 2012 corejoomla.com, Inc. All rights reserved.
 * @author		Maverick
 * @link		http://www.corejoomla.com/
 * @license		License GNU General Public License version 2 or later
 */

// no direct access
defined('_JEXEC') or die('Restricted access');

class PollsAdminHelper {
	
	function checkModules($position) {
		
        $db = JFactory::getDBO();
        $query = 'SELECT COUNT(*) FROM #__modules WHERE position='.$db->quote($position) . ' AND published=1 and access=0';
        $db->setQuery($query);
        $count = $db->loadResult();
        
        return $count;
    }
    
    function sendEmail($sender_email, $sender_name, $recievers, $subject, $body ){
    	
        foreach ($recievers as $email){
        	
            if(!in_array($email, $sent)) {
            	
                $mail = JFactory::getMailer();
                $sender = array( $sender_email, $sender_name );
                
                $mail->setSender($sender);
                $mail->addRecipient($email);
                $mail->setSubject($subject);
                $mail->setBody($body);
                $mail->send();
                
                $sent[] = $email;
            }
        }
    }
    
    /**
     * Does the third party activities needed after poll creation/vote casting.
     * Currently supporting Alpha User Points points submission,
     * JomSocial Points submission, JomSocial activity stream and
     * Twitter updates using shortened urls from bit.ly and tinyurl.com services.
     *
     * @global <type> $jcp_config
     * @param <type> $action
     * @param <type> $poll
     */
    function do3rdPartyTask($action, $poll) {
    	
        $app = JFactory::getApplication();
        $config = PollsAdminHelper::getConfig();
        $user = JFactory::getUser();
        
//        $menu = &JSite::getMenu();
//        $mnuitems	= $menu->getItems('link', 'index.php?option='.P_APP_NAME.'&view=polls');
//        $itemid = isset($mnuitems[0]) ? '&amp;Itemid='.$mnuitems[0]->id : '';

        switch ($action) {
        	
            case "POLL":
            	
        		if($config[P_POINTS_SYSTEM] != 'none'){
					
					CJFunctions::award_points(
						$config[P_POINTS_SYSTEM], 
						$poll->created_by, 
						array( 
							'function' => $config[P_POINTS_SYSTEM] == 'aup' ? P_POINTS_SYSTEM_AUP_PLUGIN_POLLS : P_POINTS_SYSTEM_JS_PLUGIN_POLLS, 
							'reference' => $poll->id, 
							'info' => 'Poll: '.$poll->id, 
							'points' => $config[P_POLL_POINTS] 
						)
					);
				}
				
				if($config[P_STREAM_NEWPOLL] == "1") {
						
					$link = JRoute::_('index.php?option=com_communitypolls&view=polls&task=viewpoll&id='.$poll->id.":".$poll->alias);
					CJFunctions::stream_activity(
							$config[P_ACTIVITY_STREAM_TYPE],
							$poll->created_by,
							array(
									'command' => P_POINTS_SYSTEM_JS_PLUGIN_POLLS,
									'component' => P_APP_NAME,
									'title' => JText::sprintf('TXT_POLL_CREATED_JS_ACTIVITY_TEXT', $link, $poll->title),
									'href' => $link,
									'description' => $poll->description,
									'length' => 256,
									'icon' => 'components/'.P_APP_NAME.'/assets/images/polls.png',
									'group' => 'Polls'
							)
					);
				}

                // Twitter Tweets Start
                if($config[P_TWITTER_ENABLED] == "1") {
                	
                    PollsHelper::sendToTwitter($poll);
                }
                // Twitter Tweets End
                
                break;
        }
    }
}
?>