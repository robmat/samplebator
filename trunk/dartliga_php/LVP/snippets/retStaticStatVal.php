<?php
	/**
	 * SNIPPET
	 * file: 		retStaticStatVal.php
	 * returns: 	Static Statistic Values for a specific Player according to statid
	 * 				Table+Rows with TD class=dcell no action buttons, this is a static lookup
	 * params: 		pid,lactive,groupid
	 * usedby:		some ajax pages v1, workflow, playerpage
	 */
	foreach ($_POST as $secvalue) {
	    if (eregi("<[^>]*script*\"?[^>]*>", $secvalue) OR eregi("\([^>]*.*\"?[^>]*\)", $secvalue)) {
			die ("X");
	    }
	}
	
	require_once("../code/config.php");
	require_once("../includes/sql_layer.php");
	require_once("../lsdbcontroller.php");
	require_once("../api_rs.php");
	require_once("../api_format.php");
	
	if (isset($_POST['pid']) && is_numeric($_POST['pid'])) {$player_id=strip_tags($_POST['pid']);}else{$player_id=0;};
	if (isset($_POST['statid']) && is_numeric($_POST['statid'])) {$statcode=strip_tags($_POST['statid']);}else{$statcode=0;};
	if (isset($_POST['limit']) && is_numeric($_POST['limit'])) {$rowlimit=strip_tags($_POST['limit']);}else{$rowlimit=10;};
	
	$dbi = sql_connect($dbhost, $dbuname, $dbpass, $dbname);
	/*
	 * no security here this is used for some public lineUp displays ...
	*/
	$OUT=LSTable_StaticStatValue('statP'.$player_id,$player_id,$statcode,$rowlimit);
	
	header('Content-Type: application/html; charset=ISO-8859-1');
	echo $OUT;
?>