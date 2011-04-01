<?php
/*
 * file: 			lsdb_sys.php
 * purpose:	new lsdb league interface v4 or v5
 * tech:			using ajax calls to render gamedata into div tags
 */
include('ls_main.php');

/** @_axTeamPerformancePage
*	purpose:	create PAGE VIEW for Team performance Graphs
* 	params:		event_id, team_id
*	returns:	empty page with teamselector and empty graph canvass ...
*/
function _axTeamPerformancePage($eventid=0,$tid=0){
	global $dbi,$event;
	# OUTPUT
	echo '<script language="JavaScript" src="code/legdatagraph.js"></script>';
	echo setpagetitle('Team Leistungen im direkten Vergleich '.$event['evname']);
	echo form_TeamSelect('','teamperf('.$eventid.','.$event['evstatcode_id'].',1)',$tid,$eventid,0);
	echo '<p>Der Graph zeigt die Verteilung der H&auml;ufigkeit der Dartsanzahl, die ben&ouml;tigt wurde um ein Leg zu <b>gewinnen</b>. Zur Ermittlung der Werte werde nur alle gewonnenen Legs aller Teamspieler in dieser betreffenden Liga herangezogen. Selbstverst&auml;ndlich kann nur eine Verteilung generiert werden wenn für diese betreffende Liga die <b>Darts-Werte pro Leg</b> erfasst wurden.</p>';
	echo '<div id="JG" style="position:relative;height:300px;width:700px"></div>';
	echo '<div id="lineUpPerf"></div>';
	echo '<div id="lineUp"></div>';
}

########################
# PARAM CHECK
########################
$event_id=0;
$team_id=0;
$match_key='';
$myfunc='';
if (isset($_REQUEST['eventid'])) {
	if (!is_numeric($_REQUEST['eventid'])) {die("<h3>Error EventID</h3>");} 
	else {$event_id=$_REQUEST['eventid'];}
}
if (isset($_REQUEST['tid'])) {
	if (!is_numeric($_REQUEST['tid'])) {die("<h3>Error TeamID</h3>");}
 	else {$team_id=$_REQUEST['tid'];}
}
if (isset($_REQUEST['vmkey'])) {
	if (!strlen($_REQUEST['vmkey'])>9) {die( "<h3>Error MatchKey</h3>");}
 	else {$match_key=strip_tags($_REQUEST['vmkey']);}
}
if (isset($_REQUEST['func'])) {
	if (strlen($_REQUEST['func'])>10) {die("<h3>Error FuncOP</h3>");}
 	else {$myfunc=strip_tags($_REQUEST['func']);}
}

switch($myfunc){
	case "teamperf":
		_axTeamPerformancePage($event_id,$team_id);
		break;
		
	default:
		break;
}
# just in case we close main div
echo '</div>';
LS_page_end();
?>