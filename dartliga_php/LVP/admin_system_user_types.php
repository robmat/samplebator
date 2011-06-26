<?php 

require_once('mainfile.php');
require_once('lsdb_layout.php');
require_once('theme/Lite/theme.php');
require_once('func_lsdb.php');

function user_types() {
	global $dbi;
	$user_type_result = sql_query( 'SELECT t.id, t.typename, t.typedescription FROM ttypeuser t ORDER BY t.id ASC', $dbi );
	
	# Table header
	$ret = '<table><tr><td class="thead">Id</td><td class="thead">Type name</td><td class="thead">Description</td></tr>';
	
	while ( list( $id, $typename, $typedescription ) = sql_fetch_row( $user_type_result, $dbi ) ) {
		$ret = $ret.'<tr><td>'.$id.'</td><td>'.$typename.'</td><td>'.$typedescription.'</td></tr>';
	}
	
	return $ret.'</table>';
}

# START OUTPUT

LS_page_start('empty');

# Left nav pane echoing
echo '<div class="navi" style="left: 5px;">'._button( 'Admin home', '', 'admin_main_menu.php' ).'</div>';
echo '<h3>System benutzer typen</h3>'; # header and start table
echo user_types();
echo '</td></tr></table>'; # end table

LS_page_end();

?>