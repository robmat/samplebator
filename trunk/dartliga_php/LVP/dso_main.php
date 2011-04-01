<?php
/*
* Main file SIP -> inclusion of NUKE files, relevant headers
* defines basic layout of windows and tables
* reads the global current realm_id
*/

#include('header.php');	# // -> require_once("mainfile.php"); --> require_once("config.php");
include('lsdb_layout.php');	# // -> require_once("mainfile.php"); --> require_once("config.php");
require("func_lsdb.php");	// Presentation Layer and basic access vars
require("lsdbcontroller.php");
require("api_rs.php");	// Backend DB API
require("api_format.php");	// Backend DB API

#
# hmm ... seems we don't need this realmid
#

#if (isset($_REQUEST['realmid']) && intval($_REQUEST['realmid'])>0) {$realm_id=strip_tags($_REQUEST['realmid']);}else{$realm_id=0;};

ls_page_start('dso');

// common layout within the MAIN TD
echo '<div style="height:5px">&nbsp;</div>';
echo '<h3 id="pagetitle"></h3>';
echo '<div id="maincontent">';

?>
