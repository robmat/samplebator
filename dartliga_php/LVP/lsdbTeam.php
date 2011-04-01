<?php
/**
*	purpose:	team manipulation interface
* 	params:		
*	returns:	VIEWS
* 	based on the ls_team original php only page...
*/

include('ls_main.php');
require_once('ORM/team.php');
global $event;

$teamcode='lsdbTeam.php';
$TEAM_LEVEL=0;
if(sizeof($usertoken)>5){
	if(sizeof($usertoken['eventmap'])>0){
		$TEAM_LEVEL=$usertoken['eventmap'][$event['id']];
	}
}

echo '<script type=\'text/javascript\' src=\'code/axTeamCode.js\'></script>';
echo '<div id=\'maincontent\'>';

function _editteam($eventid,$vtid=0){
	#
	# basic interface to ADD a team to an event ....
	# display textelemt + 2 select boxes + eventselect(default) + save button
	# tblstruct: tid | tname               | tvid | tlocid
	global $dbi,$TEAM_LEVEL,$teamcode;
	if ($TEAM_LEVEL<3) die('<h3>E:T24:NoAccess</h3>');
	
	$qry='select T.id,T.tname,T.tverein_id,T.tlocation_id,T.tevent_id from tblteam T where T.id='.$vtid;
	$precord = sql_query($qry,$dbi);
	if ($vtid>0){
		$aREC=mysql_fetch_array($precord);
	} else {
		$aREC['id']=0;
		$aREC['tname']='Teamname';
		$aREC['tverein_id']=0;
		$aREC['tlocation_id']=0;
		$aREC['tevent_id']=$eventid;
	}
	// OUTPUT //
	if ($vtid>0){
		echo setPageTitle('Team Bearbeiten, alle Ligen.');
	}else{
		echo setPageTitle('Neues Team erstellen, alle Ligen.');
	}
	echo '<div id=\'maincontent\'><div class=\'master\'>';
	echo '<p>Ein Ligawechsel eines Teams in der laufenden Saison f&uuml;hrt zu Fehlern in den Spielberichten. Das Team <b>kann und sollte nur</b> in eine andere Liga (Event) verschoben werden wenn es irrt&uuml;mlich erzeugt wurde !! Ein Team kann nur in den f&uuml;r dich <b>berechtigten</b> Ligen angelegt, kopiert oder verschoben werde.</p>';
	echo '<p>Ein Vereins Wechsel eines Teams / Mannschaft erzeugt <b>keine</b> Mitgliedschaften im Meldewesen, diese m&uuml;ssen eigens gepflegt werden.</p>';
	OpenTable('formteam');
	echo "<form action=\"$teamcode?func=saveteam&amp;vtid=$vtid\" method=\"post\">";
	echo form_Team($aREC);
	echo '</form>';
	CloseTable();
	echo '</div>';	#close master-div
	
	# only load lineup stuff if teamID>0 and not NEW
	if ($vtid>0){
		#echo "<div class=\"axmonitor\"><table bgcolor=\"#cccccc\" cellpadding=2 cellspacing=1><tr>"
		#."<td bgcolor=\"white\" id=\"getActivity\"><i>Ready</i></td>"
		#."<td bgcolor=\"white\" id=\"saveActivity\"><i>Ready</i></td>"
		#.'</tr></table></div><br/>';
		echo '<h3>Aktuelle Aufstellung</h3><div class=\'child\'>';
		echo '<p>Die jeweilige Teamaufstellung wird mit den Add/Remove Buttons direkt manipuliert und muss nicht eigens gespeichert werden.</p>';
		echo '<div id=\'lineUp\'></div></div>';
		
		echo '<h3>Aufstellung erweitern</h3><div class=\'child\'>';
		echo form_SearchPlayer('searchplayer("addplayer")');
		echo '<div id=\'qry\'></div></div>';
		echo '<script>getadmlineup('.$vtid.')</script>';
		
		echo '<h3>Spieler Check</h3><div class=\'child\'>';
		echo '<div id=\'check\'></div></div>';
	}
	echo '</div>'; #close main-div
}

function _createteamfromoldteam($eventid,$vtid){
	# // param vtid is the team we want to clone
	# // show controls: teamname, LEAGUE, LOCATION
	global $TEAM_LEVEL,$dbi,$teamcode;
	if ($TEAM_LEVEL<3) die('<h3>E:Team:NoAccess</h3>');

	$prec=sql_query('select T.id,T.tname,T.tverein_id,T.tlocation_id,T.tevent_id from tblteam T where T.id='.$vtid,$dbi);
	$aREC=mysql_fetch_array($prec);

	echo setPageTitle('Team mit allen Spielern klonen');
	echo '<div id=\'maincontent\'>';
	
	echo '<p>Diese Funktion erzeugt ein <b>neues</b> Team inklusive aller Spieler als 1:1 Kopie und ordnet dieses Team einer neuen Liga zu. Die alte Zuordnung und auch die Ergebnisse sind davon nicht betroffen. Willst du ein Team <b>ohne</b> Spieler erzeugen, so verwende die Schaltfl&auml;che <i>Neues Team</i>.</p>';
	OpenTable('tmaster');
	echo "<form action=\"$teamcode?func=cloneteam&amp;vtid=$vtid\" method=\"post\">";
	echo form_Team($aREC);
	echo '</form>';
	CloseTable();
	echo '</div>'; #close main-div
}

function _copyplayers($eventid,$voldteam,$vtname){
	// simple copy function
	// retrieve players from old team and add to new team ....
	global $TEAM_LEVEL,$dbi,$pgreenpic,$predpic;
	if ($TEAM_LEVEL<3) die("<h3>No access ....</h3>");
	echo "<h3>OLD Team = $voldteam</h3>";
	// ?????????? uhuhuh how do we get the new TEAMID ????
	// must be a unique combination of event and name (no id as yet ...)
	$prec=sql_query("Select id,tname from tblteam where tevent_id=$eventid and tname=\"$vtname\"",$dbi);
	$newTeam=sql_fetch_array($prec,$dbi);
	
	echo "<h3>NEW Team = ".$newTeam['id']."</h3>";
	$vnewteam=$newTeam['id'];
	if ($vnewteam==0) die("Error :: New Team not found ...($prec)");
	echo "<p>Anstehend wird eine Liste aller transferierten Spieler angezeigt, GR&Uuml;N bedeutet erfolgreich, ROT = fehlgeschlagen</p>";
	
	// select from tblteamplayer, - insert into tblteamplayer ...
	// select only regular players with type>0 ...
	$prec=sql_query("select lteamid,lplayerid,ltype,pfname,plname,pfkey1,ppassnr from tblteamplayer left join tplayer on lplayerid=pid where lteamid=$voldteam and ltype>0",$dbi);
	
	Opentable();
	while(list($lteamid,$lplayerid,$ltype,$pfname,$plname,$pfkey1,$ppassnr)=sql_fetch_row($prec,$dbi)){
		echo "<tr><td>$lplayerid</td><td>$pfname</td><td>$plname</td><td>$ppassnr / $pfkey1</td>";
		// INSERTS ...unqid,event,team,player,active

		$qry="insert into tblteamplayer values(0,$eventid,$vnewteam,$lplayerid,1,$ltype)";
		$pqry=sql_query($qry,$dbi);
		if ($pqry==1) {
			echo "<td>$pgreenpic</td></tr>";
		} else {
			echo "<td>($pqry)=$predpic</td></tr>";
		}
		#debug($qry);
	}
	CloseTable();

}

function _saveTeam($team_id,$event_id,$team_name,$verein_id,$location_id){
	#
	# called by create/EDIT team -- INSERT / UPDATE
	#
	global $dbi,$usertoken,$TEAM_LEVEL,$sipgoback;
	if (!$TEAM_LEVEL>2) die('<h3>E:T22:NoAccessThisLeague</h3>');
	if (!$event_id>0) die('Team must be assigned to an event ... <br/>'.$sipgoback);
	if (!$team_name) die('Team Name is missing ... <br/>'.$sipgoback);
	if ($verein_id==0){$verein_id="NULL";}
	if ($location_id==0){$location_id="NULL";}
	
	$cT=new cTeam;
	$cT->setDB($dbi);
	$cT->aDATA['id']=$team_id;
	$cT->aDATA['tname']=$team_name;
	$cT->aDATA['tlocation_id']=$location_id;
	$cT->aDATA['tverein_id']=$verein_id;
	$cT->aDATA['tevent_id']=$event_id;
	$cT->save();

	/*
	 * make sure a possible eventchange is propagated to the lineup
	 */
	$strsql="UPDATE tblteamplayer set leventid=$event_id where lteamid=$team_id limit 20";
	$precord = sql_query($strsql,$dbi);
}

function _browseteams($eventid){
	
	# zeigt einen Table mit einer Vereins / SpielerListe an
	# evtl Teil des ADMIN Backends ???
	# // bugfix 22.10.2006 changed $lasttname to $lastTID
	global $event,$dbi,$sctdcolor,$predpic,$pgreenpic,$TEAM_LEVEL,$teamcode;
	$editmode=0;
	if ($TEAM_LEVEL>2) $editmode=1;
	echo setPageTitle('Mannschaften und ihre Aufstellungen, '.$event['evname']);
	echo '<div id=\'maincontent\'>';
	echo '<p>Ranglistenwerte vom '.RetStatDateForStatCode($event['evstatcode_id']).'</p>';
	OpenTable();
		
	# query the LineUp - show current and recent players, omit non-oedv players - list all players with current STATISTC values ...
	# no left join used, player do not show up without ANY SSI Value !!!
	$precord = sql_query('select T.tevent_id,T.id,T.tname,TP.lplayerid,P.pfname,P.plname,P.'.$event['evpassfield'].',TP.ltype,TYP.text'
	.' FROM tblteam T left join tblteamplayer TP on T.id=lteamid left join tplayer P on TP.lplayerid=P.pid left join ttypeplayer TYP on TP.ltype=TYP.id'
	.' WHERE T.tevent_id='.$event['id'].' order by T.id,TP.lid;',$dbi);
	
	$lastTID=0;
	$tcount=0;	# teamcounter used to create new TR row after 2 teams
	$pcount=0;	# playercounter within a team pcount=1 => captain of team ....
	while(list($tevevid,$tid,$tname,$pid,$pfname,$plname,$ppassnr,$ltype,$ttext)=sql_fetch_row($precord,$dbi)){
		$statval=0;
    	if ($lastTID<>$tid) {
			$tcount=$tcount+1;
			$pcount=0;
			if ($tcount % 2 > 0) {
				# ok lets start a new row - there are 2 teams per row ....
				if ($tcount==1) echo '<tr><td valign=top><table width=100%>';
				if ($tcount>1) echo '</table></td></tr><tr><td valign=top><table width=100%>';
			} else {
				# second verein same row --> new cell
				echo '</table></td><td valign=top><table width=100%>';
			}
	    	echo '<tr><td valign=\'top\' class=\'thead\' colspan=\'4\'>'.$tname.'</td></tr>';
			if ($editmode){
				echo '<tr><td colspan=\'4\' id=\'teamctl'.$tid.'\'><table width=100%><tr>'
				.'<td>'._button('Edit','',$teamcode.'?func=editteam&eventid='.$tevevid.'&vtid='.$tid).'</td>'
				.'<td>'._button('Copy','',"$teamcode?func=team2team&vtid=$tid&eventid=$tevevid").'</td>'
				.'<td>'._button('L&ouml;schen','',"$teamcode?func=delete&vtid=$tid&eventid=$tevevid").'</td>'
				.'</tr></table></td></tr>';
			}
	    	echo '<tr><td width="50px"></td><td><b>PassNr</b></td><td><b>Name</b></td><td><b>Wert/Index/Kommentar</b></td></tr>';
    	}
    	
		$pcount=$pcount+1;
		# // here comes the player number, switch on event config
		if ($ltype==1){
			echo '<tr><td width=\'50px\'></td><td>'.$ppassnr.'</td><td>'.$pfname.' '.$plname.'</td>';
		}elseif ($ltype>4){
			echo '<tr><td width=\'50px\'></td><td>'.$ppassnr.'</td><td style=\'color:red\'>'.$pfname.' '.$plname.' ('.$ttext.')</td>';
		}else {
			echo '<tr><td width=\'50px\'></td><td>'.$ppassnr.'</td><td>'.$pfname.' '.$plname.' ('.$ttext.')</td>';
		}
		# // fetch existing statval - this is hardwired to existing STATLIST Values
		$statval=number_format(RetStatValForPlayerOnDate($pid,$event['evstatcode_id']),2,'.','');
		echo '<td>'.$statval.'</td></tr>';
		$lastTID=$tid;
	} 	# // END WHILE LOOP
		# // END TEAM-PLAYER QUERY for this event

	# // close last verein table and cell construct, but only if there are teams else the layout is messed up
	if ($tcount>0) echo '</table></td></tr>';
	CloseTable();
	# // admin stuff - NEW TEAM - convenience button ...
	echo '<br>';
	if ($TEAM_LEVEL>2){
		OpenTable();
		echo '<table><tr><td>'._button('Neues Team erstellen','',$teamcode.'?func=newteam&eventid='.$event['id']).'</td></tr></table>';
		CloseTable();
	}
	echo '</div>';
}


function _delTeam($vtid,$eventid){
	# delete team entry and team-event entry ...

	global $dbi,$TEAM_LEVEL,$usertoken;
	if ($TEAM_LEVEL<3) die ('E:T23:NoAccess');
	if (!$vtid>0) die ('E:T23:MissingTeamID');
	$strSQL='DELETE from tblteamplayer where lteamid='.$vtid.' AND leventid='.$eventid.' limit 16';
	$res1=sql_query($strSQL,$dbi);
	if ($res1==1) {
		debug("<i>Spieler - Team ($vtid) LineUp gel&ouml;scht.</i>");
	}else{
		debug( "Error :: $strSQL");
	}
	$strSQL="DELETE from tblteam where id=$vtid limit 1";
	$res1=sql_query($strSQL,$dbi);
	if ($res1==1) {
		debug("<i>Team Eintrag $vtid gel&ouml;scht.</i>");
		dsolog(3,$usertoken['uname'],"DELETED Team Entry $vtid and Lineup");
	}else{
		debug( "Error :: $strSQL");
	}
}


function _blank($eventid){
	echo setPageTitle('Liga System LSDB, ein Informationsservice des Dartsverband');
	echo '<div id=\'maincontent\'></div>';
}

$myfunc='';
if (isset($_REQUEST['func'])&& $_REQUEST['func']<>'undefined') {$myfunc=strip_tags($_REQUEST['func']);}else{$myfunc='NULL';};
if (isset($_REQUEST['vtname'])&& $_REQUEST['vtname']<>'undefined') {$team_name=strip_tags($_REQUEST['vtname']);}else{$team_name='NOP';};
if (isset($_REQUEST['vtid']) && intval($_REQUEST['vtid'])>0) {$t_id=strip_tags($_REQUEST['vtid']);}else{$t_id=0;};
if (isset($_REQUEST['vverein']) && intval($_REQUEST['vverein'])>0) {$verein_id=strip_tags($_REQUEST['vverein']);}else{$verein_id=0;};
if (isset($_REQUEST['vlocid']) && intval($_REQUEST['vlocid'])>0) {$location_id=strip_tags($_REQUEST['vlocid']);}else{$location_id=0;};

switch($myfunc) {

	default:
		_blank($event['id']);
		break;
		
	case "newteam":
		_editteam($event['id'],0);
		break;

	case "editteam":
		_editteam($event['id'],$t_id);
		break;

	case "team2team":
		_createteamfromoldteam($event['id'],$t_id);
		break;

	case "saveteam":
		_saveTeam($t_id,$event['id'],$team_name,$verein_id,$location_id);
		break;

	case "delete":
		_delTeam($t_id,$event['id']);
		_browseteams($event['id']);
		break;

	case "cloneteam":
		_saveTeam(0,$event['id'],$team_name,$verein_id,$location_id);
		_copyplayers($event['id'],$t_id,$team_name);
		break;

	case "browse":
    	_browseteams($event['id']);
    	break;

	
}
# just in case we close main div
echo '</div>';
LS_page_end();

?>
