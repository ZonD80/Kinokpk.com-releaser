<?php
/**
 * Enermergency login to site
 * @license GNU GPLv3 http://opensource.org/licenses/gpl-3.0.html
 * @package Kinokpk.com releaser
 * @author ZonD80 <admin@kinokpk.com>
 * @copyright (C) 2008-now, ZonD80, Germany, TorrentsBook.com
 * @link http://dev.kinokpk.com
 * @todo rewrite with class system
 */
require_once("include/bittorrent.php");
INIT();

die('need to be rewrited');

function bark($text)
{
    print("<title>" . $REL_LANG->say_by_key('error') . "!</title>");
    print("<table width='100%' height='100%' style='border: 8px ridge #FF0000'><tr><td align='center'>");
    print("<center><h1 style='color: #CC3300;'>" . $REL_LANG->say_by_key('error') . ":</h1><h2>$text</h2></center>");
    print("<center><INPUT TYPE='button' VALUE='" . $REL_LANG->say_by_key('back') . "' onClick=\"history.go(-1)\"></center>");
    print("</td></tr></table>");
    die;
}

if (!$_POST['username'] or !$_POST['password'])
    bark($REL_LANG->say_by_key('not_spec'));

$res = $REL_DB->query("SELECT id, passhash, secret, enabled, confirmed FROM users WHERE username = " . sqlesc($username));
$row = mysql_fetch_array($res);

if (!$row)
    bark($REL_LANG->say_by_key('you_not_logged'));

if (!$row["confirmed"])
    bark($REL_LANG->say_by_key('not_act_account'));

if ($row["passhash"] != md5($row["secret"] . $password . $row["secret"]))
    bark($REL_LANG->say_by_key('incorrect'));

if (!$row["enabled"])
    bark($REL_LANG->say_by_key('this_acc_disabled'));

logincookie($row["id"], $row["passhash"], $row['language']);
header("Refresh: 0; url='{$REL_CONFIG['defaultbaseurl']}'");
?>