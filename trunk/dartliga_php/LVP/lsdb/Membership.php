<?php
	/*
	* file: 	Membership.php
	* Purpose: 	complete controller for the player - verein - type membership relations and records
	* Version: 	0.9 BH Jun 2008, based entirely on ajax frontend v4, replacing v3 code
	* Methods:	edit,save,delete (the list method is public -> snippets
	*/

if ($_SERVER['REQUEST_METHOD']<>'POST') die("Y");
foreach ($_POST as $secvalue) {
    if (eregi("<[^>]*script*\"?[^>]*>", $secvalue) OR eregi("\([^>]*.*\"?[^>]*\)", $secvalue)) {
	die ('X');
    }
}

	include_once("../code/config.php");
	require_once("../includes/sql_layer.php");
	require_once("../theme/Lite/theme.php");
	require_once('../lsdbcontroller.php');
	require_once('../api_rs.php');
	require_once('../api_format.php');
	require_once("../func_sec.php");
	require_once('../func_lsdb.php');
	require_once('../func_dso.php');
	
	// INCOMING VAR CHECKS
	if (isset($_POST['action']) && strlen($_POST['action'])<8) {$myAction=strip_tags(utf8_decode($_POST['action']));}else{$myAction='';};
	if (isset($_POST['mid']) && is_numeric($_POST['mid'])) {$membership_id=strip_tags(utf8_decode($_POST['mid']));}else{$membership_id=0;};
	if (isset($_POST['pid']) && is_numeric($_POST['pid'])) {$player_id=strip_tags(utf8_decode($_POST['pid']));}else{$player_id=0;};
	if (isset($_POST['vpassnr']) && strlen($_POST['vpassnr'])<12){ $v_passnr=strip_tags(utf8_decode($_POST['vpassnr']));}else{$v_passnr='';}
	if (isset($_POST['mtype']) && is_numeric($_POST['mtype'])){ $mtype_id=strip_tags(utf8_decode($_POST['mtype']));}else{$mtype_id=0;}
	if (isset($_POST['mrealm']) && is_numeric($_POST['mrealm'])) {$verband_ID=strip_tags($_POST['mrealm']);}else{$verband_ID=0;};
	if (isset($_POST['vid']) && is_numeric($_POST['vid'])){ $verein_id=strip_tags(utf8_decode($_POST['vid']));}else{$verein_id=0;}
	if (isset($_POST['vmstart']) && strlen($_POST['vmstart'])<15){ $v_mstart=strip_tags(utf8_decode($_POST['vmstart']));}else{$v_mstart='';}
	if (isset($_POST['vmend']) && strlen($_POST['vmend'])<15){ $v_mend=strip_tags(utf8_decode($_POST['vmend']));}else{$v_mend='';}
	if (isset($_POST['mactive']) && strip_tags($_POST['mactive'])=='true') {$m_active=1;}else{$m_active=0;};
	
	$dbi = sql_connect($dbhost, $dbuname, $dbpass, $dbname);
	$usertoken = initLsdbSec($dbi);
	/*
	 * here we need access for 
	 * 	a) all type of registration admins (regmap)
	 * 	b) vereinsaccounts type=2
	 * 	c) sys_admin	type=5/6
	 */
	if (sizeof($usertoken['registermap'])<1) {
		if (!$usertoken['usertype_id']==2){
			die_red('Err:MsRec33:EmptyRegMap');
		}
	}
	
	
function _blank(){
	die('Err:MsRec36:EmptyFunc');
}

function _showform_membership($membership_id,$player_id){
	/*
	 * depending on passed in id -> either fetch existing or show new
	 * output is loaded into hosting DIV
	 */ 
	global $dbi,$usertoken;
	$aMEM=array();
	
	$d=ls_getdate();
	
	if ($membership_id>0){
		if (!$precord=sql_query('select * from tmembership where mid='.$membership_id,$dbi)) die('<font color="red">E127:Record not found</font>');
		if (!$aMEM=sql_fetch_array($precord,$dbi)) die('<font color="red">E128:DB engine Error</font>');
	}else{
		$aMEM['mpassnr']='';
		$aMEM['mtype']=0;
		$aMEM['mvereinid']=$usertoken['verein_id'];	// just in case we come here as the vereins account
		$aMEM['mcre_user']=$usertoken['uname'];
		$aMEM['mcre_date']=$d;
		$aMEM['mid']=0;
		$aMEM['mpid']=$player_id;
		$aMEM['mstart']=substr($d,0,4).'-01-01';
		$aMEM['mend']=substr($d,0,4).'-12-31';
	}
	/*
	 * OUTPUT ...
	 */
	$OUT='';
	$OUT=$OUT.include('../forms/membership.php');
	$OUT=$OUT.'<table><tr><td>'._imgButton('save','membersave('.$membership_id.','.$player_id.')').'</td><td><div id="savemsg"></div></td></tr></table>';
	return $OUT;
}

/**
*	purpose	security controlled membership listing + action buttons for non public access
*	params		
*	returns		HTML Table with rows
*/
function _listMemberShip($player_id,$verein_id,$mtype_id,$verband_ID,$m_active){
	global $dbi,$usertoken;
	/* create a meaningful type_comparison string */
	if ($mtype_id>0) {$type_comp='='.$mtype_id;} else{die_red('Err157:MemberTypeRequired');}
	
	$RS=DB_listMemberShips($dbi,$player_id,$verein_id,$type_comp,$m_active,$verband_ID);
	if (sizeof($RS)>2000){die_red('Selection returns more than 2000 rows ('.sizeof($RS).')');}
	if (sizeof($RS)<1){die_green('Search criteria returns nothing ...');}
	/*
	 * no actions for non-registermap members ...
	 */
	if (sizeof($usertoken['registermap'])<1) {
	$aTH=array('Verein','Meldeart','PassNr','Meldung Ende','ID','Vorname','Nachname');
	$ROWS=RecordsetToDataTable($RS,array(2,3,4,5,6,7,8));	
	}else{
	$aTH=array('Verein','Meldeart','PassNr','Meldung Ende','ID','Vorname','Nachname','Aktion');
	$ROWS=RecordsetToDataTable($RS,array(2,3,4,5,6,7,8),array('playeredit','memberdel'),array(array(6),array(0,6)),array('Edit Player','Del'));
	}
	$HEAD=ArrayToTableHead($aTH);
	$sum='<div>Search criteria returned '.sizeof($RS).' records.</div>';
	return $sum.'<table class="tchild" id="tmembers" name="tmembers">'.$HEAD.$ROWS.'</table>';
}

function _listPlayerActiveMemberShip($player_id){return LSTable_PlayerActiveMemberShips('tmember',$player_id);}

header('Content-Type: application/html; charset=ISO-8859-1');

switch($myAction){
	default:
		break;
	case 'delete':
		echo dso_deletemembership($membership_id);
		break;	
	case 'edit':
		echo _showform_membership($membership_id,$player_id);
		break;	
	case 'save':
		echo dso_insupdmembership($membership_id,$player_id,$verein_id,$mtype_id,$v_passnr,$v_mstart,$v_mend);
		break;
	case 'list':
		echo _listMemberShip($player_id,$verein_id,$mtype_id,$verband_ID,$m_active);
		break;
	case 'listp':
		echo _listPlayerActiveMemberShip($player_id);
		break;
}
?>