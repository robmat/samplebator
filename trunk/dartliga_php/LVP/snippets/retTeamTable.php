<?php
	/**
	 * SNIPPET
	 * file: 			fretTeams.php
	 * returns: 	TableRows with TD class=dcell on Team details for selected search modes
	 * params: 	name,eventname,eventgroup,verein,location,eventactive
	 * usedby:		some ajax pages v1
	 * example:	retTeamRow.php?verein=sporran&name=angel&eventactive=0
	 * 			retTeamRow.php?location=crown&eventactive=1
	 */
	foreach ($_GET as $secvalue) {
	    if (eregi("<[^>]*script*\"?[^>]*>", $secvalue) OR eregi("\([^>]*.*\"?[^>]*\)", $secvalue)) {
			die ("X");
	    }
	}
	
	require_once("../code/config.php");
	require_once("../includes/sql_layer.php");
	require_once("../api_rs.php");
	require_once("../api_format.php");
	
	if (isset($_GET['name'])) {$team_name=strip_tags($_GET['name']);}else{$team_name="";};
	if (isset($_GET['eventname'])) {$event_name=strip_tags($_GET['eventname']);}else{$event_name="";};
	if (isset($_GET['eventactive'])&& is_numeric($_GET['eventactive'])) {$event_active=strip_tags($_GET['eventactive']);}else{$event_active=1;};
	if (isset($_GET['verein'])) {$verein_name=strip_tags($_GET['verein']);}else{$verein_name="";};
	if (isset($_GET['location'])) {$location_name=strip_tags($_GET['location']);}else{$location_name="";};
	if (isset($_GET['locationid'])&& is_numeric($_GET['locationid'])) {$location_id=strip_tags($_GET['locationid']);}else{$location_id=0;};
	if (isset($_GET['vereinid']) && is_numeric($_GET['vereinid'])) {$verein_id=strip_tags($_GET['vereinid']);}else{$verein_id=0;};
	if (isset($_GET['eventtype'])&& is_numeric($_GET['eventtype'])) {$evtype_id=strip_tags($_GET['eventtype']);}else{$evtype_id=0;};
	if (isset($_GET['eventid']) && is_numeric($_GET['eventid'])) {$event_id=strip_tags($_GET['eventid']);}else{$event_id=0;};
		
	$dbi = sql_connect($dbhost, $dbuname, $dbpass, $dbname);
	$RS=DB_listTeams($dbi,$event_id,$evtype_id,$event_name,$event_active,$team_name,$verein_id,$verein_name,$location_id,$location_name);
	$OUT="<table class=\"tchild\" id=\"teamtable\" name=\"teamtable\">";
	$OUT=$OUT.RecordsetToDataTable($RS,array(2,3,5,7,9));
	$OUT=$OUT."</table>";
	header('Content-Type: application/xhtml+xml; charset=ISO-8859-1');
	echo $OUT;
?>