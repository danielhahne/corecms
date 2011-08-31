<?
error_reporting(0);
require("_headr.php");
if($_SESSION["password"] != $pass || $_SESSION["sessid"] != session_id()) die(logout());

debug("Password validated, logged in",0);

$contents = mysql_query("SELECT e.* FROM core_entries e ORDER BY e.entry_position DESC") or die(mysql_error());
$count = mysql_num_rows($contents);

$pages = mysql_query("SELECT p.* FROM core_pages p ORDER BY p.page_position DESC");
$p_count = mysql_num_rows($pages);
?>
    
<p class="title-head">Content</p>
Here you can add content to your website. <strong>Pages</strong> are typically used for information such as how to contact you, references and other information. <strong>Entries</strong> on the other hand are typically used to display your work. Both these are rearrangeble, click and drag on the little arrow beside the entry/page. When you're happy, click submit rearrange.
<p class="margin"></p>

<fieldset>
<legend>Pages&mdash; <? echo $p_count; ?> total</legend>
<p class="btn add" onclick="get_data('get_add_file',{'type':'page','refreshurl':'show_entries'})">Add new page</p>
<p class="btn add" onclick="p_update()">Submit rearrange</p>
<p class="clear"></p>
<p class="margin"></p>
<div id="pages">
<?
while($p = mysql_fetch_array($pages)) {
	$p_title = $p["page_title"];
	$p_path = $root . "user/pages/" . $p["page_url"];
	$p_id = $p["page_id"];
	
	echo 	"<div class=\"list-item\" id=\"listItem_".$p_id."\">
			<span class=\"arr\"><img src=\"arr.jpg\" /></span> <span class=\"list-link\" onclick=\"get_data('get_edit_file',{'type':'page','path':'$p_path','refreshurl':'show_entries','refreshid':0})\">
			".$p_title."</span>
			</div>";
}
?>
</div>
</fieldset>
<p class="margin"></p>
<fieldset>
<legend>Entries&mdash; <? echo $count; ?> total</legend>
<p class="btn add" onclick="get_data('get_add_entry')">Add new entry</p>
<p class="btn add" onclick="e_update()">Submit rearrange</p>
<p class="btn add" onclick="toggleExtra('hide',this)">Hide extra</p>
<p class="clear"></p>
<p class="margin"></p>
<div id="entries">
<?
while($e = @mysql_fetch_array($contents)) {
	$entry_id = $e["entry_id"];
	$entry_title = $e["entry_title"];
	$entry_position = $e["entry_position"]+1;
	$entry_hits = $e["hits"];
	$p = $root . "user/uploads/" . treat_string($entry_title);
	$files = search_folder($p);
	$images = $video = $sound = $other = 0;
	$layout = "";
	if($files){
		foreach($files as $f) {
			$type = detect_type($f);
			if($type == "image"){
				$images++;
			}elseif($type == "video"){
				$video++;
			}elseif($type == "sound"){
				$sound++;
			}elseif($type == "layout"){
				if(filesize($p."/layout.php") > 0) $layout = "custom layout";
			} else {
				$other++;
			}
		}
	}
	
	if($images > 0) $images = "$images image(s),";
	else $images = "";
	if($video > 0) $video = "$video video(s),";
	else $video = "";
	if($sound > 0) $sound = "$sound sound file(s),";
	else $sound = "";
	if($other > 0) $other = "$other other(s),";
	else $other = "";
	
	$br = "";
	if($images || $video || $sound || $other || $layout) $br = "<br />";
	
	$e2t = mysql_query("SELECT e2t.* FROM core_entry2tag e2t WHERE e2t.entry_id = $entry_id") or send_err(mysql_error(),1,true);
	$tagstr = "";
	while($n = @mysql_fetch_array($e2t)) {
		$tag_id = $n["tag_id"];
		$tags = mysql_query("SELECT t.* FROM core_tags t WHERE t.tag_id = $tag_id") or send_err(mysql_error(),1,true);
		while($t = @mysql_fetch_array($tags)) {
			$tag_text = $t["tag_text"];
			$tagstr = "$tagstr $tag_text, "; 
		}
	}
	$tagstr = substr($tagstr,0,strlen($str)-2);
	if($tagstr) $tagstr = $tagstr . "<br />"; 
	echo 	"<div class=\"list-item\" id=\"listItem_".$entry_id."\">
			<span class=\"arr\"><img src=\"arr.jpg\" /></span> <span class=\"list-link\" onclick=\"get_data('get_edit_entry',{'id':$entry_id})\"><b>$entry_title</b> #$entry_id &mdash;$entry_hits hits</span>
			<p class=\"extra\">
			".$tagstr." ".
			$images." ".$video." ".$sound." ".$other." ".$layout." ".$br."
			</p>
			</div>";
   
}
?>
</div>
</fieldset>

<script type="text/javascript">
function rearrange(order) {
	$.ajax({
		type: "GET",
		url: "functions/rearrange_entry.php",
		data: order
	});
}

function rearrange_p(order) {
	$.ajax({
		type: "GET",
		url: "functions/rearrange_page.php",
		data: order
	});
}

function e_update() {
	var order = $('#entries').sortable('serialize'); 
	rearrange(order);
	get_data('show_entries');
}


function p_update() {
	var order = $('#pages').sortable('serialize'); 
	rearrange_p(order);
	get_data('show_entries');
}

$("#entries").sortable(); 
$("#pages").sortable();
</script>