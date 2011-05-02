<?php 

require_once('mainfile.php');
require_once('lsdb_layout.php');
require_once('theme/Lite/theme.php');
require_once('func_lsdb.php');

global $usertoken;

function _usermenuitem($name,$target,$alt='',$pic='info.gif') {
	$strret='';
	$strret= '<td valign=\'bottom\'><font class=\'content\'>'
	."<a href=\"$target\"><img width=\"48\" src=\"images/menu/$pic\" border=\"0\" alt=\"$alt\"></a><br>"
	."<a href=\"$target\">$name</a>"
	.'</font></td>';
	return $strret;
}

# START OUTPUT

LS_page_start();

if ( !isset( $usertoken ) ) {
	echo '<script> window.location.href = "/dso_user.php" </script>';
} else {
	$ret = '<h3>Benutzer und berecht</h3>  <table border="0" cellpadding="15" align="center"><tr>';
	$ret = $ret._usermenuitem('System benutzer', 'admin_system_users.php', 'System benutzer', 'people.gif');
	$ret = $ret._usermenuitem('System benutzer typen', 'admin_system_user_types.php', 'System benutzer typen', 'people.gif');
	$ret = $ret._usermenuitem('Liga gruppe rechte zuweisung', 'admin_division_rights_to_users.php', 'Liga gruppe rechte zuweisung', 'optimize.gif');
	$ret = $ret._usermenuitem('Liga gruppe rechte typen', 'division_rights.php', 'Liga gruppe rechte typen', 'optimize.gif');
	# $ret = $ret.'</tr></table>'
	echo $ret;
}

LS_page_end();

?>