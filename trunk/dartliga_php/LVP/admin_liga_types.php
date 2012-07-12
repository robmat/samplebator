<?php 

require_once('mainfile.php');
require_once('lsdb_layout.php');
require_once('theme/Lite/theme.php');
require_once('func_lsdb.php');

function object_list() {
	global $dbi;
	
	$eventsResult = sql_query( 'SELECT id, version, typactive, typdesc, ttypeliga.mgroup_id FROM ttypeliga LEFT JOIN tmessagegroup ON ttypeliga.mgroup_id = tmessagegroup.mgroup_id' , $dbi );
	
	# Table header
	$ret = '<table id="eventtable"><tr>';
	$ret = $ret.'<td class="thead">Id</td>';
	$ret = $ret.'<td class="thead">Version</td>';
	$ret = $ret.'<td class="thead">Active</td>';
	$ret = $ret.'<td class="thead">Description</td>';
	$ret = $ret.'<td class="thead">Message group id</td>';
	
	while ( list( $id, $version, $typactive, $typdesc, $mgroup_id ) = sql_fetch_row( $eventsResult, $dbi ) ) {
		$ret = $ret.'<tr>';
		$ret = $ret.'<td>'.$id.'</td>';
		$ret = $ret.'<td>'.$version.'</td>';
		$ret = $ret.'<td>'.$typactive.'</td>';
		$ret = $ret.'<td>'.$typdesc.'</td>';
		$ret = $ret.'<td>'.$mgroup_id.'</td>';
		$ret = $ret.'<td><img src="images/edit24.png" style="cursor: pointer;" onclick="window.location.href = \'admin_liga_types.php?op=edit&id='.$id.'\'" /></td>';
		$ret = $ret.'<td align="center"><img src="images/del_icon.png" style="cursor: pointer;" onclick="window.location.href = \'admin_liga_types.php?op=delete&id='.$id.'\'" /></td>';
		$ret = $ret.'</tr>';
	}
	
	return $ret.'</table>';
}

function object_form( $event_id = -1 ) {
	global $dbi;
	
	$id = '';
	$version = '';
	$typactive = '';
	$typdesc = '';
	$mgroup_id = '';
	
	if ( $event_id != -1 ) {
		$eventResult = sql_query( 'SELECT * FROM ttypeliga WHERE id = '.$event_id, $dbi );
		list( $id, $version, $typactive, $typdesc, $mgroup_id ) = sql_fetch_row( $eventResult, $dbi );
	}
	$ret = '<form method="POST" action="admin_liga_types.php?op=save&id='.$id.'">';
	$ret = $ret.'<table>';
	//$ret = $ret.'<tr><td>Id:</td><td><input size=50 name="id" value="'.$id.'" /></td></tr>';	
	$ret = $ret.'<tr><td>Version:</td><td><input size=50 name="version" value="'.$version.'" /></td></tr>';
	$ret = $ret.'<tr><td>Active:</td><td><input size=50 name="typactive" value="'.$typactive.'" /></td></tr>';
	$ret = $ret.'<tr><td>Description:</td><td><input size=50 name="typdesc" value="'.$typdesc.'" /></td></tr>';
	$ret = $ret.'<tr><td>Message group id:</td><td><input size=50 name="mgroup_id" value="'.$mgroup_id.'" /></td></tr>';
	$ret = $ret.'<tr><td colspan="2" align="center"><input type="submit" value="Save" /></td></tr>';
	$ret = $ret.'</table>';
	$ret = $ret.'</form>';
	return $ret;
}

function save_object( $event_id = -1 ) {
	global $dbi;
	
	$version = $_REQUEST['version'];
	$typactive = $_REQUEST['typactive'];
	$typdesc = $_REQUEST['typdesc'];
	$mgroup_id = $_REQUEST['mgroup_id'];
	
	$sql = '';
	if ( !isset( $event_id ) || $event_id == '' ) {
		$sql = 'INSERT INTO ttypeliga (id, version, typactive, typdesc, mgroup_id) VALUES (null, '.$version.', '.$typactive.', "'.$typdesc.'", '.$mgroup_id.')';
	} else {
		$sql = 'UPDATE ttypeliga SET version = '.$version.', typactive = '.$typactive.', typdesc = "'.$typdesc.'", mgroup_id = '.$mgroup_id.' WHERE id = '.$event_id;
	}
	
	$insert_update_result = sql_query( $sql, $dbi );
	
	echo 'Query: '.$sql.'<br/>';
	echo 'Query result: '.$insert_update_result.'<br/>';
}

function delete_object( $event_id ) {
	global $dbi;
	
	$sql = 'DELETE from ttypeliga WHERE id = '.$event_id;	
	$delete_update_result = sql_query( $sql, $dbi );
	
	echo 'Query: '.$sql.'<br/>';
	echo 'Query result: '.$delete_update_result.'<br/>';
}

if ( isset( $_REQUEST['op'] ) && strlen( $_REQUEST['op'] ) < 25 ) { $myop = strip_tags( $_REQUEST['op'] ); } else { $myop = "list"; }
if ( isset( $_REQUEST['id'] ) && strlen( $_REQUEST['id'] ) < 25 ) { $id = strip_tags( $_REQUEST['id'] ); }

# START OUTPUT

LS_page_start('empty');

# Left nav pane echoing
echo '<div class="navi" style="left: 5px;">'._button( 'NEU Klass. / Msggr.', '', 'admin_liga_types.php?op=new' )._button( 'Admin home', '', 'admin_main_menu.php' ).'</div>';
echo '<h3>Klassen / Messagegroups</h3>'; # header and start table

switch ($myop) {
	case "list": echo object_list(); break;
	case "new": echo object_form(); break;
	case "edit": echo object_form( $id ); break;
	case "save": save_object( $id ); echo object_list(); break;
	case "delete": delete_object( $id ); echo object_list(); break;
}

echo '</td></tr></table>'; # end table

LS_page_end();

?>