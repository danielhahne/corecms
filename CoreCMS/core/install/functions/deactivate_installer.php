<?php
error_reporting(0);
session_start();

$current_root = $_SESSION["current_root"];
include($current_root . "functions/active.php");

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

if($active != 1) {
	debug("Installer not active");
	die(debug_echo());
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
	?>
	<p class="btn add" onclick="loadData('summary.php')">Back</p>
	<?
	die();
}

if(isset($_POST["pass"])) {
	$input_pass = $_POST["pass"];
	$salt = substr(md5($user),0,15);
	$pass_salted = $input_pass . $salt;
	$input_pass = sha1($pass_salted);
	if($input_pass == $pass) {

		$path = $current_root . "functions/active.php";
		
		if(!fopen($path,"w")){
			debug("<span class=\"err\">ERROR</span>: Could not open file in write mode, permission error, be sure file is CHMOD to 0777");
			$fatal = true;
		} else {
			$file = fopen($path,"w");
		}
		
		if($fatal) {
			debug("There was a fatal error. Exiting.");
			echo debug_echo();
			?>
			<p class="btn add" onclick="loadData('summary.php')">Back</p>
			<?
			die();
		}
		
		$str = "
		<?
		\$active = 0; 
		?>
		";
		
		if(!fwrite($file, $str)){
			debug("<span class=\"err\">ERROR</span>: File was emptied but could not be written to. File is still blank");
			$fatal = true;
		}
		
		if($fatal) {
			debug("There was a fatal error. Exiting.");
			echo debug_echo();
			?>
			<p class="btn add" onclick="loadData('summary.php')">Back</p>
			<?
			die();
		}

		$input_pass = NULL;
	} else {
		if($fatal) {
			debug("Wrong pass");
			
			?>
			<p class="title-head">Wrong password!</p>
			<p class="btn add" onclick="loadData('summary.php')">Back</p>
			<?
			
			echo debug_echo();
			die();
		}
	}
} else {
	
	$fatal = true;
		
	if($fatal) {
		debug("You are not logged in.");
		
		?>
		<p class="title-head">Not logged in, please enter your password!</p>
		<p class="btn add" onclick="loadData('summary.php')">Back</p>
		<?
		
		echo debug_echo();
		die();
	}
}
	

if($fatal) {
	debug("There was a fatal error. Exiting.");
	echo debug_echo();
	?>
    <p class="btn add" onclick="loadData('summary.php')">Back</p>
    <?
	die();
} else {
	debug("Deactivate success.");
	if($debug) {
		debug("End of PHP, displaying html.");
		debug_echo();
	}
}
?>

<p class="title-head">Deactivate success!</p>

The installer is now deactivated, it can be reactivated through the admin panel.