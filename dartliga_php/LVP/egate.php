<?php

/************************************************************************/
#
# // 	Basic framework for IMPORT of TXT files
# //	a - SSI games from LeagueSys export -> OK
# //	b - player records - sync on oedvpassnr
# //	c - player records - sync on oedsonummer
# // 	v 3.16 (1.2007)
/************************************************************************/
# revisited the upload player ... all passnr get loaded into pfkey1
# 5.10.08 added check if teams in same event

include("empty_main.php");
require_once('func_dso.php');

$gatecode='egate.php';

# // security ... this is easy only level=2 and above can do this ...
if ($usertoken['usertype_id'] < 5) die_red('E18:ErrTypeUser');

# // Beginn Funktionen ----------------

function LS_uploaddialog($caption,$action,$btncaption,$add_element=''){
	$OUT="<FORM ENCTYPE=\"multipart/form-data\" METHOD=\"POST\" ACTION=\"$action\">"
	.'<fieldset><legend>'.$caption.'</legend><table><tr>'
	.'<td>'._input(4,'uploadfile','',40,3).'</td><td>'.$add_element.'</td>'
	.'<td>'._button($btncaption).'</td></tr></table></fieldset></FORM>';
	return $OUT;
}

function showuploaddlg(){
	# ------------------------
	# v02 BH 2005/10/5 
	# -----------------------
	#//
	#// display user dialog which loads a file and stores it in egate folder 
	#//
	global $usertoken,$gatecode;
	
	echo setPageTitle('E-Gate Upload / User: '.$usertoken['uname']);
	echo '<p>Upload Gate zu unterschiedlichen Teilen des LSDB Systems. Verwende unbedingt die Preview Funktion um sicherzustellen dass die upload-dateien keine korrekt interpretiert werden.</p>';
	
	echo '<h3>Vorschau</h3>';
	echo '<table><tr><td>';
	echo LS_uploaddialog('Preview [Tab] Seperated File',$gatecode.'?func=previewtab','Preview');
	echo '</td><td>';
	echo LS_uploaddialog('Preview [;] Seperated File',$gatecode.'?func=previewcsv','Preview');	
	echo '</td></tr></table>';
	echo '<br/>';
	
	echo '<h3>Spielpl&auml;ne</h3>';
	echo LS_uploaddialog('Upload/Process League Schedule File for Events',$gatecode.'?func=uploadmatches','Upload');
	echo 'FORMAT f&uuml;r den Spielplan Upload'
.'<div class="code"><pre>
event;runde;teamhome;teamaway;datum
51;1;89;78;2007-09-02
51;1;73;99;2007-09-02
</pre></div><br/>';

	$element=' Pass Nummer: '.Select_Membertype('mtype',1,'',0);
	echo '<h3>Spieler</h3>';
	echo LS_uploaddialog('Upload Player File',$gatecode.'?func=loadplayer','Upload',$element);
	echo "FORMAT der Spielerdatei"
."<div class=\"code\"><pre>
PassNummer;Vorname;Nachname;Geburtstag;Geschlecht;Nation;Stadt;PLZ;Strasse;Tel1;Tel2;email;Kommentar
A234567;Max;Muster;1968-03-24;H;AUT;Wien;1050;Feldg.3/11;0676 234567;+436991234;max@mail.at;Sinnloser Kommentar
</pre></div>";
	debug('Die Spieler werden mittels der Spielerpassnummer identifiziert und synchronisiert, handelt es sich um eine unbekannte Passnummer so wird ein neuer Spieler angelegt.');
	
	$element=' Mitgliedsart: '.Select_Membertype('mtype',1,'',0);
	echo '<h3>Mitgliedschaften</h3>';
	echo LS_uploaddialog('Upload Player / Membership Records',$gatecode.'?func=loadmember','Upload',$element);		
	echo 'FORMAT of PAYMENTS File'
.'<div class=\'code\'><pre>
Passnummer;DatumAnfang;DatumEnde;Nachname;Vorname;ZVRVerein
1010345;2007-01-01;2007-12-31;Muster;Fred;2007/34/08
1010346;2007-01-01;2007-12-31;Huber;Walter;1234567-AT
</pre></div><br/>';
	debug('Liste aller Vereine eines Bundeslandes (evtl. den Parameter P1 &auml;ndern): <a href=\'query.php?name=allvereinbyblcode&p1=1\' target=\'_blank\'>LINK</a>');
	
	echo '<h3>SSI Resultate</h3>';
	echo LS_uploaddialog('Upload/Process League System OutputFile for SSI',$gatecode.'?func=loadgames','Upload');
	echo 'FORMAT f&uuml;r den SSI Spiele Upload'
.'<div class=\'code\'><pre>
event	match	matchdate	Beschreibung	Runde	Spiel	MatchKey	ID	Nachname	Vorname	Legs
51	712	2006-09-14	WDV 1.Liga R	1	6078	e51r1h269a268	360	KNAPP	Thomas	3
51	712	2006-09-14	WDV 1.Liga R	1	6078	e51r1h269a268	136	RIHS	Roland	0
</pre></div><br/>';
	
}

function _previewfile($fname,$sepchar){
# // just read and display in a table ...
	$row = 1;
	$fp = fopen($fname,"r");
	echo setPageTitle('PREVIEW of ; seperated data file');
	echo '<p>Please scroll through the preview and check if the columns in all ROWS are identified correctly.</p>';
	opentable();
	while ($data = fgetcsv($fp,1000,$sepchar)) {
    	$num = count ($data);
    	$row++;
    	print '<tr>';
    	#// arrays are zero based .... fix BH 2.4.2004
    	for ($c=0; $c < $num; $c++) {
        	print '<td bgcolor=\'#DDEEDD\' align=\'left\'>';
        	print $data[$c];
        	print '</td>';
    		}
    	print '</tr>';
	}
	fclose($fp);
	CloseTable();
}

function _uploadmatches($ufile){
	#
	# load csv data , reassign to vars and call _createMatch from ls_system
	# _initialiseMatch($eventid,$rnum,$vtid,$vpoints,$vsets,$vlegs,$vdate)
	# 50,1,66,67,'2006-09-01'

	$row = 1;
	$fp = fopen ($ufile,"r");
	print "<h3>Uploading Matches ...</h3>";
	opentable();
	while ($data = fgetcsv ($fp, 1000, ";")) {
    	$num = count ($data);
    	#// arrays are zero based .... fix BH 2.4.2004
    	for ($c=0; $c < $num; $c++) {
		#print $data[$c]."<br>";
        	Switch($c) {
        		case "0":
	        		$evid=$data[$c];
	        	break;
	        	case "1":
	        		$rnum=$data[$c];
	        	break;
	        	case "2":
	        		$vtid[0]=$data[$c];
	        	break;
	        	case "3":
	        		$vtid[1]=$data[$c];
	        	break;
	        	case "4":
	        		$vdate=$data[$c];
	        	break;
	        }
    		}
		$vpoints[0]=0;
		$vpoints[1]=0;
		$vsets[0]=0;
		$vsets[1]=0;
		$vlegs[0]=0;
		$vlegs[1]=0;
    		$mkey="e".$evid."r".$rnum."h".$vtid[0]."a".$vtid[1];
		echo "Trying::p_createMatch($mkey,$evid,$rnum,$vtid,$vpoints,$vsets,$vlegs,$vdate)<br>";
		p_createMatch($mkey,$evid,$rnum,$vtid,$vpoints,$vsets,$vlegs,$vdate);
	}
	fclose ($fp);
	CloseTable();
}

function p_createMatch($mkey,$eventid,$rnum,$vtid,$vpoints,$vsets,$vlegs,$vdate){
	/*
	 * create entries for tblmatch, tblmatchteam,tblgame,tblleg
	 */
	# location = home team
	# MatchDate = $vdate
	# SECURITY ON $LS_LEVEL

	global $dbi,$LS_LEVEL,$usertoken;
	$event=reteventconfig($eventid);
	
	# Make sure both teams are in the same event ...
	# 
	$resq=sql_query("select * from tblteam where tevent_id=$eventid and id in($vtid[0],$vtid[1])");
	$resrows=sql_fetch_row($resq,$dbi);
	if (!mysql_num_rows($resrows)==2){
		echo "<font color=red>Event: ".$event['evname']." Match: $mkey Creation failed, Wrong TEAMS for event...</font><br/>";
		return -1;
	}
	# what is the location of the HomeTeam ??
	# 
	$resx=sql_query("select T.id,T.tlocation_id,L.lname from tblteam T,tbllocation L where T.tlocation_id=L.id and T.id=$vtid[0]",$dbi);
	$row=sql_fetch_array($resx,$dbi);
	$loc=$row[lname];
	$locid=$row[tlocation_id];
	$strsql="INSERT INTO tblmatch(mid,mkey,mevid,mround,mhtid,matid,mdate,mlocation,mstatus,mactive) values(0,\"$mkey\",$eventid,$rnum,$vtid[0],$vtid[1],\"$vdate\",\"$loc\",0,1)";
	
	$resx=sql_query($strsql,$dbi);
	if ($resx<>1) {
		echo "<font color=red>Event: ".$event['evname']." Match: $mkey Creation failed, Matchkey Exists...</font><br/>";
		return -1;
	}
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
	if ($resx<>1) {
		echo "<font color=red>Event: $eventid Match: $mkey Creation failed ...cannot assign teams...</font><br>";
		return -1;
	}
	# finally we create the number of required SINGLE and pairs GAME records ...
	# read from config ==> use global event ARRAY ??????????
	# $resx=sql_query("select e.evsingles,e.evdoubles,e.evsgllegs,e.evdbllegs from tblevent e where e.id=$eventid",$dbi);
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
	if ($resx<>1) {
		echo "<font color=red>Event: $eventid Match: $mkey Creation failed ...cannot create GAMES...</font><br>";
		return -1;
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
			$resx=sql_query("INSERT into tblgameplayer(gpid,gpgid,gppid,gpsetwon,gplegswon,gptid) values(0,$gid,0,0,0,$thisTeam)",$dbi);
			# // we leave the tid entry empty here - this is filled when the player is assigned ...
			if ($resx<>1) {
			echo "<font color=red>Event: $eventid Match: $mkey Creation failed ...cannot assign players to games ...</font><br>";
			return -1;
			}
		}
	}
	
	dsolog(1,$usertoken['uname'],"Match $mkey initialised");
}


function _checkLSGameID($lsgid){
	# return 0/1 - check if this game already exists ...
	global $dbi;
	$check=0;
	$qry=sql_query("select max(rgid) from tresult where lsgid=$lsgid",$dbi);
	while(list($vID)=sql_fetch_row($qry,$dbi)){
		$check=$vID;
	}
	return $check;
}

function _saveresult($vdate,$vidwinner,$vidloser,$vdesc,$lsgid){
# ------------------------
# v02 BH 2003/7/10 (ssi_points.php)
# v03 changed for LS sys , shall we add a real GameID from LS ?
# -----------------------
	global $dbi,$user,$ssigoback,$lastdate;
	$qry=sql_query("select max(rgid) from tresult",$dbi);
	while(list($vlastID)=sql_fetch_row($qry,$dbi)){
		$nextgameID=$vlastID;
	}
	$vdesc=strip_tags($vdesc,"<b><i>");
	# // 
	# // if success then update SSI-count for player +1
	# //
	$nextgameID=$nextgameID+1;
	$strres="";
	dsolog(2,"EGATE","<b>Upload GAME $nextgameID</b> Winner:$vidwinner LOSER:$vidloser $vdesc");
	
	# // WINNER RECORD
	$qry="insert into tresult values(0,$nextgameID,$vidwinner,1,'$vdate','$vdesc',$lsgid)";
	$res = sql_query($qry,$dbi);
	$strres=$strres.$res;
	$qry="update tplayer set sipcount=sipcount+1 where pid=$vidwinner";
	$res=sql_query($qry,$dbi);
	$strres=$strres.$res;
	# // LOSER RECORD
	$qry="insert into tresult values(0,$nextgameID,$vidloser,0,'$vdate','$vdesc',$lsgid)";
	$res = sql_query($qry,$dbi);
	$strres=$strres.$res;
	$qry="update tplayer set sipcount=sipcount+1 where pid=$vidloser";
	$res=sql_query($qry,$dbi);
	$strres=$strres.$res;
	return $strres;
}


function _uploadgames($ssifname){
# ------------------------
# v02 BH 2005/10/5 for WDV Data
# -----------------------	
	$row = 0;
	$lastGameID=0;
	$fp = fopen ($ssifname,"r");
	echo setPageTitle('Log Output from Game Loader');
	opentable();
	while ($data = fgetcsv ($fp, 1000, "	")) {
    		$row++;
		if ($lastGameID<>$data[5]){
			$gDate=$data[2];
			$gDesc=$data[3].$data[4];
			$lsgid=$data[5];
			$winPID=$data[7];
		} else {
			$losPID=$data[7];
			if ($winPID>0) {
			echo "<tr><td>Game entry: $data[5]</td><td>Winner: $winPID</td><td>Loser: $losPID</td><td>$gDate $gDesc</td>";
			# OK Go for the upload ...
			if (_checkLSGameID($lsgid)==0){
				echo "<td>"._saveresult($gDate,$winPID,$losPID,$gDesc,$lsgid)."</td></tr>";
			} else {
				echo "<td>League Game exists - no upload</td>";
			}
			}
			# reset
			$gDate="";
			$gDesc="";
			$winPID="";
			$losPID="";
		}
    		#// arrays are zero based 2=date 3+4 = desc 5=gameID 7=PlayerID
        		
    	
    		#//	_saveresult($date,$IDWinner,$IDLoser,$Description);
		$lastGameID=$data[5];
	}
	fclose ($fp);
	CloseTable();
}

function _uploadnewPlayer($file,$membertype_id){
	/**
	# // Method to bulk INSERT NEW players, do some minimum field checks ..
	# // we expect a ; seperation not a TAB ...
	# //
	# // Passnr,Vorname,Nachname,gebdatum,gender,nation,stadt,plz,adresse,tel1,tel2,email,kommentar
	# // $membertype_id = type of passnumber to search or even generate ...
	**/

	$passkey=dso_getPassKeyFieldForType($membertype_id);
	
	$fp = fopen ($file,"r");
	echo setPageTitle('Log Output from Bulk IMPORT');
	print "<p>The loader is doing some minimum checks on the expected fieldvalues - rejected entries are shown in <font color=\"red\">red</font>. Please correct the values and reload the file.</p>";
	opentable();
	$l=0;
	while ($data = fgetcsv ($fp,1000,";")) {
		$num = count ($data);
		$l++;
		if($num<3) {print "Line $l Data too short ...";break;}
		# default values ...
		# TODO v5 replace with ORM backend ...
		$aP=array();
		$aP['passnr']='';
		$aP['firstname']='';
		$aP['lastname']='';
		$aP['birthdate']='1901-01-01';
		$aP['gender']='H';
		$aP['nation']='';
		$aP['town']='';
		$aP['plz']='';
		$aP['street']='';
		$aP['tel1']='';
		$aP['tel2']='';
		$aP['email']='';
		$aP['comment']='eGate upload';
		
		for ($c=0; $c < $num; $c++) {
			if(strlen($data[$c])>0){
	        	Switch($c) {
				case "0":
					$aP['passnr']=$data[$c];
					break;
				case "1":
					$aP['firstname']=$data[$c];
					break;
				case "2":
					$aP['lastname']=$data[$c];
					break;
				case "3":
					$aP['birthdate']=$data[$c];
					break;
	        	case "4":
					$aP['gender']=$data[$c];
					break;			
				case "5":
					$aP['nation']=$data[$c];
					break;
				case "6":
					$aP['town']=$data[$c];
					break;
				case "7":
					$aP['plz']=$data[$c];
					break;
				case "8":
					$aP['street']=$data[$c];
					break;
				case "9":
					$aP['tel1']=$data[$c];
					break;
				case "10":
					$aP['tel2']=$data[$c];
					break;
				case "11":
					$aP['email']=$data[$c];
					break;
				case "12":
					$aP['comment']=$data[$c];
					break;
	        		}
			} else {
				# use the pre-set defaults ....
			}
	    } # end for data split
    	
    	_InsertUpdatePlayer($aP,$passkey);
	} # end while ...
	
	fclose ($fp);
	CloseTable();
}
	
/**
 * actually handle the insert / Update logic based on the PASSNUMBER + type_id
 * param: aP=named array with PlayerData
 */
function _InsertUpdatePlayer($aP,$passkey='pfkey1'){
/* ------------------------
* v03 BH 2006/11/20 for Player Import DATA
* v031 after db version 3 - only a minimum is needed for the creation
* TODO v5 merge this and the dso_player_code to the ORM Modell ...
* TODO v5 replace model by using player_foreignkey table
* -----------------------		
*/

	global $dbi,$usertoken;
	
	$update=0;
	$pid=0;
	$pidstore=0;
	
	# set defaults
	$thisdate = ls_getdate();
	
	# A - check which PASSNUMBER to sync on the fieldname is stored in any $passkey
	
	$pidstore=dso_checkPlayerByPassNr($aP['passnr']);
	
	if ($pidstore>0){
		$MSG="Player with Pass Number: ".$aP['passnr']." found in DB trying sync on PID=$pidstore";
	} else {
		$MSG="Pass with Number: ".$aP['passnr']." NOT found in DB checking name+birthdate.";
		
		# CHECK B  try to obtain PID via name+birthdate combination
		$pidstore=dso_checkPlayerByNameAndBirth($aP['firstname'],$aP['lastname'],$aP['birthdate']);
		if ($pidstore>0){
			$MSG=$MSG.' Found Match, running sync on PID='.$pidstore;
		}
	}
	
	# check the insert flag ..... and output ...
	# we NEVER update the passnr - this is the sync point !!
	$OUT="Pass:".$aP['passnr']." Name:".$aP['firstname']." ".$aP['lastname']." Adr:".$aP['town']." ".$aP['plz']." ".$aP['street']."</td></tr>";
	
	if ( $pidstore > 0 ) {
		$qry="update tplayer set pfname=\"".$aP['firstname']."\",plname=\"".$aP['lastname']."\",pbirthdate=\"".$aP['birthdate']."\",ptel1=\"".$aP['tel1']."\",ptel2=\"".$aP['tel2']."\","
			."pemail=\"".$aP['email']."\",pnationality=\"".$aP['nation']."\",ptown=\"".$aP['town']."\",pplz=\"".$aP['plz']."\",pstreet=\"".$aP['street']."\",pactive=1,pupd_user=\"".$usertoken['uname']."\","
			."pupd_date=\"$thisdate\",pcomment=\"".$aP['comment']."\" where pid=$pidstore limit 1";
		$res = sql_query($qry,$dbi);
		if ($res==1){
			print "<tr><td>$MSG</td><td bgcolor='#eedd88'>UPDATE ".$OUT;
			dsolog(2,$usertoken['uname'],"<b>eGate UPDATE:</b> ".$OUT);
		} else {
			print "<tr><td>$MSG</td><td bgcolor='#ee4444'>FAILED ".$OUT;
			dsolog(2,$usertoken['uname'],"<b>eGate FAILURE UPDATE:</b> ".$OUT);
		}
		
	} else {
		/*
		 * when exactly are we doing AUTO-INSERTS ???
		 */
		$qry="insert into tplayer("
		."pid,pfname,plname,pgender,pactive,pcomment,pbirthdate,pcre_date,pcre_user,ptel1,ptel2,pemail,pnationality,ptown,pplz,pstreet,$passkey)"
		." values(0,\"".$aP['firstname']."\",\"".$aP['lastname']."\",\"".$aP['gender']."\",1,\"".$aP['comment']."\",\"".$aP['birthdate']."\",\"$thisdate\",\"".$usertoken['uname']."\",\"".$aP['tel1']."\",\"".$aP['tel2']."\",\"".$aP['email']."\",\"".$aP['nation']."\",\"".$aP['town']."\",\"".$aP['plz']."\",\"".$aP['street']."\",\"".$aP['passnr']."\")";
		$res = sql_query($qry,$dbi);
		if ($res==1){
			print "<tr><td>$MSG</td><td bgcolor='#88ff88'>NEW ".$OUT;
			dsolog(2,$usertoken['uname'],"<b>eGate INSERT:</b> ".$OUT);
		} else {
			print "<tr><td>$MSG</td><td bgcolor='#FF8888'>NEW Player FAILED ".$OUT;
			dsolog(2,$usertoken['uname'],"<b>eGate FAILURE INSERT:</b> ".$OUT);
		}

	}
}

function _uploadMembership($file,$membertype_id){
	#
	# // add a payment record to this player ... depending on OEDV / OEDSO
	# // passnr;start;end;firstname;lastname;vereinID => $membertype_id
	# //
	$fp = fopen ($file,"r");
	while ($lineIn = fgetcsv($fp,1000,";")) {
		if (sizeof($lineIn)<6) {die_red('Error517:DataSetSize:'.debug($lineIn));}
		if (strlen($lineIn[5])<5){die_red('Err518:NoValidVereinsZVR:'.debug($lineIn));}
		if (strlen($lineIn[1])<8){die_red('Err519:MalformedStartDate:'.debug($lineIn));}
		if (strlen($lineIn[2])<8){die_red('Err519:MalformedEndDate:'.debug($lineIn));}
		$RS[]=$lineIn;
	}
	fclose ($fp);
	
	foreach ($RS as $R){
		// check if player exist -> msg
		// check if verein exist -> msg
		// action ....			-> msg
		$PID=dso_checkPlayerByPassNr($R[0]);
		if ($PID>0){
			$aMSG[]='<font color=green>Identified Player '.$R[3].' '.$R[4].' as unique ID:'.$PID.'</font>';
			$VID=dso_verifyVereinZVR($R[5]);
			if ($VID>0){
				$aMSG[]='<font color=green>Player OK, Verein OK -- adding Membership</font>';
				$aMSG[]=dso_insupdmembership(0,$PID,$VID,$membertype_id,$R[0],$R[1],$R[2]);
			}else {
				$aMSG[]='<font color=red>Verein with ID '.$R[5].' not found or no rights to register players for this Verein</font>';
				$aMSG[]='<font color=red>REJECTED LINE:'.$R[0].';'.$R[1].';'.$R[2].';'.$R[3].';'.$R[4].';'.$R[5].';</font>';
			}
		} else {
			$aMSG[]='<font color=red>Player '.$R[3].' '.$R[4].' can not be identified, no membership added</font>';
			$aMSG[]='<font color=red>REJECTED LINE:'.$R[0].';'.$R[1].';'.$R[2].';'.$R[3].';'.$R[4].';'.$R[5].';</font>';
		}
	}
	
	// == OUTPUT == //
	// ============ //
	
	echo setPageTitle('Log Output from Bulk IMPORT (Records:'.sizeof($RS).') for Type='.$membertype_id);
	echo '<p>The loader is doing some minimum checks on the expected fieldvalues - rejected entries are shown in <font color="red">red</font>.</p>';
	OpenTable('output');
	foreach ($aMSG as $MSG){
		echo '<tr><td>'.$MSG.'</td></tr>';
	}
	CloseTable();
}

/**
 * MAIN ()
 */
if (isset($_REQUEST['func']) && strlen($_REQUEST['func'])<15) {$my_func=strip_tags($_REQUEST['func']);}else{$my_func='';};
if (isset($_REQUEST['mtype']) && is_numeric($_REQUEST['mtype'])) {$membertype_id=strip_tags($_REQUEST['mtype']);}else{$membertype_id=0;};
if (isset($_FILES['uploadfile'])) {$upload_file=strip_tags($_FILES['uploadfile']['tmp_name']);}else{$upload_file='';};

switch($my_func) {

    default:
    	showuploaddlg();
    	break;

	case "previewtab":
		_previewfile($upload_file,"	");
		break;
	case "previewcsv":
		_previewfile($upload_file,";");
		break;
	
	case "uploadmatches":
		_uploadmatches($upload_file);
		break;
	
	case "loadgames";
		_uploadgames($upload_file);
		break;
	
	case "loadplayer":
		 _uploadnewPlayer($upload_file,$membertype_id);
		break;
	
	case "loadmember":
		#die_red('Currently disabled due to system migration');
		_uploadMembership($upload_file,$membertype_id);
		break;
}

echo '</div>';
LS_page_end();

?>