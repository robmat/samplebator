<?php
/**
*	purpose:	email formular (from / to / msg)
* 	params:		$obj=array()
*	returns:	html formular
*/
    $OUT="<table border=\"0\">"
			."<tr><td></td><td>"._input(0,"op","send")."</td></tr>"
    		."<tr><td>Sender:</td><td>"._input(1,"mailfrom",$obj['mailfrom'],50,50)."</td></tr>"
			."<tr><td>Recipient:</td><td>".Select_MessageGroup('mailgrp',$obj['mailgrp'])."</td></tr>"
			."<tr><td>Message:</td><td>"._input(3,"mailmsg",$obj['mailmsg'],50)."</td></tr>"
			."<tr><td>Sys URL:</td><td>"._input(1,"sysurl",$obj['sysurl'],50,50)."</td></tr>";
			return $OUT."</table>";
?>
