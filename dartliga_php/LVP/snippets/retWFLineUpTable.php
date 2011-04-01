<?php
	/**
	 * SNIPPET
	 * file: 		retWFLineUpTable.php
	 * returns: 	Table+Rows with TD class=dcell on Team LineUp details for selected search modes
	 * 				if action=1 then insert actionbuttons (add,remove)
	 * params: 		teamid
	 * usedby:		some ajax pages v1
	 * example:	retWFLineUpTable.php?teamid=
	 * 			retWFLineUpTable.php?teamname=sporran&playertype=2
	 */
	foreach ($_POST as $secvalue) {
	    if (eregi("<[^>]*script*\"?[^>]*>", $secvalue) OR eregi("\([^>]*.*\"?[^>]*\)", $secvalue)) {
			die ("X");
	    }
	}
	
	require_once("../code/config.php");
	require_once("../includes/sql_layer.php");
	require_once("../api_rs.php");
	require_once("../api_format.php");
	
	if (isset($_POST['teamid']) && $_POST['teamid']<>'undefined') {$wfteam_id=strip_tags($_POST['teamid']);}else{$wfteam_id=0;};
	if (isset($_POST['laction']) && $_POST['laction']<>'undefined') {$client_action=strip_tags(urldecode($_POST['laction']));}else{$client_action="";};
				
	$dbi = sql_connect($dbhost, $dbuname, $dbpass, $dbname);
	$RS=DB_listWFTeamLineUp($dbi,$wfteam_id);
	
	$OUT='<table class=\'tchild\' id=\'lineupT'.$wfteam_id.'\' name=\'lineupT'.$wfteam_id.'\'>';
	if (strlen($client_action)>1){
		$OUT=$OUT.RecordsetToDataTable($RS,array(2,4,5,6,7,8),array($client_action),array(array(0,1)),array('Remove'));
	} else {
		$OUT=$OUT.RecordsetToDataTable($RS,array(2,4,5,6,7,8));
	}
	$OUT=$OUT.'</table>';
	header('Content-Type: application/html; charset=ISO-8859-1');
	echo $OUT;
?>