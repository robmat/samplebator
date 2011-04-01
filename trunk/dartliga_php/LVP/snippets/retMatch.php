<?php
    /*
     * returns a full Match rendered as HTML 
     * needs jquery/match.js loaded on client ...
     * params POST/GET: $vmkey,$eid
     */
	require_once("../mainfile.php");
	require_once("../api_rs.php");
	require_once("../api_format.php");
	require_once("../func_lsdb.php");
	require_once("../theme/Lite/theme.php");
	$m_key="";$event_ID=0;
	if (strlen($_GET['vmkey'])>7)  {$m_key=$_GET['vmkey'];} else {$m_key="e0";}
	if (strlen($_GET['eid'])>0 && is_numeric($_GET['eid'])) {$event_ID=$_GET['eid'];} else {$event_ID=0;}
	
	#debug($event_ID.":".$m_key);
	#foreach($_GET as $a) echo strlen($a)."=".$a."<br/>";
	#return;
	
	$dbi = sql_connect($dbhost, $dbuname, $dbpass, $dbname);$aTH=array("Runde","Datum","Spielort","Heim Team","Set","Leg","Set","Leg","Gast Team","Status");
	$RS=DB_listMatches($dbi,1,$event_ID,0,"","","","",'logic',$m_key);
	$fields=array(4,5,6,7,8,9,10,11,12,13);
	$ROWS=RecordsetToDataTable($RS,$fields);
	$GSRS=DB_listGames($dbi,0,$m_key,'single');
	$GPRS=DB_listGames($dbi,0,$m_key,'pair');
	#debug($ROWS);
	# SECTION 0 Navigation	
	
	# section 1 MATCH	
	$OUT="<script type=\"text/javascript\">window.lsdb=new Array();window.lsdb.eventid=$event_ID;window.lsdb.matchkey='$m_key';</script>";
	$OUT=$OUT."<h3>Matchbericht :: Administration View</h3>";
	$OUT=$OUT.OpenTable('matchhead',1);
	$OUT=$OUT.ArrayToTableHead($aTH);
	$OUT=$OUT.$ROWS;
	$OUT=$OUT.CloseTable(1);
	/*
	 * we need to know how many game DIV to create, we need the game ID's for correct
	 * placement no simple enumeration here
	 */
	$OUT=$OUT."<h3>Singles</h3>";
	$OUT=$OUT.OpenTable('matchbodys',1);	//make foldable
	#echo $SROWS;
	$OUT=$OUT."<table id='singlegames'>";
	$i=1;
	foreach($GSRS as $game){
		$OUT=$OUT."<tr class=\"rgame\" id=\"rg".$game[2]."\">"
		."<td>$i</td>"
		."<td valign=\"top\">"._button('Load','getgame(this)')._button('Detail','showhideLegs(this)')._button('Save','savegame(this)')."</td>"
		."<td valign=\"top\" class=\"cgame\" id=\"cg".$game[2]."\"></td></tr>";
		$i++;
	}
	$OUT=$OUT."</table>";
	$OUT=$OUT.CloseTable(1);
	$OUT=$OUT."<h3>Pairs</h3>";
	$OUT=$OUT.OpenTable('matchbodyp',1);	//make foldable
	$OUT=$OUT."<table id='doublegames'>";
	foreach($GPRS as $game){
		$OUT=$OUT."<tr class=\"rgame\" id=\"rg".$game[2]."\">"
		."<td>$i</td>"
		."<td valign=\"top\">"._button('Load','getgame(this)')._button('Detail','showhideLegs(this)')._button('Save','savegame(this)')."</td>"
		."<td class=\"cgame\" id=\"cg".$game[2]."\"></td></tr>";
		$i++;
	}
	$OUT=$OUT."</table>";
	$OUT=$OUT.CloseTable(1);
	/*
	 * render admin stuff ??
	 */
	$OUT=$OUT."<h3>Verwalter</h3>";
	$OUT=$OUT.OpenTable('matchadmin',1);
	$OUT=$OUT."<p>Administrative controls ... result override ...</p>";
	$OUT=$OUT.CloseTable(1);
	/*
	 * call the page init function to actually load data
	 * echo "<script language=\"javascript\">window.onload=initmatchbericht($eventid);</script>";
	 */
	$OUT=$OUT."<div id='debug'></div>";
	header('Content-Type: application/xhtml+xml; charset=ISO-8859-1');
	echo $OUT;
?>
