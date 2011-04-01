<?php
/*
 * make sure that idnames are syncronized between player,wfplayer,membership forms
 * some js-functions are shared ...
 */
if (isset($WFPLAYERFORM_READONLY)){$input_flag=2;}else{$input_flag=1;}

$OUT="<table width=\"100%\" cellpadding=\"2\" cellspacing=\"2\">"
		."<tr><td valign=\"top\" class=\"bluebox\" width=\"150\">PID / WFPID</td>"
		."<td width=\"200\">"._input(2,'pid',$obj['pid'],6,6)._input(2,'wfpid',$obj['wfplayer_id'],6,6).'</td>'
		."<td valign=\"top\" class=\"bluebox\" width=\"150\">Meldeart *</td>"
		."<td>".Select_Membertype('mtype',$obj['ttypemember_id']).'</td></tr>';

		$OUT=$OUT.'<tr><td valign="top" class="bluebox">Neuer Pass</td>'
		.'<td>'._button('Generate Pass','genautopass()').'</td>'
		.'<td valign="top" class="bluebox">Auto PassNr.</td>'
		.'<td>'._input(2,'vpassnr',$obj['ppassnr'],12,20).'</td></tr>';
		
		$OUT=$OUT."<tr><td valign=\"top\" class=\"bluebox\">Vorname *</td>"
		."<td>"._input($input_flag,'wffname',$obj['pfname'],30,40).'</td>'
		."<td valign=\"top\" class=\"bluebox\">Nachname *</td>"
		."<td>"._input($input_flag,'wflname',$obj['plname'],30,40)."</td></tr>"

		."<tr><td valign=\"top\" class=\"bluebox\">Geb. Datum * (yyyy-mm-dd)</td>"
		."<td>"._input($input_flag,'wfbirthdate',$obj['pbirthdate'],30,20).'</td>'
		."<td valign=\"top\" class=\"bluebox\" width=\"150\">Geschlecht (H/D/J)</td>"
		."<td width=\"200\">".Select_Gender('vgender',$obj['pgender'])."</td></tr>"
		
		."<tr><td colspan=\"4\"><hr></td></tr>"

		."<tr><td valign=\"top\" class=\"bluebox\">PLZ</td>"
		."<td>"._input($input_flag,'wfplz',$obj['pplz'],30,20).'</td>'
		."<td valign=\"top\" class=\"bluebox\">Stadt</td>"
		."<td>"._input($input_flag,'wftown',$obj['ptown'],30,40)."</td></tr>"

		."<tr>"
		."<td valign=\"top\" class=\"bluebox\">Strasse</td>"
		."<td colspan=3>"._input($input_flag,'wfstreet',$obj['pstreet'],60,40)."</td></tr>"
		
		."<tr><td valign=\"top\" class=\"bluebox\">Tel</td>"
		."<td>"._input($input_flag,'wftel1',$obj['ptel1'],30,20).'</td>'
		."<td valign=\"top\" class=\"bluebox\">E-mail</td>"
		."<td>"._input($input_flag,'wfemail',$obj['pemail'],30,40)."</td></tr>"
		
		.'<tr>'
		.'<td valign="top" class="bluebox">Kommentar</td>'
		.'<td colspan="2">'._input($input_flag,'pcomment',$obj['pcomment'],60,60).'</td>'
		.'<td></td></tr>';
		$OUT=$OUT.'</table><div id="check"><table class="tchild" id="lineupP'.$obj['pid'].'" name="lineupP'.$obj['pid'].'">';
		# ////////////////////// inject the master child table here ...'
		#$RS1=DB_listEventWFTeamPlayers($dbi,1,$obj['pid']);
		#$RS2=DB_listEventTeamWFPlayers($dbi,1,$obj['pid']);
		#$RS=array_merge($RS1,$RS2);
		#if (!sizeof($RS)>0) return ('<font color=green>Keine Mannschaftsmeldungen f&uuml;r Spieler:'.$obj['pid'].'</font>');
		#$aTH=array('LigaGruppe','Bewerb / Liga','Saison','Teamname','Vorname','Nachname',);
		#$OUT=$OUT.'';
		#$OUT=$OUT.ArrayToTableHead($aTH);
		#$OUT=$OUT.RecordsetToDataTable($RS,array(1,3,4,6,8,9));
		# ////////////////////
		return $OUT.'</table></div>';
?>