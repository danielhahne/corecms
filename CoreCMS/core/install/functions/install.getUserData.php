<?php
session_start(); 
error_reporting(0);

/*
 * INSTALLATION STEP 3 
 * > Get Username, Password AND Website-Title input
 */


$fatal = false; 

$root = $_SESSION['root'];
$http = $_SESSION['http'];
require_once($root. '/functions/install.session.php' ); 
$root = set_root(); 



if($_POST["debugging"]) {
	debug("Debugging enabled.");
	$debug_str = "";
	$debug = $_SESSION["debug"] = true;
}

/*
$debug = $_SESSION["debug"]; 
if($debug) {
	debug("Debugging enabled.");
	$debug_str = "";
}
*/     

$upgrade = $_SESSION["upgrade"];

if(!include($root . "user/configuration.php")){ 
	debug("Could not include user configuration. Path: ".$root."user/configuration.php");
	$fatal = true;
} else {
	if($debug)
	{
		debug("Included user configuration.");
	}
}   


if($pass) {
	die("You've already run the installer, if you wish to run it again delete the contents of core/user/configuration.php");
}

if($_SESSION["title"]){
	debug("Session variable \"title\" found");
	$db_val_t = "value=\"".$_SESSION["title"]."\" ";
}
if($_SESSION["user"]){
	debug("Session variable \"user\" found");
	$db_val_u = "value=\"".$_SESSION["user"]."\" ";
}


if($fatal) {
	debug("There was a fatal error. Exiting.");
	echo debug_echo();
	?>
    <p class="btn add" onclick="loadData('<?php echo $http; ?>install/functions/install.checkFiles.php')">Return to previous Page</p>
    <?
	die();
} else {
	if($debug) {
		debug("<br><br><span class=\"err\">INFO: Everthing is great. We now READY to install CORE in your Server..</span>");
		debug_echo();
	}
}
?>

<p class="title-head">Core configuration</p>
<form>
<input type="text" id="title" <? echo $db_val_t; ?>/><p class="form-title">&mdash;Website title</p><p class="clear"></p>
<input type="text" id="user" <? echo $db_val_u; ?>/><p class="form-title">&mdash;User</p><p class="clear"></p>
<input type="password" id="pass" /><p class="form-title">&mdash;Pass</p><p class="clear"></p>
</form>
<p class="margin"></p>
These options aren't permanent and can be changed whenever you want through the admin login.
<p class="margin"></p>

<p class="btn add" onclick="loadData('<?php echo $http; ?>install/functions/install.setUserData.php')">Save Options </p> 