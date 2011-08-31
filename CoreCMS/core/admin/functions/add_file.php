<?
error_reporting(0);
require("_headr.php");
if($_SESSION["password"] != $pass || $_SESSION["sessid"] != session_id()) die(logout());

debug("Password validated, logged in",0);

$file_folder = $_REQUEST["path"];
$type = $_REQUEST["type"];
$file_url = $_REQUEST["file_url"];
$file_title = $_REQUEST["file_title"];
$file_text = stripslashes($_REQUEST["file_text"]."");

if(strlen($file_folder) < 1) {
	debug("Variable path returned nothing",1);
}

if(strlen($file_url) < 1) {
	debug("Variable file_name returned nothing",1);
}

if(substr($file_url,0,1) == ".") {
	debug("File name can't begin with a dot (.)",1);
	$new_name = $old_name;
}

if(!strstr($file_url,".")) {
	$ext = ".php";
	if($type == "style") $ext = ".css";
	if($type == "script") $ext = ".js";
	
	$file_url .= $ext;
}

$path = $file_folder. $file_url;

if(is_file($path)) {
	debug("A file with this name does already exist.",1);
}

if(is_dir($path)) {
	debug("A directory with this name does already exist",1);
}

if($file_url && $file_title) {
	
	debug("File input is registered as a page",0);
	
	//set position
	$page_p = 0;
	
	$content = mysql_query("SELECT p.* FROM core_pages p ORDER BY p.page_position");
	
	if(@mysql_num_rows($content) > 0) {	
		while($data = mysql_fetch_array($content)) {
			$p = $data["page_position"];
			if($p > $page_p) {
				$page_p = $p;
			}
		}
		$page_p++;
	}
	
	
	$file = fopen($path, "w");
	if(!$file) {
		debug("Failed to add file $path",1);
	}
	
	if(strlen($file_text) > 0) {
		debug("Writing file_text input",0);
		if(!fwrite($file,$file_text)) {
			debug("Failed to write text",0);
		}
	}
		
	if(!mysql_query(
		"INSERT INTO `core_pages` (
		`page_title`,
		`page_url`,
		`page_position`) 
		VALUES (
		\"$file_title\",
		\"$file_url\",
		$page_p)")) {
	
		unlink($path);
		debug("Failed to add page to database",0);
		debug("Unlinking created file",0);
		debug("Dumping file input",0);
		debug($file_text,0);
		debug("End of file",0);
		debug(mysql_error(),1);
		
	}
	
} else {
		
	if(strstr($path,"scripts")) {
		if(strstr($path,".js")) {
			debug("File input is registered as a javascript",0);
		} else {
			debug("Detected file as script, but file did not have the extension .js, it must have.",1);
		}
	}
		
	if(strstr($path,"styles")) {	
		if(strstr($path,".css")) {
			debug("File input is registered as a stylesheet",0);
		} else {
			debug("Detected file as stylesheet, but file did not have the extension .css, it must have.",1);
		}
	}
	
	if(strstr($path,"pages")) {	
		debug("File input is registered as a page, but no title was submitted.",1);
	}
	
	
	$file = fopen($path, "w");
	if(!$file) {
		debug("Failed to add file $path",1);
	}
	
	if(strlen($file_text) > 0) {
		debug("Writing file_text input",0);
		if(!fwrite($file,$file_text)) {
			debug("Failed to write text",0);
		}
	}
}

debug("Ending script",0);
debug_echo();
?>