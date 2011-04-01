<?php
/**
*	purpose:	render form to search players+membership records
* 	params:		none
*	returns:	form
* 	code:		lsdbfunc.js::listmembers()
*/
	$ret='Zeige Meldungen:<br />';
	$ret=$ret.Select_RealmFromRegisterMap('vrealm',0,'listmemberr()').Select_Membertype('mtyper',1,'listmemberr()',0);
	$ret=$ret.'<br />'._checkbox('mcurrent',1,'Nur Aktuelle');
	return $ret;
	#$OUT=$OUT._button('Zeige Liste','listmemberr()');
?>