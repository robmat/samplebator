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
	if (isset($_POST['regkey']) && $_POST['regkey']<>"undefined") {$wfrequest_rkey=strip_tags($_POST['regkey']);}else{$wfrequest_rkey="";};
	if (isset($_POST['wfmid']) && intval($_POST['wfmid'])>0) {$wfmsg_id=strip_tags($_POST['wfmid']);}else{$wfmsg_id='NULL';};
	if (isset($_POST['mgroup'])&& intval($_POST['mgroup'])>0) {$mgroup_id=strip_tags($_POST['mgroup']);}else{$mgroup_id='NULL';};
	if (isset($_POST['wfmsg'])&& $_POST['wfmsg']<>'undefined') {$comment=strip_tags(utf8_decode(urldecode($_POST['wfmsg'])));}else{$comment='NULL';};
	$dbi = sql_connect($dbhost, $dbuname, $dbpass, $dbname);
	
	#TODO add lsdbsecurity Layer ...
	
	$qry='';
	if ($wfmsg_id>0){
		$qry='UPDATE wfmessage set wfcomment=\''.$comment.'\' WHERE wfmessage_id='.$wfmsg_id;
		$p1=sql_query($qry,$dbi);
		$qry='UPDATE wfrequest SET mgroup_id='.$mgroup_id.' WHERE wfrequest_id='.$wfrequest_id;
		$p2=sql_query($qry,$dbi);
	}
	
	if (($p1+$p2)==2) {
		echo '<font color=\'green\'>'.$p1.'/'.$p2.': Success saving Message</font>';
	} else {
		echo '<font color=\'red\'>'.$p1.'/'.$p2.': Error saving Message</font>';
	}
	
?>