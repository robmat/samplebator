<?php
/**
*	purpose:	ORM mapper for ocation records, internal sourced use only
* 	params:		str action, array aP
*	returns:	named array
*/
require_once('orm.php');
class cVerein extends cDbObject{
	
	function cVerein(){
		// constructor ...
		$this->dataTable='tverein';
		$this->objName='Verein';
		$this->keyfield='vid';
		$this->appkeyfield='';
		$this->aDATA[$this->keyfield]=0;
	}
	
	function toString(){
		return $this->aDATA['vname'].' / '.$this->aDATA['vort'];
	}
	
}
?>