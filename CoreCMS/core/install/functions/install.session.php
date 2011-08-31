<?php 
/*
 * Install CORE Methods 
 * 
 */




	/**
	 * Retrieve infos about CoreCMS, like Version, root etc..
	 *
	 *
	 * @since 1.3.1
	 *
	 * @param string.
	 * @return strings
	 */
	function CORE_info($show, $setRoot = '')
	{  
		global $http;
		if($setRoot!='')
		{  
			$root = $setRoot;
		} else {
			global $root;
		}
		
		include($root . "/core.data.php");   
		
		switch($show){
			case 'version':
					echo $version; 
					break;
			case 'copyright': 
					echo $copyright_year; 
					break;  
			case 'url': 
					echo $http; 
					break; 
			case 'authors': 
					echo $authors; 
					break;
			case 'license': 
					echo $license_text; 
					break;
		    case 'root': 
				    return $root; 
					break; 			 
			default: 
					""; 
					break; 		
		} 
	} 
	   
	
	
	
	
	
	/**
	 * Outputs html debug
	 *
	 *
	 * @since 1.3.1
	 *
	 * @param string.
	 * @return strings
	 */   
	function debug($str) {
		global $debug_str;
		$debug_str = $debug_str . "<br />" . $str;
	}

	function debug_echo() {
		echo "<p class=\"title-head\">INFO</p>";
		global $debug_str;
		//echo "<p class=\"margin\"></p>";
		echo $debug_str;
		echo "<p class=\"margin\"></p>";
	}  
	
	
	   
	
	
	
	/**
	 * Tests files and directory's permissions and existing
	 *
	 *
	 * @since 1.2.1
	 *
	 * @return String
	 */  
	function file_test($file,$dir,$chmod) {
		global $fatal;
		$return = "";
		
		if($dir) {
			if(!is_dir($file)) {
				$return = "<span class=\"err\">ERROR: </span>$file is not a directory.";
				$fatal = true;
			}
			if($chmod) {
				if(!is_writable($file)) {
					chmod($file,"0777") or $return = "<span class=\"err\">ERROR: </span>$file is not writable, you will have to change permissions by chmodding the file to 0777.";
					$fatal = true;
				}
			}
		} else {
			if(!is_file($file)) {
				$return = "<span class=\"err\">ERROR: </span>$file is not a file.";
				$fatal = true;
			}
			if($chmod) {
				if(!is_writable($file)) {
					chmod($file,"0777") or $return = "<span class=\"err\">ERROR: </span>$file is not writable, you will have to change permissions by chmodding the file to 0777.";
					$fatal = true;
				}
			}
		} 

		if($fatal==true) {
			debug($return);
		} else {
			debug("$file was found, no errors reported.");
		} 
	}    
	
	
	
	
	
	/**
	 * Returns the path of the current Root directory
	 *
	 *
	 * @since 1.2.1
	 *
	 * @return String / Path
	 */  
	function set_root() {
		$b = $_SERVER['REQUEST_URI'];
		$b = substr($b,0,mb_strrpos($b,"/core/")+6);
		$id = $_REQUEST["id"];
		$root = $_SERVER['DOCUMENT_ROOT'] . $b;
		return $root;
	} 
	
	 
	 
	
	
	
	
	/**
	 * Checks if all core needed tables are right in place
	 *
	 *
	 * @since 1.2.1
	 *
	 * @return Boolean
	 */

	function check_table($table) {
		$sql = "desc $table";
		if(mysql_query($sql)) {
			return true;
		} else {
			return false;
		}
	}    
	
	
	
	
	
	
	
	/**
	 * Disables the Installer. Change the Var $installerActive in installer.active.php > core/install/functions/ directory to false if the Installer finished with no errors
	 *
	 *
	 * @since 1.3.1
	 *
	 * @return Boolean
	 */   
	
	function disableInstaller() {
		global $root; 
		$res = true;
		                 
		$file = fopen($root. "install/functions/installer.active.php","w");
		if(!$file) {
			debug("<span class=\"err\">ERROR</span>: Can't " . $root . "install/functions/installer.active.php active file.");
			$res = false;
		}

		$str = "<?
		\$installerActive = false; 
		?>";

		if(!fwrite($file, $str)){
		  	debug("<span class=\"err\">ERROR</span>: Could not write to " . $root . "install/functions/installer.active.php, installer still active. <br>Please change the variable -installerActive- manualy to false.");
			$res = false;
		} 	
		return $res;
     }


?>