<?php
/**
	// file: 		func_mail.php
	// Output: 		NONE
	// Purpose: 	mail functions, include this file if needed
	**/
	
if (eregi("func_mail.php",$_SERVER['PHP_SELF'])) {Header("Location: ./"); die();}


/**
*	purpose:	resolves a logical mail group into adresses
* 	params:		group id
*	returns:	Mail TO: STRING
*/
function _GetMailAdressForGroup($mgroup_id){
	global $dbi;
	$RET='';
	$resx=sql_query('select G.mgroup_id,mgroupname,user_id,uname,email from tmessagegroup G left JOIN tmessagegroupmember GM on G.mgroup_id=GM.mgroup_id'
	.' left JOIN tuser U ON GM.user_id=U.id WHERE G.mgroup_id='.$mgroup_id,$dbi);
	while ($a=sql_fetch_row($resx,$dbi)) {
		$RET=$RET.$a[4].', ';
	}
	$RET=str_replace('[]','@',$RET);
	$RET=str_replace('[at]','@',$RET);
	$RET=substr($RET,0,strlen($RET)-2);
	return $RET;
}

/**
*	purpose	email scheduler, this is the actual sending of all emails
*	params		non, messages are taken from tmessage
*	returns		nothing or failure message
*/
function _sendpendingmails(){
	global $dbi,$RZ_server,$mailhead;
	// M.id,S.msgstatus,M.cre_user,M.cre_date,M.mgroup_id,MG.mgroupname,M.recipient,M.msgurl,M.msgbody
	
	$ret=0;
	// fetch ALL pending messages
	$RS=DB_listMessage($dbi,'','',1);
	// execute mailer , check if we have a group to send to or a pre-stored single recipient ...
	// debug($RS);
	foreach ($RS as $M){
		if ($M[4]>0){
			$to_adr=_GetMailAdressForGroup($M[4]);
		} else {
			$to_adr=$M[6];
		}
		// store or update the recipient list in tmessage -> recipient, else the INBOX is always empty ...
		$ret=mail($to_adr,'LSDB Liga Message',$M[8].'\n'.'System Link\n'.$RZ_server.$M[7],$mailhead);
		if ($ret==1){
			$ret=DB_setMessageStatus($M[0],'','',2,$to_adr);
		}else{
			debug('Error sending mails '.$M[8].'\n'.$M[7]);
		}
	}
}

?>