<?php
class TblTeams extends Table {

var $tbl = 'teams';
	
	function TblTeams($name, $op, $id, $cup_id) {
		
		Table::Table($name, $op, $id);
	
		$this->cfg->team->name = "team";
		$this->cfg->team->public_name = _Druzyna;
		$this->cfg->team->type = 'text';
		$this->cfg->team->maxlength = 32;
		$this->cfg->team->not_empty = 1;
		$this->cfg->team->edit = 1;
				
		$this->cfg->cpt_pn->name = "cpt_pn";
		$this->cfg->cpt_pn->public_name = _Kapitan;
		$this->cfg->cpt_pn->db_list2 = ''._DB_PREFIX.'_players';
		$this->cfg->cpt_pn->db_list_pk = "player_number";
		$this->cfg->cpt_pn->db_list_sel1 = "lname";
		$this->cfg->cpt_pn->db_list_sel2 = "fname";
//		$this->cfg->cpt_pn->db_list_cond = " team_id = 0 ";
		$this->cfg->cpt_pn->db_list_cond = " cup_id = ".$cup_id;
		$this->cfg->cpt_pn->not_empty = 0;
	//	$this->cfg->cpt_pn->no_add = 1;		

		$this->cfg->cup_id->name = "cup_id";
		$this->cfg->cup_id->public_name = _CUPNAME;
		$this->cfg->cup_id->db_list = ''._DB_PREFIX.'_cups';
		$this->cfg->cup_id->db_list_pk = "id";
		$this->cfg->cup_id->db_list_sel = "cup";
		$this->cfg->cup_id->db_list_cond = " close = 0";
		$this->cfg->cup_id->not_empty = 1;
		
	}
	
	function teamMenu($cid) {
		$sql = "SELECT * FROM "._DB_PREFIX."_teams WHERE cpt_pn = ".$cid;
		$sql.= " and cup_id = ".$_COOKIE['CUP_ID'];
 		
		$res = mysql_query($sql);
		$obj = mysql_fetch_object($res);
		
		$out = _Druzyna." ".$obj->team." <br /><br />";
		
		$sql = "SELECT id, CONCAT(lname, ,fname) AS name FROM "._DB_PREFIX."_players ";
    $sql.= " WHERE team_id = ".$obj->id." ORDER BY lname";
		$res = mysql_query($sql);
		
		$out .= ""._Zawodnicy." <br /><br />";
		$out .= '<a href="index.php?name=Turnieje&op=addteamplayer&id='.$obj->id.'">'._Dodajzawodnika.'</a> <br />';
		if(mysql_num_rows($res)) {
			$out .= '<form name="players" method="post" action="'.$_SERVER['PHP_SELF'].'"><table>';
			while($obj = mysql_fetch_object($res)) {
				$out .= '<tr><td><input type="checkbox" name="'.$obj->id.'"></td><td>'.$obj->name.'</td></tr>';
			}
			$out .= '<tr><td colspan="2"><input type="hidden" name="op" value="'.$this->op.'"><input type="hidden" name="name" value="'.$this->name.'"><input type="submit" name="delete" value="'._Usunzawodnika.'"></td></tr></table></form>';
		}
		else $out .= _Wdruzynieniemazawodnikow;
		return $out;
	}

	function teamName($team_id) {
		$sql = "SELECT * FROM "._DB_PREFIX."_teams WHERE id = ".$team_id;
		$res = mysql_query($sql);
		$obj = mysql_fetch_object($res);
		
		$out = _Druzyna." ".$obj->team." <br /><br />";
		return $out;
	}

	function SetFreePlayer($team_id) 
	{
		$sql = "UPDATE "._DB_PREFIX."_players set team_id = 0 where team_id = ".$team_id; 
		$res = mysql_query($sql);
	}		
	
	
	function showFreePlayers($tid, $lttr) {
		$sql = "SELECT team FROM "._DB_PREFIX."_teams WHERE id = ".$tid;
		$res = mysql_query($sql);
		$obj = mysql_fetch_object($res);
		$out = _Dodajzawodnikowdodruzyny.' '.$obj->team.'<br /><br />';
		
		for($i=65; $i<=90; $i++) {
			$out .= '<a href="index.php?name=Turnieje&op=addteamplayer&id='.$tid.'&lttr='.chr($i).'">'.chr($i).'</a> ';
		}
		$out .= "<br /><br />";
		
		if(!$lttr) $lttr = 'A';
		$sql = "SELECT id, CONCAT(lname, ,fname) AS name FROM "._DB_PREFIX."_players ";
    $sql.= " WHERE lname LIKE '".$lttr."%' AND user_group != '0' ";
    $sql.= " AND user_group != '2' AND username !='Anonymous' ";
    $sql.= " AND (team_id IS NULL OR team_id = '0') ORDER BY lname";
		$res = mysql_query($sql);
		
		if(mysql_num_rows($res)) {
			$out .= '<form name="players" method="post" action="'.$_SERVER['PHP_SELF'].'"><table>';
			while($obj = mysql_fetch_object($res)) {
				$out .= '<tr><td><input type="checkbox" name="'.$obj->id.'"></td><td>'.$obj->name.'</td></tr>';
			}
			$out .= '<tr><td colspan="2"><input type="hidden" name="id" value="'.$tid.'"><input type="hidden" name="op" value="'.$this->op.'"><input type="hidden" name="name" value="'.$this->name.'"><input type="submit" name="add" value="'._Dodaj.'"></td></tr></table></form>';
		}
		else $out .= _Niemawolnychzawodnikow;
		return $out;
	}
	
	function saveTeamPlayers() {
		foreach($_POST as $key => $value) {
			if(is_numeric($key) && $value == 'on') {
				$sql = "UPDATE "._DB_PREFIX."_players SET team_id = '".$_POST['id']."' WHERE id = ".$key;
				$res = mysql_query($sql);
			}
		}
	}
	
	function delTeamPlayers() {
		foreach($_POST as $key => $value) {
			if(is_numeric($key) && $value == 'on') {
				$sql = "UPDATE "._DB_PREFIX."_players SET team_id = 0 WHERE id = ".$key;
				$res = mysql_query($sql); 
			}
		}	
	}
	
}
?>
