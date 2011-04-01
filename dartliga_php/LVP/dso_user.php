<?php
/*
 * stripped version for the LSDB system
 * v3:	change v08 - renamed uid field ...changed header and footer sending stuff
 * v3.1: added user-ip to cookie, modified entry pages and timetolive
 * ATT: does not include the functions.file ...
 * v4b fixed bug with unset(global)
 */

require_once('mainfile.php');

$userpage = 1;
$userhome='dso_user.php';
global $usertoken;

	/**
	 * purpose: show different menu navigation items depending on the user type
	 * valid types: 1=teamcaptain,2 verein, 3=LV,4=LigaAdmin, 5=Sysadmin
	 * returns: string: navigation table()
	 */
function _usernav() {
	global $usertoken,$userhome;
	
	$usertype=0;
	$userverein=0;
	$showRegisterModul=0;
	
	if (sizeof($usertoken)>5){
		$userverein=$usertoken['verein_id'];
		$usertype=$usertoken['usertype_id'];
		if (sizeof($usertoken['registermap'])>0){
			$showRegisterModul=1;
		}
	}
	
	$strRet='';
	$strRet=$strRet.'<table border=\'0\' cellpadding=\'15\' align=\'center\'><tr><td>';
	
	switch ($usertype){
		case 0:
		case 1:
			break;
		case 2:
			// teamanmeldung, vereinsdaten,Matchliste
			$strRet=$strRet._usermenuitem('Workflow Anmeldung','wf.php','Antr&auml;ge','wf.png');
			$strRet=$strRet._usermenuitem("Verein Stammdaten","dso_verein.php?func=edit&vvid=$userverein",'Stammdaten','form.gif');
			$strRet=$strRet._usermenuitem("Matches meiner Teams","ls_verein.php?func=teammatches&vid=$userverein&show=home",'Matches','loc.png');
			$strRet=$strRet._usermenuitem("Ranglisten Werte &amp; Details meiner Vereinsspieler","ls_verein.php?func=playerranking&vid=$userverein",'Ranglisten','form.gif');
			break;
		case 3: // Landesverband ... Meldewesen etc ...
			break;
		case 6: // SYS Admin
			
		case 5:
			// Admins
			
			$strRet=$strRet._usermenuitem('Message Center','lsdbMessage.php','Nachrichten','email.png');
			$strRet=$strRet._usermenuitem('Statistik Daten','stats_main.php','Statistik','calendar.png');
			$strRet=$strRet._usermenuitem('eGate Input','egate.php','eGate/upload','optimize.gif');
			$strRet=$strRet.'</td></tr><tr><td>';
		case 4:	// Liga Admins
			$strRet=$strRet._usermenuitem('Workflow Anmeldung','wf.php','Antr&auml;ge','wf.png');
			$strRet=$strRet._usermenuitem('Locations und Spielst&auml;tten','ls_loc.php','Locations','loc.png');
			$strRet=$strRet._usermenuitem('Liga Controlling','ls_debug.php','Liga Controlling','optimize.gif');
			break;

		default:
			break;
	}
	
	if ($showRegisterModul==1){$strRet=$strRet._usermenuitem('Spieler und Vereins Meldewesen','dso_main.php?op=intro','Meldewesen','people.gif');}
	$strRet=$strRet._usermenuitem('SSI Ranglisten Modul','ssi_main.php?op=intro','SSI System','info.gif');
	$strRet=$strRet._usermenuitem('Liga Modul','ls_main.php','Liga System','info.gif');
	if (sizeof($usertoken)>5){$strRet=$strRet._usermenuitem('Abmelden',$userhome.'?op=logout','Logout','exit.gif');}
	
    $strRet=$strRet.'</tr></table>';
    return $strRet;
}

	/**
	 * generates menu icons in TD cells
	 */
function _usermenuitem($name,$target,$alt='',$pic='info.gif'){
	$strret='';
	$strret= '<td valign=\'bottom\'><font class=\'content\'>'
	."<a href=\"$target\"><img src=\"images/menu/$pic\" border=\"0\" alt=\"$alt\"></a><br>"
	."<a href=\"$target\">$name</a>"
	.'</font></td>';
	return $strret;
}

	/**
	* returns a change pwd box ...this is for the enduser
	*/
function _changepwd($responsecode){
	global $usertoken;
	$useremail=$usertoken['email'];
	$strret= '<b>Daten &auml;ndern:</b><br/>'
			.'<form action=\''.$responsecode.'\' method=\'post\'>';
	$strret=$strret.include('forms/user.php');		
	return $strret.'</form><br/>';
}

	/**
	 * purpose:	generate login form code with $responsecode?op=login
	* returns: login FORM
	*/
function _loginform($responsecode){
	$strret= '<b>Datenbank Anmeldung ... wer bist du ?</b><br/><br/>'
			.'<form action=\''.$responsecode.'\' method=\'post\'>';
	$strret=$strret.include('forms/login.php');
	return $strret.'</form><br/>';
}

	/**
	 * change password for user in DB and change email
	 * return 0/1 on success ..
	*/
function changepass(){
    # phpinfo();
	global $dbi,$usertoken;
	 include ("empty_main.php");
	extract($_POST);
	
    if (strlen($newpass1)>0) {
	    if ($newpass1<>$newpass2) {
			die("<h3>Passw&ouml;rter sind nicht ident ...<a href=\"javascript:history.back()\">Zur&uuml;ck zur Eingabe</a>");
		} elseif (strlen($newpass1)>5) {
			$ret=setUserProperty($usertoken['id'],'pass',md5($newpass1));
		} else {
			die("<h3>Passwort muss mehr als 5 Zeichen haben ...<a href=\"javascript:history.back()\">Zur&uuml;ck zur Eingabe</a>");
		}
    }
	if (strlen($newemail)>6) {
		$ret=setUserProperty($usertoken['id'],'email',$newemail);
	}
	return 1;
}

function userinfo() {
    global $dbi, $userhome, $usertoken;
    // we might not have the global access matrix here, lets calculate ...
    $priv_uname=$usertoken['uname'];
    $result = sql_query('select uname,pass,useraclevel from tuser where uactive=1 and failcount<5 and uname=\''.$priv_uname.'\'', $dbi);
    $aUInfo = sql_fetch_array($result, $dbi);
	
    include ('empty_main.php');
    
    OpenTable('userinfo');
    echo '<center>';
    if(($aUInfo['uname'] == $usertoken['uname']) AND ($aUInfo['pass'] == $usertoken['pass'])) {
	echo '<h3>Hallo '.$priv_uname.', du bist jetzt angemeldet und kannst Eingaben durchf&uuml;hren.</h3><br/>';
	echo _usernav();
	echo _changepwd($userhome);
    } else {
	echo '<font class="title">Information: '.$priv_uname.'</font></center><br/>Authentication went wrong ...<br/>';
    }
   
    CloseTable();
    echo '<table><tr><td><img src="images/menu/email.png"></td><td>'._button('Show Personal Inbox','msgtab()').'</td></tr></table>';
    echo '<script type="text/javascript">function msgtab(){$.post("lsdb/Message.php",{action:"inbox"},function(data){$("#pinbox").html(data);});}</script>';
    echo '<div id="pinbox"></div>';
}

function user_main($usertoken) {
    global $stop,$userhome;
    
    if(sizeof($usertoken)<5 || $usertoken['id']<2) {
    	
		include ("empty_main.php");
		
		echo _usernav();		// => show some public default entries
	  	OpenTable();
	  	echo '<center><font class=\'title\'><b>Bitte anmelden um mit den Daten zu arbeiten</b></font></center>';
	  	CloseTable();
	  	echo '<br/>';
		
	  	OpenTable();
	  	echo _loginform($userhome);
	  	CloseTable();
		
    } else {
        userinfo();
    }
}

	/**
	*	purpose:	logout current user
	* 	params:		none
	*	returns:	box mit abgemeldet meldung + anmelden + default ICONS
	*/
function logout() {
	global $userhome,$usertoken,$user,$aUser;
    setcookie('lsdb4user');
	unset($GLOBALS['usertoken']);unset($GLOBALS['user']);unset($GLOBALS['aUser']);
    # // BH include("header.php");
    include ('empty_main.php');
    echo _usernav();
    OpenTable();
    	echo '<center><font class=\'title\'><b>ABGEMELDET</b></font></center><br/>';
		echo '<center><font class=\'title\'><b>Bitte anmelden um mit den Daten zu arbeiten</b></font></center>';
	CloseTable();
	OpenTable('frmlogin');
    	echo _loginform($userhome);
    CloseTable();
/*
 * this somehow triggers an error break ??
 */
#	session_destroy();
}

	/**
	 * increase the failure counter for this user by 1
	 */
function _increasefailcount($username){
	global $dbi;
	$res=sql_query('update tuser set failcount=failcount+1 where uname="'.$username.'" limit 1',$dbi);
}

function _login($uname, $pass) {
    global $dbi;
    $unamepost=addslashes(trim($_POST['uname']));
    $passpost=addslashes(trim($_POST['pass']));
    $uname=addslashes(trim($uname));
    $pass=addslashes(trim($pass));
    $aUserInfo=array();
    
    if (!$uname==$unamepost) die ("var mismatch");
    if (!$pass==$passpost) die ("var mismatch");
    
    $result = sql_query("select pass,id,useraclevel,usertype_id from tuser where uactive=1 and failcount<5 and uname='$uname'", $dbi);
    if(sql_num_rows($result, $dbi)==1) {
		$aUserInfo = sql_fetch_array($result, $dbi);
		$dbpass=$aUserInfo['pass'];
		$md5_pass = md5($pass);
		if ($dbpass != $md5_pass) {
				_increasefailcount($uname);
	            Header("Location: dso_user.php?stop=1");
	    	    return;
		}
	# // headers are sent this command ???
	/*
	 * User authenticated ... do some stuff ...
	 */
		docookie('lsdb4user',$aUserInfo['id'], $uname, $md5_pass, $aUserInfo['usertype_id'],$_SERVER['REMOTE_ADDR']);
		session_name('LSDB4');
		session_start();
	   	$_SESSION['count'] = 1;
	   	$_SESSION['lsdbuid'] = $aUserInfo['id'];
		$_SESSION['lsdbuser'] = $uname;
		$_SESSION['lsdbencpass'] = $md5_pass;
		$_SESSION['lsdbusertype'] = $aUserInfo['usertype_id'];
		$_SESSION['lsdbuserip'] = $_SERVER['REMOTE_ADDR'];
		
		$result = sql_query("update tuser set current_ip='".$_SERVER['REMOTE_ADDR']."' where id=".$aUserInfo['id']." AND uname='$uname' limit 1",$dbi);
		
		Header("Location: dso_user.php");
	
    } else {
    	// user not found - redirect to stop page ...
		Header("Location: dso_user.php?stop=notfound");
    }
}

if (isset($_REQUEST['op']) && strlen($_REQUEST['op'])<10){$myop=strip_tags($_REQUEST['op']);}else{$myop="main";}
if (isset($_POST['uname']) && strlen($_POST['uname'])<20){$lsdb_uname=strip_tags($_POST['uname']);}else{unset($lsdb_uname);}
if (isset($_POST['pass']) && strlen($_POST['pass'])<20){$lsdb_pass=strip_tags($_POST['pass']);}else{unset($lsdb_pass);}

switch($myop) {

    case "logout":
		logout();
		break;

    case "login":
		_login($lsdb_uname, $lsdb_pass);
		break;

    case "chgpwd":
    	if (changepass()==1) {
    		# _login($uname, $newpass1);
    		#userinfo($uname, $bypass);
			die ("<h3>Changed ...</h3>");	
    	} else {
    		die ("<h3>Error changing user values ...</h3>");
    	}
    	break;

    default:
    	if (isset($usertoken)){
			user_main($usertoken);
    	} else {
    		user_main(array());
    	}
		break;
		
}
echo '</div>';
LS_page_end();
?>
