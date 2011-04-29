<?php
/*
 * This is the central layout definition file, containing the head - foot sections
 * and the basic table layouts from ls_startPage ...
 */
if (eregi("lsdb_layout.php",$_SERVER['PHP_SELF'])) {Header("Location: ./"); die();}

require_once('mainfile.php');
include('theme/Lite/theme.php');
    
/**
 * starts the webpage and opens the BODY TAG
 *
 * @param string $pagetitle
 */
function LS_header($pagetitle='') {
	
    $OUT='';
    
    if (!headers_sent()) {
    	
		if(sizeof($pagetitle)<2)$pagetitle='LSDB System';
		
		$OUT="<!DOCTYPE HTML PUBLIC \"-//W3C//DTD HTML 4.01 Transitional//EN\">\n";
		$OUT=$OUT.'<html><head><title>'.$pagetitle.'</title>';
		
		$OUT=$OUT.'<META HTTP-EQUIV="Content-Type" content="text/html; charset=ISO-8859-1">';
		$OUT=$OUT.'<META HTTP-EQUIV="EXPIRES" CONTENT="0">';
		$OUT=$OUT.'<META NAME="RESOURCE-TYPE" CONTENT="DOCUMENT">';
		$OUT=$OUT.'<META NAME="DISTRIBUTION" CONTENT="GLOBAL">';
		$OUT=$OUT.'<META NAME="AUTHOR" CONTENT="Hristovski">';
		$OUT=$OUT.'<META NAME="COPYRIGHT" CONTENT="Copyright (c) 2002-08 by Austrian Darts Federation (Hristovski)">';
		$OUT=$OUT.'<META NAME="KEYWORDS" CONTENT="League System,Rechenzentrum,&Ouml;DV,OEDSO,WDF,Austria">';
		$OUT=$OUT.'<META NAME="DESCRIPTION" CONTENT="Liga System Application Austrian Darts Federation &Ouml;DV">';
		$OUT=$OUT.'<META NAME="ROBOTS" CONTENT="INDEX, FOLLOW">';

		# Do not remove the following line!
		$OUT=$OUT.'<META NAME="GENERATOR" CONTENT="LSDB System 4a">';

		$OUT=$OUT.'<script language="JavaScript" src="code/js_func.js"></script>';
		$OUT=$OUT.'<script language="JavaScript" src="code/jquery-1.2.6.js"></script>';
		$OUT=$OUT.'<script language="JavaScript" src="code/wz_jsgraphics.js"></script>';
		$OUT=$OUT.'<script language="JavaScript" src="code/lsdbfunc.js"></script>';
		$OUT=$OUT.'<LINK REL="StyleSheet" HREF="theme/Lite/style.css" TYPE="text/css">';
		$OUT=$OUT.'</head>';
		#debug($GLOBALS);
    }
    return $OUT.'<body class="lsdb">';
}

/**
 * prints copy div and closes the BODY TAG
 *
 */
function LS_footer(){
	global $foot_msg;
	return '<div id="foot"><center><font class="tiny">'.$foot_msg.'</center></div></body></html>';
}

/**
 * Opens the web page by sending LS_header()
 * Defines the major Table Layout, calls the appropriate modul->menue 
 * and ends by opening the central TD for content
 *
 * @param string $module_name (ls,dso,empty,ssi,wf,stats)
 */
function LS_page_start($module_name,$pagetitle=''){

	global $event_id,$site_logo,$site_header;
	
	echo LS_header($pagetitle);
	echo '<table class="fmain">'
	.'<tr><td class="f1"></td>'
	.'<td class="f2"><a href="dso_user.php"><img src="'.$site_logo.'" border="0"></a></td>'
	.'<td class="f3">'
	.'<table><tr><td></td><td></td><td class="redbox">'.$site_header.'</td><td></td></tr></table>'
	.'</td><td class="f4"></td></tr>'
	.'<tr><td></td><td colspan="2">';
	
	echo '<div id="navi" class="navi">';
		switch($module_name){
			case 'dso':
				echo navi_dso();
				break;
			case 'empty':
				break;
			case 'ls':
				navi_lsdb($event_id);
				break;
			case 'ssi':
				navi_ssi();
				break;
			case 'wf':
				echo navi_request();
				break;
			case 'stats':
				echo navi_stats();
				break;
		}
	echo '</div>';
	
	echo '</td><td></td></tr></table>';
	
	echo '<table class="fmain"><tr><td class="f5"></td><td class="f6" id="leftpane" name="leftpane" valign="top">';
	echo '</td><td class="f7" id="mainpane" name="mainpane" valign="top">';
	
	# here comes the main DATA WIndow size = 650PX
}

/**
 * defines common DIV elements,closes the content TD and the major TABLE + calls the LS_footer()
 *
 */
function LS_page_end(){
	# close main div
	echo '<div id="bottomcontent"></div><br/><div id="debug"></div>';
	# // close the LS_main table construct
	echo '</td><td></td></tr>'
	.'<tr><td ></td><td></td><td></td><td ></td></tr>'
	.'</table>';
	
	# // include default to close page properly ...
	echo LS_footer();
}
?>