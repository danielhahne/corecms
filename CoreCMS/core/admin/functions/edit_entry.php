<?
error_reporting(0);
require("_headr.php");
if($_SESSION["password"] != $pass || $_SESSION["sessid"] != session_id()) die(logout());

debug("Password validated, logged in",0);

$entry_id = $_REQUEST["entry_id"];
if(!is_numeric($entry_id)) {
	debug("Id is not numeric",1);
}

$entry_oldtitle = $_REQUEST["entry_title_old"];
$entry_title = $_REQUEST["entry_title"];
$entry_text = $_REQUEST["entry_text"];
$entry_client = $_REQUEST["entry_client"];
$entry_extra1 = $_REQUEST["entry_extra1"];
$entry_extra2 = $_REQUEST["entry_extra2"];
// NEW
$entry_visit_link = $_REQUEST["entry_visit_link"];

if(strlen($entry_oldtitle) < 1) {
	debug("Variable for current title returned nothing.",1);
}

if(strlen($entry_title) < 1) {
	$entry_title = $entry_oldtitle;
	debug("Variable for new title returned nothing. Using old title");
}

if($_REQUEST["entry_new"]) {
	$entry_new = 1;
} else {
	$entry_new = 0;
}

if($_REQUEST["entry_show"]) {
	$entry_show = 1;
} else {
	$entry_show = 0;
}

$tags = mysql_query("SELECT * FROM `core_tags`");
if(!$tags) {
	debug("Can not fetch tag data from database table core_tags",0);
	debug(mysql_error(),0);
} else {
	while($tag_data = mysql_fetch_array($tags)) {
		$tag_text = $tag_data["tag_text"];
		$tag_id = $tag_data["tag_id"];
		$url_tag = $_REQUEST["tag".$tag_id];
		if($url_tag) {
			$check = mysql_query("SELECT * FROM `core_entry2tag` WHERE `tag_id`=$tag_id AND `entry_id` = $entry_id");
			if(!$check) {
				debug(mysql_error(),0);
			}
			if(mysql_num_rows($check) < 1) {
				if(!mysql_query("INSERT INTO `core_entry2tag` (`tag_id`,`entry_id`) VALUES ($tag_id, $entry_id)")) {
					debug(mysql_error(),0);
				}
			}
		} else {	
			$check = mysql_query("SELECT * FROM `core_entry2tag` WHERE `tag_id`=$tag_id AND `entry_id` = $entry_id");
			if(!$check) {
				debug(mysql_error(),0);
			}
			if(mysql_num_rows($check) > 0) {
				if(!mysql_query("DELETE FROM `core_entry2tag` WHERE `tag_id` = $tag_id AND `entry_id` = $entry_id")) {
					debug(mysql_error(),0);
				}
			}
		}
	}
}

$dirpath = $root . "user/uploads/" . treat_string($entry_oldtitle);
$newname = $root . "user/uploads/" . treat_string($entry_title);

if($dirpath != $newname) {
	if(!rename($dirpath,$newname)) {
		debug("Can not rename folder $dirpath to $newname",1);
	}
}
	
if(!mysql_query("UPDATE `core_entries` SET
`entry_title` = \"$entry_title\",
`entry_text` = \"$entry_text\",
`entry_new` = $entry_new,
`entry_show` = $entry_show,
`entry_client` = \"$entry_client\",
`entry_extra1` = \"$entry_extra1\",
`entry_extra2` = \"$entry_extra2\",   
`entry_visit_link` = \"$entry_visit_link\"
WHERE `entry_id` = $entry_id")) {
	debug("Can not update entry data in database table core_entries",0);
	if($dirpath != $newname) {
		if(!rename($newname,$dirpath)) {
			debug("Can not reverse renaming of folder. $newname to $dirpath",1);
		}
	}
	debug(mysql_error(),1);
}
	
debug("Ending script",0);
debug_echo();

?>