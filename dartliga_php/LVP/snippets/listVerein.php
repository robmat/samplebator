<?php
/**
*	purpose:	test team.class and XML generator
* 	params:		tid=<number>
*	returns:	XML team record
*/
	require_once("../code/config.php");
	require_once("../includes/sql_layer.php");
	require_once("../ORM/verein.php");
	if (isset($_REQUEST['id']) && is_numeric($_REQUEST['id'])) $v_id=strip_tags($_REQUEST['id']);
	
	if (!isset($v_id)) die('X2');
	$dbi = sql_connect($dbhost, $dbuname, $dbpass, $dbname);
	
	$V=new cVerein;
	$V->setDB($dbi);
	$V->getbyID($v_id);
	#$T->save();
	header('Content-Type: application/xml; charset=ISO-8859-1');
	echo $V->returnXML();
	echo $V->pError;
?>