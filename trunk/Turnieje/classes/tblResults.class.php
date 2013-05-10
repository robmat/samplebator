<?php
class TblResults extends Table {

var $tbl = 'results';

	function TblResults($name, $op, $id) {
		
		Table::Table($name, $op, $id);
		
		$this->cfg->game_id->name = "game_id";
		$this->cfg->game_id->public_name = _GAME;
		$this->cfg->game_id->db_list = ''._DB_PREFIX.'_games';
		$this->cfg->game_id->db_list_pk = "id";
		$this->cfg->game_id->db_list_sel = "game";
		$this->cfg->game_id->not_empty = 1;
		
		$this->cfg->player_id->name = "pn";
		$this->cfg->player_id->public_name = _Numer_zawodnika;
		
		$this->cfg->position->name = "position";
		$this->cfg->position->public_name = _Miejsce;
		$this->cfg->position->type = 'text';
		$this->cfg->position->maxlength = 32;
		$this->cfg->position->not_empty = 1;
		
		$this->cfg->score->name = "score";
		$this->cfg->score->public_name = _Punkty;
		$this->cfg->score->type = 'text';
		$this->cfg->score->maxlength = 10;
		$this->cfg->score->not_empty = 1;
	}

	function addGameResults($ratio, $players) {

  	$sql = "SELECT game_mode,cup_id FROM "._DB_PREFIX."_games WHERE id = ".$this->id;
		$res = mysql_query($sql);
		$obj = mysql_fetch_object($res);
	

		if(empty($_POST['mode'])) {
			$mode = $obj->game_mode;
		} else {
			$mode = $_POST['mode'];
		}
		$out .= '<table><tr><td>'._Wspolczynnik.':</td><td>'.$ratio.'</td><td></td></tr><tr><td>'._Iloscgraczy.':</td><td>'.$players.'</td><td></td></tr><tr><td colspan="2" height="12"></td></tr></table>';
//form dodaj wyniki

		$out .= "<script type=\"text/javascript\">
				function displayWindow(url, width, height) {
            var Win = window.open(url,\"displayWindow\",'width=' + width + ',height=' + height + ',resizable=0,scrollbars=yes,menubar=no' );
        }
        ps = new Array();
        ";
	
  
      	  $sql = "select id,fname,lname,player_number FROM "._DB_PREFIX."_players ";
          $sql.= " where cup_id=".$obj->cup_id;
    		  $result = mysql_query($sql);
  
      while($row = mysql_fetch_object($result)){
         $out .= " ps[".$row->player_number."] = '".$row->fname." ".$row->lname."';  
               ";
      }  
  
  	$out .= "		
				function findMe(me) {
				nr = parseInt(me.value);
				obiekt = '10' +me.id;
				document.getElementById(obiekt + '_player').value = ps[nr];
				}
		</SCRIPT>";
		

		$out .= '<form name="game_results" method="post" action="'.$_SERVER['PHP_SELF'].'">';
			$out .= '<table><tr><td>'._Miejsce.'</td><td>'._Nrgracza.'</td><td>'._Dane.'</td><td></td></tr><tr><td colspan="4" height="6"></td></tr>';
			
			if($mode == 1) {
				for($i=1; $i<=$players; $i++) {
					$out .= '<tr><td>'.$i.'</td>' .
							'<td><input type="text" name="p_'.$i.'" id="'.$i.'" maxlength="4" size="4" '.
              'onBlur="javascript:findMe(this)" value="'.$_POST[$i].'">'.
              '<a href=javascript:displayWindow(\'gracz2.php?pole='.$i.'&cup_id='.$obj->cup_id.'&form=game_results\',\'200\',\'300\');>'._Wybierz.'</a></td>' .
							'<td><input type="text" name="player_10'.$i.'" size="40" id="10'.$i.'_player" disabled></td>' .
							'<td>'.$this->err->{$i}.'</td>' .
							'</tr>';
				}
			}
			
			if($mode == 2 || $mode == 3) {
				$nr = 1;
				for($i=1; $i<=$players; $i++) {
					$i%$mode == 1 ? $out .= '<tr><td>'.$nr++.'</td>' : $out .= '<tr><td>&nbsp;</td>';
					$out .= '<td><input type="text" name="p_'.$i.'" id="'.$i.'" maxlength="4" size="4" ';
          $out .= 'onBlur="javascript:findMe(this)" value="'.$_POST[$i].'">';
          $out .= '<a href=javascript:displayWindow(\'gracz2.php?pole='.$i.'&cup_id='.$obj->cup_id;
          $out .= '&form=game_results\',\'200\',\'300\');>'._Wybierz.'</a></td>';
          $out .= '</td>';
          $out .= '<td><input type="text" name="player_10'.$i.'" size="40" id="10'.$i.'_player" disabled></td>';
//<input type="text" size="40" id="'.$i.'_player" disabled>
          $out .= '<td>'.$this->err->{$i}.'</td></tr>';
				}
			}
			
		$out .= '<tr><td colspan="4"><input type="hidden" name="mode" value="'.$mode.'"><input type="hidden" name="ratio" value="'.$ratio.'"><input type="hidden" name="players" value="'.$players.'"><input type="hidden" name="name" value="'.$this->name.'"><input type="hidden" name="op" value="'.$this->op.'"><input type="hidden" name="id" value="'.$this->id.'"><input type="submit" name="submit" value="'._Wyslij.'"></td></tr>';
		$out .= '</table></form>';
		
		return $out;
	}
	
	function editGameResults($pid, $pcat, $err=false) {
		$sql = 'SELECT game, chief_pn, game_status, ratio, game_mode, cup_id ';
    $sql.= ' FROM '._DB_PREFIX.'_games WHERE id = '.$this->id;
		$res = mysql_query($sql);
		$obj = mysql_fetch_object($res);

		if($obj->game_status == 'open') { $this->err = _Tenturniejniemawprowadzonychwynikow; return false;}
		if($obj->chief_pn != $pid && $pcat != 4) { $this->err = _Niemaszprawdoedytowaniawynikowtegoturnieju; return false; }
		
		$ratio = $obj->ratio;
		$mode = $obj->game_mode;
		if(preg_match("/[1-9]{1}/", $ratio)) $ratio .= '.0';
		$game = $obj->game;
		
		$cup_id = $obj->cup_id;
		
		$sql = "SELECT "._DB_PREFIX."_results.id, pn, position, score, CONCAT(lname, ' ', fname) AS name ";
    $sql.= " FROM "._DB_PREFIX."_results ";
    $sql.= " LEFT JOIN "._DB_PREFIX."_players ON player_number = pn and "._DB_PREFIX."_players.cup_id =".$cup_id;
    $sql.= " WHERE game_id = '".$this->id."' ORDER BY position";
		$res = mysql_query($sql);
		$players = mysql_num_rows($res);
		
		$out .= $game;
		$out .= "<br /><br />";
		
		$out .= "<script type=\"text/javascript\">
            function checked_all()
						{
            for (i = 0; i < window.document.game_results.elements.length; i++) 
               {
              	if ( window.document.game_results.elements[i].type == 'checkbox' )
				          window.document.game_results.elements[i].checked = true;
                }
            }
						
						function unchecked_all()
						{
            for (i = 0; i < window.document.game_results.elements.length; i++) 
               {
              	if ( window.document.game_results.elements[i].type == 'checkbox' )
				          window.document.game_results.elements[i].checked = false;
                }
            }
            function displayWindow(url, width, height) {
               var Win = window.open(url,\"displayWindow\",'width=' + width + ',height=' + height + ',resizable=0,scrollbars=yes,menubar=no' );
            }
						</SCRIPT>";
		
		$out .= '<form name="game_ratio" method="post" action="'.$_SERVER['PHP_SELF'].'"><table><tr><td>'._Wspolczynnikprzeliczeniowygry.':</td><td><input type="text" size="3" maxlength="3" name="ratio" value="'.$ratio.'"><input type="hidden" name="name" value="'.$this->name.'"><input type="hidden" name="op" value="checkeditedratio"><input type="hidden" name="id" value="'.$this->id.'"></td><td><input type="submit" name="change_factor" value="'._Zmien.'"></td></tr><tr><td colspan="3" height="6"></td></tr></table></form>';
		
		if($this->err->rato) $out .= $this->err->rato."<br /><br />";
			
			$out .=_UWAGAAbyzmieniclubusunacdanewybranychzawodnikowmusiszzaznaczycodpowiadajacymucheckbox;
			$out .="<form method=post>" .
					_dodajgraczywpiszilosc." <input type=text name=dodajGr >" .
					"<input type=submit value="._Dodaj.">" .
					"</form>";
			$out .= '<form name="game_results" id=game_results method="post" action="'.$_SERVER['PHP_SELF'].'"><table border="0">';
			$out .= '<tr><td colspan="4" height="12"></td></tr><tr><td></td><td>'._Gracz.'</td><td>'._Punkty.'</td><td>'._Miejsce.'</td></tr><td colspan="4" height="8"></td></tr>';
			
			if($err) $out .= '<br /><br />'._FormularzzostalbledniewypelnionyZmianyniezostalyzachowane;

			if($_POST[dodajGr]!=''){ $przeliczeniePunktacji='checked="checked"';
				$out .=  "<br>"._DodajaczawodnikawszyscypozostaligraczemuszabyczaznaczeniabysystemponownieprzeliczylpunktacjeWpiszzajeteprzezgraczamiejsce."<br>";}
		

			while($obj = mysql_fetch_object($res)) { 
				$out .= '<tr><td><input type="checkbox" '.$przeliczeniePunktacji.' id="'.$obj->id.'" name="'.$obj->id.'" ></td>' .
						//'<td><input type="text" name="pn_'.$obj->id.'" size="4" maxlength="4" value="'.$obj->pn.'"> '.$obj->name.'  <a href=javascript:displayWindow(\'gracz.php?pole=pn_'.$obj->id.'&form=game_results\',\'200\',\'300\');>'._zmien.'</a></td><td>'.$obj->score*$ratio.'</td><td><input type="text" name="'.$obj->id.'_position" size="3" maxlength="3" value="'.$obj->position.'"> ';
						//zmiana
'<td><input type="text" name="pn_'.$obj->id.'" size="4" maxlength="4" value="'.$obj->pn.'"> '.
'<input type="text" name="name_'.$obj->id.'" value="'.$obj->name.'"> '.
' <a href=javascript:displayWindow(\'gracz.php?pole='.$obj->id.'&cup_id='.$cup_id.'&form=game_results\',\'200\',\'300\');>'._zmien.'</a></td><td>'.$obj->score*$ratio.'</td><td><input type="text" name="'.$obj->id.'_position" size="3" maxlength="3" value="'.$obj->position.'"> ';

			if($this->err->{$obj->id}) $out .= $this->err->{$obj->id};
				$out .= '</td></tr>';
			}
			if($_POST[dodajGr]!=''){
				$players = $players + $_POST[dodajGr];			
				while ($_POST[dodajGr]>0) { 
					
				//$out .= '<tr><td><input type="checkbox" name="new_'.$_POST[dodajGr].'" value='.$_POST[dodajGr].' '.$przeliczeniePunktacji.'></td>' .
				//zmiana
$out .= '<tr><td><input type="checkbox" name="new_'.$_POST[dodajGr].'" value='.$_POST[dodajGr].' '.$przeliczeniePunktacji.'></td>' .

						//'<td><input type="text" name="pn_'.$_POST[dodajGr].'" size="4" maxlength="4" value=""> '.$obj->name.'  <a href=javascript:displayWindow(\'gracz.php?form=game_results&pole=pn_'.$_POST[dodajGr].'\',\'200\',\'300\');>'._wybierz.'</a></td>' .
						//zmiana
'<td><input type="text" name="pn_'.$_POST[dodajGr].'" size="4" maxlength="4" value="">'.
' <input type="text" name="name_'.$_POST[dodajGr].'" value="'.$obj->name.'">  '.
'<a href=javascript:displayWindow(\'gracz.php?form=game_results&cup_id='.$cup_id.'&pole='.$_POST[dodajGr].'\',\'200\',\'300\')'.
';>'._wybierz.'</a></td>' .

						'<td>'.$obj->score*$ratio.'</td>' .
						'<td><input type="text" name="position['.$_POST[dodajGr].']" size="3" maxlength="3" value="'.$obj->position.'"> ';
				$out .= '</td></tr>';
				
				$_POST[dodajGr]--;

				}
				
			}
			$out .="<input type=hidden name='gameiD' VALUE=".$_GET[id].">";
			$out .= '<tr><td colspan="4"><input type="hidden" name="mode" value="'.$mode.'">' .
					'<input type="hidden" name="ratio" value="'.$ratio.'">' .
					'<input type="hidden" name="players" value="'.$players.'">' .
					'<input type="hidden" name="name" value="'.$this->name.'">' .
					'<input type="hidden" name="op" value="'.$this->op.'">' .
					'<input type="hidden" name="id" value="'.$this->id.'">' .
					'<input type="submit" name="submit" value="'._Usun.'">' .
					'<input type="submit" name="submit" value="'._Zmien.'">' .
					'&nbsp;&nbsp;'.
					'<input type="button" name="button" value="'._Zaznaczwszystkie.'" onclick="javascript:checked_all();">' .
					'<input type="button" name="button" value="'._Odznaczwszystkie.'" onclick="javascript:unchecked_all();">' .
					'</td></tr>';
			$out .= '</table></form>';

	return $out;

	}
	
	function checkEditedPlaces() {
		$res = true;
		$mode = $_POST['mode'];
		foreach($_POST as $key => $value) {
			if(is_numeric($key)) {
				if(!is_numeric($_POST[$key.'_position'])) { $this->err->{$key} = _Miejscemusibycliczba; $res = false; }
				if(empty($_POST[$key.'_position'])) { $this->err->{$key} = _Miejsceniemozebycrownezero; $res = false; }
			}
		}
		return $res;
	}
	
	function showGameResults() {
	global $printMe;
		$sql = 'SELECT game, ratio, dt, cup_id FROM '._DB_PREFIX.'_games WHERE id = '.$this->id;
		
		$res = mysql_query($sql);
		$obj = mysql_fetch_object($res);
		$ratio = $obj->ratio;
		$out = _Turniej.' '.$obj->game.'<br />';
		$out .= _Data.' '.implode("-", array_reverse(explode("-", $obj->dt))).'<br />';
		$out .= _Wspolczynnikturnieju.' '.$obj->ratio.'<br /><br />';
		if(!$printMe) 
      $out .= '<a href="index.php?name=Turnieje&op=print&id='.$this->id.'" >'._Wersjadodruku.'</a><br /><br />';
		
    $sql = "SELECT game_id, pn, position, score, CONCAT(lname, ' ', fname) AS name, nick ";
    $sql.= " FROM "._DB_PREFIX."_results LEFT JOIN "._DB_PREFIX."_players ";
    $sql.= " ON "._DB_PREFIX."_players.player_number = pn and "._DB_PREFIX."_players.cup_id =".$obj->cup_id;
    $sql.= " WHERE game_id = '".$this->id."' ORDER BY position";
		
    $res = mysql_query($sql);
		
		$out .= '<table><tr><td>'._Miejsce.'</td><td>'._Gracz.'</td><td>'._Punkty.'</td></tr>';
		
		$score = 0;
		while($obj = mysql_fetch_object($res)) {
			$out .= '<tr><td>';
			$score == $obj->score ? $out .= '' : $out .= $obj->position;
			$out .= '</td><td>'.$obj->name; 
			if($obj->nick) $out .= ' "'.$obj->nick.'"';
			$out .= '</td><td align="right">'.number_format(round($obj->score*$ratio, 2), 2, ',', ' ').'</td></tr>';
			$score = $obj->score;
		}
		$out .= '</table>';
		
		$out .= '<a href="index.php?name=Turnieje">'._Wyniki.'</a>';
	return $out;
	}
	
		function showGameResults2() {
	global $printMe;
		$sql = 'SELECT game, ratio, dt, cup_id FROM '._DB_PREFIX.'_games WHERE id = '.$this->id;
		$res = mysql_query($sql);
		$obj = mysql_fetch_object($res);
		$ratio = $obj->ratio;
		$out = _Turniej.' '.$obj->game.'<br />';
		$out .= _Data.' '.implode("-", array_reverse(explode("-", $obj->dt))).'<br />';
		$out .= _Wspolczynnikturnieju.' '.$obj->ratio.'<br /><br />';
		if(!$printMe) $out .= '<a href="index.php?name=Turnieje&op=print&id='.$this->id.'" >'._Wersjadodruku.'</a><br /><br />';
		$sql = "SELECT game_id, pn, position, score, CONCAT(lname, ' ', fname) AS name, nick ";
    $sql.= " FROM "._DB_PREFIX."_results LEFT JOIN "._DB_PREFIX."_players ";
    $sql.= " ON "._DB_PREFIX."_players.player_number = pn and "._DB_PREFIX."_players.cup_id =".$obj->cup_id;
    $sql.= " WHERE game_id = '".$this->id."' ORDER BY position";
		$res = mysql_query($sql);
		
		$out .= '<table border="0"><tr><td>'._Miejsce.'</td><td>'._Gracz.'</td><td>'._Punkty.'</td></tr>';
		
		$score = 0;
		while($obj = mysql_fetch_object($res)) {
			$out .= '<tr><td>';
			$score == $obj->score ? $out .= '' : $out .= $obj->position;
			$out .= '</td><td>'.$obj->name; 
			if($obj->nick) $out .= ' "'.$obj->nick.'"';
			$out .= '</td><td align="right">'.number_format(round($obj->score*$ratio, 2), 2, ',', ' ').'</td></tr>';
			$score = $obj->score;
		}
		$out .= '</table>';
		
		$out .= '<a href="index.php?name=Turnieje">'._Wyniki.'</a>';
	return $out;
	}

	
	function checkGameResults() {
		$flag = true;
		$numbers = array();
		foreach($_POST as $key => $value) {
			if(is_numeric($key)) {
				if(!is_numeric($value)) { $this->err->{$key} = _Nieprawidlowynumer; $flag = false; }
				if(strlen($value) < 4) { $this->err->{$key} = _Nieprawidlowynumer; $flag = false; }
				if(in_array($value, $numbers)) { $this->err->{$key} = _Tengraczjestjuzwpisany; $flag = false; }
				if(empty($value)) { $this->err->{$key} = _Wprowadznumergracza; $flag = false; }
				$numbers[] = $value;
			}
		}
		return $flag;
	}

	function saveGameResults() {
	$players = $_POST['players'];
	$mode = $_POST['mode'];
		if($mode == 1) {
			
			foreach($_POST as $key => $value) {
				$key=ereg_replace('p_','',$key);
				if(is_numeric($key)) {
					$score = $this->computeResults($players, $key);
					$sql = "INSERT INTO "._DB_PREFIX."_results (game_id, pn, position, score) VALUES ('".$this->id."', '".$value."', '".$key."', '".$score."')";
					
					$res = mysql_query($sql);
				}
			}
		}

		if($mode == 2 || $mode == 3) {
			foreach($_POST as $key => $value) {
			  $key=ereg_replace('p_','',$key);
				if(is_numeric($key)) {
					if($key%$mode == 1) {
						$place = ($key+$mode-1)/$mode;
						$score = $this->computeResults($players/$mode, $place);
					}

					$sql = "INSERT INTO "._DB_PREFIX."_results (game_id, pn, position, score) VALUES ('".$this->id."', '".$value."', '".$place."', '".$score."')";	
					$res = mysql_query($sql);
				}
			}
		}	
	}
	
	function computeResults($players, $position) {
		if($position >=9 && $position <= 12) $position = 9;
		if($position >=13 && $position <= 16) $position = 13;
		if($position >=17 && $position <= 24) $position = 17;
		if($position >=25 && $position <= 32) $position = 25;
		if($position >=33 && $position <= 48) $position = 33;
		if($position >=49 && $position <= 64) $position = 49;
		if($position >=65 && $position <= 96) $position = 65;
		if($position >=97 && $position <= 128) $position = 97;
		
		$score = 0;		
		$score = 2*(51-(50*$position/$players));
		$score = round($score, 2);
		if($score < 5) $score = 5.00;
		return $score;
	}
		
	function updateGameResults() {
		$players = $_POST['players'];
		$mode = $_POST['mode'];
		if($_POST[submit]==_Usun){
			
			foreach($_POST as $key => $value) {
				$key=ereg_replace('p_','',$key);
				if(is_numeric($key) && $value == 'on') {
					
					$sql = "DELETE FROM "._DB_PREFIX."_results WHERE id = ".$key." LIMIT 1";
					$res = mysql_query($sql);
				
				
				}
				
			}
			
		}else{
		foreach($_POST as $key => $value) {
			$key=ereg_replace('p_','',$key);
			if(is_numeric($key) && $value == 'on') {
				
				$pn=$_POST['pn_'.$key];
				$position = $_POST[$key.'_position'];
				$score = $this->computeResults($players/$mode, $position);
				
 			 $sql = "UPDATE "._DB_PREFIX."_results SET pn='$pn', position = '".$position."', score = '".$score."' WHERE id = ".$key;
				$res = mysql_query($sql);
			
			}elseif(ereg('new_',$key)){
				
				
				$pos=$_POST[position][$value];
				$pn=$_POST['pn_'.$value];
				$score = $this->computeResults($players/$mode, $pos);
				
	  		$sql = "INSERT INTO "._DB_PREFIX."_results (`game_id`,  `pn`,  `position`,  `score`) " .
						"VALUES " .
						"('$_POST[gameiD]',  '$pn',  '$pos' , '$score') ";
				
				$res = mysql_query($sql);
			}
		}
		}

//	header("Location: ../dart24/index.php?name=Turnieje&op=editresult&id=".$this->id."");
	}
	
	function showRanks($id, $genre) {
	global $printMe;
		$sql = 'SELECT cup FROM '._DB_PREFIX.'_cups WHERE id = '.$id;
		
		$res = mysql_query($sql);
		$obj = mysql_fetch_object($res);
		
		$out = _Puchar.' '.$obj->cup.' <br /><br />';
		$out .= _Data.' '.date("d-m-Y").' <br /><br />';
	
		$sql = 'SELECT COUNT(*) AS suma FROM '._DB_PREFIX.'_games WHERE cup_id = '.$id;
		$res = mysql_query($sql);
		$obj = mysql_fetch_object($res);

		$out .= _Liczbarozegranychturniejow.': '.$obj->suma.' <br /><br />';
		
		if(!$printMe) $out .= '<a href="index.php?name=Turnieje&op=print&id='.$id.'&genre='.$genre.'">'._Wersjadodruku.'</a><br /><br />';

		if($genre == 3)
      { 
       $sql = "SELECT SUM(score*ratio) AS score, team, "._DB_PREFIX."_games.cup_id ";
       $sql.= " FROM "._DB_PREFIX."_results ";
       $sql.= " LEFT JOIN "._DB_PREFIX."_players ON "._DB_PREFIX."_players.player_number = pn  and "._DB_PREFIX."_players.cup_id = '".$id."'";
       $sql.= " LEFT JOIN "._DB_PREFIX."_teams ON team_id = "._DB_PREFIX."_teams.id ";
       $sql.= " LEFT JOIN "._DB_PREFIX."_games ON game_id = "._DB_PREFIX."_games.id ";
       $sql.= " WHERE "._DB_PREFIX."_games.cup_id='".$id."' GROUP BY team ORDER BY score DESC";
      }
		else
      { 
      $sql = "SELECT pn, COUNT(pn) AS games, SUM(score*ratio) AS score, ";
      $sql.= " CONCAT(lname, ' ', fname) AS name, nick, player_number, city,team, "._DB_PREFIX."_games.cup_id ";
      $sql.= " FROM "._DB_PREFIX."_results ";
      $sql.= " LEFT JOIN "._DB_PREFIX."_players ON "._DB_PREFIX."_players.player_number = pn and "._DB_PREFIX."_players.cup_id = '".$id."'";
      $sql.= " LEFT JOIN "._DB_PREFIX."_teams ON team_id = "._DB_PREFIX."_teams.id ";
      $sql.= " LEFT JOIN "._DB_PREFIX."_games ON game_id = "._DB_PREFIX."_games.id ";
      $sql.= " WHERE "._DB_PREFIX."_players.sex='".$genre."' AND "._DB_PREFIX."_games.cup_id='".$id."' ";
      $sql.= " GROUP BY pn ORDER BY score DESC";
		  }
		$res = mysql_query($sql);
		if($genre == 3) $out = '<table><tr><td>'._Miejsce.'</td><td>'._Druzyna.'</td><td>'._Punkty.'</td></tr>';
		else $tbl = '<table><tr><td>'._Miejsce.'</td><td>'._NazwiskoImiêksywa.'</td><td>'._Nrzawodnika.'</td><td>'._Druzyna.'</td><td>'._Miasto.'</td><td>'._Punkty.'</td><td>'._Iloscturniejow.'</td></tr>';
		$i = 1;
		$shares = 0;
		$points = 0;
		while($obj = mysql_fetch_object($res)) {
			if($genre == 3) {
				if(!empty($obj->team)) {
					$out .= '<tr><td>'.$i.'</td><td>'.$obj->team.'</td><td>'.$obj->score.'</td></tr>';
					$i++;
				}
			} else {
				$tbl .= '<tr><td>';
				$points == $obj->score ? $tbl .= '' : $tbl .= $i;
				$tbl .= '</td><td><a href="index.php?name=Turnieje&op=playergames&id='.$obj->pn.'&cupid='.$id.'">'.$obj->name.' '; 
				if($obj->nick) $tbl .= ' &#132;'.$obj->nick.'&#148;';
				$tbl .= '</a></td><td>'.$obj->player_number.'</td><td>'.$obj->team.'</td><td>'.$obj->city.'</td><td align="right">'.number_format(round($obj->score, 2), 2, ',', ' ').'</td><td align="right">'.$obj->games.'</td></tr>';
				$shares += $obj->games;
				$points = $obj->score;
				$i++;
			}
		}
		
		$tbl .= '</table>';
		
		if($genre == 1 || $genre == 2) {
			$out .= _Liczbaudzialow.': '.$shares.' <br /><br />';
			$out .= $tbl;
		}
		
		return $out;
	}
	
	function showRanks2($id, $genre) {
	global $printMe;
		$sql = 'SELECT cup FROM '._DB_PREFIX.'_cups WHERE id = '.$id;
		
		$res = mysql_query($sql);
		$obj = mysql_fetch_object($res);
		
		$out = _Puchar.' '.$obj->cup.' <br /><br />';
		$out .= _Data.' '.date("d-m-Y").' <br /><br />';
	
		$sql = 'SELECT COUNT(*) AS suma FROM '._DB_PREFIX.'_games WHERE cup_id = '.$id;
		$res = mysql_query($sql);
		$obj = mysql_fetch_object($res);

		$out .= _Liczbarozegranychturniejow.': '.$obj->suma.' <br /><br />';
		
		if(!$printMe) $out .= '<a href="index.php?name=Turnieje&op=print&id='.$id.'&genre='.$genre.'">'._Wersjadodruku.'</a><br /><br />';

		if($genre == 3) 
     {
     $sql = "SELECT SUM(score*ratio) AS score, team, "._DB_PREFIX."_games.cup_id ";
     $sql.= " FROM "._DB_PREFIX."_results ";
     $sql.= " LEFT JOIN "._DB_PREFIX."_players ON "._DB_PREFIX."_players.player_number = pn and "._DB_PREFIX."_players.cup_id = '".$id."'";
     $sql.= " LEFT JOIN "._DB_PREFIX."_teams ON team_id = "._DB_PREFIX."_teams.id ";
     $sql.= " LEFT JOIN "._DB_PREFIX."_games ON game_id = "._DB_PREFIX."_games.id ";
     $sql.= " WHERE "._DB_PREFIX."_games.cup_id='".$id."' ";
     $sql.= " GROUP BY team ORDER BY score DESC";
		 }
    else 
     {
     $sql = "SELECT pn, COUNT(pn) AS games, SUM(score*ratio) AS score, ";
     $sql.= " CONCAT(lname, ' ', fname) AS name, nick, player_number, city, team, "._DB_PREFIX."_games.cup_id ";
     $sql.= " FROM "._DB_PREFIX."_results ";
     $sql.= " LEFT JOIN "._DB_PREFIX."_players ON "._DB_PREFIX."_players.player_number = pn and "._DB_PREFIX."_players.cup_id = '".$id."'";
     $sql.= " LEFT JOIN "._DB_PREFIX."_teams ON team_id = "._DB_PREFIX."_teams.id ";
     $sql.= " LEFT JOIN "._DB_PREFIX."_games ON game_id = "._DB_PREFIX."_games.id ";
     $sql.= " WHERE "._DB_PREFIX."_players.sex='".$genre."' AND "._DB_PREFIX."_games.cup_id='".$id."' ";
     $sql.= " GROUP BY pn ORDER BY score DESC";
		 }
		
    $res = mysql_query($sql);
		if($genre == 3) $out = '<table border="0"><tr><td>'._Miejsce.'</td><td>'._Druzyna.'</td><td>'._Punkty.'</td></tr>';
		else $tbl = '<table border="0" rules="all"><tr><td><center>Platz</center></td><td><center>Nachname, Vorname, (Nick)</center></td><td><center>Spielernr</center></td><td><center>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;'._Druzyna.'&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</center></td><td><center>'._Miasto.'</center></td><td><center>&nbsp;&nbsp;&nbsp;'._Punkty.'&nbsp;&nbsp;&nbsp;&nbsp;</center></td><td><center>Turniere</center></td></tr>';
		$i = 1;
		$shares = 0;
		$points = 0;
		while($obj = mysql_fetch_object($res)) {
			if($genre == 3) {
				if(!empty($obj->team)) {
					$out .= '<tr><td style="font-size:13px"><center>'.$i.'</center></td><td style="font-size:13px"><center>'.$obj->team.'</center></td><td style="font-size:13px"><center>'.$obj->score.'</center></td></tr>';
					$i++;
				}
			} else {
				$tbl .= '<tr><td><center>';
				$points == $obj->score ? $tbl .= '' : $tbl .= $i;
				$tbl .= '</center></td><td style="font-size:13px">'.$obj->name.' '; 
				if($obj->nick) $tbl .= ' &#132;'.$obj->nick.'&#148;';
				$tbl .= '</td><td style="font-size:13px"><center>'.$obj->player_number.'</center></td><td style="font-size:12px">'.$obj->team.'</td><td style="font-size:13px">'.$obj->city.'</td><td style="font-size:13px" align="right">'.number_format(round($obj->score, 2), 2, ',', ' ').'</td><td style="font-size:13px"><center>'.$obj->games.'</center></td></tr>';
				$shares += $obj->games;
				$points = $obj->score;
				$i++;
			}
		}
		
		$tbl .= '</table>';
		
		if($genre == 1 || $genre == 2) {
			$out .= _Liczbaudzialow.': '.$shares.' <br /><br />';
			$out .= $tbl;
		}
		
		return $out;
	}
	
	function tblPlayerResult($id, $cupid) {
		$sql = "SELECT CONCAT(lname, ' ', fname) AS name, nick FROM "._DB_PREFIX."_players ";
    $sql.= " WHERE player_number = ".$id." and cup_id = '".$cupid."'";
		$res = mysql_query($sql);
		$obj = mysql_fetch_object($res);
		$out = _Zawodnik.': '.$obj->name.' '.$obj->nick.'<br /><br />';
	
		$sql = 'SELECT game_id, COUNT(game_id) AS players FROM '._DB_PREFIX.'_results GROUP BY game_id';
		$res = mysql_query($sql);
		
		$players = array();
		while($obj = mysql_fetch_object($res)) {
			$players[$obj->game_id] = $obj->players;
		}
		
		$sql = "SELECT position, (score*ratio) AS score, game_id, game, town, place, ratio, dt, "._DB_PREFIX."_games.cup_id ";
    $sql.= " FROM "._DB_PREFIX."_results LEFT JOIN "._DB_PREFIX."_games ON game_id = "._DB_PREFIX."_games.id ";
    $sql.= " WHERE pn = '".$id."' AND "._DB_PREFIX."_games.cup_id = '".$cupid."' ORDER BY dt DESC";
		$res = mysql_query($sql);
		
		$i = 1;
		$total = 0;
		$out .= '<table><tr><td>Nr</td><td>'._Turniej.'</td><td>'._Data.'</td><td>'._Miasto.'</td><td>'._Miejsce.'</td><td>'._Iloscgraczy.'</td><td>'._Wspolczynnik.'</td><td>'._Punkty.'</td></tr>';
		while($obj = mysql_fetch_object($res)) {
			$out .= '<tr><td>'.$i.'</td><td><a href="index.php?name=Turnieje&op=showgame&id='.$obj->game_id.'">'.$obj->game.'</a></td><td>'.implode("-", array_reverse(explode("-", $obj->dt))).'</td><td>'.$obj->town.'</td><td>'.$obj->position.'</td><td>'.$players[$obj->game_id].'</td><td>'.$obj->ratio.'</td><td>'.$obj->score.'</td></tr>';
			$i++;
			$total += $obj->score;
		}
		
		$out .= '<tr><td colspan="7"></td><td>'._Suma.': '.$total.'</td></tr></table>';
		
		return $out;
	}

}
?>
