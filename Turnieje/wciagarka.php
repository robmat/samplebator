<?/*
echo 'hallo<br>';

    $open = fopen ("_new2.csv", "r");
    $this->conn = mysql_connect("localhost", "web27", "GrIX6nfP");
    	    mysql_select_db("usr_web27_1",$this->conn);

		mysql_query ("SET SET NAMES latin1;");
    mysql_query ("SET CHARACTER SET latin1;");
    mysql_query ("SET COLLATION_CONNECTION = 'latin1_swedish_ci';");
	
    if ($open) 
    	{
    		while (!feof($open))
    		{
    		$buffer = fgets($open, 4096);
    		if ($buffer != "")
    		   {	
        			$pieces = explode(",", $buffer);
	
						if (mysql_escape_string(trim($pieces[5])) == 'M')
							$pieces[5] = 1;
						else 
							$pieces[5] = 2;
								
						echo $sql2 = "select id from nuke_teams where team = '".mysql_escape_string(trim($pieces[7]))."'";
						
						$result = mysql_query($sql2, $this->conn);
						$wynik = mysql_fetch_row($result);
						$id_team = $wynik[0];
						if ($id_team == '')
							 $id_team = 'NULL';
						echo "<br>".$id_team;
						echo "<br>";		
								
						$sql = "insert into nuke_players (player_number, fname, lname, pass, nick";
						$sql.= " ,city, sex, cat, team_id)";
						$sql.= " values (".mysql_escape_string(trim($pieces[0])).",'".mysql_escape_string(trim($pieces[2]))."','".mysql_escape_string(trim($pieces[1]));
						$sql.= "','".mysql_escape_string(trim($pieces[8]))."','".mysql_escape_string(trim($pieces[3]))."','".mysql_escape_string(trim($pieces[4]))."'";
						$sql.= ",".$pieces[5].",".mysql_escape_string(trim($pieces[6]));
						echo $sql.= ",".$id_team.")";
								mysql_query($sql, $this->conn);
echo "<br>";
    			}	
    		}
    
    	fclose ($open);
    	}
    mysql_close($this->conn);
echo 'gotowe';
/*

    $open = fopen ("zawodnicy_de.csv", "r");
    $this->conn = mysql_connect("localhost", "web27", "GrIX6nfP");
    	    mysql_select_db("usr_web27_1",$this->conn);

		mysql_query ("SET SET NAMES latin1;");
    mysql_query ("SET CHARACTER SET latin1;");
    mysql_query ("SET COLLATION_CONNECTION = 'latin1_swedish_ci';");
	
    if ($open) 
    	{
    		while (!feof($open))
    		{
    		$buffer = fgets($open, 4096);
    		if ($buffer != "")
    		   {	
        			$pieces = explode(",", $buffer);
							$sql = " select id from nuke_teams where team = '".mysql_escape_string(trim($pieces[7]))."'";
							$wynik = mysql_query($sql, $this->conn);
							$wiersz=mysql_fetch_array($wynik);
			
							if (! $wiersz)
								{
												
									$sql = "insert into nuke_teams (team)";
									//$sql.= " ,city, sex, cat)";
									$sql.= " values ('".mysql_escape_string(trim($pieces[7]));
									echo $sql.= "')";
											mysql_query($sql, $this->conn);
								}

    			}	
    		}
    
    	fclose ($open);
    	}
    mysql_close($this->conn);
echo 'gotowe';
*/

?>