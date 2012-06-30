<?php 

require_once('mainfile.php');
require_once('lsdb_layout.php');
require_once('theme/Lite/theme.php');
require_once('func_lsdb.php');

# Return full user list html
function verband_list() {
	global $dbi;
	$verband_result = sql_query( 'SELECT v.id, v.vcode,	v.vname, v.vlogic, v.vactive FROM tverband v ORDER BY v.id ASC', $dbi );
	
	# Table header
	$ret = '<table><tr><td class="thead">Act.</td><td class="thead">Id</td><td class="thead">Code</td>';
	$ret = $ret.'<td class="thead">Name</td><td class="thead">Verband logic</td><td class="thead">Edit</td></tr>';
	
	while ( list( $id, $vcode, $vname, $vlogic, $vactive ) = sql_fetch_row( $verband_result, $dbi ) ) {
		$ret = $ret.'<tr><td>'.( $vactive == 1 ? 'Yes' : 'No').'</td>';
		$ret = $ret.'<td>'.$id.'</td><td>'.$vcode.'</td><td>'.$vname.'</td><td>'.$vlogic.'</td>';
		$location_path = 'admin_verband_management.php?op=new_verband&vid='.$id;
		$ret = $ret.'<td><img src="images/edit24.png" style="cursor: pointer;" onclick="window.location.href = \''.$location_path.'\'" /></td></tr>';
	}
	
	return $ret;
}
# Output verband form
function verband_form() {
	global $dbi;
	
	if ( isset( $_REQUEST['vcode'] ) ) { $vcode=strip_tags( $_REQUEST['vcode'] ); }
	if ( isset( $_REQUEST['vname'] ) ) { $vname=strip_tags( $_REQUEST['vname'] ); }
	if ( isset( $_REQUEST['vlogic'] ) ) { $vlogic=strip_tags( $_REQUEST['vlogic'] ); }
	if ( isset( $_REQUEST['vactive'] ) ) { $vactive=strip_tags( $_REQUEST['vactive'] ); }
		
	$vcode = ''; $vname = ''; $vlogic = ''; $vactive = '';
	
	if ( isset( $_REQUEST['vid'] ) && !empty( $_REQUEST['vid'] ) ) { 
		$vid = $_REQUEST['vid'];
		$verband_result = sql_query( 'SELECT v.id, v.vcode,	v.vname, v.vlogic, v.vactive FROM tverband v WHERE v.id = '.$vid, $dbi );
		list( $id, $vcode, $vname, $vlogic, $vactive ) = sql_fetch_row( $verband_result, $dbi );
		$vid = '&vid='.$vid;
	}
	$ret = '<form action="admin_verband_management.php?op=new_verband_creation'.$vid.'" method="post">';
	$ret = $ret.'<h3>Verband und berecht</h3><table border="0" cellpadding="0" align="left">';
	$ret = $ret.'<tr><td>Verband name: </td><td>'._input( 1, 'vname', $vname, 50, 50 ).'</td></tr>';
	$ret = $ret.'<tr><td>Verband code: </td><td>'._input( 1, 'vcode', $vcode, 50, 50 ).'</td></tr>';
	$ret = $ret.'<tr><td>Verband logic:</td><td>'._input( 1, 'vlogic', $vlogic, 50, 50 ).'</td></tr>';
	$ret = $ret.'<tr><td>Active:</td><td><select id="vactive" name="vactive">';
	$ret = $ret.'<option value="1" '.( $vactive == 1 ? 'selected="selected"' : '' ).'>Active</option>';
	$ret = $ret.'<option value="0" '.( $vactive == 0 ? 'selected="selected"' : '' ).'>Inactive</option>';
	$ret = $ret.'</select></td></tr>';
	$ret = $ret.'</table>'._button( !empty( $_REQUEST['vid'] ) ? 'Update verband' : 'Create verband' ).'</form>';
	return $ret;
}
#Validate verband form
function verband_validate_form() {
	global $dbi;

	$ret = '<div style="color: red; padding: 5px;">';
	if ( empty( $_REQUEST['vname'] ) ) { $ret = $ret.'Name required!<br/>'; }
	if ( empty( $_REQUEST['vcode'] ) ) { $ret = $ret.'Code required!<br/>'; }
	$ret = $ret.'</div>';
	
	if ( strcmp( $ret, '<div style="color: red; padding: 5px;"></div>' )  == 0 ) { # valid
		$vname = $_REQUEST['vname'];
		$vcode = $_REQUEST['vcode'];
		$vlogic = $_REQUEST['vlogic'];
		$vactive = $_REQUEST['vactive'];
		$vid = $_REQUEST['vid'];
		
		$sql = 'INSERT INTO tverband (id, vcode, vname, vlogic, version, vactive) VALUES ';
		$sql = $sql.' (0, "'.$vcode.'", "'.$vname.'", "'.$vlogic.'", "1", '.$vactive.') ';
		
		if ( isset( $vid ) && !empty( $vid ) ) {
			$sql = 'UPDATE tverband SET vname = "'.$vname.'", vcode = "'.$vcode.'", vlogic = "'.$vlogic.'", vactive = '.$vactive.' WHERE id = '.$vid;
		}
		
		$insert_update_result = sql_query( $sql, $dbi );

		if ($insert_update_result == TRUE) {
			$ret = $ret.'<script> window.location.href = "admin_verband_management.php?op=new_verband_created" </script>';		
		} else {
			$ret = $ret.'<div style="color: red;">Creation/update of a berband failed for unknown reasons!</div>';		
		}
	} else {
		$ret = $ret.verband_form();
	}
	return $ret;
}
# After succesful user creation show summary. 
function verband_created() {
	$ret = 'Creation/update successful!<br/><br/>';
	$ret = $ret.verband_list();
	return $ret;
}
# START OUTPUT

LS_page_start('empty');

if ( isset( $_REQUEST['op'] ) && strlen( $_REQUEST['op'] ) < 25 ) { $myop=strip_tags( $_REQUEST['op'] ); } else { $myop = "verband_list"; }

$user_access_level = (int) $usertoken["usertype_id"];

if ( !isset( $user_access_level ) && $user_access_level > 4 ) { //more then LigaVerwaltung
	echo '<script> window.location.href = "/dso_user.php" </script>';
} else {
	echo '<h3>Bereiche Landesverbände / Ligen </h3><table class="box"><tr><td>'; # header and start table
	
	# Left nav pane echoing
	echo '<div class="navi" style="left: 5px;">'._button( 'Verband NEU', '', 'admin_verband_management.php?op=new_verband' );
	echo _button( 'Admin home', '', 'admin_main_menu.php' ).'</div>';

	switch ( $myop ) {
		case 'verband_list': { echo verband_list(); break; }
		case 'new_verband': { echo verband_form(); break;	}
		case 'new_verband_creation': { echo verband_validate_form(); break; }
		case 'new_verband_created': { echo verband_created(); break; }
	}
	echo '</td></tr></table>'; # end table
}

LS_page_end();

?>