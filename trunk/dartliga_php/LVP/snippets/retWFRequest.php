<?php
/**
 * 	file	:	retWFRequest.php
*	purpose:	return request DATA
* 	params:		wfid, wfstatus, mode (short|long)
*	returns:	HTML Table
*/

	foreach ($_POST as $secvalue) {
	    if (eregi("<[^>]*script*\"?[^>]*>", $secvalue) OR eregi("\([^>]*.*\"?[^>]*\)", $secvalue)) {
			die ("X");
	    }
	}
	
	require_once("../code/config.php");
	require_once("../includes/sql_layer.php");
	require_once("../theme/Lite/theme.php");
	require_once('../func_sec.php');
	//require_once("../lsdbcontroller.php");
	require_once('../api_rs.php');
	require_once('../api_format.php');
	
	if (isset($_POST['wfid']) && is_numeric($_POST['wfid']) && $_POST['wfid']<>'undefined'){$wf_id=$_POST['wfid'];} else {$wf_id=0;}
	if (isset($_POST['wfstatus']) && is_numeric($_POST['wfstatus']) && $_POST['wfstatus']<>'undefined'){$wf_status=$_POST['wfstatus'];} else {$wf_status=0;}
	if (isset($_POST['mode']) && strlen($_POST['mode'])<6 && $_POST['mode']<>'undefined'){$wf_mode=$_POST['mode'];} else {$wf_mode='short';}

	$dbi = sql_connect($dbhost, $dbuname, $dbpass, $dbname);
	$usertoken=initLsdbSec($dbi);
	$adm=0;
	$uid=0;
	$uid=$usertoken['id'];
	switch($usertoken['usertype_id']){
		case 4:
		case 5:
		case 6:
			// seems we have a liga admin here ...
			// pass USERID as adm and set uid to zero ....
			$uid=0;
			$adm=$usertoken['id'];
		default:
	}
	// ACTION
	$RS=DB_listWFRequest($dbi,0,$uid,$wf_status,0,'',$adm);
	if (sizeof($RS)>0) {$ROWS=RecordsetToClickTable($RS,1,'wf.php?op=edit&reqid=%P1%',0);} else {$ROWS='No requests ('.$wf_status.') found ...';}
	// OUTPUT
	$OUT='<h3>Antr&auml;ge Status: '.$wf_status.'</h3>';
	$OUT=$OUT.'<div class=\'child\'>';
	$OUT=$OUT.OpenTable('wflist',1);
	$OUT=$OUT.$ROWS;
	$OUT=$OUT.CloseTable(1);
	$OUT=$OUT.'</div>';
	/*
	$aTH=array('ID','Request','Date','User','Status','Message');
	$OUT='<table>'.ArrayToTableHead($aTH);
	$OUT=$OUT.RecordsetToDataTable($RS,array(0,1,2,3,4,5));
	*/
	header('Content-Type: application/html; charset=ISO-8859-1');
	echo $OUT;
?>