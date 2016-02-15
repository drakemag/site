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
// gets the content
	$db_blog =& JFactory::getDBO();
	$sql_blog = "SELECT * 
				FROM #__content 
				WHERE sectionid = 1 
				AND catid != 26
				AND state = 1
				ORDER BY publish_up DESC 
				LIMIT 1"; 
	$db_blog->setQuery($sql_blog); 
	$results_blog = $db_blog->loadObjectList(); 

	function admiralsTruncate_blog($string_blog, $length_blog){
		settype($string_blog, 'string');
		settype($length_blog, 'integer');
		settype($output_blog, 'string');
		for($a_blog = 0; $a_blog < $length_blog AND $a_blog < strlen($string_blog); $a_blog++){
			$output_blog .= $string_blog[$a_blog];
		}
		return($output_blog);
	}
 

	echo "<div style=\"border:2px solid #c90000; padding:10px;\">";
	echo "<h1>Lastest Blog</h1>";
	if(count($results_blog)) {
	  foreach($results_blog as $r_blog) {
		$link = ContentHelperRoute::getArticleRoute( $r_blog->id . ':' . $r_blog->alias, $r_blog->catid, $r_blog->sectionid );
		echo "			<h4><a href=\"{$link}\">" . $r_blog->title . "</a></h4>";
		echo admiralsTruncate_blog($r_blog->introtext, 400) . "&hellip;";
		echo "<a href=\"{$link}\" class=\"right\"\">read more</a>";
		//			&#8230; - also an ellipsis code			
		//			echo $row["introtext"];
	  } 
	} 
	echo "</div>";



/*
$_cached_content = ob_get_clean();
$_cache->store( $_cached_content, 'php_right_latest_blog', 'my_php' );
echo $_cached_content;
*/
?>


