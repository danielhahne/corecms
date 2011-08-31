<?
error_reporting(0);
require("_headr.php");
if($_SESSION["password"] != $pass || $_SESSION["sessid"] != session_id()) die(logout());

debug("Password validated, logged in",0);


$id = $_REQUEST["id"];
if(!is_numeric($id)) {
	debug("Id is not numeric",1);
}

$contents = mysql_query("SELECT t.tag_position FROM core_tags t WHERE t.tag_id = $id LIMIT 1");
if(!$contents || mysql_num_rows($contents) < 1) {
	debug("Can't select data from database table core_tags",0);
	debug(mysql_error(),1);
}

while($data = mysql_fetch_array($contents)) {
	$t_tag_pos = $data["tag_position"];
}

if(!mysql_query("DELETE FROM `core_entry2tag` WHERE `tag_id` = $id")) {
	debug("Can't remove tag/entry relations from database table core_entry2tag",0);
	debug(mysql_error(),1);
}
if(!mysql_query("DELETE FROM `core_tags` WHERE `tag_id` = $id")) {
	debug("Can't remove entry from database table core_tags",0);
	debug(mysql_error(),1);
}

$contents = mysql_query("SELECT * FROM core_tags t WHERE t.tag_position > $t_tag_pos") or debug(mysql_error(),1);
while($data = mysql_fetch_array($contents)) {
	$c_tag_position = $data["tag_position"];
	$c_tag_id = $data["tag_id"];
	mysql_query("UPDATE core_tags t SET
	t.tag_position = ".($c_tag_position-1)." 
	WHERE t.tag_id = ".$c_tag_id);
}

debug("Ending script",0);
debug_echo();
?>