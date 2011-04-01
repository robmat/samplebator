<?php
/**
*	purpose:	ORM mapper for player records, internal sourced use only
* 	params:		str action, array aP
*	returns:	named array
*/
require_once('orm.php');
class cPlayer extends cDbObject{
	
	function cPlayer(){
		// constructor ...
		$this->dataTable='tplayer';
		$this->objName='player';
		$this->keyfield='pid';
		$this->appkeyfield='pkey';
		$this->aDATA[$this->keyfield]=0;
	}
	
	function toString(){
		return $this->aDATA['pfname'].' '.$this->aDATA['plname'];
	}
	
	function saveMembershipVerein($verein_id,$typemember_id,$passnr='#NOP#',$v_mstart='',$v_mend=''){
		// insert - update new verein - adds a current saison membership record
		// get Verein -> LV
		// store actual period
		$p=sql_query('SELECT * from tverein WHERE vid='.$verein_id,$this->DB);
		$aV=sql_fetch_array($p,$this->DB);
		if (sizeof($aV)<3) {$this->pError='cPlayer::X1';return;}
		// just run the INSERT
		if (strlen($v_mstart)<4){
			$d = getdate();
			$upd_date = $d['year'].'-'.$d['mon'].'-'.$d['mday'];
			if ($typemember_id==2){
				$v_mstart=$d['year'].'-08-01';
				$v_mend=(intval($d['year'])+1).'-07-31';
				#TODO here we could automigically use the correct passnr ...
				#	this might not be correct ....
				if (strlen($passnr)<6){$passnr=$this->aDATA['pfkey1'];}
			} else {
				$v_mstart=$d['year'].'-01-01';
				$v_mend=$d['year'].'-12-31';
				if (strlen($passnr)<6){$passnr=$this->aDATA['pfkey2'];}
			}
		}
		$qry='INSERT into tmembership(mid,mpid,mtype,mpassnr,mstart,mend,mvereinid,mcre_user,mcre_date,mstatus,mflag)'
		.' VALUES(0,'.$this->aDATA[$this->keyfield].','.$typemember_id.',\''.$passnr.'\',\''.$v_mstart.'\',\''.$v_mend.'\','.$verein_id.',\'wfprocess\',\''.$upd_date.'\',0,0)';
		#debug($qry);
		if (!$ans=sql_query($qry,$this->DB)) $this->pError='Error saveMembershipVerein';
	}
	
	function saveMembershipTeam($team_id,$typelineup=1,$typemember_id=1){
		// insert - update new team + saison membership record ??
		$p=sql_query('SELECT * from tblteam WHERE id='.$team_id,$this->DB);
		$aT=sql_fetch_array($p,$this->DB);
		if (sizeof($aT)<3) {$this->pError='cPlayer::X2';return;}
		// INSERT LineUp
		$qry='INSERT into tblteamplayer(leventid,lteamid,lplayerid,lactive,ltype)'
		.' VALUES('.$aT['tevent_id'].','.$aT['id'].','.$this->aDATA[$this->keyfield].',1,'.$typelineup.')';
		if (!$ans=sql_query($qry,$this->DB)) $this->pError='Error saveMembershipTeam';
		// $this->saveMembershipVerein($aT['tverein_id'],$typemember_id);
	}
	
}
?>