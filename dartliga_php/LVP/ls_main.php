<?php
#//
#// Main file League System
#// defines basic layout of interface
# // called by every other LS file ...
# // include("ls_main.php");

#include('header.php');	# // -> require_once("mainfile.php"); --> require_once("config.php");
include('lsdb_layout.php');	# // -> require_once("mainfile.php"); --> require_once("config.php");
require("func_lsdb.php");
require("func_mail.php");
require("lsdbcontroller.php");
require("api_rs.php");	// Backend DB API
require("api_format.php");	// Backend  API

# // make sure we have the actual configuration loaded

if (isset($_REQUEST['eventid']) && intval($_REQUEST['eventid'])>0) {$event_id=strip_tags($_REQUEST['eventid']);}else{$event_id=0;};
if($event_id>0) $event=reteventconfig($event_id);

# // layout
LS_page_start('ls');

// common layout within the MAIN TD
echo '<div style="height:5px">&nbsp;</div>';
echo '<h3 id="pagetitle"></h3>';
echo '<div id="pagetabs"></div>';
echo '<div id="maincontent">';
?>
