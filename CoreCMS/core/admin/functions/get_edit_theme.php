<?
error_reporting(0);
require("_headr.php");
if($_SESSION["password"] != $pass || $_SESSION["sessid"] != session_id()) die(logout());

debug("Password validated, logged in",0);

$selected_theme = $_REQUEST["id"];

if(strlen($selected_theme) < 1) {
	debug("Variable theme returned nothing",1);
}
$theme_path = $root . "themes/" . $selected_theme . "/";


debug("Ending script",0);
debug_echo();
?>
<p class="title-head"><b>Editing</b> <? echo $selected_theme; ?> <span class="back-link" onclick="get_data('show_themes')">(back)</span></p>
<form>
<fieldset>
<legend>Parameters</legend>
<input type="hidden" id="current_theme" value="<? echo $selected_theme; ?>" />
<?
$the_path = $theme_path . "/configuration.php";
$conf = true;
$i = 0;
if ($f = fopen($the_path, "r")) do {
    $s = fgets($f);
	if(strstr($s,"//ENDCONFIG")) {
		$conf = false;
	} else {
		if(!strstr($s, "//CONFIG") && !strstr($s, "?php")) {
			$var_name = substr($s,1,strpos($s," ")-1);
			$s = substr($s,strlen($var_name));
			$var_value = substr($s,4,strpos($s,";")-4);
			$var_value_mix = $var_value;
			if(substr($var_value,0,1) == "\"") {
				$var_value_mix = substr($var_value,1,strlen($var_value)-1);
			}
			$s = substr($s,strlen($var_value)+6);
			$var_title = substr($s,2,strpos($s,"[help]:")-2);
			$s = substr($s,strlen($var_title)+7);
			$var_help = substr($s,2);
			echo "<input type=\"text\" id=\"$var_name\" class=\"text-input\" value=\"$var_value_mix\" /><p class=\"form-title\">&mdash;$var_title</p><p class=\"help-ico\"></p><div class=\"help-box\">$var_help</div><p class=\"clear\"></p>";
		}
	}
} while (!feof($f) && $conf);
fclose($f);
?>
</fieldset>
<p class="margin"></p>
<p class="clear"></p>
<p class="btn add" onclick="submit_form('edit_theme_config','get_edit_theme',{'id':'<? echo $selected_theme; ?>'})">Submit theme configuration</p>

<p class="clear"></p>
<p class="margin"></p>
<fieldset>
<legend>Theme documents/code</legend>

<p class="title">Layout</p>
<?
$type = "part";
$path = $theme_path . "/index.php";
$file = "Theme index";
$help = '<p class="help-ico"></p><div class="help-box">This is the main index of the theme.</div><p class="clear"></p>';
echo "<p class=\"list-link float\" onclick=\"get_data('get_edit_file',{'path':'$path','type':'$type','refreshurl':'get_edit_theme','refreshid':'$selected_theme'})\">$file</p>$help";
$path = $theme_path . "/parts/entry.php";
$type = "part";
$file = "Entry layout";
$help = '<p class="help-ico"></p><div class="help-box">The code in this document is the default layout for each entry. This layout can be overriden by creating a custom layout for an individual entry.</div><p class="clear"></p>';
echo "<p class=\"float list-link\" onclick=\"get_data('get_edit_file',{'path':'$path','type':'$type','refreshurl':'get_edit_theme','refreshid':'$selected_theme'})\">$file</p>$help";
?>

<p class="clear"></p>
<p class="margin"></p>
<p class="title">Menues</p>
<?
$path = $theme_path . "/parts/entry_link.php";
$file = "Entry link (in list)";
$help = '<p class="help-ico"></p><div class="help-box">This code is loaded for each entry when loading them as a list menu. (Core command: "CORE(LOAD:ENTRIES(LIST))")</div><p class="clear"></p>';
echo "<p class=\"float list-link\" onclick=\"get_data('get_edit_file',{'path':'$path','type':'$type','refreshurl':'get_edit_theme','refreshid':'$selected_theme'})\">$file</p>$help";
$path = $theme_path . "/parts/entry_link_thumbs.php";
$file = "Entry link (as thumbnail)";
$help = '<p class="help-ico"></p><div class="help-box">This code is loaded for each entry when loading them as a list of thumbnails (Core command: "CORE(LOAD:ENTRIES(THUMBS))")</div><p class="clear"></p>';
echo "<p class=\"float list-link\" onclick=\"get_data('get_edit_file',{'path':'$path','type':'$type','refreshurl':'get_edit_theme','refreshid':'$selected_theme'})\">$file</p>$help";
/* -- Edit by Andreas Klein  */
$path = $theme_path . "/parts/entry_image.php";
$file = "Entry link (as Image for Image-Slider)";
$help = '<p class="help-ico"></p><div class="help-box">This code is loaded for each entry when loading them as a list of images (Core command: "CORE(LOAD:ENTRIES(IMAGE))")</div><p class="clear"></p>';
echo "<p class=\"float list-link\" onclick=\"get_data('get_edit_file',{'path':'$path','type':'$type','refreshurl':'get_edit_theme','refreshid':'$selected_theme'})\">$file</p>$help";
/* End edit */
$path = $theme_path . "/parts/page_link.php";
$file = "Page link";
$help = '<p class="help-ico"></p><div class="help-box">This code is loaded for each page when using the Core command: "CORE(LOAD:PAGES)" to load all your pages as a menu.</div><p class="clear"></p>';
echo "<p class=\"float list-link\" onclick=\"get_data('get_edit_file',{'path':'$path','type':'$type','refreshurl':'get_edit_theme','refreshid':'$selected_theme'})\">$file</p>$help";
$path = $theme_path . "/parts/tag_link.php";
$file = "Tag link";
$help = '<p class="help-ico"></p><div class="help-box">This code is loaded for each tag when using the Core command: "CORE(LOAD:TAGS)" to load all tags as a menu.</div><p class="clear"></p>';
echo "<p class=\"float list-link\" onclick=\"get_data('get_edit_file',{'path':'$path','type':'$type','refreshurl':'get_edit_theme','refreshid':'$selected_theme'})\">$file</p>$help";
?>

<p class="clear"></p>
<p class="margin"></p>
<p class="title">Misc</p>
<?
$path = $theme_path . "/parts/scripts.php";
$file = "Scripts";
$help = '<p class="help-ico"></p><div class="help-box">This code is usually called at the bottom of a theme index file.</div><p class="clear"></p>';
echo "<p class=\"float list-link\" onclick=\"get_data('get_edit_file',{'path':'$path','type':'$type','refreshurl':'get_edit_theme','refreshid':'$selected_theme'})\">$file</p>$help"; 

$path = $theme_path . "/configuration.php";
$type = "part";
$file = "Theme configuration file";
$help = '<p class="help-ico"></p><div class="help-box">This is theme configuration file, you can add your own variables here, and if you follow the layout of the other variables they will show up as inputs when editing the theme through the admin panel. This is very good if you want to design a public theme.</div><p class="clear"></p>';
echo "<p class=\"list-link float\" onclick=\"get_data('get_edit_file',{'path':'$path','type':'$type','refreshurl':'get_edit_theme','refreshid':'$selected_theme'})\">$file</p>$help"; 
?>

<p class="clear"></p>
<p class="margin"></p>
<p class="title">JavaScripts</p>
<p class="clear"></p>
<p class="btn add" onclick="get_data('get_add_file',{'type':'script','refreshurl':'get_edit_theme','refreshid':'<? echo $selected_theme; ?>'})">Add new script</p>
<p class="clear"></p><br />
<?
	$type = "script";
$file_array = search_folder($theme_path . "scripts");
foreach($file_array as $file) {
	$path = $theme_path . "scripts/" . $file;
	echo "<span class=\"list-link\" onclick=\"get_data('get_edit_file',{'path':'$path','type':'$type','refreshurl':'get_edit_theme','refreshid':'$selected_theme'})\">$file</span><br />"; 
}
?>
<p class="margin"></p>
<p class="title">Stylesheets</p>
<p class="clear"></p>
<p class="btn add" onclick="get_data('get_add_file',{'type':'style','refreshurl':'get_edit_theme','refreshid':'<? echo $selected_theme; ?>'})">Add new style</p>
<p class="clear"></p><br />
<?
	$type = "style";
$file_array = search_folder($theme_path . "styles");
foreach($file_array as $file) {
	$path = $theme_path . "styles/" . $file;
	echo "<span class=\"list-link\" onclick=\"get_data('get_edit_file',{'path':'$path','type':'$type','refreshurl':'get_edit_theme','refreshid':'$selected_theme'})\">$file</span><br />"; 
}
?>
<p class="margin"></p>
<p class="title">Code to display media</p>
<?
$file_array = search_folder($theme_path . "parts/media");
	$type = "part";
foreach($file_array as $file) {
	
	$path = $theme_path . "parts/media/" . $file;
	switch($file) {
			case "image.php":
				$file = "Images";
				$help = '<p class="help-ico"></p><div class="help-box">This code is used once for every image loaded by a Core command (as "CORE(ENTRY:MEDIA)").</div><p class="clear"></p>';
			break;
			case "video.php":
				$file = "Video";
				$help = '<p class="help-ico"></p><div class="help-box">This code is used once for every video loaded by a Core command (as "CORE(ENTRY:MEDIA)").</div><p class="clear"></p>';
			break;
			case "sound.php":
				$file = "Sound";
				$help = '<p class="help-ico"></p><div class="help-box">This code is used once for every soundfile loaded by a Core command (as "CORE(ENTRY:MEDIA)").</div><p class="clear"></p>';
			break;
			case "link.php":
				$file = "Text";
				$help = '<p class="help-ico"></p><div class="help-box">This code is used once for every html and txt loaded by a Core command (as "CORE(ENTRY:MEDIA)").</div><p class="clear"></p>';
			break;
		}
	echo "<p class=\"float list-link\" onclick=\"get_data('get_edit_file',{'path':'$path','type':'$type','refreshurl':'get_edit_theme','refreshid':'$selected_theme'})\">$file</p>$help"; 
}
?>

</fieldset></form>

<p class="margin"></p>
<p class="clear"></p>
<p class="btn add" onclick="get_data('delete_theme',{'id':'<? echo $theme_path; ?>'})">Delete theme</p>
<p class="clear"></p><br />