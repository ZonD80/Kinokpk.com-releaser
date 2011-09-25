<?php

/**
 * Avatars uploader
 * @license GNU GPLv3 http://opensource.org/licenses/gpl-3.0.html
 * @package Kinokpk.com releaser
 * @author ZonD80 <admin@kinokpk.com>
 * @copyright (C) 2008-now, ZonD80, Germany, TorrentsBook.com
 * @link http://dev.kinokpk.com
 */

require "include/bittorrent.php";
INIT();

loggedinorreturn();
$max_image_width = $REL_CONFIG['avatar_max_width'];
$max_image_height = $REL_CONFIG['avatar_max_height'];
$maxfilesize = 60 * 1024;

$allowed_types = array(
"image/gif" => "gif",
"image/jpeg" => "jpg",
"image/jpg" => "jpg",
"image/png" => "png"
// Add more types here if you like
);

if(empty($_FILES['avatar']['tmp_name'])) {
	$REL_TPL->stdhead($REL_LANG->say_by_key('upload_avatar'));
	//  var_dump($_FILES);
	print '<div class="friends_search">';
	print '<table width="100%" border="0" cellpadding="0" cellspacing="0"><tr><td class="block" width="100%" align="center" valign="middle"><strong>'.$REL_LANG->say_by_key('upload_avatar').'</strong></td></tr></table>';
	print "<br /><form method=post action=\"".$REL_SEO->make_link('avatarup')."\" enctype=\"multipart/form-data\">".$REL_LANG->say_by_key('select_avatar')." : <input type=\"file\" name=\"avatar\">
<input type=submit value=".$REL_LANG->say_by_key('upload')." ></form><br /><br /><center><font color=green>(".sprintf($REL_LANG->say_by_key('hint'), number_format(round($maxfilesize/1024,2)), number_format($max_image_width), number_format($max_image_height)).")</font></center>";
	print'</div><br />';
	//	$REL_TPL->stdfoot();
}
else {
	$size = @GetImageSize($_FILES['avatar']['tmp_name']);
	//    var_dump($size);
	if (!$size)
	$REL_TPL->stderr($REL_LANG->say_by_key("error"),$REL_LANG->say_by_key('not_pic'));
	// Is valid filetype?
	elseif (!array_key_exists($_FILES['avatar']['type'], $allowed_types))
	$REL_TPL->stderr($REL_LANG->say_by_key("error"),$REL_LANG->say_by_key('attention'));

	elseif (!preg_match('/^(.+)\.(jpg|png|gif)$/si', $_FILES['avatar']['name']))
	$REL_TPL->stderr($REL_LANG->say_by_key("error"),$REL_LANG->say_by_key('invalid_filename'));

	elseif (($size[0] > $max_image_width ) || ($size[1] > $max_image_height))
	$REL_TPL->stderr ($REL_LANG->say_by_key("error")," ".sprintf($REL_LANG->say_by_key('size_you_avatar'), number_format($size[0]), number_format($size[1]), number_format($max_image_width), number_format($max_image_height))." </font></b>");
	elseif ($_FILES['avatar']['size'] > $maxfilesize) {
		$REL_TPL->stderr($REL_LANG->say_by_key("error")," ".sprintf($REL_LANG->say_by_key('size_exceeds'), number_format(round($maxfilesize/1024,2)))."</font></b>");
	}else
	{
		@unlink("./".$CURUSER['avatar']);
		copy($_FILES['avatar']['tmp_name'],"./avatars/".$CURUSER["id"].substr($_FILES['avatar']['name'], strripos($_FILES['avatar']['name'], '.')));
		$pathav = "avatars/".$CURUSER["id"].substr($_FILES['avatar']['name'], strripos($_FILES['avatar']['name'], '.'));
		$REL_DB->query("UPDATE users SET avatar = '".$pathav."' WHERE id = " . $CURUSER["id"]);

		$REL_TPL->stderr($REL_LANG->say_by_key('success'),$REL_LANG->say_by_key('succes_upload')." ".$CURUSER["id"].substr($_FILES['avatar']['name'], strripos($_FILES['avatar']['name'], '.'))."".sprintf($REL_LANG->say_by_key('file_size'), number_format(round($_FILES['avatar']['size']/1024,2))),'success');
	}
}
?>