<?php
/*
* This is meant to be shown in a seperate window (overlay or real), it returns
* a complete page with a google map of the location ID passed in
* params: locationID
*/
/*
 * 5.10.08 bugfixed the map-code
 */
foreach ($_REQUEST as $secvalue) {
    if (eregi("<[^>]*script*\"?[^>]*>", $secvalue) OR eregi("\([^>]*.*\"?[^>]*\)", $secvalue)) {
		die("X");
    }
}
	
	require_once("../code/config.php");
	require_once("../includes/sql_layer.php");
	
	if (isset($_REQUEST['locid'])&& is_numeric($_REQUEST['locid'])) {$loc_id=strip_tags($_REQUEST['locid']);}else{$loc_id=0;};
	if (isset($_REQUEST['bl'])&& is_numeric($_REQUEST['bl'])) {$bl_id=strip_tags($_REQUEST['bl']);}else{$bl_id=0;};
	
	if ($bl_id>0){
		$qry='select * from tbllocation where lrealm_id='.$bl_id;
	}elseif($loc_id>0){
		$qry='select * from tbllocation where id='.$loc_id;
	}
	$dbi = sql_connect($dbhost, $dbuname, $dbpass, $dbname);
	$qryRS=sql_query($qry,$dbi);
	$aLOC=array();
	while($a=sql_fetch_array($qryRS,$dbi)){
			$aLOC[]=$a;
			$aL=split(",",$a['lcoordinates']);
			$geoH=$geoH+$aL[0];
			$geoW=$geoW+$aL[1];
		}
	$geoCH=$geoH/sizeof($aLOC);
	$geoCW=$geoW/sizeof($aLOC);
	$pos_center=$geoCH.','.$geoCW;
	
	$adr=$aLoc['lplz']." ".$aLoc['lcity'].",".$aLoc['laddress'];
	
	$body="<body><h3>Darts Locator</h3><div id=\"map\" style=\"width: 500px; height: 300px\"></div></body>";
	$func="<script type=\"text/javascript\">google.load(\"maps\", \"2\");
	function doMarker(myMap,slng,sname,sadr){
		var M=new google.maps.Marker(slng);
		google.maps.Event.addListener(M,\"click\", function() {
        var myMsg = sname+\"<br/>\" + sadr;
        myMap.openInfoWindowHtml(slng, myMsg);
		});
		return M;
    };
	function loadmap(){
		var map = new google.maps.Map2(document.getElementById('map'));
		var mct = new google.maps.LatLng($pos_center);
        map.setCenter(mct, 13);
		map.addControl(new google.maps.SmallMapControl());";
	
	foreach ($aLOC as $LOC){
		$func=$func."var coord=new google.maps.LatLng(".$LOC['lcoordinates'].");";
		$func=$func."map.addOverlay(doMarker(map,coord,\"".$LOC['lname']."\",\"".$LOC['lplz']." ".$LOC['lcity'].",".$LOC['laddress']."\"));";
	}

	$func=$func."};google.setOnLoadCallback(loadmap);</script>";
	
	// OUTPUT
	echo "<!DOCTYPE HTML PUBLIC \"-//W3C//DTD HTML 4.01 Transitional//EN\">\n";
	//header('Content-Type: application/xhtml+html; charset=ISO-8859-1');
	echo "<html><head></head>".$google_maps_api.$func.$body."</html>";
?>