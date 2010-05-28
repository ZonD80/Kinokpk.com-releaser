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
getlang('avatarup');
loggedinorreturn();
$max_image_width = $CACHEARRAY['avatar_max_width'];
$max_image_height = $CACHEARRAY['avatar_max_height'];
$maxfilesize = 60 * 1024;

$allowed_types = array(
"image/gif" => "gif",
"image/jpeg" => "jpg",
"image/jpg" => "jpg",
"image/png" => "png"
// Add more types here if you like
);

if(empty($_FILES['avatar']['tmp_name'])) {
	stdhead($tracker_lang['upload_avatar']);
	print '<table width="100%" border="0" cellpadding="0" cellspacing="0"><tr><td class="block" width="100%" align="center" valign="middle" ><strong>'.$tracker_lang['upload_avatar'].'</strong></td></tr></table>';
	print "<br><form method=post enctype=multipart/form-data><div class=\"form-row\"><div class=\"field-widget\"><label for=\"avatar\">".$tracker_lang['select_avatar']."</label> : <input type=file name=avatar title=\"".$tracker_lang['select_avatar']."\"></div></div>
<input type=submit value=".$tracker_lang['upload']." ></form><br><br><center><font color=green>(".sprintf($tracker_lang['hint'], number_format(round($maxfilesize/1024,2)), number_format($max_image_width), number_format($max_image_height)).")</font></center>";
	stdfoot();
}
else {
	$size = @GetImageSize($_FILES['avatar']['tmp_name']);
	//    var_dump($size);
	if (!$size)
	stderr($tracker_lang["error"],$tracker_lang['not_pic']);
	// Is valid filetype?
	elseif (!array_key_exists($_FILES['avatar']['type'], $allowed_types))
	stderr($tracker_lang["error"],$tracker_lang['attention']);

	elseif (!preg_match('/^(.+)\.(jpg|png|gif)$/si', $_FILES['avatar']['name']))
	stderr($tracker_lang["error"],$tracker_lang['invalid_filename']);

	elseif (($size[0] > $max_image_width ) || ($size[1] > $max_image_height))
	stderr ($tracker_lang["error"]," ".sprintf($tracker_lang['size_you_avatar'], number_format($size[0]), number_format($size[1]), number_format($max_image_width), number_format($max_image_height))." </font></b>");
	elseif ($_FILES['avatar']['size'] > $maxfilesize) {
		stderr($tracker_lang["error"]," ".sprintf($tracker_lang['size_exceeds'], number_format(round($maxfilesize/1024,2)))."</font></b>");
	}else
	{
		@unlink("./".$CURUSER['avatar']);
		copy($_FILES['avatar']['tmp_name'],"./avatars/".$CURUSER["id"].substr($_FILES['avatar']['name'], strripos($_FILES['avatar']['name'], '.')));
		$pathav = "avatars/".$CURUSER["id"].substr($_FILES['avatar']['name'], strripos($_FILES['avatar']['name'], '.'));
		sql_query("UPDATE users SET avatar = '".$pathav."' WHERE id = " . $CURUSER["id"])or sqlerr(__FILE__,__LINE__);

		if ($CACHEARRAY['use_integration']) {
			forumconn();

			$check = sql_query("SELECT id FROM ".$fprefix."members WHERE name=".sqlesc($CURUSER['username']))  or die(mysql_error());

			if(!@mysql_result($check,0)) $ipbid = 0; else $ipbid=mysql_result($check,0);
			if ($ipbid) sql_query("UPDATE ".$fprefix."member_extra SET avatar_type = 'url', avatar_size = '".$size[0]."x".$size[1]."', avatar_location = '".$CACHEARRAY['defaultbaseurl']."/".$pathav."'  WHERE id = " . $ipbid)or sqlerr(__FILE__,__LINE__);
			relconn();
		}

		stdmsg("Okay!"," ".$tracker_lang['succes_upload']." ".$CURUSER["id"].substr($_FILES['avatar']['name'], strripos($_FILES['avatar']['name'], '.'))."".sprintf($tracker_lang['file_size'], number_format(round($_FILES['avatar']['size']/1024,2)))."");// как на релизере, так и на <a href=\"".$CACHEARRAY['defaultbaseurl']."/forums/\">Форуме</a></b></center> ";

	}
}
?>