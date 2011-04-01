<?php
#//
#// Main file Statistics System Administration
#// defines basic layout of interface
# // called by every other stats file ...
# // include("stats_main.php");

#include('header.php');	# // -> require_once("mainfile.php"); --> require_once("config.php");
include('lsdb_layout.php');	# // -> require_once("mainfile.php"); --> require_once("config.php");
require("func_lsdb.php");
require("lsdbcontroller.php");
require("api_rs.php");	// Backend DB API
require("api_format.php");	// Backend  API

# // layout
LS_page_start('stats');

# // make sure we have the actual configuration loaded
# if(isset($eventid)) $event=reteventconfig($eventid);

// common layout within the MAIN TD
echo '<div style="height:5px">&nbsp;</div>';
echo '<h3 id="pagetitle"></h3>';
echo '<div id="pagetabs"></div>';
echo '<div id="maincontent">';

?>
