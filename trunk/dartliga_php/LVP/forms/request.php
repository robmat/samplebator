<?php
	/*
	 * this is always a read only form just for information ...
	 * fields must be present since the are read by js-code
	 */
	/*
   	$OUT ='<p align="right"><table width="50%" cellpadding="2" cellspacing="2">';
   	$OUT=$OUT.'<tr><td valign=\'top\' class=\'bluebox\'>Request / Type</td>';
	$OUT=$OUT.'<td>'._input(2,'id',$obj['wfrequest_id'],5,5)
					._input(2,'object',$obj['wfobject'],15,15).'</td>';
	$OUT=$OUT.'<td valign=\'top\' class=\'bluebox\'>RKey / Verein</td>';
	$OUT=$OUT.'<td>'._input(2,'rkey',$obj['rkey'],20,20)._input(2,'vid',$obj['verein_id'],5,5).'</td></tr>';
	$OUT=$OUT.'</table></p>';
	*/
	$OUT= '<p style="color:#aaaaaa;border: 1pt solid #cccccc;background-color: #eeeeee;text-align: right">';
	$OUT=$OUT.'R:'.$obj['wfrequest_id'].'-T:'.$obj['wfobject'].'-K:'.$obj['rkey'].'-V'.$obj['verein_id'];
	$OUT=$OUT._input(0,'id',$obj['wfrequest_id'],5,5)._input(0,'object',$obj['wfobject'],15,15)._input(0,'rkey',$obj['rkey'],10,10)._input(0,'vid',$obj['verein_id'],5,5);
	$OUT=$OUT.'</p>';
	return $OUT;
?>
