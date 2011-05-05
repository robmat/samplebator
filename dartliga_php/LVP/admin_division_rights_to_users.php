<?php 

require_once('mainfile.php');
require_once('lsdb_layout.php');
require_once('theme/Lite/theme.php');
require_once('func_lsdb.php');

global $usertoken;

# List all admin liga users
function admin_liga_list() {
	global $dbi;
	$sql =  'SELECT a.id, a.auname, e.evname, c.acdesc, a.aevactive, a.acomment '.
			'FROM tbladminliga a, tblevent e, tligaaccesscode c '.
			'WHERE a.aevcode_id = e.id '.
			'AND a.access_id = c.access_id '.
			'ORDER BY a.id';
	$query_result = sql_query( $sql, $dbi );
	
	# Table header
	$ret = '<table><tr><td class="thead">Id</td><td class="thead">Admin user name</td><td class="thead">Liga name</td>';
	$ret = $ret.'<td class="thead">Access</td><td class="thead">Active</td><td class="thead">Comment</td><td class="thead">Del.</td></tr>';
	
	while ( list( $id, $auname, $evname, $acdesc, $aevactive, $acomment ) = sql_fetch_row( $query_result, $dbi ) ) {
		$ret = $ret.'<tr><td>'.$id.'</td><td>'.$auname.'</td><td>'.$evname.'</td><td>'.$acdesc.'</td><td>'.$aevactive.'</td><td>'.$acomment.'</td>';
		$ret = $ret.'<td><img src="images/del_icon.png" style="cursor: pointer;" onclick="deleteAdminLiga('.$id.');" /></td></tr>';
	}
	
	return $ret.'</table>';
}

# New admin user form
function new_admin_liga() {
	if ( isset( $_REQUEST['name'] ) ) { $name=strip_tags( $_REQUEST['name'] ); }
	if ( isset( $_REQUEST['uname'] ) ) { $uname=strip_tags( $_REQUEST['uname'] ); }
	if ( isset( $_REQUEST['liganame'] ) ) { $liganame=strip_tags( $_REQUEST['liganame'] ); }
	if ( isset( $_REQUEST['accesstype'] ) ) { $accesstype=strip_tags( $_REQUEST['accesstype'] ); }
	if ( isset( $_REQUEST['active'] ) ) { $active=strip_tags( $_REQUEST['active'] ); }
	if ( isset( $_REQUEST['comment'] ) ) { $comment=strip_tags( $_REQUEST['comment'] ); }
	
	global $dbi;
	$user_query_result = sql_query( 'SELECT u.id, u.uname FROM tuser u', $dbi );
	$liga_query_result = sql_query( 'SELECT e.id, e.evname FROM tblevent e', $dbi );
	$accs_query_result = sql_query( 'SELECT a.access_id, a.acdesc FROM tligaaccesscode a', $dbi );
	
	$ret = '<form action="admin_division_rights_to_users.php?op=new_liga_user_right_creation" method="post">';
	$ret = $ret.'<table><tr><td>User name:</td><td>';
	$ret = $ret.'<select id="uname" name="uname">';
	while ( list( $id, $name ) = sql_fetch_row( $user_query_result, $dbi ) ) {
		$selected = strcmp( $uname, $id ) == 0 ? 'selected="selected"' : '';
		$ret = $ret.'<option '.$selected.' value="'.$id.'">'.$name.'</option>';
	}
	$ret = $ret.'</select></td></tr><tr><td>League:</td><td>';
	$ret = $ret.'<select id="liganame" name="liganame">';
	while ( list( $id, $name ) = sql_fetch_row( $liga_query_result, $dbi ) ) {
		$selected = strcmp( $liganame, $id ) == 0 ? 'selected="selected"' : '';
		$ret = $ret.'<option '.$selected.' value="'.$id.'">'.$name.'</option>';
	}
	$ret = $ret.'</select></td></tr><tr><td>Access type:</td><td>';
	$ret = $ret.'<select id="accesstype" name="accesstype">';
	while ( list( $id, $name ) = sql_fetch_row( $accs_query_result, $dbi ) ) {
		$selected = strcmp( $accesstype, $id ) == 0 ? 'selected="selected"' : '';
		$ret = $ret.'<option '.$selected.' value="'.$id.'">'.$name.'</option>';
	}
	$ret = $ret.'</select></td></tr><tr><td>Active:</td><td>';
	$ret = $ret.'<select id="active" name="active">';
	$ret = $ret.'<option value="1">Yes</option>';
	$ret = $ret.'<option value="0">No</option></select>';
	$ret = $ret.'</td></tr><tr><td>Comment:</td><td>';
	$ret = $ret._input( 1, 'comment', $comment, 50, 50 );
	$ret = $ret.'</td></tr><tr><td>Create date:</td><td>';
	$ret = $ret.date('l jS \of F Y h:i:s A');
	$ret = $ret.'</td></tr><tr><td>Right name:</td><td>';
	$ret = $ret._input( 1, 'name', $name, 50, 50 );
	$ret = $ret.'</td></tr><tr><td></td><td>';
	$ret = $ret._button( 'Create right', '', 'admin_division_rights_to_users.php?op=new_liga_user_right_creation' );
	$ret = $ret.'</td></tr></table></form>';
	return $ret;
}

# AJAX called delete method
function delete_admin_liga() {
	global $dbi;
	if ( isset( $_REQUEST['del_id'] ) ) { $del_id=strip_tags( $_REQUEST['del_id'] ); }
	$delete_result = sql_query( 'DELETE FROM tbladminliga WHERE id = '.$del_id, $dbi );
	if ( $delete_result ) {
		return '[{<>}]delete_ok_token[{<>}]';
	} else {
		return '[{<>}]delete_failed_token[{<>}]';
	}
	return '';
}

# Creation of a new liga user right
function new_liga_user_right_creation() {
	global $dbi;

	$ret = '<div style="color: red; padding: 5px;">';
	if ( empty( $_REQUEST['name'] ) ) { $ret = $ret.'Name required!<br/>'; }
	if ( empty( $_REQUEST['active'] ) ) { $ret = $ret.'Active has to have a value of 0 or 1!<br/>'; }
	if ( $active != 0 && $active != 1 ) { $ret = $ret.'Active has to have a value of 0 or 1!<br/>'; }
	$ret = $ret.'</div>';
	
	if ( strcmp( $ret, '<div style="color: red; padding: 5px;"></div>' )  == 0 ) { # valid
		$name = $_REQUEST['name'];
		$uname = $_REQUEST['uname'];
		$liganame = $_REQUEST['liganame'];
		$accesstype = $_REQUEST['accesstype'];
		$active = $_REQUEST['active'];
		$comment = $_REQUEST['comment'];
		$sql = 'INSERT INTO tbladminliga (id, version, auname, access_id, aevactive, auid_id, cre_date, aevcode_id, acomment) ';
		$sql = $sql.'VALUES (NULL, "0", "'.$name.'", "'.$accesstype.'", '.$active.', '.$uname.', "'.date("Y-m-d H:i:s").'", '.$liganame.', "'.$comment.'")';
		$ret = $ret.$sql;
		$insert_result = sql_query( $sql, $dbi );
		
		if ($insert_result == TRUE) {
			$ret = 'Creation successful!<br/><br/>';
			$ret = $ret.admin_liga_list();		
		} else {
			$ret = $ret.'<div style="color: red;">Creation of a user liga right failed for unknown reasons!</div>';		
		}
	} else {
		$ret = $ret.new_admin_liga();
	}
	return $ret;
}

# START OUTPUT

LS_page_start();

if ( isset( $_REQUEST['op'] ) && strlen( $_REQUEST['op'] ) < 35 ) { $myop=strip_tags( $_REQUEST['op'] ); } else { $myop = "admin_liga_list"; }

if ( !isset( $usertoken ) ) {
	echo '<script> window.location.href = "/dso_user.php" </script>';
} else {
	echo '<h3>Liga gruppen rechte zuweisung</h3><table class="box"><tr><td>'; # header and start table
	
	# Left nav pane echoing
	echo '<div class="navi" style="left: 5px;">'._button( 'New admin liga', '', 'admin_division_rights_to_users.php?op=new_admin_liga' );
	echo _button( 'Admin home', '', 'admin_main_menu.php' ).'</div>';
	
	switch ( $myop ) {
		case 'admin_liga_list': { echo admin_liga_list(); break; }
		case 'new_admin_liga': { echo new_admin_liga(); break; }
		case 'del_admin_user': { echo delete_admin_liga(); break; }
		case 'new_liga_user_right_creation': { echo new_liga_user_right_creation(); }
	}
}

echo '</td></tr></table>'; # end table

LS_page_end();

?>