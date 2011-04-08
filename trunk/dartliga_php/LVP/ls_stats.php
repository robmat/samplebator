<?php

# //
# // statistic data digging for the LS system
# // based on BL system v2
# // v1 BH 02.2006
# // rule: all procedures have to transport the $eventid flag
# // selecting the correct event for the stats is done via (gmkey like 'e".$eventid."r%' )
# // v2.5 included gesamt ranking based on the EVENT-Group + drilldown
# // v2.5 added legcounter to the rankings
# // v2.6 included legacy stuff from tbllegx into gesamt RANKINGS.
# // v2.7 included the iTEST flag on the FEDAIndex pages to change the checkindex count
#		FIX: Liga ranking

include("ls_main.php");
require("func_stat.php");
require('ORM/fedastat.php');
# // security ... this is easy since here we are public ....
# // this is a GET only controller, no POST processing is done here ...
# // Beginn Funktionen ----------------

$tdbg="#CCDDCC";
$tdWon="#ccffcc";
$tdLost="#ffcccc";

$d = getdate();
$rtdate = "$d[year]"."-"."$d[mon]"."-"."$d[mday]";
#// hardcoded to FEDA MIX ....
$fedadefaultdate=RetStatDateForStatCode(3,$rtdate);

/** @_MakeStatPageHeader
 * this creates the top and navigation rows on every statistic page ...
 * param: $evlistnum contains the eventGroupListNumber - indicates that we are viewing a consolidated eventLIST STAT from a group
 * TODO - depending on the event we can have different headers in here .... this is awfull right now
 * 		- should this be stat_num or group_num ????????
 * 		- how about just passing a costum header ??
 **/
function _MakeStatPageHeader($event_id,$evlistnum){	
		 
	global $event;
	
	$ret="";
	
	switch($evlistnum) {
		case "3":	# FEDA MIXED
		case "5":	# FEDA Damen
			$ret='<h2>Aktuelle Rangliste f&uuml;r die Ligagruppe '.$event['typdesc'].'</h2>';
			break;
		
		case "1":
			$ret='<h2>Gesamt Auswertung f&uuml;r die Ligagruppe '.$event['typdesc'].'</h2>';
			break;
		
		default:
			$ret='<h2>Spieler Statistik '.$event['evname'].' Saison '.$event['evyear'].'</h2>';
			break;
	}
	
	#TODO => use the pagecontrols div for this ...
	#TODO => use the buttonbar layout object for this ...
	$ret=$ret.'<div id="topnav"><table width="100%" border="0"><tr>';

	if ($event['evsgldarts']==1) {
		#$ret=$ret."<td>"._button("Darts","","ls_stats.php?func=dartstats&eventid=$event_id")."</td>";
		$ret=$ret.'<td>'._button("Legs","","ls_stats.php?func=legstats&eventid=$event_id")."</td>";
		$ret=$ret.'<td>'._button("Sets","","ls_stats.php?func=setstats&eventid=$event_id")."</td>";
		$ret=$ret.'<td>'._button("3-Darts","","ls_stats.php?func=3darts&eventid=$event_id")."</td>";
		$ret=$ret.'<td>'._button("Legs Gesamte Liga Gruppe","","ls_stats.php?func=legstatsgroup&eventid=$event_id")."</td>";
}
	if ($event['evsglfinish']==1) {
		$ret=$ret."<td>"._button("Finish","","ls_stats.php?func=finishstats&eventid=$event_id")."</td>";
	}
	if ($event['evsglroundcheck']==1) {
		$ret=$ret.'<td>'._button("Score Leistung","","ls_stats.php?func=rscorestat&eventid=$event_id")."</td>";
		$ret=$ret.'<td>'._button("Check Leistung","","ls_stats.php?func=rcheckstat&eventid=$event_id")."</td>";
		$ret=$ret.'<td>'._button("Rangliste Rechner","","ls_stats.php?func=calcfedaindex&eventid=$event_id")."</td>";
		$ret=$ret.'<td>'._button("LIGA Rangliste","","ls_stats.php?func=ligaindex&eventid=$event_id")."</td>";
		$ret=$ret.'<td>'._button("Gesamt Rangliste","","ls_stats.php?func=statlist&eventid=$event_id")."</td>";
	}
	if ($event['evstatcode_id']==9){
		$ret=$ret.'<td>'._button("Leg Index","","ls_stats.php?func=statcodelist&eventid=$event_id")."</td>";
	}
	if ($event['evstatcode_id']>10){
		// here we show the official stats list for this league - based on the config
		// currently restricted to the BDSO (16) and NOEDSV 17
		$ret=$ret.'<td>'._button("Rangliste Liga","","ls_stats.php?func=statcodelist&eventid=$event_id")."</td>";
		$ret=$ret.'<td>'._button("Rangliste Liga Gruppe","","ls_stats.php?func=statcodelist&eventid=$event_id&event_group=".$event['evtypecode_id'])."</td>";
	}
	$ret=$ret.'<td>'._button("Siege","","ls_stats.php?func=wonloststats&eventid=$event_id")."</td>";
	$ret=$ret.'<td>'._button("Bestleistungen","","ls_stats.php?func=lowstats&eventid=$event_id")."</td>";
	$ret=$ret.'<td></tr></table></div><br>';
	return $ret;
}

function _showdartstat($event_id,$event_group_id){
	# 
	# query to show the actual averages per dart, this is using the most datapoints ...
	# sum(lscore)/sum(ldarts) AVG ==> total sum of score achieved divided by total sum of darts
	#
	global $dbi,$tdbg,$event;
	
	$aTH=array("Vorname","Nachname","Summe Darts","Summe Score","Average","Abweichung");
	$RS=DB_listDartStatAverage($dbi,$event_group_id,$event_id,'>0');
	$ROWS=RecordsetToDataTable($RS,array(1,2,3,4,5,6));
	
	echo _MakeStatPageHeader($event_id,0);
	echo '<h3>Alle Legs</h3>';
	echo '<table width="100%">'.ArrayToTableHead($aTH).$ROWS.'</table>';
	
}

function _show3dartroundaverage($eventid){
	/**
	 * @_show3dartroundaverage
	 * this statistics mimics the round average used in e-darts
	 * basically the same as _legStat but expressed in rounds ...
	 * chg. to DB_layer 9.2007
	 */
	global $event,$dbi,$tdbg;
	
	$aTH=array("Vorname","Nachname","Anzahl","Rest","Mittelwert d. Checkrunde","Abweichung","Mittelwert Score p. Runde");
	$RS1=DB_listRoundAverageStatForDartsLeg($dbi,0,$eventid,'='.$event['evsgldist'],$event['evsgldist']);
	$fields=array(1,2,3,4,5,6,7);
	$ROWS1=RecordsetToDataTable($RS1,$fields);	
	$RS2=DB_listRoundAverageStatForDartsLeg($dbi,0,$eventid,'<'.$event['evsgldist'],$event['evsgldist']);
	$ROWS2=RecordsetToDataTable($RS2,$fields);
	/*
	 * OUTPUT here
	 */
	echo _MakeStatPageHeader($eventid,0);
	echo '<h3>Gewonnene Legs</h3>';
	echo '<table width="100%">'.ArrayToTableHead($aTH).$ROWS1.'</table>';
	echo '<h3>Verlorene Legs</h3>';
	echo '<table width="100%">'.ArrayToTableHead($aTH).$ROWS2.'</table>';
	
}

/**
*	purpose:	Display Statistics on LEGS for darts based events
* 	params:		event_id
*	returns:	page view with lists
*/
function _showlegstat($eventid,$group_flag=0){
	global $event,$dbi,$tdbg;
	
	if ($group_flag==1){$eventgroup=$event['evtypecode_id'];} else {$eventgroup=0;}
		
	$aTH=array('Vorname','Nachname','Legs','Average','Abweichung');
	$aMode=array(2,1,0);
	$aComp=array('>0','='.$event['evsgldist'],'<'.$event['evsgldist']);
	$aLegend=array('Alle Legs','Gewonnene Legs','Verlorene Legs');
	
	# nice loop over the arrays ???
	
	$RS=DB_listLegStatAverage($dbi,$eventgroup,$eventid,'>0');
	$fields=array(1,2,3,4,5);
	$target='ls_stats.php?func=legstatdetail&eventid='.$eventid.'&pid=%P1%&mode=2';
	$ROWSA=RecordsetToClickTable($RS,1,$target,0);
	
	$RS=DB_listLegStatAverage($dbi,$eventgroup,$eventid,'='.$event['evsgldist']);
	$target='ls_stats.php?func=legstatdetail&eventid='.$eventid.'&pid=%P1%&mode=1';
	$ROWSW=RecordsetToClickTable($RS,1,$target,0);
	
	$RS=DB_listLegStatAverage($dbi,$eventgroup,$eventid,'<'.$event['evsgldist']);
	$target='ls_stats.php?func=legstatdetail&eventid='.$eventid.'&pid=%P1%&mode=0';
	$ROWSL=RecordsetToClickTable($RS,1,$target,0);
	
	$OUT= _MakeStatPageHeader($eventid,$group_flag);
	$OUT=$OUT.'<h3>Alle Legs</h3>';
	$OUT=$OUT.'<table width="100%">'.ArrayToTableHead($aTH).$ROWSA.'</table>';
	$OUT=$OUT.'<h3>Gewonnene Legs</h3>';
	$OUT=$OUT.'<table width="100%">'.ArrayToTableHead($aTH).$ROWSW.'</table>';
	$OUT=$OUT.'<h3>Verlorene Legs</h3>';
	$OUT=$OUT.'<table width="100%">'.ArrayToTableHead($aTH).$ROWSL.'</table>';
	
	# // do we show the START Statistics ???
	if ($event['evsglstart']==1) {
		$precord = sql_query('select pfname,plname,count(lscore) LEGS,round(avg(lscore/ldarts),2) AVG,round(stddev(lscore/ldarts),2) DEV from tblleg,tblgame,tplayer where lpid=pid and lgid=gid and gmkey like "e'.$eventid.'r%" and ldarts>1 and gtype=1 and lstart=1 group by lpid order by AVG desc',$dbi);
		$RS=createRecordSet($precord,$dbi);
		$ROWSV=RecordsetToDataTable($RS,array(0,1,2,3,4));

		$precord = sql_query('select pfname,plname,count(lscore) LEGS,round(avg(lscore/ldarts),2) AVG,round(stddev(lscore/ldarts),2) DEV from tblleg,tblgame,tplayer where lpid=pid and lgid=gid and gmkey like "e'.$eventid.'r%" and ldarts>1 and gtype=1 and lstart=0 group by lpid order by AVG desc',$dbi);
		$RS=createRecordSet($precord,$dbi);
		$ROWSN=RecordsetToDataTable($RS,array(0,1,2,3,4));

		$OUT=$OUT.'<h3>Vorspieler Leistung</h3>';
		$OUT=$OUT.'<table width="100%">'.ArrayToTableHead($aTH).$ROWSV.'</table>';
		$OUT=$OUT.'<h3>Nachspieler Leistung</h3>';
		$OUT=$OUT.'<table width="100%">'.ArrayToTableHead($aTH).$ROWSN.'</table>';
	} # // end evsglstart flag //
	
	echo $OUT;
}

	/** @_showlegstatDetails
	 * shows breakdown detail results for the leg statistics in darts based games
	 * called by activating a player rank from the legstats  leggroupstats page
	 * displays a GRAPH(ajaxed) and the details below
	 */
function _showlegstatDetails($eventid,$pid,$mode=2){
	global $event,$dbi;
	/*
	 * att: the eventid can be wrong since this could be triggered from the group page
	 * make sure to get the correct event for a player of this group
	 */
	$privEventID=DB_getEventForPlayer($dbi,$pid,$event['evtypecode_id']);
	#debug($privEventID);
	switch($mode){
		case 1:
			$strcomp='='.$event['evsgldist'];$head='Gewonnene Legs';break;
		case 0:
			$strcomp='<'.$event['evsgldist'];$head='Verlorene Legs';break;
		case 2:
			$strcomp='>0';$head='Alle Legs';break;
	}
	$aTH=array('Bewerb','Vorname','Nachname','Datum','Darts','Finish','Rest','Average');
	$RS=DB_listLegStatAverageBreakdown($dbi,0,$privEventID,$strcomp,$event['evsgldist'],$pid);
	$fields=array(0,2,3,4,5,6,7,8);
	$ROWS=RecordsetToDataTable($RS,$fields);
	/*
	 * OUTPUT, we could change the header here to show a group header
	 * if(!$eventid==$privEventID){
	 * echo _MakeStatPageHeader($eventid,1);
	 * }
	 */
	echo _MakeStatPageHeader($eventid,0);
	echo '<h3>Graphische Darstellung - '.$head.'</h3>';
	echo '<script language="JavaScript" src="code/legdatagraph.js"></script>';
	echo '<div id="JG" style="position:relative;height:300px;width:700px"></div>';	
	
	echo '<h3>Detailierte Auflistung - '.$head.'</h3>';
	echo '<table width="100%">'.ArrayToTableHead($aTH).$ROWS.'</table>';
	echo "<script>window.onLoad=playerhist($eventid,$mode,$pid)</script>";
}

	/** @_showsetstat
	*	purpose:	Show Statistics for SETS
	* 	params:		event_id
	*	returns:	Page View with Listing of all players, 1 row per player	won/lost are colorcoded
	*/
function _showsetstat($eventid){
	global $dbi,$tdbg,$tdLost,$tdWon,$event;
	
	# echo _MakeStatPageHeader($eventid,0);
	# FIX for the styling bug
	echo '<table><tr><td><div style="width: 155px;"></div></td><td>';
	$ret = "";
	$ret=$ret.'<h2>Spieler Statistik '.$event['evname'].' Saison '.$event['evyear'].'</h2>';
	$ret=$ret.'<div id="topnav"><table width="100%" border="0"><tr>';
	$ret=$ret.'<td>'._button("Legs","","ls_stats.php?func=legstats&eventid=$eventid")."</td>";
	$ret=$ret.'<td>'._button("Sets","","ls_stats.php?func=setstats&eventid=$eventid")."</td>";
	$ret=$ret.'<td>'._button("3-Darts","","ls_stats.php?func=3darts&eventid=$eventid")."</td>";
	$ret=$ret.'<td>'._button("Legs Gesamte Liga Gruppe","","ls_stats.php?func=legstatsgroup&eventid=$eventid")."</td>";
	$ret=$ret.'<td>'._button("Siege","","ls_stats.php?func=wonloststats&eventid=$eventid")."</td>";
	$ret=$ret.'<td>'._button("Bestleistungen","","ls_stats.php?func=lowstats&eventid=$eventid")."</td>";
	$ret=$ret.'<td></tr></table></div><br>';
	echo $ret;
	
	# indicate red/green from event configuration singles ...
	$WonOUT=intval($event['evsgllegs']/2)+1;
	
	OpenTable();
	$precord=sql_query("select mdate,mround,lgid,sum(lscore) SCORE,sum(ldarts) DARTS,sum(lscore)/sum(ldarts) AVG,sum(lscore=".$event['evsgldist'].") LCOUNT,lpid,plname,pfname from tblmatch,tblgame,tblleg,tplayer where mkey=gmkey and gid=lgid and lpid=pid and mkey like 'e".$eventid."r%' and gtype=1 group by lpid,lgid order by plname,pid,mround,lgid asc",$dbi);
	$lastround=0;
	$lastpid=0;
	$gamecount=0;
	while(list($mdate,$mround,$lgameID,$Score,$Darts,$AVG,$OUT,$pid,$plname,$pfname)=sql_fetch_row($precord,$dbi)){
		if ($pid<>$lastpid) {
			# // NEW PLAYER NEW Tablerow + first DataSet
			if ($gamecount>0) echo '<td><b>'.number_format(($gameSUM/$gamecount),2,'.','').'</b></td>';
			$gamecount=0;
			$gameSUM=0;
			echo '<tr><td>'.$pfname.'</td><td>'.$plname.'</td><td></td>';
		}
		# // increase local counters ....
		$gamecount=$gamecount+1;
		$gameSUM=$gameSUM+$AVG;
		if ($OUT==$WonOUT) {
			echo '<td bgcolor="'.$tdWon.'">'.number_format($AVG,2,'.','').'</td>';
		} else {
			echo '<td bgcolor="'.$tdLost.'">'.number_format($AVG,2,'.','').'</td>';
		}
		$lastround=$mround;
		$lastpid=$pid;
	}
	
	# FIX for the styling bug
	echo '</td></tr></table>';
	
	CloseTable();
}

function _showWonLostStat($eventid) {
	// simple WON / LOST statistic per team - per player for an event
	// this is valid for all events , even without legdata ...
	// A ListAllPlayers
	// B RenderGraphRow per Player
	global $dbi,$tdbg,$tdLost,$tdWon,$event;
	
	echo _MakeStatPageHeader($eventid,0);
	# read event configuration singles ...and determine when a game is won ...
	$WonOUT=intval($event['evsgllegs']/2)+1;
	$RP=DB_listEventTeamPlayers($dbi,'',0,'',$eventid);
	// for each Player(7,8,9):
	$max=400;
	$factor=10;
	echo '<table width="100%">';
	foreach ($RP as $R){
		echo '<tr><td>'.$R[8].'</td><td>'.$R[9].'</td><td><table width="'.$max.'px" cellspacing=0><tr>';
		$RES=DB_retPlayerWonLostNumbers($dbi,$eventid,$R[7]);
		foreach($RES as $V){
			switch($V[3]){
				case 0: $color='#FF0000';break;
				case ($WonOUT-2): $color='#CC5500';break;
				case ($WonOUT-1): $color='#AAAA00';break;
				case $WonOUT: $color='#00FF00';break;
			}
			echo '<td width="'.$V[4]*$factor.'px" bgcolor="'.$color.'">'.$V[4].'</td>';
		}
		echo '</tr></table></td></tr>';
	}
	echo '</table>';
}

function _showVereinLeistung($eventid,$vvid){
# // average per dart / SET eines Vereines
# // select vid,vname,lpid,pfname,plname,lgid,sum(lscore),sum(ldarts),(sum(lscore)/sum(ldarts)) AVG from tblleg,tblgame,tplayer,tverein where lgid=gid and lpid=pid and pvid=vid and gtype=1 group by lpid,lgid order by pvid,pid,gid
global $dbi,$tdbg;
	
	echo _MakeStatPageHeader($eventid,0);
	
	OpenTable();
	echo "<p>.... work in progress ...</p>";
	CloseTable();
}

function _showfinishstat($eventid){
	global $dbi,$tdbg;
	
	echo _MakeStatPageHeader($eventid,0);
	$RS=DB_listFinishStat($dbi,0,$eventid);
	OpenTable();
	
	echo '<p>Die angezeigte Graphik verdeutlicht die H&auml;ufigkeit der erzielten Finishzahlen innerhalb der gew&auml;hlten Liga (Singles + Pairs). Ber&uuml;cksichtigt werden nat&uuml;rlich nur alle erfolgreichen Finish Versuche und nicht die Vergebenen.</p>';
	echo '<table cellpadding=0 cellspacing=1 bgcolor=#666666><tr><td bgcolor=white><table cellspacing=1 cellpadding=0><tr height=20px><td></td><td>H&auml;ufigkeit</td></tr>'
		.'<tr><td></td><td><!-- <img src="images/countbar.gif" border="0"> --></td></tr>';
	foreach ($RS as $F){
		# paint graph, strech factor = 4
		$xw=($F[1]*4);
		echo '<tr><td>'.$F[0].'</td><td width="500px" valign="bottom"><img src="images/red128.gif" height="10px" width="'.$xw.'px" border="0" alt="'.$F[1].'x erzielt"></td></tr>';
	}
	echo '</table></td></tr></table>';
	CloseTable();
}

function _showlowstat($eventid){
	global $dbi,$tdbg,$event;
	
	echo _MakeStatPageHeader($eventid,0);
	$sql="";
	if ($event['evsgldarts']==1){
		$legend='Die 20 besten gewonnenen Legs in den Singles (Darts)';
		$sql = "select pfname,plname,ldarts from tblleg,tblgame,tplayer where lpid=pid and lgid=gid and gmkey like 'e".$eventid."r%' and gtype=1 and lscore=".$event['evsgldist']." order by ldarts asc limit 20";
	} elseif ($event['evsglroundcheck']==1){
		$legend='Die 20 besten gewonnenen Legs in den Singles (Runden)';
		$sql = "select pfname,plname,lroundcheck from tbllegrounds,tblgame,tplayer where lpid=pid and lgid=gid and gmkey like 'e".$eventid."r%' and gtype=1 and lscore=".$event['evsgldist']." order by lroundcheck asc limit 20";
	} 
	OpenTable();
	echo '<tr><td width="50%" valign="top"><table width="100%">';
	if (strlen($sql)>0) {
		$precord=sql_query($sql,$dbi);
		$RS=createRecordSet($precord,$dbi);
		$ROWS=RecordsetToDataTable($RS,array(0,1,2));
		echo '<tr><td colspan=3 class="thead">'.$legend.'</td></tr>'.$ROWS;
	}
	####################################
	$sql='';
	echo '</table></td><td width="50%" valign="top"><table width="100%">';
	if ($event['evsgldarts']==1){
		$legend='Die 20 schlechtesten gewonnenen Legs in den Singles (Darts)';
		$sql="select pfname,plname,ldarts from tblleg,tblgame,tplayer where lpid=pid and lgid=gid and gmkey like 'e".$eventid."r%' and gtype=1 and lscore=".$event['evsgldist']." order by ldarts desc limit 20";
	}
	if ($event['evsglroundcheck']==1){
		$legend='Die 20 schlechtesten gewonnenen Legs in den Singles (Runden)';
		$sql = "select pfname,plname,lroundcheck from tbllegrounds,tblgame,tplayer where lpid=pid and lgid=gid and gmkey like 'e".$eventid."r%' and gtype=1 and lscore=".$event['evsgldist']." order by lroundcheck desc limit 20";
	}
	if (strlen($sql)>0) {
		$precord = sql_query($sql,$dbi);
		$RS=createRecordSet($precord,$dbi);
		$ROWS=RecordsetToDataTable($RS,array(0,1,2));
		echo '<tr><td colspan=3 class="thead">'.$legend.'</td></tr>'.$ROWS;
	}
	#####################################
	$sql="";
	$LIM=20;
	echo '</table></td></tr>';
	echo '<tr><td width="50%"><table width="100%">';
	if ($event['evsgldarts']==1){
		$legend='Die '.$LIM.' besten Sets in den Singles (Score per Dart)';
		$sql="select pid,pfname,plname,mdate,sum(lscore)/sum(ldarts) AVG from tblmatch,tblgame,tblleg,tplayer where mkey=gmkey and gid=lgid and lpid=pid and mkey like 'e".$eventid."r%' and gtype=1 group by lpid,lgid order by AVG desc limit $LIM";
	}
	if ($event['evsglroundcheck']==1){
		$legend='Die '.$LIM.' besten Sets in den Singles (Score per Round)';
		$sql="select pid,pfname,plname,mdate,sum(lscore)/sum(lroundcheck) AVG from tblmatch,tblgame,tbllegrounds,tplayer where mkey=gmkey and gid=lgid and lpid=pid and mkey like 'e".$eventid."r%' and gtype=1 group by lpid,lgid order by AVG desc limit $LIM";
	}
	if (strlen($sql)>0) {
		$precord = sql_query($sql,$dbi);
		$RS=createRecordSet($precord,$dbi);
		$ROWS=RecordsetToDataTable($RS,array(1,2,3,4));
		echo '<tr><td colspan=4 class="thead">'.$legend.'</td></tr>'.$ROWS;
	}	
	echo '</table></td><td width="50%"><table width="100%">';
	$sql='';
	$LIM=20;
	if ($event['evsgldarts']==1){
		$legend='Die '.$LIM.' schlechtesten Sets in den Singles (Score per Dart)';
		$sql="select pid,pfname,plname,mdate,sum(lscore)/sum(ldarts) AVG from tblmatch,tblgame,tblleg,tplayer where mkey=gmkey and gid=lgid and lpid=pid and mkey like 'e".$eventid."r%' and gtype=1 group by lpid,lgid order by AVG asc limit $LIM";
	}
	if ($event['evsglroundcheck']==1){
		$legend='Die '.$LIM.' schlechtesten Sets in den Singles (Score per Round)';
		$sql="select pid,pfname,plname,mdate,sum(lscore)/sum(lroundcheck) AVG from tblmatch,tblgame,tbllegrounds,tplayer where mkey=gmkey and gid=lgid and lpid=pid and mkey like 'e".$eventid."r%' and gtype=1 group by lpid,lgid order by AVG asc limit $LIM";
	}
	if (strlen($sql)>0) {
		$precord = sql_query($sql,$dbi);
		$RS=createRecordSet($precord,$dbi);
		$ROWS=RecordsetToDataTable($RS,array(1,2,3,4));
		echo '<tr><td colspan=4 class="thead">'.$legend.'</td></tr>'.$ROWS;
	}
	$sql='';
	################################
	echo '</table></td></tr>';
	echo '<tr><td width="50%" valign="top"><table width="100%">';
	# // do we have to show finishstats ???
	if ($event['evsglfinish']==1) {
		$legend='Die 20 h&ouml;chsten in den Singles erzielten Finishzahlen';
		$precord = sql_query("select lpid,pfname,plname,lfinish from tblleg,tblgame,tplayer where lpid=pid and lgid=gid and gmkey like 'e".$eventid."r%' and gtype=1 and lfinish>0 order by lfinish desc limit 20",$dbi);
		$RS=createRecordSet($precord,$dbi);
		$ROWS=RecordsetToDataTable($RS,array(1,2,3));
		echo '<tr><td colspan=4 class="thead">'.$legend.'</td></tr>'.$ROWS;
	}
	
	echo '</table></td><td width="50%" valign="top"><table width="100%">';
	
	# // how about the HIGHSCORES ???
	if ($event['evsglhighscore']==1) {
		$legend='Anzahl erzielter Highscores';
		$precord = sql_query("select lpid,pfname,plname,sum(lhighscore) HS from tblleg,tblgame,tplayer where lpid=pid and lgid=gid and gmkey like 'e".$eventid."r%' and gtype=1 and lhighscore>0 group by lpid order by HS desc",$dbi);
		$RS=createRecordSet($precord,$dbi);
		$ROWS=RecordsetToDataTable($RS,array(1,2,3));
		echo '<tr><td colspan=4 class="thead">'.$legend.'</td></tr>'.$ROWS;
	}
	echo '</table></td></tr>';
	CloseTable();
}

/**
 * Display Statistic for the Score Performance for ROUNDBASED LegData
 *
 * @param int $eventid
 */
function _showScoreStatisticRounds($eventid){
	# shows the average of the scoreperformance (501 -159), average on all LEGS / STDDEV / + Legcount
	
	global $dbi,$event;
	
	$urltarget='ls_stats.php?func=ligaindexdetail&eventid='.$eventid.'&pid=%P1%';
	$aTH=array('Vorname','Nachname','Mittelwert','Abweichung','Anzahl');
	$HEAD=ArrayToTableHead($aTH);
	$RS=DB_listRoundAverage($dbi,0,$eventid,'='.$event['evsgldist'],0,'score');
	$ROWSW=RecordsetToClickTable($RS,1,$urltarget,0);
	$RS=DB_listRoundAverage($dbi,0,$eventid,'<'.$event['evsgldist'],0,'score');
	$ROWSL=RecordsetToClickTable($RS,1,$urltarget,0);

	// ==== OUTPUT ====
	echo _MakeStatPageHeader($eventid,0);
	echo '<h3>Gewonnene Legs</h3>';
	echo '<table width="100%">'.$HEAD.$ROWSW.'</table>';
	echo '<h3>Verlorene Legs</h3>';
	echo '<table width="100%">'.$HEAD.$ROWSL.'</table>';
}

/**
 * Display check round statistic for a specifi event, order by statval
 *
 * @param int $eventid
 */
function _showCheckStatisticRounds($eventid){
	#
	# v3 removed vereinsname because sometimes there is no match for feda players ...
	# v4 replaced by API_rs, removed 2 queries
	#
	global $dbi,$tdbg,$event;
	
	$urltarget='ls_stats.php?func=ligaindexdetail&eventid='.$eventid.'&pid=%P1%';
	$aTH=array('Vorname','Nachname','Mittelwert','Abweichung','Anzahl');
	$HEAD=ArrayToTableHead($aTH);
	$RS=DB_listRoundAverage($dbi,0,$eventid,'='.$event['evsgldist'],0,'check');
	$ROWSW=RecordsetToClickTable($RS,1,$urltarget,0);
	$RS=DB_listRoundAverage($dbi,0,$eventid,'>('.$event['evsgldist'].'-158) and L.lscore<>'.$event['evsgldist'].' AND L.lroundcheck>L.lroundscore',0,'check');
	$ROWSL=RecordsetToClickTable($RS,1,$urltarget,0);
	// ==== OUTPUT ====
	
	echo _MakeStatPageHeader($eventid,0);
	echo '<h3>Gewonnene Legs</h3>';
	echo '<table width="100%">'.$HEAD.$ROWSW.'</table>';
	echo '<h3>Verlorene Legs mit einer Chance innerhalb der Checkzone</h3>';
	echo '<table width="100%">'.$HEAD.$ROWSL.'</table>';
	
}

function _showLIGAIndex($eventid){
	#
	# show index listing for a specific league
	# from start till indexdate
	#
	# a -> get all players this league
	# b for every player get all legs - calc index - return score - check - gesamt
	# v2.1 bugfix - included event into query
	# v3 removed the indexdate - just include all games and legs from this event
	#TODO use the DB layer here ...
	
	global $dbi,$tdbg,$event;
	
	$evstatcode=$event['evstatcode_id'];
	$playerqry="select pid,pfkey1,pfname,plname,T.id,T.tname from tplayer,tblteamplayer,tblteam T where pid=lplayerid and lteamid=T.id and leventid=$eventid order by T.tname,plname";
	$precord = sql_query($playerqry,$dbi);
	$aTH=array('&Ouml;DSO','Vorname','Nachname','Team','Score','Check','Gesamt','Legs');
	
	$aRows=array();
	
	while(list($pid,$pfkey1,$pfname,$plname,$tid,$tname)=sql_fetch_row($precord,$dbi)){
		#
		# get legs per player - calc - and sum up
		#
		$legqry="select lid,lroundscore,lscore,lroundcheck,gid,gmkey,mround,mdate from tbllegrounds,tblgame,tblmatch where lpid = $pid and lgid=gid and gmkey=mkey and gmkey like 'e".$eventid."%' and lroundscore>0 order by mdate asc,lid asc";
		$Lrecord = sql_query($legqry,$dbi);
		$sumScore=0;
		$sumCheck=0;
		$CountScore=0;
		$CountCheck=0;
		$scoreindex=0;
		$checkindex=0;
		while(list($lid,$lroundscore,$lscore,$lroundcheck,$gid,$gmkey,$mround,$mdate)=sql_fetch_row($Lrecord,$dbi)){
			$idx="";
			#TODO v5 replace by using the ORM object
			$idx=retFEDAIndexZahlperLeg($event['evsgldist'],$lroundscore,($event['evsgldist']-$lscore),$lroundcheck,0);
			list($a,$b,$c)=split(":",$idx);
			# values of -1 indicate failure
			if ($a > -1) {
				$sumScore=$sumScore+$a;
				$CountScore=$CountScore+1;
				}
			if ($b > -1) {
				$sumCheck=$sumCheck+$b;
				$CountCheck=$CountCheck+1;
				}
		} // END while legs
		if ($CountScore>0) $scoreindex=($sumScore/$CountScore);
		if ($CountCheck>0) $checkindex=($sumCheck/$CountCheck);
		$aRec=array($eventid,$pid,$pfkey1,$pfname,$plname,$tname,number_format($scoreindex,2,'.',''),number_format($checkindex,2,'.',''),number_format(($scoreindex+$checkindex),2,'.',''),$CountScore);
		$aRows[]=$aRec;
	} // END WHILE Player
	$target='ls_stats.php?func=ligaindexdetail&eventid=%P1%&pid=%P2%';
	/*
	 * resort to reflect a ranking !!
	 */
	foreach ($aRows as $key => $row) {
		$cEVENTID[$key]  = $row[0];
    	$cGESAMT[$key] = $row[8];
    	$cLEGS[$key] = $row[9];
	}
	array_multisort($cGESAMT, SORT_DESC,$aRows);
	/*
	 * OUTPUT LigaRanking sorted
	 */
	echo _MakeStatPageHeader($eventid,0);
	echo '<h3>Aktuelle LIGA Werteliste erstellt NUR aus den Spielen der '.$event['evname'].'</h3>';
	echo '<p>Im Gegensatz zur eigentlichen Rangliste werden hier nur Spiele ber&uuml;cksichtigt welche in der '.$event['evname'].' '.$event['evyear'].' absolviert wurden.</p>';
	echo OpenTable('Ligaidx',1);
	echo ArrayToTableHead($aTH);
	echo RecordsetToClickTable($aRows,2,$target,0,1);
	echo CloseTable(1);
}

function _showStatisticList($eventid,$statdate=''){
	# 
	# connect to the stats table and retrieve the indicated ranking for this event.statcode=statcode
	#
	/*
	 * v3 changed the query, now 1 query is enough, we retrieve all players from the static stats list and 
	 * show them with either their actual or their last team.
	 * v4 modified to DB - View modell
	 */

	global $dbi,$tdbg,$event;
	echo _MakeStatPageHeader($eventid,0);
	if (strlen($statdate)<5) {$indexdate=$fedadefaultdate;}
	$evstatcode=$event['evstatcode_id'];
	echo "<h3>Aktuelle FEDA Rangliste berechnet am $statdate</h3>";
	echo "<form action=\"ls_stats.php?func=statlist&amp;eventid=$eventid\" method=\"post\">";
	echo "<table><tr><td colspan=2>Hier kannst du einen Ranglisten Stichtag w&auml;hlen. Es wir dann die Gesamt Rangliste des gew&auml;hlten Zeitpunktes angezeigt.";
	echo "<br/>Durch Anklicken eines gew&uuml;nschten Spielers bekommst du eine detailierte Aufstellung <b>aller</b> gespielten und gewerteten Legs der gew&auml;hlten Ranglistenperiode.</td></tr>";
	echo '<tr><td>'.Select_StatDate($evstatcode,$statdate).'</td><td>'._button("Zeige Rangliste f&uuml;r diesen Stichtag").'</td></tr>';
	echo "</table></form>";
	
	/* 
	 * from tblstat show all Players with a ranking values on DATE = $statdate
	 * display with current or last team, make row clickable to show detailed leg list
	*/
	
	$RS=DB_retTStatArray($evstatcode,$statdate);
	# statval,countgames,countlegs,player_id,pfname,plname,Teamname,Eventname
	$target="ls_stats.php?func=statlistdetail&eventid=$eventid&vindexdate=$statdate&pid=%P1%";
	$aTH=array("Ranking","Sets","Legs","Vorname","Nachname","Team","Liga","Saison");
	$fields=array(1,2,3,5,6,7,8,9);
	OpenTable();
	echo ArrayToTableHead($aTH);
	echo RecordsetToClickTable($RS,1,$target,4);
	CloseTable();
}

/**
*	purpose	show a personal drill down listing ALL legs for a period for a specific person and a specific league
*	params		event, player
*	returns		HTML Block (H3 + graphics + table)
*/
function _showLIGAIndexBreakdown($eventid,$pid){
	
	global $dbi,$tdbg,$event;
	echo _MakeStatPageHeader($eventid,0);
	
	$playerqry="select pid,pfname,plname,T.id,T.tname from tplayer,tblteamplayer,tblteam T where pid=lplayerid and lteamid=T.id and leventid=$eventid and pid=$pid";
	$precord = sql_query($playerqry,$dbi);
	$aPLAYER=sql_fetch_array($precord,$dbi);
	$statcode=$event['evstatcode_id'];
	$aTH=array("Datum","Runde","Scorerunde","Rest","Checkrunde","Scorezahl","Checkzahl");
	$legqry="select lid,lroundscore,lscore,lroundcheck,gid,gmkey,mround,mdate"
			." FROM tbllegrounds,tblgame,tblmatch"
			." WHERE lpid = $pid and lgid=gid and gmkey=mkey and gmkey like 'e".$eventid."%' and lroundscore>0"
			." ORDER by mdate asc,lid asc";
	
	echo "<h3>Auflistung aller Spiele / Legs ".$aPLAYER['pfname']." ".$aPLAYER['plname']." in ".$event['evname']."</h3>";
	echo "<script language=\"JavaScript\" src=\"code/legdatagraph.js\"></script>";
	echo "<div id=\"JG\" style=\"position:relative;height:300px;width:700px\"></div>";
	# ne need here to pass any date constraints - we take the current league instead.
	
	OpenTable('tdetail');
	#
	# get legs per player - calc - and sum up
	#		
		$Lrecord = sql_query($legqry,$dbi);
		echo ArrayToTableHead($aTH);
		while(list($lid,$lroundscore,$lscore,$lroundcheck,$gid,$gmkey,$mround,$mdate)=sql_fetch_row($Lrecord,$dbi)){
			$idx="";
			$idx=retFEDAIndexZahlperLeg($event['evsgldist'],$lroundscore,($event['evsgldist']-$lscore),$lroundcheck);
			list($a,$b,$c)=split(":",$idx);
			echo "<tr><td>$mdate</td><td>$mround</td><td>$lroundscore</td><td>".($event['evsgldist']-$lscore)."</td><td>$lroundcheck</td><td>$a</td><td>$b</td></tr>";
		}
	CloseTable();
	echo "<script>window.onLoad=perfgraph($pid,0,$eventid,'','')</script>";
}

/**
*	purpose:	show a personal detail listing ALL legs for a period of 1 year 
* 						for the FEDA Stat  for the passed event
* 	params:	$eventid,$indexdate,$pid
*	returns:	PageView: js-graph + detail listing
*/
function _showPersonStatListeDetail($eventid,$indexdate='',$pid=0){
	global $dbi,$tdbg,$event,$fedadefaultdate;
	
	if ($pid==0) return;
	
	if (strlen($indexdate)<5) {$indexdate=$fedadefaultdate;}
	$evstatcode=$event['evstatcode_id'];
	$fromdate = fnc_date_calc($indexdate,-365);
	
	# // we use a very general person query here ...
	$playerqry="select pid,pfname,plname from tplayer where pid=$pid";
	$precord = sql_query($playerqry,$dbi);
	$aPLAYER=sql_fetch_array($precord,$dbi);
	$aTH=array("Datum","Runde","Scorerunde","Rest","Checkrunde","Scorezahl","Checkzahl");
	
	echo _MakeStatPageHeader($eventid,$evstatcode);
	echo "<h3>Auflistung aller Spiele / Legs ".$aPLAYER["pfname"]." ".$aPLAYER["plname"]." von $fromdate bis $indexdate</h3>";
	
	# GRAPH ###############
	echo "<script language=\"JavaScript\" src=\"code/legdatagraph.js\"></script>";
	echo "<div id=\"JG\" style=\"position:relative;height:300px;width:700px\"></div>";
	# we need here to pass date constraints - dont pass event-id !!
	echo "<script>window.onLoad=perfgraph($pid,$evstatcode,0,'$fromdate','$indexdate')</script>";
	# GRAPH ###############
	OpenTable();
	#
	# get legs per player - calc - and sum up
	#	
	####################
	# Legacy Data from pre league system -> tbllegx
	####################
	$legqry="select lxid,lxpcnum,lxdate,lxrscore,lxrest,lxrcheck from tbllegx where lxpid=$pid and lxevlist=$evstatcode and lxdate<'$indexdate' and lxdate>'$fromdate' and lxrscore>0 order by lxdate asc";
		$Lrecord = sql_query($legqry,$dbi);
		echo ArrayToTableHead($aTH);
		while(list($lxid,$lxpcnum,$lxdate,$lxrscore,$lxrest,$lxrcheck)=sql_fetch_row($Lrecord,$dbi)){
			$idx="";
			$idx=retFEDAIndexZahlperLeg($event['evsgldist'],$lxrscore,$lxrest,$lxrcheck);
			list($a,$b,$c)=split(":",$idx);
			echo "<tr><td>$lxdate</td><td>n/a</td><td>$lxrscore</td><td>$lxrest</td><td>$lxrcheck</td><td>$a</td><td>$b</td></tr>";
		}
	####################
	# official League system Data
	####################
		$legqry="select lid,lroundscore,lscore,lroundcheck,gid,gmkey,mround,mdate from tbllegrounds,tblgame,tblmatch,tblevent E where lpid = $pid and lgid=gid and gmkey=mkey and mevid=E.id and E.evstatcode_id=$evstatcode and mdate<'$indexdate' and mdate>'$fromdate' and lroundscore>0 order by mdate asc,lid asc";
		$Lrecord = sql_query($legqry,$dbi);
		echo ArrayToTableHead($aTH);
		while(list($lid,$lroundscore,$lscore,$lroundcheck,$gid,$gmkey,$mround,$mdate)=sql_fetch_row($Lrecord,$dbi)){
			$idx="";
			$idx=retFEDAIndexZahlperLeg($event['evsgldist'],$lroundscore,($event['evsgldist']-$lscore),$lroundcheck);
			list($a,$b,$c)=split(":",$idx);
			echo "<tr><td>$mdate</td><td>$mround</td><td>$lroundscore</td><td>".($event['evsgldist']-$lscore)."</td><td>$lroundcheck</td><td>$a</td><td>$b</td></tr>";
		}
	CloseTable();

}


/**
*	purpose:	show form as a testbed for the FEDA Index calculation
* 	params:		all params for FEDACalc
*	returns:	PAGE View
*/
function _showFEDACalculationForm($eventid,$vdistance=501,$vroundscore=0,$vrest=0,$vroundcheck=0,$fedaIDX=''){
	echo _MakeStatPageHeader($eventid,0);
	
	echo '<h3>FEDA Ranglisten Berechnung</h3>';
	echo '<p>Setze deine Werte f&uuml;r ein Leg ein - dr&uuml;cke die Schaltfl&auml;che und du bekommst den berechneten FEDA Index f&uuml;r dieses eine Leg angezeigt.<br>Ein Wert von -1 bedeutet dass f&uuml;r dieses Leg kein ScoreIndex/CheckIndex berechnet wird. </p>';
	
	OpenTable();
	
	echo "<form action=\"ls_stats.php?func=calcfedaindex&amp;eventid=$eventid\" method=\"post\">"
	."<tr><td width=25%>Distanz</td><td width=25%>Scorerunde &lt; 159</td><td width=25%>Rest</td><td width=25%>Checkrunde</td></tr>"
	."<tr><td><select name=\"vdistance\" size=1><option>501</option></select></td><td>"._input(1,"vroundscore",$vroundscore,3,3)."</td><td>"._input(1,"vrest",$vrest,3,3)."</td><td>"._input(1,"vroundcheck",$vroundcheck,3,3)."</td></tr>"
	."<tr><td>"._button("Rechnen")."</td></tr>"
	."</form>";
	
	echo "<tr><td colspan=2><b>Scorezahl</b></td><td colspan=2><b>Checkzahl</b></td></tr>";
	list($a,$b,$c)=split(":",$fedaIDX);
	echo "<tr><td colspan=2><b>$a</b></td><td colspan=2><b>$b</b></td></tr>";
	
	CloseTable();
}

/**
*	purpose:	Show a Liga Ranking based on the evstatcode_id
* 				either for league or entire leage-group
* 	params:		eventid ?, evgroup_flag
*	returns:	HTML Table Page with klick-rows for breakdown records
* #TODO groupflag not working correctly for BDSO
*/
function _showStatCodeListForEvent($event_id,$event_group_flag=0){
	
	global $dbi,$event;
	echo _MakeStatPageHeader($event_id,0);
	#debug($eventid.":".$event_group);
	if ($event['evstatcode_id']==9){
		
		// show the LOSAN Leg INDEX stuff from the BDSO
		// there is no $event_group_flag for the major league ...
			$THEAD='<h3>Aktueller LegIndex f&uuml;r '.$event['evname'].'</h3>';
			$RS1=DB_retStatQueryArray($dbi,'',$event,1);
			$RS2=array();
			$aTH=array('Vorname','Nachname','Legs+','Sets+','Matches','Leg Quote');
			$target='ls_stats.php?func=statcodelistdetail&eventid='.$event_id.'&pid=%P1%';
			
	} elseif ($event['evstatcode_id']==16){
		
		if ($event_group_flag>0) {
			$THEAD='<h3>Aktuelle BDSO Rangliste &uuml;ber alle Ligen</h3>';
			$RS1x=DB_retStatQueryArray($dbi,'H',$event,1,1);
			$RS2x=DB_retStatQueryArray($dbi,'D',$event,1,1);
		} else {
			$THEAD='<h3>Aktuelle BDSO Rangliste '.$event['evname'].'</h3>';
			$RS1x=DB_retStatQueryArray($dbi,'H',$event,1);
			$RS2x=DB_retStatQueryArray($dbi,'D',$event,1);
		}
		/*
		 * re-write the resulting array adding the point values into the last column.
		 */
		$RS1=array();
		foreach ($RS1x as $aPlayerRec){
			$points=lsdb_stat_ReturnGamePointsForPlayer($dbi,$event,$aPlayerRec[0],0);
			$aPlayerRec[]=$points;
			$RS1[]=$aPlayerRec;
		}
		$RS2=array();
		foreach ($RS2x as $aPlayerRec){
			$points=lsdb_stat_ReturnGamePointsForPlayer($dbi,$event,$aPlayerRec[0],0);
			$aPlayerRec[]=$points;
			$RS2[]=$aPlayerRec;
		}
		/*
		 * we need to SORT the RS arrays , fields are
		 * pid,pfname,plname,legs,sets,games,quote,points
		 * snippet below is straight from php.net documentation
		 */
		foreach ($RS1 as $key => $row) {
//	    	$cPID[$key]  = $row[0];
//	    	$cPFNAME[$key] = $row[1];
//	    	$cPLNAME[$key] = $row[2];
//	    	$cLEGS[$key] = $row[3];
//	    	$cSETS[$key] = $row[4];
	    	$cGAMES[$key] = $row[5];
//	    	$cQUOTE[$key] = $row[6];
	    	$cPOINTS[$key] = $row[7];
		}
		array_multisort($cPOINTS, SORT_DESC, $cGAMES, SORT_ASC, $RS1);
		foreach ($RS2 as $key => $row) {
//	    	$cPID[$key]  = $row[0];
//	    	$cPFNAME[$key] = $row[1];
//	    	$cPLNAME[$key] = $row[2];
//	    	$cLEGS[$key] = $row[3];
//	    	$cSETS[$key] = $row[4];
	    	$cfGAMES[$key] = $row[5];
//	    	$cQUOTE[$key] = $row[6];
	    	$cfPOINTS[$key] = $row[7];
		}
		array_multisort($cfPOINTS, SORT_DESC, $cfGAMES, SORT_ASC, $RS2);
		
		$target='ls_stats.php?func=statcodelistdetail&eventid='.$event_id.'&pid=%P1%';
		$aTH=array('Vorname','Nachname','Legs+','Sets+','Matches','Leg Quote','BDSO Punkte');
		
	} elseif ($event['evstatcode_id']==17){
		
		if ($event_group_flag>0) {
			$THEAD='<h3>Aktuelle NOEDSV Rangliste &uuml;ber alle Ligen</h3>';
			$RS1=DB_retStatQueryArray($dbi,'',$event,1,1);
		} else {
			$THEAD='<h3>Aktuelle NOEDSV Rangliste '.$event['evname'].'</h3>';
			$RS1=DB_retStatQueryArray($dbi,'',$event);
		}
		$target='ls_stats.php?func=statcodelistdetail&eventid='.$event_id.'&pid=%P1%';
		$aTH=array('Vorname','Nachname','Legs+','Sets+','Matches','Leg Quote');
	}
	/*
	 * Output starts here
	 */
	if (!isset($THEAD)) return;
	echo $THEAD;
	OpenTable();
	if (sizeof($RS1)>0) {echo ArrayToTableHead($aTH);echo RecordsetToClickTable($RS1,0,$target,0);}
	if (sizeof($RS2)>0) {echo ArrayToTableHead($aTH); echo RecordsetToClickTable($RS2,0,$target,0);}
	CloseTable();
	
}

/**
*	purpose	generate a detailed statcodelist breakdown per Player for a specific event
*	params		eventid,playerid
*	returns		HTML Table page
*/
function _showStatCodeListForEventDetail($player_id){
	global $event,$dbi;
	
	$aTH=array('Vorname','Nachname','Runde','Datum','Legs+','Sets+','Matches+','Punkte');
	$target='ls_system.php?func=showmatch&vmkey=%P1%&eventid='.$event['id'];
	/* 
	 * supported for different type of statcodelists (9,13,16,17)
	 *	the default is to return a detailed game list without any points or calculations
	 * if special calcs are needed use the switch clause on stat_code
	*/ 
	switch($event['evstatcode_id']){
		case 16:	// BDSO Punkte 7,6,3,0
							// retrieve all games -> for each game get game data
			$RS=lsdb_stat_ReturnGamePointsForPlayer($dbi,$event,$player_id,1);
			break;
		default:
			$RS=DB_retStatQueryArrayDetail($dbi,$player_id,$event);
	}
	// OUTPUT
	$OUT=_MakeStatPageHeader($event['id'],0); 
	$OUT=$OUT.'<h3>Detailed List</h3>';
	$OUT=$OUT.OpenTable('statlistdetail',1);
	$OUT=$OUT.ArrayToTableHead($aTH);
	// skip fields: pid,gid
	$OUT=$OUT.RecordsetToClickTable($RS,2,$target,4);
	$OUT=$OUT.CloseTable(1);
	echo $OUT;
}

function _blank($eventid){
	
	echo _MakeStatPageHeader($eventid,0);
	OpenTable();
	echo '<h3>Ein Informationsservice des Dartsverband</h3>';
	echo "<p>Auf diesen Statistik Seiten findet Ihr alles Wissenswerte ber die Leistungen der einzelnen Spieler, eine mengenm&auml;ssige Aufschl&uuml;sselung der erzielten Finishzahlen sowie selektierte Bestleistungen.</p>";
	echo "<p>Der eigentliche Statistikbereich ist gegliedert in mehrere Formen der Auswertung - je nach Erfassungsgrad der Ligen ist die eine oder die andere Auswertung sichtbar oder verf&uuml;gbar<ul>"
."<li><b>Darts</b>: Hier wird der absolute Scoreschnitt pro Dart berechnet. Dies ist die f&uuml; den Spieler nachteiligste Form der Berechnung da viele schlechte Darts in einem schlechten Leg eine h&ouml;here Gewichtung haben als wenige gute in einem gutem Leg. Dieser Wert w&uuml;rde sich theoretisch mit jedem geworfenen Dart &auml;ndern. Die Berechnung erfolgt durch simple Division der gesamten erzielten Punkteleistung aller Legs durch die Anzahl aller geworfenen Darts.</li>"
."<li><b>Legs:</b> Die im allgemeinen &uuml;bliche Form der Auswertung. Hier wird zuerst der Scoreaverage per Dart pro Leg ausgerechnet und anschliessend von diesen Legwerten der Mittelwert bestimmt. Dieser Wert kann sich nur &auml;ndern sobald ein Leg abgeschlossen wurde. Als Besonderheit wird noch die mittlere Abweichung vom errechneten Mittelwert angegeben, dieser dient als Masszahl f&uuml;r die Streuung der Legperformances. Eine hohe Abweichung bedeutet Genie und Wahnsinn, eine geringe Abweichung steht f&uuml;r ein stabiles und durchg&auml;ngiges Scoreverhalten in allen Legs.</li>"
."<li><b>Sets:</b> Hier wird der Mittelwert f&uuml;r das gesamte gespielte Set ausgerechnet (also 3,4 oder 5 Legs) indem der gesamte erzielte Score durch die Gesamtanzahl an Darts geteilt wird. Diese Setwerte werden dann chronologisch angezeigt. Der gezeigte Wert ist somit ein Indikator f&uuml;r die zu erwartende Spielperformance in einem Set Best of Five, sowie f&uuml;r den Formverlauf des Spielers.</li>"
."<li><b>3-Dart:</b> Diese, f&uuml;r Steeldartspieler eher un&uuml;bliche, Form der Auswertung zeigt die Performance Werte in Runden zu je 3 Darts. Ausgewertet wird die Leistung in gewonnenen Legs sowie eine Hochrechnung der Loser-Performance.</li>"
."<li><b>Legs Liga Gesamt</b> Der Legaverage wie weiter oben beschrieben, doch diesmal &uuml;ber eine gesamte zusammenh&auml;ngende Ligagruppe berechnet. (Eine logische Ligagruppe besteht zB aus Ober,Mittel und Unterliga oder aus x-Divisionen.</li>"
."<li><b>Leg Quote:</b> Eine einfache Berechnung �ber die Effektivit&auml;t eines Spielers. Die Summe alle gewonnenen Legs innerhalb einer Liga wird durch die Anzahl der gespielten Games dividiert. Bei einer Best of 3 ist also 2 der Maximalwert, bei einer Best of 5 ist 3 der Maximalwert.</li>" 
."<li><b>Siege:</b> Einfache Gegen&uuml;berstellung von gewonnenen zu verlorenen Spielen innerhalb einer Liga. Hier gibts die Summen und Prozentwerte.</li>"
."<li><b>Finish:</b> Sofern am Spielbericht erfasst werden hier ALLE erzielten Finishzahlen der Ligaspiele dargestellt. Dies erm&ouml;glicht eine gute Einsch&auml;tzung der Finishst&auml;rke einer Liga oder Division.</li>"
."</ul></p>";
	CloseTable();
}

/**
 * PARAM checking on obvious params ...
 */



if (isset($_REQUEST['func'])&& $_REQUEST['func']<>"undefined") {$myfunc=strip_tags($_REQUEST['func']);}else{$myfunc='NULL';};
// event_id is set by ls_main 
if (isset($_REQUEST['tid']) && intval($_REQUEST['tid'])>0) {$t_id=strip_tags($_REQUEST['tid']);}else{$t_id=0;};
if (isset($_REQUEST['pid']) && intval($_REQUEST['pid'])>0) {$p_id=strip_tags($_REQUEST['pid']);}else{$p_id=0;};
if (isset($_REQUEST['event_group']) && intval($_REQUEST['event_group'])>0) {$event_group_id=strip_tags($_REQUEST['event_group']);}else{$event_group_id=0;};
if (isset($_REQUEST['vindexdate'])&& $_REQUEST['vindexdate']<>"undefined") {$stat_date=strip_tags($_REQUEST['vindexdate']);}else{$stat_date='NULL';};
if (isset($_REQUEST['mode'])&& $_REQUEST['mode']<>"undefined") {$detail_mode=strip_tags($_REQUEST['mode']);}else{$detail_mode='all';};
// calc values
if (isset($_REQUEST['vdistance']) && intval($_REQUEST['vdistance'])>0) {$v_distance=strip_tags($_REQUEST['vdistance']);}else{$v_distance=501;};
if (isset($_REQUEST['vroundscore']) && intval($_REQUEST['vroundscore'])>0) {$v_roundscore=strip_tags($_REQUEST['vroundscore']);}else{$v_roundscore=0;};
if (isset($_REQUEST['vrest']) && intval($_REQUEST['vrest'])>0) {$v_rest=strip_tags($_REQUEST['vrest']);}else{$v_rest=0;};
if (isset($_REQUEST['vroundcheck']) && intval($_REQUEST['vroundcheck'])>0) {$v_roundcheck=strip_tags($_REQUEST['vroundcheck']);}else{$v_roundcheck=0;};

switch($myfunc) {

	default:
		_blank($event_id);
		break;
	
	case 'dartstats':
		_showdartstat($event_id,$event_group_id);
		break;
	
	case 'legstats':
		_showlegstat($event_id,0);
		break;
		
	case 'legstatsgroup':
		_showlegstat($event_id,1);
		break;
		
	case 'legstatdetail':
		_showlegstatDetails($event_id,$p_id,$detail_mode);
		break;
	
	case 'setstats':
		_showsetstat($event_id);
		break;
	
	case "wonloststats":
		_showWonLostStat($event_id);
		break;
	
	case "finishstats":
		_showfinishstat($event_id);
		break;
	
	case "lowstats":
		_showlowstat($event_id);
		break;
	
	case "rscorestat":
		_showScoreStatisticRounds($event_id);
		break;

	case "rcheckstat":
		_showCheckStatisticRounds($event_id);
		break;
	
	case "3darts":
		_show3dartroundaverage($event_id);
		break;
	
	#case "showresteam";
	#	_showteamresultdetails($event_id,$t_id);
	#	break;

	case "calcfedaindex";
		$fedaIDX=retFEDAIndexZahlperLeg($v_distance,$v_roundscore,$v_rest,$v_roundcheck,0);
		_showFEDACalculationForm($event_id,$v_distance,$v_roundscore,$v_rest,$v_roundcheck,$fedaIDX);
		break;
	
	case "ligaindex":
		_showLIGAIndex($event_id);
		break;
	case "ligaindexdetail":
		_showLIGAIndexBreakdown($event_id,$p_id);
		break;
	
	case "statcodelist":
		_showStatCodeListForEvent($event_id,$event_group_id);
		break;
	case "statcodelistdetail":
		_showStatCodeListForEventDetail($p_id);
		break;		
	
	case "statlist":
		_showStatisticList($event_id,$stat_date);
		break;
	case "statlistdetail":
		_showPersonStatListeDetail($event_id,$stat_date,$p_id);
		break;
	
}


echo '</div>';
LS_page_end();

?>
