<?php
/**
 * Language administration tools
 * @license GNU GPLv3 http://opensource.org/licenses/gpl-3.0.html
 * @package Kinokpk.com releaser
 * @author ZonD80 <admin@kinokpk.com>
 * @copyright (C) 2008-now, ZonD80, Germany, TorrentsBook.com
 * @link http://dev.kinokpk.com
 */

require_once('include/bittorrent.php');
INIT();
loggedinorreturn();
get_privilege('langadmin');

httpauth();

$lang_export = substr(trim((string)$_GET['lang_export']),0,2);

if (isset($_GET['del'])) {
	$lang = sqlesc(substr(trim((string)$_GET['language']),0,2));
	$key = sqlesc((trim((string)$_GET['key'])));
	sql_query("DELETE FROM languages WHERE lkey=$key AND ltranslate=$lang");
	$REL_CACHE->clearCache('languages',$lang);
	$REL_TPL->stderr($REL_LANG->_("Successful"),$REL_LANG->_("Deleted"));
	//if (!REL_AJAX) safe_redirect(($_SERVER['HTTP_REFERER']),1);
}
elseif ($lang_export) {

	$res = $REL_DB->query('SELECT ltranslate FROM languages GROUP BY ltranslate');
	while ($row = mysql_fetch_assoc($res)) {
		$langs[] = $row['ltranslate'];
	}
	if (!in_array($lang_export, $langs)) $REL_TPL->stderr($REL_LANG->_('Error'),$REL_LANG->_('No language found for %s',$lang_export));
	$REL_LANG->export_langfile($lang_export);
}

if (!pagercheck()) {

	$REL_TPL->stdhead($REL_LANG->_("Language administration tools"));
	?>
<table width="100%">
	<tr>
		<td><?php print "<a href=\"{$REL_SEO->make_link('langadmin')}\">{$REL_LANG->_("To panel index")}</a>";?></td>
		<td><?php print "<a href=\"{$REL_SEO->make_link('langadmin','import',1)}\">{$REL_LANG->_("Import a langfile")}</a>";?></td>
		<td><?php print "<a href=\"{$REL_SEO->make_link('langadmin','export',1)}\">{$REL_LANG->_("Export language to file")}</a>";?></td>
		<td><?php print "<a href=\"{$REL_SEO->make_link('langadmin','editor',1)}\">{$REL_LANG->_("Language editor")}</a>";?></td>
		<td><?php print "<a href=\"{$REL_SEO->make_link('langadmin','clearcache',1)}\">{$REL_LANG->_("Clear language cache")}</a>";?></td>
	</tr>
</table>
	<?php

}

if (isset($_GET['clearcache'])) {
	$REL_CACHE->clearGroupCache('languages');
	stdmsg($REL_LANG->_("Successful"),$REL_LANG->_("Language cache cleared"));
	$REL_TPL->stdfoot();
	die();
}
elseif (isset($_GET['export'])) {
	$res = $REL_DB->query('SELECT ltranslate FROM languages GROUP BY ltranslate');
	if (!$lang_export) {
		?>
<form action="<?php print $REL_SEO->make_link('langadmin');?>"
	method="get"><input type="hidden" name="export" value="1" />
<table>
	<tr>
	<?php print $REL_LANG->_('Select language for export')?>
		<td></td>
	</tr>
	<tr>
		<td><select name="lang_export">
		<?php
		while ($row = mysql_fetch_assoc($res)) {
			print '<option value="'.$row['ltranslate'].'">'.$row['ltranslate'].'</option>';
		}
		?>
		</select></td>
	</tr>
	<tr>
		<td><input type="submit"
			value="<?php print $REL_LANG->_('Export and download')?>" /></td>
	</tr>
</table>
</form>
		<?php
	}
}
elseif (isset($_GET['import'])) {
	?>
<form action="<?php print $REL_SEO->make_link('langadmin','import');?>"
	method="post" enctype="multipart/form-data">
<table>
	<tr>
		<td><input type="text" name="language" maxlength="2" size="2" /> <?php print $REL_LANG->_("Language to be imported, e.g. 'ru,en,ua'");?></td>
		<td><input type="file" name="langfile" /> <?php print $REL_LANG->_("Language file as it is");?></td>
		<td><input type="checkbox" name="override" value="1" /> <?php print $REL_LANG->_("Override current data");?></td>
		<td><input type="submit"
			value="<?php print $REL_LANG->_("Continue");?>" /></td>
	</tr>
</table>
</form>
	<?php
	if ($_SERVER['REQUEST_METHOD']=='POST') {
		$lang = substr(trim((string)$_POST['language']),0,2);
		$f = $_FILES["langfile"];
		if (!is_uploaded_file($f["tmp_name"])||!filesize($f["tmp_name"])) {
			stdmsg($REL_LANG->_("Error"),$REL_LANG->_("File upload error or file size 0 bytes"));
			$REL_TPL->stdfoot();
			die();
		}
		$result = $REL_LANG->import_langfile($f["tmp_name"],$lang,$_POST['override']);
		print ("<h1>{$REL_LANG->_("Importing results")}</h1>");
		if ($result['errors']) {
			stdmsg($REL_LANG->_("Error"),implode('<br/>',$result['errors']),'error');
		}
		if ($result['words']) {
			stdmsg($REL_LANG->_("Successfully imported"),implode('<br/>',$result['words']));
		}
		$REL_CACHE->clearCache('languages',$lang);
	}
}
elseif (isset($_GET['editor'])) {
	$search = htmlspecialchars(trim((string)$_GET['search']));
	if ($_SERVER['REQUEST_METHOD']=='POST') {
		if ($_GET['a']=='saveadd') {
			$lang = substr(trim((string)$_POST['language']),0,2);
			$word = (trim((string)$_POST['word']));
			$key = (trim((string)$_POST['key']));
			if (!$key) $key=md5($word);
			sql_query("INSERT INTO languages (lkey,ltranslate,lvalue) VALUES (".sqlesc($key).",".sqlesc($lang).",".sqlesc($word).")");
			if (mysql_errno()==1062) { stdmsg($REL_LANG->_("Error"),'REDECLARATED KEY:"'.$key.'"','error'); $REL_TPL->stdfoot(); die(); }
			stdmsg($REL_LANG->_("Successful"),"$key : $word");
			$REL_CACHE->clearCache('languages',$lang);
			$REL_TPL->stdfoot();
			die();
		}
		elseif ($_GET['a']=='gensave') {
			if (is_array($_POST['key'])&&is_array($_POST['val'])) {

				foreach ($_POST['key'] as $key=>$chkey) {
					if (is_array($chkey)) {
						foreach ($chkey as $lang=>$keyvalue) {
							$lang = substr(trim($lang),0,2);
							sql_query("UPDATE languages SET lkey=".sqlesc($keyvalue).", lvalue=".sqlesc($_POST['val'][$key][$lang])." WHERE lkey=".sqlesc($key)." AND ltranslate = ".sqlesc($lang));
							if (mysql_errno()==1062) $fail[] = ($key<>$keyvalue?"{$key}-&gt;{$keyvalue}":$key)." : {$_POST['val'][$key][$lang]}";
							else
							$success[] = ($key<>$keyvalue?"{$key}-&gt;{$keyvalue}":$key)." : {$_POST['val'][$key][$lang]}";
						}
					}
				}
				if ($fail) stdmsg($REL_LANG->_("Error"),implode("<br/>",$fail),'error');
				if ($success) stdmsg($REL_LANG->_("Successful"),implode("<br/>",$success));
				$REL_CACHE->clearGroupCache('languages');
				$REL_TPL->stdfoot();
				die();
			}
		}
	}

	if (!pagercheck()) {
		?>
<script type="text/javascript">
function ajaxdel(key,lang) {

	conf = confirm('<?php print $REL_LANG->_("Are you sure?");?>');
	if (!conf) return false;
	field = "#"+key+"-"+lang;

    $.get("langadmin.php", { del: 1, key:key, language:lang}, function(data){
    	   $(field).html('<td cospan="4"><h1>'+data+'</h1></td>');
    	   $(field).fadeOut(1000);
    	}
    	);
	return false;
}
	
</script>
<form action=<?php print $REL_SEO->make_link('langadmin');?>
	method="get">
<table>
	<tr>
		<td><input type="hidden" name="editor" value="1" /> <?php print $REL_LANG->_("Search by key or value");?>
		<input type="text" name="search" value="<?php print $search;?>" /></td>
		<td><input type="submit"
			value="<?php print $REL_LANG->_("Continue");?>" /></td>
	</tr>
</table>
</form>
<p><?php print $REL_LANG->_("Add a new word. <b>Remember, that you must FIRST add ENGLISH translation, than another one!</b>");?></p>
<form
	action=<?php print $REL_SEO->make_link('langadmin','editor',1,'a','saveadd');?>
	method="post">
<table>
	<tr>
		<td><?php print $REL_LANG->_("Language to be imported, e.g. 'ru,en,ua'");?><br/><input type="text" name="language" maxlength="2" size="2" /></td>
		<td><?php print $REL_LANG->_("Key (optional, else MD5 of word)");?><br/><input type="text" name="key"></td>
		<td><?php print $REL_LANG->_("Word");?><br/><textarea name="word" rows="10" cols="40"></textarea></td>
		<td><input type="submit"
			value="<?php print $REL_LANG->_("Continue");?>" /></td>
	</tr>
</table>
</form>

<form
	action=<?php print $REL_SEO->make_link('langadmin','editor','1','a','gensave');
	?>
	method="POST">
<div id="pager_scrollbox"><table id="wordstable" width="100%">
	<tr>
		<td class="colhead"><?php print $REL_LANG->_("Key");?></td>
		<td class="colhead"><?php print $REL_LANG->_("Language");?></td>
		<td class="colhead"><?php print $REL_LANG->_("Value");?></td>
		<td class="colhead"><?php print $REL_LANG->_("Delete");?></td>
	</tr>
	<?php
	}
	$count = get_row_count('languages',($search?" WHERE lkey LIKE '%" . sqlwildcardesc($search) . "%' OR lvalue LIKE '%" . sqlwildcardesc($search) . "%'":''));

	$limit = ajaxpager(25, $count, array('langadmin','editor','1','search',$search), 'wordstable > tbody:last');

	$res = sql_query("SELECT * FROM languages".($search?" WHERE lkey LIKE '%" . sqlwildcardesc($search) . "%' OR lvalue LIKE '%" . sqlwildcardesc($search) . "%'":'')." ORDER BY lkey DESC $limit");

	while ($row = mysql_fetch_assoc($res)) {
		print "<tr id=\"{$row['lkey']}-{$row['ltranslate']}\"><td><input type=\"text\" name=\"key[{$row['lkey']}][{$row['ltranslate']}]\" value=\"{$row['lkey']}\" maxlength=\"255\"/></td>".
	"<td>{$row['ltranslate']}</td>".
	"<td><textarea name=\"val[".addslashes($row['lkey'])."][{$row['ltranslate']}]\">{$row['lvalue']}</textarea></td><td><a onclick=\"return ajaxdel('".addslashes($row['lkey'])."','{$row['ltranslate']}');\" href=\"{$REL_SEO->make_link("langadmin",'del','','key',$row['lkey'],'language',$row['ltranslate'])}\">{$REL_LANG->_("Delete")}</a></td></tr>";
	}
	if (!pagercheck()) {
		print '<tr><td colspan="4" align="right"><input type="submit" value="'.$REL_LANG->_("Save changes").'"/></td></tr>';
		?>
</table></div>
</form>
		<?php
	}
}
if (!pagercheck()) {
	$REL_TPL->stdfoot();
}
?>
