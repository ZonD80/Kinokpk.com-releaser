<?php
/**
 * Cache cleaner
 * @license GNU GPLv3 http://opensource.org/licenses/gpl-3.0.html
 * @package Kinokpk.com releaser
 * @author ZonD80 <admin@kinokpk.com>
 * @copyright (C) 2008-now, ZonD80, Germany, TorrentsBook.com
 * @link http://dev.kinokpk.com
 */

require_once "include/bittorrent.php";
dbconn();
getlang('clearcache');
if (get_user_class() < UC_ADMINISTRATOR) stderr($tracker_lang['error'], $tracker_lang['access_denied']);


if ($_SERVER['REQUEST_METHOD'] != 'POST') {

	stdhead($tracker_lang['cleaning_cache']);
	begin_frame($tracker_lang['select_cache']);

	print '<form action="clearcache.php" method="POST" id="message"><table width="100%"><tr><td class="colhead">'.$tracker_lang['name_cache'].'</td><td class="colhead">'.$tracker_lang['select_all'].'<input type="checkbox" title="'.$tracker_lang['mark_all'].'" value="'.$tracker_lang['mark_all'].'" id="toggle-all"></td></tr>';
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

	$text = '';

	if (!is_array($_POST['cache'])) stderr($tracker_lang['error'],$tracker_lang['dont_set_cache']);

	foreach ($_POST['cache'] as $clearcache){

		$CACHE->clearGroupCache($clearcache);

		$text .= "<br />".$tracker_lang['cache']." <b>".htmlspecialchars($clearcache)."</b> ".$tracker_lang['succ_purif'];

	}

	stderr($tracker_lang['cache_cleared'] . $text);

}
?>