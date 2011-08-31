<?
error_reporting(0);
require("_headr.php");
if($_SESSION["password"] != $pass || $_SESSION["sessid"] != session_id()) die(logout());

debug("Password validated, logged in",0);

$toggle = $_REQUEST["toggle"];

$file = fopen($root . "install/functions/active.php","w");
if(!$file) {
	debug("Can't open $root"."install/functions/active.php file.",1);
}

$str = "<?
\$active = $toggle; 
?>";

if(!fwrite($file, $str)){
	debug("Could not write to active.php",1);
}


debug("Ending script",0);
debug_echo();
?>