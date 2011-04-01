<?php
	/**
	 * SNIPPET	[GET]
	 * file: 	listEventTopWinners.php
	 * purpose:	can be called from outside the framework
	 * returns: leg performance according to params either as html or csv
	 * search:	event overrides stat_code
	 * params: 	pid,eventgroup,statcode,startdate,enddate
	 * example: 
	 * 	a) listLegPerformanceData.php?pid=4680&statcode=5&startdate=2006-09-05&enddate=2007-09-05
	 * 	b) listLegPerformanceData.php?pid=165&eventcode=163
	 * 	c)	
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
	
	$event_code=0;
	$limit=10;
	if (isset($_POST['eventid'])) $event_code=strip_tags($_POST['eventid']);
	if (isset($_POST['limit'])) $limit=strip_tags($_POST['limit']);
	
	if (!is_numeric($event_code)) return 0;
	if (!is_numeric($limit)) return 0;
	
	$dbi = sql_connect($dbhost, $dbuname, $dbpass, $dbname);
	
	$RSH=DB_listEventTopWinners($dbi,$event_code,$limit,'H');
	$ROWSH=RecordsetToDataTable($RSH,array(0,1,2));
	$HEADH="Top 10 Sieg Spieler Herren";

	$RSD=DB_listEventTopWinners($dbi,$event_code,$limit,'D');
	$ROWSD=RecordsetToDataTable($RSD,array(0,1,2));
	$HEADD="Top 10 Sieg Spieler Damen";

	header('Content-Type: application/html; charset=ISO-8859-1');
	echo "<table width=100%><tr><td><h3>$HEADH</h3></td><td><h3>$HEADD</h3></td></tr>"
		."<tr><td><table>".$ROWSH."</table></td><td><table>".$ROWSD."</table></td></tr></table>";
?>