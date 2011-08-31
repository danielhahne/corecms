<?
error_reporting(0);
require("_headr.php");
if($_SESSION["password"] != $pass || $_SESSION["sessid"] != session_id()) die(logout());

debug("Password validated, logged in",0);

$path = $_REQUEST["path"];
$name = $_REQUEST["name"];
$type = $_REQUEST["type"];

if(is_dir($path)) {
	debug("Path returned folder : $path",1);
}

if(!is_file($path)) {
	debug("Path returned no file : $path",0);
}

if(!unlink($path)) {
	debug("Unlinking of file failed : $path",0);
} else {
	debug("Deleted file: $path",0);
}

if($type == "page") {
	debug("File type is page, removing match in database",0);
	$sql = mysql_query("SELECT p.* FROM core_pages p WHERE p.page_url = \"$name\" LIMIT 1");
	
	if(!$sql) {
		debug("Did not find a match in database",0);
		debug(mysql_error(),1);
	}
	
	if(mysql_num_rows($sql) > 0) {
		debug("Found a match, removing",0);
		while($p = mysql_fetch_array($sql)) {
			if(!mysql_query("DELETE FROM `core_pages` WHERE `page_url` = \"$name\"")) {
				debug("Failed removal of page from database table core_pages",0);
				debug(mysql_error(),1);
			}
		}
	}
}

debug("Ending script",0);
debug_echo();
?>