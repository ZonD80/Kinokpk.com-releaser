<?php
define('ROOT_PATH',str_replace('update','',dirname(__FILE__)));

require_once(ROOT_PATH.'include/bittorrent.php');

dbconn();
@set_time_limit(0);
@ignore_user_abort(1);
ini_set("error_reporting",'E_ALL & ~E_NOTICE & ~E_WARNING');
$step = (int)$_GET['step'];

if ($_GET['setlang']) {
	setcookie('lang',(string)$_GET['setlang']);
	print('<a href="index.php">Продолжить / Continue</a>');
	footers();
	die();
}
function headers() {
	header("X-Powered-By: Kinokpk.com releaser ".RELVERSION);
	header("Cache-Control: no-cache, must-revalidate, max-age=0");
	//header("Expires:" . gmdate("D, d M Y H:i:s") . " GMT");
	header("Expires: 0");
	header("Pragma: no-cache");
	print('<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="ru" lang="ru">
<head>
<title>Kinokpk.com releaser 2.40 to 2.70 updater, step: '.$step.'</title>
<meta http-equiv="Content-Type" content="text/html; charset=windows-1251" /></head><body>');

	if (ini_get("register_globals")) die('<font color="red" size="20">Отключи register_globals, дурачок! / Turn off register_globals, noob!</font>');

}

function footers() {
	print('<hr /><div align="right">Kinokpk.com releaser 2.40 to 2.70 updater</div></body></html>');
}

function cont($step) {
	global $lang;
	print '<a href="index.php?step='.$step.'">'.$lang['continue'].'</a>';

}

function encode_quote($text) {
	$start_html = "<div align=\"center\"><div style=\"width: 85%; overflow: auto\">"
	."<table width=\"100%\" cellspacing=\"1\" cellpadding=\"3\" border=\"0\" align=\"center\" class=\"bgcolor4\">"
	."<tr bgcolor=\"FFE5E0\"><td><font class=\"block-title\">Цитата</font></td></tr><tr class=\"bgcolor1\"><td>";
	$end_html = "</td></tr></table></div></div>";
	$text = preg_replace("#\[quote\](.*?)\[/quote\]#si", "".$start_html."\\1".$end_html."", $text);
	return $text;
}

// Format quote from
function encode_quote_from($text) {
	$start_html = "<div align=\"center\"><div style=\"width: 85%; overflow: auto\">"
	."<table width=\"100%\" cellspacing=\"1\" cellpadding=\"3\" border=\"0\" align=\"center\" class=\"bgcolor4\">"
	."<tr bgcolor=\"FFE5E0\"><td><font class=\"block-title\">\\1 писал</font></td></tr><tr class=\"bgcolor1\"><td>";
	$end_html = "</td></tr></table></div></div>";
	$text = preg_replace("#\[quote=(.+?)\](.*?)\[/quote\]#si", "".$start_html."\\2".$end_html."", $text);
	return $text;
}

// Format code
function encode_code($text) {
	$start_html = "<div align=\"center\"><div style=\"width: 85%; overflow: auto\">"
	."<table width=\"100%\" cellspacing=\"1\" cellpadding=\"3\" border=\"0\" align=\"center\" class=\"bgcolor4\">"
	."<tr bgcolor=\"E5EFFF\"><td colspan=\"2\"><font class=\"block-title\">Код</font></td></tr>"
	."<tr class=\"bgcolor1\"><td align=\"right\" class=\"code\" style=\"width: 5px; border-right: none\">{ZEILEN}</td><td class=\"code\">";
	$end_html = "</td></tr></table></div></div>";
	$match_count = preg_match_all("#\[code\](.*?)\[/code\]#si", $text, $matches);
	for ($mout = 0; $mout < $match_count; ++$mout) {
		$before_replace = $matches[1][$mout];
		$after_replace = $matches[1][$mout];
		$after_replace = trim ($after_replace);
		$zeilen_array = explode ("<br />", $after_replace);
		$j = 1;
		$zeilen = "";
		foreach ($zeilen_array as $str) {
			$zeilen .= "".$j."<br />";
			++$j;
		}
		$after_replace = str_replace ("", "", $after_replace);
		$after_replace = str_replace ("&amp;", "&", $after_replace);
		$after_replace = str_replace ("", "&nbsp; ", $after_replace);
		$after_replace = str_replace ("", " &nbsp;", $after_replace);
		$after_replace = str_replace ("", "&nbsp; &nbsp;", $after_replace);
		$after_replace = preg_replace ("/^ {1}/m", "&nbsp;", $after_replace);
		$str_to_match = "[code]".$before_replace."[/code]";
		$replace = str_replace ("{ZEILEN}", $zeilen, $start_html);
		$replace .= $after_replace;
		$replace .= $end_html;
		$text = str_replace ($str_to_match, $replace, $text);
	}

	$text = str_replace ("[code]", $start_html, $text);
	$text = str_replace ("[/code]", $end_html, $text);
	return $text;
}

function encode_php($text) {
	$start_html = "<div align=\"center\"><div style=\"width: 85%; overflow: auto\">"
	."<table width=\"100%\" cellspacing=\"1\" cellpadding=\"3\" border=\"0\" align=\"center\" class=\"bgcolor4\">"
	."<tr bgcolor=\"F3E8FF\"><td colspan=\"2\"><font class=\"block-title\">PHP - Код</font></td></tr>"
	."<tr class=\"bgcolor1\"><td align=\"right\" class=\"code\" style=\"width: 5px; border-right: none\">{ZEILEN}</td><td>";
	$end_html = "</td></tr></table></div></div>";
	$match_count = preg_match_all("#\[php\](.*?)\[/php\]#si", $text, $matches);
	for ($mout = 0; $mout < $match_count; ++$mout) {
		$before_replace = $matches[1][$mout];
		$after_replace = $matches[1][$mout];
		$after_replace = trim ($after_replace);
		$after_replace = str_replace("&lt;", "<", $after_replace);
		$after_replace = str_replace("&gt;", ">", $after_replace);
		$after_replace = str_replace("&quot;", '"', $after_replace);
		$after_replace = preg_replace("/<br.*/i", "", $after_replace);
		$after_replace = (substr($after_replace, 0, 5 ) != "<?php") ? "<?php\n".$after_replace."" : "".$after_replace."";
		$after_replace = (substr($after_replace, -2 ) != "?>") ? "".$after_replace."\n?>" : "".$after_replace."";
		ob_start ();
		highlight_string ($after_replace);
		$after_replace = ob_get_contents ();
		ob_end_clean ();
		$zeilen_array = explode("<br />", $after_replace);
		$j = 1;
		$zeilen = "";
		foreach ($zeilen_array as $str) {
			$zeilen .= "".$j."<br />";
			++$j;
		}
		$after_replace = str_replace("\n", "", $after_replace);
		$after_replace = str_replace("&amp;", "&", $after_replace);
		$after_replace = str_replace("  ", "&nbsp; ", $after_replace);
		$after_replace = str_replace("  ", " &nbsp;", $after_replace);
		$after_replace = str_replace("\t", "&nbsp; &nbsp;", $after_replace);
		$after_replace = preg_replace("/^ {1}/m", "&nbsp;", $after_replace);
		$str_to_match = "[php]".$before_replace."[/php]";
		$replace = str_replace("{ZEILEN}", $zeilen, $start_html);
		$replace .= $after_replace;
		$replace .= $end_html;
		$text = str_replace ($str_to_match, $replace, $text);
	}
	$text = str_replace("[php]", $start_html, $text);
	$text = str_replace("[/php]", $end_html, $text);
	return $text;
}
function format_urls($s)
{
	return preg_replace(
    	"/(\A|[^=\]'\"a-zA-Z0-9])((http|ftp|https|ftps|irc):\/\/[^()<>\s]+)/i",
	    "\\1<a href=\"\\2\">\\2</a>", $s);
}

function format_c($text, $strip_html = false) {
	global $smilies, $privatesmilies,$CACHEARRAY;
	$smiliese = $smilies;
	$s = $text;

	// This fixes the extraneous ;) smilies problem. When there was an html escaped
	// char before a closing bracket - like >), "), ... - this would be encoded
	// to &xxx;), hence all the extra smilies. I created a new :wink: label, removed
	// the ;) one, and replace all genuine ;) by :wink: before escaping the body.
	// (What took us so long? :blush:)- wyz

	$s = str_replace(";)", ":wink:", $s);

	if ($strip_html)
	$s = htmlspecialchars_uni($s);

	$bb[] = "#\[img\]([^?](?:[^\[]+|\[(?!url))*?)\[/img\]#i";
	$html[] = "<img class=\"linked-image\" src=\"\\1\" border=\"0\" alt=\"\\1\" title=\"\\1\" />";
	$bb[] = "#\[img=([a-zA-Z]+)\]([^?](?:[^\[]+|\[(?!url))*?)\[/img\]#is";
	$html[] = "<img class=\"linked-image\" src=\"\\2\" align=\"\\1\" border=\"0\" alt=\"\\2\" title=\"\\2\" />";
	$bb[] = "#\[img\ alt=([a-zA-Zа-яА-Я0-9\_\-\. ]+)\]([^?](?:[^\[]+|\[(?!url))*?)\[/img\]#is";
	$html[] = "<img class=\"linked-image\" src=\"\\2\" align=\"\\1\" border=\"0\" alt=\"\\1\" title=\"\\1\" />";
	$bb[] = "#\[img=([a-zA-Z]+) alt=([a-zA-Zа-яА-Я0-9\_\-\. ]+)\]([^?](?:[^\[]+|\[(?!url))*?)\[/img\]#is";
	$html[] = "<img class=\"linked-image\" src=\"\\3\" align=\"\\1\" border=\"0\" alt=\"\\2\" title=\"\\2\" />";
	$bb[] = "#\[url\]([\w]+?://([\w\#$%&~/.\-;:=,?@\]+]+|\[(?!url=))*?)\[/url\]#is";
	$html[] = "<a href=\"\\1\" title=\"\\1\">\\1</a>";
	$bb[] = "#\[url\]((www|ftp)\.([\w\#$%&~/.\-;:=,?@\]+]+|\[(?!url=))*?)\[/url\]#is";
	$html[] = "<a href=\"http://\\1\" title=\"\\1\">\\1</a>";
	$bb[] = "#\[url=([\w]+?://[\w\#$%&~/.\-;:=,?@\[\]+]*?)\]([^?\n\r\t].*?)\[/url\]#is";
	$html[] = "<a href=\"\\1\" title=\"\\1\">\\2</a>";
	$bb[] = "#\[url=((www|ftp)\.[\w\#$%&~/.\-;:=,?@\[\]+]*?)\]([^?\n\r\t].*?)\[/url\]#is";
	$html[] = "<a href=\"http://\\1\" title=\"\\1\">\\3</a>";
	$bb[] = "/\[url=([^()<>\s]+?)\]((\s|.)+?)\[\/url\]/i";
	$html[] = "<a href=\"\\1\">\\2</a>";
	$bb[] = "/\[url\]([^()<>\s]+?)\[\/url\]/i";
	$html[] = "<a href=\"\\1\">\\1</a>";
	$bb[] = "#\[mail\](\S+?)\[/mail\]#i";
	$html[] = "<a href=\"mailto:\\1\">\\1</a>";
	$bb[] = "#\[mail\s*=\s*([\.\w\-]+\@[\.\w\-]+\.[\w\-]+)\s*\](.*?)\[\/mail\]#i";
	$html[] = "<a href=\"mailto:\\1\">\\2</a>";
	$bb[] = "#\[color=(\#[0-9A-F]{6}|[a-z]+)\](.*?)\[/color\]#si";
	$html[] = "<span style=\"color: \\1\">\\2</span>";
	$bb[] = "#\[(font|family)=([A-Za-z ]+)\](.*?)\[/\\1\]#si";
	$html[] = "<span style=\"font-family: \\2\">\\3</span>";
	$bb[] = "#\[size=([0-9]+)\](.*?)\[/size\]#si";
	$html[] = "<span style=\"font-size: \\1\">\\2</span>";
	$bb[] = "#\[(left|right|center|justify)\](.*?)\[/\\1\]#is";
	$html[] = "<div align=\"\\1\">\\2</div>";
	$bb[] = "#\[b\](.*?)\[/b\]#si";
	$html[] = "<b>\\1</b>";
	$bb[] = "#\[i\](.*?)\[/i\]#si";
	$html[] = "<i>\\1</i>";
	$bb[] = "#\[u\](.*?)\[/u\]#si";
	$html[] = "<u>\\1</u>";
	$bb[] = "#\[s\](.*?)\[/s\]#si";
	$html[] = "<s>\\1</s>";
	$bb[] = "#\[li\]#si";
	$html[] = "<li>";
	$bb[] = "#\[hr\]#si";
	$html[] = "<hr>";
	$bb[] = "#\[siteurl\]#si";
	$html[] = $CACHEARRAY['defaultbaseurl'];

	$s = preg_replace($bb, $html, $s);

	// Linebreaks
	$s = nl2br($s);

	while (preg_match("#\[quote\](.*?)\[/quote\]#si", $s)) $s = encode_quote($s);
	while (preg_match("#\[quote=(.+?)\](.*?)\[/quote\]#si", $s)) {
		$s = encode_quote_from($s);
	}
	while (preg_match("#\[code\](.*?)\[/code\]#si", $s)) $s = encode_code($s);
	while (preg_match("#\[php\](.*?)\[/php\]#si", $s)) $s = encode_php($s);
	//[spoiler]Text[/spoiler]
	$s = str_replace("[spoiler]","<div style=\"position: static;\" class=\"news-wrap\"><div class=\"news-head folded clickable\"><table width=100% border=0 cellspacing=0 cellpadding=0><tr><td class=bottom width=50%><i>Скрытый текст</i></td></tr></table></div><div class=\"news-body\">", $s);
	// continue below //

	//[spoiler=name]Text[/spoiler]
	$s = preg_replace("#\[spoiler=\s*((\s|.)+?)\s*\]#si",
"<div style=\"position: static;\" class=\"news-wrap\"><div class=\"news-head folded clickable\"><i>\\1</i></div><div class=\"news-body\">", $s);

	$s = str_replace("[/spoiler]","</div></div>",$s);
	// URLs
	$s = format_urls($s);
	//$s = format_local_urls($s);

	// Maintain spacing
	//$s = str_replace("  ", " &nbsp;", $s);

	foreach ($smiliese as $code => $url)
	$s = str_replace($code, "<img border=\"0\" src=\"pic/smilies/$url\" alt=\"" . htmlspecialchars_uni($code) . "\">", $s);

	foreach ($privatesmilies as $code => $url)
	$s = str_replace($code, "<img border=\"0\" src=\"pic/smilies/$url\">", $s);

	return $s;
}

headers();

if (!$_COOKIE['lang']) {
	print("<h1>Выберите язык / Choose a language: <a href=\"index.php?setlang=russian\">Русский</a>, <a href=\"index.php?setlang=english\">English</a></h1>");
	footers();
	die();
} else require_once(ROOT_PATH.'update/lang_'.$_COOKIE['lang'].'.php');

if (!$step) {
	print $lang['hello'];
	print $lang['agree'];
	print('<iframe width="100%" height="300px" src="gnu.html">GNU</iframe>');
	print $lang['agree_continue'];
	print $lang['testing_database_connection'];
	print $lang['next_step_update_db'];
}

elseif ($step==1) {
	$strings = file(ROOT_PATH."update/update.sql");
	$query = '';
	foreach ($strings AS $string)
	{
		if (preg_match("/^\s?#/", $string) || !preg_match("/[^\s]/", $string))
		continue;
		else
		{
			$query .= $string;
			if (preg_match("/;\s?$/", $query))
			{
				mysql_query($query) or die($lang['mysql_error'].'['.mysql_errno().']: ' . mysql_error(). 'QUERY: '.$query);
				$query = '';
			}
		}
	}

	//sql_query("UPDATE cron SET cron_value=(SELECT cache_value FROM cache_stats WHERE cache_name=name) WHERE cron_name=name AND name IN('autoclean_interval','points_per_hour','max_dead_torrent_time','pm_delete_sys_days','pm_delete_user_days','signup_timeout','ttl_days','announce_interval')");
	sql_query("DELETE FROM cache_stats WHERE cache_name IN IN('autoclean_interval','points_per_hour','max_dead_torrent_time','pm_delete_sys_days','pm_delete_user_days','signup_timeout','ttl_days','announce_interval','lastcleantime','minvotes','watermark')");
	print($lang['ok'].'<hr/>');
	print $lang['next_step_update_db'];
	print $lang['step2_descr'];

	cont(2);
}

elseif ($step==2) {
	$res = sql_query("SELECT category,name FROM tags");
	while ($row = mysql_fetch_assoc($res)) {
		sql_query("INSERT INTO categories (name,parent_id) VALUES (".sqlesc($row['name']).",".sqlesc($row['category']).")");
	}
	$res= sql_query("SELECT id,category,tags FROM torrents");
	while (list($id,$oldcat,$tags) = mysql_fetch_array($res)) {
		if ($tags) {
			$tags = explode(',',$tags);
			foreach ($tags AS $tag) {
				list($tagcat) = mysql_fetch_array(sql_query("SELECT id FROM categories WHERE name =".sqlesc($tag)." LIMIT 1"));
				print($tag.' -> category id '.$tagcat.' / '); flush();
				sql_query("UPDATE torrents SET category='$oldcat,$tagcat' WHERE id=$id");
			}
		}
	}
	sql_query("DROP TABLE `tags`");

	print $lang['next_step_update_db'];
	print $lang['step3_descr'];
	cont(3);
}

elseif($step==3) {
	$res = sql_query("SELECT id FROM torrents ORDER BY id DESC");

	while (list($id) = mysql_fetch_array($res)) {
		$detid = sql_query("SELECT descr_torrents.value, descr_details.name, descr_details.hide, descr_details.input, descr_details.spoiler FROM descr_torrents LEFT JOIN descr_details ON descr_details.id = descr_torrents.typeid WHERE descr_torrents.torrent = ".$id." AND descr_torrents.value<>'' ORDER BY descr_details.sort ASC");

		$descr = '';

		while ($did = mysql_fetch_array($detid))  {

			$descr.='<br/><b>'.$did['name'].':</b><br/>'.format_c($did['value']);
		}

		sql_query("UPDATE torrents SET descr=".sqlesc($descr)." WHERE id = $id");
		print ("Torrent id $id done!<br/>"); flush();
	}

	sql_query("DROP TABLE  `descr_details`");
	sql_query("DROP TABLE  `descr_torrents`");
	sql_query("DROP TABLE  `descr_types`");

	print ('Templates system tables dropped<hr/>');

	$res = sql_query("SELECT id,text FROM comments");
	while ($row = mysql_fetch_assoc($res)) {
		sql_query("UPDATE comments SET text=".sqlesc(format_c($row['text']))." WHERE id={$row['id']}");
		print ('Comment '.$row['id']." done!<br/>"); flush();
	}

	$res = sql_query("SELECT id,msg AS text FROM messages");
	while ($row = mysql_fetch_assoc($res)) {
		sql_query("UPDATE messages SET msg=".sqlesc(format_c($row['text']))." WHERE id={$row['id']}");
		print ('PM id '.$row['id']." done!<br/>"); flush();
	}

	$res = sql_query("SELECT id,text FROM newscomments");
	while ($row = mysql_fetch_assoc($res)) {
		sql_query("UPDATE newscomments SET text=".sqlesc(format_c($row['text']))." WHERE id={$row['id']}");
		print ('Newscomment id '.$row['id']." done!<br/>"); flush();
	}

	$res = sql_query("SELECT id,text FROM pollcomments");
	while ($row = mysql_fetch_assoc($res)) {
		sql_query("UPDATE pollcomments SET text=".sqlesc(format_c($row['text']))." WHERE id={$row['id']}");
		print ('Pollcomment id '.$row['id']." done!<br/>"); flush();
	}

	$res = sql_query("SELECT id,text FROM reqcomments");
	while ($row = mysql_fetch_assoc($res)) {
		sql_query("UPDATE reqcomments SET text=".sqlesc(format_c($row['text']))." WHERE id={$row['id']}");
		print ('Request comment id '.$row['id']." done!<br/>");flush();
	}

	$res = sql_query("SELECT id,info AS text FROM users");
	while ($row = mysql_fetch_assoc($res)) {
		sql_query("UPDATE users SET info=".sqlesc(format_c($row['text']))." WHERE id={$row['id']}");
		print ('User info id '.$row['id']." done!<br/>"); flush();
	}

	print($lang['ok'].'<hr/>');
	print $lang['next_step_update_db'];
	print $lang['step4_descr'];
	cont(4);
}

elseif ($step==4) {

	print ('Getting invites table<br/>'); flush();

	$row = sql_query("SELECT id,confirmed FROM invites");
	while ($res = mysql_fetch_assoc($row)){
		$value[] = $res;
	}

	sql_query("ALTER TABLE `invites` CHANGE `confirmed` `confirmed` TINYINT( 1 ) NOT NULL DEFAULT '0'");
	print ('Table invites field confirmed changed<hr/>'); flush();

	foreach ($value as $res) {
		if ($res['confirmed'] == 'yes') $res['confirmed']=1; else $res['confirmed']=0;
		sql_query("UPDATE invites SET confirmed={$res['confirmed']} WHERE id={$res['id']}");
		print ("Invite id={$res['id']} updated<br/>"); flush();
	}

	print ('Table invites finished<hr/>'); flush();

	print ('Getting messages table<br/>'); flush();

	$row = sql_query("SELECT id,unread,saved,archived FROM messages");
	while ($res = mysql_fetch_assoc($row)){
		$value[] = $res;
	}

	sql_query("ALTER TABLE `messages` CHANGE `unread` `unread` TINYINT( 1 ) NOT NULL DEFAULT '1'");
	sql_query("ALTER TABLE `messages` CHANGE `saved` `saved` TINYINT( 1 ) NOT NULL DEFAULT '0'");
	sql_query("ALTER TABLE `messages` CHANGE `archived` `archived` TINYINT( 1 ) NOT NULL DEFAULT '0'");
	print ('Table messages field unread,saved,archived changed<hr/>'); flush();

	foreach ($value as $res) {
		if ($res['unread'] == 'yes') $res['unread']=1; else $res['unread']=0;
		if ($res['saved'] == 'yes') $res['saved']=1; else $res['saved']=0;
		if ($res['archived'] == 'yes') $res['archived']=1; else $res['archived']=0;
		sql_query("UPDATE messages SET unread={$res['unread']},saved={$res['saved']},archived={$res['archived']} WHERE id={$res['id']}");
		print ("Message id={$res['id']} updated<br/>"); flush();
	}

	print ('Table messages finished<hr/>'); flush();

	print ('Editing and truncating peers table<br/>'); flush();

	sql_query("TRUNCATE TABLE peers");
	sql_query("ALTER TABLE `peers` CHANGE `seeder` `seeder` TINYINT( 1 ) NOT NULL DEFAULT '0'");
	sql_query("ALTER TABLE `peers` CHANGE `connectable` `connectable` TINYINT( 1 ) NOT NULL DEFAULT '1'");


	print ('Table peers finished<hr/>'); flush();

	print ('Getting polls table<br/>'); flush();

	$row = sql_query("SELECT id,public FROM polls");
	while ($res = mysql_fetch_assoc($row)){
		$value[] = $res;
	}

	sql_query("ALTER TABLE `polls` CHANGE `public` `public` TINYINT( 1 ) NOT NULL DEFAULT '0'");
	print ('Table polls field public changed<hr/>'); flush();

	foreach ($value as $res) {
		if ($res['public'] == 'yes') $res['public']=1; else $res['public']=0;
		sql_query("UPDATE polls SET public={$res['public']} WHERE id={$res['id']}");
		print ("Poll id={$res['id']} updated<br/>"); flush();
	}

	print ('Table polls finished<hr/>'); flush();

	print ('Getting snatched table<br/>'); flush();

	$row = sql_query("SELECT id,seeder,connectable,finished FROM snatched");
	while ($res = mysql_fetch_assoc($row)){
		$value[] = $res;
	}

	sql_query("ALTER TABLE `snatched` CHANGE `seeder` `seeder` TINYINT( 1 ) NOT NULL DEFAULT '0'");
	sql_query("ALTER TABLE `snatched` CHANGE `connectable` `connectable` TINYINT( 1 ) NOT NULL DEFAULT '1'");
	sql_query("ALTER TABLE `snatched` CHANGE `finished` `finished` TINYINT( 1 ) NOT NULL DEFAULT '0'");
	print ('Table snatched field seeder,connectable,finished changed<hr/>'); flush();

	foreach ($value as $res) {
		if ($res['seeder'] == 'yes') $res['seeder']=1; else $res['seeder']=0;
		if ($res['connectable'] == 'yes') $res['connectable']=1; else $res['connectable']=0;
		if ($res['finished'] == 'yes') $res['finished']=1; else $res['finished']=0;
		sql_query("UPDATE snatched SET seeder={$res['seeder']},connectable={$res['connectable']},finished={$res['finished']} WHERE id={$res['id']}");
		print ("Snatch id={$res['id']} updated<br/>"); flush();
	}

	print ('Table snatched finished<hr/>'); flush();

	print ('Getting torrents table<br/>'); flush();

	$row = sql_query("SELECT id,type,visible,banned,free,sticky,moderated FROM torrents");
	while ($res = mysql_fetch_assoc($row)){
		$value[] = $res;
	}

	sql_query("ALTER TABLE `torrents` CHANGE `type` `ismulti` TINYINT( 1 ) NOT NULL DEFAULT '0'");
	sql_query("ALTER TABLE `torrents` CHANGE `visible` `visible` TINYINT( 1 ) NOT NULL DEFAULT '1'");
	sql_query("ALTER TABLE `torrents` CHANGE `banned` `banned` TINYINT( 1 ) NOT NULL DEFAULT '0'");
	sql_query("ALTER TABLE `torrents` CHANGE `free` `free` TINYINT( 1 ) NOT NULL DEFAULT '0'");
	sql_query("ALTER TABLE `torrents` CHANGE `sticky` `sticky` TINYINT( 1 ) NOT NULL DEFAULT '0'");
	sql_query("ALTER TABLE `torrents` CHANGE `moderated` `moderated` TINYINT( 1 ) NOT NULL DEFAULT '0'");
	sql_query("ALTER TABLE torrents DROP type");
	print ('Table torrents field type,visible,banned,free,sticky,moderated changed<hr/>'); flush();

	foreach ($value as $res) {
		if ($res['type'] == 'single') $res['ismulti']=0; else $res['ismulti']=1;
		if ($res['visible'] == 'yes') $res['visible']=1; else $res['visible']=0;
		if ($res['banned'] == 'yes') $res['banned']=1; else $res['banned']=0;
		if ($res['free'] == 'yes') $res['free']=1; else $res['free']=0;
		if ($res['sticky'] == 'yes') $res['sticky']=1; else $res['sticky']=0;
		if ($res['moderated'] == 'yes') $res['moderated']=1; else $res['moderated']=0;
		sql_query("UPDATE torrents SET ismulti={$res['ismulti']},visible={$res['visible']},banned={$res['banned']},free={$res['free']},sticky={$res['sticky']},moderated={$res['moderated']} WHERE id={$res['id']}");
		print ("Torrent id={$res['id']} updated<br/>"); flush();
	}

	print ('Table torrents finished<hr/>'); flush();

	print ('Getting users table<br/>'); flush();

	$row = sql_query("SELECT id,status,support,enabled,parked,avatars,extra_ef,donor,warned,deletepms,savepms FROM users");
	while ($res = mysql_fetch_assoc($row)){
		$value[] = $res;
	}

	sql_query("ALTER TABLE `users` CHANGE `status` `confirmed` TINYINT( 1 ) NOT NULL DEFAULT '0'");
	sql_query("ALTER TABLE `users` CHANGE `support` `support` TINYINT( 1 ) NOT NULL DEFAULT '0'");
	sql_query("ALTER TABLE `users` CHANGE `enabled` `enabled` TINYINT( 1 ) NOT NULL DEFAULT '1'");
	sql_query("ALTER TABLE `users` CHANGE `parked` `parked` TINYINT( 1 ) NOT NULL DEFAULT '0'");
	sql_query("ALTER TABLE `users` CHANGE `avatars` `avatars` TINYINT( 1 ) NOT NULL DEFAULT '1'");
	sql_query("ALTER TABLE `users` CHANGE `extra_ef` `extra_ef` TINYINT( 1 ) NOT NULL DEFAULT '1'");
	sql_query("ALTER TABLE `users` CHANGE `donor` `donor` TINYINT( 1 ) NOT NULL DEFAULT '0'");
	sql_query("ALTER TABLE `users` CHANGE `warned` `warned` TINYINT( 1 ) NOT NULL DEFAULT '0'");
	sql_query("ALTER TABLE `users` CHANGE `deletepms` `deletepms` TINYINT( 1 ) NOT NULL DEFAULT '0'");
	sql_query("ALTER TABLE `users` CHANGE `savepms` `savepms` TINYINT( 1 ) NOT NULL DEFAULT '1'");
	sql_query("ALTERT TABLE users DROP status");
	print ('Table torrents field status,support,enabled,parked,avatars,extra_ef,donor,warned,deletepms,savepms changed<hr/>'); flush();

	foreach ($value as $res) {
		if ($res['status'] == 'pending') $res['confirmed']=0; else $res['confirmed']=1;
		if ($res['support'] == 'yes') $res['support']=1; else $res['support']=0;
		if ($res['enabled'] == 'yes') $res['enabled']=1; else $res['enabled']=0;
		if ($res['parked'] == 'yes') $res['parked']=1; else $res['parked']=0;
		if ($res['avatars'] == 'yes') $res['avatars']=1; else $res['avatars']=0;
		if ($res['extra_ef'] == 'yes') $res['extra_ef']=1; else $res['extra_ef']=0;
		if ($res['donor'] == 'yes') $res['donor']=1; else $res['donor']=0;
		if ($res['warned'] == 'yes') $res['warned']=1; else $res['warned']=0;
		if ($res['deletepms'] == 'yes') $res['deletepms']=1; else $res['deletepms']=0;
		if ($res['savepms'] == 'yes') $res['savepms']=1; else $res['savepms']=0;
		sql_query("UPDATE users SET confirmed={$res['confirmed']},support={$res['support']},enabled={$res['enabled']},parked={$res['parked']},avatars={$res['avatars']},extra_ef={$res['extra_ef']},donor={$res['donor']},warned={$res['warned']},deletepms={$res['deletepms']},savepms={$res['savepms']} WHERE id={$res['id']}");
		print ("User id={$res['id']} updated<br/>"); flush();
	}

	print ('Table users finished<hr/>'); flush();

	print($lang['ok'].'<hr/>');
	print $lang['next_step_update_db'];
	print $lang['step5_descr'];
	cont(5);

}

elseif ($step==5) {

	$ar = array();
	print ('Table bannedemails<br/>');
	$res=sql_query("SELECT id,UNIX_TIMESTAMP(added) FROM bannedemails");
	while(list($id,$added) = mysql_fetch_array($res)) {
		$ar[$id] = $added;
	}
	sql_query("ALTER TABLE  `bannedemails`  MODIFY COLUMN  `added` int(10) NOT NULL  AFTER `id`");
	foreach ($ar as $id=>$added) {
		sql_query("UPDATE bannedemails SET added=$added WHERE id=$id");
		print "Banemail $id ok<br />"; flush();
	}
	print ('Table bannedemails end<br/>');

	$ar = array();
	print ('Table comments<br/>');
	$res=sql_query("SELECT id,UNIX_TIMESTAMP(added),UNIX_TIMESTAMP(editedat) FROM comments");
	while(list($id,$added,$editedat) = mysql_fetch_array($res)) {
		$ar[$id] = array('added'=>$added,'editedat'=>$editedat);
	}
	sql_query("ALTER TABLE  `comments`  MODIFY COLUMN  `added` int(10) NOT NULL  AFTER `torrent`");

	sql_query("ALTER TABLE  `comments`  MODIFY COLUMN  `editedat` int(10) NOT NULL  AFTER `editedby`");
	foreach ($ar as $id=>$arr) {
		sql_query("UPDATE comments SET added={$arr['added']}, editedat={$arr['editedat']} WHERE id=$id");
		print "Comment $id ok<br />"; flush();
	}
	print ('Table comments end<br/>');

	$ar = array();
	print ('Table invites<br/>');
	$res=sql_query("SELECT id,UNIX_TIMESTAMP(time_invited) FROM invites");
	while(list($id,$added) = mysql_fetch_array($res)) {
		$ar[$id] = $added;
	}
	sql_query("ALTER TABLE  `invites`  MODIFY COLUMN  `time_invited` int(10) NOT NULL  AFTER `invite`");
	foreach ($ar as $id=>$added) {
		sql_query("UPDATE invites SET time_invited=$added WHERE id=$id");
		print "Invite $id ok<br />"; flush();
	}
	print ('Table invites end<br/>');

	$ar = array();
	print ('Table messages<br/>');
	$res=sql_query("SELECT id,UNIX_TIMESTAMP(added) FROM messages");
	while(list($id,$added) = mysql_fetch_array($res)) {
		$ar[$id] = $added;
	}
	sql_query("ALTER TABLE  `messages`  MODIFY COLUMN  `added` int(10) default NULL  AFTER `receiver`");
	foreach ($ar as $id=>$added) {
		sql_query("UPDATE messages SET added=$added WHERE id=$id");
		print "Message $id ok<br />"; flush();
	}
	print ('Table messages end<br/>');

	$ar = array();
	print ('Table news<br/>');
	$res=sql_query("SELECT id,UNIX_TIMESTAMP(added) FROM news");
	while(list($id,$added) = mysql_fetch_array($res)) {
		$ar[$id] = $added;
	}
	sql_query("ALTER TABLE  `news`  MODIFY COLUMN  `added` int(10) NOT NULL  AFTER `userid`");
	foreach ($ar as $id=>$added) {
		sql_query("UPDATE news SET added=$added WHERE id=$id");
		print "News $id ok<br />"; flush();
	}
	print ('Table news end<br/>');

	$ar = array();
	print ('Table newscomments<br/>');
	$res=sql_query("SELECT id,UNIX_TIMESTAMP(added),UNIX_TIMESTAMP(editedat) FROM newscomments");
	while(list($id,$added,$editedat) = mysql_fetch_array($res)) {
		$ar[$id] = array('added'=>$added,'editedat'=>$editedat);
	}
	sql_query("ALTER TABLE  `newscomments`  MODIFY COLUMN  `added` int(10) NOT NULL  AFTER `news`");

	sql_query("ALTER TABLE  `newscomments`  MODIFY COLUMN  `editedat` int(10) NOT NULL  AFTER `editedby`");
	foreach ($ar as $id=>$arr) {
		sql_query("UPDATE newscomments SET added={$arr['added']}, editedat={$arr['editedat']} WHERE id=$id");
		print "Newsomment $id ok<br />"; flush();
	}
	print ('Table newscomments end<br/>');

	$ar = array();
	print ('Table pollcomments<br/>');
	$res=sql_query("SELECT id,UNIX_TIMESTAMP(added),UNIX_TIMESTAMP(editedat) FROM pollcomments");
	while(list($id,$added,$editedat) = mysql_fetch_array($res)) {
		$ar[$id] = array('added'=>$added,'editedat'=>$editedat);
	}
	sql_query("ALTER TABLE  `pollcomments`  MODIFY COLUMN  `added` int(10) NOT NULL  AFTER `poll`");

	sql_query("ALTER TABLE  `pollcomments`  MODIFY COLUMN  `editedat` int(10) NOT NULL  AFTER `editedby`");
	foreach ($ar as $id=>$arr) {
		sql_query("UPDATE pollcomments SET added={$arr['added']}, editedat={$arr['editedat']} WHERE id=$id");
		print "Pollcomment $id ok<br />"; flush();
	}
	print ('Table pollcomments end<br/>');

	$ar = array();
	print ('Table report<br/>');
	$res=sql_query("SELECT id,UNIX_TIMESTAMP(added) FROM report");
	while(list($id,$added) = mysql_fetch_array($res)) {
		$ar[$id] = $added;
	}
	sql_query("ALTER TABLE  `report`  MODIFY COLUMN  `added` int(10) NOT NULL  AFTER `motive`");
	foreach ($ar as $id=>$added) {
		sql_query("UPDATE report SET added=$added WHERE id=$id");
		print "Report $id ok<br />"; flush();
	}
	print ('Table report end<br/>');

	$ar = array();
	print ('Table reqcomments<br/>');
	$res=sql_query("SELECT id,UNIX_TIMESTAMP(added),UNIX_TIMESTAMP(editedat) FROM reqcomments");
	while(list($id,$added,$editedat) = mysql_fetch_array($res)) {
		$ar[$id] = array('added'=>$added,'editedat'=>$editedat);
	}
	sql_query("ALTER TABLE  `reqcomments`  MODIFY COLUMN  `added` int(10) NOT NULL  AFTER `request`");

	sql_query("ALTER TABLE  `reqcomments`  MODIFY COLUMN  `editedat` int(10) NOT NULL  AFTER `editedby`");
	foreach ($ar as $id=>$arr) {
		sql_query("UPDATE reqcomments SET added={$arr['added']}, editedat={$arr['editedat']} WHERE id=$id");
		print "reqcomment $id ok<br />"; flush();
	}
	print ('Table reqcomments end<br/>');

	$ar = array();
	print ('Table requests<br/>');
	$res=sql_query("SELECT id,UNIX_TIMESTAMP(added) FROM requests");
	while(list($id,$added) = mysql_fetch_array($res)) {
		$ar[$id] = $added;
	}
	sql_query("ALTER TABLE  `requests`  MODIFY COLUMN  `added` int(10) NOT NULL  AFTER `descr`");
	foreach ($ar as $id=>$added) {
		sql_query("UPDATE requests SET added=$added WHERE id=$id");
		print "request $id ok<br />"; flush();
	}
	print ('Table requests end<br/>');

	$ar = array();
	print ('Table snatched<br/>');
	sql_query("UPDATE snatched SET startedat=UNIX_TIMESTAMP(startdat)");
	sql_query("ALTER TABLE  `snatched`  DROP COLUMN  `startdat`");
	$res=sql_query("SELECT id,UNIX_TIMESTAMP(last_action),UNIX_TIMESTAMP(completedat) FROM snatched");
	while(list($id,$last_action,$completedat) = mysql_fetch_array($res)) {
		$ar[$id] = array('last_action'=>$last_action,'completedat'=>$completedat);
	}
	sql_query("ALTER TABLE  `snatched`  ADD COLUMN  `startedat` int(10) default NULL  AFTER `last_action`");

	sql_query("ALTER TABLE  `snatched`  MODIFY COLUMN  `completedat` int(10) NOT NULL  AFTER `startedat`");
	foreach ($ar as $id=>$arr) {
		sql_query("UPDATE snatched SET last_action={$arr['last_action']}, completedat={$arr['completedat']} WHERE id=$id");
		print "Snatch $id ok<br />"; flush();
	}
	print ('Table snatched end<br/>');

	$ar = array();
	print ('Table torrents<br/>');
	/////////////////////////////////////////// FUCK MY BRAIN!!! YOHOHOHOHOHOHOH!!!! ///////////////
	$res=sql_query("SELECT id,UNIX_TIMESTAMP(added),UNIX_TIMESTAMP(last_action),UNIX_TIMESTAMP(last_reseed) FROM torrents");
	while(list($id,$added, $last_action,$last_reseed) = mysql_fetch_array($res)) {
		$ar[$id] = array('added'=>$added,'last_action'=>$last_action,'last_reseed'=>$last_reseed);
	}
	sql_query("ALTER TABLE  `torrents`  MODIFY COLUMN  `added` int(10) NOT NULL default '0'  AFTER `size`");

	sql_query("ALTER TABLE  `torrents`  MODIFY COLUMN  `last_action` int(10) NOT NULL default '0'  AFTER `remote_seeders`");

	sql_query("ALTER TABLE  `torrents`  MODIFY COLUMN  `last_reseed` int(10) NOT NULL default '0'  AFTER `last_action`");
	foreach ($ar as $id=>$arr) {
		sql_query("UPDATE torrents SET added={$arr['added']}, last_action={$arr['last_action']}, last_reseed={$arr['last_reseed']} WHERE id=$id");
		print "Torrent $id ok<br />"; flush();
	}
	print ('Table torrents end<br/>');

	$ar = array();
	print ('Table users<br/>');
	/////////////////////////////////////////// FUCK MY BRAIN!!! YOHOHOHOHOHOHOH!!!! ///////////////
	$res=sql_query("SELECT id,UNIX_TIMESTAMP(added),UNIX_TIMESTAMP(last_login),UNIX_TIMESTAMP(last_access),UNIX_TIMESTAMP(warneduntil) FROM users");
	while(list($id,$added, $last_login,$last_access,$warneduntil) = mysql_fetch_array($res)) {
		$ar[$id] = array('added'=>$added,'last_login'=>$last_login,'last_access'=>$last_access,'warneduntil'=>$warneduntil);
	}
	sql_query("ALTER TABLE  `users`  MODIFY COLUMN  `added` int(10) NOT NULL default '0'  AFTER `confirmed`");

	sql_query("ALTER TABLE  `users`  MODIFY COLUMN  `last_login` int(10) NOT NULL default '0'  AFTER `added`");

	sql_query("ALTER TABLE  `users`  MODIFY COLUMN  `last_access` int(10) NOT NULL default '0'  AFTER `last_login`");

	sql_query("ALTER TABLE  `users`  MODIFY COLUMN  `warneduntil` int(10) NOT NULL  AFTER `warned`");
	foreach ($ar as $id=>$arr) {
		sql_query("UPDATE users SET added={$arr['added']}, last_login={$arr['last_login']}, last_access={$arr['last_access']}, warneduntil={$arr['warneduntil']} WHERE id=$id");
		print "User $id ok<br />"; flush();
	}
	print ('Table users end<br/>');


	print($lang['ok'].'<hr/>');
	print $lang['next_step_update_db'];
	print $lang['step6_descr'];
	cont(6);

}

elseif ($step==6) {
	$res = sql_query("SELECT id,images FROM torrents");

	while ($row = mysql_fetch_assoc($res)) {
		if ($row['images']) {
			$imgs = array();
			$row['images'] = explode(',',$row['images']);
			foreach ($row['images'] as $key=>$img) {
				$img = $CACHEARRAY['defaultbaseurl'].'/torrents/images/'.$img;
				$imgs[] = $img;
			}
			$imgs = implode(',',$imgs);
			var_dump($imgs);
			sql_query("UPDATE torrents SET images='{$imgs}' WHERE id={$row['id']}");
			print('Images for torrent id '.$row['id'].' updated<br/>'); flush();
		}
	}
	print($lang['ok'].'<hr/>');
	print $lang['step7_descr'];
	cont(7);

}

elseif ($step==7) {
	require_once(ROOT_PATH.'include/benc.php');
	chdir(ROOT_PATH.'torrents');
	foreach (glob('*.torrent') as $fn) {
		$dict = bdec_file($fn, (1024*1024));
		unlink($fn);
		$fp = fopen($fn, "w");
		if ($fp)
		{
			@fwrite($fp, benc($dict['value']['info']), strlen(benc($dict['value']['info'])));
			fclose($fp);
			print ('Torrent '. $fn.' writed<br/>');
			flush();
		}
	}
	print($lang['ok'].'<hr/>');
	cont(8);
}
elseif ($step==8) {
	$CACHE->ClearAllCache();
	print $lang['install_complete'];
	print $lang['install_notice'];
	print $lang['donate'];
}


footers();
?>
