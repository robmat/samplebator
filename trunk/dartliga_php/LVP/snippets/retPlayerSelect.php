<?php
/**
*	purpose:	v4 ajax snippet - wrapper for lsdbcontroller::Select_Player($elem_name='vpid',$pid_sel=0)
* 	params:		elem_name,pid_sel
*	returns:	HTML select box
* 	test:		snippets/retPlayerSelect.php?elem_name=vpid&pid_sel=2
*/
foreach ($_GET as $secvalue) {
    if (eregi("<[^>]*script*\"?[^>]*>", $secvalue) OR eregi("\([^>]*.*\"?[^>]*\)", $secvalue)) {
		die ("X");
    }
}
	require_once("../code/config.php");
	require_once("../includes/sql_layer.php");
	require_once("../api_rs.php");
	require_once("../api_format.php");
	require_once("../lsdbcontroller.php");
	
	# incoming PARAM check
	if (isset($_GET['elem_name'])) {$elem_name=strip_tags($_GET['elem_name']);}else{$elem_name='';};
	if (isset($_GET['pid_sel'])) {$pid_sel=strip_tags($_GET['pid_sel']);}else{$pid_sel=0;};
	
	# create DB connection
	$dbi = sql_connect($dbhost, $dbuname, $dbpass, $dbname);
	
	# call controller
	header('Content-Type: application/html; charset=ISO-8859-1');
	echo Select_Player($elem_name,$pid_sel);
?>