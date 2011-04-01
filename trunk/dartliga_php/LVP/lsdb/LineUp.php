<?php
	/*
	// file: LineUp.php
	// Output: $presult
	// Purpose: LineUp controller for POST requests (vars come in the GET string)
	// Example: lsdb/LineUp.php?action=remove&lineupid=
	//			lsdb/LineUp.php?action=change&lineupid=&typeid=2
	// returns 1 = success 0 = failure X= no access
	*/

if ($_SERVER['REQUEST_METHOD']<>'POST') die('Y');
foreach ($_POST as $secvalue) {
    if (eregi("<[^>]*script*\"?[^>]*>", $secvalue) OR eregi("\([^>]*.*\"?[^>]*\)", $secvalue)) {
	die ("X");
    }
}

	include("../code/config.php");
	require_once("../includes/sql_layer.php");
	require_once("../func_sec.php");
	
	// VAR CHECKS
	if (isset($_POST['action'])) {$myAction=utf8_decode(strip_tags($_POST['action']));}else{$myAction="";};
	if (isset($_POST['lineupid']) && is_numeric($_POST['lineupid'])) {$lineup_ID=utf8_decode(strip_tags($_POST['lineupid']));}else{$lineup_ID=0;};
	if (isset($_POST['teamid']) && is_numeric($_POST['teamid'])) {$team_ID=utf8_decode(strip_tags($_POST['teamid']));}else{$team_ID=0;};
	if (isset($_POST['playerid']) && is_numeric($_POST['playerid'])) {$player_ID=utf8_decode(strip_tags($_POST['playerid']));}else{$player_ID=0;};
	if (isset($_POST['eventid']) && is_numeric($_POST['eventid'])) {$event_ID=utf8_decode(strip_tags($_POST['eventid']));}else{$event_ID=0;};
	if (isset($_POST['typeid']) && is_numeric($_POST['typeid'])) {$type_ID=utf8_decode(strip_tags($_POST['typeid']));}else{$type_ID=1;};
	
	$dbi = sql_connect($dbhost, $dbuname, $dbpass, $dbname);
	$usertoken=initLsdbSec($dbi);
	
	$ac=$usertoken['eventmap'][$event_ID];
	#$ac=3;
	if ($ac<3) die("<b>X2</b>");
	
function _blank(){
	echo "<b>X</b><br>";
}

function _remove($lineup_ID){
	global $dbi;
	$qry="delete from tblteamplayer where lid=$lineup_ID limit 1";
	$presult=sql_query($qry,$dbi);
	if ($presult==1) {echo 1;} else {echo 0;}
}

function _save($lineup_ID,$event_ID,$team_ID,$player_ID){
	// take action depending on the lineupID, usually we have a insert only here ...
	global $dbi;
	if ($lineup_ID>0){
		$qry="UPDATE tblteamplayer set leventid=$event_ID, lteamid=$team_ID, lplayerid=$player_ID where lid=$lineup_ID";
	}else {
		$qry="INSERT into tblteamplayer(lid,leventid,lteamid,lplayerid,lactive,ltype) values(0,$event_ID,$team_ID,$player_ID,1,1)";
	}
	$presult=sql_query($qry,$dbi);
	if ($presult==1) {echo 1;} else {echo 0;}
}
/**
*	purpose:	change the lineup Type player/captain/farmer/banned
* 	params:	
*	returns:	message string (green/red)
*/
function _change($lineup_ID,$type_ID){
	global $dbi;
	if ($lineup_ID>0){
		$qry='UPDATE tblteamplayer set ltype='.$type_ID.' WHERE lid='.$lineup_ID.' limit 1';
		$presult=sql_query($qry,$dbi);
	} else {
		$presult=0;
	}
	if ($presult==1) {echo "<font color=\"green\">Success</font>";
	} else {echo "<font color=\"red\">Error</font>";}
}

switch($myAction){
	default:
		_blank();
		break;

	case "save":
		_save($lineup_ID,$event_ID,$team_ID,$player_ID);	
		break;
	
	case "remove":
		_remove($lineup_ID);
		break;	
	
	case "change":
		_change($lineup_ID,$type_ID);
		break;
}
?>