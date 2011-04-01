<?php
$OUT= "<table width=\"100%\" cellpadding=\"2\" cellspacing=\"2\">"
		."<tr><td valign=\"top\" class=\"bluebox\" width=\"150\">Team ID</td>"
		."<td width=\"200\">"._input(2,'tid',$aRec['id'],10,10)."</td>"
		."<td></td>"
		."<td></td></tr>"

		."<tr><td valign=\"top\" class=\"bluebox\">Liga *</td>"
		."<td>".Select_Event($idname='eventid',$aRec['tevent_id'],1,'')."</td>"
		."<td valign=\"top\" class=\"bluebox\">Teamname *</td>"
		
		.'<td>'._input(1,'vtname',$aRec['tname'],30,50).'</td></tr>'
		.'<tr><td valign=\'top\' class=\'bluebox\'>Verein *</td>'
		.'<td>'.Select_Verein('vverein',$aRec['tverein_id'],array(1,2,3,4,5,6,7,8,9)).'</td>'
		.'<td valign=\'top\' class=\'bluebox\'>Heimlokal *</td>'
		.'<td>'.Select_Location('vlocid','',$aRec['tlocation_id']).'</td></tr>'

		.'<tr><td></td>'
		.'<td></td>'
		.'<td></td><td>'._button('Team Daten Speichern').'</td></tr>';
	return $OUT.'</table>';
?>