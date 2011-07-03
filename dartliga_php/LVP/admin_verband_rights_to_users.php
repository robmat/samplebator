<?php 

require_once('mainfile.php');
require_once('lsdb_layout.php');
require_once('theme/Lite/theme.php');
require_once('func_lsdb.php');

global $usertoken;

# List all admin liga users
function verband_rights_list() {
	global $dbi;
	$sql =  'SELECT rm.regmap_id, rm.user_id, rm.reg_id, rm.tactive, rm.cre_date, rm.cre_user, rm.comment, v.vname, u.fullname, rac.regdesc '.
			'FROM tregistermap rm, tverband v, tuser u, tregisteraccesscode rac '.
			'WHERE rm.verband_id = v.id AND rm.user_id = u.id AND rm.reg_id = rac.id '.
			'ORDER BY u.uname';
	$query_result = sql_query( $sql, $dbi );
	
	# Table header
	$ret = '<table><tr><td class="thead">Id</td><td class="thead">Admin user name</td><td class="thead">Verband name</td><td class="thead">Access</td>';
	$ret = $ret.'<td class="thead">Active</td><td class="thead">Comment</td><td class="thead">Creation date</td><td class="thead">Created by</td>';
	$ret = $ret.'<td class="thead">Del.</td><td class="thead">Edit</td></tr>';
	
	while ( list( $regmapid, $uid, $regaccid, $regactive, $cre_date, $cre_user, $comment, $verbandname, $username, $regmapdesc ) = sql_fetch_row( $query_result, $dbi ) ) {
		$ret = $ret.'<tr><td>'.$regmapid.'</td><td>'.$username.'</td><td>'.$verbandname;
		$ret = $ret.'</td><td>'.$regmapdesc.'</td><td>'.$regactive.'</td><td>'.$comment.'</td>';
		$ret = $ret.'<td>'.$cre_date.'</td><td>'.$cre_user.'</td>';
		$ret = $ret.'<td><img src="images/del_icon.png" style="cursor: pointer;" onclick="deleteAdminVerband('.$regmapid.');" /></td>';
		$location_path = 'admin_verband_rights_to_users.php?op=new_admin_verband&vid='.$regmapid;
		$ret = $ret.'<td><img src="images/edit24.png" style="cursor: pointer;" onclick="window.location.href = \''.$location_path.'\'" /></td></tr>';
	}
	
	return $ret.'</table>';
}

# New admin user form
function new_admin_verband() {
	global $dbi, $usertoken;	
	if ( isset( $_REQUEST['uname'] ) ) { $uname=strip_tags( $_REQUEST['uname'] ); }
	if ( isset( $_REQUEST['verbandname'] ) ) { $verbandname=strip_tags( $_REQUEST['verbandname'] ); }
	if ( isset( $_REQUEST['accesstype'] ) ) { $accesstype=strip_tags( $_REQUEST['accesstype'] ); }
	if ( isset( $_REQUEST['active'] ) ) { $active=strip_tags( $_REQUEST['active'] ); }
	if ( isset( $_REQUEST['comment'] ) ) { $comment=strip_tags( $_REQUEST['comment'] ); }
	
	if ( isset( $_REQUEST['vid'] ) && !empty( $_REQUEST['vid'] ) ) {
		$vid = $_REQUEST['vid'];
		$sql = 'SELECT rm.reg_id, rm.tactive, rm.user_id, rm.cre_date, rm.cre_user, rm.verband_id, rm.comment FROM tregistermap rm WHERE rm.regmap_id = '.$vid;
		$admin_verband_result = sql_query( $sql, $dbi );
		list( $accesstype, $aevactive, $uname, $cre_date, $cre_user, $verbandname, $comment ) = sql_fetch_row( $admin_verband_result, $dbi );
	}
	
	$user_query_result = sql_query( 'SELECT u.id, u.uname FROM tuser u ORDER BY u.uname', $dbi );
	$verb_query_result = sql_query( 'SELECT v.id, v.vname FROM tverband v ORDER BY v.vname', $dbi );
	$accs_query_result = sql_query( 'SELECT rac.id, rac.regdesc FROM tregisteraccesscode rac', $dbi );
	
	$ret = '<form action="admin_verband_rights_to_users.php?op=new_verband_user_right_creation&vid='.$vid.'" method="post">';
	$ret = $ret.'<table><tr><td>User name:</td><td>';
	$ret = $ret.'<select id="uname" name="uname">';
	while ( list( $id, $name ) = sql_fetch_row( $user_query_result, $dbi ) ) {
		$selected = strcmp( $uname, $id ) == 0 ? 'selected="selected"' : '';
		$ret = $ret.'<option '.$selected.' value="'.$id.'">'.$name.'</option>';
	}
	$ret = $ret.'</select></td></tr><tr><td>Verband:</td><td>';
	$ret = $ret.'<select id="verbandname" name="verbandname">';
	while ( list( $id, $name ) = sql_fetch_row( $verb_query_result, $dbi ) ) {
		$selected = strcmp( $verbandname, $id ) == 0 ? 'selected="selected"' : '';
		$ret = $ret.'<option '.$selected.' value="'.$id.'">'.$name.' '.$evyear.'</option>';
	}
	$ret = $ret.'</select></td></tr><tr><td>Access type:</td><td>';
	$ret = $ret.'<select id="accesstype" name="accesstype">';
	while ( list( $id, $name ) = sql_fetch_row( $accs_query_result, $dbi ) ) {
		$selected = strcmp( $accesstype, $id ) == 0 ? 'selected="selected"' : '';
		$ret = $ret.'<option '.$selected.' value="'.$id.'">'.$name.'</option>';
	}
	$ret = $ret.'</select></td></tr><tr><td>Active:</td><td>';
	$ret = $ret.'<select id="active" name="active">';
	$ret = $ret.'<option value="1" '.( $active == 1 ? 'selected' : '' ).'>Yes</option>';
	$ret = $ret.'<option value="0" '.( $active == 0 ? 'selected' : '' ).'>No</option></select>';
	$ret = $ret.'</td></tr><tr><td>Comment:</td><td>';
	$ret = $ret._input( 1, 'comment', $comment, 50, 50 );
	$ret = $ret.'</td></tr><tr><td>Create date:</td><td>';
	$ret = $ret.( empty( $vid ) ? date('l jS \of F Y h:i:s A') : $cre_date );
	$ret = $ret.'</td></tr>';
	$ret = $ret.'<tr><td>Created by:</td><td>';
	$ret = $ret.( empty( $vid ) ? $usertoken['uname'] : $cre_user );
	$ret = $ret.'</td></tr>';
	$ret = $ret.'<tr><td></td><td>';
	$ret = $ret._button( empty( $vid ) ? 'Create right' : 'Edit right', '', 'admin_division_rights_to_users.php?op=new_liga_user_right_creation' );
	$ret = $ret.'</td></tr></table></form>';
	return $ret;
}

# Creation of a new verband user right
function new_verband_user_right_creation() {
	global $dbi, $usertoken;

	$ret = '<div style="color: red; padding: 5px;">';
	$ret = $ret.'</div>'; // no validation whatsoever
	
	
	if ( strcmp( $ret, '<div style="color: red; padding: 5px;"></div>' )  == 0 ) { # valid
		$vid = $_REQUEST['vid'];
		$uname = $_REQUEST['uname'];
		$verbandname = $_REQUEST['verbandname'];
		$accesstype = $_REQUEST['accesstype'];
		$active = $_REQUEST['active'];
		$comment = $_REQUEST['comment'];
		$sql = 'INSERT INTO tregistermap (regmap_id, user_id, verband_id, reg_id, tactive, cre_date, cre_user, comment) ';
		$sql = $sql.'VALUES (NULL, '.$uname.', '.$verbandname.', "'.$accesstype.'", '.$active.', "'.date("Y-m-d H:i:s").'", "'.$usertoken['uname'].'", "'.$comment.'")';
		
		if ( !empty( $vid ) ) {
			$sql = 'UPDATE tregistermap SET user_id = '.$uname.', reg_id = '.$accesstype.', tactive = '.$active.', verband_id = '.$verbandname.', comment = "'.$comment.'" WHERE regmap_id = '.$vid;
		}
		
		$insert_result = sql_query( $sql, $dbi );
		
		if ($insert_result == TRUE) {
			$ret = 'Operation successful!<br/><br/>';
			$ret = $ret.verband_rights_list();		
		} else {
			$ret = $ret.'<div style="color: red;">Creation/editing of a user liga right failed for unknown reasons!</div>';		
		}
	} else {
		$ret = $ret.new_admin_verband();
	}
	return $ret;
}

# AJAX called delete method
function delete_admin_verband() {
	global $dbi;
	if ( isset( $_REQUEST['del_id'] ) ) { $del_id=strip_tags( $_REQUEST['del_id'] ); }
	$delete_result = sql_query( 'DELETE FROM tregistermap WHERE regmap_id = '.$del_id, $dbi );
	if ( $delete_result ) {
		return '[{<>}]delete_ok_token[{<>}]';
	} else {
		return '[{<>}]delete_failed_token[{<>}]';
	}
	return '';
}

# START OUTPUT

LS_page_start('empty');

if ( isset( $_REQUEST['op'] ) && strlen( $_REQUEST['op'] ) < 35 ) { $myop=strip_tags( $_REQUEST['op'] ); } else { $myop = "verband_rights_list"; }

if ( !isset( $usertoken ) ) {
	echo '<script> window.location.href = "/dso_user.php" </script>';
} else {
	echo '<h3>Verband rechte zuweisung</h3><table class="box"><tr><td>'; # header and start table
	
	# Left nav pane echoing
	echo '<div class="navi" style="left: 5px;">'._button( 'New verband admin', '', 'admin_verband_rights_to_users.php?op=new_admin_verband' );
	echo _button( 'Admin home', '', 'admin_main_menu.php' ).'</div>';
	
	switch ( $myop ) {
		case 'verband_rights_list': { echo verband_rights_list(); break; }
		case 'new_admin_verband': { echo new_admin_verband(); break; }
		case 'del_admin_verband': { echo delete_admin_verband(); break; }
		case 'new_verband_user_right_creation': { echo new_verband_user_right_creation(); }
	}
}

echo '</td></tr></table>'; # end table

LS_page_end();


?>