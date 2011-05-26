<?php
	/**
	 * SNIPPET
	 * file: 		retLocationTable.php
	 * returns: 	Table+TableRows with TD class=dcell on details for selected search modes
	 * params: 		id,name,active,plz
	 * usedby:		some ajax pages v4
	 * example:		snippets/retLocationTable.php
	 * 				snippets/retLocationTable.php?locname=crown
	 * 				snippets/retLocationTable.php?locplz=102
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
	//require_once("../theme/Lite/theme.php");
	
	if (isset($_POST['locname'])) {$loc_name=strip_tags($_POST['locname']);}else{$loc_name="";};
	if (isset($_POST['locid'])&& is_numeric($_POST['locid'])) {$loc_id=strip_tags($_POST['locid']);}else{$loc_id=0;};
	if (isset($_POST['locplz'])&& strlen($_POST['locid'])<7) {$loc_plz=strip_tags($_POST['locplz']);}else{$loc_plz="";};
	if (isset($_POST['locactive'])&& is_numeric($_POST['locactive'])) {$loc_active=strip_tags($_POST['locactive']);}else{$loc_active=1;};
	if (isset($_POST['eventid']) && is_numeric($_POST['eventid'])) {$event_id=strip_tags($_POST['eventid']);}else{$event_id=0;};
	if (isset($_POST['eventgroupid'])&& is_numeric($_POST['eventgroupid'])) {$event_group_id=strip_tags($_POST['eventgroupid']);}else{$event_group_id=0;};
	if (isset($_POST['extend'])&& strlen($_POST['extend'])<4) {$ext=strip_tags($_POST['extend']);}else{$ext='';};
	
	$dbi = sql_connect($dbhost, $dbuname, $dbpass, $dbname);
	$RS=DB_listLocations($dbi,$loc_id,$loc_name,$loc_active,$loc_plz,$event_id,$event_group_id);
	$OUT='<table class=\'tchild\' id=\'locationtable\' name=\'locationtable\'>';
	if ($ext=='yes'){
		// L.id,L.lname,L.lcity,L.lplz,L.laddress,L.lphone,L.lactive,L.lcoordinates,E.id,E.evname,T.id,T.tname
		foreach($RS as $r){
			$OUT=$OUT.'<tr><td>'.$r[11].'</td><td>'.$r[1].'</td><td>'.$r[4].'</td><td>'.$r[2].'<br/>'.$r[3].'</td><td>'.$r[5].'</td><td>';
			if (strlen($r[7])>5) $OUT=$OUT.'<button onclick=\'showmap('.$r[0].')\'>Map</button>';
			//if (strlen($r[7])>5) $OUT=$OUT._button('Map','showmap('.$r[0].')');
			$OUT=$OUT.'</td></tr>'; 
			$captain=DB_getCaptainDataTeam($dbi,$r[10]);
			$OUT=$OUT.'<tr><td></td><td>'.$captain['pfname'].' '.$captain['plname'].'</td><td>'.$captain['ptel1'].'</td><td>'.$captain['ptel2'].'</td></tr>';
			$OUT=$OUT.'<tr><td colspan=\'6\' class=\'bluebox\'></td></tr>';
		}
	}else {
		$OUT=$OUT.RecordsetToDataTable($RS,array(1,2,3,4,5,6,9,11),array('showmap'),array(array(0)),array('Map'));
	}
	$OUT=$OUT.'</table>';
	header('Content-Type: application/html; charset=ISO-8859-1');
	echo $OUT;
?>