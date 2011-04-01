<?php
$OUT= 'Key-1: '.$aP['pfkey1'].' / Key-2: '.$aP['pfkey2'];
$OUT=$OUT."<table width=\"100%\" cellpadding=\"2\" cellspacing=\"2\">"
		."<tr><td valign=\"top\" class=\"bluebox\" width=\"150\">System ID</td>"
		."<td width=\"200\">".$aP['pid'].'</td>'
		."<td valign=\"top\" class=\"bluebox\">Aktiv (1/0)</td></td>"
		.'<td><input type=text size=3 maxlength=1 name=\'vactive\' value='.$aP['pactive'].'></td></tr>'

		."<tr><td valign=\"top\" class=\"bluebox\">Vorname *</td>"
		."<td>"._input(1,'vfname',$aP['pfname'],30,180).'</td>'
		."<td valign=\"top\" class=\"bluebox\">Nachname *</td>"
		."<td>"._input(1,'vlname',$aP['plname'],30,180).'</td></tr>'

		."<tr><td valign=\"top\" class=\"bluebox\">Geb. Datum * (yyyy-mm-dd)</td>"
		."<td>"._input(1,'vbirthdate',$aP['pbirthdate'],30,20).'</td>'
		."<td valign=\"top\" class=\"bluebox\" width=\"150\">Geschlecht (H/D/J)</td>"
		."<td width=\"200\">".Select_Gender('vgender',$aP['pgender']).'</td></tr>'
		
		."<tr><td colspan=\"4\"><hr></td></tr>"

		."<tr><td valign=\"top\" class=\"bluebox\">Nationalit&auml;t</td>"
		."<td>"._input(1,'vnation',$aP['pnationality'],30,30).'</td>'
		."<td valign=\"top\" class=\"bluebox\">Stadt</td>"
		."<td>"._input(1,'vtown',$aP['ptown'],30,180).'</td></tr>'

		."<tr><td valign=\"top\" class=\"bluebox\">PLZ</td>"
		."<td>"._input(1,'vplz',$aP['pplz'],30,20).'</td>'
		."<td valign=\"top\" class=\"bluebox\">Strasse</td>"
		."<td>"._input(1,'vstreet',$aP['pstreet'],30,255).'</td></tr>'
		
		."<tr><td valign=\"top\" class=\"bluebox\">Tel Privat</td>"
		."<td>"._input(1,'vtel1',$aP['ptel1'],30,20).'</td>'
		."<td valign=\"top\" class=\"bluebox\">Tel Mobil</td>"
		."<td>"._input(1,'vtel2',$aP['ptel2'],30,20).'</td></tr>'

		."<tr><td valign=\"top\" class=\"bluebox\">E-mail</td>"
		."<td>"._input(1,'vemail',$aP['pemail'],30,40).'</td>'
		."<td valign=\"top\"></td>"
		."<td></td></tr>"
		
		."<tr><td valign=\"top\" class=\"bluebox\">Kommentar</td>"
		."<td>"._input(1,"vcomment",$aP['pcomment'],30,100).'</td>'
		."<td valign=\"top\"></td>"
		.'<td></td></tr>'
		
		."<tr><td colspan=2>Last modification by: ".$aP['pupd_user']." on ".$aP['pupd_date'].'</td>'
		."<td colspan=2 align=\"right\">"._button('Spieler Daten Speichern').'</td></tr>';
		return $OUT.'</table>';
?>