<?
error_reporting(0);
require("_headr.php");
if($_SESSION["password"] != $pass || $_SESSION["sessid"] != session_id()) die(logout());

debug("Password validated, logged in",0);

$tag_text = $_REQUEST["tag_text"];

if(!$tag_text) {
	debug("Tag title is blank",1);
}


$tag_p = 0;

$content = mysql_query("SELECT t.* FROM core_tags t ORDER BY t.tag_position");

if(@mysql_num_rows($content) > 0) {	
	while($data = mysql_fetch_array($content)) {
		$p = $data["tag_position"];
		if($p > $tag_p) {
			$tag_p = $p;
		}
	}
	$tag_p++;
}


//add to database
if(!mysql_query("INSERT INTO `core_tags` (
`tag_text`
)
VALUES (
\"$tag_text\"
);")) {
	debug("Error inserting data in database",0);
	debug(mysql_error(),1);
}

debug("Ending script",0);
debug_echo();
?>