<?php
/**
 * Admin control panel frontend
 * @license GNU GPLv3 http://opensource.org/licenses/gpl-3.0.html
 * @package Kinokpk.com releaser
 * @author ZonD80 <admin@kinokpk.com>
 * @copyright (C) 2008-now, ZonD80, Germany, TorrentsBook.com
 * @link http://dev.kinokpk.com
 */

require "include/bittorrent.php";
INIT();
loggedinorreturn();


get_privilege('access_to_admincp');

httpauth();
$REL_TPL->stdhead($REL_LANG->_('Administrator control panel'));
$REL_TPL->begin_main_frame();


if (get_privilege('is_owner',false)) {
	$REL_TPL->begin_frame($REL_LANG->_("Staff functions").' - '.$REL_LANG->_("For owners")); ?>
<table width=100% cellspacing=10 align=center>
	<tr>
		<td><a href="<?=$REL_SEO->make_link('configadmin');?>"><?=$REL_LANG->_("Global settings");?></a></td>
		<td><a href="<?=$REL_SEO->make_link('privadmin');?>"><?=$REL_LANG->_("Privileges configuration");?></a></td>
		<td><a href="<?=$REL_SEO->make_link('classadmin');?>"><?=$REL_LANG->_("Classes configuration");?></a></td>
		<td><a href="<?=$REL_SEO->make_link('seoadmin');?>"><?=$REL_LANG->_("Human Readable URLs configuration (SEO)");?></a></td>
	</tr>
	<tr>
		<td><a href="<?=$REL_SEO->make_link('blocksadmin');?>"><?=$REL_LANG->_("Blocks administration");?></a></td>
		<td><a href="<?=$REL_SEO->make_link('templatesadmin');?>"><?=$REL_LANG->_("Skins administration");?></a></td>
		<td colspan="2"><a href="<?=$REL_SEO->make_link('forumadmin');?>"><?=$REL_LANG->_("Forum");?></a></td>
	</tr>
	<tr>
		<td><a href="<?=$REL_SEO->make_link('spam');?>"><?=$REL_LANG->_("View private messages");?></a></td>
		<td><a href="<?=$REL_SEO->make_link('category');?>"><?=$REL_LANG->_("Categories");?></a></td>
		<td><a href="<?=$REL_SEO->make_link('stampadmin');?>"><?=$REL_LANG->_("Stamps");?></a></td>
		<td><a href="<?=$REL_SEO->make_link('countryadmin');?>"><?=$REL_LANG->_("Countries and flags");?></a></td>
	</tr>
	<tr>
		<td><a href="<?=$REL_SEO->make_link('dchubsadmin');?>"><?=$REL_LANG->_("DC Hubs administration");?></a></td>
		<td><a href="<?=$REL_SEO->make_link('retrackeradmin');?>"><?=$REL_LANG->_("Retracker administration");?></a></td>
		<td><a href="<?=$REL_SEO->make_link('cronadmin');?>"><?=$REL_LANG->_("Sheduled jobs administration");?></a></td>
		<td><a href="<?=$REL_SEO->make_link('langadmin');?>"><?=$REL_LANG->_("Language tools");?></a></td>
	</tr>
</table>
	<? $REL_TPL->end_frame();
}

if (get_privilege('is_administrator',false)) { ?>
<? $REL_TPL->begin_frame($REL_LANG->_("Staff functions").' - '.$REL_LANG->_("For administrators")); ?>
<table width=100% cellspacing=10 align=center>
	<tr>
		<td><a href="<?=$REL_SEO->make_link('unco');?>"><?=$REL_LANG->_("Unconfirmed users");?></a></td>
		<td><a href="<?=$REL_SEO->make_link('delacctadmin');?>"><?=$REL_LANG->_("Delete user account");?></a></td>
		<td><a href="<?=$REL_SEO->make_link('rgadmin');?>"><?=$REL_LANG->_("Release groups");?></a></td>
		<td><a href="<?=$REL_SEO->make_link('bans');?>"><?=$REL_LANG->_("IP/subnet bans");?></a></td>
	</tr>
	<tr>
		<td><a href="<?=$REL_SEO->make_link('banemailadmin');?>"><?=$REL_LANG->_("E-mail bans");?></a></td>
		<td><a href="<?=$REL_SEO->make_link('email');?>"><?=$REL_LANG->_("Mass e-mail");?></a></td>
		<td><a href="<?=$REL_SEO->make_link('staffmess');?>"><?=$REL_LANG->_("Mass private message");?></a></td>
		<td><a href="<?=$REL_SEO->make_link('pollsadmin');?>"><?=$REL_LANG->_("Polls administration");?></a></td>
	</tr>
	<tr>
		<td><a href="<?=$REL_SEO->make_link('mysqlstats');?>"><?=$REL_LANG->_("MySQL status");?></a></td>
		<td><a href="<?=$REL_SEO->make_link('passwordadmin');?>"><?=$REL_LANG->_("Change user password");?></a></td>
		<td><a href="<?=$REL_SEO->make_link('clearcache');?>"><?=$REL_LANG->_("Clear caches");?></a></td>
		<td><a href="<?=$REL_SEO->make_link('reltemplatesadmin');?>"><?=$REL_LANG->_("Release's templates adminsitration");?></a></td>
	</tr>
	<tr>
		<td><a href="<?=$REL_SEO->make_link('news');?>"><?=$REL_LANG->_("Add a news");?></a>
		| <a href="<?=$REL_SEO->make_link('newsarchive');?>"><?=$REL_LANG->_("View all news");?></a></td>
		<td colspan="3"><a href="<?=$REL_SEO->make_link('recountadmin');?>"><?=$REL_LANG->_("Recount/sync database values");?></a></td>


	</tr>
</table>
<? $REL_TPL->end_frame();
}

if (get_privilege('is_moderator',false)) { ?>
<? $REL_TPL->begin_frame($REL_LANG->_("Staff functions").' - '.$REL_LANG->_("For moderators")); ?>


<table width=100% cellspacing=3>
	<tr>
	</tr>
	<tr>
		<td><a href="<?=$REL_SEO->make_link('users','act','users');?>"><?=$REL_LANG->_("View users with rating below 0");?></a></td>
		<td><a href="<?=$REL_SEO->make_link('users','act','banned');?>"><?=$REL_LANG->_("View disabled users");?></a></td>
		<td><a href="<?=$REL_SEO->make_link('users','act','last');?>"><?=$REL_LANG->_("View new users");?></a></td>
		<td><a href="<?=$REL_SEO->make_link('log');?>"><?=$REL_LANG->_("View site log");?></a></td>
	</tr>
	<tr>
		<td><a href="<?=$REL_SEO->make_link('warned');?>"><?=$REL_LANG->_("View warned users");?></a></td>
		<td><a href="<?=$REL_SEO->make_link('adduser');?>"><?=$REL_LANG->_("Add a new user");?></a></td>
		<td><a href="<?=$REL_SEO->make_link('recover');?>"><?=$REL_LANG->_("Restore user access");?></a></td>
		<td><a href="<?=$REL_SEO->make_link('uploaders');?>"><?=$REL_LANG->_("View uploaders & stats");?></a></td>
	</tr>
	<tr>
		<td colspan="4"><a href="<?=$REL_SEO->make_link('users');?>"><?=$REL_LANG->_("View list of users");?></a></td>
	</tr>
	<tr>
		<td><a href="<?=$REL_SEO->make_link('stats');?>"><?=$REL_LANG->_("View statistics");?></a></td>
		<td><a href="<?=$REL_SEO->make_link('testip');?>"><?=$REL_LANG->_("Test that IP was banned");?></a></td>
		<td><a href="<?=$REL_SEO->make_link('reports');?>"><?=$REL_LANG->_("View reports");?></a></td>
		<td><a href="<?=$REL_SEO->make_link('ipcheck');?>"><?=$REL_LANG->_("Search for double IP");?></a></td>
	</tr>
	<tr>
		<td colspan="4" class=embedded>
		<form method=get action="<?=$REL_SEO->make_link('users')?>"><?=$REL_LANG->_("Search");?>:
		<input type=text size=30 name=search> <select name=class>
			<option value='-'><?=$REL_LANG->_("Select");?></option>
			<?php
			$classes = init_class_array();
			foreach ($classes AS $cid=>$cl)
			print("<option value=\"$cid\">{$REL_LANG->_($cl['name'])}</option>");
			?>
		</select> <input type=submit value='<?=$REL_LANG->_("Search");?>'></form>
		</td>
	</tr>
	<tr>
		<td class=embedded><a href="<?=$REL_SEO->make_link('usersearch');?>"><?=$REL_LANG->_("Administrative search");?></a></td>
	</tr>
</table>

			<?php
			$REL_TPL->end_frame();
}
$REL_TPL->end_main_frame();
$REL_TPL->stdfoot();
?>
