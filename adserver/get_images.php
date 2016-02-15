
<?php
// http://php.net/manual/en/function.scandir.php
// 20150119 - rgs - look at "2bbasic at gmail dot com" comments at bottom

//-- optional placemenet

//-- until here
function banners_list($exclude_list,$dir_path) {
    global $exclude_list, $dir_path;
    $directories = array_diff(scandir($dir_path), $exclude_list);

    echo "<select 
          name='image_name' 
          tabindex=''
          >";

        foreach($directories as $entry) {
          if(is_file($dir_path.$entry)) {
            echo "<option value=".$entry.">".$entry."</option>";
          }
        }
    echo "</select>";

}
$exclude_list = array(".", "..", "example.txt",".DS_Store");
$dir_path = $_SERVER["DOCUMENT_ROOT"]."/images/banners/";
echo $dir_path . '<br />';
banners_list($exclude_list,$dir_path);


?>
