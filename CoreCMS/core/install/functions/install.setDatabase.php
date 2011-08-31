<?php
session_start(); 
error_reporting(0);

/*
 * INSTALLATION STEP 6 
 * > Write/set Database pass, user, name, host   to user/configuration.php
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

$new_db_user = $_SESSION["db_user"] = $_REQUEST["db_user"];
if(!$new_db_user){
	debug("No input for variable database user.");
	 $fatal = true; 
}
$new_db_pass = $_REQUEST["db_pass"];
if(!$new_db_user){
	debug("No input for variable database pass.");
	 $fatal = true; 
}
$new_db_name = $_SESSION["db_name"] = $_REQUEST["db_name"];
if(!$new_db_user){
	debug("No input for variable database name"); 
	 $fatal = true; 
}
$new_db_server = $_SESSION["db_server"] = $_REQUEST["db_server"];
if(!$new_db_user){
	debug("No input for variable database server."); 
	$fatal = true; 
}

if($fatal) {
	debug("There was a fatal error. Exiting.");
	echo debug_echo();
	?>
    <p class="btn add" onclick="loadData('<?php echo $http; ?>install/functions/install.getDatabase.php')">Back</p>
    <?
	die();
}

$connection = mysql_connect($new_db_server, $new_db_user, $new_db_pass);
if(!$connection){
	debug("<span class=\"err\">ERROR</span>: There was an error connecting to the database server");
	debug(mysql_error());
	$fatal = true;
}

if($fatal) {
	debug("There was a fatal error. Exiting.");
	echo debug_echo();
	?>
    <p class="btn add" onclick="loadData('<?php echo $http; ?>install/functions/install.getDatabase.php')">Back</p>
    <?
	die();
}

if(!mysql_select_db($new_db_name, $connection)){
	debug("<span class=\"err\">ERROR</span>: There was an error selecting the database");
	debug(mysql_error());
	$fatal = true;
}

if($fatal) {
	debug("There was a fatal error. Exiting.");
	echo debug_echo();
	?>
    <p class="btn add" onclick="loadData('<?php echo $http; ?>install/functions/install.getDatabase.php')">Back</p>
    <?
	die();
}

$str = 
"<?php
\$title = \"".$title."\";
\$theme = \"whiteness\";
\$user = \"".$user."\";
\$pass = \"".$pass."\";
\$db_user = \"".$new_db_user."\";
\$db_pass = \"".$new_db_pass."\";
\$db_name = \"".$new_db_name."\";
\$db_server = \"".$new_db_server."\";
\$show_empty = 0;
\$use_rss = 0;	
?>";

$path = $root . "user/configuration.php";

if(!is_file($path)) {
	$fatal = true;
	debug("<span class=\"err\">ERROR</span>: $path was not found");
}

if($fatal) {
	debug("There was a fatal error. Exiting.");
	echo debug_echo();
	?>
    <p class="btn add" onclick="loadData('<?php echo $http; ?>install/functions/install.getDatabase.php')">Back</p>
    <?
	die();
}

if(!fopen($path,"w")){
	debug("<span class=\"err\">ERROR</span>: Could not open file in write mode, permission error, be sure file is CHMOD to 0777");
	$fatal = true;
} else {
	$file = fopen($path,"w");
}

if(!fwrite($file, $str)){
	debug("<span class=\"err\">ERROR</span>: File was emptied but could not be written to. File is still blank");
	$fatal = true;
}

if($fatal) {
	debug("There was a fatal error. Exiting.");
	echo debug_echo();
	?>
    <p class="btn add" onclick="loadData('<?php echo $http; ?>install/functions/install.getDatabase.php')">Back</p>
    <?
	die();
} else {
	debug("Database configuration saved.");
	if($debug) {
		debug("End of PHP, displaying html.");
		debug_echo();
	}
}
?>

<p class="title-head">Database working!</p>

Please continue to creation of database tables, note that this will not remove or change any information that you currently have in existing database tables.

<p class="margin"></p>
<p class="btn add" onclick="loadData('<?php echo $http; ?>install/functions/install.creatDatabaseTables.php')">Continue</p>