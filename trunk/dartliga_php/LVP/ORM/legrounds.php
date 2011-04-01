<?php
/**
*	purpose:	ORM mapper for ocation records, internal sourced use only
* 	params:		str action, array aP
*	returns:	named array
*/
require_once('orm.php');
class cLegRounds extends cDbObject{
	
	function cLegRounds(){
		// constructor ...
		$this->dataTable='tbllegrounds';
		$this->objName='Legrounds';
		$this->keyfield='lid';
		$this->appkeyfield='';
		$this->aDATA[$this->keyfield]=0;
	}
	
	function toString(){
		return $this->aDATA['lgid'].' / '.$this->aDATA['lpid'];
	}
	
}
?>