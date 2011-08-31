<?
error_reporting(0);
require("_headr.php");
if($_SESSION["password"] != $pass || $_SESSION["sessid"] != session_id()) die(logout());

debug("Password validated, logged in",0);

$type = $_REQUEST["type"];
$selected_theme = $_REQUEST["refreshid"];
$refreshurl = $_REQUEST["refreshurl"];
$refreshid = $_REQUEST["refreshid"];
	
if(strlen($type) < 1) {
	debug("Did not get variable for type.",1);
}

if($type == "script") {
	$folder = $root."themes/".$selected_theme."/scripts/";
} else if($type == "style") {
	$folder = $root."themes/".$selected_theme."/styles/";
} else {
	$folder = $root."user/pages/";
	$show_title = TRUE;
}

debug("Ending script",0);
debug_echo();
?>

<p class="title-head"><b>Add new file</b>: <? echo $type; ?> <span class="back-link" onclick="get_data('<? echo $refreshurl; ?>',{'id':'<? echo $refreshid; ?>'})">(back)</span></p>
<form>
<fieldset>
<legend>Inputs</legend>
<input id="path" type="hidden" value="<? echo $folder; ?>" />
<input id="type" type="hidden" value="<? echo $type; ?>" />
<?
if($show_title) {
?>
<input id="file_title" type="text" class="text-input" value="" /><p class="form-title">&mdash;File title</p><p class="help-ico"></p>
<div class="help-box">This will be the "name" of the page (what will show up when page_link.php is loaded) Examples: Contact, info, My Work</div><p class="clear"></p>
<?
}
?>
<input id="file_url" type="text" class="text-input" value="" /><p class="form-title">&mdash;File name</p><p class="help-ico"></p>
<div class="help-box">This is the filename of the page. Should not contain characters outside this scope a-z, 0-9 and _<br /><br />Examples: contact.php, scriptmania.js, stylesheet.css</div><p class="clear"></p>
<p class="clear"></p><p class="margin"></p>
<textarea rows="30" id="file_text"></textarea><p class="form-title">&mdash;Text</p><p class="help-ico"></p>
<div class="help-box">This is the contents of the file any code is alowed (php, javascript, css, etc.)</div>
</fieldset>
</form>
<p class="clear"></p>
<p class="margin"></p>
<p class="btn add" onclick="submit_form('add_file','<? echo $refreshurl; ?>',{'id':'<? echo $refreshid; ?>'})">Add file</p>
