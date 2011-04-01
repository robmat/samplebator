<?php

# //
# // Only for Liga access Level 2+3
# // This Interface is for managing the feda INDEX dates which are visible in the INDEX date-selectors
# // v3-8 use identical interface for SSI Date managing, using different type settings.
# // uses httpRequestmappings
# // v2.1 BH 08.2006, initial version
#

include("stats_main.php");
if ($usertoken['usertype_id']<4) die("<h3>No access to the Statistics Editor ...</h3>");

echo "<script type=\"text/javascript\" src=\"code/AjaxRequestCompact.js\"></script>";
echo "<script type=\"text/javascript\" src=\"code/axdatecode.js\"></script>";

# // Beginn Funktionen ----------------

$tdbg="#888888";	// this is the boxing and frames effect ....
$tdWon="#ccffcc";
$tdLost="#ffcccc";

function _mainStatsDate(){
	#
	# Show a typical Master/Detail view with toppane showing a browse list + delete buttons for each entry
	# New button loads empty Detail View + Save button --> reloads _browseDate
	# Click on a Master row shows editable details form + save button --> reloads _browseDate	
	# uses: fretAllFedaDates.php for the browselist

	global $dbi,$event,$tdbg;
	
	echo setPageTitle('Manage Dates for Static Statistic Lists');
	echo '<p>Die hier angezeigten Stichtage sind in den <i>SELECT BOXEN</i> auf den unterschiedlichen statistik seiten sichtbar und anw&auml;hlbar. Wird ein Datum gel&ouml;scht so werden damit <b>keine</b> Werte oder Spiele gel&ouml;scht, sondern lediglich die mit diesem Datum assozierte statische Werteliste.<br>Ein Klick auf einen Datumseintrag ladet dieses zum Bearbeiten. Der Datums-Statistik Typ ist zwingend erforderlich.</p>';
	
	# SECTION 0 Navigation	
	echo '<table bgcolor="'.$tdbg.'" cellpadding="2" cellspacing="1"><tr><td id="btnrefresh" bgcolor="white" onclick="initdatepage()" onMouseOver="mover(this)" onMouseOut="mout(this)">Tabelle neu laden</td>'
	.'<td></td><td bgcolor="white" id="datebrowseActivity"><i>Ready</i></td>'
	.'<td></td><td bgcolor="white" id="datesaveActivity"><i>Ready</i></td>'
	.'</tr></table><br>';
	
	# SECTION 1 BROWSELIST HEADER
	$aTH=array('ID','Datum','Comment','Creator','SyS','Ranking Name');
	$HEAD=ArrayToTableHead($aTH);
	echo '<table id="browseheader"><col width="20"><col width="90"><col width="310"><col width="90"><col width="30"><col width="170">'.$HEAD.'</table>';
	# Section 1.1 Browselist Body
	echo '<DIV class="tableroll">';
	echo '<table bgcolor="'.$tdbg.'" name="browsetable" id="browsetable" border="0" cellpadding="2" cellspacing="1" width="100%"><tbody></tbody></table></div><br/>';
	
	# SECTION 2 DETAIL VIEW
	echo '<div id="frmdate"><form name="dateentry" action="lsdb/statdate.php?opcode=save" method="post" target="_blank" onSubmit="submitForm(this);return false;"><fieldset><legend>Datum Bearbeiten</legend>';
	echo include('forms/date.php');
	echo '</fieldset></form></div>';
	
	# // call the page initialisation at last make sure all DOM stuff is in place ....
	echo '<script language="javascript">initdatepage();</script>';
}

_mainStatsDate();


# just in case we close main div
echo '</div>';
LS_page_end();

?>
