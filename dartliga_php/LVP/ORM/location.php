<?php
/**
*	purpose:	ORM mapper for ocation records, internal sourced use only
* 	params:		str action, array aP
*	returns:	named array
*/
require_once('orm.php');
class cLocation extends cDbObject{
	
	function cLocation(){
		// constructor ...
		$this->dataTable='tbllocation';
		$this->objName='location';
		$this->keyfield='id';
		$this->appkeyfield='lkey';
		$this->aDATA[$this->keyfield]=0;
	}
	
	function toString(){
		return $this->aDATA['lname'].' / '.$this->aDATA['lplz'].' '.$this->aDATA['lcity'];
	}
	
}
?>