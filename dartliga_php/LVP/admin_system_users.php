<?php 

require_once('mainfile.php');
require_once('lsdb_layout.php');
require_once('theme/Lite/theme.php');
require_once('func_lsdb.php');

# Return full user list html
function user_list() {
	global $dbi;
	$query_result = sql_query( 'SELECT u.id, u.fullname, u.uname, u.email, u.pass, u.uactive, t.typename, t.id, u.failcount FROM tuser u, ttypeuser t WHERE t.id = usertype_id ORDER BY u.fullname', $dbi );
	
	# Table header
	$ret = '<table><tr><td class="thead">Act.</td><td class="thead">Id</td><td class="thead">Full name</td>';
	$ret = $ret.'<td class="thead">Login</td><td class="thead">Email</td><td class="thead">Pass md5</td><td class="thead">User type</td><td class="thead">Del</td>';
	$ret = $ret.'<td class="thead">Login fail count</td><td class="thead">Edit</td></tr>';
	
	while ( list( $id, $fullname, $uname, $email, $pass, $user_active, $typename, $typeid, $failcount ) = sql_fetch_row( $query_result, $dbi ) ) {
		$ret = $ret.'<tr><td><img src="images/'.( $user_active ? 'greenman.png' : 'redman.png').'" /></td>';
		$ret = $ret.'<td>'.$id.'</td><td>'.$fullname.'</td><td>'.$uname.'</td><td>'.$email.'</td><td>'.$pass.'</td><td>'.$typename.'</td>';
		$ret = $ret.'<td><img src="images/del_icon.png" style="cursor: pointer;" onclick="deleteUser('.$id.');" /></td>';
		$ret = $ret.'<td align="center">'.$failcount;
		$ret = $ret.'<img src="images/unlock.png" onclick="resetUserLoginFailcount('.$id.');" style="cursor: pointer; padding-left: 3px; width: 15px; position: relative; top: 3px;" /></td>';
		$location_path = 'admin_system_users.php?op=new_user&uid='.$id;
		$ret = $ret.'<td><img src="images/edit24.png" style="cursor: pointer;" onclick="window.location.href = \''.$location_path.'\'" /></td></tr>';
	}
	
	return $ret.'</table>';
}

# New user form print
function user_form() {
	global $dbi, $usertoken;
	
	if ( isset( $_REQUEST['name'] ) ) { $name=strip_tags( $_REQUEST['name'] ); }
	if ( isset( $_REQUEST['uname'] ) ) { $uname=strip_tags( $_REQUEST['uname'] ); }
	if ( isset( $_REQUEST['pass'] ) ) { $pass=strip_tags( $_REQUEST['pass'] ); }
	if ( isset( $_REQUEST['email'] ) ) { $email=strip_tags( $_REQUEST['email'] ); }
	if ( isset( $_REQUEST['utype'] ) ) { $utype=strip_tags( $_REQUEST['utype'] ); }
	if ( isset( $_REQUEST['organisation'] ) ) { $organisation=strip_tags( $_REQUEST['organisation'] ); }
	if ( isset( $_REQUEST['location'] ) ) { $location=strip_tags( $_REQUEST['location'] ); }
	if ( isset( $_REQUEST['uactive'] ) ) { $uactive=strip_tags( $_REQUEST['uactive'] ); }
	
	$name = '';	$uname = ''; $pass = ''; $useraclevel = ''; $utype = ''; $email = ''; $organisation = ''; $theme = ''; $uactive = ''; $failcount = ''; $location_id = ''; $player_id = '';
	
	if ( isset( $_REQUEST['uid'] ) && !empty( $_REQUEST['uid'] ) ) { 
		$uid = $_REQUEST['uid'];
		$user_result = sql_query( 'SELECT fullname,	uname, pass, useraclevel, usertype_id, email, verein_id, theme, uactive, failcount,	location_id, player_id FROM tuser WHERE id = '.$uid, $dbi );
		list( $name, $uname, $pass, $useraclevel, $utype, $email, $organisation, $theme, $uactive, $failcount, $location_id, $player_id ) = sql_fetch_row( $user_result, $dbi );
		$uid = '&uid='.$uid;
	}

	$utype_query_result = sql_query( 'SELECT t.id, t.typename FROM ttypeuser t WHERE id <= '.$usertoken["usertype_id"].' ORDER BY id', $dbi );
	$organ_query_result = sql_query( 'SELECT v.vid, v.vname FROM tverein v ORDER BY vname', $dbi );
	$locat_query_result = sql_query( 'SELECT l.id, l.lname FROM tbllocation l ORDER BY lname', $dbi );
	$playr_query_result = sql_query( 'SELECT p.pid, p.pfname, p.plname FROM tplayer p ORDER BY p.plname', $dbi );
	
	$ret = '<form action="admin_system_users.php?op=new_user_creation'.$uid.'" method="post">';
	$ret = $ret.'<table><tr><td>Name and surname:</td><td>';
	$ret = $ret._input( 1, 'name', $name, 50, 50 );
	$ret = $ret.'</td></tr><tr><td>User name:</td><td>';
	$ret = $ret._input( 1, 'uname', $uname, 50, 50 );
	$ret = $ret.'</td></tr><tr><td>Password:</td><td>';
	$ret = $ret.'<input type="text" size="50" id="pass" name="pass" value="'.$pass.'" '.( empty( $uid ) ? '' : 'disabled' ).' />';
	$ret = $ret.'<input type="hidden" size="50" id="passHidden" name="passHidden" />';
	$ret = $ret.'<input type="checkbox" onclick="enablePasswordEdit(this);" '.( empty( $uid ) ? '' : 'checked="checked"' ).' />';
	$ret = $ret.'Click to unlock password editintg.';
	$ret = $ret.'</td></tr><tr><td>Email:</td><td>';
	$ret = $ret._input( 1, 'email', $email, 50, 50 );
	$ret = $ret.'</td></tr><tr><td>User type:</td><td>';
	$ret = $ret.'<select id="utype" name="utype" onchange="userTypeChange(this.options[this.selectedIndex].value);">';
	while ( list( $id, $name ) = sql_fetch_row( $utype_query_result, $dbi ) ) {
		$selected = strcmp( $utype, $id ) == 0 ? 'selected="selected"' : '';
		$ret = $ret.'<option '.$selected.' value="'.$id.'">'.$name.'</option>';
	}
	$ret = $ret.'</select>';
	$ret = $ret.'</td></tr><tr><td id="playeridlbl">Player data connected:</td><td>';
	$ret = $ret.'<select id="playerid" name="playerid">';
	while ( list( $id, $fname, $lname ) = sql_fetch_row( $playr_query_result, $dbi ) ) {
		$selected = strcmp( $player_id, $id ) == 0 ? 'selected="selected"' : '';
		$ret = $ret.'<option '.$selected.' value="'.$id.'">'.$lname.' '.$fname.'</option>';
	}
	$ret = $ret.'</select>';
	$ret = $ret.'</td></tr><tr><td>Verein:</td><td>';
	$ret = $ret.'<select id="organisation" name="organisation">';
	while ( list( $id, $name ) = sql_fetch_row( $organ_query_result, $dbi ) ) {
		$selected = strcmp( $organisation, $id ) == 0 ? 'selected="selected"' : '';
		$ret = $ret.'<option '.$selected.' value="'.$id.'">'.$name.'</option>';
	}
	$ret = $ret.'</select>';
	$ret = $ret.'</td></tr><tr><td>Location:</td><td>';
	$ret = $ret.'<select id="location" name="location">';
	while ( list( $id, $name ) = sql_fetch_row( $locat_query_result, $dbi ) ) {
		$selected = strcmp( $location_id, $id ) == 0 ? 'selected="selected"' : '';
		$ret = $ret.'<option '.$selected.' value="'.$id.'">'.$name.'</option>';
	}
	$ret = $ret.'</select>';
	$ret = $ret.'</td></tr>';
	$ret = $ret.'<tr><td>Active:</td><td><select id="uactive" name="uactive">';
	$ret = $ret.'<option value="1" '.( $uactive == '1' ? 'selected="selected"' : '' ).'>Active</option>';
	$ret = $ret.'<option value="0" '.( $uactive == '0' ? 'selected="selected"' : '' ).'>Inactive</option>';
	$ret = $ret.'</select></td></tr>';
	$ret = $ret.'<tr><td></td><td>'._button( !empty( $_REQUEST['uid'] ) ? 'Update user' : 'Create user' );
	$ret = $ret.'</td></tr></table>';
	$ret = $ret.'</form><script> $("#playerid").hide(); $("#playeridlbl").hide(); </script>'; #hide player data chooser until player user type is choosen
	return $ret;
}
 
 # Validate new user form, and return html results
function user_validate_form() {
	global $dbi;
	
	$does_password_needs_editing = !empty( $_REQUEST['passHidden'] );
	
	$ret = '<div style="color: red; padding: 5px;">';
	if ( empty( $_REQUEST['name'] ) ) { $ret = $ret.'Name required!<br/>'; }
	if ( empty( $_REQUEST['uname'] ) ) { $ret = $ret.'User name required!<br/>'; }
	if ( empty( $_REQUEST['pass'] ) && ( empty( $_REQUEST['uid'] ) || $does_password_needs_editing ) ) { $ret = $ret.'Password required!<br/>'; }
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
		$playerid = $utype == 0 ? $_REQUEST['playerid'] : 0; # if user is not a player ignore his player data connection
		$uactive = $_REQUEST['uactive'];
		
		$sql = 'INSERT INTO tuser (id, version, fullname, uname, pass, useraclevel, usertype_id, email, verein_id, theme, uactive, failcount, location_id, player_id) VALUES ';
		$sql = $sql.' (0, 0, "'.$name.'", "'.$uname.'", "'.$pass.'", "'.$aclevel.'", '.$utype.', "'.$email.'", '.$organisation.', "Lite", '.$uactive.', 0, '.$location.', '.$playerid.') ';
		
		if ( isset( $_REQUEST['uid'] ) && !empty( $_REQUEST['uid'] ) ) {
			if ( $does_password_needs_editing ) {
				$pass_edit_sql = ' pass = "'.$pass.'", ';
			}
			$sql = 'UPDATE tuser SET '.$pass_edit_sql.' fullname = "'.$name.'", uname = "'.$uname.'", usertype_id = '.$utype.', email = "'.$email.'", verein_id = '.$organisation;
			$sql = $sql.', uactive = '.$uactive.', location_id = '.$location.', player_id = '.$playerid.' WHERE id = '.$_REQUEST['uid'];
		}
		
		$insert_update_result = sql_query( $sql, $dbi );

		if ($insert_update_result == TRUE) {
			$ret = $ret.'<script> window.location.href = "admin_system_users.php?op=new_user_created" </script>';		
		} else {
			$ret = $ret.'<div style="color: red;">Creation/update of a user failed for unknown reasons!</div>';		
		}
	} else {
		$ret = $ret.user_form();
	}
	return $ret;
}
 
 # After succesful user creation show summary. 
 function user_created() {
	$ret = 'Creation/update successful!<br/><br/>';
	$ret = $ret.user_list();
	return $ret;
}

# Function able to delete the user, there's a check not to delete sys admin 
function delete_user() {
	global $dbi;
	if ( isset( $_REQUEST['del_id'] ) ) { $del_id=strip_tags( $_REQUEST['del_id'] ); }
	$delete_result = sql_query( 'DELETE FROM tuser WHERE id = '.$del_id, $dbi );
	if ( $delete_result ) {
		$delete_result = sql_query( 'DELETE FROM tbladminliga WHERE auid_id = '.$del_id, $dbi );
		if ( $delete_result ) {
			return '[{<>}]delete_ok_token[{<>}]';
		} else {
			return '[{<>}]delete_failed_token[{<>}]';
		}
	} else {
		return '[{<>}]delete_failed_token[{<>}]';
	}
	return '';
}

# Function will reset failcount for user with given id
function reset_user_failcount( $uid ) {
	global $dbi;
	$reset_result = sql_query( 'UPDATE tuser SET failcount = 0 WHERE id = '.$uid, $dbi );
	if ( $reset_result ) {
		return '[{<>}]ok_token[{<>}]';
	} else {
		return '[{<>}]failed_token[{<>}]';
	}
}

# START OUTPUT

LS_page_start('empty');

if ( isset( $_REQUEST['op'] ) && strlen( $_REQUEST['op'] ) < 25 ) { $myop=strip_tags( $_REQUEST['op'] ); } else { $myop = "user_list"; }

$user_access_level = (int) $usertoken["usertype_id"];

if ( !isset( $user_access_level ) && $user_access_level > 5 ) { //sys admin only
	echo '<script> window.location.href = "/dso_user.php" </script>';
} else {
	echo '<h3>System benutzer</h3><table class="box"><tr><td>'; # header and start table
	
	# Left nav pane echoing
	echo '<div class="navi" style="left: 5px;">'._button( 'Benutzer NEU', '', 'admin_system_users.php?op=new_user' );
	echo _button( 'Admin home', '', 'admin_main_menu.php' ).'</div>';

	switch ( $myop ) {
		case 'user_list': { echo user_list(); break; }
		case 'new_user': { echo user_form(); break;	}
		case 'new_user_creation': { echo user_validate_form(); break; }
		case 'new_user_created': { echo user_created(); break; }
		case 'del_user': { echo delete_user(); break; }
		case 'reset_user_failcount': { echo reset_user_failcount( $_REQUEST['user_id'] ); break; }
	}
	echo '</td></tr></table>'; # end table
}

LS_page_end();

?>