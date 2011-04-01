<?php
/**
*	purpose:	ORM mapper for ocation records, internal sourced use only
* 	params:		str action, array aP
*	returns:	named array
*/
require_once('orm.php');
class cLeg extends cDbObject{
	
	function cLeg(){
		// constructor ...
		$this->dataTable='tblleg';
		$this->objName='Legdarts';
		$this->keyfield='lid';
		$this->appkeyfield='';
		$this->aDATA[$this->keyfield]=0;
	}
	
	function toString(){
		return $this->aDATA['lgid'].' / '.$this->aDATA['lpid'];
	}
	
}
?>