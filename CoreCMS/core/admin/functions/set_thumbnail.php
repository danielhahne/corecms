<?
error_reporting(0);
require("_headr.php");
if($_SESSION["password"] != $pass || $_SESSION["sessid"] != session_id()) die(logout());

debug("Password validated, logged in",0);

$path = $_REQUEST["path"];

$valid = array("jpg","png","gif");
$ext = substr($path,strrpos($path,"."));
$file = substr($path,strrpos($path,"/")+1);

if(detect_type($file)!="image") {
	debug("File is not a valid fileformat (jpg, png or gif)",1);
}

$path_new = substr($path,0,strlen($path)-strlen($ext))."_thumb".$ext;

if(!copy($path,$path_new)) {
	debug("Failed to copy image to thumbnail",1);
}

?>