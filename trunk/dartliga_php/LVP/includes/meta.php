<?php

if (eregi("meta.php",$_SERVER['PHP_SELF'])) {
    Header("Location: ../index.php");
    die();
}

echo "<META HTTP-EQUIV=\"Content-Type\" content=\"text/html; charset=ISO-8859-1\">\n";
echo "<META HTTP-EQUIV=\"EXPIRES\" CONTENT=\"0\">\n";
echo "<META NAME=\"RESOURCE-TYPE\" CONTENT=\"DOCUMENT\">\n";
echo "<META NAME=\"DISTRIBUTION\" CONTENT=\"GLOBAL\">\n";
echo "<META NAME=\"AUTHOR\" CONTENT=\"Hristovski\">\n";
echo "<META NAME=\"COPYRIGHT\" CONTENT=\"Copyright (c) 2002-08 by Austrian Darts Federation (Hristovski)\">\n";
echo "<META NAME=\"KEYWORDS\" CONTENT=\"League System,Rechenzentrum,&Ouml;DV,OEDSO,WDF,Austria\">\n";
echo "<META NAME=\"DESCRIPTION\" CONTENT=\"Liga System Application Austrian Darts Federation &Ouml;DV\">\n";
echo "<META NAME=\"ROBOTS\" CONTENT=\"INDEX, FOLLOW\">\n";

# Do not remove the following line!
echo "<META NAME=\"GENERATOR\" CONTENT=\"LSDB System 3.5\">\n";

?>