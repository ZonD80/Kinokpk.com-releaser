<?php
if(!defined("IN_TRACKER")) die("Direct access to this page not allowed");
$REL_LANG->load('blocks');
?>
</head>
<body>
<table width="100%" class="clear" align="center" cellspacing="0"
	cellpadding="0" style="background: transparent;">
	<tr>
		<td style="border: none;">
		<table width="100%" class="clear" style="background: transparent;">
			<tr>
				<td style="border: none;"><a
					href="<?=$REL_CONFIG['defaultbaseurl']?>"><img style="border: none"
					alt="<?=$REL_CONFIG['sitename']?>"
					title="<?=$REL_CONFIG['sitename']?>"
					src="./themes/<?=$ss_uri;?>/images/logo.png" /></a></td>
				<td style="border: none;">
				<h1><?=$REL_CONFIG['sitename']?></h1>
				<?=$REL_CONFIG['description']?></td>
			</tr>
		</table>
		</td>
	</tr>
</table>
<!-- Top Navigation Menu for unregistered-->
<table width="100%" align="center" cellspacing="0" cellpadding="0">
	<tr>
		<td class="menu"
			style='border-style: solid none none none; padding-left: 10px;'>
		<div class="top" id="topmenu"><a
			href="<?=$REL_CONFIG['defaultbaseurl']?>"><span><?=$REL_LANG->say_by_key('homepage');?></span></a>
		<a href="browse.php"><span><?=$REL_LANG->say_by_key('browse');?></span></a> <a
			href="browse.php?unchecked"><span><?=$REL_LANG->say_by_key('test_releaser');?></span></a>
			<? if ($CURUSER) { ?> <a href="upload.php"><span><?=$REL_LANG->say_by_key('upload');?></span></a>
			<? } else { ?> <a href="login.php"><span>Вход</span></a> <a
			href="signup.php"><span>Регистрация</span></a> <? } ?> <? if ($CURUSER) { ?>
		<a href="pagebrowse.php"><span><?=$REL_LANG->say_by_key('pages');?></span></a>
		<a href="<?=$REL_CONFIG['forumurl']?>/index.php"><span><?=$REL_LANG->say_by_key('forum')." ".$REL_CONFIG['forumname'];?></span></a>
		<? } ?> <a href="rules.php"><span><?=$REL_LANG->say_by_key('rules');?></span></a>
		<a href="faq.php"><span><?=$REL_LANG->say_by_key('faq');?></span></a> <? if ($CURUSER) { ?>
		<a href="copyrights.php"><span>Report Abuse</span></a> <a
			href="peers.php"><span>Статистика пиров</span></a> <a
			href="staff.php"><span><?=$REL_LANG->say_by_key('staff');?></span></a> <? }
			if (get_user_class()>=UC_MODERATOR)
			print '<a href="admincp.php"><span>AdminCP</span></a>';
			?></div>
		</td>
	</tr>
</table>
<!-- Top Navigation Menu for unregistered-->

<!--  some vars for the statusbar;o) -->

			<? if ($CURUSER) {

				if ($CURUSER['donor'])
				$medaldon = "<img src=\"pic/star.gif\" alt=\"Донор\" title=\"Донор\"/>";
				if ($CURUSER['warned'])
				$warn = "<img src=\"pic/warned.gif\" alt=\"Предупрежден\" title=\"Предупрежден\"/>";

				if (get_user_class() >= UC_MODERATOR) $usrclass = "&nbsp;<a href=\"setclass.php\"><img src=\"pic/warning.gif\" title=\"".get_user_class_name($CURUSER['class'])."\" alt=\"".get_user_class_name($CURUSER['class'])."\" border=\"0\"/></a>&nbsp;";

				if ($CURUSER['unread'])
				$inboxpic = "<img height=\"16\" style=\"border:none\" alt=\"inbox\" title=\"Есть новые сообщения\" src=\"pic/pn_inboxnew.gif\"/>";
				else
				$inboxpic = "<img height=\"16\" style=\"border:none\" alt=\"inbox\" title=\"Нет новых сообщений\" src=\"pic/pn_inbox.gif\"/>";
			}
			?>
<!--  start the statusbar -->
<table width="100%" style="border-style: none" align="center"
	cellpadding="0" cellspacing="0">
	<tr>
		<td height="32" style="background-image: url(./themes/<?=$ss_uri;?>/images/mem.png)" class="bottom" >&nbsp;&nbsp;
		<? if ($CURUSER) { ?> <font color="black"><?=$REL_LANG->say_by_key('welcome_back');?></font><a
			href="userdetails.php?id=<?=$CURUSER['id']?>" class="online"><?=get_user_class_color($CURUSER['class'], $CURUSER['username'])?></a><?=$usrclass?><?=$medaldon?><?=$warn?>
		<font color="black"><a href="logout.php" class="black">(Выйти)</a></font>&nbsp;&nbsp;
		<font color="#1900D1">Рейтинг:&nbsp;<?=ratearea($CURUSER['ratingsum'],$CURUSER['id'],'users',$CURUSER['id']);?></font>&nbsp;&nbsp;
		<?php
		print("<span class=\"smallfont\"><a href=\"message.php\">$inboxpic</a>&nbsp;".count($CURUSER['inbox'])." (".count($CURUSER['unread']).")</span>");
		print("<span class=\"smallfont\">&nbsp;&nbsp;<a href=\"message.php?action=viewmailbox&amp;box=-1\"><img height=\"16\" style=\"border:none\" alt=\"Отправленые\" title=\"Отправленые\" src=\"pic/pn_sentbox.gif\"/></a>&nbsp;".count($CURUSER['outbox'])."</span>");

		print("<span class=\"smallfont\">&nbsp;<a href=\"friends.php\"><img style=\"border:none\" alt=\"Друзья\" title=\"Друзья\" src=\"pic/buddylist.gif\"/></a>");
		print("&nbsp;<a href=\"rss.php\"><img style=\"border:none\" alt=\"RSS\" title=\"RSS\" src=\"pic/rss.gif\"/></a>");
		print("&nbsp;<a href=\"atom.php\"><img style=\"border:none\" alt=\"ATOM\" title=\"ATOM\" src=\"pic/atom.gif\"/></a></span>");
		}
		print("</td></tr></table>\n");

		?> <!-- end statusbar --> <?php
		$w = "width=\"100%\"";
		?>
		<table class="mainouter" align="center" <?=$w; ?> cellspacing="0"
			cellpadding="5">
			<tr>

				<!-- MENU -->

				<td class="tt" width="155" style="vertical-align: top;"><?
				show_blocks("l");

				?></td>
				<td class="tt"
					style="padding-top: 5px; padding-bottom: 5px; vertical-align: top;">
					<?

					if ($_COOKIE['override_class'] && $CURUSER) // Second condition needed so that this box isn't displayed for non members/logged out members.
{
		print("<div align=\"center\"><table border=\"0\" cellspacing=\"0\" cellpadding=\"10\" bgcolor=\"green\"><tr><td style=\"padding: 10px; background: white; border: 1px solid #3B5998;\">\n");
		print("<b><a href=\"".$REL_SEO->make_link('restoreclass')."\"><font color=\"#3B5998\">".$REL_LANG->say_by_key('lower_class')."</font></a></b>");
		print("</td></tr></table></div>\n");
}

					show_blocks('c');

					?>