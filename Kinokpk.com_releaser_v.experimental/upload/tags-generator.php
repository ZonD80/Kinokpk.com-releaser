<?php
/**
 * Tags generator
 * @license GNU GPLv3 http://opensource.org/licenses/gpl-3.0.html
 * @package Kinokpk.com releaser
 * @author ZonD80 <admin@kinokpk.com>
 * @copyright (C) 2008-now, ZonD80, Germany, TorrentsBook.com
 * @link http://dev.kinokpk.com
 */

require_once("include/bittorrent.php");

INIT();


loggedinorreturn();

get_privilege('upload_releases');

$result = array();
$names = array();

$tag = htmlspecialchars((string)$_POST['q']);
$res = $REL_DB->query_return("SELECT tags FROM torrents WHERE tags LIKE '%" . $REL_DB->sqlwildcardesc($tag) . "%'");


if ($res) {
    foreach ($res as $row) {
        $tags = explode(',', $row['tags']);
        foreach ($tags as $row) {
            if (preg_match("#$tag#si", $row) && !in_array($row, $names)) {
                $result[]['name'] = $row;
                $names[] = $row;
            }

        }
    }
}

print json_encode($result);

?>