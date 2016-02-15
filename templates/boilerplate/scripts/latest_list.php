<?php
// No direct access.
defined( '_JEXEC' ) or die( 'Restricted access' ); 

/*
20110705 - UNCOMMENT THIS THE CODE AT THE BOTTOM - IT CACHES WHAT'S INSIDE
$_cache = JCache::getInstance();
$_cache->setLifeTime( 864000 ); // 10 days
$_cached_content = $_cache->get( 'php_right_latest_blog', 'my_php' );
if ( $_cached_content ) {
	echo $_cached_content;
	return;
}
ob_start();
*/





	require_once JPATH_ROOT .DS. 'components' .DS. 'com_content' .DS. 'helpers' .DS. 'route.php';
	$db_blog2 =& JFactory::getDBO();
	$sql_blog2 = "SELECT * 
				FROM #__content 
				WHERE sectionid = 1 
				ORDER BY publish_up DESC 
				LIMIT 6"; 
	$db_blog2->setQuery($sql_blog2); 
	$results_blog2 = $db_blog2->loadObjectList(); 
 
	echo "<div style=\"border:2px solid #c90000; padding:10px;\">";
	echo "<h1>Lastest List</h1>";
	echo "<ul>";
	if(count($results_blog2)) {
	  foreach($results_blog2 as $r_blog2) {
		$link2 = ContentHelperRoute::getArticleRoute( $r_blog2->id );
		echo "			<li><a href=\"{$link2}\">" . $r_blog2->title . "</a></li>";
	  } 
	} 
	echo "</ul>";




/*
$_cached_content = ob_get_clean();
$_cache->store( $_cached_content, 'php_right_latest_blog', 'my_php' );
echo $_cached_content;
*/
?>


