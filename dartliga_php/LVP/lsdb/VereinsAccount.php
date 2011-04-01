<?php
	if ($_SERVER['REQUEST_METHOD']<>'POST') die('Y');
	foreach ($_POST as $secvalue) {
	    if (eregi("<[^>]*script*\"?[^>]*>", $secvalue) OR eregi("\([^>]*.*\"?[^>]*\)", $secvalue)) {
			die ("X");
	    }
	}
	require_once("../code/config.php");
	require_once("../includes/sql_layer.php");
	require_once("../func_sec.php");
	
	if (isset($_POST['vid']) && is_numeric($_POST['vid'])) {$verein_id=strip_tags($_POST['vid']);}else{$verein_id=0;die("E1");};
	if (isset($_POST['uname'])&& $_POST['uname']<>"undefined") {$user_name=strip_tags($_POST['uname']);}else{$user_name=""; die("E2");};
	if (isset($_POST['action'])&& $_POST['action']<>"undefined") {$sys_action=strip_tags($_POST['action']);}else{$sys_action=""; die("E3");};
	
	$dbi = sql_connect($dbhost, $dbuname, $dbpass, $dbname);
	$usertoken=initLsdbSec($dbi);
	if ($usertoken['usertype_id']<>6) die('E4:VAccount:Access');
	$ret='E5:VAccount';
	
	switch($sys_action){
		case 'check':
			$qry="select count(*) cnt from tuser where uname like '$user_name' AND usertype_id=2";
			$prec=sql_query($qry,$dbi);
			$r=sql_fetch_array($prec,$dbi);
			$ret="Found ".$r['cnt']." accounts.";
			break;
		case 'create':
			$qry="insert into tuser(id,version,fullname,uname,pass,usertype_id,verein_id) values(0,0,'Vereins Account $user_name','$user_name','e9653e857c65e792197fbf30ce1063af',2,$verein_id)";
			$prec=sql_query($qry,$dbi);
			if ($prec==1){$ret="<font color=green>Success $sys_action</font>";} else {$ret="<font color=red>$sys_action Failed</font>";}
			break;
		case 'unlock':
			$qry="update tuser set failcount=0 where uname='$user_name' and verein_id=$verein_id AND usertype_id=2 limit 1";
			$prec=sql_query($qry,$dbi);
			if ($prec==1){$ret="<font color=green>Success $sys_action</font>";} else {$ret="<font color=red>$sys_action Failed</font>";}
			break;
		case 'lock':
			$qry="update tuser set failcount=10 where uname='$user_name' and verein_id=$verein_id AND usertype_id=2 limit 1";
			$prec=sql_query($qry,$dbi);
			if ($prec==1){$ret="<font color=green>Success $sys_action</font>";} else {$ret="<font color=red>$sys_action Failed</font>";}
			break;
		case 'reset':
			$qry="update tuser set pass='e9653e857c65e792197fbf30ce1063af',failcount=0 where uname='$user_name' AND verein_id=$verein_id AND usertype_id=2 limit 1";
			$prec=sql_query($qry,$dbi);
			if ($prec==1){$ret="<font color=green>PWD $sys_action</font>";} else {$ret="<font color=red>$sys_action failed</font>";}
			break;
		default:
			die('E:06:VAcc:x5');break;
	}
	die($ret);
?>