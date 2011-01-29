<?php
global $CURUSER, $REL_LANG, $REL_CONFIG, $REL_SEO;
if (!defined('BLOCK_FILE')) {
	safe_redirect(" ../".$REL_SEO->make_link('index'));
	exit;
}

if ($CURUSER)  $content = "<a class=\"menu\" href=\"".$REL_SEO->make_link('my')."\">&nbsp;".$REL_LANG->say_by_key('account_settings')."</a>"
."<a class=\"menu\" href=\"".$REL_SEO->make_link('userdetails','id',$CURUSER['id'],'username',translit($CURUSER['username']))."\">&nbsp;".$REL_LANG->say_by_key('profile')."</a>"
."<a class=\"menu\" href=\"".$REL_SEO->make_link('myrating')."\">&nbsp;".$REL_LANG->say_by_key('my_rating')."</a>"
."<a class=\"menu\" href=\"".$REL_SEO->make_link('mywarned')."\">&nbsp;".$REL_LANG->say_by_key('my_warnings')."</a>"
."<a class=\"menu\" href=\"".$REL_SEO->make_link('invite')."\">&nbsp;".$REL_LANG->say_by_key('invite')."</a>"
."<a class=\"menu\" href=\"".$REL_SEO->make_link('users')."\">&nbsp;".$REL_LANG->say_by_key('users')."</a>"
."<a class=\"menu\" href=\"".$REL_SEO->make_link('friends')."\">&nbsp;".$REL_LANG->say_by_key('personal_lists')."</a>"
."<a class=\"menu\" href=\"".$REL_SEO->make_link('subnet')."\">&nbsp;".$REL_LANG->say_by_key('neighbours')."</a>"
."<a class=\"menu\" href=\"".$REL_SEO->make_link('mytorrents')."\">&nbsp;".$REL_LANG->say_by_key('my_torrents')."</a>"
."<a class=\"menu\" href=\"".$REL_SEO->make_link('message')."\">&nbsp;".$REL_LANG->say_by_key('inbox_m')."</a>"
."<a class=\"menu\" href=\"".$REL_SEO->make_link('message','action','viewmailbox','box','-1')."\">&nbsp;".$REL_LANG->say_by_key('outbox_m')."</a>"
."<a class=\"menu\" href=\"".$REL_SEO->make_link('logout')."\">&nbsp;".$REL_LANG->say_by_key('logout')."!</a>"
."<a class=\"menu\" href=\"".$REL_SEO->make_link('index')."\">&nbsp;".$REL_LANG->say_by_key('homepage')."</a>"
."<a class=\"menu\" href=\"".$REL_SEO->make_link('browse','unchecked','')."\">&nbsp;".$REL_LANG->say_by_key('test_releaser')."</a>"
."<a class=\"menu\" href=\"".$REL_SEO->make_link('viewrequests')."\">&nbsp;".$REL_LANG->say_by_key('requests')."</a>"
."<a class=\"menu\" href=\"{$REL_CONFIG['forumurl']}/index.php\">&nbsp;".$REL_LANG->say_by_key('forum')." {$REL_CONFIG['forumname']}</a>"
."<a class=\"menu\" href=\"".$REL_SEO->make_link('testport')."\">&nbsp;".$REL_LANG->say_by_key('check_port')."</a>"
."<a class=\"menu\" href=\"".$REL_SEO->make_link('topten')."\">&nbsp;".$REL_LANG->say_by_key('topten')."</a>"
."<a class=\"menu\" href=\"".$REL_SEO->make_link('bookmarks')."\">&nbsp;".$REL_LANG->say_by_key('bookmarks')."</a>"
."<a class=\"menu\" href=\"".$REL_SEO->make_link('rules')."\">&nbsp;".$REL_LANG->say_by_key('rules')."</a>"
."<a class=\"menu\" href=\"".$REL_SEO->make_link('faq')."\">&nbsp;".$REL_LANG->say_by_key('faq')."</a>"
."<a class=\"menu\" href=\"".$REL_SEO->make_link('formats')."\">&nbsp;".$REL_LANG->say_by_key('formats')."</a>";
else
$content = "<center>
<a href=\"".$REL_SEO->make_link('login')."\"><font size=\"3\"><b><u>Войти</u></b></font></a><br /><br />
Вы можете использовать логин и пароль форума {$REL_CONFIG['forumname']} для авторизации.<br /><hr /><br />
У вас нет аккаунта?<br />
<a href=\"".$REL_SEO->make_link('signup')."\"><u>Зарегистрируйтесь</u></a> прямо сейчас!</center><br /><br />"

?>