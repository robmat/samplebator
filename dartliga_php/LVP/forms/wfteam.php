<?php
$OUT= "<table width=\"100%\" cellpadding=\"2\" cellspacing=\"2\">"
		."<tr><td valign=\"top\" class=\"bluebox\" width=\"150\">Melde ID</td>"
		."<td width=\"200\">"._input(1,'wfteamid',$obj['wfteam_id'],5,5)."</td>"
		."<td></td>"
		."<td></td></tr>"

		."<tr><td valign=\"top\" class=\"bluebox\">Liga *</td>"
		."<td>".Select_WFEvent('wfeventid',$obj['wfevent_id'],1,'')."</td>"
		."<td valign=\"top\" class=\"bluebox\">Teamname *</td>"
		."<td>"._input(1,'tname',$obj['teamname'],30,50)."</td></tr>"
		."<tr><td valign=\"top\" class=\"bluebox\">Verein</td>"
		."<td>"._input(2,'vereinid',$obj['verein_id'],5,5)."</td>"
		."<td valign=\"top\" class=\"bluebox\">Heimlokal *</td>"
		."<td>".Select_Location('locid','',$obj['location_id'])."</td></tr>"
		
		."<tr>"
		."<td valign=\"top\" class=\"bluebox\">Kommentar</td>"
		."<td colspan=3>"._input(1,'tcomment',$obj['tcomment'],60,60)."</td></tr>";
		
	return $OUT."</table>";
?>