<?php
/**
 * IPB integration stuff
 * @license GNU GPLv3 http://opensource.org/licenses/gpl-3.0.html
 * @package Kinokpk.com releaser
 * @author ZonD80 <admin@kinokpk.com>
 * @copyright (C) 2008-now, ZonD80, Germany, TorrentsBook.com
 * @link http://dev.kinokpk.com
 */

function ipb_login($CURUSER, $password)
{
    global $REL_CONFIG, $CURUSER, $REL_DB;
    if ($REL_CONFIG['forum_enabled']) {
        require_once(ROOT_PATH . 'classes/ipbwi/ipbwi.inc.php');
        if (!$CURUSER['forum_id']) {

            $created = $ipbwi->member->create("{$CURUSER['id']}-releaser", $password, $CURUSER['email'], array('avatar_new' => $CURUSER['avatar']), false);
            if ($created) {
                $created = $ipbwi->member->name2id("{$CURUSER['id']}-releaser");
                $ipbwi->group->change($REL_CONFIG['forum_signup_class'], $created);
                $ipbwi->member->login($ipbwi->member->id2name($created), $password);
                $ipbwi->member->updateMember(array('members_display_name' => $CURUSER['username']), $created, true);
                $REL_DB->query("UPDATE users SET forum_id=$created WHERE id={$CURUSER['id']}");
            }
        } else {
            $ipbwi->member->updatePassword($password, $CURUSER['forum_id']);

            $ipbwi->member->login($ipbwi->member->id2name($CURUSER['forum_id']), $password);
        }
        //if ($_COOKIE['test']) print $ipbwi->printSystemMessages();

    }
}

?>
