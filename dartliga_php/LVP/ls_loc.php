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
	
	$location_count_result = sql_query( 'SELECT COUNT(*) FROM tbllocation l, tverband v WHERE l.lrealm_id = v.id', $dbi);
	$location_count = 0;
	while ( list ( $count ) = sql_fetch_row( $location_count_result, $dbi ) ) {
		$location_count = $count;	
	}
	$response->page = 1; 
	$response->total = 1; 
	$response->records = $location_count; 

	$i=0; 
	$location_result = sql_query( 'SELECT * FROM tbllocation l, tverband v WHERE l.lrealm_id = v.id', $dbi);
	while ( list( $locid, $lname, $lcity, $lplz, $laddress, $lphone, $lactive, $lrealm_id, $lemail, $lcoordinates, $version, $lkey, $vereinid, $vcode, $vname, $vlogic, $version, $vactive ) = sql_fetch_row( $location_result, $dbi ) ) {
		$response->rows[$i]['id'] = $locid;
		$response->rows[$i]['cell'] = array( $locid, $lname, $lcity, $lplz, $laddress, $lphone, $lactive, $lemail, $vname, $lcoordinates, $lrealm_id );
		$i++; 
	}
	
	echo '<div id="locationData" style="display: none;">'.json_encode($response).'</div>';
	echo '<table id="locationTable"></table><div id="locationPager"></div><script> createLocationTable(); </script>';
	
	# SECTION 2 DETAIL VIEW
	echo '<div id="frmLocation"><form name="locentry" action="fsaveLocation.php?opcode=save" method="post" target="_blank" onSubmit="submitForm(this);return false;"><fieldset><legend>Location Bearbeiten</legend>';
	echo include('forms/location.php');
	echo '</form></div>';
	
	# section 4 BrowseOnSelectedDetail
	echo '<h3>Aktuelle Heimmannschaften dieser Spielst&auml;tte</h3><div id="axteamtable"></div>';
}

_LS_LocationPage();

echo '</div>';
LS_page_end();
?>
