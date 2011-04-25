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

# New user form print
function user_form() {
	global $dbi;
	
	if ( isset( $_REQUEST['name'] ) ) { $name=strip_tags( $_REQUEST['name'] ); }
	if ( isset( $_REQUEST['uname'] ) ) { $uname=strip_tags( $_REQUEST['uname'] ); }
	if ( isset( $_REQUEST['pass'] ) ) { $pass=strip_tags( $_REQUEST['pass'] ); }
	if ( isset( $_REQUEST['email'] ) ) { $email=strip_tags( $_REQUEST['email'] ); }
	if ( isset( $_REQUEST['utype'] ) ) { $myop=strip_tags( $_REQUEST['uname'] ); }
	
	$utype_query_result = sql_query( 'SELECT t.id, t.typename FROM ttypeuser t', $dbi );
	$organ_query_result = sql_query( 'SELECT v.vid, v.vname FROM tverein v', $dbi );
	$locat_query_result = sql_query( 'SELECT l.id, l.lname FROM tbllocation l', $dbi );
	
	$ret = '<form action="admin_system_users.php?op=new_user_creation" method="post">';
	$ret = $ret.'<table><tr><td>Name and surname:</td><td>';
	$ret = $ret._input( 1, 'name', $name, 50, 50 );
	$ret = $ret.'</td></tr><tr><td>User name:</td><td>';
	$ret = $ret._input( 1, 'uname', $uname, 50, 50 );
	$ret = $ret.'</td></tr><tr><td>Password:</td><td>';
	$ret = $ret._input( 1, 'pass', $pass, 50, 50 );
	$ret = $ret.'</td></tr><tr><td>Email:</td><td>';
	$ret = $ret._input( 1, 'email', $email, 50, 50 );
	$ret = $ret.'</td></tr><tr><td>User type:</td><td>';
	$ret = $ret.'<select id="utype" name="utype">';
	while ( list( $id, $name ) = sql_fetch_row( $utype_query_result, $dbi ) ) {
		$selected = strcmp( $utype, $id ) == 0 ? 'selected="selected"' : '';
		$ret = $ret.'<option '.$selected.' value="'.$id.'">'.$name.'</option>';
	}
	$ret = $ret.'</select>';
	$ret = $ret.'</td></tr><tr><td>Verein:</td><td>';
	$ret = $ret.'<select id="organisation" name="organisation">';
	while ( list( $id, $name ) = sql_fetch_row( $organ_query_result, $dbi ) ) {
		$ret = $ret.'<option value="'.$id.'">'.$name.'</option>';
	}
	$ret = $ret.'</select>';
	$ret = $ret.'</td></tr><tr><td>Location:</td><td>';
	$ret = $ret.'<select id="location" name="location">';
	while ( list( $id, $name ) = sql_fetch_row( $locat_query_result, $dbi ) ) {
		$ret = $ret.'<option value="'.$id.'">'.$name.'</option>';
	}
	$ret = $ret.'</select>';
	$ret = $ret.'</td></tr><tr><td>AC level:</td><td>';
	$ret = $ret._input(1, 'aclevel');
	$ret = $ret.'</td></tr><tr><td></td><td>'._button('Create user');
	$ret = $ret.'</td></tr></table>';
	$ret = $ret.'</form>';
	return $ret;
}
 
 # Validate new user form, and return html results
function user_validate_form() {
	global $dbi;

	$ret = '<div style="color: red; padding: 5px;">';
	if ( empty( $_REQUEST['name'] ) ) { $ret = $ret.'Name required!<br/>'; }
	if ( empty( $_REQUEST['uname'] ) ) { $ret = $ret.'User name required!<br/>'; }
	if ( empty( $_REQUEST['pass'] ) ) { $ret = $ret.'Password required!<br/>'; }
	$ret = $ret.'</div>';
	
	if ( strcmp( $ret, '<div style="color: red; padding: 5px;"></div>' )  == 0 ) { # valid
		$name = $_REQUEST['name'];
		$uname = $_REQUEST['uname'];
		$pass = md5( $_REQUEST['pass'] );
		$email = $_REQUEST['email'];
		$utype = $_REQUEST['utype'];
		$organisation = $_REQUEST['organisation'];
		$location = $_REQUEST['location'];
		$aclevel = $_REQUEST['aclevel'];		
		
		$sql = 'INSERT INTO tuser (id, version, fullname, uname, pass, useraclevel, usertype_id, email, verein_id, theme, uactive, failcount, location_id) VALUES ';
		$sql = $sql.' (0, 0, "'.$name.'", "'.$uname.'", "'.$pass.'", "'.$aclevel.'", '.$utype.', "'.$email.'", '.$organisation.', "Lite", 1, 0, '.$location.') ';
		$insert_result = sql_query( $sql, $dbi );

		if ($insert_result == TRUE) {
			$ret = $ret.'<script> window.location.href = "admin_system_users.php?op=new_user_created" </script>';		
		} else {
			$ret = $ret.'<div style="color: red;">Creation of a user failed for unknown reasons!</div>';		
		}
	} else {
		$ret = $ret.user_form();
	}
	return $ret;
}
 
 # After succesful user creation show summary. 
 function user_created() {
	$ret = 'Creation successful!<br/><br/>';
	$ret = $ret.user_list();
	return $ret;
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
		case 'new_user': { echo user_form(); break;	}
		case 'new_user_creation': { echo user_validate_form(); break;	}
		case 'new_user_created': { echo user_created(); break;	}
	}
	echo '</td></tr></table>'; # end table
}

LS_page_end();

?>