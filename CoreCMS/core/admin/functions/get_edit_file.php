<?
error_reporting(0);
require("_headr.php");
if($_SESSION["password"] != $pass || $_SESSION["sessid"] != session_id()) die(logout());

debug("Password validated, logged in",0);
	
$path = $_REQUEST["path"];
$type = $_REQUEST["type"];
$refreshurl = $_REQUEST["refreshurl"];
$refreshid = $_REQUEST["refreshid"];
$file_name = substr($path, 1+strrpos($path,"/"));
$file_text = "";

$title = "";
$protected = false;
$page = false;
$layout = false;

$s = "";

if(strlen($path) < 1) {
	debug("Path variable returned nothing",1);
}

switch($type) {
	case "page":
		$page = true;
		debug("File recognized as page",0);
		$sql = mysql_query("SELECT p.* FROM core_pages p WHERE p.page_url = \"$file_name\" LIMIT 1");
		if(@mysql_num_rows($sql) > 0) {
			while($p = @mysql_fetch_array($sql)) {
				$title = $p["page_title"];
				$file_title = $title;
			}
		}
	break;
	case "part":
		debug("File recognized as theme part",0);
		$protected = true;
		switch($file_name) {
			case "entry.php":
				$title = "Default entry layout";
			break;
			case "entry_link.php":
				$title = "Entry link (in list)";
			break;
			case "entry_link_thumbs.php":
				$title = "Entry link (as thumbnail)";
			break;
			case "page_link.php":
				$title = "Page link";
			break;
			case "tag_link.php":
				$title = "Tag link";
			break;
			case "video.php":
				$title = "Media layout: Video";
			break;
			case "image.php":
				$title = "Media layout: Image";
			break;
			case "sound.php":
				$title = "Media layout: Sound";
			break;
			case "link.php":
				$title = "Media layout: Text";
			break;
			case "scripts.php":
				$title = "Theme script";
			break;
			case "index.php":
				$title = "Theme index";
			break;
			case "configuration.php":
				$title = "Theme configuration file";
			break;
		}
	break;
	case "style":
		debug("File recognized as style",0);
		$title = $file_name;
	break;
	case "head":
		debug("File recognized as index head",0);
		$title = "Index head";
		$protected = true;
	break;
	case "script":
		debug("File recognized as script",0);
		$title = $file_name;
	break;
	case "layout":
		debug("File recognized as layout",0);
		$layout = true;
		$title = "Custom layout";
		$file_name = "layout.php";
		if(!strstr($path,"/layout.php")) $path = $path."/layout.php";
		if(!is_file($path)) {
			$s = "<div class=\"entry\" name=\"entry-<? echo \$entry_id; ?>\"></div>";
		}
	break;
}

if(strstr($file_name,"entry.php") || strstr($file_name,"layout.php")) {
	$help = "
	<div class=\"cc-obj\"><p class=\"cc-title\">CORE(ENTRY:TITLE)</p><p class=\"help-ico\"></p><div class=\"help-box\">Gets the title of the entry.</div></div>
	<div class=\"cc-obj\"><p class=\"cc-title\">CORE(ENTRY:DATE)</p><p class=\"help-ico\"></p><div class=\"help-box\">Gets the date of the entry.</div></div>
	<div class=\"cc-obj\"><p class=\"cc-title\">CORE(ENTRY:ID)</p><p class=\"help-ico\"></p><div class=\"help-box\">Gets the id of the entry.</div></div>
	<div class=\"cc-obj\"><p class=\"cc-title\">CORE(ENTRY:POSITION)</p><p class=\"help-ico\"></p><div class=\"help-box\">Gets the position of the entry.</div></div>
	<div class=\"cc-obj\"><p class=\"cc-title\">CORE(ENTRY:HITS)</p><p class=\"help-ico\"></p><div class=\"help-box\">Gets the number of hits (views) of the entry.</div></div>
	<div class=\"cc-obj\"><p class=\"cc-title\">CORE(ENTRY:TEXT)</p><p class=\"help-ico\"></p><div class=\"help-box\">Gets the data entered in the \"text\" field of the entry.</div></div>
	<div class=\"cc-obj\"><p class=\"cc-title\">CORE(ENTRY:CLIENT)</p><p class=\"help-ico\"></p><div class=\"help-box\">Gets the data entered in the \"client\" field of the entry.</div></div>
	<div class=\"cc-obj\"><p class=\"cc-title\">CORE(ENTRY:EXTRA1)</p><p class=\"help-ico\"></p><div class=\"help-box\">Gets the data entered in the \"extra1\" field of the entry.</div></div>
	<div class=\"cc-obj\"><p class=\"cc-title\">CORE(ENTRY:EXTRA2)</p><p class=\"help-ico\"></p><div class=\"help-box\">Gets the data entered in the \"extra2\" field of the entry.</div></div>
	<div class=\"cc-obj\"><p class=\"cc-title\">CORE(ENTRY:VISIT)</p><p class=\"help-ico\"></p><div class=\"help-box\">Gets the data entered in the \"visit_link\" field of the entry.</div></div>
	<div class=\"cc-obj\"><p class=\"cc-title\">CORE(ENTRY:PERMALINK)</p><p class=\"help-ico\"></p><div class=\"help-box\">Gets a permalink to the entry.</div></div>
	<div class=\"cc-obj\"><p class=\"cc-title\">CORE(ENTRY:MEDIA)</p><p class=\"help-ico\"></p><div class=\"help-box\">Load all media from the entry folder</div></div>
	<div class=\"cc-obj\"><p class=\"cc-title\">CORE(ENTRY:TAGS)</p><p class=\"help-ico\"></p><div class=\"help-box\">Gets a list of the tags this entry is tagged with</div></div>
	<div class=\"cc-obj\"><p class=\"cc-title\">CORE(FILE:file_name.jpg:LOAD)</p><p class=\"help-ico\"></p><div class=\"help-box\">Load a file from the entry folder with the specified filename</div></div>
	<div class=\"cc-obj\"><p class=\"cc-title\">CORE(NEW:START)(NEW:END)</p><p class=\"help-ico\"></p><div class=\"help-box\">Everything between CORE(NEW:START) and (NEW:END) will be shown if the entry is marked as new.</div></div>
	";
}

if(strstr($file_name,"entry_link.php")) {
	$help = "
	<div class=\"cc-obj\"><p class=\"cc-title\">CORE(ENTRY:TITLE)</p><p class=\"help-ico\"></p><div class=\"help-box\">Gets the title of the entry.</div></div>
	<div class=\"cc-obj\"><p class=\"cc-title\">CORE(ENTRY:DATE)</p><p class=\"help-ico\"></p><div class=\"help-box\">Gets the date of the entry.</div></div>
	<div class=\"cc-obj\"><p class=\"cc-title\">CORE(ENTRY:ID)</p><p class=\"help-ico\"></p><div class=\"help-box\">Gets the id of the entry.</div></div>
	<div class=\"cc-obj\"><p class=\"cc-title\">CORE(ENTRY:POSITION)</p><p class=\"help-ico\"></p><div class=\"help-box\">Gets the position of the entry.</div></div>
	<div class=\"cc-obj\"><p class=\"cc-title\">CORE(ENTRY:HITS)</p><p class=\"help-ico\"></p><div class=\"help-box\">Gets the number of hits (views) of the entry.</div></div>
	<div class=\"cc-obj\"><p class=\"cc-title\">CORE(ENTRY:TEXT)</p><p class=\"help-ico\"></p><div class=\"help-box\">Gets the data entered in the \"text\" field of the entry.</div></div>
	<div class=\"cc-obj\"><p class=\"cc-title\">CORE(ENTRY:CLIENT)</p><p class=\"help-ico\"></p><div class=\"help-box\">Gets the data entered in the \"client\" field of the entry.</div></div>
	<div class=\"cc-obj\"><p class=\"cc-title\">CORE(ENTRY:EXTRA1)</p><p class=\"help-ico\"></p><div class=\"help-box\">Gets the data entered in the \"extra1\" field of the entry.</div></div>
	<div class=\"cc-obj\"><p class=\"cc-title\">CORE(ENTRY:EXTRA2)</p><p class=\"help-ico\"></p><div class=\"help-box\">Gets the data entered in the \"extra2\" field of the entry.</div></div>
	<div class=\"cc-obj\"><p class=\"cc-title\">CORE(ENTRY:VISIT)</p><p class=\"help-ico\"></p><div class=\"help-box\">Gets the data entered in the \"visit_link\" field of the entry.</div></div>
	<div class=\"cc-obj\"><p class=\"cc-title\">CORE(ENTRY:PERMALINK)</p><p class=\"help-ico\"></p><div class=\"help-box\">Gets a permalink to the entry.</div></div>
	<div class=\"cc-obj\"><p class=\"cc-title\">CORE(ENTRY:TAGS)</p><p class=\"help-ico\"></p><div class=\"help-box\">Gets a list of the tags this entry is tagged with</div></div>
	<div class=\"cc-obj\"><p class=\"cc-title\">CORE(NEW:START)(NEW:END)</p><p class=\"help-ico\"></p><div class=\"help-box\">Everything between CORE(NEW:START) and (NEW:END) will be shown if the entry is marked as new.</div></div>
	";
}

if(strstr($file_name,"entry_link_thumbs.php")) {
	$help = "
	<div class=\"cc-obj\"><p class=\"cc-title\">CORE(ENTRY:TITLE)</p><p class=\"help-ico\"></p><div class=\"help-box\">Gets the title of the entry.</div></div>
	<div class=\"cc-obj\"><p class=\"cc-title\">CORE(ENTRY:DATE)</p><p class=\"help-ico\"></p><div class=\"help-box\">Gets the date of the entry.</div></div>
	<div class=\"cc-obj\"><p class=\"cc-title\">CORE(ENTRY:ID)</p><p class=\"help-ico\"></p><div class=\"help-box\">Gets the id of the entry.</div></div>
	<div class=\"cc-obj\"><p class=\"cc-title\">CORE(ENTRY:POSITION)</p><p class=\"help-ico\"></p><div class=\"help-box\">Gets the position of the entry.</div></div>
	<div class=\"cc-obj\"><p class=\"cc-title\">CORE(ENTRY:HITS)</p><p class=\"help-ico\"></p><div class=\"help-box\">Gets the number of hits (views) of the entry.</div></div>
	<div class=\"cc-obj\"><p class=\"cc-title\">CORE(ENTRY:TEXT)</p><p class=\"help-ico\"></p><div class=\"help-box\">Gets the data entered in the \"text\" field of the entry.</div></div>
	<div class=\"cc-obj\"><p class=\"cc-title\">CORE(ENTRY:CLIENT)</p><p class=\"help-ico\"></p><div class=\"help-box\">Gets the data entered in the \"client\" field of the entry.</div></div>
	<div class=\"cc-obj\"><p class=\"cc-title\">CORE(ENTRY:EXTRA1)</p><p class=\"help-ico\"></p><div class=\"help-box\">Gets the data entered in the \"extra1\" field of the entry.</div></div>
	<div class=\"cc-obj\"><p class=\"cc-title\">CORE(ENTRY:EXTRA2)</p><p class=\"help-ico\"></p><div class=\"help-box\">Gets the data entered in the \"extra2\" field of the entry.</div></div>
	<div class=\"cc-obj\"><p class=\"cc-title\">CORE(ENTRY:VISIT)</p><p class=\"help-ico\"></p><div class=\"help-box\">Gets the data entered in the \"visit_link\" field of the entry.</div></div>
	<div class=\"cc-obj\"><p class=\"cc-title\">CORE(ENTRY:PERMALINK)</p><p class=\"help-ico\"></p><div class=\"help-box\">Gets a permalink to the entry.</div></div>
	<div class=\"cc-obj\"><p class=\"cc-title\">CORE(ENTRY:TAGS)</p><p class=\"help-ico\"></p><div class=\"help-box\">Gets a list of the tags this entry is tagged with</div></div>
	<div class=\"cc-obj\"><p class=\"cc-title\">CORE(ENTRY:THUMB)</p><p class=\"help-ico\"></p><div class=\"help-box\">Gets the path to a thumbnail generated by phpThumb</div></div>
	<div class=\"cc-obj\"><p class=\"cc-title\">CORE(NEW:START)(NEW:END)</p><p class=\"help-ico\"></p><div class=\"help-box\">Everything between CORE(NEW:START) and (NEW:END) will be shown if the entry is marked as new.</div></div>
	";
}

if(strstr($file_name,"tag_link.php")) {
	$help = "
	<div class=\"cc-obj\"><p class=\"cc-title\">CORE(TAG:TITLE)</p><p class=\"help-ico\"></p><div class=\"help-box\">Gets the title of the tag.</div></div>
	<div class=\"cc-obj\"><p class=\"cc-title\">CORE(TAG:TITLE:URL)</p><p class=\"help-ico\"></p><div class=\"help-box\">Gets a url friendly title of the tag (black and white would become black+and+white), this is to ensure the site works better in browsers that treat urls \"harsher\" than Firefox.</div></div>
	<div class=\"cc-obj\"><p class=\"cc-title\">CORE(TAG:ID)</p><p class=\"help-ico\"></p><div class=\"help-box\">Gets the id of the tag.</div></div>
	";
}

if(strstr($file_name,"page_link.php")) {
	$help = "
	<div class=\"cc-obj\"><p class=\"cc-title\">CORE(PAGE:TITLE)</p><p class=\"help-ico\"></p><div class=\"help-box\">Gets the title of the page.</div></div>
	<div class=\"cc-obj\"><p class=\"cc-title\">CORE(PAGE:TITLE:URL)</p><p class=\"help-ico\"></p><div class=\"help-box\">Gets a url friendly title of the page (About me would become About+me), this is to ensure the site works better in browsers that treat urls \"harsher\" than Firefox.</div></div>
	";
}

if(strstr($file_name,"index.php")) {
	$help = "
	<div class=\"cc-obj\"><p class=\"cc-title\">CORE(LOAD:PAGES)</p><p class=\"help-ico\"></p><div class=\"help-box\">This outputs every page in the format specified in \"Page link\" (page_link.php)</div></div>
	<div class=\"cc-obj\"><p class=\"cc-title\">CORE(LOAD:TAGS)</p><p class=\"help-ico\"></p><div class=\"help-box\">This outputs every tag in the format specified in \"Tag link\" (tag_link.php)</div></div>
	<div class=\"cc-obj\"><p class=\"cc-title\">CORE(LOAD:ENTRIES(LIST))</p><p class=\"help-ico\"></p><div class=\"help-box\">This outputs every entry in the format specified in \"Entry link (as list)\" (entry_link.php)</div></div>
	<div class=\"cc-obj\"><p class=\"cc-title\">CORE(LOAD:ENTRIES(THUMBS))</p><p class=\"help-ico\"></p><div class=\"help-box\">This outputs every page in the format specified in \"Entry link (as thumbnails)\" (entry_link_thumbs.php)</div></div>
	";
}


if(strstr($file_name,"configuration.php")) {
	$help = "
	This is not something for the novice user.<br /><br />
	
	The formatting of configuration.php is a somewhat special.<br />
	If you want to make a custom variable to show up as an input in the theme configuration you have to enter it inbetween the lines //CONFIG and //ENDCONFIG, anything below //ENDCONFIG will not be included as an input but it will be preserved, so you are free to add whatever code you want there (php).<br /><br />
	
	The formatting for custom variables is this:<br />
	\$variable = \"value\"; //Variable Name [help]: The help of the variable.<br />
	
	It's important that you don't miss any spaces, and that the value is inside quotes, even if it's a number.<br />
	You can then access this variable in all theme documents (entry.php, tag_link.php, index.php, etc).
	
	";
}

if($file_name == "image.php") {
	$help = "
	<div class=\"cc-obj\"><p class=\"cc-title\">CORE(MEDIA:PATH)</p><p class=\"help-ico\"></p><div class=\"help-box\">Gets the folder name and file as a variable, for example \"entry-folder/entry_file.jpg\" (without quotes)</div></div>
	<div class=\"cc-obj\"><p class=\"cc-title\">CORE(MEDIA:FULL-PATH)</p><p class=\"help-ico\"></p><div class=\"help-box\">Gets the full relative path (relative), for example \"core/user/uploads/entry-folder/entry_file.jpg\" (without quotes)</div></div>
	<div class=\"cc-obj\"><p class=\"cc-title\">CORE(MEDIA:ABSOLUTE-PATH)</p><p class=\"help-ico\"></p><div class=\"help-box\">Gets the absolute path, for example \"http://yourserver.com/portfolio/core/user/uploads/entry-folder/entry_file.jpg\" (without quotes)</div></div>
	<div class=\"cc-obj\"><p class=\"cc-title\">CORE(MEDIA:IMAGE-WIDTH)</p><p class=\"help-ico\"></p><div class=\"help-box\">Gets the image width in pixels.</div></div>
	<div class=\"cc-obj\"><p class=\"cc-title\">CORE(MEDIA:IMAGE-HEIGHT)</p><p class=\"help-ico\"></p><div class=\"help-box\">Gets the image height in pixels.</div></div>";
}
   
if($file_name == "video.php" || $file_name == "sound.php") {
	$help = "
	<div class=\"cc-obj\"><p class=\"cc-title\">CORE(MEDIA:PATH)</p><p class=\"help-ico\"></p><div class=\"help-box\">Gets the folder name and file as a variable, for example \"entry-folder/entry_file.jpg\" (without quotes)</div></div>
	<div class=\"cc-obj\"><p class=\"cc-title\">CORE(MEDIA:FULL-PATH)</p><p class=\"help-ico\"></p><div class=\"help-box\">Gets the full relative path (relative), for example \"core/user/uploads/entry-folder/entry_file.jpg\" (without quotes)</div></div>
	<div class=\"cc-obj\"><p class=\"cc-title\">CORE(MEDIA:ABSOLUTE-PATH)</p><p class=\"help-ico\"></p><div class=\"help-box\">Gets the absolute path, for example \"http://yourserver.com/portfolio/core/user/uploads/entry-folder/entry_file.jpg\" (without quotes)</div></div>";
}

if($file_name == "link.php") {
	$help = "
	<div class=\"cc-obj\"><p class=\"cc-title\">CORE(MEDIA:READ)</p><p class=\"help-ico\"></p><div class=\"help-box\">Get the content of the file.</div></div>";
}

if(!is_file($path)||(filesize($path) < 1)){
	debug("File not found or file size = 0, recreating.",0);
	$file = fopen($path,"w");
	if(!$file) {	
		debug("Can not create file. $path",1);
	}
	if(strlen($s) > 0) {
		fwrite($file,$s);
	}
	fclose($file);
}
	
debug("Opening file in read mode.",0);
$file = fopen($path,"r");
if(!$file) {
	debug("Can not open file. $path",1);
}
if(filesize($path) > 0) $file_text = fread($file, filesize($path));


if($protected) {	
	debug("Input file name is disabled, file protected by Core",0);
	$disabled = "disabled=\"disabled\"";
}

debug("Input file name is disabled, file protected by Core",0);
debug("Ending script",0);
debug_echo();
?>

<p class="title-head"><b>Editing: </b> <? echo $title; ?> <span class="back-link" onclick="get_data('<? echo $refreshurl; ?>',{'id':'<? echo $refreshid; ?>'})">(back)</span></p>

<?
if($help) {
echo "
<fieldset>
<legend>Commands/info (clickable)</legend>";
echo $help;
echo "</fieldset>";
}
?>

<p class="clear"></p>
<p class="margin"></p>

<form>

<fieldset>
<legend>Inputs</legend>

<input id="path" type="hidden" value="<? echo $path; ?>" />
<input id="url" type="hidden" value="<? echo $file_name; ?>" />
<input id="type" type="hidden" value="<? echo $type; ?>" />
<?
if($page) {
	echo "<input id=\"file_title\" type=\"text\" width=\"400px\" value=\"$file_title\" /> &mdash;File title<p class=\"clear\"></p><p class=\"margin\"></p>";
}
?>

<p class="form-title">Contents</p>
<p class="clear"></p>
<textarea rows="30" id="file_text"><? echo readable_vars($file_text); ?></textarea>

</fieldset>
</form>

<p class="clear"></p>
<p class="margin"></p>
<p class="btn add" onclick="submit_form('edit_file','get_edit_file',{'path':'<? echo $path; ?>','type':'<? echo $type; ?>','refreshurl':'<? echo $refreshurl; ?>','refreshid':'<? echo $refreshid; ?>'})">Edit contents</p>

<?
if(!$protected){
	echo "<p class=\"btn delete\" onclick=\"delete_data('delete_file',{'path':'$path','name':'$file_name','type':'$type'},'$refreshurl',{'id':'$refreshid'})\">Delete file</p>";
}
?>

<script type="text/javascript">
$(".cc-title").click(function(){
	$("#file_text").insertAtCaret($(this).html());
});
</script>