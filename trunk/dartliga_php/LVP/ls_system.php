<?php
/* 
 * main framework for creating matchrecords / gamerecords / legrecords
* for different leaguesystems
* v01 BH 03.2006
* v02 BH using new access system
* v2.4 fixed bug in _createMatch, added global var $username
* v2.5 10.2006 BH 	fixed display in resultonly matches
*			fixed status update in resultonly matches
* v3.17 BH started support for tid in tblgameplayer, fixed leginput display
* v3.18 BH modifications to evid/id changes, fixed bug on leg-remove
* 		fixed pid passing to the legRow display
*/
	/*
	 * added support for the user.verein access layer
	 * LS_Level is the user.administrator (1,2,3)
	 * editmode is the user.verein (0,1)
	 */ 

include("ls_main.php");
require('func_match.php');

$LS_LEVEL=0;
$username='pub';

if (sizeof($usertoken)>5){
	$username=$usertoken['uname'];
	if (sizeof($usertoken['eventmap'])>0){$LS_LEVEL=$usertoken['eventmap'][$event_id];}
}
#debug($username."/".$LS_LEVEL);

function _schedule($eventid) {
	/*
	# // display nice schedule for an event - the list is clickable and leads to detailed matchresult / matchsheets
	# // sort order is done by the HOME / away flags of tblmatchteam
	# // works perfect v2 using the mkey as match link
	# // shows status ICON Green =0 OK, orange=1 part missing, red=0 all missing
	# // change v2 - 	user sees values only - rowclick takes user to matchdetails
	# //							admins have no rowclick but 2 Buttons - save result - edit details
	* v4 ==> admin save btn is a POST back , response is entire MatchRow.
	*/
	global $dbi,$sctdcolor,$redpic,$greenpic,$orangepic,$bluepic,$greencrosspic,$event,$LS_LEVEL;
	if (!$eventid>0) die ('E:LSDB1:NoEventSelected');
	
	if ($LS_LEVEL>1) {echo '<script type=\'text/javascript\' src=\'code/MatchCode.js\'></script>';}
	echo setPageTitle('Spielplan '.$event['evname']);
	echo '<div id=\'maincontent\'>';
	echo '<p>Jedes Match in diesem Spielplan funktioniert als Hyperlink und zeigt den detailierten Spielbericht an.</p>';
	
	$sSQL='SELECT mid,mkey,mround,mdate,mlocation,mstatus,mthome,mtlegs,mtsets,T.id,T.tname'
	.' FROM tblevent E,tblmatch M,tblmatchteam MT,tblteam T'
	.' WHERE E.ID='.$eventid.' AND E.ID=M.mevid and M.MKEY=MT.MTMKEY and MT.MTTID=T.ID'
	.' ORDER by M.MROUND,M.MKEY,MT.MTHOME desc';
	$precord = sql_query($sSQL,$dbi);
	$lastround=0;
	$lastmid=0;
	
	OpenTable();
	while(list($mid,$mkey,$mround,$mdate,$mlocation,$mstatus,$mthome,$mvlegs,$mvsets,$tid,$tname)=sql_fetch_row($precord,$dbi)){
		$jumptarget='ls_system.php?func=showmatch&vmkey='.$mkey.'&eventid='.$eventid;
		if ( $lastround<>$mround ) echo _roundhead($mround,10);
		if ($lastmid<>$mid) {
			#// Home TEAM
			if ($LS_LEVEL<2){
				// user view
				echo '<tr class="clickcell" onMouseOver="mover(this)" onMouseOut="mout(this)">';
			} else {
				// admin view
				echo '<tr bgcolor="white"><td><div id="trm'.$mid.'" name="trm'.$mid.'"><table><tr>' ;
			}
			echo '<td width="100px">';
			echo _editable_date( $mdate, $mid ).'</td><td width="140px" onclick=\'document.location="'.$jumptarget.'"\'>'.$mlocation.'</td>';
			echo '<td width="120px" onclick=\'document.location="'.$jumptarget.'"\'>'.$tname.'</td>';
			if ($LS_LEVEL>1){
					// admin controls
					echo '<td onclick=\'document.location="'.$jumptarget.'"\'>'._input(0,'mkey'.$mid,$mkey)._input(1,'sh'.$mid,$mvsets,3,2).'</td>';
					echo '<td onclick=\'document.location="'.$jumptarget.'"\'>'._input(1,'lh'.$mid,$mvlegs,3,2).'</td>';
			} else {
					echo '<td>'._input(2,'',$mvsets,2).'</td>';
					echo '<td>'._input(2,'',$mvlegs,3).'</td>';
			}
			echo '<td>:</td>';
		} else {
			# // AWAY TEAM
			if ($LS_LEVEL>1){
				// admin controls
				echo '<td onclick=\'document.location="'.$jumptarget.'"\'>'._input(1,'sa'.$mid,$mvsets,3,2).'</td>';
				echo '<td onclick=\'document.location="'.$jumptarget.'"\'>'._input(1,'la'.$mid,$mvlegs,3,2).'</td>';
			} else {
				echo '<td>'._input(2,'',$mvsets,2).'</td>';
				echo '<td>'._input(2,'',$mvlegs,3).'</td>';
			}
			echo '<td width=120px onclick=\'document.location="'.$jumptarget.'"\'>'.$tname.'</td>';
			echo '<td>';
			echo matchStatusToImage($mstatus);
			echo '</td>';
			if ($LS_LEVEL>1){
				// admin controls // '<image src=\'images/save.png\' align=\'left\'>' '<image src=\'images/detail.png\' align=\'left\'>'
				echo '</tr></table></div></td>';
				echo '<td>'._imgButton('save','saveMatch('.$mid.')').'</td>';
				echo '<td><form action=\'ls_system.php\' method=\'post\'>'
						._input(0,'func','showmatch')
						._input(0,'vmkey',$mkey)
						._input(0,'eventid',$eventid)
						._button('Detail').'</form></td>';
			}
			echo '</tr><tr><td colspan="10" align="right"><div id="msg'.$mid.'"></div></td></tr>';
		}

		$lastround=$mround;
		$lastmid=$mid;
	}
	CloseTable();
	echo '</div>'; #close maincontent-DIV
}
# Edit date for given match
function _edit_match_date( $match_id, $match_date ) {
	global $dbi, $LS_LEVEL;
	$update_result = sql_query( 'UPDATE tblmatch SET mdate = "'.$match_date.'" WHERE mid = '.$match_id, $dbi );
	if ( $update_result ) {
		return '[{<>}]ok_token[{<>}]';
	} else {
		return '[{<>}]failed_token[{<>}]';
	}
}
# Return an editable date
function _editable_date( $mdate, $mid ) {
	$date_arr = explode('-', $mdate);
	$ret = '<span id="matchDateSpan'.$mid.'">'.$mdate.'</span>';
	if ( $LS_LEVEL > 1 ) { //more then teamcaptain
		$ret = $ret.'<img height="15" style="padding-left: 3px; position: relative; top: 3px;" src="images/edit24.png" onclick="editMatchDateShowControls(\'editMatchDateControls'.$mid.'\');" />';
	}
	$ret = $ret.'<div id="editMatchDateControls'.$mid.'">'._input( 1, 'editMatchDateControlsYear'.$mid, $date_arr[0], 4, 4 ).' - ';
	$ret = $ret._input( 1, 'editMatchDateControlsMonth'.$mid, $date_arr[1], 2, 2 ).' - ';
	$ret = $ret._input( 1, 'editMatchDateControlsDay'.$mid, $date_arr[2], 2, 2 ).'<br/>';
	$ret = $ret._button( 'Commit change', 'editMatchDateCommitChange('.$mid.');' ).'</div>'; 
	return $ret.'<script> $("#editMatchDateControls'.$mid.'").hide(); </script>';
}

function _roundhead($mround,$colspan){
	// helper for the list displays
	global $sctdcolor;
	$thishead= "<tr style=\"height:1px\"><td align=\"center\" colspan=\"$colspan\" bgcolor=\"$sctdcolor\"></td></tr>";
	$thishead=$thishead. "<tr><td align=\"center\" colspan=\"$colspan\">Runde $mround</td></tr>";
	$thishead=$thishead. "<tr><td align=\"center\" colspan=\"$colspan\" bgcolor=\"$sctdcolor\"></td></tr>";
	return $thishead;
}

function _showmatch($vmkey,$eventid){
	#//select gid,ggameid,gmatchid,gvereinid,vname,gplayerid,pfname,plname from tblgame,tverein,tplayer where gvereinid=vid and gplayerid=pid
	# // display a typical matchcard
	# // adminmode = selectboxes and input fields
	# // usermode = readonly controls
	# // v1 bh 1.2006 initial version read only
	# // v2 using the matchkey and eventID
	# // v3 corrected gamewon counter to respect event - sgllegs/dbllegs
	# // v4 corrected bug in sending eventid to purge proc
	# // v4.1 changed code to use the input sub
	# // v4.2 added the vereins-access layer 
	# // v43 complete rework: changed to codesharing with the lsdb backend , added POSTback buttons
	
	global $dbi,$event,$redpic,$greenpic,$LS_LEVEL;
	$editmode=0;
	$thismatcheditmode=0;
	$hometeam=0;
	$awayteam=0;
	# // v4.2 team-access-layer ...
	# we dont give full access in here just make sure the DETAIL links are in place ... and the MatchSave does work
	$thismatcheditmode=retAccessThisMatchKey($vmkey);
	if ($LS_LEVEL > 1 || $thismatcheditmode==1) {
		$editmode=1; # (can be 2,3,4)
		echo '<script type="text/javascript" src="code/MatchCode.js"></script>';
		}
	echo setPageTitle('Resultat der Begegnung');
	echo '<div id="maincontent">';
	
	$precord = sql_query('select mid,mkey,mround,mdate,mlocation,mthome,mstatus,mcomment,T.id,T.tname'
	.' FROM tblmatch M,tblmatchteam MT,tblteam T'
	.' WHERE M.MKEY=MT.MTMKEY and MT.MTTID=T.ID and M.MKEY="'.$vmkey.'" order by MT.MTHOME desc',$dbi);
	$lastmid=0;
	$matchdate='';
	while(list($mid,$mkey,$mround,$mdate,$mlocation,$mthome,$mstatus,$mcomment,$tid,$tname)=sql_fetch_row($precord,$dbi)){
		if ( $lastmid<>$mid ) {
			$hometeam=$tid;
			$hometeamName=$tname;
		} else {
			$awayteam=$tid;
			$awayteamName=$tname;
		}
		$lastmid=$mid;
		$matchdate=$mdate;
		$match_round=$mround;
		$match_comment=$mcomment;
	}
	/*
	 * OUTPUT HEADER Section,make header box with 40:10:10:40
	 */
	OpenTable('mhead');
	echo '<tr><td colspan="4" align="center">'.$event['evname'].'Saison '.$event['evyear'].' Runde '.$match_round.'</td></tr><tr><td width="40%"><h3>'.$hometeamName.'</h3></td><td width="10%"></td>';
	echo '<td width="10%"></td><td width="40%"><h3>'.$awayteamName.'</h3></td></tr>';
	CloseTable();
	
	echo _input(0,'mkey',$vmkey)._input(0,'eid',$eventid).'<br/>';
	/*
	 * SECTION Explanations, legend, and Quicklinks ...
	 */
	if ($editmode==1){
		// this is accessible by LigaAdmins + Vereinsaccounts ...
		OpenTable('mcontrol');
		echo '<table><tr><td>-- Player unknown/missing -- </td><td>Dieser Eintrag ist zu w&auml;hlen wenn das Spiel stattgefunden hat, der betreffende Spieler jedoch nicht in der Teamliste oder im System aufscheint.</td></tr>';
		echo '<tr><td>-- #WO (nicht angetreten) --</td><td>Dieser Eintrag ist zu w&auml;hlen wenn kein Spieler anwesend war (zB Unterzahl). Das Spiel wird dann automatisch mit zu null f&uuml;r den anwesenden Gegner gewertet. Sind beide Spieler nicht anwesend, so sind beide mit WO zu markieren, dieses Spiel wird dann &uuml;berhaupt nicht gewertet.</td></tr>';
		echo '<tr><td colspan="2"><table width="100%"><tr>'
		.'<td width="50%" align="center">';
		echo renderWOMatchForm($eventid,$vmkey,$hometeam,$awayteam,$hometeamName);
		echo '</td><td width="50%" align="center"><form action="ls_system.php" method="post">';
		echo renderWOMatchForm($eventid,$vmkey,$awayteam,$hometeam,$awayteamName);
		echo '</td></tr></table></td></tr></table>';
		CloseTable();
		echo '<br/>';
	}
	
	/*
	 * REPLACE in v5 by reading the event.config.blocks
	 */
	OpenTable('mbody');
	# // ===============================================
	# // =========    SINGLES     ======================
	# // ===============================================
	$precord = sql_query('select gid,gmkey,gtype,gstatus,gpid,gppid,pfname,plname'
		.' FROM tblgame G,tblgameplayer GP left join tplayer P on GP.gppid=P.pid'
		.' WHERE G.gid=GP.gpgid and G.gmkey="'.$vmkey.'" and gtype=1 order by gid,gpid',$dbi);
	
	$lastgameid=0;
	while(list($gid,$gmkey,$gtype,$gstatus,$gpid,$gppid,$pfname,$plname)=sql_fetch_row($precord,$dbi)){
		
		if ( $lastgameid<>$gid ) {
			$PH=array($gid,$gmkey,$gtype,$gstatus,$gpid,$gppid,$pfname,$plname,$hometeam);
		}else{
			$PA=array($gid,$gmkey,$gtype,$gstatus,$gpid,$gppid,$pfname,$plname,$awayteam);
			echo '<tr><td><div id="trg'.$gid.'">';
			echo _LS_renderSingleGameRow($event,$editmode,$matchdate,$PH,$PA);
			echo '</div><div id="trged'.$gid.'"></div></td><td valign="top">';
			if ($editmode==1) {
				echo '<table cellpadding=0><tr><td>'._imgButton('save','saveGame('.$gid.')').'</td>';
				echo '<td>'._imgButton('edit','editGame('.$gid.')').'</td>';
				echo '<td>'._imgButton('detail','getGame('.$gid.')').'</td>'
				.'<td>'._imgButton('cancel','resetGame('.$gid.')').'</td></tr></table>';
			}
			echo '</td></tr><tr><td align="right"><div id="msg'.$gid.'"></div></td></tr>';	// close the game Table + Game DIV and the Tablerow
		}
		$lastgameid=$gid;
	}	// END WHILE singles ...
	
	
	# // ===============================================
	# // =========    PAIRS     ========================
	# // ===============================================
	#
	#
	$precord = sql_query('select G.gid,G.gmkey,G.gtype,G.gstatus,GP.gpid,GP.gppid,P.pfname,P.plname'
		.' FROM tblgame G,tblgameplayer GP left JOIN tplayer P on GP.gppid=P.pid'
		.' WHERE G.gid=GP.gpgid AND G.gmkey="'.$vmkey.'" and G.gtype=2 order by G.gid,GP.gpid',$dbi);
		
	$aAllGames=array();
	while(list($gid,$gmkey,$gtype,$gstatus,$gpid,$gppid,$pfname,$plname)=sql_fetch_row($precord,$dbi)){
		$aAllGames[]=array($gid,$gmkey,$gtype,$gstatus,$gpid,$gppid,$pfname,$plname);
	}	
	/*
	 * since we have pairs - we split Recordset into chunks of 4
	 */
	$aAllGames=array_chunk($aAllGames,4);
	foreach($aAllGames as $aThisGame){
		/*
		 * for every chunk we have this should be a pairs game 
		 * assign the team ID and => lets render
		 */
		$aThisGame[0][8]=$hometeam;$aThisGame[1][8]=$hometeam;
		$aThisGame[2][8]=$awayteam;$aThisGame[3][8]=$awayteam;
		$gid=$aThisGame[0][0];
		echo '<tr><td><div id="trg'.$gid.'">';
		echo _LS_renderPairsGameRow($event,$editmode,$matchdate,$aThisGame);
		echo '</div><br/><div id="trged'.$gid.'"></div></td><td valign="top">';
		if ($editmode==1) {
			echo '<table><tr><td>'._imgButton('save','saveGameD('.$gid.')').'</td>';
			#echo '<td>'._imgButton('edit','editGame('.$gid.')').'</td>';
			echo '<td>'._imgButton('detail','getGame('.$gid.')').'</td>';
			echo '<td>'._imgButton('cancel','resetGame('.$gid.')').'</td></tr></table>';
		}
		echo '</td></tr><tr><td align="right"><div id="msg'.$gid.'"></div></td></tr>';	// close the game Table + Game DIV and the Tablerow
	}
	
	/*
	 * Can we somehow have a summary ROW ??? with generated values ???
	 */
	$setshometeam=0;$setsawayteam=0;$legshometeam=0;$legsawayteam=0;
	#echo '<tr><td align="center"><h3>'.$setshometeam.':'.$setsawayteam.'<br/>('.$legshometeam.'):('.$legsawayteam.')</h3></td></tr>';
	CloseTable(); 
	/**
	 * =========== END of the Match Sheet Body ===========
	 **/
	
	/**
	 * =========== START Admin Section ===========
	 * a) create this match -->generates the empty tblgameplayer entries ...
	 * b) save this match --> updates the tblmatchteam with results and sets status according to the userType
	 */
	
	echo '<br/>';

	if ($LS_LEVEL>1 || $editmode==1) {
		// now this is for ADMINS only .....
		OpenTable('madmin');
		echo '<table><tr><td colspan=3>';
		echo '<div id="mcomment">';
		echo '<table><tr><td valign="top"><image src="images/detail.png" align="left">Speichere einen Kommentar</br>zu diesem Match (max 200)</td>';
		echo '<td valign="top">'._input(1,'f23',$match_comment,90).'</td>';
		echo '<td valign="top">'._button('Speichern','savemcomment()').'<div id="resp4"></div>';
		echo '</td></tr></table></div></td></tr><tr>';
		
		// render save section here in RW mode
		echo '<td valign="top" width="250px"><div id="msum">';
		echo _LS_renderSaveMatchBox($LS_LEVEL,$setshometeam,$setsawayteam,$legshometeam,$legsawayteam,0);
		echo '</div><div>'._button('1. Addieren','calcResult()')._button('2. Resultat Speichern','saveMatchA()').'</div>';
		echo '</td>';
		
		if ($LS_LEVEL>1) {
		// history button
			echo '<td valign="top" width="33%"><div id="mhist">';
			echo '<image src="images/detail.png" align="left">Lade die Bearbeitungs Historie f&uuml;r dieses Match. Die Anzeige erfolgt unterhalb.<br/>'._button('Show History','historymatch()');
			echo '</div>';
			echo '</td>';
		}
		if ($LS_LEVEL>2) {
		// delete button
			$STROUT= '<td valign="top" width="33%"><div id="mdel"><form action="ls_system.php" method="post">'
			._input(0,'func','purgematch')
			._input(0,'vmkey',$vmkey)
			._input(0,'eventid',$eventid)
			.'<table><tr><td><image src="images/stop.gif" align="left"><b>Achtung</b> Mit dem Button L&Ouml;SCHEN kannst du dieses Spiel neu anlegen, dabei werden s&auml;mtliche Spieldaten und Resultate <b>gel&ouml;scht</b>. Diese Aktion ist unwiederbringlich ...</td></tr>'
			.'<tr><td>'._button('L&ouml;schen').'</td></tr></table></form></div></td>';
			echo $STROUT;
		}
	
		echo '</tr></table>';
		CloseTable();
		
		echo '<br/><div id="hmatch"></div>';
	}
	
	/**
	 * =========== END of the Match ADMIN Section ===========
	 **/
	
	echo '</div>'; # close maincontent-div
}

function _savewomatch($vmkey,$eventid,$woteam,$winteam){

	# // this is called if an entire match is not played 
	# // set to max:0, max:0 and status = green
	# // there is no set or legdata in this case ...
	# // v3.1 BH 12.2006
	# // this can be called more than once .. etc toggle ...
	
	global $event,$dbi,$LS_LEVEL,$usertoken;

	$editmode=0;
	$editmode=retAccessThisMatchKey($vmkey);	
	if ($LS_LEVEL <2 && $editmode==0) die("<h3>E:LSDB365:NoAccess</h3>");
	
	#// calculate maximum legs x*singlemode + x*dblmode
	#// calculate maximum sets 
	$maxlegs=$event['evsingles']*(floor($event['evsgllegs']/2)+1)+$event['evdoubles']*(floor($event['evdbllegs']/2)+1);
	$maxsets=$event['evsingles']+$event['evdoubles'];
	$maxpoints=$event['evpointswin'];
	
	$sqlwinner="update tblmatchteam set mtlegs=$maxlegs,mtlegslost=0,mtsets=$maxsets,mtsetslost=0,mtpoints=$maxpoints"
	." WHERE mttid=$winteam and mtmkey='$vmkey' limit 1";
	$sqlloser="update tblmatchteam set mtlegs=0,mtlegslost=$maxlegs,mtsets=0,mtsetslost=$maxsets,mtpoints=0"
	." WHERE mttid=$woteam and mtmkey='$vmkey' limit 1";
	
	$presult=sql_query($sqlwinner,$dbi);
	if ($presult<>1) die("<h3>Event: (".$event['evname'].") Match: $vmkey Result Winner Update failed ...</h3>");
	$presult=sql_query($sqlloser,$dbi);
	if ($presult<>1) die("<h3>Event: (".$event['evname'].") Match: $vmkey Result Loser Update failed ...</h3>");
	
	# // we need to fill in the #WO players for the wo team - leave the winner team as it is ...
	# // get all games this match - cycle and insert WO-Player into the loser GPTID from tblgameplayer.
	$psql="select gid,gmkey,gpid,gpgid,gppid,gptid from tblgame,tblgameplayer where gid=gpgid and gmkey = '$vmkey' and gptid=$woteam order by gpgid";
	$pres=sql_query($psql,$dbi);
	while(list($gid,$gmkey,$gpid,$gpgid,$gppid,$gptid)=sql_fetch_row($pres,$dbi)){
		$sql="UPDATE tblgameplayer set gppid=99 where gptid=$woteam and gpgid=$gid limit 2";
		$qUPD=sql_query($sql,$dbi);
	}
	# // tested both with old schema and new schema including the tid values....
	
	if ($usertoken['usertype_id']<3) {
		$matchstatus=5;	//submitted
	} else {
		$matchstatus=2;
	}
	
	$sql="update tblmatch set mstatus=$matchstatus where mkey='$vmkey' limit 1";
	$presult=sql_query($sql,$dbi);
	if ($presult<>1) die("<h3>Event: (".$event['evname'].") Match: $vmkey Status Update failed ...</h3>");
	dsolog(1,$usertoken['uname'],'#WO Result for Match '.$vmkey.' saved or submitted');
	
	/*
	 * in case this is either a teamcaptain or a verein account we inform the Admins ...
	 */
	if ($usertoken['ttypeuser_id']<3) {
		$msg=$event['evname'].' Result -WO Match - submitted by Verein '.$usertoken['uname']. '- please review using the system link below.';
		$url='ls_system.php?func=showmatch&vmkey='.$vmkey.'&eventid='.$eventid;
		$ret=DB_setMessage($usertoken['uname'],1,1,$msg,$url,$event['mgroup_id']);
		if ($ret<>1) die('<h3>Event: ('.$event['evname'].') Match: $vmkey Request for approval could not be sent.</h3>');
		_sendpendingmails();
	}

}

function _makenewmatch($eventid){
	#
	# called by the MakeMatch Button from navigation
	# bring up a Matchsheet according to the event rules ....
	# nr of singles + modus
	# nr of pairs + modus
	#
	
	global $event,$dbi,$LS_LEVEL;
	if ($LS_LEVEL<2) die_red('E:LSDB23:NoAccess');
	#
	# TODO here we need the access MATRIX from the configuration .....
	#
	echo setPageTitle('Spielbericht anfordern oder erstellen f&uuml;r '.$event['evname']);
	echo '<div id=\'maincontent\'>';
	OpenTable('newmatch');
	echo "<form  action=\"ls_system.php?func=initmatch\" method=\"post\">";
	echo include('forms/match.php');
	echo '</form>';
		
	CloseTable();
	echo '<p>Mit dem <b>Erstellen</b> Button wird das Match angelegt und mit dem eingegebenen Resultat abgespeichert. Anschliessend wird der angeforderte Spielbericht angezeigt um zB Details zu den Legs zu erfassen</p>';
	echo '</div>';
}

function _deleteMatchCascading($vmkey,$eventid){
	# 
	# Mainly for debug functionality, this deletes all records associated with a Match
	# cleans :  tblleg, tblgameplayer, tblgame, tblmatch, tblmatchteam
	# SECURITY !!!!!
	# // set delete gid limit to 120 because of wrongly created stuff ... can we block this with a DB-Rule ???
	global $dbi,$LS_LEVEL,$username;
	if ($LS_LEVEL<3) die('<h3>E:LSDB24:NoAccess</h3>');
	
	$presult=sql_query("select gid,gtype from tblgame where gmkey='$vmkey' order by gid asc",$dbi);
	while(list($gid,$gtype)=sql_fetch_row($presult,$dbi)){
		$res1=sql_query("DELETE from tblleg where lgid=$gid limit 7",$dbi);	# count of legs
		$res1=sql_query("DELETE from tbllegrounds where lgid=$gid limit 7",$dbi);	# count of legs
		$res1=sql_query("DELETE from tblgameplayer where gpgid=$gid limit 4",$dbi);	# pairs=4 players
	}
	
	$res1=sql_query("DELETE from tblgame where gmkey='$vmkey' limit 120",$dbi);			# max of 12 games
	$res1=sql_query("DELETE from tblmatchteam where mtmkey='$vmkey' limit 2",$dbi);		# 2 entries
	$res1=sql_query("DELETE from tblmatch where mkey='$vmkey' limit 1",$dbi);			# 1 match
	dsolog(2,$username,"Match $vmkey purged completely");
}

function _initialiseMatch($eventid,$rnum,$h_team,$a_team,$vdate,$m_loc){
	global $dbi,$sipgoback,$LS_LEVEL;

	if ($LS_LEVEL<2) die_red('Err439:No access to this matchinit()');

	# // check if htid=atid !!!
	if ($h_team==$a_team) die_red('Err442:Es m&uuml;ssen 2 unterschiedliche Teams gegeneinander spielen '.$sipgoback);
	
	$mkey="e".$eventid."r".$rnum."h".$h_team."a".$a_team;
	
	$resx=sql_query("select * from tblmatch where mkey=\"$mkey\"",$dbi);
	if(sql_num_rows($resx, $dbi)==1) {
		die_red('Err449:MatchExists');
	} else {
		_createMatch($mkey,$eventid,$rnum,array($h_team,$a_team),$vdate,$m_loc);
	}
	return $mkey;
}

function _createMatch($mkey,$eventid,$rnum,$vtid,$vdate,$m_loc){
	# create entries for
	# tblmatch, tblmatchteam,tblgame,tblgameplayer
	# tblleg no entries !!!
	# location = home team
	# MatchDate = $vdate
	# based on the GLOBAL event config
	global $dbi,$LS_LEVEL,$username,$event;
	if ($LS_LEVEL <2 ) die_red('Err463:NoAccess ...');
	# where is the location of the HomeTeam ??

	$resx=sql_query("select T.id,T.tlocation_id,L.lname from tblteam T,tbllocation L where T.tlocation_id=L.id and T.id=$vtid[0]",$dbi);
	$row=sql_fetch_array($resx,$dbi);

    $resloc=sql_query("select id,lname from tbllocation where id=$m_loc", $dbi);
    $rowloc=sql_fetch_array($resloc,$dbi);
	$loc=$rowloc[lname];
    $locid=$rowloc[tlocation_id];

	$strsql="INSERT INTO tblmatch(mid,mkey,mevid,mround,mhtid,matid,mdate,mlocation,mstatus,mactive) values(0,\"$mkey\",$eventid,$rnum,$vtid[0],$vtid[1],\"$vdate\",\"$loc\",0,1)";
	#debug($strsql);
	$resx=sql_query($strsql,$dbi);
	if ($resx<>1) die_red('Err480:Event: ('.$event['evname'].') Match: '.$mkey.' Creation of Match Entry failed');
	# ok now we create the 2 matching records for match - team, match - team ...
	# make sure we have values instead of NULL ...
	if (!$vlegs[0]) {
		$vlegs[0]=0;
		$vlegs[1]=0;
	}
	if (!$vsets[0]) {
		$vsets[0]=0;
		$vsets[1]=0;
	}
	if (!$vpoints[0]) {
		$vpoints[0]=0;
		$vpoints[1]=0;
	}
	$strsql="INSERT into tblmatchteam(mtid,mtmkey,mthome,mttid,mtlegs,mtlegslost,mtsetslost,mtsets,mtpoints) values(0,'$mkey',1,$vtid[0],$vlegs[0],$vlegs[1],$vsets[1],$vsets[0],$vpoints[0])";
	$resx=sql_query($strsql,$dbi);
	$strsql="INSERT into tblmatchteam(mtid,mtmkey,mthome,mttid,mtlegs,mtlegslost,mtsetslost,mtsets,mtpoints) values(0,'$mkey',0,$vtid[1],$vlegs[1],$vlegs[0],$vsets[0],$vsets[1],$vpoints[1])";
	$resx=sql_query($strsql,$dbi);
	# finally we create the number of required singel and pairs GAME records ...
	# read from config ==> use global event ARRAY ??????????
	# $resx=sql_query("select E.evsingles,E.evdoubles,E.evsgllegs,E.evdbllegs from tblevent E where E.id=$eventid",$dbi);
	# $row=sql_fetch_array($resx,$dbi);
	$iSingle=$event['evsingles'];
	$iPair=$event['evdoubles'];
	$iLegSingle=$event['evsgllegs'];
	$iLegPair=$event['evdbllegs'];
	# for every single we create 1 game entry and ?? legentries ....
	for ($i = 1;$i<=$iSingle;$i++){
		$strsql="INSERT INTO tblgame(gid,gmkey,gtype,gsets,glegs,gstatus) values (0,\"$mkey\",1,1,$iLegSingle,0)";
		$resx=sql_query($strsql,$dbi);
	}
	for ($i = 1;$i<=$iPair;$i++){
		$strsql="INSERT INTO tblgame(gid,gmkey,gtype,gsets,glegs,gstatus) values (0,\"$mkey\",2,1,$iLegPair,0)";
		$resx=sql_query($strsql,$dbi);
	}
	# // the gameentries are done lets create some fake tblgameplayer entries
	# // gpid | gpgid | gppid | gpsetwon | gplegswon
	# // ver 3.17 storing the teamID in the tblgameplayer entry (used for the WO functionality)
	$presult=sql_query("select gid,gtype from tblgame where gmkey=\"$mkey\" order by gid asc",$dbi);
	while(list($gid,$gtype)=sql_fetch_row($presult,$dbi)){
		$i=0;
		# // it type = 1 single then 2 entries, for type=2 pairs create 4 entries
		$thisTeam=0;
		for ($i=0;$i<($gtype*2);$i++){
			if ($gtype==1){
				if ($i==0) $thisTeam=$vtid[0];
				if ($i==1) $thisTeam=$vtid[1];
			} elseif($gtype==2) {
				if ($i<2) $thisTeam=$vtid[0];
				if ($i>1) $thisTeam=$vtid[1];
			}
			$res1=sql_query("INSERT into tblgameplayer(gpid,gpgid,gppid,gpsetwon,gplegswon,gptid) values(0,$gid,0,0,0,$thisTeam)",$dbi);
			# // we leave the tid entry empty here - this is filled when the player is assigned ...
		}
	}
	# // we do not create ANY LEGENTRIES - these are created by the game-edit-save page on demand ...
	dsolog(1,$username,'Match '.$mkey.' initialised');
}

function _MakeWinnerEntrySingle($vpid,$vgid){
	//
	// called by _savegame if #WO FLAG is on ....
	// make a direct win 3:0 / 2:0 entry for this game and player
	// no SSI link in here !!!
	//
	global $event,$dbi,$LS_LEVEL,$username;
	
	$wonlegs=floor($event['evsgllegs']/2)+1;
	// reset game ... SINGLES ....
	$sqlquery="UPDATE tblgameplayer set gplegswon=0 where gpgid=$vgid limit 2";	
	$res1 = sql_query($sqlquery,$dbi);	
	$sqlquery="UPDATE tblgameplayer set gplegswon=$wonlegs where gppid=$vpid and gpgid=$vgid limit 1";
	$res1 = sql_query($sqlquery,$dbi);

	dsolog(1,$username,"SAVED: Singles Game $vgid #WO Winner=$vpid");
}

function _MakeWinnerEntryPairs($vpid,$vgid){
	//
	// called by _savegame if #WO FLAG is on ....
	// make a direct win 3:0 / 2:0 entry for this game 
	// for clarity this is a seperate proc than the _single
	// ?? executed but no effect ????????
	global $event,$dbi,$LS_LEVEL,$username;
	
	$wonlegs=floor($event['evdbllegs']/2)+1;
	// reset game ... PAIRS ....
	$sqlquery="UPDATE tblgameplayer set gplegswon=0 where gpgid=$vgid limit 4";	
	$res1 = sql_query($sqlquery,$dbi);
	#dsolog(1,$username,$sqlquery);	
	$sqlquery="UPDATE tblgameplayer set gplegswon=$wonlegs where gppid=$vpid and gpgid=$vgid limit 1";
	$res1 = sql_query($sqlquery,$dbi);
	#dsolog(1,$username,$sqlquery);	
	dsolog(1,$username,"SAVED: Pairs Game $vgid #WO Winner=$vpid");
}

function _submitMatchResult($vmkey,$eventid,$vtid,$vsets,$vlegs){
		/*
		 * unlike the saveMatch function this is triggered by non-priviledged users
		 * here we have only calculated values - no check save as they are ...
		 * TODO -> this is 60% identical to _saveMatch function ...
		 * TODO -> check if this can be replaced by the axEditor ...
		 * + saveresults status GreenRing ($$greencrosspic) and send message URL
		 */
	global $event,$dbi,$LS_LEVEL,$usertoken;
	
	$editmode=0;
	$editmode=retAccessThisMatchKey($vmkey);	
	if ($LS_LEVEL <2 && $editmode==0) die("<h3>No access ...$editmode</h3>");
	
	# // save values into the match-team-assignment table
	$ret=DB_UpdateMatchTeamResults($vmkey,$eventid,$vtid,$vsets,$vlegs);
	if ($ret<>2) die("<h3>Error saving Match result values...</h3>");
	
	// JUST set STATUS to SUBMITTED
	$ret=DB_setMatchStatus($vmkey,5);
	if ($ret<>1) die('<h3>Event: ('.$event['evname'].') Match: '.$vmkey.' Status Update failed ...</h3>');
	
	dsolog(1,$usertoken['uname'],'Result '.$vsets[0].':'.$vsets[1].' for Match $vmkey submitted for review');
	
	// MESSAGE BUS
	if ($usertoken['ttypeuser_id']<3){
		$msg=$event['evname'].' Result '.$vsets[0].':'.$vsets[1].' submitted by Account '.$usertoken['uname'].' - please review using the system link below.';
		$url='ls_system.php?func=showmatch&vmkey='.$vmkey.'&eventid='.$eventid;
		$ret=DB_setMessage($usertoken['uname'],1,1,$msg,$url,$event['mgroup_id']);
		if ($ret<>1) die('<h3>Event: ('.$event['evname'].') Match: '.$vmkey.' Request for approval could not be sent.</h3>');
	}
	_sendpendingmails();
}

function _blank(){
	
	echo setPageTitle('Liga System LSDB, ein Informationsservice des Dartsverband');
	echo '<div id=\'maincontent\'>';
	echo '<p>Die von dir gew&auml;hlte Funktion ist noch nicht verf&uuml;gbar ...</p>';
	echo '</div>';
}



if (isset($_REQUEST['func'])&& $_REQUEST['func']<>"undefined") {$myfunc=strip_tags($_REQUEST['func']);}else{$myfunc='NULL';};
if (isset($_REQUEST['vmkey'])&& $_REQUEST['vmkey']<>"undefined") {$match_key=strip_tags($_REQUEST['vmkey']);}else{$match_key='NULL';};
if (isset($_REQUEST['woteam'])&& is_numeric($_REQUEST['woteam'])) {$wo_team=strip_tags($_REQUEST['woteam']);}else{$wo_team=0;};
if (isset($_REQUEST['winteam'])&& is_numeric($_REQUEST['winteam'])) {$win_team=strip_tags($_REQUEST['winteam']);}else{$win_team=0;};
if (isset($_REQUEST['hteam'])&& is_numeric($_REQUEST['hteam'])) {$h_team=strip_tags($_REQUEST['hteam']);}else{$h_team=0;};
if (isset($_REQUEST['ateam'])&& is_numeric($_REQUEST['ateam'])) {$a_team=strip_tags($_REQUEST['ateam']);}else{$a_team=0;};
if (isset($_REQUEST['rnum'])&& is_numeric($_REQUEST['rnum'])) {$r_num=strip_tags($_REQUEST['rnum']);}else{$r_num=1;};
if (isset($_REQUEST['vdate'])) {$v_date=strip_tags($_REQUEST['vdate']);}else{$v_date='1901-01-01';};
if (isset($_REQUEST['mlocation'])) {$m_loc=$_REQUEST['mlocation'];}else{$m_loc=null;};
if (isset($_REQUEST['matchId'])&& $_REQUEST['matchId']<>"undefined") {$match_id=strip_tags($_REQUEST['matchId']);}else{$match_id='NULL';};
if (isset($_REQUEST['matchDate'])&& $_REQUEST['matchDate']<>"undefined") {$match_date=strip_tags($_REQUEST['matchDate']);}else{$match_date='NULL';};

switch($myfunc) {

	default:
	_blank();
	break;

	case "browseteams":
    	_browseteams($event_id);
    	break;
	
	case "schedule":
		_schedule($event_id);
		break;
	
	case "showmatch":
		_showmatch($match_key,$event_id);
		break;
	
	case "newmatch":
		_makenewmatch($event_id);
		break;
	
	case "purgematch":
		if (strlen($match_key)>5) _deleteMatchCascading($match_key,$event_id);
		break;
	
	case "initmatch":
		$newmatchkey=_initialiseMatch($event_id,$r_num,$h_team,$a_team,$v_date,$m_loc);		# check if exist - else create
		_showmatch($newmatchkey,$event_id);
		break;
	
	case "editgame":
		_savegame($match_key,$vgid,$event_id,$vpid,$vgpid);		#// saves the game-player assignments
		_showgame($match_key,$vgid,$event_id);			#// show game edit dialogue
		break;
	
	case "addleg":
		# // add 1 leg to specified player, than jump back to edit page
		_addleg($match_key,$vgid,$vpid);
		_showgame($match_key,$vgid,$event_id);
		break;
	
	case "removeleg":
		# // remove 1 leg and show match again
		_removeleg($match_key,$vgid,$vpid,$vlid);
		_showgame($match_key,$vgid,$event_id);
		break;

	case "savelegs":
		if (!$_SERVER['REQUEST_METHOD']=='POST') die("ERROR save Leg");
		_savelegs($match_key,$event_id,$vlid,$vlgid,$vlpid,$vlstart,$vldarts,$vlrdscore,$vlrdcheck,$vlrest,$vlfinish,$vlhighscore,$vlegswon);
		_showmatch($match_key,$event_id);
		break;

	case "savematch":
			// called from the matchsheet by user.administrators only
			// called from the scheduleview
		_savematch($match_key,$event_id,$vtid,$vsets,$vlegs);	# // saves into tblmatchverein
		_schedule($event_id);
		break;
	case "submitmatch";
		// called by the user.verein saveMatch Form
		_submitMatchResult($match_key,$event_id,$vtid,$vsets,$vlegs);
		_schedule($event_id);
		break;
		
	case "womatch":
		// called from the matchsheet
		_savewomatch($match_key,$event_id,$wo_team,$win_team);
		_schedule($event_id);
		break;
		
	case "editdate":
		// called from the js_func.js
		echo _edit_match_date( $match_id, $match_date );
		break;
}

# just in case we close main div
echo '</div>';
LS_page_end();

?>
