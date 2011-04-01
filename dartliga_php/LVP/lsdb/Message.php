<?php
	/*
	// file: 	Message.php
	// Purpose: complete controller for the message subsystem, POST Backend to lsdbMessage.php
	// Version: 0.1 BH Feb 2008, based entirely on ajax frontend
	*/

if ($_SERVER['REQUEST_METHOD']<>'POST') die("Y");
foreach ($_POST as $secvalue) {
    if (eregi("<[^>]*script*\"?[^>]*>", $secvalue) OR eregi("\([^>]*.*\"?[^>]*\)", $secvalue)) {
	die ("X");
    }
}

	include_once("../code/config.php");
	require_once("../includes/sql_layer.php");
	require_once("../func_sec.php");
	require_once("../theme/Lite/theme.php");
	require_once('../lsdbcontroller.php');
	require_once('../api_rs.php');
	require_once('../api_format.php');
	require_once('../func_mail.php');
	
	// VAR CHECKS
	if (isset($_POST['action'])) {$myAction=strip_tags(utf8_decode($_POST['action']));}else{$myAction='';};
	if (isset($_POST['btn']) && is_numeric($_POST['btn'])) {$button_ID=strip_tags(utf8_decode($_POST['btn']));}else{$button_ID=0;};
	if (isset($_POST['msgid']) && is_numeric($_POST['msgid'])) {$message_ID=strip_tags(utf8_decode($_POST['msgid']));}else{$message_ID=0;};
	if (isset($_POST['status']) && is_numeric($_POST['status'])) {$status_ID=strip_tags(utf8_decode($_POST['status']));}else{$status_ID=1;};
	
	$dbi = sql_connect($dbhost, $dbuname, $dbpass, $dbname);
	$usertoken=initLsdbSec($dbi);
	
	$ac=$usertoken['usertype_id'];
	if ($ac<2) die('Err:Msg33:WrongUser');
	
	
function _blank(){
	echo 'X1';
}

function _change($message_ID){
	global $dbi;
	$qry='UPDATE tmessage set mstatus_id=1 where id='.$message_ID.' limit 1';
	$presult=sql_query($qry,$dbi);
	if ($presult==1) {echo 'Message Status changed';} else {echo 'Error X4 (changing '.$message_ID.')';}
}

function _delete($message_ID){
	global $dbi;
	$qry='DELETE from tmessage where id='.$message_ID.' limit 1';
	$presult=sql_query($qry,$dbi);
	if ($presult==1) {echo 'Message Deleted';} else {echo 'Error X3 (deleting '.$message_ID.')';}
}

function _showform(){
	// output is loaded into the maincontent DIV
	global $usertoken,$mailfrom;
	if ($usertoken['usertype_id']<5) die_red('E58:NotPossible');
	
	$obj=array();
	$obj['mailfrom']=$mailfrom;
	$obj['mailgrp']=1;
	$obj['mailmsg']='Enter Message here';
	$obj['sysurl']='lsdbMessage.php';
	
	echo "<script type=\"text/javascript\">$('#pagetitle').html('New Message');</script>";
	echo '<p style=\'width:500px\'>';
	echo include('../forms/email.php');
	echo '<table><tr><td>'._button('Send','msgsend()').'</td></tr></table></p>';
}

/**
*	purpose:	list the msg where the LSDB user is in the receive group
* 	params:		
*	returns:	HTML table + del buttons
*/
function _showInbox(){
	global $dbi,$usertoken;
	# lookup on the recipient group ==> msgmap does not work , we have more than 1 group to check ..
	# here we check on the recipient email ...
	# att: the $usertoken['email'] must contain something else we see ALL messages
	if (strlen($usertoken['email'])<8){$usertoken['email']='fake@fake.com';}
	
	$RS=DB_listMessage($dbi,'',$usertoken['email'],2);
	$HEAD='';
	$ROWS='';
	if (!sizeof($RS)>0){
		$ROWS='<tr><td>Keine Nachrichten oder Aufgaben ...</td></tr>';
	}else {
		if ($usertoken['usertype_id']<5){
			$ROWS=RecordsetToDataTable($RS,array(1,2,3,5,6,7,8));
		} else {
			$ROWS=RecordsetToDataTable($RS,array(1,2,3,5,6,7,8),array('delmessage'),array(array(0)),array('Del'));
		}
		$aTH=array('Status','Absender','Datum','Gruppe','Empf&auml;nger','Link','Nachricht','Aktion');
		$HEAD=ArrayToTableHead($aTH);
	}
	// OUTPUT //
	echo '<script type="text/javascript">$("#pagetitle").html("Inbox");</script>';
	echo OpenTable('maillist',1);
	echo $HEAD.$ROWS;
	echo CloseTable(1);
}

/**
*	purpose	this is the system outbox, all messages send by the system
*	params	
*	returns		HTML table with action buttons per row
*/
function _showOutbox(){
	global $dbi,$usertoken;
	if ($usertoken['usertype_id']<5) die_red('Err:Msg95:WrongUser');
	$RS=DB_listMessage($dbi);
	$HEAD='';
	$ROWS='';
	$ROWS=RecordsetToDataTable($RS,array(1,2,3,5,6,7,8),array('delmessage','setmessage'),array(array(0),array(0)),array('Del','Chg'));
	$aTH=array('Status','Absender','Datum','Gruppe','Empf&auml;nger','Link','Nachricht','Aktion','Aktion');
	$HEAD=ArrayToTableHead($aTH);
	// OUTPUT //
	echo '<script type="text/javascript">$("#pagetitle").html("Outbox");</script>';
	echo '<div id=\'mailadm\'>';
	echo '<table><tr><td>'._button('Send Pending Liga','msgsendall()').'</td><td>'._button('Purge All','delall()').'</td></tr></table>';
	echo '</div>';
	echo OpenTable('maillist',1);
	echo $HEAD.$ROWS;
	echo CloseTable(1);
}

function _sendmail(){
	global $usertoken;
	if ($usertoken['usertype_id']<5) die('Err:Msg110:WrongUser');
	
	$msg=strip_tags($_POST['mailmsg']);
	$from=strip_tags($_POST['mailfrom']);
	$mail_group=strip_tags($_POST['mailgrp']);
	$to_adr=_GetMailAdressForGroup(strip_tags($_POST['mailgrp']));
	$msg_url=strip_tags($_POST['sysurl']);
	
	$msg=$msg.'\n===== System generated email, please do not reply. =====';
	if (strlen($msg_url)<1) {$msg_url='lsdbMessage.php';}
	#debug($from.":".$to.":".$msg);
	
	$ret=DB_setMessage($from,6,1,$msg,$msg_url,$mail_group,$to_adr);
	if (!$ret==1) {debug('ERROR creating mail:'.$ret);return;};

	// abschicken ...
	_sendpendingmails();

}


switch($myAction){
	default:
		break;
	case 'delete':
		_delete($message_ID);
		break;	
	case 'change':
		_change($message_ID);
		break;	
	case 'mailform':
		_showform();
		break;
	case 'inbox':
		_showInbox();
		break;
	case 'outbox':
		_showOutbox();
		break;
	case 'send':
		_sendmail();
		break;
	case 'sendp':
		_sendpendingmails();
		_showOutbox();
		break;
	case 'tabs':
		switch($button_ID){
			case 1:_showform();break;
			case 2:_showInbox();break;
			case 3:_showOutbox();break;
		}
		break;
}
?>