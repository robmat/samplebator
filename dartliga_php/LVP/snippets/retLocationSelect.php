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
	if (isset($_POST['locname'])) {$loc_name=strip_tags($_POST['locname']);}else{$loc_name="";};
	if (isset($_POST['locid'])&& is_numeric($_POST['locid'])) {$loc_id=strip_tags($_POST['locid']);}else{$loc_id=0;};
	if (isset($_POST['locplz'])&& strlen($_POST['locid'])<7) {$loc_plz=strip_tags($_POST['locplz']);}else{$loc_plz="";};
	if (isset($_POST['locactive'])&& is_numeric($_POST['locactive'])) {$loc_active=strip_tags($_POST['locactive']);}else{$loc_active=1;};
	if (isset($_POST['elem_name'])) {$elem_name=strip_tags($_POST['elem_name']);}else{$elem_name='';};
	if (isset($_POST['lid_sel'])) {$lidsel=strip_tags($_POST['lid_sel']);}else{$lidsel=0;};
	if (isset($_POST['changeaction'])) {$changeaction=strip_tags($_POST['changeaction']);}else{$changeaction='';};
	
	# create DB connection
	$dbi = sql_connect($dbhost, $dbuname, $dbpass, $dbname);
	
	# call controller
	header('Content-Type: application/html; charset=ISO-8859-1');
	echo Select_Location($elem_name,$changeaction,$lidsel,$loc_id,$loc_name,$loc_plz,$loc_active);
?>