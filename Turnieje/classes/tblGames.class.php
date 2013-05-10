<?php
class TblGames extends Table {

var $tbl = 'games';
var $mode_select = array('1' => 'Einzel', '2' => 'Doppel', '3' => 'Triple');
//var $mode_select = array('1' => 'Single', '2' => 'Double', '3' => 'Triple');
	
	function TblGames($name, $op, $id) {
		
		Table::Table($name, $op, $id);
		
		$this->cfg->cup_id->name = "cup_id";
		$this->cfg->cup_id->public_name = _CUPNAME;
		$this->cfg->cup_id->db_list = _DB_PREFIX.'_cups';
		$this->cfg->cup_id->db_list_pk = "id";
		$this->cfg->cup_id->db_list_sel = "cup";
		$this->cfg->cup_id->db_list_cond = "close!=1";
		$this->cfg->cup_id->not_empty = 1;
		
		$this->cfg->chief_pn->name = "chief_pn";
		$this->cfg->chief_pn->no_add = 1;
	
		$this->cfg->game->name = "game";
		$this->cfg->game->public_name = _GAMENAME;
		$this->cfg->game->type = 'text';
		$this->cfg->game->maxlength = 64;
		$this->cfg->game->not_empty = 1;
		
		$this->cfg->town->name = "town";
		$this->cfg->town->public_name = _GAMETOWN;
		$this->cfg->town->type = 'text';
		$this->cfg->town->maxlength = 32;
		$this->cfg->town->not_empty = 1;
		
		$this->cfg->place->name = "place";
		$this->cfg->place->public_name = _GAMEPLACE;
		$this->cfg->place->type = 'text';
		$this->cfg->place->maxlength = 32;
		$this->cfg->place->not_empty = 1;
		
		$this->cfg->dt->name = "dt";
		$this->cfg->dt->public_name = _GAMEDATE;
		$this->cfg->dt->type = 'text';
		$this->cfg->dt->maxlength = 10;
		$this->cfg->dt->is_date = 1;
		$this->cfg->dt->not_empty = 1;
		
		$this->cfg->game_mode->name = "game_mode";
		$this->cfg->game_mode->public_name = _Rodzajgry;
		$this->cfg->game_mode->radio = 'mode_select';
		$this->cfg->game_mode->not_empty = 1;
		
		$this->cfg->ratio->name = "ratio";
		$this->cfg->ratio->no_add = 1;
		
		$this->cfg->game_status->name = "game_status";
		$this->cfg->game_status->no_add = 1;
	}
	
	function checkOwner($cup_id, $user_id, $user_group) {
		if($user_group == 4) return true;
		$sql = "SELECT chief_pn FROM "._DB_PREFIX."_cups WHERE id = ".$cup_id;
		$res = mysql_query($sql);
		$obj = mysql_fetch_object($res);
		if($obj->chief_pn != $user_id) return false;
		else return true;
	}
	
	function checkGameStatus($pid, $pcat) {
		$sql = "SELECT game_status, chief_pn, dt FROM "._DB_PREFIX."_games WHERE id = ".$this->id;
		$res = mysql_query($sql);
		$obj = mysql_fetch_object($res);

    $trzydnitemu = date('Y-m-d',mktime(0, 0, 0, date("m")  , date("d")-3, date("Y")));// zmiana liczby dnie przez ktore mozemy edytowac wyniki zawsze z minusem '-'
//echo '>>>'.$obj->dt.'  '.$trzydnitemu ;
		if($obj->dt < $trzydnitemu) 
      { $this->err = _NieMoznaDodacWynikuMinely3Dni; return false; }

		if($obj->game_status == 'closed') 
      { $this->err = _TenturniejjuzmawprowadzonewynikiEdytujwynikiturnieju; return false; }
		if($obj->chief_pn != $pid && $pcat != 4) 
      { $this->err = _Niemaszprawdowprowadzaniawynikowtegoturnieju; return false; }
		

		return true;
	}
	
	function addGameProperties($ratioErr=false, $playersErr=false) {
		
		isset($_POST['ratio']) ? $ratio = $_POST['ratio'] : $ratio = '1.0';
		$sql = "SELECT game, game_mode FROM "._DB_PREFIX."_games WHERE id = ".$this->id;
		$res = mysql_query($sql);
		$obj = mysql_fetch_object($res);
		$out = _Turniej.': '.$obj->game.'<br /><br />';
		$out .= '<form name="game_ratio" method="post" action="'.$_SERVER['PHP_SELF'].'"><table>';
		$out .= '<tr><td>'._Wprowadzwspolczynnikobliczeniowyturnieju.': </td><td><input type="text" size="3" maxlength="3" name="ratio" value="'.$ratio.'"></td><td>';
		if($ratioErr) $out .= _Nieprawidlowyformatwspolczynnika.'.';
		$out .= '</td></tr>';
		$out .= '<tr><td>'._Wprowadziloscgraczyturnieju.': </td><td><input type="text" size="3" maxlength="3" name="players" value="'.$_POST['players'].'"></td><td>';
		if($playersErr) $out .= _Nieprawidlowailoscgraczy.'.';
		$out .= '</td></tr>';
		$out .= '<tr><td colspan="2">' .
				'<input type="hidden" name="mode" value="'.$obj->game_mode.'">' .
				'<input type="hidden" name="name" value="'.$this->name.'">' .
				'<input type="hidden" name="op" value="'.$this->op.'">' .
				'<input type="hidden" name="id" value="'.$this->id.'">' .
				'<input type="submit" name="submit" value="'._Wyslij.'"></td></tr>';
		$out .= '</table></form>';
		/*
		echo "ratio players mode value=".$obj->game_mode." <br>
				name =".$this->name." <br> 
				op =".$this->op." <br> 
				id =".$this->id;
			*/	
	return $out;
	}

	function tblShowGames($option) {
		
	global $printMe;
		$sqlchunk = '';
		$printChunk = '';
		$out = '';
		foreach($this->c as $key =>$value) {
			if(!empty($value)) {
				$sqlchunk .= " ".$key." = '".$value."' AND";
				$printChunk .= '&'.$key.'='.$value;
			}
		}
		
		if ($sqlchunk) {
			$sqlchunk = substr($sqlchunk, 0, strlen($sqlchunk)-4);
			//$sql = 'SELECT '._DB_PREFIX.'_games.id, cup_id, game, town, place, dt, cup, COUNT(game_id) AS players FROM '._DB_PREFIX.'_games LEFT JOIN '._DB_PREFIX.'_cups ON '._DB_PREFIX.'_cups.id = '._DB_PREFIX.'_games.cup_id LEFT JOIN '._DB_PREFIX.'_results ON '._DB_PREFIX.'_games.id = game_id WHERE'.$sqlchunk.' GROUP BY game_id ORDER BY dt DESC';
			//zmiana - teraz wyszukuje tylko nie zamkniete puchary
			$sql = 'SELECT '._DB_PREFIX.'_games.id, cup_id, game, town, place, dt, cup, COUNT(game_id) AS players ';
      $sql.= ' FROM '._DB_PREFIX.'_games LEFT JOIN '._DB_PREFIX.'_cups ON '._DB_PREFIX.'_cups.id = '._DB_PREFIX.'_games.cup_id ';
      $sql.= ' LEFT JOIN '._DB_PREFIX.'_results ON '._DB_PREFIX.'_games.id = game_id ';
      $sql.= ' WHERE'.$sqlchunk.' AND  close <> 1 ';
      $sql.= ' GROUP BY game_id ORDER BY dt DESC';
			
			$res = mysql_query($sql);
			
			
		}
		else {
		$this->err = _NOQUERY;
			return false;
		}
			if(!$printMe) $out .= '<a href="index.php?name=Turnieje&op=print'.$printChunk.'">'._Wersjadodruku.'</a><br /><br />';
			$out .= '<table width="100%"><tr><td>'._CUP.'</td><td>'._Ilosczawodnikow.'</td><td>'._GAME.'</td><td>'._GAMETOWN.'</td><td>'._GAMEPLACE.'</td><td>'._GAMEDATE.'</td></tr>';
	
		if(mysql_num_rows($res)) {
			while($obj = mysql_fetch_object($res)) {
				$out .= '<tr><td>'.$obj->cup.'</td><td>'.$obj->players.'</td><td><a href="index.php?name=Turnieje&op='.$option.'&id='.$obj->id.'">'.$obj->game.'</a></td><td>'.$obj->town.'</td><td>'.$obj->place.'</td><td>'.implode("-", array_reverse(explode("-", $obj->dt))).'</td></tr>';
			}
			$out .= '</table>';
			return $out;
		} else {
			$this->err = _NOQUERYRESULTS;
			return false;
		}
	}
	
	function tblShowGames2($option) {
		
	global $printMe;
		$sqlchunk = '';
		$printChunk = '';
		$out = '';
		foreach($this->c as $key =>$value) {
			if(!empty($value)) {
				$sqlchunk .= " ".$key." = '".$value."' AND";
				$printChunk .= '&'.$key.'='.$value;
			}
		}
		
		if ($sqlchunk) {
			$sqlchunk = substr($sqlchunk, 0, strlen($sqlchunk)-4);
			//$sql = 'SELECT '._DB_PREFIX.'_games.id, cup_id, game, town, place, dt, cup, COUNT(game_id) AS players FROM '._DB_PREFIX.'_games LEFT JOIN '._DB_PREFIX.'_cups ON '._DB_PREFIX.'_cups.id = '._DB_PREFIX.'_games.cup_id LEFT JOIN '._DB_PREFIX.'_results ON '._DB_PREFIX.'_games.id = game_id WHERE'.$sqlchunk.' GROUP BY game_id ORDER BY dt DESC';
			//zmiana - teraz wyszukuje tylko nie zamkniete puchary
			$sql = 'SELECT '._DB_PREFIX.'_games.id, cup_id, game, town, place, dt, cup, COUNT(game_id) AS players ';
      $sql.= ' FROM '._DB_PREFIX.'_games LEFT JOIN '._DB_PREFIX.'_cups ON '._DB_PREFIX.'_cups.id = '._DB_PREFIX.'_games.cup_id ';
      $sql.= ' LEFT JOIN '._DB_PREFIX.'_results ON '._DB_PREFIX.'_games.id = game_id ';
      $sql.= ' WHERE'.$sqlchunk.' AND  close <> 1 ';
      $sql.= ' GROUP BY game_id ORDER BY dt DESC';
			
			$res = mysql_query($sql);
			
			
		}
		else {
		$this->err = _NOQUERY;
			return false;
		}
			if(!$printMe) $out .= '<a href="index.php?name=Turnieje&op=print'.$printChunk.'">'._Wersjadodruku.'</a><br /><br />';
			$out .= '<table border="0" rules="all" width="100%"><tr><td style="font-size:13px"><center>'._CUP.'</center></td><td style="font-size:13px"><center>Anz. Teiln.</center></td><td style="font-size:13px"><center>'._GAME.'</center></td><td style="font-size:13px"><center>'._GAMETOWN.'</center></td><td style="font-size:13px"><center>'._GAMEPLACE.'</center></td><td style="font-size:13px"><center>'._GAMEDATE.'</center></td></tr>';
	
		if(mysql_num_rows($res)) {
			while($obj = mysql_fetch_object($res)) {
				$out .= '<tr><td style="font-size:13px">'.$obj->cup.'</td><td style="font-size:13px"><center>'.$obj->players.'</center></td><td style="font-size:13px">'.$obj->game.'</td><td style="font-size:13px">'.$obj->town.'</td><td style="font-size:13px">'.$obj->place.'</td><td style="font-size:13px">'.implode("-", array_reverse(explode("-", $obj->dt))).'</td></tr>';
			}
			$out .= '</table>';
			return $out;
		} else {
			$this->err = _NOQUERYRESULTS;
			return false;
		}
	}
	
	function killMe() {
		$sql = "DELETE FROM "._DB_PREFIX."_results WHERE game_id = ".$this->id;
		$res = mysql_query($sql);
			
		$this->makeSQL('DELETE');
		$res = mysql_query($this->sql);
	}
		

	
	
	
	function searchCup($genre) {
		global $genre_select;
		$out .= _Panelwyswietlaniaklasyfikacjipucharowych.' <br /><br />';
		$out .= '<form name="cup_search" method="post" action="'.$_SERVER['PHP_SELF'].'"><table>';
		$out .= '<tr><td>'._Nazwacyklu.': </td><td>'.$this->getListFromDB('id', 'cup', ''._DB_PREFIX.'_cups', 'cup').'</td></tr>';
		$out .= '<tr><td>'._Klasyfikacja.': </td><td>'.$this->getListFromVar('genre', $genre_select,$genre).'</td></tr>';
		$out .= '<tr><td colspan="2"><input type="hidden" name="name" value="Turnieje"><input type="hidden" name="op" value="rank"><input type="submit" name="search" value="'._Wyslij.'"></td></tr></table></form>';
		return $out;
	}
	
	function searchCupNoTable($genre) {
		global $genre_select;
		$out .= '<form name="cup_search" method="post" action="index.php">';
		$out .= '<br> '.$this->getListFromDB('id', 'cup_id', ''._DB_PREFIX.'_cups', 'cup');
		
		$out .= '<br><input type="hidden" name="name" value="Turnieje">' .
				
				'<input type="hidden" name="game" value="">' .
				'<input type="hidden" name="town" value="">' .
				'<input type="hidden" name="place" value="">' .
				'<input type="hidden" name="dt" value="">' .
				
				'<input type="hidden" name="op" value="showgames">' .
				'<input type="submit" value="'._Wyslij.'">' .
				'</form>';
		return $out;
	}

}
?>
