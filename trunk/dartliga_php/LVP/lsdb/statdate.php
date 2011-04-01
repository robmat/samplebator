<?php
	/*
	// file: 		statdate.php
	// Output: 		$presult
	// Purpose: 	insert or update date entry based on vfid
	// Security:	non critical - however this should not be over-spammed ...
	*/
if ($_SERVER['REQUEST_METHOD']<>'POST') die('Y');	
foreach ($_POST as $secvalue) {
    if (eregi("<[^>]*script*\"?[^>]*>", $secvalue) OR eregi("\([^>]*.*\"?[^>]*\)", $secvalue)) {
	die ("I don't like you...(incident logged)");
    }
}

include("../code/config.php");
require_once("../includes/sql_layer.php");
require_once("../func_sec.php");

$usertoken=initLsdbSec($dbi);
if ($usertoken['usertype_id']<>6){die ("E:Stat01:Access");}

if (isset($_POST['vfid']) && is_numeric($_POST['vfid'])){$date_id=strip_tags($_POST['vfid']);}else{unset($date_id);}
if (isset($_POST['vftype']) && is_numeric($_POST['vftype'])){$date_type=strip_tags($_POST['vftype']);}else{unset($date_type);}
if (isset($_POST['vfdate']) && strlen($_POST['vfdate'])<20){$date_date=strip_tags($_POST['vfdate']);}else{unset($date_date);}
if (isset($_POST['vfcomment']) && strlen($_POST['vfcomment'])<20){$date_comment=strip_tags($_POST['vfcomment']);}else{unset($date_comment);}

function _saveStatDate($vfid,$vfdate,$vftype,$vfcomment){

	global $dbhost, $dbuname, $dbpass, $dbname;
	$ad=getdate(time());
	
	$vfcre_info="cre: ".$ad['year']."-".$ad['mon']."-".$ad['mday'];
	$vfdate=strip_tags($vfdate);
	$vfcomment=strip_tags($vfcomment);
	
	if (strlen($vfdate)<10) die("0");
	if ( !ereg("([0-9]{1,2})", $vftype) ) die("0");
	
	if ($vfid>0) {
		$qry="update tbldate set sdate=\"$vfdate\", sdesc=\"$vfcomment\", scre_info=\"$vfcre_info\",sstatcode_id=$vftype where id=$vfid";
	} else {
		$qry="insert into tbldate(id,sdate,sdesc,scre_info,sstatcode_id) values(0,\"$vfdate\",\"$vfcomment\",\"$vfcre_info\",$vftype)";
	}

	$dbi = sql_connect($dbhost, $dbuname, $dbpass, $dbname);
	$presult=sql_query("insert into tdsolog values(0,1,'fsaveDate','$vfcre_info','EX: $qry')",$dbi);
	$presult=sql_query($qry,$dbi);

	//sleep(1);
	echo $presult;
}

_saveStatDate($date_id,$date_date,$date_type,$date_comment);
	
?>