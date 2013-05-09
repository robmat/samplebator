<?php

$dbhost = "localhost";
$dbuname = "web239";
$dbpass = "ro9OC8ks";
$dbname = "usr_web239_1";
global $prefix;
$prefix = "nuke";

global $db;
$db = mysql_connect($dbhost, $dbuname, $dbpass);
mysql_select_db($dbname, $db);

?>
