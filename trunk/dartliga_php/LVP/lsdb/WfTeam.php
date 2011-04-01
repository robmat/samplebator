<?php
/**
*	purpose:	WFTeam controller for ajax events
* 				creates the message_group entry according to the wfevent of the wfteam
* 	params:		lots of them
*	returns:	X,0,1, sMsg
*/
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
	if (isset($_POST['wfrequestid']) && intval($_POST['wfrequestid'])>0) {$wfrequest_id=strip_tags(utf8_decode($_POST['wfrequestid']));}else{$wfrequest_id='NULL';};
	if (isset($_POST['wftid']) && intval($_POST['wftid'])>0) {$wfteam_id=strip_tags($_POST['wftid']);}else{$wfteam_id='NULL';};
	if (isset($_POST['wfevent'])&& intval($_POST['wfevent'])>0) {$event_id=strip_tags($_POST['wfevent']);}else{$event_id='NULL';};
	if (isset($_POST['wftname'])&& $_POST['wftname']<>"undefined") {$team_name=strip_tags(utf8_decode(strip_tags($_POST['wftname'])));}else{$team_name="";};
	if (isset($_POST['wftloc'])&& intval($_POST['wftloc'])>0) {$location_id=strip_tags($_POST['wftloc']);}else{$location_id='NULL';};
	if (isset($_POST['wftcomment'])&& $_POST['wftcomment']<>"undefined") {$tcomment=strip_tags(utf8_decode(urldecode($_POST['wftcomment'])));}else{$tcomment='NULL';};
	$dbi = sql_connect($dbhost, $dbuname, $dbpass, $dbname);
	
	$usertoken=initLsdbSec($dbi);
	if ($usertoken['usertype_id']<2){echo '<font color="red">E:WFT01:TypeError</font>';return;};
	
	$qry='';
	if (!$wfteam_id>0){
		debug('E:WFT10:NoTeamID');
	} else{
		$qry="UPDATE wfteam set wfevent_id=$event_id, location_id=$location_id, teamname='$team_name',tcomment='$tcomment' WHERE wfteam_id=".$wfteam_id;
		$p1=sql_query($qry,$dbi);
	}
	
	// OK now based on the wfevent we should get the messagegroup and append to the request object
	
	$p=sql_query('select * from wfevent E,ttypeliga TE where E.evgroup_id=TE.id AND wfevent_id='.$event_id,$dbi);
	$aE=sql_fetch_array($p,$dbi);
	if (!$aE['mgroup_id']>0) {
		debug('E:WFT11:NoMessageGroup');
	} else {
		$qry='UPDATE wfrequest set mgroup_id='.$aE['mgroup_id'].' WHERE wfrequest_id='.$wfrequest_id;
		$p2=sql_query($qry,$dbi);
	}
	
	if ($p1+$p2==2) {
		echo '<font color="green">'.$p1.'/'.$p2.': Success saving Team '.$team_name.'</font>';
	} else {
		echo '<font color="red">'.$p1.'/'.$p2.': Error saving Team '.$team_name.'</font>';
	}
	
?>