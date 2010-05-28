<?php
if (!defined('BLOCK_FILE')) {
	Header("Location: ../index.php");
	exit;
}

global $CURUSER, $tracker_lang, $CACHEARRAY;

if ($CURUSER)  $content = "<a class=\"menu\" href=\"my.php\">&nbsp;".$tracker_lang['account_settings']."</a>"
."<a class=\"menu\" href=\"userdetails.php?id=".$CURUSER["id"]."\">&nbsp;".$tracker_lang['profile']."</a>"
."<a class=\"menu\" href=\"mybonus.php\">&nbsp;".$tracker_lang['my_bonus']."</a>"
."<a class=\"menu\" href=\"mywarned.php\">&nbsp;".$tracker_lang['my_warnings']."</a>"
."<a class=\"menu\" href=\"invite.php\">&nbsp;".$tracker_lang['invite']."</a>"
."<a class=\"menu\" href=\"users.php\">&nbsp;".$tracker_lang['users']."</a>"
."<a class=\"menu\" href=\"friends.php\">&nbsp;".$tracker_lang['personal_lists']."</a>"
."<a class=\"menu\" href=\"subnet.php\">&nbsp;".$tracker_lang['neighbours']."</a>"
."<a class=\"menu\" href=\"mytorrents.php\">&nbsp;".$tracker_lang['my_torrents']."</a>"
."<a class=\"menu\" href=\"message.php\">&nbsp;".$tracker_lang['inbox_m']."</a>"
."<a class=\"menu\" href=\"message.php?action=viewmailbox&amp;box=-1\">&nbsp;".$tracker_lang['outbox_m']."</a>"
."<a class=\"menu\" href=\"logout.php\">&nbsp;".$tracker_lang['logout']."!</a>"
."<a class=\"menu\" href=\"index.php\">&nbsp;".$tracker_lang['homepage']."</a>"
."<a class=\"menu\" href=\"browse.php?unchecked\">&nbsp;".$tracker_lang['test_releaser']."</a>"
."<a class=\"menu\" href=\"viewrequests.php\">&nbsp;".$tracker_lang['requests']."</a>"
."<a class=\"menu\" href=\"{$CACHEARRAY['forumurl']}/index.php\">&nbsp;".$tracker_lang['forum']." {$CACHEARRAY['forumname']}</a>"
."<a class=\"menu\" href=\"testport.php\">&nbsp;".$tracker_lang['check_port']."</a>"
."<a class=\"menu\" href=\"pages.php\">&nbsp;".$tracker_lang['pages']."</a>"
."<a class=\"menu\" href=\"topten.php\">&nbsp;".$tracker_lang['topten']."</a>"
."<a class=\"menu\" href=\"bookmarks.php\">&nbsp;".$tracker_lang['bookmarks']."</a>"
."<a class=\"menu\" href=\"rules.php\">&nbsp;".$tracker_lang['rules']."</a>"
."<a class=\"menu\" href=\"faq.php\">&nbsp;".$tracker_lang['faq']."</a>";
else
$content = "<center>
<a href=\"login.php\"><font size=\"3\"><b><u>Войти</u></b></font></a><br /><br />
Вы можете использовать логин и пароль форума {$CACHEARRAY['forumname']} для авторизации.<br /><hr /><br />
У вас нет аккаунта?<br />
<a href=\"signup.php\"><u>Зарегистрируйтесь</u></a> прямо сейчас!</center><br /><br />"

?>