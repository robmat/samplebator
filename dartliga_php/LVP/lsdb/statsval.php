<?php
/**
*	purpose:	implement the SAVE action for the Statistic Edit Page
* 	params:		
*	returns:	HTML Message string
*/
if ($_SERVER['REQUEST_METHOD']<>'POST') die('Y');
foreach ($_POST as $secvalue) {
    if (eregi("<[^>]*script*\"?[^>]*>", $secvalue) OR eregi("\([^>]*.*\"?[^>]*\)", $secvalue)) {
		die ("X");
    }
}

# SECURITY == Super admin only ...
include("../code/config.php");
require_once("../includes/sql_layer.php");
require_once("../func_sec.php");

$dbi = sql_connect($dbhost, $dbuname, $dbpass, $dbname);

$usertoken=initLsdbSec($dbi);
if ($usertoken['usertype_id']<>6){die ("E:Stat01:Access");}

if (isset($_POST['p_id'])) {$pid=strip_tags($_POST['p_id']);}else{$pid=0;};
if (isset($_POST['stat_code'])) {$statcode=strip_tags($_POST['stat_code']);}else{$statcode=0;};
if (isset($_POST['stat_date'])) {$statdate=strip_tags($_POST['stat_date']);}else{$statdate='';};
if (isset($_POST['stat_val'])) {$statval=strip_tags($_POST['stat_val']);}else{$statval=0;};
if (isset($_POST['stat_legs'])) {$statlegs=strip_tags($_POST['stat_legs']);}else{$statlegs=0;};
if (isset($_POST['stat_sets'])) {$statsets=strip_tags($_POST['stat_sets']);}else{$statsets=0;};

$ERR="<font color=red>ERROR saving $pid,$statcode,$statdate,$statval,$statsets,$statlegs</font>";
# check sanity ...
if ($pid==0 || $statcode==0 || $statdate=='' || $statval==0) {
	header('Content-Type: application/html; charset=ISO-8859-1');
	echo "<p><b>$ERR</b></p>";
}
# INS/UPD database ...

$ret=sql_query("INSERT INTO tblstat(statid,statdate,statcode,statval,statpid,statgames,statlegs)"
	." VALUES(0,'$statdate',$statcode,$statval,$pid,$statsets,$statlegs)",$dbi);
if ($ret<>1) {
	# try UPDATE
	$ret=sql_query("UPDATE tblstat set statval=$statval,statgames=$statsets,statlegs=$statlegs"
	." WHERE statdate='$statdate' AND statcode=$statcode AND statpid=$pid",$dbi);
}
if ($ret<>1){$MSG=$ERR;} else {$MSG="<font color=green>StatValue $statval saved for Player: $pid</font>";}
# SEND something back ...
header('Content-Type: application/html; charset=ISO-8859-1');
echo "<p><b>$MSG</b></p>";
?>