<?php
	/*
	 * SNIPPET
	 * file: 			retEventPerPerRound
	 * purpose:	can be called from outside the framework
	 * returns: 	html snippet with google chart
	 * params: 	eventid
	 * example: 	retEventPerPerRound.php?eventid=190
	 * security:	PUBLIC
	 */
	foreach ($_POST as $secvalue) {
    if (eregi("<[^>]*script*\"?[^>]*>", $secvalue) OR eregi("\([^>]*.*\"?[^>]*\)", $secvalue)) {
		die ("X");
    }
	}
	
	require_once("../code/config.php");
	require_once("../includes/sql_layer.php");
	require_once("../func_lsdb.php");

	$event_id=0;
	if (isset($_POST['eventid'])) {$event_id=strip_tags($_POST['eventid']);}
	if (!is_numeric($event_id) || $event_id==0) die('X');
	
	$dbi = sql_connect($dbhost, $dbuname, $dbpass, $dbname);
	$aEvent=reteventconfig($event_id);
	$legDistance=$aEvent['evsgldist'];
	
	$qry="select mround,count(mround) CNT,avg($legDistance/ldarts) AVG from tblmatch,tblgame,tblleg,tplayer P
where mkey=gmkey and gid=lgid and lpid=P.pid and gtype=1 and lscore=$legDistance and mevid=$event_id and P.pgender='H' group by mround asc";
	$qryResultset=sql_query($qry,$dbi);
	$arAVG=array();
	while($a=sql_fetch_row($qryResultset,$dbi)){
				// google charts are normalized between 0 - 100 , we scale from 0 - 50
				$arAVG[]=number_format(($a[2]*2),2,'.','');
		}
	$imgURLH="<img src=\""._googleAVGChart('l',$arAVG)."\"/>";
	$imgHEADH="<h3>Average per Dart Performance Herren Runde 1 - ".sizeof($arAVG)."</h3>";

	$qry="select mround,count(mround) CNT,avg($legDistance/ldarts) AVG from tblmatch,tblgame,tblleg,tplayer P
where mkey=gmkey and gid=lgid and lpid=P.pid and gtype=1 and lscore=$legDistance and mevid=$event_id and P.pgender='D' group by mround asc";
	$qryResultset=sql_query($qry,$dbi);
	$arAVG=array();
	while($a=sql_fetch_row($qryResultset,$dbi)){
				// google charts are normalized between 0 - 100 , we scale from 0 - 50
				$arAVG[]=number_format(($a[2]*2),2,'.','');
		}
	$imgURLD="<img src=\""._googleAVGChart('l',$arAVG)."\"/>";
	$imgHEADD="<h3>Average per Dart Performance Damen Runde 1 - ".sizeof($arAVG)."</h3>";

	# no header in this case ??
	#header('Content-Type: application/html; charset=ISO-8859-1');
	echo "<table width=100%><tr><td>".$imgHEADH."</td><td>".$imgHEADD."</td></tr><tr><td>".$imgURLH."</td><td>".$imgURLD."</td></tr></table";
?>