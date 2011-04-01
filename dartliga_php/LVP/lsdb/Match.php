<?php

/*
 *		File		Match.php
 * 	 	Purpose:	MatchController 
 * 		Returns:	HTML code snippets
 */
	
if ($_SERVER['REQUEST_METHOD']<>'POST') die('Y');

foreach ($_POST as $secvalue) {
    if (eregi("<[^>]*script*\"?[^>]*>", $secvalue) OR eregi("\([^>]*.*\"?[^>]*\)", $secvalue)) {
	die ("X");
    }
}

	require_once('../code/config.php');
	require_once('../includes/sql_layer.php');
	require_once('../func_sec.php');
	require_once('../func_lsdb.php');
	require_once('../api_rs.php');
	require_once('../api_format.php');
	require_once('../lsdbcontroller.php');
	require_once('../theme/Lite/theme.php');
	
	// VAR CHECKS
	if (isset($_POST['action'])) {$myAction=utf8_decode(strip_tags($_POST['action']));}else{$myAction='';};
	if (isset($_POST['mkey']) && $_POST['mkey']<>'undefined') {$match_key=utf8_decode(strip_tags($_POST['mkey']));}else{die_red('MatchKey missing');$match_key='';};
	# optional
	if (isset($_POST['mstat']) && is_numeric($_POST['mstat'])) {$match_status=utf8_decode(strip_tags($_POST['mstat']));}else{$match_status=0;};
	if (isset($_POST['sh']) && is_numeric($_POST['sh'])) {$setsH=utf8_decode(strip_tags($_POST['sh']));}else{$setsH=0;};
	if (isset($_POST['lh']) && is_numeric($_POST['lh'])) {$legsH=utf8_decode(strip_tags($_POST['lh']));}else{$legsH=0;};
	if (isset($_POST['sa']) && is_numeric($_POST['sa'])) {$setsA=utf8_decode(strip_tags($_POST['sa']));}else{$setsA=0;};
	if (isset($_POST['la']) && is_numeric($_POST['la'])) {$legsA=utf8_decode(strip_tags($_POST['la']));}else{$legsA=0;};
	if (isset($_POST['mcomment']) && strlen($_POST['mcomment'])<200) {$match_comment=utf8_decode(strip_tags($_POST['mcomment']));}else{$match_comment='';};
	
	if (strlen($match_key)<8)die('<b>Mkey-ret:'.$match_key.'</b>');
	
	$dbi = sql_connect($dbhost, $dbuname, $dbpass, $dbname);
	$usertoken=initLsdbSec($dbi);
	$editmode=retAccessThisMatchKey($match_key);
	$aMkey=decode_Matchkey($match_key);
	
	// if this is a verein_account then no need to check the eventMap ...
	
	if($usertoken['usertype_id']==2){
		if( !$editmode==1) die_red('E43:Game:AC');
	}else {
		$ac=$usertoken['eventmap'][$aMkey['event']];
		if ($ac<2) die_red('E43:Game:AC');
	}
	

/**
*	purpose:	v4 returns matchkey decoded into named array
* 	params:		matchkey format: matchkey = e???r???h???A??? is always lower_case
*	returns:	named array
*/
function decode_Matchkey($thismatchkey){
	
	$ret=array();
	$e_pos=0;
	
	$r_pos=strpos($thismatchkey,"r");
	$h_pos=strpos($thismatchkey,"h");
	$a_pos=strpos($thismatchkey,"a");
	
	$key_len_e=$r_pos-$e_pos-1;
	$key_len_r=$h_pos-$r_pos-1;
	$key_len_h=$a_pos-$h_pos-1;
	
	$ret['event']=intval(substr($thismatchkey,$e_pos+1,$key_len_e));
	$ret['round']=intval(substr($thismatchkey,$r_pos+1,$key_len_r));
	$ret['home']=intval(substr($thismatchkey,$h_pos+1,$key_len_h));
	$ret['away']=intval(substr($thismatchkey,$a_pos+1));
	
	return $ret;
}
	
function _blank(){
	echo "<b>X</b><br>";
}

function _reset($match_key){
	global $dbi;
	$qry='UPDATE tblmatch set mstatus=0 WHERE mkey=\''.$match_key.'\' limit 1';
	$p1=sql_query($qry,$dbi);
	$qry='UPDATE tblmatchteam set mtlegs=0,mtlegslost=0,mtsets=0,mtsetslost=0,mtpoints=0 WHERE mtmkey=\''.$match_key.'\' limit 2';
	$p2=sql_query($qry,$dbi);
	if (($p1+$p2)==1) {return 1;} else {return 0;}
}

/**
*	purpose:	save the matchresult into tblmatchteam / tblmatch and set status to = 5
* 	params:		array SETS array LEGS
*	returns:	1 / 0
*/
function _saveMatch($match_key,$aSets,$aLegs,$match_status){
	global $dbi,$usertoken,$aMkey;
	// there is no insert .... this is update only ... 
	// get eventconfig
	// only save when incoming USER is higher ranked than previous user, store usertype in field=usercode
	// save results to tblmatchteam
	// save match to tblmatch
	// debug($aMkey);
	$aPoints=array();
	$event=reteventconfig($aMkey['event']);
	$myUserCode=$usertoken['usertype_id'];
	
	if ($myUserCode==2){
		$match_status=5;
	} else {
		// just to make sure a second check ...
		if ($usertoken['eventmap'][$event['id']]<2)die_red('E99:AccTokenMap');
	}
	
	if($aSets[0]>$aSets[1]){
		$aPoints[0]=$event['evpointswin'];
		$aPoints[1]=0;
	} elseif($aSets[0]==$aSets[1]){
		$aPoints[0]=$event['evpointseven'];
		$aPoints[1]=$event['evpointseven'];
	} elseif($aSets[0]<$aSets[1]){
		$aPoints[0]=0;
		$aPoints[1]=$event['evpointswin'];
	}
	
	$qry1='UPDATE tblmatchteam set usercode='.$myUserCode.', mtlegs='.$aLegs[0].',mtlegslost='.$aLegs[1].',mtsets='.$aSets[0].',mtsetslost='.$aSets[1].',mtpoints='.$aPoints[0]
	.' WHERE usercode<='.$myUserCode.' AND mtmkey=\''.$match_key.'\' AND mttid='.$aMkey['home'].' limit 1';
	$p1=sql_query($qry1,$dbi);
	$qry2='UPDATE tblmatchteam set usercode='.$myUserCode.',mtlegs='.$aLegs[1].',mtlegslost='.$aLegs[0].',mtsets='.$aSets[1].',mtsetslost='.$aSets[0].',mtpoints='.$aPoints[1]
	.' WHERE usercode<='.$myUserCode.' AND mtmkey=\''.$match_key.'\' AND mttid='.$aMkey['away'].' limit 1';
	$p2=sql_query($qry2,$dbi);
	
	$qry3='update tblmatch set usercode='.$myUserCode.',mstatus='.$match_status.' where usercode<='.$myUserCode.' AND mkey="'.$match_key.'"';
	$p3=sql_query($qry3,$dbi);
	dsolog(1,$usertoken['uname'],'Result '.$aSets[0].':'.$aSets[1].' for Match '.$match_key.' saved');
	if (($p1+$p2+$p3)==3) {return 1;} else {return 0;}
}

/**
*	purpose:	fetch a complete match -> render as schedule ROW+active input boxes
* 	params:		match_key
*	returns:	HTML TABLE
* 	security:	none -> public result view ....
*/
function _getMatchRow($match_key){
	global $dbi;
	$RS=DB_listMatches($dbi,1,0,0,'','','','','logic',$match_key);
	if (!sizeof($RS)==1)die_red('E119:Match:Match not found');
	/*
	 * structure = array 5 -> 13, identical to schedule Page,$R[2]= match ID
	 */
	foreach($RS as $R){
	$ROW='<tr><td width="80px">'.$R[5].'</td><td width="140px">'.$R[6].'</td><td width="120px">'.$R[7].'</td><td>'._input(0,'mkey'.$R[2],$match_key)._input(1,'sh'.$R[2],$R[8],3,2).'</td><td>'._input(1,'lh'.$R[2],$R[9],3,2).'</td>'
	.'<td>:</td><td>'._input(1,'sa'.$R[2],$R[10],3,2).'</td><td>'._input(1,'la'.$R[2],$R[11],3,2).'</td><td width="120px">'.$R[12].'</td><td>'.matchStatusToImage($R[13]).'</td></tr>';
	}
	return '<table>'.$ROW.'</table>';
}

/**
*	purpose	sum up the stored leg/game results an render Summary Box
*	params		matchkey
*	returns		summary box to be placed into DIV on matchSheet
*/
function _summaryBox($match_key){
	global $dbi,$usertoken,$aMkey;
	/*
	 * get the accumulatedGame/Leg/results from the DB and try to determine the status
	 * GP.gptid,GP.gppid,GP.gpsetswon,GP.gplegswon,G.gid,M.mid,M.mkey
	 * order is by team / Game thus we can filter the pairs which are stored twice
	 * pass all values to the _LS_renderSaveMatchBox function
	 * all Accounts(Verein) do not feature the status selector ...
	 */
	
	if ($usertoken['usertype_id']==2){$LS_LEVEL=1;} else{$LS_LEVEL=2;}
	$event=reteventconfig($aMkey['event']);
	$RS=DB_getMatchGameResults($dbi,$match_key);
	$sH=0;$lH=0;$sA=0;$lA=0;
	$lastgame=0;
	foreach($RS as $rec){
		if($lastgame<>$rec[4]){
			if ($rec[0]==$aMkey['home']){
				$sH+=$rec[2];$lH+=$rec[3];
			}elseif ($rec[0]==$aMkey['away']) {
				$sA+=$rec[2];$lA+=$rec[3];
			}
		}
		$lastgame=$rec[4];
	}
	/*
	 * now we try to determine a sensible status
	 */
	if (($lH+$lA)==0){$mstat=0;}else{$mstat=2;}
	return _LS_renderSaveMatchBox($LS_LEVEL,$sH,$sA,$lH,$lA,$mstat);
}

/*
 * just save the match comment and pass back sucess or failure string
 */
function _saveComment($match_key,$match_comment){
	global $dbi;
	$qry1='UPDATE tblmatch set mcomment=\''.$match_comment.'\' where mkey=\''.$match_key.'\' limit 1';
	$p1=sql_query($qry1,$dbi);
	if ($p1==1) {die_green('Saved');} else {die_red('Error');}
}

switch($myAction){
	default:
		_blank();
		break;

	case 'save':
		$ret=_saveMatch($match_key,array($setsH,$setsA),array($legsH,$legsA),$match_status);	
		if ($ret==1){
			die(_getMatchRow($match_key));
		}else{
			die_red('Error saving the Matchresult');
		}
	break;
	
	case 'reset':
		echo _reset($match_key);break;	
		
	case 'sumup':
		echo _summaryBox($match_key);break;
	case 'newcomment':
		echo _saveComment($match_key,$match_comment);break;
}
?>