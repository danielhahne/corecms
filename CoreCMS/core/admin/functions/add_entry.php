<?
error_reporting(0);
require("_headr.php");
if($_SESSION["password"] != $pass || $_SESSION["sessid"] != session_id()) die(logout());

debug("Password validated, logged in",0);

$entry_title = $_REQUEST["entry_title"];
$entry_text = $_REQUEST["entry_text"];
$entry_date = date("Y-m-d");
$entry_client = $_REQUEST["entry_client"];
$entry_extra1 = $_REQUEST["entry_extra1"];
$entry_extra2 = $_REQUEST["entry_extra2"];
// NEW
$entry_visit_link = $_REQUEST["entry_visit_link"];

if(strlen($entry_title) < 1) {
	debug("Entry title is blank",1);
}

$newdir = $root . "user/uploads/" . treat_string($entry_title);
if(is_dir($newdir)) {
	debug("Can not create directory at $newdir, already a folder with that name",1);
}

if(!mkdir($newdir)) {
	debug("Failed to execute command mkdir($newdir)",1);
}

//set position
$entry_p = 0;

$content = mysql_query("SELECT e.* FROM core_entries e ORDER BY e.entry_position");

if(mysql_num_rows($content) > 0) {	
	while($data = mysql_fetch_array($content)) {
		$p = $data["entry_position"];
		if($p > $entry_p) {
			$entry_p = $p;
		}
	}
	$entry_p++;
}



//add to database
if(!mysql_query("INSERT INTO `core_entries` (
`entry_position`,
`entry_date`,
`entry_new`,
`entry_title`,
`entry_text`,
`entry_client`,
`entry_extra1`,
`entry_extra2`,
`entry_visit_link`
)
VALUES (
$entry_p, \"$entry_date\", 1, \"$entry_title\", \"$entry_text\", \"$entry_client\", \"$entry_extra1\", \"$entry_extra2\", \"$entry_visit_link\"
);")){
	debug("Error inserting data in database",0);
	debug(mysql_error(),1);
}

// GET ID.
$id = mysql_query("SELECT * FROM `core_entries` WHERE `entry_position` = $entry_p LIMIT 1");

if(!$id) {
	debug("Can not fetch new entry with position $entry_p",0);
	debug(mysql_error(),1);
}

while($entry = mysql_fetch_array($id)) {
	$entry_id = $entry["entry_id"];
}

$tags = mysql_query("SELECT * FROM `core_tags`");
if(!$tags) {
	debug("Can not fetch tags");
	debug(mysql_error(),1);
}

if(mysql_num_rows($tags) > 0);
while($tag_data = mysql_fetch_array($tags)) {
	$tag_text = $tag_data["tag_text"];
	$tag_id = $tag_data["tag_id"];
	$url_tag = $_REQUEST["tag".$tag_id];
	if($url_tag) {
		if(!mysql_query("INSERT INTO `core_entry2tag` (`tag_id`,`entry_id`) VALUES ($tag_id, $entry_id)")){
			debug("Failed adding tags");
			debug(mysql_error(),1);
		};
	}
}

debug("Ending script",0);
debug_echo();
?>