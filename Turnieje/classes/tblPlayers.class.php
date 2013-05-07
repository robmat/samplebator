<?php
class TblPlayers extends Table {

	var $tbl = 'players';
	var $sex_select = array('1' => 'Männlich', '2' => 'Weiblich');
	var $groups_select = array('1' => 'Spieler', '2' => 'Kapitän', '3' => 'Turnierleiter', '4' => 'Rankingleiter');
	//var $groups_select = array('1' => 'Gracz', '3' => 'Prowadz±cy turniej', '4' => 'Prowadz±cy ranking');
	var $groups_select_short = array('1' => 'Spieler', '2' => 'Kapitän');
	//var $groups_select_short = array('1' => 'Gracz');

	function TblPlayers($name, $op, $id, $pcat = 1) {

		Table::Table($name, $op, $id);

		$this->cfg->player_number->name = "player_number";
		$this->cfg->player_number->public_name = _Numergracza;
		$this->cfg->player_number->type = 'text';
		$this->cfg->player_number->maxlength = 4;
		$this->cfg->player_number->not_empty = 1;
		$this->cfg->player_number->numeric = 1;

		$this->cfg->lname->name = "lname";
		$this->cfg->lname->public_name = _Nazwisko;
		$this->cfg->lname->type = 'text';
		$this->cfg->lname->edit = 1;
		$this->cfg->lname->maxlength = 16;
		$this->cfg->lname->not_empty = 1;

		$this->cfg->fname->name = "fname";
		$this->cfg->fname->public_name = _Imie;
		$this->cfg->fname->type = 'text';
		$this->cfg->fname->maxlength = 16;
		$this->cfg->fname->not_empty = 1;

		$this->cfg->nick->name = "nick";
		$this->cfg->nick->public_name = _Nick;
		$this->cfg->nick->type = 'text';
		$this->cfg->nick->maxlength = 16;

		$this->cfg->city->name = "city";
		$this->cfg->city->public_name = _Miejscowosc;
		$this->cfg->city->type = 'text';
		$this->cfg->city->maxlength = 32;
		$this->cfg->city->no_list = 1;

		$this->cfg->sex->name = "sex";
		$this->cfg->sex->public_name = _Plec;
		$this->cfg->sex->radio = 'sex_select';
		$this->cfg->sex->not_empty = 1;
		$this->cfg->sex->no_list = 1;

		$this->cfg->cat->name = "cat";
		$this->cfg->cat->public_name = _Grupa;
		if($pcat == 4) $this->cfg->cat->list_me = 'groups_select';
		if($pcat == 3) $this->cfg->cat->list_me = 'groups_select_short';
		$this->cfg->cat->not_empty = 1;
		$this->cfg->cat->no_list = 1;
		$this->cfg->cat->level = 4;

		$this->cfg->team_id->name = "team_id";
		$this->cfg->team_id->public_name = _Druzyna;
		$this->cfg->team_id->db_list = 'nuke_teams';
		$this->cfg->team_id->db_list_pk = "id";
		$this->cfg->team_id->db_list_sel = "team";
		$this->cfg->team_id->db_list_cond = 'cup_id = '.$_COOKIE['CUP_ID'];
		$this->cfg->team_id->no_list = 1;

		$this->cfg->pass->name = "pass";
		$this->cfg->pass->public_name = _Haslo;
		$this->cfg->pass->type = 'password';
		$this->cfg->pass->maxlength = 16;
		$this->cfg->pass->no_list = 1;
		$this->cfg->cat->level = 4;

		$this->cfg->cup_id->name = "cup_id";
		$this->cfg->cup_id->public_name = _CUPNAME;
		$this->cfg->cup_id->db_list = 'nuke_cups';
		$this->cfg->cup_id->db_list_pk = "id";
		$this->cfg->cup_id->db_list_sel = "cup";
		$this->cfg->cup_id->db_list_cond = " close = 0";
		$this->cfg->cup_id->not_empty = 1;
	}

	function checkPass() {
		$sql = "SELECT id, player_number, pass, cat, cup_id FROM nuke_players ";
		$sql.= " WHERE player_number = '".$_POST['login']."'";
		$sql.= " and pass = '".$_POST['pass']."'";
		$sql.= " order by cup_id desc ";
		$res = mysql_query($sql);
		$obj = mysql_fetch_object($res);
		$cv = $obj->id;
		$cv .= '^'.$obj->cat;
		if($obj->pass == $_POST['pass']) {
			setcookie("player", $cv, time()+3600);
			setcookie("CUP_ID", $obj->cup_id, time()+3600);
			$_COOKIE['CUP_ID'] = $obj->cup_id;

			return ($obj->cat);
		}  else return false;
	}

	function getPlayerNumber() {
		$sql = "SELECT MAX(player_number) AS pn FROM nuke_players";
		$res = mysql_query($sql);
		$obj = mysql_fetch_object($res);
		return ($obj->pn);
	}

	function getPlayerNumber2() {
		$sql = "SELECT MAX(player_number) AS pn ";
		$sql.= " FROM nuke_players where player_number >= 5000 and player_number < 7999";
		$res = mysql_query($sql);
		$obj = mysql_fetch_object($res);
		return ($obj->pn);
	}

	function updateJsPlayers() {
		/*		$sql = "SELECT player_number, CONCAT(lname, ' ', fname) AS name, nick ";
		 $sql.= " FROM nuke_players WHERE player_number IS NOT NULL AND player_number != 0";
		 $res = mysql_query($sql);

		 $js = "<!--\n\r";
		 $js .= "ps = new Array();\n\r";
		 while($obj = mysql_fetch_object($res)) {
			if($obj->nick) $js .= "ps[".$obj->player_number."] = '".$obj->name." \"".$obj->nick."\"';\n\r";
			else $js .= "ps[".$obj->player_number."] = '".$obj->name."';\n\r";
			}
			$js .= "\n\r//-->";

			$file = 'modules/Turnieje/javas/javas.js';
			$handle = fopen($file, "w") or die(_Niemoznaotworzycpliku." ".$file);
			flock($handle, 2);
			fwrite($handle, $js);
			flock($handle, 3);
			fclose($handle);*/
	}

	function deleteplayer($id)
	{
		$sql = "DELETE FROM nuke_players WHERE id = ".$id." and player_number <> 5000";
		$res = mysql_query($sql);
	}
}
?>
