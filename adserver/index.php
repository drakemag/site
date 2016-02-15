<?php 
    require_once("includes/header_info.php");
?>
<link rel="stylesheet" href="css/style.css" type="text/css" />
<?php 
    require_once("includes/connection.php");
    require_once("includes/functions.php");
    require_once("includes/nav.php");
?>


<div id="wrapper">

<?php 
error_reporting(E_ALL);
ini_set('display_errors', TRUE);
ini_set('display_startup_errors', TRUE);
  // Reporting E_NOTICE can be good too (to report uninitialized
  // variables or catch variable name misspellings ...)
  error_reporting(E_ERROR | E_WARNING | E_PARSE | E_NOTICE);


if(!empty($_POST)){
  if(isset($_POST['del_record'])){
    //DELETING THE RECORD WHEN USER CLICKS THE 'DEL' BUTTON FROM TABLE LIST
    $query_delete = "UPDATE ads SET hide = '1', status = '0' WHERE id = " . $_POST['del_record'] or die("Error in the consult.." . mysqli_error($connection));
    //execute the query.
    $result_delete = mysqli_query($connection, $query_delete);

  }elseif(isset($_POST['unpublish_record'])){
    //UPDATING THE RECORD TO UNPUBLISHED STATUS WHEN USER CLICKS THE 'STATUS' BUTTON FROM TABLE LIST
    $query_unpublish = "UPDATE ads SET status = '0' WHERE id = " . $_POST['unpublish_record'] or die("Error in the consult.." . mysqli_error($connection));
    //execute the query.
    $result_unpublish = mysqli_query($connection, $query_unpublish);

  }elseif(isset($_POST['publish_record'])){
    //UPDATING THE RECORD TO PUBLISHED STATUS WHEN USER CLICKS THE 'STATUS' BUTTON FROM TABLE LIST
    $query_publish = "UPDATE ads SET status = '1' WHERE id = " . $_POST['publish_record'] or die("Error in the consult.." . mysqli_error($connection));
    //execute the query.
    $result_publish = mysqli_query($connection, $query_publish);


  }else{
    echo 'it be null';
  }
}
?>


<h1>Ad Server: Home</h1>
<hr />  

		<?php
            //consultation:

            $query = "SELECT * FROM ads WHERE hide != '1' ORDER BY status DESC, size ASC, company ASC" or die("Error in the consult.." . mysqli_error($connection));

            //execute the query.

            $result = mysqli_query($connection, $query);             
			/************************************************************************/

			return_ads_all($result);
			echo '<hr />' . "\n"; 
		?>	




