<?php
/**
*	purpose:	v4 ajax snippet - wrapper for lsdbcontroller::Select_StatGroup($name_id,$group_id_selected=0,$changeaction='')
* 	params:		elem_name,id_sel,changeaction
*	returns:	HTML select box
* 	test:		snippets/retStatGroupSelect.php?elem_name=vsid&id_sel=3&changeaction=alert
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
	if (isset($_GET['id_sel'])) {$idsel=strip_tags($_GET['id_sel']);}else{$id_sel=0;};
	if (isset($_GET['changeaction'])) {$changeaction=strip_tags($_GET['changeaction']);}else{$changeaction='';};
	
	# create DB connection
	$dbi = sql_connect($dbhost, $dbuname, $dbpass, $dbname);
	
	# call controller
	header('Content-Type: application/html; charset=ISO-8859-1');
	echo Select_StatGroup($elem_name,$idsel,$changeaction);
?>