<?php
/**
 * "Ask to reseed" processor
 * @license GNU GPLv3 http://opensource.org/licenses/gpl-3.0.html
 * @package Kinokpk.com releaser
 * @author ZonD80 <admin@kinokpk.com>
 * @copyright (C) 2008-now, ZonD80, Germany, TorrentsBook.com
 * @link http://dev.kinokpk.com
 */

require "include/bittorrent.php";

INIT();
loggedinorreturn();

$id = (int)$_GET["torrent"];

$res = $REL_DB->query("SELECT torrents.seeders, torrents.banned, torrents.leechers, torrents.name, torrents.id, torrents.hits, torrents.last_reseed AS lr FROM torrents WHERE torrents.id = $id");
$row = mysql_fetch_array($res);

if (!$row || $row["banned"])
    $REL_TPL->stderr($REL_LANG->say_by_key('error'), $REL_LANG->say_by_key('no_torrent_with_such_id'));

if ($row["hits"] == 0)
    $REL_TPL->stderr($REL_LANG->say_by_key('error'), $REL_LANG->_('Sorry, but this release was not downloaded by anybody yet'));

if ($row["leechers"] == 0)
    $REL_TPL->stderr($REL_LANG->say_by_key('error'), $REL_LANG->_('Sorry, but nobody is leeching this torrent now'));

$dt = TIME - 24 * 3600;

if ($row["lr"] > $dt && ($row["lr"]) != 0)
    $REL_TPL->stderr($REL_LANG->say_by_key('error'), $REL_LANG->_('Sorry, but your last request was within 24 hours. You can not do this now'));

$msgs_to = $REL_DB->query("SELECT userid FROM snatched WHERE torrent = $id AND userid != $CURUSER[id]");

while (list($receiver) = mysql_fetch_array($msgs_to)) {
    write_msg($CURUSER['id'], $receiver, $REL_LANG->_to($receiver, 'Help to seed "%s"', $row['name']), $REL_LANG->_to($receiver, 'Hi!<br/><br/>I ask you to help seed "<a href="%s"></a>".<br/>If you want to help, but deleted .torrent file, you can get it <a href="%s">here</a><br/><br/><i>Thank you</i>', $REL_SEO->make_link('details', 'id', $id, 'name', translit($row["name"])), $REL_SEO->make_link('download', 'id', $id, 'name', translit($row['name']))));
}
$REL_DB->query("UPDATE torrents SET last_reseed = " . TIME . " WHERE id = $id");

safe_redirect($REL_SEO->make_link('details', 'id', $id, 'name', translit($row['name'])), 2);

$REL_TPL->stdhead($REL_LANG->_('Call seeders for "%s"', $row['name']));

$REL_TPL->stdmsg($REL_LANG->_('Successfully'), $REL_LANG->_('Your request completed. Wait for seeders in next 24 hours'));

$REL_TPL->stdfoot();

?>