<?php
/**
*	purpose:	ORM mapper for player records, internal sourced use only
* 	params:		str action, array aP
*	returns:	named array
*/
require_once('orm.php');

class cTeam extends cDbObject{
	
	function cTeam(){
		// constructor ...
		$this->dataTable='tblteam';
		$this->objName='team';
		$this->keyfield='id';
		$this->appkeyfield='tkey';
		$this->aDATA[$this->keyfield]=0;
	}
	
	// 
	// *************** end generic functions *************** //
	// 
	function toString(){
		return $this->aDATA['tname'];
	}
	
	/**
	*	purpose:	generate LineUp Table for this Team
	* 	params:		nop
	*	returns:	Recordset Array
	*/
	function getLineUp(){
		$RS=array();
		$RS=DB_listTeamLineUp($this->DB,$this->aDATA['id']);
		return $RS;
	}
}
?>