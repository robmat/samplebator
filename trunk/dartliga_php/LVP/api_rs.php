<?php
	###########################################################################################
	/**
	 * file: 			api_rs.php
	 * Modul:		CONTROLLER + MODELL
	 * purpose:	serves as the general DB backend API serving the business modell for the application
	 * returns: 	access the DB and returns resultsets as arrays, 
	 * requires: 	global $dbi
	 *
	*
	*
	 * Conventions:
	 * Recordset Layout: -> left to right from general to specific, id precedes Namestring
	 */
	###########################################################################################
if (eregi("api_rs.php",$_SERVER['PHP_SELF'])) {
    Header("Location: ./");
    die();
}

	/**
	 * purpose:	get event object based on query params
	 * returns:	assoc named array
	 */
function DB_getEvent($DB,$event_id=0,$matck_key="",$match_id=0,$game_id=0,$team_id=0){
	$sw=array();
	if ($event_id>0) $qry="Select * from tblevent E where E.id=$event_id";
	if (strlen($matck_key)>0) $qry="select * from tblevent where id=(select M.mevid from tblmatch M where M.mkey='$matck_key')";
	if ($match_id>0) $qry="select * from tblevent where id=(select M.mevid from tblmatch M where M.mid=$match_id)";
	if ($game_id>0) $qry="select * from tblevent where id=(select M.mevid from tblmatch M where M.mkey=(select gmkey from tblgame where gid=$game_id))";
	if ($team_id>0) $qry="select * from tblevent where id=(select T.tevent_id from tblteam T where T.id=$team_id)";
	
	$prec=sql_query($qry,$DB);
	$event=sql_fetch_array($prec,$DB);
	return $event;
}

/**
*	purpose:	list Memberships
* 	params:		player,verein,type,realm,current(1,0)(actual,previous)
*	returns:	recordset ordered by person - austritt
*/
function DB_listMemberShips($DB,$player_id=0,$verein_id=0,$member_type_comp='>0',$current=1,$verband_id=0){
	if ($player_id>0) $sw[]='P.pid='.$player_id;
	if ($verein_id>0) $sw[]='V.vid='.$verein_id;
	if ($verband_id>0) $sw[]='V.verband_id='.$verband_id;
	if (strlen($member_type_comp)>0) $sw[]='M.mtype'.$member_type_comp;
	
	If ($current==1) {
			$sw[]='mend>current_date';
	} elseif ($current==0){
			$sw[]='mend<current_date';
	}
	$sWhere=' WHERE M.mtype=TM.id';
	$args = count($sw);
	for ($c=0; $c < $args; $c++) {
		$sWhere=$sWhere.' AND '.$sw[$c];
	}
	$qry='select M.mid,V.vid,V.vname,TM.memberdesc,M.mpassnr,M.mend,P.pid,P.pfname,P.plname'
	.' FROM tmembership M join tverein V on M.mvereinid=V.vid join tplayer P on M.mpid=P.pid,ttypemember TM'
	.$sWhere.' ORDER by P.plname,P.pfname,mend DESC';
	#debug($qry);
	$presult=sql_query($qry,$DB);
	$RET=createRecordSet($presult,$DB);
	return $RET;
}

	/**
	 * purpose	list events
	 * search:	id, group, statcode,name,active
	 * param:	format=full/tiny
	 */
function DB_listEvents($DB,$event_active=1,$event_id=0,$event_name="",$event_group=0,$event_stat_group=0,$event_member_code=0,$format='tiny'){
	$sw=array();
	if (is_numeric($event_active)) $sw[]="E.evactive=$event_active";
	if ($event_id>0) 	$sw[]="E.id=$event_id";
	if ($event_group>0) 	$sw[]="E.evtypecode_id=$event_group";
	if ($event_stat_group>0) 	$sw[]="E.evstatcode_id=$event_stat_group";
	if ($event_member_code>0) 	$sw[]="E.evmembercode_id=$event_member_code";
	if (strlen($event_name)>0) $sw[]="E.evname like \"%$event_name%\"";
	if ($format=="tiny"){
		$sSelect="select E.id,E.evname";
	} else {
		$sSelect="select E.id,E.evname,E.evyear,E.evstatcode_id,S.typdesc,E.evtypecode_id,L.typdesc,E.evpassfield,E.evmembercode_id";
	}
	$sWhere="WHERE E.evstatcode_id=S.id AND E.evtypecode_id=L.id";
	$sOrder="ORDER by evname";
	$args = count($sw);
	for ($c=0; $c < $args; $c++) {
		$sWhere=$sWhere." AND ".$sw[$c];
	}
	
	$qry=$sSelect." from tblevent E,ttypestat S,ttypeliga L ".$sWhere." ".$sOrder;
	#debug($qry);
	$presult=sql_query($qry,$DB);
	$RET=createRecordSet($presult,$DB);
	return $RET;
}

/**
*	purpose:	list all possible player / line up types
* 	params:		none
*	returns:	simple listing of Player Types, used by select boxes
*/
function DB_listLineUpTypes($DB){
	$sw=array();
	$qry="select id,text from ttypeplayer";
	$presult=sql_query($qry,$DB);
	$RET=createRecordSet($presult,$DB);
	return $RET;
}

	/**
	 * purpose generate tabelle
	 * search: event_id, event_group
	 * param: mode=point/legs, format=full/small/tiny
	 */
function DB_getTabelle($DB,$event_id=0,$event_group=0,$mode='set',$format='small'){
	$sw=array();
	if ($event_id>0) $sw[]="E.id=$event_id";
	if ($event_group>0) $sw[]="E.evtypecode_id=$event_group";
	if ($mode=="set"){
		$sOrder="order by E.id,SDIFF desc,LDIFF desc";
	} elseif ($mode=="point"){
		$sOrder="order by E.id,PT desc,SDIFF desc,LDIFF desc";
	}
	if ($format=="tiny"){
		if ($mode=="set") $sSelect=",sum(mtsets) SETSW";
		if ($mode=="point") $sSelect=",sum(mtpoints) PT";
	}else{
		$sSelect=",sum(mtsets) SETSW,sum(mtsetslost) SETSL,sum(mtsets)-sum(mtsetslost) SDIFF,sum(mtlegs) LEGSW,sum(mtlegslost) LEGSL,sum(mtlegs)-sum(mtlegslost) LDIFF, count(mstatus) CT,sum(mtpoints) PT";
	}
	$sWhere="where E.evactive=1 and E.id=T.tevent_id and mttid=T.id and mtmkey=mkey and mstatus<>0";
	$args = count($sw);
	for ($c=0; $c < $args; $c++) {
		$sWhere=$sWhere." AND ".$sw[$c];
	}
	$qry="select E.evtypecode_id,E.id,E.evname,T.id,T.tname".$sSelect
	." from tblevent E,tblmatchteam,tblmatch,tblteam T $sWhere group by E.id,mttid $sOrder";

	#debug($qry);
	$presult=sql_query($qry,$DB);
	$RET=createRecordSet($presult,$DB);
	return $RET;
}

	/**
	* purpose:	controller for returning all sort of Match arrays ...
	* search: 	event_id, Team_id, Hometeam_id,matchdate
	* param: 	MODE=raw / logic -- either raw records or logically re-arranged for schedule records
	* comparison arguments inklude the operator : i.e. "<\"2007-11-01\"" or "<curdate()"
	* Fields returned RAW :
	* 0=E.ID,E.evname,M.mid,M.mkey,M.mround,
	* 5=M.mdate,M.mlocation,M.mstatus,MT.mthome,MT.mtsets,
	* 10=MT.mtlegs,T.id,T.tname
	* Fields returned LOGIC:
	* 0=evid,evname,mid,mkey,mround,
	* 5=mdate,mlocation,tname,mvsets,mvlegs,
	* 10=mvsets,mvlegs,tname,mstatus
	*/ 
function DB_listMatches($DB,$event_active=1,$event_id=0,$team_id=0,$team_name="",$match_date_comp="",$home_flag="",$match_status_comp="",$mode='raw',$matchkey='',$event_group_id=0){
	$sw=array();
	$sWhere="where E.ID=M.mevid AND M.MKEY=MT.MTMKEY AND MT.MTTID=T.ID";
	if (is_numeric($event_active)) $sw[]="E.evactive=$event_active";
	if ($team_id>0) $sw[]='T.id='.$team_id;
	if ($event_id>0) $sw[]='E.ID='.$event_id;
	if ($event_group_id>0) $sw[]='E.evtypecode_id='.$event_group_id;
	if (strlen($home_flag)>0) $sw[]="MT.mthome=$home_flag";
	if (strlen($match_date_comp)>0) $sw[]="M.mdate".$match_date_comp;
	if (strlen($team_name)>0) $sw[]="T.tname like \"%$team_name%\"";
	if (strlen($match_status_comp)>0) $sw[]="M.mstatus".$match_status_comp;
	if (strlen($matchkey)>0) $sw[]="M.mkey like '%$matchkey%'";
	
	$args = count($sw);
	for ($c=0; $c < $args; $c++) {
		$sWhere=$sWhere." AND ".$sw[$c];
	}
	$sOrder="order by E.ID,M.MROUND,M.MKEY,MT.MTHOME desc";
	$qry="select E.ID,E.evname,M.mid,M.mkey,M.mround,M.mdate,M.mlocation,M.mstatus,MT.mthome,MT.mtsets,MT.mtlegs,T.id,T.tname"
				." from tblevent E,tblmatch M,tblmatchteam MT,tblteam T $sWhere $sOrder";
	#debug($qry);
	$presult=sql_query($qry,$DB);
	if ($mode=='raw'){
		$RET=createRecordSet($presult,$DB);
	}elseif ($mode=='logic'){
		$RET=createScheduleRecordSet($presult,$DB);
	}
	return $RET;
}
	
	/**
	 * controller for returning possible MatchStatus entries
	 * params_search: active,id,name
	 */
function DB_listMatchStatus($DB){
	$sw=array();
	$qry="select id,shortdesc,icon,longdesc from ttypematchstatus order by id";
	$presult=sql_query($qry,$DB);
	$RET=createRecordSet($presult,$DB);
	return $RET;
}

function DB_listMemberType($DB,$active=1){
	$sw=array();
	$qry='select M.id,M.memberdesc from ttypemember M where M.memberactive='.$active.' ORDER by M.id asc';
	$presult=sql_query($qry,$DB);
	$RET=createRecordSet($presult,$DB);
	return $RET;
}

	/**
	 * controller for returning LigaGroups
	 * params_search: active,id,name
	 */
function DB_listLigaGruppen($DB,$group_active,$group_id=0,$group_name=""){
	$sw=array();
	if (is_numeric($group_active)) $sWhere="WHERE LG.typactive=$group_active";
	if ($group_id>0) $sw[]="LG.id =$group_id";
	if (strlen($group_name)>0) $sw[]="LG.typdesc like \"%$group_name%\"";
	
	$args = count($sw);
	for ($c=0; $c < $args; $c++) {
		$sWhere=$sWhere." AND ".$sw[$c];
	}
	$sOrder="ORDER BY LG.typdesc asc";
	$qry="select LG.ID,LG.typdesc from ttypeliga LG $sWhere $sOrder";
	#debug($qry);
	$presult=sql_query($qry,$DB);
	$RET=createRecordSet($presult,$DB);
	return $RET;
}

	/**
	 * controller for returning LigaGroups
	 * params_search: active,id,name
	 */
function DB_listStatGruppen($DB,$group_active,$group_id=0,$group_name=""){
	$sw=array();
	if (is_numeric($group_active)) $sWhere="WHERE S.statactive=$group_active";
	if ($group_id>0) $sw[]="S.id =$group_id";
	if (strlen($group_name)>0) $sw[]="S.statdesc like \"%$group_name%\"";
	
	$args = count($sw);
	for ($c=0; $c < $args; $c++) {
		$sWhere=$sWhere." AND ".$sw[$c];
	}
	$sOrder="ORDER BY S.statdesc asc";
	$qry="select S.ID,S.statdesc from ttypestat S $sWhere $sOrder";
	#debug($qry);
	$presult=sql_query($qry,$DB);
	$RET=createRecordSet($presult,$DB);
	return $RET;
}
/**
*	purpose:	return Dates for specific statistic codes
* 	params:		stat code, active
*	returns:	Recordset
*/
function DB_listStatDate($DB,$stat_code=0){
	if ($stat_code>0){
		$qry="select sdate,sdate from tbldate WHERE sstatcode_id=$stat_code ORDER by sdate desc";
	} else {
		$qry="select sdate,sdate from tbldate ORDER by sdate desc";
	}
	$presult=sql_query($qry,$DB);
	$RET=createRecordSet($presult,$DB);
	return $RET;
}

	/**
	 * controller for returning Team arrays ... 
	 * Joins Event+TEAM+Verein+Location
	 * 
	 * field list:
	 * E.id,E.evtypecode_id,E.evname,E.evyear,T.id,T.tname,V.vid,V.vname,L.id,L.lname
	 */
function DB_listTeams($DB,$event_id=0,$event_typ_id=0,$event_name="",$eventactive=0,$team_name="",$verein_id=0,$verein_name="",$loc_id=0,$location_name=""){	
	$RET=array();
	$sw=array();
	$sWhere="where T.tevent_id=E.id";
	
	// change defaults - override
	if ($eventactive<>0) $sw[]="E.evactive=$eventactive";
	if ($verein_id>0) $sw[]="V.vid =$verein_id";
	if ($loc_id>0) $sw[]="T.tlocation_id = $loc_id";
	if ($event_id>0) $sw[]="E.id = $event_id";
	if ($event_typ_id>0) $sw[]="E.evtypecode_id = $event_typ_id";
	if (strlen($event_name)>0) $sw[]="E.evname like \"%$event_name%\"";
	if (strlen($team_name)>0) $sw[]="T.tname like \"%$team_name%\"";
	if (strlen($verein_name)>0) $sw[]="V.vname like \"%$verein_name%\"";
	if (strlen($location_name)>0) $sw[]="L.lname like \"%$location_name%\"";
	
	$args = count($sw);
	
	for ($c=0; $c < $args; $c++) {
		$sWhere=$sWhere." AND ".$sw[$c];
	}

	$sOrder="order by E.evname,T.tname";
	
	$qry="select E.id,E.evtypecode_id,E.evname,E.evyear,T.id,T.tname,V.vid,V.vname,L.id,L.lname"
		." from tblteam T left join tverein V on T.tverein_id=V.vid left join tbllocation L on T.tlocation_id=L.id, tblevent E ".$sWhere." ".$sOrder;
	#debug($qry);
	#echo $qry;
	$presult=sql_query($qry,$DB);
	$RET=createRecordSet($presult,$DB);
	return $RET;
} // end listTeams()

	/**
	 * purpose: DB wrapper to list public vereins data
	 * returns: recordsetarray => V.vid,V.vname,V.vbundesland,V.vort,V.vaddressclub,V.vemail,V.vwebsite
	 * 
	 * params:
	 * 
	 */
function DB_listVereine($DB,$verein_id=0,$in_realm_clause='',$vname="",$vort="",$vhomepage_link=1){
	$sw=array();
	if ($verein_id>0) $sw[]='V.vid='.$verein_id;
	if (strlen($in_realm_clause)>0) $sw[]='V.verband_id IN ('.$in_realm_clause.')';
	if (strlen($vname)>0) $sw[]="V.vname LIKE '%$vname%'";
	if (strlen($vort)>0) $sw[]="V.vort LIKE '%$vort%'";
	if ($vhomepage_link==1) $sw[]="vHomePageLink=$vhomepage_link";
	$sWhere='WHERE V.verband_id=TV.id';
	$args = count($sw);
	for ($c=0; $c < $args; $c++) {
		$sWhere=$sWhere." AND ".$sw[$c];
	}

	$sOrder=" ORDER by V.verband_id,V.vname";
	
	$qry="select V.vid,V.vname,V.vbundesland,V.vort,V.vaddressclub,V.vemail,V.vwebsite from tverein V,tverband TV ".$sWhere.$sOrder;
	#debug($qry);
	$presult=sql_query($qry,$DB);
	$RET=createRecordSet($presult,$DB);
	return $RET;
}

	/**
	 * v4 listTeam+LineUp+Player, includes no event info as in than DB_listEventTeamPlayers
	 * usefull if the event info is clear and the just the teams are needed ...
	 * returns: Team 1 row per player
	 * 			TP.lid,T.id,T.tname,pid,pfname,plname,pfkey1,pfkey2,TP.ltype
	 */
function DB_listTeamLineUp($DB,$team_id=0,$team_name='',$player_type_id=0,$p_id=0) {
	$sw=array();
	if ($team_id>0) $sw[]="T.id=$team_id";
	if ($p_id>0) $sw[]="P.pid=$p_id";
	if ($player_type_id>0) $sw[]="TP.ltype=$player_type_id";
	if (strlen($team_name)>0) $sw[]="T.tname LIKE '%$team_name%'";
	
	$sWhere=" WHERE TP.lplayerid=P.pid and TP.ltype=TY.ID";
	$args = count($sw);
	for ($c=0; $c < $args; $c++) {
		$sWhere=$sWhere." AND ".$sw[$c];
	}
	$qry="SELECT TP.lid,T.id,T.tname,P.pid,P.pfname,P.plname,P.pfkey1,P.pfkey2,TY.TEXT,TP.leventid,TY.ID"
		." FROM tblteam T left join tblteamplayer TP on T.id=TP.lteamid,tplayer P,ttypeplayer TY".$sWhere
		." ORDER by TP.lid asc";
	$presult=sql_query($qry,$DB);
	$RET=createRecordSet($presult,$DB);
	return $RET;
}

function DB_listWFTeamLineUp($DB,$wfteam_id=0,$wfteam_name='',$player_type_id=0,$p_id=0) {
	$sw=array();
	if ($wfteam_id>0) $sw[]='T.wfteam_id='.$wfteam_id;
	if ($p_id>0) $sw[]='P.pid='.$p_id;
	if ($player_type_id>0) $sw[]='TP.playertype_id='.$player_type_id;
	if (strlen($wfteam_name)>0) $sw[]='T.teamname LIKE \'%$team_name%\'';
	
	$sWhere=' WHERE TP.player_id=P.pid and TP.playertype_id=TY.ID';
	$args = count($sw);
	for ($c=0; $c < $args; $c++) {
		$sWhere=$sWhere.' AND '.$sw[$c];
	}
	$qry='SELECT TP.wflineup_id,T.wfteam_id,T.teamname,P.pid,P.pfname,P.plname,P.pfkey1,P.pfkey2,TY.TEXT'
		.' FROM wfteam T left join wflineup TP on T.wfteam_id=TP.wfteam_id,tplayer P,ttypeplayer TY'.$sWhere
		.' ORDER by TP.wflineup_id asc';
	$presult=sql_query($qry,$DB);
	$RET=createRecordSet($presult,$DB);
	#debug($qry);
	return $RET;
}


/**
*	purpose:	controller for returning selected player objects
* 	params:		
*	returns:	recordset
*/
function DB_listPlayers($DB,$p_id=0,$last_name='',$first_name='',$p_fkey1='',$p_town=''){
	$sw=array();
	if ($p_id>0) $sw[]="P.pid=$p_id";
	if (strlen($last_name)>0) $sw[]="UPPER(P.plname) LIKE UPPER('$last_name%')";
	if (strlen($first_name)>0) $sw[]="P.pfname LIKE '$first_name%'";
	if (strlen($p_fkey1)>0) $sw[]="(P.pfkey1 LIKE '%$p_fkey1%' OR P.pfkey2 LIKE '%$p_fkey1%')";
	if (strlen($p_town)>0) $sw[]="P.ptown LIKE '%$p_town%'";
	$sWHERE=" WHERE pid>0";
	$args = count($sw);
	for ($c=0; $c < $args; $c++) {
		$sWHERE=$sWHERE." AND ".$sw[$c];
	}
	$qry="SELECT P.pid,P.pactive,P.pfname,P.plname,P.pfkey1,P.pfkey2,P.pplz,P.ptown from tplayer P".$sWHERE." ORDER by P.plname,P.pfname";
	#echo $qry;
	#debug($qry);
	$presult=sql_query($qry,$DB);
	$RET=createRecordSet($presult,$DB);
	return $RET;
}

function DB_getSpecialCharsForUsersFilter($DB) {
    $a=array("A","B","C","D","E","F","G","H","I","J","K","L","M","N","O","P","Q","R","S","T","U","V","W","X","Y","Z");

    $queryBuilder = array();
    $output = array();

    foreach( $a as $letter ) {
        $queryBuilder[] = "plname NOT LIKE UPPER('" . $letter . "%')";
    }

    $query = "SELECT plname FROM tplayer WHERE " . implode( ' AND ', $queryBuilder );
    $presult = sql_query($query, $DB);
    $RET=createRecordSet($presult,$DB);

    if( count( $RET ) > 0 ) {
        foreach( $RET as $record ) {
           if( substr( $record[0], 0, 1 ) == '&' ) {
               $output[] = substr( $record[0], 0, ( strpos( $record[0], ';' ) + 1 ) );
           } else {
               $output[] = substr( $record[0], 0, 1 );
           }
        }
    }

    return array_unique( $output );
}

	/**
	 * controller for players-Eventgroup
	 * returns all players who have ever played in this group
	 * incl. all events active/passive
	 * used: by ranking list generators
	 * returns: pid,pfname,plname
	 */
function DB_listEventStatGroupPlayers($DB,$event_statgroup_id=0){
	$sw=array();
	if ($event_statgroup_id>0) $sw[]="E.evstatcode_id = $event_statgroup_id";
	$sWHERE=" WHERE P.pid=lplayerid";
	$args = count($sw);
	for ($c=0; $c < $args; $c++) {
		$sWHERE=$sWHERE." AND ".$sw[$c];
	}
	$qry="select pid,pfname,plname"
	." FROM tplayer P,tblteamplayer join tblteam T on lteamid=T.id left join tblevent E on leventid=E.id"
	.$sWHERE." ORDER by P.pid,P.plname,P.pfname";
	$presult=sql_query($qry,$DB);
	$RET=createDistinctRecordSet($DB,$presult,0);
	return $RET;
}

/**
*	purpose:	list planned lineup combination of existing players to PLANNED Teams
* 	params:		wfevent_active, playerID
*	returns:	recordset
* 	remark: 	the select clause must return identical fields than DB_listEventTeamPlayers
*/
function DB_listEventWFTeamPlayers($DB,$event_active=1,$p_id=0){
	$sSELECT='SELECT TE.id,TE.typdesc,E.wfevent_id,E.eventname,E.eventyear,T.wfteam_id,T.teamname,P.pid,P.pfname,P.plname,P.pfkey1,P.pfkey2,"" A'
	.' FROM wflineup L join wfteam T on L.wfteam_id=T.wfteam_id join tplayer P on L.player_id=P.pid join wfevent E on T.wfevent_id=E.wfevent_id join ttypeliga TE on E.evgroup_id=TE.id';
	$sWHERE = ' WHERE E.evactive='.$event_active.' AND P.pid='.$p_id;
	$sORDER = ' ORDER by T.teamname';
	#debug($sSELECT.$sWHERE.$sORDER);
	$presult=sql_query($sSELECT.$sWHERE.$sORDER,$DB);
	$RET=createRecordSet($presult,$DB);
	return $RET;
}

/**
*	purpose:	list planned lineups combination of existing players to existing Teams
* 	params:		wfevent_active, playerID
*	returns:	recordset
* 	remark: 	the select clause must return identical fields than DB_listEventTeamPlayers
*/
function DB_listEventTeamWFPlayers($DB,$event_active=1,$p_id=0){
	$sSELECT='SELECT TE.id,TE.typdesc,E.id,E.evname,E.evyear,T.id,T.tname,P.pid,P.pfname,P.plname,P.pfkey1,P.pfkey2,"" A'
	.' FROM wflineup L join tblteam T on L.team_id=T.id join tplayer P on L.player_id=P.pid join tblevent E on T.tevent_id=E.id join ttypeliga TE on E.evtypecode_id=TE.id';
	$sWHERE = ' WHERE E.evactive='.$event_active.' AND P.pid='.$p_id;
	$sORDER = ' ORDER by T.tname';
	#debug($sSELECT.$sWHERE.$sORDER);
	$presult=sql_query($sSELECT.$sWHERE.$sORDER,$DB);
	$RET=createRecordSet($presult,$DB);
	return $RET;
}

	/**
	 * v4 list query to return Group+Event+teams+playerdetails
	 * id index: eventgroup = 0, event = 2, team = 5, player = 7
	 */
function DB_listEventTeamPlayers($DB,$event_typ_IN_id='',$event_stat_code=0,$event_type_name="",$event_id=0,$event_name="",$eventactive=1,$verein_id=0,$pORDER='team',$LigaStichtag='2000-01-01',$p_id=0){
	$sw=array();
	if ($event_stat_code>0) $sw[]='E.evstatcode_id = '.$event_stat_code;
	if (strlen($event_typ_IN_id)>0) $sw[]="TE.id IN ($event_typ_IN_id)";
	if ($event_id>0) $sw[]='E.id = '.$event_id;
	if ($p_id>0) $sw[]='P.pid = '.$p_id;
	if (strlen($eventactive)>0) $sw[]='E.evactive='.$eventactive;
	if (strlen($event_name)>0) $sw[]="E.evname like \"%$event_name%\"";
	if (strlen($event_type_name)>0) $sw[]="TE.typdesc like \"%$event_type_name%\"";
	if (strlen($event_name)>0) $sw[]="E.evname like \"%$event_name%\"";
	if ($verein_id>0) $sw[]='T.tverein_id = '.$verein_id;
	
	$sWHERE = ' WHERE TE.id=E.evtypecode_id';
	if ($pORDER=='team'){
		$sORDER = ' ORDER BY TE.id,E.id,T.id,P.PLNAME';
	} elseif($pORDER=='player'){
		$sORDER = ' ORDER BY P.pid';
	}
	$args = count($sw);
	for ($c=0; $c < $args; $c++) {
		$sWHERE=$sWHERE.' AND '.$sw[$c];
	}
	#$FIELDS=array(1,3,4,6,8,9,10,11,12);
	$qry="select TE.id,TE.typdesc,E.id,E.evname,E.evyear,T.id,T.tname,P.pid,P.pfname,P.plname,P.pfkey1,P.pfkey2,TO_DAYS(pbirthdate)-TO_DAYS(DATE_SUB(\"$LigaStichtag\", INTERVAL 18 YEAR)) A"
			." from ttypeliga TE,tblevent E left join tblteam T on E.id=T.tevent_id left join tblteamplayer on T.id=lteamid left join tplayer P on lplayerid=P.pid".$sWHERE.$sORDER;
	#debug($qry);
	#echo $qry;
	$presult=sql_query($qry,$DB);
	$RET=createRecordSet($presult,$DB);
	return $RET;
}

/**
*	purpose	returns the event a specific player is in from this event_group
* 						abbreviated version of func above, returns smaller set.
*	params		event_group, player
*	returns		event_id
*/
function DB_getEventForPlayer($DB,$pid,$event_typecode_id){
	$ret=0;
	$qry='SELECT leventid from tblteamplayer TP,tblevent E where TP.leventid=E.id AND TP.lplayerid='.$pid.' AND E.evtypecode_id='.$event_typecode_id. ' AND E.evactive=1';
	$presult=sql_query($qry,$DB);
	$aRET=createRecordSet($presult,$DB);
	#debug($aRET);
	$ret=$aRET[0][0];
	return $ret;
}

	/** 
	 * @DB_listLegsFromPeriod
	 * purpose:	returns all legs from all games of all events belonging to
	 * 					a specific stat_group / event_group or single event within a specified period
	 * param:		depending on the statcode we return different stuff from different tables
	 * 				if no date is given -> RETURN everything from this stat_code
	 * 				if eventid is given then return from this event/statcode+date constraints only
	 */
function DB_listLegsFromPeriod($DB,$event_id=0,$event_group_id=0,$event_statcode_id=0,$date_start='',$date_end='',$player_id=0){
	$sw=array();
	if ($event_id>0){
		$sw[]="E.id = $event_id";
	}elseif ($event_group_id>0) {
		$sw[]="E.evtypecode_id = $event_group_id";
	} elseif ($event_statcode_id>0) {
		$sw[]="E.evstatcode_id = $event_statcode_id";
	}
	if ($player_id>0) $sw[]="lpid = $player_id";
	if (strlen($date_start)<8) {
		$sw[]="mdate>'2005-01-01'";
	}elseif (strlen($date_start)<14) {
		$sw[]="mdate>'$date_start'";
	}
	if (strlen($date_end)<8) {
		$sw[]="mdate<'2999-01-01'";
	} elseif (strlen($date_end)<14) {
		$sw[]="mdate<'$date_end'";
	}
	$sWHERE=" WHERE LR.lgid=G.gid and G.gmkey=M.mkey and M.mevid=E.id and LR.lroundscore>0";
	$args = count($sw);
	for ($c=0; $c < $args; $c++) {
		$sWHERE=$sWHERE." AND ".$sw[$c];
	}
	// this is for FEDA stuff only -> check on 3,5 vs default ...
	// fields (0,1,2,3,4,5,6,7) => 7,6,4,0,1,2,3
	$qry="select LR.lid,LR.lroundscore,LR.lscore,LR.lroundcheck,G.gid,M.mid,M.mround,M.mdate"
	." FROM tbllegrounds LR,tblgame G,tblmatch M,tblevent E".$sWHERE." ORDER by M.mdate,G.gid asc,lid asc";
	#echo $qry;
	#debug($qry);
	$presult=sql_query($qry,$DB);
	$RET=createRecordSet($presult,$DB);
	return $RET;
}

/**
*	purpose	return limit players with the most maxLegs per Game Singles
*	params	
*	returns		recordset
*/
function DB_listEventTopWinners($DB,$event_id,$limit=10,$pgender='%'){
	# make sure the weird pseudo players are not listed ...
	$qry='SELECT P.pfname,P.plname,count(GP.gplegswon) C'
	.' FROM tblgame G, tblgameplayer GP ,tplayer P'
	.' WHERE G.gid=GP.gpgid and GP.gppid=P.pid and P.pid<>99 AND P.pgender like "'.$pgender.'" and G.gtype=1 and gmkey like "e'.$event_id.'r%" and ceil(G.glegs/2)=GP.gplegswon group by GP.gppid order by C desc limit '.$limit;
	$presult=sql_query($qry,$DB);
	$RET=createRecordSet($presult,$DB);
	return $RET;
}

/**
*	purpose:	List Locations either geographically or logically based on EventSelection ...
* 	params:		plz , BL, event, eventgroup
*	returns:	Recordset
*/
function DB_listLocations($DB,$loc_id=0,$loc_name='',$loc_active=1,$loc_plz='',$event_id=0,$event_group_id=0){
	$sw=array();
	if ($loc_id>0) $sw[]="L.id=$loc_id";
	if (strlen($loc_name)>0) $sw[]="L.lname LIKE '%$loc_name%'";
	if (strlen($loc_plz)>0) $sw[]="L.lplz LIKE '$loc_plz%'";
	if ($loc_active>0) $sw[]="L.lactive=$loc_active";
	if ($event_id>0) $sw[]="E.id=$event_id";
	if ($event_group_id>0) $sw[]="E.evtypecode_id=$event_group_id";
	// check if we have to go on 3 tables instead of 1 only ...
	if ($event_id+$event_group_id>0) {
		$sSELECT="SELECT L.id,L.lname,L.lcity,L.lplz,L.laddress,L.lphone,L.lactive,L.lcoordinates,E.id,E.evname,T.id,T.tname";
		$sFROM="FROM tblevent E,tblteam T,tbllocation L";
		$sWhere="WHERE E.id=T.tevent_id AND T.tlocation_id=L.id";
	} else {
		$sSELECT="SELECT L.id,L.lname,L.lcity,L.lplz,L.laddress,L.lphone,L.lactive,L.lcoordinates";
		$sFROM="FROM tbllocation L";
		$sWhere="WHERE L.id>0";
	}
	$args = count($sw);
	for ($c=0; $c < $args; $c++) {
		$sWhere=$sWhere." AND ".$sw[$c];
	}
	$QRY=$sSELECT." ".$sFROM." ".$sWhere." ORDER BY lplz,lname asc";
	#debug($QRY);
	$presult = sql_query($QRY,$DB);
	$RET=createRecordSet($presult,$DB);
	return $RET;
}

	/*
	 * returns single named RS array containing the captains DATA of ACTIVE leagueteams
	 * basically the same can be achieved with the DB_listLineUp but we need extended contact data here ...
	 * v3 9.2007
	 * v4 05.2008
	 */ 
function DB_getCaptainDataTeam($DB,$team_id=0,$event_id=0,$event_group_id=0){
	if ($event_id>0){
		$strFROM=' FROM tblevent e LEFT JOIN tblteam t ON e.id=t.tevent_id';$strWHERE='e.id='.$event_id.' AND e.evactive=1';}
	if ($event_group_id>0){
		$strFROM=' FROM tblevent e LEFT JOIN tblteam t ON e.id=t.tevent_id';$strWHERE='e.evtypecode_id='.$event_group_id.' AND e.evactive=1';}
	if ($team_id>0){
		$strFROM=' FROM tblteam t';$strWHERE=' WHERE tp.lteamid='.$team_id;}
	
	$strsql=' select t.tevent_id,t.id,t.tname,p.pfname,p.plname,p.ptel1,p.ptel2'
		.$strFROM.' LEFT JOIN tblteamplayer tp ON t.id=tp.lteamid LEFT JOIN tplayer p ON tp.lplayerid=p.pid'
		.$strWHERE.' AND tp.ltype=2';
		
	$presult=sql_query($strsql,$DB);
	$RS=sql_fetch_array($presult,$DB);
	return $RS;
}

	/**
	 * returns: sorted recordset from tblstat containing
	 * statval,countgames,countlegs,player_id,pfname,plname,Teamname,Eventname
	 * v4 DB layer, BH 9.2007
	 * 
	 */ 
function DB_retTStatArray($statcode=0,$statdate='',$p_id=0){
	global $dbi;
	if ($statcode>0) $sw[]="E.evstatcode_id=$statcode and statcode=$statcode";
	if (strlen($statdate)>0) $sw[]="statdate='$statdate'";
	if ($p_id>0) $sw[]="P.pid=$p_id";
	$sWhere=" WHERE lteamid=T.id and T.tevent_id=E.id and statpid=P.pid";
	$args = count($sw);
	for ($c=0; $c < $args; $c++) {
		$sWhere=$sWhere." AND ".$sw[$c];
	}
	$qry="select statid,statval,statgames,statlegs,P.pid,P.pfname,P.plname,T.tname,E.evname,E.evyear"
	." FROM tblstat,tplayer P left join tblteamplayer TP on P.pid=TP.lplayerid,tblteam T,tblevent E"
	.$sWhere." ORDER by statdate desc,statval desc,P.pid asc,T.id desc";
	$prec = sql_query($qry,$dbi);
	#debug($qry);
	# echo $qry;
	# // we receive entries for every team the player has been in ... check on PID ...order is by VALUE-PID-TEAMID
	# // display the highest entry -> actual or last Team and League
	$lastpid=0;
	$OUT=array();
	while($a=sql_fetch_row($prec,$dbi)){
		if ($lastpid<>$a[4]){
			$OUT[]=$a;
		}
		$lastpid=$a[4];
	}
	return $OUT;
}

/**
*	purpose:	list static values for a player
* 	params:		player,statcode, datecomp
*	returns:	recordset
*/
function DB_listStaticStatValues($DB,$statcode=0,$statdate='',$p_id=0,$limit=0){
	$STRLIMIT="";
	if ($statcode>0) $sw[]="S.statcode=$statcode";
	if (strlen($statdate)>0) $sw[]="S.statdate='$statdate'";
	if ($p_id>0) $sw[]="P.pid=$p_id";
	if ($limit>0) $STRLIMIT=" LIMIT $limit";
	$sWhere=" WHERE S.statpid=P.pid";
	$args = count($sw);
	for ($c=0; $c < $args; $c++) {
		$sWhere=$sWhere." AND ".$sw[$c];
	}
	$qry="select S.statid,S.statdate,S.statval,S.statgames,S.statlegs,P.pid,P.pfname,P.plname"
	." FROM tblstat S,tplayer P"
	.$sWhere." ORDER by S.statdate desc,S.statval desc,P.pid $STRLIMIT";
	$presult = sql_query($qry,$DB);
	$RET=createRecordSet($presult,$DB);
	return $RET;
}

/**
	 *	returns: sorted recordset containing specific statistic values
	 * 				group_flag = 1 returns the ranking applied to the entire league group
	 * 				stat_flag = 1 returns the ranking applied to the entire statistics group
	 * 	 * v4 DB Layer, BH 9.2007
	 */
function DB_retStatQueryArray($DB,$strgender,$event_class,$event_active=1,$group_flag=0,$stat_flag=0){	
	if ($stat_flag==1) {
		$strE='and E.evstatcode_id='.$event_class['evstatcode_id'];
	}else {
		if ($group_flag==1){
			$strE='and E.evtypecode_id='.$event_class['evtypecode_id'];
		}else{
			$strE='and E.id='.$event_class['id'];
		}
	}
	if (strlen($strgender)>0){$strE=$strE." AND P.pgender like UPPER(\"$strgender\")";}
	
	$QRY="SELECT P.pid,P.pfname,P.plname,sum(GP.gplegswon) LEGS,sum(GP.gpsetwon) SETS, count(GP.gpsetwon) GAMES,sum(GP.gplegswon)/count(GP.gplegswon) QUOTE"
		." FROM tblgameplayer GP,tblgame G,tblmatch M,tblevent E,tplayer P"
		." WHERE GP.gpgid=G.gid and GP.gppid=P.pid and G.gmkey=M.mkey and M.mevid=E.id and gtype=1 and E.evactive=$event_active ".$strE." and P.pid<>99 "
		." GROUP BY P.pid ORDER BY SETS desc, GAMES desc,P.plname";

	#debug($QRY);
	$presult = sql_query($QRY,$DB);
	$RET=createRecordSet($presult,$DB);
	return $RET;
}

/**
*	purpose:	list detailed records for the statlist of an event, no LEGDATA
* 						queries the game-legdata + inserts points for every game according to event_stat
*	params:	pid = mandatory, event_class = event[array]
*	returns:	Recordset
*/
function DB_retStatQueryArrayDetail($DB,$pid,$event_class,$event_active=1,$group_flag=0,$stat_flag=0){	
	if ($pid<1) return;
	if ($stat_flag==1) {
		$strE='and E.evstatcode_id='.$event_class['evstatcode_id'];
	}else {
		if ($group_flag==1){
			$strE='and E.evtypecode_id='.$event_class['evtypecode_id'];
		}else{
			$strE='and E.id='.$event_class['id'];
		}
	}
	
	$QRY="SELECT P.pid,G.gid,P.pfname,P.plname,M.mkey,M.mround,M.mdate,GP.gplegswon LEGS,GP.gpsetwon SETS, GP.gpsetwon GAMES"
			." from tblgameplayer GP,tblgame G,tblmatch M,tblevent E,tplayer P"
			." where GP.gpgid=G.gid and GP.gppid=P.pid and G.gmkey=M.mkey and M.mevid=E.id and gtype=1 and E.evactive=$event_active ".$strE." and P.pid=$pid ORDER BY M.mround";
	$presult = sql_query($QRY,$DB);
	$RET=createRecordSet($presult,$DB);
	return $RET;
}

	/**
	 * used to update the table of match - team assignments
	 * update SET / LEG / POINT values according to event.config
	 * used by saveMatch, submitMatch
	 * returns number of successfull updates - usually 2
	 */
function DB_UpdateMatchTeamResults($vmkey,$eventid,$vtid,$vsets,$vlegs){
	global $dbi,$event;
	// get points
	switch(TRUE){
		case $vsets[0]< $vsets[1]:
			$mtpoints[0]=0;
			$mtpoints[1]=$event['evpointswin'];
			break;
		case $vsets[0]==$vsets[1]:
			$mtpoints[0]=$event['evpointseven'];
			$mtpoints[1]=$event['evpointseven'];
			break;
		case $vsets[0]> $vsets[1]:
			$mtpoints[0]=$event['evpointswin'];
			$mtpoints[1]=0;
			break;
	}
	$SQL="update tblmatchteam set mtlegs=$vlegs[0],mtlegslost=$vlegs[1],mtsets=$vsets[0],mtsetslost=$vsets[1], mtpoints=$mtpoints[0] where mttid=$vtid[0] and mtmkey='$vmkey' limit 1";
	$presult=sql_query($SQL,$dbi);
	if ($presult==1) $success=1;
	//echo "$sql0";
	$SQL="update tblmatchteam set mtlegs=$vlegs[1],mtlegslost=$vlegs[0],mtsets=$vsets[1],mtsetslost=$vsets[0], mtpoints=$mtpoints[1] where mttid=$vtid[1] and mtmkey='$vmkey' limit 1";
	//echo "<br>$sql1";
	$presult=sql_query($SQL,$dbi);
	if ($presult==1) $success=$success+1;
	
	return $success;
}

	/**
	 * simply set the status to a specific status code
	 */
function DB_setMatchStatus($vmkey,$status){
	global $dbi,$event;
	$SQL="update tblmatch set mstatus=$status where mkey='$vmkey' limit 1";
	$presult=sql_query($SQL,$dbi);
	return $presult;
}

	/**
	 * straight hack to send SQL to the message table
	 * TODO v5 --> this should go to a message controller
	 */
function DB_setMessage($cre_user='',$modul=0,$msg_status=1,$msg='',$msgurl='',$mail_group=0,$recipient=''){
	global $dbi;

	$d = getdate();
	$cre_date = $d['year'].'-'.$d['mon'].'-'.$d['mday'];

	$qry="INSERT into tmessage (id,version,cre_date,cre_user,mstatus_id,modultype_id,msgbody,msgurl,mgroup_id,recipient)"
	." VALUES(0,0,'$cre_date','$cre_user',$msg_status,$modul,'$msg','$msgurl',$mail_group,'$recipient')";
	$presult=sql_query($qry,$dbi);
	#debug($qry);
	return $presult;
}

/**
*	purpose	update the message record, set status and recipient
*	params		
*	returns		qry result
*/
function DB_setMessageStatus($mid=0,$cre_date='',$cre_user='',$msg_status=2,$recipient_list=''){
	global $dbi;
	if (strlen($recipient_list)>0) $fields=',recipient=\''.$recipient_list.'\'';
	if (strlen($cre_date)>0) $fields=$fields.',cre_date=\''.$cre_date.'\'';
	if ($mid<>0){
		$qry='UPDATE tmessage set mstatus_id='.$msg_status.$fields.' WHERE id='.$mid.' limit 1';
	}
	#debug($qry);
	$presult=sql_query($qry,$dbi);
	return $presult;
}

	/**
	 * purpose:	returns the finish statistics stored in the finish fields of single games
	 * returns:	finishnumber/count
	 * params:	event, player
	 */
function DB_listFinishStat($DB,$player_id=0,$event_id=0){
	if ($event_id>0) $sw[]="G.gmkey like 'e".$event_id."r%'";
	if ($player_id>0) $sw[]="P.pid=$player_id";
	$sWhere=" WHERE L.lpid=P.pid and L.lgid=G.gid and G.gtype=1 and L.lfinish>0";
	$args = count($sw);
	for ($c=0; $c < $args; $c++) {
		$sWhere=$sWhere." AND ".$sw[$c];
	}
	$qry="select lfinish,count(lfinish) CNT "
		." FROM tblleg L,tblgame G,tplayer P ".$sWhere
		." GROUP by L.lfinish order by L.lfinish asc";
	#debug($qry);
	$presult=sql_query($qry,$DB);
	$RET=createRecordSet($presult,$DB);
	return $RET;
}
	/**
	 * returns a mimicked round average for darts based statistics of an event by interpolating 3-darts
	 * works on singles only stored in tblleg
	 */
function DB_listRoundAverageStatForDartsLeg($DB,$player_id=0,$event_id=0,$score_comp=">0",$leg_dist=501){
	if ($event_id>0) $sw[]="G.gmkey like 'e".$event_id."r%'";
	if ($player_id>0) $sw[]="P.pid = $player_id";
	if (strlen($score_comp)>0) $sw[]="lscore".$score_comp;
	$sWhere=" WHERE L.lpid=P.pid and L.lgid=G.gid and L.ldarts>1 and G.gtype=1";
	$args = count($sw);
	for ($c=0; $c < $args; $c++) {
		$sWhere=$sWhere." AND ".$sw[$c];
	}
	$qry="select P.pid,P.pfname,P.plname,count(L.lscore) LEGS,round($leg_dist-avg(lscore),2) REST,round(avg(ceil(L.ldarts/3)),2) AVG,round(stddev(ceil(L.ldarts/3)),2) DEV,round(AVG(lscore/ceil(ldarts/3)),2) PERF"
		." from tblleg L,tblgame G,tplayer P".$sWhere
		." group by L.lpid order by AVG asc";
	#debug($qry);
	$presult=sql_query($qry,$DB);
	$RET=createRecordSet($presult,$DB);
	return $RET;		
}

/**
*	purpose:	get darts-based averages for event / eventgroup
* 	params:		
*	returns:	recordset pid,pfname,plname,sumdarts,sumscore,avg,stddev
*/
function DB_listDartStatAverage($DB,$event_group_id=0,$event_id=0,$score_comp=">0",$player_id=0){
	if ($event_group_id>0) {
		$sw[]="E.evtypecode_id = $event_group_id and E.evactive=1";
	}elseif ($event_id>0) {
		$sw[]="E.id = $event_id";
	}
	if ($player_id>0) $sw[]="P.pid = $player_id";
	if (!($event_group_id+$event_id)>0) return;
	if (strlen($score_comp)>0) $sw[]="L.lscore".$score_comp;
	$sWhere=" WHERE L.lpid=P.pid and L.lgid=G.gid and G.gmkey=M.mkey and M.mevid=E.id and L.ldarts>1 and G.gtype=1";
	$args = count($sw);
	for ($c=0; $c < $args; $c++) {
		$sWhere=$sWhere." AND ".$sw[$c];
	}
	$sOrder=" ORDER by AVG desc";
	$qry="select P.pid,P.pfname,P.plname,sum(L.ldarts) DARTS,sum(L.lscore) SCORE,sum(L.lscore)/sum(L.ldarts) AVG,round(stddev(L.lscore/L.ldarts),2) DEV"
		." FROM tblleg L,tplayer P,tblgame G,tblmatch M,tblevent E".$sWhere." GROUP by L.lpid".$sOrder;
	#debug($qry);
	$presult=sql_query($qry,$DB);
	$RET=createRecordSet($presult,$DB);
	return $RET;
}

	/**
	 * purpose:	get averages from Database darts-legs, group precedes over event
	 * returns:	1 row per player only grouped on PID
	 * params:	score_comp, event, eventgroup,player
	 * search:	event_group precedes event
	 * event.active only needed on group queries ...
	 */
function DB_listLegStatAverage($DB,$event_group_id=0,$event_id=0,$score_comp=">0",$player_id=0){
	if ($event_group_id>0) {
		$sw[]="E.evtypecode_id = $event_group_id and E.evactive=1";
	}elseif ($event_id>0) {
		$sw[]="E.id = $event_id";
	}
	if ($player_id>0) $sw[]="P.pid = $player_id";
	if (!($event_group_id+$event_id)>0) return;
	if (strlen($score_comp)>0) $sw[]="lscore".$score_comp;
	
	$sWhere=" where L.lpid=P.pid and L.lgid=G.gid and G.gmkey=M.mkey and M.mevid=E.id and L.ldarts>1 and G.gtype=1";
	$args = count($sw);
	for ($c=0; $c < $args; $c++) {
		$sWhere=$sWhere." AND ".$sw[$c];
	}
	$sOrder=" ORDER by AVG desc";
	$qry="select P.pid,P.pfname,P.plname,count(L.lscore) LEGS,round(avg(L.lscore/L.ldarts),2) AVG,round(stddev(L.lscore/L.ldarts),2) DEV"
	." from tblleg L,tblgame G,tblmatch M,tblevent E,tplayer P".$sWhere." group by L.lpid".$sOrder;

	#debug($qry);
	$presult=sql_query($qry,$DB);
	$RET=createRecordSet($presult,$DB);
	return $RET;
}

/**
 * purpose:	returns the average/stddev of the scoreround/checkround value per player, either for event/eventgroup
 * param:	group/event -- won / lost / all -- check / score
 * returns:	recordset with AVG(score)+STDEV,CNT,AVG(check)+STDEV
 * RS:		pid,pfname,plname,3=avg(),stddev(),count()
 */

function DB_listRoundAverage($DB,$event_group_id=0,$event_id=0,$score_comp='>0',$player_id=0,$mode='score'){
	
	$sw=array();
	if (!($event_group_id+$event_id)>0) return;
	
	if ($event_group_id>0) {$sw[]='E.evtypecode_id = '.$event_group_id;}
	if ($event_id>0) {$sw[]='E.id = '.$event_id;}
	if ($player_id>0) {$sw[]='P.pid = '.$player_id;}
	if (strlen($score_comp)>0) {$sw[]='lscore'.$score_comp;}
	if ($mode=='score'){
		$sORDER='ORDER by AVGS asc';
		$FIELDS=',avg(L.lroundscore) AVGS, stddev(L.lroundscore) DEVS, count(L.lroundscore)';
	} elseif($mode=='check') {
		$sORDER='ORDER by AVGC asc';
		$FIELDS=',avg(L.lroundcheck-L.lroundscore) AVGC, stddev(L.lroundcheck-L.lroundscore) DEVC, count(L.lroundcheck)';
	}
	$sSELECT='SELECT P.pid,P.pfname,P.plname'.$FIELDS;
	
	$sFROM='FROM tbllegrounds L,tblgame G,tblmatch M,tblevent E,tplayer P';
	$sWhere='WHERE L.lpid=P.pid and L.lgid=G.gid and G.gmkey=M.mkey and M.mevid=E.id and G.gtype=1 and L.lroundscore>1';
	$args = count($sw);
	for ($c=0; $c < $args; $c++) {
		$sWhere=$sWhere." AND ".$sw[$c];
	}
	
	$qry=$sSELECT.' '.$sFROM.' '.$sWhere.' GROUP by L.lpid '.$sORDER;
	#debug($qry);
	$presult=sql_query($qry,$DB);
	$RET=createRecordSet($presult,$DB);
	return $RET;
}


/**
*	purpose:	List messages stored 
* 	params:		cre_user: by standard this is the LSDB@dartsverband.at account ...
*	returns:	recordset
*/	
function DB_listMessage($DB,$modul_type='',$recipient='',$msg_status='',$cre_user='',$mail_group=0){
	$sw=array();
	if (strlen($modul_type)>0) $sw[]='M.modultype_id='.$modul_type;
	if (strlen($msg_status)>0) $sw[]='M.mstatus_id='.$msg_status;
	if (strlen($recipient)>7) $sw[]='M.recipient like \'%'.$recipient.'%\'';
	if (strlen($cre_user)>0) $sw[]='M.cre_user='.$cre_user;
	if (!$mail_group==0) $sw[]='M.mgroup_id='.$mail_group;
	$sWhere=' WHERE M.ID>0';
	$args = count($sw);
	for ($c=0; $c < $args; $c++) {
		$sWhere=$sWhere.' AND '.$sw[$c];
	}
	$qry='SELECT M.id,concat(S.msgstatus," by:"),M.cre_user,M.cre_date,M.mgroup_id,MG.mgroupname,M.recipient,'
	.'concat("<a href=",M.msgurl,">CLICK</a>") L,M.msgbody'
	.' FROM tmessage M LEFT join tmessagestatus S ON  M.mstatus_id=S.mstatus_id LEFT JOIN tmessagegroup MG ON M.mgroup_id=MG.mgroup_id'
		.$sWhere.' ORDER by cre_date desc';
	#debug($qry);
	$presult=sql_query($qry,$DB);
	$RET=createRecordSet($presult,$DB);
	return $RET;
}

	/**
	 * returns	detailed average breakdown for 1 player
	 * 			get averages from Database darts-legs, 
	 * 			if the event_group is passed then only active events are parsed
	 * search:	event_group precedes event
	 * params:	score_comp, event,player,$hist=1 returns histogram on darts instead of breakdown
	 */
function DB_listLegStatAverageBreakdown($DB,$event_group_id=0,$event_id=0,$score_comp=">0",$leg_dist=501,$player_id=0,$hist=''){
	
	if ($event_group_id>0) {
		$sw[]='E.evtypecode_id = '.$event_group_id.' and E.evactive=1';
	}elseif ($event_id>0) {
		$sw[]='E.id = '.$event_id;
	}
	if ($player_id>0) $sw[]='P.pid = '.$player_id;
	if (strlen($score_comp)>0) $sw[]='lscore'.$score_comp;
	
	$sWhere=' WHERE L.lpid=P.pid and L.lgid=G.gid and G.gmkey=M.mkey and M.mevid=E.id and L.ldarts>1 and G.gtype=1';
	$args = count($sw);
	for ($c=0; $c < $args; $c++) {
		$sWhere=$sWhere.' AND '.$sw[$c];
	}
	if (strlen($hist)==0){
		$sOrder=' ORDER by M.mdate,G.gid,L.lid';
		$sGROUP='';
		$sSELECT="SELECT E.evname,P.pid,P.pfname,P.plname,M.mdate,L.ldarts,L.lfinish,($leg_dist-L.lscore),round((L.lscore/L.ldarts),2) AVG";
	} elseif ($hist=='dart'){
		$sGROUP=" GROUP by L.ldarts";
		$sOrder=" ORDER by L.ldarts asc";
		$sSELECT="SELECT L.ldarts,count(L.ldarts)";
	}elseif ($hist=='finish'){
		$sGROUP=" GROUP by L.lfinish";
		$sOrder=" ORDER by L.lfinish asc";
		$sSELECT="SELECT L.lfinish,count(L.lfinish)";
	}
	$qry=$sSELECT." FROM tblleg L,tblgame G,tblmatch M,tblevent E,tplayer P".$sWhere.$sGROUP.$sOrder;
	#echo $qry;
	#debug($qry);
	$presult=sql_query($qry,$DB);
	$RET=createRecordSet($presult,$DB);
	return $RET;
}

/**
*	purpose:	returns a won-leg breakdown as histogram of needed darts
* 	params:		event_type,event_id,pid
*	returns:	histogram array of x,yval, default is (0,0) to prevent ajax blocking
*/
function DB_listLegStatDartsHistogramTeam($DB,$event_group_id=0,$event_stat_code=0,$event_id=0,$score_comp="=501",$leg_dist=501,$team_id=0){
	if ($event_group_id>0) {
		$sw[]='E.evtypecode_id = '.$event_group_id.' and E.evactive=1';
	}elseif ($event_id>0) {
		$sw[]="E.id = $event_id";
	}
	if ($team_id>0) $sw[]="TP.lteamid=$team_id and E.id=TP.leventid";
	if (strlen($score_comp)>0) $sw[]="L.lscore".$score_comp;
	
	if (($event_stat_code==3)||($event_stat_code==5)){
		$sWhere=" WHERE L.lpid=TP.lplayerid and G.gtype=1";
		$sGROUP=" GROUP by L.lroundcheck";
		$sOrder=" ORDER by L.lroundcheck asc";
		$sSELECT="SELECT L.lroundcheck,count(L.lroundcheck)";
		$tab="tbllegrounds";
	} else {
		$sWhere=" WHERE L.lpid=TP.lplayerid and L.ldarts>1 and G.gtype=1";
		$sGROUP=" GROUP by L.ldarts";
		$sOrder=" ORDER by L.ldarts asc";
		$sSELECT="SELECT L.ldarts,count(L.ldarts)";
		$tab="tblleg";
	}
	$args = count($sw);
	for ($c=0; $c < $args; $c++) {
		$sWhere=$sWhere." AND ".$sw[$c];
	}
	$qry=$sSELECT." FROM tblevent E left join tblmatch M on E.id=M.mevid left join tblgame G on M.mkey=G.gmkey left join $tab L on G.gid=L.lgid left join tplayer P on L.lpid=P.pid,tblteamplayer TP".$sWhere.$sGROUP.$sOrder;
	
	#echo $qry;
	#debug($qry);
	#$RET=array(0,0);
	$presult=sql_query($qry,$DB);
	$RET=createRecordSet($presult,$DB);
	return $RET;
}
	/**
	 * helper for the game edit pages, we need to retrieve the teams based on a game ID
	 * needed to select the correct players...
	 * returns: 
	 */
function DB_listGameTeams($DB,$game_id){
	$qry="SELECT mid,mkey,mdate,mtid,mthome,T.id,T.tname,gid,gtype,gstatus from tblmatch,tblmatchteam,tblgame,tblteam T"
	." WHERE mkey=mtmkey and mkey=gmkey and mttid=T.id and gid=$game_id ORDER by mthome desc";
	#debug($qry);
	$presult=sql_query($qry,$DB);
	$RET=createRecordSet($presult,$DB);
	return $RET;
}

	/**
	 * query eNgine for simplegame records ...
	 * params:	matchkey, eventid, game_type	
	 * returns:	gid,gmkey,gtype,gstatus
	 */
function DB_listGames($DB,$event_id=0,$matchkey='',$game_type='single'){
	if ($event_id>0) $sw[]="M.mevid=$event_id";
	if (strlen($matchkey)>0) $sw[]="G.gmkey='$matchkey'";
	if ($game_type=='single'){
		$sw[]="gtype=1";
	}else{
		$sw[]="gtype=2";
	}
	$sWhere=" WHERE M.mkey=G.gmkey";
	$args = count($sw);
	for ($c=0; $c < $args; $c++) {
		$sWhere=$sWhere." AND ".$sw[$c];
	}
	$qry="select M.mid,M.mevid,G.gid,G.gmkey,G.gtype,G.gstatus"
				." FROM tblmatch M,tblgame G".$sWhere." ORDER by G.gid";
	#debug($qry);
	$presult=sql_query($qry,$DB);
	$RET=createRecordSet($presult,$DB);
	return $RET;
}

	/**
	 * returns a full game record including all associated leg data and player data (0,1,2,3,4,5,6,7,8,9,10,11,12,13,[14])
	 * returns small set if no legdata present ... (0,1,2,3,4,5,6,7)
	 * used by the ax snippet
	 * depending on the event we use different tables ...
	 */
function DB_getGame($DB,$game_id,$mod_dart=0,$mod_rounds=0,$player_id=0,$leg_dist=501){
	if($player_id<>0){$pWHERE=' AND L.lpid='.$player_id;}else{$pWHERE='';}
	if ($mod_dart==1){
		$SELECT=',L.lid,L.lstart,L.ldarts,(501-L.lscore),L.lfinish,L.lhighscore';
		$JOIN=' left join tblleg L on G.gid=L.lgid';
		$sWhere=' WHERE G.gid='.$game_id.' AND L.lpid=P.pid'.$pWHERE;
		$sORDER=' ORDER by G.gid,P.pid,L.lid';
	} elseif($mod_rounds==1) {
		$SELECT=',L.lid,L.lstart,L.lroundscore,L.lroundcheck,(501-L.lscore),L.lfinish,L.lhighscore';
		$JOIN=' left join tbllegrounds L on G.gid=L.lgid';
		$sWhere=' WHERE G.gid='.$game_id.' AND L.lpid=P.pid'.$pWHERE;
		$sORDER=' ORDER by G.gid,P.pid,L.lid';
	} else {
		$SELECT='';$JOIN='';
		$sWhere=' WHERE G.gid='.$game_id;
		$sORDER=' ORDER by G.gid,GP.gpid';
	}
	$qry='SELECT G.gid,G.gtype,GP.gpid,GP.gpsetwon,GP.gplegswon,GP.gptid,P.pid,P.pfname,P.plname'.$SELECT
	.' FROM tblgame G left join tblgameplayer GP on G.gid=GP.gpgid left join tplayer P on GP.gppid=P.pid'.$JOIN
	.$sWhere.$sORDER;
	#echo $qry;
	$presult=sql_query($qry,$DB);
	$RET=createRecordSet($presult,$DB);
	return $RET;
}

/**
*	purpose	retrieve all Game Results for a specific Match / Matches, order by TeamID,PersonID
*	params	
*	returns		recordset
*/
function DB_getMatchGameResults($DB,$matchkey){
	$qry='SELECT GP.gptid,GP.gppid,GP.gpsetwon,GP.gplegswon,G.gid,M.mid,M.mkey'
	.' FROM tblmatch M left join tblgame G on mkey=gmkey left join tblgameplayer GP on gid=gpgid'
	.' WHERE mkey="'.$matchkey.'" order by GP.gptid,G.gid';
	#debug($qry);
	$presult=sql_query($qry,$DB);
	$RET=createRecordSet($presult,$DB);
	return $RET;
}

/**
*	purpose:	return a meaningfull list of all requests from the WF system
* 				take care of identical fields ...
* 	params:	
*	returns:	
*/
function DB_listWFRequest($DB,$req_id=0,$user_id=0,$status=0,$req_type=0,$reqkey='',$adm=0){
	
	$sw=array();
	if ($adm>0 && $status==1) return;	# no need for the admins to see all InCreation
	
	if ($req_id>0) $sw[]="R.wfrequest_id=$req_id";
	if ($user_id>0) $sw[]="R.user_id=$user_id";
	if ($status>0) $sw[]="R.wfstate_id=$status";
	if ($req_type>0) $sw[]="R.wfrequesttype_id=$req_type";
	if (strlen($reqkey)>0) $sw[]="R.reqkey='$reqkey'";
	/*
	 * there are 2 types of requests - team + Lineup  / lineup + Player , we need to access different child tables and pass back
	 * a description string for the WF request object ...
	 * if the user_id is valid than fetch all requests for the USER stage + if the adm>0 then we
	 * have an adm UID incoming - fetch mgroup join and deliver according to group membership....
	 */
	$sWhere=' WHERE R.user_id=U.id';
	$args = count($sw);
	for ($c=0; $c < $args; $c++) {
		$sWhere=$sWhere.' AND '.$sw[$c];
	}
	$sWhereTeam=$sWhere.' AND R.wfrequesttype_id=1 AND R.wfrequest_id=WFT.wfrequest_id';
	if ($adm>0){
		$TLIST=',tmessagegroup MG,tmessagegroupmember MGM';
		$sWhereTeam=$TLIST.$sWhereTeam.' AND R.mgroup_id=MG.mgroup_id AND MG.mgroup_id=MGM.mgroup_id AND MGM.user_id='.$adm;
	} 
	$sWhereLineUp=$sWhere.' AND R.wfrequesttype_id=2 AND R.wfrequest_id=WFL.wfrequest_id AND R.wfrequest_id=WFP.wfrequest_id';
	
	$sWherePlayer=$sWhere.' AND R.wfrequesttype_id=3 AND R.wfrequest_id=WFP.wfrequest_id AND R.wfrequest_id=WFM.wfrequest_id';
	if ($adm>0){
		$TLIST=',tmessagegroup MG,tmessagegroupmember MGM';
		$sWherePlayer=$TLIST.$sWherePlayer.' AND R.mgroup_id=MG.mgroup_id AND MG.mgroup_id=MGM.mgroup_id AND MGM.user_id='.$adm;
	} 
	$sWhereMessage=$sWhere.' AND R.wfrequesttype_id=4 AND R.wfrequest_id=M.wfrequest_id';
	if ($adm>0){
		$TLIST=",tmessagegroup MG,tmessagegroupmember MGM";
		$sWhereMessage=$TLIST.$sWhereMessage.' AND R.mgroup_id=MG.mgroup_id AND MG.mgroup_id=MGM.mgroup_id AND MGM.user_id='.$adm;
	}
	$qry1 ='SELECT R.wfrequest_id,R.reqdate,U.uname,T.description,concat(E.eventname,\': \',WFT.teamname) objdesc,S.statedesc'
	.' FROM wfrequest R left join wfrequesttype T on R.wfrequesttype_id=T.wfrequesttype_id'
	.' left join wfstate S on R.wfstate_id=S.wfstate_id, tuser U,wfteam WFT left join wfevent E on WFT.wfevent_id=E.wfevent_id'.$sWhereTeam
	.' ORDER by R.reqdate desc';
	#debug($qry1);
	$qry2='SELECT DISTINCT R.wfrequest_id,R.reqdate,U.uname,T.description,concat(TT.tname,\': \',WFP.pfname,\' \',WFP.plname) objdesc,S.statedesc'
	.' FROM wfrequest R left join wfrequesttype T on R.wfrequesttype_id=T.wfrequesttype_id'
	.' left join wfstate S on R.wfstate_id=S.wfstate_id, tuser U,wfplayer WFP,wflineup WFL left join tblteam TT on WFL.team_id=TT.id'.$sWhereLineUp
	.' ORDER by R.reqdate desc';
	#debug($qry2);
	$qry3 ='SELECT R.wfrequest_id,R.reqdate,U.uname,T.description,concat(TM.memberdesc,\': \',WFP.pfname,\' \',WFP.plname) objdesc,S.statedesc'
	.' FROM wfrequest R left join wfrequesttype T on R.wfrequesttype_id=T.wfrequesttype_id'
	.' left join wfstate S on R.wfstate_id=S.wfstate_id, tuser U,wfplayer WFP,wfmembership WFM left join ttypemember TM on WFM.ttypemember_id=TM.id'.$sWherePlayer
	.' ORDER by R.reqdate desc';
	#debug($qry3);
	$qry4='SELECT R.wfrequest_id,R.reqdate,U.uname,T.description,left(M.wfcomment,40) objdesc,S.statedesc'
	.' FROM wfrequest R left join wfrequesttype T on R.wfrequesttype_id=T.wfrequesttype_id'
	.' left join wfstate S on R.wfstate_id=S.wfstate_id, tuser U,wfmessage M'.$sWhereMessage
	.' ORDER by R.reqdate desc';
	#debug($qry4);
	$presult1=sql_query($qry1,$DB);$presult2=sql_query($qry2,$DB);$presult3=sql_query($qry3,$DB);$presult4=sql_query($qry4,$DB);
	$RS1=createRecordSet($presult1,$DB);$RS2=createRecordSet($presult2,$DB);$RS3=createRecordSet($presult3,$DB);$RS4=createRecordSet($presult4,$DB);
	$c=array();
	if (sizeof($RS1)>0)	$c=$RS1;
	if (sizeof($RS2)>0) $c=array_merge($c,$RS2);
	if (sizeof($RS3)>0) $c=array_merge($c,$RS3);
	if (sizeof($RS4)>0) $c=array_merge($c,$RS4);
	return $c;
}

/**
*	purpose:	list request history 
* 	params:		request id
*	returns:	Recordset
*/
function DB_listWFRequestHistory($DB,$req_id){
	$qry='select H.wfrequesthistory_id,H.wfrequest_id,H.wfstatechange,U.uname,S.statedesc,H.message'
	.' FROM wfrequesthistory H left join wfstate S on H.wfstate_id=S.wfstate_id left join tuser U on H.wfuser_id=U.id'
	.' WHERE wfrequest_id='.$req_id.' ORDER by H.wfstatechange desc';
	$presult = sql_query($qry,$DB);
	return createRecordSet($presult,$DB);
}

function DB_listWFStatus($DB,$stateID=''){
	$sw=array();
	if (strlen($stateID)>0) $sw[]="S.wfstate_id='$stateID'";	
	$sWhere=" WHERE S.wfstate_id>0";
	$args = count($sw);
	for ($c=0; $c < $args; $c++) {
		$sWhere=$sWhere." AND ".$sw[$c];
	}
	$qry="select S.wfstate_id,S.statedesc,S.enterstateaction,S.stateaction,S.leavestateaction from wfstate S".$sWhere." ORDER by S.wfstate_id";
	$presult=sql_query($qry,$DB);
	return createRecordSet($presult,$DB);
}

function DB_listWFRequestType($DB,$active=1){
	$qry="select wfrequesttype_id,description,explanation from wfrequesttype where active=$active order by description";
	#debug($QRY);
	$presult = sql_query($qry,$DB);
	return createRecordSet($presult,$DB);
}
function DB_listWFEvent($DB,$active=1){
	$qry="select wfevent_id,concat(eventname,' ',eventyear) from wfevent where active=$active order by description";
	#debug($QRY);
	$presult = sql_query($qry,$DB);
	return createRecordSet($presult,$DB);
}

/**
*	purpose:	return match change history
* 	params:		matchkey
*	returns:	ROWSET + TABLEHEAD
*/
function DB_listMatchHistory($DB,$vmkey){
	/*
	 * returns a table containing the change history
	 * for a passed match KEY
	 */
	$p=sql_query("select * from tdsolog where ltext like '%".$vmkey."%' order by lid desc",$DB);
	$strTable='';
	while(list($lid,$lcode,$luser,$ldate,$ltext,$clientIP)=sql_fetch_row($p,$DB)) {
		/* $lcode is numeric 1-5, can we use some indexed icons for this .... */
		$strTable=$strTable."<tr><td><img src=\"images/".$lcode."log.png\"></td><td>$luser</td><td>$ldate</td><td>$ltext</td><td>$clientIP</td></tr>";
	}
	return $strTable;
}

/**
 * Return a recordset describing how many games a player has achieved with 3-2-1-0 legs ..
 * in a specific league or league=group
 *
 * @param pointer $DB,$event_id,$player_id
 */
function DB_retPlayerWonLostNumbers($DB,$event_id,$player_id){
	$qry='SELECT P.pid,P.plname,P.pfname, GP.gplegswon ,count(*) C FROM tblmatch M,tblgame G,tblgameplayer GP,tplayer P' 
	.' WHERE M.mkey=G.gmkey and G.gid=GP.gpgid and GP.gppid=P.pid and M.mkey like "e'.$event_id.'r%" and G.gtype=1 and P.pid='.$player_id.' group by GP.gplegswon ORDER by GP.gplegswon desc';
	#debug($qry);
	$presult = sql_query($qry,$DB);
	return createRecordSet($presult,$DB);
}

/*
 * RECORDSET CREATION FUNCTIONS, PRIVAT
 * ****************************************
 */
function createDistinctRecordSet($DB,&$qryResultset,$unique_idx){
	/*
	 * returns a distinct recordset, double entries are filtered 
	 * based on the field val in $unique_idx
	 */
	$id_unq='';
	$OUT=array();
	while($a=sql_fetch_row($qryResultset,$DB)){
				if ($id_unq<>$a[$unique_idx]) $OUT[]=$a;
				$id_unq=$a[$unique_idx];
		}
	return $OUT;
}

function createRecordSet(&$qryResultset,$DB){
	/*
	 * returns data from a queryresultset as array of arrays
	 * ATT: no associative array here !!
	 */
	$OUT=array();
		while($a=sql_fetch_row($qryResultset,$DB)){
				$OUT[]=$a;
		}
		return $OUT;
}

function createScheduleRecordSet(&$presult,$DB){
	/*
	 * This takes a RAW recordset as input and re-arranges into logical
	 * Match records, grouping corresponding entries into 1 typical schedule record
	 * 0=evid,evname,mid,mkey,mround,
	 * 5=mdate,mlocation,tname,mvsets,mvlegs,
	 * 10=mvsets,mvlegs,tname,mstatus
	 */
	$OUT=array();
	$lastmid=0;
	$whichTeam=0;
	/*
	 * Att:here we assume that every MATCH has MAX 2 Teams associated ...
	 */
	while(list($mevid,$evname,$mid,$mkey,$mround,$mdate,$mlocation,$mstatus,$mthome,$mvsets,$mvlegs,$tid,$tname)=sql_fetch_row($presult,$DB)){
		if ($lastmid<>$mid){
			// TEAM 1
			$whichTeam=1;
			$MATCHROW=array($mevid,$evname,$mid,$mkey,$mround,$mdate,$mlocation,$tname,$mvsets,$mvlegs);
		}else{
			// TEAM 2
			$whichTeam=2;
			$MATCHROW[]=$mvsets;
			$MATCHROW[]=$mvlegs;
			$MATCHROW[]=$tname;
			$MATCHROW[]=$mstatus;
		}
		if ($whichTeam==2) $OUT[]=$MATCHROW;
		$lastmid=$mid;
	} // end while resultset ...
	return $OUT;
}


?>