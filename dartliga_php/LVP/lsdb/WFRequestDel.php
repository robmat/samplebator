<?php
/*
 * Snippet to delete cascading wfrequest data
 * access for sysadmin and members of destination group
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
	
	if (isset($_POST['wfid']) && $_POST['wfid']<>'undefined') {$wf_id=strip_tags($_POST['wfid']);}else{$wf_id=0;};
	$dbi = sql_connect($dbhost, $dbuname, $dbpass, $dbname);
	
	$usertoken=initLsdbSec($dbi);
	$WHERE='';
	
	if ($usertoken['usertype_id']<2){die("E1");}
	if ($usertoken['usertype_id']<4) $WHERE=" AND user_id=".$usertoken['id'];
	
	// TODO msggroup membership CHECK !!
	// ACTION
	
	$qry='delete from wfrequest WHERE wfrequest_id='.$wf_id.$WHERE.' limit 1';
	$allresult=sql_query($qry,$dbi);
	if ($allresult<>1) die("E2");
	// make history entry
	$qry='INSERT into wfrequesthistory(message,wfrequest_id,wfstatechange,wfuser_id) values(\'Request Deleted\','.$wf_id.',NOW(),'.$usertoken['id'].')';
	$presult=sql_query($qry,$dbi);
	// child records 
	$qry="delete from wflineup where wfrequest_id=$wf_id limit 1";
	$p=sql_query($qry,$dbi);$allresult=$allresult+$p;
	$qry="delete from wfplayer where wfrequest_id=$wf_id limit 1";
	$p=sql_query($qry,$dbi);$allresult=$allresult+$p;
	$qry="delete from wfteam where wfrequest_id=$wf_id limit 1";
	$p=sql_query($qry,$dbi);$allresult=$allresult+$p;
	
	die('<font color=\'green\'>Deleted Request '.$wf_id.'</font>');
?>