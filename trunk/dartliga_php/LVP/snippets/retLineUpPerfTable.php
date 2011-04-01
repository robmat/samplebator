<?php
	require_once("../code/config.php");
	require_once("../includes/sql_layer.php");
	require_once("../func_lsdb.php");
	require_once("../api_rs.php");
	require_once("../api_format.php");
	require_once("../theme/Lite/theme.php");
	
if (isset($_POST['teamid']) && is_numeric($_POST['teamid'])){$tid=strip_tags($_POST['teamid']);}else{$tid=0;};

$dbi = sql_connect($dbhost, $dbuname, $dbpass, $dbname);

echo '<p>Chronologische Darstellung der erzielten Averages in allen Legs aller eingesetzen Spieler eines Teams. Aus dieser Kurve lassen sich Leistungssteigerungen und Einbr&uuml;che ableiten.<i>Falls Spieler gemeldet aber nie eingesetzt wurden so werden NULL Werte angezeigt ...</i></p>';
echo getTeamAvgHist($tid);

?>