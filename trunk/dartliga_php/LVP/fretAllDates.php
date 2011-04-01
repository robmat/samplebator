<?php

	/*
	// file fretAllDates
	// returns: 	all defined statistic Dates for a simple browse list
	// format: 		fid;fdate;fcomment;fcre_info	=> as HttpTextRequest
	// syntax: 		fretAllDates.php (no params)
	*/

foreach ($HTTP_GET_VARS as $secvalue) {
    if (eregi("<[^>]*script*\"?[^>]*>", $secvalue) OR eregi("\([^>]*.*\"?[^>]*\)", $secvalue)) {
	die ("I don't like you...");
    }
}
require_once("code/config.php");
require_once("includes/sql_layer.php");
	
	$dbi = sql_connect($dbhost, $dbuname, $dbpass, $dbname);
	$qry="select D.id,D.sdate,D.sdesc,D.scre_info,D.sstatcode_id,S.statdesc from tbldate D left join ttypestat S on D.sstatcode_id=S.id order by sdate desc";
	$presult=sql_query($qry,$dbi);
	$strRET="";
	while($row=sql_fetch_row($presult,$dbi)){
		foreach ($row as $v) $strRET=$strRET."$v;";
		$strRET=$strRET."<br>";
	}
	header('Content-Type: application/xhtml+xml; charset=ISO-8859-1');
	echo $strRET;
?>