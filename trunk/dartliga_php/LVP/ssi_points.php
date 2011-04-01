<?php

/************************************************************************/
/*
/* Copyright (c) 2003 by Boris Hristovski          			*/
/*                                                                      */
/************************************************************************/
#
# // Functions for manipulating SSI records BH, initial interface v1 2003
# // browse (default), update, new, delete, activate, deactivate
# // 12.2005 added ssi_points.php?func=preload to set a median startvalue for all players
# // v3-8 switched to common datetable
# // moved calculation into func_stats.php

include("ssi_main.php");

$thisfile="ssi_points.php";
$minsip=14;			# mindestanzahl an entries fr anzeige
$minSSIVal=1400;			# mindest SSI Wert kann nicht unterschritten werden.
$allSSIDateQuery='select sdate from tbldate where sstatcode_id=2 order by sdate desc';

# // Beginn Funktionen ----------------
function listgamesplayer($vpid){
	# v01 bh 14.7.2003
	# list all games per player (since $lastdate)
	# order by date - break on TSSIdates - show currSSI for these dates ...
	#
	global $dbi,$sctdcolor,$redpic,$greenpic,$lastdate;

	echo '<h1>Alle Resultate eines Spielers</h1>';
	echo "<p>$greenpic Spiel gewonnen<br>$redpic Spiel verloren<br></p>";

	$srec=sql_query("select statdate,statval from tblstat where statcode=2 AND statpid=$vpid order by statdate asc",$dbi);

	$olddate='2002-06-01'; # // fake old date long past ....

	OpenTable();
	echo '<table>';

	while (list($sipdate,$sippoints)=sql_fetch_row($srec,$dbi)){
		# // lets fetch all games the player is in this perios (sipdate - olddate)
		$rrecords = sql_query("select rgid,pid"
			." from tplayer,tresult"
			." where pid=rpid and pid=$vpid"
			." and rdate > '$olddate' and rdate < '$sipdate' order by rdate,rgid asc",$dbi);

	while (list($rgid,$pid)=sql_fetch_row($rrecords,$dbi)){
		$i=$i+1;
		# // echo "<br>PID=$pid Spielnummer=$rgid";
		# // OK now run the game query for each game, so we get the opponent
		$r=0;
		$gqry=sql_query("select rid,rgid,rdate,rdesc,pfname,plname,rresult"
				." from tresult,tplayer where rpid=pid and rgid=$rgid order by rdate",$dbi);
		while (list($rid,$rgid,$rdate,$rdesc,$pfname,$plname,$rresult)=sql_fetch_row($gqry,$dbi)){
		$r=$r+1;
		if ($r < 2) {
			# SECURITY ADMIN HERE ///////
			echo "<tr><td><a href=\"?func=editgame&amp;vrgid=$rgid\">Spiel:$rgid</a></td>"
			."<td>$rdate</td><td>$rdesc</td>";
			if ($rresult == 1){
				echo "<td>$greenpic $pfname $plname</td>";
			} else {
				echo "<td>$redpic $pfname $plname</td>";
			}
		} else {
			if ($rresult == 1){
				echo "<td>$greenpic $pfname $plname</td></tr>";
			} else {
				echo "<td>$redpic $pfname $plname</td></tr>";
			}
		}
		}

	} # // END WHILE GAME LOOP
		# // write the SSI from the above listed games
		echo "<tr><td colspan=\"3\"><b>Datum $sipdate SSI ZAHL = ".number_format($sippoints,2,'.','')."<b></td></tr>";
		$olddate=$sipdate;
	} # // END while ssidate LOOP

	echo "</table>";
	CloseTable();
	echo "<br><i>Anzahl der gespeicherten Spiele = $i</i><br>";
}

function listgames($vdate1=''){
	#
	# // Listet alle Spiele seit dem letzten Stichdatum
	# // bzw ab $vdate1 (vdate2 wird dyn ermittelt ...)
	# // v04 gr�ser als vdate1 -> qry dann abschneiden bei vdate2
	# // geht in qry nicht
	# // v07 retselectBox funktion eingebaut
	#
	global $dbi,$sctdcolor,$predpic,$pgreenpic,$lastdate,$allSSIDateQuery;
	# // defaults
	if (empty($vdate1)) $vdate1 = $lastdate;

	$trecords=sql_query($allSSIDateQuery,$dbi);
	while (list($tdate)=sql_fetch_row($trecords,$dbi)){
		if ($tdate > $vdate1) $vdate2=$tdate;
		}

	OpenTable();
	echo "<h1>Spiele der Periode $vdate1 bis $vdate2</h1>";
	echo "<h3>SSI vom Stichtag: $vdate1</h3>";

	# // BEGINN DATE SELECT FORM //
	echo "<form action=\"?func=listgames\" method=\"post\"><table><tr>"
	."<td>AB: ".Select_SSIPeriode('vdate1',$vdate1,2).'</td><td>'._button("Diese Periode anzeigen")."</td></tr></table></form>&nbsp;";
	# // END DATE SELECT FORM //
	CloseTable();

	OpenTable();
	$srecords = sql_query("select pid,pfname,plname,rid,rgid,rpid,rresult,rdate,rdesc,statval"
			." from tplayer,tresult,tblstat"
			." where pid=rpid and pid=statpid and statcode=2 and statdate = '$vdate1' and rdate>'$vdate1' and rdate<'$vdate2'"
			." order by rdate,rgid asc,rresult desc",$dbi);
	echo "<table>"
		."<tr><td class=\"thead\">Wann,Wo</td>"
		."<td class=\"thead\">Sieger</td><td class=\"thead\">Verlierer</td>"
		."<td class=\"thead\">Sieger</td><td class=\"thead\">Verlierer</td>"
		."<td class=\"thead\">Gewinnchance</td></tr>";
	$i=0;
	$lastgame=0;
	while(list($pid,$pfname,$plname,$rid,$rgid,$rpid,$rresult,$rdate,$rdesc,$sippoints)=sql_fetch_row($srecords,$dbi)){
		#
		# es gibt immer 2 entries pro Spiel -> darstellung in einer Reihe
		# SORTORDER = WINNER A / Loser B
		$i=$i+1;
		if ($rgid<>$lastgame) {
			$sipA=$sippoints;
			# SECURITY ADMIN HERE ///////
			echo "<tr><td><a href=\"?func=editgame&amp;vrgid=$rgid\">S&nbsp;$rgid</a> $rdate<br>$rdesc</td>"
			."<td>$pfname<br>$plname (".number_format($sippoints,2,'.','').")</td>";
		} else{
			$sipB=$sippoints;
			echo "<td>$pfname<br>$plname (".number_format($sippoints,2,'.','').")</td>";
		# calculate PLAYER A the WINNER ////
		$valAwin=ReturnSSIChangePlayer($sipA,$sipB,1);
		$valChance=ReturnWinExpectancy($sipA,$sipB);
		# calculate PLAYER B the Loser ////
		$valBLost=ReturnSSIChangePlayer($sipB,$sipA,0);
		echo "<td>+ ".number_format($valAwin,2,'.','')."</td>"
			."<td><font color='red'>- ".number_format($valBLost,2,'.','')."</font></td>"
			."<td>(".number_format($valChance,2,'.','')."%)</td></tr>";
		}
		$lastgame=$rgid;
	}
	echo "</table>";
	echo
	CloseTable();
}

/**
 * zeigt einen Table mit einer SpielerListe / SSI an, sortiert nach SSI Wert
 * zeigt nur spieler die einen valid SSI haben und mahr als mingames
 * @return FULL TABLE
 */
function ssiranking(){
	
	global $dbi,$sctdcolor,$predpic,$pgreenpic,$porangepic,$lastdate,$minsip;

	$cres=sql_query("select count(*) CNT from tresult",$dbi);
	$aCNT=sql_fetch_array($cres,$dbi);
	$gamecount=$aCNT['CNT'];
	
	echo '<h3>Spielst&auml;rke Index Punkte</h3>';
	OpenTable();
	echo "<p>In dieser Liste werden nur Spieler angezeigt die mit mind. $minsip Begegnungen im SSI System erfasst wurden. Wenn du in dieser Liste nicht sichtbar bist aber schon die erforderliche Anzahl an Spielen absolviert hast, so wende dich an den <a href=\"ssi_main.php?op=intro\">SSI Verantwortlichen</a> deines Landesverbandes."
	."<br/>$pgreenpic : SSI Zahl aktuell <br/>$porangepic : Zu wenig Spiele in der letzten Periode <br/>$predpic : Kein Spiel in der letzten Periode</p>";
	echo "<p>Klick auf die SSI Zahl zeigt die historische Entwicklung eines Spielers<br/>Klick auf die Spiele Anzahl zeigt ein detailiertes Listing aller Spiele.</p>";
	CloseTable();
	
	$aTH=array('Vorname','Nachname','SSI','Anzahl Spiele','SSI Status');
	$qry="select pid,pfname,plname,psipstatus,statid,statval,sipcount from tplayer,tblstat"
			." where pactive=1 AND pid=statpid AND sipcount>$minsip AND statdate = '$lastdate' and statcode=2 ORDER by statval desc";
	$srecords = sql_query($qry,$dbi);
	$i=0;
	$vlast=0;
	
	echo "<h3>Stichdatum $lastdate</h3>";
	OpenTable();
	echo "<table width=\"100%\" cellpadding=\"2\" cellspacing=\"2\">";
	echo ArrayToTableHead($aTH);
	
	while(list($pid,$pfname,$plname,$pssi_status,$stat_id,$stat_val,$ssi_count)=sql_fetch_row($srecords,$dbi)){
	  	$i=$i+1;
	  	# // write typical player row //
		echo ssiPlayerRow($pid,$pfname,$plname,$pssi_status,$stat_val,$ssi_count);
	} # // END WHILE LOOP
	
	echo '</table>';
	CloseTable();
	
	echo '<br/>';
	MakeSSICurve();
	echo '<br/>&nbsp;<i>Anzahl der angezeigten Spieler = '.$i.'</i><br/>'
	.'&nbsp;<i>Anzahl der gespeicherten Spiele = '.($gamecount/2).'</i>';
}

/**
 * renders a typical PLAYER ROW
 *
 * @param int $pid
 * @param string $pfname
 * @param string $plname
 * @param int $psipstatus
 * @param long $sippoints
 * @param int $sipcount
 * @return TR HTML String
 */
function ssiPlayerRow($pid,$pfname,$plname,$psipstatus,$sippoints,$sipcount){
	global $porangepic,$predpic,$pgreenpic;

	$OUT='<tr><td>'.$pfname.'</td><td>'.$plname.'</td>';
	$OUT=$OUT.'<td><a href="?func=historyplayer&amp;vpid='.$pid.'">'.number_format($sippoints,2,'.','').'</a></td>'
		.'<td><a href="?func=listplayer&amp;vpid='.$pid.'">'.$sipcount.'</a></td><td>';
	switch($psipstatus){
			case "0":
				$OUT=$OUT.$predpic;
				break;
			case "1":
				$OUT=$OUT.$porangepic;
				break;
			case "2":
				$OUT=$OUT.$pgreenpic;
				break;
		}
	return $OUT.'</td></tr>';
}

/*
function delssicurrent($vsid) {
# ----------------
# v01 BH 2003/07/01
# ----------------
	global $dbi,$user,$SSI_Level;
	if ($SSI_LEVEL<3) die("<h1>Not allowed ...</h1>");
	$res1 = sql_query("delete from tblstat where statid=$vsid and statcode=2",$dbi);
	ssilog(3,$user,"DELETED SSI Record ($vpid)");
	# re-read all player records and show in browser
}
*/

/*
function newresult($vdate,$vdesc,$vfilter){
	#
	#  interface for entering GAME Results into the SSI system
	#  $vdate is the date of the last newresult-operation
	#
	#  playerlist is a join to current-ssi -> don't show player without a SSI
	# 
	#
	global $dbi,$sctdcolor,$predpic,$pgreenpic,$lastdate,$SSI_LEVEL;
	if ($SSI_LEVEL<2) die("<h1>Not allowed for access level:$SSI_LEVEL</h1>");
	switch($vfilter){
		default:
		$filter="";
		break;
		case "H":
		$filter="and pgender='H'";
		break;
		case "D":
		$filter="and pgender='D'";
		break;
		case "W":
		$filter="and prealm=1";
		break;
		case "K":
		$filter="and prealm=9";
		break;
		case "N":
		$filter="and prealm=2";
		break;
	}
	$srecords = sql_query("select pid,pfname,plname from tplayer,tblstat"
	." where pid=statpid and statdate = '$lastdate' and statcode=2 $filter order by plname asc",$dbi);

	while(list($pid,$pfname,$plname)=sql_fetch_row($srecords,$dbi)){
	$optionstring=$optionstring."<option value=$pid>$plname $pfname</option>";
	}

	OpenTable();
	echo "<h3>Neues Spielresultat eingeben</h3>";

	echo "<table>"
		."<form action=\"ssi_points.php?func=saveresultn\" method=\"post\">"
		."<tr><td width=\"30%\">Anzeige Filter ($vfilter)</td><td width=\"30%\">"
		."<select size=1 maxlength=50 name=\"vfilter\"><option value=A>Alle </option><option value=H>Herren </option><option value=D>Damen </option><option value=K>KEDSV</option><option value=N>NOEDSV</option><option value=W>WDV </option></select>"
		."<input type=\"hidden\" name=\"vdesc\" value=\"$vdesc\"></td>"
		."<input type=\"hidden\" name=\"vdate\" value=$vdate></td>"
		."</td><td width=\"30%\"><input type=\"submit\" value=\"Filter setzen\">"
		."</td></tr></form><tr><td></td></tr>"
		."<tr><td valign=\"top\" class=\"bluebox\">Datum<br>Beschreibung</td>"
		."<td valign=\"top\" class=\"bluebox\">Name Sieger</td>"
		."<td valign=\"top\" class=\"bluebox\">Name Verlierer</td></tr>"
		."<form action=\"ssi_points.php?func=saveresultn\" method=\"post\">"
		."<tr><td valign=\"top\"><input type=\"text\" size=15 maxlength=15 name=\"vdate\" value=$vdate><br>"
		."<input type=\"text\" size=30 maxlength=49 name=\"vdesc\" value=\"$vdesc\"><br>"
		."<input type=\"hidden\" name=\"vfilter\" value=\"$vfilter\">"
		."Das Datum muss in folgendem Format angegeben werden: JAHR-MM-TT, also 2005-03-27 alle anderen Formate werden als Jahr 00 interpretiert.</td>"
		."<td><font=+1><select size=20 maxlength=50 name=\"vidwinner\">$optionstring</select></font></td>"
		."<td><font=+1><select size=20 maxlength=50 name=\"vidloser\">$optionstring</select></font></td></tr>"
		."<tr><td></td></tr><tr><td></td><td>"
		."<input type=\"submit\" value=\"Spiel Speichern\"></td><td></td></tr></form>"
		."</table>";
	CloseTable();

}
*/

function editgame($vrgid){
	OpenTable();echo 'Disabled';CloseTable();
}
/*
function editgame($vrgid){

	global $dbi,$user,$sipgoback,$lastdate,$SSI_LEVEL;
	if ($SSI_LEVEL<2) die("<h1>Not allowed for access level:$SSI_LEVEL</h1>");
	echo "<h3>Bearbeiten von Spiel $vrgid</h3>";
	echo "<p>Wenn du die Spieler falsch eingegeben hast so ist es besser das Spiel zu l�chen und neu zu erfassen, hier kannst du an sich nur 3 Dinge �dern:</p><p><ul><li>Datum<li>Kommentar<li>Resultat - also Sieger (1) u. Verlierer (0) vertauschen</ul></p><p>Es gibt nur ein Resultat und dass <b>muss 1:0</b> lauten nicht (2:3 oder 2:4).<br>Nach dem Bet&auml;tigen des Speichern Buttons wird das ge&auml;nderte Spiel nochmals angezeigt zur Kontrolle.</p>";

	OpenTable();
	echo "<form action=\"?func=changegame&amp;vrgid=$vrgid\" method=\"post\"><table>";
	$gqry=sql_query("select rid,rgid,rdate,rdesc,pfname,plname,rresult from tresult,tplayer where rpid=pid and rgid=$vrgid order by rresult desc",$dbi);
	while (list($rid,$rgid,$rdate,$rdesc,$pfname,$plname,$rresult)=sql_fetch_row($gqry,$dbi)){
		$i=$i+1;
		if ($i < 2) {
		echo "<tr><td>Spiel</td><td>$rgid</td><td>$rgid</td></tr>"
		."<tr><td>Datum</td><td>$rdate</td><td><input type=\"text\" name=\"vrdate\" value=\"$rdate\"></td></tr>"
		."<tr><td>Beschreibung</td><td>$rdesc</td><td><input type=\"text\" name=\"vrdesc\" value=\"$rdesc\"></td></tr>"
		."<tr><td>Sieger ($rresult)</td><td>$pfname $plname</td><td><input type=\"text\" name=\"vrresultw\" value=\"$rresult\"></td></tr>";
		} else {
		echo "<tr><td>Verlierer ($rresult)</td><td>$pfname $plname</td><td><input type=\"text\" name=\"vrresultl\" value=\"$rresult\"></td></tr>"
		."<tr><td></td><td></td><td><input type=submit value=\"&Auml;nderungen Speichern\"></td></tr>";
		}
	}
	echo "</table></form>";
	CloseTable();
}
*/

/*
function changegame($vrgid,$vrdate,$vrdesc,$vrresultw,$vrresultl){
#  called by editgame page
#  BH 14.7.2002
#  usually $vrresultw > $vrresultl --> else the results have to be changed ....
	global $dbi,$user,$SSI_LEVEL;
	if ($SSI_LEVEL<2) die("<h1>Not allowed for access level:$SSI_LEVEL</h1>");
	
	$vrdesc=strip_tags($vrdesc,"<b><i>");
	$upqry="update tresult set rdate='$vrdate',rdesc='$vrdesc' where rgid=$vrgid";
	$res1 = sql_query($upqry,$dbi);

	if ($vrresultw < $vrresultl){
		# circle change ... uhuhu dirty but avoids passing unknown rid
		ssilog(2,$user,"<b>Results changed</b> Game: $vrgid am $vrdate");
		$upqry="update tresult set rresult=2 where rgid=$vrgid and rresult=1";
		$res1 = sql_query($upqry,$dbi);
		echo mysql_error();
		$upqry="update tresult set rresult=1 where rgid=$vrgid and rresult=0";
		$res1 = sql_query($upqry,$dbi);
		echo mysql_error();
		$upqry="update tresult set rresult=0 where rgid=$vrgid and rresult=2";
		$res1 = sql_query($upqry,$dbi);
		echo mysql_error();
		echo "<h1>Results Changed !!</h1>";
		}

}
*/
/*
function saveresult($vdate,$vidwinner,$vidloser,$vdesc){
# ------------------------
# v02 BH 2003/7/10
# -----------------------
	global $dbi,$user,$sipgoback,$lastdate,$SSI_LEVEL;
	
	if ($SSI_LEVEL<2) die("<h1>Not allowed for access level:$SSI_LEVEL</h1>");
	if ($vidwinner==$vidloser) die("<h3>Spieler sind ident ....</h3>$sipgoback");

	$qry=sql_query("select max(rgid) from tresult",$dbi);
	while(list($vlastID)=sql_fetch_row($qry,$dbi)){
		$nextgameID=$vlastID;
	}
	$vdesc=strip_tags($vdesc,"<b><i>");

	$nextgameID=$nextgameID+1;

	ssilog(2,$user,"<b>INSERT REQUEST GAME</b> Winner: $vidwinner LOSER:$vidloser");
	$qry="insert into tresult values(0,$nextgameID,$vidwinner,1,'$vdate','$vdesc',0)";
	$res = sql_query($qry,$dbi);
	$qry="insert into tresult values(0,$nextgameID,$vidloser,0,'$vdate','$vdesc',0)";
	$res = sql_query($qry,$dbi);
	#
	# if success then update SSI-count for player +1
	#
	$qry="update tplayer set sipcount=sipcount+1 where pid=$vidwinner";
	$res=sql_query($qry,$dbi);
	$qry="update tplayer set sipcount=sipcount+1 where pid=$vidloser";
	$res=sql_query($qry,$dbi);
}
*/

function historyplayer($vpid){
	# //
	# // display personal SSI history per player
	# //
	global $dbi;

	echo "<h3>SSI History Player $vpid</h3>";
	$qry=sql_query("select pid,pfname,plname,statdate,statval from tplayer,tblstat where pid=statpid and pid=$vpid and statcode=2 order by statdate asc",$dbi);
	OpenTable();
	echo "<table>";
	while(list($pid,$pfname,$plname,$sipdate,$sippoints)=sql_fetch_row($qry,$dbi)){
		echo "<tr><td>$sipdate</td><td>$pfname $plname</td><td><b>".number_format($sippoints,2,'.','')."</b></td></tr>";
	}
	echo "</table>";
	CloseTable();
	echo "<br>";
	echo MakeSSIChart($vpid);
}


function showpersonal($vpid){
	# displays dialog + personal chances against other SSI players
	# displays only registered players with more than $mingames (4)
	global $dbi,$user,$sipgoback,$lastdate,$predpic,$pgreenpic,$porangepic,$minsip;

	$trecords=sql_query("select pid,pfname,plname,psipstatus,statval,sipcount from tplayer,tblstat"
			." where pid=statpid and sipcount>$minsip and statdate = '$lastdate' and statcode=2 order by plname asc",$dbi);
	$optionstring="";
	while (list($pid,$pfname,$plname,$psipstatus,$sippoints,$sipcount)=sql_fetch_row($trecords,$dbi)){
		if ($vpid == $pid) {
			$strplayer = $plname.' '.$pfname.' ('.number_format($sippoints,2,'.','').')';
			$sipplayer = $sippoints;
			$optionstring=$optionstring.'<option selected value='.$pid.'>'.$plname.' '.$pfname.'</option>';
		} else {
			$optionstring=$optionstring.'<option value='.$pid.'>'.$plname.' '.$pfname.'</option>';
		}
	}

	#OpenTable();
	echo '<h3>Pers&ouml;nliche SSI +/- Tabelle</h1>'
	."<p>Gew&uuml;nschten Spieler aussuchen. F&uuml;r diesen Spieler werden die SSI +/- gegen alle anderen Spieler berechnet, die bereits einen g&uuml;ltige SSI Wert besitzen. Die SSI Zahl ist vom Stichtag $lastdate.</p>";
	echo "<form action=\"?func=personal\" method=\"post\"><table><tr>"
	.'<td><select name=vpid>'.$optionstring.'</select></td>'
	.'<td>'._button('SSI +/- Werte berechnen').'</td>'
	.'</tr></table></form>';
	#CloseTable();

	if (strlen($strplayer) > 0){
		echo "<h3>SSI Tabelle f&uuml;r $strplayer</h3>";
	} else {
		die("<i>Einen Spieler aussuchen und auf Berechnen klicken.</i>");
	}
	# // execute this block only if a player has been selected ..

	mysql_data_seek($trecords,0);	#// move record pointer
	$aTH=array('Status','Gegner','Wert','Verlieren','Gewinnen','Gewinnwahrscheinlichkeit');
	
	OpenTable();
	echo ArrayToTableHead($aTH);

	while (list($pid,$pfname,$plname,$psipstatus,$sippoints,$sipcount)=sql_fetch_row($trecords,$dbi)){
		echo '<tr><td>';
		switch($psipstatus){
			case "0":
			echo $predpic;
			break;
			case "1":
			echo $porangepic;
			break;
			case "2":
			echo $pgreenpic;
			break;
		}
		echo '</td><td>'.$pfname.' '.$plname.'</td><td>'.number_format($sippoints,2,'.','').'</td>';
		# calculate Player Loses //////////
			$valLost=ReturnSSIChangePlayer($sipplayer,$sippoints,0);
			echo '<td><font color="red">-'.number_format($valLost,2,'.','').'</font></td>';
		# calculate PLAYER Winner /////////
			$valWin=ReturnSSIChangePlayer($sipplayer,$sippoints,1);
			$expWin=ReturnWinExpectancy($sipplayer,$sippoints);
			echo '<td>+'.number_format($valWin,2,'.','').'</td><td>'.number_format($expWin,2,'.','').'%</td></tr>';
	}
	echo '</table>';
	CloseTable();
}

/**
 * This is for starting the whole SSI System or can be used to reset all values ...
 * when starting the system set all players to START VALUE of 1670 (median of system 2005)
 */
function initialload(){
	global $dbi;
	$startdate='2003-07-01';
	$startval=1670;
	
	$trecords=sql_query("select pid from tplayer order by plname",$dbi);
	while (list($vpid)=sql_fetch_row($trecords,$dbi)){
		$res = sql_query("INSERT into tblstat values (0,'$startdate',2,1670,$vpid)",$dbi);
	}
	# reset counters to ZERO
	$res = sql_query("update tplayer set psipstatus=0 where pid>0",$dbi);
	$res = sql_query("update tplayer set sipcount=0 where pid>0",$dbi);
}

if (isset($_REQUEST['func'])) {$func=$_REQUEST['func'];} else {$func="";}
if (isset($_REQUEST['vdate1'])) {$vdate1=$_REQUEST['vdate1'];} else {$vdate1="";}
if (isset($_REQUEST['vpid']) && is_numeric($_REQUEST['vpid'])) {$v_pid=$_REQUEST['vpid'];} else {$v_pid=0;}
if (isset($_REQUEST['vrgid']) && is_numeric($_REQUEST['vrgid'])) {$v_rgid=$_REQUEST['vrgid'];} else {$v_rgid=0;}

switch($func) {

    default:
    	ssiranking();
    	break;

	case "delete":
	delssicurrent($vsid);
	ssiranking("lv");
	break;

	case "saveresultn":
	#saveresult($vdate,$vidwinner,$vidloser,$vdesc);
	#newresult($vdate,$vdesc,$vfilter);
	break;

	case "editgame":
	editgame($v_rgid);
	break;

	case "changegame":
	#changegame($v_rgid,$vrdate,$vrdesc,$vrresultw,$vrresultl);
	editgame($v_rgid);
	break;

	case "listgames":
	listgames($vdate1);
	break;

	case "listplayer":
	listgamesplayer($v_pid);
	break;

	case "newresult":
	#newresult($vdate,$vdesc,"A");
	break;

	case "historyplayer":
	historyplayer($v_pid);
	break;

	case "personal":
	showpersonal($v_pid);
	break;
	
	case "preload":
	initialload();
	ssiranking();
	break;
}

# just in case we close main div
echo '</div>';
LS_page_end();
?>
