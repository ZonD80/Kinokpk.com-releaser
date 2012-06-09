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

<H1><?php print $mailbox_name; ?></H1>

	<?php	#amount of messages
	$inbox_all = count($CURUSER['inbox']);
	$outbox_all = count($CURUSER['outbox']);
	$all_mess = $inbox_all+$outbox_all;
	$all_mess_procent = round(($all_mess/$REL_CONFIG['pm_max'])*100);
	//print($all_mess_procent);
	$inbox_all_procent = round(($inbox_all/$REL_CONFIG['pm_max'])*100);
	$outbox_all_procent = round(($outbox_all/$REL_CONFIG['pm_max'])*100);

	print("<b>{$REL_LANG->_('Your mailbox filled by')}:</b><font color=\"#8da6cf\"> $all_mess</font><font color=\"green\"> ($all_mess_procent%) </font> <small>{$REL_LANG->_('Maximum amoutm of messages is %s',$REL_CONFIG['pm_max'])}</small><br />");
	#amount end

	print('<form id="message" action="'.$REL_SEO->make_link('message').'" method="POST"><input type="hidden" name="action" value="moveordel">');
	print("<div id=\"tabs\"><ul>
	<li class=\"tab".(($mailbox != PM_SENTBOX)?'1':'2')."\"><a href=\"".$REL_SEO->make_link('message','action','viewmailbox','box',1)."\"><span>{$REL_LANG->_('Received')} <font color=\"#8da6cf\">$inbox_all </font><font color=\"green\">($inbox_all_procent%) </font></span></a></li>
	<li nowrap=\"\" class=\"tab".(($mailbox != PM_SENTBOX)?'2':'1')."\"><a href=\"".$REL_SEO->make_link('message','action','viewmailbox','box',-1)."\"><span>{$REL_LANG->_('Sent')} <font color=\"#8da6cf\">$outbox_all </font><font color=\"green\">($outbox_all_procent%) </font></span></a></li>
	</ul></div>");
	?>

<TABLE border="0" cellpadding="4" cellspacing="0" width="100%"
	style="float: left; margin-top: 10px;">
	<TR>
		<TD width="2%" class="colhead">&nbsp;&nbsp;</TD>
		<TD width="41%" class="colhead"><?php print $REL_LANG->say_by_key('subject'); ?></TD>
		<?php		if ($mailbox == PM_INBOX )
		print ("<TD width=\"30%\" class=\"colhead\">".$REL_LANG->say_by_key('sender')."</TD>");
		else
		print ("<TD width=\"30%\" class=\"colhead\">".$REL_LANG->say_by_key('receiver')."</TD>");
		?>
		<TD width="10%" class="colhead"><?php print $REL_LANG->say_by_key('date'); ?></TD>
		<TD width="10%" class="colhead"><?php print $REL_LANG->_('Archived'); ?></TD>
		<TD width="10%" class="colhead"><?php print $REL_LANG->_('Storage period'); ?></TD>
		<TD width="2%" class="colhead"><input id="toggle-all"
			style="float: right;" type="checkbox"
			title="<?php print $REL_LANG->say_by_key('mark_all'); ?>"
			value="<?php print $REL_LANG->say_by_key('mark_all'); ?>" /></TD>

	</TR>
	<?php
	$secs_system = $REL_CRON['pm_delete_sys_days']*86400; 
	$dt_system = time() - $secs_system;
	$secs_all = $REL_CRON['pm_delete_user_days']*86400;
	$dt_all = time() - $secs_all;

	if ($mailbox != PM_SENTBOX) {
		$res = $REL_DB->query ( "SELECT m.*, u.username AS sender_username, friends.id AS fid, friends.confirmed AS fconf FROM messages AS m LEFT JOIN users AS u ON m.sender = u.id LEFT JOIN friends ON (friends.userid=m.sender AND friends.friendid={$CURUSER['id']}) OR (friends.friendid=m.sender AND friends.userid={$CURUSER['id']}) WHERE receiver=" . sqlesc ( $CURUSER ['id'] ) . " AND location=" . sqlesc ( $mailbox ) . " AND IF(m.archived_receiver=1, 1=1, IF(m.sender=0,m.added>$dt_system,m.added>$dt_all)) ORDER BY id DESC" );
	} else {
		$res = $REL_DB->query ( "SELECT m.*, u.username AS receiver_username, friends.id AS fid, friends.confirmed AS fconf FROM messages AS m LEFT JOIN users AS u ON m.receiver = u.id LEFT JOIN friends ON (friends.userid=m.receiver AND friends.friendid={$CURUSER['id']}) OR (friends.friendid=m.receiver AND friends.userid={$CURUSER['id']}) WHERE sender=" . sqlesc ( $CURUSER ['id'] ) . " AND saved=1 AND IF(m.archived_receiver<>1, 1=1, IF(m.sender=0,m.added>$dt_system,m.added>$dt_all)) ORDER BY id DESC" );
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
			$msgtext = "<small>".(mb_strlen($msgtext)>70?'...'.mb_substr($msgtext,-70):$msgtext)."</small>";
			echo ("<TD><A href=\"".$REL_SEO->make_link('message','action','viewmessage','id',$row ['id'])."\">" . $subject . "</A><br/>$msgtext</TD>\n");
			if ($mailbox != PM_SENTBOX) {
				echo ("<TD>$username</TD>\n");
			} else {
				echo ("<TD>$receiver</TD>\n");
			}
			echo ("<TD>" . mkprettytime ( $row ['added'] ) . "</TD>\n");

			echo ("<TD>" . (($row ['archived']) ? "<font color=\"red\">{$REL_LANG->_('Yes')}</font>" : $REL_LANG->_('No')) . "</TD>\n");
			if ($row ['sender'] == 0)
			$pm_del = $REL_CRON ['pm_delete_sys_days'];
			else
			$pm_del = $REL_CRON ['pm_delete_user_days'];

			echo ("<TD>" . (($row ['archived']) ? "N/A" : ($pm_del - round ( (time () - $row ['added']) / 86400 )) . " {$REL_LANG->_('day(s)')}</TD>\n"));
			echo ("<TD><INPUT type=\"checkbox\" name=\"messages[]\" title=\"" . $REL_LANG->say_by_key('mark') . "\" value=\"" . $row ['id'] . "\" id=\"checkbox_tbl_" . $row ['id'] . "\"></TD>\n</TR>\n");
		}
	}
	?>
	<tr class="colhead">
		<td class="colhead">&nbsp;</td>
		<td colspan="6" align="right" width="100%" class="colhead" />
		<input type="hidden" name="box" value="<?php print $mailbox; ?>" />
		<input type="submit" name="delete"
			title="<?php print $REL_LANG->say_by_key('delete_marked_messages'); ?>"
			value="<?php print $REL_LANG->say_by_key('delete'); ?>"
			onClick="return confirm('<?php print $REL_LANG->say_by_key('sure_mark_delete'); ?>')" />
		<input type="submit" name="markread"
			title="<?php print $REL_LANG->say_by_key('mark_as_read'); ?>"
			value="<?php print $REL_LANG->say_by_key('mark_read'); ?>"
			onClick="return confirm('<?php print $REL_LANG->say_by_key('sure_mark_read'); ?>')" />
		<input type="submit" name="archive" title="<?php print $REL_LANG->_('Add to archive'); ?>"
			value="<?php print $REL_LANG->_('Add to archive'); ?>"
			onClick="return confirm('<?php print $REL_LANG->_('Do you want to add these messages to arcive? It will not be deleted by system automatically.'); ?>')" />
		<input type="submit" name="unarchive" title="<?php print $REL_LANG->_('Remove from archive'); ?>"
			value="<?php print $REL_LANG->_('Remove from archive'); ?>"
			onClick="return confirm('<?php print $REL_LANG->_('Do you want to remove these messages from arcive? It will be deleted by system automatically.'); ?>')" />
		</td>

	</tr>
</table>
</form>
<div align="left"><img src="pic/pn_inboxnew.gif" alt="<?php print $REL_LANG->_('Unread'); ?>" />
	<?php print $REL_LANG->say_by_key('mail_unread_desc'); ?><br />
<img src="pic/pn_inbox.gif" alt="<?php print $REL_LANG->_('Readed'); ?>" /> <?php print $REL_LANG->say_by_key('mail_read_desc'); ?></div>
	<?php	$REL_TPL->stdfoot();
}


elseif ($action == "viewmessage") {
	if (! is_valid_id ( $_GET ["id"] ))
	$REL_TPL->stderr ( $REL_LANG->say_by_key('error'), $REL_LANG->say_by_key('invalid_id') );
	$pm_id = (int)$_GET ['id'];

	// Get the message
	if (!get_privilege('view_pms',false)) {
		$res = $REL_DB->query ( 'SELECT * FROM messages WHERE messages.id=' . sqlesc ( $pm_id ) . ' AND (messages.receiver=' . sqlesc ( $CURUSER ['id'] ) . ' OR (messages.sender=' . sqlesc ( $CURUSER ['id'] ) . ' AND messages.saved=1)) LIMIT 1' );
		if (mysql_num_rows ( $res ) == 0) {
			$REL_TPL->stderr ( $REL_LANG->say_by_key('error'), $REL_LANG->_('There is no such message') );
		}

	} else {
		$res = $REL_DB->query ( 'SELECT * FROM messages WHERE messages.id=' . sqlesc ( $pm_id ) );
		if (mysql_num_rows ( $res ) == 0) {
			$REL_TPL->stderr ( $REL_LANG->say_by_key('error'),  $REL_LANG->_('There is no such message') );
		}
		$adminview = 1;
	}

	// Prepare for displaying message
	$message = mysql_fetch_assoc ( $res );
	if ($message ['sender'] == $CURUSER ['id']) {
		// Display to
		$res2 = $REL_DB->query ( "SELECT username FROM users WHERE id=" . sqlesc ( $message ['receiver'] ) );
		$sender = mysql_fetch_array ( $res2 );
		$sender = "<A href=\"".$REL_SEO->make_link('userdetails','id',$message['receiver'],'username',translit($sender [0]))."\">" . $sender [0] . "</A>";
		$reply = "";
		$from = $REL_LANG->_('To');
	} else {
		$from = $REL_LANG->_('From');
		if ($message ['sender'] == 0) {
			$sender = $REL_LANG->_('From system');
			$reply = "";
		} else {
			$res2 = $REL_DB->query ( "SELECT username FROM users WHERE id=" . sqlesc ( $message ['sender'] ) );
			$sender = mysql_fetch_array ( $res2 );
			$sender = "<A href=\"".$REL_SEO->make_link('userdetails','id',$message['sender'],'username',translit($sender [0]))."\">{$sender [0]}</A>";
			$reply = " [ <A href=\"".$REL_SEO->make_link('message','action','sendmessage','receiver',$message['sender'],'replyto',$pm_id)."\">{$REL_LANG->_('Reply')}</A> ]";
		}
	}
	$body = format_comment ( $message ['msg'] );
	$added = mkprettytime ( $message ['added'] );
	$unread = ($message ['unread'] ? "<SPAN style=\"color: #FF0000;\"><b>({$REL_LANG->_('New message')})</b></A>" : "");

	$subject = makesafe ( $message ['subject'] );
	if (mb_strlen ( $subject ) <= 0) {
		$subject = $REL_LANG->_('(No subject)');
	}
	// Mark message unread
	if ($adminview && ($CURUSER ['id'] != $message ['receiver']) && ($CURUSER ['id'] != $message ['sender'])) {
	} else
	$REL_DB->query ( "UPDATE messages SET unread=0 WHERE id=" . sqlesc ( $pm_id ) . " AND receiver=" . sqlesc ( $CURUSER ['id'] ) . " LIMIT 1" );
	// Display message
	$REL_TPL->stdhead( $REL_LANG->_('Private message (subject: %s)',$subject) );
	?>
<TABLE width="100%" border="0" cellpadding="4" cellspacing="0">

	<TR>
		<TD class="colhead" colspan="2"><?php print $REL_LANG->_('Subject') ?>: <?php print $subject; ?><span class="higo"><a
			href="javascript:history.go(-1);"><?php print $REL_LANG->_('Go back') ?></a></span></TD>

	</TR>
	<TR>
		<TD width="50%" class="colhead"><?php print $from; ?></TD>
		<TD width="50%" class="colhead"><?php print $REL_LANG->_('Date sent') ?></TD>
	</TR>
	<TR>
		<TD><?php print $sender; ?></TD>
		<TD><?php print $added; ?>&nbsp;&nbsp;<?php print $unread; ?></TD>
	</TR>
	<TR>
		<TD colspan="2" style="padding: 20px;"><?php print $body; ?></TD>
	</TR>
	<TR>
		<TD align="right" colspan=2><?php
		if ($adminview && ($CURUSER ['id'] != $message ['receiver']) && ($CURUSER ['id'] != $message ['sender'])) {
			$a_receiver = $REL_DB->query ( "SELECT username FROM users WHERE id = " . $message ['receiver'] );
			$a_receiver = mysql_result ( $a_receiver, 0 );

			print ( '<font color="red">'.$REL_LANG->_('You are viewing this message as administrator').'</font> '.$REL_LANG->_('Receiver').': <a href="'.$REL_SEO->make_link('userdetails','id',$message['receiver'],'username',translit($a_receiver)).'">' . $a_receiver . '</a><br />' );
		}
		print ( "[ <A onClick=\"return confirm('{$REL_LANG->_('Are you sure?')}')\" href=\"".$REL_SEO->make_link('message','action','deletemessage','id',$pm_id)."\">{$REL_LANG->_('Delete')}</A> ]$reply [ <A href=\"".$REL_SEO->make_link('message','action','forward','id',$pm_id)."\">{$REL_LANG->_('Forward')}</A> ]" . reportarea ( $message ['id'], 'messages' ) );
		?></TD>
	</TR>
</TABLE>
		<?php		set_visited('messages',$pm_id);
		$REL_TPL->stdfoot();
}


elseif ($action == "sendmessage") {

	if (! is_valid_id ( $_GET ["receiver"] ))
	$REL_TPL->stderr ( $REL_LANG->say_by_key('error'), $REL_LANG->_('Invalid receiver') );
	$receiver = ( int ) $_GET ["receiver"];

	if ($_GET ['replyto'] && ! is_valid_id ( $_GET ["replyto"] ))
	$REL_TPL->stderr ( $REL_LANG->say_by_key('error'), $REL_LANG->_('Invalid message ID') );
	$replyto = ( int ) $_GET ["replyto"];

	$res = $REL_DB->query ( "SELECT * FROM users WHERE id=$receiver" ) or die ( mysql_error () );
	$user = mysql_fetch_assoc ( $res );
	if (! $user)
	$REL_TPL->stderr ( $REL_LANG->say_by_key('error'), $REL_LANG->_('There is no user with such ID') );

	if ($replyto) {
		$res = $REL_DB->query ( "SELECT * FROM messages WHERE id=$replyto" );
		$msga = mysql_fetch_assoc ( $res );
		if ($msga ["receiver"] != $CURUSER ["id"])
		$REL_TPL->stderr ( $REL_LANG->say_by_key('error'), $REL_LANG->_('You are trying to reply on not yours message') );

		$res = $REL_DB->query ( "SELECT username FROM users WHERE id=" . $msga ["sender"] );
		$usra = mysql_fetch_assoc ( $res );
		$body .= "{$REL_LANG->_('%s wrote:',$usra[username])}<br/>" . format_comment ( $msga ['msg'] ) . "<hr /><br />";
		// Change
		if (!preg_match("/^Re\(([0-9]+)\)\:/",$msga ['subject']))

		$subject = "Re(1): ".makesafe($msga ['subject']);
		else $subject = preg_replace("/^Re\(([0-9]+)\)\:/e","'Re('.(\\1+1).'):'",makesafe($msga ['subject']));



		// End of Change
	}

	$REL_TPL->stdhead( $REL_LANG->_('Private messages sender'), false );
	?>
<script language="JavaScript">
<!--

required = new Array("subject");
required_show = new Array("<?php print $REL_LANG->_('Message subject'); ?>");


function SendForm () {
  var i, j;

for(j=0; j<required.length; j++) {
    for (i=0; i<document.message.length; i++) {
        if (document.message.elements[i].name == required[j] &&
  document.forms[0].elements[i].value == "" ) {
            alert('<?php print $REL_LANG->_('Please fill'); ?> ' + required_show[j]);
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
			action="<?php print $REL_SEO->make_link('message'); ?>"
			onsubmit="return SendForm();"><input type=hidden name=action
			value=takemessage>
		<table class=message cellspacing=0 cellpadding=5>
			<tr>
				<td colspan=2 class=colhead><?php print $REL_LANG->_('Receiver');?> <a class=altlink_white
					href="<?php print $REL_SEO->make_link('userdetails','id',$receiver,'username',translit($user['username'])); ?>"><?php print $user ["username"]; ?></a></td>
			</tr>
			<TR>
				<TD colspan="2"><B><?php print $REL_LANG->_('Subject'); ?>:&nbsp;&nbsp;</B> <INPUT name="subject"
					type="text" size="60" value="<?php print $subject; ?>" maxlength="255"></TD>
			</TR>
			<tr>
				<td <?php print $replyto ? " colspan=2" : ""; ?>><?php				print textbbcode ( "msg", $body );
				?></td>
			</tr>
			<tr>
			<?php			if ($replyto) {
				?>
				<td align=center><input type=checkbox name='delete' value='1'
				<?php print $CURUSER ['deletepms'] ? "checked" : ""; ?> /><?php print $REL_LANG->_('Delete message after sending'); ?> <input type=hidden name=origmsg value=<?php print $replyto; ?> /></td>
				<?php			}
			?>
				<td align=center><input type=checkbox name='save' value='1'
				<?php print $CURUSER ['savepms'] ? "checked" : ""; ?> /><?php print $REL_LANG->_('Save message in "sent" mailbox'); ?></td>
			</tr>
			<tr>
				<td align="center"><input type="checkbox" name='archive' value='1' /><?php print $REL_LANG->_('Add message to archive after sending'); ?></td>
			</tr>
			<tr>
				<td <?php print $replyto ? " colspan=2" : ""; ?> align=center><input
					type=submit value="<?php print $REL_LANG->_('Send message'); ?>!" class=btn /></td>
			</tr>
		</table>
		<input type=hidden name=receiver value=<?php print $receiver; ?> /></form>
		</div>
		</td>
	</tr>
</table>
				<?php				$REL_TPL->stdfoot();
}

elseif ($action == 'takemessage') {

	$receiver = ( int ) $_POST ["receiver"];
	$origmsg = ( int ) $_POST ["origmsg"];
	$save = $_POST ["save"];
	$archive = $_POST ["archive"];
	$returnto = urlencode ( $_POST ["returnto"] );
	if (! is_valid_id ( $receiver ) || ($origmsg && ! is_valid_id ( $origmsg )))
	$REL_TPL->stderr ( $REL_LANG->say_by_key('error'), $REL_LANG->say_by_key('invalid_id') );
	$msg = trim ( $_POST ["msg"] );
	if (! $msg)
	$REL_TPL->stderr ( $REL_LANG->say_by_key('error'), $REL_LANG->_('Please fill message text') );
	$subject = trim ( $_POST ['subject'] );
	if (! $subject)
	$REL_TPL->stderr ( $REL_LANG->say_by_key('error'), $REL_LANG->_('Please fill message subject') );

	// ANTISPAM SYSTEM BEGIN
	$last_pmres = $REL_DB->query ( "SELECT " . time () . "-added AS seconds, msg,id FROM messages WHERE sender=" . $CURUSER ['id'] . " OR poster=" . $CURUSER ['id'] . " ORDER BY added DESC LIMIT 4" );
	while ( $last_pmresrow = mysql_fetch_array ( $last_pmres ) ) {
		$last_pmrow [] = $last_pmresrow;
		$msgids [] = $last_pmresrow ['id'];
	}
	//   print_r($last_pmrow);
	if ($last_pmrow [0]) {
		if (($REL_CONFIG ['as_timeout'] > round ( $last_pmrow [0] ['seconds'] )) && $REL_CONFIG ['as_timeout']) {
			$seconds = $REL_CONFIG ['as_timeout'] - round ( $last_pmrow [0] ['seconds'] );
			$REL_TPL->stderr ( $REL_LANG->say_by_key('error'), $REL_LANG->_('We have anti-flood system. Please try to send comment again in %s seconds',$seconds).' '."<a href=\"javascript: history.go(-1)\">{$REL_LANG->_('Go back')}</a>");
		}

		if ($REL_CONFIG ['as_check_messages'] && ($last_pmrow [0] ['msg'] == $msg) && ($last_pmrow [1] ['msg'] == $msg) && ($last_pmrow [2] ['msg'] == $msg) && ($last_pmrow [3] ['msg'] == $msg)) {
			$msgview = '';
			foreach ( $msgids as $msgid ) {
				$msgview .= "\n{$REL_LANG->_to(0,'<a href="%s">Message with ID = %s</a> from %s',$REL_SEO->make_link('message','action','viewmessage','id',$msgid),$msgid,  make_user_link())}";
			}
			$modcomment = $REL_DB->query ( "SELECT modcomment FROM users WHERE id=" . $CURUSER ['id'] );
			$modcomment = mysql_result ( $modcomment, 0 );
			if (strpos ( $modcomment, "Maybe spammer" ) === false) {
				$reason = sqlesc($REL_LANG->_('User %s can be spammer because his last 5 comments are the same',make_user_link())." ".$msgview);
				$REL_DB->query ( "INSERT INTO reports (reportid,userid,type,motive,added) VALUES ({$CURUSER['id']},0,'users',$reason," . time () . ")" );
				$modcomment .= "\n" . time () . " - Maybe spammer";
				$REL_DB->query ( "UPDATE users SET modcomment = " . sqlesc ( $modcomment ) . " WHERE id =" . $CURUSER ['id'] );
				$REL_TPL->stderr ( $REL_LANG->say_by_key('error'), $REL_LANG->_('We have anti-spam system. Last 5 messages from you are the same. Please, write something else:) <b><u>ATTENTION! IF YOU TRY TO SEND THE SAME COMMENT AGAIN, YOU WILL BE AUTOMATICALLY BANNED BY SYSTEM!!!</u></b>')." <a href=\"javascript: history.go(-1)\">{$REL_LANG->_('Go back')}</a>" );
					
			} else {
				$REL_DB->query ( "UPDATE users SET enabled=0, dis_reason='Spam' WHERE id=" . $CURUSER ['id'] );

				$reason = sqlesc($REL_LANG->_('User %s banned by system due spam. His IP address is %',make_user_link(),$CURUSER['ip']));
				$REL_DB->query ( "INSERT INTO reports (reportid,userid,type,motive,added) VALUES ({$CURUSER['id']},0,'users',$reason," . time () . ")" );
				$REL_TPL->stderr ( $REL_LANG->_('Congratulations'),$REL_LANG->_('You are automatically banned due to spam in comments. You can report this issue to <a href="%s">Administrators</a>',$REL_SEO->make_link('contact')));
			}
		}
		
	}
	// ANTISPAM SYSTEM END
	$pms = $REL_DB->query ( "SELECT SUM(1) FROM messages WHERE (receiver = $receiver AND location=1) OR (sender = $receiver AND saved = 1)" );
	$pms = (int)mysql_result ( $pms, 0 );
	if ($pms >= $REL_CONFIG ['pm_max'])
	$REL_TPL->stderr ( $REL_LANG->say_by_key('error'), $REL_LANG->_('Inbox of receiver is full. You can not send message to this user.') );

	if ($save) {
		$pms = $REL_DB->query ( "SELECT SUM(1) FROM messages WHERE (receiver = " . $CURUSER ['id'] . " AND location=1) OR (sender = " . $CURUSER ['id'] . " AND saved = 1)" );
		$pms = (int)mysql_result ( $pms, 0 );
		if ($pms >= $REL_CONFIG ['pm_max'])
		$REL_TPL->stderr ( $REL_LANG->_('Error'), $REL_LANG->_('Unable to send and save message. Your mailbox is full. Please free some space for more messages (maximum is %s)',$REL_CONFIG['pm_max'] ));
	}

	// Change
	$save = ($save) ? 1 : 0;
	$archive = ($archive) ? 1 : 0;
	// End of Change
	$res = $REL_DB->query ( "SELECT email, acceptpms, notifs, last_access AS la FROM users WHERE id=$receiver" );
	$user = mysql_fetch_assoc ( $res );
	if (! $user)
	$REL_TPL->stderr ( $REL_LANG->say_by_key('error'), $REL_LANG->_('Invalid receiver') );
	//Make sure recipient wants this message

	if (!get_privilege('is_moderator',false)) {
		if ($user ["acceptpms"] == "friends") {
			$res2 = $REL_DB->query ( "SELECT * FROM friends WHERE userid=$receiver AND friendid=" . $CURUSER ["id"] );
			if (mysql_num_rows ( $res2 ) != 1)
			$REL_TPL->stderr ( $REL_LANG->_('Message denied'), $REL_LANG->_('This user accepting messages only from his(her) friends. <a href="%s">Make frinedship request to receiver</a>',$REL_SEO->make_link('friends','action','add','id',$receiver)) );
		} elseif ($user ["acceptpms"] == "no")
		$REL_TPL->stderr ( $REL_LANG->_('Message denied'), $REL_LANG->_('This user does not accept private messages') );
	}
	$REL_DB->query ( "INSERT INTO messages (poster, sender, receiver, added, msg, subject, saved, location, archived) VALUES(" . $CURUSER ["id"] . ", " . $CURUSER ["id"] . ",
	$receiver, '" . time () . "', " . sqlesc ( ($msg) ) . ", " . sqlesc ( $subject ) . ", " . sqlesc ( $save ) . ",  1, " . sqlesc ( $archive ) . ")" );
	$sended_id = mysql_insert_id ();


	$body = "
	{$REL_LANG->_('%s sent private message to you!',  make_user_link())}

{$REL_LANG->_('To view this message follow this link')}:

".$REL_SEO->make_link('message','action','viewmessage','id',$sended_id)."


";

	// email notifs
	send_notifs('unread',$body,$receiver);

	$delete = $_POST ["delete"];
	if ($origmsg) {
		if ($delete) {
			// Make sure receiver of $origmsg is current user
			$res = $REL_DB->query ( "SELECT * FROM messages WHERE id=$origmsg" );
			if (mysql_num_rows ( $res ) == 1) {
				$arr = mysql_fetch_assoc ( $res );
				if ($arr ["receiver"] != $CURUSER ["id"])
				$REL_TPL->stderr ( $REL_LANG->say_by_key('error'), $REL_LANG->_('You are trying to delete message, that does not belong to you') );
				if (! $arr ["saved"])
				$REL_DB->query ( "DELETE FROM messages WHERE id=$origmsg" );
				elseif ($arr ["saved"])
				$REL_DB->query ( "UPDATE messages SET location = '0' WHERE id=$origmsg" );
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
		$REL_TPL->stderr ( $REL_LANG->say_by_key('success'), $REL_LANG->_("Message was successfuly sent") ,'success');
	}

}

elseif ($action == 'mass_pm') {
	get_privilege('mass_pm');
	if (! is_valid_id ( $_POST ['n_pms'] ))
	$REL_TPL->stderr ( $REL_LANG->say_by_key('error'), $REL_LANG->say_by_key('invalid_id') );
	$n_pms = ( int ) $_POST ['n_pms'];
	$pmees = htmlspecialchars ( $_POST ['pmees'] );

	$REL_TPL->stdhead( $REL_LANG->_('Private messages sender'), false );
	?>
<table class=main border=0 cellspacing=0 cellpadding=0>
	<tr>
		<td class=embedded>
		<div align=center>
		<form method=post action=<?php print $_SERVER ['PHP_SELF']; ?> name=message><input
			type=hidden name=action value=takemass_pm> <?php			if ($_SERVER ["HTTP_REFERER"]) {
				?> <input type=hidden name=returnto
			value="<?php print htmlspecialchars ( $_SERVER ["HTTP_REFERER"] ); ?>"> <?php			}
			?>
		<table border=1 cellspacing=0 cellpadding=5>
			<tr>
				<td class=colhead colspan=2><?php print $REL_LANG->_('Amout of messages to sent: %s',$n_pms); ?></td>
			</tr>
			<TR>
				<TD colspan="2"><B><?php print $REL_LANG->_('Subject'); ?>:&nbsp;&nbsp;</B> <INPUT name="subject"
					type="text" size="60" maxlength="255"></TD>
			</TR>
			<tr>
				<td colspan="2">
				<div align="center"><?php print print textbbcode ( "msg", $body ); ?></div>
				</td>
			</tr>
			<tr>
				<td colspan="2">
				<div align="center"><b><?php print $REL_LANG->_('Comment to profile'); ?>:&nbsp;&nbsp;</b> <input
					name="comment" type="text" size="70" /></div>
				</td>
			</tr>
			<tr>
				<td>
				<div align="center"><b><?php print $REL_LANG->_('From'); ?>:&nbsp;&nbsp;</b> <?php print $CURUSER ['username']; ?>
				<input name="sender" type="radio" value="self" checked /> &nbsp;
				<?php print $REL_LANG->_('From system'); ?> <input name="sender" type="radio" value="system" /></div>
				</td>
			</tr>
			<tr>
				<td colspan="2" align=center><input type=submit value="<?php print $REL_LANG->_('Send'); ?>!"
					class=btn /></td>
			</tr>
		</table>
		<input type=hidden name=pmees value="<?php print $pmees; ?>" /> <input
			type=hidden name=n_pms value=<?php print $n_pms; ?> /></form>
		<br />
		<br />
		</div>
		</td>
	</tr>
</table>
			<?php
			$REL_TPL->stdfoot();

}

elseif ($action == 'takemass_pm') {
	get_privilege('mass_pm');
	$msg = trim ( $_POST ["msg"] );
	if (! $msg)
	$REL_TPL->stderr ( $REL_LANG->say_by_key('error'), $REL_LANG->_('Please fill message text') );
	$sender_id = ((string)$_POST ['sender'] == 'system' ? 0 : $CURUSER ['id']);
	$n_pms = ( int ) $_POST ['n_pms'];
	$comment = ( string ) $_POST ['comment'];
	$from_is = mysql_real_escape_string ( unesc ( (string)$_POST ['pmees'] ) );
	// Change
	$subject = trim ( (string)$_POST ['subject'] );
	$query = "INSERT INTO messages (sender, receiver, added, msg, subject, location, poster) " . "SELECT $sender_id, u.id, " . time () . ", " . sqlesc (  $msg ) . ", " . sqlesc ( $subject ) . ", 1, $sender_id " . $from_is;
	// End of Change
	$REL_DB->query ( $query );
	$n = mysql_affected_rows ();
	// add a custom text or stats snapshot to comments in profile
	if ($comment) {
		$res = $REL_DB->query ( "SELECT u.id, u.modcomment " . $from_is );
		if (mysql_num_rows ( $res ) > 0) {
			$l = 0;
			while ( $user = mysql_fetch_array ( $res ) ) {
				unset ( $new );
				$old = $user ['modcomment'];
				if ($comment)
				$new = $comment;

				$new .= $old ? ("\n" . $old) : $old;
				$REL_DB->query ( "UPDATE users SET modcomment = " . sqlesc ( $new ) . " WHERE id = " . $user ['id'] );
				if (mysql_affected_rows ())
				$l ++;
			}
		}
	}
	safe_redirect($REL_SEO->make_link('message'),3);
	$REL_TPL->stderr ( $REL_LANG->say_by_key('success'), $REL_LANG->_('Amout of sent messages: %s',$n_pms) . ($l ? $REL_LANG->_('Profile comments updated: %s',$l) : ""), 'success' );
}

elseif ($action == "moveordel") {
	if (isset ( $_POST ["id"] ) && ! is_valid_id ( $_POST ["id"] ))
	$REL_TPL->stderr ( $REL_LANG->say_by_key('error'), $REL_LANG->say_by_key('invalid_id') );
	$pm_id = (int)$_POST ['id'];

	$pm_box = ( int ) $_POST ['box'];
	if (! is_array ( $_POST ['messages'] ))
	$REL_TPL->stderr ( $REL_LANG->say_by_key('error'), $REL_LANG->say_by_key('invalid_id') );
	$pm_messages = $_POST ['messages'];
	if ($_POST ['move']) {
		if ($pm_id) {
			// Move a single message
			@$REL_DB->query ( "UPDATE messages SET location=" . sqlesc ( $pm_box ) . ", saved = 1 WHERE id=" . sqlesc ( $pm_id ) . " AND receiver=" . $CURUSER ['id'] . " LIMIT 1" );
		} else {
			// Move multiple messages
			@$REL_DB->query ( "UPDATE messages SET location=" . sqlesc ( $pm_box ) . ", saved = 1 WHERE id IN (" . implode ( ", ", array_map ( "sqlesc", array_map ( "intval", $pm_messages ) ) ) . ') AND receiver=' . $CURUSER ['id'] );
		}
		// Check if messages were moved
		if (@mysql_affected_rows () == 0) {
			$REL_TPL->stderr ( $REL_LANG->say_by_key('error'), $REL_LANG->_('Unable to move messages') );
		}
		safe_redirect($REL_SEO->make_link('message','action','viewmailbox','box',$pm_box));
		exit ();
	} elseif ($_POST ['delete']) {
		if ($pm_id) {
			// Delete a single message
			$res = $REL_DB->query ( "SELECT * FROM messages WHERE id=" . sqlesc ( $pm_id ) );
			$message = mysql_fetch_assoc ( $res );
			if ($message ['receiver'] == $CURUSER ['id'] && ! $message ['saved']) {
				$REL_DB->query ( "DELETE FROM messages WHERE id=" . sqlesc ( $pm_id ) );
			} elseif ($message ['sender'] == $CURUSER ['id'] && $message ['location'] == PM_DELETED) {
				$REL_DB->query ( "DELETE FROM messages WHERE id=" . sqlesc ( $pm_id ) );
			} elseif ($message ['receiver'] == $CURUSER ['id'] && $message ['saved']) {
				$REL_DB->query ( "UPDATE messages SET location=0 WHERE id=" . sqlesc ( $pm_id ) );
			} elseif ($message ['sender'] == $CURUSER ['id'] && $message ['location'] != PM_DELETED) {
				$REL_DB->query ( "UPDATE messages SET saved=0 WHERE id=" . sqlesc ( $pm_id ) );
			}
		} else {
			// Delete multiple messages
			if (is_array ( $pm_messages ))
			foreach ( $pm_messages as $id ) {
				$res = $REL_DB->query ( "SELECT * FROM messages WHERE id=" . sqlesc ( ( int ) $id ) );
				$message = mysql_fetch_assoc ( $res );
				if ($message ['receiver'] == $CURUSER ['id'] && ! $message ['saved']) {
					$REL_DB->query ( "DELETE FROM messages WHERE id=" . sqlesc ( ( int ) $id ) );
				} elseif ($message ['sender'] == $CURUSER ['id'] && $message ['location'] == PM_DELETED) {
					$REL_DB->query ( "DELETE FROM messages WHERE id=" . sqlesc ( ( int ) $id ) );
				} elseif ($message ['receiver'] == $CURUSER ['id'] && $message ['saved']) {
					$REL_DB->query ( "UPDATE messages SET location=0 WHERE id=" . sqlesc ( ( int ) $id ) );
				} elseif ($message ['sender'] == $CURUSER ['id'] && $message ['location'] != PM_DELETED) {
					$REL_DB->query ( "UPDATE messages SET saved=0 WHERE id=" . sqlesc ( ( int ) $id ) );
				}
			}
		}
		// Check if messages were moved
		if (@mysql_affected_rows () == 0) {
			$REL_TPL->stderr ( $REL_LANG->say_by_key('error'), $REL_LANG->_('Messages can not be moved') );
		} else {
			safe_redirect($REL_SEO->make_link('message','action','viewmailbox','box',$pm_box));
			exit ();
		}
	} elseif ($_POST ["markread"]) {
		if ($pm_id) {
			$REL_DB->query ( "UPDATE messages SET unread=0 WHERE id = " . sqlesc ( $pm_id ) );
		}
		else {
			if (is_array ( $pm_messages ))
			foreach ( $pm_messages as $id ) {
				$res = $REL_DB->query ( "SELECT * FROM messages WHERE id=" . sqlesc ( ( int ) $id ) );
				$message = mysql_fetch_assoc ( $res );
				$REL_DB->query ( "UPDATE messages SET unread=0 WHERE id = " . sqlesc ( ( int ) $id ) );
			}
		}

		safe_redirect($REL_SEO->make_link('message','action','viewmailbox','box',$pm_box));
		exit ();

	} elseif ($_POST ["archive"]) {
		if ($pm_id) {
			$REL_DB->query ( "UPDATE messages SET archived=IF(sender={$CURUSER['id']},1,archived), archived_receiver=IF(sender={$CURUSER['id']},archived_receiver,1) WHERE id = " . sqlesc ( $pm_id ) );
		}
		else {
			if (is_array ( $pm_messages ))
			foreach ( $pm_messages as $id ) {
				$res = $REL_DB->query ( "SELECT * FROM messages WHERE id=" . sqlesc ( ( int ) $id ) );
				$message = mysql_fetch_assoc ( $res );
				$REL_DB->query ( "UPDATE messages SET archived=IF(sender={$CURUSER['id']},1,archived), archived_receiver=IF(sender={$CURUSER['id']},archived_receiver,1) WHERE id = " . sqlesc ( ( int ) $id ) );
			}
		}

		safe_redirect($REL_SEO->make_link('message','action','viewmailbox','box',$pm_box),1 );
		$REL_TPL->stderr($REL_LANG->say_by_key('success'), $REL_LANG->_('Messages sucessfully archived'),'success');

	} elseif ($_POST ["unarchive"]) {
		if ($pm_id) {
			$REL_DB->query ( "UPDATE messages SET archived=IF(sender={$CURUSER['id']},0,archived), archived_receiver=IF(sender={$CURUSER['id']},archived_receiver,0) AND id = " . sqlesc ( $pm_id ) );
		}
		else {
			if (is_array ( $pm_messages ))
			foreach ( $pm_messages as $id ) {
				$res = $REL_DB->query ( "SELECT * FROM messages WHERE id=" . sqlesc ( ( int ) $id ) );
				$message = mysql_fetch_assoc ( $res );
				$REL_DB->query ( "UPDATE messages SET archived=IF(sender={$CURUSER['id']},0,archived), archived_receiver=IF(sender={$CURUSER['id']},archived_receiver,0) AND id = " . sqlesc ( ( int ) $id ) );
			}
		}

		safe_redirect($REL_SEO->make_link('message','action','viewmailbox','box',$pm_box),1 );
		$REL_TPL->stderr($REL_LANG->say_by_key('success'), $REL_LANG->_('Messages sucessfully removed from archive'),'success');
	}

	$REL_TPL->stderr ( $REL_LANG->say_by_key('error'), $REL_LANG->_('Unknown action') );
}
elseif ($action == "forward") {
	if ($_SERVER ['REQUEST_METHOD'] == 'GET') {
		// Display form
		if (! is_valid_id ( $_GET ["id"] ))
		$REL_TPL->stderr ( $REL_LANG->say_by_key('error'), $REL_LANG->say_by_key('invalid_id') );
		$pm_id = (int)$_GET ['id'];

		// Get the message
		$res = $REL_DB->query ( 'SELECT * FROM messages WHERE id=' . sqlesc ( $pm_id ) . ' AND (receiver=' . sqlesc ( $CURUSER ['id'] ) . ' OR sender=' . sqlesc ( $CURUSER ['id'] ) . ') LIMIT 1' );

		if (! $res) {
			$REL_TPL->stderr ( $REL_LANG->say_by_key('error'), $REL_LANG->_('You can not forward this message') );
		}
		if (mysql_num_rows ( $res ) == 0) {
			$REL_TPL->stderr ( $REL_LANG->say_by_key('error'), $REL_LANG->_('You can not forward this message') );
		}
		$message = mysql_fetch_assoc ( $res );

		// Prepare variables
		if (!preg_match("/^Fwd\(([0-9]+)\)\:/",$message ['subject']))

		$subject = "Fwd(1): ".makesafe($message ['subject']);
		else $subject = preg_replace("/^Fwd\(([0-9]+)\)\:/e","'Fwd('.(\\1+1).'):'",makesafe($message ['subject']));

		$from = $message ['sender'];
		$orig = $message ['receiver'];

		$res = $REL_DB->query ( "SELECT id,username,class,donor,warned,enabled FROM users WHERE id=" . sqlesc ( $orig ) . " OR id=" . sqlesc ( $from ) );

		$orig2 = mysql_fetch_assoc ( $res );
		$orig_name = make_user_link($orig2);
		if ($from == 0) {
			$from_name = $REL_LANG->_('From system');
			$from2 ['username'] = $REL_LANG->_('From system');
		} else {
			$from2 = mysql_fetch_assoc ( $res );
			$from_name = make_user_link($from2);
		}

		$body = "{$REL_LANG->_('%s wrote:',$from2['username'])}<br />" . format_comment ( $message ['msg'] . "<hr /><br />" );

		$REL_TPL->stdhead( $subject );
		?>

<FORM action="<?php print $REL_SEO->make_link('message'); ?>" method="post"><INPUT
	type="hidden" name="action" value="forward"> <INPUT type="hidden"
	name="id" value="<?php print $pm_id; ?>">
<TABLE border="0" cellpadding="4" cellspacing="0">
	<TR>
		<TD class="colhead" colspan="2"><?php print $subject; ?></TD>
	</TR>
	<TR>
		<TD><?php print $REL_LANG->_('Receiver'); ?>:</TD>
		<TD><INPUT type="text" name="to" value="<?php print $REL_LANG->_('Enter username'); ?>" onclick="javascript:$(this).val('');" size="83"></TD>
	</TR>
	<TR>
		<TD><?php print $REL_LANG->_('Will be sent from'); ?>:</TD>
		<TD><?php print $orig_name; ?></TD>
	</TR>
	<TR>
		<TD><?php print $REL_LANG->_('Sender'); ?>:</TD>
		<TD><?php print $from_name; ?></TD>
	</TR>
	<TR>
		<TD><?php print $REL_LANG->_('Subject'); ?>:</TD>
		<TD><INPUT type="text" name="subject" value="<?php print $subject; ?>" size="83"></TD>
	</TR>
	<TR>
		<TD><?php print $REL_LANG->_('Message'); ?>:</TD>
		<TD><?php print ( textbbcode ( "msg",$body ) ); ?></TD>
	</TR>
	<TR>
		<TD colspan="2" align="center"><?php print $REL_LANG->_('Save message'); ?> <INPUT
			type="checkbox" name="save" value="1"
			<?php print $CURUSER ['savepms'] ? " checked" : ""; ?>>&nbsp;<INPUT
			type="submit" value="<?php print $REL_LANG->_('Forward'); ?>"></TD>
	</TR>
</TABLE>
</FORM>
			<?php			$REL_TPL->stdfoot();
	}

	else {

		// Forward the message
		if (! is_valid_id ( $_POST ["id"] ))
		$REL_TPL->stderr ( $REL_LANG->say_by_key('error'), $REL_LANG->say_by_key('invalid_id') );
		$pm_id = $_POST ['id'];

		// Get the message
		$res = $REL_DB->query ( 'SELECT * FROM messages WHERE id=' . sqlesc ( $pm_id ) . ' AND (receiver=' . sqlesc ( $CURUSER ['id'] ) . ' OR sender=' . sqlesc ( $CURUSER ['id'] ) . ') LIMIT 1' );
		if (! $res) {
			$REL_TPL->stderr ( $REL_LANG->say_by_key('error'), $REL_LANG->_('You can not forward this message') );
		}

		if (mysql_num_rows ( $res ) == 0) {
			$REL_TPL->stderr ( $REL_LANG->say_by_key('error'), $REL_LANG->_('You can not forward this message') );
		}

		$message = mysql_fetch_assoc ( $res );
		$subject = ( string ) $_POST ['subject'];
		$username = strip_tags ( $_POST ['to'] );

		// Try finding a user with specified name


		$res = $REL_DB->query ( "SELECT id FROM users WHERE LOWER(username)=LOWER(" . sqlesc ( $username ) . ") LIMIT 1" );
		if (! $res) {
			$REL_TPL->stderr ( $REL_LANG->say_by_key('error'), $REL_LANG->_('There is no user with this username'));
		}
		if (mysql_num_rows ( $res ) == 0) {
			$REL_TPL->stderr ( $REL_LANG->say_by_key('error'), $REL_LANG->_('There is no user with this username') );
		}

		$to = mysql_fetch_array ( $res );
		$to = $to [0];

		// Get Orignal sender's username
		if ($message ['sender'] == 0) {
			$from = $REL_LANG->_('From system');
		} else {
			$res = $REL_DB->query ( "SELECT * FROM users WHERE id=" . sqlesc ( $message ['sender'] ) );
			$from = mysql_fetch_assoc ( $res );
			$from = $from ['username'];
		}
		$body = ( string ) $_POST ['msg'];
		$body .= "{$REL_LANG->_('%s wrote:'.$from)}<br />" . $message ['msg'] . "<hr /><br />";
		$save = ( int ) $_POST ['save'];
		if ($save) {
			$save = 1;
		} else {
			$save = 0;
		}

		//Make sure recipient wants this message
		if (get_privilege('is_moderator',false)) {
			if ($from ["acceptpms"] == "friends") {
				$res2 = $REL_DB->query ( "SELECT * FROM friends WHERE userid=$to AND friendid=" . $CURUSER ["id"] );
				if (mysql_num_rows ( $res2 ) != 1)
				$REL_TPL->stderr ( $REL_LANG->_('Denied'), $REL_LANG->_('This user allows only messages from his(her) friends') );
			}

			elseif ($from ["acceptpms"] == "no")
			$REL_TPL->stderr ( $REL_LANG->_('Denied'), $REL_LANG->_('This user does not allow private messages') );
		}

		$pms = $REL_DB->query ( "SELECT SUM(1) FROM messages WHERE (receiver = " . ($receiver ? $receiver : $to) . " AND location=1) OR (sender = " . ($receiver ? $receiver : $to) . " AND saved = 1) GROUP BY messages.id" );
		$pms = mysql_result ( $pms, 0 );
		if ($pms >= $REL_CONFIG ['pm_max'])
		$REL_TPL->stderr ( $REL_LANG->say_by_key('error'), $REL_LANG->_('User mailbox is full. You can not send message.') );

		$REL_DB->query ( "INSERT INTO messages (poster, sender, receiver, added, subject, msg, location, saved) VALUES(" . $CURUSER ["id"] . ", " . $CURUSER ["id"] . ", $to, '" . time () . "', " . sqlesc ( $subject ) . "," . sqlesc ( ($body) ) . ", " . sqlesc ( PM_INBOX ) . ", " . sqlesc ( $save ) . ")" );
		stdmsg ($REL_LANG->_('Successful'), $REL_LANG->_('Private message sent'));
	}
}


elseif ($action == "deletemessage") {
	if (! is_valid_id ( $_GET ["id"] ))
	$REL_TPL->stderr ( $REL_LANG->say_by_key('error'), $REL_LANG->say_by_key('invalid_id') );
	$pm_id = $_GET ['id'];

	// Delete message
	$res = $REL_DB->query ( "SELECT * FROM messages WHERE id=" . sqlesc ( $pm_id ) );
	if (! $res) {
		$REL_TPL->stderr ( $REL_LANG->say_by_key('error'), $REL_LANG->_('Invalid ID') );
	}
	if (mysql_num_rows ( $res ) == 0) {
		$REL_TPL->stderr ( $REL_LANG->say_by_key('error'), $REL_LANG->_('Invalid ID') );
	}
	$message = mysql_fetch_assoc ( $res );
	if ($message ['receiver'] == $CURUSER ['id'] && ! $message ['saved']) {
		$res2 = $REL_DB->query ( "DELETE FROM messages WHERE id=" . sqlesc ( $pm_id ) );
	} elseif ($message ['sender'] == $CURUSER ['id'] && $message ['location'] == PM_DELETED) {
		$res2 = $REL_DB->query ( "DELETE FROM messages WHERE id=" . sqlesc ( $pm_id ) );
	} elseif ($message ['receiver'] == $CURUSER ['id'] && $message ['saved']) {
		$res2 = $REL_DB->query ( "UPDATE messages SET location=0 WHERE id=" . sqlesc ( $pm_id ) );
	} elseif ($message ['sender'] == $CURUSER ['id'] && $message ['location'] != PM_DELETED) {
		$res2 = $REL_DB->query ( "UPDATE messages SET saved=0 WHERE id=" . sqlesc ( $pm_id ) );
	}
	if (! $res2) {
		$REL_TPL->stderr ( $REL_LANG->say_by_key('error'), $REL_LANG->_('Unable to delete message') );
	}
	if (mysql_affected_rows () == 0) {
		$REL_TPL->stderr ( $REL_LANG->say_by_key('error'), $REL_LANG->_('Unable to delete message') );
	} else {
		safe_redirect($REL_SEO->make_link('message','action','viewmailbox','id',$message['location']));
		exit ();
	}
}
//else $REL_TPL->stderr("Access Denied.","Unknown action");
?>