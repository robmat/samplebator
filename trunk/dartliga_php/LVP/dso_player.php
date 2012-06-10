<?php

# // Basic functions for manipulating player records BH
# // browse (default), update, new, delete, activate, deactivate
# //
# // v02 fixed display bug in 'edit', added tplayer.sipstatus
# // v03 removed pverband field from player table, prealm reference automatically determines the assoc.
# // v04 included some extensions for the FEDA fields
# // v05 changed to generic form
# // V06 changes to player layout, added search for fkey1(�SO)
# // v07 changes to DB schema 3-10 using membership table
# // v08 fixed correct membership listings, changed to renderTableRows
# // v09 removed membership code -> lsdb/Membership controller

include('dso_main.php');

# // Beginn Funktionen ----------------
$playercode='dso_player.php';
if (sizeof($usertoken)<5){die_red('Err19AccCode');}

/**
*	purpose:	render alphabetical browse bar
* 	params:		Letter to highlight
*	returns:	HTML Table
*/
function LastNameBrowseBar($show){
	#
	# show on the browse pages
	# display a bar A-Z with links to search in PlayerLastNames
	# param: $show=the actual Letter Tab OnScreen
	# enlarging the size of the actual letter box creates a dynamic slide effect ...
	#
	global $playercode, $dbi;
	$outstring="";
	$letters = array("A","B","C","D","E","F","G","H","I","J","K","L","M","N","O","P","Q","R","S","T","U","V","W","X","Y","Z");
    $specialChars = DB_getSpecialCharsForUsersFilter($dbi);

    $a = array_merge( $letters, $specialChars );

	$outstring="<table bgcolor=\"white\" cellpadding=1 cellspacing=1 width=100%><tr>";
	foreach($a as $i){
		if ($i==$show){
			$outstring=$outstring."<td width=100px bgcolor=\"#B2FFB2\" align=\"center\"><font color=\"#000000\">$i</font></td>";
		} else {
            if( strpos( $i, '&' ) === false ) {
                $link = $i;
            } else {
                $link = urlencode($i);
            }
			$outstring=$outstring."<td>"._button("$i","","$playercode?findstr=$link")."</td>";
		}
	}
	$outstring=$outstring."</tr></table>";
	return $outstring;
}

/**
*	purpose:	list specific players according to searchstring 
* 	params:		findstring, findpassnr
*	returns:	renders Page with Browsebar+PlayerTable
*/
function listall($findstr='',$findpass=''){
	# zeigt einen Table mit einer SpielerListe an
	
	global $dbi,$realm_id,$playercode,$usertoken;

	if (sizeof($usertoken['registermap']) < 1) die_red('Err58:RegisterMap');
	
	if (strlen($findstr)<1) $findstr='A';
	$aTH=array('Aktiv','Vorname','Nachname','Key-1','Key-2','PLZ','Wohnsitz');
	
	$RS=DB_listPlayers($dbi,0,'','',$findpass,'','',$findstr);
	$target=$playercode.'?func=edit&amp;vpid=%P1%';
	$ROWS=RecordsetToClickTable($RS,0,$target,0);
	
	// OUTPUT //
	echo setPageTitle('<h3>Liste::Spielereintr&auml;ge '.$findstr.'</h3>');
	echo LastNameBrowseBar($findstr);
	OpenTable('browse');
	echo ArrayToTableHead($aTH);
	echo $ROWS;
	CloseTable();
	debug('Anzahl der gefundenen Spieler = '.sizeof($RS));
}

function _edit($vpid) {
# // ----------------
# // v02 BH 2003/7/03
# // v03 BH nov.2006 -> added more details + edit controls
# // ----------------
	#
	# // security here -> Anzeige sowie auf den eigentlichen action functions
	# //
	# // v04 DB table changes ...
	# // v05 DB schema 3-10 using membership entries ...
	# // 	the pactive flag is set 1. manually here and 2. during the membership import
	# // 	flag cannot be reset manually as long as a valid tmembership entry is present (currDate+1 month)
	
	global $dbi,$playercode,$sctdcolor,$usertoken;
	if (!is_numeric($vpid)) die('E:P1:PNotFound');
	
	if (sizeof($usertoken['registermap'])>0){
		$precord = sql_query("select pid,pfname,plname,pgender,pbirthdate,pnationality,ptown,pplz,pstreet,ptel1,ptel2,pemail,pcomment,pupd_date,pupd_user,pactive,pfkey1,pfkey2 from tplayer where pid=$vpid",$dbi);
		if (!$aP=sql_fetch_array($precord,$dbi)) die('E:P2:PNotFound');
	} 
	if (mysql_num_rows($precord) == 0) die( '<h3>Dieser Spieler ist nicht in deinem Zust&auml;ndigkeits-Gebiet, oder kann nicht gefunden werden.</h3>');
	
	// OUTPUT //
	echo '<div class=\'master\'>';
	OpenTable();
	
	echo '<form action="'.$playercode.'?func=save&vpid='.$aP['pid'].'" method="post">';
	echo form_Player($aP);
	echo '</form>';

	CloseTable();
	
	# // CHILD TABLES Detail records ....
	
	echo '</div><h3>Aktuelle Nennungen / Meldewesen</h3><div class="child"><div id="lstMember">';
	echo LSTable_PlayerActiveMemberShips('tmember',$aP['pid']);
	echo '</div><div id="frmMember"></div>';
	echo '</div>';
	/*
	 * create the button TABS, with a check-div below
	 */
	echo '<h3>Teams, Nennungen, Statistik</h3>';
	echo '<table><tr>';
	echo '<td>'._button('Neue Mitgliedschaft','memberedit(0,'.$aP['pid'].')').'</td>';
	echo '<td>'._button('Aktuelle Teams','chkplayerteam('.$aP['pid'].')').'</td>';
	echo '<td>'._button('Alte Meldungen','chkplayermember('.$aP['pid'].')').'</td>';
	echo '<td>'._button('SSI Werte','chkplayerstat('.$aP['pid'].',2)').'</td>';
	echo '<td>'._button('FEDA MIX Werte','chkplayerstat('.$aP['pid'].',3)').'</td>';
	echo '<td>'._button('FEDA Damen Werte','chkplayerstat('.$aP['pid'].',5)').'</td></tr></table><hr/>';
	echo '<div class="child"><div id="check"></div></div>';
	
	#echo '<div class="child">'.MakeSSIChart($vpid).'</div>';
	echo '<h3>Legs im Statistikteil</h3><div class="child">';
	OpenTable();
	echo _ShowOtherData($vpid);	# last 4 entries
	CloseTable();
	echo '</div><br/><br/>';
	# // other USER controls IF RW access ....
		if ($usertoken['usertype_id']==5 || $usertoken['usertype_id']==6) {
		echo '<div class="child">'.OpenTable('padm',1);
		echo '<form action="'.$playercode.'?func=delete&vpid='.$vpid.'" method="post">';
		echo '<table width="100%" cellpadding="2" cellspacing="2">'
			.'<tr><tr><td></td><td><b>Aktiv 0/1</b><br>&Uuml;blicherweise steht dieser Eintrag fast immer auf 1, nur in begr&uuml;ndeten F&auml;llen sollte ein Spieler mit dem Eintrag=0 versteckt werden.</td></tr>'
			.'<td><image src="images/stop.gif"></td><td width="400">Die Schaltfl&auml;che Spieler l&ouml;schen wird <b>ohne</b> R&uuml;ckfrage den Eintrag aus der gesamten Spielerdatenbank <b>unwiederbringlich</b> l&ouml;schen. Davon betroffen sind auch die davon abh&auml;ngigen Datensysteme wie SSI oder das Ligasystem. In den meisten F&auml;llen willst du diesen Eintrag ja nur auf <i>(nicht aktiv)</i> setzen ...</td>'
			.'<td>'._button('Spieler L&ouml;schen').'</td></tr>';
		echo '</table></form>';
		echo CloseTable().'</div>';
	}
}
function _ShowOtherData($vpid){
	// show some child recs from tblleg, tblranking ... usw ...
	// used to debug if a record can be deleted ...
	global $dbi;
	$OUT="";
	$qry=sql_query("select count(*) CNT from tbllegrounds where lpid=$vpid",$dbi);
	$aans=sql_fetch_array($qry,$dbi);
	$leg1cnt=$aans['CNT'];
	$qry=sql_query("select count(*) CNT from tblleg where lpid=$vpid",$dbi);
	$aans=sql_fetch_array($qry,$dbi);
	$leg2cnt=$aans['CNT'];
	$qry=sql_query("select count(*) CNT from tblteamplayer where lplayerid=$vpid",$dbi);
	$aans=sql_fetch_array($qry,$dbi);
	$val3cnt=$aans['CNT'];
	$OUT=$OUT."<tr><td width=50% class=\"bluebox\">Anzahl Legs (Roundbased DATA)</td><td>$leg1cnt</td></tr>";
	$OUT=$OUT."<tr><td  width=50% class=\"bluebox\">Anzahl Legs (Dartsbased DATA)</td><td>$leg2cnt</td></tr>";
	$OUT=$OUT."<tr><td  width=50% class=\"bluebox\">Anzahl der Teamnennungen</td><td>$val3cnt</td></tr>";
	return $OUT;
}


function _newplayer() {
	
	global $playercode;

	# enter defaults for NEW player here ...
	$aPlayer['pid']=0;
	$aPlayer['pactive']=1;
	$aPlayer['pgender']="H";
	$aPlayer['pfname']="";
	$aPlayer['plname']="";
	$aPlayer['pnationality']="AUT";
	$aPlayer['ptown']="";
	$aPlayer['pplz']="";
	$aPlayer['pstreet']="";
	$aPlayer['ptel1']="+43";
	$aPlayer['ptel2']="";
	$aPlayer['pemail']="user[]mail.at";
	$aPlayer['pcomment']="";
	$aPlayer['pusername']="";
	$aPlayer['pupd_date']="";
	$aPlayer['pupd_user']="";
	$aPlayer['pbirthdate']="1901-01-01";
	
	OpenTable();
	echo "<form action=\"$playercode?func=save\" method=\"post\">";
	echo form_Player($aPlayer);
	echo "</form>";
	CloseTable();
}

function dounhtmlentities($string)
{
    // replace numeric entities
    $string = preg_replace('~&#x([0-9a-f]+);~ei', 'chr(hexdec("\\1"))', $string);
    $string = preg_replace('~&#([0-9]+);~e', 'chr("\\1")', $string);
    // replace literal entities
    $trans_tbl = get_html_translation_table(HTML_ENTITIES);
    $trans_tbl = array_flip($trans_tbl);
    return strtr($string, $trans_tbl);
}

function _insupdplayer($v_pid=0,$last_name='') {
# ------------------------
# v02 BH 2003/7/3
# v07 BH 10.9. included add fields
# v09 BH removed vverband this is done auto by vrealm
# // v10 removed membership entries - just basic player details ...
# -----------------------
# TODO v5 merge this and the egate_code to the ORM Modell ...

	if (isset($_POST['vfname']) && strlen(dounhtmlentities($_POST['vfname']))<30){ $first_name=(strip_tags($_POST['vfname']));}else{$first_name='';}
	if (isset($_POST['vbirthdate']) && strlen(dounhtmlentities($_POST['vbirthdate']))<15){ $v_birthdate=(strip_tags($_POST['vbirthdate']));}else{$v_birthdate='1901-01-01';}
	if (isset($_POST['vgender']) && strlen(dounhtmlentities($_POST['vgender']))<2){ $v_gender=(strip_tags($_POST['vgender']));}else{$v_gender='H';}
	if (isset($_POST['vactive']) && is_numeric(dounhtmlentities($_POST['vactive']))){ $v_active=(strip_tags($_POST['vactive']));}else{$v_active=1;}
	if (isset($_POST['vcomment']) && strlen(dounhtmlentities($_POST['vcomment']))<50){ $v_comment=(strip_tags($_POST['vcomment']));}else{$v_comment='';}
	$v_username='';
	if (isset($_POST['vnation']) && strlen(dounhtmlentities($_POST['vnation']))<12){ $v_nation=(strip_tags($_POST['vnation']));}else{$v_nation='';}
	if (isset($_POST['vtown']) && strlen(dounhtmlentities($_POST['vtown']))<30){ $v_town=(strip_tags($_POST['vtown']));}else{$v_town='';}
	if (isset($_POST['vplz']) && strlen(dounhtmlentities($_POST['vplz']))<10){ $v_plz=(strip_tags($_POST['vplz']));}else{$v_plz='';}
	if (isset($_POST['vstreet']) && strlen(dounhtmlentities($_POST['vstreet']))<60){ $v_street=(strip_tags($_POST['vstreet']));}else{$v_street='';}
	if (isset($_POST['vtel1']) && strlen(dounhtmlentities($_POST['vtel1']))<20){ $v_tel1=(strip_tags($_POST['vtel1']));}else{$v_tel1='';}
	if (isset($_POST['vtel2']) && strlen(dounhtmlentities($_POST['vtel2']))<20){ $v_tel2=(strip_tags($_POST['vtel2']));}else{$v_tel2='';}
	if (isset($_POST['vemail']) && strlen(dounhtmlentities($_POST['vemail']))<80){ $v_email=(strip_tags($_POST['vemail']));}else{$v_email='';}
	
	global $dbi,$sipgoback,$usertoken;
	dsolog(2,$usertoken['uname'],"<b>UPDATE REQUEST</b> Player: $last_name($v_pid)");

	if ($usertoken['usertype_id'] < 2 && $usertoken['usertype_id'] != 0) die("<h3>Illegal attempt to change data ....</h3>");
	if (sizeof($usertoken['registermap']) < 1 && $usertoken['usertype_id'] != 0) die("<h3>Illegal attempt to change data .......</h3>");
#
# sanity checks come here please ........
#
	
	$v_gender = strtoupper($v_gender);
	if ( !ereg("([H,D,J]{1})", $v_gender) ) die ("Wrong Gender should be H,D or J ... $sipgoback");
	if (checkBirthDate($v_birthdate) == 0)die ("Geburtsdatum ist <b>kein g&uuml;ltges Datum</b> ... $sipgoback");
	if (strlen($last_name) == 0)die ("Nachname <b>muss</b> angegeben werden ... $sipgoback");
	if (strlen($first_name) == 0)die ("Vorname <b>muss</b> angegeben werden ... $sipgoback");
	
	/*
	 * in case of an insert the PID=0, check if unique
	 */
	if ($v_pid==0) {
		$playerlist=CheckUniquePlayer($first_name,$last_name,$v_birthdate);
		if (!strlen($playerlist) == 0) die ("<h3>Achtung, Doppel Eintr&auml;ge gefunden:</h3><p>Vorname, Nachname, Geburtsdatum ident:</p><br/>".$playerlist."<br/><br/> ...$sipgoback");
	}
	
	$upd_user=$usertoken['uname'];
	$upd_date = ls_getdate();

	if ( !ereg("([0-1]{1})", $v_active) ) die ("<h3>Aktiv Flag entweder 0 oder 1</h3> ... $sipgoback");
	
	// FINALLY ACTION
	
	if ($v_pid>1) {
	$qry="update tplayer set pfname=\"$first_name\",plname=\"$last_name\",pgender=\"$v_gender\","
	."pactive=$v_active,pcomment=\"$v_comment\",pusername=\"$v_username\",pbirthdate=\"$v_birthdate\","
	."pupd_user=\"$upd_user\",pupd_date=\"$upd_date\",pnationality=\"$v_nation\","
	."ptown=\"$v_town\",pplz=\"$v_plz\",pstreet=\"$v_street\",ptel1=\"$v_tel1\",ptel2=\"$v_tel2\",pemail=\"$v_email\" where pid=$v_pid limit 1";
	} else {
	$qry="insert into tplayer (pid,pfname,plname,pgender,pactive,sipcount,pcomment,pusername,psipstatus,pbirthdate,pcre_date,pcre_user,ptel1,ptel2,pnationality,ptown,pplz,pstreet,pemail)  values(0,\"$first_name\",\"$last_name\",\"$v_gender\",1,0,\"$v_comment\",\"$v_username\",0,\"$v_birthdate\",\"$upd_date\",\"$upd_user\",\"$v_tel1\",\"$v_tel2\",\"$v_nation\",\"$v_town\",\"$v_plz\",\"$v_street\",\"$v_email\")";
	}

	$res = sql_query($qry,$dbi);
	return $res;
}

if (isset($_REQUEST['findstr']) && strlen($_REQUEST['findstr'])<50){ $my_findstr=(strip_tags($_REQUEST['findstr']));}else{$my_findstr='';}
if (isset($_REQUEST['findpass']) && strlen($_REQUEST['findpass'])<10){ $my_findpass=(strip_tags($_REQUEST['findpass']));}else{$my_findpass='';}
if (isset($_REQUEST['func']) && strlen($_REQUEST['func'])<15){ $my_func=(strip_tags($_REQUEST['func']));}else{$my_func='';}
# ----
if (isset($_REQUEST['vpid']) && is_numeric($_REQUEST['vpid'])){ $player_id=(strip_tags($_REQUEST['vpid']));}else{$player_id=0;}
# -- post only --> move to inupdPlayer ....
if (isset($_POST['vlname']) && strlen($_POST['vlname'])<180){ $last_name=(strip_tags($_POST['vlname']));}else{$last_name='';}

switch($my_func) {

    	default:
    	listall($my_findstr);	# show all MY players
    	break;

	case 'list':
		listall($my_findstr);
		break;

	case 'search':
		listall($my_findstr,$my_findpass);
		break;
	
	case 'nossi':
		_listnossi();
		break;

    	case 'edit':
		echo setPageTitle('Spieler Eintrag Bearbeiten');    		
    	_edit($player_id);
    	break;

	case "save":
		_insupdplayer($player_id,$last_name);
		if ( $usertoken['usertype_id'] != 0 ) {
			listall($last_name);
		} else {
			echo 'Data edited successfuly.';
		}
		break;

	case 'updatesip':
		_InsUpd_SSIVAL($vsid,$vssipoints);
		echo setPageTitle('UPDATE DONE');
		break;

	case 'new':
		echo setPageTitle('Spieler Eintrag NEU');
    	_newplayer();
    	break;
	
	case 'delete':
		echo dso_delplayer($player_id);
		break;

}


echo '</div>';
LS_page_end();

?>
