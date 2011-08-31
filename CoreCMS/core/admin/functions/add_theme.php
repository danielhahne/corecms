<?
error_reporting(0);
require("_headr.php");
if($_SESSION["password"] != $pass || $_SESSION["sessid"] != session_id()) die(logout());


$status_report = "";
$file_path = $_REQUEST["path"];
$file_name = $_REQUEST["file"];
$file_url = $file_path . $file_name;


$new_file_name = substr($file_name, 0, -4);


// Creat new theme dir
$newdir = $file_path . treat_string($new_file_name);
if(is_dir($newdir)) {
	debug("Can not create directory at $newdir, already a folder with that name",1);
	$status_report = "Can not create directory at $newdir, already a folder with that name";
}
if(!mkdir($newdir)) {
	debug("Failed to execute command mkdir($newdir)",1);
	$status_report = "Failed to execute command mkdir($newdir)";
}

// unzip theme files into new theme dir
 $zip = new ZipArchive;
 $res = $zip->open($file_url);
 if ($res === TRUE) {
     $zip->extractTo($newdir);
     $zip->close();   
     unlink($file_url);  
 } else {
       debug("Failed to unzip theme package, check write permission of: core/themes directory.",1);
 }


?>


<!--  Show themes -->
<script type="text/javascript">
	get_data('show_themes');
</script>

	