<?php
	/*
	 * SNIPPET
	 * file: 		retPlayerTable
	 * returns: 	HTML rows with TD class=dcell containing 1-x specific players:
	 * 				+ action button if param paction is passed
	 * params: 		pid, passnr (pfkey1),lastname (using plname), paction
	 * example:		snippets/retPlayerTable.php?lastname=spet&paction=addplayer()
	 * security:	to avoid name spams, the params must have a minimum length
	 */
	foreach ($_GET as $secvalue) {
	    if (eregi("<[^>]*script*\"?[^>]*>", $secvalue) OR eregi("\([^>]*.*\"?[^>]*\)", $secvalue)) {
			die ("X");
	    }
	}
	require_once("../code/config.php");
	require_once("../includes/sql_layer.php");
	require_once("../api_rs.php");
	require_once("../api_format.php");
	require_once("../theme/Lite/theme.php");
	
	if (isset($_POST['pid']) && is_numeric($_POST['pid'])) {$player_id=strip_tags($_POST['pid']);}else{$player_id=0;};
	if (isset($_POST['tid']) && is_numeric($_POST['tid'])) {$team_id=strip_tags($_POST['tid']);}else{$team_id=0;};
	if (isset($_POST['passnr'])&& $_POST['passnr']<>'undefined') {$pass_nr=strip_tags(utf8_decode(urldecode($_POST['passnr'])));}else{$pass_nr='';};
	if (isset($_POST['lastname'])&& $_POST['lastname']<>'undefined') {$last_name=strip_tags(utf8_decode(urldecode($_POST['lastname'])));}else{$last_name='';};
	if (isset($_POST['paction'])&& $_POST['paction']<>'undefined') {$client_action=strip_tags(utf8_decode(urldecode($_POST['paction'])));}else{$client_action='';};
	if (isset($_POST['pcaption'])&& $_POST['pcaption']<>'undefined') {$client_caption=strip_tags(utf8_decode(urldecode($_POST['pcaption'])));}else{$client_caption='Add';};
	
	/*
	 * security check on the length of params ...
	 */
	if ((strlen($pass_nr)+strlen($last_name))<4) die('X');
	
	$dbi = sql_connect($dbhost, $dbuname, $dbpass, $dbname);
	$RS=DB_listPlayers($dbi,$player_id,$last_name,'',$pass_nr);
	
	if (sizeof($RS)>15){debug('Selection returns more than 15 rows ...');return;}
	if (sizeof($RS)<1){debug('Search criteria returns nothing ...');return;}
	
	if ($team_id>0){
		// this is a lineup manipulation request ... override active field by Team_id into RecordSet
		$RSOUT=array();
		foreach($RS as $R){
			$r_out=$R;
			$r_out[1]=$team_id;
			$RSOUT[]=$r_out;
		}
	} else {
		$RSOUT=&$RS;
	}
	// we return a table here + costum button box ..
	$aTH=array('ID','Vorname','Nachname','&Ouml;DSO','&Ouml;DV','Wohnort','Aktion');
	$strRET='<table class=\'tchild\' id=\'qryresult\' name=\'qryresult\'>';
	$strRET=$strRET.ArrayToTableHead($aTH);
	$strRET=$strRET.RecordsetToDataTable($RSOUT,array(0,2,3,4,5,7),array($client_action,'chkplayerteam'),array(array(0,1),array(0)),array($client_caption,'Check'));
	
	header('Content-Type: application/html; charset=ISO-8859-1');
	echo $strRET.'</table>';
	
?>