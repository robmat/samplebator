<?php
$OUT='';
$OUT='<table id="faccount" width="100%" cellpadding="2" cellspacing="2">'
	.'<tr>'
	.'<td>Administration System Account: '._input(1,'vusername',$vereininfo['username'],20,20).'</td>'
	.'<td><div id="monitor"></div></td>'
	.'<td>'._button('Check','vaccount("check")').'</td>'
	.'<td>'._button('Create','vaccount("create")').'</td>'
	.'<td>'._button('Unlock','vaccount("unlock")').'</td>'
	.'<td>'._button('Reset','vaccount("reset")').'</td>'
	.'<td>'._button('Lock','vaccount("lock")').'</td></tr></table>';
	
	return $OUT;
?>