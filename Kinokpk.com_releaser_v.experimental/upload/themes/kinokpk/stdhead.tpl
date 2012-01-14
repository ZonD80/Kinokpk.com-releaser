<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="ru" lang="ru">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta name="Description" content="{$REL_CONFIG.description}{$descradd}" />
<meta name="Keywords" content="{$keywordsadd}{$REL_CONFIG.keywords}" />
<base href="{$REL_CONFIG.defaultbaseurl}/" />
<title>{$title} | {$REL_CONFIG.sitename}</title>
<script language="javascript" type="text/javascript" src="js/resizer.js"></script>
{if !$CURUSER || $CURUSER.extra_ef}
<!--<script language="javascript" type="text/javascript" src="js/snow.js"></script>-->
{/if}
<script language="javascript" type="text/javascript" src="js/jquery.js"></script>
<script language="javascript" type="text/javascript"
	src="js/jquery.history.js"></script>
<script language="javascript" type="text/javascript"
	src="js/jquery.cookie.js"></script>
<script language="javascript" type="text/javascript"
	src="js/facebox.min.js"></script>
<script language="javascript" type="text/javascript"
	src="js/jquery.jgrowl_minimized.js"></script>
<script language="javascript" type="text/javascript"
	src="js/jquery.scrollTo-min.js"></script>
<script language="javascript" type="text/javascript" src="js/coding.js"></script>
<script language="javascript" type="text/javascript"
	src="js/features.js"></script>
<script language="javascript" type="text/javascript"
	src="js/swfobject.js"></script>
<script language="javascript" type="text/javascript"
	src="js/jquery.paginator3000.js"></script>
<script language="javascript" type="text/javascript" src="js/blocks.js"></script>
<script language="javascript" type="text/javascript" src="js/json.js"></script>
<link rel="stylesheet" href="css/features.css" type="text/css" />
<link rel="stylesheet" href="css/jquery.jgrowl.css" type="text/css" />
<link rel="stylesheet" href="css/jquery.facebox.css" type="text/css" />
<!--[if IE]>
<link rel="stylesheet" href="css/features_ie.css" type="text/css"/>
<![endif]-->
<link rel="stylesheet"
	href="themes/{$REL_CONFIG.ss_uri}/{$REL_CONFIG.ss_uri}.css"
	type="text/css" />
<link rel="alternate" type="application/rss+xml" title="RSS"
	href="{$REL_SEO->make_link('rss')}" />
<link rel="alternate" type="application/atom+xml" title="Atom"
	href="{$REL_SEO->make_link('atom')}" />
<link rel="shortcut icon" href="favicon.ico" type="image/x-icon" />

{$headadd}
<!--[if IE 7]>
		<script type="text/javascript">  
			var IE6UPDATE_OPTIONS = {
				icons_path: "pic/ie6update/"
			}
			</script>
		<script type="text/javascript" src="js/ie6update.js"></script>
		<![endif]-->

{generate_lang_js()}
</head>
<body>
{if $OFFLINE}
<h1>Site offline!</h1>
{/if}
<div class="maxmh">

<div class="head">

<div id="header" align="left">
<div id="logo">
<ul style="padding-left: 0px; margin-left: 0px;">
	<li class="logo_menu" style="height: 30px;"><a
		href="{$REL_CONFIG.defaultbaseurl}" class="logo_link"><img
		class="imglogo" src="/themes/kinokpk/images/ewafcwswrfc.gif"
		align="top" alt="{$REL_LANG->say_by_key('tbhome')}"
		title="{$REL_LANG->say_by_key('tbhome')}" /></a></li>
</ul>
</div>
<div id="left_menu">

<ul id="csstopmenu_1">

	{if $CURUSER}
	<li class="mainitems">
	<div class="headerlinks">
	<h2><a href="{$REL_CONFIG.defaultbaseurl}/">{$REL_LANG->say_by_key('homepage')}</a></h2>
	</div>
	</li>
	{/if}
	<li class="mainitems">
	<div class="headerlinks">
	<h2><a href="{$REL_SEO->make_link('forum')}">{$REL_LANG->say_by_key('forum')}</a></h2>
	</div>
	</li>
	{if $CURUSER}
		<li class="mainitems">
	<div class="headerlinks">
	<h2><a href="{$REL_SEO->make_link('chat')}">{$REL_LANG->_('Chat')}</a></h2>
	</div>
	</li>
	<li class="mainitems">
	<div class="headerlinks">
	<h2><a href="#">{$REL_LANG->say_by_key('menu')}</a></h2>
	</div>
	<ul id="submenus">
		<li><!-- <a href="forums.php"><img class="images" src="./themes/{$REL_CONFIG.ss_uri}/images/forum.png" align="top" alt="" />{$REL_LANG->say_by_key('forum')}</a></li> -->
		<a href="{$REL_SEO->make_link('rules')}"><img class="images"
			src="./themes/{$REL_CONFIG.ss_uri}/images/rules.png" align="top"
			alt="" />{$REL_LANG->say_by_key('rules')}</a></li>
		<li><a href="{$REL_SEO->make_link('faq')}"><img class="images"
			src="./themes/{$REL_CONFIG.ss_uri}/images/faq.png" align="top" alt="" />{$REL_LANG->say_by_key('faq')}</a></li>
		<li><a href="{$REL_SEO->make_link('topten')}"><img class="images"
			src="./themes/{$REL_CONFIG.ss_uri}/images/top.png" align="top" alt="" />{$REL_LANG->say_by_key('topten')}</a></li>
		<li><a href="{$REL_SEO->make_link('staff')}"><img class="images"
			src="./themes/{$REL_CONFIG.ss_uri}/images/staff.png" align="top"
			alt="" />{$REL_LANG->say_by_key('staff')}</a></li>
		<li><a href="{$REL_SEO->make_link('users')}"><img class="images"
			src="./themes/{$REL_CONFIG.ss_uri}/images/users.png" align="top"
			alt="" />{$REL_LANG->say_by_key('users')}</a></li>
	</ul>
	</li>
	<li class="mainitems">
	<div class="headerlinks">
	<h2><a href="#">{$REL_LANG->say_by_key('messages')}</a></h2>
	</div>
	<ul id="submenus2">
		<li><a href="{$REL_SEO->make_link('message')}"><img class="images"
			src="./themes/{$REL_CONFIG.ss_uri}/images/inbox.png" align="top"
			alt="" />{$REL_LANG->say_by_key('inbox')}</a></li>
		<li><a
			href="{$REL_SEO->make_link('message','action','viewmailbox','box',-1)}"><img
			class="images" src="./themes/{$REL_CONFIG.ss_uri}/images/outbox.png"
			align="top" alt="" />{$REL_LANG->say_by_key('outbox')}</a></li>
	</ul>
	</li>
	{/if}
	<li class="mainitems">
	<div class="headerlinks">
	<h2><a href="{$REL_SEO->make_link('browse')}">{$REL_LANG->say_by_key('browse')}</a></h2>
	</div>
	<ul id="submenus_1">
		<li><a href="{$REL_SEO->make_link('browse')}"><img class="images"
			src="themes/{$REL_CONFIG.ss_uri}/images/video.png" align="top" alt="" />&nbsp;{$REL_LANG->say_by_key('browse_download')}</a></li>
		<li><a href="{$REL_SEO->make_link('upload')}"><img class="images"
			src="./themes/{$REL_CONFIG.ss_uri}/images/upload.png" align="top"
			alt="" />&nbsp;{$REL_LANG->say_by_key('upload_torrent')}</a></li>
		<li><a href="{$REL_SEO->make_link('relgroups')}"><img class="images"
			src="./themes/{$REL_CONFIG.ss_uri}/images/group.gif" align="top"
			alt="" />&nbsp;{$REL_LANG->say_by_key('relgroups')}</a></li>
		<li><a href="{$REL_SEO->make_link('bookmarks')}"><img class="images"
			src="./themes/{$REL_CONFIG.ss_uri}/images/bookmarks.png" align="top"
			alt="" />{$REL_LANG->say_by_key('bookmarks')}</a></li>
	</ul>
	</li>
	{if !$CURUSER}
	<li class="mainitems">
	<div class="headerlinks">
	<h2><a href="{$REL_SEO->make_link('signup')}">{$REL_LANG->say_by_key('signup')}</a></h2>
	</div>
	</li>
	<li class="mainitems">
	<div class="headerlinks">
	<h2><a href="{$REL_SEO->make_link('login')}">{$REL_LANG->say_by_key('login')}</a></h2>
	</div>
	</li>
	{/if}
</ul>
</div>

<div id="right_menu">{if $CURUSER}

<ul id="csstopmenu_2" style="margin-top: 0px;">
	<li><a href="#" id="myAccount">{$REL_LANG->say_by_key('account')}</a> <!-- BEGIN HIDDEN UL -->
	<ul id="myOptions">
		<li><a class="name"
			href="{$REL_SEO->make_link('userdetails','id',$CURUSER.id,'name',translit($CURUSER.username))}"><img
			src="{$CURUSER.avatar}" alt="{$CURUSER.username}" />{$CURUSER.username}</a></li>
		{if $IS_MODERATOR}
		<li><a href="{$REL_SEO->make_link('admincp')}"><img class="images"
			src="./themes/{$REL_CONFIG.ss_uri}/images/options.png" align="top"
			alt="" />{$REL_LANG->say_by_key('Admin')}</a></li>
		{/if}
		<li><a href="{$REL_SEO->make_link('my')}"><img class="images"
			src="./themes/{$REL_CONFIG.ss_uri}/images/options.png" align="top"
			alt="" />{$REL_LANG->say_by_key('account_settings')}</a></li>
		<li><a href="{$REL_SEO->make_link('bookmarks')}"><img class="images"
			src="./themes/{$REL_CONFIG.ss_uri}/images/bookmarks.png" align="top"
			alt="" />{$REL_LANG->say_by_key('bookmarks')}</a></li>
		<li><a href="{$REL_SEO->make_link('myrating')}"><img class="images"
			src="./themes/{$REL_CONFIG.ss_uri}/images/bonus.png" align="top"
			alt="" />{$REL_LANG->say_by_key('my_rating')}</a></li>
		<li><a href="{$REL_SEO->make_link('present')}"><img class="images"
			src="./themes/{$REL_CONFIG.ss_uri}/images/present.png" align="top"
			alt="" />{$REL_LANG->say_by_key('gifts_friends')}</a></li>
		<li><a href="{$REL_SEO->make_link('invite')}"><img class="images"
			src="./themes/{$REL_CONFIG.ss_uri}/images/add.png" align="top" alt="" />{$REL_LANG->say_by_key('invite')}</a></li>
		<li><a href="{$REL_SEO->make_link('users')}"><img class="images"
			src="./themes/{$REL_CONFIG.ss_uri}/images/users.png" align="top"
			alt="" />{$REL_LANG->say_by_key('users')}</a></li>
		<li><a href="{$REL_SEO->make_link('friends')}"><img class="images"
			src="./themes/{$REL_CONFIG.ss_uri}/images/friends.png" align="top"
			alt="" />{$REL_LANG->say_by_key('personal_lists')}</a></li>
		<li><a href="{$REL_SEO->make_link('subnet')}"><img class="images"
			src="./themes/{$REL_CONFIG.ss_uri}/images/neighbour.png" align="top"
			alt="" />{$REL_LANG->say_by_key('neighbours')}</a></li>
		<li><a href="{$REL_SEO->make_link('mytorrents')}"><img class="images"
			src="./themes/{$REL_CONFIG.ss_uri}/images/mytorrents.png" align="top"
			alt="" />{$REL_LANG->say_by_key('mine_torrents')}</a></li>
		<li><a href="{$REL_SEO->make_link('logout')}"><img class="images"
			src="./themes/{$REL_CONFIG.ss_uri}/images/logout.gif" align="top"
			alt="" />{$REL_LANG->say_by_key('logout')}</a></li>

	</ul>
	<!-- END HIDDEN UL --></li>


</ul>
{/if}</div>
<div id="search">
<form method="get" action="{$REL_SEO->make_link('browse')}">
<p><input type="text" class="search_text" name="search"
	onblur="if(this.value=='')this.style.background='#fff url(/themes/kinokpk/images/search.gif) 3px center  no-repeat';"
	onfocus="this.style.background='#fff';" id="text" /></p>
<p><input class="search_buttom" value="" name="" type="image"
	src="themes/{$REL_CONFIG.ss_uri}/images/search.png" /></p>
</form>
</div>

<div class="clear"></div>
</div>
</div>

<div id="pagecontent">


<table class="mainouter" align="center" width="100%" cellspacing="0" cellpadding="5">
	<tr>

<td valign="top" class="blocks_r" width="200px">{show_blocks('l')}</td>



		<td align="center" valign="top" class="blocks_c">{show_blocks('t')}
