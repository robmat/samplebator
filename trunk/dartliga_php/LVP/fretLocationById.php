<?php

	/*
	 *  file		 fretLocation.php
	 * purpose	returns csv array of selected locations
	 * param		loc_id - location id	1..x
	 * 					full								0/1
	 * test			fretLocationById.php?vlid=3&full=1
	 * 					fretLocation.php -> returns all
	 */
	
foreach ($_GET as $secvalue) {
    if (eregi("<[^>]*script*\"?[^>]*>", $secvalue) OR eregi("\([^>]*.*\"?[^>]*\)", $secvalue)) {
	die ("I don't like you...");
    }
}

if (isset($_GET['vlid'])) {$location_id=strip_tags($_GET['vlid']);}else{$location_id=0;};
if (isset($_GET['full'])) {$full_details=strip_tags($_GET['full']);}else{$full_details="";};

	if (!$location_id>0) return 0;
	if (strlen($full_details)>3) return 0;
	
	require_once("code/config.php");
	require_once("includes/sql_layer.php");
	
	$dbi = sql_connect($dbhost, $dbuname, $dbpass, $dbname);
	if ($full_details=='no'){
		$qry="select id,lname from tbllocation where id=$location_id order by lplz";
	} else {
		$qry="select id,lname,lcity,lplz,laddress,lphone,lactive,lrealm_id,lemail,lcoordinates from tbllocation where id=$location_id order by lplz";
	}
	#echo $qry;
	$presult=sql_query($qry,$dbi);
	$strRET="";
	while($a=sql_fetch_row($presult,$dbi)){
		foreach ($a as $val) $strRET=$strRET.$val.";";
	}
	header('Content-Type: application/xhtml+xml; charset=ISO-8859-1');
	echo $strRET;
?>