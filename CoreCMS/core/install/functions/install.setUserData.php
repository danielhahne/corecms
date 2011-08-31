<?php

session_start(); 
error_reporting(0);

/*
 * INSTALLATION STEP 4 
 * > Sets/Writes Username, Password (md5) AND Website-Title to user/configuration.php
 */


$fatal = false; 

$root = $_SESSION['root'];
$http = $_SESSION['http'];
require_once($root. '/functions/install.session.php' ); 
$root = set_root();  
require_once($root. 'user/configuration.php' ); 

$debug = $_SESSION["debug"];
$upgrade = $_SESSION["upgrade"];


if($pass) {
	die("You've already run the installer, if you wish to run it again delete the contents of core/user/configuration.php");
}  

$new_title = $_SESSION["title"] = $_REQUEST["title"];
$new_user = $_SESSION["user"] = $_REQUEST["user"];
$new_pass = $_REQUEST["pass"];

if($new_user && $new_pass) {
	$salt = substr(md5($new_user),0,15);
	$pass_salted = sha1($new_pass . $salt);
} else {
	$fatal = true;
	debug("There was no valid password/user inputed, please try again");
}

if($fatal) {
	debug("There was a fatal error. Exiting.");
	echo debug_echo();
	?>
    <p class="btn add" onclick="loadData('<?php echo $http; ?>install/functions/install.getUserData.php')">Return to previous Page</p>
    <?
	die();
}

debug("Generated password.");

$str = "<?php
\$title = \"".$new_title."\";
\$user = \"".$new_user."\";
\$pass = \"".$pass_salted."\";
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
    <p class="btn add" onclick="loadData('<?php echo $http; ?>install/functions/install.getUserData.php')">Return to previous Page</p>
    <?
	die();
}

debug("Configuration file found.");

$file = fopen($path,"w");
if(!$file){
	debug("<span class=\"err\">ERROR</span>: Could not open file in write mode, permission error, be sure file is CHMOD to 0777");
	$fatal = true;
}else{				
	if(!fwrite($file, $str)){
		debug("<span class=\"err\">ERROR</span>: File was emptied but could not be written to. File is still blank");
		$fatal = true;
	}
}

if($fatal) {
	debug("There was a fatal error. Exiting.");
	echo debug_echo();
	?>
    <p class="btn add" onclick="loadData('<?php echo $http; ?>install/functions/install.getUserData.php')">Return to previous Page</p>
    <?
	die();
} else {
	debug("Configuration saved.");
	if($debug) {
		debug("Everything goes as planned!");
		debug_echo();
	}
}
?> 

<p class="title-head">Alright <?php echo $new_user; ?>,
<br>We save your Configurations and are now ready to set up a new Database Connection.</p>
<p class="margin"></p>
<p class="btn add" onclick="loadData('<?php echo $http; ?>install/functions/install.getDatabase.php')">Set up Database</p>