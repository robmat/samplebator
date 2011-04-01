<?php
class cFedaStat{
	// properties
	var $numRoundsScore=0;
	var $numRoundsCheck=0;
	var $scoreLeft=0;
	var $LegStart=501;
	var $checkZone=159;
	var $errMSG='';
	
	function cFedaStat($rScore,$rCheck,$iLeft){
		// constructor
		if ($rCheck<$rScore) return -1;
		if ($this->LegStart==501 && $rCheck<3) return -1;
		if ($iLeft>=$this->checkZone){$rCheck=$rScore;}
		
		$this->numRoundsScore=$rScore;
		$this->numRoundsCheck=$rCheck;
		$this->scoreLeft=$iLeft;
	}
	
	function _calcScoreIndex(){
		#-----------------------------------
		# BLOCK SCOREINDEX
		# comment: there is always a scoreindex !!
		#-----------------------------------
		$IDX=-1;
		if(!$this->numRoundsScore>0) return $IDX;
		
		if($this->scoreLeft > ($this->checkZone-1)){
			$IDX=($this->LegStart-$this->scoreLeft)/$this->numRoundsScore;
		} else {
			if ($this->numRoundsCheck==$this->numRoundsScore){	# same round -> direct calculation
				$IDX=($this->LegStart-$this->scoreLeft)/$this->numRoundsScore;
			} else {			# -> 159 assumption if rounds different ...
				$IDX=($this->LegStart-($this->checkZone-1))/$this->numRoundsScore;
			}
		}
		$IDX=1.5*100*$IDX;
		return $IDX;
	}
		
	function _calcCheckIndex(){
		$IDX=-1;
		if(!$this->numRoundsCheck>0) return $IDX;
		if ($this->scoreLeft==0){
			$IDX=100*(100-5*($this->numRoundsCheck-$this->numRoundsScore));
			if ($IDX <= 0) $IDX=1;
		}else{
			$IDX=0;
			# special cases / special rules ...
			# case $scoreround=$checkround player either above 159 or no turn to throw
			if ($this->numRoundsCheck==$this->numRoundsScore) $IDX = -1;
		}
		return $IDX;
	}
/*
 * purpose:  	calculate the key values for this leg, precision 0.00
 * returns:		array score,check,total
 */
	function calculateIDX(){
		$out=array(-1,-1,-1);
		$out[0]=number_format($this->_calcScoreIndex(),2,'.','');
		$out[1]=number_format($this->_calcCheckIndex(),2,'.','');
		// only sum up if both values are positive , else just scoreIDX
		if ($out[0]>0){
			$out[2]=$out[0];
			if ($out[1]>0){$out[2]=$out[2]+$out[1];}
		}
		return $out;
	}
	
}
?>