<?php
/*
 * small sec layer for the ajax post-backs
 * decode user cookie - check DB access - and UserType
 * returns aUser array holding all relevant values to be accessed
 * by the lsdb CRUD functions
 * For visit: test/sectest.html
 */
if (eregi("func_sec.php",$_SERVER['PHP_SELF'])) {Header("Location: ./"); die();}
/**
*	purpose:	Returns the usertoken array
* 	params:		DB, [cookie]
*	returns:	array
*/
function initLsdbSec($DB){
	$aRet=array();
	if (isset($_COOKIE['lsdbuser'])) {return $aRet;}
	if (isset($_COOKIE['lsdb4user'])) { $user=$_COOKIE['lsdb4user'];}else{return $aRet;}
	$userdec = base64_decode($user);
	$aUserCookie = explode(":", $userdec);
	//debug($aUserCookie);
	//debug($_SESSION);
	if ( !intval($aUserCookie[0]) > 2 ) {return $aRet;}
	if ( !isset($aUserCookie[1])) {return $aRet;}
	
	// ok now compare to DB, try to fetch user props ... 
	// these have been set by a recent login()
	$query="select * from tuser where id=".$aUserCookie[0]." AND uname='".$aUserCookie[1]."'";
	//debug($query);
	//$Res=@mysql_query($query, $DB);
	//$aRet = mysql_fetch_array($Res);
	$Res=sql_query($query,$DB);
	$aRet=sql_fetch_array($Res,$DB);
	#debug($aRet);
	#debug($aUserCookie);
	if ($aRet['uname']<>$aUserCookie[1] || $aRet['pass']<>$aUserCookie[2]) {die ('Err42:Auth:X3:<a href=dso_user.php?op=logout>OUT</a>');}
	// when the cookie is set than the ip_adr is set into the DB as being the current one ...
	#debug($_SERVER['REMOTE_ADDR']);
	#debug($aUserCookie[4]);
	if ($_SERVER['REMOTE_ADDR']<>$aUserCookie[4]){die ('Err43:Auth:X4:<a href=dso_user.php?op=logout>OUT</a>');}	// --> has to delete cookie ...
	// additional user-rights , group memberships ...
	// the event access codes are stored in an indexed eventmap
	// only Active events are returned !!!
	$aRet['eventmap']=array();
	$eventmap=array();
	$evret=sql_query("Select E.id,E.evtypecode_id,access_id from tbladminliga,tblevent E"
	." WHERE aevcode_id=E.evtypecode_id and E.evactive=1 AND auid_id=".$aUserCookie[0]." and auname='".$aUserCookie[1]."'",$DB);
	while(list($aevid,$aevtype,$evaccess)=sql_fetch_row($evret,$DB)) {
		$eventmap[$aevid]=$evaccess;
	}
	$aRet['eventmap']=$eventmap;
	// hmm is authenticated ??
	$msgmap=array();
	$msgmapret=sql_query("SELECT mgroup_id from tmessagegroupmember where user_id=".$aUserCookie[0],$DB);
	while(list($mgroup)=sql_fetch_row($msgmapret,$DB)) {
		$msgmap[]=$mgroup;
	}
	$aRet['msgmap']=$msgmap;
	// registration MAP
	$registermap=array();
	$registermapret=sql_query("SELECT verband_id,reg_id from tregistermap where user_id=".$aUserCookie[0],$DB);
	while(list($vid,$level)=sql_fetch_row($registermapret,$DB)) {
		$registermap[$vid]=$level;
	}
	$aRet['registermap']=$registermap;
	
	return $aRet;
}

/**
	 * This is the central cookie creation ...manipulating the header ...
	 * v3 adding the client IP ...
	 */
function docookie($cookiename,$setuid, $setuname, $setpass, $setutype, $setuipadr) {
    $info = base64_encode("$setuid:$setuname:$setpass:$setutype:$setuipadr");
    // add encoding ??
    
    setcookie($cookiename,$info,time()+691200);
}
?>
