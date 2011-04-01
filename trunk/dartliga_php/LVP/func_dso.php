<?php
/**
 * Functions which are in use for the DSO Module only
 * used by -> interface, snippets and lsdb postbacks
 */

/**
 * Action handler for the INSERT / UPDATE of a membership record
 * returns err:string or success:messages ...
 *
 * @param int $membership_id
 * @param int $player_id
 * @param int $verein_id
 * @param int $mtype_id
 * @param string $v_passnr
 * @param date $v_mstart
 * @param date $v_mend
 */
function dso_insupdmembership($membership_id,$player_id,$verein_id,$mtype_id,$v_passnr,$v_mstart,$v_mend){
	global $dbi,$usertoken;
	// CHECK ACCESS 2
	$qV=sql_query('SELECT * from tverein WHERE vid='.$verein_id,$dbi);
	$aV=sql_fetch_array($qV,$dbi);
	if ($usertoken['registermap'][$aV['verband_id']]<3) return 'E50:P2:RightsMissing:'.$usertoken['registermap'][$aV['verband_id']];
	
	// cre_INFO, date defaults ...
	$upd_date = ls_getdate();
	// check Values
	if (!$verein_id>0) die_red('Verein not set');
	if (!$mtype_id>0) die_red('Type not set');
	if (!$player_id>0) die_red('Player not set');
	if (strlen($v_passnr)<3) die_red('Pass Number not set');
	if (!check_date($v_mstart)) $v_mstart=substr($upd_date,0,4).'-01-01';	
	if (!check_date($v_mend)) $v_mend=substr($upd_date,0,4).'-12-31';
	
	/* CODE from the workflow ORM stuff ...
	$p=new cPlayer;
	$p->setDB($dbi);
	$p->getbyID($player_id);
	$p->saveMembershipVerein($v_verein,$v_mtype,$v_passnr,$v_mstart,$v_mend);
	if (strlen($p->pError)>1){debug($p->pError);return 0;}else{return 1;}
	*/
	
	if ($membership_id>0){
		$qry="UPDATE tmembership SET mtype=$mtype_id,mvereinid=$verein_id,mpassnr='$v_passnr', mstart='$v_mstart',mend='$v_mend',"
		."mcre_user='".$usertoken['uname']."',mcre_date='$upd_date' where mpid=$player_id and mid=$membership_id limit 1";
	} else {
		$qry='INSERT into tmembership(mid,mpid,mtype,mpassnr,mstart,mend,mvereinid,mcre_user,mcre_date,mstatus,mflag)'
		." VALUES(0,$player_id,$mtype_id,'$v_passnr','$v_mstart','$v_mend',$verein_id,'".$usertoken['uname']."','$upd_date',0,0)";
	}
	
	if (!$presult=sql_query($qry,$dbi)) return '<font color=red>E71:DB error on MembershipRecord save possible constraint violation</font>';
	
	# // In any case we store the KEYVAL directly into the player record  ...
	# // and we make sure the player is marked as active ...
	
	$keyfield=dso_getPassKeyFieldForType($mtype_id);
	
	$sql="update tplayer set $keyfield=\"$v_passnr\",pactive=1 where pid=$player_id";
	if (!$ans=sql_query($sql,$dbi)) return 'Database error on keyfield save ...';
	dsolog(1,$usertoken['uname'],'Created Membership for pid: ('.$player_id.')');
	
	if ($presult==1) {return 'Membership Saved';} else {return 'E88:saving ';}
}

function dso_deletemembership($membership_id){
	global $dbi;
	$qry='DELETE from tmembership where mid='.$membership_id.' limit 1';
	$presult=sql_query($qry,$dbi);
	if ($presult==1) {return '<font color="green">Membership Deleted</font>';} else {return '<font color="red">DB Error (deleting '.$message_ID.')</font>';}
}

/**
 * translates a ZVR into a unique verein_id
 * returns 0/ vid
 */
function dso_verifyVereinZVR($verein_zvr){
	global $dbi,$usertoken;
	$qry='select * from tverein where tpolmeldung=\''.$verein_zvr.'\'';
	$rec = sql_query($qry,$dbi);
	$aV=sql_fetch_array($rec,$dbi);
	if (!$usertoken['registermap'][$aV['verband_id']]>1){
		return 0;
	}else{
		return $aV['vid'];
	}
}

/**
 * Return the PID of Player record, by matching passNR against all possible passkey fields
 *
 * @param string $passNR
 */
function dso_checkPlayerByPassNr($passNR){
	global $dbi;
	$qry='select pid from tplayer where pfkey1 like \''.$passNR.'\' UNION select pid from tplayer where pfkey2 like \''.$passNR.'\'';
	$rec = sql_query($qry,$dbi);
	$temp_aP=sql_fetch_array($rec,$dbi);
	return $temp_aP['pid'];
}

function dso_checkPlayerByNameAndBirth($fname,$lname,$birth){
	global $dbi;
	$qry="select pid from tplayer where concat(firstname,lastname) like \"".$fname.$lname."\" AND pbirthday=\"".$birth."\"";
	$rec = sql_query($qry,$dbi);
	$temp_aP=sql_fetch_array($rec,$dbi);
	return $temp_aP['pid'];
}

function dso_delplayer($vpid) {
	global $dbi, $usertoken;
	if (!is_numeric($vpid)) die('E1');
	if ($usertoken['usertype_id'] < 5) return'<font color=red>Err85:AccType</font>';
	$res1 = sql_query('delete from tplayer where pid='.$vpid.' LIMIT 1',$dbi);
	dsolog(3,$usertoken['uname'],"DELETED Player Record ($vpid)");
}

function dso_getPassKeyFieldForType($type_id){
	global $dbi;
	$qry='select passkey from ttypemember where id='.$type_id;
	$rec = sql_query($qry,$dbi);
	$aTEMP=sql_fetch_array($rec,$dbi);
	return $aTEMP['passkey'];	
}

?>