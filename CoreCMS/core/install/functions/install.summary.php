<?php
session_start(); 
error_reporting(0);

/*
 * INSTALLATION STEP 8 
 * > Core installation Summary
 */


$fatal = false; 

$root = $_SESSION['root'];
$http = $_SESSION['http'];
require_once($root. '/functions/install.session.php' ); 
$root = set_root();  
require_once($root. 'user/configuration.php' );

$upgrade = $_SESSION['upgrade'];
$debug = $_SESSION['debug'];

 
/*
 * Unset Installation Session Variables
 */
unset($_SESSION['root']);
unset($_SESSION['http']);
unset($_SESSION['db_name']);
unset($_SESSION['db_server']);
unset($_SESSION['db_user']);
unset($_SESSION['title']);
unset($_SESSION['user']);  
unset($_SESSION['debug']);
unset($_SESSION['upgrade']);
debug("Deleted stored session variables");

/*
 * Disable Installer
 */ 
$installerDisabled = disableInstaller();  

?>	

<p class="title-head">Summary</p>
<?
if($installerDisabled == false){
?>
The installer was not able to deactivate itself. You can either modify the "active"-file youself it's located at install/functions/active.php, change the variable to 0. Other than that all appeards to have went well.<br /><br />
<?
} else {
?>
All appears to have went well. The installer has now deactivated itself to disallow anyone from using it/having any possibility at all to access stored data. You can reactivate it in the admin panel. But don't make a habbit of having it activated if not using it.<br /><br />
<?
}
?>

I encourage you to give me bug information, feedback and general thoughts about this, still young, CMS.<br /> 
<!--
I want you to include a link back to <a href="http://core.weareastronauts.org/" target="_blank">Core</a> but other than that there are no requirements, if you feel generous and you enjoy using Core, don't hesitate to make a <a href="http://weareastronauts.org/core-cms/" target="_blank">donation</a>, any contribution is very appreciated.
-->
<br /> 

<?
if($upgrade){
	?>
The installer transfered the old data into new tables, but it did not copy the data (entry folders, images, sound, media), so you have to do this youself, move/copy the old folders to "core/user/uploads/" and upload it to your server.
<p class="margin"></p>
<p class="title-head">Options</p>

<form>
<input type="password" id="pass" /><p class="form-title">&mdash;Pass</p><p class="clear"></p>
</form>
<p class="margin"></p>
   <p class="btn add" onclick="loadData('<?php echo $http; ?>install/functions/install.removeOldDatabase.php')">Remove old database tables</p><p class="clear"></p>

<?
}
?>

<p class="margin"></p>

<p class="title-head">What now?</p>

Well, I suggest you start adding some content to the CMS aswell as check out the configuration settings. By default, entries made won't show up unless you put some content in the entry folder (all explained in the admin panel).<br /><br />

If you forget your password, some manual interaction with the configuration file is required, run passgen.php which was included in the Core_CMS_v<?php echo CORE_info('version', $root.'install/'); ?>.zip file and follow the instructions. 

<br /><br /> 

<a href="<?php echo $http; ?>admin/" target="_blank">Link to the admin panel</a><br /><br />
