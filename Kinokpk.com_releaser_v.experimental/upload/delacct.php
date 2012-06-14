<?php
/**
 * Delete accout by user
 * @license GNU GPLv3 http://opensource.org/licenses/gpl-3.0.html
 * @package Kinokpk.com releaser
 * @author ZonD80 <admin@kinokpk.com>
 * @copyright (C) 2008-now, ZonD80, Germany, TorrentsBook.com
 * @link http://dev.kinokpk.com
 */

require "include/bittorrent.php";
INIT();


if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = trim((string)$_POST["email"]);
    $password = trim((string)$_POST["password"]);
    if (!$email || !$password)
        $REL_TPL->stderr($REL_LANG->say_by_key('error'), $REL_LANG->say_by_key('fill_form'));
    $arr = $REL_DB->query_row("SELECT id,avatar,passhash,secret FROM users WHERE email=" . $REL_DB->sqlesc($email));
    if (!$arr)
        $REL_TPL->stderr($REL_LANG->say_by_key('error'), $REL_LANG->say_by_key('invalid_username'));
    //get_privilege('delete_site_users');
    if ($arr["passhash"] != md5($arr["secret"] . $password . $arr["secret"]))
        $REL_TPL->stderr($REL_LANG->_('Error'), $REL_LANG->_('Invalid password'));
    $id = $arr['id'];
    $avatar = $arr['avatar'];
    delete_user($id);
    @unlink(ROOT_PATH . $avatar);
    // if (mysql_affected_rows() != 1)     $REL_TPL->stderr($REL_LANG->say_by_key('error'), $REL_LANG->say_by_key('cant_del_acc'));
    $REL_TPL->stderr($REL_LANG->say_by_key('success'), $REL_LANG->say_by_key('account_deleted'));
}
$REL_TPL->stdhead($REL_LANG->say_by_key('delete_account'));
?>
<h1></h1>
<table border="1" cellspacing="0" cellpadding="5">
    <form method="post"
          action="<?php print $REL_SEO->make_link('delacct'); ?>">
        <tr>
            <td class="colhead" colspan="2">
                <center>
                    <?php print $REL_LANG->say_by_key('delete_account'); ?>
                </center>
            </td>
        </tr>
        <tr>
            <td class="rowhead"><?php print $REL_LANG->_('Your Email:'); ?></td>
            <td><input size="40" name="email"></td>
        </tr>
        <tr>
            <td class="rowhead"><?php print $REL_LANG->say_by_key('password'); ?>
            </td>
            <td><input type="password" size="40" name="password"></td>
        </tr>
        <tr>
            <td colspan="2" align="center"><input type="submit" class="btn"
                                                  value="<?php print $REL_LANG->say_by_key('remove'); ?>"></td>
        </tr>
    </form>
</table>
<?php
$REL_TPL->stdfoot();
?>