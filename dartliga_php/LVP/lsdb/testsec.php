<?php
/*
 * security test suite 
 */
	if ($_SERVER['REQUEST_METHOD']<>'POST') die('Y');
	
	include("../code/config.php");
	require("../includes/sql_layer.php");
	require("../func_sec.php");
	
	if (isset($_POST['action'])&& $_POST['action']<>"undefined") {$sec_action=strip_tags($_POST['action']);}else{$sec_action=""; die("E2");};
	
	session_start();
	$dbi = sql_connect($dbhost, $dbuname, $dbpass, $dbname);
	
	switch($sec_action){
		case "info":
			echo phpinfo();
			break;
		case "test":
			$usertoken=initLsdbSec($dbi);
			debug($usertoken);
			break;
	}
	// echo "Access Event 160: ".$usertoken['eventmap'][160];
?>