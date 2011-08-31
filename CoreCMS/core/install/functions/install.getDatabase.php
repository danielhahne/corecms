<?php

session_start(); 
error_reporting(0);

/*
 * INSTALLATION STEP 5 
 * > Get Database pass, user, name, host
 */


$fatal = false; 

$root = $_SESSION['root'];
$http = $_SESSION['http'];
require_once($root. '/functions/install.session.php' ); 
$root = set_root();  
require_once($root. 'user/configuration.php' );  


if($db_user || $db_name || $db_server || $db_pass) {
	die("You've already run the installer, if you wish to run it again delete the contents of core/user/configuration.php");
}

if($_SESSION["db_user"]){
	debug("Session variable for database user found");
	$db_val_u = "value=\"".$_SESSION["db_user"]."\" ";
}
if($_SESSION["db_name"]){
	debug("Session variable for database name found");
	$db_val_n = "value=\"".$_SESSION["db_name"]."\" ";
}
if($_SESSION["db_server"]){
	debug("Session variable for database server found");
	$db_val_s = "value=\"".$_SESSION["db_server"]."\" ";
}


if($fatal) {
	debug("There was a fatal error. Exiting.");
	echo debug_echo();
	?>
    <p class="btn add" onclick="loadData('<?php echo $http; ?>install/functions/install.setUserData.php')">Back</p>
    <?
	die();
} else {
	if($debug) {
		debug("End of PHP, displaying html.");
		debug_echo();
	}
}
?>

<p class="title-head">Database information</p>

<input type="text" id="db_user" <? echo $db_val_u; ?>/><p class="form-title">&mdash;Database user</p><p class="clear"></p>
<input type="password" id="db_pass" /><p class="form-title">&mdash;Database pass</p><p class="clear"></p>
<input type="text" id="db_name" <? echo $db_val_n; ?>/><p class="form-title">&mdash;Database name</p><p class="clear"></p>
<input type="text" id="db_server" <? echo $db_val_s; ?>/><p class="form-title">&mdash;Database server</p><p class="clear"></p>

<p class="margin"></p>

<p class="btn add" onclick="loadData('<?php echo $http; ?>install/functions/install.setDatabase.php')">Continue</p>