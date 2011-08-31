<?php

$b = $_SERVER['REQUEST_URI'];

if($entry) {
	$b = substr($b,0,strrpos($b,"/")) . "/core/";
	$id = $entry;
	$isPerma = true;
} else {
	$b = substr($b,0,mb_strrpos($b,"/core/")+6);
	$id = $_REQUEST["id"];
}

$root = $_SERVER['DOCUMENT_ROOT'] . $b;
$http = "http://" . $_SERVER['HTTP_HOST'] . substr($b,0,strlen($b)-5);

require_once($root . "user/configuration.php");
require_once($root . "themes/".$theme."/configuration.php");
require_once($root . "functions/session.php");

$id = secure($id);

if(is_numeric($id)) {
	$data = mysql_query("SELECT e.* FROM core_entries e WHERE e.entry_id = $id AND e.entry_show = 1");
	$entry_clicks = 0;
	if(@mysql_num_rows($data) < 1) {
		die("Invalid id, no entry to be shown");
	}
	while($e = mysql_fetch_array($data)) {
		$entry_id		= $e['entry_id'];
		$entry_hits   = $e['hits']+1;
	}
	
	mysql_query("UPDATE core_entries e SET
	e.hits = $entry_hits
	WHERE e.entry_id = $id");
}
?>