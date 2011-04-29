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
