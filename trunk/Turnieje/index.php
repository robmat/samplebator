<?php
/*COMMERCIAL MODULE DARTS_LEAGUE FOR PHPNUKE COPRIGHT: DOGMAT Sp. z o.o. 
 * http://www.dogmat.eu office@dogmat.eu
 * All Rights Reserved
 

if (!stristr($_SERVER['SCRIPT_NAME'], "modules.php")) {
    die ("You can't access this file directly...");
}
*/
error_reporting(E_ERROR | E_PARSE);

include_once("db.php");
require_once("html.php");
//require_once("mainfile.php");
$module_name = basename(dirname(__FILE__));
//get_lang($module_name);
global $currentlang; //TODO get locale
$currentlang = "german";
include_once("language/lang-".$currentlang.".php");


include('classes/table.class.php');
isLogged();

/*
if($_SERVER['REMOTE_ADDR'] == '83.30.68.151') {
	echo "cat = ".$pcat;
	echo "nr = ".$pid;
}
*/
$genre_select = array('1' => _Mezczyzn, '2' => _Kobiet, '3' => _Druzyn);

function start() {
	global $genre_select;
	searchAll('showgames', $genre_select);
}

function admin() {
global $pcat;
	include('classes/tblPlayers.class.php');
	$tblPlayers = new TblPlayers('Turnieje', 'admin', '');
	include("header.php");
	OpenTable();
	if(!empty($pcat)) {
		$out = menu();
		echo $out;
	} else {
		if($_SERVER['REQUEST_METHOD'] == 'POST') {
			$pcat = $tblPlayers->checkPass();

			if($pcat) {
				$out = menu();
				echo $out;
			} else {
				$out = passForm();
				echo $out;
			}
		}  else {
			$out = passForm();
			echo $out;
		}
	}
	CloseTable();
	

	include("footer.php");
}

function passForm() {
	$out = '<form name="passthrough" method="post" action="'.$_SERVER['PHP_SELF'].'">' .
			'<table width="100%"><tr><td>Login:</td>' .
			'<td><input type="text" name="login" maxlength="16"></td>' .
			'</tr><tr><td>'._Haslo.':</td>' .
			'<td><input type="password" name="pass" maxlength="16"></td>' .
			'</tr><tr><td colspan="2">' .
			'<input type="hidden" name="name" value="Turnieje">' .
			'<input type="hidden" name="op" value="adm">' .
			'</td></tr>' .
			'<tr><td colspan="2"><input type="submit" name="submit" value="'._Wyslij.'"></td></tr></table></form>';
	return $out;
}

function logout() {
global $pid, $pcat;
	$pid = '';
	$pcat = '';
	setcookie("player");
	setcookie("CUP_ID");
	start();
}

function isLogged() {
global $pid, $pcat;
	if(!empty($_COOKIE['player'])) {
		$ex = explode("^", $_COOKIE['player']);
		$pid = $ex[0];
		$pcat = $ex[1];
	}

}

function menu() {
global $pcat;  
  if($pcat > 1)
  {
   	$sql = 'select cup from '._DB_PREFIX.'_cups where id='.$_COOKIE['CUP_ID'];
    $result = mysql_query($sql);
    $row = mysql_fetch_row($result);

 $out= '>>>>  <b>Selected Cup : '.$row[0].'</b>  <<<<<br />';
  }

	if($pcat == 4) $out.= '<table width="100%">'.
      '<tr>' .
			'<td><a href="index.php?op=cupmanager">'._Puchary.'</a></td>' .
					'<td><a href="index.php?op=addgame">'._Dodajturniej.'</a></td>' .
					'<td><a href="index.php?op=searchadd">'._DodajWyniki.'</a></td>' .
					'<td><a href="index.php?op=searchresults">'._Edytujturniej.'</a></td>' .
					'<td><a href="index.php?op=teammanager">'._Druzyny.'</a></td>' .
					'<td><a href="index.php?op=playermanager">'._Zawodnicy.'</a></td>' .
					'<td><a href="index.php?op=importplayers">'._WciagnijZawodnikow.'</a></td>' .
					'<td><a href="index.php?op=logout">'._Wyloguj.'</a></td>' .
					'</tr><tr><td colspan="8" height="24"></td></tr></table>';
	else if($pcat == 3) $out.= '<table width="100%"><tr><td><a href="index.php?op=addgame">'._Dodajturniej.'</a></td><td><a href="index.php?op=searchadd">'._DodajWyniki.'</a></td><td><a href="index.php?op=searchresults">'._Edytujwynikiturnieju.'</a></td><td><a href="index.php?op=playermanager">'._Zawodnicy.'</a></td><td><a href="index.php?op=logout">'._Wyloguj.'</a></td></tr><tr><td colspan="5" height="24"></td></tr></table>';
	else if($pcat == 2) $out.= '<table width="100%"><tr><td><a href="index.php?op=teammanager">'._Druzyny.'</a></td><td><a href="index.php?op=logout">'._Wyloguj.'</a></td></tr><tr><td colspan="2" height="24"></td></tr></table>';
	else $out.= '<table width="100%"><tr><td><a href="index.php?op=logout">'._Wyloguj.'</a></td></tr><tr><tdheight="24"></td></tr></table>';
	return $out;
}

function searchAll($option, $genre) {
	require_once('classes/tblGames.class.php');
	$tblGames = new TblGames('Turnieje', $option, '');
	
	include("header.php");
	OpenTable();
		$out = _PanelWyszukiwaniaTurniejow.'<br /><br />';

		$out .= $tblGames->buildTable($option);
		$out .= '<br /><br /><br />';
		$out .= $tblGames->searchCup($genre);
		echo $out;
	CloseTable();
	include("footer.php");
}

function gameTable($option) {
//echo $option;
	//$option[condition] = "close!=1";
	require_once('classes/tblGames.class.php');
	$tblGames = new TblGames('Turnieje', $option, '');

	include("header.php");
	OpenTable();
		$out = menu();//.$tblGames->buildTable();
			if ($option == 'savegame')
		//$out.= '<p style="color: maroon">'._Dodajturniej.'</p><br>';
		$out.= '<p>'._Dodajturniej.'</p><br>';
			elseif ($option == 'listresults')
		$out.= '<p>'._DodajWyniki.'</p><br>';
			elseif ($option == 'editresults')
		$out.= '<p>'._Edytujturniej.'</p><br>';		
		$out.= $tblGames->buildTable(); 
				echo $out;
	CloseTable();
	include("footer.php");
}

//str stara
/*function gameTable($option) {
//echo $option;
	//$option[condition] = "close!=1";
	require_once('classes/tblGames.class.php');
	$tblGames = new TblGames('Turnieje', $option, '');

	include("header.php");
	OpenTable();
		$out = menu().$tblGames->buildTable();
		echo $out;
	CloseTable();
	include("footer.php");
}*/
//stop stara

function playerManager($lttr) {
	include('classes/tblPlayers.class.php');
	$tblPlayers = new TblPlayers('Turnieje', 'editplayer', '');
	include("header.php");
	OpenTable();
		$out = menu();
		$out .= '<a href="index.php?op=addplayer">'._Dodajzawodnika.'</a><br /><br />';
		$out .= '<a href="index.php?op=playerbynumber">'._ByNumber.'</a><br /><br />';
		$sql = "SELECT * FROM "._DB_PREFIX."_players WHERE lname LIKE '".$lttr."%' ";
    $sql.= " and cup_id=".$_COOKIE['CUP_ID']." ORDER BY lname";
		//$sql = "SELECT * FROM "._DB_PREFIX."_players ORDER BY player_number";
		$out .= $tblPlayers->display($sql, 'playermanager');
		echo $out;
	CloseTable();
	include("footer.php");
}

function PlayerByNumber() {
	include('classes/tblPlayers.class.php');
	$tblPlayers = new TblPlayers('Turnieje', 'editplayer', '');
	include("header.php");
	OpenTable();
		$out = menu();
		$out .= '<a href="index.php?op=addplayer">'._Dodajzawodnika.'</a><br /><br />';
		$out .= '<a href="index.php?op=playerbynumber">'._ByNumber.'</a><br /><br />';
		//$sql = "SELECT * FROM "._DB_PREFIX."_players WHERE lname LIKE '".$lttr."%' ORDER BY lname";
		$sql = "SELECT * FROM "._DB_PREFIX."_players ";
    $sql.= " where cup_id=".$_COOKIE['CUP_ID'];
    $sql.= " ORDER BY player_number";
		$out .= $tblPlayers->display($sql, 'playermanager');
		echo $out;
	CloseTable();
	include("footer.php");
}

function addPlayer() {
global $pcat;
	include('classes/tblPlayers.class.php');
	$tblPlayers = new TblPlayers('Turnieje', 'saveplayer', '', $pcat);
	include("header.php");
	OpenTable();
		//$tblPlayers->c->player_number = $tblPlayers->getPlayerNumber()+1;
		$tblPlayers->c->player_number = $tblPlayers->getPlayerNumber2()+1;
		$out = menu().$tblPlayers->buildTable();
		echo $out;
	CloseTable();
	include("footer.php");
}

function editPlayer() {
global $pcat;
	include('classes/tblPlayers.class.php');
	$tblPlayers = new TblPlayers('Turnieje', 'updateplayer', $_GET['id'], $pcat);

	include("header.php");
	OpenTable();
		$tblPlayers->getDBValues();
		if($tblPlayers->c->cat >= $pcat || $tblPlayers->c->player_number == 5000) $out = menu().'You can not do it';
		else $out = menu().$tblPlayers->buildTable();
		echo $out;
		echo "<a href=index.php?op=deleteplayer&id=$_GET[id]>"._DELETEPLAYER."</a><br>";//usun zawodnika>>dopisc do langa
	CloseTable();
	include("footer.php");
}

function savePlayer() {
	include('classes/tblPlayers.class.php');
	$tblPlayers = new TblPlayers('Turnieje', 'saveplayer', '');
	include("header.php");
	OpenTable();
		$tblPlayers->getFormValues();
		if(!isset($tblPlayers->c->sex)) $tblPlayers->c->sex = '';
		$ok = true;
		$ok = $tblPlayers->checkTable();
		if($ok) {
			$tblPlayers->makeSQL('INSERT');
			$res = mysql_query($tblPlayers->sql);
			$tblPlayers->updateJsPlayers();
			$out = menu();
			$out .= _SAVEPLAYER;
		}
		else $out = $out = menu().$tblPlayers->buildTable();
		echo $out;
	CloseTable();
	include("footer.php");
}

function updatePlayer() {
	include('classes/tblPlayers.class.php');
	$tblPlayers = new TblPlayers('Turnieje', 'updateplayer', $_POST['id']);
	include("header.php");
	OpenTable();
		$tblPlayers->getFormValues();
		$ok = true;
		$ok = $tblPlayers->checkTable();
		if($ok) {
			$tblPlayers->makeSQL('UPDATE');
			$res = mysql_query($tblPlayers->sql);
			$tblPlayers->updateJsPlayers();
			$out = menu();
			$out .= _SAVEPLAYER;
		}
		else $out = $out = menu().$tblPlayers->buildTable();
		echo $out;
	
	CloseTable();
	include("footer.php");
}

function deletePlayer() {
	include('classes/tblPlayers.class.php');
	$tblPlayers = new TblPlayers('Turnieje', 'deleteplayer', $_POST['id']);
	include("header.php");
	OpenTable();
			
			$tblPlayers->deleteplayer($_GET['id']);
			$out = menu();
			$out .= _DELETED;
			echo $out;

	CloseTable();
	include("footer.php");
}

function saveGame() {
global $pid;
	require_once('classes/tblGames.class.php');
	$tblGames = new TblGames('Turnieje', 'savegame', '');
	include("header.php");
	OpenTable();
		$tblGames->getFormValues();
		
//		if($tblGames->checkOwner($tblGames->c->cup_id, substr($_COOKIE['player'], 0, 1), substr($_COOKIE['player'], 2, 1))) {
			$out = menu();
			if(!isset($tblGames->c->game_mode)) $tblGames->c->game_mode = '';
			$ok = true;
			$ok = $tblGames->checkTable();
			
      if ($ok)
      {
        $trzydnitemu = date('Y-m-d',mktime(0, 0, 0, date("m")  , date("d")-3, date("Y")));// zmiana liczby dnie przez ktore mozemy edytowac wyniki zawsze z minusem '-'
        $dzisiaj = date('Y-m-d');
        $data = implode("-", array_reverse(explode("-", $tblGames->c->dt)));
        if ( ($data < $trzydnitemu) || ($data > $dzisiaj) )
        {
          $out.= '<br />'._NieMoznaDodacTurnieju.'<br />';
          $ok = false;
        }  
			}
			
			if($ok) {
				//zmiana daty na format sql...
				$tblGames->c->dt = implode("-", array_reverse(explode("-", $tblGames->c->dt)));
				
    	 	$sql = 'select player_number from '._DB_PREFIX.'_players where id='.$pid;
        $result = mysql_query($sql);
        $row = mysql_fetch_row($result);
        
				$tblGames->c->chief_pn = $row[0];
			
				$tblGames->makeSQL('INSERT');
				$res = mysql_query($tblGames->sql);
				if($res) {
					$id = mysql_insert_id();
					$out .= _GAMESAVED;
					$out .='<a href="index.php?op=checkgame&id='.$id.'"> '._INSERTRESULTS.'</a>';
				} else {
					$out .= _GAMESAVEPROBLEM;
					$out .= "<br />";
					$tblGames->c->dt = implode("-", array_reverse(explode("-", $tblGames->c->dt)));
					$out .= $tblGames->buildTable();
				}
			} else $out .= $tblGames->buildTable();
//		} else $out = "Nie jeste¶ Szefem tego turnieju. Nie mo¿esz za³o¿yæ gry.";
		echo $out;
	CloseTable();
	include("footer.php");


}

function deleteGame() {
	require_once('classes/tblGames.class.php');
	$tblGames = new TblGames('', '', $_POST['id']);
	include("header.php");
	OpenTable();
		if($_POST['kill_me'] =='on') {
			$tblGames->killMe();
		}
		echo menu();
	CloseTable();
	include("footer.php");
}

//nowe
function updateGames() {
	include('classes/tblGames.class.php');
	$tblGames = new TblGames('Turnieje', 'updateGames', $_POST['id']);
	include("header.php");
	OpenTable();
		$tblGames->getFormValues();
		$ok = true;
		$ok = $tblGames->checkTable();
		if($ok) {
		$tblGames->c->dt = implode("-", array_reverse(explode("-", $tblGames->c->dt)));
			$tblGames->makeSQL('UPDATE');
			$res = mysql_query($tblGames->sql);
			$out = menu();
			$out .= _Turniejzaktualizowany;
		}
		else $out = menu().$tblGames->buildTable();
		echo $out;
	
	CloseTable();
	include("footer.php");


}


function cupManager() {
	include('classes/tblCups.class.php');
	$tblCups = new TblCups('Turnieje', 'editcup', '');
	include("header.php");
	OpenTable();
		$out = menu();
		$out .= '<a href="index.php?op=addcup">'._DodajPuchar.'</a><br /><br />';
		$sql = "SELECT "._DB_PREFIX."_cups.id, cup,close, CONCAT(lname, ' ', fname) ";
    $sql.= " AS chief_pn FROM "._DB_PREFIX."_cups LEFT JOIN "._DB_PREFIX."_players ";
    $sql.= " ON chief_pn = "._DB_PREFIX."_players.player_number and "._DB_PREFIX."_cups.id = cup_id ";
    $sql.= " ORDER BY cup";
		$out .= $tblCups->display($sql);
		echo $out;
	CloseTable();
	include("footer.php");
}

function addCup() {
	include('classes/tblCups.class.php');
	$tblCups = new TblCups('Turnieje', 'savecup', '');
	include("header.php");
	OpenTable();
		$out = menu().$tblCups->buildTable();
		echo $out;
	CloseTable();
	include("footer.php");
}

function saveCup() {
global $pid;
  	include('classes/tblCups.class.php');
	$tblCups = new TblCups('Turnieje', 'savecup', '');

	include("header.php");
	OpenTable();
		$tblCups->getFormValues();
		$ok = true;
		$ok = $tblCups->checkTable();
		if($ok) {
		
			$sql = 'select max(id) as id from '._DB_PREFIX.'_cups ';
      $result = mysql_query($sql);
      $row = mysql_fetch_row($result);
      $old_cup_id = $row[0]; //id poprzedniego sezonu
		
		
    	 	$sql = 'select player_number from '._DB_PREFIX.'_players where id='.$pid;
        $result = mysql_query($sql);
        $row = mysql_fetch_row($result);
        
				$tblGames->c->chief_pn = $row[0];
		
			$tblCups->makeSQL('INSERT');
			$res = mysql_query($tblCups->sql);
      $new_cup_id = mysql_insert_id(); //id ostatnio dodanego
			
			$sql1 = ' insert into '._DB_PREFIX.'_teams ';
			$sql1.= ' (team,cpt_pn,cup_id) ';
			$sql1.= ' select team,cpt_pn,'.$new_cup_id;
			$sql1.= ' from '._DB_PREFIX.'_teams ';
		 	$sql1.= ' where cup_id='.$old_cup_id;
      $res2 = mysql_query($sql1);
      			
      $sql2 = ' insert into '._DB_PREFIX.'_players ';
      $sql2.= ' (team_id,player_number,fname,lname,pass,nick,city,sex,cat,cup_id) ';
      $sql2.= ' select (select t1.id from '._DB_PREFIX.'_teams t1 where t1.team = ';
      $sql2.= ' ( select t2.team from '._DB_PREFIX.'_teams t2 where t2.id = team_id ) ';
      $sql2.= ' and t1.cup_id = '.$new_cup_id.' ) ';
      $sql2.= ' ,player_number,fname,lname,pass,nick,city,sex,cat,'.$new_cup_id;
      $sql2.= ' from '._DB_PREFIX.'_players';
      $sql2.= ' where cup_id = '.$old_cup_id;			
      $res2 = mysql_query($sql2);
      			
      $out .= menu();
			$out .= _SAVECUP;
		}
		else $out = $out = menu().$tblCups->buildTable();
		
		echo $out;
	CloseTable();
	include("footer.php");
}

function dellcup($id){
	include('classes/tblCups.class.php');
$tblCups = new TblCups('Turnieje', 'savecup', '');
	include("header.php");
	OpenTable();
	$tblCups->id=$_GET[id];
	$tblCups->makeSQL('DELETE');
	$res = mysql_query($tblCups->sql);
	
	$tblCups->DeleteGamesInCup($_GET[id]);
	
	$sql = 'delete from '._DB_PREFIX.'_teams where cup_id='.$_GET[id];
	$res = mysql_query($sql);
	
	$sql = 'delete from '._DB_PREFIX.'_players where cup_id='.$_GET[id];
	$res = mysql_query($sql);

	$out = menu();
	
	echo $out;
	echo "<br>"._Pucharusuniety;
	CloseTable();
	include("footer.php");
}

function editCup() {
	include('classes/tblCups.class.php');
	$tblCups = new TblCups('Turnieje', 'updatecup', $_GET['id']);
	include("header.php");
	OpenTable();
	
  setcookie("CUP_ID", $_GET['id'], time()+3600);
  $_COOKIE['CUP_ID'] = $_GET['id'];
  	
		$tblCups->getDBValues();
		$out = menu().$tblCups->buildTable();

		echo $out;
		echo "<a href=index.php?op=dellcup&id=$_GET[id]>"._UsunPuchar."</a><br>";

	CloseTable();
	include("footer.php");
}

function updateCup() {
	include('classes/tblCups.class.php');
	$tblCups = new TblCups('Turnieje', 'updatecup', $_POST['id']);
	include("header.php");
	OpenTable();
		$tblCups->getFormValues();
		$ok = true;
		$ok = $tblCups->checkTable();
		if($ok) {
			$tblCups->makeSQL('UPDATE');
			$res = mysql_query($tblCups->sql);
			$out = menu();
			$out .= _Pucharzaktualizowany;
		}
		else $out = menu().$tblCups->buildTable();
		echo $out;
	
	CloseTable();
	include("footer.php");
}

function addTeam() {
	include('classes/tblTeams.class.php');
	$tblTeams = new TblTeams('Turnieje', 'saveteam', '', $_COOKIE['CUP_ID']);

	include("header.php");
	OpenTable();
		$out = menu().$tblTeams->buildTable();
		echo $out;
	CloseTable();
	include("footer.php");
}

function saveTeam() {
	include('classes/tblTeams.class.php');
	$tblTeams = new TblTeams('Turnieje', 'saveteam', '', $_COOKIE['CUP_ID']);

	include("header.php");
	OpenTable();
		$tblTeams->getFormValues();
		
		$out = menu();
		$ok = true;
		$ok = $tblTeams->checkTable();
	
		if($tblTeams->c->cpt_pn) {
			$sql = "SELECT cpt_pn from "._DB_PREFIX."_teams WHERE cpt_pn = ".$tblTeams->c->cpt_pn;
			$sql.= " and cup_id = ".$_COOKIE['CUP_ID'];
			$res = mysql_query($sql);
			$rows = mysql_num_rows($res);
		
			if($rows) {
				$ok = false;
				$out .= _kapitanmadruzyne;
			}
		}
		if($ok) {
			$tblTeams->makeSQL('INSERT');
			$res = mysql_query($tblTeams->sql);
			
			$id = mysql_insert_id();
			if ($tblTeams->c->cpt_pn)
			 {
			  $sql = "UPDATE "._DB_PREFIX."_players SET team_id = '".$id."' WHERE player_number=".$tblTeams->c->cpt_pn;
			  $sql.= " and cup_id = ".$_COOKIE['CUP_ID'];
			  $res = mysql_query($sql);
		   }	
			$out .= _druzynaZapisana;
		}
		else $out .= $tblTeams->buildTable();
		echo $out;
	CloseTable();
	include("footer.php");
}
/*
 * stara funkcja zapisywania druzyn -> dziala z kapitanem
function saveTeam() {
	include('classes/tblTeams.class.php');
	$tblTeams = new TblTeams('Turnieje', 'saveteam', '');

	include("header.php");
	OpenTable();
		$tblTeams->getFormValues();
		
		$out = menu();
		$ok = true;
		$ok = $tblTeams->checkTable();
	
		if($tblTeams->c->cpt_id) {
			$sql = "SELECT cpt_id from "._DB_PREFIX."_teams WHERE cpt_id = ".$tblTeams->c->cpt_id;
			$res = mysql_query($sql);
			$rows = mysql_num_rows($res);
		
			if($rows) {
				$ok = false;
				$out .= _kapitanmadruzyne;
			}
		}
		if($ok) {
			$tblTeams->makeSQL('INSERT');
			$res = mysql_query($tblTeams->sql);
			
			$id = mysql_insert_id();
			$sql = "UPDATE "._DB_PREFIX."_players SET team_id = '".$id."' WHERE id=".$tblTeams->c->cpt_id;
			$res = mysql_query($sql);
			
			$out .= _druzynaZapisana;
		}
		else $out .= $tblTeams->buildTable();
		echo $out;
	CloseTable();
	include("footer.php");
}*/

//nowe---usuwanie druzyny
function dellteam(){
include('classes/tblTeams.class.php');
$tblTeams = new TblTeams('Turnieje', 'saveteam', '', $_COOKIE['CUP_ID']);
//$tblTeams = new TblTeams('Turnieje', 'editteam', '');
	include("header.php");
	OpenTable();
	$tblTeams->SetFreePlayer($_GET[id]);
	$tblTeams->id=$_GET[id];
	$tblTeams->makeSQL('DELETE');
	$res = mysql_query($tblTeams->sql);
	$out = menu();
	
	echo $out;
	echo "<br>"._Usunietodruzyne;
	
	CloseTable();
	include("footer.php");
}


function editTeam() {
	include('classes/tblTeams.class.php');
	$tblTeams = new TblTeams('Turnieje', 'updateteam', $_GET['id'], $_COOKIE['CUP_ID']);
	include("header.php");
	OpenTable();
	  $out = $tblTeams->teamName($_GET[id]);
		$tblTeams->getDBValues();
		$out = menu().$tblTeams->buildTable();
		echo $out;
		echo "<a href=index.php?op=dellteam&id=".$_GET['id'].">"._UsunDruzyne."</a><br>";
	CloseTable();
	include("footer.php");	
}

function updateTeam() {
	include('classes/tblTeams.class.php');
	$tblTeams = new TblTeams('Turnieje', 'updateteam', $_POST['id'], $_COOKIE['CUP_ID']);
	include("header.php");
	OpenTable();
		$tblTeams->getFormValues();
		$ok = true;
		$ok = $tblTeams->checkTable();
		if($ok) {
			$tblTeams->makeSQL('UPDATE');
			$res = mysql_query($tblTeams->sql);
			$out = menu();
			$out .= _druzynaZaktualizowana;
		}
		else $out = menu().$tblTeams->buildTable();
		echo $out;
	
	CloseTable();
	include("footer.php");
}

function checkGame() {
global $pid, $pcat;
	require_once('classes/tblGames.class.php');
	$tblGames = new TblGames('Turnieje', 'checkratio', $_GET['id']);
	include("header.php");
	OpenTable();
		$out = menu();
		$ok = true;
     		
    	 	$sql = 'select player_number from '._DB_PREFIX.'_players where id='.$pid;
        $result = mysql_query($sql);
        $row = mysql_fetch_row($result);
		
		$ok = $tblGames->checkGameStatus($row[0], $pcat);
			if($ok) $out .= $tblGames->addGameProperties();
			else $out .= $tblGames->err;
		echo $out;
	CloseTable();
	include("footer.php");
}

function checkGameProperties() {
	include("header.php");
	OpenTable();
	$ratioErr = false;
	$playersErr = false;
		if(!preg_match("/[0-9]{1}\.[0-9]{1}/", $_POST['ratio'])) $ratioErr = true;
		if(!is_numeric($_POST['players']) || $_POST['players']/$_POST['mode'] < 4) $playersErr = true;
		if($ratioErr || $playersErr) {
			require_once('classes/tblGames.class.php');
			$tblGames = new TblGames('Turnieje', 'checkratio', $_POST['id']);
			$out = $tblGames->addGameProperties($ratioErr, $playersErr);
		} else {
			$sql = "UPDATE "._DB_PREFIX."_games SET ratio = '".$_POST['ratio']."' WHERE id = ".$_POST['id'];
			$res = mysql_query($sql);
			
			include('classes/tblResults.class.php');
			$tblResults = new TblResults('Turnieje', 'saveresults', $_POST['id']);
			$out = $tblResults->addGameResults($_POST['ratio'], $_POST['players']);
		}
	echo $out;
	CloseTable();
	include("footer.php");
}

function checkEditedRatio() {
global $pid, $pcat;
	include('classes/tblResults.class.php');
	$tblResults = new TblResults('Turnieje', 'updateresult', $_POST['id']);

	include("header.php");
	OpenTable();
		if(!preg_match("/[0-9]{1}\.[0-9]{1}/", $_POST['ratio'])) {
			$tblResults->err->rato = _NieprawidlowyWspolczynnik;
		}
		else {
			$sql = "UPDATE "._DB_PREFIX."_games SET ratio = '".$_POST['ratio']."' WHERE id = ".$_POST['id'];
			$res = mysql_query($sql);
		}
		
    	 	$sql = 'select player_number from "._DB_PREFIX."_players where id='.$pid;
        $result = mysql_query($sql);
        $row = mysql_fetch_row($result);
        		
		$out = $tblResults->editGameResults($row[0], $pcat);
		echo $out;
	CloseTable();
	include("footer.php");
}

function saveResults() {
	include('classes/tblResults.class.php');
	$tblResults = new TblResults('Turnieje', 'saveresults', $_POST['id']);
	include("header.php");
	OpenTable();
		$ok = true;
		$ok = $tblResults->checkGameResults();
		if($ok) {
			$tblResults->saveGameResults();
			$sql = "UPDATE "._DB_PREFIX."_games SET game_status = 'closed' WHERE id = ".$_POST['id'];
			$res = mysql_query($sql);
			echo menu()._WynikiZapisane;
		} else {
			$out = menu().$tblResults->addGameResults($_POST['ratio'], $_POST['players']);
			echo $out;
		}
	CloseTable();
	include("footer.php");
}

function editResult() {
global $pid, $pcat;
include('classes/tblResults.class.php');
include('classes/tblGames.class.php');
include("header.php");
	$TblGames = new TblGames('Turnieje', 'updategames', $_GET['id']);
	OpenTable();
		$TblGames->getDBValues();
		
		echo menu();
		
    $trzydnitemu = date('Y-m-d',mktime(0, 0, 0, date("m")  , date("d")-3, date("Y"))); //tutaj zmieniamy liczbe przez ktore mozemy edytowac wyniki, uwaga liczbe trzeba poprzedzic minusem '-'
    if($TblGames->c->dt < $trzydnitemu)
      $tblResults->err = _NieMoznaDodacWynikuMinely3Dni;
    else
     {		
  		$TblGames->c->dt = implode("-", array_reverse(explode("-", $TblGames->c->dt)));
  		$out = $TblGames->buildTable();		
  		echo $out;
  		echo'<br /><br />';
  		$tblResults = new TblResults('Turnieje', 'updateresult', $_GET['id']);
  		
  	 	$sql = 'select player_number from '._DB_PREFIX.'_players where id='.$pid;
      $result = mysql_query($sql);
      $row = mysql_fetch_row($result);
      
  		$out ='<form name="delete_game" method="post" action="'.$_SERVER['PHP_SELF'].'">';
      $out .='<table><tr><td><input type="checkbox" name="kill_me"></td><td>';
      $out .=_JeslichceszskasowacturniejzaznaczcheckboxinacisnijSkasuj;
      $out .='</td><td><input type="hidden" name="name" value="'.$TblGames->name.'">';
      $out .='<input type="hidden" name="op" value="deletegame">';
      $out .='<input type="hidden" name="id" value="'.$TblGames->id.'">';
      $out .='<input type="submit" name="killer" value="'._Skasuj.'">';
      $out .='</td></tr><tr><td colspan="3" height="12"></td></tr></table></form>';
      echo $out;
      
  		$out = $tblResults->editGameResults($row[0], $pcat);
  	 }
		if($out) echo $out;
		else {
			echo $tblResults->err;
		}
	CloseTable();
	include("footer.php");
}

function updateGameResult() {
global $pid, $pcat;
	include('classes/tblResults.class.php');


	$tblResults = new TblResults('Turnieje', 'updateresult', $_POST['id']);
	include("header.php");
	OpenTable();
	
 	$ok = true;
		$ok = $tblResults->checkEditedPlaces();
		
		$out = menu();
 
/*	 	$sql = 'select player_number from '._DB_PREFIX.'_players where id='.$pid;
    $result = mysql_query($sql);
    $row = mysql_fetch_row($result);*/
      
		if($ok) {
			$tblResults->updateGameResults();
			$out .= $tblResults->editGameResults($row[0], $pcat);
		} else $out .= $tblResults->editGameResults($row[0], $pcat, true);
		
		echo $out;
	CloseTable();
	
	include("footer.php");
}

function showGames($option, $oldop) {

	require_once('classes/tblGames.class.php');
	$tblGames = new TblGames('Turnieje', $option, '');
	
	include("header.php");
	OpenTable();
		$tblGames->getFormValues();
		$tblGames->c->dt = implode("-", array_reverse(explode("-", $tblGames->c->dt)));

		$out = '';
		if($oldop == 'listresults') $out .= menu();
		
		$out .= $tblGames->tblShowGames($option);
		
		if($tblGames->err)  {
			$tblGames->op = $oldop;
			$out .= $tblGames->err;
			$out .= "<br /><br />";
			$out .= $tblGames->buildTable();
		}
		echo $out;
	CloseTable();
	include("footer.php");
}

function printGamesList() {
	require_once('classes/tblGames.class.php');
	$tblGames = new TblGames('Turnieje', '', '');

	foreach ($_GET as $key => $value) {
		if($key != 'name' && $key != 'op') $tblGames->c->{$key} = $value;
	}
		$out = $tblGames->tblShowGames2('showgame');

    echo "<table width=\"650\"><tr><td>";
		echo $out;
		echo '</td></tr></table>';
		echo '<br><br>';
		echo '<script>print();</script>';
//		echo '<center><INPUT type=button value="Print" onClick="window.print();"></center>';

}

function printGameResult() {
	include('classes/tblResults.class.php');
	$tblResults = new TblResults('', '', $_GET['id']);
		$out = $tblResults->showGameResults2();
    echo "<table width=\"650\"><tr><td>";
		echo $out;
		echo '</td></tr></table>';
		echo '<br><br>';
		echo '<script>print();</script>';
		//echo '<center><INPUT type=button value="Print" onClick="window.print();"></center>';	
}

function printRank($cid, $genre) {
	include('classes/tblResults.class.php');
	$tblResults = new TblResults('', '', '');

	//global $cup,$genre; 
	if($cid==''){
		
		require_once('classes/tblGames.class.php');
		$tblGames = new TblGames('Turnieje', $option, '');
	}
		$out = $tblResults->showRanks2($cid, $genre);
    echo "<table width=\"650\"><tr><td>";
		echo $out;
		echo '</td></tr></table>';
		echo '<br><br>';
		echo '<script>print();</script>';
//		echo '<center><INPUT type=button value="Print" onClick="window.print();"></center>';
}

function showGameResult() {
	include('classes/tblResults.class.php');
	$tblResults = new TblResults('', '', $_GET['id']);
	
	include("header.php");
	OpenTable();
		$out = $tblResults->showGameResults();
		echo $out;
	CloseTable();
	include("footer.php");
}


function teamManager() {
	include('classes/tblTeams.class.php');
	$tblTeams = new TblTeams('Turnieje', 'editteam', '', $_COOKIE['CUP_ID']);
	include("header.php");
	OpenTable();
		$out = menu();
		$out .= '<a href="index.php?op=addteam">'._DodajDruzyne.'</a><br /><br />';
//		$out .= '<a href="index.php?op=dellteam">usun</a><br /><br />';
		$sql = "SELECT "._DB_PREFIX."_teams.id, team, CONCAT(lname, ' ', fname) AS cpt_pn ";
    $sql.= " FROM "._DB_PREFIX."_teams LEFT JOIN "._DB_PREFIX."_players ON cpt_pn = "._DB_PREFIX."_players.player_number ";
    $sql.= " and "._DB_PREFIX."_players.cup_id= ".$_COOKIE['CUP_ID'];
    $sql.= " WHERE "._DB_PREFIX."_teams.cup_id = ".$_COOKIE['CUP_ID'];
    $sql.= " ORDER BY team";
		$out .= $tblTeams->display($sql);
		echo $out;
	CloseTable();
	include("footer.php");
}

function menageTeam() {
	include('classes/tblTeams.class.php');
	$tblTeams = new TblTeams('Turnieje', 'delteamplayer', '', $_COOKIE['CUP_ID']);

	include("header.php");
	OpenTable();
		$out = $tblTeams->teamMenu();
		echo $out;
	CloseTable();
	include("footer.php");
}

function addTeamPlayer($id, $lttr) {
	include('classes/tblTeams.class.php');
	$tblTeams = new TblTeams('Turnieje', 'saveteamplayer', '', $_COOKIE['CUP_ID']);

	include("header.php");
	OpenTable();
		$out = $tblTeams->showFreePlayers($id, $lttr);
		echo $out;
	CloseTable();
	include("footer.php");
}

function saveTeamPlayer() {
	include('classes/tblTeams.class.php');
	$tblTeams = new TblTeams('', '', '', $_COOKIE['CUP_ID']);
	
	include("header.php");
	OpenTable();
		$out = $tblTeams->saveTeamPlayers();
		echo _ZmianyZachowane;
	CloseTable();
	include("footer.php");
}

function delTeamPlayer() {
	include('classes/tblTeams.class.php');
	$tblTeams = new TblTeams('', '', '', $_COOKIE['CUP_ID']);
	
	include("header.php");
	OpenTable();
		$out = $tblTeams->delTeamPlayers();
		echo _ZmianyZachowane;
	CloseTable();
	include("footer.php");
}

function showRank($cid, $genre) {
	include('classes/tblResults.class.php');
	$tblResults = new TblResults('', '', '');
	

	include("header.php");
	OpenTable();
	
	//global $cup,$genre; 

	echo $genre;
	
	if($cid==''){
		
		require_once('classes/tblGames.class.php');
		$tblGames = new TblGames('Turnieje', $option, '');
	
		//$out = PanelWyszukiwaniaTurniejow.'<br /><br />';

		//$out .= $tblGames->buildTable($option);
		
		$out .= '<br /><br /><br />';
		
		$out .= $tblGames->searchCup($genre);
		echo $out;
		die();
	}
	
		$out = $tblResults->showRanks($cid, $genre);
		echo $out;
	CloseTable();
	include("footer.php");
}

function showPlayerResult() {
	include('classes/tblResults.class.php');
	$tblResults = new TblResults('', '', '');
	
	include("header.php");
	OpenTable();
		$out = $tblResults->tblPlayerResult($_GET['id'], $_GET['cupid']);
		echo $out;
	CloseTable();
	include("footer.php");
}

function importPlayers() {
	include("header.php");
	OpenTable();
	  echo menu();
	  if ($_FILES['plik'][size]==0) //wybierz plik do wczytania
	   {
        $out = '<form name="importPlayers" method="post" action="'.$_SERVER['PHP_SELF'].'" enctype="multipart/form-data">' .
        			'<table width="100%"><tr><td>'._PlikDoImportu.':</td>' .
        			'<td><input type="file" name="plik" /></td>' .
        			'</tr><tr><td colspan="2">' .
        			'<input type="hidden" name="name" value="Turnieje">' .
        			'<input type="hidden" name="op" value="importplayers">' .
        			'</td></tr>' .
        			'<tr><td colspan="2"><input type="submit" name="submit" value="'._Wyslij.'"></td></tr></table></form>';
        echo $out;	     
	   }
	  else //wczytaj zawodnikow
     {
       $naglowek = true;
            $uchwyt = @fopen ($_FILES['plik']['tmp_name'], "r");
            if ($uchwyt) 
            {
             while (!feof($uchwyt)) 
              {
                $linia = fgets($uchwyt, 4096);
                
                $dane_usera = explode(";",$linia);
       
 //0 player number;
 //1 fname;
 //2 iname;
 //3 pass;
 //4 cat;
 //5 nick;
 //6 city;
 //7 sex;
 //8 team         
               
                if (!$naglowek && $dane_usera[1] != '')
                {  
                 try 
                  {
                  	$player_number = (int) substr($dane_usera[0],2) ;
                    
                    $dane_usera[8] = str_replace('"','', $dane_usera[8] );  
                    
                    $sql = " select count(*) as ilosc from "._DB_PREFIX."_teams ";
                  	$sql.= " where team = '".htmlspecialchars($dane_usera[8], ENT_QUOTES)."'";
                  	$sql.= " and cup_id = ".$_COOKIE['CUP_ID'];
                    $result = mysql_query($sql);
               // echo $sql.'<br />';     
                    $row = mysql_fetch_row($result);
                    if ($row[0] == 0)
                      {
                        $sql = " insert into "._DB_PREFIX."_teams (cup_id,team) values ( ";
                        $sql.= $_COOKIE['CUP_ID'].",";
                        $sql.= "'".htmlspecialchars($dane_usera[8], ENT_QUOTES)."')";
             //  echo  $sql.'<br /><br />';     
                        $result = mysql_query($sql); 
                      }               
                  
                  	$sql = " insert into "._DB_PREFIX."_players";
                  	$sql.= " (team_id, player_number, fname, lname, ";
                  	$sql.= " pass, nick, city, sex, cat, cup_id ";
                  	$sql.= " ) values (";
          
          //          if (strtoupper($dane_usera[4]) == 'TURNIERLEITER'
          //             || strtoupper($dane_usera[4]) == 'RANKINLEITER' )
                      if (Trim($dane_usera[8]) != '' )
                     {
                      $sql.= "(select id from "._DB_PREFIX."_teams ";
                      $sql.= "where team = '".htmlspecialchars($dane_usera[8], ENT_QUOTES)."'";
                    	$sql.= " and cup_id = ".$_COOKIE['CUP_ID']."),";
                  	 }
                  	else 
                  	  $sql.= "null,";
                  	 
                    $sql.= "'".$player_number."',";
                    $sql.= "'".htmlspecialchars($dane_usera[1], ENT_QUOTES)."',";
                    $sql.= "'".htmlspecialchars($dane_usera[2], ENT_QUOTES)."',";
                    
                    $sql.= "'".htmlspecialchars($dane_usera[3], ENT_QUOTES)."',";
                    $sql.= "'".htmlspecialchars($dane_usera[5], ENT_QUOTES)."',";
                    $sql.= "'".htmlspecialchars($dane_usera[6], ENT_QUOTES)."',";
                    if ($dane_usera[7] == 'M')
                      $sql.= "'1',";
                    else
                      $sql.= "'2',";
                      
                    if (strtoupper($dane_usera[4]) == 'RANKINLEITER' ) 
                      $sql.= "2,";
                    elseif (strtoupper($dane_usera[4]) == 'TURNIERLEITER' ) 
                      $sql.= "3,";
                    else  
                      $sql.= "1,";
                    $sql.= $_COOKIE['CUP_ID'].")";
                    $result = mysql_query($sql);
                    
                    

                    
                    if (strtoupper($dane_usera[4]) == 'TURNIERLEITER' 
                       || strtoupper($dane_usera[4]) == 'RANKINLEITER' )
                     {
                       $sql = "update "._DB_PREFIX."_teams set cpt_pn = '".$player_number."' ";
                       $sql.= "where team = '".htmlspecialchars($dane_usera[8], ENT_QUOTES)."'";
                       $sql.= " and cup_id = ".$_COOKIE['CUP_ID'];
                       $result = mysql_query($sql);
                     }
                   } 
                  catch (Exception $e) 
                   {
                    echo _BladPrzyZawodniku." ".$dane_usera[1]." ".$dane_usera[2];
                    echo " (".$e->getMessage().")<br />";
                   }          
                 }
                 $naglowek = false;       
                }
            fclose ($uchwyt);
            echo _ImportWykonany;
            }
       
     } 
	CloseTable();
	include("footer.php");
}

$op = $_REQUEST['op'];

switch($op) {
	// ************ Funkcje Logowania *********************//
	
	case "adm": //panel administracyjny modu³u
		admin();
	break;
	
	case "logout": //panel administracyjny modu³u
		logout();
	break;
	
    // ************ Funkcje obs³ugi Zawodników *********************//	
	case "playermanager": //
		$_GET['lttr'] ? $lttr = $_GET['lttr'] : $lttr = 'A';
		if($pcat == 3 || $pcat == 4) playerManager($lttr);
		else admin();
	break;

	case "importplayers": //
		if($pcat == 3 || $pcat == 4) importPlayers();
		else admin();
	break;
	
	case "addplayer": //
		if($pcat == 3 || $pcat == 4) addPlayer();
		else admin();
	break;
	
	case "editplayer": //
		if($pcat == 4) editPlayer();
		else admin();
	break;
	
	case "saveplayer": //
		if($pcat == 3 || $pcat == 4) savePlayer();
		else admin();
	break;
	
	case "updateplayer": //
		if($pcat == 4) updatePlayer();
		else admin();
	break;

	case "deleteplayer": //
		if($pcat == 4) deletePlayer();
		else admin();
	break;
	
	case "playerbynumber": //
		if($pcat == 3 || $pcat == 4) PlayerByNumber();
		else admin();
	break;
	
	// ************ Funkcje obs³ugi Gier *********************//
	case "addgame": //formularz dodawania turnieju
		if($pcat == 3 || $pcat == 4) gameTable('savegame');
		else admin();
	break;
//nowe
case "updategames": //formularz edycji turnieju
		if($pcat == 3 || $pcat == 4) updateGames('updategames');
		else admin();
	break;
	
	case "savegame": //zapisywanie dodanego turnieju
		if($pcat == 3 || $pcat == 4) saveGame();
		else admin();
	break;
	
	case "deletegame": //kasowanie turnieju
		if($pcat == 3 || $pcat == 4) deleteGame();
		else admin();
	break;
	
	// ************ Funkcje obs³ugi Pucharów *********************//
	
	case "cupmanager": //formularz dodawania pucharu
	  	if($pcat == 4) cupManager();
		else admin();
	break;
	
	case "dellcup": //formularz usuwania pucharu
	  	if($pcat == 4) dellcup($_GET[id]);
		else admin();
	break;
	
	case "addcup": //formularz dodawania pucharu
	  	if($pcat == 4) addCup();
		else admin();
	break;
	
	case "savecup": //zapisywanie dodanego pucharu
		if($pcat == 4) saveCup();
		else admin();
	break;
	
	case "editcup": //zapisywanie dodanego pucharu
		if($pcat == 4) editCup();
		else admin();
	break;
	
	case "updatecup": //zapisywanie dodanego pucharu
		if($pcat == 4) updateCup();
		else admin();
	break;
	
	// ************ Funkcje obs³ugi Zespo³ów *********************//
	
	case "teammanager": //menager zespo³ów
		if($pcat == 4) teamManager();
		else admin();
	break;

	case "addteam": //formularz dodawania zespo³u
		if($pcat == 4) addTeam();
		else admin();
	break;
	
	case "saveteam": //zapisywanie dodanego zespo³u
		if($pcat == 4) saveTeam();
		else admin();
	break;
	
	case "dellteam": //usuwanie zespo³u
		if($pcat == 4) dellteam();
		else admin();
	break;
	
	
	case "editteam": //edycja zespo³u
		if($pcat == 4) editTeam();
		else admin();
	break;
	
	case "updateteam": //zapisywanie zmienionego zespo³u
		if($pcat == 4) updateTeam();
		else admin();
	break;
	
	case "menageteam": //zarz±dzanie dru¿yn±
		if($pcat == 4) menageTeam();
		else admin();
	break;
	
	case "addteamplayer": //listowanie dostêpnych zawodników
		if($pcat == 4) addTeamPlayer($_GET['id'], $_GET['lttr']);
		else admin();
	break;
	
	case "saveteamplayer": //zapisywanie do dru¿yny wybranych zawodników
		if($pcat == 4) saveTeamPlayer();
		else admin();
	break;
	
	case "delteamplayer": //usuniêcie zawodnika z dru¿yny
		if($pcat == 4) delTeamPlayer();
		else admin();
	break;
	
	// ************ Funkcje dodawania Wyników *********************//
	
	case "searchadd": //formularz wyszukiwania turnieju w celu zapisu wynikow
		if($pcat == 3 || $pcat == 4) gameTable('listresults');
		else admin();
	break;
	
	case "listresults": //listowanie turniei z opcja zapisu wyników
		if($pcat == 3 || $pcat == 4) showGames('checkgame', 'listresults');
		else admin();
	break;
	
	case "checkgame": //sprawdzanie statusu gry
		if($pcat == 3 || $pcat == 4) checkGame();
		else admin();
	break;
	
	case "checkratio": //sprawdzenie i dodanie wspó³czynnika 
		if($pcat == 3 || $pcat == 4) checkGameProperties();
		else admin();
	break;
	
	case "saveresults": //zapisanie wyników turnieju
		if($pcat == 3 || $pcat == 4) saveResults();
		else admin();
	break;
	
	// ************ Funkcje edycji Wyników *********************//
	
	case "searchresults": //formularz wyszukiwania turnieju w celu edycji wynikow
		if($pcat == 3 || $pcat == 4) gameTable('editresults');
		else admin();
	break;
	
	case "editresults": //listowanie turniei z opcja edycji wyników
		if($pcat == 3 || $pcat == 4)
      showGames('editresult', 'editresults');
		else admin();
	break;
	
	case "editresult": //formularz edycji wyników turnieju
		if($pcat == 3 || $pcat == 4) editResult();
		else admin();
	break;
	
	case "checkeditedratio": //sprawdzenie edytowanego wspó³czynnika 
		if($pcat == 3 || $pcat == 4) checkEditedRatio();
		else admin();
	break;
	
	case "updateresult": //zapisanie poprawionych wyników turnieju
		if($pcat == 3 || $pcat == 4) updateGameResult();
		else admin();
	break;
	
	// ************ Funkcje wy¶wietlania Wyników *********************//
	
	case "searchgame": //formularz wyszukiwania turnieju w celu obejrzenia wyników
		searchAll('showgames', $genre_select);
	break;
	
	case "showgames": //listowanie turniei z opcja ogl±dania wyników
		showGames('showgame', 'showgames');
	break;
	
	case "showgame": //listing wyników turnieju
		showGameResult();
	break;

	case "rank": //wy¶wietlanie rankingów
		if($_SERVER['REQUEST_METHOD'] == 'POST') showRank($_POST['cup'], $_POST['genre']);
		if($_SERVER['REQUEST_METHOD'] == 'GET') showRank($_GET['id'], $_GET['genre']);
	break;
	
	case "playergames":
		showPlayerResult();
	break;
	
	case "print": //drukowanie wyników
		$printMe = true;
		if(!empty($_GET['cup_id']) || !empty($_GET['game']) || !empty($_GET['town']) || !empty($_GET['place']) || !empty($_GET['dt'])) printGamesList();
		//else if(!empty($_GET['genre'])) showRank($_GET['id'], $_GET['genre']);
		else if(!empty($_GET['genre'])) printRank($_GET['id'], $_GET['genre']);
		else printGameResult();
	break;
	
	default:
		start();
	break;
}

?>
