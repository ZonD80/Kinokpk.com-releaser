<?php

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

require_once "include/bittorrent.php";
dbconn();
getlang('clearcache');
if (get_user_class() < UC_SYSOP) stderr($tracker_lang['error'], $tracker_lang['access_denied']);


if ($_SERVER['REQUEST_METHOD'] != 'POST') {

	stdhead($tracker_lang['cleaning_cache']);
	begin_frame($tracker_lang['select_cache']);

	print('        <script type="text/javascript">
function checkAll(oForm, cbName, checked)
{
for (var i=0; i < oForm[cbName].length; i++) oForm[cbName][i].checked = checked;
}
</script>');

	print '<form action="clearcache.php" method="POST"><table width="100%"><tr><td class="colhead">'.$tracker_lang['name_cache'].'</td><td class="colhead">'.$tracker_lang['select_all'].'<input type="checkbox" title="'.$tracker_lang['mark_all'].'" value="'.$tracker_lang['mark_all'].'" onClick="checkAll(this.form,\'cache[]\',this.checked)"></td></tr>';
	if ($handle = opendir(ROOT_PATH.'cache')) {
		while (false !== ($file = readdir($handle))) {
			if ($file != "." && $file != "..") {
				print "<tr><td>$file</td><td><input type=\"checkbox\" name=\"cache[]\" value=\"$file\"></td></tr>";
			}
		}
		closedir($handle);
	}
	print('<tr><td colspan="2"><input type="submit" value="'.$tracker_lang['clean'].'"></td></tr></table></form>');
	end_frame();

	stdfoot();

} else {

	if (!defined("CACHE_REQUIRED")) {
		require_once ROOT_PATH.'classes/cache/cache.class.php';
		require_once ROOT_PATH.'classes/cache/fileCacheDriver.class.php';
	}

	$text = '';

	if (!is_array($_POST['cache'])) stderr($tracker_lang['error'],$tracker_lang['dont_set_cache']);

	foreach ($_POST['cache'] as $clearcache){


		$cache=new Cache();
		$cache->addDriver('file', new FileCacheDriver());

		$cache->clearGroupCache($clearcache);

		$text .= "".$tracker_lang['cache']." <b>".htmlspecialchars($clearcache)."</b> ".$tracker_lang['succ_purif']."<br/>";

	}

	stderr($tracker_lang['cache_cleared'] . $text . $tracker_lang['recommended']);

}
?>