<?php

class com_storelocatorInstallerScript
{
        /**
         * Constructor
         *
         * @param   JAdapterInstance  $adapter  The object responsible for running this script
         */
        public function __constructor(JAdapterInstance $adapter)
		{
			
		}
 
        /**
         * Called before any type of action
         *
         * @param   string  $route  Which action is happening (install|uninstall|discover_install)
         * @param   JAdapterInstance  $adapter  The object responsible for running this script
         *
         * @return  boolean  True on success
         */
        public function preflight($route, JAdapterInstance $adapter)
		{
			
		}
 
        /**
         * Called after any type of action
         *
         * @param   string  $route  Which action is happening (install|uninstall|discover_install)
         * @param   JAdapterInstance  $adapter  The object responsible for running this script
         *
         * @return  boolean  True on success
         */
        public function postflight($route, JAdapterInstance $adapter)
		{
			
		}
 
        /**
         * Called on installation
         *
         * @param   JAdapterInstance  $adapter  The object responsible for running this script
         *
         * @return  boolean  True on success
         */
        public function install(JAdapterInstance $adapter)
		{
			
		?>
            <hr />
            <h2><a href="http://www.sysgenmedia.com/?ref=storelocator" title="Sysgen Media LLC"><img src="https://www.sysgenmedia.com/images/sysgen_logo.jpg" alt="Sysgen Media LLC" /></a></h2>
            <h3>Sysgen Media Joomla! Store Locator v<?php echo $adapter->get('manifest')->version; ?></h3>
            <p style="color:#C00"><strong>Basic Setup: The Store Locator is a Component. To use the this component, you must create at lease one new Menu Item that points to the Store Locator. </strong>            </p>
            <p><em>To begin adding locations:</em></p>
            <ol>
              <li>Navigate to <a href="?option=com_storelocator">Components -&gt; Store Locator</a>. </li>
              <li>Click the &quot;New&quot; button and fill out the Location Details. </li>
              <li>Click &quot;calculate coordinates&quot; to determine the Latitude and Longitude for the Location.<br />
            You also have the option of manually specifying the coordinates.</li>
            </ol>
<p>The Store Locator Joomla! Component is developed by <a href="http://www.sysgenmedia.com" target="_blank">Sysgen Media LLC</a>.<br />
              For support inquires please check our <a href="https://www.sysgenmedia.com/forum">Forums</a> or if you have an active subscription <a href="https://www.sysgenmedia.com/submit-a-ticket">Submit a Ticket</a></p>
              
<?php
			
		}
 
        /**
         * Called on update
         *
         * @param   JAdapterInstance  $adapter  The object responsible for running this script
         *
         * @return  boolean  True on success
         */
        public function update(JAdapterInstance $adapter)
		{
			$xml = @JFactory::getXML(JPATH_SITE .'/administrator/components/com_storelocator/storelocator.xml');
			$version = @(string)$xml->version;
			
			?>
            <hr />
            <h2><a href="http://www.sysgenmedia.com/?ref=storelocator" title="Sysgen Media LLC"><img src="https://www.sysgenmedia.com/images/sysgen_logo.jpg" alt="Sysgen Media LLC" /></a></h2>
            <h3>Sysgen Media Joomla! Store Locator v<?php echo $adapter->get('manifest')->version; ?></h3>
            <p style="color:#C00"><strong>Basic Setup: The Store Locator is a Component. To use the this component, you must create at lease one new Menu Item that points to the Store Locator. </strong>            </p>
            <p><em>To begin adding locations:</em></p>
            <ol>
              <li>Navigate to <a href="?option=com_storelocator">Components -&gt; Store Locator</a>. </li>
              <li>Click the &quot;New&quot; button and fill out the Location Details. </li>
              <li>Click &quot;calculate coordinates&quot; to determine the Latitude and Longitude for the Location.<br />
            You also have the option of manually specifying the coordinates.</li>
            </ol>
<p>The Store Locator Joomla! Component is developed by <a href="http://www.sysgenmedia.com" target="_blank">Sysgen Media LLC</a>.<br />
              For support inquires please check our <a href="https://www.sysgenmedia.com/forum">Forums</a> or if you have an active subscription <a href="https://www.sysgenmedia.com/submit-a-ticket">Submit a Ticket</a></p>
              
<?php
		}
 
        /**
         * Called on uninstallation
         *
         * @param   JAdapterInstance  $adapter  The object responsible for running this script
         */
        public function uninstall(JAdapterInstance $adapter)
		{
			// Keep Data on Uninstall?
			$params = JComponentHelper::getParams( 'com_storelocator' );
			$keep_data = $params->get( 'keep_data', 1 );
			
			if (!$keep_data)
			{
				echo "Removing Database Tables";
				
				$db = JFactory::getDBO();
				
				$db->setQuery("DROP TABLE IF EXISTS `#__storelocator_locations`");
				$db->execute();
				
				$db->setQuery("DROP TABLE IF EXISTS `#__storelocator_cats`");
				$db->execute();
				
				$db->setQuery("DROP TABLE IF EXISTS `#__storelocator_marker_types`");
				$db->execute();
								
				$db->setQuery("DROP TABLE IF EXISTS `#__storelocator_tags`");
				$db->execute();
				
				$db->setQuery("DROP TABLE IF EXISTS `#__storelocator_log_search`");
				$db->execute();			
			
			}
			
			echo '<h4>Thank you for using the Store Locator Joomla Component, developed by <a href="http://www.sysgenmedia.com" target="_blank">Sysgen Media LLC</a> - <em>Joomla Web Design, Hosting and Custom Component Development</em></h4>';
			
		}
}