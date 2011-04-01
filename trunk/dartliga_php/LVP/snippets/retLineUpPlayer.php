<?php
	/**
	 * SNIPPET
	 * file: 		retLineUpPlayer.php
	 * returns: 	Table+Rows with TD class=dcell on Team LineUp details for selected search modes
	 * 				if action=1 then insert actionbuttons (add,remove)
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
	#require_once("../func_sec.php");
	require_once("../lsdbcontroller.php");
	require_once("../api_rs.php");
	require_once("../api_format.php");
	
	if (isset($_POST['pid']) && is_numeric($_POST['pid'])) {$player_id=strip_tags($_POST['pid']);}else{$player_id=0;};
	if (isset($_POST['wfid']) && is_numeric($_POST['wfid'])) {$wf_id=strip_tags($_POST['wfid']);}else{$wf_id=0;};
	if (isset($_POST['lactive']) && is_numeric($_POST['lactive'])) {$activeflag=strip_tags($_POST['lactive']);}else{$activeflag=1;};
	if (isset($_POST['groupid']) && is_numeric($_POST['groupid'])) {$liga_group_id=strip_tags($_POST['groupid']);}else{$liga_group_IN_id='';};
	if (isset($_POST['laction'])) {$client_action=strip_tags($_POST['laction']);}else{$client_action='';};
	
	$dbi = sql_connect($dbhost, $dbuname, $dbpass, $dbname);
	/*
	 * no security here this is used for some public lineUp displays ...
	*/
	$OUT=LSTable_PlayerToTeams('lineupP'.$player_id,$player_id);
	
	header('Content-Type: application/html; charset=ISO-8859-1');
	echo $OUT;
?>