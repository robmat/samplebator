<?php
	/**
	 * SNIPPET	[POST]
	 * file: 	listCaptain.php
	 * purpose:	list captain basic contact data for : team, event, eventgroup
	 * returns: HTML Table
	 * example: 
	 * 	a) listCaptain.php teamid=1250
	 * 	b) listCaptain.php eventid=128
	 * 	c) listCaptain.php eventgroup=5
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
	
	$team_id=0;
	$event_id=0;
	$event_group_id=0;
	
	if (isset($_POST['teamid']) && is_numeric($_POST['teamid'])) $team_id=strip_tags($_POST['teamid']);
	if (isset($_POST['eventid']) && is_numeric($_POST['eventid'])) $event_id=strip_tags($_POST['eventid']);
	if (isset($_POST['eventgroup']) && is_numeric($_POST['eventgroup'])) $event_group_id=strip_tags($_POST['eventgroup']);
	
	if ($team_id+$event_id+$event_group_id<1) die('X2');
	
	$dbi = sql_connect($dbhost, $dbuname, $dbpass, $dbname);
	
	$RS=DB_getCaptainDataTeam($dbi,$team_id,$event_id,$event_group_id);
	$ROWS=RecordsetToDataTable($RS,array(2,3,4,5,6));

	header('Content-Type: application/html; charset=ISO-8859-1');
	echo '<table width=\'100%\'>'.$ROWS.'</table>';
?>