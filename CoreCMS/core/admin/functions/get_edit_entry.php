<?
error_reporting(0);
require("_headr.php");
if($_SESSION["password"] != $pass || $_SESSION["sessid"] != session_id()) die(logout());

debug("Password validated, logged in",0);

$entry_id = $_REQUEST["id"];
if(!is_numeric($entry_id)) {
	debug("Id is not numeric",1);
}


$data = mysql_query("SELECT e.* FROM core_entries e WHERE e.entry_id=\"$entry_id\"");
if(!$data) {
	debug("Can not fetch entry data from database table core_entries",0);
	debug(mysql_error(),1);
}

if(mysql_num_rows($data) > 0) {
	while($e = mysql_fetch_array($data)) {
	
		$entry_id 		= $e["entry_id"];
		$entry_title 	= $e["entry_title"];
		$entry_text 	= $e["entry_text"];
		$entry_client 	= $e["entry_client"];
		$entry_extra1 	= $e["entry_extra1"];
		$entry_extra2 	= $e["entry_extra2"];
		$entry_new 		= $e["entry_new"];
		$entry_show 	= $e["entry_show"];
		$entry_hits 	= $e["hits"];
		
		$entry_visit_link	= $e["entry_visit_link"];
		
		$dirpath = $root . "user/uploads/" . treat_string($entry_title);
		
		if(is_dir($dirpath)) {
			$path = treat_string($entry_title);
		} else {
			debug("No folder connected to the entry found, creating one",0);
			if(!mkdir($dirpath)) {
				debug("Failed to create content folder",1);
			}
		}

		$full_path = $dirpath . "/";
		
		if(is_file($dirpath . "/layout.php")) {
		$layout = "Edit custom layout";	
		} else {
		$layout = "Add custom layout";	
		}
		
		debug("Ending debug",0);
		debug_echo();
		
?>
<p class="title-head"><b>Editing</b> <? echo $entry_title . " &mdash; ($entry_hits hits)"; ?> <span class="back-link" onclick="get_data('show_entries')">(back)</span></p>

<fieldset>
<legend>Actions</legend>
<p class="btn add" onclick="get_data('get_edit_file',{'type':'layout','path':'<? echo $dirpath; ?>','refreshurl':'get_edit_entry','refreshid':<? echo $entry_id; ?>})"><? echo $layout; ?></p>
<p class="btn delete" onclick="delete_data('delete_entry',{'id':<? echo $entry_id; ?>},'show_entries')">Delete entry</p>
<p class="btn delete" onclick="delete_data('reset_hits',{'id':<? echo $entry_id; ?>},'get_edit_entry',{'id':<? echo $entry_id; ?>})">Reset hits</p>
</fieldset>

<p class="clear"></p>
<p class="margin"></p>

<fieldset>
<legend>Files</legend>
<?

$filearray = search_folder($dirpath);
$i=0;
foreach($filearray as $f) {
detect_type($f);
$type = detect_type($f);
if($type == "image"){
$i++;
echo 	"<p class=\"media-thumb img\">
	  <img src=\"../functions/phpThumb/phpThumb.php?src=$dirpath/$f&w=100&h=60&zc=1&f=png\" /><br />
	  $f<br />
	  <span class=\"list-link\" onclick=\"delete_data('delete_file',{'path':'$dirpath/$f'},'get_edit_entry',{'id':$entry_id})\">Delete image</span>
	  </p>";
}elseif($type == "video"){
$i++;
echo "<p class=\"media-thumb vid\">
	  <img src=\"video.jpg\" /><br />
	  $f<br />
	  <span class=\"list-link\" onclick=\"delete_data('delete_file',{'path':'$dirpath/$f'},'get_edit_entry',{'id':$entry_id})\">Delete video</span>
	  </p>";
}elseif($type == "sound"){
$i++;
echo "<p class=\"media-thumb video\">
	  <img src=\"sound.jpg\" /><br />
	  $f<br />
	  <span class=\"list-link\" onclick=\"delete_data('delete_file',{'path':'$dirpath/$f'},'get_edit_entry',{'id':$entry_id})\">Delete sound</span>
	  </p>";
}elseif($type == "link"){
$i++;
echo "<p class=\"media-thumb link\">
	  <img src=\"link.jpg\" /><br />
	  $f<br />
	  <span class=\"list-link\" onclick=\"delete_data('delete_file',{'path':'$dirpath/$f'},'get_edit_entry',{'id':$entry_id})\">Delete link</span>
	  </p>";
}elseif($type == "thumbnail"){
$i++;
echo 	"<p class=\"media-thumb img\">
	  <img src=\"../functions/phpThumb/phpThumb.php?src=$dirpath/$f&w=100&h=60&zc=1&f=png\" /><br />
	  $f<br />
	  <span class=\"list-link\" onclick=\"delete_data('delete_file',{'path':'$dirpath/$f'},'get_edit_entry',{'id':$entry_id})\">Delete thumbnail</span>
	  </p>";
}
}

if($i == 0) {
echo "No files to display.<br />
<br />
";
}
?>
</fieldset>

<p class="clear"></p>
<p class="margin"></p>

<fieldset>
<legend>Upload</legend>
<form id="form1">
<div class="fieldset flash" id="fsUploadProgress">
</div>
<span id="legend"></span>
<div id="divStatus">
No files uploaded.
</div>

<p class="clear"></p>

<div>
<div id="replacer">
</div>
<p class="clear"></p>
<p class="btn delete" id="btnCancel" onclick="swfu.cancelQueue();">Cancel all</p>
</div>
</form>
</fieldset>

<p class="clear"></p>
<p class="margin"></p>

<form>
<fieldset>
<legend>Tags</legend>
<?
$tags = mysql_query("SELECT t.* FROM core_tags t");
if(mysql_num_rows($tags) > 0) {
while($t = mysql_fetch_array($tags)) {
$checked = "";
$tag_text = $t["tag_text"];
$tag_id = $t["tag_id"];

$lookup = mysql_query("SELECT e2t.entry_id FROM core_entry2tag e2t WHERE e2t.tag_id = $tag_id") or die(mysql_error());
if(mysql_num_rows($lookup) > 0) {
  while($check = mysql_fetch_array($lookup)) {
	  if($check["entry_id"] == $entry_id) {
		  $checked = "checked=\"checked\" ";
	  }
  }
}
echo "<p class=\"form-obj\">".$tag_text."<br />";
echo '<input id="tag'.$tag_id.'" type="checkbox" '.$checked.'/></p>';
}
}

$new_checked = "";
if($entry_new == 1) {
$new_checked = "checked='checked'";
}

$show_checked = "";
if($entry_show == 1) {
$show_checked = "checked='checked'";
}	
?>
</fieldset>

<p class="clear"></p>
<p class="margin"></p>

<fieldset>
<legend>Switches</legend>
<p class="clear"></p>
<p class="form-obj">New<br /><input id="entry_new" type="checkbox" <? echo $new_checked; ?> /></p>
<p class="form-obj">Show<br /><input id="entry_show" type="checkbox" <? echo $show_checked; ?> /></p>
<p class="clear"></p>
</fieldset>

<p class="clear"></p>
<p class="margin"></p>

<fieldset>
<legend>Inputs</legend>
<input id="entry_id" type="hidden" value="<? echo $entry_id; ?>" />
<input id="entry_title_old" type="hidden" value="<? echo htmlspecialchars($entry_title); ?>" />
<input id="entry_title" type="text" class="text-input" value="<? echo htmlspecialchars($entry_title); ?>" /><p class="form-title">&mdash;Title</p><p class="help-ico"></p>
<div class="help-box">The title of the entry, this is accessed by the string CORE(ENTRY:TITLE)</div><p class="clear"></p>
<input id="entry_client" type="text" class="text-input" value="<? echo htmlspecialchars($entry_client); ?>" /><p class="form-title">&mdash;Client</p><p class="help-ico"></p>
<div class="help-box">A client field, can be whatever you want though, accessed by CORE(ENTRY:CLIENT)</div><p class="clear"></p>
<input id="entry_extra1" type="text" class="text-input" value="<? echo htmlspecialchars($entry_extra1); ?>" /><p class="form-title">&mdash;Extra1</p><p class="help-ico"></p>
<div class="help-box">Extra field 1, accessed by CORE(ENTRY:EXTRA1)</div><p class="clear"></p>
<input id="entry_extra2" type="text" class="text-input" value="<? echo htmlspecialchars($entry_extra2); ?>" /><p class="form-title">&mdash;Extra2</p><p class="help-ico"></p>
<div class="help-box">Extra field 2, accessed by CORE(ENTRY:EXTRA2)</div><p class="clear"></p>

<!-- NEW -->
<input id="entry_visit_link" type="text" class="text-input" value="<? echo htmlspecialchars($entry_visit_link); ?>" /><p class="form-title">&mdash;Visit Link</p><p class="help-ico"></p>
<div class="help-box">Visit Link for Websites, accessed by CORE(ENTRY:VISIT)</div><p class="clear"></p>


<p class="margin"></p>
<p class="clear"></p>
<p class="form-title">Text</p><p class="help-ico"></p>
<div class="help-box">A textfield, intended for longer text, accessed by CORE(ENTRY:TEXT)</div>
<p class="clear"></p>
<textarea rows="10" id="entry_text"><? echo htmlspecialchars($entry_text); ?></textarea>
<p class="clear"></p></fieldset></form>
<p class="margin"></p>
<p class="clear"></p>
<p class="btn add" onclick="submit_form('edit_entry','get_edit_entry',{'id':<? echo $entry_id; ?>})">Submit changes</p>

<?
}
}
?>

<script type="text/javascript">
function uploadComplete(file) {
if (this.getStats().files_queued === 0) {
get_data('get_edit_entry',{'id':'<? echo $entry_id; ?>'});
}
}

var ACTIVE_PATH = "<? echo $full_path; ?>";
var settings = {
flash_url : "functions/js/swfupload/swfupload.swf",
upload_url: "functions/js/swfupload/upload.php",
post_params: {"path" : ACTIVE_PATH },
file_size_limit : "1000 MB",
file_types : "*.*",
file_types_description : "All Files",
file_upload_limit : 100,
file_queue_limit : 0,
custom_settings : {
progressTarget : "fsUploadProgress",
cancelButtonId : "btnCancel"
},
debug: false,

// Button settings
button_width: "50",
button_height: "25",
button_placeholder_id: "replacer",
button_text: 'Upload',
button_text_left_padding: 12,
button_text_top_padding: 5,

// The event handler functions are defined in handlers.js
file_queued_handler : fileQueued,
file_queue_error_handler : fileQueueError,
file_dialog_complete_handler : fileDialogComplete,
upload_start_handler : uploadStart,
upload_progress_handler : uploadProgress,
upload_error_handler : uploadError,
upload_success_handler : uploadComplete,
queue_complete_handler : queueComplete	// Queue plugin event
};

swfu = new SWFUpload(settings)

</script>
