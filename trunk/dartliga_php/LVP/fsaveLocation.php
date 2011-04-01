<?php

	// file fsaveFedaDate.php
	// Output: $presult
	// Purpose: insert or update date entry based on vfid
	// ToDo: Security ???

foreach ($HTTP_GET_VARS as $secvalue) {
    if (eregi("<[^>]*script*\"?[^>]*>", $secvalue) OR eregi("\([^>]*.*\"?[^>]*\)", $secvalue)) {
	die ("I don't like you...");
    }
}

	require_once("code/config.php");
	require_once("includes/sql_layer.php");

function _whistle(){
	echo "<b>fsaveLocation Service Gateway to Location Entries</b><br>"
		."&lt;seccode/&gt;"
		."&lt;opcode/&gt;"
		."&lt;vlocid/&gt;"
		."&lt;vlocactive/&gt;"
		."&lt;vlocname/&gt;"
		."&lt;vloccity/&gt;"
		."&lt;vlocplz/&gt;"
		."&lt;vlocaddress/&gt;"
		."&lt;vlocphone/&gt;"
		."&lt;vlocemail/&gt;"
		."&lt;vlocrealm/&gt;"
		."&lt;vloccoordinates/&gt;";
}

function _saveresult($vlocid,$vlocactive,$vlocname,$vloccity,$vlocplz,$vlocaddress,$vlocphone,$vlocemail,$vlocrealm,$vlcoordinates){
	#TODO v5 change to ORM class
	global $dbhost, $dbuname, $dbpass, $dbname;
	if (strlen($vlocid)>5 && !is_numeric($vlid)) return 0;

    $ad=getdate(time());
	$vfcre_info=$ad[year]."-".$ad[mon]."-".$ad[mday];

	if (strlen($vlocname) == 0) die("0");
	if ( !ereg("([0-9]{1})", $vlocrealm) ) die("0");
	
	$vlocname=strip_tags($vlocname);
	$vloccity=strip_tags($vloccity);
	$vlocplz=strip_tags($vlocplz);
	$vlocaddress=strip_tags($vlocaddress);
	$vlocphone=strip_tags($vlocphone);
	$vlocemail=strip_tags($vlocemail);
	$vlcoordinates=strip_tags($vlcoordinates);
	if ( !ereg("([0-1]{1})", $vlocactive) ) $vlocactive=1;

	if ($vlocid>0) {
		$qry="update tbllocation set lname=\"$vlocname\", lcity=\"$vloccity\", lplz=\"$vlocplz\", laddress=\"$vlocaddress\", lphone=\"$vlocphone\",lactive=$vlocactive, lrealm_id=$vlocrealm,lemail=\"$vlocemail\",lcoordinates=\"$vlcoordinates\" where id=$vlocid";
	} else {
		$qry="insert into tbllocation(id,lname,lcity,lplz,laddress,lphone,lactive,lrealm_id,lemail,lcoordinates) values(0,\"$vlocname\",\"$vloccity\",\"$vlocplz\",\"$vlocaddress\",\"$vlocphone\",$vlocactive,$vlocrealm,\"$vlocemail\",\"$vlcoordinates\")";
	}

	$dbi = sql_connect($dbhost, $dbuname, $dbpass, $dbname);
	$presult=sql_query("insert into tdsolog values(0,1,'fsaveLocation','$vfcre_info','EX: $qry')",$dbi);

    echo $qry;
	$presult=sql_query($qry,$dbi);

	//sleep(1);
	echo $presult;
}

switch($_GET['opcode']){
	default:
	_whistle();
	break;

	case "save":	
	_saveresult($_POST['vlocid'],$_POST['vlocactive'],$_POST['vlocname'],$_POST['vloccity'],$_POST['vlocplz'],$_POST['vlocaddress'],
                $_POST['vlocphone'],$_POST['vlocemail'],$_POST['vlocrealm'],$_POST['vloccoordinates']);
	break;
}
?>