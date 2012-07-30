<?php 

require_once('mainfile.php');
require_once('lsdb_layout.php');
require_once('theme/Lite/theme.php');
require_once('func_lsdb.php');

function round_event_list() {
	global $dbi;
	
	$eventsResult = sql_query( 'SELECT * FROM tblevent' , $dbi );
	
	# Table header
	$ret = '<table id="eventtable"><tr>';
	$ret = $ret.'<td class="thead">Id</td>';
	$ret = $ret.'<td class="thead">Rundenname/Eventname</td>';
	$ret = $ret.'<td class="thead">Spieljahr</td>';
	$ret = $ret.'<td class="thead">Active</td>';
	$ret = $ret.'<td class="thead">Status code</td>';
	$ret = $ret.'<td class="thead">Vielleicht Sortierreihenfolge</td>';
	$ret = $ret.'<td class="thead">Welches Passfeld</td>';
	$ret = $ret.'<td class="thead">Type</td>';
	$ret = $ret.'<td class="thead">Verband/Liga</td>';
	$ret = $ret.'<td class="thead">Versionsnummer</td>';
	$ret = $ret.'<td class="thead">Spielsystem</td>';
	$ret = $ret.'<td class="thead">Edit</td>';
	$ret = $ret.'<td class="thead">Delete</td>';
	
	while ( list ( $id, $name, $year, $active, $status_code, $list, $pass_field, $type, $member_code, $version, $config_id ) = sql_fetch_row( $eventsResult, $dbi ) ) {
		$ret = $ret.'<tr>';
		$ret = $ret.'<td>'.$id.'</td>';
		$ret = $ret.'<td>'.$name.'</td>';
		$ret = $ret.'<td>'.$year.'</td>';
		$ret = $ret.'<td>'.($active == 1 ? 'Ja' : 'Nein').'</td>';
		$ret = $ret.'<td>'.$status_code.'</td>';
		$ret = $ret.'<td>'.$list.'</td>';
		$ret = $ret.'<td>'.$pass_field.'</td>';
		
		$result = sql_query( 'SELECT typdesc FROM ttypeliga WHERE id = '.$type, $dbi );
		$type_name = sql_fetch_row( $result, $dbi );
		
		$ret = $ret.'<td>'.$type_name[0].'</td>';
		
		$result = sql_query( 'SELECT vname FROM tverband WHERE id = '.$member_code, $dbi );
		$member_name = sql_fetch_row( $result, $dbi );
		
		$ret = $ret.'<td>'.$member_name[0].'</td>';
		$ret = $ret.'<td>'.$version.'</td>';
		
		$result = sql_query( 'SELECT cfgname FROM tbleventconfig WHERE config_id = '.$config_id, $dbi );
		$config_name = sql_fetch_row( $result, $dbi );
		
		$ret = $ret.'<td>'.$config_name[0].'</td>';
		$ret = $ret.'<td><img src="images/edit24.png" style="cursor: pointer;" onclick="window.location.href = \'admin_rounds_event.php?op=edit&id='.$id.'\'" /></td>';
		$ret = $ret.'<td align="center"><img src="images/del_icon.png" style="cursor: pointer;" onclick="window.location.href = \'admin_rounds_event.php?op=delete&id='.$id.'\'" /></td>';
		$ret = $ret.'</tr>';
	}
	
	return $ret.'</table>';
}

function round_event_form( $event_id = -1 ) {
	global $dbi;
	
	$id = '';
	$name = '';
	$year = '';
	$active = '';
	$status_code = '';
	$list = '';
	$pass_field = '';
	$type = '';
	$member_code = '';
	$version = '';
	$config_id = '';
	
	if ( $event_id != -1 ) {
		$eventResult = sql_query( 'SELECT * FROM tblevent WHERE id = '.$event_id, $dbi );
		list( $id, $name, $year, $active, $status_code, $list, $pass_field, $type, $member_code, $version, $evconfig_id ) = sql_fetch_row( $eventResult, $dbi );
	}
	$ret = '<form method="POST" action="admin_rounds_event.php?op=save&id='.$id.'">';
	$ret = $ret.'<table>';
	//$ret = $ret.'<tr><td>Id:</td><td><input size=50 name="id" value="'.$id.'" /></td></tr>';	
	$ret = $ret.'<tr><td>Name:</td><td><input size=50 name="name" value="'.$name.'" /></td></tr>';
	$ret = $ret.'<tr><td>Year:</td><td><input size=50 name="year" value="'.$year.'" /></td></tr>';
	
	$ret = $ret.'<tr><td>Aktiv:</td><td>';
	$ret = $ret.'<select name="active">';
	$ret = $ret.'<option value="1" '.($active == 1 ? 'selected' : '').'>On</option>';
	$ret = $ret.'<option value="0" '.($active == 0 ? 'selected' : '').'>Off</option>';
	$ret = $ret.'</select>';
	$ret = $ret.'</td></tr>';
	
	$ret = $ret.'<tr><td>Status code:</td><td><input size=50 name="status_code" value="'.$status_code.'" /></td></tr>';
	$ret = $ret.'<tr><td>List:</td><td><input size=50 name="list" value="'.$list.'" /></td></tr>';
	
	$ret = $ret.'<tr><td>Welches Passfeld soll benutzt werden:</td><td>';
	$ret = $ret.'<select name="pass_field" value="'.$pass_field.'">';
	$ret = $ret.'<option value="pfkey2" '.($pass_field == 'pfkey2' ? 'selected' : '').'>pfkey2</option>';
	$ret = $ret.'<option value="pfkey1" '.($pass_field == 'pfkey1' ? 'selected' : '').'>pfkey1</option>';
	$ret = $ret.'</select>';
	$ret = $ret.'</td></tr>';
	
	$ret = $ret.'<tr><td>Type:</td><td>';
	$ret = $ret.'<select name="type">';
	$types_result = sql_query( 'SELECT id, typdesc FROM ttypeliga', $dbi );
	while ( list( $type_id, $type_name ) = sql_fetch_row( $types_result, $dbi ) ) {
		$ret = $ret.'<option value="'.$type_id.'" '.($type_id == $type ? 'selected' : '').'>'.$type_name.'</option>';
	} 
	$ret = $ret.'</select>';
	$ret = $ret.'</td></tr>';
	
	$ret = $ret.'<tr><td>Member code:</td><td>';
	$ret = $ret.'<select name="member_code">';
	$member_result = sql_query( 'SELECT id, vname FROM tverband', $dbi );
	while ( list( $member_id, $member_name ) = sql_fetch_row( $member_result, $dbi ) ) {
		$ret = $ret.'<option value="'.$member_id.'" '.($member_id == $member_code ? 'selected' : '').'>'.$member_name.'</option>';
	} 
	$ret = $ret.'</select>';
	$ret = $ret.'</td></tr>';
	
	$ret = $ret.'<tr><td>Version:</td><td><input size=50 name="version" value="'.$version.'" /></td></tr>';
	
	$ret = $ret.'<tr><td>Config id:</td><td>';
	$ret = $ret.'<select name="config_id">';
	$config_result = sql_query( 'SELECT config_id, cfgname FROM tbleventconfig', $dbi );
	while ( list( $config_id, $config_name ) = sql_fetch_row( $config_result, $dbi ) ) {
		$ret = $ret.'<option value="'.$config_id.'" '.($config_id == $evconfig_id ? 'selected' : '').'>'.$config_name.'</option>';
	} 
	$ret = $ret.'</select>';
	$ret = $ret.'</td></tr>';
	
	$ret = $ret.'<tr><td colspan="2" align="center"><input type="submit" value="Save" /></td></tr>';
	$ret = $ret.'</table>';
	$ret = $ret.'</form>';
	return $ret;
}

function save_event_round( $event_id = -1 ) {
	global $dbi;
	
	$name = $_REQUEST['name'];
	$year = $_REQUEST['year'];
	$active = $_REQUEST['active'];
	$status_code = $_REQUEST['status_code'];
	$list = $_REQUEST['list'];
	$pass_field = $_REQUEST['pass_field'];
	$type = $_REQUEST['type'];
	$member_code = $_REQUEST['member_code'];
	$version = $_REQUEST['version'];
	$config_id = $_REQUEST['config_id'];
	
	$sql = '';
	if ( !isset( $event_id ) || $event_id == '' ) {
		$sql = 'INSERT INTO tblevent (id, evname, evyear, evactive, evstatcode_id, evlistidx, evpassfield, evtypecode_id, evmembercode_id, version, evconfig_id) VALUES (null, "'.$name.'", "'.$year.'", '.$active.', '.$status_code.', '.$list.',"'.$pass_field.'", '.$type.',  '.$member_code.', '.$version.', '.$config_id.')';
	} else {
		$sql = 'UPDATE tblevent SET evname = "'.$name.'", evyear = "'.$year.'", evactive = '.$active.', evstatcode_id = '.$status_code.', evlistidx = '.$list.', evpassfield = "'.$pass_field.'", evtypecode_id = '.$type.', evmembercode_id = '.$member_code.', version = '.$version.', evconfig_id = '.$config_id.' WHERE id = '.$event_id;
	}
	
	$insert_update_result = sql_query( $sql, $dbi );
	
	echo 'Query: '.$sql.'<br/>';
	echo 'Query result: '.$insert_update_result.'<br/>';
}

function delete_event_round( $event_id ) {
	global $dbi;
	
	$sql = 'DELETE from tblevent WHERE id = '.$event_id;	
	$delete_update_result = sql_query( $sql, $dbi );
	
	echo 'Query: '.$sql.'<br/>';
	echo 'Query result: '.$delete_update_result.'<br/>';
}

if ( isset( $_REQUEST['op'] ) && strlen( $_REQUEST['op'] ) < 25 ) { $myop = strip_tags( $_REQUEST['op'] ); } else { $myop = "round_list"; }
if ( isset( $_REQUEST['id'] ) && strlen( $_REQUEST['id'] ) < 25 ) { $id = strip_tags( $_REQUEST['id'] ); }

# START OUTPUT

LS_page_start('empty');

# Left nav pane echoing
echo '<div class="navi" style="left: 5px;">'._button( 'NEU Runde/Event', '', 'admin_rounds_event.php?op=new' )._button( 'Admin home', '', 'admin_main_menu.php' ).'</div>';
echo '<h3>Neue Runde/Event</h3>'; # header and start table

switch ($myop) {
	case "round_list": echo round_event_list(); break;
	case "new": echo round_event_form(); break;
	case "edit": echo round_event_form( $id ); break;
	case "save": save_event_round( $id ); echo round_event_list(); break;
	case "delete": delete_event_round( $id ); echo round_event_list(); break;
}

echo '</td></tr></table>'; # end table

LS_page_end();

?>