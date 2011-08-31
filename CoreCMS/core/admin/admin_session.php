<?php

	session_start();
	
	///////////////////////////////
	//   VARIABLE DECLARATIONS   //
	///////////////////////////////
	
	global $root, $theme;
	$theme_path = $root . "themes/" . $theme . "/";
	$online_url_path = "/core/";
	
	function logout() {
		session_unregister("password");
		session_unregister("sessid");
		session_destroy();
		die("Not logged in");
	}
	
	function set_root() {
		$b = $_SERVER['REQUEST_URI'];
		$b = substr($b,0,mb_strrpos($b,"/core/")+6);
		$root = $_SERVER['DOCUMENT_ROOT'] . $b;
		return $root;
	}
	
	function debug($str,$value) {
		global $debug_str;
		if($value == 1) {
			$debug_str = $debug_str . "<br />Fatal error: " . $str;
			die(debug_echo());
		} else {
			$debug_str = $debug_str . "<br />" . $str;
		}
	}
	
	function debug_echo() {
		global $debug_str, $debugging;
		if($debugging) {
			echo "<p class=\"debug-window\">";
			echo "<b>Debug log:</b><br />";
			echo $debug_str;
			echo "<br /><br /><span class=\"debug-clear\">clear</span>";
			echo "</p>";
		}
	}


	function search_folder($dir) {
		if(is_dir($dir) == false) {
			return false;
		}
		
		$arr = scandir($dir);
		$res = array();
		foreach ($arr as $value) {
			if(strlen($value) >= 4) {
				array_push($res,$value);
			}
		}
		if(!$arr) return false;
		return($res);
	}
	
	function __r($t) {
		$t = $_REQUEST[$t];
		if(get_magic_quotes_gpc()) return $t;
		else stripslashes($t);
	}
	
	
	function detect_type($obj) {
		if(strstr($obj,"_thumb")) return "thumbnail";
		
		$str = substr($obj, strrpos($obj,"."));
		$video = array(".mov", ".mpg", ".mp4", ".avi", ".wmv", ".flv");
		$image = array(".jpg", ".jpeg", ".png", ".gif", ".tif", ".tiff");
		$sound = array(".mp3", ".aac", ".wav", ".aif", ".flac", ".ogg");
		$links = array(".txt", ".html");
		$layout = array(".php", ".php4", ".php5");
		
		foreach($image as $val) {
			if($val == $str) {
				return "image";
			}
		}
		foreach($video as $val) {
			if($val == $str) {
				return "video";
			}
		}
		foreach($sound as $val) {
			if($val == $str) {
				return "sound";
			}
		}
		foreach($links as $val) {
			if($val == $str) {
				return "link";
			}
		}
		foreach($layout as $val) {
			if($val == $str) {
				return "layout";
			}
		}
	}
	
	function treat_string($str) {
		
		$str = stripcslashes($str);
		$a = '\\\"\'_ ÀÁÂÃÄÅÆÇÈÉÊËÌÍÎÏÑÒÓÔÕÖØÙÚÛÜÝßÜüàáâãäåæçèéêëìíîïñòóôõöøùúûýýþÿŔŕ°•¶!¡©"”@#¥£€¢$%‰∞&¶§/\|({[)}]=≠≈?¿±`´^*™:;,.<>≤≥';  
		$b = '--------aaaaaaaceeeeiiiinoooooouuuuysUuaaaaaaaceeeeiiiinoooooouuuyybyrr-------------------------------------------------';
		$str = utf8_decode($str);
		$str = strtr($str,utf8_decode($a),$b);
		$str = strtolower($str);
		while(stristr($str, "--")) {
			$str = str_replace("--","-",$str);
		}
		
		return utf8_encode($str);		
	}
	
	
	function get_version() {
		echo "<script src='http://core.weareastronauts.org/callhome/checkversion.js' type='text/javascript'></script>";
	}

if( !function_exists('scandir') ) {
    function scandir($directory, $sorting_order = 0) {
        $dh  = opendir($directory);
        while( false !== ($filename = readdir($dh)) ) {
            $files[] = $filename;
        }
        if( $sorting_order == 0 ) {
            sort($files);
        } else {
            rsort($files);
        }
        return($files);
    }
}

function login($sessid, $pass) {
	session_register("password","sessid");
	$_SESSION["password"] = $pass;
	$_SESSION["sessid"] = $sessid;
}

function delete_directory($dirname) {
	if (is_dir($dirname))
	$dir_handle = opendir($dirname);
	
	if (!$dir_handle)
	return false;
	
	while($file = readdir($dir_handle)) {
		if ($file != "." && $file != "..") {
			if (!is_dir($dirname."/".$file))
			unlink($dirname."/".$file);
			else delete_directory($dirname.'/'.$file);          
		}
	}
	closedir($dir_handle);
	rmdir($dirname);
	return true;
}

function readable_vars($str) {
	$str = str_replace("<? echo \$entry_title; ?>","CORE(ENTRY:TITLE)",$str);
	$str = str_replace("<? echo \$title; ?>","CORE(PAGE:TITLE)",$str);
	$str = str_replace("<? echo _encode(\$title); ?>","CORE(PAGE:TITLE:URL)",$str);
	$str = str_replace("<? echo \$entry_thumb; ?>","CORE(ENTRY:THUMB)",$str);
	$str = str_replace("<? echo \$entry_date; ?>","CORE(ENTRY:DATE)",$str);
	$str = str_replace("<? echo \$entry_id; ?>","CORE(ENTRY:ID)",$str);
	$str = str_replace("<? echo \$entry_position; ?>","CORE(ENTRY:POSITION)",$str);
	$str = str_replace("<? echo \$entry_text; ?>","CORE(ENTRY:TEXT)",$str);
	$str = str_replace("<? echo \$entry_client; ?>","CORE(ENTRY:CLIENT)",$str);
	$str = str_replace("<? echo \$entry_extra1; ?>","CORE(ENTRY:EXTRA1)",$str);
	$str = str_replace("<? echo \$entry_extra2; ?>","CORE(ENTRY:EXTRA2)",$str);
	// NEW
	$str = str_replace("<? echo \$entry_visit_link; ?>","CORE(ENTRY:VISIT)",$str);
	$str = str_replace("<? echo \$entry_perma; ?>","CORE(ENTRY:PERMALINK)",$str);
	$str = str_replace("<? echo \$entry_hits; ?>","CORE(ENTRY:HITS)",$str);
	$str = str_replace("<? echo \$entry_tags; ?>","CORE(ENTRY:TAGS)",$str);
	$str = str_replace("<? LOAD_MEDIA(\$entry_title); ?>","CORE(ENTRY:MEDIA)",$str);
	$str = str_replace("<? LOAD_MENU(); ?>","CORE(LOAD:PAGES)",$str);
	$str = str_replace("<? LOAD_ENTRIES(\"LIST\"); ?>","CORE(LOAD:ENTRIES(LIST))",$str);
	$str = str_replace("<? LOAD_ENTRIES(\"THUMBS\"); ?>","CORE(LOAD:ENTRIES(THUMBS))",$str);
	$str = str_replace("<? LOAD_TAGS(\"LIST\"); ?>","CORE(LOAD:TAGS)",$str);
	$str = str_replace("<? echo \$tag_id; ?>","CORE(TAG:ID)",$str);
	$str = str_replace("<? echo \$tag_text; ?>","CORE(TAG:TITLE)",$str);
	$str = str_replace("<? echo _encode(\$tag_text); ?>","CORE(TAG:TITLE:URL)",$str);
	$str = str_replace("<? echo \$path; ?>","CORE(MEDIA:PATH)",$str);
	$str = str_replace("<? echo \$full_path; ?>","CORE(MEDIA:FULL-PATH)",$str);
	$str = str_replace("<? echo \$absolute_path; ?>","CORE(MEDIA:ABSOLUTE-PATH)",$str);
	$str = str_replace("<? echo \$read; ?>","CORE(MEDIA:READ)",$str);
	$str = str_replace("<? echo \$img_y; ?>","CORE(MEDIA:IMAGE-HEIGHT)",$str);
	$str = str_replace("<? echo \$img_x; ?>","CORE(MEDIA:IMAGE-WIDTH)",$str);
	// NEW	
	$str = str_replace("<? LOAD_ENTRIES(\"IMAGES\"); ?>","CORE(LOAD:ENTRIES(IMAGES))",$str);
	$str = str_replace("<? echo \$entry_image; ?>","CORE(ENTRY:IMAGE)",$str);
	
	while(strstr($str,"<? FILE_LOAD(\"")) {
		$start = strpos($str,"<? FILE_LOAD(\"");
		$end = strpos($str,"\"); ?>",$start);
		$len = $end-$start;
		$file = substr($str,strpos($str,"<? FILE_LOAD(\"")+14,$len-14);
		$replace = "CORE(FILE:$file:LOAD)";
		$str = substr_replace($str,$replace,$start,20+strlen($file));
	}
	
	while(strstr($str,"<? if(\$isNew) { ?>")) {
		$start = strpos($str,"<? if(\$isNew) { ?>");
		$end = strpos($str,"<? } ?>",$start);
		$len = $end-$start;
		$new = substr($str,strpos($str,"<? if(\$isNew) { ?>")+18,$len-18);
		$replace = "CORE(NEW:START)$new(NEW:END)";
		$str = substr_replace($str,$replace,$start,25+strlen($new));
	}
	
	return $str;
}

function php_vars($str) {
	$str = str_replace("CORE(ENTRY:TITLE)","<? echo \$entry_title; ?>",$str);
	$str = str_replace("CORE(PAGE:TITLE)","<? echo \$title; ?>",$str);
	$str = str_replace("CORE(PAGE:TITLE:URL)","<? echo _encode(\$title); ?>",$str);
	$str = str_replace("CORE(ENTRY:DATE)","<? echo \$entry_date; ?>",$str);
	$str = str_replace("CORE(ENTRY:ID)","<? echo \$entry_id; ?>",$str);
	$str = str_replace("CORE(ENTRY:POSITION)","<? echo \$entry_position; ?>",$str);
	$str = str_replace("CORE(ENTRY:TEXT)","<? echo \$entry_text; ?>",$str);
	$str = str_replace("CORE(ENTRY:CLIENT)","<? echo \$entry_client; ?>",$str);
	$str = str_replace("CORE(ENTRY:EXTRA1)","<? echo \$entry_extra1; ?>",$str);
	$str = str_replace("CORE(ENTRY:EXTRA2)","<? echo \$entry_extra2; ?>",$str);
	//NEW
	$str = str_replace("CORE(ENTRY:VISIT)","<? echo \$entry_visit_link; ?>",$str);
	$str = str_replace("CORE(ENTRY:PERMALINK)","<? echo \$entry_perma; ?>",$str);
	$str = str_replace("CORE(ENTRY:HITS)","<? echo \$entry_hits; ?>",$str);
	$str = str_replace("CORE(ENTRY:TAGS)","<? echo \$entry_tags; ?>",$str);
	$str = str_replace("CORE(ENTRY:MEDIA)","<? LOAD_MEDIA(\$entry_title); ?>",$str);
	$str = str_replace("CORE(LOAD:PAGES)","<? LOAD_MENU(); ?>",$str);
	$str = str_replace("CORE(LOAD:ENTRIES(LIST))","<? LOAD_ENTRIES(\"LIST\"); ?>",$str);
	$str = str_replace("CORE(LOAD:ENTRIES(THUMBS))","<? LOAD_ENTRIES(\"THUMBS\"); ?>",$str);
	$str = str_replace("CORE(LOAD:TAGS)","<? LOAD_TAGS(\"LIST\"); ?>",$str);
	$str = str_replace("CORE(TAG:ID)","<? echo \$tag_id; ?>",$str);
	$str = str_replace("CORE(TAG:TITLE)","<? echo \$tag_text; ?>",$str);
	$str = str_replace("CORE(TAG:TITLE:URL)","<? echo _encode(\$tag_text); ?>",$str);
	$str = str_replace("CORE(MEDIA:PATH)","<? echo \$path; ?>",$str);
	$str = str_replace("CORE(MEDIA:FULL-PATH)","<? echo \$full_path; ?>",$str);
	$str = str_replace("CORE(MEDIA:ABSOLUTE-PATH)","<? echo \$absolute_path; ?>",$str);
	$str = str_replace("CORE(MEDIA:READ)","<? echo \$read; ?>",$str);
	$str = str_replace("CORE(MEDIA:IMAGE-HEIGHT)","<? echo \$img_y; ?>",$str);
	$str = str_replace("CORE(MEDIA:IMAGE-WIDTH)","<? echo \$img_x; ?>",$str);
	
	// NEW
	$str = str_replace("CORE(LOAD:ENTRIES(IMAGES))","<? LOAD_ENTRIES(\"IMAGES\"); ?>",$str);
	$str = str_replace("CORE(ENTRY:IMAGE)","<? echo \$entry_image; ?>",$str);
	
	while(strstr($str,"CORE(FILE:")) {
		$start = strpos($str,"CORE(FILE:");
		$end = strpos($str,":LOAD)",$start);
		$len = $end-$start;
		$file = substr($str,strpos($str,"CORE(FILE:")+10,$len-10);
		$replace = "<? FILE_LOAD(\"$file\"); ?>";
		$str = substr_replace($str,$replace,$start,16+strlen($file));
	}
	
	while(strstr($str,"CORE(NEW:START)")) {
		$start = strpos($str,"CORE(NEW:START)");
		$end = strpos($str,"(NEW:END)",$start);
		$len = $end-$start;
		$new = substr($str,strpos($str,"CORE(NEW:START)")+15,$len-15);
		$replace = "<? if(\$isNew) { ?>$new<? } ?>";
		$str = substr_replace($str,$replace,$start,24+strlen($new));
	}
		
	return $str;
}

?>