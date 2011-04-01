<?php

#include('header.php');	# // -> require_once("mainfile.php"); --> require_once("config.php");
include('lsdb_layout.php');	# // -> require_once("mainfile.php"); --> require_once("config.php");
require('func_lsdb.php');
require('func_wf.php');
require('lsdbcontroller.php');
require('api_rs.php');	// Backend DB API
require('api_format.php');	// Backend FORMAT  API

# // layout
#debug($username.' UID:'.$aUser[0]);

if (sizeof($usertoken)<5){die('E:WF:X1: <a href=\'dso_user.php\'>Login</a>');}
if ($usertoken[0]==0){die('E:WF:X2: <a href=\'dso_user.php\'>Login</a>');}

$userverein=$usertoken['verein_id'];
ls_page_start('wf');

// common layout within the MAIN TD
echo '<div style="height:5px">&nbsp;</div>';
echo '<h3 id="pagetitle"></h3>';
echo '<div id="pagetabs"></div>';
echo '<div id="maincontent">';

?>
