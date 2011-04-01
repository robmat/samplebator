<?php
	$OUT=_input(0,'eventid',$eventid);
	$OUT=$OUT.'<table>';
	$OUT=$OUT.'<tr><td>Runde: </td><td>'.Select_EventRound('rnum',0,$eventid).'</td></tr>';
	$OUT=$OUT.'<tr><td>Location: </td><td>'.Select_Location('mlocation','',$mlocation).'</td></tr>';
	$OUT=$OUT.'<tr><td>Datum: (YYYY-MM-DD)</td><td>'._input(1,'vdate','2009-12-31',10,10).'</td></tr>'
		.'<tr><td>Heimmannschaft</td><td>Gastmannschaft</td></tr>'
		.'<tr><td>'.Select_Team('hteam','',0,$eventid).'</td><td>'.Select_Team('ateam','',0,$eventid).'</td></tr>';
	
    //$OUT=$OUT.'<tr><td>Punkte / S&auml;tze / Legs</td><td>Punkte / S&auml;tze / Legs</td></tr>'
	//	.'<tr><td>'._input(1,"vpoints[]","",4,4)._input(1,"vsets[]","",4,4)._input(1,"vlegs[]","",4,4)."</td>"
	//	.'<td>'._input(1,"vpoints[]","",4,4)._input(1,"vsets[]","",4,4)._input(1,"vlegs[]","",4,4).'</td></tr>';
	$OUT=$OUT.'<tr><td></td></tr>';
    $OUT=$OUT.'<tr><td></td><td>'._button('Erstellen').'</td></tr>';
	return $OUT=$OUT."</table>";
?>
