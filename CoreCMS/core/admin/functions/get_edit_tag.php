<?
error_reporting(0);
require("_headr.php");
if($_SESSION["password"] != $pass || $_SESSION["sessid"] != session_id()) die(logout());

debug("Password validated, logged in",0);

$id = $_REQUEST["id"];
if(!is_numeric($id)) {
	debug("Id is not numeric",1);
}

debug("Fetching tag data",0);
$contents = mysql_query("SELECT * FROM `core_tags` WHERE `tag_id`=\"$id\"") or debug(mysql_error(),1);

if(mysql_num_rows($contents) > 0) {
	while($data = mysql_fetch_array($contents)) {
		$tag_text = $data["tag_text"];
		$tag_id = $data["tag_id"];
	}
} else {
	debug("Error, couldn't fetch a tag with that id.",1);
}

$assoc = mysql_query("SELECT * FROM `core_entry2tag` WHERE `tag_id`=\"$tag_id\"") or die(mysql_error());
$tag_assoc = mysql_num_rows($assoc);

debug("Ending script",0);
debug_echo();
?>
<p class="title-head"><b>Editing</b> <? echo $tag_text; ?> <span class="back-link" onclick="get_data('show_tags')">(back)</span></p>
<form>
<fieldset>
<legend>Inputs</legend>


  <input id="tag_id" type="hidden" value="<? echo $tag_id; ?>" />
  <input id="tag_text" type="text" class="text-input" value="<? echo htmlspecialchars($tag_text); ?>" /><p class="form-title">&mdash;Title</p><p class="help-ico"></p>
<div class="help-box">The title of the tag, this is accessed by the string CORE(TAG:TITLE)</div><p class="clear"></p>
</fieldset></form>
<p class="margin">
<p class="clear">

  <p class="btn add" onclick="submit_form('edit_tag','get_edit_tag',{'id':<? echo $tag_id; ?>})">Submit changes</p>
  <p class="btn delete" onclick="delete_data('delete_tag',{'id':<? echo $tag_id; ?>},'show_tags')">Delete tag</p>
