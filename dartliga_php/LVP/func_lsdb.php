<?php
# ----------------
# v01 BH 2003/08/12
# lsys v1_2
# ----------------
#########################
# File links and URLS used within the module to access the code
# if you change the directory -> this file is included in all other files by directory path !!
# require("func_lsdb.php");
# last change BH fixed SQL bug in ReturnDOubleEntries 
#########################

/**
*	purpose:	general functions file
* 	att: v4		as of v4 all internal and external element render function 
* 				go into corresponding controllers and can be called as webservice
*/

if (eregi("func_lsdb.php",$_SERVER['PHP_SELF'])) {Header("Location: ./"); die();}
########
#$playercode = "ssi_player.php";
$dsoplayercode = "dso_player.php";
$dsovereincode="dso_verein.php";
$pointcode = "ssi_points.php";
##########################
# Graphics used + ALT Tags
#
$redpic = "<img src=\"images/ballr.png\" border=\"0\" alt=\"Kein Resultat bekannt\">";
$greenpic = "<img src=\"images/ballg.png\" border=\"0\" alt=\"Match komplett erfasst\">";"<img src=\"images/ballr.png\" border=\"0\" alt=\"Match komplett erfasst\">";
$greencrosspic="<img src=\"images/circleg.png\" border=\"0\" alt=\"Resultat eingereicht\">";
$orangepic = "<img src=\"images/ballo.png\" border=\"0\" alt=\"Match teilweise erfasst\">";
$bluepic = "<img src=\"images/ballb.png\" border=\"0\" alt=\"Match Resultat bekannt\">";
$predpic = "<img src=\"images/redman.png\" border=\"0\" alt=\"Person nicht aktiv\">";
$pgreenpic = "<img src=\"images/greenman.png\" border=\"0\" alt=\"Person ist aktiv\">";
$porangepic = "<img src=\"images/orangeman.png\" height=\"18px\" border=\"0\" alt=\"Person Status unbek.\">";

##########
# Some TD Cells acting as tablehead use their own BGCOLOR, set to $bgcolor3 or $bgcolor4 to use theme colors
# but the oedv theme is ... lets use a light red ....

$sctdcolor = "#cccccc";
$sipgoback = "<br><b><a href=\"javascript:history.back()\">Zur&uuml;ck zum Datenformular</a></b>";
$img120="<img src='images/pix.gif' width='100px' height='1px' border='0'>";
$clientIP=$_SERVER['REMOTE_ADDR'];

/* Beginn global functions */
/***************************/
function matchStatusToImage($status) {
	/*
	 * param:	matchstatus integer
	 * returns:	image TAG pointing to the correct visual
	 */
	global $dbi;
	$RS=DB_listMatchStatus($dbi);
	// ATT the array is zero based ...the table IDs are NOT !!
	return '<img src=\'images/'.$RS[$status-1][2].'\' border=\'0\' alt=\''.$RS[$status-1][3].'\'>';
}

/**
*	purpose:	Load Vereins Formular
* 	params:		array
*	returns:	HTML form
*/
function form_Verein($vereininfo){
	return include('forms/verein.php');
}
/**
*	purpose:	Render Player Formular without FORM Tag
* 	params:		array(aPlayer)
*	returns:	HTML Table + submit button
*/
function form_Player($aP){
	return include('forms/player.php');
}
/**
*	purpose:	render search form with passnr Lastname 
* 	params:		javascript submitaction
*	returns:	HTML
*/
function form_SearchPlayer($btnAction='',$docLocation='',$inputname=1){
		$RET='<table><tr>';
		$RET=$RET.'<td>Pass Nr: '._input(1,'ppassnr','',10,10).'</td>';
		if ($inputname==1) {$RET=$RET.'<td>Nachname: '._input(1,'lastname','',20,20).'</td>';}
		$RET=$RET.'<td>'._button('Suchen',$btnAction,$docLocation).'</td>';
		return $RET.'</tr></table>';
}
/**
*	purpose:	render Team Formular inkl submit button, without form tags
* 	params:	
*	returns:	
*/
function form_Team($aRec){
	# create a team data form using $aRec array 
	return include('forms/team.php');
}

function form_EventGroupSelect($formaction,$selchangeaction,$sel_group=0,$sel_stat=0){
	/*
	 * render typical selector + submit button to select
	 * and change between EventGroups
	 */
	$ret='';
	$ret=$ret.'<form action=\''.$formaction.'\' method=\'post\'>';
	$ret=$ret.'<table><tr><td>W&auml;hle eine Liga Gruppe:</td>';
	$ret=$ret.'<td>'.Select_LigaGroup('eventgroup',$sel_group).'</td>';
	if ($sel_stat>0) {
		$ret=$ret.'<td>W&auml;hle einen Matchstatus:</td>';
		$ret=$ret.'<td>'.Select_MatchStatus('stat',$sel_stat).'</td>';
	}
	$ret=$ret.'<td>'._button('Anzeigen').'</td>';
	$ret=$ret.'</tr></table></form>';
	return $ret;
}

function form_StatListSelect($formaction,$selchangeaction,$sel_group=0){
	/*
	 * render typical selector + submit button to select
	 * and change between Ranking Groups
	 */
	$ret='';
	$ret=$ret.'<form action=\''.$formaction.'\' method=\'post\'>';
	$ret=$ret.'W&auml;hle eine Statisktik oder eine Rangliste aus und dr&uuml;cke auf Anzeigen: ';
	$ret=$ret.Select_StatGroup('statcode',$sel_group);
	$ret=$ret.' <input type=\'submit\' value=\'Anzeigen\'>';
	$ret=$ret.'</form>';
	return $ret;
}

/**
*	purpose:	render Team Select Form
* 	params:		$actiontarget for Submit / preselected value
*	returns:	form containing select + submit button
*/
function form_TeamSelect($formaction,$selchangeaction,$sel_team=0,$event_id=0,$showsubmit=1){
	$ret="";
	$ret=$ret.'<form action="'.$formaction.'" method="post">';
	if (strlen($selchangeaction)==0) {
		$ret=$ret.'W&auml;hle ein Team aus der Liste und dr&uuml;cke auf Anzeigen: ';
	}else{
		$ret=$ret.'W&auml;hle ein Team aus der Liste: ';
	}
	$ret=$ret.Select_Team('tid',$selchangeaction,$sel_team,$event_id);
	if ($showsubmit==1) $ret=$ret.' <input type=submit value="Anzeigen">';
	$ret=$ret.'</form>';
	return $ret;
}	

function form_new_Request($aRec=array(),$formaction='',$selchangeaction='',$showsubmit=1){
	/*
	 * render request form, if user is ADMIN of some sort than verein_id=0 --> show a verein selector
	 */
	global $dbi;
	$RS=DB_listWFRequestType($dbi,1);
	$RET= '<form id=\'frequest\' name=\'frequest\' action=\''.$formaction.'\' method=\'POST\'>';
	$RET=$RET.'<table width=\'100%\' cellpadding=\'2\' cellspacing=\'2\'>'
   		.'<tr><td colspan=\'2\'>Welche Art von Anmeldung oder Anforderung m&ouml;chtest du erstellen ?</td></tr>';
		foreach ($RS as $a){
		   		$RET=$RET.'<tr><td class=\'bluebox\'>'.$a[1].'</td><td>'.$a[2].'</td></tr>';
		   	}
	if ($aRec['verein_id']==0) {
		$RET=$RET.'<tr><td>'.Select_Verein('vid').'</td><td>Hier <b>muss</b> ein antragstellender Verein gew&auml;hlt werden.(*)</td>';
	}else {
		$RET=$RET.'<tr><td>'._input(2,'vid',$aRec['verein_id']).'</td><td>Antragstellende Verein</td>';
	}
   	$RET=$RET.'<tr><td>'.Select_WfRequestType('wfreqtype',$aRec['wfrequesttype']).'</td>';
   	if ($showsubmit==1) $RET=$RET.'<td>'._button('Erstellen').'</td></tr>';
   	$RET=$RET.'</table></form>';
   	
	return $RET;
}


function fnc_date_calc($this_date,$num_days){
	# add or substract days from date
	$my_time = strtotime ($this_date); //converts date string to UNIX timestamp
	$timestamp = $my_time + ($num_days * 86400); //calculates # of days passed ($num_days) * # seconds in a day (86400)
	$return_date = date("Y-m-d",$timestamp);  //puts the UNIX timestamp back into string format

	return $return_date;	//exit function and return string
}

function reteventconfig($eventid){
	# retrieve everything into ARRAY - Name ? ....
	# FIX 04.2008 we ended up with 2 id fields in here ...
	# fix 07.2008 DB is passed as param ...
	global $dbi;
	$event=array();
	$prec=sql_query('Select E.*,C.*,T.typdesc,T.mgroup_id from tblevent E,ttypeliga T,tbleventconfig C where E.evtypecode_id=T.id AND E.evconfig_id=C.config_id AND E.id='.$eventid,$dbi);
	$event=sql_fetch_array($prec,$dbi);
	return $event;
}

function retAccessThisMatchKey($thismatchkey){
	/*
	 * purpose:v4 this is for the enhanced access model where special verein-accounts
	 * can be used to write-save Games & matchsheets (similar to AC:2) of selected teams
	 * returns: false / 1 
	 */
	/*
	 * v4: release, add check on the location accounts.
	 */
	global $dbi,$usertoken;
	
	if (sizeof($usertoken)<5){return FALSE;}
	$ret=FALSE;
	$userverein=0;
	$hometeamid=0;
	/* if user.type <> verein exit 0
	* extract hometeam from matchkey
	* get verein for hometeam
	* get location for hometeam
	* compare against user.verein - return 0/1
	* compare against user.location - return 0/1
	* 	*/
	$userverein=$usertoken['verein_id'];
	$userlocation=$usertoken['location_id'];
	$hometeamid=retTeamFromMatchkey($thismatchkey,"h");
	#debug('V'.$userverein.'-T:'.$hometeamid.'-L'.$userlocation);
	if ($userverein>0) {
		$TEAMS=DB_listTeams($dbi,0,0,"",1,"",$userverein);
		# the teamid is in the 5th element - cycle to match against hometeam
		# here we have a max of 6-8 teams no need for early break ...
		foreach ($TEAMS as $a){
			#debug("Verein ".$userverein." HomeTeam: ".$hometeamid." Team: $a[4] $a[5]");
			if ($a[4]==$hometeamid) $ret=1;
		}
	}
	#
	# compare the team-locationID against the user.location
	if ($userlocation>0) {
		$TEAMS=DB_listTeams($dbi,0,0,"",1,"",0,"",$userlocation);
		# the teamid is in the 5th element - cycle to match against hometeam
		# here we have a max of 6-8 teams no need for early break ...
		foreach ($TEAMS as $a){
			#debug("Verein ".$userverein." HomeTeam: ".$hometeamid." Team: $a[4] $a[5]");
			if ($a[4]==$hometeamid) $ret=1;
		}
	}
	return $ret;
}

function retTeamFromMatchkey($thismatchkey,$which){
	/*
	 * v4 returns hometeam ID from the patched matchkey
	 * 	matchkey = eXXXrXXXhXXXAXXX is always lower_case
	 */
	$ret=0;
	$h_pos=strpos($thismatchkey,"h");
	$a_pos=strpos($thismatchkey,"a");
	$key_len=$a_pos-$h_pos-1;
	if ($which=="h"){
		#echo "<i>$thismatchkey,$h_pos,$key_len</i>";
		$ret=intval(substr($thismatchkey,$h_pos+1,$key_len));
	} elseif ($which=="a") {
		$ret=intval(substr($thismatchkey,$a_pos+1));
	}
	return $ret;
}

function setUserProperty($user_id,$property,$value){
	/*
	 * set a specific user property to a value, returns 1 on success
	 */
	global $dbi;
	if (!$user_id>1) return 0;
	
	$ret=0;
	$QRY='UPDATE tuser set '.$property.'=\''.$value.'\' where ID='.$user_id.' limit 1';
	$ret=sql_query($QRY,$dbi);
	return $ret;
}

function retUserProperty($user_id,$property){
	/*
	 * returns a specific property for a user account 
	 */
	global $dbi;
	$ret=0;
	if ($user_id>1){
		$QRY='select '.$property.' from tuser where ID='.$user_id;
		$QRES=sql_query($QRY,$dbi);
		$QRET=sql_fetch_array($QRES,$dbi);
		if ($QRET) $ret=$QRET[0];
	}
	return $ret;
}

function navi_request(){
	$OUT= '<table>';
	$OUT=$OUT.'<tr><td>'._button('Meine Antr&auml;ge','','?op=list').'</td></tr>';
	$OUT=$OUT.'<tr><td>'._button('Neuer Antrag','','?op=new').'</td></tr>';
	$OUT=$OUT.'</table>';
	return $OUT;
}
function navi_stats(){
	$OUT= '<table>';
	$OUT=$OUT.'<tr><td>'._button('Stichtage','','stats_date.php').'</td></tr>';
	$OUT=$OUT.'<tr><td>'._button('Listen','','stats_admin.php').'</td></tr>';
	$OUT=$OUT.'<tr><td>'._button('Werte','','stats_edit.php').'</td></tr>';
	$OUT=$OUT.'</table>';
	return $OUT;
}
function navi_dso() {
	#
	# returns the DSO Navigation Pane for the left frame
	# 
	# this is placed inside the div=navi
	# 05.2008 switched to usertoken
	# level 0 no access, 1 read, 2 vereine, 3 membership
	#TODO --> improve Layout
	#
	
	global $dsoplayercode,$dsovereincode,$usertoken;
	$OUT='';
	if (sizeof($usertoken['registermap'])<1) {
		// this user has no registration rights ...
		return '<div></div>';
	} 
	# - Working but not neccessary ...
	#$OUT=Select_RealmFromRegisterMap('realmid',$realmid,'selRealmChange()');
	#
	$OUT=$OUT.'<br/>'._button('Spieler','regbtnclick(1)');
	$OUT=$OUT.'<br/>'._button('Vereine','regbtnclick(2)');
	$OUT=$OUT.'<br/><br/>'._button('Spieler NEU','regbtnclick(3)');
	$OUT=$OUT.'<br/>'._button('Verein NEU','regbtnclick(4)');
	
	// search fields 
	$OUT=$OUT.'<br/><br/>'.OpenTable('spanel',1);
	$OUT=$OUT.'<form action="'.$dsoplayercode.'?func=search" method="post">Spieler suchen (Name):<br>'._input(1,'findstr','',15,20).'</form><br/>';
	$OUT=$OUT.'<form action="'.$dsoplayercode.'?func=search" method="post">Spieler suchen (Passnr.):<br>'._input(1,'findpass','',15,20).'</form><br/>';
	$OUT=$OUT.'<form action="'.$dsovereincode.'?func=search" method="post">Verein suchen (Name):<br>'._input(1,'vname','',15,20).'</form><br/>';
	$OUT=$OUT.CloseTable(1);
	
	$OUT=$OUT.'<br/><br/>'.OpenTable('smpanel',1);
	$OUT=$OUT.include('forms/searchmembership.php');
	$OUT=$OUT.CloseTable(1);
	
	$OUT=$OUT.'<br/><p>Name: '.$usertoken['uname'].'</p>';
	$OUT=$OUT.'<br/>'.'- <a href="dso_user.php?op=logout">Abmelden</a> - <br/>';
	#$OUT=$OUT.debug($usertoken['registermap']);
	return $OUT;
}


function navi_ssi() {
	#
	# display some navigation buttons, icons ...
	#
	global $usertoken,$lastdate;

	# // ANY USER NAV
	echo _button('SSI Ranking','','ssi_points.php').'<br/>';
	#echo _button("SSI LandesVerband","","ssi_points.php?func=rankinglv").'<br/>';
	echo _button('Erfasste Spiele','','ssi_points.php?func=listgames').'<br/>';
	echo _button('Meine Chancen','','ssi_points.php?func=personal').'<br/>';
	
	/*
	if ($SSI_LEVEL > 1){
	echo _button("Alle Spieler","","ssi_player.php?func=listall").'<br/>';
	echo _button("Spieler ohne SSI","","ssi_player.php?func=nossi").'<br/>';
	echo _button("Spielresultat eingeben","","ssi_points.php?func=newresult").'<br/>';
	}
	
	if ($SSI_LEVEL > 2){
	echo _button("SSI Berechnung","","ssi_points.php?func=calcperiod").'<br/>';
	}
	*/
	echo 'System SSI Date: '.retaustriandate($lastdate);
}

function navi_lsdb($eventid){
	#
	# display some navigation buttons, icons ...
	# this is called inside a TABLE Definition
	# BH v1 12.2005
	global $lastdate,$usertoken;
	# level 0,1 = public
	# level 2 = write, edit
	# level 3 = master
	#  hdlClick() 1 = team, 2=match, 3=schedule,4=stats
	
	$AccCode=0;
	if ($eventid>0) {
		if (sizeof($usertoken)>5){
			if (sizeof($usertoken['eventmap'])>0){$AccCode=$usertoken['eventmap'][$eventid];}
		}
	}
	
	echo '<form id="frmEvSel" name="frmEvSel" action="" method="post">';
	echo Select_Event($idname='eventid',$eventid,1,'selLigaChange()').'</form>';
	echo '<br/>'._button('Teams / Aufstellung','lsdbbtnclick(1)');
	if ($AccCode==3){
		echo '<br/>'._button('Team NEU','lsdbbtnclick(9)');
	}
	echo '<br/>'._button('Alle Spielorte','lsdbbtnclick(6)');
	echo '<br/>'._button('Spielplan / Berichte','lsdbbtnclick(3)');
	if ($AccCode>1){
		echo '<br/>'._button('Spielbericht NEU','lsdbbtnclick(2)');
	}echo '<br/>'._button('Tabelle','lsdbbtnclick(5)');
	echo '<br/>'._button('Spieler Statistik','lsdbbtnclick(4)');
	echo '<br/>'._button('Team Statistik','lsdbbtnclick(8)');

	# Show Current ACCESS CODE if eventid>0 ..
	# debug(AC: '.$AccCode);
}

function checkBirthDate($vDateString){
# // return true/false (1/0)
	$dateerr=0;
	if (strlen($vDateString) == 0) $dateerr = 1;
	if (!substr_count($vDateString,"-") == 2) $dateerr=1;
	if (substr($vDateString,0,4) > 2007) $dateerr=1;
	if (substr($vDateString,5,2) > 12) $dateerr=1;
	if (substr($vDateString,8,2) > 31) $dateerr=1;

	if ($dateerr == 0) {
		return 1;
		} else {
		return 0;
		}
}

function CheckUniquePlayer($vfname,$vlname,$vbirthdate){
	# // try and find similar players in DB, if yes then
	# // return a formatted HTML table
	# // RO access no check on level or realm !!!!
	global $user,$dbi;
	$strret="";
	$resx=sql_query("select pfname,plname,pbirthdate,pcre_date,pcre_user from tplayer where pfname like '$vfname' and plname like '$vlname' and pbirthdate = '$vbirthdate' order by plname",$dbi);

	if (sql_num_rows($resx)>0) $strret="<h3>Doppeleintr&auml;ge gefunden</h3><table>";
	while (list($pfname,$plname,$pbirthdate,$prealm,$vname,$cd,$cu)=sql_fetch_row($resx,$dbi)) {
		$i=$i+1;
		$strret=$strret."<tr><td>$pfname</td><td>$plname</td><td>$pbirthdate</td><td>Gebiet: $vname ($prealm)</td><td>By: $cu am $cd </td></tr>";
	}
	if (strlen($strret)>0)$strret=$strret."</table>";
	return $strret;
}


function Select_EventRound($idname,$r,$eventid){
	/*
	 * return a selector for the round-number based on the event - configuration
	 * name = rnum
	 */
	
	global $dbi,$user,$event;
	/*
	$resx=sql_query('select E.evnumrounds from tblevent E where E.id='.$eventid,$dbi);
	if(sql_num_rows($resx, $dbi)==1) {
		$row=sql_fetch_array($resx, $dbi);
		$rMAX=$row['evnumrounds'];
	} else {
		$rMAX=50;
	}
	*/
	$rMAX=$event['evnumrounds'];
	$strret='<select name=\''.$idname.'\' size=\'1\'>';
	for ($i = 1;$i <= $rMAX;$i++){
		$strret=$strret.'<option value=\''.$i.'\'>Runde '.$i.'</option>';
	}
	return $strret.'</select>';
}

function RetStatDateForStatCode($vstatcode,$matchdate='nop'){
	// returns the date for the actual ranking for a specific event-statistics-group and a given date
	// basically this is the valid ranking for a given match
	global $dbi;
	if(!$vstatcode) $vstatcode = 1;
	if($matchdate=='nop') {
		// default is TODAY ...
		$d = getdate();
		$matchdate = "$d[year]"."-"."$d[mon]"."-"."$d[mday]";
	}
	$res=sql_query("select max(sdate) VAL from tbldate where sstatcode_id=$vstatcode and sdate<'$matchdate'",$dbi);
	$aRET=sql_fetch_array($res,$dbi);
	return $aRET['VAL'];
}

function RetStatValForPlayerOnDate($P_ID,$vstatcode=0,$matchdate=""){
	/*
	 *  	returns the ranking points for a player on a given date.
	 * 	default vstatcode=1, usually this is the evstatcode from event config
	 * 	matchdate defaults to today()
	 * 	TODO - replace by query lookup according to the evStatCode
	 * 		- dont we have a proper DB_retSomething for this ???
	 * 				- in the future statvals or else can be in different tables or static lists
	 * 	ATT there may be no actual EVENT when we come here ...
	 */  
	
	global $event,$dbi;

	if(strlen($matchdate)==0) {
		// default is TODAY ...
		$d = getdate();
		$matchdate = "$d[year]"."-"."$d[mon]"."-"."$d[mday]";
	}
	if(!$P_ID) return 0;
	// retrieve a valid ranking date for this statistics_code
	$vrankingdate=RetStatDateForStatCode($vstatcode,$matchdate);
	// get points for this date ...
	// $PID $vstatcode $vstatdate;
	#debug($vrankingdate);
	switch($vstatcode) {
		case 9:
		case 16:
		case 17:
			// experimental value generated by dividing legpoints by matches ...
			$QRY='select sum(gplegswon)/count(gplegswon) statval,pid,plname,pfname from tblmatch,tblgame,tblgameplayer,tplayer'
			.' WHERE mkey=gmkey and gid=gpgid and gppid=pid and mkey like "e'.$event['id'].'r%" and gtype=1 and pid='.$P_ID.' group by pid order by statval desc';
			$res=sql_query($QRY,$dbi);
			break;
		case 1:
		case 3:
		case 5:
		default:
			$res=sql_query("select statval from tblstat where statpid=$P_ID and statcode=$vstatcode and statdate='$vrankingdate'",$dbi);
			break;
		
	}
	$aRET=sql_fetch_array($res,$dbi);
	return $aRET['statval'];
}

function RetLigaAdminEmail($eventid=0){
	$aR=array();
	if($eventid==0) return $aR;
	global $dbi;
	$qry="select E.evname,E.evyear,T.typdesc,U.fullname,U.email from tuser U,tbladminliga A,ttypeliga T,tblevent E"
	." where U.id=auid_id and A.aevcode=T.id and E.evtypecode_id=A.aevcode_id and E.id=$eventid";
	$prec=sql_query($qry,$dbi);
	while($a=sql_fetch_row($prec,$dbi)){
				$aR[]=$a[4];
		}
	return $aR;
}

function getTableTabelleSetsByLigaID($vTlid){
	/* #TODO check if similar to existing API */
	// returns HTML table with a full tabelle for Liga ID=param
	global $dbi;
	$strsql="select T.id,T.tname,sum(mtsets) SETSW,sum(mtsetslost) SETSL,sum(mtsets)-sum(mtsetslost) SDIFF,sum(mtlegs) LEGSW,sum(mtlegslost) LEGSL,sum(mtlegs)-sum(mtlegslost) LDIFF, count(mstatus) CT from tblmatchteam,tblmatch,tblteam T where mttid=T.id and mtmkey=mkey and mstatus<>0 and T.tevent_id=$vTlid group by mttid order by SDIFF desc,LDIFF desc";
	$presult=sql_query($strsql,$dbi);
	$OUT=renderHTMLTableRows($presult,$dbi);
	return $OUT;
}

function renderHTMLTableRows($qryResultset,$DB){
	/* #TODO check if similar to existing API */
	/*
	 * Presentation Layer, this converts a RAW array into an HTML Rowset
	 * all TD have a class=dcell
	 * 
	 * ATT: old style -> use the API_RS and the API_FORMAT:RecordsetToDataTable for stuff like this in the future
	 */

	$strRET="";
	while($a=sql_fetch_row($qryResultset,$DB)){
		
		$strRET=$strRET."<tr>";
		foreach ($a as $val) $strRET=$strRET."<td class=dcell>".$val."</td>";
		$strRET=$strRET."</tr>";
		
	}
	return $strRET;
}

function renderWOMatchForm($eventid,$vmkey,$woteam,$winteam,$teamName){
	/*
	 * renders a simple Button to set an entire Match #WO if one team is not present
	 * param: $whichteam is 0/1 and sets the caption on the Button
	*/
	$btnCaption='#WO '.$teamName.' (=nicht angetreten)';
	
	$STROUT="<form action=\"ls_system.php\" method=\"post\">"
		._input(0,"func","womatch")
		._input(0,"vmkey",$vmkey)
		._input(0,"eventid",$eventid)
		._input(0,"woteam",$woteam)
		._input(0,"winteam",$winteam)
		._button($btnCaption,"")."</form>";
		return $STROUT;
}

function _LS_renderSaveMatchBox($LS_LEVEL,$setshometeam,$setsawayteam,$legshometeam,$legsawayteam,$match_status){
	/*
	 * this renders a SUMMARY form to the end of a matchsheet.
	 * either in full admin mode with SAVE or in Submit mode for the MsgSystem
	 * param: $LS_LEVEL = 0,1,2,3 => we either show the status selector or not ...
	 */				
	
		$STROUT= '<table><tr>'
			.'<td><image src="images/save.png" align="left">Mit <b>Addieren</b> wird das Matchresultat aus den gespeicherten Spielen ermittelt. Mit <b>Speichern</b> wird dieses Resultat (Sets/Legs) im Spielplan und f&uuml;r die Tabelle freigegeben. Der entsprechende Eintrag wird unterhalb eingeblendet.</td></tr>'
			.'<tr><td align="center">Heim / Ausw&auml;rts</td></tr>'
	.'<tr><td align="center">Sets:'
	._input(1,"sh",$setshometeam,4,2)
	._input(1,"sa",$setsawayteam,4,2)
	.'</td></tr><tr><td align="center">Legs:'
	._input(1,"lh",$legshometeam,4,2)
	._input(1,"la",$legsawayteam,4,2)
	.'</td></tr>';
	if ($LS_LEVEL>1) {$STROUT=$STROUT.'<tr><td align="center">'.Select_MatchStatus('mstatus',$match_status,'',0).'</td></tr>';}
	$STROUT=$STROUT.'</table>';
	return $STROUT;
}


/**
*	purpose:	retrieve darts,rest,avg for a specific player-match combination
* 	params:		gameID,playerID
*	returns:	HTML Table, colorized cells
* 	used by: 	_showmatch function....
*/
function LegTableDarts($leg_dist,$gid,$gppid){

	global $dbi;
	
	$legtable='';
	$aTH=array('Darts','Rest','Avg');
	$precord = sql_query('select ldarts,lscore from tblleg where lgid='.$gid.' and lpid='.$gppid.' ORDER by lid',$dbi);
	$legtable='<table cellpadding="1" cellspacing="1" border="0">'.ArrayToTableHead($aTH,'theadbold');
	
	while(list($darts,$score)=sql_fetch_row($precord,$dbi)){
		if ($score==$leg_dist) {
			$legtable=$legtable.'<tr><td class="legwon">'.$darts.'</td><td class="legwon">'.($leg_dist-$score).'</td><td class="legwon">'.number_format(($score/$darts),2,'.','').'</td></tr>';
		} else {
			// we get division by zero here ......
			$legtable=$legtable.'<tr><td>'.$darts.'</td><td>'.($leg_dist-$score).'</td><td>'.number_format(($score/$darts),2,'.','').'</td></tr>';			
		}
	 }
	return $legtable.'</table>';
}

function LegTableRounds($leg_dist,$gid,$gppid){
	global $dbi,$event;
	
	$legtable='';
	$aTH=array('Score','Rest','Check');
	$precord = sql_query('select lroundscore,lroundcheck,lscore from tbllegrounds where lgid='.$gid.' and lpid='.$gppid,$dbi);
	$legtable='<table cellspacing="1" border="0">'.ArrayToTableHead($aTH,'theadbold');

	while(list($rscore,$rcheck,$score)=sql_fetch_row($precord,$dbi)){
		if ($score==$leg_dist) {
			$legtable=$legtable.'<tr><td class="legwon">'.$rscore.'</td><td class="legwon">'.($leg_dist-$score).'</td><td class="legwon">'.$rcheck.'</td></tr>';
		} else {
			$legtable=$legtable.'<tr><td>'.$rscore.'</td><td>'.($leg_dist-$score).'</td><td>'.$rcheck.'</td></tr>';
					}
	 }
	return $legtable.'</table>';
}

function GetLegsWonThisGame($gid,$pid){
	# used by the _showmatch function....
	# if sgldarts than read from legtable ...
	# if sglrounds than read from legtable ...
	# if nothing read from tblgameplayer entries (result only ...)
	# v2 BH 04.2006
	global $dbi,$event;
	$resonly=1;
	if ($event['evsgldarts']==1){
		#$prec=sql_query("select count(*) C from tblleg where lgid=$gid and lscore=501 and lpid=$pid",$dbi);
		# v02 results are stored into tblgameplayer ....
		$prec=sql_query("select gplegswon from tblgameplayer where gpgid=$gid and gppid=$pid",$dbi);
		$won_count=sql_fetch_row($prec,$dbi);
		$resonly=0;
	}
	if ($event['evdbldarts']==1){
		$prec=sql_query('select count(*) C from tblleg where lgid='.$gid.' and lscore='.$event['evsgldist'].' and lpid='.$pid,$dbi);
		$won_count=sql_fetch_row($prec,$dbi);
		$resonly=0;
	}
	if ($event['evsglroundcheck']==1){
		# get from tbllegrounds or some other fields ....
		$prec=sql_query("select gplegswon from tblgameplayer where gpgid=$gid and gppid=$pid",$dbi);
		$won_count=sql_fetch_row($prec,$dbi);
		$resonly=0;
	}
	if ($event['evdblroundcheck']==1){
		# get from tbllegrounds or some other fields ....
		$resonly=0;
	}
	if ($resonly==1) {
		# this is the results only - get from tblmatchplayer ...
		$prec=sql_query("select gplegswon from tblgameplayer where gpgid=$gid and gppid=$pid",$dbi);
		$won_count=sql_fetch_row($prec,$dbi);
	}
	
	return $won_count[0];
}

function SelectPlayerFromTeam($nameid,$tid,$playerID=0,$passfield='pfkey1') {
	/*
	 * #TODO create API_RS entry for this ... or use existing ....
	 * returns a player select box from specific league - Lineup only with the passed player preselected
	 * select control name = vpid[]
	 * Param: $passfield = name of field containing the player card number
	 * v2.4 added #WO Player with PID=99, this player is added to every team ...
	 * v3	added player type = abgemeldet + join on playertype ...
	 * v4 removed eventid, added pfkey1 default
	 * v5 expanded player types according to ttypeplayer
	 */
	
	global $dbi,$user;
	$resx=sql_query("select pid,".$passfield.",plname,pfname,ltype from tblteamplayer left join tplayer on lplayerid=pid where lteamid=$tid order by plname,pfname asc",$dbi);
	$strret="<select class=\"lsdb\" id=\"$nameid\" name=\"$nameid\" size=\"1\">";
	$strret=$strret.'<option value="0">-- Player unknown/missing --</option>';
	if ($playerID == 99) {
		$strret=$strret.'<option value="99" selected="selected">-- #WO (nicht angetreten) --</option>';
	} else {
		$strret=$strret.'<option value="99">-- #WO (nicht angetreten) --</option>';
	}
	while (list($pid,$passn,$plname,$pfname,$ptyp)=sql_fetch_row($resx,$dbi)) {
		$pstring="$passn $plname $pfname";
		switch ($ptyp){
			case 3:
				$pstring=$pstring.' (Farmer)';break;
			case 5:
				$pstring=$pstring.' (Abgemeldet)';break;
			case 6:
				$pstring=$pstring.' (Gesperrt)';break;
		}
		if ($playerID == $pid) {
			$strret=$strret.'<option value="'.$pid.'" selected="selected">'.$pstring.'</option>';
		} else {
			$strret=$strret.'<option value="'.$pid.'">'.$pstring.'</option>';
		}
	}
	return $strret.'</select>';
}


function dsolog($lcode=1,$logname='nop',$logentry='Logentry') {
 #
 # mache einen logeintrag $lcode = 0,1,2,3,4,5 => severity gruen - rot in Anzeige
 #
	global $dbi,$clientIP;

	$d = getdate();
	$rtdate = $d['year'].'-'.$d['mon'].'-'.$d['mday'];
	$qry="insert into tdsolog values (0,$lcode,\"$logname\",\"$rtdate\",\"$logentry\",\"$clientIP\")";
	$res1 = sql_query($qry,$dbi);
}

function ssilog($lcode=1,$logname='nop',$logentry='Logentry') {
 #
 # mache einen logeintrag $lcode = 0,1,2,3,4,5 => severity grün - rot in Anzeige
 #
	global $dbi;
	
	$d = getdate();
	$rtdate = $d['year'].'-'.$d['mon'].'-'.$d['mday'];
	$res1 = sql_query("insert into tssilog values (0,$lcode,\"$logname\",\"$rtdate\",\"$logentry\")",$dbi);
}

function showssilog() {
	#
	# Display simple Table with log entries (last first) + purge button
	# Do not secure !!!
	#
	global $dbi;

    	echo "<h2>Syslog SSI SYSTEM</h2><p>Log entries are in reverse order - last entries at the top of table.</p>";
	$resx=sql_query("select * from tssilog order by lid desc",$dbi);
	OpenTable();
	echo "<table width=\"100%\" border=\"0\">";
	while(list($lid,$lcode,$luser,$ldate,$ltext)=sql_fetch_row($resx,$dbi)) {
		/* $lcode is numeric 1-5, can we use some indexed icons for this .... */
		echo "<tr><img src=\"images/".$lcode."log.png\"><td>$luser</td><td>$ldate</td><td>$ltext</td></tr>";
	}
	echo "</table><br>";
	#echo "<form action=\"ssi_func.php?func=purgelog\" method=\"post\"><input type=submit value=\"Alle Log Eintr&auml;ge l&ouml;schen\"></form>";
	CloseTable();
}

function showdsolog() {
	#
	# Display simple Table with log entries (last first) + purge button
	# Do not secure !!!
	#
	global $dbi;

    	echo "<h1>Syslog der Liga DB</h1><p>Log entries are in reverse order - current entries are at the top of table.</p>";
	$resx=sql_query("select * from tdsolog order by lid desc",$dbi);
	OpenTable();
	echo "<table width=\"100%\" border=\"0\">";
	while(list($lid,$lcode,$luser,$ldate,$ltext)=sql_fetch_row($resx,$dbi)) {
		/* $lcode is numeric 1-5, can we use some indexed icons for this .... */
		echo "<tr><td><img src=\"images/".$lcode."log.png\"></td><td>$luser</td><td>$ldate</td><td>$ltext</td></tr>";
	}
	echo "</table><br>";
	#echo "<form action=\"sip_func.php?func=purgelog\" method=\"post\"><input type=submit value=\"Alle Log Eintr&auml;ge l&ouml;schen\"></form>";
	CloseTable();
}

function purgedsolog() {
	global $user, $dbi;
	$resx=sql_query("delete from tdsolog where lid>1",$dbi);
	dsolog(3,$user,"LOG Table purged and cleaned.");
}

/**
 * generate mysql compliant datestring of current date
 * @return datestring
 */
function ls_getdate(){
	$d = getdate();
	return $d['year'].'-'.$d['mon'].'-'.$d['mday'];
}

/**
 * reformat the usual MYSQL DB Date format into Austrian Settings
 *
 * @param datestring $vdate
 * @param orderstring $DMY default='DMY'
 * @return datestring
 */
function retaustriandate($vdate,$DMY='DMY'){
 
	$j=substr($vdate,2,2);
	$m=substr($vdate,5,2);
	$d=substr($vdate,8,2);
	if ($DMY=='DMY'){
		return $d.".".$m.".".$j;
	}elseif ($DMY=='MY'){
		return $m.".".$j;
	}elseif ($DMY=='Y'){
		return $j;
	}
}

/**
 * Loads the titlestring into the h3-pagetitle
 *
 * @param string $titlestring
 * @return js scriptcode
 */
function setPageTitle($titlestring){
	return '<script type="text/javascript">$("#pagetitle").html("'.$titlestring.'");</script>';
}

/**
 * loads the TAB control into the pagetab-DIV
 *
 * @param html-tabstring $controlstring
 * @return js scriptcode
 */
function setPageControlTabs($controlstring){
	return '<script type="text/javascript">$("#pagetabs").html("'.$controlstring.'");</script>';
}

/**
 * returns true / false based on $data
 *
 * @param datestring $cdata
 * @return bool
 */
function check_date($cdata){
	# // returns true / false based on $data
	if (!isset($cdata) || $cdata=="") return false;
	list($yy,$mm,$dd)=explode("-",$cdata);
	if ($dd!="" && $mm!="" && $yy!="") return checkdate($mm,$dd,$yy);
	return false;
}

function die_red($msg){
	die('<font style="color:red;font-bold:true">'.$msg.'</font>');
}
function die_green($msg){
	die('<font style="color:green;font-bold:true">'.$msg.'</font>');
}
/****************************************************/
/* BEGINN PUBLIC SSI FUNCTIONS */
/* non public stuff goes into the func_stat library */
/****************************************************/

function MakeSSIChart($vpid) {
	# // create a nicely formatted graph with the stored SSI values
	# // this is a personal chart for a single player

	global $dbi;

	$srec=sql_query("select statdate,statval from tblstat where statcode=2 and statpid=$vpid order by statdate asc",$dbi);
	$OUT='<table><tr>';

	while (list($sipdate,$sippoints)=sql_fetch_row($srec,$dbi)){
		$hval=(($sippoints-1000)/6);
		$hval=number_format($hval,0,'.','');
		$OUT=$OUT.'<td valign="bottom" height="240px">'.number_format($sippoints,0,'.','').'<br><img src="images/grey128pix.gif" height="'.$hval.'px" width="20px"><hr>'.retaustriandate($sipdate,'MY').'</td>';
		}
	return $OUT.'</tr></table>';
}


function MakeSSICurve(){
# // this function draws a chart with all current SSI Values
# // in order to display a curve all values are deducted by 1000 (min possible val=1200)
# // and divided by 8 (this is just a random operator ...)

	global $dbi,$sctdcolor,$lastdate,$minsip;
	$srecords = sql_query("select statid,statdate,statval,statpid from tblstat where statcode=2 and statdate = '$lastdate' order by statval desc",$dbi);
	echo '<h3>SSI Verteilung</h3>';
	OpenTable();
	echo '<table border="0" cellspacing="0" cellpadding="0"><tr>';
	while(list($sipid,$sipdate,$sippoints,$sippid)=sql_fetch_row($srecords,$dbi)){
	    	$hval=(($sippoints-1000)/8);
		$hval=number_format($hval,0,'.','');
		echo '<td valign="bottom" height="240px"><img src="images/grey128pix.gif" height="'.$hval.'px" width="1px"></td>';
		sql_fetch_row($srecords,$dbi);
		} # // END WHILE LOOP
	echo '</tr></table>';
	CloseTable();
}

/**
*	purpose:	generate performance graph for a team
* 	params:		team_id
*	returns:	complete table with Google API graphics
*/
function getTeamAvgHist($vtid){
	# changed to google API and using the api_rs layer
	#TODO here we still are hardcoded to 501 playing ...
	global $dbi;
	$OUT="";
	$RS=DB_listTeamLineUp($dbi,$vtid);
	# TP.lid,T.id,T.tname,P.pid,P.pfname,P.plname,P.pfkey1,P.pfkey2,TY.TEXT,TP.leventid #
	foreach($RS as $row){
		$PlayerDat=DB_listLegStatAverageBreakdown($dbi,0,$row[9],'>0',501,$row[3]);
		# E.evname,P.pid,P.pfname,P.plname,M.mdate,L.ldarts,L.lfinish,(501-L.lscore),round((L.lscore/L.ldarts),2) AVG
		if (sizeof($PlayerDat)>0){
			$arAVG=array();
			foreach ($PlayerDat as $rowleg){$arAVG[]=round($rowleg[8])*2;}
			$imgURL='<img src="'._googleAVGChart('l',$arAVG).'"/><br/>';
			# to tu kurwa
			$OUT=$OUT.'<tr><td>'.mb_convert_encoding($rowleg[2], 'UTF-8').' '.mb_convert_encoding($rowleg[3], 'UTF-8').'</td><td>'.$imgURL.'</td></tr>';
		}
	}
	
	$RET= OpenTable('teamdata',1);
	$RET=$RET.$OUT;
	$RET=$RET.CloseTable(1);
	return $RET;
}
/**
*	purpose:	construct a IMG url for the google chart API
* 	params:		chart type, data
*	returns:	string URL
*/
function _googleAVGChart($chtype='l',$chData=array(50,25,75)){
	$chd="";
	foreach($chData as $val){$chd=$chd.$val.",";}
	$chd=substr($chd,0,strlen($chd)-1);
	return "http://chart.apis.google.com/chart?chs=300x200&chd=t:".$chd
	."&cht=lc&chxt=x,y&chxl=0:||1:||10|20|30|40|50&chg=0,20&chco=ff8888&chtt=Average+per+dart";
}

?>