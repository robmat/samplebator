<?php
	/*
	 * define the membership form to be used as an embedded child detail.
	 * no action buttons, simple table
	 */
	$FRM= '<table width="100%" cellpadding="2" cellspacing="2">'
		
	.'<tr><td valign="top" class="bluebox">Pass Nr.</td>'
		.'<td><table><tr><td>'._input(1,'vpassnr',$aMEM['mpassnr'],30,10).'</td><td>'._button('Generate Pass','genautopass()').'</td></tr></table></td>'
		.'<td valign="top" class="bluebox">Meldung</td>'
		.'<td>'.Select_Membertype('mtype',$aMEM['mtype'],'setmdate(this)',0).'</td></tr>'
		
	.'<tr><tr><td valign="top" class="bluebox">Verein</td>'
		.'<td>'.Select_Verein('vid',$aMEM['mvereinid'],$usertoken['registermap'],'',0).'</td>'		// name = vverein, return from resp-realm only ...
		.'<td valign="top" class="bluebox">Beginn</td>'
		.'<td>'._input(1,'vmstart',$aMEM['mstart'],14,12).'</td>'
		.'<td valign="top" class="bluebox">Ende</td>'
		.'<td>'._input(1,'vmend',$aMEM['mend'],14,12).'</td></tr>'

	.'<tr><td colspan="2">Last modification by: '.$aMEM['mcre_user'].' on '.$aMEM['mcre_date'].'</td>'
		.'<td>'._input(0,'vmid',$aMEM['mid'])._input(0,'vpid',$aMEM['mpid']).'</td></tr>';
		
	return $FRM.'</table>';
?>