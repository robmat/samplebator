<?php
	/*
	 * internal form render, caller is responsible for the form action
	 * changed to _theme widgets and introduced checkboxes ...
	 * req: array: $vereininfo, access to selectLists
	 */
	
	$OUT="<table width=\"100%\" cellpadding=\"2\" cellspacing=\"2\">";
		$OUT=$OUT."<tr><td valign=\"top\" width=\"50%\" colspan=2><h3>".$vereininfo['vname']."</h3></td>"
		."<td width=\"50%\" colspan=2><img src='images/logos/logo".$vereininfo['vid'].".png' border='0'></td></tr>"
		
		."<tr><td valign=\"top\" class=\"bluebox\">DB id</td>"
		.'<td>'._input(2,'vid',$vereininfo['vid']).'</td>'
		."<td valign=\"top\" class=\"bluebox\">* Zust&auml;ndigkeit</td>"
		#.'<td>'.Select_RealmFromRegisterMap('vrealm',$vereininfo['verband_id']).'</td></tr>'
		.'<td>'.Select_Realm('vrealm',$vereininfo['verband_id'],0,'').'</td></tr>'
		.'<tr><td></td>'
		.'<td></td>'
		."<td valign=\"top\" class=\"bluebox\">Vereinslogik (Passnr)<br>(10,AS,xy)</td>"
		.'<td>'._input(1,'vlogic',$vereininfo['vlogic'],30,3).'</td></tr>'
		."<tr><td valign=\"top\" class=\"bluebox\">* Vereinsname (kurz)</td>"
		
		.'<td>'._input(1,'vname',$vereininfo['vname'],30,30).'</td>'
		."<td valign=\"top\" class=\"bluebox\">Vereinsname (lang)</td>"
		.'<td>'._input(1,'vfullname',$vereininfo['vfullname'],30,40).'</td></tr>'
		."<tr><td colspan=\"4\"><hr></td></tr>"

		."<tr><td valign=\"top\" class=\"bluebox\">Klubadresse</td>"
		.'<td>'._input(1,'vaddressclub',$vereininfo['vaddressclub'],30,50).'</td>'
		."<td valign=\"top\" class=\"bluebox\">Ort / PLZ</td>"
		.'<td>'._input(1,'vort',$vereininfo['vort'],30,40).'</td></tr>'
		.'<tr><td></td>'
		.'<td></td>'
		."<td valign=\"top\" class=\"bluebox\">Bundesland</td>"
		.'<td>'._input(1,'vbundesland',$vereininfo['vbundesland'],30,40).'</td></tr>'
		."<tr><td valign=\"top\" class=\"bluebox\">e-mail (club)</td>"
		.'<td>'._input(1,'vemail',$vereininfo['vemail'],30,50).'</td>'
		."<td valign=\"top\" class=\"bluebox\">Web Site</td>"
		.'<td>'._input(1,'vwebsite',$vereininfo['vwebsite'],30,40).'</td></tr>'
		
		."<tr><td colspan=\"4\"><hr></td></tr>"
		
		."<tr><td valign=\"top\" class=\"bluebox\">Kontakt (Pr&auml;sident)</td>"
		.'<td>'._input(1,'vaddress',$vereininfo['vaddress'],30,50).'</td>'
		."<td valign=\"top\" class=\"bluebox\">Ungf. Anzahl Mitglieder</td>"
		.'<td>'._input(1,'vmembercount',$vereininfo['vmembercount'],30,5).'</td></tr>'
		."<tr><td valign=\"top\" class=\"bluebox\">Electronic Darts</td>"
		.'<td>'._checkbox('vsoft',$vereininfo['vsoft']).'</td>'
		."<td valign=\"top\" class=\"bluebox\">Steel Darts</td>"
		.'<td>'._checkbox('vsteel',$vereininfo['vsteel']).'</td></tr>'
		
		."<tr><td valign=\"top\" class=\"bluebox\">Zeige Link in Liste 1</td>"
		.'<td>'._checkbox('vhomepagelink',$vereininfo['vHomePageLink']).'</td>'
		
		."<td valign=\"top\" class=\"bluebox\">Vereinsregister Nr.</td>"
		.'<td>'._input(1,'tpolmeldung',$vereininfo['tpolmeldung'],30,25).'</td></tr>'
		
		."<tr><td valign=\"top\" class=\"bluebox\">Zeige Link in Liste 2</td>"
		.'<td>'._checkbox('voedsopagelink',$vereininfo['oedsopagelink']).'</td>'
		
		.'<td></td>'
		.'<td></td></tr>'
		
		."<tr><td colspan=2><i>Last change: ".$vereininfo['cre_user']." ".$vereininfo['cre_date']."</i></td><td></td>"
		."<td align=\"right\">"._button("Vereins Daten Speichern")."</td></tr>";
	
	return $OUT."</table>";
?>