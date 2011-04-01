<?php
/*
 * myteam.php
 * ==========================
 * this could be the public entry for showing
 * a individual team information page
 * v3 8.2007 BH
 */
include("ls_main.php");

function _teampage($team_id) {
	/*
	 * generate the teampage
	 */
	global $dbi;
	$RS=DB_listMatches($dbi,1,0,$team_id,'','','','','raw');
	$aTH=array('Liga','Runde','Datum','Location','Sets','Legs','Team');
	$ROWS=RecordsetToDataTable($RS,array(1,4,5,6,9,10,12));
	/*
	 * Output
	 */
	echo '<h3>Team Schedule</h3>';
	echo '<p><table>'.ArrayToTableHead($aTH).$ROWS.'</table></p>';
	echo '<h3>Team Averages</h3>';
	echo '<p>Chronologische Darstellung der erzielten Averages in allen Legs aller eingesetzen Spieler eines Teams. Aus dieser Kurve lassen sich Leistungssteigerungen und Einbr&uuml;che ableiten.<i>Falls Spieler gemeldet aber nie eingesetzt wurden so werden NULL werte angezeigt ...</i></p>';
	echo getTeamAvgHist($team_id);
}

/**
 * Show a team selector + generate button
 *
 * @param int $param
 */
function _blank($param){
	echo "<h3>Team Information Generator</h3>";
	echo "<p>Select your Team from the drop-down box and click the generate button.</p>";
	echo "<p><form action=\"myteam.php?func=generate\" method=\"post\">";
	echo '<table><tr><td>'.Select_Team('tid','',$param,0).'</td><td>'._button("Generate").'</td></tr></table>';
	echo '</form></p><div id=\'maincontent\'>';
}

if (isset($_REQUEST['tid']) && intval($_REQUEST['tid'])>0) {$t_id=strip_tags($_REQUEST['tid']);}else{$t_id=0;};

switch($func) {
	default:
		_blank($t_id);
		break;
		
	case "generate":
		_blank($t_id);
		_teampage($t_id);
		break;
}
# just in case we close main div
echo '</div>';
LS_page_end();
?>