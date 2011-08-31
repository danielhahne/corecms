<?php


$b = $_SERVER['REQUEST_URI'];
$b = substr($b,0,mb_strrpos($b,"/core/")+6);
$root = $_SERVER['DOCUMENT_ROOT'] . $b;
$http = "http://" . $_SERVER['HTTP_HOST'] . substr($b,0,strlen($b)-5);

require_once($root . "/user/configuration.php");
require($root . "/functions/session.php");

if($use_rss == 0) {
	exit("No feed available");
}

function LOAD_MEDIA_RSS($t) {
	global $root;
	$folder = treat_string($t);
	$arr = search_folder($root . "user/uploads/" . $folder);
	if(!$arr) echo("No files to display");
	else {
		foreach ($arr as $value) {
			$type = detect_type($value);
			$path = $folder . "/" . $value;
			show_media_rss($type, $path);
		}
	}
}

function show_media_rss($type, $path) {
	global $theme_path, $root;
	switch($type) {
		case "video":
		break;
			
		case "image":
                ?>
				&lt;img src="<? echo $http; ?>user/uploads/<? echo $path; ?>" /&gt;            
            	<?
		break;
	}
}

echo '<?xml version="1.0" encoding="ISO-8859-1" ?>';
?>

<rss version="2.0">
<channel>

  <title><? echo $title ?></title>
  <link>http://<? echo $_SERVER['HTTP_HOST']; ?></link>
  <description>Generated rss-feed from <? echo $title." (".str_replace("http://","",$http).")"; ?></description>
  
<?

$connection = mysql_connect($db_server, $db_user, $db_pass) or die("Error: Failed to establish connection to database");
mysql_select_db($db_name, $connection) or die("Error: Could not find specified database, check \"database name\" in configuration");

$data = mysql_query("SELECT e.* FROM core_entries e WHERE e.entry_show = 1 ORDER BY e.entry_position DESC");

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
	$entry_client	= $e['entry_client'];
	$entry_position	= $e['entry_position'];
	$entry_clicks	= $e['hits']+1;
	
			
			if($nice_permalinks) {
				$entry_permalink = $http.$entry_id;
			} else {
				$entry_permalink = $http."index.php?entry=$entry_id";
			}	
	?>
    
    <item>
    	<title><? echo $entry_title; ?></title>
        <link><? echo $entry_permalink; ?></link>
        <description>
        <?
		
		LOAD_MEDIA_RSS($entry_title);
		
		echo $entry_text;
		?>
        </description>
    </item>
    
    <?	
}
?>
</channel>
</rss>
