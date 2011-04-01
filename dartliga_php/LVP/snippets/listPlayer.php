<?php
/**
*	purpose:	test player.clss and XML generator
* 	params:		pid=<number>
*	returns:	XML player record
*/
	require_once("../code/config.php");
	require_once("../includes/sql_layer.php");
	require_once("../ORM/player.php");
	if (isset($_REQUEST['id']) && is_numeric($_REQUEST['id'])) $p_id=strip_tags($_REQUEST['id']);
	
	if (!isset($p_id)) die('X2');
	$dbi = sql_connect($dbhost, $dbuname, $dbpass, $dbname);
	
	$p=new cPlayer;
	$p->setDB($dbi);
	$p->getbyID($p_id);
	$p->aDATA['pupd_user']='webservice';
	#$p->save();
	header('Content-Type: application/xml; charset=ISO-8859-1');
	echo $p->returnXML();
	echo $p->pError;
?>