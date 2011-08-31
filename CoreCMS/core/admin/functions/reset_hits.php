<?
error_reporting(0);
require("_headr.php");
	
	if($_SESSION["password"] == $pass && $_SESSION["sessid"] == session_id()) {
			
		$id = $_REQUEST["id"];
		if($id == "all") {
			//DATABASE CHANGE	
			mysql_query("UPDATE core_entries e SET `hits` = 0") or die("Failed: ".mysql_error());
		} else if(is_numeric($id)) {
			mysql_query("UPDATE core_entries e SET `hits` = 0 WHERE `entry_id` = $id") or die("Failed: ".mysql_error());
		}
	}
?>