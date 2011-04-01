<?php
	$OUT="<table cellspacing=2>";
	$OUT=$OUT."<tr><td class=\"bluebox\">ID</td><td>"._input(1,"vfid","",5,5)."</td></tr>"
	."<tr><td class=\"bluebox\">Datum <i>(YYYY-MM-DD)</i></td><td>"._input(1,"vfdate","",12,12)."</td></tr>"
	."<tr><td class=\"bluebox\">Kommentar</td><td>"._input(1,"vfcomment","",30,30)."</td></tr>"
	."<tr><td class=\"bluebox\">Statistik Gruppe</td><td>".Select_StatGroup('vftype')."</td></tr>";

	$OUT=$OUT."<tr><td></td><td></td><td></td></tr>";
	 
	$OUT=$OUT."<tr><td></td><td>"._button("Save")."</td>";
	$OUT=$OUT."<td>"._button("Delete","deleteDate()")."</td>";
	$OUT=$OUT."<td>"._button("Clear","clearDateDetails()")."</td></tr>";
	return $OUT."</table>";
?>
