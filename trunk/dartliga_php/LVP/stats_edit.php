<?php
    /*
     * LSDB Statistic List Editor
     * Quick Version, mostly self contained, because this is a backport
     * uses the ajay-snippets and controller modell v4
     */
include("stats_main.php");
if ($usertoken['usertype_id']<4) die("<h3>No access to the Statistics Editor ...</h3>");

echo "<script type=\"text/javascript\" src=\"code/statsedit.js\"></script>";

function _editStat(){
	echo "<h3>Statistic Value Editor</h3>";
	echo "<p></p>";
	
	echo include("forms/statentry.php");
	echo "<p></p><table width=400px><tr><td width=120px></td><td>"._button("Save","statvalsubmit()")."</td></tr></table>";
	
	
}

_editStat();

# just in case we close main div
echo '</div>';
LS_page_end();
?>
