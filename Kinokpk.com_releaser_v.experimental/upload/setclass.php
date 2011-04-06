<?php
/**
 * Class changer for admin testing purposes
 * @license GNU GPLv3 http://opensource.org/licenses/gpl-3.0.html
 * @package Kinokpk.com releaser
 * @author ZonD80 <admin@kinokpk.com>
 * @copyright (C) 2008-now, ZonD80, Germany, TorrentsBook.com
 * @link http://dev.kinokpk.com
 */

require_once "include/bittorrent.php";

INIT();

loggedinorreturn();
httpauth();

if (isset($_COOKIE['override_class']) || !get_privilege('is_moderator',false))
stderr($REL_LANG->say_by_key('error'),$REL_LANG->say_by_key('access_denied'));

if ($_GET['action'] == 'editclass') //Process the querystring - No security checks are done as a temporary class higher
{                                   //than the actual class mean absoluetly nothing.
$newclass = (int)$_GET['class'];
if (get_class_priority($CURUSER['class']) < get_class_priority($newclass)) stderr($REL_LANG->say_by_key('error'),$REL_LANG->say_by_key('class_override_denied'));

$returnto = makesafe($_GET['returnto']);

setcookie('override_class', $newclass, 0x7fffffff, "/");

safe_redirect($returnto);
die();
}

// HTML Code to allow changes to current class
$REL_TPL->stdhead($REL_LANG->say_by_key('change_class'));
?>

<form method=get
	action='<?=$REL_SEO->make_link('setclass');?>'><input type=hidden
	name='action' value='editclass'> <input type=hidden name='returnto'
	value='<?=$REL_SEO->make_link('userdetails','id',$CURUSER['id'],'username',translit($CURUSER['username']));?>'>
<!-- Change to any page you want -->
<table width=150 border=2 cellspacing=5 cellpadding=5>
	<tr>
		<td><?=$REL_LANG->say_by_key('class')?></td>
		<td align=left><?php print make_classes_select();?></td>
	</tr>
	</td>
	</tr>
	<tr>
		<td colspan=3 align=center><input type=submit class=btn
			value='<?=$REL_LANG->say_by_key('change_class')?>'></td>
	</tr>
	</form>
	</form>
</table>
<br />
<?php
$REL_TPL->stdfoot();
?>