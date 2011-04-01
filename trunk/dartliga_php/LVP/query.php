<?php

# //
# // super public page - shows and executes queries into the system
# // queries are returned seperated by ; and linefeed = <br>
# // v2 added special entry for the tabelle incl. won,lost counters
# // v3 added special recursive entry for the ligagruppe tabelle inkl. players.
# // v3 BH: OK checked
# // v4 BH: adapted for the non_globals environments
# //

foreach ($HTTP_GET_VARS as $secvalue) {
    if (eregi("<[^>]*script*\"?[^>]*>", $secvalue) OR eregi("\([^>]*.*\"?[^>]*\)", $secvalue)) {
	die ($_SERVER['REMOTE_ADDR']);
    }
}

	require_once("code/config.php");
	require_once("includes/sql_layer.php");
	
function _showlegend(){
	# list queries from db
	global $dbhost, $dbuname, $dbpass, $dbname;
	$dbi = sql_connect($dbhost, $dbuname, $dbpass, $dbname);
	$qry="select qid,qname,qp1,qp2,qp3,qdesc from tblquery";
	$presult=sql_query($qry,$dbi);
	$strRET='<h3> Export &amp; Query Interface</h3>';
    #$strRET=$strRET.'<p>Att: as of v4 (summer 2008) this interface will be replaced by service calls to the snippets engine</p>';
    $strRET=$strRET.'<p><b>Usage:</b> query.php?name=teamplayerbyleagueid&amp;p1=xy&amp;p2=xy&amp;p3=xy</p>';
	$strRET=$strRET.'<p><b>Format:</b> The records returned are terminated with html-linebreak . Data are seperated by semicolon.</p>';
	$strRET=$strRET.'<table border>';
	$strRET=$strRET."<tr style='font-size:12px;color:red;'><td></td><td>name</td><td style='width:100px'>p1</td><td style='width:100px'>p2</td><td style='width:100px'>p3</td><td>Beschreibung</td></tr>";
	while(list($a,$b,$c,$d,$e,$de)=sql_fetch_row($presult,$dbi)){
		$strRET=$strRET."<tr><td></td><td>$b</td><td>$c</td><td>$d</td><td>$e</td><td>$de</td></tr>";
	}
	$strRET=$strRET."<tr><td></td><td>tabellecomplete</td><td>LigaID(1-xx)</td><td>1/0</td><td></td><td>Tabelle inklusive der Sieg/Unentschieden/Verloren Werte. p2 dient der Sortierung nach Punkten (=1) oder Sets (=0)</td></tr>";
	$strRET=$strRET."<tr><td></td><td>alleligatabellen</td><td>LigaGruppe(1-xx)</td><td>1/0</td><td></td><td>Alle Tabellen einer Liga Gruppe inklusive aller Spieler eines Teams. p2 dient der Sortierung nach Punkten (=1) oder Sets (=0)</td></tr>";
	echo $strRET."</table>";
}

function _executequery($name,$p1,$p2,$p3){
	# just execute the query and return as list ...
	switch ($name) {
		case "tabellecomplete":
		if(!isset($p1)) die("param 1 LigaID needed");
		if(!isset($p2)) die("param 2 points/sets needed");
		_tabellecomplete($p1,$p2);
		die();
		break;
		
		case "alleligatabellen":
		if(!isset($p1)) die("param 1 LigaGruppe needed");
		if(!isset($p2)) die("param 2 points/sets needed");
		_tabelleligateamsplayer($p1,$p2);
		die();
		break;
		default:
	}
	global $dbhost, $dbuname, $dbpass, $dbname;
	$dbi = sql_connect($dbhost, $dbuname, $dbpass, $dbname);
	$qry="select qbody from tblquery where qname=\"$name\"";
	$presult=sql_query($qry,$dbi);
	$ret=sql_fetch_array($presult,$dbi);
	$qtext=$ret['qbody'];
	if (isset($p1)) $qtext=str_replace("<%1%>", $p1, $qtext);	#//check on params append ...
	if (isset($p2)) $qtext=str_replace("<%2%>", $p2, $qtext);	#//check on params append ...
	if (isset($p3)) $qtext=str_replace("<%3%>", $p3, $qtext);	#//check on params append ...
	
	# debug($qtext);
	$presult=sql_query($qtext,$dbi);
	while($a=sql_fetch_row($presult,$dbi)){
		#// compile output line seperated by ;
		foreach ($a as $val) {$strRET=$strRET.$val.';';}
		$strRET=$strRET.'<br/>';
	}
	header('Content-Type: text/html; charset=ISO-8859-1');
	echo $strRET;
}

function _retTabelleQuery($eventid,$tabpoints){
	# // query returns the correct SQL
	# // for accessing the tabelle
	# SETS based standings

	if ($tabpoints==0){
	$strsql="select T.id,T.tname,sum(mtsets) SETSW,sum(mtsetslost) SETSL,sum(mtsets)-sum(mtsetslost) SDIFF,sum(mtlegs) LEGSW,sum(mtlegslost) LEGSL,sum(mtlegs)-sum(mtlegslost) LDIFF, count(mstatus) CT,sum(mtpoints) PT from tblmatchteam,tblmatch,tblteam T where mttid=T.id and mtmkey=mkey and mstatus>1 and T.tevent_id=$eventid group by T.id order by SDIFF desc,LDIFF desc";
	}

	# POINTS based standings 
	if ($tabpoints==1){
	$strsql="select T.id,T.tname,sum(mtsets) SETSW,sum(mtsetslost) SETSL,sum(mtsets)-sum(mtsetslost) SDIFF,sum(mtlegs) LEGSW,sum(mtlegslost) LEGSL,sum(mtlegs)-sum(mtlegslost) LDIFF, count(mstatus) CT,sum(mtpoints) PT from tblmatchteam,tblmatch,tblteam T where mttid=T.id and mtmkey=mkey and mstatus>1 and T.tevent_id=$eventid group by T.id order by PT desc,SDIFF desc,LDIFF desc";
	}
	return $strsql;
}

function _tabellecomplete($eventid,$tabpoints){
	# this is a special handler, since we need a subselect here
	# // attention this is duplicate to stuff in ls_tabelle
	# //

	global $dbhost, $dbuname, $dbpass, $dbname;
	$dbi = sql_connect($dbhost, $dbuname, $dbpass, $dbname);
	
	$strsql=_retTabelleQuery($eventid,$tabpoints);
	$prec=sql_query($strsql,$dbi);
	
	#
	$strRET="";
	while(list($tid,$tname,$SETSW,$SETSL,$SDIFF,$LEGSW,$LEGSL,$LDIFF,$CT,$PT)=sql_fetch_row($prec,$dbi)){
		
		$strRET=$strRET."$tname;$CT";
		
		# GET THE WON and EVEN counters by summarizing entries per team .... (sub select !!!!)
		$resW="select count(mtpoints) CNT from tblmatchteam,tblmatch,tblteam T where mttid=T.id and mtmkey=mkey and mstatus>1 and T.tevent_id=$eventid and T.id=$tid and mtpoints=2";
		$resE="select count(mtpoints) CNT from tblmatchteam,tblmatch,tblteam T where mttid=T.id and mtmkey=mkey and mstatus>1 and T.tevent_id=$eventid and T.id=$tid and mtpoints=1";
		$precW=sql_query($resW,$dbi);
		$cntW=sql_fetch_array($precW,$dbi);
		$precE=sql_query($resE,$dbi);
		$cntE=sql_fetch_array($precE,$dbi);
		$strRET=$strRET.";".$cntW['CNT'].";".$cntE['CNT'].";".($CT-($cntW['CNT']+$cntE['CNT']));
		$strRET=$strRET.";$SETSW;$SETSL;$SDIFF;$LEGSW;$LEGSL;$LDIFF;$PT;<br/>";
	}
	echo $strRET;
}

function _tabelleligateamsplayer($eventGroup,$tabpoints) {
	# // special sub selected query 
	# // select all events from event group
	# // for every event show tabelle first 4 teams according to eventconfig
	# // for every team show players
	# // used for zB urkunden or other gimmicks
	#//
	global $dbhost, $dbuname, $dbpass, $dbname;
	$dbi = sql_connect($dbhost, $dbuname, $dbpass, $dbname);
	
	$qryevents='select E.id,E.evyear,E.evname from tblevent E where E.evactive=1 and E.evtypecode_id='.$eventGroup.' order by E.id';

	$prec=sql_query($qryevents,$dbi);
	while(list($evid,$evyear,$evname)=sql_fetch_row($prec,$dbi)){
		$qrytab=_retTabelleQuery($evid,$tabpoints);
		$pTAB=sql_query($qrytab,$dbi);
		while(list($tid,$tname,$SETSW,$SETSL,$SDIFF,$LEGSW,$LEGSL,$LDIFF,$CT,$PT)=sql_fetch_row($pTAB,$dbi)){
			$qryteam='select pfname,plname from tblteamplayer,tplayer where lplayerid=pid and lteamid='.$tid.' order by plname asc';
			$pTEAM=sql_query($qryteam,$dbi);
			while(list($pfname,$plname)=sql_fetch_row($pTEAM,$dbi)){
				$strRET=$strRET."$evname;$tname;$CT";
				$strRET=$strRET.";$SETSW;$SETSL;$SDIFF;$LEGSW;$LEGSL;$LDIFF;$PT;$pfname;$plname<br/>";
			} // end while teamplayers
		} // end while teamtabelle
	} // end while events
	echo $strRET;
}

if (isset($_REQUEST['name']) && strlen($_REQUEST['name'])<30) {$p_name=strip_tags($_REQUEST['name']);}else{$p_name='';};
if (isset($_REQUEST['p1']) && strlen($_REQUEST['p1'])<15) {$p_p1=strip_tags($_REQUEST['p1']);}else{$p_p1='';};
if (isset($_REQUEST['p2']) && strlen($_REQUEST['p2'])<15) {$p_p2=strip_tags($_REQUEST['p2']);}else{$p_p2='';};
if (isset($_REQUEST['p3']) && strlen($_REQUEST['p3'])<15) {$p_p3=strip_tags($_REQUEST['p3']);}else{$p_p3='';};

if (strlen($p_name)>0) {
	_executequery($p_name,$p_p1,$p_p2,$p_p3);
} else {	
	_showlegend();
}
?>
