<?
error_reporting(0);
require("_headr.php");
if($_SESSION["password"] != $pass || $_SESSION["sessid"] != session_id()) die(logout());

debug("Password validated, logged in",0);

$old_name = $_REQUEST["url"];
$new_name = $_REQUEST["file_name"];
$type = $_REQUEST["type"];
$path = $_REQUEST["path"];
$title = $_REQUEST["file_title"];
$file_text = php_vars(stripslashes($_REQUEST["file_text"]));


if(strlen($old_name) < 1) {
	debug("Variable old_file_name returned nothing",1);
}

if(strlen($new_name) < 1) {
	debug("Variable new_file_name returned nothing using old name",0);
	$new_name = $old_name;
}

if(substr($new_name,0,1) == ".") {
	debug("File name can't begin with a dot (.), using old name",0);
	$new_name = $old_name;
}

if(strlen($path) < 1) {
	debug("Variable path returned nothing",1);
}

if(!is_file($path) && $type != "layout") {
	debug("$path did not return file",1);
}

if(is_dir($path)) {
	debug("$path returned directory, not file",1);
}


if(!strstr($new_name,".")) {
	$ext = ".php";
	if($type == "style") $ext = ".css";
	if($type == "script") $ext = ".js";
	debug("Can not find any extension to the new name of the file, adding one ($ext)",0);
	$new_name = $new_name . $ext;
}


$protected = false;
$is_page = false;
switch($type) {
	case "page":
		$is_page = true;
		if(strlen($title) < 1) {
			debug("Variable title returned nothing",1);
		}
	break;
	case "part":
		$protected = true;
	break;
	case "style":
	break;
	case "script":
	break;
	case "layout":
		$new_name = $old_name;
	break;
}

if(!$new_name) {
	$new_name = $old_name;
}

if($new_name != $old_name && !($protected)) {
	$path_old = $path;
	$path_new = substr($path_old,0,strlen($path_old)-strlen($old_name)) . $new_name;
	if(is_file($path_new)) {
		debug("There is already a file with that name. $path_new",1);
	}
	
	if(is_dir($path_new)) {
		debug("Invalid file name, returned directory not file. $path_new",1);
	}
	
	if(!rename($path_old,$path_new)) {
		debug("Can not rename file $path_old to $path_new",1);
	}
	
	$path = $path_new;
}
if($is_page) {
	$sql = mysql_query("SELECT p.* FROM core_pages p WHERE p.page_url = \"$old_name\" LIMIT 1");
	if(!$sql) {
		debug("Did not find a match in database",0);
		debug(mysql_error(),1);
	}
	
	if(mysql_num_rows($sql) > 0) {
		if(!mysql_query("UPDATE `core_pages` SET
		`page_title` = \"$title\",
		`page_url` = \"$new_name\"
		WHERE `page_url` = \"$old_name\"")) {
			debug("Can not update data in database table core_pages",0);
			if($new_name != $old_name) {
				if(!rename($path,$path_old)) {
					debug("Can not reverse renaming of folder. $path to $path_old",1);
				}
			}
			debug(mysql_error(),1);
		}
	}
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

debug("Ending script",0);
debug_echo();
?>