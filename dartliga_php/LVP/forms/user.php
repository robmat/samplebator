<?php
   $OUT="<table border=\"0\">"
			."<tr><td>e-mail f&uuml;r system nachrichten:</td><td>"
			._input(1,"newemail",$useremail,20,50)."</td></tr>"			
			."<tr><td>Neues Passwort:</td><td>"
			._input(1,"newpass1")."</td></tr>"
			."<tr><td>Neues Passwort (Best&auml;tigung):</td><td>"
			._input(1,"newpass2")."</td></tr>"
			."<tr><td>"._input(0,"op","chgpwd")."</td><td>"
			."<input type=\"submit\" value=\"&Auml;ndern\"></td></tr>";
	return $OUT."</table>";
?>
