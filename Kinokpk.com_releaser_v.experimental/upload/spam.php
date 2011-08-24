<?php
/**
 * Private messages admin viewer
 * @license GNU GPLv3 http://opensource.org/licenses/gpl-3.0.html
 * @package Kinokpk.com releaser
 * @author ZonD80 <admin@kinokpk.com>
 * @copyright (C) 2008-now, ZonD80, Germany, TorrentsBook.com
 * @link http://dev.kinokpk.com
 */


require "include/bittorrent.php";
INIT();
loggedinorreturn();
get_privilege('spamadmin');
httpauth();


$count = get_row_count("messages");

if (!$count){
	$REL_TPL->stderr($REL_LANG->_('Error'),$REL_LANG->_('Nothing was found'));
}

$limit = ajaxpager(50, $count, array('spam'), "messages > tbody:last");
if (!pagercheck()) {
$REL_TPL->stdhead($REL_LANG->_('Private messages viewer'));

?>

<form method="post" action="<?php print $REL_SEO->make_link('take-delmp');?>"
	name="form1" id="message">

<div id="pager_scrollbox"><table id="messages" border="1" cellspacing="1" cellpadding="1" width="100%">
	<tr>
		<td colspan="5" class=colhead align=center><?php print $REL_LANG->_('Private messages (%s total)',$count);?></td>
	</tr>
	<tr>
		<td colspan="5">
		<div style="float: right;"><input type="submit"
			value="<?php print $REL_LANG->_('Delete selected');?>!" onClick="return confirm('<?php print $REL_LANG->_('Are you sure?');?>')"></div>
		</td>
	</tr>
	<tr>
		<td class=colhead align=center><?php print $REL_LANG->_('Sender/Receiver');?></td>
		<td class=colhead align=center>ID</td>
		<td class=colhead align=center><?php print $REL_LANG->_('Text');?></td>
		<td class=colhead align=center><?php print $REL_LANG->_('Date');?></td>
		<td class=colhead>
		<center><INPUT type="checkbox" title="<?php print $REL_LANG->_('Select All');?>" value="<?php print $REL_LANG->_('Select All');?>"
			id="toggle-all"></center>
		</td>
	</tr>
	<?php
}

	$res = sql_query("SELECT * FROM messages $where ORDER BY id DESC $limit") or sqlerr(__FILE__, __LINE__);
	while ($arr = mysql_fetch_assoc($res))
	{
		$res2 = sql_query("SELECT username, class, id, warned, donor, enabled FROM users WHERE id=".$arr["receiver"]) or sqlerr(__FILE__, __LINE__);
		$arr2 = mysql_fetch_assoc($res2);

		if($arr["receiver"] == 0 or !$arr["receiver"]){
			$receiver = "<strike><b>{$REL_LANG->_('Unknown')}</b></strike>";
		} else {
			$receiver = make_user_link($arr2);
		}

		$res3 = sql_query("SELECT username, class, id, warned, donor, enabled FROM users WHERE id=".$arr["sender"]) or sqlerr(__FILE__, __LINE__);
		$arr3 = mysql_fetch_assoc($res3);

		if($arr["sender"] == 0){
			$sender = "<font color=red><b>{$REL_LANG->_('System')}</b></font>";
		} else {
			$sender = make_user_link($arr2);
		}
		$msg = format_comment($arr['msg']);
		$added = mkprettytime($arr['added']);

		print("<tr><td align='left'>
        <div style='padding-top:5px; padding-bottom:10px;'>{$REL_LANG->_('Sender')}:&nbsp;".$sender."</div>
        <div style='padding-top:10px; padding-bottom:5px;'>{$REL_LANG->_('Receiver')}:&nbsp;".$receiver."</div>
        </td><td align=center><a href=\"".$REL_SEO->make_link('message','action','viewmessage','id',$arr["id"])."\">".$arr["id"]."</a></td>
        <td>$msg</td>
        <td align=center>$added</td>");
		print("<TD align=center><INPUT type=\"checkbox\" name=\"delmp[]\" value=\"".$arr['id']."\" id=\"checkbox_tbl_".$arr['id']."\">
          </TD></tr>");
	}
	
	?>
		<tr>
			<td class=colhead colspan="4"></td>
			<td class=colhead>
			<center><INPUT type="checkbox" title="<?php print $REL_LANG->_('Select All');?>"
				value="<?php print $REL_LANG->_('Select All');?>" id="toggle-all"></center>
			</td>
		</tr>

		<?php
		
			
		if ($where && $count){
			?>
		<tr>
			<td colspan="5"><a href="<?php print$REL_SEO->make_link('spam');?>"><?php print $REL_LANG->_('Back')?></a></td>
		</tr>
		<?php }?>

		<tr>
			<td colspan="5">
			<div style="float: right;"><input type="submit"
				value="<?php print $REL_LANG->_('Delete selected');?>!" onClick="return confirm('<?php print $REL_LANG->_('Are you sure?');?>')">
			</div>
			</td>
		</tr>
<?php 			if (pagercheck()) die();?>
</table></div>
</form>
<br />
<?php
		$REL_TPL->stdfoot();
		?>