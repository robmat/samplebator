<?php
include('empty_main.php');

if (sizeof($usertoken['msgmap']) < 1 && $usertoken['usertype_id']<6) die_red('No access to the Message SubSystem');
#debug($usertoken['msgmap']);

/*
 * common pageobjects
 */
$buttons='<table><tr><td>'._button('Create','msgtab(1)').'</td><td>'._button('Inbox','msgtab(2)').'</td><td>'._button('Outbox','msgtab(3)').'</td></tr></table><hr/>';
echo '<script type="text/javascript" src="code/lsdbmail.js"></script>';
echo '</div>';

echo setPageTitle('Message Center');
echo setPageControlTabs($buttons);
LS_page_end();

?>
