<?
error_reporting(0);
require("_headr.php");
if($_SESSION["password"] != $pass || $_SESSION["sessid"] != session_id()) die(logout());

debug("Password validated, logged in",0);


$dirpath 	= 	$root . "themes";
$full_path 	= 	$dirpath . "/";




?>
<p class="title-head">Style/Layout</p>
These are the folders that are inside core/themes/. To use another theme, see CONFIGURATION, or upload a new one in the fields below.
<p class="margin"></p>
<fieldset>
<legend>Themes</legend>
<?

$th_arr = search_folder($root . "themes");
foreach($th_arr as $th) {
	$s = "";
	if($th == $theme)
		$s = " (Currently used)";
	else
		$s = "";
	echo 	"<p class=\"list-item\">\n
			<b><span class=\"list-link\" onclick=\"get_data('get_edit_theme',{'id':'$th'})\">$th</span></b>$s<br />\n
			</p>\n";
   
}
?>
</fieldset>


<!-- Upload theme function -->


<p class="clear"></p>
<p class="margin"></p>

<fieldset>
<legend>Upload</legend>
<form id="form1">
<div class="fieldset flash" id="fsUploadProgress">
</div>
<span id="legend"></span>
<div id="divStatus">
No theme uploaded.
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



<script type="text/javascript">
function uploadComplete(file) {
if (this.getStats().files_queued === 0) {
	get_data('add_theme',{'path':'<? echo $full_path ?>', 'file':file.name});
	//get_data('get_edit_theme',{'id':file.name});
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


