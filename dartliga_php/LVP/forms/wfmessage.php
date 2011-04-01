<?php
$OUT= '<table width=\'100%\' cellpadding=\'2\' cellspacing=\'2\'>'
		.'<tr><td valign=\'top\' class=\'bluebox\' width=\'150\'>Melde ID</td>'
		.'<td width=\'200\'>'._input(1,'wfmid',$obj['wfmessage_id'],5,5).'</td>'
		.'<td valign=\'top\' class=\'bluebox\' width=\'150\'>Empf&auml;nger</td>'
		.'<td>'.Select_MessageGroup('mgroup',$obj['mgroup_id'],1,$obj['verband_id']).'</td></tr>'
		
		.'<tr>'
		.'<td valign=\'top\' class=\'bluebox\'>Nachricht</td>'
		.'<td colspan=3>'._input(3,'wfcomment',$obj['wfcomment'],60,255).'</td></tr>';
		
	return $OUT.'</table>';
?>