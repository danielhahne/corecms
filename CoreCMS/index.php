<?php
session_start();
$installed = false; 

$b = $_SERVER['REQUEST_URI'];
$b = substr($b,0,strrpos($b,"/"))."/core/";
$root = $_SERVER['DOCUMENT_ROOT'] . $b;
$http = "http://" . $_SERVER['HTTP_HOST'] . substr($b,0,strlen($b)-5);

require_once($root . "user/configuration.php");
require_once($root . "install/functions/installer.active.php");    

// If Core is installed > Load Core methods
if($installerActive == false){
	$installed = true;
	require_once($root . "functions/session.php");  
	require_once($root . "themes/" . $theme . "/configuration.php"); 
}
?> 

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<?php if($installed==true){?>
	<base href="<?php echo $http; ?>" />
	<title><?php echo $title; ?></title>
	<?php require_once($root . "head.php"); ?>
	<?php if($use_rss == 1) { ?><link href="core/rss-feed.php" type="application/rss+xml" rel="alternate"/><? } ?>
	<?php LOAD_THEME(); 
} else {?> 
	<link rel="stylesheet" type="text/css" href="<?php echo $http; ?>core/install/css/install.style.css"/> 
<?php } ?>

</head>
<body>

	<? 
	
	if($installed) {
		$entry = $_GET["entry"];
		if(is_numeric($entry)) {
			require_once($root . "functions/get_entry.php");
		} else {
			require_once($theme_path . "index.php");
		} 
	 } else { 
		
	 ?>
	 <div class="no-core-found">
	   <p> No Core-Installation found on your Server. Please load the InstallScript.</p>
	   <a class="btn add" href="<?php echo $http; ?>core/install/">Run Installer</a>
	 </div> 
	
	<?php
     }
	?>

</body>
</html>