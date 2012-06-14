<?php
/**
 * Release editing form
 * @license GNU GPLv3 http://opensource.org/licenses/gpl-3.0.html
 * @package Kinokpk.com releaser
 * @author ZonD80 <admin@kinokpk.com>
 * @copyright (C) 2008-now, ZonD80, Germany, TorrentsBook.com
 * @link http://dev.kinokpk.com
 */

require_once("include/bittorrent.php");

INIT();


loggedinorreturn();

if (!is_valid_id($_GET['id'])) $REL_TPL->stderr($REL_LANG->say_by_key('error'), $REL_LANG->say_by_key('invalid_id'));

$id = (int)$_GET['id'];
$tree = make_tree();

$res = $REL_DB->query("SELECT id,name,descr,tiger_hash,images,category,size,visible,banned,free,sticky,moderatedby,tags,owner,relgroup,modcomm FROM torrents WHERE id = $id");
$row = mysql_fetch_array($res);
if (!$row)
    $REL_TPL->stderr($REL_LANG->say_by_key('error'), $REL_LANG->say_by_key('invalid_id'));

$res = $REL_DB->query("SELECT tracker FROM trackers WHERE torrent=$id AND tracker<>'localhost'");
while (list($tracker) = mysql_fetch_array($res)) $trackers[] = $tracker;
if ($trackers) $trackers = implode("\n", $trackers);

if ($row['tags']) {
    foreach (explode(',', $row['tags']) as $tag) {
        $tags[]['name'] = $tag;
    }
}
$script = '        <script type="text/javascript">
        $(document).ready(function() {
            $("#tags").tokenInput("tags-generator.php", {
                theme: "facebook",
                hintText: "' . $REL_LANG->_('Begin to type tag') . '",
                noResultsText: "' . $REL_LANG->_('No results, new tag will be created') . '",
                searchingText: "' . $REL_LANG->_('Search') . '",
                preventDuplicates: true, ' . ($tags ? 'prePopulate: ' . json_encode($tags) : '') . '
            });
        });
        </script>';
$REL_TPL->stdhead($REL_LANG->_('Editing release "%s"', makesafe($row["name"])), '', '', '<script src="js/jquery.tokeninput.js"></script><link rel="stylesheet" href="css/token-input-facebook.css" type="text/css" />' . $script);

?>
<script type="text/javascript" language="javascript">

    function openwindow() {
        window.open("<?php print $REL_SEO->make_link('takean', 'id', $id); ?>", "mywindow", "width=250,height=250");
    }

    function checkname() {
        pcre = /(.*?) \([0-9-]+\) \[(.*?)\]/g;
        ERRORTEXT = "<?php print $REL_LANG->_("Release name does not corresponding to rule, please change it and try again:");?>" + "\n\n" + $("#namematch").text();
        if (!pcre.test($("#name").val())) {
            alert(ERRORTEXT);
            $("#name").focus();
            return false;
        }
        else return true;
    }
</script>
<?php

if (($CURUSER["id"] != $row["owner"]) && !get_privilege('edit_releases', false)) {
    $REL_TPL->stdmsg($REL_LANG->say_by_key('error'), $REL_LANG->_('You can not edit this release'));
} else {
    print("<form name=\"edit\" method=post action=\"" . $REL_SEO->make_link('takeedit') . "\" enctype=\"multipart/form-data\" onsubmit=\"return checkname();\">\n");
    print("<input type=\"hidden\" name=\"id\" value=\"$id\">\n");
    if (isset($_GET["returnto"]))
        print("<input type=\"hidden\" name=\"returnto\" value=\"" . htmlspecialchars($_GET["returnto"]) . "\" />\n");
    print("<table border=\"1\" cellspacing=\"0\" cellpadding=\"5\">\n");
    print("<tr><td class=\"colhead\" colspan=\"2\">{$REL_LANG->_('Edit release')}</td></tr>");
    if (get_privilege('edit_releases', false)) tr($REL_LANG->say_by_key('check'), "<input type=\"checkbox\" name=\"approve\" value=\"1\"" . ($row['moderatedby'] ? ' checked' : '') . "> {$REL_LANG->say_by_key('approve')}", 1);
    tr($REL_LANG->say_by_key('torrent_file'), "<input type=file name=tfile size=80><br /><input type=\"checkbox\" name=\"multi\" value=\"1\">&nbsp;{$REL_LANG->say_by_key('multitracker_torrent')}<br /><small>{$REL_LANG->say_by_key('multitracker_torrent_notice')}</small>\n", 1);
    if (get_privilege('is_releaser', false) && $REL_CONFIG['use_dc'])
        tr($REL_LANG->say_by_key('tiger_hash'), "<input type=\"text\" size=\"60\" maxlength=\"38\" name=\"tiger_hash\" value=\"{$row['tiger_hash']}\"><br/>" . $REL_LANG->say_by_key('tiger_hash_notice'), 1);

    if (get_privilege('is_releaser', false))
        tr($REL_LANG->say_by_key('announce_urls'), "<textarea name=\"trackers\" rows=\"6\" cols=\"60\" wrap=\"off\">$trackers</textarea><br/><input type=\"submit\" name=\"add_trackers\" value=\"{$REL_LANG->say_by_key('add_announce_urls')}\"><br/>{$REL_LANG->say_by_key('announce_urls_notice')}", 1);
    tr($REL_LANG->say_by_key('torrent_name') . "<font color=\"red\">*</font>", "<input type=\"text\" name=\"name\" id=\"name\" value=\"" . strip_tags($row["name"]) . "\" size=\"80\" /><div id=\"namematch\">{$REL_LANG->_("Example")}: " . $REL_LANG->say_by_key('taken_from_torrent') . "</div>", 1);

    $row['images'] = explode(',', $row['images']);
    $imagecontent = '';
    // die(var_dump($images));

    for ($i = 0; $i < $REL_CONFIG['max_images']; $i++) {
        $imagecontent .= $REL_LANG->say_by_key('image') . " " . ($i + 1) . " {$REL_LANG->_("File")}: <input type=\"file\" name=\"image$i\" size=\"80\"><br/>{$REL_LANG->_('or')} URL: <input type=\"text\" size=\"63\" name=\"img$i\" value=\"{$row['images'][$i]}\"><hr />";
    }
    tr($REL_LANG->say_by_key('images'), $REL_LANG->say_by_key('max_file_size') . ": 500kb<br />" . $REL_LANG->say_by_key('avialable_formats') . ": .jpg .png .gif<br />$imagecontent", 1);

    print '<tr><td align="left"><b>' . $REL_LANG->say_by_key('description') . '</b></td><td>' . textbbcode('descr', $row['descr'], 1) . '</td></tr>';

    //relgroups
    $rgarrayres = $REL_DB->query("SELECT id,name FROM relgroups ORDER BY added DESC");
    while ($rgarrayrow = mysql_fetch_assoc($rgarrayres)) {
        $rgarray[$rgarrayrow['id']] = $rgarrayrow['name'];
    }

    if ($rgarray) {
        $rgselect = '<select name="relgroup"><option value="0">(' . $REL_LANG->say_by_key('choose') . ')</option>';
        foreach ($rgarray as $rgid => $rgname) $rgselect .= '<option value="' . $rgid . '"' . (($row['relgroup'] == $rgid) ? " selected=\"1\"" : '') . '>' . $rgname . "</option>\n";
        $rgselect .= '</select>';
    }
    if ($rgselect)
        tr($REL_LANG->say_by_key('relgroup'), $rgselect, 1);

    tr($REL_LANG->say_by_key('category'), gen_select_area('type', $tree, $row['category'], true, true), 1);

    tr($REL_LANG->_('Tags'), '<input type="text" name="tags" id="tags" size="100">', 1);

    if (get_privilege('post_releases_to_mainpage', false))
        tr($REL_LANG->_("Viewing"), "<input type=\"checkbox\" name=\"visible\"" . (($row["visible"]) ? " checked=\"checked\"" : "") . " value=\"1\" /> {$REL_LANG->_('Seen on main page')}", 1);
    if (get_privilege('edit_releases', false)) {
        tr($REL_LANG->_("Updated"), "<input type=\"checkbox\" name=\"upd\" value=\"1\" />{$REL_LANG->_('Make first on main page')}", 1);
        tr($REL_LANG->_("Banned"), "<input type=\"checkbox\" name=\"banned\"" . (($row["banned"]) ? " checked=\"checked\"" : "") . " value=\"1\" />", 1);
    }

    if (get_privilege('edit_releases', false)) {
        tr($REL_LANG->_('Golden release'), "<input type=\"checkbox\" name=\"free\"" . (($row["free"]) ? " checked=\"checked\"" : "") . " value=\"1\" /> {$REL_LANG->_('Golden release (rating will not decrease on downloading)')}", 1);
        tr($REL_LANG->_('Important'), "<input type=\"checkbox\" name=\"sticky\"" . (($row["sticky"]) ? " checked=\"checked\"" : "") . " value=\"1\" /> {$REL_LANG->_('Stick this torrent (it will be on the top always)')}", 1);
        tr("{$REL_LANG->_('Moderator comments')}<br /><small>{$REL_LANG->_('No signature required')}</small></td>", "<textarea cols=60 rows=6 name=modcomm" . ">" . htmlspecialchars($row['modcomm']) . "</textarea>\n", 1);

    }
    if ($row['filename'] != 'nofile') $word = ''; else $word = 'checked=\"checked\"';
    $nofsize = $row['size'] / 1024 / 1024;
    tr($REL_LANG->_('Release without torrent'), "<input type=\"checkbox\" name=\"nofile\" " . $word . " value=\"1\">{$REL_LANG->_('This is release without torrent')} ; {$REL_LANG->_('Size')} <input type=\"text\" name=\"nofilesize\" value=\"" . $nofsize . "\" size=\"20\" /> {$REL_LANG->_('Megabytes')}", 1);

    if (get_privilege('edit_releases', false))
        tr($REL_LANG->_('Copyright protection'), "<a href=\"javascript:openwindow()\">{$REL_LANG->_('Anonymize release / Recover release owner')}</a> ({$REL_LANG->_('Popup will open')})", 1, 1);

    print("<tr><td colspan=\"2\" align=\"center\"><input type=\"submit\" value=\"{$REL_LANG->say_by_key('edit')}\" style=\"height: 25px; width: 100px\"> <input type=reset value=\"{$REL_LANG->_('Reset form')}\" style=\"height: 25px; width: 100px\"></td></tr>\n");
    print("</table>\n");
    print("</form>\n");
    if (get_privilege('delete_releases', false)) {
        print("<p>\n");
        print("<form method=\"post\" action=\"" . $REL_SEO->make_link('delete') . "\">\n");
        print("<table border=\"1\" cellspacing=\"0\" cellpadding=\"5\">\n");
        print("<tr><td class=embedded style='background-color: #F5F4EA;padding-bottom: 5px' colspan=\"2\"><b>{$REL_LANG->_('Delete release')}</b> {$REL_LANG->_('Reason')}:</td></tr>");
        print("<td><input name=\"reasontype\" type=\"radio\" value=\"1\">&nbsp;{$REL_LANG->_('Dead')} </td><td> {$REL_LANG->_('0 seeders, 0 leechers = 0 peers')}</td></tr>\n");
        print("<tr><td><input name=\"reasontype\" type=\"radio\" value=\"2\">&nbsp;{$REL_LANG->_('Duplicate')}</td><td><input type=\"text\" size=\"40\" name=\"reason[]\"></td></tr>\n");
        print("<tr><td><input name=\"reasontype\" type=\"radio\" value=\"3\">&nbsp;{$REL_LANG->_('Nuked')}</td><td><input type=\"text\" size=\"40\" name=\"reason[]\"></td></tr>\n");
        print("<tr><td><input name=\"reasontype\" type=\"radio\" value=\"4\">&nbsp;{$REL_LANG->_('Rules violation')}</td><td><input type=\"text\" size=\"40\" name=\"reason[]\">({$REL_LANG->_('Required')})</td></tr>");
        print("<tr><td><input name=\"reasontype\" type=\"radio\" value=\"5\" checked>&nbsp;{$REL_LANG->_('Other')}:</td><td><input type=\"text\" size=\"40\" name=\"reason[]\">({$REL_LANG->_('Required')})</td></tr>\n");
        print("<input type=\"hidden\" name=\"id\" value=\"$id\">\n");
        if (isset($_GET["returnto"]))
            print("<input type=\"hidden\" name=\"returnto\" value=\"" . htmlspecialchars($_GET["returnto"]) . "\" />\n");
        print("<td colspan=\"2\" align=\"center\"><input type=submit value='{$REL_LANG->_('Delete')}' style='height: 25px'></td></tr>\n");
        print("</table>");
        print("</form>\n");
        print("</p>\n");
    }
}
$REL_TPL->stdfoot();

?>
