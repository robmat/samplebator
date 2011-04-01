<?php
	/**
	 * SNIPPET	[GET]
	 * file: 	retEventHistogram
	 * purpose:	designed for ax-requests
	 * returns: leg performance histogram according to params either for team or single player based on results from SINGLES
	 * search:	event overides stat_code
	 * params: 	eventgroup,eventid,tid+scorecomp,pid+eventid+scorecomp
	 * example: 
	 * 	a) retEventHistogram.php?eventid=86	- for every event even in archive
	 * 	b) retEventHistogram.php?eventgroup=1	- for active events in group
	 * 	c) retEventHistogram.php?eventgroup=1&pid=2	- only player 2 in active events of group 1
	 *  d) retEventHistogram.php?eventid=0&tid=767	- for every team even in archive
	 *  e) retEventHistogram.php?eventid=163&tid=978 -> returns hist on checkround (FEDA)
	 *  f) scorecomp 0 , 1=won, 2=lost
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
	require_once("../func_lsdb.php");
	
	$hist_mode='dart';
	
	if (isset($_GET['statcode']) && is_numeric($_GET['statcode'])) {$statcode_id=strip_tags($_GET['statcode']);}else{$statcode_id=0;};
	if (isset($_GET['eventgroup']) && is_numeric($_GET['eventgroup'])) {$event_group_id=strip_tags($_GET['eventgroup']);}else{$event_group_id=0;};
	if (isset($_GET['eventid']) && is_numeric($_GET['eventid'])) {$event_id=strip_tags($_GET['eventid']);}else{$event_id=0;};
	if (isset($_GET['pid'])&& is_numeric($_GET['pid'])) {$p_id=strip_tags($_GET['pid']);}else{$p_id=0;};
	if (isset($_GET['tid'])&& is_numeric($_GET['tid'])) {$t_id=strip_tags($_GET['tid']);}else{$t_id=0;};
	if (isset($_GET['scorecomp'])&& is_numeric($_GET['scorecomp'])) {$score_comp=strip_tags($_GET['scorecomp']);}else{$score_comp=0;};
	
	$dbi = sql_connect($dbhost, $dbuname, $dbpass, $dbname);
	$aEvent=reteventconfig($event_id);
	if (sizeof($aEvent)<3) die('Err40:NoEventID');
	#debug($aEvent);
	switch ($score_comp) {
		case 0:	$score_comp='<'.$aEvent['evsgldist'];	break;
		case 2:	$score_comp='>0'; break;
		case 1:	$score_comp='='.$aEvent['evsgldist'];	break;
	}
	
	# raw listing of all games in this league
	if ($t_id>0) $RS=DB_listLegStatDartsHistogramTeam($dbi,$event_group_id,$statcode_id,$event_id,$score_comp,$aEvent['evsgldist'],$t_id);
	if ($p_id>0) {
		#hack since this could come from a group page with non matching pid-eventid pairings
		$privEventID=DB_getEventForPlayer($dbi,$p_id,$aEvent['evtypecode_id']);
		$RS=DB_listLegStatAverageBreakdown($dbi,0,$privEventID,$score_comp,$aEvent['evsgldist'],$p_id,$hist_mode);
	}
	#$fields=array(0,1);
	#$ROWS=RecordsetToDataTable($RS,$fields);
	$ROWS=RecordsetToCSV($RS);
	header('Content-Type: application/text; charset=ISO-8859-1');
	echo $ROWS;
?>