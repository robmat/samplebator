<?php
/*
 * file ls_verein.php
 * purpose: all verein relevant information from league system
 * functions: list of teams+matches for direct access
 */
include("empty_main.php");

# // Begin Functions ----------------
function _main($eventid){
	// empty ...
}

	/*function:	_vereinteammatchlist
	 * purpose: 	show a list of all teams and all team-matches ...
	 * no access check in here this is a static list and basically PUBLIC ...
	 * param: 	vid = verein_id(int)
	 * 				show=home/away/all
	 * v4
	 */ 
function _vereinteammatchlist($vid,$show='home'){
	global $dbi;

	// step 1 collect the teams for this verein
	// step 2 for each team we collect the schedule and create click-rows to the matchsheet
	
	echo '<h3>Alle Vereins Teams und ihre Heim Spiele</h3>';

	$RS=DB_listTeams($dbi,0,0,"",1,"",$vid);
	foreach ($RS as $row){
		if($show=='home'){
			$MATCHLIST=DB_listMatches($dbi,1,0,$row[4],"","",1);
		}elseif($show='away'){
			$MATCHLIST=DB_listMatches($dbi,1,0,$row[4],"","",0);
		}elseif($show='all'){ // this doesn't work ...
			$MATCHLIST=DB_listMatches($dbi,1,0,$row[4],"","","","","logic");
		}
		$aTH=array("Runde","Datum","Spielort","Status","","Sets","Legs","Team","Teamname");
		echo "<div class=\"sectionhead\">Team $row[5] in $row[2] (".sizeof($MATCHLIST)." Matches)</div><br/>";
		echo '<div class="child">';
		OpenTable();
		echo ArrayToTableHead($aTH);
		echo RecordsetToClickTable($MATCHLIST,4,"ls_system.php?func=showmatch&vmkey=%P1%&eventid=%P2%",3,0);
		CloseTable();
		echo '</div><br/>';
	}
}

	/** _vereinplayerrankinghistory
	 * show history details for a specific player ... graphic ??
	 */
function _vereinplayerrankinghistory($pid,$statcode){
	global $dbi;
	
	// OUTPUT

	echo "<h3>History Player $pid in Ranking $statcode</h3>";
	
}

	/** _vereinplayerranking
	 * zeigt for alle vereinsspieler ihren ranglistenwert an
	 */
function _vereinplayerranking($verein_id,$statcode){
	global $dbi;
	echo '<h3>Spielerliste meines Vereins</h3>';
	echo Select_StatGroup('statcode',$statcode,"vereinstatcodeplayer(".$verein_id.")");
	echo '<div>W&auml;hle einen Ranglisten/Statistik Typ. Anschliessend werden alle Spieler angezeigt. Ein Klick auf den Spieler zeigt dann Details zu seinem Ranglisten Wert.</div><br/>';
	echo "<div id=\"qry\"></div>";
}


if (isset($_REQUEST['eventid']) && intval($_REQUEST['eventid'])>0) {$event_id=strip_tags($_REQUEST['eventid']);}else{$event_id=0;};
if (isset($_REQUEST['func'])&& $_REQUEST['func']<>"undefined") {$myfunc=strip_tags($_REQUEST['func']);}else{$myfunc='NULL';};
if (isset($_REQUEST['statcode']) && intval($_REQUEST['statcode'])>0) {$stat_code=strip_tags($_REQUEST['statcode']);}else{$stat_code=0;};
if (isset($_REQUEST['vid']) && intval($_REQUEST['vid'])>0) {$verein_id=strip_tags($_REQUEST['vid']);}else{$verein_id=0;};
if (isset($_REQUEST['show'])&& $_REQUEST['show']<>"undefined") {$show_match=strip_tags($_REQUEST['show']);}else{$show_match='NULL';};

switch($myfunc) {
	case "teammatches":
		_vereinteammatchlist($verein_id,$show_match);
		break;
	
	case "playerranking":
		_vereinplayerranking($verein_id,$stat_code);
		break;
		
	default:
		_main($event_id);
	break;
	
}


# just in case we close main div
echo '</div>';
LS_page_end();
?>
