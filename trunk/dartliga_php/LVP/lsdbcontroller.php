<?php
/**
*	purpose:	general controller for LSDB Interface elements
* 				all functions in here are exposed both internal 
* 				and external as webservice
*   architecture: internal only function are not allowed in here
* 	params:		numerous
*	returns:	interface elements
*/


/**
 * returns a option select box with the passed
 * eventID = EVENT selected
 * name = eventid
 * $jsaction = yes / no inserting $selchangeaction
 * $eventid = 1,2,3, ...$activeflag = 0/1
 */
function Select_Event($idname='eventid',$eventid=0,$activeflag=1,$selchangeaction='') {
	# TODO RecordsetToSelectOptionList
	global $dbi;
	if ($activeflag==0) {	# // return all events ...
		$resx=sql_query('select E.id,E.evname,E.evyear from tblevent E order by E.evyear desc E.evname asc',$dbi);
	} else { 		# // return active events ...
		$resx=sql_query('select E.id,E.evname,E.evyear from tblevent E where E.evactive=1 order by E.evname asc',$dbi);
	}
	if (strlen($selchangeaction)>0){
		$strret='<select onchange=\''.$selchangeaction.'\' id=\'eventid\' name=\'eventid\' size=\'1\'>';
	} else {
		$strret='<select id=\'eventid\' name=\'eventid\' size=\'1\'>';
	}
	while (list($evid,$evname,$evyear)=sql_fetch_row($resx,$dbi)) {
		if ($eventid == $evid) {
			$strret=$strret.'<option value=\''.$evid.'\' selected=\'selected\'>'.$evname.' ('.$evyear.')</option>';
		} else {
			$strret=$strret.'<option value=\''.$evid.'\'>'.$evname.' ('.$evyear.')</option>';
		}
	}
	return $strret.'</select>';
}

	/**
	 * returns:		HTML select box
	 * param: 		$name_id=the name/id of the select TAG
	 * 				$group_id_selected = pre-selected ID
	 * webservice:	snippets/retStatGroupSelect.php
	 */
function Select_StatGroup($name_id,$group_id_selected=0,$changeaction=''){
	global $dbi;
	$RS=DB_listStatGruppen($dbi,1);
	$OUT=RecordsetToSelectOptionList($RS,array(0,1),$name_id,$group_id_selected,$changeaction);
	return $OUT;
}

/**
*	purpose:	returns player names from DB in select box
* 	params:		default name = vpid, default selected = pid=0
*	returns:	HTML select box
* 	webservice:	snippets/retPlayerSelect.php
*/
function Select_Player($name_id='vpid',$pidsel=0){
	global $dbi,$user;
	$RS=DB_listPlayers($dbi);
	# we return -- pid,pfname,plname,pfkey1 --
	$SEL=RecordsetToSelectOptionList($RS,array(0,3,2,4),$name_id,$pidsel);
	return $SEL;
}

/**
*	purpose:	returns statistic DATES from DB in select box
* 	params:		$vstatcode = 2(SSI),3(FEDA), 4(WDV),$vstatdate = pre selected date,elem_name
*	returns:	HTML select box
* 	webservice:	snippets/retStatDateSelect.php
*/
function Select_StatDate($vstatcode=0,$vstatdate='',$name_id='vindexdate',$onChangeAction=''){
	global $dbi;
	$RS=DB_listStatDate($dbi,$vstatcode);
	# returns - date,date
	$OUT=RecordsetToSelectOptionList($RS,array(0,1),$name_id,$vstatdate,$onChangeAction);
	return $OUT;
}

function Select_Location($idname='vloc',$onChangeAction='',$lidsel=0,$loc_name='',$loc_plz='',$loc_active=1){
	global $dbi;
	$RS=DB_listLocations($dbi,0,$loc_name,$loc_active,$loc_plz);
	$OUT=RecordsetToSelectOptionList($RS,array(0,1,3),$idname,$lidsel,$onChangeAction);
	return $OUT;
}

/**
*	purpose:	returns select box with Liga Groups
* 	params:		$name_id ; $group_id_selected
*	returns:	html select box
*/
function Select_LigaGroup($name_id,$group_id_selected=0,$changeaction=''){
	global $dbi;
	$RS=DB_listLigaGruppen($dbi,1);
	$OUT=RecordsetToSelectOptionList($RS,array(0,1),$name_id,$group_id_selected,$changeaction);
	return $OUT;
}

/**
*	purpose: returns select box of TeamNames, if event=0 return all active
* 	params:	 id/name,change_action,id_selected,eventid
*	returns:	HTML select Box
*/
function Select_Team($idname,$selchangeaction='',$tid=0,$eventid=0) {
	global $dbi;
	$RS=DB_listTeams($dbi,$eventid);
	$OUT=RecordsetToSelectOptionList($RS,array(4,2,5),$idname,$tid,$selchangeaction);
	return $OUT;
}

function Select_Verein($idname='vverein',$vidselected=0,$aRealm=array(),$selchangeaction='',$allowNoSelect=1) {
	# // $aRealm == the registermap entries like (1:2,2:1,9:3), we need the keys and convert to SQL INCLAUSE
	# v1.7 removed vlvcode from listbox
	# 
	
	global $dbi;
	$realm_in_clause='';
	if (sizeof($aRealm)>1){
		$aRealmK=array_keys($aRealm);
		foreach($aRealmK as $r){$realm_in_clause=$realm_in_clause.$r.',';}
		$realm_in_clause=substr($realm_in_clause,0,strlen($realm_in_clause)-1);
		$RS=DB_listVereine($dbi,0,$realm_in_clause,'','',0);
	} else {
		$RS=DB_listVereine($dbi,$vidselected,'','','',0);
	}
	$OUT=RecordsetToSelectOptionList($RS,array(0,1,3),$idname,$vidselected,$selchangeaction,$allowNoSelect);
	return $OUT;
}

/**
 * returns select box string
 * param: 	$name_id=the name/id of the select TAG
 * 				$$status_selected = pre-selected ID
 */
function Select_MatchStatus($name_id,$status_selected=0,$changeaction='',$allowNoSelect=1){
	global $dbi;
	$RS=DB_listMatchStatus($dbi);
	$OUT=RecordsetToSelectOptionList($RS,array(0,1),$name_id,$status_selected,$changeaction,$allowNoSelect);
	return $OUT;
}

function Select_WfRequestType($idname,$selected){
	global $dbi;
	$RS=DB_listWFRequestType($dbi,1);
	return RecordsetToSelectOptionList($RS,array(0,1),$idname,$selected);
}

/**
*	purpose:	returns ACTIVE MEMBERSHIP TYPES like primary,secondary ... from ttypemember
* 	params:		
*	returns:	HTML SELECT with onChange
*/
function Select_Membertype($name_id='vmtype',$mSelected=1,$changeaction='',$allowNoSelect=1) {
	global $dbi,$usertoken;
	
	$RS=DB_listMemberType($dbi,1);
	$OUT=RecordsetToSelectOptionList($RS,array(0,1),$name_id,$mSelected,$changeaction,$allowNoSelect);
	return $OUT;
}

/**
 * returns a option select box for Bereiche/Verbaende
 * $option_selected = 1,2,3,-10,11 ...
 * $allowNoSelect = 1|0
**/
function Select_Realm($idname='vrealm',$option_selected=0,$allowNoSelect=1,$selchangeaction='') {
	
	global $dbi;
	$resx=sql_query('select V.id,V.vcode,V.vname from tverband V order by V.id asc',$dbi);
	$strret='<select id="'.$idname.'" name="'.$idname.'" onchange="'.$selchangeaction.'" size="1">';
	if ($allowNoSelect==1) $strret=$strret.'<option value="0">-- No selection --</option>';
	while (list($vid,$vcode,$vname)=sql_fetch_row($resx,$dbi)) {
		if ($option_selected == $vid) {
			$strret=$strret.'<option value="'.$vid.'" selected="selected">'.$vname.'</option>';
		} else {
			$strret=$strret.'<option value="'.$vid.'">'.$vname.'</option>';
		}
	}
	return $strret.'</select>';
}

/**
*	purpose:	Return a Select Box, based on entries in the registermap
* 	params:		option_selected, none (usertoken)
*	returns:	HTML Select + attached change_action
*/
function Select_RealmFromRegisterMap($idname='',$option_selected=0,$selchangeaction=''){
	global $usertoken,$dbi;
	$resx=sql_query('select V.id,V.vname from tregistermap R, tverband V WHERE R.verband_id=V.id and R.user_id='.$usertoken['id'],$dbi);
	$strret='<select id=\''.$idname.'\' name=\''.$idname.'\' onchange=\''.$selchangeaction.'\' size=\'1\'>';
	while (list($vid,$vname)=sql_fetch_row($resx,$dbi)) {
	if ($option_selected == $vid) {
			$strret=$strret.'<option value=\''.$vid.'\' selected=\'selected\'>'.$vname.'</option>';
		} else {
			$strret=$strret.'<option value=\''.$vid.'\'>'.$vname.'</option>';
		}
	}
	return $strret.'</select>';
}

function Select_Gender($idname='vgender',$gselected='H'){
	$a=array('H','D');
	$strret='<select name="'.$idname.'" id="'.$idname.'" size="1">';
	foreach ($a as $val) {
		if ($val == $gselected) {
			$strret=$strret.'<option value="'.$val.'" selected="selected">'.$val.'</option>';
		} else {
			$strret=$strret.'<option value="'.$val.'">'.$val.'</option>';
		}
	}
	return $strret.'</select>';
}

function Select_MessageGroup($nameid='wfmember',$selected=1,$publicview=1,$verband_id=0){
	global $dbi;
	$sWHERE=' AND publicview='.$publicview;
	if ($verband_id>0) $sWHERE=$sWHERE.' AND verband_id='.$verband_id;
	$resx=sql_query('SELECT M.mgroup_id,M.mgroupname from tmessagegroup M where M.active=1 '.$sWHERE.' ORDER by M.mgroupname asc',$dbi);
	$strret='<select id=\''.$nameid.'\' name=\''.$nameid.'\' size=\'1\'>';
	// $strret=$strret."<option value=0>-- No selection --</option>";
	while (list($id,$name)=sql_fetch_row($resx,$dbi)) {
		if ($selected == $id) {
			$strret=$strret.'<option value='.$id.' selected=\'selected\'>'.$name.'</option>';
		} else {
			$strret=$strret.'<option value='.$id.'>'.$name.'</option>';
		}
	}
	return $strret.'</select>';
}

/**
*	purpose:	render player type select box
* 	params:		name/id , pre_sel
*	returns:	select box
*/
function Select_LineUpType($name_id,$type_id_selected,$selchangeaction){
	global $dbi;
	$RS=DB_listLineUpTypes($dbi);
	$OUT=RecordsetToSelectOptionList($RS,array(0,1),$name_id,$type_id_selected,$selchangeaction);
	return $OUT;
}

/**
*	purpose:	returns a from -to date select box
* 	params:		$dateselected
*	returns:	HTML select box
*/
function Select_SSIPeriode($idname,$dateselected,$statcode) {
	# // returns a option select box with the passed
	# // datevar highlighted and preselected
	# // v14: select box contains a from - to date
	# v 3-8 using the common tbldate
	
	global $dbi,$user;
	$trecords=sql_query('SELECT sdate from tbldate where sstatcode_id='.$statcode.' order by sdate desc',$dbi);
	$strret="<select name=\"$idname\" id=\"$idname\" size=\"1\">";
	$dateEnd='';
	while (list($tdate)=sql_fetch_row($trecords,$dbi)){
	
		$dateStart=$tdate;
		if ($dateEnd == ''){
			# // first record no action
		} else {
			# // second+ record - action
			$strDATE=$dateStart.'  bis  '.$dateEnd.'&nbsp';
			if ($dateselected == $dateStart) {
				$strret=$strret."<option value=\"$dateStart\" selected=\"selected\">$strDATE</option>";
			 } else {
				$strret=$strret."<option value=\"$dateStart\">$strDATE</option>";
			 }
		}
		$dateEnd=$tdate;
	}
	return $strret.'</select>';
}

function Select_WFEvent($idname,$optionsel,$evactive=1,$selchangeaction='') {
	# TODO RecordsetToSelectOptionList
	global $dbi;
	# // return active events ... ATT: no option <-- nothing -->
	$resx=sql_query("select E.wfevent_id,E.eventname,E.eventyear from wfevent E where E.evactive=$evactive order by E.eventname asc",$dbi);
	if (strlen($selchangeaction)>0){
		$strret="<select onchange=\"$selchangeaction\" id=\"$idname\" name=\"$idname\" size=\"1\">";
	} else {
		$strret="<select id=\"$idname\" name=\"$idname\" size=\"1\">";
	}
	while (list($evid,$evname,$evyear)=sql_fetch_row($resx,$dbi)) {
		if ($optionsel == $evid) {
			$strret=$strret."<option value=\"$evid\" selected=\"selected\">$evname ($evyear)</option>";
		} else {
			$strret=$strret."<option value=\"$evid\">$evname ($evyear)</option>";
		}
	}
	return $strret."</select>";
}

/**
*	purpose:	typical child table, listing of all active teams for a specific Player
* 	params:		player
*	returns:	HTML Table
*/
function LSTable_PlayerToTeams($idname,$player_id,$btnCaption='',$btnAction=''){
	global $dbi;
	$ROWS='';$HEAD='';
	$RS1=DB_listEventWFTeamPlayers($dbi,1,$player_id);
	$RS2=DB_listEventTeamWFPlayers($dbi,1,$player_id);
	$RS3=DB_listEventTeamPlayers($dbi,'',0,'',0,'',1,0,'team','',$player_id);
	
	$RS=array_merge($RS1,$RS2);
	$RS=array_merge($RS,$RS3);
	
	if (!sizeof($RS)>0) {
		$ROWS='<tr><td><font color=green>Keine Mannschaftsmeldungen f&uuml;r Spieler:'.$player_id.'</font></td></tr>';
	} else {
		$aTH=array('LigaGruppe','Bewerb / Liga','Saison','Teamname','Vorname','Nachname',);
		if (strlen($btnAction)>1) $aTH[]='Aktion';
		$HEAD=ArrayToTableHead($aTH);
		if (strlen($btnAction)>1){
			$ROWS=RecordsetToDataTable($RS,array(1,3,4,6,8,9),array($btnAction),array(array(7)),array($btnCaption));
		} else {
			$ROWS=RecordsetToDataTable($RS,array(1,3,4,6,8,9));
		}
	}
	
	return '<table class="tchild" id="'.$idname.'" name="'.$idname.'">'.$HEAD.$ROWS.'</table>';
}

/**
*	purpose:	Render Table with Static Statistic Values
* 	params:		
*	returns:	HTML Table + Header
*/
function LSTable_StaticStatValue($idname,$pid,$statcode,$rowlimit){
	global $dbi;
	$ROWS='';$HEAD='';
	$RS=DB_listStaticStatValues($dbi,$statcode,'',$pid,$rowlimit);
	$aTH=array("Datum","Wert","Spiele","Legs","Vorname","Nachname");
	$HEAD=ArrayToTableHead($aTH);
	$ROWS=RecordsetToDataTable($RS,array(1,2,3,4,6,7));
	return '<table class="tchild" id="'.$idname.'" name="'.$idname.'">'.$HEAD.$ROWS.'</table>';
}

/**
*	purpose:	render table with active memberships for specified player
* 	params:		player_id
*	returns:	TABLE
*/
function LSTable_PlayerActiveMemberShips($idname,$player_id){
	global $dbi;
	$ROWS='';$HEAD='';
	$RS=DB_listMemberShips($dbi,$player_id,0,'>0',1);
	$aTH=array('id','Verein','Art','PassNr','Ende','Vorname','Nachname','Aktion');
	$HEAD=ArrayToTableHead($aTH);
	$ROWS=RecordsetToDataTable($RS,array(0,2,3,4,5,7,8),array('memberedit','memberdel'),array(array(0,6),array(0,6)),array('Edit','Del'));
	return '<table class="tchild" id="'.$idname.'" name="'.$idname.'">'.$HEAD.$ROWS.'</table>';
}
?>