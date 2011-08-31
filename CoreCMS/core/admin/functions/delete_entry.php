<?
error_reporting(0);
require("_headr.php");
if($_SESSION["password"] != $pass || $_SESSION["sessid"] != session_id()) die(logout());

debug("Password validated, logged in",0);

$entry_id = $_REQUEST["id"];
if(!is_numeric($entry_id)) {
	debug("Id is not numeric",1);
}

$contents = mysql_query("SELECT * FROM `core_entries` WHERE `entry_id` = $entry_id LIMIT 1");
if(!$contents || mysql_num_rows($contents) < 1) {
	debug("Can't select data from database table core_entries",0);
	debug(mysql_error(),1);
}

while($data = mysql_fetch_array($contents)) {
	$entry_title = $data["entry_title"];
	$e_entry_position = $data["entry_position"];
}

if(!treat_string($entry_title)) {
	debug("Folder-string returned nothing, removing database entry but skipping unlinking of folder.",0);
} else {
	
	$dir = $root . "user/uploads/" . treat_string($entry_title);
	
	if(is_dir($dir) && !($dir == ($root . "user/uploads/"))) {
		delete_directory($dir);
	} else {
		debug("Found no directory to delete.",0);
	}
	
}

if(!mysql_query("DELETE FROM `core_entry2tag` WHERE `entry_id` = $entry_id")) {
	debug("Can't remove tag/entry relations from database table core_entry2tag",0);
	debug(mysql_error(),1);
}
if(!mysql_query("DELETE FROM `core_entries` WHERE `entry_id` = $entry_id")) {
	debug("Can't remove entry from database table core_entries",0);
	debug(mysql_error(),1);
}

$contents = mysql_query("SELECT * FROM core_entries e WHERE e.entry_position > $e_entry_position") or debug(mysql_error(),1);

while($data = mysql_fetch_array($contents)) {
	$c_entry_position = $data["entry_position"];
	$c_entry_id = $data["entry_id"];
	mysql_query("UPDATE core_entries e SET
	e.entry_position = ".($c_entry_position-1)." 
	WHERE t.entry_id = ".$c_entry_id);
}

if($return) {
	print($return);
} else {
	$return = "Deleted entry from database. <br />";
	print($return);
}

debug("Ending script",0);
debug_echo();
?>