<?php
#//
#// EMPTY Main file League/DSO/SSI System
#// defines basic layout of interface
# // called by every neutral file - defines layout + User Buttons ...

#include('header.php');	# // -> require_once("mainfile.php"); --> require_once("config.php");
include('lsdb_layout.php');	# // -> require_once("mainfile.php"); --> require_once("config.php");
require('func_lsdb.php');
require('lsdbcontroller.php');
require('api_rs.php');	// Backend DB API
require('api_format.php');	// Backend FORMAT  API

# // layout
ls_page_start('empty');

// common layout within the MAIN TD
echo '<div style="height:5px">&nbsp;</div>';
echo '<h3 id="pagetitle"></h3>';
echo '<div id="pagetabs"></div>';
echo '<div id="maincontent">';

?>
