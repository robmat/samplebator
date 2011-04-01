<?php
	/*
	 * LSDB Game Controller save/reset/manipulate legs
	 * file: 		Game.php
	 * returns: 	Table containing complete Game Entry for the Matchsheet, no action buttons
	 * params: 		eid=eventID,gid=gameID
	 * methods:		save,addleg,delleg,reset
	 * security:	Event Admins only ....
	 * structure from the User GUI: TR /TD with Game DIV /TD with Game Buttons
	 * Results are loaded into a DIV=trg[gid]
	 */

	require_once('../code/config.php');
	require_once('../includes/sql_layer.php');
	require_once("../api_rs.php");
	require_once("../api_format.php");
	require_once("../func_lsdb.php");
	require_once("../func_sec.php");
	require_once("../func_match.php");
	require_once("../theme/Lite/theme.php");
	
	# mandatory POST
	if (isset($_POST['action']) && strlen($_POST['action'])<10) {$myAction=utf8_decode(strip_tags($_POST['action']));}else{die_red('No action');}
	if (isset($_POST['gid']) && is_numeric($_POST['gid'])) {$GameID=$_POST['gid'];}else{die_red('Error Game_id');}
	if (isset($_POST['eid']) && is_numeric($_POST['eid'])) {$eventID=$_POST['eid'];}else{die_red('Error Event_id');}
	if (isset($_POST['mkey']) && $_POST['mkey']<>'undefined') {$match_key=utf8_decode(strip_tags($_POST['mkey']));}else{die_red('Error Match_Key');};
	# optional POST
	if (isset($_POST['lid']) && is_numeric($_POST['lid'])) {$legID=$_POST['lid'];}else{$legID=0;}
	if (isset($_POST['pid']) && is_numeric($_POST['pid'])) {$playerID=$_POST['pid'];}else{$playerID=0;}
	if (isset($_POST['gmode']) && strlen($_POST['gmode'])<4) {$game_mode=$_POST['gmode'];}else{$game_mode='sgl';}
	if (isset($_POST['ldata']) && $_POST['ldata']<>'undefined') {$str_LData=utf8_decode(strip_tags($_POST['ldata']));}else{$str_LData='';};
	
	// legstuff for the tblgameplayer entries, in pairs entries are mixed 1:3 vs 2:4
	if (isset($_POST['r1'])) {$aRes[]=$_POST['r1'];}
	if (isset($_POST['r2'])) {$aRes[]=$_POST['r2'];}
	if (isset($_POST['r3'])) {$aRes[]=$_POST['r3'];}
	if (isset($_POST['r4'])) {$aRes[]=$_POST['r4'];}
	
	// SECURITY Event Admins only ...
	$dbi = sql_connect($dbhost, $dbuname, $dbpass, $dbname);
	$usertoken=initLsdbSec($dbi);
	$editmode=retAccessThisMatchKey($match_key);
	
	// if this is a verein_account then no need to check the eventMap ...
	
	if($usertoken['usertype_id']==2){
		if( !$editmode==1) die_red('E43:Game:AC');
	}else {
		if ($usertoken['eventmap'][$eventID]<2) die_red('E43:Game:AC');
	}
	

/**
*	purpose:	return a Complete Game as Table to be included into the MatchSheet DIV
* 				renders both single or pairs depending on the gtype
* 				parts of this code are similar/identical to the generation of the MatchSheet in ls_system.php
* 	params:		$GameID,$eventID
*	returns:	HTML TABLE of GameRecord
*/
function _getGameTable($GameID,$eventID){
	
	global $dbi,$usertoken;
	
	$event=reteventconfig($eventID);
	$editmode=1;
	$matchdate='';
	$RSG=DB_listGameTeams($dbi,$GameID);
	#mid,mkey,mdate,mtid,mthome,T.id,T.tname,gid,gtype,gstatus
	$matchdate=$RSG[0][2];
	$hometeam=$RSG[0][5];
	$awayteam=$RSG[1][5];
	
	if ($RSG[0][8]==1){
		# this is a singles GAME ....
		# Att: the correct order of HOME-AWAY relies on the sequence of the game-player records
		$precord = sql_query('select G.gid,G.gmkey,G.gtype,G.gstatus,GP.gpid,GP.gppid,P.pfname,P.plname'
			.' FROM tblgame G,tblgameplayer GP left join tplayer P on GP.gppid=P.pid'
			.' WHERE G.gid=GP.gpgid and G.gid='.$GameID.' AND G.gtype=1 ORDER by G.gid,GP.gpid',$dbi);
		
		$lastgameid=0;
		while(list($gid,$gmkey,$gtype,$gstatus,$gpid,$gppid,$pfname,$plname)=sql_fetch_row($precord,$dbi)){
			
			if ( $lastgameid<>$gid ) {
				$PH=array($gid,$gmkey,$gtype,$gstatus,$gpid,$gppid,$pfname,$plname,$hometeam);
			}else{
				$PA=array($gid,$gmkey,$gtype,$gstatus,$gpid,$gppid,$pfname,$plname,$awayteam);
				echo _LS_renderSingleGameRow($event,$editmode,$matchdate,$PH,$PA);
			}
			$lastgameid=$gid;
		}
		/*
		 * end SINGLES 
		 */
		return;
	} elseif($RSG[0][8]==2){
		# OK, this is a pairs ....
		$precord = sql_query('select G.gid,G.gmkey,G.gtype,G.gstatus,GP.gpid,GP.gppid,P.pfname,P.plname'
			.' FROM tblgame G,tblgameplayer GP left join tplayer P on GP.gppid=P.pid'
			.' WHERE G.gid=GP.gpgid and G.gid='.$GameID.' AND G.gtype=2 ORDER by G.gid,GP.gpid',$dbi);
			
		while(list($gid,$gmkey,$gtype,$gstatus,$gpid,$gppid,$pfname,$plname)=sql_fetch_row($precord,$dbi)){
			$aThisGame[]=array($gid,$gmkey,$gtype,$gstatus,$gpid,$gppid,$pfname,$plname);
		}
		$aThisGame[0][8]=$hometeam;$aThisGame[1][8]=$hometeam;
		$aThisGame[2][8]=$awayteam;$aThisGame[3][8]=$awayteam;
		echo _LS_renderPairsGameRow($event,$editmode,$matchdate,$aThisGame);
		/*
		 * END PAIRS
		 */
		return;
	}else{
		echo 'This Game Type is not supported ...';
		return;
	}
}

/**
*	purpose:	render the content of the detail pane for a darts-based statistics
* 	params:		gameID
*	returns:	HTML Table
*/
function _getGameDetailTable($GameID,$eventID,$match_key){return _LegInputForm($GameID,$match_key,$eventID);}

/**
*	purpose:	remove a specific Leg
* 	params:	
*	returns:	0/1
*/
function _removeLeg($legID,$GameID,$match_key,$eventID){return _LS_RemoveLeg($legID,$GameID,$match_key,$eventID);}

/**
*	purpose:	add a leg to the interface ...
* 	params:		
*	returns:	0/1
*/
function _addLeg($GameID,$playerID,$match_key,$eventID){return _LS_addLeg($GameID,$playerID,$match_key,$eventID);}

/**
*	purpose:	save essential game values Players,Result
* 	params:		array with JSON strings gpid:gid:pid:tid:sets:legs
*	returns:	added sql return codes
*/
function _savegame($match_key,$eventID,$aRes){return _LS_saveGame($match_key,$eventID,$aRes);}

function _blankGame(){die_red('E:WrongAction');}

/**
*	purpose:	reset a game to start - wipe all legstat and playerassoc ...
* 	params:		
*	returns:	0/1
*/
function _resetGame($GameID,$match_key){return _LS_resetGame($GameID,$match_key);}

/**
*	purpose:	handler for the incoming Leg Detail DATA
* 	params:		json string
*	returns:	0/1
*/
function _saveLegs($eventID,$match_key,$str_LData){
	/*
	 * here we receive a mixture of update and insert requests
	 */
	global $usertoken;
	$aLDATA=explode('|',$str_LData);
	if (sizeof($aLDATA)<1)debug('E131:LegDATA empty');
	$p=0;
	foreach($aLDATA as $L){
		if (strlen($L)>3){
			$aLegValues=explode(',',$L);
			if (sizeof($aLegValues)<3)debug('E:135:UnexpectedLegData');
			$p+=_LS_InsUpdateLegRecord($eventID,$aLegValues);
		}
	}
	dsolog(1,$usertoken['uname'],'Saved Legs: Match '.$match_key.' Data:'.$str_LData);
	return $p;
}



header('Content-Type: application/html; charset=ISO-8859-1');

switch($myAction){
	default:
		_blankGame();
		break;
	case 'get':
		echo _getGameTable($GameID,$eventID);break;
	case 'save':
		if(_savegame($match_key,$eventID,$aRes)>0){
			echo _getGameTable($GameID,$eventID);
		}else{die_red('E152:GameResultSave');}
		break;
	case 'addleg':
		if (_addLeg($GameID,$playerID,$match_key,$eventID)==1){
			echo _getGameDetailTable($GameID,$eventID,$match_key);
		}else{die_red('E157:LegAdd');}
		break;
		
	case 'delleg':
		if (_removeLeg($legID,$GameID,$match_key,$eventID)==1){
			echo _getGameDetailTable($GameID,$eventID,$match_key);
		}else{die_red('E163:LegRemove');}
		break;
		
	case 'reset':
		if(_resetGame($GameID,$match_key)>0){
			echo _getGameTable($GameID,$eventID);
		}else{die_red('E169:GameReset');}
		break;
	
	case 'gdetail':
		echo _getGameDetailTable($GameID,$eventID,$match_key);break;
	case 'savelegs':
		if(_saveLegs($eventID,$match_key,$str_LData)){
			_LS_updateGameRecordFromLegData($eventID,$GameID);
			echo _getGameTable($GameID,$eventID);
		}else{die_red('E176:LegSave');}
		break;
}

?>