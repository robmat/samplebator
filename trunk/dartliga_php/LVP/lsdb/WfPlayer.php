<?php
/**
*	purpose:	WFPlayer controller for ajax events
* 	params:		
*	returns:	X,0,1, sMsg, or the wfplayer_id
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
	
	// VAR CHECKS if not set incoming value = "undefined"
	if (isset($_POST['wfpid']) && intval($_POST['wfpid'])>0) {$wfplayer_id=strip_tags($_POST['wfpid']);}else{$wfplayer_id=0;};
	if (isset($_POST['pid']) && intval($_POST['pid'])>0) {$wfplayer_pid=strip_tags($_POST['pid']);}else{$wfplayer_pid=0;};
	if (isset($_POST['wfrequestid']) && intval($_POST['wfrequestid'])>0) {$wfrequest_id=utf8_decode(strip_tags($_POST['wfrequestid']));}else{$wfrequest_id='NULL';};
	if (isset($_POST['regkey']) && $_POST['regkey']<>"undefined") {$wfrequest_rkey=strip_tags($_POST['regkey']);}else{$wfrequest_rkey="";};
	if (isset($_POST['fname']) && $_POST['fname']<>"undefined") {$pfname=utf8_decode(strip_tags($_POST['fname']));}else{$pfname="";};
	if (isset($_POST['lname']) && $_POST['lname']<>"undefined") {$plname=utf8_decode(strip_tags($_POST['lname']));}else{$plname="";};
	if (isset($_POST['birthdate']) && $_POST['birthdate']<>"undefined") {$pbirthdate=utf8_decode(strip_tags($_POST['birthdate']));}else{$pbirthdate="1900-01-01";};
	if (isset($_POST['autopass']) && strlen($_POST['autopass'])<10) {$ppassnr=utf8_decode(strip_tags($_POST['autopass']));}else{$ppassnr="";};
	if (isset($_POST['plz']) && $_POST['plz']<>"undefined") {$pplz=utf8_decode(strip_tags($_POST['plz']));}else{$pplz="";};
	if (isset($_POST['town']) && $_POST['town']<>"undefined") {$ptown=utf8_decode(strip_tags($_POST['town']));}else{$ptown="";};
	if (isset($_POST['street']) && $_POST['street']<>"undefined") {$pstreet=utf8_decode(strip_tags($_POST['street']));}else{$pstreet="";};
	if (isset($_POST['tel']) && $_POST['tel']<>"undefined") {$ptel=utf8_decode(strip_tags($_POST['tel']));}else{$ptel="";};
	if (isset($_POST['email']) && $_POST['email']<>"undefined") {$pemail=utf8_decode(strip_tags($_POST['email']));}else{$pemail="";};
	if (isset($_POST['gender']) && $_POST['gender']<>"undefined") {$pgender=utf8_decode(strip_tags($_POST['gender']));}else{$pgender="H";};
	if (isset($_POST['pcomment']) && $_POST['pcomment']<>"undefined") {$pcomment=utf8_decode(strip_tags($_POST['pcomment']));}else{$pcomment="";};
	if (isset($_POST['member']) && intval($_POST['member'])>0) {$pmember=utf8_decode(strip_tags($_POST['member']));}else{$pmember='NULL';};

		
	$dbi = sql_connect($dbhost, $dbuname, $dbpass, $dbname);
	$usertoken=initLsdbSec($dbi);
	
	if ($usertoken['usertype_id']<2){echo "<font color=\"red\">E:WFP1:TypeError</font>";return;};
	if ($usertoken['verein_id']<1)$usertoken['verein_id']=0;
	
	$qry='';
	if ($wfplayer_id>0){
		// UPDATE ?? 
		$qry="UPDATE wfplayer set pid=$wfplayer_pid,pfname='$pfname', plname='$plname', pbirthdate='$pbirthdate',pgender='$pgender'"
		.",ppassnr='$ppassnr',pplz='$pplz',ptown='$ptown',pstreet='$pstreet',ptel1='$ptel',pemail='$pemail',pcomment='$pcomment' where wfplayer_id=$wfplayer_id";
		$pres1=sql_query($qry,$dbi);
	} else {
		// INSERT ??
		$qry="INSERT into wfplayer(wfplayer_id,wfrequest_id,pid,rkey,pfname,plname,pbirthdate,pgender,ppassnr,pplz,ptown,pstreet,ptel1,pemail,pcomment)"
		." VALUES (0,$wfrequest_id,$wfplayer_pid,'$wfrequest_rkey','$pfname','$plname','$pbirthdate','$pgender','$ppassnr','$pplz','$ptown','$pstreet','$ptel','$pemail','$pcomment')";
		$pres1=sql_query($qry,$dbi);
		// re-fetch the wfplayer record to get the ID ...
		$p=sql_query('SELECT wfplayer_id from wfplayer WHERE wfrequest_id='.$wfrequest_id,$dbi);
		$aP=sql_fetch_array($p,$dbi);
		// this can return more than 1 record ...
		#debug($aP);
		$wfplayer_id=$aP['wfplayer_id'];
	}
	
	#TODO this should loop for every player_id found for this request ... can be a TEAM !!
	// retrieve some values from the original REQUEST combined with VEREIN
	$p0=sql_query('select R.*,V.vname,V.verband_id from wfrequest R left join tverein V on R.verein_id=V.vid where wfrequest_id='.$wfrequest_id,$dbi);
	$aRV=sql_fetch_array($p0,$dbi);
	
	// depending on the existence of a matching membership record create or update....
	$p2=sql_query('select count(*) CNT from wfmembership where wfrequest_id='.$wfrequest_id,$dbi);
	$aC=sql_fetch_array($p2,$dbi);
	
	// there is no PassNumber with the Membership in WF !!
	if ($aC['CNT']>0){
		$qry2='UPDATE wfmembership set wfplayer_id='.$wfplayer_id.',ttypemember_id='.$pmember.',wfmembershipdesc="Verband:'.$aRV['vname'].'"'
		.' WHERE wfrequest_id='.$wfrequest_id;
	}else {
		$qry2='INSERT into wfmembership(wfrequest_id,rkey,wfplayer_id,ttypemember_id,wfmembershipdesc)'
		.' VALUES('.$wfrequest_id.',"'.$wfrequest_rkey.'",'.$wfplayer_id.','.$pmember.',"Verband:'.$aRV['vname'].'")';
	}
	$pres2=sql_query($qry2,$dbi);
	
	/*
	 * WFREQUEST Message Group
	 * this is only valid if this is a PLAYER request ONLY (type=3) ... else we overwrite the TeamLineUp_Entry
	 * the messagegroup_id is synchronized with the verband_id (1-10) -> get from the aRV[]
	 */ 
	$qry3='UPDATE wfrequest set mgroup_id='.$aRV['verband_id'].' WHERE wfrequesttype_id=3 AND wfrequest_id='.$wfrequest_id.' LIMIT 1';
	$pres3=sql_query($qry3,$dbi);
	
	$pres=$pres1+$pres2+$pres3;
	if ($pres>2) {
		echo '<font color=\'green\'>'.$wfplayer_id.'/'.$pres2.'/'.$pres3.':Success saving Player '.$wfplayer_id.'</font>';
	} else {
		echo '<font color=\'red\'>'.$wfplayer_id.'/'.$pres2.'/'.$pres3.':Error saving Player '.$wfplayer_id.'</font>';
	}

?>