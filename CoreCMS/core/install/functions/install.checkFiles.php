<?php 
session_start();
error_reporting(0);
  
/*
 * INSTALLATION STEP 2 
 * > Checks if all core files are right in place
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

if($_POST["upgrading"]) {
	debug("Upgrade enabled.");
	$_SESSION["upgrade"] = true;
}

if(!include($root . "/user/configuration.php")){
	debug("Could not include user configuration. Path: ".$root."/user/configuration.php");
} else {
	debug("Included user configuration. ");
}  

if(!include($root . "install/functions/installer.active.php")){ 
	debug("Could not include user configuration. Path: ".$root . "install/functions/installer.active.php");
	$fatal = true;
} else {
	if($debug)
	{
		debug("Included installer active file");
	}
}

if($pass) {
    // maybe overwrite functionality
	die("You've already run the installer, if you wish to run it again delete the contents of core/user/configuration.php");
}
    
file_test($root,true,false);
file_test($root . "admin",true,false);
file_test($root . "user",true,false);
file_test($root . "user/uploads",true,true);
file_test($root . "user/cache",true,true);
file_test($root . "functions",true,false);
file_test($root . "themes",true,false);
file_test($root . "user/configuration.php",false,true);
file_test($root . "functions/session.php",false,false);
file_test($root . "functions/get_entry.php",false,false); 


if($fatal==true) {
	debug("There was a fatal error. Exiting.");
	echo debug_echo();
	?>
	<p class="btn add" onclick="loadData('<?php echo $http; ?>install/functions/install.start.php')">Return to previous Page</p>
    <p class="btn add" onclick="loadData('<?php echo $http; ?>install/functions/install.getUserData.php')">Try to install anyway?</p>  
    <?
	die();
} else {
		debug("<br><br><span class=\"err\">INFO: We check all permissions by chmodding for you. Everything is allright..!</span>");
		debug_echo();
}
?>
<p class="btn add" onclick="loadData('<?php echo $http; ?>install/functions/install.getUserData.php')">All files where in place. Please continue! </p>