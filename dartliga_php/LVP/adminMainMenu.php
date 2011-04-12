<?php 

require_once('mainfile.php');
require_once('lsdb_layout.php');
require_once('theme/Lite/theme.php');
require_once('func_lsdb.php');

# Left nav pane
function admin_panel_left_menu() {
	$ret = '';
	$ret = $ret._button('Benutzer und berecht.','','?op=system_benutzer').'<br/>';
	$ret = $ret._button('Meldewesen','','?op=meldewesen').'<br/>';
	$ret = $ret._button('Liga system','','?op=liga_system');
	return $ret;
}
# Manage users, users rights, 
function system_benutzer_menu_items() {
	
}

LS_page_start();

# Left nav pane echoing
echo '<div class="navi" style="left: 5px;">'.admin_panel_left_menu().'</div>';

# Switch operation type
if (isset($_REQUEST['op']) && strlen( $_REQUEST['op'] ) < 30) { $myop=strip_tags( $_REQUEST['op'] ); }

switch ( $myop ) {
    case "system_benutzer": break;
	case "meldewesen": break;
	case "liga_system": break;
    default: echo 'Seems a wrong operation given!';	break;
}


LS_page_end();

?>