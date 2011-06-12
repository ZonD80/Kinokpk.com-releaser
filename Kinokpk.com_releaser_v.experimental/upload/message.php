<?php
/**
 * Private messages mailbox
 * @license GNU GPLv3 http://opensource.org/licenses/gpl-3.0.html
 * @package Kinokpk.com releaser
 * @author ZonD80 <admin@kinokpk.com>
 * @copyright (C) 2008-now, ZonD80, Germany, TorrentsBook.com
 * @link http://dev.kinokpk.com
 */

require_once ("include/bittorrent.php");

INIT();

loggedinorreturn ();

// Define constants
define ( 'PM_DELETED', 0 ); // Message was deleted
define ( 'PM_INBOX', 1 ); // Message located in Inbox for reciever
define ( 'PM_SENTBOX', - 1 ); // GET value for sent box


// Determine action
$action = ( string ) $_GET ['action'];
if (! $action) {
	$action = ( string ) $_POST ['action'];
	if (! $action) {
		$action = 'viewmailbox';
	}
}

// начало просмотр почтового ящика
if ($action == "viewmailbox") {
	// Get Mailbox Number
	$mailbox = ( int ) $_GET ['box'];
	if (! $mailbox) {
		$mailbox = PM_INBOX;
	}
	if ($mailbox == PM_INBOX) {
		$mailbox_name = $REL_LANG->say_by_key('inbox');
	} else {
		$mailbox_name = $REL_LANG->say_by_key('outbox');
	}

	// Start Page


	$REL_TPL->stdhead( $mailbox_name );

	?>

<H1><?=$mailbox_name?></H1>

	<?
	#amount of messages
	$inbox_all = count($CURUSER['inbox']);
	$outbox_all = count($CURUSER['outbox']);
	$all_mess = $inbox_all+$outbox_all;
	$all_mess_procent = round(($all_mess/$REL_CONFIG['pm_max'])*100);
	//print($all_mess_procent);
	$inbox_all_procent = round(($inbox_all/$REL_CONFIG['pm_max'])*100);
	$outbox_all_procent = round(($outbox_all/$REL_CONFIG['pm_max'])*100);

	print("<b>Ваш ящик заполнен на:</b><font color=\"#8da6cf\"> $all_mess</font><font color=\"green\"> ($all_mess_procent%) </font> <small>максимальное количество сообщений - ". $REL_CONFIG['pm_max'] ."</small><br />");
	#amount end

	print('<form id="message" action="'.$REL_SEO->make_link('message').'" method="POST"><input type="hidden" name="action" value="moveordel">');
	print("<div id=\"tabs\"><ul>
	<li class=\"tab".(($mailbox != PM_SENTBOX)?'1':'2')."\"><a href=\"".$REL_SEO->make_link('message','action','viewmailbox','box',1)."\"><span>Входящие <font color=\"#8da6cf\">$inbox_all </font><font color=\"green\">($inbox_all_procent%) </font></span></a></li>
	<li nowrap=\"\" class=\"tab".(($mailbox != PM_SENTBOX)?'2':'1')."\"><a href=\"".$REL_SEO->make_link('message','action','viewmailbox','box',-1)."\"><span>Отправленные <font color=\"#8da6cf\">$outbox_all </font><font color=\"green\">($outbox_all_procent%) </font></span></a></li>
	</ul></div>");
	?>

<TABLE border="0" cellpadding="4" cellspacing="0" width="100%"
	style="float: left; margin-top: 10px;">
	<TR>
		<TD width="2%" class="colhead">&nbsp;&nbsp;</TD>
		<TD width="41%" class="colhead"><?=$REL_LANG->say_by_key('subject');?></TD>
		<?
		if ($mailbox == PM_INBOX )
		print ("<TD width=\"30%\" class=\"colhead\">".$REL_LANG->say_by_key('sender')."</TD>");
		else
		print ("<TD width=\"30%\" class=\"colhead\">".$REL_LANG->say_by_key('receiver')."</TD>");
		?>
		<TD width="10%" class="colhead"><?=$REL_LANG->say_by_key('date');?></TD>
		<TD width="10%" class="colhead">В архиве</TD>
		<TD width="10%" class="colhead">Срок хранения</TD>
		<TD width="2%" class="colhead"><input id="toggle-all"
			style="float: right;" type="checkbox"
			title="<?=$REL_LANG->say_by_key('mark_all');?>"
			value="<?=$REL_LANG->say_by_key('mark_all');?>" /></TD>

	</TR>
	<?

	$secs_system = $REL_CRON['pm_delete_sys_days']*86400; // Количество дней
	$dt_system = time() - $secs_system; // Сегодня минус количество дней
	$secs_all = $REL_CRON['pm_delete_user_days']*86400; // Количество дней
	$dt_all = time() - $secs_all; // Сегодня минус количество дней

	if ($mailbox != PM_SENTBOX) {
		$res = sql_query ( "SELECT m.*, u.username AS sender_username, friends.id AS fid, friends.confirmed AS fconf FROM messages AS m LEFT JOIN users AS u ON m.sender = u.id LEFT JOIN friends ON (friends.userid=m.sender AND friends.friendid={$CURUSER['id']}) OR (friends.friendid=m.sender AND friends.userid={$CURUSER['id']}) WHERE receiver=" . sqlesc ( $CURUSER ['id'] ) . " AND location=" . sqlesc ( $mailbox ) . " AND IF(m.archived_receiver=1, 1=1, IF(m.sender=0,m.added>$dt_system,m.added>$dt_all)) ORDER BY id DESC" ) or sqlerr ( __FILE__, __LINE__ );
	} else {
		$res = sql_query ( "SELECT m.*, u.username AS receiver_username, friends.id AS fid, friends.confirmed AS fconf FROM messages AS m LEFT JOIN users AS u ON m.receiver = u.id LEFT JOIN friends ON (friends.userid=m.receiver AND friends.friendid={$CURUSER['id']}) OR (friends.friendid=m.receiver AND friends.userid={$CURUSER['id']}) WHERE sender=" . sqlesc ( $CURUSER ['id'] ) . " AND saved=1 AND IF(m.archived_receiver<>1, 1=1, IF(m.sender=0,m.added>$dt_system,m.added>$dt_all)) ORDER BY id DESC" ) or sqlerr ( __FILE__, __LINE__ );
	}

	if (mysql_num_rows ( $res ) == 0) {

		echo ("<TD colspan=\"6\" align=\"center\">" . $REL_LANG->say_by_key('no_messages') . ".</TD>\n");
	} else {
		while ( $row = mysql_fetch_assoc ( $res ) ) {
			if ($row['receiver']==$CURUSER['id']) $row['archived'] = $row['archived_receiver'];

			$friend = $row ['fid'];
			$fconf = $row ['fconf'];
			// Get Sender Username
			if ($row ['sender'] != 0) {
				$username = "<a href=\"".$REL_SEO->make_link('userdetails','id',$row['sender'],'username',translit($row["sender_username"]))."\">" . $row ["sender_username"] . "</a>";
				$id = $row ['sender'];

				if ($friend)
				$username .= "<br /><small>[<a href=\"".$REL_SEO->make_link('friends','action','deny','id',$row['fid'])."\">{$REL_LANG->say_by_key('delete_from_friends')}</a>]" . (! $fconf ? "[<a href=\"".$REL_SEO->make_link('friends','action','confirm','id',$row['fid'])."\">{$REL_LANG->say_by_key('confirm')}</a>]" : '') . "</small>";
				else
				$username .= "<br /><small>[<a href=\"".$REL_SEO->make_link('friends','action','add','id',$id)."\">{$REL_LANG->say_by_key('add_to_friends')}</a>]</small>";
			} else {
				$username = $REL_LANG->say_by_key('from_system');
			}
			// Get Receiver Username
			if ($row ['receiver'] != 0) {
				$receiver = "<a href=\"".$REL_SEO->make_link('userdetails','id',$row ['receiver'],'username',translit($row["receiver_username"]))."\">" . $row ["receiver_username"] . "</a>";
				$id_r = $row ['receiver'];

				if ($friend)
				$receiver .= "<br /><small>[<a href=\"".$REL_SEO->make_link('friends','action','deny','id',$row['fid'])."\">{$REL_LANG->say_by_key('delete_from_friends')}</a>]" . (! $fconf ? $REL_LANG->say_by_key('confirm') : '') . "</small>";
				else
				$receiver .= "<br /><small>[<a href=\"".$REL_SEO->make_link('friends','action','add','id',$id_r)."\">{$REL_LANG->say_by_key('add_to_friends')}</a>]</small>";
			} else {
				$receiver = $REL_LANG->say_by_key('from_system');
			}
			$subject = makesafe ( $row ['subject'] );
			if (mb_strlen ( $subject ) <= 0) {
				$subject = $REL_LANG->say_by_key('no_subject');
			}
			if ($row ['unread'] && $mailbox != PM_SENTBOX) {
				echo ("<TR>\n<TD ><IMG src=\"pic/pn_inboxnew.gif\" alt=\"" . $REL_LANG->say_by_key('mail_unread') . "\"></TD>\n");
			} else {
				echo ("<TR>\n<TD><IMG src=\"pic/pn_inbox.gif\" alt=\"" . $REL_LANG->say_by_key('mail_read') . "\"></TD>\n");
			}
			$msgtext = strip_tags($row['msg']);
			$msgtext = "<small>".(mb_strlen($msgtext)>70?'...'.substr($msgtext,-70):$msgtext)."</small>";
			echo ("<TD><A href=\"".$REL_SEO->make_link('message','action','viewmessage','id',$row ['id'])."\">" . $subject . "</A><br/>$msgtext</TD>\n");
			if ($mailbox != PM_SENTBOX) {
				echo ("<TD>$username</TD>\n");
			} else {
				echo ("<TD>$receiver</TD>\n");
			}
			echo ("<TD>" . mkprettytime ( $row ['added'] ) . "</TD>\n");

			echo ("<TD>" . (($row ['archived']) ? "<font color=\"red\">Да</font>" : "Нет") . "</TD>\n");
			if ($row ['sender'] == 0)
			$pm_del = $REL_CRON ['pm_delete_sys_days'];
			else
			$pm_del = $REL_CRON ['pm_delete_user_days'];

			echo ("<TD>" . (($row ['archived']) ? "N/A" : ($pm_del - round ( (time () - $row ['added']) / 86400 )) . " дня(ей)</TD>\n"));
			echo ("<TD><INPUT type=\"checkbox\" name=\"messages[]\" title=\"" . $REL_LANG->say_by_key('mark') . "\" value=\"" . $row ['id'] . "\" id=\"checkbox_tbl_" . $row ['id'] . "\"></TD>\n</TR>\n");
		}
	}
	?>
	<tr class="colhead">
		<td class="colhead">&nbsp;</td>
		<td colspan="6" align="right" width="100%" class="colhead" />
		<input type="hidden" name="box" value="<?=$mailbox?>" />
		<input type="submit" name="delete"
			title="<?=$REL_LANG->say_by_key('delete_marked_messages');?>"
			value="<?=$REL_LANG->say_by_key('delete');?>"
			onClick="return confirm('<?=$REL_LANG->say_by_key('sure_mark_delete');?>')" />
		<input type="submit" name="markread"
			title="<?=$REL_LANG->say_by_key('mark_as_read');?>"
			value="<?=$REL_LANG->say_by_key('mark_read');?>"
			onClick="return confirm('<?=$REL_LANG->say_by_key('sure_mark_read');?>')" />
		<input type="submit" name="archive" title="Архивировать"
			value="Архивировать"
			onClick="return confirm('Архивировать выбранные сообщения? (они не будут удалены системой автоматически)')" />
		<input type="submit" name="unarchive" title="Разархивировать"
			value="Разархивировать"
			onClick="return confirm('Разархивировать выбранные сообщения? (они будут удалены системой автоматически)')" />
		</td>

	</tr>
</table>
</form>
<div align="left"><img src="pic/pn_inboxnew.gif" alt="Непрочитанные" />
	<?=$REL_LANG->say_by_key('mail_unread_desc');?><br />
<img src="pic/pn_inbox.gif" alt="Прочитанные" /> <?=$REL_LANG->say_by_key('mail_read_desc');?></div>
	<?
	$REL_TPL->stdfoot();
} // конец просмотр почтового ящика


// начало просмотр тела сообщения
elseif ($action == "viewmessage") {
	if (! is_valid_id ( $_GET ["id"] ))
	stderr ( $REL_LANG->say_by_key('error'), $REL_LANG->say_by_key('invalid_id') );
	$pm_id = (int)$_GET ['id'];

	// Get the message
	if (!get_privilege('view_pms',false)) {
		$res = sql_query ( 'SELECT * FROM messages WHERE messages.id=' . sqlesc ( $pm_id ) . ' AND (messages.receiver=' . sqlesc ( $CURUSER ['id'] ) . ' OR (messages.sender=' . sqlesc ( $CURUSER ['id'] ) . ' AND messages.saved=1)) LIMIT 1' ) or sqlerr ( __FILE__, __LINE__ );
		if (mysql_num_rows ( $res ) == 0) {
			stderr ( $REL_LANG->say_by_key('error'), "Такого сообщения не существует." );
		}

	} else {
		$res = sql_query ( 'SELECT * FROM messages WHERE messages.id=' . sqlesc ( $pm_id ) );
		if (mysql_num_rows ( $res ) == 0) {
			stderr ( $REL_LANG->say_by_key('error'), "Такого сообщения не существует." );
		}
		$adminview = 1;
	}

	// Prepare for displaying message
	$message = mysql_fetch_assoc ( $res );
	if ($message ['sender'] == $CURUSER ['id']) {
		// Display to
		$res2 = sql_query ( "SELECT username FROM users WHERE id=" . sqlesc ( $message ['receiver'] ) ) or sqlerr ( __FILE__, __LINE__ );
		$sender = mysql_fetch_array ( $res2 );
		$sender = "<A href=\"".$REL_SEO->make_link('userdetails','id',$message['receiver'],'username',translit($sender [0]))."\">" . $sender [0] . "</A>";
		$reply = "";
		$from = "Кому";
	} else {
		$from = "От кого";
		if ($message ['sender'] == 0) {
			$sender = "Системное";
			$reply = "";
		} else {
			$res2 = sql_query ( "SELECT username FROM users WHERE id=" . sqlesc ( $message ['sender'] ) ) or sqlerr ( __FILE__, __LINE__ );
			$sender = mysql_fetch_array ( $res2 );
			$sender = "<A href=\"".$REL_SEO->make_link('userdetails','id',$message['sender'],'username',translit($sender [0]))."\">{$sender [0]}</A>";
			$reply = " [ <A href=\"".$REL_SEO->make_link('message','action','sendmessage','receiver',$message['sender'],'replyto',$pm_id)."\">Ответить</A> ]";
		}
	}
	$body = format_comment ( $message ['msg'] );
	$added = mkprettytime ( $message ['added'] );
	$unread = ($message ['unread'] ? "<SPAN style=\"color: #FF0000;\"><b>(Новое)</b></A>" : "");

	$subject = makesafe ( $message ['subject'] );
	if (mb_strlen ( $subject ) <= 0) {
		$subject = "Без темы";
	}
	// Mark message unread
	if ($adminview && ($CURUSER ['id'] != $message ['receiver']) && ($CURUSER ['id'] != $message ['sender'])) {
	} else
	sql_query ( "UPDATE messages SET unread=0 WHERE id=" . sqlesc ( $pm_id ) . " AND receiver=" . sqlesc ( $CURUSER ['id'] ) . " LIMIT 1" );
	// Display message
	$REL_TPL->stdhead( "Личное Сообщение (Тема: $subject)" );
	?>
<TABLE width="100%" border="0" cellpadding="4" cellspacing="0">

	<TR>
		<TD class="colhead" colspan="2">Тема: <?=$subject?><span class="higo"><a
			href="javascript:history.go(-1);">назад</a></span></TD>

	</TR>
	<TR>
		<TD width="50%" class="colhead"><?=$from?></TD>
		<TD width="50%" class="colhead">Дата отправки</TD>
	</TR>
	<TR>
		<TD><?=$sender?></TD>
		<TD><?=$added?>&nbsp;&nbsp;<?=$unread?></TD>
	</TR>
	<TR>
		<TD colspan="2" style="padding: 20px;"><?=$body?></TD>
	</TR>
	<TR>
		<TD align="right" colspan=2><?php
		if ($adminview && ($CURUSER ['id'] != $message ['receiver']) && ($CURUSER ['id'] != $message ['sender'])) {
			$a_receiver = sql_query ( "SELECT username FROM users WHERE id = " . $message ['receiver'] );
			$a_receiver = mysql_result ( $a_receiver, 0 );

			print ( '<font color="red">Вы просматриваете это сообщение от прав администратора!</font> Получатель: <a href="'.$REL_SEO->make_link('userdetails','id',$message['receiver'],'username',translit($a_receiver)).'">' . $a_receiver . '</a><br />' );
		}
		print ( "[ <A onClick=\"return confirm('Вы уверены?')\" href=\"".$REL_SEO->make_link('message','action','deletemessage','id',$pm_id)."\">Удалить</A> ]$reply [ <A href=\"".$REL_SEO->make_link('message','action','forward','id',$pm_id)."\">Переслать</A> ]" . reportarea ( $message ['id'], 'messages' ) );
		?></TD>
	</TR>
</TABLE>
		<?
		set_visited('messages',$pm_id);
		$REL_TPL->stdfoot();
} // конец просмотр тела сообщения


// начало просмотр посылка сообщения
elseif ($action == "sendmessage") {

	if (! is_valid_id ( $_GET ["receiver"] ))
	stderr ( $REL_LANG->say_by_key('error'), "Неверное ID получателя" );
	$receiver = ( int ) $_GET ["receiver"];

	if ($_GET ['replyto'] && ! is_valid_id ( $_GET ["replyto"] ))
	stderr ( $REL_LANG->say_by_key('error'), "Неверное ID сообщения" );
	$replyto = ( int ) $_GET ["replyto"];

	$res = sql_query ( "SELECT * FROM users WHERE id=$receiver" ) or die ( mysql_error () );
	$user = mysql_fetch_assoc ( $res );
	if (! $user)
	stderr ( $REL_LANG->say_by_key('error'), "Пользователя с таким ID не существует." );

	if ($replyto) {
		$res = sql_query ( "SELECT * FROM messages WHERE id=$replyto" ) or sqlerr ( __FILE__, __LINE__ );
		$msga = mysql_fetch_assoc ( $res );
		if ($msga ["receiver"] != $CURUSER ["id"])
		stderr ( $REL_LANG->say_by_key('error'), "Вы пытаетесь ответить не на свое сообщение!" );

		$res = sql_query ( "SELECT username FROM users WHERE id=" . $msga ["sender"] ) or sqlerr ( __FILE__, __LINE__ );
		$usra = mysql_fetch_assoc ( $res );
		$body .= "<blockquote>" . format_comment ( $msga ['msg'] ) . "</blockquote><cite>$usra[username]</cite><hr /><br /><br />";
		// Change
		if (!preg_match("/^Re\(([0-9]+)\)\:/",$msga ['subject']))

		$subject = "Re(1): ".makesafe($msga ['subject']);
		else $subject = preg_replace("/^Re\(([0-9]+)\)\:/e","'Re('.(\\1+1).'):'",makesafe($msga ['subject']));



		// End of Change
	}

	$REL_TPL->stdhead( "Отсылка сообщений", false );
	?>
<script language="JavaScript">
<!--

required = new Array("subject");
required_show = new Array("тему сообщения");


function SendForm () {
  var i, j;

for(j=0; j<required.length; j++) {
    for (i=0; i<document.message.length; i++) {
        if (document.message.elements[i].name == required[j] &&
  document.forms[0].elements[i].value == "" ) {
            alert('Пожалуйста, введите ' + required_show[j]);
            document.message.elements[i].focus();
            return false;
        }
    }
}

  return true;
}
//-->

</script>
<table class=main border=0 cellspacing=0 cellpadding=0>
	<tr>
		<td class=embedded>
		<form name="message" method="post"
			action="<?=$REL_SEO->make_link('message')?>"
			onsubmit="return SendForm();"><input type=hidden name=action
			value=takemessage>
		<table class=message cellspacing=0 cellpadding=5>
			<tr>
				<td colspan=2 class=colhead>Сообщение для <a class=altlink_white
					href="<?=$REL_SEO->make_link('userdetails','id',$receiver,'username',translit($user['username']))?>"><?=$user ["username"]?></a></td>
			</tr>
			<TR>
				<TD colspan="2"><B>Тема:&nbsp;&nbsp;</B> <INPUT name="subject"
					type="text" size="60" value="<?=$subject?>" maxlength="255"></TD>
			</TR>
			<tr>
				<td <?=$replyto ? " colspan=2" : ""?>><?
				print textbbcode ( "msg", $body );
				?></td>
			</tr>
			<tr>
			<?
			if ($replyto) {
				?>
				<td align=center><input type=checkbox name='delete' value='1'
				<?=$CURUSER ['deletepms'] ? "checked" : ""?> />Удалить сообщение
				после ответа <input type=hidden name=origmsg value=<?=$replyto?> /></td>
				<?
			}
			?>
				<td align=center><input type=checkbox name='save' value='1'
				<?=$CURUSER ['savepms'] ? "checked" : ""?> />Сохранить сообщение в
				отправленных</td>
			</tr>
			<tr>
				<td align="center"><input type="checkbox" name='archive' value='1' />Архивировать
				после отправки</td>
			</tr>
			<tr>
				<td <?=$replyto ? " colspan=2" : ""?> align=center><input
					type=submit value="Послать!" class=btn /></td>
			</tr>
		</table>
		<input type=hidden name=receiver value=<?=$receiver?> /></form>
		</div>
		</td>
	</tr>
</table>
				<?
				$REL_TPL->stdfoot();
} // конец посылка сообщения


// начало прием посланного сообщения
elseif ($action == 'takemessage') {

	$receiver = ( int ) $_POST ["receiver"];
	$origmsg = ( int ) $_POST ["origmsg"];
	$save = $_POST ["save"];
	$archive = $_POST ["archive"];
	$returnto = urlencode ( $_POST ["returnto"] );
	if (! is_valid_id ( $receiver ) || ($origmsg && ! is_valid_id ( $origmsg )))
	stderr ( $REL_LANG->say_by_key('error'), $REL_LANG->say_by_key('invalid_id') );
	$msg = trim ( $_POST ["msg"] );
	if (! $msg)
	stderr ( $REL_LANG->say_by_key('error'), "Пожалуйста введите сообщение!" );
	$subject = trim ( $_POST ['subject'] );
	if (! $subject)
	stderr ( $REL_LANG->say_by_key('error'), "Пожалуйста введите тему сообщения!" );

	// ANTISPAM SYSTEM BEGIN
	$last_pmres = sql_query ( "SELECT " . time () . "-added AS seconds, msg,id FROM messages WHERE sender=" . $CURUSER ['id'] . " OR poster=" . $CURUSER ['id'] . " ORDER BY added DESC LIMIT 4" );
	while ( $last_pmresrow = mysql_fetch_array ( $last_pmres ) ) {
		$last_pmrow [] = $last_pmresrow;
		$msgids [] = $last_pmresrow ['id'];
	}
	//   print_r($last_pmrow);
	if ($last_pmrow [0]) {
		if (($REL_CONFIG ['as_timeout'] > round ( $last_pmrow [0] ['seconds'] )) && $REL_CONFIG ['as_timeout']) {
			$seconds = $REL_CONFIG ['as_timeout'] - round ( $last_pmrow [0] ['seconds'] );
			stderr ( $REL_LANG->say_by_key('error'), "На нашем сайте стоит защита от флуда, пожалуйста, повторите попытку через $seconds секунд. <a href=\"javascript: history.go(-1)\">Назад</a>" );
		}

		if ($REL_CONFIG ['as_check_messages'] && ($last_pmrow [0] ['msg'] == $msg) && ($last_pmrow [1] ['msg'] == $msg) && ($last_pmrow [2] ['msg'] == $msg) && ($last_pmrow [3] ['msg'] == $msg)) {
			$msgview = '';
			foreach ( $msgids as $msgid ) {
				$msgview .= "\n<a href=\"".$REL_SEO->make_link('message','action','viewmessage','id',$msgid)."\">Сообщение с ID={$msgid}</a> от пользователя " . $CURUSER ['username'];
			}
			$modcomment = sql_query ( "SELECT modcomment FROM users WHERE id=" . $CURUSER ['id'] );
			$modcomment = mysql_result ( $modcomment, 0 );
			if (strpos ( $modcomment, "Maybe spammer" ) === false) {
				$reason = sqlesc("Пользователь <a href=\"".$REL_SEO->make_link('userdetails','id',$CURUSER ['id'],'username',translit($CURUSER['username']))."\">" . $CURUSER ['username'] . "</a> может быть спамером, т.к. его 5 последних посланных сообщений полностью совпадают.$msgview");
				sql_query ( "INSERT INTO reports (reportid,userid,type,motive,added) VALUES ({$CURUSER['id']},0,'users',$reason," . time () . ")" );
				$modcomment .= "\n" . time () . " - Maybe spammer";
				sql_query ( "UPDATE users SET modcomment = " . sqlesc ( $modcomment ) . " WHERE id =" . $CURUSER ['id'] );
				stderr ( $REL_LANG->say_by_key('error'), "На нашем сайте стоит защита от спама, ваши 5 последних сообщений совпадают. В отсылке личного сообщения отказано. <b><u>ВНИМАНИЕ! ЕСЛИ ВЫ ЕЩЕ РАЗ ПОПЫТАЕТЕСЬ ОТПРАВИТЬ ИДЕНТИЧНОЕ СООБЩЕНИЕ, ВЫ БУДЕТЕ АВТОМАТИЧЕСКИ ЗАБЛОКИРОВАНЫ СИСТЕМОЙ!!!</u></b> <a href=\"javascript: history.go(-1)\">Назад</a>" );
					
			} else {
				sql_query ( "UPDATE users SET enabled=0, dis_reason='Spam' WHERE id=" . $CURUSER ['id'] );

				$reason = sqlesc("Пользователь ".make_user_link()." забанен системой за спам, его IP адрес (" . $CURUSER ['ip'] . ")");
				sql_query ( "INSERT INTO reports (reportid,userid,type,motive,added) VALUES ({$CURUSER['id']},0,'users',$reason," . time () . ")" );
				stderr ( "Поздравляем!", "Вы успешно забанены системой за спам в Личных Сообщениях! Если вы не согласны с решением системы, <a href=\"".$REL_SEO->make_link('contact')."\">подайте жалобу админам</a>." );
			}
		}
		
	}
	// ANTISPAM SYSTEM END
	$pms = sql_query ( "SELECT SUM(1) FROM messages WHERE (receiver = $receiver AND location=1) OR (sender = $receiver AND saved = 1)" );
	$pms = (int)mysql_result ( $pms, 0 );
	if ($pms >= $REL_CONFIG ['pm_max'])
	stderr ( $REL_LANG->say_by_key('error'), "Ящик личных сообщений получателя заполнен, вы не можете отправить ему сообщение." );

	if ($save) {
		$pms = sql_query ( "SELECT SUM(1) FROM messages WHERE (receiver = " . $CURUSER ['id'] . " AND location=1) OR (sender = " . $CURUSER ['id'] . " AND saved = 1)" );
		$pms = (int)mysql_result ( $pms, 0 );
		if ($pms >= $REL_CONFIG ['pm_max'])
		stderr ( "Невозможно сохранить сообщение", "Ваш ящик личных сообщений заполнен, максимальное кол-во {$REL_CONFIG['pm_max']}. Вы не можете отправить сообщение, вам необходимо очистить ящик личных сообщений" );
	}

	// Change
	$save = ($save) ? 1 : 0;
	$archive = ($archive) ? 1 : 0;
	// End of Change
	$res = sql_query ( "SELECT email, acceptpms, notifs, last_access AS la FROM users WHERE id=$receiver" ) or sqlerr ( __FILE__, __LINE__ );
	$user = mysql_fetch_assoc ( $res );
	if (! $user)
	stderr ( $REL_LANG->say_by_key('error'), "Нет пользователя с таким ID $receiver." );
	//Make sure recipient wants this message

	if (!get_privilege('is_moderator',false)) {
		if ($user ["acceptpms"] == "friends") {
			$res2 = sql_query ( "SELECT * FROM friends WHERE userid=$receiver AND friendid=" . $CURUSER ["id"] ) or sqlerr ( __FILE__, __LINE__ );
			if (mysql_num_rows ( $res2 ) != 1)
			stderr ( "Отклонено", "Этот пользователь принимает сообщение только из списка своих друзей" );
		} elseif ($user ["acceptpms"] == "no")
		stderr ( "Отклонено", "Этот пользователь не принимает сообщения." );
	}
	sql_query ( "INSERT INTO messages (poster, sender, receiver, added, msg, subject, saved, location, archived) VALUES(" . $CURUSER ["id"] . ", " . $CURUSER ["id"] . ",
	$receiver, '" . time () . "', " . sqlesc ( ($msg) ) . ", " . sqlesc ( $subject ) . ", " . sqlesc ( $save ) . ",  1, " . sqlesc ( $archive ) . ")" ) or sqlerr ( __FILE__, __LINE__ );
	$sended_id = mysql_insert_id ();

	$username = $CURUSER ["username"];
	$usremail = $user ["email"];
	$body = "
	$username послал вам личное сообщение!

Пройдите по ссылке ниже, чтобы его прочитать.

".$REL_SEO->make_link('message','action','viewmessage','id',$sended_id)."


";

	// email notifs
	send_notifs('unread',$body,$receiver);

	$delete = $_POST ["delete"];
	if ($origmsg) {
		if ($delete) {
			// Make sure receiver of $origmsg is current user
			$res = sql_query ( "SELECT * FROM messages WHERE id=$origmsg" ) or sqlerr ( __FILE__, __LINE__ );
			if (mysql_num_rows ( $res ) == 1) {
				$arr = mysql_fetch_assoc ( $res );
				if ($arr ["receiver"] != $CURUSER ["id"])
				stderr ( $REL_LANG->say_by_key('error'), "Вы пытаетесь удалить не свое сообщение!" );
				if (! $arr ["saved"])
				sql_query ( "DELETE FROM messages WHERE id=$origmsg" ) or sqlerr ( __FILE__, __LINE__ );
				elseif ($arr ["saved"])
				sql_query ( "UPDATE messages SET location = '0' WHERE id=$origmsg" ) or sqlerr ( __FILE__, __LINE__ );
			}
		}
		if (! $returnto)
		$returnto = $REL_SEO->make_link("message");
	}
	if ($returnto) {
		safe_redirect(" $returnto" );
		die ();
	} else {
		safe_redirect($REL_SEO->make_link('message'),2);
		stderr ( $REL_LANG->say_by_key('success'), $REL_LANG->_("Message was successfuly sent") ,'success');
	}

} // конец прием посланного сообщения


//начало массовая рассылка
elseif ($action == 'mass_pm') {
	get_privilege('mass_pm');
	if (! is_valid_id ( $_POST ['n_pms'] ))
	stderr ( $REL_LANG->say_by_key('error'), $REL_LANG->say_by_key('invalid_id') );
	$n_pms = ( int ) $_POST ['n_pms'];
	$pmees = htmlspecialchars ( $_POST ['pmees'] );

	$REL_TPL->stdhead( "Отсылка сообщений", false );
	?>
<table class=main border=0 cellspacing=0 cellpadding=0>
	<tr>
		<td class=embedded>
		<div align=center>
		<form method=post action=<?=$_SERVER ['PHP_SELF']?> name=message><input
			type=hidden name=action value=takemass_pm> <?
			if ($_SERVER ["HTTP_REFERER"]) {
				?> <input type=hidden name=returnto
			value="<?=htmlspecialchars ( $_SERVER ["HTTP_REFERER"] );?>"> <?
			}
			?>
		<table border=1 cellspacing=0 cellpadding=5>
			<tr>
				<td class=colhead colspan=2>Массовая рассылка для <?=$n_pms?>
				пользовате<?=($n_pms > 1 ? "лей" : "ля")?></td>
			</tr>
			<TR>
				<TD colspan="2"><B>Тема:&nbsp;&nbsp;</B> <INPUT name="subject"
					type="text" size="60" maxlength="255"></TD>
			</TR>
			<tr>
				<td colspan="2">
				<div align="center"><?=print textbbcode ( "msg", $body );?></div>
				</td>
			</tr>
			<tr>
				<td colspan="2">
				<div align="center"><b>Комментарий:&nbsp;&nbsp;</b> <input
					name="comment" type="text" size="70" /></div>
				</td>
			</tr>
			<tr>
				<td>
				<div align="center"><b>От:&nbsp;&nbsp;</b> <?=$CURUSER ['username']?>
				<input name="sender" type="radio" value="self" checked /> &nbsp;
				Системное <input name="sender" type="radio" value="system" /></div>
				</td>
			</tr>
			<tr>
				<td colspan="2" align=center><input type=submit value="Послать!"
					class=btn /></td>
			</tr>
		</table>
		<input type=hidden name=pmees value="<?=$pmees?>" /> <input
			type=hidden name=n_pms value=<?=$n_pms?> /></form>
		<br />
		<br />
		</div>
		</td>
	</tr>
</table>
			<?php
			$REL_TPL->stdfoot();

} //конец массовая рассылка


//начало прием сообщений из массовой рассылки
elseif ($action == 'takemass_pm') {
	get_privilege('mass_pm');
	$msg = trim ( $_POST ["msg"] );
	if (! $msg)
	stderr ( $REL_LANG->say_by_key('error'), "Пожалуйста введите сообщение." );
	$sender_id = ($_POST ['sender'] == 'system' ? 0 : $CURUSER ['id']);
	$n_pms = ( int ) $_POST ['n_pms'];
	$comment = ( string ) $_POST ['comment'];
	$from_is = mysql_real_escape_string ( unesc ( $_POST ['pmees'] ) );
	// Change
	$subject = trim ( $_POST ['subject'] );
	$query = "INSERT INTO messages (sender, receiver, added, msg, subject, location, poster) " . "SELECT $sender_id, u.id, " . time () . ", " . sqlesc (  $msg ) . ", " . sqlesc ( $subject ) . ", 1, $sender_id " . $from_is;
	// End of Change
	sql_query ( $query ) or sqlerr ( __FILE__, __LINE__ );
	$n = mysql_affected_rows ();
	// add a custom text or stats snapshot to comments in profile
	if ($comment) {
		$res = sql_query ( "SELECT u.id, u.modcomment " . $from_is ) or sqlerr ( __FILE__, __LINE__ );
		if (mysql_num_rows ( $res ) > 0) {
			$l = 0;
			while ( $user = mysql_fetch_array ( $res ) ) {
				unset ( $new );
				$old = $user ['modcomment'];
				if ($comment)
				$new = $comment;

				$new .= $old ? ("\n" . $old) : $old;
				sql_query ( "UPDATE users SET modcomment = " . sqlesc ( $new ) . " WHERE id = " . $user ['id'] ) or sqlerr ( __FILE__, __LINE__ );
				if (mysql_affected_rows ())
				$l ++;
			}
		}
	}
	safe_redirect($REL_SEO->make_link('message'),3);
	stderr ( $REL_LANG->say_by_key('success'), (($n_pms > 1) ? "$n сообщений из $n_pms было" : "Сообщение было") . " успешно отправлено!" . ($l ? " $l комментарий(ев) в профиле " . (($l > 1) ? "были" : " был") . " обновлен!" : ""), 'success' );
} //конец прием сообщений из массовой рассылки


//начало перемещение, помечание как прочитанного
elseif ($action == "moveordel") {
	if (isset ( $_POST ["id"] ) && ! is_valid_id ( $_POST ["id"] ))
	stderr ( $REL_LANG->say_by_key('error'), $REL_LANG->say_by_key('invalid_id') );
	$pm_id = $_POST ['id'];

	$pm_box = ( int ) $_POST ['box'];
	if (! is_array ( $_POST ['messages'] ))
	stderr ( $REL_LANG->say_by_key('error'), $REL_LANG->say_by_key('invalid_id') );
	$pm_messages = $_POST ['messages'];
	if ($_POST ['move']) {
		if ($pm_id) {
			// Move a single message
			@sql_query ( "UPDATE messages SET location=" . sqlesc ( $pm_box ) . ", saved = 1 WHERE id=" . sqlesc ( $pm_id ) . " AND receiver=" . $CURUSER ['id'] . " LIMIT 1" );
		} else {
			// Move multiple messages
			@sql_query ( "UPDATE messages SET location=" . sqlesc ( $pm_box ) . ", saved = 1 WHERE id IN (" . implode ( ", ", array_map ( "sqlesc", array_map ( "intval", $pm_messages ) ) ) . ') AND receiver=' . $CURUSER ['id'] );
		}
		// Check if messages were moved
		if (@mysql_affected_rows () == 0) {
			stderr ( $REL_LANG->say_by_key('error'), "Не возможно переместить сообщения!" );
		}
		safe_redirect($REL_SEO->make_link('message','action','viewmailbox','box',$pm_box));
		exit ();
	} elseif ($_POST ['delete']) {
		if ($pm_id) {
			// Delete a single message
			$res = sql_query ( "SELECT * FROM messages WHERE id=" . sqlesc ( $pm_id ) ) or sqlerr ( __FILE__, __LINE__ );
			$message = mysql_fetch_assoc ( $res );
			if ($message ['receiver'] == $CURUSER ['id'] && ! $message ['saved']) {
				sql_query ( "DELETE FROM messages WHERE id=" . sqlesc ( $pm_id ) ) or sqlerr ( __FILE__, __LINE__ );
			} elseif ($message ['sender'] == $CURUSER ['id'] && $message ['location'] == PM_DELETED) {
				sql_query ( "DELETE FROM messages WHERE id=" . sqlesc ( $pm_id ) ) or sqlerr ( __FILE__, __LINE__ );
			} elseif ($message ['receiver'] == $CURUSER ['id'] && $message ['saved']) {
				sql_query ( "UPDATE messages SET location=0 WHERE id=" . sqlesc ( $pm_id ) ) or sqlerr ( __FILE__, __LINE__ );
			} elseif ($message ['sender'] == $CURUSER ['id'] && $message ['location'] != PM_DELETED) {
				sql_query ( "UPDATE messages SET saved=0 WHERE id=" . sqlesc ( $pm_id ) ) or sqlerr ( __FILE__, __LINE__ );
			}
		} else {
			// Delete multiple messages
			if (is_array ( $pm_messages ))
			foreach ( $pm_messages as $id ) {
				$res = sql_query ( "SELECT * FROM messages WHERE id=" . sqlesc ( ( int ) $id ) );
				$message = mysql_fetch_assoc ( $res );
				if ($message ['receiver'] == $CURUSER ['id'] && ! $message ['saved']) {
					sql_query ( "DELETE FROM messages WHERE id=" . sqlesc ( ( int ) $id ) ) or sqlerr ( __FILE__, __LINE__ );
				} elseif ($message ['sender'] == $CURUSER ['id'] && $message ['location'] == PM_DELETED) {
					sql_query ( "DELETE FROM messages WHERE id=" . sqlesc ( ( int ) $id ) ) or sqlerr ( __FILE__, __LINE__ );
				} elseif ($message ['receiver'] == $CURUSER ['id'] && $message ['saved']) {
					sql_query ( "UPDATE messages SET location=0 WHERE id=" . sqlesc ( ( int ) $id ) ) or sqlerr ( __FILE__, __LINE__ );
				} elseif ($message ['sender'] == $CURUSER ['id'] && $message ['location'] != PM_DELETED) {
					sql_query ( "UPDATE messages SET saved=0 WHERE id=" . sqlesc ( ( int ) $id ) ) or sqlerr ( __FILE__, __LINE__ );
				}
			}
		}
		// Check if messages were moved
		if (@mysql_affected_rows () == 0) {
			stderr ( $REL_LANG->say_by_key('error'), "Сообщение не может быть удалено!" );
		} else {
			safe_redirect($REL_SEO->make_link('message','action','viewmailbox','box',$pm_box));
			exit ();
		}
	} elseif ($_POST ["markread"]) {
		//помечаем одно сообщение
		if ($pm_id) {
			sql_query ( "UPDATE messages SET unread=0 WHERE id = " . sqlesc ( $pm_id ) ) or sqlerr ( __FILE__, __LINE__ );
		} //помечаем множество сообщений
		else {
			if (is_array ( $pm_messages ))
			foreach ( $pm_messages as $id ) {
				$res = sql_query ( "SELECT * FROM messages WHERE id=" . sqlesc ( ( int ) $id ) );
				$message = mysql_fetch_assoc ( $res );
				sql_query ( "UPDATE messages SET unread=0 WHERE id = " . sqlesc ( ( int ) $id ) ) or sqlerr ( __FILE__, __LINE__ );
			}
		}
		// Проверяем, были ли помечены сообщения

		safe_redirect($REL_SEO->make_link('message','action','viewmailbox','box',$pm_box));
		exit ();

	} elseif ($_POST ["archive"]) {
		//архивируем одно сообщение
		if ($pm_id) {
			sql_query ( "UPDATE messages SET archived=IF(sender={$CURUSER['id']},1,archived), archived_receiver=IF(sender={$CURUSER['id']},archived_receiver,1) WHERE id = " . sqlesc ( $pm_id ) ) or sqlerr ( __FILE__, __LINE__ );
		} //архивируем множество сообщений
		else {
			if (is_array ( $pm_messages ))
			foreach ( $pm_messages as $id ) {
				$res = sql_query ( "SELECT * FROM messages WHERE id=" . sqlesc ( ( int ) $id ) );
				$message = mysql_fetch_assoc ( $res );
				sql_query ( "UPDATE messages SET archived=IF(sender={$CURUSER['id']},1,archived), archived_receiver=IF(sender={$CURUSER['id']},archived_receiver,1) WHERE id = " . sqlesc ( ( int ) $id ) ) or sqlerr ( __FILE__, __LINE__ );
			}
		}

		safe_redirect($REL_SEO->make_link('message','action','viewmailbox','box',$pm_box),1 );
		stderr($REL_LANG->say_by_key('success'), "Сообщение(я) архивировано(ы)!",'success');

	} elseif ($_POST ["unarchive"]) {
		//архивируем одно сообщение
		if ($pm_id) {
			sql_query ( "UPDATE messages SET archived=IF(sender={$CURUSER['id']},0,archived), archived_receiver=IF(sender={$CURUSER['id']},archived_receiver,0) AND id = " . sqlesc ( $pm_id ) ) or sqlerr ( __FILE__, __LINE__ );
		} //архивируем множество сообщений
		else {
			if (is_array ( $pm_messages ))
			foreach ( $pm_messages as $id ) {
				$res = sql_query ( "SELECT * FROM messages WHERE id=" . sqlesc ( ( int ) $id ) );
				$message = mysql_fetch_assoc ( $res );
				sql_query ( "UPDATE messages SET archived=IF(sender={$CURUSER['id']},0,archived), archived_receiver=IF(sender={$CURUSER['id']},archived_receiver,0) AND id = " . sqlesc ( ( int ) $id ) ) or sqlerr ( __FILE__, __LINE__ );
			}
		}

		safe_redirect($REL_SEO->make_link('message','action','viewmailbox','box',$pm_box),1 );
		stderr($REL_LANG->say_by_key('success'), "Сообщение(я) разархивировано(ы)!",'success');
	}

	stderr ( $REL_LANG->say_by_key('error'), "Нет действия." );
} //конец перемещение, помечание как прочитанного


//начало пересылка
elseif ($action == "forward") {
	if ($_SERVER ['REQUEST_METHOD'] == 'GET') {
		// Display form
		if (! is_valid_id ( $_GET ["id"] ))
		stderr ( $REL_LANG->say_by_key('error'), $REL_LANG->say_by_key('invalid_id') );
		$pm_id = $_GET ['id'];

		// Get the message
		$res = sql_query ( 'SELECT * FROM messages WHERE id=' . sqlesc ( $pm_id ) . ' AND (receiver=' . sqlesc ( $CURUSER ['id'] ) . ' OR sender=' . sqlesc ( $CURUSER ['id'] ) . ') LIMIT 1' ) or sqlerr ( __FILE__, __LINE__ );

		if (! $res) {
			stderr ( $REL_LANG->say_by_key('error'), "У вас нет разрешения пересылать это сообщение." );
		}
		if (mysql_num_rows ( $res ) == 0) {
			stderr ( $REL_LANG->say_by_key('error'), "У вас нет разрешения пересылать это сообщение." );
		}
		$message = mysql_fetch_assoc ( $res );

		// Prepare variables
		if (!preg_match("/^Fwd\(([0-9]+)\)\:/",$message ['subject']))

		$subject = "Fwd(1): ".makesafe($message ['subject']);
		else $subject = preg_replace("/^Fwd\(([0-9]+)\)\:/e","'Fwd('.(\\1+1).'):'",makesafe($message ['subject']));

		$from = $message ['sender'];
		$orig = $message ['receiver'];

		$res = sql_query ( "SELECT id,username,class,donor,warned,enabled FROM users WHERE id=" . sqlesc ( $orig ) . " OR id=" . sqlesc ( $from ) ) or sqlerr ( __FILE__, __LINE__ );

		$orig2 = mysql_fetch_assoc ( $res );
		$orig_name = make_user_link($orig2);
		if ($from == 0) {
			$from_name = "Системное";
			$from2 ['username'] = "Системное";
		} else {
			$from2 = mysql_fetch_assoc ( $res );
			$from_name = make_user_link($from2);
		}

		$body = "Оригинальное сообщение:<hr /><blockquote>" . format_comment ( $message ['msg'] . "</blockquote><cite>{$from2['username']}</cite><hr /><br /><br />" );

		$REL_TPL->stdhead( $subject );
		?>

<FORM action="<?=$REL_SEO->make_link('message')?>" method="post"><INPUT
	type="hidden" name="action" value="forward"> <INPUT type="hidden"
	name="id" value="<?=$pm_id?>">
<TABLE border="0" cellpadding="4" cellspacing="0">
	<TR>
		<TD class="colhead" colspan="2"><?=$subject?></TD>
	</TR>
	<TR>
		<TD>Кому:</TD>
		<TD><INPUT type="text" name="to" value="Введите имя" size="83"></TD>
	</TR>
	<TR>
		<TD>Оригинальный<br />
		отправитель:</TD>
		<TD><?=$orig_name?></TD>
	</TR>
	<TR>
		<TD>От:</TD>
		<TD><?=$from_name?></TD>
	</TR>
	<TR>
		<TD>Тема:</TD>
		<TD><INPUT type="text" name="subject" value="<?=$subject?>" size="83"></TD>
	</TR>
	<TR>
		<TD>Сообщение:</TD>
		<TD><?=( textbbcode ( "msg" ) );?></TD>
	</TR>
	<TR>
		<TD colspan="2" align="center">Сохранить сообщение <INPUT
			type="checkbox" name="save" value="1"
			<?=$CURUSER ['savepms'] ? " checked" : ""?>>&nbsp;<INPUT
			type="submit" value="Переслать"></TD>
	</TR>
</TABLE>
</FORM>
			<?
			$REL_TPL->stdfoot();
	}

	else {

		// Forward the message
		if (! is_valid_id ( $_POST ["id"] ))
		stderr ( $REL_LANG->say_by_key('error'), $REL_LANG->say_by_key('invalid_id') );
		$pm_id = $_POST ['id'];

		// Get the message
		$res = sql_query ( 'SELECT * FROM messages WHERE id=' . sqlesc ( $pm_id ) . ' AND (receiver=' . sqlesc ( $CURUSER ['id'] ) . ' OR sender=' . sqlesc ( $CURUSER ['id'] ) . ') LIMIT 1' ) or sqlerr ( __FILE__, __LINE__ );
		if (! $res) {
			stderr ( $REL_LANG->say_by_key('error'), "У вас нет разрешения пересылать это сообщение." );
		}

		if (mysql_num_rows ( $res ) == 0) {
			stderr ( $REL_LANG->say_by_key('error'), "У вас нет разрешения пересылать это сообщение." );
		}

		$message = mysql_fetch_assoc ( $res );
		$subject = ( string ) $_POST ['subject'];
		$username = strip_tags ( $_POST ['to'] );

		// Try finding a user with specified name


		$res = sql_query ( "SELECT id FROM users WHERE LOWER(username)=LOWER(" . sqlesc ( $username ) . ") LIMIT 1" );
		if (! $res) {
			stderr ( $REL_LANG->say_by_key('error'), "Пользователя, с таким именем не существует." );
		}
		if (mysql_num_rows ( $res ) == 0) {
			stderr ( $REL_LANG->say_by_key('error'), "Пользователя, с таким именем не существует." );
		}

		$to = mysql_fetch_array ( $res );
		$to = $to [0];

		// Get Orignal sender's username
		if ($message ['sender'] == 0) {
			$from = "Системное";
		} else {
			$res = sql_query ( "SELECT * FROM users WHERE id=" . sqlesc ( $message ['sender'] ) ) or sqlerr ( __FILE__, __LINE__ );
			$from = mysql_fetch_assoc ( $res );
			$from = $from ['username'];
		}
		$body = ( string ) $_POST ['msg'];
		$body .= "Оригинальное сообщение:<hr /><blockquote>" . $message ['msg'] . "</blockquote><cite>{$from}</cite><hr /><br /><br />";
		$save = ( int ) $_POST ['save'];
		if ($save) {
			$save = 1;
		} else {
			$save = 0;
		}

		//Make sure recipient wants this message
		if (get_privilege('is_moderator',false)) {
			if ($from ["acceptpms"] == "friends") {
				$res2 = sql_query ( "SELECT * FROM friends WHERE userid=$to AND friendid=" . $CURUSER ["id"] ) or sqlerr ( __FILE__, __LINE__ );
				if (mysql_num_rows ( $res2 ) != 1)
				stderr ( "Отклонено", "Этот пользователь принимает сообщение только из списка своих друзей." );
			}

			elseif ($from ["acceptpms"] == "no")
			stderr ( "Отклонено", "Этот пользователь не принимает сообщения." );
		}

		$pms = sql_query ( "SELECT SUM(1) FROM messages WHERE (receiver = " . ($receiver ? $receiver : $to) . " AND location=1) OR (sender = " . ($receiver ? $receiver : $to) . " AND saved = 1) GROUP BY messages.id" );
		$pms = mysql_result ( $pms, 0 );
		if ($pms >= $REL_CONFIG ['pm_max'])
		stderr ( $REL_LANG->say_by_key('error'), "Ящик личных сообщений получателя заполнен, вы не можете переслать ему сообщение." );

		sql_query ( "INSERT INTO messages (poster, sender, receiver, added, subject, msg, location, saved) VALUES(" . $CURUSER ["id"] . ", " . $CURUSER ["id"] . ", $to, '" . time () . "', " . sqlesc ( $subject ) . "," . sqlesc ( ($body) ) . ", " . sqlesc ( PM_INBOX ) . ", " . sqlesc ( $save ) . ")" ) or sqlerr ( __FILE__, __LINE__ );
		stdmsg ( "Удачно", "ЛС переслано." );
	}
} //конец пересылка


//начало удаление сообщения
elseif ($action == "deletemessage") {
	if (! is_valid_id ( $_GET ["id"] ))
	stderr ( $REL_LANG->say_by_key('error'), $REL_LANG->say_by_key('invalid_id') );
	$pm_id = $_GET ['id'];

	// Delete message
	$res = sql_query ( "SELECT * FROM messages WHERE id=" . sqlesc ( $pm_id ) ) or sqlerr ( __FILE__, __LINE__ );
	if (! $res) {
		stderr ( $REL_LANG->say_by_key('error'), "Сообщения с таким ID не существует." );
	}
	if (mysql_num_rows ( $res ) == 0) {
		stderr ( $REL_LANG->say_by_key('error'), "Сообщения с таким ID не существует." );
	}
	$message = mysql_fetch_assoc ( $res );
	if ($message ['receiver'] == $CURUSER ['id'] && ! $message ['saved']) {
		$res2 = sql_query ( "DELETE FROM messages WHERE id=" . sqlesc ( $pm_id ) ) or sqlerr ( __FILE__, __LINE__ );
	} elseif ($message ['sender'] == $CURUSER ['id'] && $message ['location'] == PM_DELETED) {
		$res2 = sql_query ( "DELETE FROM messages WHERE id=" . sqlesc ( $pm_id ) ) or sqlerr ( __FILE__, __LINE__ );
	} elseif ($message ['receiver'] == $CURUSER ['id'] && $message ['saved']) {
		$res2 = sql_query ( "UPDATE messages SET location=0 WHERE id=" . sqlesc ( $pm_id ) ) or sqlerr ( __FILE__, __LINE__ );
	} elseif ($message ['sender'] == $CURUSER ['id'] && $message ['location'] != PM_DELETED) {
		$res2 = sql_query ( "UPDATE messages SET saved=0 WHERE id=" . sqlesc ( $pm_id ) ) or sqlerr ( __FILE__, __LINE__ );
	}
	if (! $res2) {
		stderr ( $REL_LANG->say_by_key('error'), "Невозможно удалить сообщение." );
	}
	if (mysql_affected_rows () == 0) {
		stderr ( $REL_LANG->say_by_key('error'), "Невозможно удалить сообщение." );
	} else {
		safe_redirect($REL_SEO->make_link('message','action','viewmailbox','id',$message['location']));
		exit ();
	}
	//конец удаление сообщения
}
//else stderr("Access Denied.","Unknown action");
?>