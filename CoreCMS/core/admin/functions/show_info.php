<?
error_reporting(E_ALL ^ E_NOTICE);
require("_headr.php");
if($_SESSION["password"] != $pass || $_SESSION["sessid"] != session_id()) die(logout());

debug("Password validated, logged in",0);
?>


<p class="title-head">Info/FAQs</p>

<form>
<fieldset>
<legend>Reporting bugs</legend>

If you think you've found a bug, then please go to Configuration and check "debugging". Then do whatever you did when the bug occured, a debuglog will now show up giving you more details about what went wrong. Then send the debug log to me (info@core.weareastronauts.org) along with any other information you think will help.<br /><br />

</fieldset>

<p class="margin"></p>

<fieldset>
<legend>Core commands</legend>

I've added a few commands that I refer to as "Core commands". These are added to hide any php interaction from the user, and they only work when using the admin panel. When editing a document, a list of available Core commands will show up, hover over the question mark to see what that command does, click on it to add it to the textfield.

</fieldset>

<p class="margin"></p>

<fieldset>
<legend>Custom thumbnail</legend>
By default Core selects the first image it finds inside a folder and uses phpThumb to generate a thumbnail of the size you've specified in the theme configuration. However, if you which to use a custom thumbnail you can do this by uploading an image which should have "_thumb" somewhere in the file name (example: pict012_thumb.jpg).<br /><br />

When there is an image that has "_thumb" in the file name uploaded to an entry, Core will select this one to use as thumbnail instead. But it's up to you to make sure that thumbnail is the right size.
</fieldset>

<p class="margin"></p>

<fieldset>
<legend>Explorer bug fix</legend>
v1.2 and earlier had a pretty fatal bug when browsing with internet explorer, none of the tags or entry links worked and this was fixed with 1.21.<br /><br />

If you've customized your own theme based on a theme from v1.2 or earlier you want to make sure you add this fix to your theme aswell.<br /><br />

In the theme script (edit by using admin panel, or open core/themes/yourtheme/parts/script.php), add these lines at the bottom of the script body:
<br /><br />
<code>
var http = "&lt;? echo $http; ?&gt;";<br />
$("a").click(function(){<br />
	var hash = this.href;<br />
	if(hash.indexOf("#") > 0); hash = hash.replace(http,'');<br />
	if(hash.charAt(0)=="#") {<br />
		hash = hash.replace(/^.*#/, '');<br />
		$.historyLoad(hash);<br />
		return false;<br />
	}<br />
});<br />
</code><br /><br />
If you are unsure on how it should look, try looking at one of the built in themes from the v1.21 update.
</fieldset>

</form>