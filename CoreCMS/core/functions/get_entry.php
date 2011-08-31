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

if(is_numeric($id)) {
	$type = "entry";
} else {
	$type = "page";
}

$id = secure($id);

if($type == "page") {
	$data = mysql_query("SELECT p.* FROM core_pages p WHERE p.page_title = \"$id\"");
	$page_clicks = 0;
	while($p = mysql_fetch_array($data)) {
		$url = $p["page_url"];
		$path = $root . "user/pages/" . $url;
		$page_clicks = $p['hits']+1;
		require($path);
	}
	
	mysql_query("UPDATE core_pages p SET
	p.hits = $page_clicks
	WHERE p.page_title = $id");
}

if($type == "entry") {
	$data = mysql_query("SELECT e.* FROM core_entries e WHERE e.entry_id = $id AND e.entry_show = 1");
	$entry_clicks = 0;
	if(@mysql_num_rows($data) < 1) {
		die("Invalid id, no entry to be shown");
	}
	while($e = mysql_fetch_array($data)) {
		$entry_id		= $e['entry_id'];
		$entry_title	= $e['entry_title'];
		// DATE
		$t				= $e["entry_date"];
		$y 				= substr($t,0,4);
		$m 				= substr($t,5,2);
		$d 				= substr($t,8,2);
		$entry_date		= date($date_format,mktime(0,0,0,$m,$d,$y));
		$entry_text		= $e['entry_text'];
		$entry_extra1	= $e['entry_extra1'];
		$entry_extra2	= $e['entry_extra2'];
		$entry_visit_link = $e['entry_visit_link'];
		$entry_client	= $e['entry_client'];
		$entry_position	= $e['entry_position'];
		$entry_hits		= $e['hits']+1;
		$entry_new		= $e['entry_new'];
		
		
		if($entry_new == 1) {
			$isNew = true;
		} else {
			$isNew = false;
		}
			
		if($nice_permalinks) {
			$entry_perma = "$http".$entry_id;
		} else {
			$entry_perma = "$http"."?entry=$entry_id";
		}
		
		$data_e2t = @mysql_query("SELECT e2t.tag_id FROM core_entry2tag e2t WHERE e2t.entry_id = $entry_id");
				
		$tag_str = "";
			
			while($e2t = @mysql_fetch_array($data_e2t)) {
				$tag_id = $e2t["tag_id"];
				$data_tags = @mysql_query("SELECT t.tag_text FROM core_tags t WHERE t.tag_id = $tag_id");
					while($t = @mysql_fetch_array($data_tags)) {
						$tag_text = $t["tag_text"];
						$tag_str = $tag_str . "<a class=\"tag-link\" name=\"tag".$tag_id."\" href=\"#tag-"._encode($tag_text)."\">".$tag_text."</a>".$separator_tags;
					}
			}
			
			$entry_tags = substr($tag_str,0,strlen($tag_str)-strlen($separator_tags));
		
		$layout_path = $root . "user/uploads/" . treat_string($entry_title) . "/layout.php";
		if(is_file($layout_path) && (@filesize($layout_path) > 0)) {
			require($layout_path);
		} else {
			require($theme_path . "parts/entry.php");
		}
	}
	
	mysql_query("UPDATE core_entries e SET
	e.hits = $entry_hits
	WHERE e.entry_id = $id");
	
}

if($isPerma) {
echo "<a class=\"index-link\" href=\"$http\">back to index</a>";
}
?>