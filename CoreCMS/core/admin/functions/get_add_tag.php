<?
error_reporting(0);
require("_headr.php");
if($_SESSION["password"] != $pass || $_SESSION["sessid"] != session_id()) die(logout());

debug("Password validated, logged in",0);
debug("Ending script",0);
debug_echo();
?>

<p class="title-head">Add new tag <span class="back-link" onclick="get_data('show_tags')">(back)</span></p>
<form>
<fieldset>
<legend>Inputs</legend>
  <input id="tag_text" type="text" class="text-input" /><p class="form-title">&mdash;Title</p><p class="help-ico"></p>
<div class="help-box">The title of the tag, this is accessed by the string CORE(TAG:TITLE)</div>
</fieldset>
</form>
<p class="clear"></p>
<p class="margin"></p>
<p class="btn submit" onclick="submit_form('add_tag','show_tags')">Add tag</p>