<?php
$chmod = 0777;
require("../admin_session.php");
$root = set_root();
require($root . "user/configuration.php");
$debug_str = "";
$fatal = false;

/*
 *  moved to login function in admin_session.php
 *  session_start();
 */

$connection = @mysql_connect($db_server, $db_user, $db_pass);
@mysql_select_db($db_name, $connection);
?>