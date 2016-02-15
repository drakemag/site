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
if($_POST){  
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
    /*
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


      echo "GA Event Tracking snippit is as follows: <br />";
      //BUILD THE EVENT TRACKING CODE
      $ga_event_code_snippit ='
        <a 
          href="' . $link_url. '" 
          onClick="_gaq.push([\'_trackEvent\', \'Ad - ' . ad_size($size). '\', \'Click\', \'' . $company. ' - ' . $ad_name. '\',1.00,true]);" 
          target="' . $link_target. '" 
          rel="nofollow" 
        > 
          <img 
            src="http://www.drakemag.com/images/banners/' . $image_name. '" 
            alt="' . $alt_text. '" 
            onload="_gaq.push([\'_trackEvent\', \'Ad - ' . ad_size($size) . '\', \'Impression\', \'' . $company . ' - ' . $ad_name . '\',1.00,true]);" 
          /> 
        </a>';
      echo htmlspecialchars($ga_event_code_snippit);
    }

    */

      /* ?> IF UNCOMMENTED, THEN THE SNIPPIT WILL DISPLAY PROPERLY 
      <a 
        href="<?php echo $link_url; ?>" 
        onClick="_gaq.push(['_trackEvent', 'Ad - <?php echo ad_size($size); ?>', 'Click', '<?php echo $company; ?> - <?php echo $ad_name; ?>',1.00,true]);" 
        target="<?php echo $link_target; ?>" 
        rel="nofollow" 
      > 
        <img 
          src="http://www.drakemag.com/images/banners/<?php echo $image_name; ?>" 
          alt="<?php echo $alt_text; ?>" 
          onload="_gaq.push(['_trackEvent', 'Ad - <?php echo ad_size($size); ?>', 'Impression', '<?php echo $company; ?> - <?php echo $ad_name; ?>',1.00,true]);" 
        /> 
      </a>
      */
}
?>


<h1>Ad Server: Add New Advert</h1>
    
<hr />
<!--     
Post data
<hr /> 
-->
<?php //print_r($_POST); ?>

		<?php
            //consultation:

            //$query = "SELECT * FROM ads ORDER BY status DESC, company ASC" or die("Error in the consult.." . mysqli_error($connection));

            //execute the query.

            //$result = mysqli_query($connection, $query);             
			/************************************************************************/

			//return_ads_all($result);
			//echo '<hr />' . "\n"; 


		?>	



<fieldset>
  <legend>Add new advert</legend>

  <form name='form_update' method='post' action='#'>

    <div class="field">
      <label>status</label>
      <select 
        name='status' 
        tabindex="2" 
      >
        <option value=""> -- select --</option>
        <option value="1">published</option>
        <option value="0">unpublished</option>
      </select>
    </div>
    <div class="field">
      <label>company</label>
      <input 
        type='text' 
        name='company' 
        tabindex="4" 
      />
    </div>
    <div class="field">
      <label>ad_name</label>
      <input 
        type='text' 
        name='ad_name' 
        tabindex="3" 
      />
    </div>
    <div class="field">
      <label>size</label>
      <div class="select_radio">
        <input type="radio" name="size" value="1"> rectangle (300x250)<br />
        <input type="radio" name="size" value="2"> skyscraper (160x600)<br />
        <input type="radio" name="size" value="3"> forum ad (329x131)<br />
        <input type="radio" name="size" value="4"> leaderboard (728x90)
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

                    banners_list($exclude_list,$dir_path);
                  ?>
              </div>
              <div class="field">
                <label>alt_text</label>
                <input 
                  type='text' 
                  name='alt_text' 
                  tabindex="8" 
                />
              </div>
              <div class="field">
                <label>link_url</label>
                <input 
                  type='text' 
                  name='link_url' 
                  tabindex="9" 
                />
              </div>
              <div class="field">
                <label>link_target</label>
                <div class="select_radio">
                  <input type="radio" name="link_target" value="_blank"> _blank<br />
                  <input type="radio" name="link_target" value="_parent"> _parent<br />
                </div>
              </div>
          </div>
          <div id="tab-2" style="display: none;">
              <div class="field">
                <label>custom_code</label>
                <textarea 
                  rows = '8'
                  cols = '42'
                  name='custom_code' 
                  class= 'custom_code'
                  tabindex="11" 
                /></textarea>
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
        value='<?php echo date("Y-m-d H:i:s"); ?>' 
      />
    </div>
    <div class="field">
      <label>start_date</label>
      <input 
        type='text' 
        name='start_date' 
        tabindex="13" 
        class="some_class"
      />
    </div>
    <div class="field">
      <label>end_date</label>
      <input 
        type='text' 
        name='end_date' 
        tabindex="14" 
        class="some_class"
      />

    </div>

      <!-- datetime picker info here: http://xdsoft.net/jqplugins/datetimepicker/ -->

    <div class="field">
      <link rel="stylesheet" type="text/css" href="./datetime_picker/jquery.datetimepicker.css"/>
      <script src="./datetime_picker/jquery.js"></script>
      <script src="./datetime_picker/jquery.datetimepicker.js"></script>
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
        type='submit' 
        value='submit' 
      />
    </div>

  </form>


  </div>

</fieldset>


<?php
    /* GA EVENT CODE
    <a 
      href="" 
      onClick="_gaq.push(['_trackEvent', 'Banner - SIZE', 'Click', 'COMPANY - CAMPAIGN - SIZE - DATE',1.00,true]);" 
      target="_blank" 
      rel="nofollow"
    >
      <img 
        style="border: 0px" 
        src="IMG_URL_HERE" 
        alt="" 
        onload="_gaq.push(['_trackEvent', 'Banner - SIZE', 'Impression', 'COMPANY - CAMPAIGN  - SIZE - DATE',1.00,true]);" 
      />
    </a>
    */

    /* AD SIZES
        1 - rectangle (300x250)
        2 - skyscraper (160x600)
        3 - forum ad (329x131)
        4 - leaderboard (728x90)
    */
?>


<script type="text/javascript">
  function confirm_delete() {
    //return confirm('are you sure?');
    return confirm('Are you sure to delete all '+form.id.value + '?');
  }
</script>


