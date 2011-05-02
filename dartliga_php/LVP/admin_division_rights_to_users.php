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
	$ret = $ret.'<td class="thead">Access</td><td class="thead">Active</td><td class="thead">Comment</td></tr>';
	
	while ( list( $id, $auname, $evname, $acdesc, $aevactive, $acomment ) = sql_fetch_row( $query_result, $dbi ) ) {
		$ret = $ret.'<tr><td>'.$id.'</td><td>'.$auname.'</td><td>'.$evname.'</td><td>'.$acdesc.'</td><td>'.$aevactive.'</td><td>'.$acomment.'</td>';
		$ret = $ret.'<td><img src="images/del_icon.png" style="cursor: pointer;" onclick="deleteAdminLiga('.$id.');" /></td></tr>';
	}
	
	return $ret.'</table>';
}

# New admin user form
function new_admin_liga() {
	
}

# AJAX called delete method
function delete_admin_liga() {
	global $dbi;
	if ( isset( $_REQUEST['del_id'] ) ) { $del_id=strip_tags( $_REQUEST['del_id'] ); }
	$sql = 'DELETE FROM tbladminliga WHERE id = '.$del_id;
	$delete_result = sql_query( $sql, $dbi );
	if ( $delete_result ) {
		return '[{<>}]delete_ok_token[{<>}]';
	} else {
		return '[{<>}]delete_failed_token[{<>}]';
	}
	return '';
}

# START OUTPUT

LS_page_start();

if ( isset( $_REQUEST['op'] ) && strlen( $_REQUEST['op'] ) < 25 ) { $myop=strip_tags( $_REQUEST['op'] ); } else { $myop = "admin_liga_list"; }

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
	}
}

echo '</td></tr></table>'; # end table

LS_page_end();

?>