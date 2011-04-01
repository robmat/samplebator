<?php
	/*
	 * SNIPPET
	 * file: 		listMembership
	 * returns: 	HTML rows with TD class=dcell containing 1-x specific players:
	 * 				+ js action button if param paction is passed
	 * params: 		mrealm,mcode,paction(strlength<20),vid,pid,mactive(0/1)
	 * 				if mcode is set than compare else take all ...
	 * example:		snippets/listMembership.php?mrealm=2&mcode=1&mactive=1&paction=showplayer()
	 * security:	to avoid name spams, the params must have a min/max length
	 * TODO check if this snippet is needed at all
	 */
	foreach ($_POST as $secvalue) {
	    if (eregi("<[^>]*script*\"?[^>]*>", $secvalue) OR eregi("\([^>]*.*\"?[^>]*\)", $secvalue)) {
			die ("X1");
	    }
	}
	require_once("../code/config.php");
	require_once("../includes/sql_layer.php");
	require_once("../api_rs.php");
	require_once("../api_format.php");
	require_once("../func_lsdb.php");
	require_once("../theme/Lite/theme.php");
	
	if (isset($_POST['mrealm']) && is_numeric($_POST['mrealm'])) {$m_realm=strip_tags($_POST['mrealm']);}else{$m_realm=0;};
	if (isset($_POST['mcode'])&& is_numeric($_POST['mcode'])) {$m_code=strip_tags($_POST['mcode']);}else{$m_code=0;};
	if (isset($_POST['vid']) && is_numeric($_POST['vid'])) {$verein_id=strip_tags($_POST['vid']);}else{$verein_id=0;};
	if (isset($_POST['pid']) && is_numeric($_POST['pid'])) {$player_id=strip_tags($_POST['pid']);}else{$player_id=0;};
	# this is either 'true' or 'undefined'
	if (isset($_POST['mactive']) && strip_tags($_POST['mactive'])=='true') {$m_active=1;}else{$m_active=0;};
	/*
	 * security check on the length of params ...
	 */
	if ($m_code>0) {$type_comp='='.$m_code;} else{$type_comp='>0';}
	
	$dbi = sql_connect($dbhost, $dbuname, $dbpass, $dbname);
	$RS=DB_listMemberShips($dbi,$player_id,$verein_id,$type_comp,$m_active,$m_realm);
	
	if (sizeof($RS)>2000){die_red('Selection returns more than 2000 rows ('.sizeof($RS).')');}
	if (sizeof($RS)<1){die_green('Search criteria returns nothing ...');}
	
	$aTH=array('Verein','Meldeart','PassNr','Meldung Ende','Vorname','Nachname');
	$ROWS=RecordsetToDataTable($RS,array(2,3,4,5,7,8));
	$HEAD=ArrayToTableHead($aTH);
	
	header('Content-Type: application/html; charset=ISO-8859-1');
	echo '<table class="tchild" id="tmembers" name="tmembers">'.$HEAD.$ROWS.'</table>';
	debug('Search criteria returned '.sizeof($RS).' records.');
?>