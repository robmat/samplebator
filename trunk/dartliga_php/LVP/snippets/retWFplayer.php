<?php
/**
*	purpose:	return a complete prefilled wfplayer form based on the passed existing playerID
* 				snippet is executed when existing players are fetched from the LSDB into the WF system
* 	params:		pid from LSDB
*	returns:	HTML snippet Form identical to forms:player and forms:wfplayer
* 				some object.properties are modified since tables are not 100% identical
* 	security:	#TODO this should be restricted to Account: Ligaadmin, Account:Verein
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
	require_once("../api_rs.php");
	require_once("../api_format.php");
	
	if (isset($_POST['pid']) && is_numeric($_POST['pid']) && $_POST['pid']<>"undefined"){$p_id=$_POST['pid'];} else {$p_id=0;}
	if (isset($_POST['wfpid']) && is_numeric($_POST['wfpid']) && $_POST['wfpid']<>"undefined"){$wfp_id=$_POST['wfpid'];} else {$wfp_id=0;}
	
	$dbi = sql_connect($dbhost, $dbuname, $dbpass, $dbname);
	$obj=array();
	$prec=sql_query("Select * from tplayer P where P.pid=$p_id",$dbi);
	$obj=sql_fetch_array($prec,$dbi);

	// prepend some values not in TPLAYER ... but needed for the WF system ...
	$obj['wfmembership_id']=0;
	$obj['wfplayer_id']=$wfp_id;
	$OUT=include('../forms/wfplayer.php');
		
	header('Content-Type: application/html; charset=ISO-8859-1');
	echo $OUT;
?>