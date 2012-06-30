<?php

if (eregi("config.php",$_SERVER['PHP_SELF'])) {
    Header("Location: ../");
    die();
}
//config RZ DDSVEV

//PROD
//$dbhost = 'localhost';
//$dbuname = 'web363';
//$dbpass = 'ddsv2008';
//$dbname = 'usr_web363_1';

//TEST
//$dbhost = 'localhost';
//$dbuname = 'web233';
//$dbpass = 'jz63UrGH';
//$dbname = 'usr_web233_2';

//DEV
$dbhost = 'localhost:3306';
$dbuname = 'lvp';
$dbpass = 'lvp';
$dbname = 'lvp';

$dbtype = 'MySQL';
$sitename = 'RZ DDSVEV';
$site_logo = 'images/dblogo.png';
$myurl = 'http://www.mernberger.de/LVP';
$google_maps_api="<script type=\"text/javascript\" src=\"http://www.google.com/jsapi?key=AIzaSyAuB8t_taV21SB-_g7FToCC0muLAwovEqk\"></script>";
$mailfrom='LSDB@ddsvev.de';
$mailadr_verein='verein[at]ddsvev.de';
$RZ_server='http://www.dartligaverwaltung.de/LVP/';
$mailhead='From: lsdb@ddsvev.de\n'.'X-Mailer: PHP\n'.'X-Priority: 1\n'.'Return-Path: <system@ddsvev.de>\n';	

/*
 * Common Globals 
 */
$verstring='Version 4b rev 337';
$site_header='Dartligaverwaltung  - '.$verstring;
$foot_msg = '&copy; Hristovski 2003-2008 <a href="">Impressum</a>';

function debug($VAR){
	/*
	 * debug vars by screenprinting into nice box .. use of gettype is discouraged ...
	 */
	if (is_array($VAR)){
		echo '<p style="color:black;border: 1pt solid red;background-color: #cceedf">';
		foreach($VAR as $K=>$V){
			if (is_array($V)){
				foreach($V as $K1=>$V1){
					echo '<br/>'.$VAR.':'.$K.':'.$V.':'.$K1.':'.$V1.'<br/>';
				}
			}else{
				echo '<br/>'.$VAR.':'.$K.':'.$V.'<br/>';
			}
		}
		echo '</p>';
	} else {
		echo '<p style="color:black;border: 1pt solid red;background-color: #cceedd"><br/>'.$VAR.'<br/></p>';
	}
}

?>
