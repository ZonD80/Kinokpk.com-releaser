<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="ru" lang="ru">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=windows-1251" />
<meta name="Description" content="{$REL_CONFIG.description}{$descradd}" />
<meta name="Keywords" content="{$keywordsadd}{$REL_CONFIG.keywords}" />
<base href="{$REL_CONFIG.defaultbaseurl}/" />
<!--Тоже любишь смотреть исходники HTML? Знаешь еще и PHP/MySQL? обратись к админам, наверняка для тебя есть местечко в нашей команде http://www.kinokpk.com/staff.php -->
<title>{$title}</title>
<link rel="stylesheet" href="themes/{$REL_CONFIG.ss_uri}/main.css" type="text/css"/>
<link rel="stylesheet" href="css/features.css" type="text/css"/>
<link rel="stylesheet" href="css/jquery.jgrowl.css" type="text/css"/>
<link rel="stylesheet" href="css/jquery.facebox.css" type="text/css"/>
<link rel="stylesheet" href="css/link/jquery.linkselect.style.select.css" type="text/css"/>
<!--[if IE]>
<link rel="stylesheet" href="css/features_ie.css" type="text/css"/>
<![endif]-->
<link rel="alternate" type="application/rss+xml" title="RSS" href="{$REL_SEO->make_link('rss')}" />
<link rel="alternate" type="application/atom+xml" title="Atom" href="{$REL_SEO->make_link('atom')}" />
<link rel="shortcut icon" href="favicon.ico" type="image/x-icon" />
<script language="javascript" type="text/javascript" src="js/resizer.js"></script>
{if !$CURUSER || $CURUSER.extra_ef}
<!--<script language="javascript" type="text/javascript" src="js/snow.js"></script>-->
{/if}
<script language="javascript" type="text/javascript" src="js/jquery.js"></script>
<script language="javascript" type="text/javascript" src="js/jquery.history.js"></script>
<script language="javascript" type="text/javascript" src="js/jquery.cookie.js"></script>
<script language="javascript" type="text/javascript" src="js/facebox.min.js"></script>
<script language="javascript" type="text/javascript" src="js/jquery.jgrowl_minimized.js"></script>
<script language="javascript" type="text/javascript" src="js/coding.js"></script>
<script language="javascript" type="text/javascript" src="js/features.js"></script>
<script language="javascript" type="text/javascript" src="js/swfobject.js"></script>
<script language="javascript" type="text/javascript" src="js/paginator3000.js"></script>
<script language="javascript" type="text/javascript" src="js/jquery.utils.js"></script>
<script language="javascript" type="text/javascript" src="js/blocks.js"></script>
<!--Выпадающее меню-->
<script language="javascript" type="text/javascript" src="themes/{$REL_CONFIG.ss_uri}/js/topmenu.js"></script>
{$headadd}
<!--[if lte IE 7]>
<font color="red" size="5">Тебя приветствуют твои предки, %username%! Похоже, ты используешь Internet Explorer, над которым мы смеялись еще в лохматые годы. Мы, твои предки, %username%, предлагаем тебе <a href="http://ie.yandex.ru/">обновиться до самой свежей версии</a>, дабы получать вселенское удовольствие от использования этого прекрасного и милого сайта. Спасибо!</font>
<![endif]-->
<!-- Theme as it is -->
</head>
<body>
<h1>TESTING LABORATORY #1, DO NOT REGISTER HERE</h1>
{if $OFFLINE}
<h1>Site offline!</h1>
{/if}
<table border="0" class="index bottom">
<tr>
  <td valign="top" class="itd bottom"><div class="tl"></div></td>
  <td valign="top" class="itdc bottom"><div class="index2">
    <div class="header1">
      <div class="header3">
        <div class="header2">
          <div class="logo"><a href="{$REL_CONFIG.defaultbaseurl}"><img src="themes/{$REL_CONFIG.ss_uri}/images/spacer.gif" height="60" width="217" border="0" alt="{$REL_LANG->say_by_key('tbhome')}" title="{$REL_LANG->say_by_key('tbhome')}" /></a></div>
          <div class="cp"></div>
          <div class="banner"><a href="contact.php"><img src="http://dev.kinokpk.com/images/b468_dev.jpg" border="0" alt="ТБ" /></a></div>
        </div>
      </div>
    </div>
    <!--Меню-->
    <div class="tmenur">
      <ul class="topmenu" id="topmenu">
        <li><a href="{$REL_CONFIG.defaultbaseurl}/">{$REL_LANG->say_by_key('homepage')}</a></li>
        <li><a rel="tmlnk3" href="{$REL_SEO->make_link('browse')}">{$REL_LANG->say_by_key('browse')}</a></li>
        <li><a rel="tmlnk4" href="#">Меню</a></li>
        {if $CURUSER}    
        <li><a rel="tmlnk1" href="{$REL_SEO->make_link('my')}">{$REL_LANG->say_by_key('user_menu')}</a></li>
        <li><a rel="tmlnk2" href="{$REL_SEO->make_link('staff')}">{$REL_LANG->say_by_key('staff')}</a></li>
         <li><a rel="tmlnk5"  href="{$REL_SEO->make_link('mynotifs')}">{$REL_LANG->_("Notifications")}{if $REL_NOTIFS.total} ({$REL_NOTIFS.total}){/if}</a></li>    

        {else}
        <li><a rel="tmlnk1" href="{$REL_SEO->make_link('signup')}">{$REL_LANG->say_by_key('signup')}</a></li>
        <li><a rel="tmlnk1" href="{$REL_SEO->make_link('login')}">{$REL_LANG->say_by_key('login')}</a></li>
        {/if}
      </ul>
    </div>
      {if $CURUSER}
    <div id="tmlnk1" class="submenu">
      <ul class="reset">
        <li><a href="{$REL_SEO->make_link('my')}"><span>{$REL_LANG->say_by_key('account_settings')}</span></a></li>
        <li><a href="{$REL_SEO->make_link('bookmarks')}"><span>{$REL_LANG->say_by_key('bookmarks')}</span></a></li>
        <li><a href="{$REL_SEO->make_link('myrating')}"><span>{$REL_LANG->say_by_key('my_rating')}</span></a></li>
        <li><a href="{$REL_SEO->make_link('present')}"><span>{$REL_LANG->say_by_key('gifts_friends')}</span></a></li>
        <li><a href="{$REL_SEO->make_link('mytorrents')}"><span>{$REL_LANG->say_by_key('mine_torrents')}</span></a></li>
        <li><a href="{$REL_SEO->make_link('subnet')}"><span>{$REL_LANG->say_by_key('neighbours')}</span></a></li>
        <li><a href="{$REL_SEO->make_link('friends')}"><span>{$REL_LANG->say_by_key('personal_lists')}</span></a></li>
        <li><a href="{$REL_SEO->make_link('invite')}"><span>{$REL_LANG->say_by_key('invite')}</span></a></li>
        <li><a href="{$REL_SEO->make_link('users')}"><span>{$REL_LANG->say_by_key('users')}</span></a></li>
        <li><a href="{$REL_SEO->make_link('logout')}"><span>{$REL_LANG->say_by_key('logout')}</span></a></li>
      </ul>
    </div>
    {/if}
    {if $IS_MODERATOR}
    <div id="tmlnk2" class="submenu">
      <ul class="reset">
        <li><a href="{$REL_SEO->make_link('admincp')}"><span>Админка</span></a></li>
      </ul>
    </div>
    {/if}
    <div id="tmlnk3" class="submenu">
      <ul class="reset">
        <li><a href="{$REL_SEO->make_link('upload')}"><span>{$REL_LANG->say_by_key('upload_torrent')}</span></a></li>
        <li><a href="{$REL_SEO->make_link('relgroups')}"><span>{$REL_LANG->say_by_key('relgroups')}</span></a></li>
      </ul>
    </div>
    <div id="tmlnk4" class="submenu">
      <ul class="reset">
        <li><a href="#"><span>{$REL_LANG->say_by_key('rules')}</span></a></li>
        <li><a href="{$REL_SEO->make_link('faq')}"><span>{$REL_LANG->say_by_key('faq')}</span></a></li>
        <li><a href="{$REL_SEO->make_link('topten')}"><span>{$REL_LANG->say_by_key('topten')}</span></a></li>
      </ul>
    </div>
                    <div id="tmlnk5" class="submenu">
          <ul class="reset">
	{foreach from=$REL_NOTIFS.notifs key=notify item=ncount}
		<li><a href="{if $notify<>'unread'}{$REL_SEO->make_link('mynotifs','type',$notify)}{else}{$REL_SEO->make_link('message')}{/if}">{$REL_LANG->_(ucfirst($notify))}: {$ncount}</a></li>
		{/foreach}
          </ul>
        </div>
    <script type="text/javascript">cssdropdown.startchrome("topmenu")</script>
    <!--/Меню-->
    <div class="search" align="right">
      <form action="browse.php" method="get" name="search">
        <input class="search_input" type="text" value="&nbsp;Поиск..." size="15" alt="Поиск" maxlength="40" name="search" />
        <div class="search_bottom">
          <input value="Поиск" type="image" src="themes/{$REL_CONFIG.ss_uri}/images/spacer.gif" style="height:25px; width:30px; border:0px;" />
        </div>
      </form>
    </div>
    <div class="b4">
    <div class="b6">
    <div class="b5">
    <div class="cont">
    <table width="100%" border="0" class="bottom" style="vertical-align:top;">
    <tr>
      {*<!--<td class="bottom" width="200" valign="top"> {show_blocks('l')} </td>-->*}
      <td valign="top" class="bottom"> {if $access_overrided}
        <div align="center">
          <table border="0" cellspacing="0" cellpadding="10" bgcolor="green">
            <tr>
              <td style="padding: 10px; background: white; border: 1px solid #3B5998;"><b><a href="{$REL_SEO->make_link('restoreclass')}"><font color="#3B5998">{$REL_LANG->say_by_key('lower_class')}</font></a></b> </td>
            </tr>
          </table>
        </div>
        {/if}
        {show_blocks('t')}