<?php
	/*
	// file:		fretDateById.php
	// returns: 	specific FEDA/SSI Date for a Detailed LIST
	// format: 		fid;fdate;fcomment;fcre_info	=> as HttpTextRequest
	// syntax:		fretDateById.php?vfid=1
	*
	*/
if ($_SERVER['REQUEST_METHOD']<>'POST') die("0");

foreach ($_POST as $secvalue) {
    if (eregi("<[^>]*script*\"?[^>]*>", $secvalue) OR eregi("\([^>]*.*\"?[^>]*\)", $secvalue)) {
	die ("I don't like you...");
    }
}

if (isset($_POST['vfid']) && is_numeric($_POST['vfid'])){$date_id=strip_tags($_POST['vfid']);}else{$date_id='NULL';}
	
	
	require_once("code/config.php");
	require_once("includes/sql_layer.php");
	
	$dbi = sql_connect($dbhost, $dbuname, $dbpass, $dbname);
	$qry="select D.id,D.sdate,D.sdesc,D.scre_info,D.sstatcode_id,S.statdesc from tbldate D left join ttypestat S on D.sstatcode_id=S.id where D.id=$date_id order by D.id";
	$presult=sql_query($qry,$dbi);
	// TEST - we just wait 1 sec ....
	//sleep(1);
	$strRET="";
	while($a=sql_fetch_row($presult,$dbi)){
		#// compile output line seperated by ;
		foreach ($a as $val) $strRET=$strRET.$val.";";
	}
	header('Content-Type: application/text; charset=ISO-8859-1');
	echo $strRET;
?>