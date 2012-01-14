<?php
/**
 * Release details
* @license GNU GPLv3 http://opensource.org/licenses/gpl-3.0.html
* @package Kinokpk.com releaser
* @author ZonD80 <admin@kinokpk.com>
* @copyright (C) 2008-now, ZonD80, Germany, TorrentsBook.com
* @link http://dev.kinokpk.com
*/

require_once ("include/bittorrent.php");

INIT();

loggedinorreturn();

require_once "chat/src/phpfreechat.class.php";

include "include/secrets.php";
$params['container_type'] = 'mysql';
$params["serverid"] = md5(__FILE__);
$params['container_cfg_mysql_host'] = $db['host'];
$params['container_cfg_mysql_port'] = 3306;
$params['container_cfg_mysql_database'] = $db['db'];
$params['container_cfg_mysql_username'] = $db['user'];
$params['container_cfg_mysql_password'] = $db['pass'];
unset($db);

$params['theme'] = 'phpbb2';
$params['display_pfc_logo'] = false;
$params['admins'] = array();
$params['nick'] = $CURUSER['username'];
$params['frozen_nick'] = true;

$params['title'] = $REL_LANG->_('Chat');
$params['channels'] = array('Проблемы','Разговор с администрацией','Флудилка','Общее');
/*if ($CURUSER['gender']==1) {
	$params['nickmeta'] = array('gender'=>'m');
} else {*/
$lang = getlang();

if ($lang=='ru') {
	$params['language'] = 'ru_RU';
}
elseif ($lang='ua') {
	$params['language'] = 'uk_UA';
}
if (get_privilege('is_chat_admin',false)) {
	$params['isadmin'] = true;
}

$chat = new phpFreeChat($params);

$chat->printChat();
?>