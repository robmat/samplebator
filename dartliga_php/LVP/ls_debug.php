<?php

/*
 * Controlling modul for the LIGA SYSTEM
 * Access ONLY for user.type = LigaSysAdmin or higher ... 
 * old stuff was LS_LEVEL must be 3 ...
 * There is no global EVENT or EVENTID any longer 
 */

#TODO add common DIV Layout ...

include("empty_main.php");
include("func_stat.php");

// old access mode ...
if (sizeof($usertoken['eventmap'])<1) die_red('Err16:NotAllowed');

# // Beginn Funktionen ----------------
$tdbg="#CCDDCC";
$tdWon="#ccffcc";
$tdLost="#ffcccc";

function _blank() {
	echo '<h3>Liga Modul - System Controlling</h3>';
	OpenTable('mnudebug');
	
	echo '<tr><td>'._button('Zeige alle Spiele mit ungerader Leg Anzahl aus allen aktiven Bewerben','','ls_debug.php?func=oddlegs').'<td></tr>';
	echo '<tr><td>'._button('Zeige alle Legs mit IRREALEN Runden Werten aus allen aktiven Bewerben','','ls_debug.php?func=weirdlegs').'<td></tr>';
	echo '<tr><td>'._button('Zeige alle Legs mit IRREALEN Darts Werten aus allen aktiven Bewerben','','ls_debug.php?func=strangelegs').'<td></tr>';
	echo '<tr><td>'._button('Zeige alle gespielten Matches einer Liga Gruppe und ihren Status','','ls_debug.php?func=matches&stat=1').'<td></tr>';
	echo '<tr><td>'._button('Berechne fiktive Rangliste ALLER Spieler einer Statistikgruppe f&uuml;r einen beliebigen Zeitpunkt','','ls_debug.php?func=allranking').'<td></tr>';
	echo '<tr><td>'._button('Zeige alle Teams einer Ligagruppe','','ls_debug.php?func=allteams').'<td></tr>';
	echo '<tr><td>'._button('Zeige alle Spieler/Teams einer Ligagruppe','','ls_debug.php?func=allplayers').'<td></tr>';
	
	CloseTable();
}


function _listOddLegs(){
	# v2.4
	global $dbi;
	
	#echo setPageTitle('Liste aller Spiele mit ungerader Leganzahl aus allen aktiven Bewerben');
	echo '<h3>Liste aller Spiele mit ungerader Leganzahl aus allen aktiven Bewerben</h3>';
	echo '<p>Jede Zeile funktioniert als Hyperlink und springt direkt in den betreffenden Spielbericht</p>';
	$qry="select count(lgid) C,lgid,gmkey,mround,mevid,E.evname,mhtid,tname from tbllegrounds left join tblgame on lgid=gid,tblmatch,tblevent E,tblteam T where gtype=1 and gmkey=mkey and mevid=E.id and mhtid=T.id and E.evactive=1 group by lgid order by E.id, mround desc";
	$prec=sql_query($qry,$dbi);
	#debug($qry);
	OpenTable();
	
	$aTH=array("Bewerb","Runde","Heimmannschaft","Spiel","Leganzahl");
	echo ArrayToTableHead($aTH);
	echo '<tr bgcolor=\'white\'>'.$Tlegend.'</tr>'; 
	echo '<tr height=5px></tr>';
	#
	# each row has A Link to the corresponding MATCHSHEET + Link to GAME
	#
	while(list($Count,$lgid,$gmkey,$mround,$mevid,$evname,$mhtid,$tname)=sql_fetch_row($prec,$dbi)){
		if (!($Count % 2) == 0) {
		echo "<tr bgcolor=\"white\" onclick=(document.location=\"ls_system.php?func=showmatch&vmkey=$gmkey&eventid=$mevid\") onMouseOver=(mover(this)) onMouseOut=(mout(this)) >"
		."<td>$evname</td><td>$mround</td><td>$tname</td><td>$lgid</td><td>$Count</td></tr>"; 
		echo '<tr height=1px></tr>';
		}
	}
	
	CloseTable();
}

function _listWeirdLegs(){
	# v2.4
	#TODO add the clickrowstuff in here 
	global $dbi;

	$aTH=array("Bewerb","Runde","Datum","Spiel","Spieler (Team)","Scorerunde","Score","Checkrunde");
	$qry="select E.id,E.evname,mround,mdate,gmkey,L.lid,lgid,lroundscore,lroundcheck,lscore,pfname,plname,tname from tblevent E,tblmatch,tblgame,tbllegrounds L,tplayer,tblteamplayer,tblteam T where E.evactive=1 AND E.id=mevid and mkey=gmkey and gid=lgid and lpid=pid and lpid=lplayerid and E.id=leventid and lteamid=T.id and lroundcheck<lroundscore";
	$prec=sql_query($qry,$dbi);
	
	echo '<h3>Liste aller Legs mit verd&auml;chtigen Score / Checkrunden aus allen aktiven Bewerben</h3>';
	echo '<p>Verd&auml;chtige Werte sind in <font color=red>ROT</font> dargestellt und sollten &uuml;berprft bzw korrigiert werden. Jede Zeile funktioniert als Hyperlink und springt direkt in den betreffenden Spielbericht</p>';
	
	OpenTable();
	
	echo ArrayToTableHead($aTH);
	echo '<tr height=5px></tr>';
	#
	# each row has A Link to the corresponding MATCHSHEET + Link to GAME
	#
	while(list($evid,$evname,$mround,$mdate,$gmkey,$lid,$lgid,$lrscore,$lrcheck,$score,$pfname,$plname,$tname)=sql_fetch_row($prec,$dbi)){
		echo "<tr bgcolor=\"white\" onclick=(document.location=\"ls_system.php?func=showmatch&vmkey=$gmkey&eventid=$evid\") onMouseOver=(mover(this)) onMouseOut=(mout(this)) >"
		."<td>$evname</td><td>$mround</td><td>$mdate</td><td>$lgid</td><td>$pfname $plname ($tname)</td><td>$lrscore</td><td>$score</td><td><font color=red><b>$lrcheck</b></font></td></tr>"; 
		echo '<tr height=1px></tr>';
	}
	
	CloseTable();

	$qry="select E.id,E.evname,mround,mdate,gmkey,L.lid,lgid,lroundscore,lroundcheck,lscore,pfname,plname,tname from tblevent E,tblmatch,tblgame,tbllegrounds L,tplayer,tblteamplayer,tblteam T where E.evactive=1 AND E.id=mevid and mkey=gmkey and gid=lgid and lpid=pid and lpid=lplayerid and E.id=leventid and lteamid=T.id and lroundscore<3";
	$prec=sql_query($qry,$dbi);
	#debug($qry);
	OpenTable();
		
	echo ArrayToTableHead($aTH);
	echo "<tr height=5px></tr>";
	#
	# each row has A Link to the corresponding MATCHSHEET + Link to GAME
	#
	while(list($evid,$evname,$mround,$mdate,$gmkey,$lid,$lgid,$lrscore,$lrcheck,$score,$pfname,$plname,$tname)=sql_fetch_row($prec,$dbi)){
		echo "<tr bgcolor=\"white\" onclick=(document.location=\"ls_system.php?func=showmatch&vmkey=$gmkey&eventid=$evid\") onMouseOver=(mover(this)) onMouseOut=(mout(this)) >"
		."<td>$evname</td><td>$mround</td><td>$mdate</td><td>$lgid</td><td>$pfname $plname ($tname)</td><td><font color=red><b>$lrscore</b></font></td><td>$score</td><td>$lrcheck</td></tr>"; 
		echo "<tr height=1px></tr>";
	}
	
	CloseTable();

	# RESTWERTE CHECKEN Score > 50 .........
	
	$qry="select E.id,E.evname,mround,mdate,gmkey,L.lid,lgid,lroundscore,lroundcheck,lscore,pfname,plname,tname from tblevent E,tblmatch,tblgame,tbllegrounds L,tplayer,tblteamplayer,tblteam T where E.evactive=1 AND E.id=mevid and mkey=gmkey and gid=lgid and lpid=pid and lpid=lplayerid and E.id=leventid and lteamid=T.id and lscore<50";
	$prec=sql_query($qry,$dbi);
	#debug($qry);
	OpenTable();
	
	echo ArrayToTableHead($aTH);
	echo "<tr height=5px></tr>";
	#
	# each row has A Link to the corresponding MATCHSHEET + Link to GAME
	#
	while(list($evid,$evname,$mround,$mdate,$gmkey,$lid,$lgid,$lrscore,$lrcheck,$score,$pfname,$plname,$tname)=sql_fetch_row($prec,$dbi)){
		echo "<tr bgcolor=\"white\" onclick=(document.location=\"ls_system.php?func=showmatch&vmkey=$gmkey&eventid=$evid\") onMouseOver=(mover(this)) onMouseOut=(mout(this)) >"
		."<td>$evname</td><td>$mround</td><td>$mdate</td><td>$lgid</td><td>$pfname $plname ($tname)</td><td>$lrscore</td><td><font color=red><b>$score</b></font></td><td>$lrcheck</td></tr>"; 
		echo "<tr height=1px></tr>";
	}
	
	CloseTable();
}

function _listStrangeLegs(){
	# v2.4
	# v2.5
	global $dbi;
	
	echo "<h3>Liste aller Legs mit verd&auml;chtigen Darts/Rest Werten aus allen aktiven Bewerben</h3>";
	echo "<p>Verd&auml;chtige Werte sind in <font color=red>ROT</font> dargestellt und sollten &uuml;berpr&uuml;ft bzw korrigiert werden. Jede Zeile funktioniert als Hyperlink und springt direkt in den betreffenden Spielbericht</p>";
	
	# score weniger als 50 ??
	$aTH=array("Bewerb","Runde","Datum","Spiel","Spieler (Team)","Darts","Score","Finish");
	$qry="select E.id,E.evname,mround,mdate,gmkey,L.lid,L.lgid,L.ldarts,L.lscore,L.lfinish,pfname,plname,tname from tblevent E,tblmatch,tblgame,tblleg  L,tplayer,tblteamplayer,tblteam T where E.evactive=1 AND E.id=mevid and mkey=gmkey and gid=lgid and lpid=pid and lpid=lplayerid and E.id=leventid and lteamid=T.id and L.lscore<50";
	$prec=sql_query($qry,$dbi);
	
	OpenTable();
		
	echo ArrayToTableHead($aTH);
	echo "<tr height=5px></tr>";
	#
	# each row has A Link to the corresponding MATCHSHEET + Link to GAME
	#
	while(list($evid,$evname,$mround,$mdate,$gmkey,$lid,$lgid,$ldarts,$score,$lfinish,$pfname,$plname,$tname)=sql_fetch_row($prec,$dbi)){
		echo "<tr bgcolor=\"white\" onclick=(document.location=\"ls_system.php?func=showmatch&vmkey=$gmkey&eventid=$evid\") onMouseOver=(mover(this)) onMouseOut=(mout(this)) >"
		."<td>$evname</td><td>$mround</td><td>$mdate</td><td>$lgid</td><td>$pfname $plname ($tname)</td><td>$ldarts</td><td><font color=red><b>$score</b></font></td><td>$lfinish</td></tr>"; 
		echo "<tr height=1px></tr>";
	}
	
	CloseTable();

	# DARTS < 13
	$qry="select E.id,E.evname,mround,mdate,gmkey,L.lid,L.lgid,L.ldarts,L.lscore,L.lfinish,pfname,plname,tname from tblevent E,tblmatch,tblgame,tblleg  L,tplayer,tblteamplayer,tblteam T where E.evactive=1 AND E.id=mevid and mkey=gmkey and gid=lgid and lpid=pid and lpid=lplayerid and E.id=leventid and lteamid=T.id and L.ldarts<13";
	$prec=sql_query($qry,$dbi);
	
	OpenTable();
	
	echo ArrayToTableHead($aTH);
	echo "<tr height=5px></tr>";
	#
	# each row has A Link to the corresponding MATCHSHEET + Link to GAME
	#
	while(list($evid,$evname,$mround,$mdate,$gmkey,$lid,$lgid,$ldarts,$score,$lfinish,$pfname,$plname,$tname)=sql_fetch_row($prec,$dbi)){
		echo "<tr bgcolor=\"white\" onclick=(document.location=\"ls_system.php?func=showmatch&vmkey=$gmkey&eventid=$evid\") onMouseOver=(mover(this)) onMouseOut=(mout(this)) >"
		."<td>$evname</td><td>$mround</td><td>$mdate</td><td>$lgid</td><td>$pfname $plname ($tname)</td><td><font color=red><b>$ldarts</b></font></td><td>$score</td><td>$lfinish</td></tr>"; 
		echo "<tr height=1px></tr>";
	}
	
	CloseTable();
	
	# DARTS > 79 
	$qry="select E.id,E.evname,mround,mdate,gmkey,L.lid,L.lgid,L.ldarts,L.lscore,L.lfinish,pfname,plname,tname from tblevent E,tblmatch,tblgame,tblleg  L,tplayer,tblteamplayer,tblteam T where E.evactive=1 AND E.id=mevid and mkey=gmkey and gid=lgid and lpid=pid and lpid=lplayerid and E.id=leventid and lteamid=T.id and L.ldarts>79";
	$prec=sql_query($qry,$dbi);
	
	OpenTable();
	
	echo ArrayToTableHead($aTH);
	echo "<tr height=5px></tr>";
	#
	# each row has A Link to the corresponding MATCHSHEET + Link to GAME
	#
	while(list($evid,$evname,$mround,$mdate,$gmkey,$lid,$lgid,$ldarts,$score,$lfinish,$pfname,$plname,$tname)=sql_fetch_row($prec,$dbi)){
		echo "<tr bgcolor=\"white\" onclick=(document.location=\"ls_system.php?func=showmatch&vmkey=$gmkey&eventid=$evid\") onMouseOver=(mover(this)) onMouseOut=(mout(this)) >"
		."<td>$evname</td><td>$mround</td><td>$mdate</td><td>$lgid</td><td>$pfname $plname ($tname)</td><td><font color=red><b>$ldarts</b></font></td><td>$score</td><td>$lfinish</td></tr>"; 
		echo "<tr height=1px></tr>";
	}
	
	CloseTable();

}

function _listMatches($eventgroup,$vmstatus){
	/*
	 * Modul: Controlling
	 * purpose: List matches from event_groups based on MatchStatus FLAG
	 * param:		$vmstatus (int) 0-x	
	 * v4 BH using the layer architecture
	 */

	global $dbi,$orangepic,$redpic,$bluepic;
	
	echo form_EventGroupSelect('ls_debug.php?func=matches','',$eventgroup,$vmstatus);
	if (!$eventgroup>0) return;
	if (!$vmstatus>0) return;

	$aTH=array("Bewerb","ID","Runde","Datum","Spielort","Heim Team","Sets","Legs","Sets","Legs","Gast Team","S");
	$RS=DB_listMatches($dbi,1,0,0,"","<curdate()","","=$vmstatus","logic","",$eventgroup);
	
	echo "<h3>Liste aller Spiele mit STATUS=".$vmstatus."</h3>";
	echo "<p>Jede Zeile funktioniert als Hyperlink und springt direkt in den betreffenden Spielbericht</p>";
	OpenTable();
	echo ArrayToTableHead($aTH);
	echo RecordsetToClickTable($RS,1,"ls_system.php?func=showmatch&vmkey=%P1%&eventid=%P2%",3,0);
	CloseTable();
}

function _showEVENTIndexReallyALLPlayers($indexdate,$evstatcode){
	#
	# this shows the ranking points of all players who belong
	# to a specific Statistics-Group $evstatcode
	# a) retrieve all legs belonging to this listnumber form all events for the specified period
	# b) check on legacy table and include data
	#
	global $dbi,$tdbg,$event;
	
	$fromdate  = fnc_date_calc($indexdate,-365);
	
	echo "<h3>Fiktive Rangliste berechnet aus allen Spielen von $fromdate bis $indexdate der Statistik-Gruppe $evstatcode</h3>";
	
	echo '<form action=\'ls_debug.php?func=allranking&amp;vindexdate='.$indexdate.'\' method=\'post\'>';
	echo '<p>Beliebigen Stichtag w&auml;hlen (YYYY-MM-DD). F&uuml;r diesen Tag wird ein FEDA Wert pro Spieler erstellt der die letzten 365 Tage ber&uuml;cksichtigt.<br>Im Gegensatz zur offiziell sichtbaren Liste werden hier wirklich ALLE Spieler angezeigt die jemals in dieser Ligagruppe t&auml;tig waren, unabh&auml;ngig davon ob sie jetzt bei einer aktiven Mannschaft t&auml;tig sind .... (kann etwas dauern ... das ist eine LIVE Berechnung mit tausenden legs ...)<br>Es werden auch nur die Spieler angezeigt f&uuml;r die mind. 1 Leg gefunden wurde...</p>';
	echo '<table><tr><td>'._input(1,"vindexdate",$indexdate,12,12).'</td>';
	echo '<td>'.Select_StatGroup('evstatcode',$evstatcode).'</td>';
	echo '<td>'._button("Rangliste bis Stichtag").'</td>';
	echo "</tr></table></form>";
	
	if ($evstatcode>0){
	generateStaticFEDAStatisticEntries($evstatcode,$indexdate,'no');
	}
}

function _listTeams($eventgroup){
	#
	# // List all active teams for the actual eventgroup
	# // get eventgroup - list teams with hyperlinks into the schedule page for this league
	#
	global $dbi,$tdbg;
	/*
	 * we have no global event struct here ... retieve based on passed param
	 * show selector for change of eventgroup ....
	 */
	echo form_EventGroupSelect('ls_debug.php?func=allteams','',$eventgroup);	
	if (!$eventgroup>0) return;
	
	$aTH=array('Bewerb','Saison','Team-id','Teamname','Verein-id','Vereinsname','Spielort-id','Heimspielort');
	$target='ls_system.php?func=schedule&eventid=%P1%';
	$RS=DB_listTeams($dbi,0,$eventgroup,'',1);
	$ROWS=RecordsetToClickTable($RS,2,$target,0);

	// OUTPUT

	echo '<h3>Alle Teams der Liga-Gruppe '.$eventgroup.'</h3>';
	echo '<p>Hier werden alle Teams die in <b>allen</b> aktiven Ligen dieser Liga Gruppe spielen alphabetisch gelistet. Ein Klick auf einen Teameintrag f&uuml;hrt direkt in die Seite mit allen Spielberichten (Spielplan) der betreffenden Liga oder Klasse.</p>';
	OpenTable();	
	echo ArrayToTableHead($aTH);
	echo "<tr height=5px></tr>";
	echo $ROWS;
	CloseTable();
	echo "<p>Anzahl der Teams: ".count($RS)."</p>";
	
}

function _listTeamPlayers($eventgroup){
	#
	#// List all Players with their teams for the actual eventgroup
	#// Hyperlinked list ???
	#
	global $dbi,$tdbg;
	#TODO replaye by event-config
	$LigaStichtag="2008-09-01";		

	echo form_EventGroupSelect('ls_debug.php?func=allplayers','',$eventgroup);
	if (!$eventgroup>0) return;
	
	$RS=DB_listEventTeamPlayers($dbi,$eventgroup,0,'',0,'',1,0,'team',$LigaStichtag);
	// field(12) is the day count if positive than youth player <18J else return 1
	/*
	 foreach($RS as $r){
		#echo $r[12];
		if ($r[12]>0) {
			$r[12]=0;
		}else {
			$r[12]=1;
		}
	}
	*/
	$FIELDS=array(3,4,5,6,7,8,9,10,11,12);
	$ROWS=RecordsetToDataTable($RS,$FIELDS);
	$aTH=array("Bewerb","Saison","tid","Teamname","pid","Vorname","Nachname","PassNr","PassNr","18J+");
	
	// OUTPUT

	echo '<h3>Alle gemeldeten Spieler/Teams der Liga-Gruppe '.$eventgroup.'</h3>';
	echo "<p>Alphabetische Liste aller Teams - <b>aller</b> aktiven Ligen einer Liga Gruppe mit allen gemeldeten Spieler. Falls Spieler innerhalb einer Liga Gruppe in 2 Teams spielen d&uuml;rfen so werden sie auch 2x angezeigt. Der Stichtag f&uuml;r die Altersgrenze ist der $LigaStichtag.</p>";
	OpenTable();	
	echo ArrayToTableHead($aTH);
	echo "<tr height=5px></tr>";
	echo $ROWS;
	CloseTable();
	echo "<p>Anzahl der Spieler: ".count($RS)."</p>";
}

if (isset($_REQUEST['func'])&& $_REQUEST['func']<>'undefined') {$myfunc=strip_tags($_REQUEST['func']);}else{$myfunc='';};
if (isset($_REQUEST['vindexdate'])&& $_REQUEST['vindexdate']<>'undefined') {$my_indexdate=strip_tags($_REQUEST['vindexdate']);}else{$my_indexdate=date("Y-m-d",time());};
if (isset($_REQUEST['evstatcode']) && intval($_REQUEST['evstatcode'])>0) {$event_stat_id=strip_tags($_REQUEST['evstatcode']);}else{$event_stat_id=0;};
if (isset($_REQUEST['eventgroup']) && intval($_REQUEST['eventgroup'])>0) {$event_group_id=strip_tags($_REQUEST['eventgroup']);}else{$event_group_id=0;};
if (isset($_REQUEST['stat']) && intval($_REQUEST['stat'])>0) {$match_status=strip_tags($_REQUEST['stat']);}else{$match_status=0;};

switch($myfunc) {

	default:
	_blank();
	break;
	
	case "oddlegs":
	_listOddLegs();
	break;
	
	case "weirdlegs":
	_listWeirdLegs();
	break;
	
	case "strangelegs":
	_listStrangeLegs();
	break;
	
	case "matches":
	_listMatches($event_group_id,$match_status);
	break;
	
	case "allranking":
	_showEVENTIndexReallyALLPlayers($my_indexdate,$event_stat_id);
	break;
	
	case "allteams":
	_listTeams($event_group_id);
	break;

	case "allplayers":
	_listTeamPlayers($event_group_id);
	break;
}


echo '</div>';
LS_page_end();

?>
