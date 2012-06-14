<?php
/**
 * Invites confirmer
 * @license GNU GPLv3 http://opensource.org/licenses/gpl-3.0.html
 * @package Kinokpk.com releaser
 * @author ZonD80 <admin@kinokpk.com>
 * @copyright (C) 2008-now, ZonD80, Germany, TorrentsBook.com
 * @link http://dev.kinokpk.com
 */

require_once("include/bittorrent.php");
INIT();

loggedinorreturn();

if (!is_valid_id($_GET["id"]))
    $REL_TPL->stderr($REL_LANG->say_by_key('error'), $REL_LANG->say_by_key('invalid_id'));

$id = (int)$_GET["id"];
if (is_array($_POST["conusr"])) {
    $ids = @implode(",", array_map("intval", $_POST["conusr"]));
    $REL_DB->query("UPDATE invites SET confirmed=1 WHERE inviteid IN (" . $ids . ") AND confirmed=0" . (get_privilege('approve_invites', false) ? " AND inviter = $CURUSER[id]" : ""));

    if ($REL_CRON['rating_enabled']) {
        $REL_DB->query("UPDATE users SET ratingsum=ratingsum+{$REL_CRON['rating_perinvite']} WHERE id IN($ids,$id)");
    }

    $ids = explode(',', $ids);
    if ($ids)
        foreach ($ids as $id) {
            $REL_DB->query("INSERT INTO friends (userid,friendid,confirmed) VALUES ({$CURUSER['id']},$id,1)");

            write_sys_msg($id, sprintf($REL_LANG->say_by_key_to($id, 'invite_confirmed'), $REL_CRON['rating_per_invite']), $REL_LANG->say_by_key_to($d, 'invite_confirmed_title'));
        }
}
safe_redirect($REL_SEO->make_link('invite', 'id', $id));

?>