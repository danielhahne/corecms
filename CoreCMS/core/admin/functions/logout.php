<?php

$b = $_SERVER['REQUEST_URI'];
$b = substr($b,0,mb_strrpos($b,"/core/"));
$http = "http://" . $_SERVER['HTTP_HOST'] . substr($b,0,strlen($b));
$core_admin = $http . "/core/admin/";

	session_start();
	session_unregister("password");
	session_unregister("sessid");
	session_destroy();
	header('location:'.$core_admin);
?>
