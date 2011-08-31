<?php 
session_start();
/*
 * INSTALLATION STEP 1  -  DEFAULT LOADING INSTALLATION CONTENT  -  START OF INSTALLATION
 */

$root = $_SESSION['root'];
$http = $_SESSION['http'];
require_once($root. '/functions/install.session.php' );

?>   

<p class="title-head">CORE Licence</p>
Core CMS v<?php CORE_info('version'); ?><br /><br />
Copyright (C) <?php CORE_info('copyright');  ?> by <?php CORE_info('authors');  ?><br /><br /><?php CORE_info('license');  ?>


<p class="margin"></p>
<p class="title-head">CORE Installer</p>
This is the installer of Core v<?php CORE_info('version'); ?><br /><br />
During installing you will be prompted for your database information, so you might aswell look that up now.<br /><br />
<p class="margin"></p>
<form> 	
	<div class="form-obj">            
		<input id="upgrading" type="checkbox">
		<span class="floatLeft">Upgrading</span>
		<span class="help-ico"></span>
		<div class="help-box">If you want to update your Core Version. You dont loose any Databse Content.</div>   
	</div>

	<div class="form-obj">
		<input id="debugging" type="checkbox"> </input>
		<span class="floatLeft">Debugging</span>
		<span class="help-ico"></span>
		<div class="help-box">Debug your Installation. Get all Error's and Information, what we do due the Installation process.</div>
    </div>
	<p class="clear"></p>
</form>


<p class="btn add" onclick="loadData('<?php echo $http; ?>install/functions/install.checkFiles.php')">Check Files</p> 
<p class="btn add" onclick="loadData('<?php echo $http; ?>install/functions/install.getUserData.php')">Start installation..</p>   