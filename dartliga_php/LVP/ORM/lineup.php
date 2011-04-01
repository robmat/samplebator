<?php
/**
*	purpose:	ORM mapper for player records, internal sourced use only
* 	params:		str action, array aP
*	returns:	named array
*/
require_once('orm.php');

class cLineup extends cDbObject{
	
	function cLineup(){
		// constructor ...
		$this->dataTable='tblteamplayer';
		$this->objName='lineup';
		$this->keyfield='lid';
		$this->appkeyfield='';
		$this->aDATA[$this->keyfield]=0;
		# DEFAULTS
		$this->aDATA['lactive']=1;
		$this->aDATA['ltype']=1;
	}
	
	// 
	// *************** end generic functions *************** //
	// 
	function toString(){
		return $this->aDATA['lteamid'].' '.$this->aDATA['lplayerid'];
	}

}
?>