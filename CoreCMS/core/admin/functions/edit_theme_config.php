<?
error_reporting(0);
require("_headr.php");
if($_SESSION["password"] != $pass || $_SESSION["sessid"] != session_id()) die(logout());

debug("Password validated, logged in",0);

$current_theme = $_REQUEST["current_theme"];

class ThemeObj {
	var $name, $value, $title, $help;
	function setValues($a,$b,$c,$d) {
		$this->name = $a;
		$this->value = $b;
		$this->title = $c;
		$this->help = $d;
	}
}

$theme_path = $root . "themes/" . $current_theme . "/configuration.php";
if(!is_file($theme_path)) {
	debug("$theme_path path is not a file.",1);
}

$i = 0;
$conf = true;

if ($f = fopen($theme_path, "r")) do {
    $s = fgets($f);
	
	if(strstr($s, "?php")) {
		$t_str = "<?php"."\n";
	} elseif(strstr($s, "//CONFIG")) {
		$t_str = $t_str."//CONFIG"."\n";
	} elseif(strstr($s,"//ENDCONFIG")) {
		$t_str = $t_str."//ENDCONFIG"."\n";
		$conf = false;
	} elseif($conf && strlen($s)>3) {
		$obj[$i] = new ThemeObj();
		$var_name = substr($s,1,strpos($s," ")-1);
		$s = substr($s,strlen($var_name)+4);
		$var_value = substr($s,0,strpos($s,";"));
		$var_value_mix = $var_value;
		if(substr($var_value,0,1) == "\"") {
			$var_value_mix = substr($var_value,1,strlen($var_value)-1);
		}
		$s = substr($s,strlen($var_value)+2);
		$var_title = substr($s,2,strpos($s,"[help]:")-2);
		$s = substr($s,strlen($var_title)+8);
		$var_help = substr($s,2);
		$obj[$i]->setValues($var_name,$var_value,$var_title,$var_help);
		$t_str = $t_str . "$".$obj[$i]->name." = \"".$_REQUEST[$obj[$i]->name]."\"; //".$obj[$i]->title."[help]: ".$obj[$i]->help;
		$i++;
	} elseif(strstr($s, "?>")) {
		$t_str = $t_str.$s;
	} elseif(strlen($s)>0) {
		$t_str = $t_str.$s;
	}
} while (!feof($f) && ($i<50));
fclose($f);

if(!is_file($theme_path)) {
	debug("Error: path did not return file, have you moved theme configuration file?<br /> path: ".$t_path,1);
}

$file = fopen($theme_path,"w") or debug("Could not open theme config in write mode",1);
fwrite($file, $t_str) or debug("Theme config was emptied but could not be written to. File is now blank",1);


debug("Ending script",0);
debug_echo();
?>