<?php
session_start();
error_reporting(0);
 
$b = $_SERVER['REQUEST_URI'];
$b = substr($b,0,strrpos($b,"/"));
$root = $_SERVER['DOCUMENT_ROOT'] . $b;
$http = "http://" . $_SERVER['HTTP_HOST'] . substr($b,0,strlen($b)-7); 

$_SESSION['root'] = $root;
$_SESSION['http'] = $http;   

require_once("../user/configuration.php");              
require_once($root. '/functions/install.session.php' );   
 
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<title>CORE Installation</title>

<!-- 01 Styles --> 
<link rel="stylesheet" type="text/css" href="<?php echo $http; ?>install/css/install.style.css"/>

<!-- 02 Scripts --> 
<script src="<?php echo $http; ?>install/js/jquery.min.js" type="text/javascript"></script>
<script type="text/javascript"> var loaderElem = "<img src='<?php echo $http; ?>install/gfx/ajax-loader.gif' />";  </script>
<script src="<?php echo $http; ?>install/js/install.js" type="text/javascript"></script> 
<script type="text/javascript"> 
        $(document).ready(function(){ 
	       	loadData("<?php echo $http; ?>install/functions/install.start.php");
		});
</script>
</head>

<body>
<?
if($pass || $user) {
	die("You've already run the installer, if you wish to run it again delete the contents of core/user/configuration.php");
}

if(ini_get('register_globals')) {
	die("You have \"register globals\" on in your PHP configuration, Core was not designed with this in mind, and will not be able to install if you don't turn them off. <a href=\"http://www.google.com/search?q=turn+off+register+globals\" target=\"_blank\">Google search</a>");
}
?>
<div class="wrap">
    <div class="header">
		<div id="loader"></div>
		<h1>Core <?php CORE_info('version'); ?> Installation</h1>
	</div>
	<p class="margin"></p>
	<div id="content">
		<!-- PLACE FOR AJAX -->
	</div> 
</div> 

</body>
</html>