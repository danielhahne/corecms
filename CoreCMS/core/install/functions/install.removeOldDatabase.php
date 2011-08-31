<?php
error_reporting(0);
session_start();

$current_root = $_SESSION["current_root"];

if(!include($current_root . "install_session.php")){
	debug("Could not include install_session.php. Path: ".$current_root."install_session.php");
	$fatal = true;
} else {
	debug("Included install_session.php.");
}

function add_s($s){
	if (1 == get_magic_quotes_gpc()){
		$s = addslashes($s);
	}
	return $s;
}

$fatal = false;
$debug = $_SESSION["debug"];
if($debug) {
	debug("Debugging enabled.");
	$debug_str = "";
}

$upgrade = $_SESSION["upgrade"];

if(!include($current_root . "functions/active.php")){
	debug("Could not include active.php. Path: ".$current_root."functions/active.php");
	$fatal = true;
} else {
	debug("Included functions/active.php.");
}

$root = set_root();
if(!include($root . "user/configuration.php")){
	debug("Could not include user configuration. Path: ".$root."user/configuration.php");
	$fatal = true;
} else {
	debug("Included user configuration.");
}


if($fatal) {
	debug("There was a fatal error. Exiting.");
	echo debug_echo();
	die();
}

if(isset($_POST["pass"])) {
	$input_pass = $_POST["pass"];
	$salt = substr(md5($user),0,15);
	$pass_salted = $input_pass . $salt;
	$input_pass = sha1($pass_salted);
	if($input_pass == $pass) {
		
		$connection = mysql_connect($db_server, $db_user, $db_pass);
		if(!$connection){
			debug("<span class=\"err\">ERROR</span>: There was an error connecting to the database server");
			debug(mysql_error());
			$fatal=true;
		}else{
			debug("Connected to database server.");
			if(!mysql_select_db($db_name, $connection)){
				debug("<span class=\"err\">ERROR</span>: There was an error selecting the database");
				debug(mysql_error());
				$fatal=true;
			}
		}
		if($fatal) {
			debug("There was a fatal error. Exiting.");
			echo debug_echo();
			die();
		}
		
		if(check_table("data")){
			debug("Table 'data' was found, deleting");
			if(!mysql_query("DROP TABLE `data`")) {
				debug("<span class=\"err\">ERROR</span>: There was an error deleting the old database table");
				debug(mysql_error());
				$fatal=true;
			} else {
				debug("Table 'data' was dropped");
			}
			
			if($fatal) {
				debug("There was a fatal error. Exiting.");
				echo debug_echo();
				die();
			}
			
		}
		$input_pass = NULL;
	}
}

if($fatal) {
	debug("There was a fatal error. Exiting.");
	echo debug_echo();
	die();
} else {
	debug("Deletion success.");
	if($debug) {
		debug("End of PHP, displaying html.");
		debug_echo();
	}
}
?>
<p class="title-head">Deletion success!</p>

I encourage you to give me bug information, feedback and general thoughts about this, still young, CMS.<br />
<br />

Other than that I want you to include a link back to <a href="http://core.weareastronauts.org/" target="_blank">Core</a> if you use it, <a href="http://core.weareastronauts.org/" target="_blank">Core</a> is a completely free product, developed only by myself (Simon Jakobsson), remember that when you ask me about help or bugfixes, I do what I can with the time I have to spend on <a href="http://core.weareastronauts.org/" target="_blank">Core</a>.<br /><br />
<p class="margin"></p>

<p class="title-head">What now?</p>

Well, I suggest you start adding some content to the CMS aswell as check out the configuration settings. By default, entries made won't show up unless you put some content in the entry folder (all explained in the admin panel).<br /><br />

<a href="../admin/" target="_blank">Link to the admin panel</a><br /><br />

If you forget your password, some manual interaction with the configuration file is required, run passgen.php which was included in the Core v1.1.zip file and follow the instructions.