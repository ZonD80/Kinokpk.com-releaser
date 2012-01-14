<?php
/**
 * Upload torrent script
 * @license GNU GPLv3 http://opensource.org/licenses/gpl-3.0.html
 * @package Kinokpk.com releaser
 * @author ZonD80 <admin@kinokpk.com>
 * @copyright (C) 2008-now, ZonD80, Germany, TorrentsBook.com
 * @link http://dev.kinokpk.com
 */


require_once("include/bittorrent.php");


INIT();

loggedinorreturn();
get_privilege('upload_releases');

if (!$_GET['ts']) {
$REL_TPL->stderr($REL_LANG->_('Select what to upload'),"<a href=\"{$REL_SEO->make_link('upload','type','movie','ts','1')}\">Фильм</a>, <a href=\"{$REL_SEO->make_link('upload','ts','1')}\">Без формы</a>",'success');
}

$allowed_types = array('movie');

$type=(string)$_GET['type'];

if ($type&&!in_array($type,$allowed_types)) $REL_TPL->stderr($REL_LANG->_('Error'),$REL_LANG->_('Unknown release type'));

$script = '        <script type="text/javascript">
$(document).ready(function() {
$("#tags").tokenInput("tags-generator.php", {
theme: "facebook",
hintText: "'.$REL_LANG->_('Begin to type tag').'",
noResultsText: "'.$REL_LANG->_('No results, new tag will be created').'",
searchingText: "'.$REL_LANG->_('Search').'",
preventDuplicates: true
});
});
</script>';
$REL_TPL->stdhead($REL_LANG->say_by_key('upload_torrent'),'','','<script src="js/jquery.tokeninput.js"></script><link rel="stylesheet" href="css/token-input-facebook.css" type="text/css" />'.$script);
$tree = make_tree();

if (mb_strlen($CURUSER['passkey']) != 32) {
	$CURUSER['passkey'] = md5($CURUSER['username'].time().$CURUSER['passhash']);
	$REL_DB->query("UPDATE xbt_users SET torrent_pass=".sqlesc($CURUSER[passkey])." WHERE uid=".sqlesc($CURUSER[id]));
}

?>
<script type="text/javascript">
//<!--
function checkname() {
	pcre = /(.*?) \([0-9-]+\) \[(.*?)\]/g;
	ERRORTEXT = "<?php print $REL_LANG->_("Release name does not corresponding to rule, please change it and try again:");?>"+"\n\n"+$("#namematch").text();
	if (!pcre.test($("#name").val())) {
		alert(ERRORTEXT);
		$("#name").focus();
		return false;
	}
	else return true;
}
//-->
</script>
<form name="upload" enctype="multipart/form-data"
	action="<?php print $REL_SEO->make_link('takeupload'); ?>" method="post"
	onsubmit="return checkname();">
<table border="1" cellspacing="0" cellpadding="5">
	<tr>
		<td class="colhead" colspan="2"><?php print $REL_LANG->say_by_key('upload_torrent')?></td>
	</tr>
	<?php	//tr($REL_LANG->say_by_key('announce_url'), $announce_urls[0], 1);
	tr($REL_LANG->say_by_key('torrent_file'), "<input  type=file name=tfile  size=80><br /><input type=\"checkbox\"  name=\"multi\" value=\"1\">&nbsp;{$REL_LANG->say_by_key('multitracker_torrent')}<br /><small>{$REL_LANG->say_by_key('multitracker_torrent_notice')}</small>\n", 1);
	if (get_privilege('edit_releases',false) && $REL_CONFIG['use_dc'])
	tr($REL_LANG->say_by_key('tiger_hash'),"<input type=\"text\" size=\"60\" maxlength=\"38\" name=\"tiger_hash\" value=\"{$row['tiger_hash']}\"><br/>".$REL_LANG->say_by_key('tiger_hash_notice'),1);

	tr($REL_LANG->say_by_key('torrent_name')."<font color=\"red\">*</font>", "<input type=\"text\" id=\"name\" name=\"name\" size=\"80\" value=\"Русский / Original (год или диапазон годов) [качество, примечание]\"/><br /><div id=\"namematch\">{$REL_LANG->_("Example")}: ".$REL_LANG->say_by_key('taken_from_torrent')."</div>\n", 1);

	$imagecontent = '<br />';

	for ($i = 0; $i < $REL_CONFIG['max_images']; $i++) {
		$imagecontent.=$REL_LANG->say_by_key('image')." ".($i+1)." {$REL_LANG->_("File")}: <input type=\"file\" name=\"image$i\" size=\"80\"><br/>{$REL_LANG->_('or')} URL: <input type=\"text\" size=\"63\" name=\"img$i\"><hr />";
	}

	tr($REL_LANG->say_by_key('images'), $REL_LANG->say_by_key('max_file_size').": 500kb<br />".$REL_LANG->say_by_key('avialable_formats').": .jpg .png .gif$imagecontent\n", 1);
	
	if (!$type)
	tr($REL_LANG->say_by_key('description').'<br/>'.$REL_LANG->say_by_key('description_notice'),textbbcode("descr"),1);
	else {
		$REL_TPL->output($type);
	}
	/// RELEASE group
	$rgarrayres = $REL_DB->query("SELECT id,name FROM relgroups ORDER BY added DESC");
	while($rgarrayrow = mysql_fetch_assoc($rgarrayres)) {
		$rgarray[$rgarrayrow['id']] = $rgarrayrow['name'];
	}

	if ($rgarray) {
		$rgselect = '<select name="relgroup" style="width: 150px;"><option value="0">('.$REL_LANG->say_by_key('choose').')</option>';
		foreach ($rgarray as $rgid=>$rgname) $rgselect.='<option value="'.$rgid.'">'.$rgname."</option>\n";
		$rgselect.='</select>';
	}
	if ($rgselect)
	tr($REL_LANG->say_by_key('relgroup'),$rgselect,1);

	tr ($REL_LANG->say_by_key('category'),gen_select_area('type',$tree,NULL,true,true),1);
	
	tr ($REL_LANG->_('Tags'),'<input type="text" name="tags" id="tags" size="100">',1);


	if(get_privilege('post_releases_to_mainpage',false))
		tr($REL_LANG->_("Viewing"), "<input type=\"checkbox\" name=\"visible\" value=\"1\" /> Видимый на главной", 1);
	if (get_privilege('edit_releases',false)) {
		tr($REL_LANG->say_by_key('golden'), "<input type=checkbox name=free value=\"1\"> ".$REL_LANG->say_by_key('golden_descr'), 1);
		tr("Важный", "<input type=\"checkbox\" name=\"sticky\" value=\"1\">Прикрепить этот торрент (всегда наверху)", 1);
	}
	tr("Релиз без торрента", "<input type=\"checkbox\" name=\"nofile\" value=\"1\">Этот релиз без торрента ; Размер: <input type=\"text\" name=\"nofilesize\" size=\"20\" /> МБ", 1);
	tr("<font color=\"red\">АНОНС</font>", "<input type=\"checkbox\" name=\"annonce\" value=\"1\">Это всего-лишь анонс релиза. Ссылки указывать не обязательно,<br />&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;но обязательно выложить релиз после анонсирования!", 1);
	?>
	<tr>
		<td align="center" colspan="2"><input type="submit" class="btn button"
			value="<?php print $REL_LANG->say_by_key('upload'); ?>" /></td>
	</tr>
</table>
</form>
	<?php
	$REL_TPL->stdfoot();

	?>