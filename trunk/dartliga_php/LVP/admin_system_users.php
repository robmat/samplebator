<?php 

require_once('mainfile.php');
require_once('lsdb_layout.php');
require_once('theme/Lite/theme.php');
require_once('func_lsdb.php');

# Return full user list html
function user_list() {
	global $dbi;
	$query_result = sql_query( 'SELECT u.id, u.fullname, u.uname, u.email, u.pass, u.uactive, t.typename FROM tuser u, ttypeuser t WHERE t.id = usertype_id ORDER BY u.id', $dbi );
	
	# Table header
	$ret = '<table><tr><td class="thead">Act.</td><td class="thead">Id</td><td class="thead">Full name</td>';
	$ret = $ret.'<td class="thead">Login</td><td class="thead">Pass md5</td><td class="thead">Email</td><td class="thead">User type</td></tr>';
	
	while ( list( $id, $fullname, $uname, $email, $pass, $user_active, $typename ) = sql_fetch_row( $query_result, $dbi ) ) {
		$ret = $ret.'<tr><td><img src="images/'.( $user_active ? 'greenman.png' : 'redman.png').'" /></td>';
		$ret = $ret.'<td>'.$id.'</td><td>'.$fullname.'</td><td>'.$uname.'</td><td>'.$email.'</td><td>'.$pass.'</td><td>'.$typename.'</td></tr>';
	}
	
	return $ret.'</table>';
}

# START OUTPUT

LS_page_start();

if ( isset( $_REQUEST['op'] ) && strlen( $_REQUEST['op'] ) < 25 ) { $myop=strip_tags( $_REQUEST['op'] ); } else { $myop = "user_list"; }

if ( !isset( $usertoken ) ) {
	echo '<script> window.location.href = "/dso_user.php"';
} else {
	echo '<h3>System benutzer</h3><table class="box"><tr><td>'; # header and start table
	
	# Left nav pane echoing
	echo '<div class="navi" style="left: 5px;">'._button( 'Benutzer NEU', '', 'admin_system_users.php?op=new_user' );
	echo _button( 'Home', '', 'admin_main_menu.php' ).'</div>';

	switch ( $myop ) {
		case 'user_list': { echo user_list(); break; }
		case 'new_user': {
			echo 'New user.';
			break;
		}
	}
	echo '</td></tr></table>'; # end table
}

LS_page_end();

?>