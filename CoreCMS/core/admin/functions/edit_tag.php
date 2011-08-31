<?
error_reporting(0);
require("_headr.php");
if($_SESSION["password"] != $pass || $_SESSION["sessid"] != session_id()) die(logout());

debug("Password validated, logged in",0);

$id = $_REQUEST["tag_id"];
if(!is_numeric($id)) {
	debug("Id is not numeric",1);
}

$tag_text = $_REQUEST["tag_text"];

if(!$tag_text) {
	debug("Tag title is blank",1);
}

if(!mysql_query("UPDATE `core_tags` SET
`tag_text` = \"$tag_text\"
WHERE `tag_id` = $id")) {
	debug("Can not update tag data in database table core_tags",0);
	debug(mysql_error(),1);
}
	
debug("Ending script",0);
debug_echo();
?>