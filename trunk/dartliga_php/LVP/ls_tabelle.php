<?php

# //
# // super public page - show TABELLE for selected event
# //
# // change 6.2007 BH:: added button and funtion for showing all standings
# //			of the current league group
# //

include("ls_main.php");

# // Beginn Funktionen ----------------

if (!$event_id>0) die ("Sorry ... lost track of Event or League, please select again ...");

$tdbg="#CCDDCC";
$tdWon="#ccffcc";
$tdLost="#ffcccc";
$thisfile="ls_tabelle.php";

function _tabellenav($eventid){
	$OUT='<div style="width:250px">';
	$OUT=$OUT._button('Alle Tabellen dieser Liga Gruppe','','ls_tabelle.php?func=entiregroup&eventid='.$eventid);
	return $OUT.'</div>';
}

function _blank($eventid){
	/*
	 * This lists the Tabelle for a specific league
	 * some data interpretation needed due to -> WIN/LOST/EVEN and the numup/numdown
	 */
	global $dbi,$event,$greenpic,$redpic;
	
	$numup=$event['evnumup'];
	$numdown=$event['evnumdown'];
	// Get Number of Team to descend (numTeams - NumDown +1)
	$p=sql_query("select count(*) NumTeams from tblteam T where T.tevent_id=$eventid",$dbi);
	$ans=sql_fetch_array($p,$dbi);
	$NumTeams=$ans['NumTeams'];
	$teamdown=$NumTeams-$numdown;
	
	
	echo setPageTitle('Tabelle '.$event['evname'].' Saison '.$event['evyear']);
	echo _tabellenav($eventid);
	
	OpenTable();
	
	# SETS based standings
	if ($event['evtabsets']==1){
		$aTH=array('','Team Name','Matches','S','U','V','SETS +','SETS -','LEGS +','LEGS -','');
		$strsql="select T.id,T.tname,sum(mtsets) SETSW,sum(mtsetslost) SETSL,sum(mtsets)-sum(mtsetslost) SDIFF,sum(mtlegs) LEGSW,sum(mtlegslost) LEGSL,sum(mtlegs)-sum(mtlegslost) LDIFF, count(mstatus) CT from tblmatchteam,tblmatch,tblteam T where mttid=T.id and mtmkey=mkey and mstatus<>0 and T.tevent_id=$eventid group by mttid order by SDIFF desc,LDIFF desc";
	}
	# POINTS based standings 
	if ($event['evtabpoints']==1){
		$aTH=array("","Team Name","Matches","S","U","V","SETS +","SETS -","LEGS +","LEGS -","POINTS");
		$strsql="select T.id,T.tname,sum(mtsets) SETSW,sum(mtsetslost) SETSL,sum(mtsets)-sum(mtsetslost) SDIFF,sum(mtlegs) LEGSW,sum(mtlegslost) LEGSL,sum(mtlegs)-sum(mtlegslost) LDIFF, count(mstatus) CT,sum(mtpoints) PT from tblmatchteam,tblmatch,tblteam T where mttid=T.id and mtmkey=mkey and mstatus<>0 and T.tevent_id=$eventid group by mttid order by PT desc,SDIFF desc,LDIFF desc";
	}
	
	$prec=sql_query($strsql,$dbi);
	
	echo ArrayToTableHead($aTH);
	echo '<tr height="5px"></tr>';
	#
	# each row is a link to ls_stats -> showresteam
	#
	$c=0;
	while(list($tid,$tname,$SETSW,$SETSL,$SDIFF,$LEGSW,$LEGSL,$LDIFF,$CT,$PT)=sql_fetch_row($prec,$dbi)){
		$c=$c+1;
		# JUMP to a Team Stats Page
		#$target='ls_stats.php?func=showresteam&eventid='.$eventid.'&tid='.$tid;
		#$TR='<tr bgcolor=\'white\' onclick=(document.location=\''.$target.'\') onMouseOver=(mover(this)) onMouseOut=(mout(this))>';
		$TR='<tr bgcolor="white">';
		#
		if ($c<($numup+1)){
			$TR=$TR.'<td>'.$greenpic.'</td>';
		} elseif ($c>$teamdown) {
			$TR=$TR.'<td>'.$redpic.'</td>';
		} else { // default
			$TR=$TR.'<td></td>';
		}
		
		$TR=$TR.'<td>'.$tname.'</td><td>'.$CT.'</td>';
		
		# GET THE WON and EVEN counters by summarizing entries per team .... (sub select !!!!)
		$resW='select count(MT.mtpoints) CNT from tblteam T left join tblmatchteam MT on T.id=MT.mttid left join tblmatch M on MT.mtmkey=M.mkey WHERE T.id='.$tid.' and M.mstatus<>0 AND MT.mtpoints='.$event['evpointswin'];
		$resE='select count(MT.mtpoints) CNT from tblteam T left join tblmatchteam MT on T.id=MT.mttid left join tblmatch M on MT.mtmkey=M.mkey WHERE T.id='.$tid.' and M.mstatus<>0 AND MT.mtpoints='.$event['evpointseven'];
		$precW=sql_query($resW,$dbi);
		$cntW=sql_fetch_array($precW,$dbi);
		$precE=sql_query($resE,$dbi);
		$cntE=sql_fetch_array($precE,$dbi);
		$TR=$TR.'<td>'.$cntW['CNT'].'</td><td>'.$cntE['CNT'].'</td><td>'.($CT-($cntW['CNT']+$cntE['CNT'])).'</td>';
		$TR=$TR.'<td>'.$SETSW.'</td><td>'.$SETSL.'</td><td>'.$LEGSW.'</td><td>'.$LEGSL.'</td><td>'.$PT.'</td></tr>'; 
		echo $TR.'<tr height=1px></tr>';
	}
	
	CloseTable();
	echo '</div>';
}

function _showallgroup($eventid){
	// this function returns the standings for the entire group a selected event is member of
	
	global $dbi,$event,$tdbg;

	$evgrp=$event['evtypecode_id'];
	$ES=DB_listEvents($dbi,1,0,'',$evgrp);
	$aTH=array('Team','Set+','Set-','Diff','Leg+','Leg-','Diff','Matches','Points');
	
	echo setPageTitle('Alle Tabellen der Liga Gruppe '.$event['typdesc']);
	echo _tabellenav($eventid);
	
	OpenTable();
	foreach ($ES as $ev){
		echo '<tr><td colspan="9" bgcolor="'.$tdbg.'">'.$ev[1].'</td></tr>';
		echo ArrayToTableHead($aTH);
		// this is for point-standings
		if ($event['evtabpoints']==1){
			$RS=DB_getTabelle($dbi,$ev[0],0,'point','small');
			}
		// this is for the set-standings
		if ($event['evtabsets']==1){
			$RS=DB_getTabelle($dbi,$ev[0],0,'set','small');
			}
		//$fields=array(4,5,6,7,8,9,10,11,12);
		//$ROWS=RecordsetToDataTable($RS,$fields);
		$target='';
		$ROWS=RecordsetToClickTable($RS,4,$target,1,3);
		echo $ROWS;	
	}
	CloseTable();
	echo '</div>';
}

if (isset($_REQUEST['func'])&& $_REQUEST['func']<>"undefined") {$myfunc=strip_tags($_REQUEST['func']);}else{$myfunc='NULL';};
/*
 * common page elements are render4ed by the 
 */
switch($myfunc) {

	case 'entiregroup':
	_showallgroup($event_id);
	break;

	default:
	_blank($event_id);
	break;
	
}

# just in case we close main div
echo '</div>';
LS_page_end();

?>
