<?php 

require_once('mainfile.php');
require_once('lsdb_layout.php');
require_once('theme/Lite/theme.php');
require_once('func_lsdb.php');

function list_objects() {
	global $dbi;
	
	$eventsResult = sql_query( 'SELECT * FROM tbleventconfig' , $dbi );
	
	# Table header
	$ret = '<table id="eventtable"><tr>';
	$ret = $ret.'<td class="thead">Id</td>';
	$ret = $ret.'<td class="thead">Versionsnummer</td>';
	$ret = $ret.'<td class="thead">Aktiv</td>';
	$ret = $ret.'<td class="thead">Spielsystem Name</td>';
	/*
	$ret = $ret.'<td class="thead">evdbldarts</td>';
	$ret = $ret.'<td class="thead">evdbldist</td>';
	$ret = $ret.'<td class="thead">evdblfinish</td>';
	$ret = $ret.'<td class="thead">evdblhighscore</td>';
	$ret = $ret.'<td class="thead">evdbllegs</td>';
	$ret = $ret.'<td class="thead">evdblrest</td>';
	$ret = $ret.'<td class="thead">evdblroundcheck</td>';
	$ret = $ret.'<td class="thead">evdblroundscore</td>';
	$ret = $ret.'<td class="thead">evdblstart</td>';
	$ret = $ret.'<td class="thead">evdoubles</td>';
	$ret = $ret.'<td class="thead">evmaxteamsize</td>';
	$ret = $ret.'<td class="thead">evnumdown</td>';
	$ret = $ret.'<td class="thead">evnumrounds</td>';
	$ret = $ret.'<td class="thead">evnumup</td>';
	$ret = $ret.'<td class="thead">evplayerfee</td>';
	$ret = $ret.'<td class="thead">evpointseven</td>';
	$ret = $ret.'<td class="thead">evpointslost</td>';
	$ret = $ret.'<td class="thead">evpointswin</td>';
	$ret = $ret.'<td class="thead">evsgldarts</td>';
	$ret = $ret.'<td class="thead">evsgldist</td>';
	$ret = $ret.'<td class="thead">evsglfinish</td>';
	$ret = $ret.'<td class="thead">evsglhighscore</td>';
	$ret = $ret.'<td class="thead">evsgllegs</td>';
	$ret = $ret.'<td class="thead">evsglrest</td>';
	$ret = $ret.'<td class="thead">evsglroundcheck</td>';
	$ret = $ret.'<td class="thead">evsglroundscore</td>';
	$ret = $ret.'<td class="thead">evsglstart</td>';
	$ret = $ret.'<td class="thead">evsingles</td>';
	$ret = $ret.'<td class="thead">evtabpoints</td>';
	$ret = $ret.'<td class="thead">evtabsets</td>';
	$ret = $ret.'<td class="thead">evteamfee</td>';
	$ret = $ret.'<td class="thead">evsglhighscore171</td>';
	*/
	$ret = $ret.'<td class="thead">Edit</td>';
	$ret = $ret.'<td class="thead">Delete</td>';
	
	while ( list( $id, $version, $cfgactive, $cfgname, $evdbldarts, $evdbldist, $evdblfinish, 
		$evdblhighscore, $evdbllegs, $evdblrest, $evdblroundcheck, $evdblroundscore, $evdblstart,
	  	$evdoubles, $evmaxteamsize, $evnumdown, $evnumrounds, $evnumup, $evplayerfee, $evpointseven,
	  	$evpointslost, $evpointswin, $evsgldarts, $evsgldist, $evsglfinish, $evsglhighscore,
	  	$evsgllegs, $evsglrest, $evsglroundcheck, $evsglroundscore, $evsglstart, $evsingles, $evtabpoints,
	  	$evtabsets, $evteamfee, $evsglhighscore171) = sql_fetch_row( $eventsResult, $dbi ) ) {
	  		
		$ret = $ret.'<tr>';
		$ret = $ret.'<td>'.$id.'</td>';
		$ret = $ret.'<td>'.$version.'</td>';
		$ret = $ret.'<td>'.$cfgactive.'</td>';
		$ret = $ret.'<td>'.$cfgname.'</td>';
		/*
		$ret = $ret.'<td>'.$evdbldarts.'</td>';
		$ret = $ret.'<td>'.$evdbldist.'</td>';
		$ret = $ret.'<td>'.$evdblfinish.'</td>';
		$ret = $ret.'<td>'.$evdblhighscore.'</td>';
		$ret = $ret.'<td>'.$evdbllegs.'</td>';
		$ret = $ret.'<td>'.$evdblrest.'</td>';
		$ret = $ret.'<td>'.$evdblroundcheck.'</td>';
		$ret = $ret.'<td>'.$evdblroundscore.'</td>';
		$ret = $ret.'<td>'.$evdblstart.'</td>';
		$ret = $ret.'<td>'.$evdoubles.'</td>';
		$ret = $ret.'<td>'.$evmaxteamsize.'</td>';
		$ret = $ret.'<td>'.$evnumdown.'</td>';
		$ret = $ret.'<td>'.$evnumrounds.'</td>';
		$ret = $ret.'<td>'.$evnumup.'</td>';
		$ret = $ret.'<td>'.$evplayerfee.'</td>';
		$ret = $ret.'<td>'.$evpointseven.'</td>';
		$ret = $ret.'<td>'.$evpointslost.'</td>';
		$ret = $ret.'<td>'.$evpointswin.'</td>';
		$ret = $ret.'<td>'.$evsgldarts.'</td>';
		$ret = $ret.'<td>'.$evsgldist.'</td>';
		$ret = $ret.'<td>'.$evsglfinish.'</td>';
		$ret = $ret.'<td>'.$evsglhighscore.'</td>';
		$ret = $ret.'<td>'.$evsgllegs.'</td>';
		$ret = $ret.'<td>'.$evsglrest.'</td>';
		$ret = $ret.'<td>'.$evsglroundcheck.'</td>';
		$ret = $ret.'<td>'.$evsglroundscore.'</td>';
		$ret = $ret.'<td>'.$evsglstart.'</td>';
		$ret = $ret.'<td>'.$evsingles.'</td>';
		$ret = $ret.'<td>'.$evtabpoints.'</td>';
		$ret = $ret.'<td>'.$evtabsets.'</td>';
		$ret = $ret.'<td>'.$evteamfee.'</td>';
		$ret = $ret.'<td>'.$evsglhighscore171.'</td>';
		*/
		$ret = $ret.'<td><img src="images/edit24.png" style="cursor: pointer;" onclick="window.location.href = \'admin_event_configs.php?op=edit&id='.$id.'\'" /></td>';
		$ret = $ret.'<td align="center"><img src="images/del_icon.png" style="cursor: pointer;" onclick="window.location.href = \'admin_event_configs.php?op=delete&id='.$id.'\'" /></td>';
		$ret = $ret.'</tr>';
	}
	
	return $ret.'</table>';
}

function object_form( $object_id = -1 ) {
	global $dbi;
	
	$id = '';
	$version = '';
	$cfgactive = '';
	$cfgname = '';
	$evdbldarts = '';
	$evdbldist = '';
	$evdblfinish = '';
	$evdblhighscore = '';
	$evdbllegs = '';
	$evdblrest = '';
	$evdblroundcheck = '';
	$evdblroundscore = '';
	$evdblstart = '';
  	$evdoubles = '';
  	$evmaxteamsize = '';
  	$evnumdown = '';
  	$evnumrounds = '';
  	$evnumup = '';
  	$evplayerfee = '';
  	$evpointseven = '';
  	$evpointslost = '';
  	$evpointswin = '';
  	$evsgldarts = '';
  	$evsgldist = '';
  	$evsglfinish = '';
  	$evsglhighscore = '';
  	$evsgllegs = '';
  	$evsglrest = '';
  	$evsglroundcheck = '';
  	$evsglroundscore = '';
  	$evsglstart = '';
  	$evsingles = '';
  	$evtabpoints = '';
  	$evtabsets = '';
  	$evteamfee = '';
  	$evsglhighscore171 = '';
	
	if ( $object_id != -1 ) {
		$eventResult = sql_query( 'SELECT * FROM tbleventconfig WHERE config_id = '.$object_id, $dbi );
		list( $id, $version, $cfgactive, $cfgname, $evdbldarts, $evdbldist, $evdblfinish, 
			$evdblhighscore, $evdbllegs, $evdblrest, $evdblroundcheck, $evdblroundscore, $evdblstart,
		  	$evdoubles, $evmaxteamsize, $evnumdown, $evnumrounds, $evnumup, $evplayerfee, $evpointseven,
		  	$evpointslost, $evpointswin, $evsgldarts, $evsgldist, $evsglfinish, $evsglhighscore,
		  	$evsgllegs, $evsglrest, $evsglroundcheck, $evsglroundscore, $evsglstart, $evsingles, $evtabpoints,
		  	$evtabsets, $evteamfee, $evsglhighscore171) = sql_fetch_row( $eventResult, $dbi );
	}
	$ret = '<form method="POST" action="admin_event_configs.php?op=save&id='.$id.'">';
	$ret = $ret.'<table>';
	//$ret = $ret.'<tr><td>Id:</td><td><input size=50 name="id" value="'.$id.'" /></td></tr>';	
	$ret = $ret.'<tr><td>Versionsnummer:</td><td><input size=50 name="version" value="'.$version.'" /></td></tr>';
	
	$ret = $ret.'<tr><td>Aktiv:</td><td>';
	$ret = $ret.'<select name="cfgactive" value="'.$cfgactive.'">';
	$ret = $ret.'<option value="1" '.($cfgactive == 1 ? 'selected' : '').'>On</option>';
	$ret = $ret.'<option value="0" '.($cfgactive == 0 ? 'selected' : '').'>Off</option>';
	$ret = $ret.'</select>';
	$ret = $ret.'</td></tr>';
	
	$ret = $ret.'<tr><td>Spielsystem Name:</td><td><input size=50 name="cfgname" value="'.$cfgname.'" /></td></tr>';
	$ret = $ret.'<tr><td>evdbldarts:</td><td><input size=50 name="evdbldarts" value="'.$evdbldarts.'" /></td></tr>';
	$ret = $ret.'<tr><td>Double: Spieldistanz:</td><td><input size=50 name="evdbldist" value="'.$evdbldist.'" /></td></tr>';
	
	$ret = $ret.'<tr><td>Double: Finishzahl(wenn Finishzahl miterfasst werden sollen dann bitte hier On ansonsten Off):</td>';
	$ret = $ret.'<td><select name="evdblfinish" value="'.$evdblfinish.'"><option value="1" '.($evdblfinish == 1 ? 'selected' : '').'>On</option><option value="0" '.($evdblfinish == 0 ? 'selected' : '').'>Off</option></select></td></tr>';
	
	$ret = $ret.'<tr><td>Double Highscore (z.B. 180er Wertung dann hier On eintragen sonst Off) :</td>';
	$ret = $ret.'<td><select name="evdblhighscore" value="'.$evdblhighscore.'"><option value="1" '.($evdblhighscore == 1 ? 'selected' : '').'>On</option><option value="0" '.($evdblhighscore == 0 ? 'selected' : '').'>Off</option></select></td></tr>';
	
	$ret = $ret.'<tr><td>Double: Best of x Legs:</td><td><input size=50 name="evdbllegs" value="'.$evdbllegs.'" /></td></tr>';
	
	$ret = $ret.'<tr><td>Double: Wenn Restpunkte eingetragen werden sollen dann hier On ansonsten Off:</td>';
	$ret = $ret.'<td><select name="evdblrest" value="'.$evdblrest.'"><option value="1" '.($evdblrest == 1 ? 'selected' : '').'>On</option><option value="0" '.($evdblrest == 0 ? 'selected' : '').'>Off</option></select></td></tr>';
	
	$ret = $ret.'<tr><td>Double: Wenn eingetragen werden soll in welcher Runde gecheckt wurde dann hier On ansonsten Off:</td><td>';
	$ret = $ret.'<select name="evdblroundcheck" value="'.$evdblroundcheck.'"><option value="1" '.($evdblroundcheck == 1 ? 'selected' : '').'>On</option><option value="0" '.($evdblroundcheck == 0 ? 'selected' : '').'>Off</option></select></td></tr>';
	
	$ret = $ret.'<tr><td>Double: Wenn eingetragen werden soll Rundenscore dann hier On ansonsten Off:</td><td>';
	$ret = $ret.'<select name="evdblroundscore" value="'.$evdblroundscore.'"><option value="1" '.($evdblroundscore == 1 ? 'selected' : '').'>On</option><option value="0" '.($evdblroundscore == 0 ? 'selected' : '').'>Off</option></select></td></tr>';
	
	$ret = $ret.'<tr><td>evdblstart:</td><td><input size=50 name="evdblstart" value="'.$evdblstart.'" /></td></tr>';
	$ret = $ret.'<tr><td>Wieviel Doubles werden gespielt?</td><td><input size=50 name="evdoubles" value="'.$evdoubles.'" /></td></tr>';
	$ret = $ret.'<tr><td>Maximale Teamanzahl in der Gruppe/Klasse/Event:</td><td><input size=50 name="evmaxteamsize" value="'.$evmaxteamsize.'" /></td></tr>';
	$ret = $ret.'<tr><td>evnumdown:</td><td><input size=50 name="evnumdown" value="'.$evnumdown.'" /></td></tr>';
	$ret = $ret.'<tr><td>evnumrounds:</td><td><input size=50 name="evnumrounds" value="'.$evnumrounds.'" /></td></tr>';
	$ret = $ret.'<tr><td>evnumup:</td><td><input size=50 name="evnumup" value="'.$evnumup.'" /></td></tr>';
	$ret = $ret.'<tr><td>evplayerfee:</td><td><input size=50 name="evplayerfee" value="'.$evplayerfee.'" /></td></tr>';
	$ret = $ret.'<tr><td>Wieviel Punkte bei unentschieden?</td><td><input size=50 name="evpointseven" value="'.$evpointseven.'" /></td></tr>';
	$ret = $ret.'<tr><td>Wieviel Punkte bei verloren?</td><td><input size=50 name="evpointslost" value="'.$evpointslost.'" /></td></tr>';
	$ret = $ret.'<tr><td>Wieviel Punkte bei gewonnen?</td><td><input size=50 name="evpointswin" value="'.$evpointswin.'" /></td></tr>';
	$ret = $ret.'<tr><td>evsgldarts:</td><td><input size=50 name="evsgldarts" value="'.$evsgldarts.'" /></td></tr>';
	$ret = $ret.'<tr><td>Single: Spieldistanz:</td><td><input size=50 name="evsgldist" value="'.$evsgldist.'" /></td></tr>';
	
	$ret = $ret.'<tr><td>Single: Finishzahl(wenn Finishzahl miterfasst werden sollen dann bitte hier On ansonsten Off):</td><td>';
	$ret = $ret.'<select name="evsglfinish" value="'.$evsglfinish.'"><option value="1" '.($evsglfinish == 1 ? 'selected' : '').'>On</option><option value="0" '.($evsglfinish == 0 ? 'selected' : '').'>Off</option></select></td></tr>';
	
	$ret = $ret.'<tr><td> Single Highscore (z.B. 180er Wertung dann hier On eintragen sonstOff0):</td>';
	$ret = $ret.'<td><select name="evsglhighscore" value="'.$evsglhighscore.'"><option value="1" '.($evsglhighscore == 1 ? 'selected' : '').'>On</option><option value="0" '.($evsglhighscore == 0 ? 'selected' : '').'>Off</option></select></td></tr>';
	
	$ret = $ret.'<tr><td>Single: Best of x Legs:</td><td><input size=50 name="evsgllegs" value="'.$evsgllegs.'" /></td></tr>';
	
	$ret = $ret.'<tr><td>Single: Wenn Restpunkte eingetragen werden sollen dann hier On ansonsten Off:</td>';
	$ret = $ret.'<td><select name="evsglrest" value="'.$evsglrest.'"><option value="1" '.($evsglrest == 1 ? 'selected' : '').'>On</option><option value="0" '.($evsglrest == 0 ? 'selected' : '').'>Off</option></select></td></tr>';
	
	$ret = $ret.'<tr><td>Single: Wenn eingetragen werden soll in welcher Runde gecheckt wurde dann hier On ansonsten Off:</td><td>';
	$ret = $ret.'<select name="evsglroundcheck" value="'.$evsglroundcheck.'"><option value="1" '.($evsglroundcheck == 1 ? 'selected' : '').'>On</option><option value="0" '.($evsglroundcheck == 0 ? 'selected' : '').'>Off</option></select></td></tr>';
	
	$ret = $ret.'<tr><td>Single: Wenn eingetragen werden soll Rundenscore dann hier On ansonsten Off:</td><td>';
	$ret = $ret.'<select name="evsglroundscore" value="'.$evsglroundscore.'"><option value="1" '.($evsglroundscore == 1 ? 'selected' : '').'>On</option><option value="0" '.($evsglroundscore == 0 ? 'selected' : '').'>Off</option></select></td></tr>';
	
	$ret = $ret.'<tr><td>evsglstart:</td><td><input size=50 name="evsglstart" value="'.$evsglstart.'" /></td></tr>';
	$ret = $ret.'<tr><td>Wieviel Doubles werden gespielt?</td><td><input size=50 name="evsingles" value="'.$evsingles.'" /></td></tr>';
	
	$ret = $ret.'<tr><td>Tabellenberechnung erst nach Punkten</td><td>';
	$ret = $ret.'<select name="evtabpoints" value="'.$evtabpoints.'"><option value="1" '.($evtabpoints == 1 ? 'selected' : '').'>Ja</option><option value="0" '.($evtabpoints == 0 ? 'selected' : '').'>Nein</option></select></td></tr>';
	
	$ret = $ret.'<tr><td>Tabellenberechnung erst nach Sätzen:</td><td>';
	$ret = $ret.'<select name="evtabsets" value="'.$evtabsets.'"><option value="1" '.($evtabsets == 1 ? 'selected' : '').'>Ja</option><option value="0" '.($evtabsets == 0 ? 'selected' : '').'>Nein</option></select></td></tr>';
	
	$ret = $ret.'<tr><td>evteamfee:</td><td><input size=50 name="evteamfee" value="'.$evteamfee.'" /></td></tr>';
	
	$ret = $ret.'<tr><td>Single Highscore (z.B. 171er Wertung dann hier On eintragen sonst Off):</td><td>';
	$ret = $ret.'<select name="evsglhighscore171" value="'.$evsglhighscore171.'"><option value="1"  '.($evsglhighscore171 == 1 ? 'selected' : '').'>On</option><option value="0" '.($evsglhighscore171 == 0 ? 'selected' : '').'>Off</option></select></td></tr>';
	
	$ret = $ret.'<tr><td colspan="2" align="center"><input type="submit" value="Save" /></td></tr>';
	$ret = $ret.'</table>';
	$ret = $ret.'</form>';
	return $ret;
}

function save_object_round( $object_id = -1 ) {
	global $dbi;
	
	$id = $object_id;
	$version = $_REQUEST['version'];
	$cfgactive = $_REQUEST['cfgactive'];
	$cfgname = $_REQUEST['cfgname'];
	$evdbldarts = $_REQUEST['evdbldarts'];
	$evdbldist = $_REQUEST['evdbldist'];
	$evdblfinish = $_REQUEST['evdblfinish'];
	$evdblhighscore = $_REQUEST['evdblhighscore'];
	$evdbllegs = $_REQUEST['evdbllegs'];
	$evdblrest = $_REQUEST['evdblrest'];
	$evdblroundcheck = $_REQUEST['evdblroundcheck'];
	$evdblroundscore = $_REQUEST['evdblroundscore'];
	$evdblstart = $_REQUEST['evdblstart'];
  	$evdoubles = $_REQUEST['evdoubles'];
  	$evmaxteamsize = $_REQUEST['evmaxteamsize'];
  	$evnumdown = $_REQUEST['evnumdown'];
  	$evnumrounds = $_REQUEST['evnumrounds'];
  	$evnumup = $_REQUEST['evnumup'];
  	$evplayerfee = $_REQUEST['evplayerfee'];
  	$evpointseven = $_REQUEST['evpointseven'];
  	$evpointslost = $_REQUEST['evpointslost'];
  	$evpointswin = $_REQUEST['evpointswin'];
  	$evsgldarts = $_REQUEST['evsgldarts'];
  	$evsgldist = $_REQUEST['evsgldist'];
  	$evsglfinish = $_REQUEST['evsglfinish'];
  	$evsglhighscore = $_REQUEST['evsglhighscore'];
  	$evsgllegs = $_REQUEST['evsgllegs'];
  	$evsglrest = $_REQUEST['evsglrest'];
  	$evsglroundcheck = $_REQUEST['evsglroundcheck'];
  	$evsglroundscore = $_REQUEST['evsglroundscore'];
  	$evsglstart = $_REQUEST['evsglstart'];
  	$evsingles = $_REQUEST['evsingles'];
  	$evtabpoints = $_REQUEST['evtabpoints'];
  	$evtabsets = $_REQUEST['evtabsets'];
  	$evteamfee = $_REQUEST['evteamfee'];
  	$evsglhighscore171 = $_REQUEST['evsglhighscore171'];
	
	$sql = '';
	if ( !isset( $object_id ) || $object_id == '' ) {
		$sql = 'INSERT INTO tbleventconfig (
		config_id, version, cfgactive, cfgname, evdbldarts, 
		evdbldist, evdblfinish, evdblhighscore, evdbllegs, evdblrest,
		evdblroundcheck, evdblroundscore, evdblstart, evdoubles, evmaxteamsize,
		evnumdown, evnumrounds, evnumup, evplayerfee, evpointseven,
		evpointslost, evpointswin, evsgldarts, evsgldist, evsglfinish,
		evsglhighscore, evsgllegs, evsglrest, evsglroundcheck, evsglroundscore,
		evsglstart, evsingles, evtabpoints, evtabsets, evteamfee, 
		evsglhighscore171) VALUES (null, '.$version.', '.$cfgactive.', "'.$cfgname.'", '.$evdbldarts.', '.$evdbldist
		.', '.$evdblfinish.', '.$evdblhighscore.', '.$evdbllegs.', '.$evdblrest.', '.$evdblroundcheck
		.', '.$evdblroundscore.', '.$evdblstart.', '.$evdoubles.', '.$evmaxteamsize.', '.$evnumdown
		.', '.$evnumrounds.', '.$evnumup.', '.$evplayerfee.', '.$evpointseven.', '.$evpointslost
		.', '.$evpointswin.', '.$evsgldarts.', '.$evsgldist.', '.$evsglfinish.', '.$evsglhighscore
		.', '.$evsgllegs.', '.$evsglrest.', '.$evsglroundcheck.', '.$evsglroundscore.', '.$evsglstart
		.', '.$evsingles.', '.$evtabpoints.', '.$evtabsets.', '.$evteamfee.', '.$evsglhighscore171
		.')';
	} else {
		$sql = 'UPDATE tbleventconfig SET version = "'.$version.'", cfgactive = '.$cfgactive.', cfgname = "'.$cfgname.'", evdbldarts = '.$evdbldarts.', evdbldist = '.$evdbldist
		.', evdblfinish = '.$evdblfinish.', evdblhighscore = '.$evdblhighscore.', evdbllegs = '.$evdbllegs.', evdblrest = '.$evdblrest.', evdblroundcheck = '.$evdblroundcheck
		.', evdblroundscore = '.$evdblroundscore.', evdblstart = '.$evdblstart.', evdoubles = '.$evdoubles.', evmaxteamsize = '.$evmaxteamsize.', evnumdown = '.$evnumdown
		.', evnumrounds = '.$evnumrounds.', evnumup = '.$evnumup.', evplayerfee = '.$evplayerfee.', evpointseven = '.$evpointseven.', evpointslost = '.$evpointslost
		.', evpointswin = '.$evpointswin.', evsgldarts = '.$evsgldarts.', evsgldist = '.$evsgldist.', evsglfinish = '.$evsglfinish.', evsglhighscore = '.$evsglhighscore
		.', evsgllegs = '.$evsgllegs.', evsglrest = '.$evsglrest.', evsglroundcheck = '.$evsglroundcheck.', evsglroundscore = '.$evsglroundscore.', evsglstart = '.$evsglstart
		.', evsingles = '.$evsingles.', evtabpoints = '.$evtabpoints.', evtabsets = '.$evtabsets.', evteamfee = '.$evteamfee.', evsglhighscore171 = '.$evsglhighscore171
		.' WHERE config_id = '.$object_id;
	}
	
	$insert_update_result = sql_query( $sql, $dbi );
	
	echo 'Query: '.$sql.'<br/>';
	echo 'Query result: '.$insert_update_result.'<br/>';
}

function delete_object_round( $object_id ) {
	global $dbi;
	
	$sql = 'DELETE from tbleventconfig WHERE config_id = '.$object_id;	
	$delete_update_result = sql_query( $sql, $dbi );
	
	echo 'Query: '.$sql.'<br/>';
	echo 'Query result: '.$delete_update_result.'<br/>';
}

if ( isset( $_REQUEST['op'] ) && strlen( $_REQUEST['op'] ) < 25 ) { $myop = strip_tags( $_REQUEST['op'] ); } else { $myop = "list"; }
if ( isset( $_REQUEST['id'] ) && strlen( $_REQUEST['id'] ) < 25 ) { $id = strip_tags( $_REQUEST['id'] ); }

# START OUTPUT

LS_page_start('empty');

# Left nav pane echoing
echo '<div class="navi" style="left: 5px;">'._button( 'NEU Spielsystem', '', 'admin_event_configs.php?op=new' )._button( 'Admin home', '', 'admin_main_menu.php' ).'</div>';
echo '<h3>Spielsystem</h3>'; # header and start table

switch ($myop) {
	case "list": echo list_objects(); break;
	case "new": echo object_form(); break;
	case "edit": echo object_form( $id ); break;
	case "save": save_object_round( $id ); echo list_objects(); break;
	case "delete": delete_object_round( $id ); echo list_objects(); break;
}

echo '</td></tr></table>'; # end table

LS_page_end();

?>