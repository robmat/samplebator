// call the delete user function in admin_system_users.php
function deleteUser( id ) {
	if (!confirm ('Are you certain?')) { return 0; }
	
	$.ajax(
		{
			type: 	"POST",
			url: 	"admin_system_users.php",
			data: 	"op=del_user&del_id=" + id,
			success: function(msg){
				if ( msg.indexOf( '[{<>}]delete_ok_token[{<>}]' ) != -1 ) {
					alert('Delete successful.');
					window.location.href = 'admin_system_users.php?op=user_list';
				}
				if ( msg.indexOf( '[{<>}]delete_failed_token[{<>}]' ) != -1 ) {
					alert('Delete failed.');
				}
			}
		}
	);
}
// call the delete admin for liga function in admin_division_rights_to_users.php
function deleteAdminLiga( id ) {
	if (!confirm ('Are you certain?')) { return 0; }
	
	$.ajax(
		{
			type: 	"POST",
			url: 	"admin_division_rights_to_users.php",
			data: 	"op=del_admin_user&del_id=" + id,
			success: function(msg){
				if ( msg.indexOf( '[{<>}]delete_ok_token[{<>}]' ) != -1 ) {
					alert('Delete successful.');
					window.location.href = 'admin_division_rights_to_users.php?op=admin_liga_list';
				}
				if ( msg.indexOf( '[{<>}]delete_failed_token[{<>}]' ) != -1 ) {
					alert('Delete failed.');
				}
			}
		}
	);
}
// In user creation form, when user is a player, then a select linking to player table should be visible -> admin_system_users.php
function userTypeChange( userType ) {
	if ( userType == 0 ) {
		$("#playerid").show();
		$("#playeridlbl").show();
	} else {
		$("#playerid").hide();
		$("#playeridlbl").hide();
	}
}