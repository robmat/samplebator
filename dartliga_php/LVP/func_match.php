<?php
/*
 * file: 		func_match.php
 * contains:	active edit functions for match and game manipulation
 * version:		v4a
 * 
 */

if (eregi("func_match.php",$_SERVER['PHP_SELF'])) {Header("Location: ./"); die();}

/**
*	purpose:	render a complete TABLE for a single game including all details but NO Action Buttons
* 	params:		event[],admin 0/1,$P=gameplayerrecords (2/4)
*	returns:	HTML TABLE
*/
function _LS_renderSingleGameRow($event,$editmode,$matchdate,&$PH,&$PA){
	
	# structure: P $gid,$gmkey,$gtype,$gstatus,4=$gpid,$gppid,$pfname,$plname,8=$tid
	if ($PH[0]<>$PA[0])die('E:M10:GameIDWrong');
	
	$statvalH=0;$statvalA=0;
	$legswonH=0;$legswonA=0;
	$OUT='';
	
	#// generate statistics values based on the list NUM
	
	$statvalH=number_format(RetStatValForPlayerOnDate($PH[5],$event['evstatcode_id'],$matchdate),2,'.','');
	$statvalA=number_format(RetStatValForPlayerOnDate($PA[5],$event['evstatcode_id'],$matchdate),2,'.','');
	if ($editmode==1) {
		$playerselectH=SelectPlayerFromTeam('ph'.$PH[0],$PH[8],$PH[5],$event['evpassfield'])._input(0,'th'.$PH[0],$PH[8])._input(0,'gph'.$PH[0],$PH[4]);
		$playerselectA=SelectPlayerFromTeam('pa'.$PA[0],$PA[8],$PA[5],$event['evpassfield'])._input(0,'ta'.$PA[0],$PA[8])._input(0,'gpa'.$PA[0],$PA[4]);
	}
	$legswonH=GetLegsWonThisGame($PH[0],$PH[5]);
	$legswonA=GetLegsWonThisGame($PA[0],$PA[5]);
			
	if ($editmode==1) {
		$OUT=$OUT.'<table width="100%"><tr>';	# // open ROW and GAME DIV
		#$OUT=$OUT.'<td>S:'.$PH[0].'</td><td align="right">'.$playerselectH.'<br/>'.$statvalH.'</td>';
		$OUT=$OUT.'<td align="right">'.$playerselectH.'<br/>'.$statvalH.'</td>';
	} else {
		$OUT=$OUT.'<table width="100%"><tr><td></td><td width="30%" align="right">'.$PH[7].' '.$PH[6].'<br/>'.$statvalH.'</td>';
	}
	
	$OUT=$OUT.'<td width="10%">';
	if ($event['evsgldarts']==1) $OUT=$OUT.LegTableDarts($event['evsgldist'],$PH[0],$PH[5]);
	if ($event['evsglroundcheck']==1) $OUT=$OUT.LegTableRounds($event['evsgldist'],$PH[0],$PH[5]);
	$OUT=$OUT.'</td>';
	
	if ($editmode==1) {
		$OUT=$OUT.'<td align="right">'._input(1,'lh'.$PH[0],$legswonH,2).'</td>';
	} else {
	 	$OUT=$OUT.'<td align="right">'._input(2,'lh'.$PH[0],$legswonH,2).'</td>';
	 }
	# 
	# Player from AWAY team
	#	
	 if ($editmode==1) {	
		$OUT=$OUT.'<td >'._input(1,'la'.$PA[0],$legswonA,2).'</td>';
	 } else {
	 	$OUT=$OUT.'<td >'._input(2,'la'.$PA[0],$legswonA,2).'</td>';
	 }
	$OUT=$OUT.'<td width="10%">';
	if ($event['evsgldarts']==1) $OUT=$OUT.LegTableDarts($event['evsgldist'],$PA[0],$PA[5]);
	if ($event['evsglroundcheck']==1) $OUT=$OUT.LegTableRounds($event['evsgldist'],$PA[0],$PA[5]);
	$OUT=$OUT.'</td>';
	
	if ($editmode==1) {
		$OUT=$OUT.'<td>'.$playerselectA.'<br/>'.$statvalA.'</td>';
	} else {
		$OUT=$OUT.'<td width="30%">'.$PA[7].' '.$PA[6].'<br/>'.$statvalA.'</td>';
	}
	$OUT=$OUT.'</tr></table>';
	return $OUT;
}

/**
*	purpose:	receive a complete GameArray and render as Table into a TD Tag
* 	params:		GameArray(default size=4)
*	returns:	HTML TABLE
*/
function _LS_renderPairsGameRow($event,$editmode,$matchdate,$aThisGame){
	# structure: [0] $gid,$gmkey,$gtype,$gstatus,4=$gpid,$gppid,$pfname,$plname,8=$tid
	if ($aThisGame[0][0]<>$aThisGame[3][0])die('E:M10:GameIDWrong');
	/*
	 * in pairs mode we have no statval display
	 */
	$game_ID=$aThisGame[0][0];
	$aPlayerSelect=array();
	$aLegsWon=array();
	$i=0;
	foreach($aThisGame as $rec){
		#debug($rec);
		if ($editmode==1) {
			$aPlayerSelect[]=SelectPlayerFromTeam('c'.$i.'p'.$game_ID,$rec[8],$rec[5],$event['evpassfield'])
							._input(0,'c'.$i.'t'.$game_ID,$rec[8])
							._input(0,'c'.$i.'gp'.$game_ID,$rec[4]);
		} else {
			$aPlayerSelect[]=$rec[6].' '.$rec[7];
		}
		$aLegsWon[]=GetLegsWonThisGame($game_ID,$rec[5]);
		$i++;
	}
	
	$OUT='';
	$OUT=$OUT.'<table width="100%"><tr>';
	#if ($editmode==1) {$OUT=$OUT.'<td>S:'.$game_ID.'</td>';}
	$OUT=$OUT.'<td width="30%" align="right">'.$aPlayerSelect[0].'<br/>'.$aPlayerSelect[1].'</td>';
	$OUT=$OUT.'<td width="10%"></td>';
	if ($editmode==1) {$OUT=$OUT.'<td width="10%" align="right">'._input(1,'lh'.$game_ID,$aLegsWon[0],2).'</td>';}
		else{$OUT=$OUT.'<td width="10%" align="right">'._input(2,'lh'.$game_ID,$aLegsWon[0],2).'</td>';}
	if ($editmode==1) {$OUT=$OUT.'<td width="10%">'._input(1,'la'.$game_ID,$aLegsWon[2],2).'</td>';}
		else{$OUT=$OUT.'<td width="10%">'._input(2,'la'.$game_ID,$aLegsWon[2],2).'</td>';}
	$OUT=$OUT.'<td width="10%"></td>';
	$OUT=$OUT.'<td width="30%">'.$aPlayerSelect[2].'<br/>'.$aPlayerSelect[3].'</td>';
	$OUT=$OUT.'</tr></table>';
	
	return $OUT;
}

/**
*	purpose:	create editable LegTable or both TEAMS to be loaded into Game-Detail DIV
* 	params:		
*	returns:	HTML Table + action Buttons
*/
function _LegInputForm($vgid,$vmkey,$eventid){
	/*
	 * render a complete LegInput Form + close Button, either from existing data or generate empty rows ...
	 * a) get players
	 * b) get table for each player
	 */
	global $dbi,$usertoken;
	$event=reteventconfig($eventid);
	$aPID=array();
	$aGAME=DB_getGame($dbi,$vgid);	// ==> get basic GameData must be 2/4 records
	#pid=6
	if (!sizeof($aGAME)>1){die_red('E91:ErrorRetrievingPlayers');}
	
	$Game_Type=$aGAME[0][1];
	$aPID[0]=$aGAME[0][6];
	if ($Game_Type==1){$aPID[1]=$aGAME[1][6];$maxleg=$event['evsgllegs'];}
		else{die_red('E96:NoDoubleConfig');$maxleg=$event['evdbllegs'];}
	
	# a) get players + LegData involved in this game 
	
	$OUT='<p style="text-align:right">'._imgButton('close','closeed('.$vgid.')').'</p>'.OpenTable('',1);
	$OUT=$OUT.'<table width="100%" border="0"><tr>';
	
	foreach($aPID as $pid){
		$OUT=$OUT.'<td valign="top"><table id="TG'.$vgid.'P'.$pid.'">';	#code the table with G+gid+P+pid
		/*
		 * for every player involved we render the TD+LegTable + Buttons ....
		 */
		$RS=DB_getGame($dbi,$vgid,$event['evsgldarts'],$event['evsglroundcheck'],$pid,$event['evsgldist']);
		if(sizeof($RS)<1){
			$OUT=$OUT._LS_leglegend($event);
			// empty rows here ....
			for($i=0;$i<$maxleg;$i++){
				$OUT=$OUT._LS_legrow($event,array_fill(0,15,0));
			}
		} else {
			$OUT=$OUT._LS_leglegend($event);
			foreach($RS as $LegRow){
				$OUT=$OUT._LS_legrow($event,$LegRow);
			}
		}
		$OUT=$OUT.'</table></td>';
	}
	
	$OUT=$OUT.'</tr><tr>';
	/*
	 * now the controls for each player
	 */
	foreach($aPID as $pid){
		$OUT=$OUT.'<td>'._imgButton('add','addLeg('.$vgid.','.$pid.')');
		$OUT=$OUT._imgButton('save','saveLegs('.$vgid.','.$pid.')').'</td>';
	}
	return $OUT.'</tr>'.CloseTable(1);
	
}


/**
 * returns legend TR for typical vertical legbox according to the event-config
 * first TD = empty for controls
 * v4 business layer spans over 2 cells
 **/
function _LS_leglegend($objEvent){
		$strret='<tr class="legend"><td class="btnlegend"></td>';
		if ($objEvent['evsgldarts']==1){
			if ($objEvent['evsglstart']==1) $strret=$strret.'<td class="legend">Anfang</td>';
			if ($objEvent['evsgldarts']==1) $strret=$strret.'<td class="legend">Darts</td>';
			if ($objEvent['evsglrest']==1) $strret=$strret.'<td class="legend">Rest</td>';
			if ($objEvent['evsglfinish']==1) $strret=$strret.'<td class="legend">Finish</td>';
			if ($objEvent['evsglhighscore']==1) $strret=$strret.'<td class="legend">Max</td>';
		} elseif ($objEvent['evsglroundcheck']==1){
			if ($objEvent['evsglstart']==1) $strret=$strret.'<td class="legend">Anfang</td>';
			if ($objEvent['evsglroundscore']==1) $strret=$strret.'<td class="legend">Runde &lt; 159</td>';
			if ($objEvent['evsglrest']==1) $strret=$strret.'<td class="legend">Rest</td>';
			if ($objEvent['evsglroundcheck']==1) $strret=$strret.'<td class="legend">Runde Check</td>';
			if ($objEvent['evsglfinish']==1) $strret=$strret.'<td class="legend">Finish</td>';
			if ($objEvent['evsglhighscore']) $strret=$strret.'<td class="legend">Max</td>';
		} else {
			if ($objEvent['evsglfinish']==1) $strret=$strret.'<td class="legend">Finish</td>';
			if ($objEvent['evsglhighscore']) $strret=$strret.'<td class="legend">Max</td>';
		}
		return $strret."</tr>";
	}

/**
 * return a horizontal leg row according to the values passed and the event-config
 * this is a legrow for individual leg data
 * currently SINGLE only ..
 **/	
function _LS_legrow($objEvent,$data=array()){
	// data structure from api_rs:
	// G.gid,G.gtype,GP.gpid,GP.gpsetwon,GP.gplegswon,GP.gptid,P.pid,P.pfname,P.plname
	//	[L.lid,L.lstart,L.ldarts,(501-L.lscore),L.lfinish,L.lhighscore]
	//	[L.lid,L.lstart,L.lroundscore,L.lroundcheck,(501-L.lscore),L.lfinish,L.lhighscore]
	#if (sizeof($data)<10){$idname="RES";} else {$idname="L".$data[8];}
	if (sizeof($data)>9){$legID=$data[9];} else {$legID=0;}
	
	$strret='<tr id="'.$legID.'"><td>'._imgButton('remove','remLeg('.$legID.','.$data[0].')').'</td>';
	if ($objEvent['evsgldarts']==1){
		if ($objEvent['evsglstart']==1) $strret=$strret.'<td>'._checkbox('B',$data[10]).'</td>';
		if ($objEvent['evsgldarts']==1) $strret=$strret.'<td>'._input(1,'D',$data[11],3,3).'</td>';
		if ($objEvent['evsglrest']==1) $strret=$strret.'<td>'._input(1,'R',$data[12],3,3).'</td>';
		if ($objEvent['evsglfinish']==1) $strret=$strret.'<td>'._input(1,'F',$data[13],3,3).'</td>';
		if ($objEvent['evsglhighscore']==1) $strret=$strret.'<td>'._input(1,'M',$data[14],3,3).'</td>';
	} elseif ($objEvent['evsglroundcheck']==1){
		if ($objEvent['evsglstart']==1) $strret=$strret.'<td>'._checkbox('B',$data[10]).'</td>';
		if ($objEvent['evsglroundscore']==1) $strret=$strret.'<td>'._input(1,'S',$data[11],3,3).'</td>';
		if ($objEvent['evsglrest']==1) $strret=$strret.'<td>'._input(1,'R',$data[13],3,3).'</td>';
		if ($objEvent['evsglroundcheck']==1) $strret=$strret.'<td>'._input(1,'C',$data[12],3,3).'</td>';
		if ($objEvent['evsglfinish']==1) $strret=$strret.'<td>'._input(1,'F',$data[14],3,3).'</td>';
		if ($objEvent['evsglhighscore']) $strret=$strret.'<td>'._input(1,'M',$data[15],3,3).'</td>';
	} else {
		$strret=$strret.'<td>Keine Leg Statistik konfiguriert</td>';
	}
	return $strret.'</tr>';
}	

/**
*	returns:	0/1
**/
function _LS_addLeg($GameID,$playerID,$match_key,$eventID){
	if (!$playerID>0){return 0;}
	if (!$GameID>0){return 0;}
	
	global $dbi,$usertoken;
	$event=reteventconfig($eventID);
	if ($event['evsgldarts']==1) {
		$qry="insert into tblleg values(0,$GameID,$playerID,0,0,0,0,0)";
	} else {
		$qry="insert into tbllegrounds values(0,$GameID,$playerID,0,0,0,0,0,0)";
	}
	$p1=sql_query($qry,$dbi);
	dsolog(1,$usertoken['uname'],"Added Leg to Game: $GameID Player: $playerID Match: $match_key");
	return $p1;
}

/**
*	returns:	0/1
**/
function _LS_RemoveLeg($legID,$GameID,$match_key,$eventID){
	if (!$legID>0){return 0;}
	if (!$GameID>0){return 0;}
	
	global $dbi,$usertoken;
	$event=reteventconfig($eventID);
	if ($event['evsgldarts']==1) {
		$sql='DELETE from tblleg where lid='.$legID.' AND lgid='.$GameID.' AND lpid>0 limit 1';
	} else {
		$sql='DELETE from tbllegrounds where lid='.$legID.' AND lgid='.$GameID.' AND lpid>0 limit 1';
	}
	$p1=sql_query($sql,$dbi);
	dsolog(1,$usertoken['uname'],"Removed Leg from Game: $GameID Match: $match_key");
	return $p1;
}

/**
 * 	purpose: resets a game to start, deletes all associations and data
 * 	TODO v5 use the same usercode protection as in tblmatch ???
*	returns:	0/1
**/
function _LS_resetGame($GameID,$match_key){
	global $dbi,$usertoken;
	$qry='update tblgame set gstatus=0 where gid='.$GameID.' limit 1';
	$p1=sql_query($qry,$dbi);
	$qry='update tblgameplayer set gppid=0,gpsetwon=0,gplegswon=0 where gpgid='.$GameID.' limit 4';
	$p2=sql_query($qry,$dbi);
	$qry='delete from tblleg where lgid='.$GameID.' AND lpid>0';
	$p3=sql_query($qry,$dbi);
	$qry='delete from tbllegrounds where lgid='.$GameID.' AND lpid>0';
	$p4=sql_query($qry,$dbi);
	dsolog(3,$usertoken['uname'],'Game Reset: '.$GameID.' Match: '.$match_key.'.');
	if (($p1+$p2+$p3+$p4)>2){return 1;}else{return 0;}
}


/**
*	purpose:	update / INSERT into existing LEG Record, function is executed once for each Leg
* 	params:		$aLegValuePairs array containing ["L:xx" "G:xx" "P:xx" ....]
*	returns:	ORM class Error String
* 	logic:		depending on some key values the SAVE is discarded
*/
function _LS_InsUpdateLegRecord($eventID,&$aLegValuePairs){
	require_once("../ORM/leg.php");
	require_once("../ORM/legrounds.php");
	global $dbi;
	$event=reteventconfig($eventID);
	$leg_dist=$event['evsgldist'];
	if (!$leg_dist>1) die_red('Err312:UnknownLegDistance');
	$leg_ID=0;
	/*
	 * retrieve the l_id value from the key-pairs
	 */
	foreach($aLegValuePairs as $keypair){
		if (substr($keypair,0,1)=='L'){$leg_ID=substr($keypair,2);}
	}
	
	if ($event['evsgldarts']==1) {
		$cL=new cLeg;
	}else{
		$cL=new cLegRounds;
	}
	$cL->setDB($dbi);
	$cL->getbyID($leg_ID);
	/*
	 * decode the denominator strings G,P,S,F ... into field names
	 * set sensible defaults, any OUTPUT is written into the Game-DIV
	 * WE use the new ORM objects for this ...
	 */
	foreach($aLegValuePairs as $keypair){
		$aVal=explode(':',$keypair);
		if (!is_numeric($aVal[1])){$aVal[1]=0;}
		if (sizeof($aVal)>2) die_red('E274:SaveLeg:KeyValueError:Please correct and save again');
		/*
		 * decode json and pass values into objects
		 */
		switch(strtoupper($aVal[0])){
			case 'G':
				$cL->aDATA['lgid']=$aVal[1];break;
			case 'P':
				$cL->aDATA['lpid']=$aVal[1];break;
			case 'S':
				$cL->aDATA['lroundscore']=$aVal[1];break;
			case 'D':
				$cL->aDATA['ldarts']=$aVal[1];break;
			case 'R':
				$cL->aDATA['lscore']=$leg_dist-$aVal[1];break;
			case 'C':
				$cL->aDATA['lroundcheck']=$aVal[1];break;
			case 'F':
				$cL->aDATA['lfinish']=$aVal[1];break;
			case 'B':
				$cL->aDATA['lstart']=$aVal[1];break;
			case 'M':
				$cL->aDATA['lhighscore']=$aVal[1];break;
		}
	}
	/*
	 * logic tests ... same as in GUI ... shall we put this into the DBclass ???
	 */
	if (!$cL->aDATA['lpid']>0) return 0;
	if (!$cL->aDATA['lgid']>0) return 0;
	if ($event['evsgldarts']==1) {
		if (!$cL->aDATA['ldarts']>0) return 0;
	}else{
		if (!$cL->aDATA['lroundscore']>0) return 0;
		if (!$cL->aDATA['lroundcheck']>0) return 0;
	}
	#debug($cL->aDATA);
	$cL->save();
	return $cL->pDBret;
}

/**
*	purpose:	check LegData on WON records and update the GameResult accordingly
* 	params:		$gameID
*	returns:	NOTHING
*/
function _LS_updateGameRecordFromLegData($eventID,$gameID){
	global $dbi,$usertoken;
	$event=reteventconfig($eventID);
	if ($event['evsgldarts']==1) {$TBL='tblleg';}else{$TBL='tbllegrounds';}
	/*
	* Query: select lgid,lpid,count(lscore) CWON from tblleg where lscore=501 and lgid=299 group by lpid;
	* ATT: this returns ONLY the won legs .... we have no idea about the second player in the case of a X:0
	* 	| lpid | CWON |
	*	+------+------+
	* 	|  394 |    3 |
	* 	|  790 |    1 | 
	 */
	$p1 = sql_query('select lgid,lpid,count(lscore) CWON from '.$TBL.' WHERE lscore='.$event['evsgldist'].' AND lgid='.$gameID.' GROUP by lpid',$dbi);
	$i=0;
	while(list($v_gid,$v_pid,$v_CWON)=sql_fetch_row($p1,$dbi)){
		$vlgid[$i]=$v_gid;
		$vlpid[$i]=$v_pid;
		$vlegswon[$i]=$v_CWON;
		$i=$i+1;
	}
	/*
	 * Action
	 * check on legswon array - perhaps this is a result only SAVE and there is no actual LEGDATA
	 * ==> jump to the _LS_saveGame here ...
	 */
	$rcount=sizeof($vlegswon);
	// should be 2 if both players won a leg else its just 1 ....
	// execute this block only if $rcount > 0 .... the default legswon entry is zero anyway ....
	// the problem is that when accidentially a result of 3:1 is reset to 3:0 we dont get data from one player
	// => reset both LEG and SET than update with actuals
	$legsforset=ceil($event['evsgllegs']/2);
	if ($rcount>0) {
		// get all players involved in this game and reset ALL game-results to zero
		$resP=sql_query('SELECT lpid from '.$TBL.' WHERE lgid='.$gameID.' group by lpid',$dbi);
		while(list($PID)=sql_fetch_row($resP,$dbi)){
			$reset_qry='UPDATE tblgameplayer set gplegswon=0,gpsetwon=0 WHERE gppid='.$PID.' and gpgid='.$gameID.' limit 1';
			$res1=sql_query($reset_qry,$dbi);
		}
		// OK now we load the new and corrected values ...
		for ($i=0;$i<$rcount;$i++){
			# // this is ALWAYS an update since the GAME records already exist at that point ...
			if ($vlegswon[$i]>=$legsforset) {
				$sSET='set gplegswon='.$vlegswon[$i].',gpsetwon=1';
			}	elseif ($vlegswon[$i]<$legsforset){
				$sSET='set gplegswon='.$vlegswon[$i].',gpsetwon=0';
			}
			$sql ='UPDATE tblgameplayer '.$sSET.' WHERE gppid='.$vlpid[$i].' AND gpgid='.$gameID.' limit 1';
			$res1=sql_query($sql,$dbi);
			#if($event['evssilink']==1) _exSSILINK($vlgid[$i],$vlpid[$i],$vlegswon[$i]);
		}
	}
}

function _LS_saveGame($match_key,$eventID,$aRes){
	/*
	 * TODO v5 change to json string encoding G:L:P: as in the savelegs ...
	 * in ax-POST mode we always have 2/4 arrays
	 * currently: gpid:gid:pid:tid:legs
	 * find out what is needed to win a set in this event ...
	 */
	global $dbi,$usertoken;
	$event=reteventconfig($eventID);
	$legsforset=ceil($event['evsgllegs']/2);
	$out=0;
	
	foreach($aRes as $a){
		$res=explode(':',$a);
		if (sizeof($res)>2){
			if ($res[4]>=$legsforset) {
				$sSET=',gplegswon='.$res[4].',gpsetwon=1';
			} else {
				$sSET=',gplegswon='.$res[4].',gpsetwon=0';
			}
			$qry='UPDATE tblgameplayer set gppid='.$res[2].$sSET
			.'  WHERE gpid='.$res[0].' and gptid='.$res[3].' limit 1';
			$p1=sql_query($qry,$dbi);
			$out=$out+$p1;
		}
	}
	#debug('New Result '.$aRes[0].':'.$aRes[1].' Match '.$match_key.' by direct edit.');
	dsolog(1,$usertoken['uname'],'Result '.$aRes[0].':'.$aRes[1].' Match: '.$match_key.'.');
	return $out;
}

?>