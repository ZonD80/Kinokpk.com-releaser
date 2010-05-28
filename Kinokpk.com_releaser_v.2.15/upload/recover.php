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

require "include/bittorrent.php";

dbconn();

if ($CACHEARRAY['use_integration']) header("Location: ".$CACHEARRAY['forumurl']."/index.php?act=Reg&CODE=10");

if ($_SERVER["REQUEST_METHOD"] == "POST")
{
  $email = trim($_POST["email"]);
  if (!$email)
    stderr($tracker_lang['error'], "�� ������ ������ email �����");
  $res = sql_query("SELECT * FROM users WHERE email=" . sqlesc($email) . " LIMIT 1") or sqlerr(__FILE__, __LINE__);
  $arr = mysql_fetch_array($res) or stderr($tracker_lang['error'], "Email ����� �� ������ � ���� ������.\n");

	$sec = mksecret();

  sql_query("UPDATE users SET editsecret=" . sqlesc($sec) . " WHERE id=" . $arr["id"]) or sqlerr(__FILE__, __LINE__);
  if (!mysql_affected_rows())
	  stderr($tracker_lang['error'], "������ ���� ������. ��������� � ��������������� ������������ ���� ������.");

  $hash = md5($sec . $email . $arr["passhash"] . $sec);

  $body = <<<EOD
��, ��� ���-�� ������, ��������� ����� ������ � �������� ��������� � ���� ������� ($email).

���� ��� ���� �� ��, �������������� ��� ������. ��������� �� ���������.

���� �� ������������� ���� ������, ��������� �� ��������� ������:

{$CACHEARRAY['defaultbaseurl']}/recover.php?confirm&id={$arr["id"]}&secret=$hash


����� ���� ��� �� ��� ��������, ��� ������ ����� ������� � ����� ������ ����� ��������� ��� �� E-Mail.

--
{$CACHEARRAY['sitename']}
EOD;

	if (mail($arr["email"], "{$CACHEARRAY['defaultbaseurl']} �������������� ������", wordwrap($body,70), "From: {$CACHEARRAY['siteemail']}")==false) stderr($tracker_lang['error'],"������ ��� �������� ������");
	
	stderr($tracker_lang['success'], "�������������� ������ ���� ����������.\n" .
		" ����� ��������� ����� (������ �����) ��� ������� ������ � ����������� ����������.");
}
elseif(isset($_GET['confirm']))
{
//	if (!preg_match(':^/(\d{1,10})/([\w]{32})/(.+)$:', $_SERVER["PATH_INFO"], $matches))
//	  httperr();

//	$id = 0 + $matches[1];
//	$md5 = $matches[2];

    	  if (!is_valid_id($_GET["id"]))
			stderr($tracker_lang['error'], $tracker_lang['invalid_id']);
			
	$id = 0 + $_GET["id"];
  $md5 = $_GET["secret"];

	$res = sql_query("SELECT username, email, passhash, editsecret FROM users WHERE id = $id");
	$arr = mysql_fetch_array($res) or stderr($tracker_lang['error'],"��� ������������ � ����� ID");

  $email = $arr["email"];

	$sec = hash_pad($arr["editsecret"]);
	if (preg_match('/^ *$/s', $sec))
   stderr($tracker_lang['error'],"������ ���������� ���� �������������");
	if ($md5 != md5($sec . $email . $arr["passhash"] . $sec))
   stderr($tracker_lang['error'],"��� ������������� �������");

	// generate new password;
	$chars = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789";

  $newpassword = "";
  for ($i = 0; $i < 10; $i++)
    $newpassword .= $chars[mt_rand(0, strlen($chars) - 1)];

 	$sec = mksecret();

  $newpasshash = md5($sec . $newpassword . $sec);

	sql_query("UPDATE users SET secret=" . sqlesc($sec) . ", editsecret='', passhash=" . sqlesc($newpasshash) . " WHERE id=$id AND editsecret=" . sqlesc($arr["editsecret"]));

	if (!mysql_affected_rows())
		stderr($tracker_lang['error'], "���������� �������� ������ ������������. ��������� ��������� � ��������������� ������������ ���� ������.");

  $body = <<<EOD
�� ������ ������� �� �������������� ������, �� ������������� ��� ����� ������.

��� ���� ����� ������ ��� ����� ��������:

    ������������: {$arr["username"]}
    ������:       $newpassword

�� ������ ����� �� ���� ���: {$CACHEARRAY['defaultbaseurl']}/login.php

--
{$CACHEARRAY['sitename']}
EOD;

  	mail($email, "{$CACHEARRAY['defaultbaseurl']} ������ ��������", $body, "From: {$CACHEARRAY['siteemail']}");
  stderr($tracker_lang['success'], "����� ������ �� �������� ���������� �� E-Mail <b>$email</b>.\n" .
    "����� ��������� ����� (������ �����) �� �������� ���� ����� ������.");
}
else
{
 	stdhead("�������������� ������");
	?>
	<form method="post" action="recover.php">
	<table border="1" cellspacing="0" cellpadding="5">
	<tr><td class="colhead" colspan="2">�������������� ����� ������������ ��� ������</td></tr>
	<tr><td colspan="2">����������� ����� ���� ��� ������������� ������<br /> � ���� ������ ����� ���������� ��� �� �����.<br /><br />
	�� ����� ������ ����������� ������.</td></tr>
	<tr><td class="rowhead">����������������� email</td>
	<td><input type="text" size="40" name="email"></td></tr>
	<tr><td colspan="2" align="center"><input type="submit" value="������������"></td></tr>
	</table>
	<?
	stdfoot();
}

?>