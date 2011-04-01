<?php
    $OUT="";
	$OUT="<table cellspacing=2>";
	$OUT=$OUT."<tr><td class=\"bluebox\">Player</td><td id=\"select_player\" name=\"select_player\">".Select_Player('spid',0)."</td></tr>";
	$OUT=$OUT."<tr><td class=\"bluebox\">Statistik Gruppe</td><td id=\"select_statcode\" name=\"select_statcode\">".Select_StatGroup('scode',3,'getdates(this)')."</td></tr>";
	$OUT=$OUT."<tr><td class=\"bluebox\">Stichtag</td><td id=\"select_statdate\" name=\"select_statdate\">".Select_StatDate(0,'','sdate')."</td></tr>";
	$OUT=$OUT."<tr><td class=\"bluebox\">Value</td><td id=\"input_statval\" name=\"input_statval\">"._input(1,'sval','',8,8)."</td></tr>";
	$OUT=$OUT."<tr><td class=\"bluebox\">Anzahl Legs</td><td id=\"input_statlegs\" name=\"input_statlegs\">"._input(1,'slegs','',4,4)."</td></tr>";
	$OUT=$OUT."<tr><td class=\"bluebox\">Anzahl Sets</td><td id=\"input_statsets\" name=\"input_statsets\">"._input(1,'ssets','',4,4)."</td></tr>";
	$OUT=$OUT."</table>";
	return $OUT;
?>
