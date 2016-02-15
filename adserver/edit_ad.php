<?php 
    require_once("includes/header_info.php");
?>
<?php 
    require_once("includes/connection.php");
    require_once("includes/functions.php");
    require_once("includes/nav.php");
?>
<link rel="stylesheet" href="css/style.css" type="text/css" />


<div id="wrapper">

<?php 
  // Reporting E_NOTICE can be good too (to report uninitialized
  // variables or catch variable name misspellings ...)
  //error_reporting(E_ERROR | E_WARNING | E_PARSE | E_NOTICE);

  //var_dump(get_defined_vars());


  if($_POST['save_record']){
    //DELETING THE RECORD WHEN USER CLICKS THE 'DEL' BUTTON FROM TABLE LIST
    $query_save = "UPDATE ads 
      SET 
        status = '" . $_POST['status'] . "',
        company = '" . $_POST['company'] . "',
        ad_name = '" . $_POST['ad_name'] . "',
        size = '" . $_POST['size'] . "',
        image_name = '" . $_POST['image_name'] . "',
        alt_text = '" . $_POST['alt_text'] . "',
        link_url = '" . $_POST['link_url'] . "',
        link_target = '" . $_POST['link_target'] . "',
        custom_code = '" . $_POST['custom_code'] . "',
        created_date = '" . $_POST['created_date'] . "',
        start_date = '" . $_POST['start_date'] . "',
        end_date = '" . $_POST['end_date'] . "'
      WHERE id = '" . $_POST['id'] . "'" or die("Error in the consult.." . mysqli_error($connection));

    //echo $query_save;
    //execute the query.
    $result_save = mysqli_query($connection, $query_save);
	if (!$result_save) {
    printf("Error: %s\n", mysqli_error($connection));
    exit();
	}
    if($result_save){
      echo '<h2>saved</h2>';
    }

  /*
  }elseif($_POST){  
    //GRAB POSTED VALUES FROM SAME PAGE TO INSERT INTO DATABASE
    //ASSIGNING VALUES
    //$id = $_POST['id'];
    $status = $_POST['status'];
    $company = $_POST['company'];
    $ad_name = $_POST['ad_name'];
    $size = $_POST['size'];
    $image_name = $_POST['image_name'];
    $alt_text = $_POST['alt_text'];
    $link_url = $_POST['link_url'];
    $link_target = $_POST['link_target'];
    $custom_code = $_POST['custom_code'];
    $created_date = $_POST['created_date'];
    if($_POST['start_date']){
      $start_date = $_POST['start_date'];
    }else{
      $start_date = '0000-00-00 00:00:00';
    };
    if($_POST['end_date']){
      $end_date = $_POST['end_date'];
    }else{
      $end_date = '2020-00-00 00:00:01';
    };

    //BUILIDNG QUERY FROM POSTED VALUES
    $query = "
      INSERT INTO ads 
        (status,company,ad_name,size,image_name,alt_text,link_url,link_target,custom_code,created_date,start_date,end_date)
      values 
        ('$status','$company','$ad_name','$size','$image_name','$alt_text','$link_url','$link_target','$custom_code','$created_date','$start_date','$end_date')
        " or die("Error in the consult.." . mysqli_error($connection));

    //EXECUTE THE QUERY
    $result = mysqli_query($connection, $query);

    //DISPLAY RESULTS IF SUCCESSFUL
    if ($result) {
      echo '$ad_name - <em>successfully added!</em><br />';
      echo "status = " . $status . "<br />";
      echo "company = " . $company . "<br />";
      echo "ad_name = " . $ad_name . "<br />";
      echo "size = " . $size . "<br />";
      echo "image_name = " . $image_name . "<br />";
      echo "alt_text = " . $alt_text . "<br />";
      echo "link_url = " . $link_url . "<br />";
      echo "link_target = " . $link_target . "<br />";
      echo "custom_code = " . $custom_code . "<br />";
      echo "created_date = " . $created_date . "<br />";
      echo "start_date = " . $start_date . "<br />";
      echo "end_date = " . $end_date . "<br />";



    } else {
      // Display error message.
      echo "<p>Subject creation failed.</p>";
      echo "<p>" . mysql_error() . "</p>";
    }

  */
}else{
  //echo 'it be null';
}
?>




<h1>Ad Server: Edit Existing Advert</h1>

<hr />
<!--     
Post data
<hr /> 
-->
<?php //print_r($_POST); ?>
		<?php
			//if record was edited, then we'll pull that data
			if($_POST['edit_record']){
				$get_this_record = $_POST["edit_record"];
			}elseif($_POST['save_record']){
				$get_this_record = $_POST["save_record"];
			}else{
				$get_this_record = "";
			};


            //consultation:
            $query = "SELECT * FROM ads WHERE id = " . $get_this_record or die("Error in the consult.." . mysqli_error($connection));
            //echo $query;

            //execute the query.

            $result = mysqli_query($connection, $query);             
			if (!$result) {
				printf("Error: %s\n", mysqli_error($connection));
				exit();
			}
		/************************************************************************/




  while ($row = mysqli_fetch_array($result)) {

    //GRAB POSTED VALUES FROM SAME PAGE TO INSERT INTO DATABASE
    //ASSIGNING VALUES
    $temp_edit_id = $row['id'];
    $temp_edit_status = $row['status'];
    $temp_edit_ad_name = $row['ad_name'];
    $temp_edit_company = $row['company'];
    $temp_edit_size = $row['size'];
    $temp_edit_image_name = $row['image_name'];
    $temp_edit_alt_text = $row['alt_text'];
    $temp_edit_link_url = $row['link_url'];
    $temp_edit_link_target = $row['link_target'];
    $temp_edit_custom_code = $row['custom_code'];
    $temp_edit_created_date = $row['created_date'];
    $temp_edit_start_date = $row['start_date'];
    $temp_edit_end_date = $row['end_date'];

  }


			//echo '<hr />' . "\n"; 
		?>	




<form name='form_update' method='post' action='#?save_record=<?php echo $temp_edit_id; ?>'>

  <div class="field">
    <label>id</label>
    <input 
      type='text' 
      name='id' 
      tabindex="4" 
      readonly=""
      value="<?php echo $temp_edit_id; ?>" 
    />
  </div>
  <div class="field">
    <label>status</label>
    <select 
      name='status' 
      tabindex="2" 
    >
      <option value="" <?php if($temp_edit_status == ""){ echo "selected";} ?>> -- select --</option>
      <option value="1" <?php if($temp_edit_status == "1"){ echo "selected";} ?>>published</option>
      <option value="0" <?php if($temp_edit_status == "0"){ echo "selected";} ?>>unpublished</option>
    </select>
  </div>
  <div class="field">
    <label>company</label>
    <input 
      type='text' 
      name='company' 
      tabindex="4" 
      value = '<?php echo $temp_edit_company; ?>'
    />
  </div>
  <div class="field">
    <label>ad_name</label>
    <input 
      type='text' 
      name='ad_name' 
      tabindex="3" 
      value = '<?php echo $temp_edit_ad_name; ?>'
    />
  </div>
  <div class="field">
    <label>size</label>
    <div class="select_radio">
      <input type="radio" name="size" value="1"<?php if($temp_edit_size == "1"){ echo "checked";} ?>> rectangle (300x250)<br />
      <input type="radio" name="size" value="2"<?php if($temp_edit_size == "2"){ echo "checked";} ?>> skyscraper (160x600)<br />
      <input type="radio" name="size" value="3"<?php if($temp_edit_size == "3"){ echo "checked";} ?>> forum ad (329x131)<br />
      <input type="radio" name="size" value="4"<?php if($temp_edit_size == "4"){ echo "checked";} ?>> leaderboard (728x90)
    </div>
  </div>
  
<div class="ad_type">
  <script type="text/javascript" src="//code.jquery.com/jquery-1.9.1.js"></script>
  <script type="text/javascript">//<![CDATA[ 
    $(function(){
    // From http://learn.shayhowe.com/advanced-html-css/jquery

    // Change tab class and display content
    $('.tabs-nav a').on('click', function (event) {
        event.preventDefault();
        
        $('.tab-active').removeClass('tab-active');
        $(this).parent().addClass('tab-active');
        $('.tabs-stage > div').hide();
        $($(this).attr('href')).show();
    });

    $('.tabs-nav a:first').trigger('click'); // Default
    });//]]>  
  </script>


  <fieldset>
    <legend>
      <ul class="tabs-nav">
        <li class="tab-active"><a href="#tab-1" rel="nofollow">Standard</a></li>
        <li> | </li>
        <li class=""><a href="#tab-2" rel="nofollow">Custom Code</a></li>
      </ul>
    </legend>

      <div class="tabs-stage">
        <div id="tab-1" style="display: block;">
            <div class="field">
              <label>image_name</label>
              <?php
                $exclude_list = array(".", "..", "example.txt",".DS_Store");
                $dir_path = $_SERVER["DOCUMENT_ROOT"]."/adserver/images/banners/";
                $current_ad_image = $temp_edit_image_name;
                edit_banners_list($exclude_list,$dir_path,$current_ad_image);
              ?>
            </div>
            <div class="field">
              <label>alt_text</label>
              <input 
                type='text' 
                name='alt_text' 
                tabindex="8" 
                value = '<?php echo $temp_edit_alt_text; ?>'
              />
            </div>
            <div class="field">
              <label>link_url</label>
              <input 
                type='text' 
                name='link_url' 
                tabindex='9' 
                value = '<?php echo $temp_edit_link_url; ?>'
              />
            </div>
            <div class="field">
              <label>link_target</label>
              <div class="select_radio">
                <input type="radio" name="link_target" value="_blank"<?php if($temp_edit_link_target == "_blank"){ echo "checked";} ?>> _blank<br />
                <input type="radio" name="link_target" value="_parent"<?php if($temp_edit_link_target == "_parent"){ echo "checked";} ?>> _parent<br />
              </div>
            </div>
        </div>
        <div id="tab-2" style="display: none;">
            <div class="field">
              <label>custom_code</label>
              <input 
                type='text' 
                name='custom_code' 
                tabindex="11" 
                value = '<?php echo $temp_edit_custom_code; ?>'
             />
            </div>
        </div>
    </div>
  </fieldset>
</div>










  <div class="field">
    <label>created_date</label>
    <input 
      type='text' 
      name='created_date' 
      tabindex="12"
      value = '<?php echo $temp_edit_created_date; ?>'
    />
  </div>
  <div class="field">
    <label>start_date</label>
    <input 
      type='text' 
      name='start_date' 
      tabindex="13" 
      class="some_class"
      value = '<?php echo $temp_edit_start_date; ?>'
    />
  </div>
  <div class="field">
    <label>end_date</label>
    <input 
      type='text' 
      name='end_date' 
      tabindex="14" 
      class="some_class"
      value = '<?php echo $temp_edit_end_date; ?>'
    />

  </div>

    <!-- datetime picker info here: http://xdsoft.net/jqplugins/datetimepicker/ -->

  <div class="field">
    <link rel="stylesheet" type="text/css" href="/adserver/datetime_picker/jquery.datetimepicker.css"/>
    <script src="/adserver/datetime_picker/jquery.js"></script>
    <script src="/adserver/datetime_picker/jquery.datetimepicker.js"></script>
  </div>
  <script>/*
      window.onerror = function(errorMsg) {
        $('#console').html($('#console').html()+'<br>'+errorMsg)
      }*/
      $('#datetimepicker').datetimepicker({
        format:'Y-m-d H:i:s',
        dayOfWeekStart : 0,
        lang:'en'
      });

      $('.some_class').datetimepicker({
        format:'Y-m-d H:i:s',
        dayOfWeekStart : 0,
        lang:'en'
      });
  </script>


  <div class="field">
    <label></label>
    <input
      type='hidden'
      name='save_record'
      value='<?php echo $temp_edit_id; ?>'
    />
    <input 
      type='submit' 
      value='submit' 
    />
  </div>

</form>

</div>





