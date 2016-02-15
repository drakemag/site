<?php 
    require_once("includes/header_info.php");
?>
<?php

$reqfilename = 'remote_ad.php';

$cachefile = "cache/".$reqfilename.".html";



 	//OPTION #1 - FLIP CACHE WHEN FILE IS MODIFIED

        // Serve from the cache if it is the same age or younger than the last 

        // modification time of the included file (includes/$reqfilename)

 		/*

        if (file_exists($cachefile) && (filemtime($reqfilename)

           < filemtime($cachefile))) {  





           include($cachefile);



           echo "<!-- Cached ".date('H:i', filemtime($cachefile))."-->";





           exit;

        }

		*/



 	//OPTION #2 - REFRESH CACHE ON TIME INTERVALS

	 // 5 minutes



        $cachetime = 15 * 60; 



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

?>



<?php

	//THIS IS THE /INCLUDES/CONSTANTS.PHP INCLUDES FILE

	// Database Constants

	/*

	define("DB_SERVER", "localhost");

	define("DB_USER", "root");

	define("DB_PASS", "root");

	define("DB_NAME", "adserver");

	*/

?>

<?php

	//THIS IS THE /INCLUDES/CONNECTION.PHP INCLUDES FILE

	//CONNECT TO THE DATABASE

	/*

	require("includes/constants.php");

	//conection:

	$connection = mysqli_connect(DB_SERVER,DB_USER,DB_PASS,DB_NAME) or die("Error " . mysqli_error($connection)); 

	*/

?>

<?php 

	// CALL THE DB CONNECTION AND FUNCTION FILES

	// THESE ARE INSERTED INTO THE TOP OF THE PHP FILE TO OUTPUT

	require_once("includes/connection.php"); 

	require_once("includes/functions.php"); 

?>

<?php

    //MAKE THE QUERY:

	$current_date = date("Y-m-d H:i:s"); 

    $query_ads_forum = "

    	SELECT * FROM ads 

    	WHERE 

    	size = '1' 

    	and status = '1' 

    	and hide <> '1'

    	and start_date <= '" . $current_date . "' 

    	and end_date >= '" . $current_date . "'

    	ORDER BY RAND() LIMIT 3

    	" or die("Error in the consult.." . mysqli_error($connection));

	//echo '$query_ads_forum = '. $query_ads_forum . '<hr />';



    //EXECUTE THE QUERY

    $result_ads_forum = mysqli_query($connection, $query_ads_forum);             



	

	while( $row = mysqli_fetch_assoc($result_ads_forum)){ ?>

      <a 

        href="<?php echo $row['link_url']; ?>" 

        onClick="_gaq.push(['_trackEvent', 'Ad - <?php echo ad_size($row['size']); ?>', 'Click', '<?php echo $row['company']; ?> - <?php echo $row['ad_name']; ?>',1.00,true]);" 

        target="<?php echo $row['link_target']; ?>" 

        rel="nofollow" 

      > 

        <img 

          src="/adserver/images/banners/<?php echo $row['image_name']; ?>" 

          alt="<?php echo $row['alt_text']; ?>" 

          onload="_gaq.push(['_trackEvent', 'Ad - <?php echo ad_size($row['size']); ?>', 'Impression', '<?php echo $row['company']; ?> - <?php echo $row['ad_name']; ?>',1.00,true]);" 

        /> 

      </a>







	<?php

      //THIS OUTPUTS THE CORRECT GA TRACKING SCRIPT WHEN UNCOMMENTED

      /*

      echo "<br /><hr />GA Event Tracking snippit is as follows: <br />";

      //BUILD THE EVENT TRACKING CODE

      $ga_event_code_snippit ='

        <a 

          href="' . $row['link_url'] . '" 

          onClick="_gaq.push([\'_trackEvent\', \'Ad - ' . ad_size($row['size']). '\', \'Click\', \'' . $row['company'] . ' - ' . $row['ad_name'] . '\',1.00,true]);" 

          target="' . $row['link_target'] . '" 

          rel="nofollow" 

        > 

          <img 

            src="http://www.drakemag.com/images/banners/' . $row['image_name'] . '" 

            alt="' . $row['alt_text'] . '" 

            onload="_gaq.push([\'_trackEvent\', \'Ad - ' . ad_size($row['size']) . '\', \'Impression\', \'' . $row['company'] . ' - ' . $row['ad_name']  . '\',1.00,true]);" 

          /> 

        </a>';

      echo htmlspecialchars($ga_event_code_snippit);



      */



	 };



    /* AD SIZES

        1 - rectangle (300x250)

        2 - skyscraper (160x600)

        3 - forum ad (329x131)

          4 - leaderboard (728x90)

   */

?>



<?php

        // open the cache file for writing



        $fp = fopen($cachefile, 'w');



		 // save the contents of output buffer to the file

        fwrite($fp, ob_get_contents());





		 // close the file

        fclose($fp);



		 // Send the output to the browser

        ob_end_flush();

?>



