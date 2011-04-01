<?php

/*
 * Interface to the Locations Table 
 * Showing a typical Master Detail Edit View
 * ActiveLocations show ChildRecords from the Teams Table
 */

include('empty_main.php');

if ($usertoken['usertype_id']<4) die_red('E:LOC1:ErrType');

echo '<script type="text/javascript" src="code/AjaxRequestCompact.js"></script>';
echo '<script type="text/javascript" src="code/axlocationcode.js"></script>';

# // Beginn Funktionen ----------------

$tdbg='#888888';	// this is the boxing and frames effect ....
$tdWon='#ccffcc';
$tdLost='#ffcccc';


function _LS_LocationPage(){

	global $dbi,$event,$tdbg;
	
	echo setPageTitle('Manage Locations');
	echo '<p>Hier werden die Spielst&auml;tten bzw. Locations im gesamten Bundesgebiet angezeigt. Ein Klick auf einen Eintrag ladet diesen zum Bearbeiten.<br><b>Unterhalb</b> der Datenmaske werden zugleich alle eingetragenen Heimmannschaften aus dem LigaSystem angezeigt.</p>';
	
	# SECTION 0 Navigation	
	echo '<table bgcolor="'.$tdbg.'" cellpadding="2" cellspacing="1"><tr><td id="btnrefresh" bgcolor="white" onclick="initlocationpage()" onMouseOver="mover(this)" onMouseOut="mout(this)">Tabelle neu laden</td>'
	.'<td></td><td bgcolor="white" id="locbrowseActivity"><i>Ready</i></td>'
	.'<td></td><td bgcolor="white" id="locsaveActivity"><i>Ready</i></td>'
	.'</tr></table><br>';
	
	# SECTION 1 BROWSELIST HEADER
	$aTH=array('ID','Name','Adresse','Telephon','Aktiv','Map');
	$HEAD=ArrayToTableHead($aTH);
	echo '<table id="browseheader"><col width=30><col width=130><col width=310><col width=80><col width=40><col width=100>'.$HEAD.'</table>';
	# Section 1.1 Browselist Body
	echo '<DIV class="tableroll">';
	echo '<table bgcolor="'.$tdbg.'" name="browsetable" id="browsetable" border="0" cellpadding="2" cellspacing="1" width="100%"><tbody></tbody></table></div><br/>';
		
	# SECTION 2 DETAIL VIEW
	echo '<div id="frmLocation"><form name="locentry" action="fsaveLocation.php?opcode=save" method="post" target="_blank" onSubmit="submitForm(this);return false;"><fieldset><legend>Location Bearbeiten</legend>';
	echo include('forms/location.php');
	echo '</form></div>';
	
	# section 4 BrowseOnSelectedDetail
	echo '<h3>Aktuelle Heimmannschaften dieser Spielst&auml;tte</h3><div id="axteamtable"></div>';

	# // call the page initialisation at last make sure all DOM stuff is in place ....
	echo '<script language=\'javascript\'>window.onload=initlocationpage();</script>';
	
}

_LS_LocationPage();

echo '</div>';
LS_page_end();
?>
