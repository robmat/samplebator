<?php

######################################################################
# PARTS OF THIS SHAMELESSLY RIPPED FROM THE PHPBB project
# BH: entire file not needed in LSDB v3 onwards
#
######################################################################

$functions = 1;
$modules_name = "Forum";
include ("config.php");

function new_session($userid, $remote_ip, $lifespan, $db) {

        mt_srand((double)microtime()*1000000);
        $sessid = mt_rand();

        $currtime = (string) (time());
        $expirytime = (string) (time() - $lifespan);

        $deleteSQL = "DELETE FROM sessions WHERE (start_time < $expirytime)";
        $delresult = mysql_query($deleteSQL, $db);

        if (!$delresult) {
                die("Delete failed in new_session()");
        }

        $sql = "INSERT INTO sessions (sess_id, user_id, start_time, remote_ip) VALUES ($sessid, $userid, $currtime, '$remote_ip')";

        $result = mysql_query($sql, $db);

        if ($result) {
                return $sessid;
        } else {
        	echo mysql_errno().": ".mysql_error()."<BR>";
                die("Insert failed in new_session()");
        } 

} 

/**
 * Sets the sessID cookie for the given session ID. the $cookietime parameter
 * is no longer used, but just hasn't been removed yet. It'll break all the modules
 * (just login) that call this code when it gets removed.
 * Sets a cookie with no specified expiry time. This makes the cookie last until the
 * user's browser is closed. (at last that's the case in IE5 and NS4.7.. Haven't tried
 * it with anything else.)
 */
function set_session_cookie($sessid, $cookietime, $cookiename, $cookiepath, $cookiedomain, $cookiesecure) {

        // This sets a cookie that will persist until the user closes their browser window.
        // since session expiry is handled on the server-side, cookie expiry time isn't a big deal.
        setcookie($cookiename,$sessid,'',$cookiepath,$cookiedomain,$cookiesecure);

} // set_session_cookie()


/**
 * Returns the userID associated with the given session, based on
 * the given session lifespan $cookietime and the given remote IP
 * address. If no match found, returns 0.
 */
function get_userid_from_session($sessid, $cookietime, $remote_ip, $db) {

        $mintime = time() - $cookietime;
        $sql = "SELECT user_id FROM sessions WHERE (sess_id = $sessid) AND (start_time > $mintime) AND (remote_ip = '$remote_ip')";
        $result = mysql_query($sql, $db);
        if (!$result) {
                echo mysql_error() . "<br>\n";
                die("Error doing DB query in get_userid_from_session()");
        }
        $row = mysql_fetch_array($result);

        if (!$row) {
                return 0;
        } else {
                return $row[user_id];
        }

} // get_userid_from_session()

/**
 * Refresh the start_time of the given session in the database.
 * This is called whenever a page is hit by a user with a valid session.
 */
function update_session_time($sessid, $db) {

        $newtime = (string) time();
        $sql = "UPDATE sessions SET start_time=$newtime WHERE (sess_id = $sessid)";
        $result = mysql_query($sql, $db);
        if (!$result) {
                echo mysql_error() . "<br>\n";
                die("Error doing DB update in update_session_time()");
        }
        return 1;

} // update_session_time()

/**
 * Delete the given session from the database. Used by the logout page.
 */
function end_user_session($userid, $db) {

        $sql = "DELETE FROM sessions WHERE (user_id = $userid)";
        $result = mysql_query($sql, $db);
        if (!$result) {
                echo mysql_error() . "<br>\n";
                die("Delete failed in end_user_session()");
        }
        return 1;

} // end_session()

/**
 * Prints either "logged in as [username]. Log out." or
 * "Not logged in. Log in.", depending on the value of
 * $user_logged_in.
 */
function print_login_status($user_logged_in, $username, $url_phpbb) {
        global $l_loggedinas, $l_notloggedin, $l_logout, $l_login;

        if($user_logged_in) {
                echo "<b>$l_loggedinas $username. <a href=\"$url_phpbb/logout.php\">$l_logout.</a></b><br>\n";
        } else {
                echo "<b>$l_notloggedin. <a href=\"$url_phpbb/login.php\">$l_login.</a></b><br>\n";
        }
} // print_login_status()


/**
 * End session-management functions
 */



function escape_slashes($input)
{
        $output = str_replace('/', '\/', $input);
        return $output;
}

function undo_htmlspecialchars($input) {
        $input = preg_replace("/&gt;/i", ">", $input);
        $input = preg_replace("/&lt;/i", "<", $input);
        $input = preg_replace("/&quot;/i", "\"", $input);
        $input = preg_replace("/&amp;/i", "&", $input);

        return $input;
}





/**
 * Less agressive version of stripslashes. Only replaces \\ \' and \"
 * The PHP stripslashes() also removed single backslashes from the string.
 * Expects a string or array as an argument.
 * Returns the result.
 */
function own_stripslashes($string)
{
   $find = array(
            '/\\\\\'/',  // \\\'
            '/\\\\/',    // \\
                                '/\\\'/',    // \'
            '/\\\"/');   // \"
   $replace = array(
            '\'',   // \
            '\\',   // \
            '\'',   // '
            '"');   // "
   return preg_replace($find, $replace, $string);
}

?>
