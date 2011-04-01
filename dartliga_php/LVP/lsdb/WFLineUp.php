<?php

	// file: WFLineUp.php
	// Output: $presult
	// Purpose: LineUp controller for POST requests
	// Example: lsdb/LineUp.php?action=remove&lineupid=
	// returns 1 = success 0 = failure X= no access
	#TODO: Security ???(we have the lsdb cookie in the header ...)
	
if ($_SERVER['REQUEST_METHOD']<>'POST') die("Y");
foreach ($_POST as $secvalue) {
    if (eregi("<[^>]*script*\"?[^>]*>", $secvalue) OR eregi("\([^>]*.*\"?[^>]*\)", $secvalue)) {
	die ("X");
    }
}

	include("../code/config.php");
	require_once("../includes/sql_layer.php");
	require_once("../func_sec.php");
	
	// VAR CHECKS 
	if (isset($_POST['wfaction']) && $_POST['wfaction']<>"undefined") {$myAction=utf8_decode(strip_tags($_POST['wfaction']));}else{$myAction="";};
	if (isset($_POST['wflineupid']) && $_POST['wflineupid']<>"undefined") {$wflineup_id=utf8_decode(strip_tags($_POST['wflineupid']));}else{$wflineup_id=0;};
	if (isset($_POST['wfrequestid']) && intval($_POST['wfrequestid'])>0) {$wfrequest_id=utf8_decode(strip_tags($_POST['wfrequestid']));}else{$wfrequest_id='NULL';};
	if (isset($_POST['teamid']) && intval($_POST['teamid'])>0) {$team_ID=utf8_decode(strip_tags($_POST['teamid']));}else{$team_ID='NULL';};
	if (isset($_POST['wfteamid']) && intval($_POST['wfteamid'])>0) {$wfteam_ID=utf8_decode(strip_tags($_POST['wfteamid']));}else{$wfteam_ID='NULL';};
	if (isset($_POST['pid']) && intval($_POST['pid'])>0) {$player_ID=utf8_decode(strip_tags($_POST['pid']));}else{$player_ID='NULL';};
	if (isset($_POST['wfpid']) && intval($_POST['wfpid'])>0) {$wfplayer_ID=utf8_decode(strip_tags($_POST['wfpid']));}else{$wfplayer_ID='NULL';};
	if (isset($_POST['wfeventid']) && intval($_POST['wfeventid'])>0) {$wfevent_ID=utf8_decode(strip_tags($_POST['wfeventid']));}else{$wfevent_ID='NULL';};
	if (isset($_POST['lcomment']) && $_POST['lcomment']<>"undefined") {$comment=utf8_decode(strip_tags($_POST['lcomment']));}else{$comment='';};
	if (isset($_POST['rkey']) && $_POST['rkey']<>"undefined") {$rkey=utf8_decode(strip_tags($_POST['rkey']));}else{$rkey='NULL';};
	$dbi = sql_connect($dbhost, $dbuname, $dbpass, $dbname);
	
	$usertoken=initLsdbSec($dbi);
	if ($usertoken['usertype_id']<2){echo "<font color=\"red\">E1</font>";return;};
	if ($usertoken['verein_id']<1)$usertoken['verein_id']=0;
	
function _blank(){
	echo "<font color=red>Error</font>";
}

function _removewf($wflineup_id,$wfteam_ID){
	global $dbi;
	$qry='DELETE from wflineup where wflineup_id='.$wflineup_id.' AND wfteam_id='.$wfteam_ID.' limit 1';
	#debug($qry);
	$p1=sql_query($qry,$dbi);
	if ($p1==1) {echo '<font color=\'green\'>'.$p1.':Success removing LineUp ('.$wflineup_id.')</font>';
	} else {echo '<font color=\'red\'>'.$p1.':Error removing LineUp ('.$wflineup_id.')</font>';}
}

function _savewfteam($wfrequest_id,$rkey,$wflineup_id,$team_ID,$wfteam_ID,$player_ID,$wfplayer_ID,$wfevent_ID,$comment){
	// take action depending on the lineupID, usually we have a insert only here ...
	// case 1 -> wfteam/wfevent_ID + player
	// case 2 -> wfteam/wfevent_ID + wfplayer
	// case 3 -> team + player
	// case 4 -> team + wfplayer
	global $dbi;
	if ($wflineup_id>0){
		$qry="UPDATE wflineup set team_id=$team_ID,wfteam_id=$wfteam_ID, player_id=$player_ID,wfplayer_id=$wfplayer_ID,"
		."wfevent_id=$wfevent_ID,playertype_id=1,lcomment=\"$comment\" where wfrequest_id=$wfrequest_id";
	}else {
		$qry="INSERT into wflineup(wflineup_id,wfrequest_id,rkey,team_id,wfteam_id,player_id,wfplayer_id,wfevent_id,playertype_id,lcomment)"
		." VALUES(0,$wfrequest_id,\"$rkey\",$team_ID,$wfteam_ID,$player_ID,$wfplayer_ID,$wfevent_ID,1,\"$comment\")";
	}
	$p1=sql_query($qry,$dbi);
	// The request messagegroup is set based on the team-event combination 
	// .... the wfplayer (save) sets based on membership ...
	/*
	 * try the real teams -> then try the WFTeams ....
	 */
	if ($team_ID>0){
		$pT=sql_query('SELECT T.*,TE.* from tblteam T left join tblevent E on T.tevent_id=E.id left join ttypeliga TE on E.evtypecode_id=TE.id where T.id='.$team_ID,$dbi);
		$aT=sql_fetch_array($pT,$dbi);
	} elseif ($wfteam_ID>0){
		$pT=sql_query('SELECT T.*,TE.* from wfteam T left join wfevent E on T.wfevent_id=E.wfevent_id left join ttypeliga TE on E.evgroup_id=TE.id where T.wfteam_id='.$wfteam_ID,$dbi);
		$aT=sql_fetch_array($pT,$dbi);
	}
	#debug($aT);
	$p2=sql_query('UPDATE wfrequest set mgroup_id='.$aT['mgroup_id'].' WHERE wfrequest_id='.$wfrequest_id,$dbi);
	if ($p1+$p2==2) {echo '<font color=\'green\'>'.$p1.'/'.$p2.':Success saving LineUp ('.$player_ID.')</font>';
	} else {echo '<font color=\'red\'>'.$p1.'/'.$p2.':Error saving LineUp ('.$player_ID.')</font>';}
}

switch($myAction){
	default:
	_blank();
	break;

	case "savewf":
	_savewfteam($wfrequest_id,$rkey,$wflineup_id,$team_ID,$wfteam_ID,$player_ID,$wfplayer_ID,$wfevent_ID,$comment);	
	break;
	
	case "removewf":
	_removewf($wflineup_id,$wfteam_ID);
	break;	
}
?>