<?php
error_reporting(0);

$current_root = getenv('DOCUMENT_ROOT') . $_SERVER['REQUEST_URI'];
require($current_root . "admin_session.php");

$root = set_root();
$login_message = "";
require($root . "user/configuration.php");

//VALIDATE INPUT
if(isset($_POST["pass"])) {
	$input_pass = $_POST["pass"];
	$salt = substr(md5($user),0,15);
	$pass_salted = $input_pass . $salt;
	$input_pass = sha1($pass_salted);
	if($input_pass == $pass) {
		login(session_id(), $input_pass);
		$input_pass = NULL;
	}
}

?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<title>CORE Admin</title>

<link rel="stylesheet" type="text/css" href="style.css" />

<?php 

if($_SESSION["password"] == $pass && $_SESSION["sessid"] == session_id()) {
	
?>

<link rel="stylesheet" type="text/css" href="functions/js/swfupload/default.css" />


<script type="text/javascript" src="functions/js/jquery-1.3.2.min.js"></script>
<script type="text/javascript" src="functions/js/jquery-ui-1.7.custom.min.js"></script>
<script type="text/javascript" src="functions/js/jquery.highlight-3.js"></script>

<script type="text/javascript" src="functions/js/swfupload/swfupload.js"></script>
<script type="text/javascript" src="functions/js/swfupload/plugins/swfupload.queue.js"></script>
<script type="text/javascript" src="functions/js/swfupload/plugins/fileprogress.js"></script>
<script type="text/javascript" src="functions/js/swfupload/plugins/handlers.js"></script>
<script type="text/javascript">

var swfu;
var ACTIVE_PATH
var PHP_SESSID = "<?php echo session_id(); ?>";

</script>
<?php get_version(); ?>

<script type="text/javascript" src="functions/js/ajax_helpers.js"></script>
<script type="text/javascript">
var running = 1.21;
	
function update_notify() {
	$("#update").css("display","block");
}

$.fn.insertAtCaret = function (myValue) {
        return this.each(function(){
                //IE support
                if (document.selection) {
                        this.focus();
                        sel = document.selection.createRange();
                        sel.text = myValue;
                        this.focus();
                }
                //MOZILLA/NETSCAPE support
                else if (this.selectionStart || this.selectionStart == '0') {
                        var startPos = this.selectionStart;
                        var endPos = this.selectionEnd;
                        var scrollTop = this.scrollTop;
                        this.value = this.value.substring(0, startPos)
                                      + myValue
                              + this.value.substring(endPos,
this.value.length);
                        this.focus();
                        this.selectionStart = startPos + myValue.length;
                        this.selectionEnd = startPos + myValue.length;
                        this.scrollTop = scrollTop;
                } else {
                        this.value += myValue;
                        this.focus();
                }
        });

};


$(document).ready(function(){
	if(core_version > running) {
		update_notify();
	}
	get_data('show_statistics');
 });
	
</script>
</head>

<body>
<div id="update">There is an update available. Visit <a href="http://weareastronauts.org/core-cms/" target="_blank">weareastronauts.org/core-cms/</a> to download it!</div>

<div id="header">
<p id="logo"><img src="gfx/core_logo_small_black.png" /></p>
        <div class="menu-obj home" onclick="get_data('show_statistics')">OVERVIEW</div>
        <div class="menu-obj config" onclick="get_data('show_config')">CONFIGURATION</div>
        <div class="menu-obj content" onclick="get_data('show_entries')">CONTENT</div>
        <div class="menu-obj tags" onclick="get_data('show_tags')">TAGS</div>
        <div class="menu-obj style" onclick="get_data('show_themes')">STYLE/LAYOUT</div>
        <div class="menu-obj info" onclick="get_data('show_info')">INFO/FAQ</div>
        <div class="menu-obj logout"><a href="functions/logout.php">&mdash;Logout</a></div>
</div>

<div id="debug"></div>
<div id="main"></div>

<? }
else {
	
if(isset($_POST["pass"])){
	$login_message = "Invalid username or wrong pass. Try again:<br />";
}
?>

<!--<div id="login">

<form name="login" action="../admin/" method="post">
  <input name="pass" value="pass" type="password" />&mdash;Password<p class="clear"></p>
  <input type="submit" value="login" />
</form>
</div>

-->


<div id="login">
	<p id="core-logo"><img src="gfx/core_logo_small_black.png" /></p>
	<p class="site-title"><? echo $title;?></p>
<p class="clear"></p>
<form name="login" action="../admin/" method="post">
	<p class="input-lable">Username<p>
	<input type="text" value="" class="input"/>
	<p class="clear"></p>
	<p class="input-lable">Password<p>
	<input name="pass" value="" type="password" />
	<p class="clear"></p>
	<input type="submit" value="login" class="login-button" />
</form>
<p class="clear"></p>
<div id="login-message" class="highlight"> <? echo $login_message; ?> </div>
</div>

<?
}
?>
</body>
</html>
