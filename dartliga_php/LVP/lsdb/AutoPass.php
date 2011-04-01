<?php
/**
 * 	file: 		AutoPass.php
*	purpose:	auto generate new passnumber and send to client -> do not save ...
* 	params:		verein(for LV),meldetyp,gender [vid,gdr,mtype]
*	returns:	Message string to be used as element.value
*/
if ($_SERVER['REQUEST_METHOD']<>'POST') die('Y');
foreach ($_POST as $secvalue) {
    if (eregi("<[^>]*script*\"?[^>]*>", $secvalue) OR eregi("\([^>]*.*\"?[^>]*\)", $secvalue)) {
		die ('X');
    }
}

# SECURITY == Valid users: Meldewesen, LV and Admins
# regmap > 0

include('../code/config.php');
require_once('../includes/sql_layer.php');
require_once('../func_sec.php');

if (isset($_POST['vid']) && is_numeric($_POST['vid'])) {$v_id=utf8_decode(strip_tags($_POST['vid']));}else{$v_id=0;};
if (isset($_POST['gdr']) && strlen($_POST['gdr'])<3) {$v_gender=utf8_decode(strip_tags($_POST['gdr']));}else{$v_gender='';};
if (isset($_POST['mtype']) && is_numeric($_POST['mtype'])) {$v_mtype=utf8_decode(strip_tags($_POST['mtype']));}else{$v_mtype=0;};

if ($v_id<1){die ('E:Pass01:Param:V');}
if ($v_mtype<1){die ('E:Pass01:Param:M');}
if ( !ereg('([H,D]{1})', $v_gender) ){die ('E:Pass01:Param:G');}

$dbi = sql_connect($dbhost, $dbuname, $dbpass, $dbname);

$usertoken=initLsdbSec($dbi);
if ($usertoken['usertype_id']<3){die ('E:Pass02:Access');}

$ERR='ERROR';
$ret=0;
$p1=sql_query('select verband_id from tverein WHERE vid='.$v_id,$dbi);
$aV=sql_fetch_array($p1,$dbi);

/*
 * Business Rules for the MEMBER Types Steel / Electronic ....
 * Att: these generators only work if at least 1 valid passnr is in the DB !!
 */
$pre='';
switch($v_mtype){
	case '1':
		// classic steeldart: Gender+LV+Number in PFKEY2
		if($v_gender=='H'){$gcode=1;}else{$gcode=2;}
		$p3=sql_query('select max(pfkey2) ret from tplayer where pfkey2 like "'.$gcode.$aV['verband_id'].'%"',$dbi);
		break;
	case '2':
		// classic electronic: LV+Gender+Number in PFKEY1
		if($v_gender=='H'){$gcode=1;}else{$gcode=2;}
		$p3=sql_query('select max(pfkey1) ret from tplayer where pfkey1 like "'.$aV['verband_id'].$gcode.'%"',$dbi);
		break;
	case '3':
		// PlayerCard Code as of 2008: 'A'+gender+number = 6digits
		$pre='A';
		if($v_gender=='H'){$gcode=1;}else{$gcode=8;}
		$p3=sql_query('select max(substr(pfkey1,2)) ret from tplayer where pfkey1 like "'.$pre.$gcode.'%"',$dbi);
		break;
	
	default:
		$p3=sql_query('select "0" ret from tplayer',$dbi);
}

$aP=sql_fetch_array($p3,$dbi);

if (is_numeric($aP['ret'])){$ret=1;}

if ($ret<>1){$MSG=$ERR;} else {$MSG=$pre.($aP['ret']+1);}

# SEND something back ...
echo $MSG;
?>