<?
error_reporting(0);
require("_headr.php");
if($_SESSION["password"] != $pass || $_SESSION["sessid"] != session_id()) die(logout());

debug("Password validated, logged in",0);
$tags = mysql_query("SELECT t.* FROM core_tags t");
if(!$tags) {
	debug("Could not retrieve tag data from database table core_tags",0);
	debug(mysql_error(),1);
} else {
	debug("Ending script",0);
	debug_echo();
	?>
    <p class="title-head">Add new entry <span class="back-link" onclick="get_data('show_entries')">(back)</span></p>
    <form><fieldset>
<legend>Tags</legend>
    <?
	while($tag_data = mysql_fetch_array($tags)) {
		$tag_text = $tag_data["tag_text"];
		$tag_id = $tag_data["tag_id"];
		echo "<p class=\"form-obj\">".$tag_text."<br />";
		echo '<input id="tag'.$tag_id.'" type="checkbox" /></p>';
	}
}

?>
</fieldset>
<p class="clear"></p>
<p class="margin"></p>
<fieldset>
<legend>Inputs</legend>
<p class="clear"></p>
<input id="entry_title" type="text" class="text-input" /><p class="form-title">&mdash;Title</p><p class="help-ico"></p>
<div class="help-box">The title of the entry, this is accessed by the string CORE(ENTRY:TITLE)</div><p class="clear"></p>
<input id="entry_client" type="text" class="text-input" /><p class="form-title">&mdash;Client</p><p class="help-ico"></p>
<div class="help-box">A client field, can be whatever you want though, accessed by CORE(ENTRY:CLIENT)</div><p class="clear"></p>
<input id="entry_extra1" type="text" class="text-input" /><p class="form-title">&mdash;Extra1</p><p class="help-ico"></p>
<div class="help-box">Extra field 1, accessed by CORE(ENTRY:EXTRA1)</div><p class="clear"></p>
<input id="entry_extra2" type="text" class="text-input" /><p class="form-title">&mdash;Extra2</p><p class="help-ico"></p>
<div class="help-box">Extra field 1, accessed by CORE(ENTRY:EXTRA2)</div><p class="clear"></p>
<!-- NEW -->
<input id="entry_visit_link" type="text" class="text-input"/><p class="form-title">&mdash;Visit Link</p><p class="help-ico"></p>
<div class="help-box">Visit Link for Websites, accessed by CORE(ENTRY:VISIT)</div><p class="clear"></p>


<p class="margin"></p>
<p class="clear"></p>
<p class="form-title">Text</p><p class="help-ico"></p>
<div class="help-box">A textfield, intended for longer text, accessed by CORE(ENTRY:TEXT)</div>
<p class="clear"></p>
<textarea rows="10" id="entry_text"></textarea>
</fieldset>
</form>
<p class="margin"></p>
<p class="clear"></p>
<p class="btn add" onclick="submit_form('add_entry','show_entries')">Add</p>
