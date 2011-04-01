<?php
	/**
	 * SNIPPET	[GET]
	 * file: 	listLegPerformanceData
	 * purpose:	can be called from outside the framework
	 * returns: leg performance according to params either as html or csv
	 * search:	event overrides stat_code
	 * params: 	pid,eventgroup,statcode,startdate,enddate
	 * example: 
	 * 	a) listLegPerformanceData.php?pid=4680&statcode=5&startdate=2006-09-05&enddate=2007-09-05
	 * 	b) listLegPerformanceData.php?pid=165&eventcode=163
	 * 	c)	
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
	
	$p_id=0;
	$event_code=0;
	$stat_code=0;
	$event_group=0;
	$start_date='';
	$end_date='';
	
	if (isset($_GET['pid'])) $p_id=strip_tags($_GET['pid']);
	if (isset($_GET['statcode'])) $stat_code=strip_tags($_GET['statcode']);
	if (isset($_GET['eventgroup'])) $event_group=strip_tags($_GET['eventgroup']);
	if (isset($_GET['eventcode'])) $event_code=strip_tags($_GET['eventcode']);
	if (isset($_GET['startdate'])) $start_date=strip_tags($_GET['startdate']);
	if (isset($_GET['enddate'])) $end_date=strip_tags($_GET['enddate']);
	
	if (!is_numeric($p_id)) return 0;
	if (!is_numeric($event_code)) return 0;
	if (!is_numeric($stat_code)) return 0;
	if (!is_numeric($event_group)) return 0;
	if (strlen($start_date)>12) return 0;
	if (strlen($end_date)>12) return 0;
	
	$dbi = sql_connect($dbhost, $dbuname, $dbpass, $dbname);
	#echo $event_code.":".$event_group.":".$stat_code.":".$start_date.":".$end_date.":".$p_id;
	$RS=DB_listLegsFromPeriod($dbi,$event_code,$event_group,$stat_code,$start_date,$end_date,$p_id);
	#$fields=array(7,6,4,0,1,2,3);
	#$ROWS=RecordsetToDataTable($RS,$fields);
	$ROWS=RecordsetToCSV($RS);
	header('Content-Type: application/text; charset=ISO-8859-1');
	echo $ROWS;
?>