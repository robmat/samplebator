<?php
	/**
	 * SNIPPET
	 * file: 		retLineUpTable.php
	 * returns: 	Table+Rows with TD class=dcell on Team LineUp details for selected search modes
	 * 				if action=1 then insert actionbuttons (add,remove)
	 * params: 		teamid,teamname,playertype
	 * usedby:		some ajax pages v1
	 * example:	retLineUpTable.php?teamid=
	 * 			retLineUpTable.php?teamname=sporran&playertype=2
	 */
	foreach ($_POST as $secvalue) {
	    if (eregi("<[^>]*script*\"?[^>]*>", $secvalue) OR eregi("\([^>]*.*\"?[^>]*\)", $secvalue)) {
			die ("X");
	    }
	}
	
	require_once("../code/config.php");
	require_once("../includes/sql_layer.php");
	require_once("../func_sec.php");
	require_once("../lsdbcontroller.php");
	require_once("../api_rs.php");
	require_once("../api_format.php");
	
	if (isset($_POST['teamname'])) {$team_name=strip_tags($_POST['teamname']);}else{$team_name="";};
	if (isset($_POST['teamid'])) {$team_id=strip_tags($_POST['teamid']);}else{$team_id=0;};
	if (isset($_POST['playertype'])) {$player_type=strip_tags($_POST['playertype']);}else{$player_type=0;};
	if (isset($_POST['eventid']) && is_numeric($_POST['eventid'])) {$event_ID=utf8_decode(strip_tags($_POST['eventid']));}else{$event_ID=0;};
	if (isset($_POST['laction'])) {$client_action=strip_tags($_POST['laction']);}else{$client_action="";};
				
	$dbi = sql_connect($dbhost, $dbuname, $dbpass, $dbname);
	/*
	 * no security here this is used for some public lineUp displays ...
	$usertoken=initLsdbSec($dbi);
	$ac=$usertoken['eventmap'][$event_ID];
	if ($ac<3) die("<b>X2</b>");
	*/
	$RS=DB_listTeamLineUp($dbi,$team_id,$team_name,$player_type);
	
	$aTH=array('Team','Vorname','Nachname','&Ouml;DSO','&Ouml;DV','Aufstellung');
	if (strlen($client_action)>1) $aTH[]='Aktion';
	
	$OUT="<table class=\"tchild\" id=\"lineupT$team_id\" name=\"lineupT$team_id\">";
	$OUT=$OUT.ArrayToTableHead($aTH);
	if (strlen($client_action)>1){
		# this is an adm request -> inject the lineup selector and modify out RS
		$RSOUT=array();
		foreach($RS as $r){
			$r_out=array();
			$r_out=$r;
			$r_out[10]=Select_LineUpType('ltype',$r[10],'changetype('.$r[0].',this)').'<div id=\'l_'.$r[0].'\'></div>';
			$RSOUT[]=$r_out;
		}
		$OUT=$OUT.RecordsetToDataTable($RSOUT,array(2,4,5,6,7,10),array($client_action),array(array(0,1)),array('Remove'));
	} else {
		$OUT=$OUT.RecordsetToDataTable($RS,array(2,4,5,6,7,8));
	}
	$OUT=$OUT."</table>";
	header('Content-Type: application/html; charset=ISO-8859-1');
	echo $OUT;
?>