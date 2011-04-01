<?php

################################################################################
#
# Main Entry to the Vereine DB 
# default op=listall
# 
################################################################################
# v02 removed the vlvcode field, is identical to realm logic ..
# v03 added player lists to detail/edit sections based on membership records
#			changed to insupd + form code
# v04
/*
 * added support for the user.verein access layer - account can edit its own data ...
 * added tpolmeldung field
 */ 
# v05	changed access modell to the usertoken code, removed old level vars ...

include('dso_main.php');

$vereinsdb='dso_verein.php';
$dartgreen="<img src=\"images/greenman.png\" border=\"0\">";
$imgdetail="<img src=\"images/info22.png\" border=\"0\" align=\"left\">";
$imgedit="<img src=\"images/detail24.png\" border=\"0\">";

function _listverein($findstr=''){
	/*
	 * List is a Meldewesen Public function, no restrictions here ...
	 * all Meldewesen accounts can see this List here ... + Vereinsaccount his own Verein
	 * TODO generate snippet listing ...
	 */

	global $dbi,$vereinsdb, $sctdcolor,$dartred,$dartgreen,$imgdetail,$imgedit,$usertoken;
	
	if (sizeof($usertoken['registermap']) < 1) {
		if (!$usertoken['verein_id']>0)die('E:Type:0');
		$precord = sql_query('select vid,verband_id,vbundesland,vOrt,vname,vemail,vwebsite,vsoft,vsteel from tverein where vid='.$usertoken['verein_id'],$dbi); 
	} else {
		$precord = sql_query("select vid,verband_id,vbundesland,vOrt,vname,vemail,vwebsite,vsoft,vsteel from tverein where vname like '%$findstr%' order by verband_id desc,vname asc",$dbi);
	}
	
	if (strlen($findstr)>0){
		$pageheader='Ergebniss der Suchanfrage '.$findstr;
	} else {
		$pageheader='Liste aller Vereine';
	}
	
	$aTH=array('Bundesland','Verein','email','WWW','Soft','Steel');
	
	setPageTitle($pageheader);
	OpenTable('tblverein');
	
	echo '<div id="lstverein"><table width="100%" cellpadding="2" cellspacing="2">';
	echo ArrayToTableHead($aTH);
	$i=0;
	$target=$vereinsdb.'?func=edit&amp;vvid=';
	$onClick='vereinedit(vid)';
	while(list($vid,$verband_id,$varea,$vOrt,$vname,$vemail,$vwebsite,$vsoft,$vsteel)=sql_fetch_row($precord,$dbi)){
		// $i=$i+1;
		echo '<tr>' 	#<td>$vid</td>"
		.'<td>'.$imgdetail.' '.$varea.'<br>'.$vOrt.'</td>'
		.'<td><a href="'.$target.$vid.'"><b>'.$vname.'</b></a></td>'
		.'<td>'.$vemail.'</td>'
		.'<td><a href="http://'.$vwebsite.'" target="_blank">'.$vwebsite.'</a></td>';
		if ($vsoft==1){
			echo '<td>'.$dartgreen.'</td>';
		} else {
			echo '<td></td>';
		}
		if ($vsteel==1){
			echo '<td>'.$dartgreen.'</td>';
		} else {
			echo '<td></td>';
		}

		echo '</tr>';
	} # // END WHILE LOOP
	echo '</table></div>';
	CloseTable();
	/*
	debug("Anzahl der Vereine: $i");
	$RS=DB_listVereine($dbi,0,$usertoken['realm'],$findstr);
	$ROWS=RecordsetToClickTable($RS,1,$target."%P1%",0);
	// OUTPUT
	echo "<div id=\"lstverein\"><table>$ROWS</table></div>";
	*/
}

function _editverein($vvid){
	//
	// v02 BH 09.2005
	// added homepagelink 02.2007
	//
	/*
	 * added user.verein access layer ...
	 */

	global $dbi,$vereinsdb,$sctdcolor,$dartred,$dartgreen,$usertoken;
	/*
	 * fetch Verein DATA
	 */
	$precord = mysql_query('select * from tverein where vid='.$vvid);
	$vereininfo = mysql_fetch_array($precord); // this is a case sensitive array !!!
	if (mysql_num_rows($precord) == 0) {die('<h3>Error:V0:VereinNotFound='.$vvid.'</h3>');}
	/*
	 * check ACCESS
	 */
	switch ($usertoken['usertype_id']){
		case '1':
			die('<h3>Error:V1:Type='.$usertoken['usertype_id']).'</h3>';
			break;
		case '2':
			if (!$usertoken['verein_id']==$vvid){die('<h3>Error:V2:WrongVerein</h3>');}
			break;
		default:
			if (sizeof($usertoken['registermap'])<1)die('<h3>Error:V3:Size:0</h3>');
			if ($usertoken['registermap'][$vereininfo['verband_id']]<2) {die('<h3>No rights in '.$vereininfo['verband_id'].'...</h3>');}
			if ($usertoken['usertype_id']==5 || $usertoken['usertype_id']==6){
				echo '<script language=\'JavaScript\' src=\'code/account.js\'></script>';
			}
			break;
	}
	/*
	 * Start OUTPUT
	 */
	setPageTitle('Vereins Eintrag Bearbeiten');
	// Vereinsformular
	OpenTable();
	echo '<form action="'.$vereinsdb.'?func=save&amp;vvid='.$vvid.'" method="post">';
	echo form_Verein($vereininfo);
	echo '</form>';
	CloseTable();
	// Account Line + Button
	if ($usertoken['usertype_id']==5 || $usertoken['usertype_id']==6){
		echo '<div id="pnladmin">';
		OpenTable();
		echo include('forms/vereinsaccount.php');
		CloseTable();
		echo '</div>';
	}
	
	// CHILD INFO 
	echo '<h3>Aktive Teams</h3><div class="child">';
	$RS=DB_listTeams($dbi,0,0,'',1,'',$vereininfo['vid']);
	echo RecordsetToSelectOptionList($RS,array(4,2,5),'teamid',0,'getlineup(this.value)').' W&auml;hle ein Team um die Aufstellung anzuzeigen.';
	echo '<div id="lineUp"></div></div>';
	
	echo '<h3>Aktuelle Mitglieder</h3><div class="child">';
	
	echo Select_Membertype('mtype',1,'listmemberv(this.value)',0).' W&auml;hle eine Mitgliedsart um die aktuellen Mitglieder anzuzeigen.';
	echo '<div id="frmMember"></div>';
	echo '<div id="memberv"></div></div>';
	
}

function _newverein(){
	//
	// v1.7 BH 09.2005
	//
	global $vereinsdb, $sctdcolor,$dartred,$dartgreen,$usertoken,$mailadr_verein;
	// user_type needs to be at least some sort of ADMIN
	if ($usertoken['usertype_id'] < 2) die("<h3>Illegal attempt to enter data ....</h3>");
	// TODO replace by ORM code ...
	// initialize default array
	$vereininfo['vid']=0;
	$vereininfo['verband_id']='';
	$vereininfo['vlogic']='';
	$vereininfo['vname']='Name*';
	$vereininfo['vfullname']='Name lt vereinsregister*';
	$vereininfo['vaddressclub']='Adresse Vereinslokal';
	$vereininfo['vort']='Ort';
	$vereininfo['vbundesland']='Bundesland';
	$vereininfo['vemail']=$mailadr_verein;
	$vereininfo['vwebsite']='www.verein.net';
	$vereininfo['vaddress']='offiz. anschrift';
	$vereininfo['vmembercount']=4;
	$vereininfo['vsoft']=0;
	$vereininfo['vsteel']=0;
	$vereininfo['vHomePageLink']=0;
	$vereininfo['oedsopagelink']=0;
	$vereininfo['tpolmeldung']='';
	$vereininfo['cre_user']=$usertoken['uname'];
	$vereininfo['cre_date']='';
	
	setPageTitle('Vereins Eintrag NEU');
	
	OpenTable();
	echo '<form action="'.$vereinsdb.'?func=save&amp;vvid=0" method="post">';
	echo form_Verein($vereininfo);
	echo '</form>';
	
	CloseTable();
	echo '<br/>';
	OpenTable();
	echo 'Die Daten in den ersten beiden Sektionen dienen der internen Verwaltung und sind allgemein zug&auml;nglich und einsehbar. '
			.'Teile der Daten in der 3. Sektion (z.B Kontaktadr. des Pr&auml;sidenten) sind nicht allgemein einsichtig '
			.'und k&ouml;nnen nur von berechtigten Personen gesehen werden.';
	CloseTable();
}

function _insupdverein($vvid,$vrealm,$vlogic,$vname,$vfullname,$vaddressclub,$vort,$vbundesland,$vemail,$vwebsite,$vaddress,$vmembercount,$vsoft=1,$vsteel=1,$vhomepagelink=0,$tpolmeldung='NULL',$oedsopagelink=0){
	/*
	 * insert or update record
	 * parse values and check on sanity ....
	 * 05.2008 switched to usertoken
	 * Allowed users => registermap=2 + vereinsaccount=vvid
	 */
	
	global $sipgoback,$dbi,$usertoken;
	
	// CHECK 1
	if ($usertoken['usertype_id'] < 2) die('<h3>E:V1:Type:'.$usertoken['usertype_id'].'</h3>'.$sipgoback);
	// CHECK 2
	if ($usertoken['usertype_id'] == 2){
		if (!$usertoken['verein_id']==$vvid){die('<h3>E:V2:WrongVerein:'.$usertoken['verein_id'].'</h3>'.$sipgoback);}
	} else {
		if ($usertoken['registermap'][$vrealm]<2) die('<h3>E:V3:RegisterRightsMissing</h3>'.$sipgoback);
	}
	// CHECK 3 Incoming variables ...
	if (strlen($vname) == 0) die('E:V4:Vereinsname <b>muss</b> angegeben werden ... '.$sipgoback);
	if ( !ereg("([1-9]{1})", $vrealm) ) die('E:V4:Zust&auml;ndigkeit <b>muss</b> angegeben werden ... '.$sipgoback);
	if ( strlen($vlogic)>2 ) $vlogic=substr($vlogic,0,2);
	if ( !is_numeric($vmembercount) ) $vmembercount=1;
	if ( !ereg("([0-1]{1})", $vsoft) ) $vsoft=0;
	if ( !ereg("([0-1]{1})", $vsoft) ) $vsteel=0;
	if ( !ereg("([0-1]{1})", $vhomepagelink) ) $vhomepagelink=0;
	if ( !ereg("([0-1]{1})", $oedsopagelink) ) $oedsopagelink=0;
	
	// FINALLY ACTION Based on ID
	// cre_INFO and upd_info
	$d = getdate();
	$upd_date = $d['year'].'-'.$d['mon'].'-'.$d['mday'];
	// TODO replace by ORM code ...
	if ($vvid>1) {
		$qry="update tverein set verband_id=$vrealm, vlogic=\"$vlogic\",vname=\"$vname\", vfullname=\"$vfullname\","
					."vaddressclub=\"$vaddressclub\", vort=\"$vort\", vbundesland=\"$vbundesland\", vemail=\"$vemail\","
					."vwebsite=\"$vwebsite\", vaddress=\"$vaddress\", vmembercount=$vmembercount, vsoft=$vsoft,"
					."vsteel=$vsteel ,vhomepagelink=$vhomepagelink, oedsopagelink=$oedsopagelink, tpolmeldung=\"$tpolmeldung\","
					."cre_user='".$usertoken['uname']."', cre_date=\"$upd_date\" where vid=$vvid";
	} else {
		$qry="INSERT into tverein (verband_id,vlogic,vname,vfullname,vaddressclub,vort,vbundesland,vemail,vwebsite,vaddress,"
		."vmembercount,vsoft,vsteel,vhomepagelink,oedsopagelink,tpolmeldung,cre_user,cre_date)"
		." VALUES (".$vrealm.",'$vlogic','$vname','$vfullname','$vaddressclub','$vort','$vbundesland','$vemail', '$vwebsite', '$vaddress',$vmembercount,$vsoft,$vsteel,$vhomepagelink,$oedsopagelink,'$tpolmeldung','".$usertoken['uname']."','$upd_date')";
	}
	#debug($qry);
	$res = sql_query($qry,$dbi); 
	dsolog(1,$usertoken['uname'],'<b>SAVE Verein:</b> '.$vname.' ('.$vaddress.')');
}

# MAIN ENTRY POINT ##################
#####################################

if (isset($_REQUEST['func'])&& $_REQUEST['func']<>"undefined") {$myfunc=strip_tags($_REQUEST['func']);}else{$myfunc='NULL';};
if (isset($_REQUEST['vvid']) && intval($_REQUEST['vvid'])>0) {$verein_id=strip_tags($_REQUEST['vvid']);}else{$verein_id=0;};
if (isset($_REQUEST['vname'])&& $_REQUEST['vname']<>"undefined") {$v_name=strip_tags($_REQUEST['vname']);}else{$v_name='NULL';};
if (isset($_POST['vsoft']) && intval($_POST['vsoft'])>0) {$v_soft=strip_tags($_POST['vsoft']);}else{$v_soft=0;};
if (isset($_POST['vsteel']) && intval($_POST['vsteel'])>0) {$v_steel=strip_tags($_POST['vsteel']);}else{$v_steel=0;};
if (isset($_POST['vhomepagelink']) && intval($_POST['vhomepagelink'])>0) {$v_homepagelink=strip_tags($_POST['vhomepagelink']);}else{$v_homepagelink=0;};
if (isset($_POST['vrealm']) && intval($_POST['vrealm'])>0) {$v_realm=strip_tags($_POST['vrealm']);}else{$v_realm=0;};
if (isset($_POST['vlogic'])&& $_POST['vlogic']<>"undefined") {$v_logic=strip_tags($_POST['vlogic']);}else{$v_logic='NULL';};
if (isset($_POST['vfullname'])&& $_POST['vfullname']<>"undefined") {$v_fullname=strip_tags($_POST['vfullname']);}else{$v_fullname='NULL';};
if (isset($_POST['vaddressclub'])&& $_POST['vaddressclub']<>"undefined") {$v_addressclub=strip_tags($_POST['vaddressclub']);}else{$v_addressclub='NULL';};
if (isset($_POST['vort'])&& $_POST['vort']<>"undefined") {$v_ort=strip_tags($_POST['vort']);}else{$v_ort='NULL';};
if (isset($_POST['vbundesland'])&& $_POST['vbundesland']<>"undefined") {$v_bundesland=strip_tags($_POST['vbundesland']);}else{$v_bundesland='NULL';};
if (isset($_POST['vemail'])&& $_POST['vemail']<>"undefined") {$v_email=strip_tags($_POST['vemail']);}else{$v_email='NULL';};
if (isset($_POST['vwebsite'])&& $_POST['vwebsite']<>"undefined") {$v_website=strip_tags($_POST['vwebsite']);}else{$v_website='NULL';};
if (isset($_POST['vaddress'])&& $_POST['vaddress']<>"undefined") {$v_address=strip_tags($_POST['vaddress']);}else{$v_address='NULL';};
if (isset($_POST['vmembercount']) && intval($_POST['vmembercount'])>0) {$v_membercount=strip_tags($_POST['vmembercount']);}else{$v_membercount=0;};
if (isset($_POST['tpolmeldung'])&& $_POST['tpolmeldung']<>"undefined") {$v_tpolmeldung=strip_tags($_POST['tpolmeldung']);}else{$v_tpolmeldung='NULL';};
if (isset($_POST['voedsopagelink'])&& $_POST['voedsopagelink']<>"undefined") {$v_oedsopagelink=strip_tags($_POST['voedsopagelink']);}else{$v_oedsopagelink=0;};

switch($myfunc) {

    default:
    	_listverein();
    	break;

	case "list":
		_listverein();
    	break;
	
	case "search":
		_listverein($v_name);
    	break;
	
	case "edit":
		_editverein($verein_id);
		break;
	
	case "save":
		_insupdverein($verein_id,$v_realm,$v_logic,$v_name,$v_fullname,$v_addressclub,$v_ort,$v_bundesland,$v_email,$v_website,$v_address,$v_membercount,$v_soft,$v_steel,$v_homepagelink,$v_tpolmeldung,$v_oedsopagelink);
		_listverein($v_name);
		break;
	
	case "new":
		_newverein();
		break;
	
}

echo '</div>';
LS_page_end();
?>
