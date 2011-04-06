<?php
/**
 * Releases anonymizer
 * @license GNU GPLv3 http://opensource.org/licenses/gpl-3.0.html
 * @package Kinokpk.com releaser
 * @author ZonD80 <admin@kinokpk.com>
 * @copyright (C) 2008-now, ZonD80, Germany, TorrentsBook.com
 * @link http://dev.kinokpk.com
 */


require "include/bittorrent.php";
INIT();

if ((@strpos($_SERVER['HTTP_REFERER'],"edit.php") === false) || !is_numeric($_GET['id'])) die ($REL_LANG->say_by_key('wrong_id'));
$id = $_GET['id'];
$curowner = sql_query("SELECT owner FROM torrents WHERE id = ".$id);
$curowner = mysql_result($curowner,0);

headers(REL_AJAX);

if ($curowner != 0) {
	sql_query("UPDATE torrents SET owner=0, orig_owner = ".$curowner." WHERE id = ".$id);

	$REL_CACHE->clearGroupCache('block-indextorrents');
	print('<html>
<head>
<title>'.$REL_LANG->say_by_key('anonymous_release').'</title>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
</head>
<body>
'.$REL_LANG->say_by_key('release_anonymous').'<br />
[<a href="javascript:self.close()">'.$REL_LANG->say_by_key('close_window').'</a>]
</body>
</html>');
}
elseif ($curowner == 0) {
	$origowner = sql_query("SELECT torrents.orig_owner AS id, users.username  FROM torrents LEFT JOIN users ON torrents.orig_owner = users.id WHERE torrents.id =".$id);
	$origowner = mysql_fetch_array($origowner);
	sql_query("UPDATE torrents SET owner = ".$origowner['id'].", orig_owner = 0 WHERE id = ".$id);


	$REL_CACHE->clearGroupCache('block-indextorrents');
	print('<html>
<head>
<title>'.$REL_LANG->say_by_key('make_anonymous').'</title>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
</head>
<body>
'.$REL_LANG->say_by_key('owner_release').' '.$origowner['username'].' '.$REL_LANG->say_by_key('restored').'<br />
[<a href="javascript:self.close()">'.$REL_LANG->say_by_key('close_window').'</a>]
</body>
</html>');
}
?>
