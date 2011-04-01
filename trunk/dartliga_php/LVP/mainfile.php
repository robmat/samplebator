<?php

/************************************************************************/
/************************************************************************/

foreach ($_REQUEST as $secvalue) {
    if (eregi("<[^>]*script*\"?[^>]*>", $secvalue) OR eregi("\([^>]*.*\"?[^>]*\)", $secvalue)) {
    	die ("Error in request var $secvalue");
    }
}

if (eregi("mainfile.php",$_SERVER['PHP_SELF'])) {
    Header("Location: ./");
    die();
}

require_once('code/config.php');
require_once('includes/sql_layer.php');
require_once('func_sec.php');

$dbi = sql_connect($dbhost, $dbuname, $dbpass, $dbname);
$usertoken=initLsdbSec($dbi);

unset($GLOBALS['user']);
$username='public';

$clientIP=$_SERVER['REMOTE_ADDR'];
$mainfile = 1;

function FixQuotes ($what = "") {
	$what = ereg_replace("'","''",$what);
	while (eregi("\\\\'", $what)) {
		$what = ereg_replace("\\\\'","'",$what);
	}
	return $what;
}



?>
