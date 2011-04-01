<?php

	// syntax to test this service: fretAllLocations.php (no params)

foreach ($_GET as $secvalue) {
    if (eregi("<[^>]*script*\"?[^>]*>", $secvalue) OR eregi("\([^>]*.*\"?[^>]*\)", $secvalue)) {
	die ("I don't like you...");
    }
}
	require_once("code/config.php");
	require_once("includes/sql_layer.php");
	require_once("api_rs.php");
	require_once("api_format.php");
	
	$dbi=sql_connect($dbhost, $dbuname, $dbpass, $dbname);
	$RS=DB_listLocations($dbi);
	$OUT=RecordsetToCSV($RS);
	header('Content-Type: application/xhtml+xml; charset=ISO-8859-1');
	echo $OUT;
	
?>