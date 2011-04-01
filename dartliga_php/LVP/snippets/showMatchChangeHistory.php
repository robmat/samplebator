<?php
/**
 * 	file	:	showMatchChangeHistory.php
*	purpose:	return request history DATA
* 	params:		matchkey
*	returns:	HTML Table
*/

	foreach ($_POST as $secvalue) {
	    if (eregi("<[^>]*script*\"?[^>]*>", $secvalue) OR eregi("\([^>]*.*\"?[^>]*\)", $secvalue)) {
			die ("Err11:Sec");
	    }
	}
	
	require_once("../code/config.php");
	require_once("../includes/sql_layer.php");
	#require_once("../theme/Lite/theme.php");
	require_once("../lsdbcontroller.php");
	require_once('../api_rs.php');
	require_once('../api_format.php');
	
	if (isset($_POST['matchkey']) && substr($_POST['matchkey'],0,1)=='e' && $_POST['matchkey']<>"undefined"){$mkey=$_POST['matchkey'];} else {$mkey='';}
	if (strlen($mkey)<8) die('ErrX1:Matchkey');
	$dbi = sql_connect($dbhost, $dbuname, $dbpass, $dbname);
	$ROWS=DB_listMatchHistory($dbi,$mkey);
	$aTH=array('','User','Datum','Eintrag','Client');
	$OUT='<table width=\'100%\'>'.ArrayToTableHead($aTH);
	$OUT=$OUT.$ROWS;
	header('Content-Type: application/html; charset=ISO-8859-1');
	echo $OUT.'</table>';
?>