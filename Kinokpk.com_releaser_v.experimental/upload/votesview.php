<?php
/**
 * View votes for requests
 * @license GNU GPLv3 http://opensource.org/licenses/gpl-3.0.html
 * @package Kinokpk.com releaser
 * @author ZonD80 <admin@kinokpk.com>
 * @copyright (C) 2008-now, ZonD80, Germany, TorrentsBook.com
 * @link http://dev.kinokpk.com
 */


require "include/bittorrent.php";

INIT();

loggedinorreturn();

if ($_GET[requestid]) {
    $requestid = (int)$_GET[requestid];
    $res2 = $REL_DB->query("SELECT SUM(1) FROM addedrequests INNER JOIN users ON addedrequests.userid = users.id INNER JOIN requests ON addedrequests.requestid = requests.id WHERE addedrequests.requestid = " . sqlesc($requestid) . " GROUP BY requests.id") or die(mysql_error());
    $row = mysql_fetch_array($res2);
    $count = $row[0];
    $perpage = 50;
    $limit = pager($perpage, $count, array('votesview'));
    $res = $REL_DB->query("SELECT users.id as userid,users.username, users.ratingsum, users.class, users.enabled, users.warned, users.donor requests.id as requestid, requests.request FROM addedrequests INNER JOIN users ON addedrequests.userid = users.id INNER JOIN requests ON addedrequests.requestid = requests.id WHERE addedrequests.requestid =$requestid $limit");
    $REL_TPL->stdhead($REL_LANG->_('Voted users'));
    $res2 = $REL_DB->query("SELECT request FROM requests WHERE id=$requestid");
    $arr2 = mysql_fetch_assoc($res2);

    print("<h1>{$REL_LANG->_('Voted for <a href="%s"><b>%s</b></a>',$REL_SEO->make_link('requests','id',$requestid),$arr2[request])}</h1>");
    print("<p>{$REL_LANG->_('<a href="%s">Vote for this request</a>',$REL_SEO->make_link('requests','action','vote','voteid',$requestid))}</p>");

    if (mysql_num_rows($res) == 0)
        print("<p align=center><b>{$REL_LANG->_('Nothing was found')}</b></p>\n");
    else {
        print("<table border=1 cellspacing=0 cellpadding=5>\n");
        print("<tr><td class=colhead>{$REL_LANG->_('User')}</td><td class=colhead>{$REL_LANG->_('Rating')}</td></tr>\n");
        while ($arr = mysql_fetch_assoc($res)) {
            $ratio = ratearea($arr['ratingsum'], $arr['userid'], 'users', $CURUSER['id']);
            $user = $arr;
            $user['id'] = $arr['userid'];
            print("<tr><td>" . make_user_link($user) . "</td><td nowrap>$ratio</td></tr>\n");
        }
        print("</table>\n");
    }
    $REL_TPL->stdfoot();
}


die("Direct access to this file not allowed.");

?>