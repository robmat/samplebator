<?php 

require_once('mainfile.php');
require_once('lsdb_layout.php');
require_once('theme/Lite/theme.php');
require_once('func_lsdb.php');

function right_types() {
	global $dbi;
	$right_result = sql_query( 'SELECT a.access_id, a.acactive, a.acdesc FROM tligaaccesscode a ORDER BY a.access_id ASC', $dbi );
	
	# Table header
	$ret = '<table><tr><td class="thead">Id</td><td class="thead">Active</td><td class="thead">Right name</td></tr>';
	
	while ( list( $id, $acactive, $acdesc ) = sql_fetch_row( $right_result, $dbi ) ) {
		$ret = $ret.'<tr><td>'.$id.'</td><td>'.( $acactive == 1 ? 'Yes' : 'No' ).'</td><td>'.$acdesc.'</td></tr>';
	}
	
	return $ret.'</table>';
}

# START OUTPUT

LS_page_start();

# Left nav pane echoing
echo '<div class="navi" style="left: 5px;">'._button( 'Admin home', '', 'admin_main_menu.php' ).'</div>';
echo '<h3>Liga gruppe rechte</h3>'; # header and start table
echo right_types();
echo '</td></tr></table>'; # end table

LS_page_end();

?>