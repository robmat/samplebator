<?php
	foreach ($_POST as $secvalue) {
	    if (eregi("<[^>]*script*\"?[^>]*>", $secvalue) OR eregi("\([^>]*.*\"?[^>]*\)", $secvalue)) {
			die ("X");
	    }
	}
	
	require_once("../code/config.php");
	require_once("../includes/sql_layer.php");
	require_once("../api_rs.php");
	require_once("../api_format.php");
	
	if (isset($_POST['eventtype']) && intval($_POST['eventtype'])>0) {$event_type_id=strip_tags($_POST['eventtype']);}else{$event_type_id="";};
	if (isset($_POST['eventstat']) && intval($_POST['eventstat'])>0) {$event_stat_id=strip_tags($_POST['eventstat']);}else{$event_stat_id=0;};
	if (isset($_POST['eventtypename'])&& $_POST['eventtypename']<>"undefined") {$event_type_name=strip_tags($_POST['eventtypename']);}else{$event_type_name="";};
	if (isset($_POST['eventid']) && intval($_POST['eventid'])>0) {$event_id=strip_tags($_POST['eventid']);}else{$event_id=0;};
	if (isset($_POST['eventname'])&& $_POST['eventname']<>"undefined") {$event_name=strip_tags($_POST['eventname']);}else{$event_name="";};
	if (isset($_POST['eventactive']) && intval($_POST['eventactive'])>0) {$event_active=strip_tags($_POST['eventactive']);}else{$event_active=1;};
	if (isset($_POST['vereinid']) && intval($_POST['vereinid'])>0) {$verein_id=strip_tags($_POST['vereinid']);}else{$verein_id=0;};
	if (isset($_POST['paction'])&& $_POST['paction']<>"undefined") {$client_action=strip_tags(urldecode($_POST['paction']));}else{$client_action="";};
	
	$dbi = sql_connect($dbhost, $dbuname, $dbpass, $dbname);
	$RS=DB_listEventTeamPlayers($dbi,$event_type_id,$event_stat_id,$event_type_name,$event_id,$event_name,$event_active,$verein_id,'team');
	if (sizeof($RS)<1){debug("Search criteria returns nothing ...");return;}
	
	// we return a table here + costum button box ..
	
	$strRET="<table class=\"tchild\" id=\"qryresult\" name=\"qryresult\">";
	foreach($RS as $a){
		$strRET=$strRET."<tr>";
		foreach ($a as $val) $strRET=$strRET."<td class=\"dcell\">".$val."</td>";
		if (strlen($client_action)>1){
			$strRET=$strRET."<td class=\"dcell\"><button id=\"".$a[7]."\" onClick=\"$client_action(this,".$a[2].")\">Detail</button></td>";
		}else { $strRET=$strRET."</tr>"; }
	}
	header('Content-Type: application/html; charset=ISO-8859-1');
	echo $strRET."</table>";
	
?>