<?php
/**
*	purpose:	v4 ajax snippet - wrapper for lsdbcontroller::Select_StatDate($vstatcode=0,$vstatdate='',$elem_name='vindexdate',$onChangeAction='')
* 	params:		elem_name,stat_sel,stat_code,changeaction
*	returns:	HTML select box
* 	test:		snippets/retStatDateSelect.php?elem_name=vsid&stat_code=3&stat_sel=2007-11-01&changeaction=alert
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
	if (isset($_GET['stat_sel'])) {$stat_sel=strip_tags($_GET['stat_sel']);}else{$stat_sel='';};
	if (isset($_GET['stat_code'])) {$stat_code=strip_tags($_GET['stat_code']);}else{$stat_code=0;};
	if (isset($_GET['changeaction'])) {$changeaction=strip_tags($_GET['changeaction']);}else{$changeaction='';};
	
	# create DB connection
	$dbi = sql_connect($dbhost, $dbuname, $dbpass, $dbname);
	
	# call controller
	header('Content-Type: application/html; charset=ISO-8859-1');
	echo Select_StatDate($stat_code,$stat_sel,$elem_name,$changeaction);
?>