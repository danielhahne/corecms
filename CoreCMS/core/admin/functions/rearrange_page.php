<?
error_reporting(0);
require("_headr.php");
if($_SESSION["password"] != $pass || $_SESSION["sessid"] != session_id()) die(logout());

debug("Password validated, logged in",0);

$i=0;
$max = count($_REQUEST['listItem']);
debug("Rearranging $max items");
while($i<$max) {
	debug("Id ".($max-$i-1)." has position $i");
	$new_pos = $_REQUEST['listItem'][$max-$i-1];
	mysql_query("UPDATE core_pages p SET p.page_position = $i WHERE p.page_id = $new_pos");
	$i++;
}
?>