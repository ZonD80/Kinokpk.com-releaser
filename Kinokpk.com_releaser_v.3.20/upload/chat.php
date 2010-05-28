<?php
require_once("include/bittorrent.php");
dbconn();
loggedinorreturn();
error_reporting(E_ALL & ~E_NOTICE & ~E_DEPRECATED);
  $params["serverid"] = md5(__FILE__); // used to identify the chat
  $lang_trans = array ('ru'=>'ru_RU','ua'=>'uk_UA','en'=>'en_US');
  $gender_trans = array(1=>$REL_LANG->say_by_key('signup_male'),2=>$REL_LANG->say_by_key('signup_female'));
  
  
  if ($lang_trans[$CURUSER['language']]) $params["language"] = $lang_trans[$CURUSER['language']];
  $params["nick"] = iconv("windows-1251","utf-8",$CURUSER['username']);
  $params["max_nick_len"] = 40;
  $params["frozen_nick"] = true;

  if (get_user_class()>=UC_ADMINISTRATOR) $params["isadmin"] = true;
  if ($gender_trans[$CURUSER['gender']]) $params["nickmeta"][iconv("windows-1251","utf-8",$REL_LANG->say_by_key('signup_gender'))] = iconv("windows-1251","utf-8",$gender_trans[$CURUSER['gender']]);
  $params["nickmeta"][iconv("windows-1251","utf-8","Класс")] = iconv("windows-1251","utf-8",get_user_class_name(get_user_class()));

  $params["title"] = iconv("windows-1251","utf-8","TorrentsBook.com Чат: Бета");
  $params["channels"] = array(iconv("windows-1251","utf-8","Поддержка"),"Test Room",iconv("windows-1251","utf-8","Курилка"));
  $params['display_pfc_logo'] = false;
  /* $params["container_type"] = 'Mysql';
  $params["container_cfg_mysql_database"] = "snt_chat";
  $params["container_cfg_mysql_username"] = "snt_c";
  $params["container_cfg_mysql_password"] = "SSkidl@#*fvd";
  $params["container_cfg_mysql_table"] = "phpfreechat";*/
  $params["time_offset"] = $CURUSER['timezone']*3600;
  /*$params["shownotice"] = 7;
  $params["debug"] = true;*/
  require_once "chat/src/phpfreechat.class.php"; // adjust to your own path
  // mysql_close();
  $chat = new phpFreeChat($params);

?>
 <!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
         "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
  <html>
    <head>
      <meta http-equiv="content-type" content="text/html; charset=utf-8" />
      <title>TorrentsBook.com chat:beta</title>
    </head>
    <body>
<?php $chat->printChat(); ?>
    </body>
  </html>