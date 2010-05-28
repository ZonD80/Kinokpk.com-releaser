<?

loggedinorreturn();

if (get_user_class() < UC_ADMINISTRATOR)
	stderr($tracker_lang['error'], "Что вы тут забыли?");

require_once($rootpath . "admin/core.php");

function BuildMenu($url, $title, $image = '') {
	global $admin_file, $counter;
	$image_link = "admin/pic/$image";
	echo "<td align=\"center\" valign=\"top\" width=\"15%\" style=\"border: none;\"><a href=\"$url\" title=\"$title\">".($image != '' ? "<img src=\"$image_link\" border=\"0\" alt=\"$title\" title=\"$title\">" : "")."<br><b>$title</b></a></td>";
	if ($counter == 5) {
		echo "</tr><tr>";
		$counter = 0;
	} else {
		$counter++;
	}
}

switch ($op) {

	case "Main":
		echo "<table width=\"100%\" border=\"0\" cellspacing=\"0\" cellpadding=\"3\"><tr><td class=\"colhead\" colspan=\"6\">Панель администратора</td></tr>";
		$dir = opendir("admin/links");
		while ($file = readdir($dir)) {
			if (preg_match("/(\.php)$/is", $file) && $file != "." && $file != "..") require_once("admin/links/".$file."");
		}
		echo "<tr><td align=\"center\" class=\"colhead\" width=\"100%\" colspan=\"6\">&nbsp;</td></tr>";
		echo "</table>";
		//echo "<hr size=\"1\">";
	break;

	default:
		$dir = opendir("admin/modules");
		while ($file = readdir($dir)) {
			if (preg_match("/(\.php)$/is", $file) && $file != "." && $file != "..") require_once("admin/modules/".$file."");
		}
	break;
}

?>