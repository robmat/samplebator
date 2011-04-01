<?php

# //
# // LS_statsadmin
# // only for access level 3 (main) and SSI (3)
# // controls to trigger or delete a) ssi ranking tables b)feda ranking tables 
# // for a predefined DATE (from stats_date.php)
# // v 0.1 BH 01.2007 SSI create / delete working !
#

# // creation of table:
# // create table tblstat(statid int not null auto_increment primary key,statdate date,statcode int not null,statval double not null default 0,statpid int not null);
# // load existing SSI values from tsipcurrent
# // insert into tblstat select sipid,sipdate,2,sippoints,sippid from tsipcurrent;

include("stats_main.php");
include("func_stat.php");

if ($usertoken['usertype_id']<4) die("<h3>No access to the Statistics Editor ...</h3>");

$allSSIDateQueryD="select sdate from tbldate where sstatcode_id=2 order by sdate desc";
$allSSIDateQueryA="select sdate from tbldate where sstatcode_id=2 order by sdate asc";
$allFEDADateQuery="select sdate from tbldate where sstatcode_id=3 order by sdate desc";
$thisfile="stats_admin.php";
// definitions from ttypestat
$statcodeSSI=2;
$statcodeFEDAM=3;
$statcodeWDV=10;
$statcodeFEDAD=5;

# // Beginn Funktionen ----------------
function _blank(){
	#
	# // show the controls
	# // description + pair of date selector - delete and create buttons
	# // show table with a group by statement counted by date as feedback control ...
	#
	global $statcodeSSI,$statcodeFEDAM,$statcodeWDV,$statcodeFEDAD,$thisfile;
	
	echo "<h3>Spieler Statistik Administration</h3>";
	echo "<p>Zugang zu diesen Funktionen haben nur Administratoren mit Level 3 aus dem Liga System oder der SSI Wertung. Diese Funktionen hier dienen der Erstellung einer statischen Rangliste f&uuml;r einen gewissen Zeitpunkt. Die Werte aus diesen statischen Listen werden dann an unterschiedlichen Stellen angezeigt und auch verwendet.<br>Alle auf den Statistik Seiten der Ligen angezeigte Listen oder Auswertungen k&ouml;nnen als statische Listen mit einem Datum versehen gespeichert werden.</p>";
	
	## FEDA MIXED
	echo "<table width=100%><tr><td width=30% valign=top>Existing FEDA Mixed Werte<br>";
	OpenTable();
	echo _retEntryCounts($statcodeFEDAM);
	CloseTable();
	echo "</td><td width=70% valign=top>";
	echo "<form action=\"$thisfile\" method=\"post\">"
	."<fieldset><legend>FEDA MIX Ranking Erzeugen</legend>";
	echo Select_StatDate($statcodeFEDAM,'','vindexdate');
	echo _input(0,"func","runfedacalc");
	echo _input(0,"statcode",$statcodeFEDAM);
	echo _button("Create");
	echo "<p>Erzeugt eine neue FEDA MIX Rangliste f&uuml;r <b>alle</b> Spieler die jemals an diesem Bewerb teilgenommen haben. Werden f&uuml;r das letzte Jahr keine LEGS gefunden so wird kein Statistik Wert gespeichert. Nach dem Erzeugen der neuen Werte wird in allen Ranglisten automatisch immer auf den aktuellen Wert Bezug genommen</p>";
	echo "</fieldset></form>";
	echo "<form action=\"$thisfile\" method=\"post\">"
	."<fieldset><legend>FEDA MIX Ranking L&ouml;schen</legend>";
	echo Select_StatDate($statcodeFEDAM,'','vindexdate');
	echo _input(0,"func","delfedacalc");
	echo _input(0,"statcode",$statcodeFEDAM);
	echo _button("Delete");
	echo "<p>Bist du sicher dass du alle FEDA Ranglisten Werte aller Spieler f&uuml;r ein bestimmtes Datum <b>l&ouml;schen</b> willst ?? Anschliessend muss die Rangliste wieder berechnet werden sonst kann keine Aktuelle angezeigt werden.<br><i>Diese Funktion dient ausschliesslich zum Software-Debugen oder bei krassen Fehleingaben ...</i></p>";
	echo "</fieldset></form>";
	echo "</td></tr></table>";
	
	## FEDA DAMEN
	echo "<table width=100%><tr><td width=30% valign=top>Existing FEDA Damen Werte<br>";
	OpenTable();
	echo _retEntryCounts($statcodeFEDAD);
	CloseTable();
	echo "</td><td width=70% valign=top>";
	echo "<form action=\"$thisfile\" method=\"post\">"
	."<fieldset><legend>FEDA Damen Ranking Erzeugen</legend>";
	echo Select_StatDate($statcodeFEDAD,'','vindexdate');
	echo _input(0,"func","runfedacalc");
	echo _input(0,"statcode",$statcodeFEDAD);
	echo _button("Create");
	echo "<p>Erzeugt eine neue FEDA DAMEN Rangliste f&uuml;r <b>alle</b> Spielerinnen die jemals an diesem Bewerb teilgenommen haben. Werden f&uuml;r das letzte Jahr keine LEGS gefunden so wird kein Statistik Wert gespeichert. Nach dem Erzeugen der neuen Werte wird in allen Ranglisten automatisch immer auf den aktuellen Wert Bezug genommen</p>";
	echo "</fieldset></form>";
	echo "<form action=\"$thisfile\" method=\"post\">"
	."<fieldset><legend>FEDA Damen Ranking L&ouml;schen</legend>";
	echo Select_StatDate($statcodeFEDAD,'','vindexdate');
	echo _input(0,"func","delfedacalc");
	echo _input(0,"statcode",$statcodeFEDAD);
	echo _button("Delete");
	echo "<p>Bist du sicher dass du alle FEDA Ranglisten Werte aller Spieler f&uuml;r ein bestimmtes Datum <b>l&ouml;schen</b> willst ?? Anschliessend muss die Rangliste wieder berechnet werden sonst kann keine Aktuelle angezeigt werden.<br><i>Diese Funktion dient ausschliesslich zum Software-Debugen oder bei krassen Fehleingaben ...</i></p>";
	echo "</fieldset></form>";
	echo "</td></tr></table>";

	## SSI
	echo "<table width=100%><tr><td width=30% valign=top>Existing SSI Values<br>";
	OpenTable();
	echo _retEntryCounts($statcodeSSI);
	CloseTable();
	echo "</td><td width=70% valign=top>";
	echo "<form action=\"$thisfile\" method=\"post\">"
	."<fieldset><legend>SSI Ranking Erzeugen</legend>";
	echo Select_StatDate($statcodeSSI,'','vindexdate');
	echo _input(0,"statcode",$statcodeSSI);
	echo _input(0,"func","runssicalc");
	echo _button("Create");
	echo "<p>Nach dem Erzeugen der neuen Werte wird in allen Ranglisten automatisch immer auf den aktuellen Wert Bezug genommen</p>";
	echo "</fieldset></form>";
	echo "<form action=\"$thisfile\" method=\"post\">"
	."<fieldset><legend>SSI Ranking L&ouml;schen</legend>";
	echo Select_StatDate($statcodeSSI,'','vindexdate');
	echo _input(0,"func","delssicalc");
	echo _input(0,"statcode",$statcodeSSI);
	echo _button("Delete");
	echo "<p>Bist du sicher dass du alle SSI Werte aller Spieler ab einem bestimmten Datum <b>l&ouml;schen</b> willst ?? Anschliessend m&uuml;ssen alle Stichtage wieder manuell berechnet werden, bzw <b>alle eingesch&auml;tzten</b> Spieler mit neuen Werten versehen werden.<br><i>Diese Funktion dient ausschliesslich zum Software-Debugen oder bei krassen Fehleingaben ...</i></p>";
	echo "</fieldset></form>";
	echo "</td></tr></table>";

}


function _retEntryCounts($syscode){
	#
	# returns a table body (rows only) with Date + Num Entries for a passed systemcode
	#
	global $dbi;
	$retstr="";
	$qry="select statdate,count(statdate) C from tblstat where statcode=$syscode group by statdate order by statdate desc";
	$p=sql_query($qry,$dbi);
	while(list($d,$v)=sql_fetch_row($p,$dbi)){
		$retstr=$retstr."<tr><td>$d</td><td>$v</td></tr>";
	}
	return $retstr;
}


function _createStatsEntries($vsyscode,$vdatenew){
	# create static table entries for syscode and passed date
	# check on the syscode and take respective action for different systems 
	
	global $allSSIDateQueryA,$dbi;
	echo "<h3>Creating for $vsyscode,$vdatenew</h3>";
	switch($vsyscode) {
		case "2":
			# // the calculation function requires a period of 2 dates ...
			# // target is known - get the previous ... (max from tblstat not possible)
			$trecords=sql_query($allSSIDateQueryA,$dbi);	#//ascending
			while (list($tdate)=sql_fetch_row($trecords,$dbi)){
				if ($tdate < $vdatenew) $vstartdate=$tdate;
				}
			#echo "<br>_calculatessi($vstartdate,$vdatenew)";
			generateStaticSSIStatisticEntries($vstartdate,$vdatenew);		# // this produces some output ....
		break;
		
		case "3":
			generateStaticFEDAStatisticEntries(3,$vdatenew,'yes');	# produces some output ....
		break;
		
		case "5":
			generateStaticFEDAStatisticEntries(5,$vdatenew,'yes');	# produces some output ....
		break;
	}
}

function _deleteStatsEntries($vsyscode,$vdeldate){
	# delete selected static table entries for syscode and passed date
	
	global $dbi,$user,$SSI_LEVEL;
	if (!isset($vdeldate)) die ("No date selected");
	if ($SSI_LEVEL<3) die("<h1>Not allowed ...</h1>");
	
	$deleteqry="DELETE FROM tblstat WHERE statdate='$vdeldate' and statcode=$vsyscode";
	$res1 = sql_query($deleteqry,$dbi);
	if ($res1) echo "(".$res1.") ".$deleteqry;
	dsolog(3,$user,"DELETED ALL STATS Records for ($vdeldate)");
}

################ WORKING EXECUTION FUNTIONS ####################
################################################################


if (isset($_POST['func']) && strlen($_POST['func'])<20){$myfunc=strip_tags($_POST['func']);}else{unset($myfunc);$myfunc='';}
if (isset($_POST['vindexdate']) && strlen($_POST['vindexdate'])<20){$stat_date=strip_tags($_POST['vindexdate']);}else{unset($stat_date);}
if (isset($_POST['statcode']) && is_numeric($_POST['statcode'])){$stat_code=strip_tags($_POST['statcode']);}else{unset($stat_code);}

switch($myfunc) {

	default:
	_blank();
	break;
	
	case "runfedacalc":
	_createStatsEntries($stat_code,$stat_date);
	break;
	
	case "delfedacalc":
	_deleteStatsEntries($stat_code,$stat_date);
	_blank();
	break;
	
	case "runssicalc":
	_createStatsEntries($stat_code,$stat_date);
	break;
	
	case "delssicalc":
	_deleteStatsEntries($stat_code,$stat_date);
	_blank();
	break;
	
}


# just in case we close main div
echo '</div>';
LS_page_end();

?>
