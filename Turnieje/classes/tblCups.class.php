<?php
class TblCups extends Table {

var $tbl = 'cups';
//var $pucharClose_select = array('0' => 'otwarty', '1' => 'zamkniêty');
var $pucharClose_select = array('0' => 'Otwarty', '1' => 'Zamkniêty');
	
	function TblCups($name, $op, $id) {
		
		Table::Table($name, $op, $id);
	
		$this->cfg->cup->name = "cup";
		$this->cfg->cup->public_name = _CUPNAME;
		$this->cfg->cup->type = 'text';
		$this->cfg->cup->size = 32;
		$this->cfg->cup->maxlength = 64;
		$this->cfg->cup->not_empty = 1;
		$this->cfg->cup->edit = 1;
		
		$this->cfg->chief_pn->name = "chief_pn";
		$this->cfg->chief_pn->no_add = 1;
		
		$this->cfg->close->name = "close";
		$this->cfg->close->public_name = _PucharZamkniety;
		$this->cfg->close->radio = 'pucharClose_select';
		$this->cfg->close->edit = 1;
		

	}
	
	
	function DeleteGamesInCup($cup_id) 
	{
		$sql = "DELETE FROM "._DB_PREFIX."_games WHERE cup_id = ".$cup_id;
		$res = mysql_query($sql);
			
//		$this->makeSQL('DELETE');
//		$res = mysql_query($this->sql);
	}	
	
}
?>
