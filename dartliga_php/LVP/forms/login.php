<?php
    $OUT="<table border=\"0\"><tr><td>"
			."Benutzername:</td><td><input type=\"text\" name=\"uname\" size=\"15\" maxlength=\"25\"></td></tr>"
			."<tr><td>Passwort:</td><td><input type=\"password\" name=\"pass\" size=\"15\" maxlength=\"20\"></td></tr>"
			."<tr><td><input type=\"hidden\" name=\"op\" value=\"login\"></td>"
			."<td><input type=\"submit\" value=\"Anmelden\"></td></tr>";
	return $OUT."</table>";
?>
