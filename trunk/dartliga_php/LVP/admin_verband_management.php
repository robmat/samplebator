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
		$ret = $ret.'<tr><td>'.( $user_active ? 'Yes' : 'No').'</td>';
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
	$ret = '<h3>Verband und berecht</h3><table border="0" cellpadding="15" align="center"><tr>';
	$ret = $ret.'<tr><td>Verband name: </td><td>'._input( 1, 'vname', $vname, 50, 50 ).'</td></tr>';
	$ret = $ret.'<tr><td>Verband code: </td><td>'._input( 1, 'vcode', $vcode, 50, 50 ).'</td></tr>';
	$ret = $ret.'<tr><td>Verband logic:</td><td>'._input( 1, 'vlogic', $vlogic, 50, 50 ).'</td></tr>';
	$ret = $ret.'<tr><td>Active:</td><td><select id="uactive" name="uactive">';
	$ret = $ret.'<option value="1" '.( $uactive == '1' ? 'selected="selected"' : '' ).'>Active</option>';
	$ret = $ret.'<option value="0" '.( $uactive == '0' ? 'selected="selected"' : '' ).'>Inactive</option>';
	$ret = $ret.'</select></td></tr>';
	$ret = $ret.'</table>';
	return $ret;
}

# START OUTPUT

LS_page_start('empty');

if ( isset( $_REQUEST['op'] ) && strlen( $_REQUEST['op'] ) < 25 ) { $myop=strip_tags( $_REQUEST['op'] ); } else { $myop = "verband_list"; }

if ( !isset( $usertoken ) ) {
	echo '<script> window.location.href = "/dso_user.php" </script>';
} else {
	echo '<h3>Bereiche landesvarbände</h3><table class="box"><tr><td>'; # header and start table
	
	# Left nav pane echoing
	echo '<div class="navi" style="left: 5px;">'._button( 'Verband NEU', '', 'admin_verband_management.php?op=new_verband' );
	echo _button( 'Admin home', '', 'admin_main_menu.php' ).'</div>';

	switch ( $myop ) {
		case 'verband_list': { echo verband_list(); break; }
		case 'new_user': { echo verband_form(); break;	}
		case 'new_user_creation': { echo user_validate_form(); break; }
		case 'new_user_created': { echo user_created(); break; }
		case 'reset_user_failcount': { echo reset_user_failcount( $_REQUEST['user_id'] ); break; }
	}
	echo '</td></tr></table>'; # end table
}

LS_page_end();

?>