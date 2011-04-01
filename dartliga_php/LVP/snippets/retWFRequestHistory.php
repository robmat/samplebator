<?php
/**
 * 	file	:	showWFRequestHistory.php
*	purpose:	return request history DATA
* 	params:		wfid from WF
*	returns:	HTML Table
*/

	foreach ($_POST as $secvalue) {
	    if (eregi("<[^>]*script*\"?[^>]*>", $secvalue) OR eregi("\([^>]*.*\"?[^>]*\)", $secvalue)) {
			die ("X");
	    }
	}
	
	require_once("../code/config.php");
	require_once("../includes/sql_layer.php");
	require_once("../theme/Lite/theme.php");
	require_once("../lsdbcontroller.php");
	require_once('../api_rs.php');
	require_once('../api_format.php');
	
	if (isset($_POST['wfid']) && is_numeric($_POST['wfid']) && $_POST['wfid']<>"undefined"){$wfid=$_POST['wfid'];} else {$wfid=0;}
	$dbi = sql_connect($dbhost, $dbuname, $dbpass, $dbname);
	$RS=DB_listWFRequestHistory($dbi,$wfid);
	$aTH=array('ID','Request','Date','User','Status','Message');
	$OUT='<table>'.ArrayToTableHead($aTH);
	$OUT=$OUT.RecordsetToDataTable($RS,array(0,1,2,3,4,5));
	header('Content-Type: application/html; charset=ISO-8859-1');
	echo $OUT.'</table>';
?>