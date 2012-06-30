//cell the delete admin verband in admin_verband_rights_to_users.php
function deleteAdminVerband( id ) {
	if ( !confirm( 'Are you certain?' ) ) { return 0; }
	
	$.ajax(
		{
			type: 	"POST",
			url: 	"admin_verband_rights_to_users.php",
			data: 	"op=del_admin_verband&del_id=" + id,
			success: function( msg ) {
				if ( msg.indexOf( '[{<>}]delete_ok_token[{<>}]' ) != -1 ) {
					alert( 'Delete successful.' );
					window.location.href = 'admin_verband_rights_to_users.php';
				}
				if ( msg.indexOf( '[{<>}]delete_failed_token[{<>}]' ) != -1 ) {
					alert('Delete failed.');
				}
			},
			error: function ( msg ) {
				alert( 'Delete failed.' );
			}
		}
	);
}
// call the delete user function in admin_system_users.php
function deleteUser( id ) {
	if ( !confirm( 'Are you certain?' ) ) { return 0; }
	
	$.ajax(
		{
			type: 	"POST",
			url: 	"admin_system_users.php",
			data: 	"op=del_user&del_id=" + id,
			success: function( msg ) {
				if ( msg.indexOf( '[{<>}]delete_ok_token[{<>}]' ) != -1 ) {
					alert( 'Delete successful.' );
					window.location.href = 'admin_system_users.php?op=user_list';
				}
				if ( msg.indexOf( '[{<>}]delete_failed_token[{<>}]' ) != -1 ) {
					alert( 'Delete failed.' );
				}
			},
			error: function ( msg ) {
				alert( 'Delete failed.' );
			}
		}
	);
}
// call the delete admin for liga function in admin_division_rights_to_users.php
function deleteAdminLiga( id ) {
	if ( !confirm( 'Are you certain?' ) ) { return 0; }
	
	$.ajax(
		{
			type: 	"POST",
			url: 	"admin_division_rights_to_users.php",
			data: 	"op=del_admin_user&del_id=" + id,
			success: function( msg ) {
				if ( msg.indexOf( '[{<>}]delete_ok_token[{<>}]' ) != -1 ) {
					alert( 'Delete successful.' );
					window.location.href = 'admin_division_rights_to_users.php?op=admin_liga_list';
				}
				if ( msg.indexOf( '[{<>}]delete_failed_token[{<>}]' ) != -1 ) {
					alert( 'Delete failed.' );
				}
			},
			error: function ( msg ) {
				alert( 'Delete failed.' );
			}
		}
	);
}
// In user creation form, when user is a player, then a select linking to player table should be visible -> admin_system_users.php
function userTypeChange( userType ) {
	if ( userType == 0 ) {
		$("#playerid").slideDown();
		$("#playeridlbl").slideDown();
	} else {
		$("#playerid").slideUp();
		$("#playeridlbl").slideUp();
	}
}
// Show controlls used to edit date of a given match
function editMatchDateShowControls( divId ) {
	$( '#' + divId ).show();
	var trElement = document.getElementById( divId ).parentNode;
	trElement.style.width = '180';
}
// Commit date for give match
function editMatchDateCommitChange( matchId ) {
	var day = document.getElementById( 'editMatchDateControlsDay' + matchId ).value;
	var mon = document.getElementById( 'editMatchDateControlsMonth' + matchId ).value;
	var yer = document.getElementById( 'editMatchDateControlsYear' + matchId ).value;
	if ( day && mon && yer ) {
		var dateStr = yer + '-' + mon + '-' + day;
		$.ajax(
		{
			type: 	"POST",
			url: 	"ls_system.php",
			data: 	"func=editdate&matchId=" + matchId + "&matchDate=" + dateStr,
			success: function( msg ) {
				if ( msg.indexOf( '[{<>}]ok_token[{<>}]' ) != -1 ) {
					document.getElementById( 'matchDateSpan' + matchId ).innerHTML = dateStr;
					$( '#editMatchDateControls' + matchId ).hide();
					var trElement = document.getElementById( 'editMatchDateControls' + matchId ).parentNode;
					trElement.style.width = '100';
				}
				if ( msg.indexOf( '[{<>}]failed_token[{<>}]' ) != -1 ) {
					alert('Edit failed. Try again.');
				}
			}
		}
	);
	}	
}
// Reset user login fail count to 0
function resetUserLoginFailcount( uid ) {
	$.ajax({
		type: 	"POST",
		url: 	"admin_system_users.php",
		data: 	"op=reset_user_failcount&user_id=" + uid,
		success: function( msg ) {
			if ( msg.indexOf( '[{<>}]ok_token[{<>}]' ) != -1 ) {
				alert('Reset OK. User can login again.');
				window.location.href = 'admin_system_users.php';
			}
			if ( msg.indexOf( '[{<>}]failed_token[{<>}]' ) != -1 ) {
				alert('Reset failed. Try again.');
			}
		}
	});
}
//Locations grid
function createLocationTable() {
	var locationDataStr = $('#locationData').html();
	var mygrid = jQuery( '#locationTable' ).jqGrid({
		datatype: 'jsonstring',
		datastr: locationDataStr,
		multiselect: false,
		height: 'auto',
		autowidth: true,
		forceFit: true,
		colNames: [ 'Id', 'Name', 'City', 'Postcode', 'Address', 'Phone', 'Active', 'Email', 'Region', 'Coordinates', 'reg_id' ],
		colModel : [
			{ name:'id', index:'id', sorttype:"int" },
			{ name:'name', index:'name', align:'center' },
			{ name:'city', index:'city', align:'center' },
			{ name:'postcode', index:'postcode', align:'center' },
			{ name:'address', index:'address', align:'center' },
			{ name:'phone', index:'phone', align:'center' },
			{ name:'active', index:'active', align:'center' },
			{ name:'email', index:'email', align:'center' },
			{ name:'region', index:'region', align:'center' },
			{ name:'coordinates', index:'coordinates', align:'center' },
			{ name:'reg_id', index:'reg_id', hidden: true }
		],
		pager: jQuery('#locationPager'),
		rowNum:10,
		rowList:[10,20,30],
	   	viewrecords: true,
	   	altRows:true,
	   	onSelectRow: function(id){
			var rowData = mygrid.getRowData(id);
			$('#vlocid').val(rowData['id']);
			$('#vlocname').val(rowData['name']);
			$('#vlocactive').val(rowData['active']);
			$('#vloccity').val(rowData['city']);
			$('#vlocplz').val(rowData['postcode']);
			$('#vlocaddress').val(rowData['address']);
			$('#vlocphone').val(rowData['phone']);
			$('#vlocemail').val(rowData['mail']);
			$('#vloccoordinates').val(rowData['coordinates']);
			$('#vlocrealm option').removeAttr('selected');
			$('#vlocrealm option[value=' + rowData['reg_id'] + ']').attr('selected', 'selected');
	    }
	}).navGrid('#locationPager',{edit:false,add:false,del:false,search:false,refresh:false}); 
	mygrid.filterToolbar(); 
}
function enablePasswordEdit( checkBox ) {
	if ( $(checkBox).is(':checked') ) {
		$( 'input#passHidden' ).val( $( 'input#pass' ).val() );
		$( 'input#pass' ).val('');
		$( 'input#pass' ).removeAttr( 'disabled' );
	} else {
		$( 'input#pass' ).val( $( 'input#passHidden' ).val() );
		$( 'input#passHidden' ).val('');
		$( 'input#pass' ).attr( 'disabled', 'disabled' );
	}
}