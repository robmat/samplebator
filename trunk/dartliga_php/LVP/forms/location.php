<?php
   echo '<table width=\'100%\' cellpadding=\'2\' cellspacing=\'2\'>'
		.'<tr><td valign=\'top\' class=\'bluebox\' width=\'100\'>System ID</td><td>'._input(2,'vlocid','',10,10).'</td>'
		."<td valign=\"top\" class=\"bluebox\">Aktiv (1/0) *</td><td>"._input(1,'vlocactive','',30,50).'</td></tr>'

		."<tr><td valign=\"top\" class=\"bluebox\">Name *</td><td>"._input(1,'vlocname','',30,60).'</td>'
		."<td valign=\"top\" class=\"bluebox\">Stadt *</td><td>"._input(1,'vloccity','',30,50).'</td></tr>'
		."<tr><td valign=\"top\" class=\"bluebox\">PLZ *</td><td>"._input(1,'vlocplz','',30,10).'</td>'
		."<td valign=\"top\" class=\"bluebox\">Strasse *</td><td>"._input(1,'vlocaddress','',30,50).'</td></tr>'

		.'<tr><td colspan=\'4\'><hr></td></tr>'

		."<tr><td valign=\"top\" class=\"bluebox\">Telephon</td><td>"._input(1,'vlocphone','',30,60).'</td>'
		."<td valign=\"top\" class=\"bluebox\">e-mail</td><td>"._input(1,'vlocemail','',30,50).'</td></tr>'
		.'<tr><td valign=\'top\' class=\'bluebox\'>Gebiet (1..9)</td><td>'.Select_Realm('vlocrealm',0).'</td>'
		."<td valign=\"top\" class=\"bluebox\">GMap Lngt</td><td>"._input(1,'vloccoordinates','',30,50).'</td></tr>'

		.'<tr><td></td>'
		.'<td></td>'
		.'<td></td><td>'._button('Spielort Daten Speichern').'</td></tr>';
	echo '</table>';
?>
