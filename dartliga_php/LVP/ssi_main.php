<?php
#//
#// Main file SSI -> inclusion of NUKE files, relevant headers
#// defines basic layout of windows and tables
# // this file is INCLUDED into all other page calls
# //

#include('header.php');	# // -> require_once("mainfile.php"); --> require_once("config.php");
include('lsdb_layout.php');	# // -> require_once("mainfile.php"); --> require_once("config.php");
require("func_lsdb.php");
require("func_stat.php");
require("api_rs.php");	// Backend DB API
require("api_format.php");	// Backend DB API
require('lsdbcontroller.php');

global $dbi;
$lastdate='2006-10-15';
// we retrieve the current SSI date from the static list not from the DATE Table
$qry=sql_query("select max(statdate) from tblstat where statcode=2",$dbi);
	while(list($tlastdate)=sql_fetch_row($qry,$dbi)){
		$lastdate=$tlastdate;
	}

LS_page_start('ssi');

// common layout within the MAIN TD
echo '<div style=\'height:5px\'>&nbsp;</div>';
echo '<h3 id=\'pagetitle\'></h3>';

$my_op="";
if (isset($_REQUEST['op'])) { if (strlen($_REQUEST['op'])>20) {return "<h3>Error method_call</h3>";
							} else {$my_op=$_REQUEST['op'];}}
# here comes the main DATA WIndow
if ($my_op == "intro") {
	echo '<h1>SSI System &Ouml;DV</h1><p>Das Spielst&auml;rke Index Punkte System wird von den Liga Administratoren betreut und gewartet.</p>'
	.'<p>Die im LigaSystem erfassten Spiele werden automatisch geladen und ausgewertet.</p><p>F&uuml;r weiterf&uuml;hrende Fragen oder detailiertere Auskunft &uuml;ber das System wende dich bitte an die entsprechenden Personen.';
	echo "<p>Das SSI System des &Ouml;DV ist offen f&uuml;r Dartssportler aller Verb&auml;nde, wenn du daran teilhaben willst so wende dich bitte direkt an <a href=\"mailto:boris.hristovski[]dartsverband.at\">Boris</a>. Wir werden dann eine Einf&uuml;hrung sowie eine Schulung organisieren.";

}


?>
