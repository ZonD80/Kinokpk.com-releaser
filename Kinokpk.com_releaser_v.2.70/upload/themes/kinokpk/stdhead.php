<?php
if(!defined("IN_TRACKER")) die("Direct access to this page not allowed");
getlang('blocks');
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
					href="<?=$CACHEARRAY['defaultbaseurl']?>"><img style="border: none"
					alt="<?=$CACHEARRAY['sitename']?>"
					title="<?=$CACHEARRAY['sitename']?>"
					src="./themes/<?=$ss_uri;?>/images/logo.png" /></a></td>
				<td style="border: none;">
				<h1><?=$CACHEARRAY['sitename']?></h1>
				<?=$CACHEARRAY['description']?></td>
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
			href="<?=$CACHEARRAY['defaultbaseurl']?>"><span><?=$tracker_lang['homepage'];?></span></a>
		<a href="browse.php"><span><?=$tracker_lang['browse'];?></span></a> <a
			href="browse.php?unchecked"><span><?=$tracker_lang['test_releaser'];?></span></a>
			<? if ($CURUSER) { ?> <a href="upload.php"><span><?=$tracker_lang['upload'];?></span></a>
			<? } else { ?> <a href="login.php"><span>Вход</span></a> <a
			href="signup.php"><span>Регистрация</span></a> <? } ?> <? if ($CURUSER) { ?>
		<a href="pages.php"><span><?=$tracker_lang['pages'];?></span></a> <a
			href="<?=$CACHEARRAY['forumurl']?>/index.php"><span><?=$tracker_lang['forum']." ".$CACHEARRAY['forumname'];?></span></a>
			<? } ?> <a href="rules.php"><span><?=$tracker_lang['rules'];?></span></a>
		<a href="faq.php"><span><?=$tracker_lang['faq'];?></span></a> <? if ($CURUSER) { ?>
		<a href="copyrights.php"><span>Report Abuse</span></a> <a
			href="peers.php"><span>Статистика пиров</span></a> <a
			href="staff.php"><span><?=$tracker_lang['staff'];?></span></a> <? } ?>
		</div>
		</td>
	</tr>
</table>
<!-- Top Navigation Menu for unregistered-->

<!--  some vars for the statusbar;o) -->

			<? if ($CURUSER) {

				$uped = mksize($CURUSER['uploaded']);

				$downed = mksize($CURUSER['downloaded']);

				if ($CURUSER["downloaded"] > 0)

				{

					$ratio = $CURUSER['uploaded'] / $CURUSER['downloaded'];

					$ratio = number_format($ratio, 3);

					$color = get_ratio_color($ratio);

					if ($color)

					$ratio = "<font color=$color>$ratio</font>";

				}

				else

				if ($CURUSER["uploaded"] > 0)

				$ratio = "Inf.";

				else

				$ratio = "---";

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
		<? if ($CURUSER) { ?> <font color="black"><?=$tracker_lang['welcome_back'];?></font><a
			href="userdetails.php?id=<?=$CURUSER['id']?>" class="online"><?=get_user_class_color($CURUSER['class'], $CURUSER['username'])?></a><?=$usrclass?><?=$medaldon?><?=$warn?>
		<font color="black"><a href="logout.php" class="black">(Выйти)</a></font>&nbsp;&nbsp;
		<font color="#1900D1">Рейтинг:&nbsp;<?=$ratio?></font>&nbsp;&nbsp; <font
			color="#00008b">Бонус:&nbsp;<a href="mybonus.php" class="online"><?=$CURUSER["bonus"]?></a></font>&nbsp;&nbsp;
		<font color="green">Раздал:&nbsp;<?=$uped?></font>&nbsp;&nbsp; <font
			color="red">Скачал:&nbsp;<?=$downed?></font>&nbsp;&nbsp; <font
			color="#1900D1">Торренты:&nbsp;</font> <img alt="Раздает"
			title="Раздает" src="./themes/<?=$ss_uri;?>/images/arrowup.gif" /> <font
			color="green"><span class="smallfont"><?=$activeseed?></span></font>&nbsp;&nbsp;
		<img alt="Качает" title="Качает"
			src="./themes/<?=$ss_uri;?>/images/arrowdown.gif" /> <font
			color="red"><span class="smallfont"><?=$activeleech?></span></font> <?

			print("<span class=\"smallfont\"><a href=\"message.php\">$inboxpic</a>&nbsp;{$CURUSER['messages']} ({$CURUSER['unread']})</span>");
			print("<span class=\"smallfont\">&nbsp;&nbsp;<a href=\"message.php?action=viewmailbox&amp;box=-1\"><img height=\"16\" style=\"border:none\" alt=\"Отправленые\" title=\"Отправленые\" src=\"pic/pn_sentbox.gif\"/></a>&nbsp;{$CURUSER['outmessages']}</span>");

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

			<? $fn = substr($_SERVER['PHP_SELF'], strrpos($_SERVER['PHP_SELF'], "/") + 1); ?>
				<td class="tt" width="155" style="vertical-align: top;"><?
				show_blocks("l");

				?></td>
				<td class="tt"
					style="padding-top: 5px; padding-bottom: 5px; vertical-align: top;">
					<?
					if ($CURUSER) {
						if ($CURUSER['unread'])
						{
							print("<p><table border=\"0\" cellspacing=\"0\" cellpadding=\"10\" bgcolor=\"red\"><tr><td style=\"padding: 10; background: red\">\n");
							print("<b><a href=\"message.php\"><font color=\"white\">".sprintf($tracker_lang['new_pms'],$CURUSER['unread'])."</font></a></b>");
							print("</td></tr></table></p>\n");
						}
						if ($CURUSER['unchecked'])
						{
							print("<p><table border=\"0\" cellspacing=\"0\" cellpadding=\"10\" bgcolor=\"red\"><tr><td style=\"padding: 10; background: red\">\n");
							print("<b><a href=\"browse.php?unchecked\"><font color=\"white\">".sprintf($tracker_lang['has_unchecked'],$CURUSER['unchecked'])."</font></a></b>");
							print("</td></tr></table></p>\n");
						}
					}
					if ($CURUSER['override_class'] != 255 && $CURUSER) // Second condition needed so that this box isn't displayed for non members/logged out members.
					{
						print("<p><table border=\"0\" cellspacing=\"0\" cellpadding=\"10\" bgcolor=\"green\"><tr><td style=\"padding: 10; background: green\">\n");
						print("<b><a href=\"restoreclass.php\"><font color=\"white\">".$tracker_lang['lower_class']."</font></a></b>");
						print("</td></tr></table></p>\n");
					}

					show_blocks('c');

					?>