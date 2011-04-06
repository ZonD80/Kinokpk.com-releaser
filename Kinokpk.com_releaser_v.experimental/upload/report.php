<?php
/**
 * Report form parser
 * @license GNU GPLv3 http://opensource.org/licenses/gpl-3.0.html
 * @package Kinokpk.com releaser
 * @author ZonD80 <admin@kinokpk.com>
 * @copyright (C) 2008-now, ZonD80, Germany, TorrentsBook.com
 * @link http://dev.kinokpk.com
 */
require_once ("include/bittorrent.php");
INIT();
loggedinorreturn ();

$id = ( int ) $_GET ['id'];
if (! is_valid_id ( $id ))
$REL_TPL->stderr ( $REL_LANG->say_by_key("error"), $REL_LANG->say_by_key("invalid_id") );

$type = trim ( ( string ) $_GET ['type'] );

if (!$type) $type='rel';

$allowed_types = array ('messages', 'torrents', 'users', 'relgroups', 'poll', 'news', 'user', 'req', 'rel', 'rg','forum');
$comment_types = array('poll', 'news', 'user', 'req', 'rel', 'rg','forum');
if (! in_array ( $type, $allowed_types ))
$REL_TPL->stderr ( $REL_LANG->say_by_key('error'), $REL_LANG->_('Invalid report type') );

$motive = $_POST ["motive"] ? $_POST ["motive"] : $_POST ["smotive"];

$motive = htmlspecialchars ( trim ((string) $motive ) );

$reportform = '<form action="'.$REL_SEO->make_link('report','id',$id,'type',$type).'" method="post"><input type="submit" name="motive" value="' . $REL_LANG->say_by_key('spam') . '"><input type="submit" name="motive" value="' . $REL_LANG->say_by_key('abuse') . '"><input type="submit" name="motive" value="' . $REL_LANG->say_by_key('badwords') . '"><input type="submit" name="motive" value="' . $REL_LANG->say_by_key('hack') . '">' . $REL_LANG->say_by_key('own_reason') . '<input type="text" size="100" name="smotive"><input type="submit" value="' . $REL_LANG->say_by_key('go') . '"></form>';
//var_dump($_POST);
if (! $motive)
$REL_TPL->stderr ( $REL_LANG->say_by_key('reason'), $reportform, 'success' );

$check = @mysql_result ( sql_query ( "SELECT 1 FROM ".(in_array($type,$comment_types)?"comments WHERE type='$type' AND":"$type WHERE")." id=$id" ), 0 );

if (! $check)
$REL_TPL->stderr ( $REL_LANG->say_by_key('error'), $REL_LANG->say_by_key('invalid_id') );

$reason = sqlesc ( $motive );

sql_query ( "INSERT INTO reports (reportid,userid,type,motive,added) VALUES ($id,{$CURUSER['id']},'$type',$reason," . time () . ")" );
$_SESSION['already_reported'][$type][$id] = $motive;
if (mysql_errno () == 1062)
$REL_TPL->stderr ( $REL_LANG->say_by_key('error'), $REL_LANG->say_by_key('already_report') );

send_notifs('reports');

$REL_TPL->stderr ( $REL_LANG->say_by_key('success'), $REL_LANG->say_by_key('report_ok'), 'success' );
?>