<?

/*
 Project: Kinokpk.com releaser
 This file is part of Kinokpk.com releaser.
 Kinokpk.com releaser is based on TBDev,
 originally by RedBeard of TorrentBits, extensively modified by
 Gartenzwerg and Yuna Scatari.
 Kinokpk.com releaser is free software;
 you can redistribute it and/or modify
 it under the terms of the GNU General Public License as published by
 the Free Software Foundation; either version 2 of the License, or
 (at your option) any later version.
 Kinokpk.com is distributed in the hope that it will be useful,
 but WITHOUT ANY WARRANTY; without even the implied warranty of
 MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 GNU General Public License for more details.
 You should have received a copy of the GNU General Public License
 along with Kinokpk.com releaser; if not, write to the
 Free Software Foundation, Inc., 59 Temple Place, Suite 330, Boston,
 MA  02111-1307  USA
 Do not remove above lines!
 */

require_once("include/bittorrent.php");
INIT();

if (!is_valid_id($_GET["id"]))
stderr($REL_LANG->say_by_key('error'), $REL_LANG->say_by_key('invalid_id'));
$id = (int) $_GET["id"];
$email = urldecode($_GET['email']);



$res = sql_query("SELECT editsecret FROM users WHERE id = $id");
$row = mysql_fetch_array($res);

if (!$row)
stderr($REL_LANG->say_by_key('error'),$REL_LANG->say_by_key('no_user_id'));

$sec = hash_pad($row["editsecret"]);
if (preg_match('/^ *$/s', $sec))
stderr($REL_LANG->say_by_key('error'),$REL_LANG->say_by_key('error_calculating'));
if ($_GET['confirmcode'] != md5($sec . $email . $sec))
stderr($REL_LANG->say_by_key('error'),$REL_LANG->say_by_key('code_incorrect'));

sql_query("UPDATE users SET editsecret='', email=" . sqlesc(strtolower($email)) . " WHERE id = $id AND editsecret = " . sqlesc($row["editsecret"]));

if (!mysql_affected_rows())
stderr($REL_LANG->say_by_key('error'),$REL_LANG->say_by_key('error_change_address'));

safe_redirect($REL_SEO->make_link('my','emailch',1),0);


?>