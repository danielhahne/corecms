<?php

	session_start(); 
	
	$connection = mysql_connect($db_server, $db_user, $db_pass) or die("Error: Failed to establish connection to database");
	mysql_select_db($db_name, $connection) or die("Error: Could not find specified database, check \"database name\" in configuration");
	
	///////////////////////////////
	//   VARIABLE DECLARATIONS   //
	///////////////////////////////

	$theme_path = $root . "themes/" . $theme . "/";
	$online_url_path = "/core/";
	$online_path = str_replace("http://".$_SERVER['HTTP_HOST'],'',$http);
	
	
	///////////////////////////////
	//         LOAD THEME        //
	///////////////////////////////
	
	function secure($s) {
		if(get_magic_quotes_gpc()==1) $s = stripslashes($s);
		$s = mysql_real_escape_string($s);
		return $s;
	}
	
	/* went to APIs */
	function LOAD_THEME() {
		global $theme_path;
		GET_STYLES($theme_path);
		GET_SCRIPTS($theme_path);
	}
	
	function GET_STYLES($path) {
		global $theme;
		$str = "core/themes/".$theme."/styles/";
		$arr = search_folder($path . "styles");
		foreach ($arr as $value) {
			echo "<link rel=\"stylesheet\" type=\"text/css\" href=\"$str$value\" />\n";
		}
	}
	
	function GET_SCRIPTS($path) {
		global $theme;
		$s = "jquery-";
		$str = "core/themes/".$theme."/scripts/";
		$arr = search_folder($path . "scripts");
		foreach ($arr as $value) {
			echo "<script type=\"text/javascript\" src=\"$str$value\"></script>\n";
		}
	}
	/**/
	
	function _encode($str) {
		$str = str_replace(" ","+",$str);
		return $str;
	}

	///////////////////////////////
	//  DATABASE RETRIVE ENTRIES //
	///////////////////////////////
	
	function LOAD_TAGS($type) {
		global $theme_path;
		$data = mysql_query("SELECT t.* FROM core_tags t ORDER BY t.tag_position DESC");
		
		if($type == "JAVASCRIPT") {
			echo "var tags=new Object();\n";
			while($t = mysql_fetch_array($data)) {
				$tag_id = $t["tag_id"];
				$id_str = "";
				$id_str = "tags.tag" . $tag_id . "=";
				$entries = mysql_query("SELECT e2t.entry_id FROM core_entry2tag e2t WHERE e2t.tag_id = $tag_id");
				
				if(@mysql_num_rows($entries)>0) {
					$id_str = $id_str . "[";
					while($e = mysql_fetch_array($entries)) {
						$id_str = $id_str . $e["entry_id"] . ",";
					}
					
					$id_str = substr($id_str,0,strlen($id_str)-1);
					$id_str = $id_str . "];\n";
				} else {
					$id_str = $id_str . "0;\n";
				}
				
				echo $id_str;
			}
		} else {
			while($r = mysql_fetch_array($data)) {
				$tag_text = $r["tag_text"];
				$tag_id = $r["tag_id"];		
				require($theme_path . "parts/tag_link.php");
			}
		}
	}
	
	function LOAD_ENTRIES($type) {
		global $online_path, $theme_path, $current_tag, $thumb_w, $thumb_h, $root, $date_format, $separator_tags, $show_empty, $nice_permalinks;
		$data_entries = mysql_query("SELECT * FROM core_entries e WHERE e.entry_show = 1 ORDER BY e.entry_position DESC");
		
		$absolute_path = "http://andreasklein.org";
		
		while($e = mysql_fetch_array($data_entries)) {
			$entry_id 		= $e["entry_id"];
			$entry_title	= $e["entry_title"];
			
			// DATE
			$date = $e["entry_date"];
			$y = substr($t,0,4);
			$m = substr($t,5,2);
			$d = substr($t,8,2);
			$entry_date	= date($date_format,mktime(0,0,0,$m,$d,$y));
			$entry_position	= $e["entry_position"];
			$entry_client	= $e["entry_client"];
			$entry_extra1	= $e["entry_extra1"];
			$entry_extra2	= $e["entry_extra2"];
			// NEW
			$entry_visit_link	= $e["entry_visit_link"];
			$entry_text		= $e["entry_text"];
			$entry_new		= $e["entry_new"];
			$hits			= $e["hits"];
			
			if($entry_new == 1) {
				$isNew = true;
			} else {
				$isNew = false;
			}
			
			if($nice_permalinks) {
				$entry_permalink = "$http".$entry_id;
			} else {
				$entry_permalink = "$http"."entry=$entry_id";
			}
			
			$data_e2t = @mysql_query("SELECT e2t.tag_id FROM core_entry2tag e2t WHERE e2t.entry_id = $entry_id");
			
			$tag_str = "";
			
			while($e2t = @mysql_fetch_array($data_e2t)) {
				$tag_id = $e2t["tag_id"];
				$data_tags = @mysql_query("SELECT t.tag_text FROM core_tags t WHERE t.tag_id = $tag_id ORDER BY t.tag_position DESC");
					while($t = @mysql_fetch_array($data_tags)) {
						$tag_text = $t["tag_text"];
						$tt_friendly = _encode($tag_text);
						$tag_str = $tag_str . "<a class=\"tag-link\" name=\"tag".$tag_id."\" href=\"#tag-".$tt_friendly."\">".$tag_text."</a>".$separator_tags;
					}
			}
			
			$entry_tags = substr($tag_str,0,strlen($tag_str)-strlen($separator_tags));
			
			
			/*
			*  Detect the src of the thumb and send it to phpThumb   
			*/
			
			
			/*------------------------------
			
			  01 Get entry-thumb
			  
			---------------------------------*/
			
			if($type == "THUMBS") {
				$folder = treat_string($entry_title);
				$r_str = $root . "user/uploads/" . $folder;
				$f_str = "core/user/uploads/" . $folder;
				$arr = search_folder($r_str);
				if($arr || $show_empty) {
					$img = false;
					$thumb = false;
					
					foreach($arr as $f) {
						if(strstr($f,"_thumb")) {
							$thumb = $f;
						}
						else
						{
							if(strstr($f,"_slider"))
							{
						    	if((!$thumb)) {
									$img = $f;
								}	
							}
						}
						
						
					}
					
					if($thumb) {
						$entry_thumb = "$f_str/$thumb";
					} elseif($img) {
						$entry_thumb = "core/functions/phpThumb/phpThumb.php?src=/$online_path/$f_str/$img&w=$thumb_w&h=$thumb_h&zc=1&f=png";
					}
	
					require($theme_path . "parts/entry_link_thumbs.php");
				}
				
			/*------------------------------

			  02 Get the entry-title and link_id

			---------------------------------*/	
				
			} elseif($type == "LIST") {
				$arr = search_folder($root . "user/uploads/" . treat_string($entry_title));
				if($arr || $show_empty) {
					require($theme_path . "parts/entry_link.php");
				}
				
				
			/*------------------------------
			
			  03 New functionality for getting just the '_slider' Images
			  
			---------------------------------*/	
				
			} elseif($type == "IMAGES") {
				
								
					$folder = treat_string($entry_title);
					$r_str = $root . "user/uploads/" . $folder;
					$f_str = "core/user/uploads/" . $folder;
					$arr = search_folder($r_str);
					
					if($arr || $show_empty) {
						$image = false;

						foreach($arr as $f) {
							if(strstr($f,"_slider")) {
								$image = $f;
								
								
								$entry_image = "$absolute_path/$f_str/$image";

								require($theme_path . "parts/entry_image.php");
							}
							else
							{
								//non usable
							}
						}
						
					}
				
			} // --- end if image
			
		}
	}
	
	
	function LOAD_MENU() {
		global $theme_path, $theme;
		$pages = mysql_query("SELECT p.* FROM core_pages p ORDER BY p.page_position DESC");
		while($p = mysql_fetch_array($pages)) {
			if($p["page_title"] != 'home')
			{
				$url = $p["page_url"];
				$title = $p["page_title"];
		    	require($theme_path . "parts/page_link.php");
		    }
		}
	}
	
	function LOAD_MEDIA($t) {
		global $root,$show_empty;
		$folder = treat_string($t);
		$arr = search_folder($root . "user/uploads/" . $folder);
		if(!$arr && $show_empty == 0) echo("Could not retrieve files.");
		else {
			foreach ($arr as $value) {
				$type = detect_type($value);
				$path = $folder . "/" . $value;
				
				// Edit and update for slider-Images
				if(!strstr($path,"_thumb") && !strstr($path,"_slider") && !strstr($path,"_hover")) {
					show_media($type, $path);
				}
			}
		}
	}
	
	function FILE_LOAD($file) {
		global $root,$entry_title;
		$path = $root . "user/uploads/" . treat_string($entry_title) . "/$file";
		if(is_file($path)) {
			show_media(detect_type($file),treat_string($entry_title)."/$file");
		}
	}
   
/* 
	function UPDATE_HITS(){
	      global $entry_id, $entry_hits;
	     id = secure($id);

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
	}  
	
	*/
	
	function show_media($type, $path) {
		global $theme_path, $root, $http;
		
		$absolute_path = "$http/core/user/uploads/$path";
		$full_path = "core/user/uploads/$path";
		
		switch($type) {
			case "video":
			require($theme_path . "parts/media/video.php");
		 	break;
				
			case "image":
			$size = getimagesize($root ."user/uploads/".$path);
			$img_x = $size[0]."px";
			$img_y = $size[1]."px";
			require($theme_path . "parts/media/image.php");
		 	break;
			
			case "sound":
			require($theme_path . "parts/media/sound.php");
			break;
			
			case "link":
			$file = fopen($root . "user/uploads/" . $path, "r");
			$read = fread($file,filesize($root . "user/uploads/" . $path));
			require($theme_path . "parts/media/link.php");
			break;
		}
	}
	
	function detect_type($obj) {
		$str = substr($obj, strrpos($obj,"."));
		
		$image = array(".jpg", ".jpeg", ".png", ".gif", ".tif", ".tiff");
		foreach($image as $val) {
			if($val == $str) {
				return "image";
			}
		}
	
		$video = array(".mov", ".mpg", ".mp4", ".avi", ".wmv", ".flv");
		foreach($video as $val) {
			if($val == $str) {
				return "video";
			}
		}
		
		$sound = array(".mp3", ".aac", ".wav", ".aif", ".flac", ".ogg");
		foreach($sound as $val) {
			if($val == $str) {
				return "sound";
			}
		}
		
		$links = array(".txt");
		foreach($links as $val) {
			if($val == $str) {
				return "link";
			}
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
		
		return($res);
	}
	
	function treat_string($str) {
		
		$str = stripcslashes($str);
		$a = '\"\'_ ÀÁÂÃÄÅÆÇÈÉÊËÌÍÎÏÑÒÓÔÕÖØÙÚÛÜÝßÜüàáâãäåæçèéêëìíîïñòóôõöøùúûýýþÿŔŕ°•¶!¡©"”@#¥£€¢$%‰∞&¶§/\|({[)}]=≠≈?¿±`´^*™:;,.<>≤≥';  
		$b = '------aaaaaaaceeeeiiiinoooooouuuuysUuaaaaaaaceeeeiiiinoooooouuuyybyrr-------------------------------------------------';
		$str = utf8_decode($str);
		$str = strtr($str,utf8_decode($a),$b);
		$str = strtolower($str);
		while(stristr($str, "--")) {
			$str = str_replace("--","-",$str);
		}
		
		return utf8_encode($str);		
	}
	
	
	function get_version() {
		//do not alter please.
		"<script src='http://core.weareastronauts.org/callhome/checkversion.js' type='text/javascript'></script>";
	}
	
	
	
// BACKWARDS COMPABILITY.
// THE FOLLOWING FUNCTIONS ARE TO LET PHP4 USERS UTILIZE CORE,
// IF YOU ARE CERTAIN YOU ARE RUNNING PHP5 AND YOU'RE IN HER
// MESSING AROUND, YOU SHOULD DELETE THESE JUST FOR THE HECK
// OF IT.

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

// ALRIGHT.. THAT WAS EASIER THAN I THOUGH. THANK YOU GOOGLE.


?>