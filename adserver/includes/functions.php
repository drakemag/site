<?php

	function mysql_prep( $value ) {
		$magic_quotes_active = get_magic_quotes_gpc();
		$new_enough_php = function_exists( "mysql_real_escape_string" ); // i.e. PHP >= v4.3.0
		if( $new_enough_php ) { // PHP v4.3.0 or higher
			// undo any magic quote effects so mysql_real_escape_string can do the work
			if( $magic_quotes_active ) { $value = stripslashes( $value ); }
			$value = mysql_real_escape_string( $value );
		} else { // before PHP v4.3.0
			// if magic quotes aren't already on then add slashes manually
			if( !$magic_quotes_active ) { $value = addslashes( $value ); }
			// if magic quotes are active, then the slashes already exist
		}
		return $value;
	}

	function redirect_to( $location = NULL ) {
		if ($location != NULL) {
			header("Location: {$location}");
			exit;
		}
	}

	function confirm_query($result_set) {
		if (!$result_set) {
			die("Database query failed: " . mysql_error());
		}
	}
	
	function display_errors($error_array) {
		echo "<p class=\"errors\">";
		echo "Please review the following fields:<br />";
		foreach($error_array as $error) {
			echo " - " . $error . "<br />";
		}
		echo "</p>";
	}




/****** return all ads listed results *******************************/
function return_ads_all($result){
		echo '<table><tr>';
			echo '<th>id</th>';
			echo '<th>edit</th>';
			echo '<th>del?</th>';
			echo '<th>status</th>';
			echo '<th>company</th>';
			echo '<th>ad_name</th>';
			echo '<th>size</th>';
			echo '<th>image_name</th>';
			echo '<th>alt_text</th>';
			echo '<th>link_url</th>';
			echo '<th>link_target</th>';
			echo '<th>custom code</th>';
			echo '<th>created_date</th>';
			echo '<th>start_date</th>';
			echo '<th>end_date</th>';
		echo '</tr>';
	while ($row = mysqli_fetch_array($result)) {
		echo '<tr>';
			echo '<td>' . $row["id"] . '</td>';
			echo '<td> 
					<form name="edit_record" method="post" action="edit_ad.php?edit_record=' . $row["id"] . '">
						<input type="hidden" name="edit_record" value="' . $row["id"] . '" />
						<input class="edit_record_button" type="submit" value="" />
					</form>
				</td>';
			echo '<td>
					<form name="delete_record_from_table" method="post" onclick="javascript:return confirm(\'Are you sure you want to delete this record?\n\n' 
						. 'id = ' . $row["id"] . '\n' 
						. 'company = ' . $row["company"] . '\n'
						. 'ad_name = ' . $row["ad_name"] . '\n'
						. 'status = ' . $row["status"] . '\n'
						. 'size = ' . $row["size"] . '\n'
						. '\')" action="#?del_record=' . $row["id"] . '">
						<input type="hidden" name="del_record" value="' . $row["id"] . '">
						<input class="del_button" type="image" value="">
					</form>
				</td>';
			echo '<td><div class="publish_container">';
				$current_pub_state = $row["status"];
				$pub_row_id = $row["id"];
				published_state($current_pub_state,$pub_row_id);
			echo '</div></td>';
			echo '<td>' . $row["company"] . '</td>';
			echo '<td>' . $row["ad_name"] . '</td>';
			echo '<td>' . ad_size($row["size"]) . '</td>';
			echo '<td class="banner_img"><img src="/adserver/images/banners/' . $row["image_name"] . '" alt="' . $row["alt_text"] . '" /></td>';
			echo '<td>' . $row["alt_text"] . '</td>';
			echo '<td>' . $row["link_url"] . '</td>';
			echo '<td>' . $row["link_target"] . '</td>';
			echo '<td>' . htmlspecialchars($row["custom_code"]) . '</td>';
			echo '<td>' . $row["created_date"] . '</td>';
			echo '<td>' . $row["start_date"] . '</td>';
			echo '<td>' . $row["end_date"] . '</td>';
		echo '</tr>';
	}
	echo '</table>';
}

function published_state($current_pub_state,$pub_row_id){
	if($current_pub_state == 1){
		//echo '<img src="/images/icon-32-publish.png" alt="published" />';
		echo '
			<form name="edit_record" method="post" action="#?unpublish=' . $pub_row_id . '">
				<input type="hidden" name="unpublish_record" value="' . $pub_row_id . '" />
				<input class="unpublish_button" type="submit" value="" />
			</form>';
	}elseif($current_pub_state == 0){
		//echo '<img src="/images/icon-32-unpublish.png" alt="unpublished" />';
		echo '
			<form name="edit_record2" method="post" action="#?publish=' . $pub_row_id . '">
				<input type="hidden" name="publish_record" value="' . $pub_row_id . '" />
				<input class="publish_button" type="submit" value="" />
			</form>';
	};

}

function ad_size($size_in){
	switch($size_in){
		case '1':
			return 'rectangle (300x250)';
			break;
		case '2':
			return 'skyscraper (160x600)';
			break;
		case '3':
			return 'forum ad (329x131)';
			break;
	}
}

function banners_list($exclude_list,$dir_path) {
    global $exclude_list, $dir_path;
    $directories = array_diff(scandir($dir_path), $exclude_list);

    echo "<select 
          name='image_name' 
          tabindex=''
          >";
    echo "	<option value=''> -- select -- </option>";

        foreach($directories as $entry) {
          if(is_file($dir_path.$entry)) {
            echo "<option value=".$entry.">".$entry."</option>";
          }
        }
    echo "</select>";

}
function edit_banners_list($exclude_list,$dir_path,$current_ad_image) {
    global $exclude_list, $dir_path;
    $directories = array_diff(scandir($dir_path), $exclude_list);

    echo "<select 
          name='image_name' 
          tabindex='7'
          >";
    echo "	<option value='". $current_ad_image . "'>". $current_ad_image . "</option>";

        foreach($directories as $entry) {
          if(is_file($dir_path.$entry)) {
            echo "<option value=".$entry.">".$entry."</option>";
          }
        }
    echo "</select>";

}

/****** Advert serving function *******************************/
function advert_func($no_of_ads,$ad_layout,$pos,$advert_size) {

		// CALL THE DB CONNECTION AND FUNCTION FILES
		// THESE ARE INSERTED INTO THE TOP OF THE PHP FILE TO OUTPUT
		require("connection.php"); 
		require("constants.php"); 

	    //MAKE THE QUERY:
		$current_date = date("Y-m-d H:i:s"); 
	    $query_ads_forum = "
	    	SELECT * FROM ads 
	    	WHERE 
	    	size = '" . $advert_size . "' 
	    	and status = '1' 
	    	and hide <> '1'
	    	and start_date <= '" . $current_date . "' 
	    	and end_date >= '" . $current_date . "'
	    	ORDER BY RAND() LIMIT " . $no_of_ads . "
	    	" or die("Error in the consult.." . mysqli_error($connection));
		//echo '$query_ads_forum = '. $query_ads_forum . '<hr />';

	    //EXECUTE THE QUERY
	    $result_ads_forum = mysqli_query($connection, $query_ads_forum);             
		while( $row = mysqli_fetch_assoc($result_ads_forum)){ 

		if($row['custom_code'] != ""){
			if($row['size'] == "2"){
				echo '<style>iframe { overflow:hidden; }</style><iframe src="/adserver/images/' . $row['custom_code'] . '/index.html" style="border: 0; width: 160px; height: 600px; margin: 0 0 10px;" scrolling="no"></iframe>'; 
			}else{
				echo '<style>iframe { overflow:hidden; }</style><iframe src="/adserver/images/' . $row['custom_code'] . '/index.html" style="border: 0; width:300px; height: 250px; margin: 0 0 10px;" scrolling="no"></iframe>'; 
			};
		}else{
		
		
		echo '
	      <div class="advert rectangle">
		      <a 
		        href="' . $row['link_url'] . '" 
		        onClick="
					ga(\'send\', \'event\', { 
						eventCategory: \'Ad - ' . ad_size($row['size']) . '\', 
						eventLabel: \'' . $row['company'] . ' - ' . $row['ad_name'] . '\', 
						eventAction: \'Click\'
					});
				" 
		        target="' . $row['link_target'] . '" 
		        rel="nofollow" 
		      > 
		        <img 
		          src="/adserver/images/banners/' . $row['image_name'] . '" 
		          alt="' . $row['alt_text'] . '" 
		          onload="
					ga(\'send\', \'event\', { 
						eventCategory: \'Ad - ' . ad_size($row['size']) . '\', 
						eventLabel: \'' . $row['company'] . ' - ' . $row['ad_name'] . '\', 
						eventAction: \'Impression\' 
					});
				" 
		        /> 
		      </a>
		     </div>
	      ';
			};
		 };
};

/****** ORIGINAL (AND BROKEN) VERSION - RGS 20150529 - Advert serving function *******************************
function advert_func($no_of_ads,$ad_layout,$pos,$advert_size) {

		// CALL THE DB CONNECTION AND FUNCTION FILES
		// THESE ARE INSERTED INTO THE TOP OF THE PHP FILE TO OUTPUT
		require("connection.php"); 
		require("constants.php"); 

	    //MAKE THE QUERY:
		$current_date = date("Y-m-d H:i:s"); 
	    $query_ads_forum = "
	    	SELECT * FROM ads 
	    	WHERE 
	    	size = '" . $advert_size . "' 
	    	and status = '1' 
	    	and hide <> '1'
	    	and start_date <= '" . $current_date . "' 
	    	and end_date >= '" . $current_date . "'
	    	ORDER BY RAND() LIMIT " . $no_of_ads . "
	    	" or die("Error in the consult.." . mysqli_error($connection));
		//echo '$query_ads_forum = '. $query_ads_forum . '<hr />';

	    //EXECUTE THE QUERY
	    $result_ads_forum = mysqli_query($connection, $query_ads_forum);             
		while( $row = mysqli_fetch_assoc($result_ads_forum)){ 
		echo '
	      <div class="advert rectangle">
		      <a 
		        href="' . $row['link_url'] . '" 
		        onClick="_gaq.push([\'_trackEvent\', \'Ad - ' . ad_size($row['size']) . '\', \'Click\', \'' . $row['company'] . ' - ' . $row['ad_name'] . '\',1.00,true]);" 
		        target="' . $row['link_target'] . '" 
		        rel="nofollow" 
		      > 
		        <img 
		          src="/adserver/images/banners/' . $row['image_name'] . '" 
		          alt="' . $row['alt_text'] . '" 
		          onload="_gaq.push([\'_trackEvent\', \'Ad - ' . ad_size($row['size']) . '\', \'Impression\', \'' . $row['company'] . ' - ' . $row['ad_name'] . '\',1.00,true]);" 
		        /> 
		      </a>
		     </div>
	      ';

		 };
};
****/

/****** Advert serving function *******************************/
function advert_sky_func($no_of_ads,$ad_layout,$pos,$advert_size) {

	$cachefile = "./adserver/cache/".$pos.".html";

	 	//OPTION #2 - REFRESH CACHE ON TIME INTERVALS
		 // 5 minutes

	        $cachetime = 15 * 1; 

	        // Serve from the cache if it is younger than $cachetime

	        if (file_exists($cachefile) && 
	           (time() - $cachetime < filemtime($cachefile))) 
	        {


	        	include($cachefile);

	        	echo "<!-- From cache generated ".date('H:i', filemtime($cachefile))." -->";


	        	exit;

	        }


			 // start the output buffer
	        ob_start(); 

		// CALL THE DB CONNECTION AND FUNCTION FILES
		// THESE ARE INSERTED INTO THE TOP OF THE PHP FILE TO OUTPUT
		require("connection.php"); 
		require("constants.php"); 

	    //MAKE THE QUERY:
		$current_date = date("Y-m-d H:i:s"); 
	    $query_ads_sky = "
	    	SELECT * FROM ads 
	    	WHERE 
	    	size = '" . $advert_size . "' 
	    	and status = '1' 
	    	and hide <> '1'
	    	and start_date <= '" . $current_date . "' 
	    	and end_date >= '" . $current_date . "'
	    	ORDER BY RAND() LIMIT " . $no_of_ads . "
	    	" or die("Error in the consult.." . mysqli_error($connection));
		//echo '$query_ads_sky = '. $query_ads_sky . '<hr />';

	    //EXECUTE THE QUERY
	    $result_ads_sky = mysqli_query($connection, $query_ads_sky);             
		while( $row = mysqli_fetch_assoc($result_ads_sky)){ 
		echo '
	      <div class="advert rectangle">
		      <a 
		        href="' . $row['link_url'] . '" 
		        onClick="_gaq.push([\'_trackEvent\', \'Ad - ' . ad_size($row['size']) . '\', \'Click\', \'' . $row['company'] . ' - ' . $row['ad_name'] . '\',1.00,true]);" 
		        target="' . $row['link_target'] . '" 
		        rel="nofollow" 
		      > 
		        <img 
		          src="/adserver/images/banners/' . $row['image_name'] . '" 
		          alt="' . $row['alt_text'] . '" 
		          onload="_gaq.push([\'_trackEvent\', \'Ad - ' . ad_size($row['size']) . '\', \'Impression\', \'' . $row['company'] . ' - ' . $row['ad_name'] . '\',1.00,true]);" 
		        /> 
		      </a>
		     </div>
	      ';

		 };

	    /* AD SIZES
	        1 - rectangle (300x250)
	        2 - skyscraper (160x600)
	        3 - forum ad (329x131)
	        4 - leaderboard (728x90)
	    */
        // open the cache file for writing

        $fp = fopen($cachefile, 'w');

		 // save the contents of output buffer to the file
        fwrite($fp, ob_get_contents());


		 // close the file
        fclose($fp);

		 // Send the output to the browser
        ob_end_flush();

};


/****** FORUMS - RECTANGLE *******************************/
function advert_forum_rect_func($no_of_ads,$ad_layout,$pos,$advert_size) {

		// CALL THE DB CONNECTION AND FUNCTION FILES
		// THESE ARE INSERTED INTO THE TOP OF THE PHP FILE TO OUTPUT
		require("connection.php"); 
		require("constants.php"); 

	    //MAKE THE QUERY:
		$current_date = date("Y-m-d H:i:s"); 
	    $query_ads_forum = "
	    	SELECT * FROM ads 
	    	WHERE 
	    	size = '" . $advert_size . "' 
	    	and status = '1' 
	    	and hide <> '1'
	    	and start_date <= '" . $current_date . "' 
	    	and end_date >= '" . $current_date . "'
	    	ORDER BY RAND() LIMIT " . $no_of_ads . "
	    	" or die("Error in the consult.." . mysqli_error($connection));
		//echo '$query_ads_forum = '. $query_ads_forum . '<hr />';

	    //EXECUTE THE QUERY
	    $result_ads_forum = mysqli_query($connection, $query_ads_forum);             
	    echo '<ul>';
			while( $row = mysqli_fetch_assoc($result_ads_forum)){ 
			echo '
				<li class="ad_rectangle">
					<a 
						href="' . $row['link_url'] . '" 
						onClick="_gaq.push([\'_trackEvent\', \'Ad - ' . ad_size($row['size']) . '\', \'Click\', \'' . $row['company'] . ' - ' . $row['ad_name'] . '\',1.00,true]);" 
						target="' . $row['link_target'] . '" 
						rel="nofollow" 
					> 
					<img 
						src="/adserver/images/banners/' . $row['image_name'] . '" 
						alt="' . $row['alt_text'] . '" 
						onload="_gaq.push([\'_trackEvent\', \'Ad - ' . ad_size($row['size']) . '\', \'Impression\', \'' . $row['company'] . ' - ' . $row['ad_name'] . '\',1.00,true]);" 
					/> 
					</a>
			 	</li>
			  ';
		};
		echo '</ul>';
};

/****** FORUMS - LEADERBOARD *******************************/
function advert_forum_leader_func($no_of_ads,$ad_layout,$pos,$advert_size) {

		// CALL THE DB CONNECTION AND FUNCTION FILES
		// THESE ARE INSERTED INTO THE TOP OF THE PHP FILE TO OUTPUT
		require("connection.php"); 
		require("constants.php"); 

	    //MAKE THE QUERY:
		$current_date = date("Y-m-d H:i:s"); 
	    $query_ads_forum = "
	    	SELECT * FROM ads 
	    	WHERE 
	    	size = '" . $advert_size . "' 
	    	and status = '1' 
	    	and hide <> '1'
	    	and start_date <= '" . $current_date . "' 
	    	and end_date >= '" . $current_date . "'
	    	ORDER BY RAND() LIMIT " . $no_of_ads . "
	    	" or die("Error in the consult.." . mysqli_error($connection));
		//echo '$query_ads_forum = '. $query_ads_forum . '<hr />';

	    //EXECUTE THE QUERY
	    $result_ads_forum = mysqli_query($connection, $query_ads_forum);             
	    echo '<ul>';
			while( $row = mysqli_fetch_assoc($result_ads_forum)){ 
			echo '
				<li class="ad_leader">
					<a 
						href="' . $row['link_url'] . '" 
						onClick="_gaq.push([\'_trackEvent\', \'Ad - ' . ad_size($row['size']) . '\', \'Click\', \'' . $row['company'] . ' - ' . $row['ad_name'] . '\',1.00,true]);" 
						target="' . $row['link_target'] . '" 
						rel="nofollow" 
					> 
					<img 
						src="/adserver/images/banners/' . $row['image_name'] . '" 
						alt="' . $row['alt_text'] . '" 
						onload="_gaq.push([\'_trackEvent\', \'Ad - ' . ad_size($row['size']) . '\', \'Impression\', \'' . $row['company'] . ' - ' . $row['ad_name'] . '\',1.00,true]);" 
					/> 
					</a>
			 	</li>
			  ';
		};
		echo '</ul>';
};



/****** test function *******************************/
function test(){
	echo "test";	
}
?>






