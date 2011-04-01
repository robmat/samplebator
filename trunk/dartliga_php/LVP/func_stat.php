<?php
/**
# ----------------
# v01 BH 2007/01/01
# ----------------
# functions to be used for the statistics part only - no general stuff here ....
#
# OK which is the last SSI date, basis for all calculations and preview LISTS
# this is done in the ls_func code ...
# Here we have only functions which are called by administrative users ...
**/

if (eregi("func_stat.php",$_SERVER['PHP_SELF'])) {Header("Location: ./"); die();}

$minsip=14;		# mindestanzahl an entries fr anzeige
$minSSIVal=1400;	# mindest SSI Wert kann nicht unterschritten werden.

/**
	# this calculates the ranking points of all players who belong
	# to a specific LEAGUE-Group $fedagroup FEDA (MIXED or DAMEN)
	# a) retrieve all legs belonging to this listnumber form all events for the specified period
	# b) check on legacy table and include data
	# v2.6 incl current league in report
	*	v3 changed to fit into ls_statsadmin - produce minimal output ...
	*	v4 changed and removed the team - event strings ...
	*			includes all events of this statgroup not only active events
	* v5  extended by the API_RS model
	* v5 reM: works only on 501 legs !!
	**/
function generateStaticFEDAStatisticEntries($feda_stat_group,$indexdate,$writeToDB='no'){
	
	global $user,$dbi;

	$fromdate  = fnc_date_calc($indexdate,-365);
	/*
	* Rangliste berechnet aus allen Spielen von $fromdate bis $indexdate
	* we query after all players from all events belonging to this statgroup ...
	* this returns really ALL players who have ever played in this group (active + non-active events ..)
	* No sense to include the teams+events here, since there could be multiple ...
	* this returns multiple records for each player ....
	*/
	$RS=DB_listEventStatGroupPlayers($dbi,$feda_stat_group);
	$aTH=array("Vorname","Nachname","Scorezahl","Checkzahl","Gesamtzahl","Legs","Spiele");
	debug("Calculating actual stat values for ".count($RS)." Players");
	OpenTable();
	echo ArrayToTableHead($aTH);
	foreach ($RS as $p){
		#
		# get legs per player - calc - and sum up, att. here we can have multiple player entries
		#	the recordset is sorted by PID ...
		#
		$sumScore=0;
		$sumCheck=0;
		$CountScore=0;
		$CountCheck=0;
		$scoreindex=0;
		$checkindex=0;
		#######################
		# LEGACY data from pre system times
		#######################
		$legqry="select lxid,lxdate,lxrscore,lxrest,lxrcheck from tbllegx where lxpid=$p[0] and lxevlist=$feda_stat_group and lxdate<'$indexdate' and lxdate>'$fromdate' and lxrscore>0 order by lxdate asc";
		$Lrecord = sql_query($legqry,$dbi);
		while(list($lxid,$lxdate,$lxrscore,$lxrest,$lxrcheck)=sql_fetch_row($Lrecord,$dbi)){
			$idx="";
			$idx=retFEDAIndexZahlperLeg(501,$lxrscore,$lxrest,$lxrcheck,$iTEST);
			list($a,$b,$c)=split(":",$idx);
			# values of -1 indicate failure
			if ($a > -1) {
				$sumScore=$sumScore+$a;
				$CountScore=$CountScore+1;
				}
			if ($b > -1) {
				$sumCheck=$sumCheck+$b;
				$CountCheck=$CountCheck+1;
				}
		}
		#######################
		# structured data from League-System
		#######################
		$LEGS=DB_listLegsFromPeriod($dbi,0,0,$feda_stat_group,$fromdate,$indexdate,$p[0]);
		#debug(count($LEGS));
		# lid,lroundscore,lscore,lroundcheck,gid,mid,mround,mdate ##
		$gamecount=0;
		$lastgid=0;
		foreach ($LEGS as $L){
			if ($lastgid<>$L[4]) $gamecount=$gamecount+1;
			$idx="";
			$idx=retFEDAIndexZahlperLeg(501,$L[1],(501-$L[2]),$L[3]);
			list($a,$b,$c)=split(":",$idx);
			# values of -1 indicate failure
			if ($a > -1) {
				$sumScore=$sumScore+$a;
				$CountScore=$CountScore+1;
				}
			if ($b > -1) {
				$sumCheck=$sumCheck+$b;
				$CountCheck=$CountCheck+1;
				}
			$lastgid=$L[4];
		}
		/*
		 * calculate index by division with countvalues
		 */
		if ($CountScore>0) $scoreindex=($sumScore/$CountScore);
		if ($CountCheck>0) $checkindex=($sumCheck/$CountCheck);
		/*
		* Finally Output into TABLEROW or OUTVAR
		*/
		/*
	 	* V3.1 change, since we have ALL players here we have a lot of cases where no actual statval is compiled in this case its zero and
	 	* we cont store anything ..
	 	*/
		if ($gamecount>0) {
			echo "<tr><td>$p[1]</td><td>$p[2]</td><td>".number_format($scoreindex,2,'.','')."</td><td>".number_format($checkindex,2,'.','')."</td><td>".number_format(($scoreindex+$checkindex),2,'.','')."</td><td>$CountScore</td><td>$gamecount</td></tr>";
			if ($writeToDB=='yes'){
			$qry="insert into tblstat(statid,statdate,statcode,statval,statpid,statgames,statlegs) values(0,'$indexdate',$feda_stat_group,".number_format(($scoreindex+$checkindex),2,'.','').",$p[0],$gamecount,$CountScore)";
			$res=sql_query($qry,$dbi);
			}
		} else {
			echo "<tr style=\"color:#ff0000;\"><td>$p[1]</td><td>$p[2]</td><td>NO DATA</td><td>NO DATA</td><td>NO DATA</td><td></td><td></td></tr>";
		}
} #//  END FOR EACH PLAYER
	
	CloseTable();
}

/**
*	purpose:	calculate new SSI based on GAME results between vdate1 -> vdate2
* 				stores new SSI in stat-table
* 	params:		date,date
*	returns:	output as ECHO statements
*/
function generateStaticSSIStatisticEntries($vdate1,$vdate2){
	# //
	# // v01 BH 12.7.2003 initial version - works
	# // run game query -> store pid,points in array (PID=index)
	# // run loop on every player - add points from array ... or from last SSI
	# // v02 BH for every player get the gamecount for this period and set the STATUS flag
	# // v03 added Rule 1 and rule 2
	# // v04 re-worked for ls_statsadmin
	
	global $dbi,$user,$redpic,$greenpic,$minSSIVal,$goback;
	if (!isset($vdate1)) die("Error: no START date".$goback);
	if (!isset($vdate2)) die("Error: no END date".$goback);
	
	$srecords = sql_query("select pid,pfname,plname,rid,rgid,rpid,rresult,rdate,rdesc,statval"
			." from tplayer,tresult,tblstat"
			." where pid=rpid and pid=statpid and statcode=2 and statdate = '$vdate1' and rdate>'$vdate1' and rdate<'$vdate2'"
			." order by rdate,rgid asc,rresult desc",$dbi);
	$i=0;			
	$lastgame=0;
	$arr=array();
	while(list($pid,$pfname,$plname,$rid,$rgid,$rpid,$rresult,$rdate,$rdesc,$sippoints)=sql_fetch_row($srecords,$dbi)){
		#
		# es gibt immer 2 entries pro Spiel -> darstellung in einer Reihe
		# SORTORDER = WINNER A / Loser B -> Sieger immer in Record 1 Loser in Record 2
		# momentan entpricht der valAwin immer dem -valBWin (also reziprok wert ...)
		# der dynamic faktor liegt fr alle spieler bei 50 =(200/4)=3:1
		#
		$i=$i+1;
		if ($rgid<>$lastgame) {
			$sipA=$sippoints;
			$pidA=$pid;
		} else{
			$sipB=$sippoints;
			$pidB=$pid;

			# // calculate PLAYER A WINNER //////////
			$valAwin=ReturnSSIChangePlayer($sipA,$sipB,1);
			
			# // calculate PLAYER B Loser /////////
			$valBLost=ReturnSSIChangePlayer($sipB,$sipA,0);
			
			#debug("A PID=$pidA Value Arr=$arr[$pidA] adding $valAwin");
			#debug("B PID=$pidB Value Arr=$arr[$pidB] adding ".(-$valBLost));
			$arr[$pidA]=$arr[$pidA]+$valAwin;
			$arr[$pidB]=$arr[$pidB]+(-$valBLost);
			$gamecount[$pidA]=$gamecount[$pidA]+1;
			$gamecount[$pidB]=$gamecount[$pidB]+1;
		}

		$lastgame=$rgid;
	}
	echo "<h3>Abrechnung der Spielresultate Periode $vdate1 / $vdate2</h3><p>Zwecks &Uuml;berpr&uuml;fung oder einer manuellen Buchhaltung k&ouml;nnen die angezeigten Daten mit copy-paste &uuml;bernommen und z.B. anderweitig gesichert werden.<br>SSI Base Value=$minSSIVal</p>";
	#foreach ($arr as $key => $valwin){
	#	echo "Valarray $key = $valwin<br>";
	#}
	# // now get the playerslist cycle with an INSERT into tblstat on $vdate2
	$pqry=sql_query("select pid,pfname,plname,statval from tplayer,tblstat where pid=statpid and statdate='$vdate1' and statcode=2 order by pid",$dbi);
	while (list($pid,$pfname,$plname,$sippoints)=sql_fetch_row($pqry,$dbi)){

		if ($arr[$pid] <> 0) {
			# // Rule 1 any player can not gain or loose more than 200 points
			if ($arr[$pid]>200) $arr[$pid]=200;
			if ($arr[$pid]<-200) $arr[$pid]=-200;
			# // get new value from $arr array and add to existing value from last period
			# // Rule 2 SSI can not get lower than 600 !!! //
			$newval=($sippoints+$arr[$pid]);
			if ($newval<$minSSIVal) $newval=$minSSIVal;
			echo "$greenpic<b> NEW SSI for $pfname $plname: adding ".number_format($arr[$pid],2,'.','')." pt to $sippoints for $vdate1/$vdate2 = $newval</b></br>";
			#//echo "insert into tblstat values(0,'$vdate2',".($sippoints+$arr[$pid]).",$pid)<br>";
			$qry="insert into tblstat(statid,statdate,statcode,statval,statpid) values(0,'$vdate2',2,$newval,$pid)";
			$res=sql_query($qry,$dbi);
			dsolog(2,$user,"<b>SSI UPDATE</b> $pfname $plname = $newval");
		} else {
			echo "$redpic<i>NO SSI update for $pfname $plname Points: ".number_format($arr[$pid],2,'.','')."</i></br>";
			#//echo "insert into tblstat values(0,'$vdate2',".$sippoints.",$pid)<br>";
			$qry="insert into tblstat(statid,statdate,statcode,statval,statpid) values(0,'$vdate2',2,$sippoints,$pid)";
			$res=sql_query($qry,$dbi);
			dsolog(1,$user,"<b>NO SSI UPDATE</b> $pfname $plname = $sippoints");
		}
		# // TOGGLE the SSI status field for this player case 0, -6, +6
		# // this is used to know how actual the current SSI value is
		switch ($gamecount[$pid]){
			case 0:
				$res=sql_query("update tplayer set psipstatus=0 where pid=$pid",$dbi);
				break;
			case 1:
			case 2:
			case 3:
			case 4:
			case 5:
				$res=sql_query("update tplayer set psipstatus=1 where pid=$pid",$dbi);
				break;
			default:
				$res=sql_query("update tplayer set psipstatus=2 where pid=$pid",$dbi);
				break;
		} # // end switch status toggle
	}
}

/**
*	purpose:	this returns the FEDA Index numbers on a leg basis
* 	params:		$distance = 301/501/701
* 				if $checkround empty than $checkround=$scoreround
* 				if $rest is 0 than $score=$distance-158
*	returns:	string "scorezahl:checkzahl:gesamt"
* 				-1 if scoreIDX shall not count
*/
function retFEDAIndexZahlperLeg($distance,$scoreround,$rest,$checkround,$iTEST=0){
	#
	# v01 BH 8.2006 tested OK
	# v02 introduced iTEST as a flag for different math algorithms....
	
	/*
	 * TODO v5 New class Usage ... calc tested ok 
	 * -> replace return by array instead of serial string
	 */
	#$cF=new cFedaStat($scoreround,$checkround,$rest);
	#debug($cF->calculateIDX());
	
	$checkzone=159;
	$fedaidx="";
	
	# sanity checks ...
	if ($checkround<$scoreround) return "-1:-1:-1";
	if ($checkround<3) return "-1:-1:-1";
	
	#-----------------------------------
	# BLOCK SCOREINDEX
	# comment: there is always a scoreindex !!
	#-----------------------------------
	$scoreIDX=0;
	
	/* new style short code ...*/
	if($rest > 158){
			$scoreIDX=(501-$rest)/$scoreround;
	} else {	
			if ($checkround==$scoreround){	# same round -> direct calculation
				$scoreIDX=(501-$rest)/$scoreround;
			} else {			# -> 159 assumption if rounds different ...
				$scoreIDX=(501-158)/$scoreround;
			}
	}
	$scoreIDX=1.5*100*$scoreIDX;
		
	#-----------------------------------
	# BLOCK CHECKINDEX
	# comment: can be zero ...
	#-----------------------------------
	$checkIDX=0;
	
	if ($rest==0){
		$checkIDX=100*(100-5*($checkround-$scoreround));
		if ($checkIDX <= 0) $checkIDX=1;
	}else{
		$checkIDX=0;
		# special cases / special rules ...
		# case $scoreround=$checkround player either above 159 or no turn to throw
		if ($checkround==$scoreround) $checkIDX = -1;
		# override in TESTMode
		if ($iTEST==1) $checkIDX=0;
	}
	
	# return + rundung ##.##
	$fedaidx=number_format($scoreIDX,2,'.','').":".number_format($checkIDX,2,'.','').":".number_format(($scoreIDX+$checkIDX),2,'.','');
	return $fedaidx;
}

/**
*	purpose:	we use a dynamic dependend on the current SSI value
* 				lower SSI is higher dynamic than the good ones,the dynamic factors are stored in tsipdynamic
* 	params:		current SSI value (numeric)
*	returns:	integer
*/
function RetDynamic($vsipcurrent) {
	global $dbi;
	$vdyn=120;
	$trecords=sql_query("select tdpoints,tfactor from tsipdynamic order by tdpoints asc",$dbi);
	while (list($tdpoints,$tfactor)=sql_fetch_row($trecords,$dbi)) {
		if ($vsipcurrent > $tdpoints) $vdyn=$tfactor;
	}
	return $vdyn;
}

function ReturnSSIChangePlayer($ssiplayer,$siiopponent,$WinLost){
	# // Diese Funktion errechnet das SSI Punkte Delta fr SPIELER gegen OPPONENT
	# // a)	errechnung der siegwahrscheinlichkeit based on ($ssiplayer - $siiopponent)
	# //	und Umsetzung in einen konkreten SSI Wert
	# // b)	relativierung dieses konkreten wertes entsprechend der SSI-Dynamic Tabelle
	# // c) wenn der realtivierte wert einen schwellwert nicht bersteigt dann wird dies
	# //	als insignifikant gewertet und der SSIChange auf 0 gesetzt.
	# // $WinLost = 1 : return SSI change for a WIN
	# // $WinLost = 0 : return SSI change for a LOST
	# /////////////////////////////////////////////////////////////////////////////////////
	
	$consDYN=50;				#// dynamic konstante (1/4 des postulierten Klassenunterschiedes von 200)
						#// enspricht einer Gewinnrate von 3:1 = 75% Gewinnwahrscheinlichkeit
	$dynSSI=RetDynamic($ssiplayer);		# // mit welcher SSI dynamic ist SPIELER belegt (120 - 50)
	$diffSSI=$ssiplayer-$siiopponent;	# // calculate diff in SSI
		
		if ($diffSSI<0){
			$expA=pow(0.5,(-($diffSSI)/($consDYN*4)+1));	# // expectancy of win
			$valAwin=$consDYN*(1-$expA);			# // SSI change from expectancy
		} else {
			$expA=1-(pow(0.5,($diffSSI/($consDYN*4)+1)));
			$valAwin=$consDYN*(1-$expA);
		}

	$valAwin=($valAwin/100)*$dynSSI;	# // apply dynamic factor
	$valALost=($consDYN/100)*$dynSSI-$valAwin;
	if ($valAwin < 4) $valAwin=0;		# // apply significance factor
	if ($valALost < 5) $valALost=0;	# // apply significance factor
	if ($WinLost == 1) return $valAwin;
	if ($WinLost == 0) return $valALost;
}

function ReturnWinExpectancy($ssiplayer,$siiopponent){
	# // Diese Funktion errechnet die Gewinnwahrscheinlichkeit
	# // a)	errechnung der Gewinnwahrscheinlichkeit based on ($ssiplayer - $siiopponent)
	# //
	# /////////////////////////////////////////////////////////////////////////////////////
	
	$consDYN=50;				#// dynamic konstante (1/4 des postulierten Klassenunterschiedes von 200)
						#// enspricht einer Gewinnrate von 3:1 = 75% Gewinnwahrscheinlichkeit
	$dynSSI=RetDynamic($ssiplayer);		# // mit welcher SSI dynamic ist SPIELER belegt (120 - 50)
	$diffSSI=$ssiplayer-$siiopponent;	# // calculate diff in SSI
		
		if ($diffSSI<0){
			$expA=pow(0.5,(-($diffSSI)/($consDYN*4)+1));	# // expectancy of win
		} else{
			$expA=1-(pow(0.5,($diffSSI/($consDYN*4)+1)));
		}

	$expA=$expA*100;		#// expA is always between 0 and 1
	return $expA;
}

/**
*	purpose	Returns GamePoints for Player, either cummulated as record or detailed as RecordSet
*	params		Event_class, Player,detail=0/1
*	returns		integer : sum of points
* 						Recordset(x) player,game,points
*/
function lsdb_stat_ReturnGamePointsForPlayer($DB,$event_class,$player_id,$detail=0){
	$sumpoints=0;
	$points=0;

	// generate detailed gamedata + points
	$RS=DB_retStatQueryArrayDetail($DB,$player_id,$event_class);
	$GDATA=array();
	foreach($RS as $aGRec){
		$GD=DB_getGame($DB,$aGRec[1]);
		// debug($GD);
		// stuff is paired in GD ... => pass to calculation function 
		$aGP=lsdb_stat_ReturnGamePoints($GD);
		// debug($aGP);
		// fetch correct data from return array and slip into GDATA
		if ($aGP[0][0]==$player_id){
			$points=$aGP[0][1];
			$sumpoints=$sumpoints+$points;
		}elseif($aGP[1][0]==$player_id){
			$points=$aGP[1][1];
			$sumpoints=$sumpoints+$points;
		}
		$aGRec[10]=$points;		// attach field=with points
		$GDATA[]=$aGRec;
	}
	/*
	 * According to DETAIL=0/1 pass back summary RS or detail RS
	 */
	if ($detail==0){
		// generate summary row RS
		return $sumpoints;
	}elseif ($detail==1){
		return $GDATA;
	}
}

/**
*	purpose	Calculate and return the pid+Points for a GameRecord 
* 						Basically this is coded for Losan 7,6,3,0 points in best of 3 games
*	params		array(GameRecord as generated by api_rs)
*	returns		array (pid+points)
*/
function lsdb_stat_ReturnGamePoints($aGame=array()){
	// structure of aGame
	// G.gid,G.gtype,GP.gpid,GP.gpsetwon,GP.gplegswon,GP.gptid,P.pid,P.pfname,P.plname, ....dep on legdetails
	$aRET[0]=array(0,0);
	// straight switch statement based on the legres of player 0
	$legsfirstp=$aGame[0][4];
	$legssecondp=$aGame[1][4];
	// debug($legsfirstp.':'.$legssecondp);
	switch($legsfirstp){
		case 0:
			$aRET[0]=array($aGame[0][6],0);
			$aRET[1]=array($aGame[1][6],7);
			break;
		case 1:
			$aRET[0]=array($aGame[0][6],3);
			$aRET[1]=array($aGame[1][6],6);
			break;
		case 2:
			if ($legssecondp==0){
				$aRET[0]=array($aGame[0][6],7);
				$aRET[1]=array($aGame[1][6],0);
			} elseif ($legssecondp==1){
				$aRET[0]=array($aGame[0][6],6);
				$aRET[1]=array($aGame[1][6],3);
			}
			break;
	}
	// make sure its containing 2 objects
	if (sizeof($aRET)<2){
		debug('Error in lsdb_stat_ReturnGamePoints.');
	}
	return $aRET;
}
?>
