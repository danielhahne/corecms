<?
error_reporting(0);
require("_headr.php");
if($_SESSION["password"] != $pass || $_SESSION["sessid"] != session_id()) die(logout());

debug("Password validated, logged in",0);

$new_title = $_REQUEST["title"];
$new_theme = $_REQUEST["theme"];
$new_db_user = $_REQUEST["db_user"];
$new_db_pass = $_REQUEST["db_pass"];
$new_db_name = $_REQUEST["db_name"];
$new_db_server = $_REQUEST["db_server"];
$new_user = $_REQUEST["user"];
$new_pass = $_REQUEST["pass"];

if($_REQUEST["use_rss"]) {
	debug("RSS active",0);
	$new_use_rss = 1;
} else {
	$new_use_rss = 0;
}

if($_REQUEST["show_empty"]) {
	debug("Show empty active",0);
	$new_show_empty = 1;
} else {
	$new_show_empty = 0;
}

if($_REQUEST["nice_perma"]) {
	debug("Nice permalinks active",0);
	$new_nice_perma = 1;
} else {
	$new_nice_perma = 0;
}

if($_REQUEST["debugging"]) {
	debug("Debugging active",0);
	$new_debugging = 1;
} else {
	$new_debugging = 0;
}

if($new_user && $new_pass) {
	debug("Generating new password",0);
	$salt = substr(md5($new_user),0,15);
	$pass_salted = sha1($new_pass . $salt);
} else {
	$pass_salted = $pass;
	$new_user = $user;
}

if(!$new_db_pass || $new_db_pass == "(hidden)") {
	$new_db_pass = $db_pass;
}

if(!is_dir($root . "themes/" . $new_theme)) {
	debug("No existing folder with the entered theme name, reverting to old theme",0);
	$new_theme = $theme;
}

$str = "<?php
\$title = \"".$new_title."\";
\$theme = \"".$new_theme."\";
\$db_user = \"".$new_db_user."\";
\$db_pass = \"".$new_db_pass."\";
\$db_name = \"".$new_db_name."\";
\$db_server = \"".$new_db_server."\";
\$user = \"".$new_user."\";
\$pass = \"".$pass_salted."\";
\$show_empty = ".$new_show_empty.";
\$use_rss = ".$new_use_rss.";
\$debugging = ".$new_debugging.";
\$nice_permalinks = ".$new_nice_perma.";
?>";

$path = $root . "user/configuration.php";

if(!is_file($path)) {
	debug("$path did not return file, configuration must be in core/user/configuration.php",1);
}

$file = fopen($path, "w");
if(!$file) {
	debug("Could not open $path in write mode",1);
}

if(strlen($str) > 0) {
	debug("Writing file_text input",0);
	if(!fwrite($file,$str)) {
		debug("Failed to write new configuration, file is blank",0);
		debug("Dumping configuration str for manual restoration",0);
		debug($str,0);
		debug("End of file");
		debug("There was a fatal error with the configuration file, you will have to manually restore it.",1);
	}
}

debug("Ending script",0);
debug_echo();
?>