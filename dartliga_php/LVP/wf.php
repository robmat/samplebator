<?php
/*
 * Main modul for requests , this uses the new lsdbsec und $usertoken[] structure
 */
include('wf_main.php');

echo '<script type=\'text/javascript\' src=\'code/wfrequest.js\'></script>';
$wfcode='wf.php';

function _newrequest(){
	
	global $dbi,$usertoken,$wfcode;
	$obj['verein_id']=$usertoken['verein_id'];
	$obj['wfrequesttype']=1;
	echo setPageTitle('Neuer Antrag');
	echo form_new_Request($obj,$wfcode.'?op=save');
	
}
/**
*	purpose:	NEW request speichern - status creation, sowie erzeugen der dummyobj childtabellen
* 	params:		vid,request formular
*	returns:	unique req_id
* 	rem:		we pass the vid from the form, enabling the Admin to change it
*/
function _saverequest($req_type,$v_id){
	global $dbi,$usertoken;
	if (!$req_type>0){debug('Error :: Request Type not set ...');return;}
	if (!$v_id>0){debug('Error :: Verein not set ...');return;}
	
	$rkey='r'.time();
	$d = getdate();
	$rtdate = $d['year'].'-'.$d['mon'].'-'.$d['mday'];
	
	if (is_array($usertoken)){$uid=$usertoken['id'];} else {die('E1');}
	/*
	 * create request with STATUS=CREATE, using the param: v_id instead of the usertoken ...
	 */
	$qry='insert into wfrequest(wfrequest_id,reqdate,user_id,verein_id,wfrequesttype_id,wfstate_id,rkey)'
	.' values(0,\''.$rtdate.'\','.$usertoken['id'].','.$v_id.','.$req_type.',1,\''.$rkey.'\')';
	#debug($qry);
	$prec=sql_query($qry,$dbi);
	# get ID
	$p=sql_query('select R.*,T.wftablename wfobject from wfrequest R,wfrequesttype T where R.wfrequesttype_id=T.wfrequesttype_id and rkey=\''.$rkey.'\'',$dbi);
	$ret=sql_fetch_array($p,$dbi);
	if (sizeof($ret)<2) die ('<h3>Error creating initial request object</h3>');
	// create child records ...
	switch($ret['wfobject']){
		case 'wflineup':
			$qry='INSERT INTO wflineup(wflineup_id,wfrequest_id,rkey) VALUES (0,'.$ret['wfrequest_id'].',\''.$rkey.'\')';
			$p=sql_query($qry,$dbi);
			break;
		case 'wfplayer':
			$qry='INSERT INTO wfplayer(wfplayer_id,wfrequest_id,rkey) VALUES (0,'.$ret['wfrequest_id'].',\''.$rkey.'\')';
			$p=sql_query($qry,$dbi);
			break;
		case 'wfteam':
			// create empty wfteam 
			$qry='INSERT INTO wfteam(wfteam_id,wfrequest_id,rkey) VALUES (0,'.$ret['wfrequest_id'].',\''.$rkey.'\')';
			$p=sql_query($qry,$dbi);
			break;
		case 'wfmessage':
			// create empty wfmessage
			$qry='INSERT INTO wfmessage(wfmessage_id,wfrequest_id,rkey) VALUES (0,'.$ret['wfrequest_id'].',\''.$rkey.'\')';
			$p=sql_query($qry,$dbi);
			break;
	}
	
	return $ret['wfrequest_id'];
}

/**
*	purpose:	render page objects for WF LISTING
* 	params:		nop
*	returns:	list controls and div=maincontent
*/
function _listRequestTab(){
	
	global $usertoken;
	
	$TABS='<table><tr>'
	.'<td>'._button('Erstellung','listWF(1)').'</td>'
	.'<td>'._button('Abgegeben','listWF(2)').'</td>'
	.'<td>'._button('Akzeptiert','listWF(3)').'</td>'
	.'<td>'._button('Abgelehnt','listWF(4)').'</td>'
	.'<td>'._button('Prozessiert','listWF(5)').'</td>'
	.'<td>'._button('Geschlossen','listWF(6)').'</td>'
	.'</tr></table><hr/>';
	
	echo setPageTitle('&Uuml;bersicht aller Antr&auml;ge f&uuml;r Benutzer: '.$usertoken['uname']);
	echo setPageControlTabs($TABS);
}

/**
*	purpose:	functional edit page or stages stuff
* 	params:		obj of request + child, this is a named array of a request+object view
*	returns:	html page, form,process buttons
*/
function _editobject($obj){
	
	# IF USER = Y and STATE = other than create or rejected (1,4) than this should be READ ONLY
	
	global $usertoken,$dbi;
	echo setPageTitle('Antrag Bearbeiten Benutzer: '.$usertoken['uname']);
	
	echo '<div class=\'child\'>';
	echo include('forms/request.php');
	echo '</div>';
	#debug($obj);
	switch($obj['wfobject']){
	case 'wflineup':
		
		// step 1 collect and SHOW the teams for this verein
		echo '<h3>Team w&auml;hlen</h3><div class=\'child\'><p>* Das Team w&auml;hlen zu dem ein Spieler hinzugef&uuml;gt werden soll. Die aktuelle Aufstellung wird dann unterhalb abgezeigt. F&uuml;r <b>jeden</b> Spieler ist ein eigener Antrag auszuf&uuml;llen.</p>';
		$RS=DB_listTeams($dbi,0,0,'',1,'',$usertoken['verein_id']);
		echo RecordsetToSelectOptionList($RS,array(4,2,5),'teamid',$obj['team_id'],'getlineup(this.value)');
		echo '</div>';
		
		echo '<h3>Aktuelle Aufstellung</h3><div class=\'child\'><div id=\'lineUp\'></div></div>';
		
		echo '<h3>Aufstellung erweitern</h3><div class=\'child\'>';
		echo _input(0,'wflineupid',$obj['wflineup_id']);
		echo '<p>Hier kannst einen existierenden Spieler aus der Datenbank suchen und diesen in das Formular &uuml;bernehmen. Kann der Spieler nicht gefunden werden so muss das Formular ausgef&uuml;llt werden.</p>';

		echo form_SearchPlayer('searchplayer("loadplayer")');
		echo '<div id=\'qry\'></div>';
		
		// Only show the LOAD Button if the wfplayer record does not exists
		if (!($obj['wfplayer_id']+$obj['player_id'])>0) echo '<p>Ein neues, leeres Spieler Melde Formular laden: '._button('Spieler neu','loadplayer(this)').'</p>';
		echo '</div>';
		echo '<h3>Spieler Bearbeiten</h3><div class="child">';
		echo '<div id="requestdata">';
		// only show the form if there is a saved wfplayer record - else show LOAD Button ?
		if (($obj['wfplayer_id']+$obj['player_id'])>0) {
			echo include('forms/wfplayer.php');
		} else {
			echo '<div id="check"></div>';
		}
		echo '</div>';
		echo '</div>';
		echo '<h3>Prozess</h3><div class="child">';
		echo _show_process_buttons('lineup',$obj);
		echo '</div>';
		break;
		
	case 'wfplayer':
		
		echo '<h3>Suchen</h3><div class=\'child\'>';
		echo '<p id=\'explain\'>1. Einen existierenden Spieler in der aktuellen Datenbank nach Passnummer suchen und die Suchergebnisse anzeigen.</p>';
		echo form_SearchPlayer('searchplayer("loadplayer")');
		echo '<p id=\'explain\'>2. Hast du richtigen Spieler gefunden, so kannst du mit dem Load Button die bekannten Daten dieses Spielers aus der Datenbank in das Formular &uuml;bernehmen. Die Daten k&ouml;nnen nun ge&auml;ndert bzw erg&auml;nzt werden.<br>Konnte der betreffende Spieler nicht gefunden werden oder es handelt sich um einen neuen Spieler so sind alle mit (*) gekennzeichneten Felder auszuf&uuml;llen.</p>';
		echo '<div id=\'qry\'></div></div>';
		echo '<h3>Bearbeiten</h3><div class=\'child\'>';
		echo '<div id=\'requestdata\'>';
		echo include('forms/wfplayer.php');
		echo '</div></div>';
		echo '<h3>Prozess</h3><div class=\'child\'>';
		echo _show_process_buttons('player',$obj);
		echo '</div>';
		break;
		
	case 'wfteam':
		
		#debug($obj);
		echo "<h3>Team</h3><div class=\"child\">";
		echo "<div id=\"requestdata\">";
		echo include('forms/wfteam.php');
		echo '</div></div>';
		echo '<h3>Aufstellung</h3><div class=\'child\'><div id=\'lineUp\'></div></div>';
		echo '<h3>Aufstellung erweitern</h3><div class=\'child\'>';
		echo form_SearchPlayer('searchplayer("addwflineup")');
		echo '<div id=\'qry\'></div><div id="check"></div></div>';
		echo '<script>getwflineup('.$obj['wfteam_id'].')</script>';
		
		echo '<h3>Prozess</h3><div class=\'child\'>';
		echo _show_process_buttons('team',$obj);
		echo '</div>';
		break;
		
	case 'wfmessage':
		
		#debug($obj);
		echo '<h3>Unstrukturierte freie Meldung</h3><div class=\'child\'>';
		echo '<div id=\'requestdata\'>';
		echo include('forms/wfmessage.php');
		echo '</div></div>';
		
		echo '<h3>Prozess</h3><div class=\'child\'>';
		echo _show_process_buttons('message',$obj);
		echo '</div>';
		break;
	
	default:
		echo '<div class=\'child\'>E:WF23:UnknownRequestType</div>';
	}
	
}

/**
*	purpose:	display process action buttons according to object and wfstate of object
* 	params:		$item (player,lineup,team),$processstate(1..6)
*	returns:	HTML TABLE with lsdb_buttons and comment field
*/
function _show_process_buttons($item,&$obj){
		global $usertoken;
		#debug($usertoken);
		$adm=0;
		$processstate=$obj['wfstate_id'];
		
		switch($usertoken['usertype_id']){
			case 4:
				#TODO replace by Access Lookup ... ==> Message Group Membership !!
				#TODO is member of ligaadmin level 3 ???
				break;
			case 5:
			case 6:
				$adm=$usertoken['id'];
				break;
			default:
				$adm=0;
		}
		// layout = table with max of 4 cols, 1-row is requestcomment 2-row is button 3-row is explain
		$RS=array();
		#$r1=array();
		$r2=array();
		$r3=array();
		switch($processstate){
			case 1:
			case 4:
				$r2[]=_button('Save','save'.$item.'()');
				$r2[]=_button('Submit','submit'.$item.'()');
				$r3[]='Speichert das Formular <b>ohne</b> es abzuschicken, dieses Formular kann sp&auml;ter weiter bearbeitet werden.';
				$r3[]='Schliesst die Bearbeitung ab und <b>sendet</b> den Antrag an die Liga Verwaltung, der Antrag liegt zur Begutachtung vor und kann erst bei einer Ablehnung wieder bearbeitet werden.';
				$r2[]=_button('Delete','deleterequest()');
				$r3[]='Diesen Antrag <b>l&ouml;schen</b> und aus dem System entfernen.';
				break;
			case 2:
				if ($adm==0) {
					$r2[]=_button('Save','save'.$item.'()');
					$r3[]='Speichert das Formular mit <b>aktualisierten</b> Daten.';
				} else {
				$r2[]=_button('Accept','accept'.$item.'()');
				$r3[]='Den Antrag in dieser Form <b>akzeptieren</b>, alle Daten vorhanden';
				$r2[]=_button('Reject','reject'.$item.'()');
				$r3[]='Antrag ist fehlerhat - <b>abweisen</b> und dem Benutzer wieder als zur&uuml;ckgewiesen freischalten.';
				}
				break;
			case 3:
				if ($adm>0) {
				$r2[]=_button('Process','process'.$item.'()');
				$r3[]='Aktion durchf&uuml;hren und in das Ligasystem <b>&uuml;bernehmen</b> - Spieler anlegen bzw. Team anlegen usw ...';
				$r2[]=_button('Reject','reject'.$item.'()');
				$r3[]='Antrag ist fehlerhat - <b>abweisen</b> und dem Benutzer wieder als zur&uuml;ckgewiesen freischalten.';
				}
				break;
			case 5:
				// If the request is processed, user controls shows invoice ...
				if ($adm>0) {
					$r2[]=_button('Close','close'.$item.'()');
					$r3[]='Bearbeitung dieses Antrages beenden und als geschlossen ablegen.';
					$r2[]=_button('Delete','deleterequest()');
					$r3[]='Diesen Antrag <b>l&ouml;schen</b> und aus dem System entfernen.';
				} else {
					$r2[]=_button('Rechnung','invoice'.$item.'()');
					$r3[]='F&uuml;r diesen Antrag eine Rechnung erstellen.';
				}
				break;
			case 6:
				if ($adm>0) {
				$r2[]=_button('Open','reject'.$item.'()');
				$r3[]='Antrag erneut &ouml;ffnen und dem Benutzer wieder als zur&uuml;ckgewiesen freischalten.';
				$r2[]=_button('Archive','archive'.$item.'()');
				$r3[]='Diesen Antrag archivieren.';
				$r2[]=_button('Delete','deleterequest()');
				$r3[]='Diesen Antrag <b>l&ouml;schen</b> und aus dem System entfernen.';
				}else {
					$r2[]=_button('Rechnung','invoice'.$item.'()');
					$r3[]='F&uuml;r diesen Antrag eine Rechnung erstellen.';
				}
				break;
		}
		
		if ($adm>0){
			$r2[]=_button('History','historywf()');
			$r3[]='Zeige die Bearbeitung dieses Antrages in einem neuen Fenster.';
		}
		
		$RS[]=$r2;$RS[]=$r3;
		// OUTPUT //
		$OUT='<div id="pmsg"></div><table><tr><td valign=\'top\' class=\'bluebox\'>Kommentar</td>';
		$OUT=$OUT.'<td>'._input(1,'rcomment',$obj['reqcomment'],80,100).'</td></table>';		
		return $OUT=$OUT.'<table>'.RecordsetToDataTable($RS,array(0,1,2,3)).'</table>';
}


if (isset($_POST['vid']) && is_numeric($_POST['vid'])){$v_id=$_POST['vid'];} else {$v_id=0;}
if (isset($_POST['wfreqtype'])) {$req_type=strip_tags($_POST['wfreqtype']);} else $req_type=0;
if (isset($_GET['op']) && strlen($_GET['op'])<10) {$op=strip_tags($_GET['op']);} else {$op="";}
if (isset($_GET['reqid']) && is_numeric($_GET['reqid'])){$req_id=$_GET['reqid'];} else {$req_id=0;}

/*
 * common pageobjects are loaded in wf_main
 */

switch($op){
	case 'list':
		_listRequestTab();
		break;
	case 'save':
		$req_id=_saverequest($req_type,$v_id);
		if ($req_id>0) {$obj=wf_getReqObject($req_id);} else {debug('Error :: Request could not be created');return;}
		if (sizeof($obj)>2)	{_editobject($obj);} else {debug('Error :: Request not found');return;}
		break;
	case 'edit':
		$obj=wf_getReqObject($req_id);
		if (sizeof($obj)>2)	{_editobject($obj);} else {debug('Error :: Request not found');return;}
		break;
	case 'new':
	default:
		_newrequest();
}

echo '</div>';	#-> close the maincontent
LS_page_end();

?>
