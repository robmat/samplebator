<?php
/**
 * 	File: func_wf.php
 * 	Usage:	included by all wf relevant snippets and controllers
 * access to this file only by
 * LigaAdmins, MeldeAdmins, SysAdmins
 * 
 * This file comprises the main workflow engine with enter - leave actions
 **/
if (eregi("wf_func.php",$_SERVER['PHP_SELF'])) {    Header("Location: ./");    die();}

require('ORM/player.php');
require('ORM/team.php');

function wf_processRequest($wf_id,$wfstatus_id,$action){
		global $dbi,$usertoken;
		$ret=0;
		/*
		 * $action = enter / leave
		 */
		switch($wfstatus_id){
			case 4:
				if ($action=='enter'){
					// this is a reject case ....
					// dsolog(3,'wfengine','Request rejected by:'.$usertoken['uname']);
					$obj=wf_getReqObject($wf_id);
					#debug($obj);
				}
				break;
			case 5:
				if ($action=='enter'){
					// dsolog(1,'wfengine','Enter State for S:'.$wfstatus_id.'/ R:'.$wf_id);
					$obj=wf_getReqObject($wf_id);
					#debug($obj);
					if ($obj['wfobject']=='wflineup'){
						$ret=wf_process_lineup($obj);
					} elseif ($obj['wfobject']=='wfplayer'){
						$ret=wf_process_player($obj);
					} elseif ($obj['wfobject']=='wfteam'){
						$ret=wf_process_team($obj);
					} elseif ($obj['wfobject']=='wfmessage'){
						/*
						 * nothing to do - there is no actual automatic
						 * processing job to finish
						 */
						$ret=1;
					}
				}
				break;
			default:
				// dsolog(3,'wfengine','Unhandled opcodes:'.$wfstatus_id.'/'.$action);
		}
		return $ret;
}

/**
*	purpose:	This actually processes the lineup extention request, new or existing player is synced and
* 				the lineup of an existing team is extended ...
* 	params:		request OBJECT
* 				// $obj['player_id']&&[pid] -> existing player
*				// $obj['wfplayer_id'] -> new player
*				// $obj['team_id'] -> existing team
*				// $obj['playertype_id'] -> player/lienup type
*				// $obj['wfmembership_id'] -> type of membership record to create for team-verein
*	returns:	0/1
*/
function wf_process_lineup($obj){
	#debug($obj);
	$ret=0;
	$player_id=wf_sync_player($obj);
	if ($player_id > 1) $ret=$ret+1;
	#debug('Player ID='.$player_id);
	$aT=wf_add_player2team($player_id,$obj['team_id'],0,$obj['playertype_id']);
	/*
	 * check if lineup is success else do not generate membership ...
	 * pass back 0 to block status changes
	 */
	if (sizeof($aT) > 3) {
		$ret=$ret+1;	
		$member_id=wf_add_MembershipForPlayer($player_id,$obj);
		if ($member_id > 0) $ret=$ret+1;
	}
	if ($ret==3) {return 1;} else{return 0;}
}


function wf_process_player($obj){
	$ret=0;
	$player_id=wf_sync_player($obj);
	if (!$player_id>0) die('E:WFP1:NoPlayerRetFromLSDB');
	# 	 --> add the membership record for verein/verband/type
	#ATT --> this is the stored req.verein not the user.verein
	$membership_id=wf_add_MembershipForPlayer($player_id,$obj);
	if ($player_id > 1) $ret=$ret+1;
	if ($membership_id > 0) $ret=$ret+1;
	if ($ret==2) {return 1;} else{return 0;}
}


function wf_process_team($obj){
	/*
	 * actually create/clone the wfteam into any LIGA of the LSDB System
	 * this can be a 'registration pool' or an actual event. LigaID link is stored with the wfevent object
	 */
	global $dbi;
	$ret=0;
	$aT=wf_create_LSDB_Team($obj);
	if ($aT['id'] > 1) {
		$ret=$ret+1;
		// ok now the Players ...
		$RS=DB_listWFTeamLineUp($dbi,$obj['wfteam_id']);
		if (sizeof(!$RS>0)){
			debug('E:WFF113:NoPlayerInTeam');
		}else{
			foreach($RS as $r){
				wf_add_player2team($r[3],$aT['id'],$aT['tevent_id'],1);
			}
		}
	}
	return $ret;
}

/*
 * ACTION HELPER FUNCTIONS
 * #######################
 */
/**
*	purpose:	create LSDB Team from wf Team
* 	params:		request object
*	returns:	Team Object
*/
function wf_create_LSDB_Team($obj){
	global $dbi;
	$verein_id=0;
	$sql='Select * from wfevent where wfevent_id='.$obj['wfevent_id'];
	$pres=sql_query($sql,$dbi);
	$aWF=sql_fetch_array($pres,$dbi);
	if ($aWF['event_id']>0){
		debug('Registering new Team for Event: '.$aWF['event_id']);
		// CREATE:
		$cT=new cTeam;
		$cT->setDB($dbi);
		$cT->aDATA['id']=0;
		$cT->aDATA['tname']=$obj['teamname'];
		$cT->aDATA['tlocation_id']=$obj['location_id'];
		$cT->aDATA['tverein_id']=$obj['verein_id'];
		$cT->aDATA['tevent_id']=$aWF['event_id'];
		$cT->save();
	} else {
		debug('E:WFF150:NoTargetLigaConfigured');
	}
	return $cT->aDATA;
}

function wf_sync_player($obj){
	// returns the NEW or REAL PlayerID from LSDB
	// requires the appkey field in the DB ...
	global $dbi,$usertoken;
	$cP=new cPlayer;
	$cP->setDB($dbi);
	$cP->aDATA['pid']=$obj['player_id'];
	$cP->aDATA['pfname']=$obj['pfname'];
	$cP->aDATA['plname']=$obj['plname'];
	$cP->aDATA['pbirthdate']=$obj['pbirthdate'];
	$cP->aDATA['pgender']=$obj['pgender'];
	$cP->aDATA['ptown']=$obj['ptown'];
	$cP->aDATA['pplz']=$obj['pplz'];
	$cP->aDATA['pstreet']=$obj['pstreet'];
	$cP->aDATA['pemail']=$obj['pemail'];
	$cP->aDATA['ptel1']=$obj['ptel1'];
	$cP->aDATA['pcomment']=$obj['pcomment'];
	$cP->save();
	// asynchronous issues here ??
	return $cP->aDATA['pid'];
}


/**
*	purpose:	add LSDB player to LSDB team, this is similar to lsdb/LineUp.php
* 	params:		if event_id not set, than the team record is fetched from the DB
*	returns:	
*/
function wf_add_player2team($player_id,$team_id,$event_id=0,$lineup_type_id=1){
	// returns the TEAM object of the selected team
	// 1 -> get event - team - verein values from the team table
	global $dbi;
	$aTeam['id']=$team_id;
	$aTeam['tevent_id']=$event_id;
	debug('Adding Player:'.$player_id.' to Team:'.$team_id.' as Type:'.$lineup_type_id);
	if ($event_id==0){
		$rec=sql_query('select * from tblteam T where T.id='.$team_id,$dbi);
		$aTeam=sql_fetch_array($rec,$dbi);
		$event_id=$aTeam['tevent_id'];
	}
	$qry='INSERT into tblteamplayer(lid,leventid,lteamid,lplayerid,lactive,ltype)'
		.' VALUES(0,'.$event_id.','.$team_id.','.$player_id.',1,'.$lineup_type_id.')';
	$p1=sql_query($qry,$dbi);
	/*
	 * TODO How do we know the DB-Constraint fired ??
	 */
	if ($p1<>1){debug('Spieler kann nicht eingesetzt werden, existiert bereits in dieser <a href="lsdbTeam.php?func=browse&eventid='.$event_id.'">Liga Gruppe</a> ....');return;}
	return $aTeam;
}

/**
*	purpose:	add a membership record into the LSDB for a specific player
* 	params:		player_id (LSDB) , request object
*	returns:	0/1
*/

function wf_add_MembershipForPlayer($player_id,$obj){
	// returns Membership_record_id
	if (!$obj['verein_id']>0) return 0;
	debug('Adding Player:'.$player_id.' to Verein:'.$obj['verein_id'].' as Member:'.$obj['ttypemember_id']);
	
	global $dbi,$usertoken;
	$p=new cPlayer;
	$p->setDB($dbi);
	$p->getbyID($player_id);
	$p->saveMembershipVerein($obj['verein_id'],$obj['ttypemember_id']);
	#debug('PERR: '.$p->pError);
	if (strlen($p->pError)>1){debug($p->pError);return 0;}else{return 1;}
}


/**
 *  ############ ORM FETCH ####################################
*	purpose:	get request object from DB if request_user==current.user
* 	params:		id
*	returns:	object request and data
*/
function wf_getReqObject($req_id){
	/*
	 * load hibernated request object including all childs + special values ...
	 * for the verein_ID use hibernated value in request object ...
	 * slips a [msg] field to be used for display
	 */
	global $dbi,$usertoken;
	$adm=0;
	if (!$req_id>0) {debug("Error :: No request ID");return;}
	$qry="select R.*,T.wftablename wfobject"
	." FROM wfrequest R,wfrequesttype T where R.wfrequesttype_id=T.wfrequesttype_id and R.wfrequest_id=$req_id";
	// make sure the incoming admins sees all the objetcs ...
	// by default we drill down to user-generated content only
	switch($usertoken['usertype_id']){
		case 4:
		case 5:
		case 6:
			$adm=$usertoken['id'];
			//#TODO append query to the messagegroup membership here ...
			break;
		default:
			// by default drill down to user identity
			$qry=$qry." AND R.user_id=".$usertoken['id'];
	}
	$obj=array();
	# 1 get object type (wfplayer or wfteam ...), make sure its from this user ...
	$pQ=sql_query($qry,$dbi);
	$obj=sql_fetch_array($pQ,$dbi);
	# 2 look up object data ...
	$pQ=sql_query('select * from '.$obj['wfobject'].' where wfrequest_id='.$obj['wfrequest_id'],$dbi);
	$objdata=sql_fetch_array($pQ,$dbi);
	if (sizeof($objdata)>1) $obj=array_merge($obj,$objdata);
	#debug($obj);
	if ($obj['wfobject']=='wflineup'){
			// load potential player DATA and merge into obj data ...
			$pQ=sql_query('select * from wfplayer where wfrequest_id='.$obj['wfrequest_id'],$dbi);
			$childdata=sql_fetch_array($pQ,$dbi);
			if (sizeof($childdata)>1) $obj=array_merge($obj,$childdata);
			// get potential membership ?? usually there must be a record .... if its a saved record ..
			$pQ=sql_query('select * from wfmembership where wfrequest_id='.$obj['wfrequest_id'].' AND wfplayer_id='.$obj['wfplayer_id'],$dbi);
			$childdata=sql_fetch_array($pQ,$dbi);
			if (sizeof($childdata)>1) $obj['ttypemember_id']=$childdata['ttypemember_id'];
			//if (sizeof($childdata)>1) $obj=array_merge($obj,$childdata);
			
	} elseif ($obj['wfobject']=='wfplayer'){
		// load PLAYER data
		if ($obj['wfplayer_id']>0){
			$pQ=sql_query('select *,TM.memberdesc from wfmembership M,ttypemember TM where M.ttypemember_id=TM.id AND M.wfplayer_id='.$obj['wfplayer_id'],$dbi);
			$childdata=sql_fetch_array($pQ,$dbi);
			if (sizeof($childdata)>1) $obj=array_merge($obj,$childdata);
			$obj['msg']='Spieler Anmeldung: '.$obj['pfname'].' '.$obj['plname'].' Type: '.$obj['memberdesc'];
		}
	} elseif ($obj['wfobject']=='wfteam'){
		// additional loading needed ???
		if ($obj['wfteam_id']>0){
			$pQ=sql_query('select eventname from wfevent where wfevent_id='.$obj['wfevent_id'],$dbi);
			$childdata=sql_fetch_array($pQ,$dbi);
			if (sizeof($childdata)>1) $obj=array_merge($obj,$childdata);
			$obj['msg']='Event:'.$obj['eventname'].' Team:'.$obj['teamname'];
		}
	} elseif ($obj['wfobject']=='wfmessage'){
		// additional loading => Verein Data ...
		$pQ=sql_query('select vemail,verband_id from tverein where vid='.$obj['verein_id'],$dbi);
		$childdata=sql_fetch_array($pQ,$dbi);
		if (sizeof($childdata)>1) $obj=array_merge($obj,$childdata);
		$obj['msg']='Message: '.$obj['wfcomment'];
	}
	
	#debug($obj);
	return $obj;
}

?>