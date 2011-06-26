<?php 

require_once('mainfile.php');
require_once('lsdb_layout.php');
require_once('theme/Lite/theme.php');
require_once('func_lsdb.php');

global $usertoken;

function right_types() {
	global $dbi;
	$right_result = sql_query( 'SELECT a.id, a.regactive, a.regdesc FROM  tregisteraccesscode a ORDER BY a.id ASC', $dbi );
	
	# Table header
	$ret = '<table><tr><td class="thead">Id</td><td class="thead">Active</td><td class="thead">Right name</td></tr>';
	
	while ( list( $id, $acactive, $acdesc ) = sql_fetch_row( $right_result, $dbi ) ) {
		$ret = $ret.'<tr><td>'.$id.'</td><td>'.( $acactive == 1 ? 'Yes' : 'No' ).'</td><td>'.$acdesc.'</td></tr>';
	}
	
	return $ret.'</table>';
}

# START OUTPUT

LS_page_start('empty');

# Left nav pane echoing
echo '<div class="navi" style="left: 5px;">'._button( 'Admin home', '', 'admin_main_menu.php' ).'</div>';
echo '<h3>Meldewesen rechte</h3>'; # header and start table
echo right_types();
echo '</td></tr></table>'; # end table

LS_page_end();

?>