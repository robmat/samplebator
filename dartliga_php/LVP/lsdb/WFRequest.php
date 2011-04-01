<?php
/**
*	purpose:	WFRequest controller for ajax events
* 				triggers neccessary actions on the workflow eNgine on Statuschanges ....
* 	params:		wfid, status
*	returns:	X,0,1, sMsg
*/
if ($_SERVER['REQUEST_METHOD']<>'POST') die("Y");
foreach ($_POST as $secvalue) {
    if (eregi("<[^>]*script*\"?[^>]*>", $secvalue) OR eregi("\([^>]*.*\"?[^>]*\)", $secvalue)) {
	die ("X");
    }
}

	include_once('../code/config.php');
	require_once('../includes/sql_layer.php');
	require_once('../func_sec.php');
	require_once('../func_lsdb.php');
	require_once('../func_wf.php');
	require_once('../func_mail.php');
	require_once('../api_rs.php');
	
	// VAR CHECKS 
	if (isset($_POST['wfid']) && $_POST['wfid']<>'undefined') {$wf_id=strip_tags($_POST['wfid']);}else{$wf_id=0;};
	if (isset($_POST['mode']) && $_POST['mode']<>'undefined') {$action=strip_tags(utf8_decode($_POST['mode']));}else{$action='';};
	if (isset($_POST['status']) && $_POST['status']<>'undefined') {$wfstatus_id=strip_tags($_POST['status']);}else{$wfstatus_id=1;};
	if (isset($_POST['vrcomm']) && $_POST['vrcomm']<>'undefined') {$wfcomment=strip_tags(utf8_decode(urldecode($_POST['vrcomm'])));}else{$wfcomment='';};
	
	$dbi = sql_connect($dbhost, $dbuname, $dbpass, $dbname);
	
	$usertoken=initLsdbSec($dbi);
	if ($usertoken['usertype_id']<5){
		// looks bad ... prevent change by setting to identical UID
		$WHERE=' AND user_id='.$usertoken['id'];
	} else {
		$WHERE='';
	}
	$qry="";
	$allresult=0;
	
	$obj=wf_getReqObject($wf_id);
	/*
	 * a meaningful description is loaded into $obj['msg']
	 */
	switch($wfstatus_id){
		case 1:
			$sMSG='Created: '.$obj['msg'].' Rem: '.$wfcomment;
			$p1=1;
			break;
		case 2:
			$sMSG='Submitted: '.$obj['msg'].' Rem: '.$wfcomment;
			$recipient='admin';
			$p1=1;
			break;
		case 3:
			$sMSG='Approved: '.$obj['msg'].' Rem: '.$wfcomment;
			$recipient='user';
			$p1=1;
			break;
		case 4:
			$sMSG='Rejected: '.$obj['msg'].' Rem: '.$wfcomment;
			$recipient='user';
			$p1=1;
			break;
		case 5:
			if ($usertoken['usertype_id']==5 || $usertoken['usertype_id']==6){
				$p1=wf_processRequest($wf_id,$wfstatus_id,'enter');
			}
			$recipient='user';
			$sMSG='Processed: '.$obj['msg'].' Rem: '.$wfcomment;
			break;
		case 6:
			$sMSG='Closed: '.$obj['msg'].' Rem: '.$wfcomment;
			$recipient='';
			$p1=1;
			break;
		default:
			$sMSG='Unknown change to '.$wfstatus_id;
			$recipient='';
			$p1=0;
	}
	if ($p1 >0){
		$qry='UPDATE wfrequest set wfstate_id='.$wfstatus_id.',reqcomment=\''.$wfcomment.'\' WHERE wfrequest_id='.$wf_id.$WHERE;
		$p2=sql_query($qry,$dbi);
		if ($p2<>1) {echo '<h3>Request could not be updated ...</h3>';}
		#debug($obj);
		$qry='INSERT into wfrequesthistory(message,mgroup_id,wfrequest_id,wfstate_id,wfstatechange,wfuser_id)'
		.' VALUES("'.$sMSG.'",'.$obj['mgroup_id'].','.$wf_id.','.$wfstatus_id.',NOW(),'.$usertoken['id'].')';
		#debug($qry);
		$p3=sql_query($qry,$dbi);
		if ($p3<>1) {echo '<h3>History could not be created ...</h3>';}
		/*
		 *  can we generate a mail message here  ??? we cant hook into the _sedmail proc since its a service ...
		 *  we use the DB_setMessage api instead
		 *  recipients are either user/admin 
		 */
		$msg='Antrag: '.$obj['wfobject'].' Status: '.$wfstatus_id.' Info: '.$sMSG;
		$sysurl='wf.php?op=edit&reqid='.$wf_id;
		if ($recipient=='user'){
			$recipient_addres=retUserProperty($obj['user_id'],'email');
			$p4=DB_setMessage($usertoken['uname'],4,1,$msg,$sysurl,0,$recipient_addres);
		}else{
			$p4=DB_setMessage($usertoken['uname'],4,1,$msg,$sysurl,$obj['mgroup_id']);
		}
		if ($p4<>1) {echo '<h3>Message could not be created ...'.$ret.'</h3>';} else {_sendpendingmails();}
	}
	
	if (($p1+$p2+$p3+$p4)==4) {
		echo '<font color=\'green\'>'.$p1.'/'.$p2.'/'.$p3.'/'.$p4.':Success RequestChangeStatus'.$wf_id.'</font>';
	} else {
		echo '<font color=\'red\'>'.$p1.'/'.$p2.'/'.$p3.'/'.$p4.':Error during RequestChangeStatus'.$wf_id.'</font>';
	}
	
?>